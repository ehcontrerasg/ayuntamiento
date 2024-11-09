<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
setlocale(LC_MONETARY, 'es_DO');


$pro     = $_POST["proyecto"];
$procIni = $_POST["FechaIni"];
$procFin = $_POST["FechaFin"];
$contratista = $_POST["contratista"];

include_once '../../recursos/PHPExcel.php';
include_once '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../../clases/class.corte.php';
include_once '../../clases/class.periodo.php';

$a     = new Periodo();
$datos = $a->getPerByPeriniPerfin($procIni,$procFin);


    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
        getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle(utf8_encode("Analisis de cortes ") . $proyecto)
        ->setSubject("")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("usuarios phpexcel")
        ->setCategory("Reportes Cortes");

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



    $column="A";

//

$periodos="";
$numeroPeriodos=0;

while (oci_fetch($datos)) {
    $column++;
    $numeroPeriodos++;
    $periodo                 = oci_result($datos, "ID_PERIODO");
    $periodos.="'$periodo',";
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($column.'2', $periodo);

}

$periodos=trim($periodos,',');
oci_free_statement($datos);

$a     = new Corte();
$datos = $a->getResumCortByPerProCont($procIni,$procFin,$pro,$contratista,$periodos);

$fila=2;
while (oci_fetch($datos)) {
    $fila++;
    $columna="A";
    for ($i = 1; $i <= $numeroPeriodos+1; $i++) {
        $iten = oci_result($datos, $i);

        if ($fila==3){
            $temp[$i]=$iten;
        }elseif ($fila==4){
            if($columna=="A"){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columna .'3', '1 PAGOS');
            }
            else{
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columna .'3', $iten+$temp[$i]);
            }

        }else{

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columna . ($fila-1), $iten);
        }

        $columna++;

    }
}




$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:'.$column.'1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:'.$column.'2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:'.$column.'2')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'ANALISIS DE CORTE')
    ->setCellValue('A2', 'ITEM');



$objPHPExcel->getActiveSheet()->setTitle('Analisis');
$objPHPExcel->setActiveSheetIndex(0);



$filaGrafica = $fila+2;
// activa leyendas
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);
// definir etiquetas
$labels = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'Analisis!$A$3', null, 1),
);
// definir origen de los valores
$values = array(
    new PHPExcel_Chart_DataSeriesValues('Number', 'Analisis!$B$3:$'.$columna.'$3', null, 20),

);
// definir origen de los rotulos
$categories = array(
    new PHPExcel_Chart_DataSeriesValues('Number', 'Analisis!$B$2:$'.$columna.'$2', null, 20),
);
// definir  gráfico
$series = new PHPExcel_Chart_DataSeries(
    PHPExcel_Chart_DataSeries::TYPE_BARCHART, // tipo de gráfico
    PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
    range(0, count($values)-1), //array(0),
    $labels, //array(),GROUPING_STACKED
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
$title->setCaption('Nº Pagos ');

// definir posiciondo gráfico y título
$chart->setTopLeftPosition('A'.$filaGrafica);
$filaFinal = $filaGrafica + 20;
$chart->setBottomRightPosition($columna.$filaFinal);
$chart->setTitle($title);

// adicionar los graficos a la hoja
$objPHPExcel->getActiveSheet()->addChart($chart);




$filaGrafica = $fila+25;
// activa leyendas
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, null, false);
// definir etiquetas
$labels = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'Analisis!$A$6', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'Analisis!$A$7', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'Analisis!$A$8', null, 1),
    new PHPExcel_Chart_DataSeriesValues('String', 'Analisis!$A$9', null, 1),
);
// definir origen de los valores
$values = array(
    new PHPExcel_Chart_DataSeriesValues('Number', 'Analisis!$B$6:$'.$columna.'$6', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'Analisis!$B$7:$'.$columna.'$7', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'Analisis!$B$8:$'.$columna.'$8', null, 20),
    new PHPExcel_Chart_DataSeriesValues('Number', 'Analisis!$B$9:$'.$columna.'$9', null, 20),

);
// definir origen de los rotulos
$categories = array(
    new PHPExcel_Chart_DataSeriesValues('Number', 'Analisis!$B$2:$'.$columna.'$2', null, 20),
);
// definir  gráfico
$series = new PHPExcel_Chart_DataSeries(
    PHPExcel_Chart_DataSeries::TYPE_BARCHART, // tipo de gráfico
    PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED,
    range(0, count($values)-1), //array(0),
    $labels, //array(),GROUPING_STACKED
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
$title->setCaption('Nº Pagos ');

// definir posiciondo gráfico y título
$chart->setTopLeftPosition('A'.$filaGrafica);
$filaFinal = $filaGrafica + 20;
$chart->setBottomRightPosition($columna.$filaFinal);
$chart->setTitle($title);

// adicionar los graficos a la hoja
$objPHPExcel->getActiveSheet()->addChart($chart);


$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->setIncludeCharts(TRUE);
$nomarch   = "../../temp/analisisDeCorte" . time() . ".xlsx";
$objWriter->save($nomarch);
echo $nomarch;


