<?php
include "../../recursos/PHPExcel.php";
include "../../recursos/PHPExcel/Writer/Excel2007.php";
include "../../clases/class.medidor.php";
ini_set('memory_limit', '-1');
$factura = $_POST["fac"];

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Facturas de Instalacion ".$factura)
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Medidores");

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

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:U1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:U1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'No')
    ->setCellValue('B1', utf8_encode(utf8_decode('Zona')))
    ->setCellValue('C1', utf8_encode(utf8_decode('Código')))
    ->setCellValue('D1', utf8_encode(utf8_decode('Dirección')))
    ->setCellValue('E1', utf8_encode('Urb'))
    ->setCellValue('F1', utf8_encode('Cliente'))
    ->setCellValue('G1', utf8_encode('Documento'))
    ->setCellValue('H1', utf8_encode('Proceso'))
    ->setCellValue('I1', utf8_encode('Catastro'))
    ->setCellValue('J1', utf8_encode('Med.'))
    ->setCellValue('K1', utf8_encode('Serial'))
    ->setCellValue('L1', utf8_encode('Emp.'))
    ->setCellValue('M1', utf8_encode('Ger.'))
    ->setCellValue('N1', utf8_encode('Cal.'))
    ->setCellValue('O1', utf8_encode('Uso'))
    ->setCellValue('P1', utf8_encode('Act.'))
    ->setCellValue('Q1', utf8_encode('Uni.'))
    ->setCellValue('R1', utf8_encode('Sum'))
    ->setCellValue('S1', utf8_encode('Contrato'))
    ->setCellValue('T1', utf8_encode('Estado'))
    ->setCellValue('U1', utf8_encode('Fecha'));
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(5);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(5);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(30);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(50);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(14);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(9);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(9);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(9);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(9);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(9);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Q")->setWidth(9);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("R")->setWidth(9);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("S")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("T")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("U")->setWidth(10);

$b= new Medidor();
$datos2=$b->getFecMinMaxInsByFact($factura);
if(oci_fetch($datos2)){
    $fecMax=oci_result($datos2,"MAXIMA");
    $fecMin=oci_result($datos2,"MINIMA");
}

/*$zonaAnt='0';
$totZona;
$totGral=0;
$y=40;*/
$fila = 2;
$cont = 1;

$a =new Medidor();
$datos=$a->getMedInmByFact($factura);
while (oci_fetch($datos)) {
    $zona=oci_result($datos,"ID_ZONA");
    $codSis=oci_result($datos,"CODIGO_INM");
    $direccion=oci_result($datos,"DIRECCION");
    $urb=oci_result($datos,"DESC_URBANIZACION");
    $cliente=oci_result($datos,"NOMBRE");
    $documento=oci_result($datos,"DOCUMENTO");
    $proceso=oci_result($datos,"ID_PROCESO");
    $catastro=oci_result($datos,"CATASTRO");
    $medidor=oci_result($datos,"MARCA_MEDNUEVO") ;
    $serial=oci_result($datos,"SERIAL_MEDNUEVO");
    $emplazamiento=oci_result($datos,"DESC_EMPLAZAMIENTO");
    $gerencia=oci_result($datos,"ID_GERENCIA");
    $calibre=oci_result($datos,"DESC_CALIBRE");
    $uso= oci_result($datos,"ID_USO");
    $act=oci_result($datos,"ID_ACTIVIDAD");
    $unidades =oci_result($datos,"UNIDADES_HAB");
    $suministro=oci_result($datos,"SUMINISTRO");
    $contrato=oci_result($datos,"CONTRATO");
    $estado=oci_result($datos,"ID_ESTADO");
    $fecha=oci_result($datos,"FECHA_REEALIZACION");

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $cont)
        ->setCellValue('B'.$fila, $zona)
        ->setCellValue('C'.$fila, $codSis)
        ->setCellValue('D'.$fila, $direccion)
        ->setCellValue('E'.$fila, $urb)
        ->setCellValue('F'.$fila, $cliente)
        ->setCellValue('G'.$fila, $documento)
        ->setCellValue('H'.$fila, $proceso)
        ->setCellValue('I'.$fila, $catastro)
        ->setCellValue('J'.$fila, $medidor)
        ->setCellValue('K'.$fila, $serial)
        ->setCellValue('L'.$fila, $emplazamiento)
        ->setCellValue('M'.$fila, $gerencia)
        ->setCellValue('N'.$fila, $calibre)
        ->setCellValue('O'.$fila, $uso)
        ->setCellValue('P'.$fila, $act)
        ->setCellValue('Q'.$fila, $unidades)
        ->setCellValue('R'.$fila, $suministro)
        ->setCellValue('S'.$fila, $contrato)
        ->setCellValue('T'.$fila, $estado)
        ->setCellValue('U'.$fila, date('d/m/y',strtotime($fecha)));
    $cont++;
    $fila++;
}oci_free_statement($datos);

$objPHPExcel->getActiveSheet()->setTitle('Facturas Instalacion');

//mostrar la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/FacturasInstalacion".$factura.time().".xlsx";
$objWriter->save($nomarch);
echo $nomarch;
?>