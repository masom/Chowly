<?php

namespace chowly\controllers;

class PagesController extends \lithium\action\Controller {

	public function view() {
		$path = func_get_args();

		if (empty($path)) {
			$path = array('about');
		}
		$this->render(array('template' => join('/', $path)));
	}
}

?>