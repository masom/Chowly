<?php

namespace chowly\models;

use chowly\models\Images;

class Venues extends \chowly\extensions\data\Model{
	protected static $_states = array( 'published'=>'published','unpublished'=>'unpublished');
	private $_errors = array();

	protected $_schema = array(
		'_id' => array('type'=>'id'),
		'name' => array('type'=>'string','default'=> '', 'null'=>false),
		'description' => array('type'=>'string','default'=>'','null' => true),
		'address' => array('type'=>'string', 'default'=> '', 'null'=> false),
		'phone' => array('type'=>'string','default'=>'', 'null'=>false),
		'state'=> array('type'=>'string', 'default'=> 'published', 'null'=>false),
		'created'=>array('type'=>'string', 'null'=>true),
		'logo' => array('type' => 'id'),
		'image' => array('type'=>'id'),
		'updated' => array('type' => 'string', 'null' => true)
	);

	public static function states(){
		return static::$_states;
	}

	public static function defaultState(){
		return 'unpublished';
	}

	public function getErrors(){
		return $this->_errors;
	}

	public function save($entity, $data = null, array $options = array()) {
		$files = array();
		$keys = array('logo','image');
		foreach ($keys as $key){
			if (isset($data[$key])){
				$files[$key] = $data[$key];
				unset($data[$key]);
			}
		}

		if (!$entity->_id){
			$entity->_id = new \MongoId();
		}

		$this->_errors = array();
		foreach ($files as $key => $file){
			if (!$file['tmp_name'] || empty($file['tmp_name'])){
				continue;
			}

			$image = Images::create();
			$imageData = array('file'=> $file, 'parent_id'=> $entity->_id, 'parent_type'=>'venue');
			if ($image->save($imageData)){
				$data[$key] = $image->_id;
			}else{
				$this->_errors[] = "Image {$key} could not be saved.";
			}
		}
		return parent::save($entity, $data, $options);
	}
}

?>