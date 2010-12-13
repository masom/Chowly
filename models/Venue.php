<?php
namespace chowly\models;

use chowly\models\Image;

class Venue extends \lithium\data\Model{
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
		'modified' => array('type' => 'string', 'null' => true)
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
		$files['logo'] = $data['logo'];
		$files['image'] = $data['image'];
		unset($data['logo'],$data['image']);
		
		if(!parent::save($entity, $data, $options)){
			return false;
		}
		
		$this->_errors = array();
		foreach($files as $key => $file){
			if(!$file['tmp_name'] || empty($file['tmp_name'])) continue;
			
			$image = Image::create();
			$imageData = array('file'=> $file, 'parent_id'=> $entity->_id, 'parent_type'=>'venue');
			if(!$image->save($imageData)){
				$this->_errors[]= "Image {$key} could not be saved.";
			}
			$data[$key] = $image->_id;
		}
		return parent::save($entity, $data, $options);
	}

}