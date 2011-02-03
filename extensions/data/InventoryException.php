<?php
namespace chowly\extensions\data;

class InventoryException extends \Exception{
	protected $_inventory_id;
	public function __construct($inventory_id = null, $message = null){
		$this->_inventory_id = $inventory_id;
		parent::__construct($message);
	}
	public function getInventoryId(){
		return $this->_inventory_id;
	}
}
?>