<?php
namespace chowly\controllers;

use chowly\models\Venue;
use chowly\models\Offer;
use li3_flash_message\extensions\storage\FlashMessage;

class VenuesController extends \chowly\extensions\action\Controller{
	public function index(){
		$conditions = array('state' => 'published');
		$venues = Venue::all(compact('conditions'));
		return compact('venues');
	}
	public function view(){
		if(!$this->request->id){
			FlashMessage::set("Missing data.");
			$this->redirect(array('Venues::index'));
		}
		$conditions = array('_id'=>$this->request->id, 'state'=>'published');
		$venue = Venue::first(compact('conditions'));
		
		$conditions = array('venue_id' => $this->request->id, 'state'=>'published', 'availability' => array('$gt'=> 0));
		$offers = Offer::all(compact('conditions'));
		return compact('venue');
	}
	public function add() {
		$venue = Venue::create();
		if (($this->request->data)){
			$success = $venue->save($this->request->data);
			if($success){
				FlashMessage::set("Venue added.");
				$this->redirect(array('Venues::view', 'id' => $venue->_id));
			}
		}
		$this->_render['template'] = 'edit';
		
		$publishedOptions = $venue->states();
		
		return compact('venue','publishedOptions');
	}

	public function edit() {
		$venue = Venue::find($this->request->id);

		if (!$venue) {
			FlashMessage::set("Venue not found.");
			$this->redirect('Venues::index');
		}
		if (($this->request->data) && $venue->save($this->request->data)) {
			FlashMessage::set("Venue modified.");
			$this->redirect(array('Venues::view', 'id' => $venue->_id));
		}
		
		$publishedOptions = $venue->states();
		return compact('venue','publishedOptions');
	}
}
?>