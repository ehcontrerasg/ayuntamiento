<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../../clases/class.corte.php';
ini_set('memory_limit', '-1');

$proyecto=$_POST['proyecto'];
$fecini=$_POST['fecini'];
$fecfin=$_POST['fecfin'];
$cont=$_POST['contratista'];


$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("RendCortes")
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Gerenciales");

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

//HOJA INMUEBLES MEDIDOS POR DIFERENCIA LECTURA

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:N1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:N1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:N2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:N1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:N2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'RENDIMIENTO DE CORTES '.$proyecto.' DE '.$fecini.' AL '.$fecfin)
    ->setCellValue('A2', 'USUARIO')
    ->setCellValue('B2', 'CORTES ASIGNADOS')
    ->setCellValue('C2', 'CORTES VISITADOS')
    ->setCellValue('D2', 'CORTES EFECTIVOS')
    ->setCellValue('E2', 'PAGOS X CORTES')
    ->setCellValue('F2', 'RENDIMIENTO CORTES')
    ->setCellValue('G2', 'PROMEDIO VISITAS CORTE DIA')
    ->setCellValue('H2', 'INSPECCIONES ASIGNADAS')
    ->setCellValue('I2', 'INSPECCIONES REALIZADAS')
    ->setCellValue('J2', 'PAGOS X INSPECCIONES')
    ->setCellValue('K2', 'PROMEDIO VISITAS INS DIA')
    ->setCellValue('L2', 'RENDIMIENTO INSPECCIONES')
    ->setCellValue('M2', 'TOTAL PAGOS')
    ->setCellValue('N2', 'PROMEDIO PAGOS DIA')

   ;
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10.71);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(18.14);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(17);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(17);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(15.4);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(27.4);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(24.2);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(24.5);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(24.4);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(21);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(26);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(20);


$c= new Corte();
$fila = 3;
$cantinm = 0;
$registros=$c->getRendCorteGerenciaByProyContFec($proyecto,$cont,$fecini,$fecfin);
while (oci_fetch($registros)) {


    $usu=utf8_decode(oci_result($registros,"USU"));
    $cortAsi=utf8_decode(oci_result($registros,"ASIGNADOS"));
    $insReal=utf8_decode(oci_result($registros,"INSPECCIONES"));
    $ins_asig=utf8_decode(oci_result($registros,"INSPECCIONES_ASI"));
    $cortVisi=utf8_decode(oci_result($registros,"VISITADOS"));
    $cortEfe=utf8_decode(oci_result($registros,"EFECTIVOS"));
    $pagXCort=utf8_decode(oci_result($registros,"PAGOS_CORT"));
    $pagXIns=utf8_decode(oci_result($registros,"PAGOS_INS"));
    $dias=utf8_decode(oci_result($registros,"DIAS"));
//
//
//    //$totalmtsinm = $cant1+$cant2+$cant3+$cant4+$cant5+$cant6+$cant7+$cant8+$cant9+$cant10+$cant11+$cant12+$cant13;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $usu)
        ->setCellValue('B'.$fila, $cortAsi)
        ->setCellValue('C'.$fila, $cortVisi)
        ->setCellValue('D'.$fila, $cortEfe)
        ->setCellValue('E'.$fila, $pagXCort)
        ->setCellValue('F'.$fila, '%'.round(($pagXCort/($cortEfe+0.0000001)*100),2) )
        ->setCellValue('G'.$fila, round($cortVisi/$dias,2))
        ->setCellValue('H'.$fila, $ins_asig)
        ->setCellValue('I'.$fila, $insReal)
        ->setCellValue('J'.$fila, $pagXIns)
        ->setCellValue('K'.$fila, round($insReal/$dias,2))
        ->setCellValue('L'.$fila, '%'.round(($pagXIns/($insReal+0.0000001)*100),2) )
        ->setCellValue('M'.$fila, $pagXCort+$pagXIns)
        ->setCellValue('N'.$fila, round(($pagXCort+$pagXIns)/$dias,2))
    ;
   $fila++;
}

$objPHPExcel->getActiveSheet(0)->setTitle('Rendimiento');

//mostrafr la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/rend cort-".$proyecto.'-del-'.$fecini.'-'.$fecfin.".xlsx";

$objWriter->save($nomarch);
echo $nomarch;

?>