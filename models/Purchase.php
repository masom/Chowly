<?php
namespace chowly\models;


class Purchase extends \lithium\data\Model{
	
	protected $_schema = array(
		'_id' => array('type'=>'id'),
		'customer_id' => array('type'=>'_id'),
		'state' => array('type'=>'string'),
		'created' => array('type'=>'date')
	);
	
	protected $validate = array(
		'status' => array(
			array('inList', 'list' => array('completed','new','invalid', 'failed', 'cancelled'))
		)
	);
	public function process($entity, Array $data = array()){
		$entity->set(data);
		$entity->status = 'complete';
		return $entity->save();
	}
	
	public function isComplete($entity){
		return ($entity->status == 'complete')? true : false;
	}
}
?>