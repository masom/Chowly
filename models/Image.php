<?php
namespace chowly\models;

class Image extends \lithium\data\Model{
	
	protected $_meta = array('source' => 'fs.files');
	
	protected $_schema = array('_id'=>array('type'=>'id'));
	
	public function save($entity, $data, $options = array()){
		if($data['error']){
			return false;
		}
		
		$md5 = md5_file($data['file']['tmp_name']);
		$image = static::first(array('conditions'=>array('md5'=>$md5), 'fields'=>array('_id')));
		if($image){
			return $image;
		}
		unset($data['error']);
		$entity->set($data);
		$entity->type = $data['file']['type'];
		
		return parent::save($entity, null, $options);
	}
}