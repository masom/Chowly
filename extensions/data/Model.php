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
	
	/**
     * Generate a unique slug
     * 
     * @params $options Array
     *      - field: The field used to generate the slug
     *      - separator: The optional separator symbol for spaces (default: -)
     * @return String The unique pretty url.
    */
    public function slug($entity, Array $options = array()) {
        $options += array('field'=>'name', 'separator' => '-');
        
        // all URLs are lowercase
        $slug = date('y-m-d-') . strtolower($entity->{$options['field']});
        $slug = \lithium\util\Inflector::slug($slug, $options['separator']);
        
        $conditions = array('slug' => $slug);
        $conflicts = static::count(compact('conditions'));
        
        if($conflicts){
	        $i = 0;
	        $newSlug = '';
	        while($conficts){
	        	$i++;
	        	$newSlug = "{$slug}-{$i}";
        		$conditions = array('slug' => $newSlug);
        		$conflicts = static::count(compact('conditions'));
	        }
	        // Out of conflict.
	        $slug = $newSlug;
        }
        return $slug;
    }
}
?>