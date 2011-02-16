<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use \lithium\net\http\Router;
use \lithium\core\Environment;
use \lithium\storage\Session;

Router::connect('/images/{:id:[0-9a-f]{24}}.(jpe?g|png|gif)', array(), function($request) {

	$image = chowly\models\Image::first($request->id);
	if(!$image || !$image->file){	
		header("Status: 404 Not Found");
		header("HTTP/1.0 404 Not Found");
		die;
	}
	return new \lithium\action\Response(array(
		'headers' => array('Content-type' => $image->type),
		'body' => $image->file->getBytes()
	));
});

/**
 * Connect the testing routes.
 */
if (!Environment::is('production')) {
	Router::connect('/test/{:args}', array('controller' => 'lithium\test\Controller'));
	Router::connect('/test', array('controller' => 'lithium\test\Controller'));
}

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'view', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.html.php)...
 */
Router::connect('/', 'Offers::index');

Router::connect('/login', 'Users::login');
Router::connect('/logout', 'Users::logout');
Router::connect('/register', 'Users::add');

//TODO: add only when auth
if(Session::read('user')){
	Router::connect('/profile', 'Users::view');
	Router::connect('/settings', 'Users::edit');
	
	if(Session::read('user.role') == 'admin'){
		Router::connect('/admin/{:controller}/{:action}/page:{:page:[0-9]+}', array('admin' => true), array('persist' => array('controller')));
		Router::connect('/admin/{:controller}/{:action}/{:id:[0-9a-f]{24}}.{:type}', array('id' => null, 'admin' => true), array('persist' => array('controller')));
		Router::connect('/admin/{:controller}/{:action}/{:id:[0-9a-f]{24}}', array('admin' => true), array('persist' => array('controller')));
		Router::connect('/admin/{:controller}/{:action}/{:args}', array('admin' => true), array('persist' => array('controller')));
	}
	if(in_array(Session::read('user.role'), array('admin','venue','staff'))){
		Router::connect('/offers/add/{:id:[0-9a-f]{24}}', array('controller'=>'Offers','action'=>'add'));
		Router::connect('/offers/publish/{:id:[0-9a-f]{24}}', 'Offers::publish');
		Router::connect('/offers/unpublish/{:id:[0-9a-f]{24}}', 'Offers::unpublish');
	}
}

/**
 * Finally, connect the default routes.
 
Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}.{:type}', array('id' => null));
Router::connect('/{:controller}/{:action}/{:id:[0-9a-f]{24}}');
Router::connect('/{:controller}/{:action}/{:args}');

*/
Router::connect('/offers/{:id:[0-9a-f]{24}}', 'Offers::view');
Router::connect('/offers/buy/{:id:[0-9a-f]{24}}', 'Offers::buy');

Router::connect('/venues', 'Venues::index');
Router::connect('/venues/{:id:[0-9a-f]{24}}', 'Venues::view');

Router::connect('/confirm', 'Checkouts::confirm');
Router::connect('/checkout', 'Checkouts::checkout');

Router::connect('/contact/{:args}', 'Tickets::add');

//TODO: Move these to admin auth only




Router::connect('/venues/edit/{:id:[0-9a-f]{24}}', 'Venues::edit');
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */


Router::connect('/{:args}', 'Pages::view');
?>