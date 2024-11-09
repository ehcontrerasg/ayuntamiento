<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../clases/classPqrs.php';
ini_set('memory_limit', '-1');

$proy = $_POST['proyecto'];
$fecini = $_POST['fecIni'];
$fecfin = $_POST['fecFin'];


$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle(utf8_encode("Reporte Telefono y Correo ").$proy)
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reporte Telefono y Correo");

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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:E1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:E1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:E2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:E2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(40);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(20);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'Reporte Telefono y Correo '.$proy)
    ->setCellValue('A2', 'Numero')
    ->setCellValue('B2', 'Usuario')
    ->setCellValue('C2', 'Nombre')
    ->setCellValue('D2', 'Cantidad Telefonos')
    ->setCellValue('E2', 'Cantidad Email');

$fila = 3;
$totales04=0;
$a =new PQRs();
$datos=$a->datosTeleCorreo($proy,$fecini,$fecfin,9999999999,0,$where);
while (oci_fetch($datos)){
    $numero=oci_result($datos, 'RNUM');
    $login = oci_result($datos, 'LOGIN');
    $usuario = oci_result($datos, 'USUARIO');
    $cant_tel = oci_result($datos, 'CANTTEL');
    $cant_email = oci_result($datos, 'CANTEMAIL');

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $numero)
        ->setCellValue('B'.$fila, $login)
        ->setCellValue('C'.$fila, $usuario)
        ->setCellValue('D'.$fila, $cant_tel)
        ->setCellValue('E'.$fila, $cant_email);
    $fila++;
}oci_free_statement($datos);

$objPHPExcel->getActiveSheet()->setTitle('Telefonos');

$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Reporte_Telefono_y_Correo_".$proy."_".time().".xlsx";
$objWriter->save($nomarch);
echo $nomarch;
?>