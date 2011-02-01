<?php
namespace chowly\models;


class Purchase extends \lithium\data\Model{
	
	public $error = null;
	protected $_schema = array(
		'_id' => array('type'=>'id'),
		'customer_id' => array('type'=>'_id'),
		'state' => array('type'=>'string'),
		'created' => array('type'=>'date'),
		'modified' => array('type'=>'date'),
		'offers' => array('type'=>'array', 'array'=>true)
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
	
	public function process($entity, $offers){
		if(empty($offers)){
			$entity->error = "There are no offers matching the cart items.";
			throw new \Exception();
		}
		if($entity->cc_sc == 999){
			return false;
		}
		$entity->price = 0.00;

		foreach($offers as $offer){
			$entity->price += $offer->cost;
			$entity->offers[] = array('_id' => $offer->_id, 'price'=> $offer->cost);
		}
		
		//TODO: Actual CC Processing.
		
		$entity->status = 'completed';
		$entity->cc_number = substr($entity->cc_number, -4, 4);
		unset($entity->cc_sc, $entity->cc_e_month, $entity->cc_e_year);
		
		if(!$entity->save(null,array('validate'=>false))){
			$this->error = "Transaction Error";
			throw new \Exception();
		}
		return true;
	}
	
	public function isCompleted($entity){
		if($entity->cc_sc == 999){
			return false;
		}
		return ($entity->status == 'completed')? true : false;
	}
	public static function getProvinces(){
		return static::$_provinces;
	}
}
?>