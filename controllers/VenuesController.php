<?php
/**
 * Chowly Pick. Eat. Save!
 *
 * @copyright     Copyright 2011, Martin Samson <pyrolian@gmail.com>
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
namespace chowly\controllers;

use chowly\models\Venues;
use chowly\models\Offers;
use li3_flash_message\extensions\storage\FlashMessage;

class VenuesController extends \chowly\extensions\action\Controller{
	public function admin_index(){
		$limit = 20;
		$page = $this->request->page ?: 1;
		$order = array('name' => 'ASC');

		$total = Venues::count();
		$venues = Venues::all(compact('order','limit','page'));

		return compact('venues', 'total', 'page', 'limit');
	}
	public function admin_add(){
		$venue = Venues::create();
		if (($this->request->data)){
			$success = $venue->save($this->request->data);
			if ($success){
				FlashMessage::set("Venue added.");
				return $this->redirect(array('Venues::view', 'id' => $venue->_id));
			}
		}
		$this->_render['template'] = 'admin_edit';

		$publishedOptions = $venue->states();

		return compact('venue','publishedOptions');
	}
	public function admin_edit(){
		$venue = Venues::find($this->request->id);

		if (!$venue) {
			FlashMessage::set("Venue not found.");
			return $this->redirect('Venues::index');
		}
		if (($this->request->data) && $venue->save($this->request->data)) {
			FlashMessage::set("Venue modified.");
			return $this->redirect('Venues::index');
		}

		$publishedOptions = $venue->states();
		return compact('venue','publishedOptions');
	}

	public function admin_view(){

		if (!$this->request->id){
			FlashMessage::set("Missing data.");
			return $this->redirect(array('Venues::index'));
		}

		$conditions = array('_id'=>$this->request->id);

		$venue = Venues::first(compact('conditions'));
		if (!$venue){
			FlashMessage::set("The specified venue does not exists.");
			return $this->redirect($this->request->referer());
		}

		$conditions = array('venue_id' => $venue->_id);
		$offers = Offers::all(compact('conditions'));
		return compact('venue', 'offers');
	}

	public function index(){
		$conditions = array('state' => 'published');
		$venues = Venues::all(compact('conditions'));
		return compact('venues');
	}

	public function view(){
		if (!$this->request->id){
			FlashMessage::set("Missing data.");
			return $this->redirect(array('Venues::index'));
		}
		$conditions = array('_id'=>$this->request->id, 'state'=>'published');
		$venue = Venues::first(compact('conditions'));

		if (!$venue){
			FlashMessage::set("The specified venue does not exists.");
			return $this->redirect($this->request->referer());
		}

		$conditions = array('venue_id' => $this->request->id, 'state'=>'published',
			'availability' => array('$gt'=> 0)
		);
		$offers = Offers::all(compact('conditions'));
		return compact('venue','offers');
	}
}

?>