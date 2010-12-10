<?php
namespace chowly\controllers;

use chowly\models\Offer;

class OffersController extends \lithium\action\Controller{
	public function index(){
		$offers = Offer::all();
		return compact('offers');
	}
}