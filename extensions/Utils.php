<?php
/**
 * Chowly Pick. Eat. Save!
 * @copyright	Copyright 2011, Martin Samson
 * @copyright	Copyright 2011, Chowly Corporation
 */

namespace chowly\extensions;

use \lithium\template\View;
use chowly\models\Purchases;

class Utils extends \lithium\core\StaticObject{
	
	/**
	 * Returns the path to a PDF file. Will generate the pdf if it does not exists.
	 * @param $purchase The purchase entity
	 * @param $offers The offers collection
	 * @param $venues The venues collection
	 * @throws \Exception
	 * @return var Full path to the pdf file
	 */
	public static function getPdf(&$purchase, &$offers = null, &$venues = null){
		$path = Purchases::pdfPath();
		$filepath = $path . DIRECTORY_SEPARATOR . $purchase->_id .'.pdf';
		
		if(file_exists($filepath)){
			return $filepath;
		}
		return static::_writePdf($path, $purchase->_id, static::_generatePdf($purchase, $offers, $venues));
	}
	
	/**
	 * Generates a PDF
	 * 
	 * @param var $purchase The purchase entity
	 * @param var $offers The offers collection
	 * @param var $venues The venues collection
	 */
	private static function _generatePdf($purchase, $offers, $venues){
		$view  = new View(
		array(

		    'paths' => array(
				'element' => '{:library}/views/elements/{:template}.{:type}.php',
		        'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
		        'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
		    )
		));	
		
		return $view->render(
		    'all',
		    compact('purchase','offers','venues'),
		    array(
		        'controller' => 'purchases',
		        'template' => 'purchase',
		        'type' => 'pdf',
		        'layout' =>'purchase'
		    )
		);
	}
	
	/**
	 * Write a purchase pdf
	 * 
	 * @param var $path Path to containing folder
	 * @param var $purchaseId Purchase id, used to generate file name
	 * @param var $pdf The pdf content
	 * @throws \Exception
	 * @return Full path to pdf file
	 */
	private static function _writePdf($path, $purchaseId, $pdf){
		$filepath = $path. DIRECTORY_SEPARATOR . $purchaseId.'.pdf';
		if(!is_writable($path)){
			throw new \Exception("File path is not writable.");
		}
		if(file_put_contents($filepath, $pdf,LOCK_EX)){
			return $filepath;
		}else{
			throw new \Exception("Could not write to file");
		}
	}
}
?>