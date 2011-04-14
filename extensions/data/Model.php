<?php
namespace chowly\extensions\data;

class Model extends \lithium\data\Model{
	protected $_meta = array('key' => '_id');
	public function save($entity, $data = null, array $options = array()) {
		$date = new \MongoDate(time());
		
		if(!$entity->_exists){
			$entity->created = $date;
		}

		$entity->updated = $date;
		return parent::save($entity, $data, $options);
	}
}
?>