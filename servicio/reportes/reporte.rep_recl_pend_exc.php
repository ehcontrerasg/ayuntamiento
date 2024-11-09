<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../../clases/class.pqr.php';
ini_set('memory_limit', '-1');
$pro=$_POST["proyecto"];
$motivo=$_POST["motivo"];
$fecIni=$_POST["fecIni"];
$fecFin=$_POST["fecFin"];

$objPHPExcel = new PHPExcel();

$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Reclamos pendientes');

$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(21);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(33);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);



$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:H2');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B2', 'REPORTE RECLAMOS PENDIENTES');
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


$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B4', 'Sector');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C4', 'Urbanización');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D4', 'Dirección');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('E4', 'Telefono');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F4', 'Codido sistema');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('G4', 'Fecha entrada');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H4', 'Fecha cierre');

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



$a=new Pqr();
$datos=$a->getPqrByFecPro($pro,$fecIni,$fecFin,$motivo);
$t=4;
while (oci_fetch($datos)){

    $t++;
    $sector=oci_result($datos,"SECTOR");
    $urbanizacion=oci_result($datos,"URBANIZACION");
    $direccion=oci_result($datos,"DIRECCION");
    $telefono=oci_result($datos,"TELEFONO");
    $codigo_sis=oci_result($datos,"CODIGO_INM");
    $fecha_ent=oci_result($datos,"FECHA_PQR");
    $fecha_sal=oci_result($datos,"FECHA_CIERRE");


    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B'.$t, $sector);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C'.$t, $urbanizacion);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D'.$t, $direccion);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('E'.$t, $telefono);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F'.$t, $codigo_sis);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('G'.$t, $fecha_ent);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H'.$t, $fecha_sal);

}

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:H'.$t)->getFont()
    ->setName('Arial')
    ->setSize(12);

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
$nomarch="../../temp/RepReclamosFaltantes".$proyecto.'-'.time().".xlsx";

$objWriter->save($nomarch);
echo $nomarch;
