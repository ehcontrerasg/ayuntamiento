<?php
/**
 * Created by PhpStorm.
 * User: Jgutierrez
 * Date: 07/09/2022
 * Time: 11:51 AM
 */
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
setlocale(LC_MONETARY, 'es_DO');

$proyecto=$_POST['proyecto'];
$fecini=$_POST['fecini'];
$fecfin=$_POST['fecfin'];

//include "../../clases/class.pdfRep.php";
include "../../clases/class.factura.php";
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle(utf8_encode("Reporte Detalle Notas ").$proyecto)
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Detalle Notas");

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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:L1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:L1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:L1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:L2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:L2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(50);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(180);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'DETALLE NOTAS '.$proyecto.' DEL '.$fecini.' AL '.$fecfin)
    ->setCellValue('A2', 'No. PQR')
    ->setCellValue('B2', 'Fecha PQR')
    ->setCellValue('C2', 'Tipo Nota')
    ->setCellValue('D2', 'Inmueble')
    ->setCellValue('E2', 'Nombre')
    ->setCellValue('F2', 'Factura')
    ->setCellValue('G2', 'Total Factura RD$')
    ->setCellValue('H2', 'NCF Original')
    ->setCellValue('I2', 'NCF Nota')
    ->setCellValue('J2', 'Monto Nota RD$')
    ->setCellValue('K2', 'Fecha Reliquidación')
    ->setCellValue('L2', 'Observación');

$fila = 3;
$totales04=0;
$a =new Factura();
$datos=$a->getDetNotB04($proyecto,$fecini,$fecfin);
while (oci_fetch($datos)){
    $pqr=oci_result($datos,"CODIGO_PQR");
    $fechapqr=oci_result($datos,"FECHA_PQR");
    $tiponota=oci_result($datos,"TIPO_NOTA");
    $inmueble=oci_result($datos,"CODIGO_INM");
    $alias=oci_result($datos,"NOMBRE");
    $factura=oci_result($datos,"FACTURA_APLICA");
    $total_fac=oci_result($datos,"VALOR_FACTURA");
    $ncf_ori=oci_result($datos,"NCF_ORI");
    $ncf_nota=oci_result($datos,"NCF_NOTA");
    $valor=oci_result($datos,"TOTAL_NOTA");
    $fecharel=oci_result($datos,"FEC_REL");
    $observa=oci_result($datos,"DESCRIPCION");

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $pqr)
        ->setCellValue('B'.$fila, $fechapqr)
        ->setCellValue('C'.$fila, $tiponota)
        ->setCellValue('D'.$fila, $inmueble)
        ->setCellValue('E'.$fila, $alias)
        ->setCellValue('F'.$fila, $factura)
        ->setCellValue('G'.$fila, $total_fac)
        ->setCellValue('H'.$fila, $ncf_ori)
        ->setCellValue('I'.$fila, $ncf_nota)
        ->setCellValue('J'.$fila, $valor)
        ->setCellValue('K'.$fila, $fecharel)
        ->setCellValue('L'.$fila, $observa);
    $fila++;
    $totales04 = $totales04 + $valor;
}oci_free_statement($datos);

$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":L".$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':I'.$fila);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'TOTAL')
    ->setCellValue('J'.$fila, $totales04);

$objPHPExcel->getActiveSheet()->setTitle('B04');

$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'B99');
$objPHPExcel->addSheet($myWorkSheet, 1);
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:G1');
$objPHPExcel->setActiveSheetIndex(1)->getStyle('A1:G1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:G1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('A2:G2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A2:G2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("A")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("B")->setWidth(50);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("C")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("D")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("E")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("F")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("G")->setWidth(80);
$objPHPExcel->setActiveSheetIndex(1)
    ->setCellValue('A1', 'DETALLE NOTAS '.$proyecto.' DEL '.$fecini.' AL '.$fecfin)
    ->setCellValue('A2', 'Inmueble')
    ->setCellValue('B2', 'Nombre')
    ->setCellValue('C2', 'Factura')
    ->setCellValue('D2', 'Total Factura')
    ->setCellValue('E2', 'Consecutivo NCF')
    ->setCellValue('F2', 'Monto Nota RD$')
    ->setCellValue('G2', 'Observación');

$fila = 3;
$totales99=0;
$b =new Factura();
$datosb=$b->getDetNotB99($proyecto,$fecini,$fecfin);
while (oci_fetch($datosb)) {
    $inmueble=oci_result($datos,"CODIGO_INM");
    $alias=oci_result($datos,"NOMBRE");
    $factura=oci_result($datos,"FACTURA_APLICA");
    $total_fac=oci_result($datos,"VALOR_FACTURA");
    $ncf=oci_result($datos,"NCF");
    $conse_ncf=oci_result($datos,"NCF_CONSEC");
    $valor=oci_result($datos,"TOTAL_NOTA");
    $observa=oci_result($datos,"OBSERVACION");

    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$fila, $inmueble)
        ->setCellValue('B'.$fila, $alias)
        ->setCellValue('C'.$fila, $factura)
        ->setCellValue('D'.$fila, $total_fac)
        ->setCellValue('E'.$fila, $ncf.$conse_ncf)
        ->setCellValue('F'.$fila, $valor)
        ->setCellValue('G'.$fila, $observa);
    $fila++;
    $totales99 = $totales99 + $valor;

}oci_free_statement($datosb);

$objPHPExcel->setActiveSheetIndex(1)->getStyle("A".$fila.":G".$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A'.$fila.':E'.$fila);
$objPHPExcel->setActiveSheetIndex(1)
    ->setCellValue('A'.$fila, 'TOTAL')
    ->setCellValue('F'.$fila, $totales99);

$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'B98');
$objPHPExcel->addSheet($myWorkSheet, 2);
$objPHPExcel->setActiveSheetIndex(2)->mergeCells('A1:G1');
$objPHPExcel->setActiveSheetIndex(2)->getStyle('A1:G1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle("A1:G1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('A2:G2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle("A2:G2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("A")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("B")->setWidth(50);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("C")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("D")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("E")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("F")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("G")->setWidth(80);
$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('A1', 'DETALLE NOTAS '.$proyecto.' DEL '.$fecini.' AL '.$fecfin)
    ->setCellValue('A2', 'Inmueble')
    ->setCellValue('B2', 'Nombre')
    ->setCellValue('C2', 'Factura')
    ->setCellValue('D2', 'Total Factura')
    ->setCellValue('E2', 'Consecutivo NCF')
    ->setCellValue('F2', 'Monto Nota RD$')
    ->setCellValue('G2', 'Observación');

$fila = 3;
$totales98=0;
$c =new Factura();
$datosc=$c->getDetNotB98($proyecto,$fecini,$fecfin);
while (oci_fetch($datosc)) {
    $inmueble=oci_result($datos,"CODIGO_INM");
    $alias=oci_result($datos,"NOMBRE");
    $factura=oci_result($datos,"FACTURA_APLICA");
    $total_fac=oci_result($datos,"VALOR_FACTURA");
    $ncf=oci_result($datos,"NCF");
    $conse_ncf=oci_result($datos,"NCF_CONSEC");
    $valor=oci_result($datos,"TOTAL_NOTA");
    $observa=oci_result($datos,"OBSERVACION");

    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A'.$fila, $inmueble)
        ->setCellValue('B'.$fila, $alias)
        ->setCellValue('C'.$fila, $factura)
        ->setCellValue('D'.$fila, $total_fac)
        ->setCellValue('E'.$fila, $ncf.$conse_ncf)
        ->setCellValue('F'.$fila, $valor)
        ->setCellValue('G'.$fila, $observa);
    $fila++;
    $totales98 = $totales98 + $valor;

}oci_free_statement($datosc);

$objPHPExcel->setActiveSheetIndex(2)->getStyle("A".$fila.":G".$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->mergeCells('A'.$fila.':E'.$fila);
$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('A'.$fila, 'TOTAL')
    ->setCellValue('F'.$fila, $totales98);


$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Detalle_Notas_".$proyecto."_del_".$fecini."_al_".$fecfin."_".time().".xlsx";
$objWriter->save($nomarch);
echo $nomarch;
