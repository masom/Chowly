<?php
/**
 * Chowly Pick. Eat. Save!
 *
 * @copyright     Copyright 2011, Martin Samson <pyrolian@gmail.com>
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
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