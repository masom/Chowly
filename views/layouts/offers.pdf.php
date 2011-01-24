<?php
header("Content-type: application/pdf");
$this->Pdf->Output('filename.pdf', 'I');
?>