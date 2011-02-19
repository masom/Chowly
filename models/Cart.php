<?php
namespace chowly\models;

use \lithium\storage\Session;

class Cart extends \lithium\data\Model{
	/**
	 * Holds the instance of the session storage class
	 *
	 * @see \lithium\storage\Session
	 */
	protected static $_storage = null;

	protected static $_classes = array(
		'session' => '\lithium\storage\Session'
	);
	protected static $_options = array(
		'name'=>'ChowlyCart'
	);
	
	/**
	 * Initializes the session class.
	 *
	 * @return void
	 */
	public static function __init() {
		static::$_storage = static::$_classes['session'];
	}
	
	public static function endTransaction(){
		$storage = static::$_storage;
		return $storage::write("Cart.State", $states['frozen'], static::$_options);
	}
	public static function startTransaction(){
		$storage = static::$_storage;
		return $storage::write("Cart.State", static::$_states['transaction'], static::$_options);
	}
	public static function inTransaction(){
		$storage = static::$_storage;
		return ($storage::read("Cart.State", static::$_options) == 'transaction');
	}
	
	public static function freeze(){
		$storage = static::$_storage;
		
		switch ($storage::read("Cart.State", static::$_options)){
			case 'transaction':
			case 'frozen':
				return true;
				break;
			default:
				return $storage::write("Cart.State", $states['frozen'], static::$_options);
				break;
		}
	}
	
	public static function unfreeze(){
		$storage = static::$_storage;
		
		switch($storage::read("Cart.State", static::$_options)){
			case 'transaction':
				break;
			case 'frozen':
				$storage::write("Cart.State", 'default', static::$_options);
				break;
			default:
				return true;
				break;
		}
	}
	
	public static function contain($offer_id){
		$storage = static::$_storage;
		static::clean();
		return $storage::check("Cart.Items.{$offer_id}", static::$_options);
	}
	
	/**
	 * Add a cart item
	 * @param var $offer_id
	 * @return bool
	 */
	public static function add($offer_id, $inventory_id){
		$storage = static::$_storage;
		
		if(in_array($storage::read("Cart.State", static::$_options), array('transaction','frozen'))){
			return false;
		}
		
		if(static::contain($offer_id)){
			return true;
		}
		return $storage::write("Cart.Items.{$offer_id}", array('inventory_id'=>$inventory_id,'expires'=> time() + 15 * 60), static::$_options);
	}
	
	/**
	 * Get the cart content. Cleanup old items
	 * @return array
	 */
	public static function get() {
		$storage = static::$_storage;

		if(!in_array($storage::read("Cart.State", static::$_options), array('transaction','frozen'))){
			static::clean();
		}
		return $storage::read("Cart.Items", static::$_options);
	}
	
	public static function clean(){
		$storage	= static::$_storage;
		$time		= time();
		
		foreach($storage::read("Cart.Items", static::$_options) as $offer => $attr){
			if($attr['expires'] < $time){
				static::clear($offer);
			}
		}
	}
	public static function isEmpty(){
		$storage = static::$_storage;

		if(!in_array($storage::read("Cart.State", static::$_options), array('transaction','frozen'))){
			static::clean();
		}
		
		return ($storage::read("Cart.Items", static::$_options))? false : true;
	}
	/**
	 * Clears one or all items from the storage.
	 *
	 * @param string [$key] Optional key. Set this to `null` to delete all items.
	 * @return void
	 */
	public static function clear($key = '') {
		$storage = static::$_storage;

		if(in_array($storage::read("Cart.State", static::$_options), array('transaction','frozen'))){
			return false;
		}
		
		if (empty($key)) {
			$storage::write("Cart.Items", array(), static::$_options);
		}else{
			$storage::delete("Cart.Items.{$key}", static::$_options);
		}
		
	}
	public static function isReadOnly(){
		$storage = static::$_storage;
		if(in_array($storage::read("Cart.State", static::$_options), array('transaction','frozen'))){
			return true;
		}
		return false;
	}
}
?>