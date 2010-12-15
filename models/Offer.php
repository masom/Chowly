<?php
namespace chowly\models;


use chowly\models\Inventory;

class Offer extends \lithium\data\Model{
	protected static $_states = array('published'=>'published', 'unpublished'=>'unpublished');
	public $validates = array(
		'name' => array(
			array('notEmpty','message'=>'Please enter a name'),
			array('lengthBetween', 'min'=>1,'max'=>255, 'message' => 'Please enter a name that is between 1 and 255')
		),
		'state' => array(
			array('inList', 'list' => array('published', 'unpublished'))
		),
		'starts' => array(
			array('datetime', 'message'=>'The publication start date must be valid.')
		),
		'ends' => array(
			array('datetime', 'message'=>'The publication end date must be valid.')
		),
		'availability' => array(
			array('numeric', 'message'=>'Please enter a number.'),
			array('inRange', 'upper'=>255,'lower'=>1,'message'=>'Please enter a number between 1 and 255')
		),
		'venue_id' => array(
			array('notEmpty', 'message'=>'There is a relationship problem...')
		),
		'cost' => array(
			array('numeric','message'=>'Must be a monetary amount (ex: 33.00)')
		)
	);
	
	protected $_schema = array(
		'_id' => array('type'=>'id'),
		'venue_id' => array('type'=>'id'),
		'state' => array('type'=>'string'),
		'name' => array('type'=>'string','null'=>false),
		'starts' => array('type'=>'string','null'=>false),
		'ends'=>array('type'=>'string','null'=>false),
		'availability' => array('type'=>'number'),
		'created'=>array('type'=>'date'),
	);
	
	public static function states(){
		return static::$_states;
	}
	
	public static function defaultState(){
		return 'unpublished';
	}

	public static function current(){
		$conditions = array(
			'starts' => array('$lt' => new \MongoDate()),
			'ends' => array('$gt' => new \MongoDate()),
			'availability' => array('$gt' => 0)
		);
		return static::all(compact('conditions'));
	}
	/**
	 * 
	 * @param var $customer_id
	 * @param var $offer_id
	 * @todo Add indexes to inventory
	 */
	public static function reserve($customer_id, $offer_id){
		$conditions = array(
			'starts' => array('$lt' => new \MongoDate()),
			'ends' => array('$gt' => new \MongoDate()),
			'availability' => array('$gt' => 0)
		);
		$offer = static::first(array('conditions'=> $conditions));
		if(!$offer){
			return array('successfull'=>false, 'error'=>'not_found');
		}
		if(Inventory::reserve($customer_id,$offer_id)){
			return array('successfull'=>true);
		}else{
			return array('successfull'=>false, 'error'=>'sold_out');
		}
	}
}
?>