<?php
$pdf =& $this->Pdf;
$this->Pdf->setCustomLayout(array(
	'header'=>function() use($pdf){
		list($r, $g, $b) = array(200,200,200);
		$pdf->SetFillColor($r, $g, $b);
		$pdf->SetTextColor(0 , 0, 0);
		$pdf->Cell(0,15, 'PDF created using Lithium', 0,1,'C', 1);
		$pdf->Ln();
	},
	'footer'=>function() use($pdf){
		$footertext = sprintf('Copyright © %d Chowly. All rights reserved.', date('Y'));
		$pdf->SetY(-20);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont(PDF_FONT_NAME_MAIN,'', 8);
		$pdf->Cell(0,8, $footertext,'T',1,'C');
	}
));

$this->Pdf->SetMargins(10,30,10);
$this->Pdf->SetAuthor('Chowly: Pick, Eat, Save.');
$this->Pdf->SetAutoPageBreak(true);

$this->Pdf->AddPage();

$this->Pdf->Cell(200,200, "TEST", 0, false, 'L');
?>