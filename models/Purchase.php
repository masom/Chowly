<?php
namespace chowly\models;


class Purchase extends \lithium\data\Model{
	
	protected $_schema = array(
		'_id' => array('type'=>'id'),
		'customer_id' => array('type'=>'_id'),
		'state' => array('type'=>'string'),
		'created' => array('type'=>'date')
	);
	
	public $validates = array(
		'agreed_tos_privacy' => array(
			array('notEmpty', 'message' => 'Must be agreed.')
		),
		'name' => array(
			array('lengthBetween', 'min' => 1, 'max' => 255, 'message' => 'Please enter a name between 1 and 255 characters.')
		),
		'email' => array(
			array('email', 'message' => 'Email is not valid.')
		),
		'address'=>array(
			array('notEmpty', 'message'=>'The Cardholder Address cannot be empty.')
		),
		'city' => array(
			array('notEmpty', 'message'=>'The Cardholder City cannot be empty.')
		),
		'phone' => array(
			array('notEmpty', 'message'=>'The Cardholder Phone Number cannot be empty.')
		),
		'postal' => array(
			array('notEmpty', 'message'=>'The Cardholder Postal Code cannot be empty.')
		),
		'cc_number' => array(
			array('creditCard', 'deep'=>true, 'format' => 'any', 'message'=>'Invalid Credit Card Number.')
		),
		'cc_sc' => array(
			array('numeric','message'=>'Invalid')
		),
		'status' => array(
			array('inList', 'list' => array('completed','new', 'failed', 'cancelled'))
		),
		'province' => array(
			array('inList', 'list'=>array(), 'message'=>'Invalid Province.')
		)
	);
	protected static $_provinces = array(
		'Alberta'=>'Alberta',
		'British Columbia' => 'British Columbia',
		'Manitoba' => 'Manitoba',
		'New Brunswick' => 'New Brunswick',
		'Newfoundland' => 'Newfoundland',
		'Norhtwest Territories' => 'Norhtwest Territories',
		'Nova Scotia' => 'Nova Scotia',
		'Nunavut' => 'Nunavut',
		'Ontario' => 'Ontario',
		'Prince Edward Island' => 'Prince Edward Island',
		'Quebec' => 'Quebec',
		'Saskatchewan' => 'Saskatchewan',
		'Yukon' => 'Yukon'
	);
	
	public function validates($entity, $options = array()){
		$this->validates['province'][0]['list'] = static::$_provinces;
		return parent::validates($entity,$options);
	}
	
	public function process($entity, Array $data = array()){
		$entity->set(data);
		$entity->status = 'complete';
		return $entity->save();
	}
	
	public function isComplete($entity){
		return ($entity->status == 'complete')? true : false;
	}
	public static function getProvinces(){
		return static::$_provinces;
	}
	public function save($entity, $data = null, Array $options = array()){
		parent::save($entity, $data, $options);
	}
}
?>