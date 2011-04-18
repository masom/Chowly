<?php

namespace chowly\controllers;

use chowly\models\Purchases;
use chowly\models\Offers;
use chowly\models\Venues;
use chowly\extensions\Utils;
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

		if (!$purchase){
			FlashMessage::set("The specified purchase could not be found.");
			return $this->redirect($this->request->referer());
		}

		$conditions = array('_id'=>array());
		foreach ($purchase->offers as $offer){
			$conditions['_id'][] = $offer->_id;
		}
		$offers = Offers::all(compact('conditions'));

		$conditions = array('_id'=>array());
		foreach ($offers as $offer){
			$conditions['_id'][] = $offer->venue_id;
		}
		$venues = Venues::all(compact('conditions'));

		return compact('purchase','offers','venues');
	}

	public function admin_search(){
		switch ($this->request->data['search']['type']){
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
		if (!$this->request->id){
			FlashMessage::set("Missing download details.");
			return $this->redirect(array('Purchases::index'));
		}

		$conditions = array('_id' => $this->request->id);
		$purchase = Purchases::first(compact('conditions'));
		if (!$purchase){
			FlashMessage::set("The purchase could not be found");
			return $this->redirect(array('Purchases::index'));
		}

		$offers = $purchase->offers;

		$conditions = array('_id'=>array());
		foreach ($offers as $offer){
			$conditions['_id'][] = $offer->venue_id;
		}
		$venues = Venues::all(compact('conditions'));

		$filename = $purchase->_id . '.pdf';
		$this->_view['renderer'] = 'Pdf';
		$this->_render['template'] = 'purchase';
		return compact('purchase','venues', 'offers','filename');
	}

	/**
	 * Resend a purchase
	 */
	public function admin_resend(){
		$conditions = array('_id' => $this->request->id);

		$purchase = Purchases::first(compact('conditions'));
		if (!$purchase){
			FlashMessage::set('The specified purchase could not be found in the database.');
			return $this->redirect(array('Purchases::index'));
		}

		$offers = $purchase->offers;

		$conditions = array('_id'=>array());
		foreach ($offers as $offer){
			$conditions['_id'][] = $offer->venue_id;
		}
		$venues = Venues::all(compact('conditions'));

		$pdfPath = null;
		try{
			$pdfPath = Utils::getPdf($purchase, $offers, $venues);
		}catch(\Exception $e){
			FlashMessage::set("An error occured while generating the PDF: {$e->getMessage()}");
			return $this->redirect(array('Purchases::view', 'id'=>$purchase->_id));
		}
		if (!$pdfPath){
			FlashMessage::set("Unable to retreive PDF");
			return $this->redirect(array('Purchases::view', 'id'=>$purchase->_id));
		}

		$to = $purchase->email;

		$transport = Swift_MailTransport::newInstance();
		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance();
		$message->setSubject("Chowly Purchase Confirmation");
		$message->setFrom(array('purchases@chowly.com' => 'Chowly'));
		$message->setTo($to);
		$message->setBody(parent::_getEmail(compact('purchase'), 'purchase', 'purchases'));
		$message->attach(Swift_Attachment::fromPath($pdfPath));

		if ($mailer->send($message)){
			FlashMessage::set("Email re-sent");
		}else{
			FlashMessage::set("The email could not be re-sent.");
		}

		return $this->redirect(array('Purchases::view', 'id'=>$purchase->_id));
	}

	public function download(){
		if (!$this->request->id){
			FlashMessage::set("Missing download details.");
			return $this->redirect(array('Offers::index'));
		}

		$conditions = array('_id' => $this->request->id);
		$purchase = Purchases::first(compact('conditions'));

		if (!$purchase){
			FlashMessage::set("The purchase could not be found");
			return $this->redirect($this->request->referer());
		}

		/**
		if($purchase->downloaded){
			$message = "The purchase has already been downloaded.";
			$message .= " Contact Chowly support to re-download.";
			FlashMessage::set($message);
			return $this->redirect(array('Offers::index'));
		}*/

		$options = array('multiple' => false, 'safe' => false, 'upsert'=>false);
		$data = array('$set'=>array('downloaded'=>true));
		$conditions = array('_id' => $purchase->_id);
		Purchases::update($data, $conditions, $options);

		$path = Purchases::pdfPath() . DIRECTORY_SEPARATOR . $purchase->_id . '.pdf';

		if (file_exists($path)){
			return new \lithium\action\Response(array(
				'headers' => array('Content-type' => 'application/pdf'),
				'body' => file_get_contents($path)
			));
		}

		$offers = $purchase->offers;

		$conditions = array('_id'=>array());
		foreach ($offers as $offer){
			$conditions['_id'][] = $offer->venue_id;
		}

		$venues = Venues::all(compact('conditions'));

		$filename = $purchase->_id . '.pdf';
		$this->_view['renderer'] = 'Pdf';
		$this->_render['template'] = 'purchase';
		return compact('purchase','venues', 'offers','filename');
	}
}

?>