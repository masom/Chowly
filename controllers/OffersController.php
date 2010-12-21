<?php
namespace chowly\controllers;

use chowly\models\Offer;
use chowly\models\Venue;

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
			$this->redirect(array("Offers::index", 'args'=>array('reason'=>'not-specified')));
		}
		$offer = Offer::first($this->request->id);
		if(!$offer){
			$this->redirect(array("Offers::index", "args"=>array("reason"=>"non-existent")));
		}
		return compact('offer');
	}
	public function buy(){
		if(!$this->request->id){
			$this->redirect(array("Offers::index", 'args'=>array('reason'=>'not-specified')));
		}
		
		//TODO: Actual customer identification... php session id to start?
		$item = Offer::reserve('testaccount', $this->request->id);
		if($item['ok']){
			//TODO: Payments logic
			die("OK");
		}else{
			die(print_r($item));
		}
		
	}
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
			$this->redirect(array('Offers::index'));
		}
		$offer->publish();
		$this->redirect(array('Offers::view','id'=>$offer->_id));
	}
	public function add(){
		$offer = Offer::create();
		if (($this->request->data)){
			$offer->set($this->request->data);

			$success = $offer->save();
			if($success){
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
			$this->redirect(array('Offers::index'));
		}
		$this->_render['template'] = 'edit';
		
		return compact('venue', 'offer');
	}
	public function edit(){
		$offer = Offer::create();
		if (($this->request->data)){
			$success = $offer->save($this->request->data);
			if($success){
				$this->redirect(array('Offer::view', 'id' => $venue->_id));
			}
		}
		$publishOptions = $offer->states();
		return compact('venue','publishOptions');
	}
}
?>