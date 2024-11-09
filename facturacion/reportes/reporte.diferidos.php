<?php
/**
 * Created by PhpStorm.
 * User: Jgutierrez
 * Date: 6/25/2016
 * Time: 11:51 AM
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
setlocale(LC_MONETARY, 'es_DO');
//$descarga=$_POST['descarga'];
$proyecto=$_POST['proyecto'];
$periodo=$_POST['periodo'];
//$zona=$_POST['zona'];
//$raiz=$_POST['raizNCF'];



//include "../../clases/class.pdfRep.php";
include "../../clases/class.factura.php";
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';

$a =new Factura();
$datos=$a->getDifDet($proyecto,$periodo);


$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle(utf8_encode("Reporte Diferidos ").$proyecto)
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Diferidos");

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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:C1');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E1:F1');

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:C2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:C2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('E1:F2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("E1:F2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'DETALLE DIFERIDOS PERIODO '.$periodo)
    ->setCellValue('A2', 'Inmueble')
    ->setCellValue('B2', 'Concepto Diferido')
    ->setCellValue('C2', 'Monto RD$')
    ->setCellValue('E1', 'RESUMEN DIFERIDOS PERIODO '.$periodo)
    ->setCellValue('E2', 'Concepto Diferido')
    ->setCellValue('F2', 'Monto RD$');

$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(30);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(50);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(30);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(20);

$fila = 3;
$totales=0;
while (oci_fetch($datos)){
    $inmueble=oci_result($datos,"INMUEBLE");
    $concepto=oci_result($datos,"DESC_SERVICIO");
    $valor=oci_result($datos,"VALOR_DIFERIDO");

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $inmueble)
        ->setCellValue('B'.$fila, $concepto)
        ->setCellValue('C'.$fila, $valor);
    $fila++;
    $totales = $totales + $valor;
}oci_free_statement($datos);

$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":C".$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':B'.$fila);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'TOTALES')
    ->setCellValue('C'.$fila, $totales);

$a =new Factura();
$datos=$a->getDifRes($proyecto,$periodo);



$filar = 3;
$totalesr=0;

while (oci_fetch($datos)){
    $conceptor=oci_result($datos,"DESC_SERVICIO");
    $valorr=oci_result($datos,"TOTAL");

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('E'.$filar, $conceptor)
        ->setCellValue('F'.$filar, $valorr);
    $filar++;
    $totalesr = $totalesr + $valorr;
}oci_free_statement($datos);

$objPHPExcel->setActiveSheetIndex(0)->getStyle("E".$filar.":F".$filar)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('E'.$filar, 'TOTALES')
    ->setCellValue('F'.$filar, $totalesr);


$objPHPExcel->getActiveSheet()->setTitle('Listado Inmubles');
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Diferidos_".$periodo.".xlsx";
$objWriter->save($nomarch);
echo $nomarch;
