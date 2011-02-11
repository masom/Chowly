<?php
namespace chowly\controllers;

use chowly\models\Purchase;
use chowly\models\Offer;
use chowly\models\Venue;
use li3_flash_message\extensions\storage\FlashMessage;

class PurchasesController extends \chowly\extensions\action\Controller{
	
	public function admin_index(){
		$purchases = Purchase::all();
		return compact('purchases');
	}
	public function admin_view(){
		
		$conditions = array('_id' => $this->request->id);
		$purchase = Purchase::first(compact('conditions'));
		
		if(!$purchase){
			FlashMessage::set("The specified purchase could not be found.");
			$this->redirect($this->request->referer());
		}
		
		$conditions = array('_id'=>array());
		foreach($purchase->offers as $offer){
			$conditions['_id'][] = $offer->_id;
		}
		$offers = Offer::all(compact('conditions'));
		
		$conditions = array('_id'=>array());
		foreach($offers as $offer){
			$conditions['_id'][] = $offer->venue_id;
		}
		$venues = Venue::all(compact('conditions'));
		
		
		return compact('purchase','offers','venues');
	}
	public function admin_download(){
		if(!$this->request->id){
			FlashMessage::set("Missing download details.");
			$this->redirect(array('Offers::index'));
		}
		
		$conditions = array('_id' => $this->request->id);
		$purchase = Purchase::first(compact('conditions'));
		if(!$purchase){
			FlashMessage::set("The purchase could not be found");
			$this->redirect($this->request->referer());
		}
		
		$conditions = array('_id'=>array());
		foreach($purchase->offers as $offer){
			$conditions['_id'][] = $offer->_id;
		}
		$offers = Offer::all(compact('conditions'));
		
		$conditions = array('_id'=>array());
		foreach($offers as $offer){
			$conditions['_id'][] = $offer->venue_id;
		}
		$venues = Venue::all(compact('conditions'));
		
		$filename = $purchase->_id.'.pdf';
		$this->_view['renderer'] = 'Pdf';
		$this->_render['template'] = 'purchase';
		return compact('purchase','venues', 'offers','filename');
	}
	public function download(){
		if(!$this->request->id){
			FlashMessage::set("Missing download details.");
			$this->redirect(array('Offers::index'));
		}
		
		$conditions = array('_id' => $this->request->id);
		$purchase = Purchase::first(compact('conditions'));
		if(!$purchase){
			FlashMessage::set("The purchase could not be found");
			$this->redirect($this->request->referer());
		}
		if($purchase->downloaded){
			FlashMessage::set("The purchase has already been downloaded. Contact Chowly support to re-download.");
			$this->redirect(array('Offers::index'));
		}
		$conditions = array('_id'=>array());
		foreach($purchase->offers as $offer){
			$conditions['_id'][] = $offer->_id;
		}
		$offers = Offer::all(compact('conditions'));
		
		$conditions = array('_id'=>array());
		foreach($offers as $offer){
			$conditions['_id'][] = $offer->venue_id;
		}
		$venues = Venue::all(compact('conditions'));
		
		$filename = $purchase->_id.'.pdf';
		return compact('purchase','venues', 'offers','filename');
	}
}
?>