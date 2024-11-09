<?php
header('Content-Type: text/html; charset=utf-8');
include_once '../fpdf/fpdf.php';
include_once '../Clases/reports.php';

$pdf                = new FPDF();
$v                  = new Reports();
$id                 = $_GET['id'];
$edicion_formulario = "";
$fecha_edicion      = "";
$imagen             = "";

$pdf->AddPage();

$pdf->SetDrawColor(0, 0, 0);

$pdf->SetLineWidth(0.2);

$pdf->Rect(13.8, 12, 25.2, 22.3);
$pdf->Rect(162.8, 24.8, 38.3, 9.5);

$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(0, 0, 0);

$pdf->SetXY(39.1, 12);
$pdf->Cell(123.7, 22.3, 'SOLICITUD DE CAMBIOS Y MEJORAS DEL SISTEMA', 1, 0, 'C');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetXY(162.8, 12);
$pdf->Cell(38.3, 6.3, utf8_decode('Código:'), 1, 0);
$pdf->SetXY(162.8, 18.3);
$pdf->Cell(38.3, 6.3, utf8_decode('Edición No.:'), 1, 0);
$pdf->SetXY(162.8, 25);
$pdf->Cell(38.3, 4.3, utf8_decode('Fecha de emisión:'), 0, 0);

$pdf->SetFont('helvetica', '', 10);
$pdf->Text(178, 16.5, 'FO-TIC-03');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Text(19, 50, utf8_decode('Información de la Solicitud'));

$pdf->SetFont('helvetica', 'B', 9);
$pdf->Text(18, 59, utf8_decode('Solicitado Por '));
$pdf->Text(18, 67, utf8_decode('Departamento '));
$pdf->Text(18, 75, utf8_decode('Módulo '));

$pdf->Text(113, 59, utf8_decode('Solicitud Número '));
$pdf->Text(113, 67, utf8_decode('Fecha Solicitud '));
$pdf->Text(113, 75, utf8_decode('Firma Encargado '));

$r      = new Reports();
$report = $r->getDataReportPdf($id);

// Datos de la informacion de la solicitud

$pdf->SetFont('helvetica', '', 9);
$pdf->Text(50, 59, utf8_decode($report['SOLICITADOR']));
$pdf->Text(50, 67, utf8_decode($report['DEPARTAMENTO']));
$pdf->Text(50, 75, utf8_decode($report['MODULO']));

$pdf->Text(146, 59, utf8_decode($report['ID']));
$pdf->Text(146, 67, utf8_decode($report['FECHA_SOLICITUD']));
$pdf->Text(146, 75, utf8_decode($report['SOLICITADOR']));

$reemplazo_slashes = str_replace('/', '-', $report['FECHA_SOLICITUD']);
//ECHO "FECHA_SOLICITUD: ". $reemplazo_slashes;
$fecha_solicitud     = strtotime($reemplazo_slashes);

//echo "FECHA_SOLICITUD: ". $fecha_solicitud;
//TRAER LAS EDICIONES DEL FORMULARIO
$ediciones = $v->getFormDates('FO-TIC-03');
$edicion_formulario ="";
 $fecha_edicion     ="";
 $imagen            ="";
while($row = oci_fetch_assoc($ediciones)){
   // echo "FECHA_EMISION: ".strtotime($row["FECHA_EMISION"]);

    $reemplazo_slashes = str_replace('/', '-', $row["FECHA_EMISION"]);
    //ECHO "FECHA EMISION: ".  date('d-M-Y',$reemplazo_slashes);
    $fecha_emision     = strtotime($reemplazo_slashes);

    if($fecha_solicitud>=$fecha_emision){
        $edicion_formulario = $row["EDICION"];
        $fecha_edicion      = $row["FECHA_EMISION"];
        $imagen             = $row["IMAGEN"];

        //echo $edicion_formulario." ".$fecha_edicion;
    }
}
    $pdf->Image($imagen, 15, 20, 23);
    $pdf->Text(186, 22.5, $edicion_formulario);
    //$fecha_edicion = strtotime($fecha_edicion);
    //$fecha_edicion = date('d-m-Y',$fecha_edicion);
    $pdf->Text(163.8, 32.5, $fecha_edicion);

// Fin Datos de la informacion de la solicitud
$pdf->Line(48.5, 60, 95.3, 60);
$pdf->Line(48.5, 68, 95.3, 68);
$pdf->Line(48.5, 76, 95.3, 76);
$pdf->Line(144.5, 60, 191.3, 60);
$pdf->Line(144.5, 68, 191.3, 68);
$pdf->Line(144.5, 76, 191.3, 76);

if ($vEdition) {
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->Text(18, 83, utf8_decode('Fecha Aprobación '));
    $pdf->Text(108, 83, utf8_decode('Firma de Aprobación '));

    $pdf->SetFont('helvetica', '', 9);
    $pdf->Text(50, 83, utf8_decode($report['FECHA_CALIDAD']));
    $pdf->Text(146, 83, utf8_decode($report['USR_CALIDAD']));

    $pdf->Line(48.5, 84, 95.3, 84);
    $pdf->Line(144.5, 84, 191.3, 84);
}

$pdf->SetFont('helvetica', 'B', 10);

$vEdition == 1 ? $y = 8 : $y = 0;

$pdf->Text(19, $y + 85, 'Prioridad');

$pdf->Rect(19, $y + 89, 172, 12);

// Aqui va la variable de Prioridad
switch ($report['PRIORIDAD']) {
    case '1':
        $p1 = 'F';
        break;
    case '2':
        $p3 = 'F';
        break;
    case '3':
        $p2 = 'F';
        break;
    case '4':
        $p4 = 'F';
        break;
}
$pdf->Rect(57, $y + 93, 3.5, 3.5, $p1);
$pdf->Rect(95.8, $y + 93, 3.5, 3.5, $p2);
$pdf->Rect(137.8, $y + 93, 3.5, 3.5, $p3);
/*$pdf->Rect(152.6, $y + 93, 3.5, 3.5, $p4);*/

$pdf->SetFont('helvetica', '', 9);
$pdf->Text(63.7, $y + 95.8, 'Alta');
$pdf->Text(102.5, $y + 95.8, 'Media');
$pdf->Text(144.5, $y + 95.8, 'Baja');
/*$pdf->Text(159.3, $y + 95.8, 'En espera');*/

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Text(19, $y + 113.7, 'Tipo de Requerimiento');

$pdf->SetFont('helvetica', '', 9);

// Variable de Tipo de Requerimiento
switch ($report['REQUERIMIENTO']) {
    case '1':
        $r1 = 'F';
        break;
    case '2':
        $r3 = 'F';
        break;
    case '3':
        $r5 = 'F';
        break;
    case '4':
        $r7 = 'F';
        break;
    case '5':
        $r2 = 'F';
        break;
    case '6':
        $r4 = 'F';
        break;
    case '7':
        $r6 = 'F';
        break;
    case '8':
        $r8 = 'F';
        break;
}

$pdf->Rect(19, $y + 121.8, 3.5, 3.5, $r1);
$pdf->Rect(19, $y + 128, 3.5, 3.5, $r2);

$pdf->Rect(61, $y + 121.8, 3.5, 3.5, $r3);
$pdf->Rect(61, $y + 128, 3.5, 3.5, $r4);

$pdf->Rect(108, $y + 121.8, 3.5, 3.5, $r5);
$pdf->Rect(108, $y + 128, 3.5, 3.5, $r6);

$pdf->Rect(159, $y + 121.8, 3.5, 3.5, $r7);
$pdf->Rect(159, $y + 128, 3.5, 3.5, $r8);

$pdf->Text(26, $y + 124.5, 'Falla Proceso');
$pdf->Text(26, $y + 131, 'Mejoras de Procesos');
$pdf->Text(68, $y + 124.5, 'Falla Reporte');
$pdf->Text(68, $y + 131, 'Mejoramiento de Reporte');
$pdf->Text(115, $y + 124.5, 'Falla de Pantalla');
$pdf->Text(115, $y + 131, 'Mejoramiento de Pantalla');
$pdf->Text(166, $y + 124.5, 'Nueva Pantalla');
$pdf->Text(166, $y + 131, 'Nuevo Reporte');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Text(19, $y + 142.2, utf8_decode('Observación del Requerimiento'));

$pdf->SetFont('helvetica', '', 9);
$pdf->SetXY(21, $y + 147.9);
// Aqui la informacion de la observacion.
$pdf->MultiCell(173.7, 5, utf8_decode($report['DESCRIPCION']), 0);
$pdf->Rect(19, $y + 145.9, 177.7, 47.7);

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Text(19, $y + 210.7, utf8_decode('Uso Exclusivo del Área de Tecnología'));

$pdf->SetFont('arial', '', 9);
$pdf->Text(19, $y + 218.2, utf8_decode('Fecha Recepción'));
$pdf->Text(19, $y + 227.7, 'Fecha Compromiso');
$pdf->Text(19, $y + 237.2, 'Desarrollado por ');

$pdf->Text(114.1, $y + 218.2, 'Revisado por');
$pdf->Text(114.1, $y + 227.7, 'Validado Por ');
$pdf->Text(114.1, $y + 237.2, utf8_decode('Fecha Conclusión '));

// Informacion del area de tecnologia
$pdf->Text(52.7, $y + 218.3, $report['FECHA_CALIDAD']);
$pdf->Text(52.7, $y + 227.3, $report['FECHA_COMPROMISO']);
$pdf->Text(52.7, $y + 237.3, utf8_decode($report['DESARROLLADOR']));

if ($report['VALIDA_SOLICITANTE'] == 'S') {
    $solicitador = $report['SOLICITADOR'];
}
$pdf->Text(145.7, $y + 218.3, utf8_decode($report['USR_REVISION']));
$pdf->Text(145.7, $y + 227.3, utf8_decode($report['USR_CALIDAD']));
$pdf->Text(145.7, $y + 237.3, $report['FECHA_CONCLUCION']);

$pdf->Line(50.7, $y + 219.3, 96.2, $y + 219.3);
$pdf->Line(50.7, $y + 228.6, 96.2, $y + 228.6);
$pdf->Line(50.7, $y + 238.1, 96.2, $y + 238.1);

$pdf->Line(143.9, $y + 219.3, 189.4, $y + 219.3);
$pdf->Line(143.9, $y + 228.6, 189.4, $y + 228.6);
$pdf->Line(143.9, $y + 238.1, 189.4, $y + 238.1);

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Text(19, $y + 243.5, 'Comentario');
$pdf->SetXY(21, $y + 247.4);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(173.7, 5, utf8_decode($report['COMENTARIO_ENCARGADO_TI']), 0);
$pdf->Rect(19, $y + 247.4, 177.7, 12.9);

if (!$vEdition) {
    $pdf->SetFont('arial', 'B', 7);
    $pdf->Text(19, 265, 'Nota: Todas las solicitudes de mejoras deben tener Anexo, un Printscreen del formulario o reporte a modificar.');

    $pdf->Line(19, 267.3, 196.5, 267.3);
}

$pdf->Output("Solicitud de Cambios y Mejoras.pdf", 'I');
