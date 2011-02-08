<?php 
namespace chowly\extensions\action;

use \lithium\net\http\Router;
class Controller extends \lithium\action\Controller{
	
	protected function _init(){
		parent::_init();
		if($this->request->is('ssl') && !in_array($this->request->controller, array('checkouts','users'))){
			$this->redirect(Router::match(
				$this->request->params,
				$this->request,
				array('absolute' => true, 'scheme'=>'http://')
				)
			);
		}
		
	}
}
?>