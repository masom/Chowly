<?php
/**
 * Chowly Pick. Eat. Save!
 *
 * @copyright     Copyright 2011, Martin Samson <pyrolian@gmail.com>
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
namespace chowly\controllers;

use chowly\models\Offers;

class CartsController extends \chowly\extensions\action\Controller{

	public function remove(){

		$data = array("cleared" => false, 'id' => $this->request->id);
		if (!$this->request->id){
			return $this->render(array('json' => $data));
		}
		if ($this->Cart->removeItem($this->request->id)){
			$data['cleared'] = true;
			Offers::releaseInventory($this->Cart->_id, $this->request->id);
		}
		$this->render(array('json' => $data));
	}
}

?>