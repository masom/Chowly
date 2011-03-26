<?php
namespace chowly\models;

class Carts extends \lithium\data\Model{
	
	protected $_schema = array(
		'_id' => array('type'=>'id'),
		'items' => array('type'=>'id', 'array' => true)
	);
	public function endTransaction(){
		$command = $this->_transaction($entity, 'transaction', 'default');
		$result = static::connection()->connection->command($command);
		return !isset($result['errmsg']);
	}
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
	private function _transaction($entity, $from = 'default', $to = 'transaction'){
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
	 * Add a cart item
	 * @return bool
	 */
	public function addItem($entity, $offer_id, $inventory_id){
		if($this->isReadOnly($entity)){
			return false;
		}
		
		$expires = time() + 15 * 60;
		$conditions = array('_id' => $entity->_id, 'state' => 'default');
		$data = array('$addToSet' => array('items' => array('_id'=> new \MongoId($offer_id), 'inventory_id'=>$inventory_id, 'expires'=> $expires)));
		$options = array('multiple' => false, 'safe' => true, 'upsert'=>true);
		return static::update($data, $conditions, $options);
	}
	
	public static function isEmpty($entity){
		return !(count($entity->items));
	}
	
	public function clearItems($entity){
			if($this->isReadOnly($entity)){
			return false;
		}
		
		$conditions = array('_id' => $entity->_id, 'state' => 'default');
		$data = array('$set' => array('items' => array()));
		$options = array('multiple' => false, 'safe' => true);
		return static::update($data, $conditions, $options);
	}
	
	public function removeItem($entity, $offer_id){
		if($this->isReadOnly($entity)){
			return false;
		}
		
		$conditions = array('_id' => $entity->_id, 'state' => 'default');
		$data = array('$pull' => array('items' => $offer_id));
		$options = array('safe'=>true);
		return static::update($data, $conditions, $options);
	}
	
	public function containItem($entity, $offer_id){
		return $entity->items->first(function($i) use ($offer_id) { return $i->_id == $offer_id; });
	}
	
	public function isReadOnly($entity){
		return ($entity->state == "default");
	}
}
?>