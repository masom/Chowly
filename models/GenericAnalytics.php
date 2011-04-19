<?php
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\models;

class GenericAnalytics extends \lithium\data\Model{

	protected $_meta = array('connection'=>'analytics');

	/**
	 * Logs every request made to chowly.
	 * @param var $cart_id The cart id we are logging data from
	 * @param object $request The request object
	 * @param object &$requestDate The current date for the request
	 */
	public static function log($cart_id, &$request, &$requestDate){
		$analytics = static::create();
		$analytics->created = $requestDate;
		$analytics->cart_id = $cart_id;
		$analytics->url = $request->url;
		$analytics->params = $request->params;
		$analytics->ip_address = $request->env('REMOTE_ADDR');
		$analytics->referer = $request->env('HTTP_REFERER');
		$analytics->server = $request->env('SERVER_ADDR');
		$analytics->method = $request->env('REQUEST_METHOD');
		$analytics->save(null, array('safe'=>false, 'fsync'=>false)); // We do not care about loosing analytics data
	}
}

?>