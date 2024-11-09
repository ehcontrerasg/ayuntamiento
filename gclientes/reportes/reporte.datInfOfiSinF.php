<?
/*error_reporting(-1);
ini_set('display_errors', '-1');*/
include_once '../clases/class.reportes_gclientes.php';
require_once '../../recursos/PHPExcel.php';

$proyecto = $_POST['proyecto'];
$grupo = $_POST['grupo'];
$periodo  = $_POST['periodo'];
$perfin = $_POST['perfin'];
$zona = $_POST['zona'];
$documento = $_POST['documento'];

$cont  = 0;

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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:L1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:L2")->getFont()->setBold(true);

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:L2')->applyFromArray($estiloTitulos);
//$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:F2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'INFORME GRANDES CLIENTES GUBERNAMENTALES PERIODO ' . $proyecto . ' ' . $periodo)
    ->setCellValue('A2', utf8_encode('Código'))
    ->setCellValue('B2', utf8_encode('Zona'))
    ->setCellValue('C2', utf8_encode('Nombre'))
    ->setCellValue('D2', utf8_encode('Uso'))
    ->setCellValue('E2', utf8_encode('M3 Facturados'))
    ->setCellValue('F2', utf8_encode('Facturado en RD$ ' . $periodo))
    ->setCellValue('G2', 'Pendiente en RD$')
    ->setCellValue('H2', 'Deuda Total en RD$')
    ->setCellValue('I2', 'Recaudado en RD$ ')
    ->setCellValue('J2', 'Factura Pendientes')
    ->setCellValue('K2', 'Grupo Actividad')
    ->setCellValue('L2', 'Medido');
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(40);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(40);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(13);
// $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("k")->setWidth(40);

$fila      = 3;
$c         = new ReportesGerencia();
$registros = $c->RepOficialSinFinDeLucro($proyecto, $periodo, $perfin, $grupo, $zona, $documento);

$totalmetros            = 0;
$totalfacturadoper      = 0;
$totalfacturadodeuda    = 0;
$totalrecaudado         = 0;
$totalFactPen           = 0;
while (oci_fetch($registros)) {
    $cod_inm = oci_result($registros, "CODIGO_INM");
    $zona = oci_result($registros, "ID_ZONA");
    $alias   = oci_result($registros, "ALIAS");
    $metros  = oci_result($registros, "METROS");
    $uso     = oci_result($registros, "ID_USO");
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, $cod_inm)
        ->setCellValue('B' . $fila, $zona)
        ->setCellValue('C' . $fila, $alias)
        ->setCellValue('D' . $fila, $uso)
        ->setCellValue('E' . $fila, $metros);
    $d          = new ReportesGerencia();
    $registrosF = $d->RepOficialSinFinDeLucroFacturadoPeriodo($cod_inm, $periodo);
    while (oci_fetch($registrosF)) {
        $facturadoper = oci_result($registrosF, "FACTURADO");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F' . $fila, $facturadoper);
    }
    oci_free_statement($registrosF);
    $d          = new ReportesGerencia();
    $registrosF = $d->RepOficialSinFinDeLucroFacturadoDeuda($cod_inm, $periodo);
    while (oci_fetch($registrosF)) {
        $facturadodeuda = oci_result($registrosF, "FACTURADODEU");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G' . $fila, $facturadodeuda);
    }
    oci_free_statement($registrosF);

    $d          = new ReportesGerencia();
    $registrosF = $d->RepOficialSinFinDeLucroFacturadoDeudaTotal($cod_inm, $periodo);
    while (oci_fetch($registrosF)) {
        $facturadodeudatot = oci_result($registrosF, "DEUDATOT");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('H' . $fila, $facturadodeudatot);
    }
    oci_free_statement($registrosF);

    $d          = new ReportesGerencia();
    $registrosR = $d->RepOficialSinFinDeLucroRecaudo($cod_inm, $periodo);
    while (oci_fetch($registrosR)) {
        $recaudado = utf8_decode(oci_result($registrosR, "IMPORTE"));
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('I' . $fila, $recaudado);
    }
    oci_free_statement($registrosR);

    $d          = new ReportesGerencia();
    $registrosF = $d->RepOficialSinFinDeLucroFactPend($cod_inm);
    while (oci_fetch($registrosF)) {
        $facturapend = oci_result($registrosF, "FACTPEN");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J' . $fila, $facturapend);
    }
    oci_free_statement($registrosF);

    $d          = new ReportesGerencia();
    $registrosG = $d->RepOficialSinFinDeLucroGrupoInm($cod_inm);
    while (oci_fetch($registrosG)) {
        $grupoInm = oci_result($registrosG, "GRUPO");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('K' . $fila, $grupoInm);
    }
    oci_free_statement($registrosG);

    $d          = new ReportesGerencia();
    $registrosM = $d->RepOficialSinFinDeLucroMed($cod_inm);
    while (oci_fetch($registrosM)) {
        $grupoInm = oci_result($registrosM, "MEDIDO");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('L' . $fila, $grupoInm);
    }
    oci_free_statement($registrosM);

    //$objPHPExcel->setActiveSheetIndex(0)
    //    ->setCellValue('G'.$fila, ($facturadoper + $facturadodeuda - $recaudado));
    $totalmetros += $metros;
    $totalfacturadoper += $facturadoper;
    $totalfacturadodeuda += $facturadodeuda;
    //$totaldeuda += $totalfacturadoper + $totalfacturadodeuda;
    $totalrecaudado += $recaudado;
    $totalFactPen += $facturapend;
    $fila++;
}
oci_free_statement($registros);

//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':B'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":J" . $fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('C' . $fila, 'Totales')
    ->setCellValue('E' . $fila, $totalmetros)
    ->setCellValue('F' . $fila, $totalfacturadoper)
    ->setCellValue('G' . $fila, $totalfacturadodeuda)
    ->setCellValue('H' . $fila, $totalfacturadoper + $totalfacturadodeuda)
    ->setCellValue('I' . $fila, $totalrecaudado)
    ->setCellValue('J' . $fila, $totalFactPen);

//mostrar la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Datos_Oficiales".time().".xlsx";
$objWriter->save($nomarch);
echo $nomarch;