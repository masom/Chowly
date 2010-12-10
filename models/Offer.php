<?php
namespace chowly\models;

use \lithium\data\collection\DocumentSet;

class Offer extends \lithium\data\Model{
	public static function current(){
		$command = array(
			'distinct' => 'offers',
			'key' => 'offer_id',
			array(
				'state' => 'available',
				'starts' => array('$lt' => new \MongoDate()),
				'ends' => array('$gt' => new \MongoDate())
			)
		);
		
		$result = static::_connection()->connection->command($command);
		var_dump($result);die;
		
		$conditions = array();
		if($result && isset($result['ok']) && $result['ok']){
			return new DocumentSet(array('data' => $result['values'],'model' => 'chowly\models\Offer'));
		}else{
			return false;
		}
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
			'findAndModify' => 'offers', 
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
		if($result){
			return $result;
		}else{
			return false;
		}
		
	}
}
?>