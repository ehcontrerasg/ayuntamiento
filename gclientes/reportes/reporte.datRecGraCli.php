<?
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../clases/class.reportes_gclientes.php';
$fechaini = $_POST['fechaini'];
$fechafin = $_POST['fechafin'];
$proyecto = $_POST['proyecto'];
$uso = $_POST['uso'];
$cont = 0;

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Recaudo Grandes Clientes")
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Gerenciales");

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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:L1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:L2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:L2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1',    'REPORTE RECAUDO GRANDES CLIENTES '.$proyecto.' DEL '.$fechaini.' AL '.$fechafin)
    ->setCellValue('A2',    'Código')
    ->setCellValue('B2',    'Estado')
    ->setCellValue('C2',    'Proceso')
    ->setCellValue('D2',    'Catastro')
    ->setCellValue('E2',    'Cliente')
    ->setCellValue('F2',    'Dirección')
    ->setCellValue('G2',    'Urbanización')
    ->setCellValue('H2',    'Importe')
    ->setCellValue('I2',    'No Pago')
    ->setCellValue('J2',    'Fecha Pago')
    ->setCellValue('K2',    'Forma Pago')
    ->setCellValue('L2',    'Uso');
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
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(18);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(18);

$fila = 3;
$c=new ReportesGerencia();
$registros=$c->RepRecaudoGrandesClientes($proyecto, $fechaini, $fechafin, $uso);
while (oci_fetch($registros)) {
    $cod_inm=oci_result($registros,"CODIGO_INM");
    $est_inm=oci_result($registros,"ID_ESTADO");
    $pro_inm=oci_result($registros,"ID_PROCESO");
    $cat_inm=oci_result($registros,"CATASTRO");
    $cli_nom=oci_result($registros,"ALIAS");
    $dir_inm=oci_result($registros,"DIRECCION");
    $urb_inm=oci_result($registros,"DESC_URBANIZACION");
    $imp_inm=oci_result($registros,"IMPORTE");
    $pag_inm=oci_result($registros,"PAGO");
    $fec_pag=oci_result($registros,"FECPAGO");
    $for_pag=oci_result($registros,"FORMAPAGO");
    $desc_uso=oci_result($registros,"DESC_USO");

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $cod_inm)
        ->setCellValue('B'.$fila, $est_inm)
        ->setCellValue('C'.$fila, $pro_inm)
        ->setCellValue('D'.$fila, $cat_inm)
        ->setCellValue('E'.$fila, $cli_nom)
        ->setCellValue('F'.$fila, $dir_inm)
        ->setCellValue('G'.$fila, $urb_inm)
        ->setCellValue('H'.$fila, $imp_inm)
        ->setCellValue('I'.$fila, $pag_inm)
        ->setCellValue('J'.$fila, $fec_pag)
        ->setCellValue('K'.$fila, $for_pag)
        ->setCellValue('L'.$fila, $desc_uso);
    $fila++;
}oci_free_statement($registros);


//mostrar la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename='Reporte_Recaudo_Grandes_Clientes_".$proyecto."_".$fechaini."_al_".$fechafin.".xls'");
header("Cache-Control: max-age=0");

if($uso == "")
    $uso = "Todos";

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Recaudo_grandes_clientes".time()."_"."uso: ".$uso."_".$proyecto."_".$fechaini."_al_".$fechafin.".xlsx";
$objWriter->save($nomarch);
echo $nomarch;
?>
