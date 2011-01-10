<?php
$this->Pdf->AddPage();
$tcpdf->SetTextColor(0, 0, 0); 
$tcpdf->SetFont($textfont,'B',20); 
$tcpdf->Cell(0,14, "Hello World", 0,1,'L');
?>