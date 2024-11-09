<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../../clases/class.lectura.php';
ini_set('memory_limit', '-1');

$proyecto=$_POST['proyecto'];
$fecini=$_POST['fecini'];
$fecfin=$_POST['fecfin'];


$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("RendLecturas")
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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:E1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:E2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:E1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:E2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'RENDIMIENTO DE LECTURAS '.$proyecto.' DE '.$fecini.' AL '.$fecfin)
    ->setCellValue('A2', 'USUARIO')
    ->setCellValue('B2', 'VISITADOS')
    ->setCellValue('C2', 'LEIDOS')
    ->setCellValue('D2', 'ERRORES DE LECTURA')
    ->setCellValue('E2', 'LECTURAS PROMEDIO POR DIA')
   ;
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(12);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(7);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(27);


$c= new Lectura();
$fila = 3;
$cantinm = 0;
$registros=$c->gtRendLctByProFec($proyecto, $fecini,$fecfin);
while (oci_fetch($registros)) {


    $usu=utf8_decode(oci_result($registros,"LOGIN"));
    $visitados=utf8_decode(oci_result($registros,"VISITADOS"));
    $leidos=utf8_decode(oci_result($registros,"LEIDOS"));
    $errorLect=utf8_decode(oci_result($registros,"ERRORES_LECT"));
    $prom=utf8_decode(oci_result($registros,"PROMEDIO_DIA"));


    //$totalmtsinm = $cant1+$cant2+$cant3+$cant4+$cant5+$cant6+$cant7+$cant8+$cant9+$cant10+$cant11+$cant12+$cant13;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $usu)
        ->setCellValue('B'.$fila, $visitados)
        ->setCellValue('C'.$fila, $leidos)
        ->setCellValue('D'.$fila, $errorLect)
        ->setCellValue('E'.$fila, $prom)
       ;
    $fila++;
}

$objPHPExcel->getActiveSheet(0)->setTitle('Rendimiento');

//mostrafr la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/rend lect-".$proyecto.'-del-'.$fecini.'-'.$fecfin.".xlsx";

$objWriter->save($nomarch);
echo $nomarch;

?>