<?php
/**
 * Chowly Pick. Eat. Save!
 *
 * @copyright     Copyright 2011, Martin Samson <pyrolian@gmail.com>
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
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