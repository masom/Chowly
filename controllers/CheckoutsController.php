<?php
namespace chowly\controllers;

use li3_flash_message\extensions\storage\FlashMessage;
use chowly\models\Cart;
use chowly\models\Inventory;
use chowly\models\Venue;
use chowly\models\Offer;
use chowly\extensions\data\InventoryException;

class CheckoutsController extends \lithium\action\Controller{
	public function confirm(){
		//some cart cleanup...
		$cart = Cart::get();
		if(empty($cart)){
			FlashMessage::set("Empty Cart!");
			$this->redirect($this->request->referer());
		}
		
		$conditions = array(
			'_id' => array_keys($cart)
		);
		$offers = Offer::all(compact('conditions'));
		return compact('offers', 'cart');
	}
	public function checkout(){
		
		Cart::freeze();
		$cart = Cart::get();		
		if(empty($cart)){
			FlashMessage::set("Empty Cart!");
			$this->redirect($this->request->referer());
		}

		//Secure inventory so it does not expire while in checkout.
		try{
			foreach($cart as $offer_id => $attr){
				Inventory::secure($attr['inventory_id']);
			}
		}catch(InventoryException $e){
			//TODO:Log failure
			//TODO: Do we fail at that point or still sell the item?
		}
		
		//TODO: Credit Card data processing...
		if($this->request->data){
			Cart::lock();
			//TODO: Send email
			//TODO: Log transaction for history/accounting
			//TODO: HERE BE CC Processing
			$transaction = array('id'=>'234DCDAA', 'time' => time(), 'auth'=>'DeC22A335z', 'status'=>'complete');
			
			if($transaction['status'] != 'complete'){
				FlashMessage::set("Some processing errors occured.");
				return compact('transaction');
			}
			
			foreach($cart as $offer_id => $attr){
				//TODO: Add logs of a failure...
				// A few concurency errors is acceptable.
				Inventory::purchase($attr['inventory_id']);
			}
			Cart::unlock();
			Cart::unfreeze();
			Cart::clear();
			$this->redirect("Checkouts::success");
		}
		
	}
	public function success(){}
}
?>