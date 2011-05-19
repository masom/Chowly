<?php 
namespace chowly\extensions\command;

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
		
	}

	private function _release(){
		
	}
}

?>