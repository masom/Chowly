<?php
namespace chowly\models;

class Ticket extends \lithium\data\Model{
	public $_schema = array(
		'user_id' => array('type' => 'id'),
		'name' => array('type' => 'string','null'=>false),
		'issue' => array('type'=>'string','null'=>false),
		'status' => array('type'=>'string','null'=>false),
		'created'=>array('type'=>'date'),
		'modified'=>array('type'=>'date')
		
	);
}
?>