<?php
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\models;

class OfferTemplates extends \chowly\extensions\data\Model{
	protected static $_states = array('published'=>'published', 'unpublished'=>'unpublished');
	public $validates = array(
		'name' => array(
			array('notEmpty','message'=>'Please enter a name'),
			array('lengthBetween', 'min'=>1,'max'=>255,
				'message' => 'Please enter a name that is between 1 and 255')
		),
		'cost' => array(
			array('numeric','message'=>'Must be a monetary amount (ex: 33.00)')
		)
	);

	protected $_schema = array(
		'_id' => array('type' => 'id'),
		'name' => array('type' => 'string','null'=>false), // Name of the coupon
		'description'=>array('type'=>'string'), // Description (if any) of the coupon
		'limitations'=>array('type'=>'string'), // Limitations regarding usage of the coupon
		'created'=>array('type'=>'date'),
		'updated'=>array('type'=>'date')
	);

	public function getErrors(){
		return $this->_errors;
	}

	public function save($entity, $data = null, array $options = array()) {
		$files = array();
		if (isset($data['image'])){
			$files['image'] = $data['image'];
			unset($data['image']);
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
			$imageData = array('file'=> $file, 'parent_id'=> $entity->_id, 'parent_type'=>'offer');
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