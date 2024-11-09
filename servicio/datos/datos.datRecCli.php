<?php
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];

include_once '../clases/class.reportes_clientes.php';
require_once '../clases/PHPExcel.php';
session_start();
$cod = $_SESSION['codigo'];

if ($tipo == 'sess') {
    $segundos = 1800; //si pasa este tiempo se detecta la inactividad del usuario en el sitio
    if (($_SESSION['tiempo'] + $segundos) < time()) {
        session_destroy();
        echo "false";
    } else {
        $_SESSION['tiempo'] = time();
        echo "true";
    }
}

if ($tipo == 'selPro') {
    $l     = new ReportesGerencia();
    $datos = $l->seleccionaAcueducto();
    $i     = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}

if ($tipo == 'RecGraCli') {
    $fechaini = $_POST['fechaini'];
    $fechafin = $_POST['fechafin'];
    $proyecto = $_POST['proyecto'];
    $inmueble = $_POST['inmueble'];
    $cont     = 0;

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
        getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle("Recaudo Clientes")
        ->setSubject("")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("usuarios phpexcel")
        ->setCategory("Reportes Gerenciales");

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

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:J1');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:J2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:J2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'REPORTE RECAUDO CLIENTES ' . $proyecto . ' DEL ' . $fechaini . ' AL ' . $fechafin)
        ->setCellValue('A2', utf8_encode('Código'))
        ->setCellValue('B2', utf8_encode('Estado'))
        ->setCellValue('C2', utf8_encode('Proceso'))
        ->setCellValue('D2', utf8_encode('Catastro'))
        ->setCellValue('E2', utf8_encode('Cliente'))
        ->setCellValue('F2', utf8_encode('Dirección'))
        ->setCellValue('G2', utf8_encode('Urbanización'))
        ->setCellValue('H2', utf8_encode('Importe'))
        ->setCellValue('I2', utf8_encode('No Pago'))
        ->setCellValue('J2', utf8_encode('Fecha Pago'));
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(25);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(50);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(40);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(28);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(18);

    $fila      = 3;
    $c         = new ReportesGerencia();
    $registros = $c->RepRecaudoGrandesClientes($proyecto, $fechaini, $fechafin, $inmueble);
    while (oci_fetch($registros)) {
        $cod_inm = oci_result($registros, "CODIGO_INM");
        $est_inm = oci_result($registros, "ID_ESTADO");
        $pro_inm = oci_result($registros, "ID_PROCESO");
        $cat_inm = oci_result($registros, "CATASTRO");
        $cli_nom = oci_result($registros, "ALIAS");
        $dir_inm = oci_result($registros, "DIRECCION");
        $urb_inm = oci_result($registros, "DESC_URBANIZACION");
        $imp_inm = oci_result($registros, "IMPORTE");
        $pag_inm = oci_result($registros, "PAGO");
        $fec_pag = oci_result($registros, "FECPAGO");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $cod_inm)
            ->setCellValue('B' . $fila, $est_inm)
            ->setCellValue('C' . $fila, $pro_inm)
            ->setCellValue('D' . $fila, $cat_inm)
            ->setCellValue('E' . $fila, $cli_nom)
            ->setCellValue('F' . $fila, $dir_inm)
            ->setCellValue('G' . $fila, $urb_inm)
            ->setCellValue('H' . $fila, $imp_inm)
            ->setCellValue('I' . $fila, $pag_inm)
            ->setCellValue('J' . $fila, $fec_pag);
        //$totalmetros += $metros;
        //$totalfacturadoper += $facturadoper;
        //$totalfacturadodeuda += $facturadodeuda;
        //$totalrecaudado += $recaudado;
        $fila++;
    }
    oci_free_statement($registros);

    //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':B'.$fila);
    //$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":G".$fila)->getFont()->setBold(true);
    /*$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B'.$fila, 'Totales')
    ->setCellValue('C'.$fila, $totalmetros)
    ->setCellValue('D'.$fila, $totalfacturadoper)
    ->setCellValue('E'.$fila, $totalfacturadodeuda)
    ->setCellValue('F'.$fila, $totalfacturadoper + $totalfacturadodeuda)
    ->setCellValue('G'.$fila, $totalrecaudado);*/

    //mostrar la hoja q se abrira
    $objPHPExcel->setActiveSheetIndex(0);

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename=Reporte_Recaudo_Clientes_" . $proyecto . "_" . $fechaini . "_al_" . $fechafin . ".xls");
    header("Cache-Control: max-age=0");

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}
