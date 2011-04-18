<?php

namespace chowly\models;

use lithium\util\String;

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
			array('lengthBetween', 'min' => 1, 'max' => 255,
				'message' => 'Please enter a name between 1 and 255 characters')
		),
		'email' => array(
			array('email', 'message' => 'Email is not valid.')
		),
		'password' => array(
			array('notEmpty','message' => 'Password is empty.')
		),
		'role' => array(
			array('inList', 'list' => array('admin','staff','venue', 'customer'),
				'message'=>'Invalid role.')
		)
	);

	/**
	 * Holds the different possible roles a user can have
	 * @var array List of roles (key => display)
	 */
	private $_roles = array('admin','staff','venue','customer');

	/**
	 * Returns the list of roles;
	 * @return array List of roles
	 */
	public function roles(){
		return $this->_roles;
	}

	/**
	 * Resets password
	 * @param Users $entity
	 * @throws \Exception
	 * @return boolean
	 */
	public function resetPassword($entity){
		if ($entity->password != $entity->password_repeat){
			throw new \Exception("Password fields do not match.");
		}
		$entity->password = String::hash($entity->password);
		unset($entity->password_repeat);

		return $entity->save(null,array('validate'=>false));
	}

	/**
	 * Set a user's role
	 * @param Users $entity
	 * @param var $role
	 * @return boolean
	 */
	public function setRole($entity, $role){
		if (!in_array($role, $this->_roles)){
			return false;
		}
		if (!$entity->_id){
			return false;
		}
		$entity->role = $role;
		return $entity->save(null,array('validate'=>true,'whitelist'=>array('role')));
	}

	/**
	 * Activate/Deactive a user account.
	 * @param Users $entity
	 * @param boolean $active
	 * @return boolean
	 */
	public function setActive($entity, $active){
		$entity->active = $active;
		return $entity->save(null,array('validate'=>true,'whitelist'=>array('active')));
	}

	public function save($entity, $data = null, array $options = array()) {
		if (!$entity->exists()){
			$conditions = array('email' => $data['email']);
			if (static::first(compact('conditions'))){
				throw new \Exception("This email address is already registered.");
			}
			$data['active'] = true;
		}

		$data['password'] = trim($data['password']);

		if ($entity->exists() && !empty($data['password'])){
			if ($data['password'] != $data['password_repeat']){
				throw new \Exception("Password fields do not match.");
			}
		}else{
			if ($data['password'] != $data['password_repeat']){
				throw new \Exception("Password fields do not match.");
			}
		}

		unset($data['password_repeat']);

		if (empty($data['password'])){
			unset($data['password']);
		}else{
			$data['password'] = \lithium\util\String::hash($data['password']);
		}

		if (is_numeric($data['role']) && isset($this->_roles[$data['role']])){
			$data['role'] = $this->_roles[$data['role']];
		}else{
			$data['role'] = 'customer';
		}

		$entity->set($data);
		return parent::save($entity);
	}
}

?>