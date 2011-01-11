<?php

$pdf =& $this->Pdf;
$header = function() use($pdf){
	list($r, $b, $g) = array(0,0,200);
	$pdf->SetFillColor($r, $b, $g); 
	$pdf->SetTextColor(0 , 0, 0);
	$pdf->Cell(0,20, '', 0,1,'C', 1); 
	$pdf->Text(15,26, 'PDF created using Lithium' ); 
};
$footer = function() use($pdf){
	$footertext = sprintf('Copyright © %d XXXXXXXXXXX. All rights reserved.', date('Y')); 
	$pdf->SetY(-20); 
	$pdf->SetTextColor(0, 0, 0); 
	$pdf->SetFont(PDF_FONT_NAME_MAIN,'', 8); 
	$pdf->Cell(0,8, $footertext,'T',1,'C');
};
$this->Pdf->setCustomHeader($header);
$this->Pdf->setCustomFooter($footer);


$this->Pdf->AddPage();
$this->Pdf->SetTextColor(0, 0, 0);
$this->Pdf->SetFont($textfont,'B',20); 
$this->Pdf->Cell(0,14, "Hello World", 0,1,'L');
?>