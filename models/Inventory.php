<?php
namespace chowly\models;

use chowly\extensions\data\InventoryException;

class Inventory extends \lithium\data\Model{
	protected $_schema = array(
		'_id' => array('type'=>'id'),
		'offer_id' => array('type'=>'id'),
		'customer_id' => array('type'=>'_id'),
		'state' => array('type'=>'string', 'default' => 'available'),
	);
	
	protected static $_states = array('available'=>'available', 'reserved'=>'reserved', 'purchased'=>'purchased');
	
	public static function states(){
		return static::$_states;
	}
	
	public static function defaultState(){
		return 'available';
	}
	
	public static function release($customer_id, $offer_id){
		$command = array(
			'findAndModify' => 'inventories', 
			'query' => array(
				'offer_id' => new \MongoId($offer_id),
				'state' => 'reserved'
			), 
			'update'=> array(
				'$set' => array(
					'state'=> 'available',
					'customer_id' => null
				)
			)
		);
		
		$result = static::_connection()->connection->command($command);
		
		if(isset($result['errmsg'])){
			throw new InventoryException($result['errmsg']);
		}

		$inventory = new \lithium\data\entity\Document();
		$inventory->set($result['value']);

		return $inventory;
	}
	/**
	 * 
	 * Reserve a inventory item for a limited duration.
	 * @param var $customer_id
	 * @param var $offer_id
	 * @todo Add indexes to inventory
	 */
	public static function reserve($customer_id, $offer_id){
		$command = array(
			'findAndModify' => 'inventories', 
			'query' => array(
				'offer_id' => new \MongoId($offer_id),
				'state' => 'available'
			), 
			'update'=> array(
				'$set' => array(
					'state'=> 'reserved',
					'customer_id' => $customer_id,
					'expires' => new \MongoDate(time() + 20 * 60) // 15 minutes to buy the offer at the UI, 5 minutes buffer
				)
			)
		);
		
		$result = static::_connection()->connection->command($command);
		
		if(isset($result['errmsg'])){
			throw new InventoryException($result['errmsg']);
		}

		$inventory = new \lithium\data\entity\Document();
		$inventory->set($result['value']);

		return $inventory;
	}
	public static function secure($inventory_id){
		$command = array(
			'findAndModify' => 'inventories', 
			'query' => array(
				'_id' => $inventory_id,
			), 
			'update'=> array(
				'$set' => array(
					'expires' => new \MongoDate(time() + 15 * 60) //15 minutes of "security"
				)
			)
		);
		
		$result = static::_connection()->connection->command($command);
		if(isset($result['errmsg'])){
			throw new InventoryException($result['errmsg']);
		}		
		return true;
	}
	public static function purchase($inventory_id){
		$command = array(
			'findAndModify' => 'inventories', 
			'query' => array(
				'_id' => new \MongoId($inventory_id),
			), 
			'update'=> array(
				'$set' => array(
					'state' => 'purchased'
				)
			)
		);
		
		$result = static::_connection()->connection->command($command);
		if(isset($result['errmsg'])){
			
			throw new InventoryException($result['errmsg']);
		}
		return true;
	}
	public static function createForOffer($offer_id){
		$inventory = static::create();
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