<?php
namespace chowly\controllers;

use chowly\models\Purchases;
use chowly\models\Offers;
use chowly\models\Venues;
use li3_flash_message\extensions\storage\FlashMessage;

class PurchasesController extends \chowly\extensions\action\Controller{
	
	public function admin_index(){
		
		$limit = 20;
		$page = ($this->request->page) ?: 1;
		$order = array('created' => 'DESC');
		
		$total = Purchases::count();
		$purchases = Purchases::all(compact('order','limit','page'));
		
		return compact('purchases', 'total', 'page', 'limit');
	}
	public function admin_view(){
		
		$conditions = array('_id' => $this->request->id);
		$purchase = Purchases::first(compact('conditions'));
		
		if(!$purchase){
			FlashMessage::set("The specified purchase could not be found.");
			return $this->redirect($this->request->referer());
		}
		
		$conditions = array('_id'=>array());
		foreach($purchase->offers as $offer){
			$conditions['_id'][] = $offer->_id;
		}
		$offers = Offers::all(compact('conditions'));
		
		$conditions = array('_id'=>array());
		foreach($offers as $offer){
			$conditions['_id'][] = $offer->venue_id;
		}
		$venues = Venues::all(compact('conditions'));
		
		
		return compact('purchase','offers','venues');
	}
	public function admin_search(){
		switch($this->request->data['search']['type']){
			case "email":
				$conditions = array('email' => $this->request->data['search']['value']);
				break;
			case "name":
				$conditions = array('name' => $this->request->data['search']['value']);
				break;				
			default:
				FlashMessage::set("Invalid search parameters");
				return $this->redirect(array('Purchases::index','admin'=>true));
		}
		$order = array('created' => 'DESC');
		$purchases = Purchases::all(compact('order','conditions'));
		$this->_render['template'] = 'admin_index';
		return compact('purchases');
	}
	public function admin_download(){
		if(!$this->request->id){
			FlashMessage::set("Missing download details.");
			return $this->redirect(array('Offers::index'));
		}
		
		$conditions = array('_id' => $this->request->id);
		$purchase = Purchases::first(compact('conditions'));
		if(!$purchase){
			FlashMessage::set("The purchase could not be found");
			return $this->redirect($this->request->referer());
		}
		
		$conditions = array('_id'=>array());
		foreach($purchase->offers as $offer){
			$conditions['_id'][] = $offer->_id;
		}
		$offers = Offers::all(compact('conditions'));
		
		$conditions = array('_id'=>array());
		foreach($offers as $offer){
			$conditions['_id'][] = $offer->venue_id;
		}
		$venues = Venues::all(compact('conditions'));
		
		$filename = $purchase->_id.'.pdf';
		$this->_view['renderer'] = 'Pdf';
		$this->_render['template'] = 'purchase';
		return compact('purchase','venues', 'offers','filename');
	}
	public function download(){
		if(!$this->request->id){
			FlashMessage::set("Missing download details.");
			return $this->redirect(array('Offers::index'));
		}
		
		$conditions = array('_id' => $this->request->id);
		$purchase = Purchases::first(compact('conditions'));
		if(!$purchase){
			FlashMessage::set("The purchase could not be found");
			return $this->redirect($this->request->referer());
		}
		if($purchase->downloaded){
			FlashMessage::set("The purchase has already been downloaded. Contact Chowly support to re-download.");
			return $this->redirect(array('Offers::index'));
		}
		$conditions = array('_id'=>array());
		foreach($purchase->offers as $offer){
			$conditions['_id'][] = $offer->_id;
		}
		$offers = Offers::all(compact('conditions'));
		
		$conditions = array('_id'=>array());
		foreach($offers as $offer){
			$conditions['_id'][] = $offer->venue_id;
		}
		$venues = Venues::all(compact('conditions'));
		
		$filename = $purchase->_id.'.pdf';
		return compact('purchase','venues', 'offers','filename');
	}
}
?>