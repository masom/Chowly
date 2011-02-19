<?php
namespace chowly\controllers;

use chowly\models\Cart;

class CartsController extends \chowly\extensions\action\Controller{
	
	public function remove(){
		
		$data = array("cleared" => false, 'id' => $this->request->id);
		
		if(!$this->request->id){
			return $this->render(array('json' => $data));
		}
		
		if(!Cart::clear($this->request->id)){
			$data['cleared'] = true;
		}
		$this->render(array('json' => $data));
	}
}
?>