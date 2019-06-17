<?php
 
$invoice_number = "1";
$invoice_date = date("d.m.Y");
$delivery_date = date("d.m.Y");
$author = "XXX YYY"; // Firstname and Lastname
 
$invoice_header = '
<img src="logo.png">
PHP Development - PDF
XXX YYY';
 
$invoice_recipient = 'Max Mustermann
Musterstraße 1
12345 Musterstadt';
 
$invoice_footer = "Wir bitten um eine Begleichung der Rechnung innerhalb von 14 Tagen nach Erhalt. Bitte Überweisen Sie den vollständigen Betrag an:

<div>
  <b>Empfänger:</b><span>Meine Firma</span>
  <b>IBAN:</b><span>DE11 11111111 1111111111</span>
  <b>BIC:</b><span>A1B2C3D4E5</span>
</div>";
 
// List all different products and positions in a format
// [ Produktbezeichnung, amount, single_price ]
$invoice_products = array(
  array("Produkt 1", 1, 42.50),
  array("Produkt 2", 5, 5.20),
  array("Produkt 3", 3, 10.00)
);
 
// Value Added Tax: 0.19 for 19% Value Added Tax
$valueAddedTax = 0.0; 
 
$pdfName = "Rechnung_" . $invoice_number . ".pdf";
 
// Content of PDF as HTML Source Code \\ 
 
$html = '
<table cellpadding="5" cellspacing="0" style="width: 100%; ">
  <tr>
    <td>' . nl2br(trim($invoice_header)) . '</td>
      <div style="text-align: right">
        <p>Rechnungsnummer ' . $invoice_number . '</p>
        <p>Rechnungsdatum: ' . $invoice_date . '</p>
        <p>delivery_date: ' . $delivery_date . '</p>
      </div>
    </td>
  </tr>
  <tr>
    <td style="font-size:1.3em; font-weight: bold;">
      Rechnung
    </td>
  </tr>
  <tr>
    <td colspan="2">' . nl2br(trim($invoice_recipient)) . '</td>
  </tr>
</table>
<br><br><br>
 
<table cellpadding="5" cellspacing="0" style="width: 100%;" border="0">
  <tr style="background-color: #cccccc; padding:5px;">
    <td style="padding:5px;"><b>Bezeichnung</b></td>
    <td style="text-align: center;"><b>Menge</b></td>
    <td style="text-align: center;"><b>Einzelpreis</b></td>
    <td style="text-align: center;"><b>Preis</b></td>
  </tr>';
 
$total_price = 0;
 
foreach($invoice_products as $product) {

 $amount = $product[1];
 $single_price = $product[2];
 $price = $amount*$single_price;
 $total_price += $price;

 $html .= '
  <tr>
    <td>' . $product[0] . '</td>
    <td style="text-align: center;">' . $product[1] . '</td> 
    <td style="text-align: center;">' . number_format($product[2], 2, ',', '') . ' Euro</td>	
    <td style="text-align: center;">' . number_format($price, 2, ',', '') . ' Euro</td>
  </tr>';
}
$html .= "</table>";
  
$html .= '
  <hr>
  <table cellpadding="5" cellspacing="0" style="width: 100%;" border="0">';

if($valueAddedTax > 0) {

  $net = $total_price / (1 + $valueAddedTax);
  $$vat_amount = $total_price - $net;

  $html .= '
  <tr>
    <td colspan="3">Zwischensumme (Netto)</td>
    <td style="text-align: center;">' . number_format($net , 2, ',', '') . ' Euro</td>
  </tr>
  <tr>
    <td colspan="3">Umsatzsteuer (' . intval($valueAddedTax*100) . '%)</td>
    <td style="text-align: center;">' . number_format($$vat_amount, 2, ',', '') . ' Euro</td>
  </tr>';

}
 
$html .='
  <tr>
    <td colspan="3"><b>Gesamtsumme: </b></td>
    <td style="text-align: center;"><b>' . number_format($total_price, 2, ',', '') . ' Euro</b></td>
  </tr> 
</table>
<br><br><br>';
 
if($valueAddedTax == 0) {

  $html .= 'Nach § 19 Abs. 1 UStG wird keine Umsatzsteuer berechnet.<br><br>';

}
 
$html .= nl2br($invoice_footer);
 
// Create a PDF Document \\
 
// Load TCPDF Library
require_once('tecnick.com/tcpdf');
 
// Create PDF Document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
 
// Document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($author);
$pdf->SetTitle('Invoice ' . $invoice_number);
$pdf->SetSubject('Invoice ' . $invoice_number);
 
 
// Informations: Header and Footer
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
// Choose of Font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 
// Choose of Margin
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
 
// Automatisches Autobreak der Seiten
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
 
// Image Scale 
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
// Font
$pdf->SetFont('verdana', '', 10);
 
// New Site
$pdf->AddPage();
 
// Compress HTML in a PDF
$pdf->writeHTML($html, true, false, true, false, '');
 
// Print of PDF
 
// Variant 1: Send PDF direct

$pdf->Output($pdfName, 'I');
 
// Variant 2: Save PDF in a directory

// $pdf->Output(dirname(__FILE__).'/'.$pdfName, 'F');
// echo 'PDF herunterladen: <a href="'.$pdfName.'">'.$pdfName.'</a>';
 
?>