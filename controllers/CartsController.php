<?php
namespace chowly\controllers;

use chowly\models\Carts;
use chowly\models\Offers;

class CartsController extends \chowly\extensions\action\Controller{
	
	public function remove(){
		
		$data = array("cleared" => false, 'id' => $this->request->id);
		
		if(!$this->request->id){
			return $this->render(array('json' => $data));
		}
		
		if($this->Cart->removeItem($this->request->id)){
			$data['cleared'] = true;
			Offers::releaseInventory($this->request->id, $this->Cart->_id);
		}
		$this->render(array('json' => $data));
	}
}
?>