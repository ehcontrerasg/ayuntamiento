<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../clases/classPqrs.php';
ini_set('memory_limit', '-1');
$pro=$_POST["proyecto"];
$login=$_POST["login"];
$fecIni=$_POST["fecIni"];
$fecFin=$_POST["fecFin"];
$ofIni=$_POST["ofIni"];
$ofFin=$_POST["ofFin"];

$objPHPExcel = new PHPExcel();

$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'INTERACCIONES DIARIAS');

$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(6);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(70);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('h')->setWidth(12);

$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:Z99")->getFont()
    ->setName('Arial')
    ->setSize(12);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:H2');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B2', 'REPORTE INTERACCIONES DIARIAS');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B2:H2')->applyFromArray(

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


$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B4', 'No');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C4', 'Fecha');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D4', 'Asunto');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('E4', 'Texto');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F4', 'Ususario');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('G4', 'Inmueble');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H4', 'Medio');

$objPHPExcel->setActiveSheetIndex(0)->getStyle('B4:H4')->applyFromArray(

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
$datos=$a->datosInteraccion($pro,$ofIni,$ofFin,$fecIni,$fecFin,$login,9999999999,0,$where);
$t=4;
while (oci_fetch($datos)){

    $t++;
    $numero=oci_result($datos,"RNUM");
    $fecha=oci_result($datos,"FECREG");
    $asunto=oci_result($datos,"DESC_MOTIVO_REC");
    $texto=oci_result($datos,"DESCRIPCION");
    $usuario=oci_result($datos,"LOGIN");
    $inmueble=oci_result($datos,"COD_INMUEBLE");
    $medio=oci_result($datos,"MEDIOREC");


    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B'.$t, $numero);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C'.$t, $fecha);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D'.$t, $asunto);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('E'.$t, $texto);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F'.$t, $usuario);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('G'.$t, $inmueble);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H'.$t, $medio);

}

$objPHPExcel->setActiveSheetIndex(0)->getStyle('B5:H'.$t)->getFont()
    ->setName('Arial')
    ->setSize(10);

$objPHPExcel->setActiveSheetIndex(0)->getStyle('B5:H'.$t)->applyFromArray(

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
$nomarch="../../temp/ReporteInteraccionesDiarias".$proyecto.'-'.time().".xlsx";

$objWriter->save($nomarch);
echo $nomarch;
