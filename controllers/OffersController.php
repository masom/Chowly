<?php
namespace chowly\controllers;

use chowly\models\Offer;
use chowly\models\Venue;
use chowly\models\Cart;
use li3_flash_message\extensions\storage\FlashMessage;

class OffersController extends \lithium\action\Controller{
	
	public function index(){
		$offers = Offer::current();
		$venues = array();
		$venues_id = array();
		foreach($offers as $offer){
			$venues_id[(string)$offer->venue_id] = (string)$offer->venue_id;
		}
		
		if(!empty($venues_id)){
			$conditions = array('_id' => array_keys($venues_id));
			Venue::meta('title', 'logo');
			$venues = Venue::find('list', compact('conditions','fields'));
		}
		return compact('offers', 'venues');
	}
	public function view(){
		if(!$this->request->id){
			FlashMessage::set("Missing data.");
			$this->redirect(array("Offers::index"));
		}
		$offer = Offer::first($this->request->id);
		if(!$offer){
			FlashMessage::set("The specified offer does not exists.");
			$this->redirect(array("Offers::index"));
		}
		$conditions = array('_id' => $offer->venue_id);
		$venue = Venue::first(compact('conditions'));
		return compact('offer','venue');
	}
	public function buy(){
		if(!$this->request->id){
			FlashMessage::set("Missing data.");
			$this->redirect(array("Offers::index"));
		}
		$reserved = Offer::reserve($this->request->id, 'test');
		if($reserved['successfull']){
			Cart::add($this->request->id);
			$this->redirect(array('Offers::confirm'));
		}else{
			$this->redirect($this->request->referer());
		}
	}
	public function confirm(){
		$cart = Cart::get();
		if(empty($cart)){
			FlashMessage::set("Empty Cart!");
			$this->redirect($this->request->referer());
		}
		$conditions = array(
			'_id' => array_keys($cart)
		);
		$offers = Offer::all(compact('conditions'));
		
		return compact('offers');
	}
	
	
	
	/**
	 * ADMIN FUNCTIONS BELLOW
	 */
	
	
	public function preview(){
		$offer = Offer::first($this->request->id);
		if(!$offer){
			$this->redirect(array('Offers::index'));
		}
		$conditions = array('_id'=> $offer->venue_id);
		$venue = Venue::first(compact('conditions'));
		return compact('venue','offer');
	}
	public function publish(){
		$offer = Offer::first($this->request->id);
		if(!$offer){
			FlashMessage::set("Offer not found.");
			$this->redirect(array('Offers::index'));
		}
		$retval = $offer->publish();
		if($retval['success']){
			FlashMessage::set("Offer published.");
		}else{
			FlashMessage::set("The offer could not be published.");
		}
		$this->redirect(array('Offers::view','id'=>$offer->_id));
	}
	public function unpublish(){
		$offer = Offer::first($this->request->id);
		if(!$offer){
			FlashMessage::set("Offer not found");
			$this->redirect(array('Offers::index'));
		}
		if($offer->unpublish()){
			FlashMessage::set("Offer unpublished.");
		}else{
			FlashMessage::set("The offer could not be unpublished.");
		}
		$this->redirect(array('Offers::view','id'=>$offer->_id));
	}
	public function add(){
		$offer = Offer::create();
		if (($this->request->data)){
			$offer->set($this->request->data);

			$success = $offer->save();
			if($success){
				FlashMessage::set("Offer created.");
				$this->redirect(array('Offers::preview', 'id' => $offer->_id));
			}
		}
		
		$conditions = array();
		if($this->request->id){
			//in this case, request->id is the venue id.
			$conditions = array('_id' => $this->request->id);
		}elseif ($this->request->data['venue_id']){
			$conditions = array('_id' => $this->request->data['venue_id']);
		}
		if(!$conditions){
			$this->redirect(array('Offers::index'));
		}
		$venue = Venue::first(compact('conditions'));
		
		if(!$venue){
			FlashMessage::set("Venue not found.");
			$this->redirect($this->request->referer());
		}
		$this->_render['template'] = 'edit';
		return compact('venue', 'offer');
	}
	public function edit(){
		$conditions = array(
			'_id' => $this->request->id
		);
		$offer = Offer::first(compact('conditions'));
		if (($this->request->data)){
			$success = $offer->save($this->request->data);
			if($success){
				FlashMessage::set("Offer modified.");
				$this->redirect(array('Offers::view', 'id' => $venue->_id));
			}
		}
		
		$publishOptions = $offer->states();
		return compact('offer','publishOptions');
	}
}