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
Router::connect('/', array('Offers::index'));



/**
 * Connect the testing routes.
 */
if (!Environment::is('production')) {
	Router::connect('/test/{:args}', array('controller' => 'lithium\test\Controller'));
	Router::connect('/test', array('controller' => 'lithium\test\Controller'));
}

/**
 * Finally, connect the default routes.
*/ 
Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}.{:type}', array('id' => null));
Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}');
Router::connect('/{:controller}/{:action}/{:args}');
/**
Router::connect('/offers/{:id:[0-9a-f]{24}}', array('Offers::view'));
Router::connect('/offers/buy/{:id:[0-9a-f]{24}}', array('Offers::buy'));

Router::connect('/venues', array('Venues::index'));
Router::connect('/venues/{:id:[0-9a-f]{24}}', array('Venues::view'));

Router::connect('/confirm', array('Checkouts::confirm'));
Router::connect('/checkout', array('Checkouts::checkout'));//controller'=>'Checkouts','action'=>'checkout'));

Router::connect('/contact/{:args}', array('Tickets::add'));


//ADMIN
//TODO: Move these to user auth only
Router::connect('/offers/add/{:id:[0-9a-f]{24}}', array('controller'=>'Offers','action'=>'add'));
Router::connect('/offers/publish/{:id:[0-9a-f]{24}}', array('controller'=>'Offers','action'=>'publish'));
Router::connect('/offers/unpublish/{:id:[0-9a-f]{24}}', array('controller'=>'Offers','action'=>'unpublish'));

Router::connect('/venues/add', array('controller'=>'Venues','action'=>'add'));
Router::connect('/venues/publish', array('controller'=>'Venues','action'=>'publish'));
Router::connect('/venues/unpublish', array('controller'=>'Venues','action'=>'unpublish'));
Router::connect('/venues/edit/{:id:[0-9a-f]{24}}', array('controller'=>'Venues','action'=>'edit'));
*/
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */


Router::connect('/{:args}', 'Pages::view');
?>