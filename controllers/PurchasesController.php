<?php
namespace chowly\controllers;

use chowly\models\Purchase;
use li3_flash_message\extensions\storage\FlashMessage;

class PurchasesController extends \chowly\extensions\action\Controller{
	
	public function download(){
		if(!$this->request->id){
			FlashMessage::set("Missing download details.");
			$this->redirect(array('Offers::index'));
		}
		
		$conditions = array('_id' => $this->request->id);
		$purchase = Purchase::first(compact('conditions'));
		
		if($purchase->downloaded){
			FlashMessage::set("The purchase has already been downloaded. Contact Chowly support to re-download.");
			$this->redirect(array('Offers::index'));
		}
		return compact('purchase');
	}
}
?>