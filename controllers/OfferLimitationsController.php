<?php
/**
 * Chowly Pick. Eat. Save!
 *
 * @copyright     Copyright 2011, Martin Samson <pyrolian@gmail.com>
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
namespace chowly\controllers;

use chowly\models\OfferLimitations;
use li3_flash_message\extensions\storage\FlashMessage;

class OfferLimitationsController extends \chowly\extensions\action\Controller{
	public function admin_index(){
		$limit = 20;
		$page = $this->request->page ?: 1;
		$order = array('created' => 'DESC');

		$total = OfferLimitations::count();
		$limitations = OfferLimitations::all(compact('order','limit','page'));

		return compact('limitations', 'total', 'page', 'limit');
	}

	public function admin_add(){
		$limitation = OfferLimitations::create();

		if ($this->request->data){
			if ($limitation->save($this->request->data)){
				FlashMessage::set("Limitation created.");
				return $this->redirect(array('action'=>'index', 'admin'=>true));
			}
		}

		$this->_render['template'] = 'admin_edit';
		return compact('limitation');
	}

	public function admin_edit(){
		$conditions = array(
			'_id' => $this->request->id
		);

		$limitation = OfferLimitations::first(compact('conditions'));
		if ($this->request->data){
			if ($limitation->save($this->request->data)){
				FlashMessage::set("Limitation modified.");
				return $this->redirect(array('OfferLimitations::index','admin'=>true));
			}
		}
		return compact('limitation');
	}
}

?>