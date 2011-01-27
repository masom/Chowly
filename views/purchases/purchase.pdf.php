<?php
$pdf =& $this->Pdf;
$this->Pdf->setCustomLayout(array(
	'footer'=>function() use($pdf){
		$footertext = sprintf('Copyright © %d Chowly. All rights reserved.', date('Y'));
		$pdf->SetY(-20);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont(PDF_FONT_NAME_MAIN,'', 8);
		$pdf->Cell(0,8, $footertext,'T',1,'C');
	}
));

$this->Pdf->SetPrintHeader(false);
$this->Pdf->SetMargins(10,30,10);
$this->Pdf->SetAuthor('Chowly: Pick, Eat, Save.');
$this->Pdf->SetAutoPageBreak(true);
$this->Pdf->SetTextColor(0,0,0);
$this->Pdf->SetFillColor(255,255,255);

foreach($offers as $offer):
	$this->View()->render(array('element'=>'purchases/offer'), compact('offer','venues'),array('type'=>'pdf'));
endforeach;
?>