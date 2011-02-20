<?php
namespace chowly\controllers;

use chowly\models\Ticket;
use li3_flash_message\extensions\storage\FlashMessage;

class TicketsController extends \chowly\extensions\action\Controller{
	public function add(){
		$ticket = Ticket::create();
		if($this->request->data){
			$ticket->state = 'new';
			if($ticket->save($this->request->data)){
				$this->redirect(array('Tickets::received'));
			}else{
				FlashMessage::set('Sorry, there is something wrong with the provided information.');
			}
		}
		
		$isRestaurant = false;
		
		if(in_array('restaurants',$this->request->args)){
			$isRestaurant = true;
		}
		return compact('ticket','isRestaurant');
	}
	public function received(){}
	
	public function admin_index(){
		$conditions = array('state'=>'new');
		$order = array('created' => 'ASC');
		$tickets = Ticket::all(compact('conditions','order'));
		return compact('tickets');
	}
	public function admin_view(){
		$conditions = array('_id' => $this->request->id);
		$ticket = Ticket::first(compact('conditions'));
		return compact('ticket');
	}
}
?>