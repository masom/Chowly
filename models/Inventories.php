<?php
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\models;

use chowly\extensions\data\InventoryException;

class Inventories extends \chowly\extensions\data\Model{
	protected $_schema = array(
		'_id' => array('type' => 'id'),
		'offer_id' => array('type' => 'id'),
		'customer_id' => array('type' => 'id'),
		'state' => array('type' => 'string', 'default' => 'available'),
		'expires' => array('type' => 'date')
	);

	protected static $_states = array(
		'available'=>'available',
		'reserved'=>'reserved',
		'purchased'=>'purchased'
	);

	public static function states(){
		return static::$_states;
	}

	public static function defaultState(){
		return 'available';
	}

	public static function getAvailable(){
		$conditions = array('state'=>'available');
		return static::all(compact('conditions'));
	}

	public static function releaseExpired(){
		$data = array('$set'=> array('state'=>'available', 'expires'=>null, 'purchase_id'=> null));
		$conditions = array('state'=> 'reserved',
			'expires'=> array('$lt' => new \MongoDate())
		);
		return static::update( $data , $conditions);
	}

	/**
	 * Releases inventory for a given customer and offer
	 * @param var $customer_id
	 * @param var $offer_id
	 * @throws InventoryException
	 * @return object Inventory instance
	 */
	public static function release($customer_id, $offer_id){
		$command = array(
			'findAndModify' => static::meta('source'),
			'query' => array(
				'customer_id' => $customer_id, 'offer_id' => $offer_id, 'state' => 'reserved'
			),
			'update'=> array( '$set' => array(
					'state'=> 'available', 'customer_id' => null, 'expires' => null
				)
			)
		);

		$result = static::connection()->connection->command($command);

		if (isset($result['errmsg'])){
			throw new InventoryException(null, $result['errmsg']);
		}

		$inventory = new \lithium\data\entity\Document();
		$inventory->set($result['value']);
		return $inventory;
	}

	/**
	 * Reserve a inventory item for a limited duration.
	 * @param var $offer_id
	 * @param var $customer_id
	 * @todo Add indexes to inventory
	 */
	public static function reserve($offer_id, $customer_id){
		$command = array(
			'findAndModify' => static::meta('source'),
			'query' => array(
				'offer_id' => $offer_id, 'state' => 'available'
			),
			'update'=> array('$set' => array(
					'state'=> 'reserved',
					'customer_id' => $customer_id,
					//15 minutes to buy the offer at the UI, 5 minutes buffer
					'expires' => new \MongoDate(time() + 20 * 60)
				)
			)
		);
		$result = static::connection()->connection->command($command);

		if (isset($result['errmsg'])){
			throw new InventoryException(null,$result['errmsg']);
		}

		$inventory = new \lithium\data\entity\Document();
		$inventory->set($result['value']);

		return $inventory;
	}

	public static function secure($inventory_id){
		$command = array(
			'findAndModify' => static::meta('source'),
			'query' => array( '_id' => $inventory_id ),
			'update'=> array( '$set' => array(
					'expires' => new \MongoDate(time() + 15 * 60) //15 minutes of "security"
				)
			)
		);

		$result = static::connection()->connection->command($command);
		if (isset($result['errmsg'])){
			throw new InventoryException($inventory_id, $result['errmsg']);
		}
		return true;
	}

	public static function purchase($purchase_id, $inventory_id){
		$command = array(
			'findAndModify' => static::meta('source'),
			'query' => array( '_id' => $inventory_id ),
			'update'=> array( '$set' => array(
					'state' => 'purchased', 'purchase_id' => $purchase_id
				)
			)
		);

		$result = static::connection()->connection->command($command);
		if (isset($result['errmsg'])){
			throw new InventoryException($inventory_id, $result['errmsg']);
		}
		return true;
	}

	/**
	 * Create a inventory item for a offer
	 * @param var $offer_id
	 * @param numeric $sequence_number
	 * @return boolean
	 */
	public static function createForOffer($offer_id, $sequence_number = null){

		$inventory = static::create();

		//Need to do type checking to differentiate 0 and null
		if ($sequence_number !== null){
			$inventory->sequence_number = $sequence_number;
		}

		$inventory->state = static::defaultState();
		$inventory->offer_id = $offer_id;
		return $inventory->save();
	}

	public static function deleteForOffer($offer_id){
		$conditions = array( 
			'offer_id' => $offer_id,
			'state' => array('available', 'reserved')
		);
		return static::remove($conditions);
	}
}

?>