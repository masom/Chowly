<?php
namespace chowly\controllers;

use li3_flash_message\extensions\storage\FlashMessage;
use chowly\models\Cart;
use chowly\models\Inventory;
use chowly\models\Venue;
use chowly\models\Offer;
use chowly\models\Purchase;

use chowly\extensions\data\InventoryException;
use \lithium\net\http\Router;
use \lithium\template\View;

use \Swift_MailTransport;
use \Swift_Mailer;
use \Swift_Message;
use \Swift_Attachment;

class CheckoutsController extends \chowly\extensions\action\Controller{
		
	protected function _init(){
		parent::_init();
		/** lets wait before requiring this
		if(!$this->request->is('ssl')){
			$this->redirect(Router::match(
				$this->request->params,
				$this->request,
				array('absolute' => true, 'scheme'=>'https://')
				)
			);
		}*/
	}
	public function cancel(){
		Cart::unlock();
		Cart::unfreeze();
		$this->redirect(array('Offers::index'));
	}
	public function confirm(){
		if(Cart::isEmpty()){
			FlashMessage::set("Empty Cart!");
			$this->redirect("Offers::index");
		}
		
		$conditions = array(
			'_id' => array_keys(Cart::get())
		);
		$offers = Offer::all(compact('conditions'));
		
		$cart = Cart::get();
		return compact('offers', 'cart');
	}
	
	public function checkout(){
		$provinces = Purchase::getProvinces();
		
		Cart::freeze();
		if(Cart::isEmpty()){
			Cart::unfreeze();
			FlashMessage::set("Empty Cart!");
			$this->redirect("Offers::index");
			exit();
		}
		
		$cart = Cart::get();
		
		//Secure inventory so it does not expire while in checkout.
		foreach($cart as $offer_id => $attr){
			try{
				Inventory::secure($attr['inventory_id']);
			}catch(InventoryException $e){
				//TODO:Log failure
				//TODO: Do we fail at that point or still sell the item?
			}
		}
		
		//TODO: Credit Card data processing...
		if($this->request->data){
			
			$purchase = Purchase::create();
			$purchase->set($this->request->data);
			$purchase->status = 'new';
			
			if(!$purchase->validates()){
				unset($purchase->cc_number, $purchase->cc_sc);
				return compact('purchase', 'provinces');
			}
			
			Cart::lock();
			$cart = Cart::get();
			
			$conditions = array('_id'=> array_keys($cart));
			$offers = Offer::all(compact('conditions'));
			
			//TODO: Log transaction for history/accounting
			try{
				$purchase->process($offers);
			}catch(\Exception $e){
				unset($purchase->cc_number, $purchase->cc_sc);
				//TODO: Processing error handling
				die(debug($e));
			}
			
			if(!$purchase->isCompleted()){
				FlashMessage::set("Some processing errors occured.");
				return compact('purchase');
			}
			
			foreach($cart as $offer_id => $attr){
				try{
					Inventory::purchase($purchase->_id, $attr['inventory_id']);
				}catch(InventoryException $e){
					//TODO: Add logs of a failure...
					die(debug($e));
				}
			}
			
			try{
				$path = $this->_writePdf($purchase->_id, $this->_getPdf($purchase));
			} catch (\Exception $e){
				//TODO: Handle PDF Generation Errors.
				die(debug($e));
			}
			
			$to = $purchase->email;
			
			$transport = Swift_MailTransport::newInstance();
			$mailer = Swift_Mailer::newInstance($transport);
			$message = Swift_Message::newInstance();
			$message->setSubject("Chowly Purchase {:$purchase->_id} confirmation");
			$message->setFrom(array('purchases@chowly.com' => 'Chowly'));
			$message->setTo($to);
			$message->setBody($this->_getEmail($purchase));
			
			$message->attach(Swift_Attachment::fromPath($path));
			
			if(!$mailer->send($message)){
				//TODO: Email failure...
			}
			
			Cart::unlock();
			Cart::unfreeze();
			Cart::clear();
			$this->_render['template'] = 'success';
			return compact('purchase');
		}
		return compact('provinces');
	}
	private function _getEmail($purchase){
			$view  = new View(array(
			    'loader' => 'File',
			    'renderer' => 'File',
			    'paths' => array(
			        'template' => '{:library}/views/{:controller}/{:template}.{:type}.php'
			    )
			));
			return $view->render(
			    'template',
			    array(compact('purchase')),
			    array(
			        'controller' => 'purchases',
			        'template'=>'purchase',
			        'type' => 'mail',
			        'layout' => false
			    )
			);
	}
	private function _getPdf($purchase){
			$view  = new View(array(
			    'loader' => 'Pdf',
			    'renderer' => 'Pdf',
			    'paths' => array(
			        'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
			        'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
			    )
			));
			return $view->render(
			    'all',
			    array(compact('purchase')),
			    array(
			        'controller' => 'purchases',
			        'template'=>'purchase',
			        'type' => 'pdf',
			        'layout' =>'purchase'
			    )
			);
	}
	private function _writePdf($purchaseId, &$pdf){
		$path = LITHIUM_APP_PATH.'/resources/purchases/'. $purchaseId.'.pdf';
		if(file_exists($path)){
			return true;
		}
		$file = fopen($path, 'w');
		if(!file){
			throw new \Exception("Cannot create pdf");
		}
		$writen = fwrite($file,$pdf);
		fclose($file);
		
		if($writen){
			return $path;
		}else{
			throw new \Exception("File not written");
		}
	}
}
?>