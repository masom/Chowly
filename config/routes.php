<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use \lithium\net\http\Router;
use \lithium\core\Environment;
use chowly\models\Image;
use \lithium\action\Response;


Router::connect('/images/{:id:[0-9a-f]{24}}.(jpe?g|png|gif)', array(), function($request) {

	$image = Image::first($request->id);
	if(!$image || !$image->file){	
		header("Status: 404 Not Found");
		header("HTTP/1.0 404 Not Found");
		die;
	}
	return new Response(array(
		'headers' => array('Content-type' => $image->type),
		'body' => $image->file->getBytes()
	));
});

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'view', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.html.php)...
 */
Router::connect('/', array('Landings::pre'));

/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
Router::connect('/contact/{:args}', 'Tickets::add');
Router::connect('/pages/{:args}', 'Pages::view');

/**
 * Finally, connect the default routes.
 */
Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}.{:type}', array('id' => null));
Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}');
Router::connect('/{:controller}/{:action}/{:args}');


?>