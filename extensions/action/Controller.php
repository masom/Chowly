<?php
/**
 * Chowly Pick. Eat. Save!
 *
 * @copyright     Copyright 2011, Martin Samson <pyrolian@gmail.com>
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
namespace chowly\extensions\action;

use lithium\net\http\Router;
use lithium\core\Environment;
use lithium\template\View;
use lithium\storage\Session;
use chowly\models\Carts;
use chowly\models\GenericAnalytics;

class Controller extends \lithium\action\Controller{

	protected $Cart;
	protected $requestDate;

	protected function _init(){
		parent::_init();
		$isSsl = $this->request->is('ssl');
		$isSecured = in_array($this->request->controller, array('checkouts','users'));
		if ( $isSsl && !$isSecured){
			return $this->redirect(Router::match(
				$this->request->params,
				$this->request,
					array('absolute' => true, 'scheme'=>'http://')
				)
			);
		}

		//TODO Make this better
		if($this->request->admin){
			$user = \lithium\security\Auth::check('user', $this->request);
			if(!$user['role'] == 'admin'){
				return $this->redirect('/');
			}
		}

		$this->requestDate = new \MongoDate();

		$conditions = array('_id' => Session::read('cart.id'));
		$this->Cart = Carts::first(compact('conditions'));

		if (!$this->Cart){
			$this->Cart = Carts::create();
			$this->Cart->_id = new \MongoId();
			Session::write('cart.id', $this->Cart->_id);
		}

		if(!isset($this->request->params['admin'])){
			GenericAnalytics::log($this->Cart->_id, $this->request, $this->requestDate);
		}
	}

	/**
	 * Generates an email test
	 * @param array $data Information used by the templates
	 * @param var $template Template to be used
	 * @param var $controller	Folder containing the template (based on controller name).
	 * 							Defaults to the current controller name
	 * @throws ConfigException
	 * @return var The email text
	 */
	protected function _getEmail(array $data, $template = null, $controller = null){

		if (!$template){
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