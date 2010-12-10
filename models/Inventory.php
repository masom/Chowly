<?php
namespace chowly\models;

class Inventory extends \lithium\data\Model{
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
					'state'=> 'reserved',
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