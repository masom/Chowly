<?php 
namespace chowly\extensions\action;

use \lithium\net\http\Router;
use \lithium\template\View;

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
		$this->Cart = Carts::first(array('_id' => session_id()));
		if(!$this->Cart){
			$this->Cart = Carts::create();
			$this->Cart->_id = new \MongoId();
		}
	}
	protected function _getEmail(array $data, $template = null, $controller = null){
		
		if(!$template){
			throw new \lithium\core\ConfigException("Undefined email template");
		}
		
		$view  = new View(array(
		    'paths' => array(
		        'template' => '{:library}/views/{:controller}/{:template}.{:type}.php'
		    )
		));
		
		$controller = ($controller)? $controller : $this->request->params['controller'];
		
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