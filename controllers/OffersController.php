<?php
namespace chowly\controllers;

use chowly\models\Offer;

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
		$offer->venue_id = new \MongoId("test");
		$offer->name = "40$ off at Lithium";
		$offer->cost = 2000;
		$offer->starts = new \MongoDate(time() - 15 * 60);
		$offer->ends = new \MongoDate(time() + 15 * 60);
		$offer->availability = 100;
		
		if($offer->save()){
			$this->redirect(array('Offers::index'));
		}
		$this->redirect(array('Offers::index'));
	}
}
?>