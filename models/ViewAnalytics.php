<?php
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\models;

use \MongoCode;

class ViewAnalytics extends \lithium\data\Model{

	protected $_meta = array('connection'=>'analytics');

	/**
	 * Logs page views.
	 * @param var $cart_id The cart id we are logging data from
	 * @param var $page_id The page id to log the view
	 * @param object $request The request object
	 * @param object &$requestDate The current date for the request
	 */
	public static function log($cart_id, $offer_id, $request, $requestDate){
		$analytics = static::create();
		$analytics->created = $requestDate;
		$analytics->cart_id = $cart_id;
		$analytics->offer_id = $offer_id;
		$analytics->ip_address = $request->env('REMOTE_ADDR');
		$analytics->referer = $request->env('HTTP_REFERER');
		$analytics->server = $request->env('SERVER_ADDR');
		$analytics->save(null, array('safe'=>false, 'fsync'=>false)); // We do not care about loosing analytics data
	}

	public static function mostViewed(){
		$map = new MongoCode("function() { emit(this.user_id,1); }");
		$reduce = new MongoCode("function(k, vals) {
			var sum = 0;
			for (var i in vals){
				sum += vals[i];
			}
			return sum;
		");
		$out = array("merge" => "eventCounts");
		$visited = static::mapReduce($map, $reduce, $out);
		$users = $db->selectCollection($sales['result'])->find();
		debug($users);die;
	}
}

?>