<?php
namespace chowly\models;

class Venue extends \lithium\data\Model{
	public $_schema = array(
		'name' => array('default'=> 'undefined', 'null'=>false),
		'address' => array('default'=> 'undefined', 'null'=> false),
		'location' => array()
	);
}