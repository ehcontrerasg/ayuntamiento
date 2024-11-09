<?php
/**
 * Created by PhpStorm.
 * User: Jgutierrez
 * Date: 20/09/2022
 * Time: 11:51 AM
 */
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
setlocale(LC_MONETARY, 'es_DO');

$proyecto=$_POST['proyecto'];
$fecini=$_POST['fecini'];
$fecfin=$_POST['fecfin'];

include "../../clases/class.factura.php";
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle(utf8_encode("Reporte Solicitud B04 ").$proyecto)
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Solicitud NCF B04");

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
    )
);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:K1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:K1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:K1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:K2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:K2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(12);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(30);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(35);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(160);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(30);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'SOLICITUD NCF B04 '.$proyecto)
    ->setCellValue('A2', 'Tipo Nota')
    ->setCellValue('B2', 'Inmueble')
    ->setCellValue('C2', 'No. PQR')
    ->setCellValue('D2', 'Fecha PQR')
    ->setCellValue('E2', 'Nombre')
    ->setCellValue('F2', 'Total Factura')
    ->setCellValue('G2', 'NCF Factura')
    ->setCellValue('H2', 'Monto Nota')
    ->setCellValue('I2', 'Motivo')
    ->setCellValue('J2', 'Descripción PQR')
    ->setCellValue('K2', 'Auxiliar');

$fila = 3;
$totales04=0;
$a =new Factura();
$datos=$a->getSolNcfB04($proyecto,$fecini,$fecfin);
while (oci_fetch($datos)){
    $tipoNota=oci_result($datos,"TIPO_NOTA");
    $inmueble=oci_result($datos,"CODIGO_INM");
    $codPqr=oci_result($datos,"CODIGO_PQR");
    $fecPqr=oci_result($datos,"FECHA_PQR");
    $cliente=oci_result($datos,"ALIAS");
    $totFac=oci_result($datos,"TOTAL_FACTURA");
    $ncfFac=oci_result($datos,"NCF");
    $monNot=oci_result($datos,"MONTO_NOTA");
    $motivo=oci_result($datos,"MOTIVO");
    $descripcion=oci_result($datos,"DESCRIPCION");
    $auxiliar=oci_result($datos,"AUXILIAR");

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $tipoNota)
        ->setCellValue('B'.$fila, $inmueble)
        ->setCellValue('C'.$fila, $codPqr)
        ->setCellValue('D'.$fila, $fecPqr)
        ->setCellValue('E'.$fila, $cliente)
        ->setCellValue('F'.$fila, $totFac)
        ->setCellValue('G'.$fila, $ncfFac)
        ->setCellValue('H'.$fila, $monNot)
        ->setCellValue('I'.$fila, $motivo)
        ->setCellValue('J'.$fila, $descripcion)
        ->setCellValue('K'.$fila, $auxiliar);
    $fila++;
    //$totales04 = $totales04 + $valor;
}oci_free_statement($datos);

/*$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":G".$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':E'.$fila);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'TOTAL')
    ->setCellValue('F'.$fila, $totales04);*/

$objPHPExcel->getActiveSheet()->setTitle('B04');

$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Solicitud_B04_".$proyecto."_".time().".xlsx";
$objWriter->save($nomarch);
echo $nomarch;
?>