<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 *
 * Edited By SublimeText
 * User: AMOSQUEA
 * Date:06/03/2018
 * Time : 10:47 AM
 */

include "../clases/class.pdfRep.php";
include "../clases/classPagos.php";

$fechPagoIni  = $_GET['fecPagIni'];
$fechPagoFin  = $_GET['fecPagFin'];
$idConcepto   = $_GET['idConcepto'];
$descConcepto = $_GET['descConcepto'];
$acueducto    = $_GET['acueducto'];
$entIni       = $_GET['entIni'];
$entFin       = $_GET['entFin'];
$punIni       = $_GET['punIni'];
$punFin       = $_GET['punFin'];
$cajaIni      = $_GET['cajaIni'];
$cajaFin      = $_GET['cajaFin'];
$pdf          = new PdfRepRecConc();
$pdf->setFechPagoIni($fechPagoIni);
$pdf->setFechPagoFin($fechPagoFin);
$pdf->setDescConcepto($descConcepto);
//$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(64, 128, 191);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetLineWidth(0.3);

if ($acueducto == 'SD') {
    $pdf->Image("../../images/LogoCaasd.jpg", 8, 5, 12, 15.5);
    $pdf->SetFont('times', "", 10);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Text(8, 25, utf8_decode("CAASD Gerencia Comercial"));
}
if ($acueducto == 'BC') {
    $pdf->Image("../../images/logo_coraabo.jpg", 8, 5, 40, 15.5);
    $pdf->SetFont('times', "", 10);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Text(8, 23, utf8_decode("CORAABO Gerencia Comercial"));
}
$pdf->SetFont('times', "", 8);
$pdf->Text(160, 8, utf8_decode('Fecha Impresion: '.date('d/m/Y')));
$pdf->SetFont('times', "", 10);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetY(35);
$pdf->SetX(15);
$pdf->Cell(180, 5, utf8_decode("Detalle Diario "), 1, 3, 'C', true);
$pdf->SetY(40);
$pdf->SetX(15);
$pdf->Cell(40, 5, utf8_decode("Código y descripción"), 1, 3, 'C', true);
$pdf->SetY(40);
$pdf->SetX(55);
$pdf->Cell(35, 5, utf8_decode("Nro. Pagos"), 1, 3, 'C', true);
$pdf->SetY(40);
$pdf->SetX(90);
$pdf->Cell(35, 5, utf8_decode("Importe recaudado"), 1, 3, 'C', true);
$pdf->SetY(40);
$pdf->SetX(125);
$pdf->Cell(35, 5, utf8_decode("Tipo"), 1, 3, 'C', true);
$pdf->SetY(40);
$pdf->SetX(160);
$pdf->Cell(35, 5, utf8_decode("Fecha"), 1, 3, 'C', true);

$a     = new Pagos();
$datos = $a->getPagosByconceptosFecha($acueducto, $idConcepto, $entIni, $entFin, $punIni, $punFin, $cajaIni, $cajaFin, $fechPagoIni, $fechPagoFin);

$y = 0;
while (oci_fetch($datos)) {

    if ($y > 210) {
        $pdf->AddPage();
        $y = 0;

        if ($acueducto == 'SD') {
            $pdf->Image("../../images/LogoCaasd.jpg", 8, 5, 12, 15.5);
            $pdf->SetFont('times', "", 10);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Text(8, 25, utf8_decode("CAASD Gerencia Comercial"));
        }
        if ($acueducto == 'BC') {
            $pdf->Image("../../images/logo_coraabo.jpg", 8, 5, 40, 15.5);
            $pdf->SetFont('times', "", 10);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Text(8, 23, utf8_decode("CORAABO Gerencia Comercial"));
        }
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetY(35);
        $pdf->SetX(15);
        $pdf->Cell(180, 5, utf8_decode("Detalle Diario "), 1, 3, 'C', true);
        $pdf->SetY(40);
        $pdf->SetX(15);
        $pdf->Cell(40, 5, utf8_decode("Código y descripción"), 1, 3, 'C', true);
        $pdf->SetY(40);
        $pdf->SetX(55);
        $pdf->Cell(35, 5, utf8_decode("Nro. Pagos"), 1, 3, 'C', true);
        $pdf->SetY(40);
        $pdf->SetX(90);
        $pdf->Cell(35, 5, utf8_decode("Importe recaudado"), 1, 3, 'C', true);
        $pdf->SetY(40);
        $pdf->SetX(125);
        $pdf->Cell(35, 5, utf8_decode("Tipo"), 1, 3, 'C', true);
        $pdf->SetY(40);
        $pdf->SetX(160);
        $pdf->Cell(35, 5, utf8_decode("Fecha"), 1, 3, 'C', true);
    }

    $totalPagos    = oci_result($datos, "TOTAL");
    $descConcepto  = oci_result($datos, "CONCEPTO");
    $cantidadPagos = oci_result($datos, "CANTIDAD");
    $tiporecaudo   = oci_result($datos, "TIPO");
    $fecha         = oci_result($datos, "FECHAPAGO");
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetY(45 + $y);
    $pdf->SetX(15);
    $pdf->Cell(40, 5, utf8_decode($descConcepto), 1, 3, 'C', false);
    $pdf->SetY(45 + $y);
    $pdf->SetX(55);
    $pdf->Cell(35, 5, utf8_decode($cantidadPagos), 1, 3, 'C', false);
    $pdf->SetY(45 + $y);
    $pdf->SetX(90);
    $pdf->Cell(35, 5, utf8_decode(round($totalPagos)), 1, 3, 'C', false);
    $pdf->SetY(45 + $y);
    $pdf->SetX(125);
    $pdf->Cell(35, 5, utf8_decode($tiporecaudo), 1, 3, 'C', false);
    $pdf->SetY(45 + $y);
    $pdf->SetX(160);
    $pdf->Cell(35, 5, utf8_decode($fecha), 1, 3, 'C', false);
    $y += 5;
    $cantpagos += $cantidadPagos;
    $total += $totalPagos;
    if ($tiporecaudo == 'PAGOS') {
        $cantpagostipo1 += $cantidadPagos;
        $totalpagostipo1 += $totalPagos;
        $tiporecaudo1 = 'PAGOS';
    }
    if ($tiporecaudo == 'OTROS RECAUDOS') {
        $cantpagostipo2 += $cantidadPagos;
        $totalpagostipo2 += $totalPagos;
        $tiporecaudo2 = 'OTROS RECAUDOS';
    }
}
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(64, 128, 191);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetY(45 + $y);
$pdf->SetX(15);
$pdf->Cell(40, 5, utf8_decode("Totales"), 1, 3, 'C', true);
$pdf->SetY(45 + $y);
$pdf->SetX(55);
$pdf->Cell(35, 5, $cantpagos, 1, 3, 'C', true);
$pdf->SetY(45 + $y);
$pdf->SetX(90);
$pdf->Cell(35, 5, round($total), 1, 3, 'C', true);
$pdf->SetY(45 + $y);
$pdf->SetX(125);
$pdf->Cell(35, 5, '', 1, 3, 'C', true);
$pdf->SetY(45 + $y);
$pdf->SetX(160);
$pdf->Cell(35, 5, '', 1, 3, 'C', true);

///RESUMEN GENERAL
$pdf->AddPage();
$pdf->SetTextColor(255, 255, 255);
$pdf->SetY(10);
$pdf->SetX(35);
$pdf->Cell(145, 5, utf8_decode("Resumen Mensual "), 1, 3, 'C', true);
$pdf->SetY(15);
$pdf->SetX(35);
$pdf->Cell(40, 5, utf8_decode("Código y descripción"), 1, 3, 'C', true);
$pdf->SetY(15);
$pdf->SetX(75);
$pdf->Cell(35, 5, utf8_decode("Nro. Pagos"), 1, 3, 'C', true);
$pdf->SetY(15);
$pdf->SetX(110);
$pdf->Cell(35, 5, utf8_decode("Importe recaudado"), 1, 3, 'C', true);
$pdf->SetY(15);
$pdf->SetX(145);
$pdf->Cell(35, 5, utf8_decode("Tipo"), 1, 3, 'C', true);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetY(20);
$pdf->SetX(35);
$pdf->Cell(40, 5, utf8_decode($descConcepto), 1, 3, 'C', true);
$pdf->SetY(20);
$pdf->SetX(75);
$pdf->Cell(40, 5, utf8_decode($cantpagostipo1), 1, 3, 'C', true);
$pdf->SetY(20);
$pdf->SetX(110);
$pdf->Cell(40, 5, utf8_decode($totalpagostipo1), 1, 3, 'C', true);
$pdf->SetY(20);
$pdf->SetX(145);
$pdf->Cell(35, 5, utf8_decode($tiporecaudo1), 1, 3, 'C', true);

$pdf->SetY(25);
$pdf->SetX(35);
$pdf->Cell(40, 5, utf8_decode($descConcepto), 1, 3, 'C', true);
$pdf->SetY(25);
$pdf->SetX(75);
$pdf->Cell(40, 5, utf8_decode($cantpagostipo2), 1, 3, 'C', true);
$pdf->SetY(25);
$pdf->SetX(110);
$pdf->Cell(40, 5, utf8_decode($totalpagostipo2), 1, 3, 'C', true);
$pdf->SetY(25);
$pdf->SetX(145);
$pdf->Cell(35, 5, utf8_decode($tiporecaudo2), 1, 3, 'C', true);

$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(64, 128, 191);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetY(30);
$pdf->SetX(35);
$pdf->Cell(40, 5, utf8_decode("Totales"), 1, 3, 'C', true);
$pdf->SetY(30);
$pdf->SetX(75);
$pdf->Cell(40, 5, utf8_decode($cantpagostipo1 + $cantpagostipo2), 1, 3, 'C', true);
$pdf->SetY(30);
$pdf->SetX(110);
$pdf->Cell(40, 5, utf8_decode($totalpagostipo1 + $totalpagostipo2), 1, 3, 'C', true);
$pdf->SetY(30);
$pdf->SetX(145);
$pdf->Cell(35, 5, '', 1, 3, 'C', true);

$pdf->Output("Libro.pdf", 'I');
