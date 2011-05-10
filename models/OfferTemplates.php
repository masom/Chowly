<?php
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\models;

class OfferTemplates extends \chowly\extensions\data\Model{

	public $validates = array(
		'name' => array(
			array('notEmpty','message'=>'Please enter a name'),
			array('lengthBetween', 'min'=>1,'max'=>255,
				'message' => 'Please enter a name that is between 1 and 255')
		),
		'cost' => array(
			array('numeric','message'=>'Must be a monetary amount (ex: 33.00)')
		)
	);

	protected $_schema = array(
		'_id' => array('type' => 'id'),
		'name' => array('type' => 'string','null'=>false), // Name of the coupon
		'description'=>array('type'=>'string'), // Description (if any) of the coupon
		'limitations'=>array('type'=>'string'), // Limitations regarding usage of the coupon
		'created'=>array('type'=>'date'),
		'updated'=>array('type'=>'date')
	);
}

?>