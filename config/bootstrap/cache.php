<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * This file creates a default cache configuration using the most optimized adapter available, and
 * uses it to provide default caching for high-overhead operations.
 */
use lithium\storage\Cache;
use lithium\core\Libraries;
use lithium\action\Dispatcher;
use lithium\storage\cache\adapter\Apc;

if (PHP_SAPI === 'cli') {
	return;
}
$default = array(
	'adapter' => 'lithium\storage\cache\adapter\File',
	'strategies' => array('Serializer')
);

Cache::config(compact('default'));

Dispatcher::applyFilter('run', function($self, $params, $chain) {
	if ($cache = Cache::read('default', 'core.libraries')) {
		$cache = (array) $cache + Libraries::cache();
		Libraries::cache($cache);
	}
	$result = $chain->next($self, $params, $chain);

	if ($cache != Libraries::cache()) {
		Cache::write('default', 'core.libraries', Libraries::cache(), '+1 day');
	}
	return $result;
});

?>