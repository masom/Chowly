<?php
namespace chowly\tests\cases\models;

use chowly\models\Purchases;
use \lithium\data\entity\Document;

class PurchasesTest extends \lithium\test\Unit{
	public function setUp() {
		Purchases::config(array('connection' => 'test'));
	}
	public function tearDown() {
		Purchases::remove();
	}

	public function testCreate() {
		$purchase = Purchases::create();
		$this->assertTrue($purchase instanceof Document);
	}

	public function testPdfPath(){
		$this->assertTrue(is_dir(Purchases::pdfPath()));
		$this->assertTrue(is_writable(Purchases::pdfPath()));
	}

	public function testValidateOverride(){
		$purchase = Purchases::create();
		$this->assertFalse($purchase->validates());

		//Get some valid info
		$purchase->name = 'Test Name';
		$purchase->cc_number = '4222222222222';
		$purchase->cc_sc = '222';
		$purchase->province = 'Ontario';
		$purchase->phone = '2222222222';
		$purchase->agreed_tos_privacy = true;
		$purchase->email = 'test@chowly.com';
		$purchase->address = '225 test street';
		$purchase->city = 'Test City';
		$purchase->postal = 'j8j 0t4';
		$purchase->status = 'new';
		$this->assertTrue($purchase->validates());
		
		$purchase->name = '';
		$this->assertFalse($purchase->validates());
		$purchase->name = 'Test Name';
		
		$purchase->cc_number = '422233222222';
		$this->assertFalse($purchase->validates());
		$purchase->cc_number = '4222222222222';

		$purchase->cc_sc = '';
		$this->assertFalse($purchase->validates());
		$purchase->cc_sc = '222';

		$purchase->province = 'New York';
		$this->assertFalse($purchase->validates());
		$purchase->province = 'Ontario';		

		$purchase->agreed_tos_privacy = false;
		$this->assertFalse($purchase->validates());
		$purchase->agreed_tos_privacy = true;

		$purchase->email = 'asfd';
		$this->assertFalse($purchase->validates());
		$purchase->email = 'asfd@@asdf.com';
		$this->assertFalse($purchase->validates());
		$purchase->email = 'test@chowly.com';
		
		$purchase->phone = '';
		$this->assertFalse($purchase->validates());
		$purchase->phone = '2222222222';
		
		//We only validates postal code for being there or not in case foreigners buy coupons.
		$purchase->postal = '';
		$this->assertFalse($purchase->validates());
		$purchase->postal = 'j8j 0t4';
		
		
		
	}
}

?>