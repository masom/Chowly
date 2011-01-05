<?php

use li3_flash_message\extensions\storage\FlashMessage;
use chowly\models\Cart;
use chowly\models\Inventory;
use chowly\models\Venue;
use chowly\models\Offer;

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
		$cart = Cart::get();
		if(empty($cart)){
			FlashMessage::set("Empty Cart!");
			$this->redirect($this->referer());
		}
		
		//TODO: Credit Card data processing...
		if($this->request->data){
			//TODO: HERE BE CC Processing
			$transaction = array('id'=>'234DCDAA', 'time' => time(), 'auth'=>'DeC22A335z', 'status'=>'complete');
			
			if($transaction['status'] == 'complete'){
				foreach($cart as $offer_id => $attr){
					
				}
			}
		}
	}
}
?>