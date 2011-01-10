<?php
header("Content-type: application/pdf");
$this->Pdf->setHeader(function(){
	list($r, $b, $g) = array(0,0,200);
	$this->setY(10);
	$this->SetFillColor($r, $b, $g); 
	$this->SetTextColor(0 , 0, 0);
	$this->Cell(0,20, '', 0,1,'C', 1); 
	$this->Text(15,26, 'PDF created using Lithium' ); 
});
$this->Pdf->setFooter(function(){
	$footertext = sprintf('Copyright © %d XXXXXXXXXXX. All rights reserved.', date('Y')); 
	$this->SetY(-20); 
	$this->SetTextColor(0, 0, 0); 
	$this->SetFont(PDF_FONT_NAME_MAIN,'', 8); 
	$this->Cell(0,8, $footertext,'T',1,'C');
});

echo $this->content;

echo $tcpdf->Output('filename.pdf', 'D');
?>