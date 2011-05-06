<?php
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\models;

/**
Cleanup:
db.carts.update({state: "default"},
	{$pull : { items : { expires : { $lt : 1301520008} } } },
	{ multi: true, safe: true}
);
 */
class Carts extends \lithium\data\Model{

	/**
	 * Holds MongoDb Schema definition
	 * @var array
	 */
	protected $_schema = array(
		'_id' => array('type'=>'id'),
		'items' => array('type'=>'object', 'array' => true),
		'state' => array('type' => 'string', 'default' => 'default')
	);

	public function items($entity){
		return $entity->items;
	}

	/**
	 * End a transaction on a cart.
	 * @param Entity $entity
	 * @return var A Carts entity -or- null if the transaction could not be ended.
	 */
	public function endTransaction($entity){
		$command = $this->_transaction($entity, 'transaction', 'default');
		$result = static::connection()->connection->command($command);
		return !isset($result['errmsg']);
	}

	/**
	 * Start a transaction on a cart.
	 * @param Entity $entity
	 * @return var A Carts entity -or- null if the transaction could not be started.
	 */
	public function startTransaction($entity){
		$command = $this->_transaction($entity, 'default','transaction');
		$result = static::connection()->connection->command($command);
		return !isset($result['errmsg']);
	}

	/**
	 * Wraps findAndModify mongodb command for transaction status handling.
	 * @param Model $entity
	 * @param var $from
	 * @param var $to
	 * @return var The mongodb command structure.
	 */
	protected function _transaction($entity, $from = 'default', $to = 'transaction'){
		return array(
			'findAndModify' => static::meta('source'),
			'query' => array( 
				'_id' => $entity->_id,
				'state' => $from
			),
			'update'=> array(
				'$set' => array(
					'state'=> $to 
				) 
			)
		);
	}

	/**
	 * Cart Item stub
	 * @param var $offer_id Offer reference
	 * @param var $inventory_id Inventory item
	 * @param var $expires Item expiration date as a UNIX timestamp
	 */
	protected function _newItem($offer_id, $inventory_id, $expires){
		return array(
			'_id' => $offer_id,
			'inventory_id' => $inventory_id,
			'expires' => $expires
		);
	}

	/**
	 * Add a cart item
	 * @param Object $entity
	 * @param MongoId $offer_id
	 * @param MongoId $inventory_id
	 * @return boolean
	 */
	public function addItem($entity, $offer_id, $inventory_id){
		if ($this->isReadOnly($entity)){
			return false;
		}

		$expires = time() + 15 * 60;
		$conditions = array('_id' => $entity->_id, 'state' => 'default');
		$data = array('$addToSet' => array(
			'items' => $this->_newItem($offer_id, $inventory_id, $expires)
		));

		$options = array('multiple' => false, 'safe' => true, 'upsert'=>true);
		return static::update($data, $conditions, $options);
	}

	public static function isEmpty($entity){
		return !(count($entity->items));
	}

	public function clearItems($entity){
		if ($this->isReadOnly($entity)){
			return false;
		}

		$conditions = array('_id' => $entity->_id, 'state' => 'default');
		$data = array('$set' => array('items' => array()));
		$options = array('multiple' => false, 'safe' => true);
		return static::update($data, $conditions, $options);
	}

	public function removeItem($entity, $offer_id){
		if ($this->isReadOnly($entity)){
			return false;
		}

		$conditions = array('_id' => $entity->_id, 'state' => 'default');
		$data = array('$pull' => array(
			'items' => array(
				'_id'=> new \MongoId($offer_id)
			)
		));
		$options = array('multiple' => false, 'safe' => true);
		return static::update($data, $conditions, $options);
	}

	public function containItem($entity, $offer_id){
		return $entity->items->first(function($i) use ($offer_id) { return $i->_id == $offer_id; });
	}

	public function isReadOnly($entity){
		return ($entity->state != "default");
	}
}

?>