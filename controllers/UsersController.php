<?php
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\controllers;

use chowly\models\Users;
use li3_flash_message\extensions\storage\FlashMessage;
use lithium\security\Auth;
use lithium\storage\Session;

class UsersController extends \chowly\extensions\action\Controller{

	public function admin_index(){
		$limit = 20;
		$page = $this->request->page ?: 1;
		$order = array('name' => 'ASC');

		$total = Users::count();
		$users = Users::all(compact('order','limit','page'));

		return compact('users', 'total', 'page', 'limit');
	}

	public function admin_add(){
		$user = Users::create();
		if (!empty($this->request->data)){
			try{
				$saved = $user->save($this->request->data);
			}catch(\Exception $e){
				FlashMessage::set($e->getMessage());
				return compact('user');
			}
			if ($saved){
				return $this->redirect(array("Users::index", 'admin'=>true));
			}
		}
		return compact('user');
	}

	public function admin_edit(){
		$conditions = array(
			'_id' => $this->request->id
		);
		$user = Users::first(compact('conditions'));

		if (!$user){
			FlashMessage::set('User not found.');
			return $this->redirect($this->request->referer());
		}

		if (!empty($this->request->data)){
			try{
				$saved = $user->save($this->request->data);
			}catch(\Exception $e){
				FlashMessage::set("The user could not be modified.");
				return compact('user');
			}
			if ($saved){
				FlashMessage::set("User modified.");
				return $this->redirect(array("Users::index", 'admin'=>true));
			}else{
				FlashMessage::set("The user could not be modified.");
			}
		}
		return compact('user');
	}

	public function admin_enable(){
		$data = array('success'=>false,'active'=>false, 'id'=>$this->request->id);

		if (empty($this->request->id)){
			return $this->render(array('json'=>$data));
		}

		$conditions = array('_id'=>$this->request->id);
		$user = Users::first(compact('conditions'));

		if (!$user){
			return $this->render(array('json' => $data));
		}

		if ($user->setActive(true)){
			$data['active'] = true;
			$data['success'] = true;
		}
		return $this->render(array('json' => $data));
	}

	public function admin_disable(){
		$data = array('success'=>false,'active'=>false, 'id'=>$this->request->id);

		if (empty($this->request->id)){
			return $this->render(array('json'=>$data));
		}

		$conditions = array('_id'=>$this->request->id);
		$user = Users::first(compact('conditions'));

		if (!$user){
			return $this->render(array('json' => $data));
		}

		if ($user->setActive(false)){
			$data['active'] = false;
			$data['success'] = true;
		}

		return $this->render(array('json' => $data));
	}

	public function reset_password(){}

	public function edit(){
		$conditions = array('_id' => Session::read('user._id'));
		$user = Users::first(compact('conditions'));
		if (!empty($this->request->data)){
			$user->set($this->request->data);
			if ($user->save($this->request->data,array('whitelist'=>array('name')))){
				FlashMessage::set("Profile updated.");
				return $this->redirect('/');
			}
		}
		return compact('user');
	}
	public function login(){
		if (!empty($this->request->data)){
			if (Auth::check('user', $this->request)){
				return $this->redirect('Offers::index');
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
		$user = Users::create();
		if (!empty($this->request->data)){
			try{
				$saved = $user->save($this->request->data);
			}catch(\Exception $e){
				FlashMessage::set($e->getMessage());
				return compact('user');
			}

			if ($saved){
				Auth::check('user', $this->request);
				return $this->redirect('/');
			}
		}
		return compact('user');
	}
}

?>