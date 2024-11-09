<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once  '../../clases/class.pdfRep.php';

/*ini_set('memory_limit', '-1');
ini_set('display_errors', 1);*/
$periodo=$_POST['periodo'];
$proyecto=$_POST['proyecto'];
$nombre_formulario = "";
$edicion_formulario = "";
$fecha_edicion = "";
$imagen = "";


$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo acea');
//$objDrawing->setPath('../../images/logo_acea.png');


$r = new Reporte();


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



$estiloLineaBaja = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'   =>PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);


$estiloLineaLateral = array(
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        ),
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    )
);


$estiloLineaDelgada = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    )
);

$estiloTitulos2 = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => '000000')
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        'vertical'   =>PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);




$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("Aceasoft");
$objPHPExcel->getProperties()->setLastModifiedBy("Aaceasoft");
$objPHPExcel->getProperties()->setTitle("Reporte reconexiones recibidas");
$objPHPExcel->getProperties()->setSubject("Reporte");
$objPHPExcel->getProperties()->setDescription("Reporte general aceasoft");
$objDrawing->setCoordinates('A1');
$objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));




//TRAER LAS EDICIONES DEL FORMULARIO
$ediciones = $r->getFormDates('FO-COR-05');

while($row = oci_fetch_assoc($ediciones)){
    //echo date('Ym',strtotime($row["FECHA_EMISION"]))/*print_r($row)*/;
   // echo count($row);
    //if(strtotime($periodo) >= strtotime(date('Ym',strtotime($row["FECHA_EMISION"])))){

        $nombre_formulario  =  $row["DESCRIPCION"];
        $edicion_formulario =  $row["EDICION"];
        $fecha_edicion      =  $row["FECHA_EMISION"];
       $imagen =  $row["IMAGEN"];
       // echo 'IMAGEN'.$imagen;
        //echo $nombre_formulario;

    //}
}

//$this->SetFont('times', "B", 19);

//$this->Text(54, 11, utf8_decode($nombre_formulario/*"Relación de inmuebles  "*/));
if($imagen != ""){
    $objDrawing->setWidth(2);
    $objDrawing->setHeight(100);
    $objDrawing->setPath($imagen/*'../../images/logo_acea.png'*/);

   // $this->Image($this->imagen, 7.5, 3.5, 17, 15.5);
}

$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C1', $nombre_formulario/*'REPORTE MENSUAL:VERIFICACIÓN MEDIDORES Y ACOMETIDAS'*/);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('N1', 'Código: FO-COR-05');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('N3', 'Edición No.: '. $edicion_formulario/*'04'*/);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('N5', 'Fecha de emisión: '. date('d-m-Y',strtotime($fecha_edicion))/*'10-07-2018'*/);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(28.57);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A8', 'PERIODO:');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A9', 'GERENCIA COMERCIAL');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C8', $periodo);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C9', "ACEA DOMINICANA (ESTE-NORTE)");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H8', "CLIENTE:");
if($proyecto=='SD'){
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('I8', "CORPORACIÓN DE ACUEDUCTO Y ALCANTARILLADO DE SANTO DOMINGO");
}

if($proyecto=='BC'){
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('I8', "CORPORACIÓN DE ACUEDUCTO Y ALCANTARILLADO DE BOCA CHICA");
}

$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A11', "1. PLANIFICACION, VERIFICACION COMERCIAL Y PROCESAMIENTO EN EL SISTEMA DE GESTION COMERCIAL DE LAS ACTIVIDADES DE INSTALACION DE MEDIDORES Y MANTENIMIENTO, REPARACION Y RENOVACION DE MEDIDORES EXISTENTES");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A14', "1.1 SITUACION MEDIDORES RESULTANTES EN CAMPO");



$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/reconexiones_recibidas".$proyecto.'-'.time().".xlsx";

$objWriter->save($nomarch);
echo $nomarch;
