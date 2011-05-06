<?php
namespace chowly\tests\cases\models;

use chowly\models\Inventories;
use \lithium\data\entity\Document;

class InventoriesTest extends \lithium\test\Unit{

	public function setUp() {
		Inventories::config(array('connection' => 'test'));
	}

	public function tearDown() {
		Inventories::remove();
	}

	public function testCreate() {
		$inventory = Inventories::create();
		$this->assertTrue($inventory instanceof Document);
	}
	
	public function testDefaultState(){
		$this->assertTrue(in_array(Inventories::defaultState(), Inventories::states()));
	}

	public function testSave(){
		$inventory = Inventories::create();
		$this->assertTrue($inventory->save());
		
		$conditions = array('_id' => $inventory->_id);
		$saved = Inventories::find('first', compact('conditions'));
		$this->assertEqual($inventory->to('array'), $saved->to('array'));
	}
	public function testAvailable(){
		$inventory = Inventories::create();
		$inventory->offer_id = new \MongoId();
		$inventory->save();
		
		$inventory = Inventories::create();
		$inventory->offer_id = new \MongoId();
		$inventory->state = 'purchased';
		$inventory->save();
		
		$this->assertEqual(1, count(Inventories::getAvailable()));
	}
	public function testReleaseExpired(){
		
		$inventory = Inventories::create();
		$inventory->offer_id = new \MongoId();
		$inventory->state = 'reserved';
		$inventory->expires = new \MongoDate(time() + 20 * 60);
		$inventory->save();
		
		$inventory = Inventories::create();
		$inventory->offer_id = new \MongoId();
		$inventory->state = 'reserved';
		$inventory->expires = new \MongoDate(time() - 2);
		$inventory->save();
		
		$this->assertEqual(0, count(Inventories::getAvailable()));
		
		Inventories::releaseExpired();
		
		$this->assertEqual(1, count(Inventories::getAvailable()));
	}
	public function testRelease(){
		$customer_id = new \MongoId();
		
		$inventory = Inventories::create();
		$inventory->offer_id = new \MongoId();
		$inventory->state = 'reserved';
		$inventory->customer_id = $customer_id;
		$inventory->save();
		
		$inventory = Inventories::create();
		$inventory->offer_id = new \MongoId();
		$inventory->state = 'reserved';
		$inventory->customer_id = $customer_id;
		$inventory->save();
		
		$released = Inventories::release($customer_id, $inventory->offer_id);
		$this->assertTrue($released instanceof Document);
		$this->assertEqual(1, Inventories::getAvailable());
	}
	
	
	public function testReserve(){
		$inventory = Inventories::create();
		$inventory->offer_id = new \MongoId();
		$this->assertTrue($inventory->save());
		
		$this->assertTrue(Inventories::reserve($inventory->offer_id, new \MongoId()) instanceof Document);
		
		$offer_id = new \MongoId();
		$this->expectException();
		Inventories::reserve($offer_id, new \MongoId());
	}
	
	public function testSecure(){
		$inventory = Inventories::create();
		$inventory->offer_id = new \MongoId();
		$this->assertTrue($inventory->save());
		
		$this->assertTrue(Inventories::secure($inventory->_id));
		
		$inventory_id = new \MongoId();

		$this->expectException();
		Inventories::secure(new \MongoId(), $inventory_id);
	}
	
	public function testPurchase(){
		$inventory = Inventories::create();
		$inventory->offer_id = new \MongoId();
		$this->assertTrue($inventory->save());
		
		$this->assertTrue(Inventories::purchase(new \MongoId(), $inventory->_id));

		$this->expectException();
		Inventories::purchase(new \MongoId(), new \MongoId());
	}
}
?>