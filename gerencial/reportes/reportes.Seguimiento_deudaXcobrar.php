<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.repSegDeu.php';
ini_set('memory_limit', '-1');
$periodo=$_POST['periodo'];
$proyecto=$_POST['proyecto'];

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Seguimiento Conciliacion ".$proyecto." ".$periodo)
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

if($proyecto == 'SD') $acueducto = 'CAASD';
if($proyecto == 'BC') $acueducto = 'CORAABO';

//HOJA FACTURADO POR CLIENTE POR CONCEPTO
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:E1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('994C00');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5989A0');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('D2:E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5989A0');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:E1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:E2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'REPORTE DEUDA POR COBRAR '. $acueducto .' '. $periodo)
    ->setCellValue('A2', 'INMUEBLE')
    ->setCellValue('B2', 'DEUDA SIN MORA')
    ->setCellValue('D2', 'INMUEBLE')
    ->setCellValue('E2', 'DEUDA MORA');;
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(50);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(50);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(50);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(50);

$fila = 3;
$c=new ReportesSegDeu();
$registros=$c->seguimientoDeudaSinMora($proyecto, $periodo);
while (oci_fetch($registros)) {
    $inmueble    = utf8_decode(oci_result($registros,"INMUEBLE"));
    $deuda  = utf8_decode(oci_result($registros,"VALOR"));

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $inmueble)
        ->setCellValue('B'.$fila, number_format($deuda,2,',','.'));

    $fila++;

    $totalDeuda += $deuda;

}oci_free_statement($registros);

$filaM = $fila;

$fila = 3;
$c=new ReportesSegDeu();
$registros=$c->seguimientoDeudaConMora($proyecto, $periodo);
while (oci_fetch($registros)) {
    $inmueble    = utf8_decode(oci_result($registros,"INMUEBLE"));
    $deuda  = utf8_decode(oci_result($registros,"VALOR"));

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D'.$fila, $inmueble)
        ->setCellValue('E'.$fila, number_format($deuda,2,',','.'));

    $fila++;

    $totalDeuda2 += $deuda;

}oci_free_statement($registros);

//$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':N'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$filaM, 'Total')
    ->setCellValue('B'.$filaM, number_format($totalDeuda,2,',','.'))
    ->setCellValue('D'.$fila, 'Total')
    ->setCellValue('E'.$fila, number_format($totalDeuda2,2,',','.'));

$objPHPExcel->getActiveSheet()->setTitle('DEUDA');
//mostrafr la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Reporte_Deuda_X_Cobrar-".$proyecto.'-'.$periodo.'_'.time().".xlsx";

$objWriter->save($nomarch);
echo $nomarch;

?>