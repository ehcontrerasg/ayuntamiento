<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../../clases/class.lectura.php';
ini_set('memory_limit', '-1');

$proyecto=$_POST['proyecto'];
$fecini=$_POST['fecini'];
$fecfin=$_POST['fecfin'];


$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet(0)->setTitle('Rendimiento');

$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("RendCatastro")
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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:D1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:D2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:D1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:D2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'RENDIMIENTO CATASTRO '.$proyecto.' DE '.$fecini.' AL '.$fecfin)
    ->setCellValue('A2', 'USUARIO')
    ->setCellValue('B2', 'ASIGNADOS')
    ->setCellValue('C2', 'VISITADOS')
    ->setCellValue('D2', 'PROMEDIO POR DIA')
;
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(18);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(20);


$c= new Lectura();
$fila = 3;
$cantinm = 0;
$registros=$c->gtRendCatByProFec($proyecto, $fecini,$fecfin);
while (oci_fetch($registros)) {


    $usu=utf8_decode(oci_result($registros,"LOGIN"));
    $asignados=utf8_decode(oci_result($registros,"ASIGNADOS"));
    $visitados=utf8_decode(oci_result($registros,"VISITADOS"));
    $prom=utf8_decode(oci_result($registros,"PROMEDIO_DIA"));


    //$totalmtsinm = $cant1+$cant2+$cant3+$cant4+$cant5+$cant6+$cant7+$cant8+$cant9+$cant10+$cant11+$cant12+$cant13;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $usu)
        ->setCellValue('B'.$fila, $asignados)
        ->setCellValue('C'.$fila, $visitados)
        ->setCellValue('D'.$fila, $prom)
    ;
    $fila++;
}

$filaGrafica = $fila+2;
// activa leyendas
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);
// definir etiquetas
$labels = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'Rendimiento!$B$2', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'Rendimiento!$C$2', null, 1),
);
// definir origen de los valores
$values = array(
    new PHPExcel_Chart_DataSeriesValues('Number', 'Rendimiento!$B$3:$B$'.$fila, null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'Rendimiento!$C$3:$C$'.$fila, null, 20),
);
// definir origen de los rotulos
$categories = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'Rendimiento!$A$3:$A$'.$fila, null, 20),
);
// definir  gráfico
$series = new PHPExcel_Chart_DataSeries(
    PHPExcel_Chart_DataSeries::TYPE_BARCHART, // tipo de gráfico
    PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
    range(0, count($values)-1), //array(0),
    $labels, //array(),
    $categories, // rótulos das columnas
    $values// valores
);

$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_VERTICAL);

// inicializar gráfico
$layout = new PHPExcel_Chart_Layout();
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
$chart = new PHPExcel_Chart('grafica1', null, $legend, $plotarea);

// definir título de gráfico
$title = new PHPExcel_Chart_Title(null, $layout);
$title->setCaption('Rendimiento Catastro');

// definir posiciondo gráfico y título
$chart->setTopLeftPosition('A'.$filaGrafica);
$filaFinal = $filaGrafica + 20;
$chart->setBottomRightPosition('G'.$filaFinal);
$chart->setTitle($title);

// adicionar los graficos a la hoja
$objPHPExcel->getActiveSheet()->addChart($chart);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->setIncludeCharts(TRUE);
$nomarch="../../temp/RendimientoCatastro-".$proyecto.'-del-'.$fecini.' al '.$fecfin.".xlsx";

$objWriter->save($nomarch);
echo $nomarch;

?>