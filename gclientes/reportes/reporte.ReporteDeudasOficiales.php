<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.reportes_gclientes.php';

 $periodo  = $_POST['periodo'];
$proyecto = $_POST['proyecto'];
$cont     = 0;

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Deuda Actual Oficiales")
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Gerenciales");

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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:J1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:J2")->getFont()->setBold(true);

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:J2')->applyFromArray($estiloTitulos);
//$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:F2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'INFORME GRANDES CLIENTES PERIODO ' . $proyecto . ' ' . $periodo)
    ->setCellValue('A2', utf8_encode('Código'))
    ->setCellValue('B2', utf8_encode('Nombre'))
    ->setCellValue('C2', utf8_encode('Uso'))
    ->setCellValue('D2', utf8_encode('M3 Facturados'))
    ->setCellValue('E2', utf8_encode('Facturado en RD$ ' . $periodo))
    ->setCellValue('F2', 'Pendiente en RD$')
    ->setCellValue('G2', 'Deuda Total en RD$')
    ->setCellValue('H2', 'Recaudado en RD$ ')
    ->setCellValue('I2', 'Facturas Pendientes')
    ->setCellValue('J2', 'Grupo Actividad');
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(40);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(40);

$fila      = 3;
$c         = new ReportesGerencia();
$registros = $c->RepOficialSinFinDeLucro($proyecto, $periodo);
while (oci_fetch($registros)) {
    $cod_inm = oci_result($registros, "CODIGO_INM");
    $alias   = oci_result($registros, "ALIAS");
    $metros  = oci_result($registros, "METROS");
    $uso     = oci_result($registros, "ID_USO");
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, $cod_inm)
        ->setCellValue('B' . $fila, $alias)
        ->setCellValue('C' . $fila, $uso)
        ->setCellValue('D' . $fila, $metros);
    $d          = new ReportesGerencia();
    $registrosF = $d->RepOficialSinFinDeLucroFacturadoPeriodo($cod_inm, $periodo);
    while (oci_fetch($registrosF)) {
        $facturadoper = oci_result($registrosF, "FACTURADO");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E' . $fila, $facturadoper);
    }
    oci_free_statement($registrosF);
    $d          = new ReportesGerencia();
    $registrosF = $d->RepOficialSinFinDeLucroFacturadoDeuda($cod_inm, $periodo);
    while (oci_fetch($registrosF)) {
        $facturadodeuda = oci_result($registrosF, "FACTURADODEU");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F' . $fila, $facturadodeuda);
    }
    oci_free_statement($registrosF);

    $d          = new ReportesGerencia();
    $registrosF = $d->RepOficialSinFinDeLucroFacturadoDeudaTotal($cod_inm, $periodo);
    while (oci_fetch($registrosF)) {
        $facturadodeudatot = oci_result($registrosF, "DEUDATOT");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G' . $fila, $facturadodeudatot);
    }
    oci_free_statement($registrosF);

    $d          = new ReportesGerencia();
    $registrosR = $d->RepOficialSinFinDeLucroRecaudo($cod_inm, $periodo);
    while (oci_fetch($registrosR)) {
        $recaudado = utf8_decode(oci_result($registrosR, "IMPORTE"));
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('H' . $fila, $recaudado);
    }
    oci_free_statement($registrosR);

    $d          = new ReportesGerencia();
    $registrosF = $d->RepOficialSinFinDeLucroFactPend($cod_inm);
    while (oci_fetch($registrosF)) {
        $facturapend = oci_result($registrosF, "FACTPEN");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('I' . $fila, $facturapend);
    }
    oci_free_statement($registrosF);

    $d          = new ReportesGerencia();
    $registrosG = $d->RepOficialSinFinDeLucroGrupoInm($cod_inm);
    while (oci_fetch($registrosG)) {
        $grupoInm = oci_result($registrosG, "GRUPO");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J' . $fila, $grupoInm);
    }
    oci_free_statement($registrosG);

    //$objPHPExcel->setActiveSheetIndex(0)
    //    ->setCellValue('G'.$fila, ($facturadoper + $facturadodeuda - $recaudado));
    $totalmetros += $metros;
    $totalfacturadoper += $facturadoper;
    $totalfacturadodeuda += $facturadodeuda;
    //$totaldeuda += $totalfacturadoper + $totalfacturadodeuda;
    $totalrecaudado += $recaudado;
    $totalFactPen += $facturapend;
    $fila++;
}
oci_free_statement($registros);

//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':B'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":J" . $fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B' . $fila, 'Totales')
    ->setCellValue('D' . $fila, $totalmetros)
    ->setCellValue('E' . $fila, $totalfacturadoper)
    ->setCellValue('F' . $fila, $totalfacturadodeuda)
    ->setCellValue('G' . $fila, $totalfacturadoper + $totalfacturadodeuda)
    ->setCellValue('H' . $fila, $totalrecaudado)
    ->setCellValue('I' . $fila, $totalFactPen);

//mostrar la hoja que se abrira
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Listado Inmubles');
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Reporte_Oficiales_Ins_sin_fin_lucro_" . $proyecto . "_" . $periodo . ".xlsx";
$objWriter->save($nomarch);
echo $nomarch;

/*header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename='Reporte_Oficiales_Ins_sin_fin_lucro_" . $proyecto . "_" . $periodo . ".xls'");
header("Cache-Control: max-age=0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;*/