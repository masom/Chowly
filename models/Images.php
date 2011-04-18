<?php

namespace chowly\models;

class Images extends \chowly\extensions\data\Model{

	protected $_meta = array('source' => 'fs.files');

	protected $_schema = array('_id'=>array('type'=>'id'));

	public function save($entity, $data = null, array $options = array()){
		if ($data['error']){
			return false;
		}

		$md5 = md5_file($data['file']['tmp_name']);
		$image = static::first(array('conditions'=>array('md5'=>$md5), 'fields'=>array('_id')));
		if ($image){
			$entity->set($image->data());
			return true;
		}
		unset($data['error']);
		$entity->set($data);
		$entity->type = $data['file']['type'];

		return parent::save($entity, null, $options);
	}
}

?>