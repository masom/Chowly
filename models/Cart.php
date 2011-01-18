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
	
	public static function lock(){
		$storage = static::$_storage;
		return $storage::write("CartLock", true, static::$_options);
	}
	public static function unlock(){
		$storage = static::$_storage;
		if($storage::check("CartLock", static::$_options)){
			return $storage::delete("CartLock", static::$_options);
		}
		return true;
	}
	public static function freeze(){
		$storage = static::$_storage;
		
		if($storage::read("CartLock", static::$_options)){
			return false;
		}
		
		return $storage::write("CartFreeze",true, static::$_options);
	}
	
	public static function unfreeze(){
		$storage = static::$_storage;
		
		if($storage::read("CartLock", static::$_options)){
			return false;
		}
		
		if($storage::check("CartFreeze", static::$_options)){
			return $storage::delete("CartFreeze", static::$_options);
		}
		return true;
	}
	
	public static function contain($offer_id){
		$storage = static::$_storage;
		static::clean();
		return $storage::check("Cart.{$offer_id}", static::$_options);
	}
	
	/**
	 * Add a cart item
	 * @param var $offer_id
	 * @return bool
	 */
	public static function add($offer_id, $inventory_id){
		$storage = static::$_storage;
		
		if($storage::read("CartLock", static::$_options)){
			return false;
		}
		
		if($storage::read("CartFreeze", static::$_options)){
			return false;
		}
		
		if(static::contain($offer_id)){
			return true;
		}
		return $storage::write("Cart.{$offer_id}", array('inventory_id'=>$inventory_id,'expires'=> time() + 15 * 60), static::$_options);
	}
	
	/**
	 * Get the cart content. Cleanup old items
	 * @return array
	 */
	public static function get() {
		$storage = static::$_storage;

		if($storage::read("CartLock", static::$_options)){
			return $storage::read("Cart", static::$_options);
		}
		if($storage::read("CartFreeze", static::$_options)){
			return $storage::read("Cart", static::$_options);
		}
		
		static::clean();
		return $storage::read("Cart", static::$_options);
	}
	
	public static function clean(){
		$storage	= static::$_storage;
		$time		= time();
		
		foreach($storage::read("Cart", static::$_options) as $offer => $attr){
			if($attr['expires'] < $time){
				static::clear($offer);
			}
		}
	}
	public static function isEmpty(){
		$storage = static::$_storage;

		if($storage::read("CartLock", static::$_options)){
			return ($storage::read("Cart", static::$_options))? false : true;
		}
		if($storage::read("CartFreeze", static::$_options)){
			return ($storage::read("Cart", static::$_options))? false : true;
		}
		
		static::clean();
		return ($storage::read("Cart", static::$_options))? false : true;
	}
	/**
	 * Clears one or all items from the storage.
	 *
	 * @param string [$key] Optional key. Set this to `null` to delete all items.
	 * @return void
	 */
	public static function clear($key = '') {
		$storage = static::$_storage;

		if($storage::read("CartLock", static::$_options)){
			return false;
		}

		if($storage::read("CartFreeze", static::$_options)){
			return false;
		}
		
		$sessionKey = 'Cart';
		if (empty($key)) {
			$storage::write("Cart", array(), static::$_options);
		}else{
			$sessionKey .= ".{$key}";
			$storage::delete($sessionKey, static::$_options);
		}
		
	}
}
?>