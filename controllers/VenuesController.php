<?php
namespace chowly\controllers;

use chowly\models\Venue;

class VenuesController extends \lithium\action\Controller{
	public function index(){
		$venues = Venue::all();
		return compact('venues');
	}
	public function view(){
		if(!$this->request->id){
			$this->redirect(array('Venues::index'));
		}
		$venue = Venue::first($this->request->id);
		return compact('venue');
	}
	public function add() {
		$venue = Venue::create();
		if (($this->request->data)){
			$success = $venue->save($this->request->data);
			if($success){
				debug($success);
				die;
				$this->redirect(array('Venues::view', 'id' => $venue->_id));
			}
			
		}
		$this->_render['template'] = 'edit';
		return compact('venue');
	}

	public function edit() {
		$venue = Venue::find($this->request->id);

		if (!$venue) {
			$this->redirect('Venues::index');
		}
		if (($this->request->data) && $venue->save($this->request->data)) {
			$this->redirect(array('Venues::view', 'id' => $venue->_id));
		}
		return compact('venue');
	}
}