<?php
error_reporting(0);
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../clases/classPqrs.php';
ini_set('memory_limit', '-1');
$proyecto = $_POST['proyecto'];
$fecGestionIni = $_POST['fecGestionIni'];
$fecGestionFin = $_POST['fecGestionFin'];
$usr = $_POST['sltUsrGestion'];
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->
	getProperties()
		->setCreator("AceaSoft")
		->setLastModifiedBy("AceaSoft")
		->setTitle("Reporte Gestion de Cobro")
		->setSubject("")
		->setDescription("Documento generado con PHPExcel")
		->setKeywords("reportes phpexcel")
		->setCategory("Reportes de Servicio al Cliente");
		
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
	
	//REPORTE GESTION DE COBRO
	    
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:L1');
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:L1')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:L2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:L1")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:L2")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'REPORTE GESTION DE COBROS '.$proyecto.' DEL '.$fecGestionIni.' AL '.$fecGestionFin)
		->setCellValue('A2', 'No')
		->setCellValue('B2', 'Codigo PQR')
		->setCellValue('C2', 'Inmueble')
    	->setCellValue('D2', 'Cliente')
        ->setCellValue('E2', 'Direccion')
        ->setCellValue('F2', 'Doc Cliente')
		->setCellValue('G2', 'Tel Cliente')
        ->setCellValue('H2', 'Fecha Gestion')
        ->setCellValue('I2', 'Fecha Pago')
		->setCellValue('J2', 'Concepto')
		->setCellValue('K2', 'pagado')
        ->setCellValue('L2', 'Usuario');
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(40);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(50);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(18);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(22);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(22);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(40);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(30);
		
	$c=new PQRs();
	$fila = 3;
	$cont = 1;
	$registros=$c->ReporteGestionCobro($proyecto, $fecGestionIni, $fecGestionFin, $usr);
	
	/*oci_fetch_all($registros, $result);
	header('Content-Type: text/json');
	var_dump($result);*/
	$cntllamadas = 1;
	$recaudado = 0;
	$usr_gestion;
	while (oci_fetch($registros)) {
		$pqr 		= utf8_decode(oci_result($registros,"CODIGO_PQR"));
		$inmueble 	= utf8_decode(oci_result($registros,"COD_INMUEBLE"));
		$cliente 	= oci_result($registros,"NOM_CLIENTE");
		$direccion 	= oci_result($registros,"DIR_CLIENTE");
		$documento 	= utf8_decode(oci_result($registros,"DOC_CLIENTE"));
		$telefono 	= utf8_decode(oci_result($registros,"TEL_CLIENTE"));
		$fechagestion = utf8_decode(oci_result($registros,"FECHA_GESTION_COBRO"));
		$fechapago  = utf8_decode(oci_result($registros,"FECHA_PAGO"));
		$concepto   = preg_replace('/\&(.)[^;]*;/', '\\1', oci_result($registros,"CONCEPTO"));
		$pagado    	= oci_result($registros,"PAGADO");
		$usuario 	= utf8_decode(oci_result($registros,"USUARIO"));
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $cont)
			->setCellValue('B'.$fila, $pqr)
			->setCellValue('C'.$fila, $inmueble)
			->setCellValue('D'.$fila, $cliente)
			->setCellValue('E'.$fila, $direccion)
			->setCellValue('F'.$fila, $documento)
			->setCellValue('G'.$fila, $telefono)
			->setCellValue('H'.$fila, $fechagestion)
			->setCellValue('I'.$fila, $fechapago)
			->setCellValue('J'.$fila, $concepto)
			->setCellValue('K'.$fila, $pagado)
			->setCellValue('L'.$fila, $usuario);

		if ($cont == 1) {
			$recaudado .= $pagado;
			$usr_gestion = array($usuario => array('cantidad' => $cantidad, 'recaudado' => $recaudado));
		}else{
			if (array_key_exists($usuario, $usr_gestion)) {
				$cntllamadas++;
				$recaudado= $recaudado+$pagado;
				$usr_gestion[$usuario]['cantidad'] = $cntllamadas;
				$usr_gestion[$usuario]['recaudado'] = $recaudado;
			}else{
				$fila++;
				$coordenadas = "J$fila:K$fila";
				$objPHPExcel->setActiveSheetIndex(0)->getStyle($coordenadas)->applyFromArray($estiloTitulos);
				$objPHPExcel->setActiveSheetIndex(0)->getStyle($coordenadas)->getFont()->setBold(true);
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('J'.$fila, "Total Gestiones:")
					->setCellValue('k'.$fila, $usr_gestion[$usuario]['cantidad']);
				$fila++;
				$coordenadas = "J$fila:K$fila";
				$objPHPExcel->setActiveSheetIndex(0)->getStyle($coordenadas)->applyFromArray($estiloTitulos);
				$objPHPExcel->setActiveSheetIndex(0)->getStyle($coordenadas)->getFont()->setBold(true);
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('J'.$fila, "Total Recaudado:")
					->setCellValue('k'.$fila, $usr_gestion[$usuario]['recaudado']);

				$cntllamadas = 1;
				$usr_gestion[$usuario] = $cntllamadas;
			}
		}

		$fila++;
		$cont++;
	}
	if (empty($usr_gestion)) {
		echo 0;
		exit();
	}
	$last = end($usr_gestion);
	//var_dump($last);
	//$cont++;
	$coordenadas = "J$fila:K$fila";
	$objPHPExcel->setActiveSheetIndex(0)->getStyle($coordenadas)->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle($coordenadas)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('J'.$fila, "Total Gestiones:")
		->setCellValue('k'.$fila, $last['cantidad']);
	$fila++;
	$coordenadas = "J$fila:K$fila";
	$objPHPExcel->setActiveSheetIndex(0)->getStyle($coordenadas)->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle($coordenadas)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('J'.$fila, "Total Recaudado:")
		->setCellValue('k'.$fila, $last['recaudado']);
	
	oci_free_statement($registros);
	
	//mostrafr la hoja q se abrira	
	$objPHPExcel->setActiveSheetIndex(0);	

	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	$nomarch="../../temp/Gestion_Cobro_".$proyecto.'_del_'.$fecGestionIni.'_al_'.$fecGestionFin.".xlsx";
	
	$objWriter->save($nomarch);
	echo $nomarch;

?>