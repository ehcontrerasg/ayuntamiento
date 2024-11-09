<?php
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];

include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../clases/class.reportes_gerenciales.php';
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
    $datos = $l->seleccionaAcueducto($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='ResRecEnt'){
	$fecini = $_POST['fecini'];
	$fecfin = $_POST['fecfin'];
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
	
	//HOJA RECAUDADO POR ENTIDAD
	    
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D1');
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:D1')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:D2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:D2')->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'RESUMEN DE RECAUDO POR ENTIDAD '.$proyecto.' DEL '.$fecini.' AL '.$fecfin)
		->setCellValue('A2', 'Código')
		->setCellValue('B2', 'Entidad')
		->setCellValue('C2', 'Importe')
		->setCellValue('D2', 'Pagos');
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(40);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(12);
	$fila = 3;
	$c=new ReportesGerencia();
	$registros=$c->RecaudoPorEntidad($proyecto, $fecini, $fecfin);
	while (oci_fetch($registros)) {
		$cod_ent=utf8_decode(oci_result($registros,"ENTIDAD_COD"));
		$des_ent=utf8_decode(oci_result($registros,"DESC_ENTIDAD"));
		$importe=utf8_decode(oci_result($registros,"IMPORTE"));
		$cantidad=utf8_decode(oci_result($registros,"CANTIDAD"));
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $cod_ent)
			->setCellValue('B'.$fila, utf8_encode($des_ent))
			->setCellValue('C'.$fila, $importe)
			->setCellValue('D'.$fila, $cantidad);
		$fila++;
		$totalimporte += $importe;
		$totalpagos += $cantidad;
	}oci_free_statement($registros);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":D".$fila)->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$fila.":B".$fila);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":D".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$fila, 'Total Resumen')
		->setCellValue('C'.$fila, $totalimporte)
		->setCellValue('D'.$fila, $totalpagos);
		
	
	
	//HOJA RECAUDADO POR AGENCIAS OFICIALES ACEA
	    
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G1:J1');
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('G1:J1')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('G2:J2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('G1:J2')->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('G1', 'RESUMEN DETALLADO ENTIDAD 900 '.$proyecto.' DEL '.$fecini.' AL '.$fecfin)
		->setCellValue('G2', 'Código')
		->setCellValue('H2', 'Punto Pago')
		->setCellValue('I2', 'Importe')
		->setCellValue('J2', 'Pagos');
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(40);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(12);
	$fila = 3;
	$c=new ReportesGerencia();
	$registros=$c->RecaudoPorEntidadesAcea($proyecto, $fecini, $fecfin);
	while (oci_fetch($registros)) {
		$cod_pun=utf8_decode(oci_result($registros,"ID_PUNTO_PAGO"));
		$des_pun=utf8_decode(oci_result($registros,"DESCRIPCION"));
		$importe1=utf8_decode(oci_result($registros,"IMPORTE"));
		$cantidad1=utf8_decode(oci_result($registros,"CANTIDAD"));
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila, $cod_pun)
			->setCellValue('H'.$fila,utf8_encode( $des_pun))
			->setCellValue('I'.$fila, $importe1)
			->setCellValue('J'.$fila, $cantidad1);
		$fila++;
		$totalimporte1 += $importe1;
		$totalpagos1 += $cantidad1;
	}oci_free_statement($registros);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("G".$fila.":J".$fila)->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells("G".$fila.":H".$fila);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("G".$fila.":J".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('G'.$fila, 'Total Resumen')
		->setCellValue('I'.$fila, $totalimporte1)
		->setCellValue('J'.$fila, $totalpagos1);

    /*$objPHPExcel->getActiveSheet(0)->setTitle('Resumen Recaudacion Por Entidad');

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename='Resumen_Recaudacion_Entidad".$proyecto."_del_".$fecini."_al_".$fecfin.".xls'");
    header("Cache-Control: max-age=0");

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;*/
    //mostrar la hoja que se abrira
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle("Resumen Recaudacion Por Entidad");
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Resumen_Recaudacion_Entidad".$proyecto."_del_".$fecini."_al_".$fecfin.".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;
		

}