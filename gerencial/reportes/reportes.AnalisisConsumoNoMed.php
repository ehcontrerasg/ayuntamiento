<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.repAnaConNomed.php';
ini_set('memory_limit', '-1');

$proyecto=$_POST['proyecto'];
$sector=$_POST['sector'];
$ruta=$_POST['ruta'];
$cliente=$_POST['cliente'];
$diametro=$_POST['diametro'];
$perini=$_POST['perini'];
$perfin=$_POST['perfin'];

$anoini = substr($perini,0,4);
$mesini = substr($perini,4,2);
$anofin = substr($perfin,0,4);
$mesini = substr($perfin,4,2);
$l = 0;

if($anoini == $anofin) {
    for ($periodo = $perini; $periodo <= $perfin; $periodo++) {
        $vectorperiodo[$l]= $periodo;
        $arrayperiodo .= "'" . $periodo . "',";
        $l++;
    }
    $arrayperiodo = substr($arrayperiodo, 1, -2);
}

else{
    for ($periodo = $perini; $periodo <= $anoini.'12'; $periodo++) {
        $vectorperiodo[$l]= $periodo;
        $arrayperiodo .= "'" . $periodo . "',";
        $l++;
        if($periodo == $anoini.'12'){
            for ($periodo = $anofin.'01'; $periodo <= $perfin; $periodo++) {
                $vectorperiodo[$l]= $periodo;
                $arrayperiodo .= "'" . $periodo . "',";
                $l++;
            }
        }
    }
    $arrayperiodo = substr($arrayperiodo, 1, -2);
}


$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("AnalisisConsumoNoMedidosDel-".$perini."-al-".$perfin."-sector-".$sector)
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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:Q1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:Q1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:Q2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:Q1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:Q2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'ANALISIS CONSUMO '.$proyecto.' SECTOR '.$sector.' RUTA '.$ruta.' PERIODOS '.$perini.'-'.$perfin)
    ->setCellValue('A2', 'INMUEBLE')
    ->setCellValue('B2', 'USO')
    ->setCellValue('C2', 'CALIBRE')
    ->setCellValue('D2', $vectorperiodo[0])
    ->setCellValue('E2', $vectorperiodo[1])
    ->setCellValue('F2', $vectorperiodo[2])
    ->setCellValue('G2', $vectorperiodo[3])
    ->setCellValue('H2', $vectorperiodo[4])
    ->setCellValue('I2', $vectorperiodo[5])
    ->setCellValue('J2', $vectorperiodo[6])
    ->setCellValue('K2', $vectorperiodo[7])
    ->setCellValue('L2', $vectorperiodo[8])
    ->setCellValue('M2', $vectorperiodo[9])
    ->setCellValue('N2', $vectorperiodo[10])
    ->setCellValue('O2', $vectorperiodo[11])
    ->setCellValue('P2', $vectorperiodo[12])
    ->setCellValue('Q2', 'TOTAL METROS');
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(12);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Q")->setWidth(15);

$c=new ReportesAnaConNomed();
$fila = 3;
$cantinm = 0;
$registros=$c->NoMedidosPromedio($proyecto, $sector, $ruta, $cliente, $diametro, $perini, $perfin, $arrayperiodo);
while (oci_fetch($registros)) {
    $inmueble=utf8_decode(oci_result($registros,"CODIGO_INM"));
    $uso=utf8_decode(oci_result($registros,"ID_USO"));
    $diametroa=utf8_decode(oci_result($registros,"DESC_CALIBRE"));
    $cant1=utf8_decode(oci_result($registros,"'$vectorperiodo[0]'"));
    $cant2=utf8_decode(oci_result($registros,"'$vectorperiodo[1]'"));
    $cant3=utf8_decode(oci_result($registros,"'$vectorperiodo[2]'"));
    $cant4=utf8_decode(oci_result($registros,"'$vectorperiodo[3]'"));
    $cant5=utf8_decode(oci_result($registros,"'$vectorperiodo[4]'"));
    $cant6=utf8_decode(oci_result($registros,"'$vectorperiodo[5]'"));
    $cant7=utf8_decode(oci_result($registros,"'$vectorperiodo[6]'"));
    $cant8=utf8_decode(oci_result($registros,"'$vectorperiodo[7]'"));
    $cant9=utf8_decode(oci_result($registros,"'$vectorperiodo[8]'"));
    $cant10=utf8_decode(oci_result($registros,"'$vectorperiodo[9]'"));
    $cant11=utf8_decode(oci_result($registros,"'$vectorperiodo[10]'"));
    $cant12=utf8_decode(oci_result($registros,"'$vectorperiodo[11]'"));
    $cant13=utf8_decode(oci_result($registros,"'$vectorperiodo[12]'"));
    /* if($cant1 == '')$cant1 = 0;
     if($cant2 == '')$cant2 = 0;
     if($cant3 == '')$cant3 = 0;
     if($cant4 == '')$cant4 = 0;
     if($cant5 == '')$cant5 = 0;
     if($cant6 == '')$cant6 = 0;
     if($cant7 == '')$cant7 = 0;
     if($cant8 == '')$cant8 = 0;
     if($cant9 == '')$cant9 = 0;
     if($cant10 == '')$cant10 = 0;
     if($cant11 == '')$cant11 = 0;
     if($cant12 == '')$cant12 = 0;
     if($cant13 == '')$cant13 = 0;*/
    $totalmtsinm = $cant1+$cant2+$cant3+$cant4+$cant5+$cant6+$cant7+$cant8+$cant9+$cant10+$cant11+$cant12+$cant13;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $inmueble)
        ->setCellValue('B'.$fila, $uso)
        ->setCellValue('C'.$fila, $diametroa)
        ->setCellValue('D'.$fila, $cant1)
        ->setCellValue('E'.$fila, $cant2)
        ->setCellValue('F'.$fila, $cant3)
        ->setCellValue('G'.$fila, $cant4)
        ->setCellValue('H'.$fila, $cant5)
        ->setCellValue('I'.$fila, $cant6)
        ->setCellValue('J'.$fila, $cant7)
        ->setCellValue('K'.$fila, $cant8)
        ->setCellValue('L'.$fila, $cant9)
        ->setCellValue('M'.$fila, $cant10)
        ->setCellValue('N'.$fila, $cant11)
        ->setCellValue('O'.$fila, $cant12)
        ->setCellValue('P'.$fila, $cant13)
        ->setCellValue('Q'.$fila, $totalmtsinm);
    $fila++;
    $totalperiodo1 += $cant1;
    $totalperiodo2 += $cant2;
    $totalperiodo3 += $cant3;
    $totalperiodo4 += $cant4;
    $totalperiodo5 += $cant5;
    $totalperiodo6 += $cant6;
    $totalperiodo7 += $cant7;
    $totalperiodo8 += $cant8;
    $totalperiodo9 += $cant9;
    $totalperiodo10 += $cant10;
    $totalperiodo11 += $cant11;
    $totalperiodo12 += $cant12;
    $totalperiodo13 += $cant13;
    $cantinm ++;
}oci_free_statement($registros);
$totalperiodos = $totalperiodo1+$totalperiodo2+$totalperiodo3+$totalperiodo4+$totalperiodo5+$totalperiodo6+$totalperiodo7
    +$totalperiodo8+$totalperiodo9+$totalperiodo10+$totalperiodo11+$totalperiodo12+$totalperiodo13;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":C".$fila);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":Q".$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'Total mts')
    ->setCellValue('D'.$fila, $totalperiodo1)
    ->setCellValue('E'.$fila, $totalperiodo2)
    ->setCellValue('F'.$fila, $totalperiodo3)
    ->setCellValue('G'.$fila, $totalperiodo4)
    ->setCellValue('H'.$fila, $totalperiodo5)
    ->setCellValue('I'.$fila, $totalperiodo6)
    ->setCellValue('J'.$fila, $totalperiodo7)
    ->setCellValue('K'.$fila, $totalperiodo8)
    ->setCellValue('L'.$fila, $totalperiodo9)
    ->setCellValue('M'.$fila, $totalperiodo10)
    ->setCellValue('N'.$fila, $totalperiodo11)
    ->setCellValue('O'.$fila, $totalperiodo12)
    ->setCellValue('P'.$fila, $totalperiodo13)
    ->setCellValue('Q'.$fila, $totalperiodos);
$fila = $fila+5;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":E".$fila);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":E".$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'RESUMEN:');
$fila++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":E".$fila);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'Clientes No Medidos Facturados por Promedio: '.$cantinm);

if ($ruta == '') $descruta = 'Todas las rutas';
else $descruta = $ruta;
$fila++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":E".$fila);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'Sector: '.$sector.' Ruta: '.$descruta);

if ($cliente == '') $descliente = 'Todos los usos';
else $descliente = $cliente;
$fila++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":E".$fila);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'Uso: '.$descliente);

if ($diametro == '') $desdiametro = 'Todos los diametros';
else $desdiametro = $diametro;
$fila++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":E".$fila);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'Diametro: '.$desdiametro);

$fila++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":E".$fila);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'Metros Facturados Totales: '.$totalperiodos);

$fila++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":E".$fila);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'Periodos del '.$perini.' al '.$perfin);

$objPHPExcel->getActiveSheet(0)->setTitle('No Medidos Promedio');

//mostrafr la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/AnalisisConsumoNoMedidos-".$proyecto.'-'.$perini.'-'.$perfin.".xlsx";

$objWriter->save($nomarch);
echo $nomarch;

?>