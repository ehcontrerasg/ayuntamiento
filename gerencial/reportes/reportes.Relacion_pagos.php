<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.repRelPag.php';
ini_set('memory_limit', '-1');
$proyecto=$_POST['proyecto'];
$fecini=$_POST['fecini'];
$fecfin=$_POST['fecfin'];
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->
	getProperties()
		->setCreator("AceaSoft")
		->setLastModifiedBy("AceaSoft")
		->setTitle("Relacion de Pagos ".$proyecto." ".$fecini." ".$fecfin)
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
	
	//HOJA RELACION DE PAGOS
	    
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:Z1')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:Z1")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'No')
		->setCellValue('B1', utf8_encode(utf8_decode('Código Pago')))
		->setCellValue('C1', utf8_encode(utf8_decode('Código Inmueble')))
    	->setCellValue('D1', utf8_encode('Fecha Pago'))
        ->setCellValue('E1', utf8_encode('Importe Pagado'))
        ->setCellValue('F1', utf8_encode('Importe Aplcado'))
        ->setCellValue('G1', utf8_encode('Nota Credito'))
		->setCellValue('H1', utf8_encode('Saldo Ant'))
        ->setCellValue('I1', utf8_encode('Facturas Pagas'))
        ->setCellValue('J1', utf8_encode('Total Facturado'))
		->setCellValue('K1', utf8_encode('Periodo Inicial'))
        ->setCellValue('L1', utf8_encode('Periodo Final'))
        ->setCellValue('M1', utf8_encode('Diferencia'))
		->setCellValue('N1', utf8_encode('Entidad'))
		->setCellValue('O1', utf8_encode('Punto'))
        ->setCellValue('P1', utf8_encode('Caja'))
        ->setCellValue('Q1', utf8_encode('Tipo'))
		->setCellValue('R1', utf8_encode('Suministro'))
		->setCellValue('S1', utf8_encode('Unidades'))
        ->setCellValue('T1', utf8_encode(utf8_decode('Descripción')))
        ->setCellValue('U1', utf8_encode('Medidor'))
		->setCellValue('V1', utf8_encode(utf8_decode('Categoría')))
		->setCellValue('W1', utf8_encode('Tipo Pago'))
        ->setCellValue('X1', utf8_encode('Sector'))
        ->setCellValue('Y1', utf8_encode('Ruta'))
        ->setCellValue('Z1', utf8_encode('Medio Pago'));

	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Q")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("R")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("S")->setWidth(55);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("T")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("U")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("V")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("W")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("X")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Y")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Z")->setWidth(15);
	$cont = 0;
	$fila = 2;
	$c=new ReportesRelPag();
	$registros=$c->relacionPagos($proyecto, $fecini, $fecfin);
	while (oci_fetch($registros)) {
		$codpago=utf8_decode(oci_result($registros,"COD_PAGO"));
		$inmueble=utf8_decode(oci_result($registros,"INMUEBLE"));
		$fecpago=utf8_decode(oci_result($registros,"FECHA_PAGO"));
		$imppago=utf8_decode(oci_result($registros,"IMPORTE_PAGADO"));
		$impapli=utf8_decode(oci_result($registros,"IMPORTE_APLICADO"));
		$saldoant=utf8_decode(oci_result($registros,"SALDOANT"));
		$numfac=utf8_decode(oci_result($registros,"NUM_FACTURAS"));
        $totnota=utf8_decode(oci_result($registros,"NOTA_CREDITO"));
		$totfac=utf8_decode(oci_result($registros,"TOTAL_FACTURADO"));
		$perini=utf8_decode(oci_result($registros,"PERMIN"));
		$perfin=utf8_decode(oci_result($registros,"PERMAX"));
		$diferencia=utf8_decode(oci_result($registros,"DIFERENCIA"));
		$entidad=utf8_decode(oci_result($registros,"COD_ENTIDAD"));
		$punto=utf8_decode(oci_result($registros,"PUNTO"));
		$caja=utf8_decode(oci_result($registros,"NUM_CAJA"));
		$tipo=utf8_decode(oci_result($registros,"TIPO"));
		$suministro=utf8_decode(oci_result($registros,"SUMINISTRO"));
		$unidades=utf8_decode(oci_result($registros,"UNIDADES"));
		$descrip=utf8_decode(oci_result($registros,"DESCRIPCION"));
		$medidor=utf8_decode(oci_result($registros,"MEDIDOR"));
		$categoria=utf8_decode(oci_result($registros,"CATEGORIA"));
		$concepto =utf8_decode(oci_result($registros,"CONCEPTO"));
		$uso =utf8_decode(oci_result($registros,"USO"));
		$cupo =utf8_decode(oci_result($registros,"CUPO_BASICO"));
        $sector =utf8_decode(oci_result($registros,"ID_SECTOR"));
        $ruta =utf8_decode(oci_result($registros,"ID_RUTA"));
        $zona =utf8_decode(oci_result($registros,"ID_ZONA"));
        $medio_pago =utf8_decode(oci_result($registros,"DESCRIPCIONM"));

		//BORRAR ESTE CODIGO CUANDO SE CORRIJAN LAS TARIFAS Y CUPOS
		if($uso == 'R'){
			if($concepto == 1){
				if ($cupo >=0 && $cupo <=9.9) $categoria = 'R1';
				if ($cupo >=10 && $cupo <=15.9) $categoria = 'R2';
				if ($cupo >=16 && $cupo <=21.9) $categoria = 'R3';
				if ($cupo >=22 && $cupo <=31.9) $categoria = 'R4';
				if ($cupo >=32) $categoria = 'R5';
			}
			if($concepto == 3){
				if ($cupo >=0 && $cupo <=59.9) $categoria = 'R1';
				if ($cupo >=60 && $cupo <=95.9) $categoria = 'R2';
				if ($cupo >=96 && $cupo <=131.9) $categoria = 'R3';
				if ($cupo >=132 && $cupo <=191.9) $categoria = 'R4';
				if ($cupo >=192) $categoria = 'R5';
			}
			
		}
		///////////////////////
		if($saldoant==0) $saldoant = '';
		if($tipo == 'Recaudo' && $impapli < $totfac && $numfac == 1 || ($concepto == 20 || $concepto == 21 || $concepto == 22 || $concepto == 28 || $concepto == 30 || $concepto == 93 || $concepto == 101 || $concepto == 128 || $concepto == 193)){	
			$concepto="No Valido";
			$cont++;
		}
		else{
			$concepto="Valido";
			$cont++;
		}	
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $cont)
			->setCellValue('B'.$fila, $codpago)
			->setCellValue('C'.$fila, $inmueble)
			->setCellValue('D'.$fila, $fecpago)
			->setCellValue('E'.$fila, $imppago)
			->setCellValue('F'.$fila, $impapli)
            ->setCellValue('G'.$fila, $totnota)
			->setCellValue('H'.$fila, $saldoant)
			->setCellValue('I'.$fila, $numfac)
			->setCellValue('J'.$fila, $totfac)
			->setCellValue('K'.$fila, $perini)
			->setCellValue('L'.$fila, $perfin)
			->setCellValue('M'.$fila, $diferencia)
			->setCellValue('N'.$fila, $entidad)
			->setCellValue('O'.$fila, $punto)
			->setCellValue('P'.$fila, $caja)
			->setCellValue('Q'.$fila, $tipo)
			->setCellValue('R'.$fila, $suministro)
			->setCellValue('S'.$fila, $unidades)
			->setCellValue('T'.$fila, $descrip)
			->setCellValue('U'.$fila, $medidor)
			->setCellValue('V'.$fila, $categoria)
			->setCellValue('W'.$fila, $concepto)
            ->setCellValue('X'.$fila, $sector)
            ->setCellValue('Y'.$fila, $ruta)
            ->setCellValue('Z'.$fila, $medio_pago);
		$fila++;
	}oci_free_statement($registros);
	
	$objPHPExcel->getActiveSheet()->setTitle('Relacion Pagos');
	
	//mostrar la hoja q se abrira	
	$objPHPExcel->setActiveSheetIndex(0);	
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	$nomarch="../../temp/Relacion_Pagos-".$proyecto.'-'.' DEL '.$fecini.' AL '.$fecfin.".xlsx";
	$objWriter->save($nomarch);
	echo $nomarch;
	//unlink($nomarch);
?>