<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */
setlocale(LC_MONETARY, 'es_DO');


$pro     = $_POST["proyecto"];
$procIni = $_POST["FechaIni"];
$procFin = $_POST["FechaFin"];
$contratista = $_POST["contratista"];

include_once '../../recursos/PHPExcel.php';
include_once '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../../clases/class.corte.php';

$a     = new Corte();
$datos = $a->getCorXFecAsig($procIni, $procFin,$pro,$contratista);


    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
        getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle(utf8_encode("Cortes por fecha asignacion ") . $proyecto)
        ->setSubject("")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("usuarios phpexcel")
        ->setCategory("Reportes Corted");

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

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:l1');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:l2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:l2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'CORTES POR FECHA DE ASIGNACION')
        ->setCellValue('A2', 'Inmueble')
        ->setCellValue('B2', 'Fecha Ejecucion')
        ->setCellValue('C2', 'Tipo corte')
        ->setCellValue('D2', 'Fecha Planificacion')
        ->setCellValue('E2', 'Login')
        ->setCellValue('F2', 'Medidor')
        ->setCellValue('G2', 'Sector')
        ->setCellValue('H2', 'Ruta')
        ->setCellValue('I2', 'Pago')
        ->setCellValue('J2', 'Fecha Pago')
        ->setCellValue('K2', 'Gestion')
        ->setCellValue('L2', 'Facturas Pendientes antes del pago');

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(24);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(7);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(13);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(9);


    $fila = 3;
    while (oci_fetch($datos)) {
        $inmueble                 = oci_result($datos, "ID_INMUEBLE");
        $fecha_eje              = oci_result($datos, "FECHA_EJE");
        $tipo_corte                   = oci_result($datos, "TIPO_CORTE");
        $fecha_pla            = oci_result($datos, "FECHA_PLANIFICACION");
        $login          = oci_result($datos, "LOGIN");
        $medidor            = oci_result($datos, "MEDIDOR");
        $sector     = oci_result($datos, "ID_SECTOR");
        $ruta         = oci_result($datos, "ID_RUTA");
        $importe = oci_result($datos, "IMPORTE");
        $fecha_pago     = oci_result($datos, "FECHA_PAGO");
        $gestion         = oci_result($datos, "GESTION");
        $fac_pend           = oci_result($datos, "FACT_PEND");


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $inmueble)
            ->setCellValue('B' . $fila, $fecha_eje)
            ->setCellValue('C' . $fila, $tipo_corte)
            ->setCellValue('D' . $fila, $fecha_pla)
            ->setCellValue('E' . $fila, $login)
            ->setCellValue('F' . $fila, $medidor)
            ->setCellValue('G' . $fila, $sector)
            ->setCellValue('H' . $fila, $ruta)
            ->setCellValue('I' . $fila, $importe)
            ->setCellValue('J' . $fila, $fecha_pago)
            ->setCellValue('K' . $fila, $gestion)
            ->setCellValue('L' . $fila, $fac_pend)
           ;
        $fila++;
    }
    oci_free_statement($datos);

    $objPHPExcel->getActiveSheet()->setTitle('Cortes X fecha asignacion');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch   = "../../temp/corXFechaAsig" . time() . ".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;


