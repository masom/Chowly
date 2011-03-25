<?php
namespace chowly\models;


use chowly\models\Inventory;
use chowly\extensions\data\InventoryException;
use chowly\extensions\data\OfferException;

use \lithium\analysis\Logger;

class Offers extends \chowly\extensions\data\Model{
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
		'image' => array('type'=>'id'),
		'availability' => array('type'=>'integer'),
		'inventoryCount' => array('type'=>'integer'),
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
	public static function rebuildInventory(){
		Inventories::releaseExpired();
		$availableInventory = Inventories::getAvailable();
		$offersInventory = array();
		foreach($availableInventory as $inventory){
			if(!isset($offersInventory[(string)$inventory->offer_id])){
				$offersInventory[(string)$inventory->offer_id] = 0;
			}
			$offersInventory[(string)$inventory->offer_id] ++;
		}
		
		foreach($offersInventory as $offer_id => $availability){
			static::update(compact('availability'), array('_id'=>$offer_id));
		}
	}
	public static function releaseInventory($offer_id){
		try{
			Inventories::release(Cart::id(), $offer_id);
		}catch(InventoryException $e){
			Logger::write('error', "Could not release inventor for the following reason: {$e->getMessage()}");
			return false;
		}
		
		$query = array('$inc' => array('availability'=>1));
		$conditions = array('_id' => $offer_id);
		return static::update($query, $conditions);
	}
	/**
	 * 
	 * @param var $customer_id
	 * @param var $offer_id
	 */
	public static function reserve($offer_id, $cart_id){
		$date = new \MongoDate();
		$conditions = array(
			'starts' => array('$lt' => $date),
			'ends' => array('$gt' => $date),
			'_id' => $offer_id,
			'state'=> 'published'
		);

		$offer = static::first(array('conditions'=> $conditions));
		if(!$offer){
			throw new OfferException("Offer not found.");
		}

		
		try{
			$inventory = Inventories::reserve($offer_id, $cart_id);
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
	public function createWithInventory($entity){
		$entity->inventoryCount = $entity->availability;
		
		if(!$entity->save()){
			return false;
		}
		
		$created = 0;
		for($i =0; $i < $entity->inventoryCount; $i++){
			if(Inventories::createForOffer($entity->_id)){
				$created++;
			}else{
				Logger::write('error', 'Could not create inventory item for {$entity->_id}.');
			}
		}
		
		if($created != $entity->inventoryCount){
			throw new \InventoryException("Only {$created} item created.");
		}
		return true;
	}
	public function publish($entity){
		$entity->state = 'published';
		return $entity->save(null,array('validate'=>false,'whitelist'=>array('state')));
	}

	public function unpublish($entity){
		$entity->state = 'unpublished';
		return $entity->save(null,array('validate'=>false,'whitelist'=>array('state')));
	}
	
	public function getErrors(){
		return $this->_errors;
	}
	public function save($entity, $data = null, array $options = array()) {
		
		$files = array();
		if(isset($data['image'])){
			$files['image'] = $data['image'];
			unset($data['image']);
		}
		
		if(!$entity->_id){
			$entity->_id = new \MongoId();
		}
		
		$this->_errors = array();
		foreach($files as $key => $file){
			if(!$file['tmp_name'] || empty($file['tmp_name'])) continue;
			
			$image = Images::create();
			$imageData = array('file'=> $file, 'parent_id'=> $entity->_id, 'parent_type'=>'offer');
			if($image->save($imageData)){
				$data[$key] = $image->_id;
			}else{
				$this->_errors[]= "Image {$key} could not be saved.";
			}
			
		}
		return parent::save($entity, $data, $options);
	}
}
?>