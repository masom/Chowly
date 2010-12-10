<?php
namespace chowly\controllers;

use chowly\models\Offer;
use chowly\models\Inventory;

class OffersController extends \lithium\action\Controller{

	public function index(){
		$offers = Offer::all();
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
		$item = Inventory::reserve('testaccount', $this->request->id);
		if($item['ok']){
			//TODO: Payments logic
			die("OK");
		}else{
			//TODO: Error message explaining the item could be out of stock/non-existent
			nl2br(print_r($item));
			die;
		}
		
	}
	public function add(){
		$offer = Offer::create();
		if($offer->save(array('venue_id' => 'bleh', 'offer'=>'40$ off at Lithium', 'starts' => new \MongoDate(), 'ends'=> new \MongoDate(time() + 60 * 60)))){
			$this->redirect(array('Offers::index', 'id' => $offer->_id));
		}
		$this->redirect(array('Offers::index'));
	}
}
?>