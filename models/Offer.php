<?php
namespace chowly\models;


use chowly\models\Inventory;
use chowly\extensions\data\InventoryException;
use chowly\extensions\data\OfferException;

use \lithium\analysis\Logger;

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

		$date = new \MongoDate();
		$conditions = array(
			'starts' => array('$lt' => $date),
			'ends' => array('$gt' => $date),
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
	 */
	public static function reserve($offer_id,$customer_id){
		$date = new \MongoDate();
		$conditions = array(
			'starts' => array('$lt' => $date),
			'ends' => array('$gt' => $date),
			'_id' => $offer_id
		);

		$offer = static::first(array('conditions'=> $conditions));
		if(!$offer){
			throw new OfferException("Offer not found.");
		}

		try{
			$inventory = Inventory::reserve($customer_id,$offer_id);
			$offer->availability--;
			if($offer->availability <= 0){
				$offer->availability = 0;
			}
			$offer->save(null,array('validate'=>false));
		}catch(InventoryException $e){
			$offer->availability = 0;
			$offer->save(null, array('validate' => false));
			throw $e;
		}

		return $inventory->_id;
	}
	
	public function publish($entity){
		if($entity->state == 'published'){
			throw new \Exception('The offer is already published.');
		}

		$entity->state = 'published';

		$conditions = array('offer_id' => $entity->_id);
		$inventory_count = Inventory::count(compact('conditions'));
		die(debug($inventory_count));
		
		$created = 0;
		for($i = $inventory_count; $i < $entity->availability; $i++){
			if(Inventory::createForOffer($entity->_id)){
				$created++;
			}else{
				Logger::write('error', 'Could not create inventory item for {$entity_id}.');
			}
		}

		if($entity->save()){
			$count = ($created + $inventory_count);
			if($count != $entity->availability){
				throw new \InventoryException("Only {$count} item created.");
			}
			return true;
		}else{
			Inventory::deleteForOffer($entity->_id);
			return false;
		}
	}

	public function unpublish($entity){
		Inventory::deleteForOffer($entity->_id);
		$entity->state = 'unpublished';
		return $entity->save(null,array('validate'=>false,'whitelist'=>array('state')));
	}
}
?>