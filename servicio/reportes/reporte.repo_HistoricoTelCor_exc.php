<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../clases/classPqrs.php';
ini_set('memory_limit', '-1');

$pro=$_POST["proyecto"];
$fecIni=$_POST["fecIni"];
$fecFin=$_POST["fecFin"];

/*
$fecIni='2022-09-01';
$fecFin='2023-12-31';
*/

$objPHPExcel = new PHPExcel();

$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'HISTORICO TELEFONO Y CORREOS');

$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);

$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:Z99")->getFont()
    ->setName('Arial')
    ->setSize(12);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:I2');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B2', 'REPORTE HISTORICO TELEFONO Y CORREOS');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B2:I2')->applyFromArray(

    array(

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
    )

);


$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B4', 'Codigo de sistema');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C4', 'Nombre');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D4', 'Telefono Anterior');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('E4', 'Telefono Actualizado');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F4', 'Correo Anterior');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('G4', 'Correo Actualizado');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H4', 'Fecha Actualizado');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('I4', 'Usuario');

$objPHPExcel->setActiveSheetIndex(0)->getStyle('B4:I4')->applyFromArray(

    array(

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
    )

);



$a=new PQRs();
$datos=$a->datosHistoricoTelCor($pro,$fecIni,$fecFin,9999999999,0);
$t=4;
while (oci_fetch($datos)){

    $t++;
    $inmueble = oci_result($datos, 'CODIGO_INM');
    $nombre = oci_result($datos, 'NOMBRE');
    $telefono_ant = oci_result($datos, 'TELEFONO_ANT');
    $telefono_act = oci_result($datos, 'TELEFONO_ACT');
    $correo_ant = oci_result($datos, 'CORREO_ANT');
    $correo_act = oci_result($datos, 'CORREO_ACT');
    $fecha = oci_result($datos, 'FECHA_ACTUALIZACION');
    $usuario = oci_result($datos, 'USUARIO');


    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B'.$t, $inmueble);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C'.$t, $nombre);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D'.$t, $telefono_ant);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('E'.$t, $telefono_act);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F'.$t, $correo_ant);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('G'.$t, $correo_act);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H'.$t, $fecha);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('I'.$t, $usuario);

}

$objPHPExcel->setActiveSheetIndex(0)->getStyle('B5:I'.$t)->getFont()
    ->setName('Arial')
    ->setSize(10);

$objPHPExcel->setActiveSheetIndex(0)->getStyle('B5:I'.$t)->applyFromArray(

    array(

        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_HAIR,
                'color' => array('rgb' => '000000')
            )
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical'   =>PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    )

);





$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/ReporteHistoricoTelCor".$pro.'-'.time().".xlsx";

$objWriter->save($nomarch);
echo $nomarch;
