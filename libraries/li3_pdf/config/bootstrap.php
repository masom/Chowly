<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD, Inc. (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\core\Libraries;
use lithium\core\ConfigException;

try {
	if (!Libraries::get('tcpdf')) {
		Libraries::add('tcpdf', array('bootstrap' => false));
	}
} catch (ConfigException $e) {
	return;
}
?>