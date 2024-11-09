<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.repAntMed.php';
ini_set('memory_limit', '-1');

$proyecto=$_POST['proyecto'];
$sector=$_POST['sector'];
$ruta=$_POST['ruta'];
$cliente=$_POST['cliente'];
$diametro=$_POST['diametro'];
//$perini=$_POST['perini'];
//$perfin=$_POST['perfin'];


$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("AntiguedadMedidores")
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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:H1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:H2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:H1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:H2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'ANTIGUEDAD MEDIDORES'.$proyecto.' SECTOR '.$sector.' RUTA '.$ruta)
    ->setCellValue('A2', 'SECTOR')
    ->setCellValue('B2', 'RUTA')
    ->setCellValue('C2', 'INMUEBLE')
    ->setCellValue('D2', 'DIAMETRO')
    ->setCellValue('E2', 'MEDIDOR')
    ->setCellValue('F2', 'SERIAL')
    ->setCellValue('G2', 'FECHA INSTALACION')
    ->setCellValue('H2', 'FECHA BAJA');
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(20);

$c=new ReportesAntMed();
$fila = 3;
$cantinm = 0;
$registros=$c->antiguedadMedidores($proyecto, $sector, $ruta, $cliente, $diametro);
while (oci_fetch($registros)) {
    $idsector=utf8_decode(oci_result($registros,"ID_SECTOR"));
    $idruta=utf8_decode(oci_result($registros,"ID_RUTA"));
    $inmueble=utf8_decode(oci_result($registros,"CODIGO_INM"));
    $calibre=utf8_decode(oci_result($registros,"DESC_CALIBRE"));
    $medidor=utf8_decode(oci_result($registros,"DESC_MED"));
    $serial=utf8_decode(oci_result($registros,"SERIAL"));
    $fecinst=utf8_decode(oci_result($registros,"FECHA_INSTAL"));
    $fecbaja=utf8_decode(oci_result($registros,"FECHA_BAJA"));

    //$totalmtsinm = $cant1+$cant2+$cant3+$cant4+$cant5+$cant6+$cant7+$cant8+$cant9+$cant10+$cant11+$cant12+$cant13;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $idsector)
        ->setCellValue('B'.$fila, $idruta)
        ->setCellValue('C'.$fila, $inmueble)
        ->setCellValue('D'.$fila, $calibre)
        ->setCellValue('E'.$fila, $medidor)
        ->setCellValue('F'.$fila, $serial)
        ->setCellValue('G'.$fila, $fecinst)
        ->setCellValue('H'.$fila, $fecbaja);
    $fila++;
  /*  $totalperiodo1 += $cant1;
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
    $totalperiodo13 += $cant13;*/
    $cantinm ++;
}oci_free_statement($registros);
/*$totalperiodos = $totalperiodo1+$totalperiodo2+$totalperiodo3+$totalperiodo4+$totalperiodo5+$totalperiodo6+$totalperiodo7
    +$totalperiodo8+$totalperiodo9+$totalperiodo10+$totalperiodo11+$totalperiodo12+$totalperiodo13;*/
/*$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":C".$fila);
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
    ->setCellValue('Q'.$fila, $totalperiodos);*/
$fila = $fila+5;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":E".$fila);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":E".$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'RESUMEN:');
$fila++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":E".$fila);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'Cantidad de medidores: '.$cantinm);

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

/*$fila++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":E".$fila);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'Metros Facturados Totales: '.$totalperiodos);

$fila++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":E".$fila);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'Periodos del '.$perini.' al '.$perfin);*/

$objPHPExcel->getActiveSheet(0)->setTitle('Antiguedad');

//mostrafr la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/AntiguedadMedidores-".$proyecto.'-Sector-'.$sector.'-'.$descruta.".xlsx";

$objWriter->save($nomarch);
echo $nomarch;

?>