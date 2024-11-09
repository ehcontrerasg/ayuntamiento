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
$periodo=$_POST['periodo'];
$inmueble=$_POST['inmueble'];

include "../../clases/class.factura.php";
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle(utf8_encode("Reporte Relación Facturación ").$proyecto)
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Relación Facturación");

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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:G1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:G1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:G2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:G2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(25);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'RELACIÓN FACTURACIÓN INMUEBLE '.$inmueble.' PERIODO '.$periodo)
    ->setCellValue('A2', 'INMUEBLE')
    ->setCellValue('B2', 'RD$ FACTURADO AÑO PASADO')
    ->setCellValue('C2', 'RD$ FACTURADO MES PASADO')
    ->setCellValue('D2', 'RD$ FACTURADO MES ACTUAL')
    ->setCellValue('E2', 'MT3 FACTURADO AÑO PASADO')
    ->setCellValue('F2', 'MT3 FACTURADO MES PASADO')
    ->setCellValue('G2', 'MT3 FACTURADO MES ACTUAL');

$fila = 3;
//$totales04=0;
$a =new Factura();
$datos=$a->getRelacionFacturacionMesAct($periodo,$inmueble);
while (oci_fetch($datos)){
    $facturado=oci_result($datos,"TOTAL");
    $inmueble=oci_result($datos,"INMUEBLE");

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $inmueble)
        ->setCellValue('D'.$fila, $facturado);
    //$fila++;
    //$totales04 = $totales04 + $valor;
}oci_free_statement($datos);

$a =new Factura();
$datos=$a->getRelacionFacturacionMesAnt($periodo,$inmueble);
while (oci_fetch($datos)){
    $facturado=oci_result($datos,"TOTAL");
    $inmueble=oci_result($datos,"INMUEBLE");

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $inmueble)
        ->setCellValue('C'.$fila, $facturado);
    //$fila++;
    //$totales04 = $totales04 + $valor;
}oci_free_statement($datos);

$a =new Factura();
$datos=$a->getRelacionFacturacionAnoAnt($periodo,$inmueble);
while (oci_fetch($datos)){
    $facturado=oci_result($datos,"TOTAL");
    $inmueble=oci_result($datos,"INMUEBLE");

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $inmueble)
        ->setCellValue('B'.$fila, $facturado);
    //$fila++;
    //$totales04 = $totales04 + $valor;
}oci_free_statement($datos);

$a =new Factura();
$datos=$a->getRelacionMetrosMesAct($periodo,$inmueble);
while (oci_fetch($datos)){
    $facturado=oci_result($datos,"UNIDADES");
    $inmueble=oci_result($datos,"COD_INMUEBLE");

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $inmueble)
        ->setCellValue('G'.$fila, $facturado);
    //$fila++;
    //$totales04 = $totales04 + $valor;
}oci_free_statement($datos);

$a =new Factura();
$datos=$a->getRelacionMetrosMesAnt($periodo,$inmueble);
while (oci_fetch($datos)){
    $facturado=oci_result($datos,"UNIDADES");
    $inmueble=oci_result($datos,"COD_INMUEBLE");

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $inmueble)
        ->setCellValue('F'.$fila, $facturado);
    //$fila++;
    //$totales04 = $totales04 + $valor;
}oci_free_statement($datos);

$a =new Factura();
$datos=$a->getRelacionMetrosAnoAnt($periodo,$inmueble);
while (oci_fetch($datos)){
    $facturado=oci_result($datos,"UNIDADES");
    $inmueble=oci_result($datos,"COD_INMUEBLE");

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $inmueble)
        ->setCellValue('E'.$fila, $facturado);
    //$fila++;
    //$totales04 = $totales04 + $valor;
}oci_free_statement($datos);





/*$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":G".$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':E'.$fila);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'TOTAL')
    ->setCellValue('F'.$fila, $totales04);*/

$objPHPExcel->getActiveSheet()->setTitle('Facturación');

$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Relacion_Facturacion_".$inmueble."_".time().".xlsx";
$objWriter->save($nomarch);
echo $nomarch;
?>