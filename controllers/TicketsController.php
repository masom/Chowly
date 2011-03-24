<?php
namespace chowly\controllers;

use chowly\models\Ticket;
use li3_flash_message\extensions\storage\FlashMessage;
use \Swift_MailTransport;
use \Swift_Mailer;
use \Swift_Message;

class TicketsController extends \chowly\extensions\action\Controller{
	public function add(){
		
		$ticket = Ticket::create();
		if($this->request->data){
			$ticket->state = 'new';
			if($ticket->save($this->request->data)){
				
				$transport = Swift_MailTransport::newInstance();
				$mailer = Swift_Mailer::newInstance($transport);
				$message = Swift_Message::newInstance();
				$message->setSubject("Chowly - New Ticket");
				$message->setFrom(array('no-reply@chowly.com' => 'Chowly'));
				$message->setTo(array('msamson@chowly.com'));
				$message->setBody($this->_getEmail(compact('ticket'), 'new'));
				$mailer->send($message);
				
				return $this->redirect(array('Tickets::received'));
			}else{
				FlashMessage::set('Sorry, there is something wrong with the provided information.');
			}
		}
		
		$args = $this->request->args ?: array();
		$isRestaurant = in_array('restaurants', $args) ?: false;
		
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