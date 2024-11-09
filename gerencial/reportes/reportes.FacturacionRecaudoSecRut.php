<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.repFacRecSecRut.php';
ini_set('memory_limit', '-1');

$proyecto=$_POST['proyecto'];
$sector=$_POST['sector'];
$rutaini=$_POST['rutaini'];
$rutafin=$_POST['rutafin'];
$uso=$_POST['uso'];
//$diametro=$_POST['diametro'];
$periodo=$_POST['periodo'];
$ano = substr($periodo,0,4);
$mes = substr($periodo,4,2);

if($mes == '01' || $mes == '03' || $mes == '05' || $mes == '07' || $mes == '08' || $mes == '10' || $mes == '12') $dia = '31';
if($mes == '04' || $mes == '06' || $mes == '09' || $mes == '11') $dia = '30';
if($ano%4 == 0 && $mes == '02') $dia = '29';
if($ano%4 != 0 && $mes == '02') $dia = '28';



$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet(0)->setTitle('FacturadoVsRecaudo');

$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("FacturacionRecaudoSectorRuta")
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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A1:G1");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:G1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:G2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:G1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:G2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'FACTURACION VS RECAUDO SECTOR '.$sector.' RUTAS '.$rutaini.' A '.$rutafin)
    ->setCellValue('A2', 'RUTA')
    ->setCellValue('B2', 'FACTURACION')
    ->setCellValue('C2', 'RECAUDO')
    ->setCellValue('D2', '% REC/FAC')
    ->setCellValue('E2', 'Nº FACTURAS')
    ->setCellValue('F2', 'Nº PAGOS')
    ->setCellValue('G2', '% Nº PAG/Nº FAC');
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(15);
$d=new ReportesFacRecSecRut();
$fila = 3;
$cantrutas = 1;
$datos = $d->facturadoRuta($proyecto, $sector, $rutaini, $rutafin,$uso, $periodo);
while(oci_fetch($datos)) {

    $ruta = utf8_decode(oci_result($datos, "ID_RUTA"));
    $facturado = utf8_decode(oci_result($datos, "FACTURADO"));
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $ruta)
        ->setCellValue('B'.$fila, $facturado);

    $c=new ReportesFacRecSecRut();
    $datosa = $c->recaudadoRuta($proyecto, $sector, $ruta, $uso, $ano, $mes, $dia);
    while(oci_fetch($datosa)) {
        $recaudado = utf8_decode(oci_result($datosa, "RECAUDADO"));
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.$fila, $recaudado);
    }oci_free_statement($datosa);

    $porcentaje = round((($recaudado/$facturado)*100),2);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D'.$fila, $porcentaje.' %' );
    $totalfacturado += $facturado;
    $totalrecaudado += $recaudado;

    $fila++;
    $cantrutas++;
}
oci_free_statement($datos);
$totalporcentaje = round((($totalrecaudado/$totalfacturado)*100),2);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':G'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$fila, 'TOTAL')
    ->setCellValue('B'.$fila, $totalfacturado)
    ->setCellValue('C'.$fila, $totalrecaudado)
    ->setCellValue('D'.$fila, $totalporcentaje.' %' );

$d=new ReportesFacRecSecRut();
$fila = 3;
$datos = $d->facturasRuta($proyecto, $sector, $rutaini, $rutafin,$uso, $periodo);
while(oci_fetch($datos)) {
    $ruta = utf8_decode(oci_result($datos, "ID_RUTA"));
    $facturas = utf8_decode(oci_result($datos, "FACTURAS"));
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('E'.$fila, $facturas);

    $c=new ReportesFacRecSecRut();
    $datosa = $c->pagosRuta($proyecto, $sector, $ruta, $uso, $ano, $mes, $dia);
    while(oci_fetch($datosa)) {
        $pagos = utf8_decode(oci_result($datosa, "PAGOS"));
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F'.$fila, $pagos);
    }oci_free_statement($datosa);

    $porcentaje2 = round((($pagos/$facturas)*100),2);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('G'.$fila, $porcentaje2.' %' );
    $totalfacturas += $facturas;
    $totalpagos += $pagos;


    $fila++;
}
oci_free_statement($datos);
$totalporcentaje2 = round((($totalpagos/$totalfacturas)*100),2);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':G'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)

    ->setCellValue('E'.$fila, $totalfacturas)
    ->setCellValue('F'.$fila, $totalpagos)
    ->setCellValue('G'.$fila, $totalporcentaje2.' %' );


//INICIO GRAFICA 1
if($cantrutas <= 10) $filaPosicion = 15;
if($cantrutas > 10 && $cantrutas <= 20) $filaPosicion = 30;
if($cantrutas > 20 && $cantrutas <= 30) $filaPosicion = 45;
if($cantrutas > 30 && $cantrutas <= 40) $filaPosicion = 60;
if($cantrutas > 40 && $cantrutas <= 50) $filaPosicion = 75;
if($cantrutas > 50 && $cantrutas <= 60) $filaPosicion = 90;
if($cantrutas > 60 && $cantrutas <= 70) $filaPosicion = 105;
if($cantrutas > 70 && $cantrutas <= 80) $filaPosicion = 120;
if($cantrutas > 80 && $cantrutas <= 90) $filaPosicion = 135;
if($cantrutas > 90 && $cantrutas <= 100) $filaPosicion = 150;

$filaGrafica = $fila+2;
// activa leyendas
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);
// definir etiquetas
$labels = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'FacturadoVsRecaudo!$B$2', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'FacturadoVsRecaudo!$C$2', null, 1),
);
// definir origen de los valores
$values = array(
    new PHPExcel_Chart_DataSeriesValues('Number', 'FacturadoVsRecaudo!$B$3:$B$'.$fila, null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'FacturadoVsRecaudo!$C$3:$C$'.$fila, null, 20),
);
// definir origen de los rotulos
$categories = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'FacturadoVsRecaudo!$A$3:$A$'.$fila, null, 20),
);
// definir  gráfico
$series = new PHPExcel_Chart_DataSeries(
    PHPExcel_Chart_DataSeries::TYPE_BARCHART, // tipo de gráfico
    PHPExcel_Chart_DataSeries::GROUPING_PERCENT_STACKED,
    range(0, count($values)-1), //array(0),
    $labels, //array(),
    $categories, // rótulos das columnas
    $values// valores
);

$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_HORIZONTAL);

// inicializar gráfico
$layout = new PHPExcel_Chart_Layout();
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
$chart = new PHPExcel_Chart('grafica1', null, $legend, $plotarea);

// definir título de gráfico
$title = new PHPExcel_Chart_Title(null, $layout);
$title->setCaption('Facturacion VS Recaudo');

// definir posiciondo gráfico y título
$chart->setTopLeftPosition('B'.$filaGrafica);
$filaFinal = $filaGrafica + $filaPosicion;
$chart->setBottomRightPosition('E'.$filaFinal);
$chart->setTitle($title);

//GRAFICA2
$filaGrafica2 = $fila+2;
// activa leyendas
$legend2 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);
// definir etiquetas
$labels2 = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'FacturadoVsRecaudo!$E$2', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'FacturadoVsRecaudo!$F$2', null, 1),
);
// definir origen de los valores
$values2 = array(
    new PHPExcel_Chart_DataSeriesValues('Number', 'FacturadoVsRecaudo!$E$3:$E$'.$fila, null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'FacturadoVsRecaudo!$F$3:$F$'.$fila, null, 20),
);
// definir origen de los rotulos
$categories2 = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'FacturadoVsRecaudo!$A$3:$A$'.$fila, null, 20),
);
// definir  gráfico
$series2 = new PHPExcel_Chart_DataSeries(
    PHPExcel_Chart_DataSeries::TYPE_BARCHART, // tipo de gráfico
    PHPExcel_Chart_DataSeries::GROUPING_PERCENT_STACKED,
    range(0, count($values2)-1), //array(0),
    $labels2, //array(),
    $categories2, // rótulos das columnas
    $values2// valores
);

$series2->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_HORIZONTAL);

// inicializar gráfico
$layout2 = new PHPExcel_Chart_Layout();
$plotarea2 = new PHPExcel_Chart_PlotArea($layout2, array($series2));
$chart2 = new PHPExcel_Chart('grafica1', null, $legend2, $plotarea2);

// definir título de gráfico
$title2 = new PHPExcel_Chart_Title(null, $layout2);
$title2->setCaption('Facturas VS Pagos');

// definir posiciondo gráfico y título
$chart2->setTopLeftPosition('E'.$filaGrafica2);
$filaFinal2 = $filaGrafica2 + $filaPosicion;
$chart2->setBottomRightPosition('H'.$filaFinal2);
$chart2->setTitle($title2);

// adicionar los graficos a la hoja
$objPHPExcel->getActiveSheet()->addChart($chart);
$objPHPExcel->getActiveSheet()->addChart($chart2);


// FIN DE GRAFICA

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->setIncludeCharts(TRUE);
$nomarch="../../temp/Facturado Vs Recaudado-".$proyecto.'-Sector-'.$sector.".xlsx";

$objWriter->save($nomarch);
echo $nomarch;

?>