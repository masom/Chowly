<?php
namespace chowly\models;

class User extends \lithium\data\Model{
	public $_schema = array(
		'_id' => array('type'=>'id'),
		'name' => array('type'=>'string','null'=>false),
		'email' => array('type'=>'string','null'=>false),
		'password' => array('type'=>'string','null'=>false),
		'created' => array('type'=>'date')
	);
	public $validates = array(
		'name' => array(
			array('notEmpty', 'message' => 'Please enter your name'),
			array('lengthBetween', 'min' => 1, 'max' => 255, 'message' => 'Please enter a name between 1 and 255 characters')
		),
		'email' => array(
			array('notEmpty', 'message' => 'Email is empty.'),
			array('email', 'message' => 'Email is not valid.')
		),
		'password' => array(
			array('notEmpty','message' => 'Password is empty.')
		),
		'role' => array(
			array('inList', 'list' => array('admin','staff','venue', 'customer'))	
		)
	);
	
	private $_roles = array('admin','staff','venue','customer');
	
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