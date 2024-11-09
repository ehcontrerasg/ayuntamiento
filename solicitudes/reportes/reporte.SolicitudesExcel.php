<?php
require_once '../../recursos/PHPExcel.php';
include '../../clases/class.solicitudes.php';
include '../Clases/reports.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');

$departamento =  $_GET["department"];
$fechaInicio = $_GET["fecha_ini"];
$fechaFin = $_GET["fecha_fin"];
$estado = $_GET["estado"];

$fecha_edicion   = "";
$numero_edicion  = "";
$logo_formulario = "";

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Reportes Solicitudes")
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Solicitudes");

$estiloTitulos = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => '000000')
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'   =>PHPExcel_Style_Alignment::VERTICAL_CENTER
    ),
    'font' => array(
        'bold' => true
    )
);

$objPHPExcel->getActiveSheet()->getStyle("A:J")->getFont()->setSize(12);

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:J6')->applyFromArray($estiloTitulos);

/*$r = new Reports();

$datos_res = $r->getFormDates("FO-TIC-05");

while($row = oci_fetch_assoc($datos_res)){


    if(strtotime(date("d-m-y")) >= strtotime($row["FECHA_EMISION"])){

        $fecha_edicion   = $row["FECHA_EMISION"];
        $numero_edicion  = $row["EDICION"];
        $logo_formulario = $row["IMAGEN"];
    }
}*/


$objPHPExcel->setActiveSheetIndex(0)
   /* ->setCellValue('A1', 'ACEA DOMINICANA')*/
    ->setCellValue('B1', 'CONTROL DE REQUERIMIENTOS DE CAMBIOS Y MEJORAS DEL SISTEMA')
    ->setCellValue('J1', 'Código: FO-TIC-05')
    ->setCellValue('J3', 'Edición No.: 3 '/*.$numero_edicion*/)
    ->setCellValue('J5', 'Fecha de emisión: 2019-04-10'/*.$fecha_edicion*/);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:A6');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:A6');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B1:I6');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J1:J2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J3:J4');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J5:J6');

$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(24);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(12.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(22.43);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(21.14);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(21.86);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(21.29);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(21.29);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(35.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(35.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(49.71);


$estiloCabeceraTabla = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER_CONTINUOUS,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => '82d9ed')
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => '000000')
        )
    )
);

$objPHPExcel->setActiveSheetIndex(0)->getStyle("A10:J10")->applyFromArray($estiloCabeceraTabla);
$gdImage = imagecreatefromjpeg(/*$logo_formulario*/'../../images/aceadom201904.jpg');

$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
$objDrawing->setImageResource($gdImage);
$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
/*$objDrawing->setHeight(10);*/
$objDrawing->setCoordinates('A2');

$objPHPExcel->getActiveSheet()->getStyle('A10:J10')
    ->getAlignment()->setWrapText(true);

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte solicitudes TI');
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A8', 'Fecha de actualización')
    ->setCellValue('A10', 'Departamento')
    ->setCellValue('B10', 'Solicitud No.')
    ->setCellValue('C10', 'Fecha solicitud')
    ->setCellValue('D10', 'Fecha de Aprobación')
    ->setCellValue('E10', 'Fecha de Recepción')
    ->setCellValue('F10', 'Fecha de Compromiso')
    ->setCellValue('G10', 'Fecha de Conclusión')
    ->setCellValue('H10', 'Estado')
    ->setCellValue('I10', 'Valida solicitante')
    ->setCellValue('J10', 'Observaciones');


$s=new Solicitudes();
$fechaActualizacion= $s->getUltimaActualizacion();
while (oci_fetch($fechaActualizacion)) {
    $fecha = oci_result($fechaActualizacion, 'ULTIMA_ACTUALIZACION');
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B8', $fecha);
}oci_free_statement($fechaActualizacion);

$l=new Solicitudes();
$registros=$l->getSolicitudes($departamento,$fechaInicio,$fechaFin, $estado);
$fila = 11;
while (oci_fetch($registros)) {
    $idSolicitud=oci_result($registros, 'ID_SCMS');
    $departamento = oci_result($registros, 'DEPARTAMENTO');
    $fechaSolicitud = oci_result($registros, 'FECHA_SOLICITUD');
    $fechaAprobacion = oci_result($registros, 'FECHA_APROBACION');
    $fechaRecepcion = oci_result($registros, 'FECHA_RECEPCION');
    $fechaCompromiso = oci_result($registros, 'FECHA_COMPROMISO');
    $fechaConclusion = oci_result($registros, 'FECHA_CONCLUSION');
    $estadoSolicitud = oci_result($registros, 'ESTADO');
    $valida_solicitante = oci_result($registros, 'VALIDA_SOLICITANTE');
    $descripcion = oci_result($registros, 'DESCRIPCION');


    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $departamento)
        ->setCellValue('B'.$fila, $idSolicitud)
        ->setCellValue('C'.$fila, $fechaSolicitud)
        ->setCellValue('D'.$fila, $fechaAprobacion)
        ->setCellValue('E'.$fila, $fechaRecepcion)
        ->setCellValue('F'.$fila, $fechaCompromiso)
        ->setCellValue('G'.$fila, $fechaConclusion)
        ->setCellValue('H'.$fila, $estadoSolicitud)
        ->setCellValue('I'.$fila, $valida_solicitante)
        ->setCellValue('J'.$fila, $descripcion);
    $fila++;
}oci_free_statement($registros);

//mostrafr la hoja q se abrira
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Reporte_SOLICITUDES_".$fechaInicio.$fechaFin.".xls";
$objWriter->save($nomarch);
echo $nomarch;
