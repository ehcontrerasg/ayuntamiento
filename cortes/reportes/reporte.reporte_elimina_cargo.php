<?php
/**
 * Created by PhpStorm.
 * User: Jesus Gutierrez
 * Date: 6/25/2016
 * Time: 11:51 AM
 */
setlocale(LC_MONETARY, 'es_DO');
$proyecto = $_POST['proyecto'];
$fecini = $_POST['fecini'];
$fecfin = $_POST['fecfin'];

ini_set('memory_limit', '-1');

//include "../../clases/class.pdfRep.php";
//include "../../clases/class.inmueble.php";
include "../../clases/class.corte.php";
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';

$a = new Corte();

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle(utf8_encode("Reporte Cargos Reconexion Eliminados ") . $proyecto)
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Cortes");

$estiloTitulos = array(
    'borders'   => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => '000000'),
        ),
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    ),
);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:I1');

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:I2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:I2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'CARGOS RECONEXION ELIMINADOS')
    ->setCellValue('A2', 'Código')
    ->setCellValue('B2', 'Fecha Reversión')
    ->setCellValue('C2', 'Sector')
    ->setCellValue('D2', 'Ruta')
    ->setCellValue('E2', 'Catastro')
    ->setCellValue('F2', 'Proceso')
    ->setCellValue('G2', 'Usuario Reversión')
    ->setCellValue('H2', 'Fac. Vencidas')
    ->setCellValue('I2', 'Categoría');

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(25);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(40);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(40);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(35);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(15);

    $fila = 3;
    $datos = $a->repEliCarRec($proyecto, $fecini, $fecfin);
    while (oci_fetch($datos)) {
        $inmueble       = oci_result($datos, "CODIGO_INM");
        $fecrever       = oci_result($datos, "FECHA_REVERSION");
        $sector         = oci_result($datos, "ID_SECTOR");
        $ruta           = oci_result($datos, "ID_RUTA");
        $catastro       = oci_result($datos, "CATASTRO");
        $proceso        = oci_result($datos, "ID_PROCESO");
        $usrrever       = oci_result($datos, "USR_REVERSION");
        $facvencidas    = oci_result($datos, "FAC_VENCIDAS");
        $categoria      = oci_result($datos, "CATEGORIA");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $inmueble)
            ->setCellValue('B' . $fila, $fecrever)
            ->setCellValue('C' . $fila, $sector)
            ->setCellValue('D' . $fila, $ruta)
            ->setCellValue('E' . $fila, $catastro)
            ->setCellValue('F' . $fila, $proceso)
            ->setCellValue('G' . $fila, $usrrever)
            ->setCellValue('H' . $fila, $facvencidas)
            ->setCellValue('I' . $fila, $categoria);
        $fila++;
    }
    oci_free_statement($datos);

    $objPHPExcel->getActiveSheet()->setTitle('Maestro');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch   = "../../temp/Cargos_Reconexion_Eliminados.xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;

