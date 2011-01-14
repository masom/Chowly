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
$this->Pdf->SetTextColor(0,0,0);
$this->Pdf->SetFillColor(255,255,255);

$this->Pdf->AddPage();

$this->Pdf->SetXY(10,20);
$this->Pdf->Cell(0, 100, '', 1, 'L', 1, 0, '','',true);
$style = array(
    'border' => 1,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255)
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);
$this->Pdf->SetXY(175,22);
$this->Pdf->write2DBarcode("BLEH", 'QRCODE,H', '', '', 20, 20, $style, 'N');
$this->Pdf->Ln();

$this->Pdf->Cell(0,8,'The Fine Print');
?>