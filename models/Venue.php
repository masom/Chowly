<?php
namespace chowly\models;

use chowly\models\Image;

class Venue extends \lithium\data\Model{
	protected static $_states = array('published', 'unpublished');
	
	protected $_schema = array(
		'_id' => array('type'=>'id'),
		'name' => array('type'=>'string','default'=> '', 'null'=>false),
		'description' => array('type'=>'string','default'=>'','null' => true),
		'address' => array('type'=>'string', 'default'=> '', 'null'=> false),
		'location' => array('type'=>'string', 'default'=> '' , 'null' => false),
		'state'=> array('type'=>'string', 'default'=> 'unpublished', 'null'=>false),
		'created'=>array('type'=>'string', 'null'=>true),
		'logo' => array('type' => 'id'),
		'image' => array('type'=>'id'),
		'modified' => array('type' => 'string', 'null' => true)
	);
	public static function states(){
		return array_values(static::$_states);
	}
	public static function defaultState(){
		return 'unpublished';
	}
	
	public function save($entity, $data = null, array $options = array()) {
		
		$files = array();
		$files['logo'] = $data['logo'];
		$files['image'] = $data['image'];
		unset($data['logo'],$data['image']);
		
		if(!parent::save($entity, $data, $options)){
			return false;
		}
		
		$errors = array();
		foreach($files as $key => $file){
			if(!$file['tmp_name'] || empty($file['tmp_name'])) continue;
			
			$image = Image::create();
			$imageData = array('parent_id' => $entity->_id, 'parent'=>'venue', 'file'=> $file);
			if(!$image->save($imageData)){
				$errors[]= "Image {$key} could not be saved.";
			}
		}
		return $errors;
	}

}