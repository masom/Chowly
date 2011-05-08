<?php
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\models;

use chowly\models\Images;
use chowly\extensions\data\InventoryException;
use chowly\extensions\data\OfferException;
use lithium\analysis\Logger;

class Offers extends \chowly\extensions\data\Model{
	protected static $_states = array('published'=>'published', 'unpublished'=>'unpublished');
	public $validates = array(
		'name' => array(
			array('notEmpty','message'=>'Please enter a name'),
			array('lengthBetween', 'min'=>1,'max'=>255,
				'message' => 'Please enter a name that is between 1 and 255')
		),
		'state' => array(
			array('inList', 'list' => array('published', 'unpublished'))
		),
		'availability' => array(
			array('numeric', 'message'=>'Please enter a number.'),
			array('inRange', 'upper'=>256,'lower'=>0,
				'message'=>'Please enter a number between 1 and 255')
		),
		'venue_id' => array(
			array('notEmpty', 'message'=>'There is a relationship problem...')
		),
		'cost' => array(
			array('numeric','message'=>'Must be a monetary amount (ex: 33.00)')
		)
	);

	protected $_schema = array(
		'_id' => array('type' => 'id'),
		'venue_id' => array('type'=>'id'), // The venue
		'state' => array('type'=>'string', 'default' => 'unpublished'),
		'name' => array('type' => 'string','null'=>false), // Name of the coupon
		'slug' => array('type' => 'string'), //Url friendly name
		'description'=>array('type'=>'string'), // Description (if any) of the coupon
		'limitations'=>array('type'=>'string'), // Limitations regarding usage of the cuopon
		'starts' => array('type'=>'date','null'=>false), // Publication start
		'ends'=>array('type'=>'date','null'=>false), // Publication ends
		'expires' => array('type'=>'date','null'=>false), //Expiry date of the offer
		'availability' => array('type'=>'integer'), // Holds how many items are available
		'inventoryCount' => array('type'=>'integer'), // Holds number of inventory items
		'sold' => array('type'=>'integer'), // Holds how many where sold
		'created'=>array('type'=>'date'),
		'updated'=>array('type'=>'date')
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
	 * Rebuilds the system inventory count
	 * @throws UnexpectedValueException Thrown when the inventory collection is empty.
	 */
	public static function rebuildInventory(){
		Inventories::releaseExpired();
		$availableInventory = Inventories::getAvailable();

		if (count($availableInventory) == 0){
			if (Inventories::count() == 0){
				throw new UnexpectedValueException('No more inventory.');
			}
			static::update(array('availability'=>0));
		}

		$offersInventory = array();
		foreach ($availableInventory as $inventory){
			if (!isset($offersInventory[(string) $inventory->offer_id])){
				$offersInventory[(string) $inventory->offer_id] = 0;
			}
			$offersInventory[(string) $inventory->offer_id] ++;
		}

		foreach ($offersInventory as $offer_id => $availability){
			static::update(compact('availability'), array('_id'=>$offer_id));
		}
	}

	/**
	 * Release specified inventory and make it available again.
	 * @param var $cart_id
	 * @param var $offer_id
	 * @return boolean
	 */
	public static function releaseInventory($cart_id, $offer_id){
		try{
			Inventories::release($cart_id, $offer_id);
		}catch(InventoryException $e){
			$message =  "Could not release inventory for the following reason: {$message}";
			Logger::write('error', $message);
			return false;
		}

		$query = array('$inc' => array('availability'=>1));
		$conditions = array('_id' => $offer_id);
		return static::update($query, $conditions);
	}

	/**
	 * Reserve an item in the inventory
	 * @param var $offer_id The offer id
	 * @param var $cart_id The cart id the item is reserved for.
	 * @return var Inventory id that got reserved
	 */
	public static function reserve($offer_id, $cart_id){
		$date = new \MongoDate();
		$conditions = array(
			'starts' => array('$lt' => $date),
			'ends' => array('$gt' => $date),
			'_id' => $offer_id,
			'state'=> 'published'
		);

		$offer = static::first(compact('conditions'));
		if (!$offer){
			throw new OfferException("Offer not found.");
		}

		$error = null;
		try{
			$inventory = Inventories::reserve($offer_id, $cart_id);
			$offer->availability--;
		}catch (InventoryException $e){
			$offer->availability = 0;
			$error = $e;
		}

		if ($offer->availability < 0){
			$offer->availability = 0;
		}

		$offer->save(null, array('validate' => false, 'whitelist' => array('availability')));

		if ($error){
			throw $error;	
		}

		return $inventory->_id;
	}

	public function createWithInventory($entity){
		$entity->inventoryCount = $entity->availability;

		if (!$entity->save()){
			return false;
		}

		$created = 0;
		for ($i = 0; $i < $entity->inventoryCount; $i++){
			if (Inventories::createForOffer($entity->_id, $i)){
				$created++;
			}else{
				Logger::write('error', "Could not create inventory item for {$entity->_id}.");
			}
		}

		if ($created != $entity->inventoryCount){
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
		if (isset($data['image'])){
			$files['image'] = $data['image'];
			unset($data['image']);
		}

		if (!$entity->_id){
			$entity->_id = new \MongoId();
		}

		$this->_errors = array();
		foreach ($files as $key => $file){
			if (!$file['tmp_name'] || empty($file['tmp_name'])){
				continue;
			}

			$image = Images::create();
			$imageData = array('file'=> $file, 'parent_id'=> $entity->_id, 'parent_type'=>'offer');
			if ($image->save($imageData)){
				$data[$key] = $image->_id;
			}else{
				$this->_errors[] = "Image {$key} could not be saved.";
			}
		}
		return parent::save($entity, $data, $options);
	}
}

?>