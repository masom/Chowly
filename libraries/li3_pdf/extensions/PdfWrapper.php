<?php
namespace li3_pdf\extensions\template;
use TCPDF;

class PdfWrapper extends TCPDF{
    private $__header = null;
    private $__footer = null;
    
    protected function Header(){
    	$this->__header();
    } 

    protected function Footer() {
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