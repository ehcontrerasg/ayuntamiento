<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../../clases/class.reconexion.php';
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
    ->setTitle("RendReconexiones")
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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:D1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:D2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:D1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:D2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'RENDIMIENTO DE RECONEXIONES '.$proyecto.' DE '.$fecini.' AL '.$fecfin)
    ->setCellValue('A2', 'USUARIO')
    ->setCellValue('B2', 'RECONEXIONES ASIGNADAS')
    ->setCellValue('C2', 'RECONEXIONES VISITADAS')
    ->setCellValue('D2', 'PROMEDIO VISITAS RECONEXION DIA')


   ;
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(34);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(34);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(34);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(34);

$c= new Reconexion();
$fila = 3;
$cantinm = 0;
$registros=$c->getRendRecGerenciaByProyContFec($proyecto,$cont,$fecini,$fecfin);
while (oci_fetch($registros)) {


    $usu=utf8_decode(oci_result($registros,"USU"));
    $cortAsi=utf8_decode(oci_result($registros,"ASIGNADOS"));
    $cortVisi=utf8_decode(oci_result($registros,"VISITADOS"));
    $dias=utf8_decode(oci_result($registros,"DIAS"));
//
//
//    //$totalmtsinm = $cant1+$cant2+$cant3+$cant4+$cant5+$cant6+$cant7+$cant8+$cant9+$cant10+$cant11+$cant12+$cant13;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $usu)
        ->setCellValue('B'.$fila, $cortAsi)
        ->setCellValue('C'.$fila, $cortVisi)
        ->setCellValue('D'.$fila, round($cortVisi/$dias,2))
         ;
   $fila++;
}

$objPHPExcel->getActiveSheet(0)->setTitle('Rendimiento');

//mostrafr la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/rend rec-".$proyecto.'-del-'.$fecini.'-'.$fecfin.".xlsx";

$objWriter->save($nomarch);
echo $nomarch;

?>