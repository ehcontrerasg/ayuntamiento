<?php
/**
 * Created by PhpStorm.
 * User: AMOSQUEA
 * Date: 21/11/2017
 * Time: 3:21 PM
 */

session_start();
setlocale(LC_MONETARY, 'es_DO');
$usr  = $_SESSION["codigo"];
$tip  = $_REQUEST["tip"];
$tipo = $_GET['tipo'];
//$tip="M";
$proy     = $_REQUEST["proy"];
$fechaIni = $_GET['fechaIni'];
$fechaFin = $_GET['fechaFin'];

if ($tip == "proy") {
    include_once "../../clases/class.proyecto.php";
    $q     = new Proyecto();
    $datos = $q->obtenerProyecto($usr);
    $i     = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $proyectos[$i] = $row;
        $i++;
    }
    echo json_encode($proyectos);
}

if ($tip == 'rep') {
    include_once "../../clases/class.pdfRep.php";
    include_once "../../clases/class.corte.php";

    $pdf = new pdfInsMan();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $posX = 0;
    $poxY = 0;

    if ($proy == 'SD') {
        $pdf->Image("../../images/LogoCaasd.jpg", 5 + $posX, 5 + $posY, 12, 16);
    }
    if ($proy == 'BC') {
        $pdf->Image("../../images/coraabo.jpg", 5 + $posX, 5 + $posY, 12, 16);
    }

    if ($proy == 'SD') {
        $pdf->SetFont('times', '', 22);
        $pdf->Text(33 + $posX, 12 + $posY, utf8_decode("CA"));
        $pdf->Text(50.5 + $posX, 12 + $posY, utf8_decode("SD"));
        $pdf->SetFont('times', '', 29);
        $pdf->Text(43 + $posX, 12 + $posY, utf8_decode("A"));

        $pdf->SetFont('times', '', 9);
        $pdf->Text(29 + $posX, 16 + $posY, utf8_decode("Corporación del Acueducto"));
        $pdf->Text(24 + $posX, 19.5 + $posY, utf8_decode("y Alcantarillado de Santo Domingo"));

    }

    if ($proy == 'BC') {
        $pdf->SetFont('times', '', 22);
        $pdf->Text(23 + $posX, 13 + $posY, utf8_decode("CORAABO"));

        $pdf->SetFont('times', '', 9);
        $pdf->Text(25 + $posX, 17 + $posY, utf8_decode("Corporación del Acueducto"));
        $pdf->Text(22 + $posX, 19.5 + $posY, utf8_decode("y Alcantarillado de Boca Chica "));

    }

    $pdf->SetFont('times', '', 12);
    $pdf->Text(180, 10, date('d/m/Y'));

    $pdf->SetFont('arial', 'B', 22);
    $pdf->SetTextColor(1, 149, 221);
    $pdf->Text(55, 35, utf8_decode("Reporte Cortes Eliminados"));

    $posY = 45;
    $posX = 6;

    //$pdf->SetDrawColor(0,0,0);
    $pdf->SetFillColor(1, 149, 221);
    $pdf->SetFont('arial', '', 9);
    $pdf->SetTextColor(255, 255, 255);

    $pdf->SetY($posY);
    $pdf->SetX($posX);
    $pdf->Cell(13, 6, 'Inmueble', 0, 3, 'C', true);

    $pdf->SetY($posY);
    $pdf->SetX(13 + $posX);
    $pdf->Cell(9, 6, 'Ruta', 0, 3, 'C', true);

    $pdf->SetY($posY);
    $pdf->SetX(21 + $posX);
    $pdf->Cell(8, 6, 'Zona', 0, 3, 'C', true);

    $pdf->SetY($posY);
    $pdf->SetX(29 + $posX);
    $pdf->Cell(9, 6, 'Uso', 0, 3, 'C', true);

    $pdf->SetY($posY);
    $pdf->SetX(38 + $posX);
    $pdf->Cell(16, 6, 'Suministro', 0, 3, 'C', true);

    $pdf->SetY($posY);
    $pdf->SetX(54 + $posX);
    $pdf->Cell(38, 6, 'Cliente', 0, 3, 'C', true);

    $pdf->SetY($posY);
    $pdf->SetX(92 + $posX);
    $pdf->Cell(15, 6, 'Fecha', 0, 3, 'C', true);

    $pdf->SetY($posY);
    $pdf->SetX(107 + $posX);
    $pdf->Cell(17, 6, 'Importe', 0, 3, 'C', true);

    $pdf->SetY($posY);
    $pdf->SetX(124 + $posX);
    $pdf->Cell(26, 6, 'Autorizador', 0, 3, 'C', true);

    $pdf->SetY($posY);
    $pdf->SetX(150 + $posX);
    $pdf->Cell(46.5, 6, utf8_decode('Observación'), 0, 3, 'C', true);

    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetTextColor(40, 40, 40);
    $pdf->SetFont('Arial', '', 8);

    $posY = 51;

    $c     = new Corte();
    $data  = $c->getCorElimPorFec($proy, $fechaIni, $fechaFin, $tipo);
    $total = 0;
    while (oci_fetch($data)) {
        $inmueble    = oci_result($data, 'INMUEBLE');
        $ruta        = oci_result($data, 'RUTA');
        $zona        = oci_result($data, 'ZONA');
        $uso         = oci_result($data, 'USO');
        $suministro  = oci_result($data, 'SUMINISTRO') == 1 ? 'Agua' : 'Pozo';
        $nombre      = oci_result($data, 'NOMBRE');
        $telefono    = oci_result($data, 'TELEFONO');
        $fecha       = oci_result($data, 'FECHA');
        $importe     = oci_result($data, 'IMPORTE');
        $usuario     = oci_result($data, 'USUARIO');
        $observacion = oci_result($data, 'OBSERVACION');

        if ($posY >= 285) {
            $posY = 0;
            $pdf->AddPage();

            if ($proy == 'SD') {
                $pdf->Image("../../images/LogoCaasd.jpg", 5 + $posX, 5 + $posY, 12, 16);
            }
            if ($proy == 'BC') {
                $pdf->Image("../../images/coraabo.jpg", 5 + $posX, 5 + $posY, 12, 16);
            }

            if ($proy == 'SD') {
                $pdf->SetFont('times', '', 22);
                $pdf->Text(33 + $posX, 12 + $posY, utf8_decode("CA"));
                $pdf->Text(50.5 + $posX, 12 + $posY, utf8_decode("SD"));
                $pdf->SetFont('times', '', 29);
                $pdf->Text(43 + $posX, 12 + $posY, utf8_decode("A"));

                $pdf->SetFont('times', '', 9);
                $pdf->Text(29 + $posX, 16 + $posY, utf8_decode("Corporación del Acueducto"));
                $pdf->Text(24 + $posX, 19.5 + $posY, utf8_decode("y Alcantarillado de Santo Domingo"));

            }

            if ($proy == 'BC') {
                $pdf->SetFont('times', '', 22);
                $pdf->Text(23 + $posX, 13 + $posY, utf8_decode("CORAABO"));

                $pdf->SetFont('times', '', 9);
                $pdf->Text(25 + $posX, 17 + $posY, utf8_decode("Corporación del Acueducto"));
                $pdf->Text(22 + $posX, 19.5 + $posY, utf8_decode("y Alcantarillado de Boca Chica "));

            }

            $pdf->SetFont('times', '', 12);
            $pdf->Text(180, 10, date('d/m/Y'));

            $pdf->SetFont('arial', 'B', 22);
            $pdf->SetTextColor(1, 149, 221);
            $pdf->Text(55, 35, utf8_decode("Reporte Cortes Eliminados"));

            $posY = 45;
            $posX = 6;

            //$pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(1, 149, 221);
            $pdf->SetFont('arial', '', 9);
            $pdf->SetTextColor(255, 255, 255);

            $pdf->SetY($posY);
            $pdf->SetX($posX);
            $pdf->Cell(13, 6, 'Inmueble', 0, 3, 'C', true);

            $pdf->SetY($posY);
            $pdf->SetX(13 + $posX);
            $pdf->Cell(9, 6, 'Ruta', 0, 3, 'C', true);

            $pdf->SetY($posY);
            $pdf->SetX(21 + $posX);
            $pdf->Cell(8, 6, 'Zona', 0, 3, 'C', true);

            $pdf->SetY($posY);
            $pdf->SetX(29 + $posX);
            $pdf->Cell(9, 6, 'Uso', 0, 3, 'C', true);

            $pdf->SetY($posY);
            $pdf->SetX(38 + $posX);
            $pdf->Cell(14, 6, 'Suministro', 0, 3, 'C', true);

            $pdf->SetY($posY);
            $pdf->SetX(52 + $posX);
            $pdf->Cell(38, 6, 'Cliente', 0, 3, 'C', true);

            $pdf->SetY($posY);
            $pdf->SetX(90 + $posX);
            $pdf->Cell(17, 6, 'Fecha', 0, 3, 'C', true);

            $pdf->SetY($posY);
            $pdf->SetX(107 + $posX);
            $pdf->Cell(17, 6, 'Importe', 0, 3, 'C', true);

            $pdf->SetY($posY);
            $pdf->SetX(124 + $posX);
            $pdf->Cell(26, 6, 'Autorizador', 0, 3, 'C', true);

            $pdf->SetY($posY);
            $pdf->SetX(150 + $posX);
            $pdf->Cell(46.5, 6, utf8_decode('Observación'), 0, 3, 'C', true);

            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(40, 40, 40);
            $pdf->SetFont('Arial', '', 8);

            $posY = 51;
        }

        $pdf->SetY($posY);
        $pdf->SetX($posX);
        $pdf->Cell(13, 15, $inmueble, 1, 3, 'C', true);

        $pdf->SetY($posY);
        $pdf->SetX(13 + $posX);
        $pdf->Cell(9, 15, $ruta, 1, 3, 'C', true);

        $pdf->SetY($posY);
        $pdf->SetX(21 + $posX);
        $pdf->Cell(8, 15, $zona, 1, 3, 'C', true);

        $pdf->SetY($posY);
        $pdf->SetX(29 + $posX);
        $pdf->Cell(9, 15, $uso, 1, 3, 'C', true);

        $pdf->SetY($posY);
        $pdf->SetX(38 + $posX);
        $pdf->Cell(14, 15, strtolower($suministro), 1, 3, 'C', true);

        if (!is_null($telefono)) {
            // $pdf->Line($posX + 54, $posY, $posX + 92, $posY);
            $pdf->SetY($posY);
            $pdf->SetX(52 + $posX);
            $pdf->Cell(38, 9, ucwords(strtolower(utf8_decode(substr($nombre, 0, 25)))), 'TRL', 3, 'C', false);

            $pdf->SetY($posY + 9);
            $pdf->SetX(52 + $posX);
            $pdf->Cell(38, 6, $telefono, "BRL", 3, 'C', false);
            //$pdf->Line($posX + 54, $posY + 15, $posX + 92, $posY + 15);
        } else {
            $pdf->SetY($posY);
            $pdf->SetX(52 + $posX);
            $pdf->Cell(38, 15, ucwords(strtolower(utf8_decode(substr($nombre, 0, 25)))), 1, 3, 'C', true);
        }

        $pdf->SetY($posY);
        $pdf->SetX(90 + $posX);
        $pdf->Cell(17, 15, $fecha, 1, 3, 'C', true);

        $pdf->SetY($posY);
        $pdf->SetX(107 + $posX);
        $pdf->Cell(17, 15, number_format($importe, 2), 1, 3, 'C', true);

        $pdf->SetY($posY);
        $pdf->SetX(124 + $posX);
        $pdf->Cell(26, 15, ucwords(strtolower(utf8_decode($usuario))), 1, 3, 'C', true);

        $pdf->SetY($posY);
        $pdf->SetX(150 + $posX);
        $pdf->MultiCell(46, 3.75, str_pad($observacion, 100, " ", STR_PAD_RIGHT), 1, 3, 'C', true);

        $total++;
        $posY += 15;

    }
    oci_free_statement($data);

    // Area del total de resultados

    if ($total > 0) {
        if ($posY > 285) {
            $pdf->AddPage();

            $posY = 20;

            $pdf->SetFillColor(1, 141, 221);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 14);

            $pdf->SetY($posY);
            $pdf->SetX(67 + $posX);
            $pdf->Cell(74, 10, "Cortes Eliminados Totales", 0, 3, 'C', true);

            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(1, 141, 221);
            $pdf->SetFont('Arial', 'B', 18);

            $pdf->SetY($posY + 10);
            $pdf->SetX(67 + $posX);
            $pdf->Cell(73.7, 10, $total, 1, 3, 'C', true);

        } else {

            $posY += 30;

            $pdf->SetFillColor(1, 141, 221);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetFont('Arial', 'B', 14);

            $pdf->SetY($posY);
            $pdf->SetX(67 + $posX);
            $pdf->Cell(74, 10, "Cortes Eliminados Totales", 0, 3, 'C', true);

            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(1, 141, 221);
            $pdf->SetFont('Arial', 'B', 18);

            $pdf->SetY($posY + 10);
            $pdf->SetX(67 + $posX);
            $pdf->Cell(73.7, 10, $total, 1, 3, 'C', true);
        }

    } else {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->SetFont('Arial', 'B', 24);

        $pdf->SetY($posY + 30);
        $pdf->SetX(39 + $posX);
        $pdf->Cell(112, 10, "No se encontraron resultados", 0, 3, 'C', true);
    }

    $pdf->Output('Reporte-Cortes-Eliminados.pdf', 'I');
}

if ($tip == 'excel') {
    include '../../recursos/PHPExcel.php';
    include '../../recursos/PHPExcel/Writer/Excel2007.php';
    include_once "../../clases/class.corte.php";
    ini_set('memory_limit', '-1');
    $proyecto = $_POST['proyecto'];
    $fecini   = $_POST['fecini'];
    $fecfin   = $_POST['fecfin'];

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
        getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle("Cortes Eliminados " . $proy . " " . $fecini . " " . $fecfin)
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

    //HOJA RELACION DE PAGOS

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:L1')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:L1")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'No')
        ->setCellValue('B1', utf8_encode('Inmueble'))
        ->setCellValue('C1', utf8_encode('Ruta'))
        ->setCellValue('D1', utf8_encode('Zona'))
        ->setCellValue('E1', utf8_encode('Uso'))
        ->setCellValue('F1', utf8_encode('Suministro'))
        ->setCellValue('G1', utf8_encode('Cliente'))
        ->setCellValue('H1', utf8_encode(utf8_decode('Teléfono')))
        ->setCellValue('I1', utf8_encode('Fecha'))
        ->setCellValue('J1', utf8_encode('Importe'))
        ->setCellValue('K1', utf8_encode(utf8_decode('Autorizador')))
        ->setCellValue('L1', utf8_encode(utf8_decode('Observación')));

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(35);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(100);
    $cont = 0;
    $fila = 2;
    $c    = new Corte();
    $data = $c->getCorElimPorFec($proy, $fechaIni, $fechaFin, $tipo);
    //$total = 0;
    while (oci_fetch($data)) {
        $inmueble    = oci_result($data, 'INMUEBLE');
        $ruta        = oci_result($data, 'RUTA');
        $zona        = oci_result($data, 'ZONA');
        $uso         = oci_result($data, 'USO');
        $suministro  = oci_result($data, 'SUMINISTRO') == 1 ? 'Agua' : 'Pozo';
        $nombre      = oci_result($data, 'NOMBRE');
        $telefono    = oci_result($data, 'TELEFONO') != null ? oci_result($data, 'TELEFONO') : 'N/A';
        $fecha       = oci_result($data, 'FECHA');
        $importe     = money_format('%n', oci_result($data, 'IMPORTE'));
        $usuario     = oci_result($data, 'USUARIO');
        $observacion = oci_result($data, 'OBSERVACION');

        $cont++;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $cont)
            ->setCellValue('B' . $fila, $inmueble)
            ->setCellValue('C' . $fila, $ruta)
            ->setCellValue('D' . $fila, $zona)
            ->setCellValue('E' . $fila, $uso)
            ->setCellValue('F' . $fila, $suministro)
            ->setCellValue('G' . $fila, $nombre)
            ->setCellValue('H' . $fila, $telefono)
            ->setCellValue('I' . $fila, $fecha)
            ->setCellValue('J' . $fila, $importe)
            ->setCellValue('K' . $fila, $usuario)
            ->setCellValue('L' . $fila, $observacion);

        $fila++;
    }
    oci_free_statement($data);

    $objPHPExcel->getActiveSheet()->setTitle('Cortes Eliminados');

    //mostrar la hoja q se abrira
    $objPHPExcel->setActiveSheetIndex(0);
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch   = "../../temp/Cortes_Eliminados-" . $proy . '-' . ' DEL ' . $fechaIni . ' AL ' . $fechaFin . ".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;
    //unlink($nomarch);

}
