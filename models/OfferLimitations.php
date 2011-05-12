<?php
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\models;

class OfferLimitations extends \chowly\extensions\data\Model{

	public $validates = array(
		'name' => array(
			array('notEmpty','message'=>'Please enter a name')
		)
	);

	protected $_schema = array(
		'_id' => array('type' => 'id'),
		'name' => array('type' => 'string', 'null'=>false), // Name of the coupon
		'created'=>array('type'=>'date'),
		'updated'=>array('type'=>'date')
	);
}

?>