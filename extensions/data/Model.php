<?php
/**
 * Chowly Pick. Eat. Save!
 *
 * @copyright     Copyright 2011, Martin Samson <pyrolian@gmail.com>
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
namespace chowly\extensions\data;

class Model extends \lithium\data\Model{
	protected $_meta = array('key' => '_id');
	public function save($entity, $data = null, array $options = array()) {
		$date = new \MongoDate(time());

		if (!$entity->exists()){
			$entity->created = $date;
		}

		$entity->updated = $date;
		return parent::save($entity, $data, $options);
	}

	/**
     * Generate a unique slug
     * @param object $entity
     * @param array $options
     *      - field: The field used to generate the slug
     *      - separator: The optional separator symbol for spaces (default: -)
     * @return String The unique pretty url.
    */
    public function slug($entity, array $options = array()){
        $options += array('field'=>'name', 'separator' => '-', 'prepend'=> '');

        extract($options);

        // all URLs are lowercase
        $slug = $prepend . $separator . $entity->{$field};
        $slug = date('Ymd') . $separator . strtolower($slug);
        $slug = \lithium\util\Inflector::slug($slug, $separator);

        $conditions = array('slug' => $slug);
        $conflicts = static::count(compact('conditions'));

        if ($conflicts){
	        $i = 0;
	        $newSlug = '';
	        while ($conflicts){
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

    protected function mapReduce($map, $reduce, $out = null){
    	$options = array(
		    "mapreduce" => static::meta('source'), 
		    "map" => $map,
		    "reduce" => $reduce,
		);

		if($out){
			$options['out'] = $out;
		}

    	return static::connection()->connection->command($options);
    }
}

?>