<?php
namespace chowly\controllers;


use chowly\models\User;
use \lithium\data\Controller;
use \li3_flash_message\extensions\storage\FlashMessage;

class LandingsController extends \chowly\extensions\action\Controller{
	public function pre(){
		$user = User::create();
		if($this->request->data){
			
			unset($this->request->data['x'],$this->request->data['y']);
			if(empty($user->zip)){
				unset($user->zip);
			}
			if($user->preRegister($this->request->data)){
				$this->redirect(array('Pages::view','args'=>'thankyou'));
			}else{
				FlashMessage::set('Sorry, there is something wrong with the provided information.');
			}
		}
		return compact('user');
	}
}
?>