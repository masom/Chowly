<?php
namespace chowly\tests\cases\models;

use chowly\models\Inventory;
use \lithium\data\entity\Document;

class InventoryTest extends \lithium\test\Unit{

	public function setUp() {
		Inventory::config(array('connection' => 'test'));
	}

	public function tearDown() {
		Inventory::remove();
	}

	public function testCreate() {
		$inventory = Inventory::create();
		$this->assertTrue($inventory instanceof Document);
	}
	
	public function testDefaultState(){
		$this->assertTrue(in_array(Inventory::defaultState(), Inventory::states()));
	}
	
	public function testSave(){
		$inventory = Inventory::create();
		$this->assertTrue($inventory->save());
		
		$conditions = array('_id' => $inventory->_id);
		$saved = Inventory::find('first', compact('conditions'));
		$this->assertEqual($inventory->to('array'), $saved->to('array'));
	}
	
	public function testReserve(){
		$inventory = Inventory::create();
		$inventory->offer_id = new \MongoId();
		$this->assertTrue($inventory->save());
		
		$this->assertTrue(Inventory::reserve(new \MongoId(), $inventory->offer_id) instanceof Document);
		
		$offer_id = new \MongoId();
		$this->expectException();
		Inventory::reserve(new \MongoId(), $offer_id);
	}
	
	public function testSecure(){
		$inventory = Inventory::create();
		$inventory->offer_id = new \MongoId();
		$this->assertTrue($inventory->save());
		
		$this->assertTrue(Inventory::secure($inventory->_id));
		
		$inventory_id = new \MongoId();

		$this->expectException();
		Inventory::secure(new \MongoId(), $inventory_id);
	}
	
	public function testPurchase(){
		$inventory = Inventory::create();
		$inventory->offer_id = new \MongoId();
		$this->assertTrue($inventory->save());
		
		$this->assertTrue(Inventory::purchase($inventory->_id));

		$this->expectException();
		Inventory::purchase(new \MongoId(), new \MongoId());
	}
}
?>