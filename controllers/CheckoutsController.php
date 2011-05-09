<?php
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\controllers;

use li3_flash_message\extensions\storage\FlashMessage;
use chowly\models\Inventories;
use chowly\models\Venues;
use chowly\models\Offers;
use chowly\models\Purchases;
use chowly\extensions\Utils;
use chowly\extensions\data\InventoryException;
use lithium\net\http\Router;
use lithium\analysis\Logger;
use Swift_MailTransport;
use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;

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

	public function confirm(){
		if ($this->Cart->isEmpty()){
			FlashMessage::set("Your cart is currently empty.");
			return $this->redirect("Offers::index");
		}

		$cart_items = $this->Cart->items();
		$conditions = array('_id' => array());

		foreach ($cart_items as $item){
			$conditions['_id'][] = $item->_id;
		}

		$offers = Offers::all(compact('conditions'));

		return compact('offers','cart_items');
	}

	public function checkout(){
		$provinces = Purchases::getProvinces();

		if ($this->Cart->isEmpty()){
			FlashMessage::set("Your cart is currently empty.");
			return $this->redirect("Offers::index");
		}

		if (!$this->Cart->startTransaction()){
			FlashMessage::set("There is a transaction in progress on your cart. Please try again.");
			return $this->redirect("Offers::index");
		}

		//Secure inventory so it does not expire while in checkout.
		$this->_secureInventory();

		$purchase = Purchases::create();

		if (!$this->request->data){
			$this->Cart->endTransaction();
			return compact('provinces', 'purchase');
		}

		$purchase->set($this->request->data);
		$purchase->status = 'new';

		if (!$purchase->validates()){
			unset($purchase->cc_number, $purchase->cc_sc);
			$this->Cart->endTransaction();
			return compact('purchase', 'provinces');
		}

		$offers = $this->_getCartOffers();
		//TODO: Credit Card data processing...
		$sucessfull = $this->_processPurchase($purchase, $offers);

		unset($purchase->cc_number, $purchase->cc_sc);

		if(!$sucessfull){
			$this->Cart->endTransaction();
			return compact('purchase', 'provinces');
		}

		$message = "P[{$purchase->_id}] Transaction completed.";
		Logger::write('info', $message, array('name'=>'transactions'));

		$this->_markItemsPurchased($purchase);

		$venues = $this->_getVenues($offers);

		$pdfPath = null;
		try{
			$pdfPath = Utils::getPdf($purchase, $offers, $venues);
		} catch (\Exception $e){
			Logger::write('error',
				"P[{$purchase->_id}] Could not generate pdf due to: " . $e->getMessage(),
				array('name'=>'transactions')
			);
		}

		$emailSent = $this->_sendEmail($purchase, $pdfPath);

		$this->Cart->endTransaction();
		$this->Cart->clearItems();

		$this->_render['template'] = 'success';
		return compact('purchase', 'emailSent', 'pdfPath');
	}

	/**
	 * Purchase processing handling
	 * @param var $purchase The purchase object
	 * @param var $offers The offers being purchased
	 * @return boolean
	 */
	private function _processPurchase($purchase, $offers){

		$processed = false;

		try{
			$processed = $purchase->process($offers);
		}catch(\Exception $e){
			Logger::write('notice',
				"C[{$cart->_id}] Could not process purchase due to: " . $e->getMessage(),
				array('name'=>'transactions')
			);
			FlashMessage::set("Some processing errors occured.");
			return false;
		}
		if (!$purchase->isCompleted()){
			Logger::write('notice',
				"C[{$cart->_id}] Transaction failed: {$purchase->error}.",
				array('name'=>'transactions')
			);
			FlashMessage::set("The purchase could not be completed.");
			return false;
		}

		return true;
	}
	/**
	 * Get the venues associated with the offers.
	 * @param DocumentSet $offers The offers collection to get venues for.
	 * @return DocumentSet A collection of venues.
	 */
	private function _getVenues($offers){
			$conditions = array('_id' => array());
			foreach ($offers as $offer){
				$conditions['_id'][] = $offer->venue_id;
			}
			return Venues::find('all', compact('conditions'));
	}

	/**
	 * Marks cart items as being purchased.
	 * @param var $purchase The purchase entity
	 */
	private function _markItemsPurchased($purchase){
		$items = $this->Cart->items();
		foreach ($items as $item){
			try{
				Inventories::purchase($purchase->_id, $item->inventory_id);
			}catch(InventoryException $e){
				$message = "P[{$purchase->_id}]";
				$message .= " Could not mark inventory [{$item->inventory_id}] as purchased.";
				$message .= " Reason: " . $e->getMessage();
				Logger::write('error', $message, array('name'=>'transactions'));
			}
		}
	}

	/**
	 * Fetches the offers contained in the cart.
	 * @return \lithium\data\collection\DocumentSet
	 */
	private function _getCartOffers(){
		$items = $this->Cart->items();
		$conditions = array('_id' => array());
		foreach ($items as $item){
			$conditions['_id'][] = $item->_id;
		}
		$fields = array('_id', 'name', 'limitations', 'description',
			'cost', 'created', 'expires', 'venue_id'
		);
		return Offers::all(compact('conditions','fields'));
	}

	/**
	 * Secures the inventory to prevet expiration during processing.
	 */
	private function _secureInventory(){
		$items = $this->Cart->items();
		foreach ($items as $item){
			try{
				Inventories::secure($item->inventory_id);
			}catch(InventoryException $e){
				$message = "Could not secure {$item->inventory_id} Reason: " . $e->getMessage();
				Logger::write('warning', $message, array('name'=>'transactions'));
				//TODO: Do we fail at that point or still sell the item?
			}
		}
	}

	/**
	 * Wraps the email sending feature for new purchases.
	 * @param var $purchase The purchase object
	 * @param var $pdfPath The path to the pdf to be attached.
	 * @return boolean
	 */
	private function _sendEmail($purchase, $pdfPath){
		$to = $purchase->email;

		$transport = Swift_MailTransport::newInstance();
		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance();
		$message->setSubject("Chowly Purchase Confirmation");
		$message->setFrom(array('purchases@chowly.com' => 'Chowly'));
		$message->setTo($to);

		if ($pdfPath){
			$message->setBody(parent::_getEmail(compact('purchase'), 'purchase', 'purchases'));
			$message->attach(Swift_Attachment::fromPath($pdfPath));
		}else{
			$message->setBody(parent::_getEmail(compact('purchase'), 'generation_failure'));
		}

		if (!$mailer->send($message)){
			Logger::write('error', "P[{$purchase->_id}] Could not send email", array('name'=>'transactions'));
			return false;
		}

		return true;
	}
}

?>