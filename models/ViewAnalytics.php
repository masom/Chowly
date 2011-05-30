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

	/**
	 * Returns the top-most viewed pages.
	 * @param var $limit The amount of items to return. Default to 10. If set to null, returns all page views.
	 */
	public static function mostViewed($limit = 10){
		$db = static::connection();
		$map = new MongoCode("function(){ 
			var doc = {
				'views' : 1,
				'ip_addresses' : {}
			};
			doc.ip_addresses[this.ip_address] = 1;
			emit(this.offer_id, doc);
		}");
		$reduce = new MongoCode("function(k, vals) {
			var views = 0;
			var ip_addresses = {};
			vals.forEach(function(doc){
				views += doc.views;
				for (var i in doc.ip_addresses){
					if(typeof(ip_addresses[i]) == 'undefined'){
						ip_addresses[i] = 0;
					}
					ip_addresses[i] += doc.ip_addresses[i];
				}
			});
			return {'views' : views, 'ip_addresses' : ip_addresses};
		}");
		$out = array("merge" => "viewMetrics");
		$mapreduce = 'view_analytics';

		$metrics = $db->connection->command(compact('mapreduce','map','reduce','out'));
		if (!$metrics['ok']){
			return false;
		}

		$cursor = $db->connection->selectCollection($metrics['result'])->find();
		$cursor->sort(array('views' => 1));

		if ($limit){
			$cursor->limit($limit);
		}

		$viewCounts = array();
		foreach ($cursor as $doc){
			$viewCounts[(string)$doc['_id']] = $doc['value'];
		}

		return $viewCounts;
	}

	/**
	 * Returns the most viewed items and their details
	 * @param var $limit Number of items to be returned from the most viewed to less. If set to null, returns all items having a view count
	 */
	public static function mostViewedDetails($limit = 10){
		$mostViewed = static::mostViewed(10);

		$conditions = array('_id' => array_keys($mostViewed));
		$items = \chowly\models\Offers::all(compact('conditions'));

		return compact('items', 'mostViewed');
	}
}

?>