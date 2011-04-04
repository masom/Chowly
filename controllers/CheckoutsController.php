<?php
namespace chowly\controllers;

use lithium\data\source\Database;

use li3_flash_message\extensions\storage\FlashMessage;
use chowly\models\Carts;
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
		
		if($this->Cart->isEmpty()){
			FlashMessage::set("Your cart is currently empty.");
			return $this->redirect("Offers::index");
		}
		
		$cart_items = $this->Cart->items;
		$cart_items_id = array();
		foreach($cart_items as $item){
			$cart_items_id[] = $item->_id;
		}

		$conditions = array(
			'_id' => $cart_items_id
		);
		$offers = Offers::all(compact('conditions'));
		
		
		return compact('offers','cart_items');
	}
	
	public function checkout(){
		$provinces = Purchases::getProvinces();
		
		if($this->Cart->isEmpty()){
			FlashMessage::set("Your cart is currently empty.");
			return $this->redirect("Offers::index");
		}
		
		//Secure inventory so it does not expire while in checkout.
		foreach($this->Cart->items as $item){
			try{
				Inventories::secure($item->inventory_id);
			}catch(InventoryException $e){
				Logger::write('warning', "Could not secure {$item->inventory_id} Reason: {$e->getMessage()}");
				//TODO: Do we fail at that point or still sell the item?
			}
		}
		
		if(!$this->Cart->startTransaction()){
			FlashMessage::set("There is a transaction in progress on your cart. Please try again.");
			return $this->redirect("Offers::index");
		}
		
		$purchase = Purchases::create();
		
		//TODO: Credit Card data processing...
		if($this->request->data){
			
			
			$purchase->set($this->request->data);
			$purchase->status = 'new';
			
			if(!$purchase->validates()){
				unset($purchase->cc_number, $purchase->cc_sc);
				$this->Cart->endTransaction();
				return compact('purchase', 'provinces');
			}
			
			$cart_items_id = array();
			foreach($this->Cart->items as $item){
				$cart_items_id[] = $item->_id;
			}
			$conditions = array('_id'=> $cart_items_id);
			$offers = Offers::all(compact('conditions'));
			
			//TODO: Log transaction for history/accounting
			try{
				$purchase->process($offers);
			}catch(\Exception $e){
				unset($purchase->cc_number, $purchase->cc_sc);
				Logger::write('notice', "Could not process purchase due to: ". $e->getMessage());
				FlashMessage::set("Some processing errors occured.");
				
				$this->Cart->endTransaction();
				return compact('purchase', 'provinces');
			}
			unset($purchase->cc_number, $purchase->cc_sc);
			
			if(!$purchase->isCompleted()){
				Logger::write('notice', "Transaction not completed due to : {$purchase->error}");
				FlashMessage::set("The purchase could not be completed.");
				
				$this->Cart->endTransaction();
				
				return compact('purchase', 'provinces');
			}
			
			Logger::write('info', "TC E[{$purchase->email}] I[{$purchase->_id}] P[{$purchase->price}]");
			foreach($this->Cart->items as $item){
				try{
					Inventories::purchase($purchase->_id, $item->inventory_id);
				}catch(InventoryException $e){
					Logger::write('warning', "Could not mark inventory as purchased. Purchase: {$purchase->_id}. Item: {$item->inventory_id} Reason: ". $e->getMessage());
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
				$message->setBody($this->_getEmail(compact('purchase'), 'purchase', 'purchases'));
				$message->attach(Swift_Attachment::fromPath($path));
			}else{
				$message->setBody($this->_getEmail(compact('purchase'), 'generation_failure'));
			}
			
			if(!$mailer->send($message)){
				Logger::write('error', "Could not send email for purchase {$purchase->_id}");
			}
			
			
			$this->Cart->endTransaction();
			$this->Cart->clearItems();
			
			$this->_render['template'] = 'success';
			return compact('purchase');
		}
		
		$this->Cart->endTransaction();
		return compact('provinces', 'purchase');
	}
	private function _getPdf($purchase, $offers, $venues){
		$view  = new View(
		array(

		    'paths' => array(
				'element' => '{:library}/views/elements/{:template}.{:type}.php',
		        'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
		        'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
		    )
		));	
		
		return $view->render(
		    'all',
		    compact('purchase','offers','venues'),
		    array(
		        'controller' => 'purchases',
		        'template' => 'purchase',
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