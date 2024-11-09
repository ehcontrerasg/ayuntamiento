<?php
/*error_reporting(-1);
ini_set('display_errors', '-1');*/
include_once '../clases/class.reportes_gclientes.php';
require_once '../../recursos/PHPExcel.php';
$fecha = $_POST['fechaini'];
$proyecto = $_POST['proyecto'];

$cont = 0;

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Cuentas Por Cobrar")
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Gerenciales");

$estiloTitulos = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => '000000'),
        ),
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    ),
);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:H2")->getFont()->setBold(true);

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:H2')->applyFromArray($estiloTitulos);
//$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:F2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'REPORTE CUENTAS POR COBRAR ' . $proyecto . ' DEL ' . $fecha)
    ->setCellValue('A2', 'Código')
    ->setCellValue('B2', 'Nombre')
    ->setCellValue('C2', 'Fac Pendientes')
    ->setCellValue('D2', 'Val Pendiente')
    ->setCellValue('E2', 'Fac Pend Mora')
    ->setCellValue('F2', 'Val Pend Mora')
    ->setCellValue('G2', 'Fac Pend Sin Mora')
    ->setCellValue('H2', 'Val Pend Sin Mora');
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(40);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(20);

$fila = 3;
$totalfacturas = 0;
$totaldeuda = 0;
$totalfacturasmora = 0;
$totaldeudamora = 0;
$totalfacturasinmora = 0;
$totaldeudasinmora = 0;

$c = new ReportesGerencia();
$registros = $c->RepCuePorCob($proyecto, $fecha);
while (oci_fetch($registros)) {
    $cod_inm = oci_result($registros, "CODIGO_INM");
    $alias = oci_result($registros, "NOMBRE");
    $facpend = oci_result($registros, "FAC_PENDIENTE");
    $valpend = oci_result($registros, "VAL_PENDIENTE");
    $facpendmora = oci_result($registros, "FAC_PEN_MORA");
    $valpendmora = oci_result($registros, "VAL_PEN_MORA");
    $facpendsinmora = oci_result($registros, "FAC_PEN_S_MORA");
    $valpendsinmora = oci_result($registros, "VAL_PEN_S_MORA");
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, $cod_inm)
        ->setCellValue('B' . $fila, $alias)
        ->setCellValue('C' . $fila, $facpend)
        ->setCellValue('D' . $fila, $valpend)
        ->setCellValue('E' . $fila, $facpendmora)
        ->setCellValue('F' . $fila, $valpendmora)
        ->setCellValue('G' . $fila, $facpendsinmora)
        ->setCellValue('H' . $fila, $valpendsinmora);

    $totalfacturas += $facpend;
    $totaldeuda += $valpend;
    $totalfacturasmora += $facpendmora;
    $totaldeudamora += $valpendmora;
    $totalfacturasinmora += $facpendsinmora;
    $totaldeudasinmora += $valpendsinmora;
    $fila++;
}
oci_free_statement($registros);

//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':B'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":H" . $fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B' . $fila, 'Totales')
    ->setCellValue('C' . $fila, $totalfacturas)
    ->setCellValue('D' . $fila, $totaldeuda)
    ->setCellValue('E' . $fila, $totalfacturasmora)
    ->setCellValue('F' . $fila, $totaldeudamora)
    ->setCellValue('G' . $fila, $totalfacturasinmora)
    ->setCellValue('H' . $fila, $totaldeudasinmora);

//mostrar la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch = "../../temp/Cuentas_Por_Cobrar" . time() . ".xlsx";
$objWriter->save($nomarch);
echo $nomarch;
