<?php
namespace chowly\tests\cases\models;

use chowly\tests\mocks\MockCarts;
use \lithium\data\entity\Document;
use lithium\data\collection\DocumentArray;

class CartsTest extends \lithium\test\Unit{

	public function tearDown() {
		MockCarts::remove();
	}

	public function testCreate() {
		$cart = MockCarts::create();
		$this->assertTrue($cart instanceof Document);
	}
	public function testTransactions(){
		$cart = MockCarts::create();
		//Trying to start a transaction on a new cart should fail.
		$this->assertFalse($cart->startTransaction());
		
		$cart = MockCarts::create();
		$this->assertTrue($cart->save());
		
		$this->assertTrue($cart->startTransaction());
		//Shouldnt be able to start a transaction 2 times
		$this->assertFalse($cart->startTransaction());
		
		$this->assertTrue($cart->endTransaction());
		//Shouldn't be able to end a transaction 2 times;
		$this->assertFalse($cart->endTransaction());
		
		//Start the transaction again.
		$this->assertTrue($cart->startTransaction());
		
	}
	public function testNewItem(){
		$cart = MockCarts::create();
		
		$id = new \MongoId();
		$time = time();
		$expected = array(
			'_id' => $id,
			'inventory_id' => $id,
			'expires' => $time
		);
		$this->assertEqual($expected, $cart->_mockNewItem($id, $id, $time));
	}
	public function testAddItem(){
		$cart = MockCarts::create();
		$cart->save();
		
		$cart_id = $cart->_id;
		$id = new \MongoId();
		
		$this->assertTrue($cart->addItem($id, $id));
		
		$conditions = array('_id' => $cart->_id);
		$cart = MockCarts::first(compact('conditions'));
		
		$expected = array(
			'_id' => $cart_id,
			'items' => array(
				array(
					'_id' => $id,
					'inventory_id' => $id,
					'expires' => $cart->items[0]['expires']
				)
			),
			'state' => 'default'
		);
		
		$this->assertEqual($expected, $cart->to('array'));
		
		//Attempt to add a item in a 'in-transaction' cart is prohibited.
		$cart->state = 'transaction';
		$this->assertFalse($cart->addItem(new \MongoId(), new \MongoId()));
	}
	public function testItems(){
		$cart = MockCarts::create();
		$this->assertEqual(array(), $cart->items()->to('array'));
	}
}
	
?>