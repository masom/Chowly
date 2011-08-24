<?php
/**
 * Chowly Pick. Eat. Save!
 *
 * @copyright     Copyright 2011, Martin Samson <pyrolian@gmail.com>
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
namespace chowly\tests\mocks;

class MockCarts extends \chowly\models\Carts{
	public $_meta = array('connection' => 'test');

	public function _mockNewItem($entity, $offer_id, $inventory_id, $expires){
		return parent::_newItem($offer_id, $inventory_id, $expires);
	}
	
}

?>