<?php
if(!isset($filename) || empty($filename)){
	$filename = 'Document.pdf';
}
header("Content-type: application/pdf");
$this->Pdf->Output($filename, 'D');
?>