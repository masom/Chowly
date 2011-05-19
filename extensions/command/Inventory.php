<?php 
namespace chowly\extensions\command;

use chowly\models\Inventories;
use chowly\models\Offers;
/**
 * Inventory control command
 * 
 * Controls inventory by releasing or rebuilding
 * @author msamson
 */
class Inventory extends \lithium\console\Command{
	public function run($command = null) {
		$this->header("Chowly inventory control");

		switch($command){
			default:
				$this->_usage();
				return true;
				break;

			case 'rebuild':
				return $this->_rebuild();
				break;

			case 'release':
				return $this->_release();
				break;
		}
	}

	/**
	 * Print command usage
	 */
	private function _usage(){
		$this->out('Usage:');
		$rows = array(
			array('rebuild', 'Rebuilds the inventory'),
			array('release', 'Release expired reserved inventory.'),
		);
		$this->columns($rows);
	}

	/**
	 * Rebuild inventory
	 */
	private function _rebuild(){
		$this->out('Inventory Rebuild');
		$this->out('Rebuilt inventory with the following values:');
		$inventory = Offers::rebuildInventory();

		$map = function($k, $v){
			return array($k, $v);
		};

		$this->columns(array_map($map, array_keys($inventory), array_values($inventory)));
		$this->out('done.');
		return true;
	}

	/**
	 * Release expired inventory
	 */
	private function _release(){
		$this->out('Releasing expired reservations.');
		if(Inventories::releaseExpired()){
			$this->out('Success.');
			return true;
		}else{
			$this->out('Failed.');
			return false;
		}
	}
}

?>