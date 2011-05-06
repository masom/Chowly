<?php
namespace chowly\tests\cases\models;

use chowly\models\Offers;
use \lithium\data\entity\Document;

class OffersTest extends \lithium\test\Unit{

	public function setUp() {
		Offers::config(array('connection' => 'test'));
	}

	public function tearDown() {
		Offers::remove();
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
		$this->expectException();
		$this->assertFalse(Offers::reserve(new \MongoId(), new \MongoId()));
	}
}
?>