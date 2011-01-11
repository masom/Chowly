<?php
namespace li3_pdf\extensions;
use \tcpdf\TCPDF;

class PdfWrapper extends \tcpdf\TCPDF{
    private $__header = null;
    private $__footer = null;
    
    public function Header(){
    	call_user_func($this->__header);
    } 
    public function Footer() {
    	call_user_func($this->__footer);
    }
    public function setCustomHeader(&$header){
    	if(is_callable($header)){
    		$this->__header = $header;
    	}
    	
    }
    public function setCustomFooter(&$footer){
    	if(is_callable($footer)){
    		$this->__footer = $footer;
    	}
    }
}
?>