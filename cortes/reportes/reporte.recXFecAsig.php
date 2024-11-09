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
    include_once '../../clases/class.reconexion.php';

    $a     = new Reconexion();
    $datos = $a->getRecXFecAsig($procIni, $procFin,$pro,$contratista);

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
        getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle(utf8_encode("Reconexiones por fecha asignacion ") . $pro)
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

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E1');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:E2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:E2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'RECONEXIONES POR FECHA DE ASIGNACION')
        ->setCellValue('A2', 'Inmueble')
        ->setCellValue('B2', 'Fecha Ejecucion')
        ->setCellValue('C2', 'Tipo reconexion')
        ->setCellValue('D2', 'Fecha Planificacion')
        ->setCellValue('E2', 'Login');

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(24);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(7);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(20);


    $fila = 3;
    while (oci_fetch($datos)) {
        $inmueble                 = oci_result($datos, "ID_INMUEBLE");
        $fecha_eje               = oci_result($datos, "FECHA_EJE");
        $tipo_rec                    = oci_result($datos, "TIPO_RECONEXION");
        $fecha_pla          = oci_result($datos, "FECHA_PLANIFICACION");
        $login         = oci_result($datos, "LOGIN");


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $inmueble)
            ->setCellValue('B' . $fila, $fecha_eje)
            ->setCellValue('C' . $fila, $tipo_rec)
            ->setCellValue('D' . $fila, $fecha_pla)
            ->setCellValue('E' . $fila, $login)
            ;
        $fila++;
    }
    oci_free_statement($datos);

    $objPHPExcel->getActiveSheet()->setTitle('Reconexiones X fecha asignacion');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch   = "../../temp/recXFechaAsig" . time() . ".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;


