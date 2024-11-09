<?php
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];


/*require_once '../clases/PHPExcel.php';*/

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


if($tipo=='UniUsoPer'){
	$periodo = $_POST['periodo'];
    $proyecto = $_POST['proyecto'];
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->
	getProperties()
		->setCreator("AceaSoft")
		->setLastModifiedBy("AceaSoft")
		->setTitle("Unidades Uso y Periodo ".$proyecto." ".$periodo)
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
	    
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:C1');
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:C1')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:C2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'TOTAL UNIDADES USUARIOS ACTIVOS '.$proyecto.' '.$periodo)
    	->setCellValue('A2', 'Uso')
        ->setCellValue('B2', 'Concepto')
        ->setCellValue('C2', 'Unidades');
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(18);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(18);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(18);
	
	$c=new ReportesGerencia();
	$fila = 3;
	$registros=$c->UnidadesUsoPeriodo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$uso=utf8_decode(oci_result($registros,"USO"));
		$concepto=utf8_decode(oci_result($registros,"CONCEPTO"));
		$unidades=utf8_decode(oci_result($registros,"UNIDADES"));
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $uso)
			->setCellValue('B'.$fila, $concepto)
			->setCellValue('C'.$fila, $unidades);
		$fila++;
		$totalunidades += $unidades;
	}oci_free_statement($registros);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":C".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':B'.$fila);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$fila, 'Total')
		->setCellValue('C'.$fila, $totalunidades);
	
	//mostrar la hoja q se abrira	
	/*$objPHPExcel->setActiveSheetIndex(0);

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename='Total_Unidades_Usuarios_Activos_".$proyecto."_".$periodo.".xls'");
	header("Cache-Control: max-age=0");
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');*/
    //mostrar la hoja que se abrira
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle("Total_UNI");
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Total_Unidades_Usuarios_Activos_".$proyecto."_".$periodo.".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;


	exit;
}