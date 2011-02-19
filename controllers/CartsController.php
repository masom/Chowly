<?php
namespace chowly\controllers;

use chowly\models\Cart;
use chowly\models\Offer;
use \lithium\analysis\Logger;
class CartsController extends \chowly\extensions\action\Controller{
	
	public function remove(){
		
		$data = array("cleared" => false, 'id' => $this->request->id);
		
		if(!$this->request->id){
			return $this->render(array('json' => $data));
		}
		
		if(Cart::clear($this->request->id)){
			$data['cleared'] = true;
			Offer::releaseInventory($this->request->id);
		}
		$this->render(array('json' => $data));
	}
}
?>