<?php
namespace chowly\extensions\helper;

use \lithium\net\http\Router;

class Html extends \lithium\template\helper\Html {
	
	public function url($url){
		return Router::match($url);
	}
}
?>