<?php 
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\extensions\action;

use \lithium\net\http\Router;
use \lithium\template\View;
use \lithium\storage\Session;
use chowly\models\Carts;

class Controller extends \lithium\action\Controller{
	
	protected $Cart;
	
	protected function _init(){
		parent::_init();
		if($this->request->is('ssl') && !in_array($this->request->controller, array('checkouts','users'))){
			return $this->redirect(Router::match(
				$this->request->params,
				$this->request,
					array('absolute' => true, 'scheme'=>'http://')
				)
			);
		}
		
		$conditions = array('_id' => Session::read('cart.id'));
		$this->Cart = Carts::first(compact('conditions'));

		if(!$this->Cart){
			$this->Cart = Carts::create();
			$this->Cart->_id = new \MongoId();
			Session::write('cart.id', $this->Cart->_id);
		}

	}
	/**
	 * Generates an email test
	 * @param array $data Information used by the templates
	 * @param var $template Template to be used
	 * @param var $controller Folder containing the template (based on controller name). Defaults to the current controller name
	 * @throws \lithium\core\ConfigException
	 * @return var The email text
	 */
	protected function _getEmail(array $data, $template = null, $controller = null){
		
		if(!$template){
			throw new \lithium\core\ConfigException("Undefined email template");
		}
		
		$view  = new View(array(
		    'paths' => array(
		        'template' => '{:library}/views/{:controller}/{:template}.{:type}.php'
		    )
		));
		
		$controller = $controller ?: $this->request->params['controller'];
		
		return $view->render(
		    'template',
		    $data,
		    array(
		        'controller' => $controller,
		        'template'=> $template,
		        'type' => 'mail',
		        'layout' => false
		    )
		);
	}
}
?>