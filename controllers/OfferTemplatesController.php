<?php
/**
 * Chowly Pick. Eat. Save!
 *
 * @copyright     Copyright 2011, Martin Samson <pyrolian@gmail.com>
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
namespace chowly\controllers;

use chowly\models\OfferTemplates;
use li3_flash_message\extensions\storage\FlashMessage;

class OfferTemplatesController extends \chowly\extensions\action\Controller{
	public function admin_index(){
		$limit = 20;
		$page = $this->request->page ?: 1;
		$order = array('created' => 'DESC');

		$total = OfferTemplates::count();
		$templates = OfferTemplates::all(compact('order','limit','page'));

		return compact('templates', 'total', 'page', 'limit');
	}

	public function admin_view(){
		if (!$this->request->id){
			FlashMessage::set("Missing data.");
			return $this->redirect(array("OfferTemplates::index"));
		}

		$conditions['_id'] = $this->request->id;

		$template = OfferTemplates::first(compact('conditions'));
		if (!$template){
			FlashMessage::set("The specified template does not exists.");
			return $this->redirect(array("OfferTemplates::index"));
		}
		return compact('template');
	}

	public function admin_add(){
		$template = OfferTemplates::create();

		if ($this->request->data){
			$template->set($this->request->data);
			if ($template->save()){
				FlashMessage::set("Template created.");
				return $this->redirect(array('OfferTemplates::view', 'id' => $template->_id, 'admin'=>true));
			}
		}

		$this->_render['template'] = 'admin_edit';
		return compact('template');
	}

	public function admin_edit(){
		$conditions = array(
			'_id' => $this->request->id
		);

		$template = OfferTemplates::first(compact('conditions'));
		if ($this->request->data){
			if ($template->save($this->request->data)){
				FlashMessage::set("Template modified.");
				return $this->redirect(array('OfferTemplates::view', 'id' => $venue->_id));
			}
		}
		return compact('template');
	}
}

?>