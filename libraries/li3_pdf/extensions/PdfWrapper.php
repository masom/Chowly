<?php
namespace li3_pdf\extensions;
use \tcpdf\TCPDF;

class PdfWrapper extends \tcpdf\TCPDF{
    private $__header = null;
    private $__footer = null;
    
    public function Header(){
    	$this->__header();
    } 

    public function Footer() {
    	$this->__footer();
    }
    public function setHeader($header){
    	if( $header instanceof Closure){
    		$this->__header = $header;
    	}
    }
    public function setFooter($footer){
    	if($footer instanceof Closure){
    		$this->__footer = $footer;
    	}
    }
}
?>