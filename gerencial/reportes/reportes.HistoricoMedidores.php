<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.repHisMed.php';
ini_set('memory_limit', '-1');

$proyecto=$_POST['proyecto'];
$sector=$_POST['sector'];
$ruta=$_POST['ruta'];
$inmueble=$_POST['inmueble'];
//$diametro=$_POST['diametro'];
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
        //$vectorperiodo2[$l]= $periodo;
        $arrayperiodo .= "'" . $periodo . "',";
        $l++;
    }
    $arrayperiodo = substr($arrayperiodo, 1, -2);
}

else{
    for ($periodo = $perini; $periodo <= $anoini.'12'; $periodo++) {
        $vectorperiodo[$l]= $periodo;
        //$vectorperiodo2[$l]= $periodo;
        $arrayperiodo .= "'" . $periodo . "',";
        $l++;
        if($periodo == $anoini.'12'){
            for ($periodo = $anofin.'01'; $periodo <= $perfin; $periodo++) {
                $vectorperiodo[$l]= $periodo;
                //$vectorperiodo2[$l]= $periodo;
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
    ->setTitle("HistoricoMedidores")
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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A1:O1");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:O1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:O2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:O1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:O2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'HISTORICO MEDIDORES INMUEBLE SECTOR'.$sector)
    ->setCellValue('A2', 'INMUEBLE')
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
    ->setCellValue('N2', 'TOTAL CONSUMO')
    ->setCellValue('O2', 'PROMEDIO');
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(20);
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
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(20);



$d=new ReportesHisMed();
$fila = 3;
$datos = $d->InmueblesMedidores($proyecto, $sector, $ruta, $inmueble);
while(oci_fetch($datos)) {
    //$cantper = 0;
    $totalconsumo = 0;
    $promedio = 0;
    //$fila1 = $fila;
    $inmueble = utf8_decode(oci_result($datos, "CODIGO_INM"));
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, $inmueble);
    $c=new ReportesHisMed();
    $registros = $c->historicoMedidores($inmueble, $perini, $perfin, $arrayperiodo);

    while (oci_fetch($registros)) {
        $totalconsumo = 0;
        $promedio = 0;
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

        $totalconsumo = $cant1+$cant2+$cant3+$cant4+$cant5+$cant6+$cant7+$cant8+$cant9+$cant10+$cant11+$cant12;
        $promedio = $totalconsumo/$l;


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $cant1)
            ->setCellValue('C' . $fila, $cant2)
            ->setCellValue('D' . $fila, $cant3)
            ->setCellValue('E' . $fila, $cant4)
            ->setCellValue('F' . $fila, $cant5)
            ->setCellValue('G' . $fila, $cant6)
            ->setCellValue('H' . $fila, $cant7)
            ->setCellValue('I' . $fila, $cant8)
            ->setCellValue('J' . $fila, $cant9)
            ->setCellValue('K' . $fila, $cant10)
            ->setCellValue('L' . $fila, $cant11)
            ->setCellValue('M' . $fila, $cant12)
            ->setCellValue('N' . $fila, $totalconsumo)
            ->setCellValue('O' . $fila, $promedio);

        $fila++;
       // $cantper++;
    }
    oci_free_statement($registros);
}
oci_free_statement($datos);

$objPHPExcel->getActiveSheet(0)->setTitle('Consumos');

//mostrafr la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/HistoricoMedidores-".$proyecto.'-Inmueble-'.$inmueble.".xlsx";

$objWriter->save($nomarch);
echo $nomarch;

?>