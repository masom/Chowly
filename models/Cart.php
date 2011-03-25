<?php
namespace chowly\models;

class Carts extends \lithium\data\Model{
	
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
	 * @param var $offer_id
	 * @return bool
	 */
	public function add($entity, $offer_id, $inventory_id){
		$conditions = array('_id' => $entity->_id, 'state' => 'default');
		$data = array('$addToSet' => array('items' => $offer_id));
		$options = array('multiple' => false, 'safe' => true);
		return $entity->update($data, $conditions, $options);
	}
	
	public static function isEmpty($entity){
		return empty($entity->items);
	}
	
	public function clear($entity){
		$conditions = array('_id' => $entity->_id, 'state' => 'default');
		$data = array('$set' => array('items' => array()));
		$options = array('multiple' => false, 'safe' => true);
		return $entity->update($data, $conditions, $options);
	}
	public function remove($entity, $offer_id){
		$conditions = array('_id' => $entity->_id, 'state' => 'default');
		$data = array('$pull' => array('items' => $offer_id));
		$options = array('safe'=>true);
		return $entity->update($data, $conditions, $options);
	}
}
?>