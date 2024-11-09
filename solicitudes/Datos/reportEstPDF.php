<?php
header('Content-Type: text/html; charset=utf-8');
include_once '../fpdf/fpdf.php';
include_once '../Clases/reports.php';

$pdf        = new FPDF();
$v          = new Reports();
$department = $_GET['department'];
$fecha_ini  = $_GET['fecha_ini'];
$fecha_fin  = $_GET['fecha_fin'];

$pdf->AddPage();

$pdf->SetDrawColor(0, 0, 0);

$pdf->SetLineWidth(0.2);

//$pdf->Image('../img/acea_dominicana.png', 15, 20, 23);
$pdf->Image('../../images/aceadom201904.png', 15, 20, 23);

$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetXY(39.1, 12);
$pdf->Cell(140.7, 22.3, utf8_decode('ESTADÍSTICAS DE SOLICITUD DE CAMBIOS Y MEJORAS DEL SISTEMA'), 0, 0, 'C');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Text(100, 29.3, utf8_decode($fecha_ini . ' a ' . $fecha_fin));
$pdf->Text(19, 50, utf8_decode('Detalles'));

$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetXY(18, 55);
$pdf->SetFillColor(0, 158, 227);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(40, 5, utf8_decode('Solicitudes Totales'), 1, 0, 'C', true);
$pdf->SetXY(18, 62);
$pdf->Cell(40, 5, utf8_decode('Solicitudes Realizadas'), 1, 0, 'C', true);
$pdf->SetXY(18, 69);
$pdf->Cell(40, 5, utf8_decode('Solicitudes Pendientes'), 1, 0, 'C', true);
$pdf->SetXY(18, 76);
$pdf->Cell(40, 5, utf8_decode('Solicitudes Rechazadas'), 1, 0, 'C', true);
$pdf->SetXY(18, 83);
$pdf->Cell(40, 5, utf8_decode('Solicitudes Anuladas'), 1, 0, 'C', true);

$pdf->SetXY(130, 55);
$pdf->Cell(40, 5, utf8_decode('Terminadas a Tiempo'), 1, 0, 'C', true);
$pdf->SetXY(130, 62);
$pdf->Cell(40, 5, utf8_decode('Terminadas a Destiempo'), 1, 0, 'C', true);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);

$data       = $v->getDataReportDetPdf($department, $fecha_ini, $fecha_fin);
$totales    = $data['TOTALES'];
$terminadas = $data['TERMINADAS'];
$a_tiempo   = $data['A_TIEMPO'];
$anuladas   = $data['ANULADAS'];
$rechazadas = $data['RECHAZADAS'];

$pdf->SetXY(58.1, 55);
$pdf->Cell(15, 5, utf8_decode($totales), 1, 0, 'C');
$pdf->SetXY(58.1, 62);
$pdf->Cell(15, 5, utf8_decode($terminadas), 1, 0, 'C');
$pdf->SetXY(58.1, 69);
$pdf->Cell(15, 5, utf8_decode($totales - $terminadas), 1, 0, 'C');
$pdf->SetXY(58.1, 76);
$pdf->Cell(15, 5, utf8_decode($rechazadas), 1, 0, 'C');
$pdf->SetXY(58.1, 83);
$pdf->Cell(15, 5, utf8_decode($anuladas), 1, 0, 'C');

$pdf->SetXY(170.1, 55);
$pdf->Cell(15, 5, utf8_decode($a_tiempo), 1, 0, 'C');
$pdf->SetXY(170.1, 62);
$pdf->Cell(15, 5, utf8_decode($terminadas - $a_tiempo), 1, 0, 'C');

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetXY(85, 99);
$pdf->SetFillColor(0, 158, 227);
$pdf->SetTextColor(255, 255, 255);

$pdf->Cell(40, 15, utf8_decode('RESULTADOS'), 1, 0, 'C', true);

$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(85, 114);
$pdf->Cell(40, 15, round((($terminadas - ($terminadas - $a_tiempo)) / $terminadas) * 100, 0) . '%', 1, 0, 'C');

$pdf->Output("Solicitud de Cambios y Mejoras.pdf", 'I');
