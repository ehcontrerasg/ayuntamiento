<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../clases/classPqrs.php';
ini_set('memory_limit', '-1');
$proyecto = $_POST['proyecto'];
$secIni   = $_POST['secIni'];
$secFin   = $_POST['secFin'];
$rutIni   = $_POST['rutIni'];
$rutFin   = $_POST['rutFin'];
$uniIni   = $_POST['uniIni'];
$uniFin   = $_POST['uniFin'];
$facIni   = $_POST['facIni'];
$facFin   = $_POST['facFin'];
$periodo1 = $_POST['periodo1'];
$periodo2 = $_POST['periodo2'];

$objPHPExcel = new PHPExcel();
$objPHPExcel->
    getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Reporte Inmuebles Deuda")
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("reportes phpexcel")
    ->setCategory("Reportes de Servicio al Cliente");

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

//REPORTE INMUEBLES DEUDA

if (trim($periodo1) != "" && trim($periodo2)) {
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:U1');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:U2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:U2")->getFont()->setBold(true);
} else {
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:S1');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:S2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:S2")->getFont()->setBold(true);

}

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'INMUEBLES CON DEUDA DEL SECTOR ' . $secIni . ' AL ' . $secFin)
    ->setCellValue('A2', 'Inmueble')
    ->setCellValue('B2', 'Nombre')
    ->setCellValue('C2', 'Monto Pendiente')
    ->setCellValue('D2', 'Telefono')
    ->setCellValue('E2', 'Estado')
    ->setCellValue('F2', 'Unidades')
    ->setCellValue('G2', 'Catastro')
    ->setCellValue('H2', 'Proceso')
    ->setCellValue('I2', 'Sector')
    ->setCellValue('J2', 'Zona')
    ->setCellValue('K2', 'Ruta')
    ->setCellValue('L2', 'Fac. Pendientes')
    ->setCellValue('M2', 'Medido')
    ->setCellValue('N2', 'Uso')
    ->setCellValue('O2', 'Urbanizacion')
    ->setCellValue('P2', 'Suministro')
    ->setCellValue('Q2', 'Estado PDC')
    ->setCellValue('R2', 'Serial')
    ->setCellValue('S2', 'Tipo');
if (trim($periodo1) != "" && trim($periodo2)) {
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('T2', 'Recaudo periodo ' . $periodo1)
        ->setCellValue('U2', 'Recaudo periodo ' . $periodo2);
}

$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(28);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(18);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(12);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Q")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("R")->setWidth(15);
if (trim($periodo1) != "" && trim($periodo2)) {
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("S")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("T")->setWidth(15);
}

$c         = new PQRs();
$fila      = 3;
$registros = $c->InmueblesDeuda($proyecto, $secIni, $secFin, $rutIni, $rutFin, $uniIni, $uniFin, $periodo1, $periodo2);
while (oci_fetch($registros)) {
    $inmueble   = utf8_decode(oci_result($registros, "CODIGO_INM"));
    $alias      = oci_result($registros, "ALIAS");
    $nombre     = oci_result($registros, "NOMBRE_CLI");
    $monto      = utf8_decode(oci_result($registros, "TOTAL"));
    $telefono   = utf8_decode(oci_result($registros, "TELEFONO"));
    $estado     = utf8_decode(oci_result($registros, "ID_ESTADO"));
    $unidades   = utf8_decode(oci_result($registros, "UNIDADES_HAB"));
    $catastro   = utf8_decode(oci_result($registros, "CATASTRO"));
    $proceso    = utf8_decode(oci_result($registros, "ID_PROCESO"));
    $sector     = utf8_decode(oci_result($registros, "SECTOR"));
    $ruta       = utf8_decode(oci_result($registros, "ID_RUTA"));
    $zona       = oci_result($registros, 'ID_ZONA');
    $facpend    = utf8_decode(oci_result($registros, "CANTIDAD"));
    $medido     = utf8_decode(oci_result($registros, "MEDIDO"));
    $uso        = utf8_decode(oci_result($registros, "ID_USO"));
    $urbaniza   = oci_result($registros, "DESC_URBANIZACION");
    $direccion  = oci_result($registros, "DIRECCION");
    $suministro = utf8_decode(oci_result($registros, "SUMINISTRO"));
    $estado_pdc = utf8_decode(oci_result($registros, "ESTADO_PDC"));
    $rec1       = utf8_decode(oci_result($registros, "PERIODO1"));
    $rec2       = utf8_decode(oci_result($registros, "PERIODO2"));
    $serial     = utf8_decode(oci_result($registros, "SERIAL"));
    $tipocli    = utf8_decode(oci_result($registros, "ID_TIPO_CLIENTE"));
    if ($alias == '') {
        $alias = $nombre;
    }

    if ($facpend >= $facIni && $facpend <= $facFin) {
        if ($estado_pdc == 'PDC Inactivo' && $facpend < 10) {
            $estado_pdc = 'No Apto para PDC';
        }

        if ($estado_pdc == 'PDC Inactivo' && $facpend >= 10) {
            $estado_pdc = 'Apto para PDC';
        }

        if ($estado_pdc == 'Sin PDC' && $facpend < 10) {
            $estado_pdc = 'No Apto para PDC';
        }

        if ($estado_pdc == 'Sin PDC' && $facpend >= 10) {
            $estado_pdc = 'Apto para PDC';
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $inmueble)
            ->setCellValue('B' . $fila, $alias)
            ->setCellValue('C' . $fila, $monto)
            ->setCellValue('D' . $fila, $telefono)
            ->setCellValue('E' . $fila, $estado)
            ->setCellValue('F' . $fila, $unidades)
            ->setCellValue('G' . $fila, $catastro)
            ->setCellValue('H' . $fila, $proceso)
            ->setCellValue('I' . $fila, $sector)
            ->setCellValue('J' . $fila, $zona)
            ->setCellValue('K' . $fila, $ruta)
            ->setCellValue('L' . $fila, $facpend)
            ->setCellValue('M' . $fila, $medido)
            ->setCellValue('N' . $fila, $uso)
            ->setCellValue('O' . $fila, $urbaniza . ' ' . $direccion)
            ->setCellValue('P' . $fila, $suministro)
            ->setCellValue('Q' . $fila, $estado_pdc)
            ->setCellValue('R' . $fila, $serial)
            ->setCellValue('S' . $fila, $tipocli);
        if (trim($periodo1) != "" && trim($periodo2)) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('T' . $fila, $rec1)
                ->setCellValue('U' . $fila, $rec2);
        }
        $fila++;
    }
}
oci_free_statement($registros);

//mostrafr la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch   = "../../temp/Inmuebles_Deuda_" . $proyecto . ".xlsx";

$objWriter->save($nomarch);
echo $nomarch;
