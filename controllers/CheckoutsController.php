<?php
namespace chowly\controllers;

use lithium\data\source\Database;

use li3_flash_message\extensions\storage\FlashMessage;
use chowly\models\Cart;
use chowly\models\Inventories;
use chowly\models\Venues;
use chowly\models\Offers;
use chowly\models\Purchases;

use chowly\extensions\data\InventoryException;
use \lithium\net\http\Router;
use \lithium\template\View;
use \lithium\analysis\Logger;
use \Swift_MailTransport;
use \Swift_Mailer;
use \Swift_Message;
use \Swift_Attachment;

class CheckoutsController extends \chowly\extensions\action\Controller{
		
	protected function _init(){
		parent::_init();
		/** TODO: lets wait before requiring this
		if(!$this->request->is('ssl')){
			return $this->redirect(Router::match(
				$this->request->params,
				$this->request,
				array('absolute' => true, 'scheme'=>'https://')
				)
			);
		}*/
	}
	public function cancel(){
		if(Cart::inTransaction()){
			FlashMessage::set("There is currently a transaction in progress. Cannot modify the cart.");
		}else{
			Cart::unfreeze();
		}
		return $this->redirect(array('Offers::index'));
	}
	public function confirm(){
		if(Cart::isEmpty()){
			FlashMessage::set("Your cart is currently empty.");
			return $this->redirect("Offers::index");
		}
		
		$conditions = array(
			'_id' => array_keys(Cart::get())
		);
		$offers = Offers::all(compact('conditions'));
		
		$cart = Cart::get();
		return compact('offers', 'cart');
	}
	
	public function checkout(){
		$provinces = Purchases::getProvinces();
		
		Cart::freeze();
		if(Cart::isEmpty()){
			Cart::unfreeze();
			FlashMessage::set("Your cart is currently empty.");
			return $this->redirect("Offers::index");
		}

		$cart = Cart::get();
		
		//Secure inventory so it does not expire while in checkout.
		foreach($cart as $offer_id => $attr){
			try{
				Inventories::secure($attr['inventory_id']);
			}catch(InventoryException $e){
				Logger::write('warning', "Could not secure {$attr['inventory_id']} Reason: {$e->getMessage()}");
				//TODO: Do we fail at that point or still sell the item?
			}
		}
		
		if(Cart::inTransaction()){
			FlashMessage::set("There is a transaction in progress on your cart. Please try again.");
			return $this->redirect("Offers::index");
		}
		
		//TODO: Credit Card data processing...
		if($this->request->data){
			
			Cart::startTransaction();
			
			$purchase = Purchases::create();
			$purchase->set($this->request->data);
			$purchase->status = 'new';
			
			if(!$purchase->validates()){
				unset($purchase->cc_number, $purchase->cc_sc);
				
				Cart::endTransaction();
				
				return compact('purchase', 'provinces');
			}
			
			
			$cart = Cart::get();
			
			$conditions = array('_id'=> array_keys($cart));
			$offers = Offers::all(compact('conditions'));
			
			//TODO: Log transaction for history/accounting
			try{
				$purchase->process($offers);
			}catch(\Exception $e){
				unset($purchase->cc_number, $purchase->cc_sc);
				Logger::write('notice', "Could not process purchase due to: ". $e->getMessage());
				FlashMessage::set("Some processing errors occured.");
				
				Cart::endTransaction();
				
				return compact('purchase', 'provinces');
			}
			unset($purchase->cc_number, $purchase->cc_sc);
			
			
			if(!$purchase->isCompleted()){
				Logger::write('notice', "Transaction not completed due to : {$purchase->error}");
				FlashMessage::set("The purchase could not be completed.");
				
				Cart::endTransaction();
				
				return compact('purchase', 'provinces');
			}
			
			Logger::write('info', "Transaction Completed. E[{$purchase->email}] I[{$purchase->_id}] P[{$purchase->price}]");
			foreach($cart as $offer_id => $attr){
				try{
					Inventories::purchase($purchase->_id, $attr['inventory_id']);
				}catch(InventoryException $e){
					Logger::write('warning', "Could not mark inventory as purchased. Purchase: {$purchase->_id}. Item: {$attr['inventory_id']} Reason: ". $e->getMessage());
				}
			}
			
			$venuesList = array();
			foreach($offers as $offer){
				$venuesList[] = $offer->venue_id;
			}
			
			$conditions = array('_id' => $venuesList);
			$venues = Venues::find('all', compact('conditions'));

			$path = null;
			try{
				$path = $this->_writePdf($purchase->_id, $this->_getPdf($purchase, $offers, $venues));
			} catch (\Exception $e){
				Logger::write('error', "Could not generate pdf due to: ". $e->getMessage());
			}
			$to = $purchase->email;
			
			$transport = Swift_MailTransport::newInstance();
			$mailer = Swift_Mailer::newInstance($transport);
			$message = Swift_Message::newInstance();
			$message->setSubject("Chowly Purchase Confirmation");
			$message->setFrom(array('purchases@chowly.com' => 'Chowly'));
			$message->setTo($to);
			
			if($path){
				$message->setBody($this->_getEmail(compact('purchase'), 'purchase'));
				$message->attach(Swift_Attachment::fromPath($path));
			}else{
				$message->setBody($this->_getEmail(compact('purchase'), 'generation_failure'));
			}
			
			if(!$mailer->send($message)){
				Logger::write('error', "Could not send email for purchase {$purchase->_id}");
			}
			
			Cart::endTransaction();
			Cart::unfreeze();
			Cart::clear();
			
			$this->_render['template'] = 'success';
			return compact('purchase');
		}
		return compact('provinces');
	}
	private function _getPdf($purchase, $offers, $venues){
		$view  = new View(
		array(

		    'paths' => array(
		        'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
		        'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
		    )
		));	
		
		return $view->render(
		    'all',
		    compact('purchase','offers','venues'),
		    array(
		        'controller' => 'purchases',
		        'template'=>'purchase',
		        'type' => 'pdf',
		        'layout' =>'purchase'
		    )
		);
	}
	private function _writePdf($purchaseId, &$pdf){
		$path = LITHIUM_APP_PATH.'/resources/purchases';
		$filepath = $path.DIRECTORY_SEPARATOR. $purchaseId.'.pdf';
		if(file_exists($filepath)){
			return true;
		}
		if(!is_writable($path)){
			throw new \Exception("File path is not writable.");
		}
		if(file_put_contents($filepath, $pdf,LOCK_EX)){
			return $filepath;
		}else{
			throw new \Exception("Could not write to file");
		}
	}
}
?>