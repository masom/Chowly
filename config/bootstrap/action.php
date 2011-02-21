<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * This file contains a series of method filters that allow you to intercept different parts of
 * Lithium's dispatch cycle. The filters below are used for on-demand loading of routing
 * configuration, and automatically configuring the correct environment in which the application
 * runs.
 *
 * For more information on in the filters system, see `lithium\util\collection\Filters`.
 *
 * @see lithium\util\collection\Filters
 */

use lithium\core\Libraries;
use lithium\net\http\Router;
use lithium\core\Environment;
use lithium\action\Dispatcher;
use lithium\util\collection\Filters;
use \lithium\util\Validator;
use \lithium\storage\Session;
/**
 * This filter intercepts the `run()` method of the `Dispatcher`, and first passes the `'request'`
 * parameter (an instance of the `Request` object) to the `Environment` class to detect which
 * environment the application is running in. Then, loads all application routes in all plugins,
 * loading the default application routes last.
 *
 * Change this code if plugin routes must be loaded in a specific order (i.e. not the same order as
 * the plugins are added in your bootstrap configuration), or if application routes must be loaded
 * first (in which case the default catch-all routes should be removed).
 *
 * If `Dispatcher::run()` is called multiple times in the course of a single request, change the
 * `include`s to `include_once`.
 *
 * @see lithium\action\Request
 * @see lithium\core\Environment
 * @see lithium\net\http\Router
 */
Dispatcher::applyFilter('run', function($self, $params, $chain) {
	Environment::set($params['request']);

	foreach (array_reverse(Libraries::get()) as $name => $config) {
		if ($name === 'lithium') {
			continue;
		}
		$file = "{$config['path']}/config/routes.php";
		file_exists($file) ? include $file : null;
	}
	return $chain->next($self, $params, $chain);
});

Dispatcher::config(array(
    'rules' => array('admin' => array('action' => 'admin_{:action}'))
));

Validator::add('zip', function($value){
	return preg_match('/^[ABCEGHJKLMNPRSTVXY]\d[A-Z]\s?\d[A-Z]\d$/i',$value);
});

/**
 * Add the default state to the document.
 * static::defaultState() must be defined.
 */
$insureDefaultState = function($self, $params, $chain){
	$states = $self::states();
	if(!in_array($params['entity']->state, $states)){
		$params['entity']->state = $self::defaultState();
	}
	return $chain->next($self, $params, $chain);
};

Filters::apply('chowly\models\Venue', 'save', $insureDefaultState);
Filters::apply('chowly\models\Inventory', 'save', $insureDefaultState );
Filters::apply('chowly\models\Offer', 'save', $insureDefaultState);

/**
// Allow access  if user is the owner
Access::adapter('minerva_access')->add('allowIfOwner', function($user, $request, $options) {
   
   if(($user) && (isset($options['document']))) {
	  // if owner_id matches user id
	  if(isset($options['document']['owner_id'])) {
		 if($options['document']['owner_id'] === $user['_id']) {
			return true;
		 }
	  }
	  // or if the document id IS the user's document id (it's the user him/herself - for updating your own record)
	  if(isset($options['document']['_id'])) {
		 if($options['document']['_id'] === $user['_id']) {
			return true;
		 }
	  }
	  
   }
   return false;
});
 */
?>