<?php
$this->Pdf->AddPage();
$this->Pdf->SetTextColor(0, 0, 0); 
$this->Pdf->SetFont($textfont,'B',20); 
$this->Pdf->Cell(0,14, "Hello World", 0,1,'L');
?>