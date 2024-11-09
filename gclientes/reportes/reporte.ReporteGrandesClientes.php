<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.reportes_gclientes.php';

function cellColor($obj, $cells, $color)
{

    $obj->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
            'rgb' => $color,
        ),
    ));
}

$periodo  = $_POST['periodo'];
$proyecto = $_POST['proyecto'];
$estado   = $_POST['estado'];
$cont     = 0;

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Deuda Actual Oficiales")
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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:M1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:M2")->getFont()->setBold(true);

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:M2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'INFORME GRANDES CLIENTES PERIODO ' . $proyecto . ' ' . $periodo)
    ->setCellValue('A2', utf8_encode('Código'))
    ->setCellValue('B2', utf8_encode('Zona'))
    ->setCellValue('C2', utf8_encode('Nombre'))
    ->setCellValue('D2', utf8_encode('Uso'))
    ->setCellValue('E2', utf8_encode('Suministro'))
    ->setCellValue('F2', utf8_encode('M3 Facturados'))
    ->setCellValue('G2', utf8_encode('Facturado en RD$ ' . $periodo))
    ->setCellValue('H2', 'Pendiente en RD$')
    ->setCellValue('I2', 'Deuda Total en RD$')
    ->setCellValue('J2', 'Recaudado en RD$ ')
    ->setCellValue('K2', 'Facturado Ori en RD$ ')
    ->setCellValue('L2', 'Serial ')
    ->setCellValue('M2', 'Diametro ');
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(40);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(25);

$fila      = 3;
$c         = new ReportesGerencia();
$registros = $c->RepGrandesClientes($proyecto, $periodo,$estado);
while (oci_fetch($registros)) {
    $cod_inm    = oci_result($registros, "CODIGO_INM");
    $zona       = oci_result($registros, "ID_ZONA");
    $suministro = oci_result($registros, "SUMINISTRO");
    $alias      = oci_result($registros, "ALIAS");
    $metros     = oci_result($registros, "METROS");
    $serial     = oci_result($registros, "SERIAL");
    $diametro     = oci_result($registros, "DIAMETRO");
    $uso = oci_result($registros, "ID_USO");
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, $cod_inm)
        ->setCellValue('B' . $fila, $zona)
        ->setCellValue('C' . $fila, $alias)
        ->setCellValue('D' . $fila, $uso)
        ->setCellValue('E' . $fila, $suministro)
        ->setCellValue('F' . $fila, $metros);
    $d = new ReportesGerencia();

    $d          = new ReportesGerencia();
    $registrosF = $d->RepGrandesClientesFacturadoPeriodo($cod_inm, $periodo);
    while (oci_fetch($registrosF)) {
        $facturadoper = oci_result($registrosF, "FACTURADO");
        $facturadoOri = oci_result($registrosF, "FACT_ORI");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G' . $fila, $facturadoper);
        if ($facturadoOri > 0) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('K' . $fila, $facturadoOri);
            cellColor($objPHPExcel, 'K' . $fila, 'ff0000');
        }
    }
    oci_free_statement($registrosF);
    $d          = new ReportesGerencia();
    $registrosF = $d->RepGrandesClientesFacturadoDeuda($cod_inm, $periodo);
    while (oci_fetch($registrosF)) {
        $facturadodeuda = oci_result($registrosF, "FACTURADODEU");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('H' . $fila, $facturadodeuda);
    }
    oci_free_statement($registrosF);

    $d          = new ReportesGerencia();
    $registrosF = $d->RepGrandesClientesFacturadoDeudaTotal($cod_inm, $periodo);
    while (oci_fetch($registrosF)) {
        $facturadodeudatot = oci_result($registrosF, "DEUDATOT");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('I' . $fila, $facturadodeudatot);
    }
    oci_free_statement($registrosF);

    $d          = new ReportesGerencia();
    $registrosR = $d->RepGrandesClientesRecaudo($cod_inm, $periodo);
    while (oci_fetch($registrosR)) {
        $recaudado = utf8_decode(oci_result($registrosR, "IMPORTE"));
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J' . $fila, $recaudado);
    }
    oci_free_statement($registrosR);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('L' . $fila, $serial);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('M' . $fila, $diametro);
    
    $totalmetros += $metros;
    $totalfacturadoper += $facturadoper;
    $totalfacturadodeuda += $facturadodeuda;
    $totalrecaudado += $recaudado;
    $fila++;
}
oci_free_statement($registros);

$objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":M" . $fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B' . $fila, '-')
    ->setCellValue('C' . $fila, 'Totales')
    ->setCellValue('D' . $fila, '-')
    ->setCellValue('E' . $fila, '-')
    ->setCellValue('F' . $fila, $totalmetros)
    ->setCellValue('G' . $fila, $totalfacturadoper)
    ->setCellValue('H' . $fila, $totalfacturadodeuda)
    ->setCellValue('I' . $fila, $totalfacturadodeuda)
    ->setCellValue('J' . $fila, $totalrecaudado);

//mostrar la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Listado Inmubles');
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Reporte_Grandes_Clientes_" . $proyecto . "_" . $periodo . ".xlsx";
$objWriter->save($nomarch);
echo $nomarch;
exit;