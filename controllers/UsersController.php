<?php
namespace chowly\controllers;

use chowly\models\User;
use \li3_flash_message\extensions\storage\FlashMessage;
use \lithium\security\Auth;
use \lithium\storage\Session;

class UsersController extends \chowly\extensions\action\Controller{

	public function admin_index(){
		$users = User::all();
		return compact('users');
	}
	public function admin_add(){
		$user = User::create();
		if(!empty($this->request->data)){
			
		}
		$this->_render['template'] = 'edit';
		return compact('user');
	}
	public function admin_edit(){
		$conditions = array(
			'_id' => $this->request->id
		);
		$user = User::first(compact('conditions'));
		
		if(!empty($this->request->data)){
			if($user->save($this->request->data)){
				FlashMessage::set("User modified.");
				$this->redirect(array('Users::view',$user->_id));
			}else{
				FlashMessage::set("The user could not be modified.");
			}
		}
				
		if(empty($user)){
			FlashMessage::set('User not found.');
			$this->redirect($this->request->referer());
		}
		
		return compact('user');
	}
	public function admin_enable(){
		if(empty($this->request->data)){
			return;
		}
		
		$conditions = array('_id'=>$this->request->data['id']);
		$user = User::first(compact('conditions'));
		
		if(!$user){
			return;
		}
		
		$user->enable();
	}
	public function admin_disable(){
		if(empty($this->request->data)){
			return;
		}
		
		$conditions = array('_id'=>$this->request->data['id']);
		$user = User::first(compact('conditions'));
		
		if(!$user){
			return;
		}
		
		$user->disable();
	}
	public function reset_password(){
		
	}
	
	public function edit(){
		debug(Session::read());die;
		$conditions = array('_id' => null);
		$user = User::first(compact('conditions'));
		if(!empty($this->request->data)){
			
		}
	}
	public function login(){
		if(!empty($this->request->data)){
			if(Auth::check('user', $this->request)){
				$this->redirect('Offers::index');
			}else{
				FlashMessage::set("Wrong email or password.");
			}
		}
	}
	public function logout(){
        Auth::clear('user');
        return $this->redirect('/');
	}
	public function add(){

		$user = User::create();
		if(!empty($this->request->data)){
			$user->set($this->request->data);
			$conditions = array('email' => $user->email);
			if(User::first(compact('conditions'))){
				FlashMessage::set("This email address is already registered.");
				return compact($user);
			}
			if($user->save()){
				Auth::check('user',$this->request->data);
				$this->redirect('/');
			}
		}
		
		return compact('user');
	}
}

User::applyFilter('save', function($self, $params, $chain){
    $record = $params['entity'];
    if (!$record->_id) {
        $record->password = \lithium\util\String::hash($record->password);
    }
    $params['entity'] = $record;
    return $chain->next($self, $params, $chain);
});
?>