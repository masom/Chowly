<?php
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\controllers;

use chowly\models\ViewAnalytics;
use chowly\models\GenericAnalytics;
use li3_flash_message\extensions\storage\FlashMessage;
/**
 * Handles chowly's analytics.
 * @author masom
 */
class AnalyticsController extends \chowly\extensions\action\Controller{

	protected $_typeMap = array(
		'generic' => 'chowly\models\GenericAnalytics',
		'page' => 'chowly\models\ViewAnalytics'
	);

	protected $_types = array('generic' => 'Global', 'page' => 'Page Specific');

	public function admin_index(){
		$types = $this->_types;
		return compact('types');
	}

	public function admin_view(){

		if (!isset($this->_typeMap[$this->request->class])){
			FlashMessage::set('Unhandled analytic type.');
			return $this->redirect($this->request->referer());
		}

		$analytic = $this->_typeMap[$this->request->class];
		$order = array('_id' => 'DESC');
		$limit = 5;
		$analytics = $analytic::all(compact('order', 'limit'));

		$this->_render['template'] = "admin_view_{$this->request->class}";

		$mostViewedDetails = $analytic::mostViewedDetails(10);
		return compact('analytics');
	}

	public function admin_ip(){
		if (!$type || !$ip){
			FlashMessage::set('Missing IP to analyze.');
			return $this->redirect($this->request->referer());
		}

		$analytic = $this->_typeMap[$type];
		$order = array('_id' => 'DESC');
		$limit = 5;
		$latests = $analytic::all(compact('order', 'limit'));
	}
}

?>