<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.repSegCon.php';
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
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:N1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4:A50')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C2:N2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5989A0');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:N1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:N1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A4:A50")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4:A50')->getAlignment()->setTextRotation(90);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'REPORTE CONCILIACIÓN '. $acueducto .' '. $periodo)
    ->setCellValue('A4', '(+) Facturación')
    ->setCellValue('B2', '')
    ->setCellValue('C2', $periodo.'-01')
    ->setCellValue('D2', $periodo.'-02')
    ->setCellValue('E2', $periodo.'-03')
    ->setCellValue('F2', $periodo.'-04')
    ->setCellValue('G2', $periodo.'-05')
    ->setCellValue('H2', $periodo.'-06')
    ->setCellValue('I2', $periodo.'-07')
    ->setCellValue('J2', $periodo.'-08')
    ->setCellValue('K2', $periodo.'-09')
    ->setCellValue('L2', $periodo.'-10')
    ->setCellValue('M2', $periodo.'-11')
    ->setCellValue('N2', $periodo.'-12');
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(3);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(30);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(20);

$fila = 4;
$c=new ReportesSegCon();
$registros=$c->seguimientoConciliacionF($proyecto, $periodo);

$per01 = $periodo.'01';$per02 = $periodo.'02';$per03 = $periodo.'03';$per04 = $periodo.'04';$per05 = $periodo.'05';$per06 = $periodo.'06';
$per07 = $periodo.'07';$per08 = $periodo.'08';$per09 = $periodo.'09';$per10 = $periodo.'10';$per11 = $periodo.'11';$per12 = $periodo.'12';

while (oci_fetch($registros)) {
    $concepto    = utf8_decode(oci_result($registros,"DESC_CONCILIA"));
    $mes01  = utf8_decode(oci_result($registros,$per01));
    $mes02  = utf8_decode(oci_result($registros,$per02));
    $mes03  = utf8_decode(oci_result($registros,$per03));
    $mes04  = utf8_decode(oci_result($registros,$per04));
    $mes05  = utf8_decode(oci_result($registros,$per05));
    $mes06  = utf8_decode(oci_result($registros,$per06));
    $mes07  = utf8_decode(oci_result($registros,$per07));
    $mes08  = utf8_decode(oci_result($registros,$per08));
    $mes09  = utf8_decode(oci_result($registros,$per09));
    $mes10  = utf8_decode(oci_result($registros,$per10));
    $mes11  = utf8_decode(oci_result($registros,$per11));
    $mes12  = utf8_decode(oci_result($registros,$per12));

    if($concepto == 'Balance Inicial'){
        $totalBal01 = $mes01;
        $totalBal02 = $mes02;
        $totalBal03 = $mes03;
        $totalBal04 = $mes04;
        $totalBal05 = $mes05;
        $totalBal06 = $mes06;
        $totalBal07 = $mes07;
        $totalBal08 = $mes08;
        $totalBal09 = $mes09;
        $totalBal10 = $mes10;
        $totalBal11 = $mes11;
        $totalBal12 = $mes12;
    }

    if($concepto <> 'Balance Inicial') {
        $totalFac01 += $mes01;
        $totalFac02 += $mes02;
        $totalFac03 += $mes03;
        $totalFac04 += $mes04;
        $totalFac05 += $mes05;
        $totalFac06 += $mes06;
        $totalFac07 += $mes07;
        $totalFac08 += $mes08;
        $totalFac09 += $mes09;
        $totalFac10 += $mes10;
        $totalFac11 += $mes11;
        $totalFac12 += $mes12;
    }

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, $concepto)
        ->setCellValue('C'.$fila, number_format($mes01,2,',','.'))
        ->setCellValue('D'.$fila, number_format($mes02,2,',','.'))
        ->setCellValue('E'.$fila, number_format($mes03,2,',','.'))
        ->setCellValue('F'.$fila, number_format($mes04,2,',','.'))
        ->setCellValue('G'.$fila, number_format($mes05,2,',','.'))
        ->setCellValue('H'.$fila, number_format($mes06,2,',','.'))
        ->setCellValue('I'.$fila, number_format($mes07,2,',','.'))
        ->setCellValue('J'.$fila, number_format($mes08,2,',','.'))
        ->setCellValue('K'.$fila, number_format($mes09,2,',','.'))
        ->setCellValue('L'.$fila, number_format($mes10,2,',','.'))
        ->setCellValue('M'.$fila, number_format($mes11,2,',','.'))
        ->setCellValue('N'.$fila, number_format($mes12,2,',','.'));

    $fila++;

}oci_free_statement($registros);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':N'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B'.$fila, 'Total')
    ->setCellValue('C'.$fila, number_format($totalFac01,2,',','.'))
    ->setCellValue('D'.$fila, number_format($totalFac02,2,',','.'))
    ->setCellValue('E'.$fila, number_format($totalFac03,2,',','.'))
    ->setCellValue('F'.$fila, number_format($totalFac04,2,',','.'))
    ->setCellValue('G'.$fila, number_format($totalFac05,2,',','.'))
    ->setCellValue('H'.$fila, number_format($totalFac06,2,',','.'))
    ->setCellValue('I'.$fila, number_format($totalFac07,2,',','.'))
    ->setCellValue('J'.$fila, number_format($totalFac08,2,',','.'))
    ->setCellValue('K'.$fila, number_format($totalFac09,2,',','.'))
    ->setCellValue('L'.$fila, number_format($totalFac10,2,',','.'))
    ->setCellValue('M'.$fila, number_format($totalFac11,2,',','.'))
    ->setCellValue('N'.$fila, number_format($totalFac12,2,',','.'));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A4:A'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4:A'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C1BFBF');

$fila += 2;
$filab = $fila +2;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':A'.$filab);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':A'.$filab)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('AD8825');


$registros=$c->seguimientoConciliacionNC($proyecto, $periodo);
while (oci_fetch($registros)) {
    $concepto    = utf8_decode(oci_result($registros,"DESC_CONCILIA"));
    $mes01  = utf8_decode(oci_result($registros,$per01));
    $mes02  = utf8_decode(oci_result($registros,$per02));
    $mes03  = utf8_decode(oci_result($registros,$per03));
    $mes04  = utf8_decode(oci_result($registros,$per04));
    $mes05  = utf8_decode(oci_result($registros,$per05));
    $mes06  = utf8_decode(oci_result($registros,$per06));
    $mes07  = utf8_decode(oci_result($registros,$per07));
    $mes08  = utf8_decode(oci_result($registros,$per08));
    $mes09  = utf8_decode(oci_result($registros,$per09));
    $mes10  = utf8_decode(oci_result($registros,$per10));
    $mes11  = utf8_decode(oci_result($registros,$per11));
    $mes12  = utf8_decode(oci_result($registros,$per12));

    $totalNot01 += $mes01;
    $totalNot02 += $mes02;
    $totalNot03 += $mes03;
    $totalNot04 += $mes04;
    $totalNot05 += $mes05;
    $totalNot06 += $mes06;
    $totalNot07 += $mes07;
    $totalNot08 += $mes08;
    $totalNot09 += $mes09;
    $totalNot10 += $mes10;
    $totalNot11 += $mes11;
    $totalNot12 += $mes12;

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, '(-) NC')
        ->setCellValue('B'.$fila, $concepto)
        ->setCellValue('C'.$fila, number_format($mes01,2,',','.'))
        ->setCellValue('D'.$fila, number_format($mes02,2,',','.'))
        ->setCellValue('E'.$fila, number_format($mes03,2,',','.'))
        ->setCellValue('F'.$fila, number_format($mes04,2,',','.'))
        ->setCellValue('G'.$fila, number_format($mes05,2,',','.'))
        ->setCellValue('H'.$fila, number_format($mes06,2,',','.'))
        ->setCellValue('I'.$fila, number_format($mes07,2,',','.'))
        ->setCellValue('J'.$fila, number_format($mes08,2,',','.'))
        ->setCellValue('K'.$fila, number_format($mes09,2,',','.'))
        ->setCellValue('L'.$fila, number_format($mes10,2,',','.'))
        ->setCellValue('M'.$fila, number_format($mes11,2,',','.'))
        ->setCellValue('N'.$fila, number_format($mes12,2,',','.'));

    $fila++;
}oci_free_statement($registros);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':N'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B'.$fila, 'Total')
    ->setCellValue('C'.$fila, number_format($totalNot01,2,',','.'))
    ->setCellValue('D'.$fila, number_format($totalNot02,2,',','.'))
    ->setCellValue('E'.$fila, number_format($totalNot03,2,',','.'))
    ->setCellValue('F'.$fila, number_format($totalNot04,2,',','.'))
    ->setCellValue('G'.$fila, number_format($totalNot05,2,',','.'))
    ->setCellValue('H'.$fila, number_format($totalNot06,2,',','.'))
    ->setCellValue('I'.$fila, number_format($totalNot07,2,',','.'))
    ->setCellValue('J'.$fila, number_format($totalNot08,2,',','.'))
    ->setCellValue('K'.$fila, number_format($totalNot09,2,',','.'))
    ->setCellValue('L'.$fila, number_format($totalNot10,2,',','.'))
    ->setCellValue('M'.$fila, number_format($totalNot11,2,',','.'))
    ->setCellValue('N'.$fila, number_format($totalNot12,2,',','.'));

$fila += 2;
$filab = $fila +1;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':A'.$filab);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':A'.$filab)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('665017');

$registros=$c->seguimientoConciliacionND($proyecto, $periodo);
while (oci_fetch($registros)) {
    $concepto    = utf8_decode(oci_result($registros,"DESC_CONCILIA"));
    $mes01  = utf8_decode(oci_result($registros,$per01));
    $mes02  = utf8_decode(oci_result($registros,$per02));
    $mes03  = utf8_decode(oci_result($registros,$per03));
    $mes04  = utf8_decode(oci_result($registros,$per04));
    $mes05  = utf8_decode(oci_result($registros,$per05));
    $mes06  = utf8_decode(oci_result($registros,$per06));
    $mes07  = utf8_decode(oci_result($registros,$per07));
    $mes08  = utf8_decode(oci_result($registros,$per08));
    $mes09  = utf8_decode(oci_result($registros,$per09));
    $mes10  = utf8_decode(oci_result($registros,$per10));
    $mes11  = utf8_decode(oci_result($registros,$per11));
    $mes12  = utf8_decode(oci_result($registros,$per12));

    $totalNotD01 += $mes01;
    $totalNotD02 += $mes02;
    $totalNotD03 += $mes03;
    $totalNotD04 += $mes04;
    $totalNotD05 += $mes05;
    $totalNotD06 += $mes06;
    $totalNotD07 += $mes07;
    $totalNotD08 += $mes08;
    $totalNotD09 += $mes09;
    $totalNotD10 += $mes10;
    $totalNotD11 += $mes11;
    $totalNotD12 += $mes12;

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, '(+) ND')
        ->setCellValue('B'.$fila, $concepto)
        ->setCellValue('C'.$fila, number_format($mes01,2,',','.'))
        ->setCellValue('D'.$fila, number_format($mes02,2,',','.'))
        ->setCellValue('E'.$fila, number_format($mes03,2,',','.'))
        ->setCellValue('F'.$fila, number_format($mes04,2,',','.'))
        ->setCellValue('G'.$fila, number_format($mes05,2,',','.'))
        ->setCellValue('H'.$fila, number_format($mes06,2,',','.'))
        ->setCellValue('I'.$fila, number_format($mes07,2,',','.'))
        ->setCellValue('J'.$fila, number_format($mes08,2,',','.'))
        ->setCellValue('K'.$fila, number_format($mes09,2,',','.'))
        ->setCellValue('L'.$fila, number_format($mes10,2,',','.'))
        ->setCellValue('M'.$fila, number_format($mes11,2,',','.'))
        ->setCellValue('N'.$fila, number_format($mes12,2,',','.'));

    $fila++;
}oci_free_statement($registros);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':N'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B'.$fila, 'Total')
    ->setCellValue('C'.$fila, number_format($totalNotD01,2,',','.'))
    ->setCellValue('D'.$fila, number_format($totalNotD02,2,',','.'))
    ->setCellValue('E'.$fila, number_format($totalNotD03,2,',','.'))
    ->setCellValue('F'.$fila, number_format($totalNotD04,2,',','.'))
    ->setCellValue('G'.$fila, number_format($totalNotD05,2,',','.'))
    ->setCellValue('H'.$fila, number_format($totalNotD06,2,',','.'))
    ->setCellValue('I'.$fila, number_format($totalNotD07,2,',','.'))
    ->setCellValue('J'.$fila, number_format($totalNotD08,2,',','.'))
    ->setCellValue('K'.$fila, number_format($totalNotD09,2,',','.'))
    ->setCellValue('L'.$fila, number_format($totalNotD10,2,',','.'))
    ->setCellValue('M'.$fila, number_format($totalNotD11,2,',','.'))
    ->setCellValue('N'.$fila, number_format($totalNotD12,2,',','.'));

$fila += 2;
$filar = $fila;

$registros=$c->seguimientoConciliacionR($proyecto, $periodo);
while (oci_fetch($registros)) {
    $concepto    = utf8_decode(oci_result($registros,"DESC_CONCILIA"));
    $mes01  = utf8_decode(oci_result($registros,$per01));
    $mes02  = utf8_decode(oci_result($registros,$per02));
    $mes03  = utf8_decode(oci_result($registros,$per03));
    $mes04  = utf8_decode(oci_result($registros,$per04));
    $mes05  = utf8_decode(oci_result($registros,$per05));
    $mes06  = utf8_decode(oci_result($registros,$per06));
    $mes07  = utf8_decode(oci_result($registros,$per07));
    $mes08  = utf8_decode(oci_result($registros,$per08));
    $mes09  = utf8_decode(oci_result($registros,$per09));
    $mes10  = utf8_decode(oci_result($registros,$per10));
    $mes11  = utf8_decode(oci_result($registros,$per11));
    $mes12  = utf8_decode(oci_result($registros,$per12));

    $totalRec01 += $mes01;
    $totalRec02 += $mes02;
    $totalRec03 += $mes03;
    $totalRec04 += $mes04;
    $totalRec05 += $mes05;
    $totalRec06 += $mes06;
    $totalRec07 += $mes07;
    $totalRec08 += $mes08;
    $totalRec09 += $mes09;
    $totalRec10 += $mes10;
    $totalRec11 += $mes11;
    $totalRec12 += $mes12;

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, '(-) Cobros')
        ->setCellValue('B'.$fila, $concepto)
        ->setCellValue('C'.$fila, number_format($mes01,2,',','.'))
        ->setCellValue('D'.$fila, number_format($mes02,2,',','.'))
        ->setCellValue('E'.$fila, number_format($mes03,2,',','.'))
        ->setCellValue('F'.$fila, number_format($mes04,2,',','.'))
        ->setCellValue('G'.$fila, number_format($mes05,2,',','.'))
        ->setCellValue('H'.$fila, number_format($mes06,2,',','.'))
        ->setCellValue('I'.$fila, number_format($mes07,2,',','.'))
        ->setCellValue('J'.$fila, number_format($mes08,2,',','.'))
        ->setCellValue('K'.$fila, number_format($mes09,2,',','.'))
        ->setCellValue('L'.$fila, number_format($mes10,2,',','.'))
        ->setCellValue('M'.$fila, number_format($mes11,2,',','.'))
        ->setCellValue('N'.$fila, number_format($mes12,2,',','.'));

    $fila++;
}oci_free_statement($registros);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':N'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B'.$fila, 'Total')
    ->setCellValue('C'.$fila, number_format($totalRec01,2,',','.'))
    ->setCellValue('D'.$fila, number_format($totalRec02,2,',','.'))
    ->setCellValue('E'.$fila, number_format($totalRec03,2,',','.'))
    ->setCellValue('F'.$fila, number_format($totalRec04,2,',','.'))
    ->setCellValue('G'.$fila, number_format($totalRec05,2,',','.'))
    ->setCellValue('H'.$fila, number_format($totalRec06,2,',','.'))
    ->setCellValue('I'.$fila, number_format($totalRec07,2,',','.'))
    ->setCellValue('J'.$fila, number_format($totalRec08,2,',','.'))
    ->setCellValue('K'.$fila, number_format($totalRec09,2,',','.'))
    ->setCellValue('L'.$fila, number_format($totalRec10,2,',','.'))
    ->setCellValue('M'.$fila, number_format($totalRec11,2,',','.'))
    ->setCellValue('N'.$fila, number_format($totalRec12,2,',','.'));

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$filar.':A'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$filar.':A'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('976C46');


$fila += 2;

$BalFin01 = $totalBal01 + $totalFac01 - $totalNot01 + $totalNotD01 - $totalRec01;
$BalFin02 = $totalBal02 + $totalFac02 - $totalNot02 + $totalNotD02 - $totalRec02;
$BalFin03 = $totalBal03 + $totalFac03 - $totalNot03 + $totalNotD03 - $totalRec03;
$BalFin04 = $totalBal04 + $totalFac04 - $totalNot04 + $totalNotD04 - $totalRec04;
$BalFin05 = $totalBal05 + $totalFac05 - $totalNot05 + $totalNotD05 - $totalRec05;
$BalFin06 = $totalBal06 + $totalFac06 - $totalNot06 + $totalNotD06 - $totalRec06;
$BalFin07 = $totalBal07 + $totalFac07 - $totalNot07 + $totalNotD07 - $totalRec07;
$BalFin08 = $totalBal08 + $totalFac08 - $totalNot08 + $totalNotD08 - $totalRec08;
$BalFin09 = $totalBal09 + $totalFac09 - $totalNot09 + $totalNotD09 - $totalRec09;
$BalFin10 = $totalBal10 + $totalFac10 - $totalNot10 + $totalNotD10 - $totalRec10;
$BalFin11 = $totalBal11 + $totalFac11 - $totalNot11 + $totalNotD11 - $totalRec11;
$BalFin12 = $totalBal12 + $totalFac12 - $totalNot12 + $totalNotD12 - $totalRec12;

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':N'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B'.$fila, 'Balance Final')
    ->setCellValue('C'.$fila, number_format($BalFin01,2,',','.'))
    ->setCellValue('D'.$fila, number_format($BalFin02,2,',','.'))
    ->setCellValue('E'.$fila, number_format($BalFin03,2,',','.'))
    ->setCellValue('F'.$fila, number_format($BalFin04,2,',','.'))
    ->setCellValue('G'.$fila, number_format($BalFin05,2,',','.'))
    ->setCellValue('H'.$fila, number_format($BalFin06,2,',','.'))
    ->setCellValue('I'.$fila, number_format($BalFin07,2,',','.'))
    ->setCellValue('J'.$fila, number_format($BalFin08,2,',','.'))
    ->setCellValue('K'.$fila, number_format($BalFin09,2,',','.'))
    ->setCellValue('L'.$fila, number_format($BalFin10,2,',','.'))
    ->setCellValue('M'.$fila, number_format($BalFin11,2,',','.'))
    ->setCellValue('N'.$fila, number_format($BalFin12,2,',','.'));



$objPHPExcel->getActiveSheet()->setTitle('Conciliacion '.$periodo);
//mostrafr la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Reporte_Conciliacion-".$proyecto.'-'.$periodo.'_'.time().".xlsx";

$objWriter->save($nomarch);
echo $nomarch;

?>