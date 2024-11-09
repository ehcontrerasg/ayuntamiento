<?php
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include_once '../clases/class.reportes_gclientes.php';
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
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
    $l=new ReportesGerencia();
    $datos = $l->seleccionaAcueducto();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='DeuOfiPer'){
	$periodo = $_POST['periodo'];
	$periodof = $_POST['periodof'];
    $proyecto = $_POST['proyecto'];
    $totalfacturadoper = 0;
    $totalfacturasper  = 0;
    $totalfacven       = 0;
    $totalfacturvenc = 0;

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
	
	//REPORTE UNIDADES POR USO Y PERIODO
	    
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1');
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:F2")->getFont()->setBold(true);
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:F1')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:F2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'DEUDA ACTUAL OFICIALES '.$proyecto.' '.$periodo.' AL '.$periodof)
    	->setCellValue('A2', 'Código')
        ->setCellValue('B2', utf8_encode('Descripción'))
        ->setCellValue('C2', utf8_encode('Facturación '.$periodo.' AL '.$periodof))
		->setCellValue('D2', 'Monto en RD$ Fac. Vencidas')
		->setCellValue('E2', 'Cantidad Fac. Vencidas')
		->setCellValue('F2', 'Deuda Total en RD$');
        //->setCellValue('G2', 'Periodo Inicial')
        //->setCellValue('H2', 'Periodo Final');
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(40);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(25);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(25);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(25);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(25);
    //$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(25);
    //$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(25);
	
	$fila = 3;
	$c=new ReportesGerencia();
	$registros=$c->DeudaOficialVencidas($proyecto,$periodo,$periodof);
	while (oci_fetch($registros)) {
		$cod_grupo=oci_result($registros,"GRUPO");
		$des_grupo=oci_result($registros,"DESC_GRUPO");
		$facturado=utf8_encode(oci_result($registros,"FACTURADO"));
		$cant_venc=utf8_encode(oci_result($registros,"CANTIDADFACVE"));
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $cod_grupo)
			->setCellValue('B'.$fila, $des_grupo)
			->setCellValue('D'.$fila, $facturado)
			->setCellValue('E'.$fila, $cant_venc);
		$d=new ReportesGerencia();
		$registrosd=$d->DeudaOficialPeriodo($proyecto, $periodo, $periodof, $cod_grupo);
		while (oci_fetch($registrosd)) {
			$facper=utf8_encode(oci_result($registrosd,"FACTURADOPER"));
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('C'.$fila, $facper);
		}oci_free_statement($registrosd);
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('F'.$fila, $facper + $facturado);
		$fila++;
		$totalfacturadoper += $facper;
		$totalfacturasper += $facturado;
		$totalfacven += $cant_venc;
		$totalfacturvenc += $facper + $facturado;
	}oci_free_statement($registros);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':B'.$fila);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":F".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$fila, 'Totales')
		->setCellValue('C'.$fila, $totalfacturadoper)
		->setCellValue('D'.$fila, $totalfacturasper)
		->setCellValue('E'.$fila, $totalfacven)
		->setCellValue('F'.$fila, $totalfacturvenc);
	
	//mostrar la hoja q se abrira	
/*	$objPHPExcel->setActiveSheetIndex(0);

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename='Deuda_Oficiales_".$proyecto."_".$periodo.".xls'");
	header("Cache-Control: max-age=0");
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'
	$objWriter->save('php://output');
	exit;*/

//mostrar la hoja que se abrira
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Deuda de Oficiales');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Deuda_Oficiales_".$proyecto."_".$periodo.".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;
}