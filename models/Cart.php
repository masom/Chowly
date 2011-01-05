<?php
namespace chowly\models;
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
	
	/**
	 * Initializes the session class.
	 *
	 * @return void
	 */
	public static function __init() {
		static::$_storage = static::$_classes['session'];
	}
	
	
	public static function freeze(){
		$storage = static::$_storage;
		return $storage::write("CartFreeze",true, array('name'=>'ChowlyCart'));
	}
	
	public static function unfreeze(){
		$storage = static::$_storage;
		if($storage::check("CartFreeze", array('name'=>'ChowlyCart'))){
			return $storage::delete("CartFreeze", array('name'=>'ChowlyCart'));
		}
		return true;
	}
	/**
	 * Add a cart item
	 * @param var $offer_id
	 * @return bool
	 */
	public static function add($offer_id, $inventory_id){
		$storage = static::$_storage;

		if($storage::read("CartFreeze", array('name'=>'ChowlyCart'))){
			return false;
		}
		
		if($storage::check("Cart.{$offer_id}", array('name'=>'ChowlyCart'))){
			return true;
		}
		
		return $storage::write("Cart.{$offer_id}", array('inventory_id'=>$inventory_id,'expires'=> time() + 15 * 60), array('name' => 'ChowlyCart'));
	}
	
	/**
	 * Get the cart content. Cleanup old items
	 * @return array
	 */
	public static function get() {
		$storage = static::$_storage;
		$cart = $storage::read("Cart", array('name' => 'ChowlyCart'));
		
		if($storage::read("CartFreeze", array('name'=>'ChowlyCart'))){
			return $cart;
		}
		
		$time = time();
		foreach($cart as $offer => $attr){
			if($attr['expires'] < $time){
				Cart::clear($offer);
				unset($cart[$offer]);
			}
		}
		return $cart; 
	}
	/**
	 * Clears one or all items from the storage.
	 *
	 * @param string [$key] Optional key. Set this to `null` to delete all items.
	 * @return void
	 */
	public static function clear($key = '') {
		$storage = static::$_storage;
		
		if($storage::read("CartFreeze", array('name'=>'ChowlyCart'))){
			return false;
		}
		
		$sessionKey = 'Cart';
		if (!empty($key)) {
			$sessionKey .= ".{$key}"; 
		}
		$storage::delete($sessionKey, array('name' => 'ChowlyCart'));
	}
}
?>