<?php
namespace chowly\controllers;

use chowly\models\Offers;
use chowly\models\Venues;
use chowly\models\Cart;

use chowly\extensions\data\OfferException;
use chowly\extensions\data\InventoryException;

use li3_flash_message\extensions\storage\FlashMessage;

class OffersController extends \chowly\extensions\action\Controller{
	
	public function index(){	
		$offers = Offers::current();

		$venues = array();
		$venues_id = array();
		foreach($offers as $offer){
			$venues_id[(string)$offer->venue_id] = (string)$offer->venue_id;
		}
		
		if(!empty($venues_id)){
			$conditions = array('_id' => array_keys($venues_id));
			Venues::meta('title', 'logo');
			$venues = Venues::find('list', compact('conditions','fields'));
		}
		return compact('offers', 'venues');
	}
	public function view(){
		if(!$this->request->id && !$this->request->slug){
			FlashMessage::set("Missing data.");
			return $this->redirect(array("Offers::index"));
		}
		
		$conditions = array('state'=>'published');
		if($this->request->id){
			$conditions['_id'] = $this->request->id;
		}else{
			$conditions['slug'] = $this->request->slug;
		}

		$offer = Offers::first(compact('conditions'));
		if(!$offer){
			FlashMessage::set("The specified offer does not exists.");
			return $this->redirect(array("Offers::index"));
		}
		$conditions = array('_id' => $offer->venue_id);
		$venue = Venues::first(compact('conditions'));
		
		return compact('offer','venue');
	}
	public function buy(){
		if(!$this->request->id){
			FlashMessage::set("Missing data.");
			return $this->redirect( array("Offers::index") );
		}
		
		if($this->Cart->containItem($this->request->id)){
			return $this->redirect( array('Checkouts::confirm') );
		}

		if($this->Cart->isReadOnly()){
			FlashMessage::set("There is currently a transaction in progress on your cart.");
			return $this->redirect( array('Checkouts::confirm') );
		}
		try{
			$reserved = Offers::reserve($this->request->id, $this->Cart->_id);
		}catch(InventoryException $e){
			FlashMessage::set("Sorry, The item could not be added to your cart");
			return $this->redirect($this->request->referer());
		}catch(OfferException $e){
			FlashMessage::set("Sorry, The item could not be added to your cart for the following reason: {$e->getMessage()}");
			return $this->redirect($this->request->referer());
		}
		$this->Cart->addItem($this->request->id, $reserved);
		return $this->redirect(array('Checkouts::confirm'));
	}

	public function admin_index(){
		$limit = 20;
		$page = $this->request->page ?: 1;
		$order = array('created' => 'DESC');
		
		$total = Offers::count();
		$offers = Offers::all(compact('order','limit','page'));
		
		return compact('offers', 'total', 'page', 'limit');
	}
	public function admin_view(){
		$offer = Offers::first($this->request->id);
		if(!$offer){
			return $this->redirect(array('Offers::index','admin'=>true));
		}
		$conditions = array('_id'=> $offer->venue_id);
		$venue = Venues::first(compact('conditions'));
		
		return compact('venue','offer');
	}
	public function admin_publish(){
		$offer = Offers::first($this->request->id);
		if(!$offer){
			FlashMessage::set("Offer not found.");
			return $this->redirect($this->request->referer());
		}
		if($offer->publish()){
			FlashMessage::set("Offer published.");
		}else{
			FlashMessage::set("The offer could not be published.");
		}
		return $this->redirect($this->request->referer());
	}
	public function admin_unpublish(){
		$offer = Offers::first($this->request->id);
		if(!$offer){
			FlashMessage::set("Offer not found");
			return $this->redirect($this->request->referer());
		}
		if($offer->unpublish()){
			FlashMessage::set("Offer unpublished.");
		}else{
			FlashMessage::set("The offer could not be unpublished.");
		}
		return $this->redirect($this->request->referer());
	}
	
	public function admin_rebuild_inventory(){
		Offers::rebuildInventory();
		return $this->redirect($this->request->referer());
	}
	
	public function admin_add(){
		$offer = Offers::create();
		
		//Get Venue
		$conditions = array();
		if($this->request->id){
			//in this case, request->id is the venue id.
			$conditions = array('_id' => $this->request->id);
		}elseif ($this->request->data['venue_id']){
			$conditions = array('_id' => $this->request->data['venue_id']);
		}
		if(!$conditions){
			return $this->redirect(array('Offers::index','admin'=>true));
		}
		
		$venue = Venues::first(compact('conditions'));
		
		if(!$venue){
			FlashMessage::set("Venue not found.");
			return $this->redirect($this->request->referer());
		}
				
		if ($this->request->data){
			$offer->set($this->request->data);
			
			$offer->slug = $offer->slug(array('prepend'=>$venue->name));
			
			$success = false;
			try{
				$success = $offer->createWithInventory();
			}catch(\Exception $e){
				FlashMessage::set($e->getMessage());
				return $this->redirect(array('Offers::index','admin'=>true));
			}
			
			if($success){
				FlashMessage::set("Offer created.");
				return $this->redirect(array('Offers::view', 'id' => $offer->_id, 'admin'=>true));
			}
		}
		
		$this->_render['template'] = 'admin_edit';
		return compact('venue', 'offer');
	}
	public function admin_edit(){
		$conditions = array(
			'_id' => $this->request->id
		);
		
		$offer = Offers::first(compact('conditions'));
		if (($this->request->data)){
			$success = $offer->save($this->request->data);
			if($success){
				FlashMessage::set("Offer modified.");
				return $this->redirect(array('Offers::view', 'id' => $venue->_id));
			}
		}
		
		$publishOptions = $offer->states();
		return compact('offer','publishOptions');
	}
}
?>