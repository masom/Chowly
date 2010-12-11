<?php
namespace chowly\models;

use \lithium\data\collection\DocumentSet;
use chowly\models\Inventory;

class Offer extends \lithium\data\Model{
	
	//protected $_meta = array('source'=>'fs.files');
	
	protected static $_states = array('published', 'unpublished');
	protected $_schema = array(
		'_id' => array('type'=>'id'),
		'venue_id' => array('type'=>'id'),
		'state' => array('type'=>'string'),
		'name' => array('type'=>'string','default'=>'undefined'),
		'starts' => array('type'=>'date','default'=>0),
		'ends'=>array('type'=>'date','default'=>0),
		'availability' => array('type'=>'number'),
		'created'=>array('type'=>'date'),
	);
	public static function states(){
		return array_values(static::$_states);
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