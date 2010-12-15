<?php
namespace chowly\controllers;

use chowly\models\Offer;
use chowly\models\Venue;

class OffersController extends \lithium\action\Controller{

	public function index(){
		$offers = Offer::current();
		return compact('offers');
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
	
	public function add(){
		$offer = Offer::create();
		if (($this->request->data)){
			$offer->set($this->request->data);

			$success = $offer->save();
			if($success){
				$this->redirect(array('Offer::view', 'id' => $offer->_id));
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
		
		$publishOptions = $offer->states();
		
		return compact('venue', 'offer', 'publishOptions');
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