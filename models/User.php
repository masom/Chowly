<?php
namespace chowly\models;

use \lithium\util\Validator;

class User extends \lithium\data\Model{
	public $_schema = array(
		'_id' => array('type'=>'id'),
		'name' => array('type'=>'string','null'=>false),
		'email' => array('type'=>'string','null'=>false),
		'password' => array('type'=>'string','null'=>false),
		'created' => array('type'=>'date')
	);
	public $validates = array(
		'email' => array(
			array('email', 'message' => 'Email is not valid.'),
			array('unique', 'message' => 'Email already registered.')
		),
		'zip' => array(
			array('zip', 'skipEmpty'=>true, 'message' => 'Invalid Canadian postal code.')
		),
		'role' => array(
			array('inList', 'list' => array('admin','staff','venue', 'customer'))	
		)
	);
	
	private $_roles = array('admin','staff','venue','customer');
	
	public function preRegister($entity, $data){
		
		Validator::add('unique', function($value){
			if(User::first(array('conditions'=>array('email'=>$value)))){
				return false;
			}
			return true;
		});
		
		$entity->set($data);
		$entity->role = 'customer';
		return $entity->save(null,array('whitelist'=>array('email','zip','role')));
	}
	public static function register($data){
		$user = static::create();
		$user->set($data);
		$user->password = sha1($user->password);
		if($user->save()){
			//TODO: Send email
			return true;
		}
		return false;
	}
	public function resetPassword($entity){
		if(!$entity->_id){
			return false;
		}
		//TODO: Email password
		$newPassword = "test";
		$password = sha1($newPassword);
		return $entity->save(null,array('validate'=>false));
	}
	public function setRole($entity, $role){
		if(!in_array($class, $this->_roles)){
			return false;
		}
		if(!$entity->_id){
			return false;
		}
		$entity->role = $role;
		return $entity->save(null,array('validate'=>true,'whitelist'=>'role'));
	}
}