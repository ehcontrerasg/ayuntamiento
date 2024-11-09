<?php
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];
ini_set('memory_limit', '3000M');
include_once '../clases/classPqrs.php';
require_once '../clases/PHPExcel.php';
session_start();
$cod=$_SESSION['codigo'];

if($tipo=='sess'){
    $segundos = 1800; //si pasa este tiempo se detecta la inactividad del usuario en el sitio
    if(($_SESSION['tiempo']+$segundos) < time()) {
        session_destroy();
        echo "false";
    }else{
        $_SESSION['tiempo']=time();
        echo "true";
    }
}

if($tipo=='selPro'){
    $l=new PQRs();
    $datos = $l->seleccionaAcueducto();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='InmPdc'){
    $proyecto = $_POST['proyecto'];
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->
	getProperties()
		->setCreator("AceaSoft")
		->setLastModifiedBy("AceaSoft")
		->setTitle("Historico Recaudacion ".$proyecto." ".$periodo)
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
	
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:R1');	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:R2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:R2")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'SEGUIMIENTO A INMUEBLES CON PDC')
		->setCellValue('A2', 'Inmueble')
		->setCellValue('B2', 'Nombre')
		->setCellValue('C2', 'Monto Pendiente')
    	->setCellValue('D2', 'Telefono')
        ->setCellValue('E2', 'Estado')
        ->setCellValue('F2', 'Unidades')
		->setCellValue('G2', 'Catastro')
        ->setCellValue('H2', 'Proceso')
        ->setCellValue('I2', 'Sector')
		->setCellValue('J2', 'Ruta')
        ->setCellValue('K2', 'Fac. Pendientes')
        ->setCellValue('L2', 'Medido')
		->setCellValue('M2', 'Uso')
        ->setCellValue('N2', 'Urbanizacion')
        ->setCellValue('O2', 'Estado PDC')
		->setCellValue('P2', 'Cuotas Pagadas')
        ->setCellValue('Q2', 'Total Deuda')
        ->setCellValue('R2', 'Total Pagado');
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(28);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(18);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(8);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(25);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Q")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("R")->setWidth(15);
	
	$c=new PQRs();
	$fila = 3;
	$registros=$c->InmueblesPdc($proyecto);
	while (oci_fetch($registros)) {
		$inmueble=utf8_decode(oci_result($registros,"CODIGO_INM"));
		$alias=utf8_decode(oci_result($registros,"ALIAS"));
		$nombre=utf8_decode(oci_result($registros,"NOMBRE_CLI"));
		$monto=utf8_decode(oci_result($registros,"TOTAL"));
		$telefono=utf8_decode(oci_result($registros,"TELEFONO"));
		$estado=utf8_decode(oci_result($registros,"ID_ESTADO"));
		$unidades=utf8_decode(oci_result($registros,"UNIDADES_HAB"));
		$catastro=utf8_decode(oci_result($registros,"CATASTRO"));
		$proceso=utf8_decode(oci_result($registros,"ID_PROCESO"));
		$sector=utf8_decode(oci_result($registros,"SECTOR"));
		$ruta=utf8_decode(oci_result($registros,"ID_RUTA"));
		$facpend=utf8_decode(oci_result($registros,"CANTIDAD"));
		$medido=utf8_decode(oci_result($registros,"MEDIDO"));
		$uso=utf8_decode(oci_result($registros,"ID_USO"));
		$urbaniza=utf8_decode(oci_result($registros,"DESC_URBANIZACION"));
		$estadopdc=utf8_decode(oci_result($registros,"ESTADO"));
		$cuotaspagas=utf8_decode(oci_result($registros,"TOTAL_CUOTAS_PAG"));
		$totalpdc=utf8_decode(oci_result($registros,"TOTAL_PDC"));
		$totalpagado=utf8_decode(oci_result($registros,"TOTAL_PAGADO"));
		if($alias == '') $alias = $nombre;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $inmueble)
			->setCellValue('B'.$fila, $alias)
			->setCellValue('C'.$fila, $monto)
			->setCellValue('D'.$fila, $telefono)
			->setCellValue('E'.$fila, $estado)
			->setCellValue('F'.$fila, $unidades)
			->setCellValue('G'.$fila, $catastro)
			->setCellValue('H'.$fila, $proceso)
			->setCellValue('I'.$fila, $sector)
			->setCellValue('J'.$fila, $ruta)
			->setCellValue('K'.$fila, $facpend)
			->setCellValue('L'.$fila, $medido)
			->setCellValue('M'.$fila, $uso)
			->setCellValue('N'.$fila, $urbaniza)
			->setCellValue('O'.$fila, $estadopdc)
			->setCellValue('P'.$fila, $cuotaspagas)
			->setCellValue('Q'.$fila, $totalpdc)
			->setCellValue('R'.$fila, $totalpagado);
		$fila++;
	}oci_free_statement($registros);
	
	//mostrar la hoja q se abrira	
	$objPHPExcel->setActiveSheetIndex(0);	

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename='Seguimiento_PDC_".$proyecto.".xls'");
	header("Cache-Control: max-age=0");
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}