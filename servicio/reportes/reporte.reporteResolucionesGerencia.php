<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../../clases/classPqrs.php';
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/

/*include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.reportes_gclientes.php';*/

$fecini = $_POST['fecini'];
$fecfin = $_POST['fecfin'];
$proyecto = $_POST['proyecto'];

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Resumen PQRs")
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Servicio al Cliente");

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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:Q1');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:E2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G2:K2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('M2:Q2');

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:Q3')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:Q3")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'RESUMEN POR TIPO DE RESOLUCION Y GERENCIA - DESFAVORABLE DEL '.$fecini.' AL '.$fecfin)
    ->setCellValue('A2', 'GERENCIA NORTE')
    ->setCellValue('G2', 'GERENCIA ESTE')
    ->setCellValue('M2', 'GERENCIA NORTE + ESTE')
    ->setCellValue('A3', 'No')
    ->setCellValue('B3', 'DESCRIPCION')
    ->setCellValue('C3', 'Proc.')
    ->setCellValue('D3', 'No Proc.')
    ->setCellValue('E3', 'TOTAL')
    ->setCellValue('G3', 'No')
    ->setCellValue('H3', 'DESCRIPCION')
    ->setCellValue('I3', 'Proc.')
    ->setCellValue('J3', 'No Proc.')
    ->setCellValue('K3', 'TOTAL')
    ->setCellValue('M3', 'No')
    ->setCellValue('N3', 'DESCRIPCION')
    ->setCellValue('O3', 'Proc.')
    ->setCellValue('P3', 'No Proc.')
    ->setCellValue('Q3', 'TOTAL');
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(5);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(40);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(4);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(5);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(40);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(4);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(5);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(40);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Q")->setWidth(8);

$fila = 4;
$l=new PQRs();
$registros=$l->MotivosDesfavorables();
while (oci_fetch($registros)) {
    $codmotivo=oci_result($registros, 'ID_MOTIVO_REC');
    $desmotivo=oci_result($registros, 'DESC_MOTIVO_REC');

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $codmotivo)
        ->setCellValue('B'.$fila, $desmotivo)
        ->setCellValue('G'.$fila, $codmotivo)
        ->setCellValue('H'.$fila, $desmotivo)
        ->setCellValue('M'.$fila, $codmotivo)
        ->setCellValue('N'.$fila, $desmotivo);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesNorteProc($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.$fila, $cantidad);
        $totalNP += $cantidad;
    }oci_free_statement($registrosa);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesNorteNoProc($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D'.$fila, $cantidad);
        $totalNNP += $cantidad;
    }oci_free_statement($registrosa);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesNorte($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E'.$fila, $cantidad);
        $totalN += $cantidad;
    }oci_free_statement($registrosa);

    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesEsteProc($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('I'.$fila, $cantidad);
        $totalEP += $cantidad;
    }oci_free_statement($registrosa);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesEsteNoProc($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J'.$fila, $cantidad);
        $totalENP += $cantidad;
    }oci_free_statement($registrosa);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesEste($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('K'.$fila, $cantidad);
        $totalE += $cantidad;
    }oci_free_statement($registrosa);

    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesTotalProc($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O'.$fila, $cantidad);
        $totalTP += $cantidad;
    }oci_free_statement($registrosa);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesTotalNoProc($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('P'.$fila, $cantidad);
        $totalTNP += $cantidad;
    }oci_free_statement($registrosa);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesTotal($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q'.$fila, $cantidad);
        $totalT += $cantidad;
    }oci_free_statement($registrosa);
    $fila++;
}oci_free_statement($registros);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':Q'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':Q'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B'.$fila, 'Total')
    ->setCellValue('C'.$fila, $totalNP)
    ->setCellValue('D'.$fila, $totalNNP)
    ->setCellValue('E'.$fila, $totalN)
    ->setCellValue('H'.$fila, 'Total')
    ->setCellValue('I'.$fila, $totalEP)
    ->setCellValue('J'.$fila, $totalENP)
    ->setCellValue('K'.$fila, $totalE)
    ->setCellValue('N'.$fila, 'Total')
    ->setCellValue('O'.$fila, $totalTP)
    ->setCellValue('P'.$fila, $totalTNP)
    ->setCellValue('Q'.$fila, $totalT);

$fila = $fila + 3;

$fila1 = $fila+1;
$fila2 = $fila+2;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':Q'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila1.':E'.$fila1);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G'.$fila1.':K'.$fila1);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('M'.$fila1.':Q'.$fila1);

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':Q'.$fila2)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':Q'.$fila2)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'RESUMEN POR TIPO DE RESOLUCION Y GERENCIA - FAVORABLE DEL '.$fecini.' AL '.$fecfin)
    ->setCellValue('A'.$fila1, 'GERENCIA NORTE')
    ->setCellValue('G'.$fila1, 'GERENCIA ESTE')
    ->setCellValue('M'.$fila1, 'GERENCIA NORTE + ESTE')
    ->setCellValue('A'.$fila2, 'No')
    ->setCellValue('B'.$fila2, 'DESCRIPCION')
    ->setCellValue('C'.$fila2, 'Proc.')
    ->setCellValue('D'.$fila2, 'No Proc.')
    ->setCellValue('E'.$fila2, 'TOTAL')
    ->setCellValue('G'.$fila2, 'No')
    ->setCellValue('H'.$fila2, 'DESCRIPCION')
    ->setCellValue('I'.$fila2, 'Proc.')
    ->setCellValue('J'.$fila2, 'No Proc.')
    ->setCellValue('K'.$fila2, 'TOTAL')
    ->setCellValue('M'.$fila2, 'No')
    ->setCellValue('N'.$fila2, 'DESCRIPCION')
    ->setCellValue('O'.$fila2, 'Proc.')
    ->setCellValue('P'.$fila2, 'No Proc.')
    ->setCellValue('Q'.$fila2, 'TOTAL');

$fila = $fila2 + 1;
$l=new PQRs();
$registros=$l->MotivosFavorables();
while (oci_fetch($registros)) {
    $codmotivo=oci_result($registros, 'ID_MOTIVO_REC');
    $desmotivo=oci_result($registros, 'DESC_MOTIVO_REC');
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $codmotivo)
        ->setCellValue('B'.$fila, $desmotivo)
        ->setCellValue('G'.$fila, $codmotivo)
        ->setCellValue('H'.$fila, $desmotivo)
        ->setCellValue('M'.$fila, $codmotivo)
        ->setCellValue('N'.$fila, $desmotivo);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesNorteProc($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.$fila, $cantidad);
        $totalDNP += $cantidad;
    }oci_free_statement($registrosa);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesNorteNoProc($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D'.$fila, $cantidad);
        $totalDNNP += $cantidad;
    }oci_free_statement($registrosa);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesNorte($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E'.$fila, $cantidad);
        $totalDNN += $cantidad;
    }oci_free_statement($registrosa);

    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesEsteProc($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('I'.$fila, $cantidad);
        $totalDEP += $cantidad;
    }oci_free_statement($registrosa);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesEsteNoProc($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J'.$fila, $cantidad);
        $totalDENP += $cantidad;
    }oci_free_statement($registrosa);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesEste($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('K'.$fila, $cantidad);
        $totalDE += $cantidad;
    }oci_free_statement($registrosa);

    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesTotalProc($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('O'.$fila, $cantidad);
        $totalDTP += $cantidad;
    }oci_free_statement($registrosa);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesTotalNoProc($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('P'.$fila, $cantidad);
        $totalDTNP += $cantidad;
    }oci_free_statement($registrosa);
    $a=new PQRs();
    $registrosa=$a->CantidadDesfavorablesTotal($codmotivo, $proyecto, $fecini, $fecfin);
    while (oci_fetch($registrosa)) {
        $cantidad=oci_result($registrosa, 'CANTIDAD');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q'.$fila, $cantidad);
        $totalDT += $cantidad;
    }oci_free_statement($registrosa);
    $fila++;
}oci_free_statement($registros);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':Q'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':Q'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B'.$fila, 'Total')
    ->setCellValue('C'.$fila, $totalDNP)
    ->setCellValue('D'.$fila, $totalDNNP)
    ->setCellValue('E'.$fila, $totalDNN)
    ->setCellValue('H'.$fila, 'Total')
    ->setCellValue('I'.$fila, $totalDEP)
    ->setCellValue('J'.$fila, $totalDENP)
    ->setCellValue('K'.$fila, $totalDE)
    ->setCellValue('N'.$fila, 'Total')
    ->setCellValue('O'.$fila, $totalDTP)
    ->setCellValue('P'.$fila, $totalDTNP)
    ->setCellValue('Q'.$fila, $totalDT);

//mostrar la hoja que se abrira
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Resoluciones de Gerencia');
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Resumen_Resoluciones_".$proyecto.".xlsx";
$objWriter->save($nomarch);
echo $nomarch;

/*$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Resumen_Resoluciones_".$proyecto.".xlsx";
$objWriter->save($nomarch);
echo $nomarch;*/

/*
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename='Resumen_Resoluciones_".$proyecto.".xls'");
header("Cache-Control: max-age=0");
ini_set('memory_limit','250M');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;*/