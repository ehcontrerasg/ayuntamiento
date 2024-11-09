<?php
/*session_start();*/
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
include '../clases/classPqrs.php';
ini_set('memory_limit', '-1');

    require_once '../../recursos/PHPExcel.php';

    $proyecto = $_POST['proyecto'];
    $zonini = $_POST['zonini'];
    $zonfin = $_POST['zonfin'];
    $secini = $_POST['secini'];
    $secfin = $_POST['secfin'];
    $rutini = $_POST['rutini'];
    $rutfin = $_POST['rutfin'];
    $ofiini = $_POST['ofiini'];
    $ofifin = $_POST['ofifin'];
    $recini = $_POST['recini'];
    $recfin = $_POST['recfin'];
    $motivo = $_POST['motivo'];
    $fecinirad = $_POST['fecinirad'];
    $fecfinrad = $_POST['fecfinrad'];
    $fecinires = $_POST['fecinires'];
    $fecfinres = $_POST['fecfinres'];
    $tipo_resol = $_POST['tipo_resol'];
    //$tipo_estado = $_POST['radTipoResol'];
    $tipo_estado = $_POST['tipo_estado'];

   /* echo 'Fechini value:'.$fecinires;*/

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
    getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle("Resumen PQRs")
        ->setSubject("")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("usuarios phpexcel")
        ->setCategory("Reportes Servicio al Cliente");

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

    //HOJA RESUMEN PQRS

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:P1');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:P2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:P2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'REPORTE RESUMEN PQRs ')
        ->setCellValue('A2', 'No')
        ->setCellValue('B2', 'CODIGO PQR')
        ->setCellValue('C2', 'FECHA RADICACION')
        ->setCellValue('D2', 'INMUEBLE')
        ->setCellValue('E2', 'CLIENTE')
        ->setCellValue('F2', 'MED. RECEP.')
        ->setCellValue('G2', 'ZONA')
        ->setCellValue('H2', 'GERENCIA')
        ->setCellValue('I2', 'OFICINA')
        ->setCellValue('J2', 'DESCRIPCION')
        ->setCellValue('K2', 'FECHA DIAG.')
        ->setCellValue('L2', 'DIAGNOSTICO')
        ->setCellValue('M2', 'FECHA RESOL.')
        ->setCellValue('N2', 'TIPO')
        ->setCellValue('O2', 'RESOLUCION')
        ->setCellValue('P2', 'RESPUESTA');
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(5);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(13);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(18);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(22);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(80);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(18);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(40);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(18);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(30);



    $l=new PQRs();
    $registros=$l->datosResumenPqr($proyecto,$zonini,$zonfin,$secini,$secfin,$rutini,$rutfin,$tipo_resol,$tipo_estado,$ofiini,$ofifin,$motivo,$fecinirad,$fecfinrad,$fecinires,$fecfinres,$recini,$recfin,10000,0,$where);
    $fila = 3;
    while (oci_fetch($registros)) {
        $numero=oci_result($registros, 'RNUM');
        $codigo_pqr = oci_result($registros, 'CODIGO_PQR');
        $fecha_pqr = oci_result($registros, 'FECRAD');
        $cod_inm = oci_result($registros, 'COD_INMUEBLE');
        $nom_cliente = oci_result($registros, 'NOM_CLIENTE');
        $medio_rec = oci_result($registros, 'MEDIO_REC_PQR');
        $zona = oci_result($registros, 'ID_ZONA');
        $gerencia = oci_result($registros, 'GERENCIA');
        $oficina = oci_result($registros, 'COD_VIEJO');
        $descripcion = oci_result($registros, 'DESCRIPCION');
        $fecha_diag = oci_result($registros, 'FECDIAG');
        $diagnostico = oci_result($registros, 'DIAGNOSTICO');
        $fecha_resol = oci_result($registros, 'FECRESOL');
        $motivo = oci_result($registros, 'DESC_MOTIVO_REC');
        $resolucion = oci_result($registros, 'RESOLUCION');
        $respuesta = oci_result($registros, 'RESPUESTA');

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$fila, $numero)
            ->setCellValue('B'.$fila, $codigo_pqr)
            ->setCellValue('C'.$fila, $fecha_pqr)
            ->setCellValue('D'.$fila, $cod_inm)
            ->setCellValue('E'.$fila, $nom_cliente)
            ->setCellValue('F'.$fila, $medio_rec)
            ->setCellValue('G'.$fila, $zona)
            ->setCellValue('H'.$fila, $gerencia)
            ->setCellValue('I'.$fila, $oficina)
            ->setCellValue('J'.$fila, $descripcion)
            ->setCellValue('K'.$fila, $fecha_diag)
            ->setCellValue('L'.$fila, $diagnostico)
            ->setCellValue('M'.$fila, $fecha_resol)
            ->setCellValue('N'.$fila, $motivo)
            ->setCellValue('O'.$fila, $resolucion)
            ->setCellValue('P'.$fila, $respuesta);
        $fila++;
    }oci_free_statement($registros);

    //mostrafr la hoja q se abrira
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Reporte PQRs');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Reporte_PQRs_".$proyecto.".xls";
    $objWriter->save($nomarch);
    echo $nomarch;

   /* $objPHPExcel->setActiveSheetIndex(0);

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename='Reporte_PQRs_".$proyecto.".xls'");
    header("Cache-Control: max-age=0");
    ini_set('memory_limit','250M');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;*/
