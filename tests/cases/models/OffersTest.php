<?php
namespace chowly\tests\cases\models;

use chowly\models\Offers;
use chowly\models\Inventories;
use \lithium\data\entity\Document;

class OffersTest extends \lithium\test\Unit{

	private $_offerFactoryCount = 0;
	
	public function setUp() {
		Offers::config(array('connection' => 'test'));
		Inventories::config(array('connection' => 'test'));
		$this->_offerFactoryCount = 0;
	}

	public function tearDown() {
		Offers::remove();
		Inventories::remove();
	}
	
	public function testCreate() {
		$offer = Offers::create();
		$this->assertTrue($offer instanceof Document);
	}
	
	public function testDefaultState(){
		$this->assertTrue(in_array(Offers::defaultState(), Offers::states()));
	}
	
	public function testCreateWithoutName(){
		$offer = Offers::create();
		$offer->name = null;
		$offer->state = Offers::defaultState();
		$offer->starts = new \MongoDate();
		$offer->ends = new \MongoDate();
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$this->assertFalse($offer->save());
		
		$offer = Offers::create();
		$offer->state = Offers::defaultState();
		$offer->starts = new \MongoDate();
		$offer->ends = new \MongoDate();
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$this->assertFalse($offer->save());
	}
	
	public function testCreateWithoutAvailability(){
		$offer = Offers::create();
		$offer->name = "test";
		$offer->state = Offers::defaultState();
		$offer->starts = new \MongoDate();
		$offer->ends = new \MongoDate();
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$this->assertFalse($offer->save());
		
		$offer = Offers::create();
		$offer->name = "test";
		$offer->state = Offers::defaultState();
		$offer->starts = new \MongoDate();
		$offer->ends = new \MongoDate();
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$offer->availability = 0;
		$this->assertFalse($offer->save());
	}
	public function testCurrent(){

		//Past
		$offer = Offers::create();
		$offer->state = 'published';
		$offer->starts = new \MongoDate(time() - 120);
		$offer->ends = new \MongoDate(time() - 60);
		$offer->name = "test";
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$offer->availability = 11;
		$this->assertTrue($offer->save());

		//Current
		$offer = Offers::create();
		$offer->state = 'published';
		$offer->starts = new \MongoDate(time() - 60);
		$offer->ends = new \MongoDate(time() + 60);
		$offer->name = "Test2";
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$offer->availability = 11;
		$this->assertTrue($offer->save());
		$good = $offer;

		//Future
		$offer = Offers::create();
		$offer->state = 'published';
		$offer->starts = new \MongoDate(time() + 360);
		$offer->ends = new \MongoDate(time() + 360);
		$offer->name = "Test3";
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$offer->availability = 11;
		$this->assertTrue($offer->save());

		$this->assertEqual(Offers::current()->to('array'), array($good->to('array')) );
	}

	public function testReserveNonExistent(){
		$this->expectException("/Offer not found./");
		$this->assertFalse(Offers::reserve(new \MongoId(), new \MongoId()));
	}
	
	public function testRebuildInventoryEmpty(){
		$this->expectException("/No more inventory./");
		Offers::rebuildInventory();
	}
	public function testRebuildInventorySoldOut(){

		$inventory = Inventories::create();
		$inventory->offer_id = new \MongoId();
		$inventory->state = 'reserved';
		$this->assertTrue($inventory->save());

		//Here we expect no available inventory found.
		$this->assertEqual(0, Offers::rebuildInventory());
		
	}
	public function testReleaseInventory(){

		$this->assertFalse(Offers::releaseInventory(new \MongoId(), new \MongoId()));
		
		$offer = $this->_offerFactory();
		$offer->availability = 1;
		$offer->createWithInventory();

		//Verify we cannot release inventory that has not been reserved or sold
		$conditions = array('offer_id' => $offer->_id);
		$inventory = Inventories::first(compact('conditions'));
		$this->assertFalse(Offers::releaseInventory(new \MongoId(), $offer->_id));

		$inventory->state = 'purchased';
		$inventory->save();

		$this->assertFalse(Offers::releaseInventory(new \MongoId(), $offer->_id));

		//We should be able to release inventory for a offer a customer has reserved.
		$inventory->state = 'reserved';
		$inventory->customer_id = new \MongoId();
		$inventory->save();

		$this->assertTrue(Offers::releaseInventory($inventory->customer_id, $offer->_id));
	}
	public function testRebuildInventory(){

		$expected = array();

		$offer = $this->_offerFactory();
		$offer->state = 'published';
		$offer->availability = 10;
		$offer->createWithInventory();

		//Update 5 items to be purchased
		$data = array('state'=>'purchased', 'expires' => time() - 60 * 30);
		$conditions = array('sequence_number' => array('$lt' => 5));
		Inventories::update($data, $conditions);

		$expected[(string)$offer->_id] = 5;

		//Create 2 more offers
		$offer = $this->_offerFactory();
		$offer->state = 'published';
		$offer->availability = 10;
		$offer->createWithInventory();

		$expected[(string)$offer->_id] = 10;

		$offer = $this->_offerFactory();
		$offer->state = 'published';
		$offer->availability = 6;
		$offer->createWithInventory();

		$expected[(string)$offer->_id] = 6;

		//Update the inventory of 2 offers and let the 3rd one be at default
		$data = array('state'=>'reserved', 'expires' => new \MongoDate(time() - 60 * 30));
		$conditions = array('offer_id' => array('$ne' => $offer->_id), 'state' => array('$ne' => 'purchased'));
		Inventories::update($data, $conditions);

		// We should only have updated 2 offers inventory
		$this->assertEqual($expected, Offers::rebuildInventory());
		
	}
	public function testCreateWithInventory(){
		$offer = Offers::create();
		$offer->state = 'published';
		$offer->starts = new \MongoDate(time());
		$offer->ends = new \MongoDate(time() + 360);
		$offer->name = "Test3";
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$offer->availability = 11;
		$this->assertTrue($offer->createWithInventory());
		$conditions = array('offer_id' => $offer->_id);
		$this->assertEqual(11, Inventories::count(compact('conditions')));
	}
	public function testReserve(){
		
		$offer = $this->_offerFactory();
		$offer->state = 'published';
		$offer->createWithInventory();
		
		$this->assertTrue(Offers::reserve($offer->_id, new \MongoId()));
		
		$this->assertEqual(9, Offers::first(compact('conditions'))->availability);
	}
	public function testReserveNoInventory(){
		$offer = $this->_offerFactory();
		$offer->state = 'published';
		$offer->save();
		
		$this->expectException("/No matching object found/");
		$this->assertFalse(Offers::reserve($offer->_id, new \MongoId()));
	}
	public function testReserveUnpublished(){
		$offer = $this->_offerFactory();
		$offer->state = 'unpublished';
		$offer->createWithInventory();
		
		$this->expectException("/Offer not found./");
		$this->assertTrue(Offers::reserve($offer->_id, new \MongoId()));
	}
	public function testGetErrors(){
		$offer = Offers::create();
		$this->assertTrue($offer->getErrors() === array());
	}
	public function testPublishing(){
		$offer = $this->_offerFactory();
		$offer->save();

		$conditions = array('_id' => $offer->_id);
		$this->assertTrue($offer->publish());
		
		$offer = Offers::first(compact('conditions'));
		$this->assertEqual('published', $offer->state);
		
		$this->assertTrue($offer->unpublish());
		
		$offer = Offers::first(compact('conditions'));
		$this->assertEqual('unpublished', $offer->state);
	}
	private function _offerFactory($state = 'unpublished', $name = 'OfferFactory_', $venue_id = null, $cost = 10, $availability = 10, $starts = null, $ends = null){
		
		$venue_id = $venue_id ?: new \MongoId();
		$starts = $starts ?: new \MongoDate(time() - 10);
		$ends = $ends ?: new \MongoDate(time() + 360);
		
		$offer = Offers::create();
		$offer->state = $state;
		$offer->starts = $starts;
		$offer->ends = $ends;
		$offer->name = $name . $this->_offerFactoryCount;
		$offer->venue_id = $venue_id;
		$offer->cost = $cost;
		$offer->availability = $availability;
		return $offer;
	}
}

?>