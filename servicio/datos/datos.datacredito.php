<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 7/18/2016
 * Time: 12:14 PM
 */

$tipo = $_POST['tip'];
session_start();
$cod=$_SESSION['codigo'];


if($tipo=='selPro'){
    include_once '../../clases/class.proyecto.php';
    $l=new Proyecto();
    $datos = $l->obtenerProyecto($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='reporte'){


    setlocale(LC_MONETARY, 'es_DO');
    $proyecto=$_POST['proy'];

    include "../../clases/class.inmueble.php";
    include '../../recursos/PHPExcel.php';
    include '../../recursos/PHPExcel/Writer/Excel2007.php';

    $a =new Inmueble();
    $datos=$a->getDatInmDatByProy($proyecto);
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
    getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle(utf8_encode("Reporte Inmuebles Datacredito ").$proyecto)
        ->setSubject("")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("usuarios phpexcel")
        ->setCategory("Reportes Medidores");

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

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:L1');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:L2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:L2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'MEDIDORES EN DATACREDITO')
        ->setCellValue('A2', 'Código')
        ->setCellValue('B2', 'Nombre')
        ->setCellValue('C2', 'Sector')
        ->setCellValue('D2', 'Ruta')
        ->setCellValue('E2', 'Telefono')
        ->setCellValue('F2', 'Documento')
        ->setCellValue('G2', 'Direccion')
        ->setCellValue('H2', 'Email')
        ->setCellValue('I2', 'Contrato')
        ->setCellValue('J2', 'Facturas')
        ->setCellValue('K2', 'Deuda')
        ->setCellValue('L2', 'Contrato Fisico');

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(32);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(55);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(13);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(9);

    $fila = 3;
    while (oci_fetch($datos)){
        $inmueble=oci_result($datos,"CODIGO_INM");
        $nombre=oci_result($datos,"NOMBRE_CLI");
        $sector=oci_result($datos,"ID_SECTOR");
        $ruta=oci_result($datos,"ID_RUTA");
        $telefono=oci_result($datos,"TELEFONO");
        $documento=oci_result($datos,"DOCUMENTO") ;
        $direccion=oci_result($datos,"DIRECCION");
        $email=oci_result($datos,"EMAIL");
        $contrato=oci_result($datos,"ID_CONTRATO");
        $facturas=oci_result($datos,"FACTURAS");
        $deuda= oci_result($datos,"DEUDA");
        $contratoFis=oci_result($datos,"CONTRATO_FISICO");


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$fila, $inmueble)
            ->setCellValue('B'.$fila, $nombre)
            ->setCellValue('C'.$fila, $sector)
            ->setCellValue('D'.$fila, $ruta)
            ->setCellValue('E'.$fila, $telefono)
            ->setCellValue('F'.$fila, $documento)
            ->setCellValue('G'.$fila, $direccion)
            ->setCellValue('H'.$fila, $email)
            ->setCellValue('I'.$fila, $contrato)
            ->setCellValue('J'.$fila, $facturas)
            ->setCellValue('K'.$fila, $deuda)
            ->setCellValue('L'.$fila, $contratoFis);
        $fila++;
    }oci_free_statement($datos);

    $objPHPExcel->getActiveSheet()->setTitle('Listado Inmubles');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Inmuebles_Datacredito".time().".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;


}








