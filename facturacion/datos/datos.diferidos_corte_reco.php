<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$tip=$_POST['tip'];

if ($tip=='genReporte') {
    include_once '../clases/class.reportes_difcortes.php';


    $requestData = $_REQUEST;

    $proyecto = $_POST['proyecto'];
    $fecini = $_POST['fecini'];
    $fecfin = $_POST['fecfin'];
    $datos = array();
    $requestDataStart = $requestData["start"];
    $requestDataLenght = $requestData["length"];

    $page = $requestDataStart + $requestDataLenght;
    $page = $page / $requestDataLenght;

    $rp = $requestDataLenght;

    $end = ($page - 1) * $rp;  // MAX_ROW_TO_FETCH
    $start = ($page) * $rp;  // MIN_ROW_TO_FETCH

    if ($requestDataLenght == -1) {
        $end = '';
        $start = '';
    }

    $c = new ReportesDifCortes();
    $registros = $c->DatosDiferidosCorte($proyecto, $fecini, $fecfin, $start, $end);
    $cantidad = $c->cantidadDiferidosCorte($proyecto, $fecini, $fecfin);
    while (oci_fetch($registros)) {
        $codigodif = oci_result($registros, 'CODIGO');
        $fechadif = oci_result($registros, 'FECPAGO');
        $concepdif = oci_result($registros, 'CONCEPTO');
        $descdif = oci_result($registros, 'DESC_SERVICIO');
        $formdif = oci_result($registros, 'ID_FORM_PAGO');
        $descformdif = oci_result($registros, 'DESCRIPCION');
        $usuariodif = oci_result($registros, 'LOGIN');
        $importedif = oci_result($registros, 'VALOR_DIFERIDO');
        $inmuebledif = oci_result($registros, 'INMUEBLE');


        $arr = array($codigodif, $fechadif, $descdif, $descformdif, $usuariodif, $importedif, $inmuebledif);
        array_push($datos, $arr);

    }
    oci_free_statement($registros);


    $cantidad = oci_fetch_array($cantidad)[0];

    $json_data = array(
        "recordsTotal" => intval($cantidad),  // total number of records
        "recordsFiltered" => intval($cantidad), // total number of records after searching, if there is no searching then totalFiltered = totalData
        "data" => $datos   // total data array
    );

    echo json_encode($json_data);
}


if ($tip=='exportarTabla2'){



}

if ($tip=='exportarTabla') {
    include_once '../clases/class.reportes_difcortes.php';
    include_once '../../recursos/PHPExcel.php';
    include_once '../../recursos/PHPExcel/Writer/Excel2007.php';


    $proyecto = $_POST['proyecto'];
    $fecini = $_POST['fecini'];
    $fecfin = $_POST['fecfin'];
    ini_set('memory_limit','16096M');
/*    $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
    $cacheSettings = array( ' memoryCacheSize ' => '8MB');
    PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);*/
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
    getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle("Diferidos corte y reconexion")
        ->setSubject("")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("usuarios phpexcel")
        ->setCategory("Reportes Servicio al Cliente");

    $estiloTitulos = array(
        'borders'   => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                // 'color' => array('rgb' => '000000'),
            ),
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        ),
    );

    $borderArray1 = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
            ),
        ),
    );

    // Titulos de la hoja
    $objPHPExcel->setActiveSheetIndex(0)
        ->SetCellValue('A1', 'CODIGO')
        ->SetCellValue('B1', 'FECHA DE PAGO')
        ->SetCellValue('C1', 'CONCEPTO')
        ->SetCellValue('D1', 'FORMA DE PAGO')
        ->SetCellValue('E1', 'USUARIO')
        ->SetCellValue('F1', 'IMPORTE')
        ->SetCellValue('G1', 'INMUEBLE');


    //Insertamos los datos
    $r = new ReportesDifCortes();
    $fila      = 2;
    $registros = $r->DatosDiferidosCorte($proyecto, $fecini, $fecfin);

    while ($row = oci_fetch_array($registros, OCI_ASSOC)) {
        // $nestedData=array();
        $codigodif = oci_result($registros, 'CODIGO');
        $fechadif = oci_result($registros, 'FECPAGO');
        $concepdif = oci_result($registros, 'CONCEPTO');
        $descdif = oci_result($registros, 'DESC_SERVICIO');
        $formdif = oci_result($registros, 'ID_FORM_PAGO');
        $descformdif = oci_result($registros, 'DESCRIPCION');
        $usuariodif = oci_result($registros, 'LOGIN');
        $importedif = oci_result($registros, 'VALOR_DIFERIDO');
        $inmuebledif = oci_result($registros, 'INMUEBLE');



        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $codigodif)
            ->setCellValue('B' . $fila, $fechadif)
            ->setCellValue('C' . $fila, $concepdif)
            ->setCellValue('D' . $fila, $descformdif)
            ->setCellValue('E' . $fila, $usuariodif)
            ->setCellValue('F' . $fila, $importedif)
            ->setCellValue('G' . $fila, $inmuebledif);


        $objPHPExcel->getActiveSheet()->getRowDimension($fila)->setRowHeight(25);

        $fila++;
    }
    oci_free_statement($registros);


    // Width de las celdas
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(13.22);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(25.72);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(14.15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(20.58);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(34.86);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(25.86);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(20.86);



    // Fuentes en Bold
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:G1')->getFont()->setBold(true);


    //Estilos para las celdas
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:G1' . $fila)->applyFromArray(
        array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        )
    );
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:G1')->applyFromArray($estiloTitulos);

    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Diferidos corte reco');
    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Reporte_diferidos_corte_reco.xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;
}
?>
