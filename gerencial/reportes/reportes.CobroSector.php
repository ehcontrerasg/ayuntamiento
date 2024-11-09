<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.repCobSec.php';
ini_set('memory_limit', '-1');

$proyecto=$_POST['proyecto'];
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
        $vectorperiodo2[$l]= $periodo;
        $arrayperiodo .= "'" . $periodo . "',";
        $l++;
    }
    $arrayperiodo = substr($arrayperiodo, 1, -2);
}

else{
    for ($periodo = $perini; $periodo <= $anoini.'12'; $periodo++) {
        $vectorperiodo[$l]= $periodo;
        $vectorperiodo2[$l]= $periodo;
        $arrayperiodo .= "'" . $periodo . "',";
        $l++;
        if($periodo == $anoini.'12'){
            for ($periodo = $anofin.'01'; $periodo <= $perfin; $periodo++) {
                $vectorperiodo[$l]= $periodo;
                $vectorperiodo2[$l]= $periodo;
                $arrayperiodo .= "'" . $periodo . "',";
                $l++;
            }
        }
    }
    $arrayperiodo = substr($arrayperiodo, 1, -2);
}


$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet(0)->setTitle('EfectividadCobro');

$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("AnalisisConsumoMedidosDel-".$perini."-al-".$perfin."-sector-".$sector)
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

//HOJA EFECTIVIDAD COBRO POR SECTOR

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:N1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:N1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:N2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:N1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:N2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'EFECTIVIDAD COBRO POR SECTOR PERIODOS '.$perini.'-'.$perfin)
    ->setCellValue('A2', 'SECTOR')
    ->setCellValue('B2', $vectorperiodo[0])
    ->setCellValue('C2', $vectorperiodo[1])
    ->setCellValue('D2', $vectorperiodo[2])
    ->setCellValue('E2', $vectorperiodo[3])
    ->setCellValue('F2', $vectorperiodo[4])
    ->setCellValue('G2', $vectorperiodo[5])
    ->setCellValue('H2', $vectorperiodo[6])
    ->setCellValue('I2', $vectorperiodo[7])
    ->setCellValue('J2', $vectorperiodo[8])
    ->setCellValue('K2', $vectorperiodo[9])
    ->setCellValue('L2', $vectorperiodo[10])
    ->setCellValue('M2', $vectorperiodo[11])
    ->setCellValue('N2', $vectorperiodo[12]);
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

$c=new ReportesCobSec();
$fila = 3;
$registros=$c->facturadoSector($proyecto, $perini, $perfin, $arrayperiodo);
while (oci_fetch($registros)) {
    $sector=utf8_decode(oci_result($registros,"ID_SECTOR"));
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

    $d=new ReportesCobSec();
    $datos=$d->recaudadoSector($proyecto, $sector, $perini, $perfin, $arrayperiodo);
    while (oci_fetch($datos)){
        $canta1=utf8_decode(oci_result($datos,"'$vectorperiodo2[0]'"));
        $canta2=utf8_decode(oci_result($datos,"'$vectorperiodo2[1]'"));
        $canta3=utf8_decode(oci_result($datos,"'$vectorperiodo2[2]'"));
        $canta4=utf8_decode(oci_result($datos,"'$vectorperiodo2[3]'"));
        $canta5=utf8_decode(oci_result($datos,"'$vectorperiodo2[4]'"));
        $canta6=utf8_decode(oci_result($datos,"'$vectorperiodo2[5]'"));
        $canta7=utf8_decode(oci_result($datos,"'$vectorperiodo2[6]'"));
        $canta8=utf8_decode(oci_result($datos,"'$vectorperiodo2[7]'"));
        $canta9=utf8_decode(oci_result($datos,"'$vectorperiodo2[8]'"));
        $canta10=utf8_decode(oci_result($datos,"'$vectorperiodo2[9]'"));
        $canta11=utf8_decode(oci_result($datos,"'$vectorperiodo2[10]'"));
        $canta12=utf8_decode(oci_result($datos,"'$vectorperiodo2[11]'"));
        $canta13=utf8_decode(oci_result($datos,"'$vectorperiodo2[12]'"));

        $porcentaje1 = round((($canta1/$cant1)*100),2);
        $porcentaje2 = round((($canta2/$cant2)*100),2);
        $porcentaje3 = round((($canta3/$cant3)*100),2);
        $porcentaje4 = round((($canta4/$cant4)*100),2);
        $porcentaje5 = round((($canta5/$cant5)*100),2);
        $porcentaje6 = round((($canta6/$cant6)*100),2);
        $porcentaje7 = round((($canta7/$cant7)*100),2);
        $porcentaje8 = round((($canta8/$cant8)*100),2);
        $porcentaje9 = round((($canta9/$cant9)*100),2);
        $porcentaje10 = round((($canta10/$cant10)*100),2);
        $porcentaje11 = round((($canta11/$cant11)*100),2);
        $porcentaje12 = round((($canta12/$cant12)*100),2);
        $porcentaje13 = round((($canta13/$cant13)*100),2);

        if($porcentaje1 == '') $porcentaje1 = '';
        if($porcentaje2 == '') $porcentaje2 = '';
        if($porcentaje3 == '') $porcentaje3 = '';
        if($porcentaje4 == '') $porcentaje4 = '';
        if($porcentaje5 == '') $porcentaje5 = '';
        if($porcentaje6 == '') $porcentaje6 = '';
        if($porcentaje7 == '') $porcentaje7 = '';
        if($porcentaje8 == '') $porcentaje8 = '';
        if($porcentaje9 == '') $porcentaje9 = '';
        if($porcentaje10 == '') $porcentaje10 = '';
        if($porcentaje11 == '') $porcentaje11 = '';
        if($porcentaje12 == '') $porcentaje12 = '';
        if($porcentaje13 == '') $porcentaje13 = '';

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $sector)
        ->setCellValue('B'.$fila, $porcentaje1)
        ->setCellValue('C'.$fila, $porcentaje2)
        ->setCellValue('D'.$fila, $porcentaje3)
        ->setCellValue('E'.$fila, $porcentaje4)
        ->setCellValue('F'.$fila, $porcentaje5)
        ->setCellValue('G'.$fila, $porcentaje6)
        ->setCellValue('H'.$fila, $porcentaje7)
        ->setCellValue('I'.$fila, $porcentaje8)
        ->setCellValue('J'.$fila, $porcentaje9)
        ->setCellValue('K'.$fila, $porcentaje10)
        ->setCellValue('L'.$fila, $porcentaje11)
        ->setCellValue('M'.$fila, $porcentaje12)
        ->setCellValue('N'.$fila, $porcentaje13);
    $fila++;
    }oci_free_statement($datos);

}oci_free_statement($registros);

//INICIO DE GRAFICA 1

$filaGrafica = $fila+2;
// activa leyendas
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);
// definir etiquetas

$labels = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$3', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$4', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$5', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$6', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$7', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$8', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$9', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$10', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$11', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$12', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$13', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$14', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$15', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$16', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$17', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$18', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$19', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$20', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$21', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$22', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$A$23', null, 1),
);

// definir origen de los valores
$values = array(
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$3:$N$3', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$4:$N$4', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$5:$N$5', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$6:$N$6', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$7:$N$7', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$8:$N$8', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$9:$N$9', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$10:$N$10', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$11:$N$11', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$12:$N$12', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$13:$N$13', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$14:$N$14', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$15:$N$15', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$16:$N$16', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$17:$N$17', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$18:$N$18', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$19:$N$19', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$20:$N$20', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$21:$N$21', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$22:$N$22', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'EfectividadCobro!$B$23:$N$23', null, 20),
);
// definir origen de los rotulos
$categories = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'EfectividadCobro!$B$2:$N$2', null, 20),
);
// definir  gráfico
$series = new PHPExcel_Chart_DataSeries(
    PHPExcel_Chart_DataSeries::TYPE_LINECHART, // tipo de gráfico
    PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
    range(0, count($values)-1), //array(0),
    $labels, //array(),
    $categories, // rótulos das columnas
    $values// valores
);

$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

// inicializar gráfico
$layout = new PHPExcel_Chart_Layout();
$layout->setShowPercent(TRUE);
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series));
$chart = new PHPExcel_Chart('grafica1', null, $legend, $plotarea);

// definir título de gráfico
$title = new PHPExcel_Chart_Title(null, $layout);
$title->setCaption('Efectividad Cobro Por Sector');

// definir posiciondo gráfico y título
$chart->setTopLeftPosition('B'.$filaGrafica);
$filaFinal = $filaGrafica + 20;
$chart->setBottomRightPosition('M'.$filaFinal);
$chart->setTitle($title);

// adicionar o gráfico à folha
$objPHPExcel->getActiveSheet()->addChart($chart);

// FIN DE GRAFICA 1


$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->setIncludeCharts(TRUE);
$nomarch="../../temp/EfectividadCobro-".$proyecto.".xlsx";

$objWriter->save($nomarch);
echo $nomarch;

?>