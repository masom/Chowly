<?php
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\controllers;

class PagesController extends \chowly\extensions\action\Controller {

	public function view() {
		$path = func_get_args();

		if (empty($path)) {
			$path = array('about');
		}
		$this->render(array('template' => join('/', $path)));
	}
}

?>