<?php
namespace chowly\controllers;

use chowly\models\Cart;

class CartsController extends \chowly\extensions\action\Controller{
	
	public function remove(){
		if(!$this->request->id){
			return;
		}
		
		if(!Cart::clear($this->request->id)){
			return true;
		}else{
			return false;
		}
	}
}
?>