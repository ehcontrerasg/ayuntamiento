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
include_once '../../clases/class.inspeccionCorte.php';

$a     = new InspeccionCorte();
$datos = $a->getInsXFecEjec($procIni, $procFin,$pro,$contratista);
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
        getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle(utf8_encode("Inpecciones por fecha ejecucion ") . $proyecto)
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

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:N1');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:N2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:N2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'INSPECCIONES POR FECHA DE EJECUCION')
        ->setCellValue('A2', 'Inmueble')
        ->setCellValue('B2', 'Fecha Ejecucion')
        ->setCellValue('C2', 'Tipo corte')
        ->setCellValue('D2', 'Tipo corte ejecutado')
        ->setCellValue('E2', 'Reconectado')
        ->setCellValue('F2', 'Fecha Planificacion')
        ->setCellValue('G2', 'Login')
        ->setCellValue('H2', 'Medidor')
        ->setCellValue('I2', 'Sector')
        ->setCellValue('J2', 'Ruta')
        ->setCellValue('K2', 'Pago')
        ->setCellValue('L2', 'Fecha Pago')
        ->setCellValue('M2', 'Gestion')
        ->setCellValue('N2', 'Facturas Pendientes antes del pago');

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
        $inmueble                 = oci_result($datos, "CODIGO_INM");
        $fecha_eje              = oci_result($datos, "FECHA_EJE");
        $tipo_corte                   = oci_result($datos, "TIPO_CORTE");
        $tipo_corte_eje            = oci_result($datos, "TIPO_CORTE_EJE");
        $reconectado          = oci_result($datos, "RECONECTADO");
        $fecha_pla            = oci_result($datos, "FECHA_PLANIFICACION");
        $login       = oci_result($datos, "LOGIN");
        $medidor         = oci_result($datos, "MEDIDOR");
        $sector = oci_result($datos, "ID_SECTOR");
        $ruta   = oci_result($datos, "ID_RUTA");
        $importe         = oci_result($datos, "IMPORTE");
        $fecha_paao        = oci_result($datos, "FECHA_PAGO");
        $gestion       = oci_result($datos, "GESTION");
        $fac_pend         = oci_result($datos, "FACT_PEND");


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $inmueble)
            ->setCellValue('B' . $fila, $fecha_eje)
            ->setCellValue('C' . $fila, $tipo_corte)
            ->setCellValue('D' . $fila, $tipo_corte_eje)
            ->setCellValue('E' . $fila, $reconectado)
            ->setCellValue('F' . $fila, $fecha_pla)
            ->setCellValue('G' . $fila, $login)
            ->setCellValue('H' . $fila, $medidor)
            ->setCellValue('I' . $fila, $sector)
            ->setCellValue('J' . $fila, $ruta)
            ->setCellValue('K' . $fila, $importe)
            ->setCellValue('L' . $fila, $fecha_paao)
            ->setCellValue('M' . $fila, $gestion)
            ->setCellValue('N' . $fila, $fac_pend);
        $fila++;
    }
    oci_free_statement($datos);

    $objPHPExcel->getActiveSheet()->setTitle('Inspecciones X fecha ejecucion');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch   = "../../temp/insXFechaEjec" . time() . ".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;


