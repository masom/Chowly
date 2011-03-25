<?php
namespace chowly\models;

class Users extends \chowly\extensions\data\Model{
	public $_schema = array(
		'_id' => array('type'=>'id'),
		'name' => array('type'=>'string','null'=>false),
		'email' => array('type'=>'string','null'=>false),
		'password' => array('type'=>'string','null'=>false),
		'created' => array('type'=>'date')
	);
	public $validates = array(
		'name' => array(
			array('lengthBetween', 'min' => 1, 'max' => 255, 'message' => 'Please enter a name between 1 and 255 characters')
		),
		'email' => array(
			array('email', 'message' => 'Email is not valid.')
		),
		'password' => array(
			array('notEmpty','message' => 'Password is empty.')
		),
		'role' => array(
			array('inList', 'list' => array('admin','staff','venue', 'customer'), 'message'=>'Invalid role.')	
		)
	);
	
	private $_roles = array('admin'=>'admin','staff'=>'staff','venue'=>'venue','customer'=>'customer');
	public function roles(){
		return $this->_roles;
	}
	public function register($entity){
		
		$conditions = array('email' => $entity->email);
		if(static::first(compact('conditions'))){
			throw new \Exception("This email address is already registered.");
		}
		
		if($entity->password != $entity->password_repeat){
			throw new \Exception("Password fields do not match.");	
		}
		unset($entity->password_repeat);
		
		if(!empty($entity->password)){
			$entity->password = \lithium\util\String::hash($entity->password);
		}
		
		$entity->active = true;
		if($entity->save()){
			return true;
		}
		return false;
	}
	public function resetPassword($entity){
		return $entity->save(null,array('validate'=>false));
	}
	public function setRole($entity, $role){
		if(!in_array($role, $this->_roles)){
			return false;
		}
		if(!$entity->_id){
			return false;
		}
		$entity->role = $role;
		return $entity->save(null,array('validate'=>true,'whitelist'=>array('role')));
	}
	public function setActive($entity, $active){
		$entity->active = $active;
		return $entity->save(null,array('validate'=>true,'whitelist'=>array('active')));
	}
}
?>