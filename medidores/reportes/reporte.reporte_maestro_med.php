<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */
setlocale(LC_MONETARY, 'es_DO');
$proyecto = $_POST['proyecto'];
$tipoM    = $_POST['tipoM'];
$descarga = $_POST['descarga'];
$fecini = $_POST['fecini'];
$fecfin = $_POST['fecfin'];

ini_set('memory_limit', '-1');

include "../../clases/class.pdfRep.php";
include "../../clases/class.inmueble.php";
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';

$a = new Inmueble();
if ($tipoM == 'SM') {
    $datos = $a->getMaestroInmMedByProy($proyecto,$fecini,$fecfin);
} elseif ($tipoM == 'NM') {
    $datos = $a->getMaestroInmByProy($proyecto);
}
if ($descarga == 'pdf') {
    $pdf = new PdfMaestromed();
    $pdf->setProyecto($proyecto);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->AliasNbPages();
    $pdf->AddPage('L');

    $y       = 45;
    $secrut  = '0';
    $tot_inm = 0;

    while (oci_fetch($datos)) {
        $inmueble      = oci_result($datos, "CODIGO");
        $urbanizacion  = oci_result($datos, "URBANIZACION");
        $direccion     = oci_result($datos, "DIRECCION");
        $cliente       = oci_result($datos, "NOMBRE_CLIENTE");
        $proceso       = oci_result($datos, "ID_PROCESO");
        $catastro      = oci_result($datos, "ID_INMUEBLE");
        $medidor       = oci_result($datos, "COD_MEDIDOR");
        $serial        = oci_result($datos, "SERIAL");
        $emplazamiento = oci_result($datos, "DESC_EMPLAZAMIENTO");
        $calibre       = oci_result($datos, "CALIBRE");
        $uso           = oci_result($datos, "USO");
        $estado        = oci_result($datos, "ESTADO");
        $lectura       = oci_result($datos, "LECTURA");
        $obslectura    = oci_result($datos, "OBSLECTURA");
        $sector        = oci_result($datos, "SECTOR");
        $ruta          = oci_result($datos, "RUTA");


        if ($y >= 200) {
            $pdf->AddPage('L');
            $y = 45;
        }

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('times', "", 8);
        $pdf->Text(3, $y, utf8_decode("$inmueble"));
        $pdf->Text(15, $y, ucwords(strtolower(utf8_decode("$urbanizacion"))));
        $pdf->Text(37, $y, ucwords(strtolower(utf8_decode("$direccion"))));
        $pdf->Text(90, $y, substr(ucwords(strtolower(utf8_decode("$cliente"))), 0, 30));
        $pdf->Text(134, $y, utf8_decode("$proceso"));
        $pdf->Text(153, $y, utf8_decode("$catastro"));
        $pdf->Text(183, $y, utf8_decode("$medidor"));
        $pdf->Text(193, $y, utf8_decode("$serial"));
        $pdf->Text(212, $y, utf8_decode("$emplazamiento"));
        $pdf->Text(235, $y, utf8_decode("$calibre"));
        $pdf->Text(250, $y, utf8_decode("$uso"));
        $pdf->Text(260, $y, utf8_decode("$estado"));
        $pdf->Text(270, $y, utf8_decode("$lectura"));
        $pdf->Text(280, $y, utf8_decode("$obslectura"));

        $y += 5;
        ++$tot_inm;
    }

    $pdf->SetFont('times', "B", 13);
    $pdf->Text(240, $y, utf8_decode("Total Inmuebles: "));
    $pdf->Text(280, $y, utf8_decode($tot_inm));

    $nomarch = "../../temp/repMaeMed" . time() . ".pdf";
    $pdf->Output($nomarch, 'F');
    echo $nomarch;
}
if ($descarga == 'xls') {
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
    getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle(utf8_encode("Reporte Maestro Medidores ") . $proyecto)
        ->setSubject("")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("usuarios phpexcel")
        ->setCategory("Reportes Medidores");

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

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:R1');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:R2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:R2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'MAESTRO DE MEDIDORES')
        ->setCellValue('A2', 'Código')
        ->setCellValue('B2', 'Urbanización')
        ->setCellValue('C2', 'Dirección')
        ->setCellValue('D2', 'Cliente')
        ->setCellValue('E2', 'Proceso')
        ->setCellValue('F2', 'Catastro')
        ->setCellValue('G2', 'Medidor')
        ->setCellValue('H2', 'Serial')
        ->setCellValue('I2', 'Emplazamiento')
        ->setCellValue('J2', 'Calibre')
        ->setCellValue('K2', 'Uso')
        ->setCellValue('L2', 'Estado')
        ->setCellValue('M2', 'Lectura')
        ->setCellValue('N2', 'Obs Lectura')
        ->setCellValue('O2', 'Sector')
        ->setCellValue('P2', 'Ruta')
        ->setCellValue('Q2', 'Fecha Instalación')
        ->setCellValue('R2', 'Zona');

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(24);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(34);
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
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Q")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("R")->setWidth(9);

    $fila = 3;
    while (oci_fetch($datos)) {
        $inmueble      = oci_result($datos, "CODIGO");
        $urbanizacion  = oci_result($datos, "URBANIZACION");
        $direccion     = oci_result($datos, "DIRECCION");
        $cliente       = oci_result($datos, "NOMBRE_CLIENTE");
        $proceso       = oci_result($datos, "ID_PROCESO");
        $catastro      = oci_result($datos, "ID_INMUEBLE");
        $medidor       = oci_result($datos, "COD_MEDIDOR");
        $serial        = oci_result($datos, "SERIAL");
        $emplazamiento = oci_result($datos, "DESC_EMPLAZAMIENTO");
        $calibre       = oci_result($datos, "CALIBRE");
        $uso           = oci_result($datos, "USO");
        $estado        = oci_result($datos, "ESTADO");
        $lectura       = oci_result($datos, "LECTURA");
        $obslectura    = oci_result($datos, "OBSLECTURA");
        $sector        = oci_result($datos, "SECTOR");
        $ruta          = oci_result($datos, "RUTA");
        $fechaIns      = oci_result($datos, "FECHA_INSTALACION");
        $zona          = oci_result($datos, "ID_ZONA");

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $inmueble)
            ->setCellValue('B' . $fila, $urbanizacion)
            ->setCellValue('C' . $fila, $direccion)
            ->setCellValue('D' . $fila, $cliente)
            ->setCellValue('E' . $fila, $proceso)
            ->setCellValue('F' . $fila, $catastro)
            ->setCellValue('G' . $fila, $medidor)
            ->setCellValue('H' . $fila, $serial)
            ->setCellValue('I' . $fila, $emplazamiento)
            ->setCellValue('J' . $fila, $calibre)
            ->setCellValue('K' . $fila, $uso)
            ->setCellValue('L' . $fila, $estado)
            ->setCellValue('M' . $fila, $lectura)
            ->setCellValue('N' . $fila, $obslectura)
            ->setCellValue('O' . $fila, $sector)
            ->setCellValue('P' . $fila, $ruta)
            ->setCellValue('Q' . $fila, $fechaIns)
            ->setCellValue('R' . $fila, $zona);
        $fila++;
    }
    oci_free_statement($datos);

    $objPHPExcel->getActiveSheet()->setTitle('Maestro');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch   = "../../temp/Maestro_Medidores" . time() . ".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;
}


if ($descarga == 'txt') {
    $archivo= "../../temp/Maestro_Medidores" . time() . ".txt";
    //$archivo= "Maestro_Medidores" . time() . ".txt";
    $fp= fopen($archivo, "a");
    $fila[0]=utf8_decode("Código");
    $fila[1]=utf8_decode("Urbanización");
    $fila[2]=utf8_decode("Dirección");
    $fila[3]=utf8_decode("Cliente");
    $fila[4]=utf8_decode("Proceso");
    $fila[5]=utf8_decode("Catastro");
    $fila[6]=utf8_decode("Medidor");
    $fila[7]=utf8_decode("Serial");
    $fila[8]=utf8_decode("Emplazamiento");
    $fila[9]=utf8_decode("Calibre");
    $fila[10]=utf8_decode("Uso");
    $fila[11]=utf8_decode("Estado");
    $fila[12]=utf8_decode("Lectura");
    $fila[13]=utf8_decode("Obs Lectura");
    $fila[14]=utf8_decode("Sector");
    $fila[15]=utf8_decode("Ruta");
    $fila[16]=utf8_decode("Fecha Instalación");
    $fila[17]=utf8_decode("Zona");
    fputcsv( $fp, $fila );

    while (oci_fetch($datos)) {
        $inmueble      = oci_result($datos, "CODIGO");
        $urbanizacion  = oci_result($datos, "URBANIZACION");
        $direccion     = oci_result($datos, "DIRECCION");
        $cliente       = oci_result($datos, "NOMBRE_CLIENTE");
        $proceso       = oci_result($datos, "ID_PROCESO");
        $catastro      = oci_result($datos, "ID_INMUEBLE");
        $medidor       = oci_result($datos, "COD_MEDIDOR");
        $serial        = oci_result($datos, "SERIAL");
        $emplazamiento = oci_result($datos, "DESC_EMPLAZAMIENTO");
        $calibre       = oci_result($datos, "CALIBRE");
        $uso           = oci_result($datos, "USO");
        $estado        = oci_result($datos, "ESTADO");
        $lectura       = oci_result($datos, "LECTURA");
        $obslectura    = oci_result($datos, "OBSLECTURA");
        $sector        = oci_result($datos, "SECTOR");
        $ruta          = oci_result($datos, "RUTA");
        $fechaIns      = oci_result($datos, "FECHA_INSTALACION");
        $zona          = oci_result($datos, "ID_ZONA");

        $fila[0]=utf8_decode($inmueble);
        $fila[1]=utf8_decode($urbanizacion);
        $fila[2]=utf8_decode($direccion);
        $fila[3]=utf8_decode($cliente);
        $fila[4]=utf8_decode($proceso);
        $fila[5]=utf8_decode($catastro);
        $fila[6]=utf8_decode($medidor);
        $fila[7]=utf8_decode($serial);
        $fila[8]=utf8_decode($emplazamiento);
        $fila[9]=utf8_decode($calibre);
        $fila[10]=utf8_decode($uso);
        $fila[11]=utf8_decode($estado);
        $fila[12]=utf8_decode($lectura);
        $fila[13]=utf8_decode($obslectura);
        $fila[14]=utf8_decode($sector);
        $fila[15]=utf8_decode($ruta);
        $fila[16]=utf8_decode($fechaIns);
        $fila[17]=utf8_decode($zona);
        fputcsv( $fp, $fila );
    }
    oci_free_statement($datos);

    rewind( $fp );
    $output = stream_get_contents( $fp );
    // cerrar archivo
    fclose( $fp );

    echo $archivo;
}