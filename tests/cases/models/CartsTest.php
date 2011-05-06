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
	public function testItems(){
		$cart = MockCarts::create();
		$cart->save();
		
		$this->assertTrue($cart->isEmpty());
		$this->assertEqual(array(), $cart->items()->to('array'));

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
		$this->assertFalse($cart->isEmpty());
		
		//Attempt to add a item in a 'in-transaction' cart is prohibited.
		$cart->state = 'transaction';
		$this->assertFalse($cart->addItem(new \MongoId(), new \MongoId()));
		
		//Should not be able to add multiple times the same item.
		//TODO Test with non-existing cart for upsert.
		$cart->addItem($id, $id);
		$cart->addItem($id, $id);
		$this->assertEqual(1, count($cart->items()));

		$this->assertTrue($cart->containItem($id));
		$this->assertFalse($cart->containItem(new \MongoId()));
	}
	public function testRemoveItems(){
		$cart = MockCarts::create();
		$cart->save();
		$id = new \MongoId();
		
		$cart->addItem(new \MongoId(), $id);
		$cart->addItem(new \MongoId(), $id);
		$cart->addItem(new \MongoId(), $id);
		
		$conditions = array('_id' => $cart->_id);
		$cart = MockCarts::first(compact('conditions'));
		$this->assertEqual(3, count($cart->items()));
		
		//We should not be able to clear the cart if in a transaction
		$cart->state = 'transaction';
		$this->assertTrue($cart->isReadOnly());
		$this->assertFalse($cart->removeItem(new \MongoId()));
		$this->assertFalse($cart->clearItems());
		
		$cart->state = 'default';
		$this->assertTrue($cart->removeItem($cart->items[0]['_id']));
		
		//Refetch the cart to compare
		$cart = MockCarts::first(compact('conditions'));
		$this->assertEqual(2, count($cart->items()));
		
		$this->assertTrue($cart->clearItems());
		$cart = MockCarts::first(compact('conditions'));
		$this->assertEqual(0, count($cart->items()));
		
	}
}
	
?>