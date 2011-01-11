<?php
header("Content-type: application/pdf");
echo $this->Pdf->Output('filename.pdf', 'S');
?>