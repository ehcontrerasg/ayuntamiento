<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */
setlocale(LC_MONETARY, 'es_DO');

$tipo    = $_POST["formato"];
$pro     = $_POST["proyecto"];
$procIni = $_POST["ProcesoIni"];
$procFin = $_POST["ProcesoFin"];
$contratista = $_POST["selCon"];

if ($tipo == 'pdf') {
    include "../../clases/class.pdfRep.php";
    include "../../clases/class.inmueble.php";
    $a     = new Inmueble();
    $datos = $a->getInmCorConLecByProcProy($pro, $procIni, $procFin,$contratista);
    $pdf   = new PdfInmCortConLec();
    $pdf->setProyecto($pro);
    $pdf->setProIni($procIni);
    $pdf->setProFin($procFin);
    $pdf->AddPage('L');

    $pdf->SetTextColor(0, 0, 0);
    $pdf->AliasNbPages();

    $y      = 45;
    $secrut = '0';
    while (oci_fetch($datos)) {

        $inmueble                 = oci_result($datos, "CODIGO_INM");
        $id_proceso               = oci_result($datos, "ID_PROCESO");
        $lectura_corte            = oci_result($datos, "LECTURA");
        $fecha_eje_corte          = oci_result($datos, "FECHA_EJE");
        $usuario_corte            = oci_result($datos, "LOGIN");
        $usuario_inspeccion       = oci_result($datos, "LOGININSPECCION");
        $fecha_inspeccion         = oci_result($datos, "FECHA_INS");
        $periodo_anterior_lectura = oci_result($datos, "PERIODO_ANTERIOR");
        $observacion_anterior     = oci_result($datos, "OBS_PERIODO_ANTERIOR");
        $lectura_anterior         = oci_result($datos, "LECTURA_PERIODO_ANTERIOR");
        $periodo_actual           = oci_result($datos, "PERIODO_ACTUAL");
        $observacion_actual       = oci_result($datos, "OBS_PERIODO_ACTUAL");
        $lectura_actual           = oci_result($datos, "LECTURA_PERIODO_ACTUAL");
        $diferencia_lectura       = oci_result($datos, "DIFERENCIA");
        $serial                   = oci_result($datos, "SERIAL");
        $catastro                 = oci_result($datos, "CATASTRO");

//    if($secrut==0){
        //
        //        $pdf->setInspector($nombreUsuario);
        //        $pdf->setRuta(substr($id_proceso, 2, 2));
        //        $pdf->setSector(substr($id_proceso, 0, 2));
        //        $pdf->AddPage('L');
        //    }
        if ($y >= 200) {
//        $pdf->setInspector($nombreUsuario);
            //        $pdf->setRuta(substr($id_proceso, 2, 2));
            //        $pdf->setSector(substr($id_proceso, 0, 2));
            $pdf->setProyecto($pro);
            $pdf->setProIni($procIni);
            $pdf->setProFin($procFin);
            $pdf->AddPage('L');
            $y = 45;
        }
//else if($secrut<>'0' and $secrut<>substr($id_proceso, 0, 4)){
        //        $pdf->setInspector($nombreUsuario);
        //        $pdf->setRuta(substr($id_proceso, 2, 2));
        //        $pdf->setSector(substr($id_proceso, 0, 2));
        //        $pdf->AddPage('L');
        //        $y=45;
        //    }
        //
        //    $secrut=substr($id_proceso, 0, 4);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('times', "", 8);
        $pdf->Text(2, $y, utf8_decode("$inmueble"));
        $pdf->Text(15, $y, utf8_decode("$id_proceso"));
        $pdf->Text(33, $y, utf8_decode("$catastro"));
        $pdf->Text(60, $y, utf8_decode("$serial"));
        $pdf->Text(80, $y, utf8_decode("$lectura_corte"));
        $pdf->Text(93, $y, utf8_decode("$fecha_eje_corte"));
        $pdf->Text(117, $y, utf8_decode("$usuario_corte"));
        $pdf->Text(141, $y, utf8_decode("$fecha_inspeccion"));
        $pdf->Text(166, $y, utf8_decode("$usuario_inspeccion"));
        $pdf->Text(191, $y, utf8_decode("$periodo_anterior_lectura"));
        $pdf->Text(206, $y, utf8_decode("$observacion_anterior"));
        $pdf->Text(221, $y, utf8_decode("$lectura_anterior"));
        $pdf->Text(236, $y, utf8_decode("$periodo_actual"));
        $pdf->Text(251, $y, utf8_decode("$observacion_actual"));
        $pdf->Text(266, $y, utf8_decode("$lectura_actual"));
        $pdf->Text(281, $y, utf8_decode("$diferencia_lectura"));
//    $pdf->Text(241,$y,utf8_decode("RD".money_format('%.2n',$deuda)));
        //    $pdf->Text(260,$y,utf8_decode("$tipoCort"));
        //    $pdf->Text(267,$y,utf8_decode("$lectura"));
        //    $pdf->Text(272,$y,utf8_decode("$obsCorte"));

        $y += 5;

    }

    $nomarch = "../../temp/rcortConLec" . time() . ".pdf";
    $pdf->Output($nomarch, 'F');
    echo $nomarch;

}

if ($tipo == 'xls') {
    include '../../recursos/PHPExcel.php';
    include '../../recursos/PHPExcel/Writer/Excel2007.php';
    include "../../clases/class.pdfRep.php";
    include "../../clases/class.inmueble.php";
    $a     = new Inmueble();
    $datos = $a->getInmCorConLecByProcProy($pro, $procIni, $procFin,$contratista);

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
        getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle(utf8_encode("Medidores Cortados con lectura ") . $proyecto)
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

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:S1');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:S2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:S2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'MEDIDORES CORTADOS CON LECTURA')
        ->setCellValue('A2', 'Código')
        ->setCellValue('B2', 'Proceso')
        ->setCellValue('C2', 'Catastro')
        ->setCellValue('D2', 'Ruta')
        ->setCellValue('E2', 'Serial')
        ->setCellValue('F2', 'LC')
        ->setCellValue('G2', 'Fec. Corte')
        ->setCellValue('H2', 'US Corte')
        ->setCellValue('I2', 'Fecha Inst.')
        ->setCellValue('J2', 'Usuario Insp.')
        ->setCellValue('K2', 'Fecha Lect. ant.')
        ->setCellValue('L2', 'Obs Lect. Ant.')
        ->setCellValue('M2', 'Lec. Ant.')
        ->setCellValue('N2', 'Fech Lect. Act')
        ->setCellValue('O2', 'Obs Lect. Ant.')
        ->setCellValue('P2', 'Lect. Ant.')
        ->setCellValue('Q2', 'Diferencia')
        ->setCellValue('R2', 'Observacion')
        ->setCellValue('S2', 'Fecha Inspe.');

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
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Q")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("R")->setWidth(13);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("S")->setWidth(13);

    $fila = 3;
    while (oci_fetch($datos)) {
        $inmueble                 = oci_result($datos, "CODIGO_INM");
        $id_proceso               = oci_result($datos, "ID_PROCESO");
        $ruta                     = oci_result($datos, "RUTA");
        $lectura_corte            = oci_result($datos, "LECTURA");
        $fecha_eje_corte          = oci_result($datos, "FECHA_EJE");
        $usuario_corte            = oci_result($datos, "LOGIN");
        $usuario_inspeccion       = oci_result($datos, "LOGININSPECCION");
        $fecha_inspeccion         = oci_result($datos, "FECHA_INS");
        $periodo_anterior_lectura = oci_result($datos, "PERIODO_ANTERIOR");
        $observacion_anterior     = oci_result($datos, "OBS_PERIODO_ANTERIOR");
        $lectura_anterior         = oci_result($datos, "LECTURA_PERIODO_ANTERIOR");
        $periodo_actual           = oci_result($datos, "PERIODO_ACTUAL");
        $observacion_actual       = oci_result($datos, "OBS_PERIODO_ACTUAL");
        $lectura_actual           = oci_result($datos, "LECTURA_PERIODO_ACTUAL");
        $diferencia_lectura       = oci_result($datos, "DIFERENCIA");
        $serial                   = oci_result($datos, "SERIAL");
        $catastro                 = oci_result($datos, "CATASTRO");
        $imp_lect                 = oci_result($datos, "IMP_LECT");
        $fecha_inspe              = oci_result($datos, "FECHA_INSP");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $inmueble)
            ->setCellValue('B' . $fila, $id_proceso)
            ->setCellValue('C' . $fila, $catastro)
            ->setCellValue('D' . $fila, $ruta)
            ->setCellValue('E' . $fila, $serial)
            ->setCellValue('F' . $fila, $lectura_corte)
            ->setCellValue('G' . $fila, $fecha_eje_corte)
            ->setCellValue('H' . $fila, $usuario_corte)
            ->setCellValue('I' . $fila, $fecha_inspeccion)
            ->setCellValue('J' . $fila, $usuario_inspeccion)
            ->setCellValue('K' . $fila, $periodo_anterior_lectura)
            ->setCellValue('L' . $fila, $observacion_anterior)
            ->setCellValue('M' . $fila, $lectura_anterior)
            ->setCellValue('N' . $fila, $periodo_actual)
            ->setCellValue('O' . $fila, $observacion_actual)
            ->setCellValue('P' . $fila, $lectura_actual)
            ->setCellValue('Q' . $fila, $diferencia_lectura)
            ->setCellValue('R' . $fila, $imp_lect)
            ->setCellValue('S' . $fila, $fecha_inspe);
        $fila++;
    }
    oci_free_statement($datos);

    $objPHPExcel->getActiveSheet()->setTitle('Medidoress Cortados Con lectura');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch   = "../../temp/Medidores_cort_lectura" . time() . ".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;

}
