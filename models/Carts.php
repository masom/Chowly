<?php
namespace chowly\models;

class Carts extends \lithium\data\Model{
	
	protected $_schema = array(
		'_id' => array('type'=>'id'),
		'items' => array('type'=>'id', 'array' => true)
	);
	public function endTransaction(){
		$command = $entity->_transaction('transaction', 'default');
		$result = static::connection()->connection->command($command);
		return !isset($result['errmsg']);
	}
	public function startTransaction($entity){
		$command = $entity->_transaction('default','transaction');
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
	private function _transaction($entity, $from = 'default', $to = 'transaction'){
		debug($entity);die;
		return array(
			'findAndModify' => $entity->_meta['source'], 
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
	 * Add a cart item
	 * @return bool
	 */
	public function addItem($entity, $offer_id, $inventory_id){
		$conditions = array('_id' => $entity->_id, 'state' => 'default');
		$data = array('$addToSet' => array('items' => $offer_id));
		$options = array('multiple' => false, 'safe' => true);
		return $entity->update($data, $conditions, $options);
	}
	
	public static function isEmpty($entity){
		return empty($entity->items);
	}
	
	public function clearItems($entity){
		$conditions = array('_id' => $entity->_id, 'state' => 'default');
		$data = array('$set' => array('items' => array()));
		$options = array('multiple' => false, 'safe' => true);
		return $entity->update($data, $conditions, $options);
	}
	public function removeItem($entity, $offer_id){
		$conditions = array('_id' => $entity->_id, 'state' => 'default');
		$data = array('$pull' => array('items' => $offer_id));
		$options = array('safe'=>true);
		return $entity->update($data, $conditions, $options);
	}
	public function containItem($entity, $offer_id){
		return $entity->items->first(function($i) use ($offer_id) { return (string) $i->_id == $offer_id; });
	}
	public function isReadOnly($entity){
		return ($entity->state == "default");
	}
}
?>