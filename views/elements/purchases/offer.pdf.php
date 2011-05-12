<?php
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
$this->Pdf->SetXY(170,22);
$this->Pdf->write2DBarcode((string)$offer->_id, 'QRCODE,H', '', '', 25, 25, $style, 'N');

$this->Pdf->Image(LITHIUM_APP_PATH.DIRECTORY_SEPARATOR.'webroot'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'logo.png',
	20,22, 30,0, 'PNG', false, '', true
);

$this->Pdf->SetXY(20,40);
$this->Pdf->SetFontSize(14);
$this->Pdf->MultiCell(140,0, $offer->name,
	0, 'L', false, 1, '','',true,0,false,true,20
);
$this->Ln();

$this->Pdf->SetFontSize(10);
$this->Pdf->SetXY(20,60);
$this->Pdf->MultiCell(140,0, $offer->description,
	0, 'L', false, 1, 20,'',true,0,false,true,50
);

$this->Pdf->SetFont('', 'B');
$this->Pdf->Text(20, 92, 'Redeem At:');

$this->Pdf->Text(110,92, 'General Conditions:');
$this->Pdf->Ln();

$this->Pdf->SetFont('', '');
$this->Pdf->MultiCell(80, 0, $venue->name .",\n". $venue->address,
	0, 'L', false, 0, 20, '', true, 0, true, true, 20
);
$offer->limitations += array(
"Not valid for cash back (unless required by law).",
"Must use in one visit.",
"Doesn't cover tax or gratuity.",
"Can't be combined with other offers.");

$this->Pdf->SetFontSize(8);
$this->Pdf->MultiCell(80,0, $conditions, 0, 'L', false, 0, 110, '', true);	
$this->Pdf->Ln();

$this->Pdf->SetY(225);
$this->Pdf->SetFont('', 'B');
$this->Pdf->Cell(0,8,'The Fine Print');
$this->Pdf->Ln();
$finePrint = str_replace("\n",'', "
General terms applicable to all Vouchers (unless otherwise set forth below, in Chowly's Terms of Sale, or in the Fine Print): Unless prohibited by applicable law the following restrictions also apply. See below for
further details. If the promotional offer stated on your coupon has expired, applicable law may require the merchant to allow you to redeem your Voucher
beyond its expiration date for goods/services equal to the amount you paid for it. If you have gone to the merchant and the merchant has refused to redeem the cash value of your expired Voucher, and if applicable
law entitles you to such redemption, Chowly will refund the purchase price of the Voucher per its Terms of Sale. Partial Redemptions: If you redeem the Voucher for less than its face value, you only will be entitled to
a credit or cash equal to the difference between the face value and the amount you redeemed from the merchant if applicable law requires it.If you redeem this Voucher for less than the total face value, you
will not be entitled to receive any credit or cash for the difference between the face value and the amount you redeemed, (unless otherwise required by applicable law). You will only be entitled to a redemption value
equal to the amount you paid for the Chowly less the amount actually redeemed. Redemption Value: If not redeemed by the discount voucher expiration date, this coupon will continue to have a redemption value
equal to the amount you paid (C{$offer->cost}$) at the named merchant for the period specified by applicable law. The redemption value will be reduced by the amount of purchases made. This coupon expiration date above,
the merchant will, in its discretion: (1) allow you to redeem this Voucher for the product or service specified on the Voucher or (2) allow you to redeem the Voucher to purchase other goods or services from the
merchant for up to the amount you paid (C{$offer->cost}$) for the Voucher. This Voucher can only can be used for making purchases of goods/services at the named merchant. Merchant is solely responsible for Voucher
redemption. Vouchers cannot be redeemed for cash or applied as payment to any account unless required by applicable law. Neither Chowly, Inc. nor the named merchant shall be responsible for Coupons
Vouchers that are lost or damaged. Voucher is for promotional purposes. Use of Vouchers are subject to Chowly's Terms of Sale found at http://www.chowly.com/terms
");
$this->Pdf->SetFont('','');
$this->Pdf->SetFontSize(6);
$this->Pdf->MultiCell(0,0, $finePrint, 0, 'L');
?>