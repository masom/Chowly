<?php
namespace chowly\models;

class Venue extends \lithium\data\Model{
	public $_schema = array(
		'_id' => array('type'=>'id'),
		'name' => array('type'=>'string','default'=> 'undefined', 'null'=>false),
		'address' => array('type'=>'string', 'default'=> 'undefined', 'null'=> false),
		'location' => array('type'=>'string', 'default'=> '' , 'null' => false),
		'state'=> array('type'=>'string', 'default'=> 'unpublished', 'null'=>false),
		'created'=>array('type'=>'string', 'null'=>true),
		'logo' => array('type' => 'id'),
		'image' => array('type'=>'id'),
		'modified' => array('type' => 'string', 'null' => true)
	);
	

}