<?php
namespace chowly\extensions\data;

class Model extends \lithium\data\Model{
	public function save($entity, $data = null, array $options = array()) {
		$date = new \MongoDate(time());
		if(!$entity->created){
			$entity->created = $date;
		}
		$entity->modified = $date;
		return parent::save($entity, $data, $options);
	}
}
?>