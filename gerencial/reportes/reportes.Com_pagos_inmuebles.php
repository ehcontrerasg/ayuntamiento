<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.repComPagInm.php';
ini_set('memory_limit', '-1');
$proyecto   = $_POST['proyecto'];
$periodouno = $_POST['periodouno'];
$periododos = $_POST['periododos'];
$anouno     = substr($periodouno, 0, 4);
$mesuno     = substr($periodouno, 4, 2);
$anodos     = substr($periododos, 0, 4);
$mesdos     = substr($periododos, 4, 2);

if ($mesuno == '01' || $mesuno == '03' || $mesuno == '05' || $mesuno == '07' || $mesuno == '08' || $mesuno == '10' || $mesuno == '12') {
    $diauno = '31';
}

if ($mesuno == '04' || $mesuno == '06' || $mesuno == '09' || $mesuno == '11') {
    $diauno = '30';
}

if ($anouno % 4 == 0 && $mesuno == '02') {
    $diauno = '29';
}

if ($anouno % 4 != 0 && $mesuno == '02') {
    $diauno = '28';
}

if ($mesdos == '01' || $mesdos == '03' || $mesdos == '05' || $mesdos == '07' || $mesdos == '08' || $mesdos == '10' || $mesdos == '12') {
    $diados = '31';
}

if ($mesdos == '04' || $mesdos == '06' || $mesdos == '09' || $mesdos == '11') {
    $diados = '30';
}

if ($anodos % 4 == 0 && $mesdos == '02') {
    $diados = '29';
}

if ($anodos % 4 != 0 && $mesdos == '02') {
    $diados = '28';
}

$objPHPExcel = new PHPExcel();
$objPHPExcel->
    getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle(utf8_decode("Comparacion de pagos por inmueble") . $proyecto . " " . $periodo)
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

//HOJA INMUEBLES QUE PAGAN EN PERIODO UNO

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:L1');

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:L2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:L2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'PAGOS INMUEBLE PERIODO ' . $periodouno)
    ->setCellValue('A2', 'Inmueble')
    ->setCellValue('B2', 'Nombre')
    ->setCellValue('C2', 'Sector')
    ->setCellValue('D2', 'Ruta')
    ->setCellValue('E2', 'ID Pro')
    ->setCellValue('F2', 'Catastro')
    ->setCellValue('G2', 'Uso')
    ->setCellValue('H2', 'Suministro')
    ->setCellValue('I2', 'Medido')
    ->setCellValue('J2', 'Serial')
    ->setCellValue('K2', 'Importe')
    ->setCellValue('L2', 'Facturas Pagas');

$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(50);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(6);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(6);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(5);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(15);

//DETALLE PAGOS INMUEBLES PERIODO UNO

$fila      = 3;
$c         = new ReportesComPagInm();
$registros = $c->PagosInmueblesUno($proyecto, $anouno, $mesuno, $diauno);
while (oci_fetch($registros)) {
    $numpago    = utf8_decode(oci_result($registros, "ID_PAGO"));
    $inmueble   = utf8_decode(oci_result($registros, "INM_CODIGO"));
    $alias      = oci_result($registros, "ALIAS");
    $sector     = utf8_decode(oci_result($registros, "ID_SECTOR"));
    $ruta       = utf8_decode(oci_result($registros, "ID_RUTA"));
    $uso        = utf8_decode(oci_result($registros, "ID_USO"));
    $categoria  = utf8_decode(oci_result($registros, "CATEGORIA"));
    $suministro = utf8_decode(oci_result($registros, "SUMINISTRO"));
    $medido     = utf8_decode(oci_result($registros, "MEDIDO"));
    $importe    = utf8_decode(oci_result($registros, "IMPORTE"));
    $facturas   = utf8_decode(oci_result($registros, "NUM_FACTURAS"));
    $id_pro     = utf8_decode(oci_result($registros, "ID_PROCESO"));
    $catastro   = utf8_decode(oci_result($registros, "CATASTRO"));
    $serial     = utf8_decode(oci_result($registros, "SERIAL"));
    if ($uso == 'R') {
        $uso = $categoria;
    }

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, $inmueble)
        ->setCellValue('B' . $fila, $alias)
        ->setCellValue('C' . $fila, $sector)
        ->setCellValue('D' . $fila, $ruta)
        ->setCellValue('E' . $fila, $id_pro)
        ->setCellValue('F' . $fila, $catastro)
        ->setCellValue('G' . $fila, $uso)
        ->setCellValue('H' . $fila, $suministro)
        ->setCellValue('I' . $fila, $medido)
        ->setCellValue('J' . $fila, $serial)
        ->setCellValue('K' . $fila, $importe)
        ->setCellValue('L' . $fila, $facturas);
    $fila++;
}
oci_free_statement($registros);

$objPHPExcel->getActiveSheet()->setTitle($periodouno);

//HOJA PAGOS INMUEBLES PERIODO DOS

$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, $periododos);
$objPHPExcel->addSheet($myWorkSheet, 1);

$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:L1');

$objPHPExcel->setActiveSheetIndex(1)->getStyle('A1:L2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:L2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)
    ->setCellValue('A1', 'PAGOS INMUEBLE PERIODO ' . $periodouno)
    ->setCellValue('A2', 'Inmueble')
    ->setCellValue('B2', 'Nombre')
    ->setCellValue('C2', 'Sector')
    ->setCellValue('D2', 'Ruta')
    ->setCellValue('E2', 'ID Pro')
    ->setCellValue('F2', 'Catastro')
    ->setCellValue('G2', 'Uso')
    ->setCellValue('H2', 'Suministro')
    ->setCellValue('I2', 'Medido')
    ->setCellValue('J2', 'Serial')
    ->setCellValue('K2', 'Importe')
    ->setCellValue('L2', 'Facturas Pagas');

$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("A")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("B")->setWidth(50);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("C")->setWidth(6);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("D")->setWidth(6);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("E")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("F")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("G")->setWidth(5);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("H")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("I")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("J")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("K")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("L")->setWidth(15);

//DETALLE PAGOS INMUEBLES PERIODO DOS

$fila       = 3;
$c          = new ReportesComPagInm();
$registrosa = $c->PagosInmueblesDos($proyecto, $anodos, $mesdos, $diados);
while (oci_fetch($registrosa)) {
    $numpago    = utf8_decode(oci_result($registrosa, "ID_PAGO"));
    $inmueble   = utf8_decode(oci_result($registrosa, "INM_CODIGO"));
    $alias      = oci_result($registrosa, "ALIAS");
    $sector     = utf8_decode(oci_result($registrosa, "ID_SECTOR"));
    $ruta       = utf8_decode(oci_result($registrosa, "ID_RUTA"));
    $uso        = utf8_decode(oci_result($registrosa, "ID_USO"));
    $categoria  = utf8_decode(oci_result($registrosa, "CATEGORIA"));
    $suministro = utf8_decode(oci_result($registrosa, "SUMINISTRO"));
    $medido     = utf8_decode(oci_result($registrosa, "MEDIDO"));
    $importe    = utf8_decode(oci_result($registrosa, "IMPORTE"));
    $facturas   = utf8_decode(oci_result($registrosa, "NUM_FACTURAS"));
    $id_pro     = utf8_decode(oci_result($registrosa, "ID_PROCESO"));
    $catastro   = utf8_decode(oci_result($registrosa, "CATASTRO"));
    $serial     = utf8_decode(oci_result($registrosa, "SERIAL"));
    if ($uso == 'R') {
        $uso = $categoria;
    }

    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A' . $fila, $inmueble)
        ->setCellValue('B' . $fila, $alias)
        ->setCellValue('C' . $fila, $sector)
        ->setCellValue('D' . $fila, $ruta)
        ->setCellValue('E' . $fila, $id_pro)
        ->setCellValue('F' . $fila, $catastro)
        ->setCellValue('G' . $fila, $uso)
        ->setCellValue('H' . $fila, $suministro)
        ->setCellValue('I' . $fila, $medido)
        ->setCellValue('J' . $fila, $serial)
        ->setCellValue('K' . $fila, $importe)
        ->setCellValue('L' . $fila, $facturas);
    $fila++;
}
oci_free_statement($registrosa);

//HOJA DIFERENCIA PAGOS INMUEBLES
$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Diferencia');
$objPHPExcel->addSheet($myWorkSheet, 2);

$objPHPExcel->setActiveSheetIndex(2)->mergeCells('A1:H1');

$objPHPExcel->setActiveSheetIndex(2)->getStyle('A1:H2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle("A1:H2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)
    ->setCellValue('A1', 'INMUEBLES CON PAGOS EN ' . $periodouno . ' SIN PAGOS EN ' . $periododos)
    ->setCellValue('A2', 'Inmueble')
    ->setCellValue('B2', 'Nombre')
    ->setCellValue('C2', 'Sector')
    ->setCellValue('D2', 'Ruta')
    ->setCellValue('E2', 'ID Pro')
    ->setCellValue('F2', 'Catastro')
    ->setCellValue('G2', 'Uso')
    ->setCellValue('H2', 'Serial');
/* ->setCellValue('F2', 'Suministro')
->setCellValue('G2', 'Medido')
->setCellValue('H2', 'Importe')
->setCellValue('I2', 'Facturas Pagas');*/

$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("A")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("B")->setWidth(50);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("C")->setWidth(6);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("D")->setWidth(6);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("E")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("F")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("G")->setWidth(5);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("H")->setWidth(15);

/*$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("F")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("G")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("H")->setWidth(12);
$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("I")->setWidth(14);*/

$fila       = 3;
$c          = new ReportesComPagInm();
$registrosb = $c->DiferenciasPagosInm($proyecto, $anouno, $mesuno, $diauno, $anodos, $mesdos, $diados);
while (oci_fetch($registrosb)) {
    // $numpago=utf8_decode(oci_result($registrosb,"ID_PAGO"));
    $inmueble  = utf8_decode(oci_result($registrosb, "INM_CODIGO"));
    $alias     = oci_result($registrosb, "ALIAS");
    $sector    = utf8_decode(oci_result($registrosb, "ID_SECTOR"));
    $ruta      = utf8_decode(oci_result($registrosb, "ID_RUTA"));
    $uso       = utf8_decode(oci_result($registrosb, "ID_USO"));
    $categoria = utf8_decode(oci_result($registrosb, "CATEGORIA"));
    $id_pro    = utf8_decode(oci_result($registrosb, "ID_PROCESO"));
    $catastro  = utf8_decode(oci_result($registrosb, "CATASTRO"));
    $serial    = utf8_decode(oci_result($registrosb, "SERIAL"));
    /* $suministro=utf8_decode(oci_result($registrosa,"SUMINISTRO"));
    $medido=utf8_decode(oci_result($registrosa,"MEDIDO"));
    $importe=utf8_decode(oci_result($registrosa,"IMPORTE"));
    $facturas=utf8_decode(oci_result($registrosa,"NUM_FACTURAS"));*/
    if ($uso == 'R') {
        $uso = $categoria;
    }

    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A' . $fila, $inmueble)
        ->setCellValue('B' . $fila, $alias)
        ->setCellValue('C' . $fila, $sector)
        ->setCellValue('D' . $fila, $ruta)
        ->setCellValue('E' . $fila, $id_pro)
        ->setCellValue('F' . $fila, $catastro)
        ->setCellValue('G' . $fila, $uso)
        ->setCellValue('H' . $fila, $serial);
    /*  ->setCellValue('F'.$fila, $suministro)
    ->setCellValue('G'.$fila, $medido)
    ->setCellValue('H'.$fila, $importe)
    ->setCellValue('I'.$fila, $facturas);*/
    $fila++;
}
oci_free_statement($registrosb);

//mostrafr la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch   = "../../temp/Facturacion_Recaudo_Detallado_Uso-" . $proyecto . '-' . $periodo . ".xlsx";
$objWriter->save($nomarch);
echo $nomarch;
