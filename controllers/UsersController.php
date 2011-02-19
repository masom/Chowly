<?php
namespace chowly\controllers;

use chowly\models\User;
use \li3_flash_message\extensions\storage\FlashMessage;
use \lithium\security\Auth;
use \lithium\storage\Session;

class UsersController extends \chowly\extensions\action\Controller{

	public function admin_index(){
		
		$limit = 20;
		$page = ($this->request->params['page'])? $this->request->params['page'] : 1;
		$order = array('name' => 'ASC');
		
		$total = User::count();
		$users = User::all(compact('order','limit','page'));
		
		return compact('users', 'total', 'page', 'limit');
	}
	public function admin_add(){
		$user = User::create();
		if(!empty($this->request->data)){
			
		}
		return compact('user');
	}
	public function admin_edit(){
		$conditions = array(
			'_id' => $this->request->id
		);
		$user = User::first(compact('conditions'));
		
		if(!empty($this->request->data)){
			
			$user->set($this->request->data);
			
			if(empty($user->password)){
				unset($user->password);
			}else{
				$user->password = \lithium\util\String::hash($user->password);
			}
			
			if($user->save()){
				FlashMessage::set("User modified.");
				$this->redirect('Users::index');
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
		$data = array('success'=>false,'active'=>false, 'id'=>$this->request->id);
		
		if(empty($this->request->id)){
			return $this->render(array('json'=>$data));
		}
		
		$conditions = array('_id'=>$this->request->id);
		$user = User::first(compact('conditions'));
		
		if(!$user){
			return $this->render(array('json' => $data));
		}
		
		if($user->setActive(true)){
			$data['active'] = true;
			$data['success'] = true;
		}
		return $this->render(array('json' => $data));
	}
	public function admin_disable(){
		
		$data = array('success'=>false,'active'=>false, 'id'=>$this->request->id);
		
		if(empty($this->request->id)){
			return $this->render(array('json'=>$data));
		}
		
		$conditions = array('_id'=>$this->request->id);
		$user = User::first(compact('conditions'));
		
		if(!$user){
			return $this->render(array('json' => $data));
		}
		if($user->setActive(false)){
			$data['active'] = false;
			$data['success'] = true;
		}
		return $this->render(array('json' => $data));
		
	}
	public function reset_password(){
		
	}
	
	public function edit(){
		$conditions = array('_id' => Session::read('user._id'));
		$user = User::first(compact('conditions'));
		if(!empty($this->request->data)){
			$user->set($this->request->data);
			if($user->save($this->request->data,array('whitelist'=>array('name')))){
				FlashMessage::set("Profile updated.");
				$this->redirect('/');
			}
		}
		return compact('user');
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
        FlashMessage::set("Your session has been terminated.");
        return $this->redirect('/');
	}
	public function add(){

		$user = User::create();
		if(!empty($this->request->data)){
			try{
				$user->set($this->request->data);
				$user->role = 'customer';
				$saved = $user->register();
			}catch(\Exception $e){
				FlashMessage::set($e->getMessage());
				return compact('user');
			}
			
			if($saved){
				Auth::check('user',$this->request->data);
				$this->redirect('/');
			}		
		}
		
		return compact('user');
	}
}
?>