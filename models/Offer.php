<?php
namespace chowly\models;


use chowly\models\Inventory;
use \lithium\storage\Session;

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
		'state' => array('type'=>'string', 'default' => 'unpublished'),
		'name' => array('type'=>'string','null'=>false),
		'starts' => array('type'=>'date','null'=>false),
		'ends'=>array('type'=>'date','null'=>false),
		'availability' => array('type'=>'integer'),
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
			'state'=> 'published'
		);
		$order = array(
			'ends'=>'ASC',
			'availability' => 'DESC'
		);
		return static::all(compact('conditions','order'));
	}
	
	/**
	 * 
	 * @param var $customer_id
	 * @param var $offer_id
	 * @todo Add indexes to inventory
	 */
	public static function reserve($offer_id,$customer_id){
		$conditions = array(
			'starts' => array('$lt' => new \MongoDate()),
			'ends' => array('$gt' => new \MongoDate()),
			'_id' => $offer_id
		);
		$offer = static::first(array('conditions'=> $conditions));
		if(!$offer){
			return array('successfull'=>false, 'error'=>'not_found');
		}
		$inventory = Inventory::reserve($customer_id,$offer_id);
		if(is_array($inventory)){
			$offer->availability--;
			if($offer->availability <= 0){
				$offer->availability = 0;
			}
			$offer->save(null,array('validate'=>false));
			return array('successfull'=>true, 'inventory_id' => $inventory->_id);
		}else{
			$offer->availability = 0;
			$offer->save(null, array('validate' => false));
			return array('successfull'=>false, 'error'=>'sold_out');
		}
	}
	
	public function publish($entity){
		$retval = array('success'=>false,'errors'=>array(),'count'=>0);
		if(!$entity->_id){
			$retval['errors'][] = 'The offer could not be found';
			return $retval;
		}
		
		if($entity->state == 'published'){
			$retval['errors'][] = 'The offer is already published.';
			return $retval;
		}
		
		$entity->state = 'published';
		
		$created = 0;
		for($i = 0; $i < $entity->availability; $i++){
			if(Inventory::createForOffer($entity->_id)){
				$created++;
			}
		}
		
		if($created != $entity->availability){
			$retval['errors'][] = "Only {$created} inventory items where created.";
		}
		if($entity->save()){
			$retval['success'] = true;
		}else{
			Inventory::deleteForOffer($entity->_id);
			$retval['success'] = false;
			$retval['errors'][] = 'The offer could not be published.';
		}
		return $retval;
	}
	
	public function unpublish($entity){
		$retval = array('success'=>false,'errors'=>array(),'count'=>0);
		if(!$entity->_id){
			$retval['errors'][] = 'The offer could not be found';
			return $retval;
		}
		Inventory::deleteForOffer($entity->_id);
		$entity->state = 'unpublished';
		return $entity->save(null,array('validate'=>false,'whitelist'=>array('state')));
	}
}
?>