<?php
namespace chowly\models;

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
		return 'unpublished';
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
					'state'=> 'taken',
					'customer_id' => $customer_id,
					'expires' => new \MongoDate(time() + 15 * 60) // 15 minutes to buy the offer
				)
			)
		);
		
		$result = static::_connection()->connection->command($command);
		if(!$result || !isset($result['ok'])){
			return false;
		}
		$inventory = new \lithium\data\entity\Document();
		$inventory->set($result['value']);
		
		return array($result['ok'], $inventory);
	}
	public static function createForOffer($offer_id){
		$inventory = static::create();
		$inventory->state = 'available';
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