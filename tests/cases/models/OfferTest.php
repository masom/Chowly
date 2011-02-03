<?php
namespace chowly\tests\cases\models;

use chowly\models\Offer;
use \lithium\data\entity\Document;

class OfferTest extends \lithium\test\Unit{

	public function setUp() {
		Offer::config(array('connection' => 'test'));
	}

	public function tearDown() {
		Offer::remove();
	}
	
	public function testCreate() {
		$offer = Offer::create();
		$this->assertTrue($offer instanceof Document);
	}
	
	public function testDefaultState(){
		$this->assertTrue(in_array(Offer::defaultState(), Offer::states()));
	}
	
	public function testCreateWithoutName(){
		$offer = Offer::create();
		$offer->name = null;
		$offer->state = Offer::defaultState();
		$offer->starts = new \MongoDate();
		$offer->ends = new \MongoDate();
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$this->assertFalse($offer->save());
		
		$offer = Offer::create();
		$offer->state = Offer::defaultState();
		$offer->starts = new \MongoDate();
		$offer->ends = new \MongoDate();
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$this->assertFalse($offer->save());
	}
	
	public function testCreateWithoutAvailability(){
		$offer = Offer::create();
		$offer->name = "test";
		$offer->state = Offer::defaultState();
		$offer->starts = new \MongoDate();
		$offer->ends = new \MongoDate();
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$this->assertFalse($offer->save());
		
		$offer = Offer::create();
		$offer->name = "test";
		$offer->state = Offer::defaultState();
		$offer->starts = new \MongoDate();
		$offer->ends = new \MongoDate();
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$offer->availability = 0;
		$this->assertFalse($offer->save());
	}
	public function testCurrent(){
		
		//Past
		$offer = Offer::create();
		$offer->state = 'published';
		$offer->starts = new \MongoDate(time() - 120);
		$offer->ends = new \MongoDate(time() - 60);
		$offer->name = "test";
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$offer->availability = 11;
		$this->assertTrue($offer->save());
		
		//Current
		$offer = Offer::create();
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
		$offer = Offer::create();
		$offer->state = 'published';
		$offer->starts = new \MongoDate(time() + 360);
		$offer->ends = new \MongoDate(time() + 360);
		$offer->name = "Test3";
		$offer->venue_id = new \MongoId();
		$offer->cost = 22;
		$offer->availability = 11;
		$this->assertTrue($offer->save());

		$this->assertEqual(Offer::current()->to('array'), array($good->to('array')) );
	}

	public function testReserveNonExistent(){
		$this->expectException();
		$this->assertFalse(Offer::reserve(new \MongoId(), new \MongoId()));
	}
}
?>