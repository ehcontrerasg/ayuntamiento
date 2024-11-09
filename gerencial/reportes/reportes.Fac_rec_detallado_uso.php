<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.repFacRecDetUso.php';
ini_set('memory_limit', '-1');
$proyecto=$_POST['proyecto'];
$periodo=$_POST['periodo'];
$ano = substr($periodo,0,4);
$mes = substr($periodo,4,2);
	
if($mes == '01' || $mes == '03' || $mes == '05' || $mes == '07' || $mes == '08' || $mes == '10' || $mes == '12') $dia = '31';
if($mes == '04' || $mes == '06' || $mes == '09' || $mes == '11') $dia = '30';
if($ano%4 == 0 && $mes == '02') $dia = '29';	
if($ano%4 != 0 && $mes == '02') $dia = '28';
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->
	getProperties()
		->setCreator("AceaSoft")
		->setLastModifiedBy("AceaSoft")
		->setTitle("Facturación y Recaudo Detallado Por uso ".$proyecto." ".$periodo)
		->setSubject("")
		->setDescription("Documento generado con PHPExcel")
		->setKeywords("usuarios phpexcel")
		->setCategory("Reportes Gerenciales");
		
	$estiloTitulos = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('rgb' => '000000')
			)
		),
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'   =>PHPExcel_Style_Alignment::VERTICAL_CENTER
		)
	);	
	
	//HOJA FACTURACION Y RECAUDO DETALLADO POR USO
	    
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:C3');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E3:G3');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A17:C17');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E17:G17');
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:G1')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A3:C4')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('E3:G4')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:G4")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'FACTURACION Y RECAUDO '.$periodo.' DETALLADO POR USO')
		->setCellValue('A3', 'AGUA - ESTE '.$periodo)
		->setCellValue('E3', 'ALCANTARILLADO - ESTE '.$periodo)
		->setCellValue('A4', 'Uso')
		->setCellValue('B4', 'Facturación')
    	->setCellValue('C4', 'Recaudación')
		->setCellValue('E4', 'Uso')
		->setCellValue('F4', 'Facturación')
    	->setCellValue('G4', 'Recaudación');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A17:C18')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A17:G18")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('E17:G18')->applyFromArray($estiloTitulos);
	//$objPHPExcel->setActiveSheetIndex(0)->getStyle("A17:G18")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A17', 'AGUA - NORTE '.$periodo)
		->setCellValue('E17', 'ALCANTARILLADO - NORTE '.$periodo)	
		->setCellValue('A18', 'Uso')
		->setCellValue('B18', 'Facturación')
    	->setCellValue('C18', 'Recaudación')
		->setCellValue('E18', 'Uso')
		->setCellValue('F18', 'Facturación')
    	->setCellValue('G18', 'Recaudación');
			
       
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(20);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(2);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(20);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(12);
	
	//FACTURADO - RECAUDADO AGUA ESTE
	
    $fila = 5;
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAguaEste($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_encode(oci_result($registros,"USO"));
		$facturado=utf8_encode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $des_uso)
			->setCellValue('B'.$fila, number_format (round($facturado,2),  0 ,"." , "," ));

		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAguaEsteSolar($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_encode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, 'Solares')
			->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAguaEsteZFC($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_encode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, 'Zona Franca Comercial')
			->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAguaEsteZFI($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_encode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, 'Zona Franca Industrial')
			->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":"."C".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, 'Total')
			->setCellValue('B'.$fila, number_format ( $totalfacturadoaguaeste,  0 ,"." , "," ));

	
	$fila = 5;
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAguaEste($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_encode(oci_result($registros,"RECAUDADO"));
		$totalrecaudadoaguaeste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));

		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAguaEsteSolar($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_encode(oci_result($registros,"RECAUDADO"));
		$totalrecaudadoaguaeste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAguaEsteZFC($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_encode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '') $recaudado = 0;
		$totalrecaudadoaguaeste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAguaEsteZFI($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_encode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '') $recaudado = 0;
		$totalrecaudadoaguaeste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, number_format ( $totalrecaudadoaguaeste,  0 ,"." , "," ));



    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A5:C'.$fila)->applyFromArray($estiloTitulos);

	//FACTURADO - RECAUDADO ALCANTARILLADO ESTE	
	
	$fila = 5;
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAlcEste($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_encode(oci_result($registros,"USO"));
		$facturado=utf8_encode(oci_result($registros,"FACTURADO"));
		$totalfacturadoalceste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, $des_uso)
			->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));

		$fila++;
	}oci_free_statement($registros);
	
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAlcEsteSolar($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_encode(oci_result($registros,"FACTURADO"));
		$totalfacturadoalceste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, 'Solares')
			->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));


        $fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAlcEsteZFC($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_encode(oci_result($registros,"FACTURADO"));
		if($facturado == '')$facturado = 0;
		$totalfacturadoalceste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, 'Zona Franca Comercial')
			->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAlcEsteZFI($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_encode(oci_result($registros,"FACTURADO"));
		if($facturado == '')$facturado = 0;
		$totalfacturadoalceste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, 'Zona Franca Industrial')
			->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	

	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, 'Total')
			->setCellValue('F'.$fila, number_format ( $totalfacturadoalceste,  0 ,"." , "," ));


			
	$fila = 5;
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAlcEste($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_encode(oci_result($registros,"RECAUDADO"));
		$totalrecaudadoalceste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila,number_format ( $recaudado,  0 ,"." , "," ));

		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAlcEsteSolar($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_encode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '')$recaudado = 0;
		$totalrecaudadoalceste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAlcEsteZFC($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_encode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '')$recaudado = 0;
		$totalrecaudadoalceste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAlcEsteZFI($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_encode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '')$recaudado = 0;
		$totalrecaudadoalceste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila,  number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila, number_format ( $totalrecaudadoalceste,  0 ,"." , "," ));



    $objPHPExcel->setActiveSheetIndex(0)->getStyle('E5:G'.$fila)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("E".$fila.":"."G".$fila)->getFont()->setBold(true);
			
	//FACTURADO - RECAUDADO AGUA NORTE
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A19:G28".$fila)->getFont()->setBold(false);
	
	$fila = 19;
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAguaNorte($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguanorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $des_uso)
			->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAguaNorteSolar($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguanorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, 'Solares')
			->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAguaNorteZFC($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguanorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, 'Zona Franca Comercial')
			->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;

	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAguaNorteZFI($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguanorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, 'Zona Franca Industrial')
			->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A29:C29".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, 'Total')
			->setCellValue('B'.$fila, number_format ( $totalfacturadoaguanorte,  0 ,"." , "," ));

	
	$fila = 19;
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAguaNorte($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalrecaudadoaguanorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
      		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAguaNorteSolar($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalrecaudadoaguanorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAguaNorteZFC($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '') $recaudado = 0;
		$totalrecaudadoaguanorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAguaNorteZFI($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '') $recaudado = 0;
		$totalrecaudadoaguanorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, number_format ( $totalrecaudadoaguanorte,  0 ,"." , "," ));


    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A19:C'.$fila)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":"."G".$fila)->getFont()->setBold(true);
	//FACTURADO - RECAUDADO ALCANTARILLADO NORTE
	
	$fila = 19;
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAlcNorte($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoalcnorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, $des_uso)
			->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));

		$fila++;
	}oci_free_statement($registros);
	
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAlcNorteSolar($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoalcnorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, 'Solares')
			->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAlcNorteZFC($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		if($facturado == '')$facturado = 0;
		$totalfacturadoalcnorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, 'Zona Franca Comercial')
			->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->FacturacionUsoAlcNorteZFI($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		if($facturado == '')$facturado = 0;
		$totalfacturadoalcnorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, 'Zona Franca Industrial')
			->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("E29:G29")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, 'Total')
			->setCellValue('F'.$fila, number_format ( $totalfacturadoalcnorte,  0 ,"." , "," ));

			
	$fila = 19;
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAlcNorte($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalrecaudadoalcnorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila, number_format ( $recaudado,  0 ,"." , "," ));

		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAlcNorteSolar($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '')$recaudado = 0;
		$totalrecaudadoalcnorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);

	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAlcNorteZFC($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '')$recaudado = 0;
		$totalrecaudadoalcnorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesFacRecDetUso();
	$registros=$c->RecaudoUsoAlcNorteZFI($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '')$recaudado = 0;
		$totalrecaudadoalcnorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila, number_format ( $totalrecaudadoalcnorte,  0 ,"." , "," ));

	$objPHPExcel->getActiveSheet()->setTitle('Uso');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('E19:G'.$fila)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":"."G".$fila)->getFont()->setBold(true);

	//HOJA FACTURACION - RECAUDO POR CONCEPTO NORTE Y ESTE
	
	$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Concepto Oficiales');
	$objPHPExcel->addSheet($myWorkSheet, 1);
	    
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:K1');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A3:C3');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('E3:G3');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('I3:K3');
	
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A1:K1')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A3:C3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('E3:G3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('I3:K3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:K3")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('A1', 'FACTURACION Y RECAUDO '.$periodo.' POR CONCEPTO OFICIALES')
		->setCellValue('A3', 'ESTE + NORTE '.$periodo)
		->setCellValue('E3', 'NORTE '.$periodo)
		->setCellValue('I3', 'ESTE '.$periodo)
		->setCellValue('A4', 'Concepto')
		->setCellValue('B4', 'Facturación')
    	->setCellValue('C4', 'Recaudación')
		->setCellValue('E4', 'Concepto')
		->setCellValue('F4', 'Facturación')
    	->setCellValue('G4', 'Recaudación')
		->setCellValue('I4', 'Concepto')
		->setCellValue('J4', 'Facturación')
    	->setCellValue('K4', 'Recaudación');
       
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("A")->setWidth(45);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("B")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("C")->setWidth(12);
	//$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("D")->setWidth(5);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("E")->setWidth(45);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("F")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("G")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("H")->setWidth(5);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("I")->setWidth(45);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("J")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("K")->setWidth(12);
	
	$fila = 4;
	$a=new ReportesFacRecDetUso();
	$registrosh=$a->grupoConceptos();
	while (oci_fetch($registrosh)) {
		$id_grupo=utf8_decode(oci_result($registrosh,"ID_GRUPO"));
		$des_grupo=utf8_decode(oci_result($registrosh,"DES_GRUPO"));
		
		$c=new ReportesFacRecDetUso();
		$registros=$c->FacturadoConceptoOficialesTotal($proyecto, $periodo, $id_grupo);
		while (oci_fetch($registros)) {
			$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		}oci_free_statement($registros);
		$totalfacturado = $totalfacturado + $facturado;
		
		$c=new ReportesFacRecDetUso();
		$registros=$c->FacturadoConceptoOficialesNorte($proyecto, $periodo, $id_grupo);
		while (oci_fetch($registros)) {
			$facturadon=utf8_decode(oci_result($registros,"FACTURADO"));
		}oci_free_statement($registros);
		$totalfacturadon = $totalfacturadon + $facturadon;
		
		$c=new ReportesFacRecDetUso();
		$registros=$c->FacturadoConceptoOficialesEste($proyecto, $periodo, $id_grupo);
		while (oci_fetch($registros)) {
			$facturadoe=utf8_decode(oci_result($registros,"FACTURADO"));
		}oci_free_statement($registros);
		$totalfacturadoe = $totalfacturadoe + $facturadoe;
			
		$c=new ReportesFacRecDetUso();
		$registros=$c->RecaudadoConceptoOficialesTotal($proyecto, $ano, $mes, $dia, $id_grupo);
		while (oci_fetch($registros)) {
			$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		}oci_free_statement($registros);
		$totalrecaudado = $totalrecaudado + $recaudado;
		
		$c=new ReportesFacRecDetUso();
		$registros=$c->RecaudadoConceptoOficialesNorte($proyecto, $ano, $mes, $dia, $id_grupo);
		while (oci_fetch($registros)) {
			$recaudadon=utf8_decode(oci_result($registros,"RECAUDADO"));
		}oci_free_statement($registros);
		$totalrecaudadon = $totalrecaudadon + $recaudadon;
		
		$c=new ReportesFacRecDetUso();
		$registros=$c->RecaudadoConceptoOficialesEste($proyecto, $ano, $mes, $dia, $id_grupo);
		while (oci_fetch($registros)) {
			$recaudadoe=utf8_decode(oci_result($registros,"RECAUDADO"));
		}oci_free_statement($registros);
		$totalrecaudadoe = $totalrecaudadoe + $recaudadoe;
		
		
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, utf8_encode($des_grupo))
			->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ))
			->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ))
			->setCellValue('E'.$fila, utf8_encode($des_grupo))
			->setCellValue('F'.$fila, number_format ( $facturadon,  0 ,"." , "," ))
			->setCellValue('G'.$fila, number_format ( $recaudadon,  0 ,"." , "," ))
			->setCellValue('I'.$fila, utf8_encode($des_grupo))
			->setCellValue('J'.$fila, number_format ( $facturadoe,  0 ,"." , "," ))
			->setCellValue('K'.$fila, number_format ( $recaudadoe,  0 ,"." , "," ));




		$fila++;
	}oci_free_statement($registrosh);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A4:C'.$fila)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('E4:G'.$fila)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('I4:K'.$fila)->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A".$fila.":K".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('A'.$fila, 'Totales')
		->setCellValue('B'.$fila, number_format ( $totalfacturado,  0 ,"." , "," ))
		->setCellValue('C'.$fila, number_format ( $totalrecaudado,  0 ,"." , "," ))
		->setCellValue('E'.$fila, 'Totales')
		->setCellValue('F'.$fila, number_format ( $totalfacturadon,  0 ,"." , "," ))
		->setCellValue('G'.$fila, number_format ( $totalrecaudadon,  0 ,"." , "," ))
		->setCellValue('I'.$fila, 'Totales')
		->setCellValue('J'.$fila, number_format ( $totalfacturadoe,  0 ,"." , "," ))
		->setCellValue('K'.$fila, number_format ( $totalrecaudadoe,  0 ,"." , "," ));



    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Uso Sin Mora');
    $objPHPExcel->addSheet($myWorkSheet, 2);

    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('A1:G1');
    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('A3:C3');
    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('E3:G3');
    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('A17:C17');
    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('E17:G17');

    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A1:G1')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A3:C4')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('E3:G4')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle("A1:G4")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A1', 'FACTURACION Y RECAUDO '.$periodo.' DETALLADO POR USO SIN MORA')
        ->setCellValue('A3', 'AGUA - ESTE '.$periodo)
        ->setCellValue('E3', 'ALCANTARILLADO - ESTE '.$periodo)
        ->setCellValue('A4', 'Uso')
        ->setCellValue('B4', 'Facturación')
        ->setCellValue('C4', 'Recaudación')
        ->setCellValue('E4', 'Uso')
        ->setCellValue('F4', 'Facturación')
        ->setCellValue('G4', 'Recaudación');

    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A17:C18')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('E17:G18')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle("A17:G18")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A17', 'AGUA - NORTE '.$periodo)
        ->setCellValue('E17', 'ALCANTARILLADO - NORTE '.$periodo)
        ->setCellValue('A18', 'Uso')
        ->setCellValue('B18', 'Facturación')
        ->setCellValue('C18', 'Recaudación')
        ->setCellValue('E18', 'Uso')
        ->setCellValue('F18', 'Facturación')
        ->setCellValue('G18', 'Recaudación');


    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("A")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("B")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("C")->setWidth(12);
  //  $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("D")->setWidth(2);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("E")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("F")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("G")->setWidth(12);

    //FACTURADO - RECAUDADO AGUA ESTE

    $fila = 5;
    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAguaEste($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"USO"));
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        $totalfacturadoaguaeste += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A'.$fila, $des_uso)
            ->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));


        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAguaEsteSolar($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        $totalfacturadoaguaeste += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A'.$fila, 'Solares')
            ->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAguaEsteZFC($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        $totalfacturadoaguaeste += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A'.$fila, 'Zona Franca Comercial')
            ->setCellValue('B'.$fila,number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAguaEsteZFI($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        $totalfacturadoaguaeste += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A'.$fila, 'Zona Franca Industrial')
            ->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $objPHPExcel->setActiveSheetIndex(2)->getStyle("A".$fila.":C".$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A'.$fila, 'Total')
        ->setCellValue('B'.$fila, number_format ( $totalfacturadoaguaeste,  0 ,"." , "," ));


    $fila = 5;
    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAguaEste($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        $totalrecaudadoaguaeste += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));


        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAguaEsteSolar($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        $totalrecaudadoaguaeste += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAguaEsteZFC($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        if($recaudado == '') $recaudado = 0;
        $totalrecaudadoaguaeste += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAguaEsteZFI($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        if($recaudado == '') $recaudado = 0;
        $totalrecaudadoaguaeste += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('C'.$fila, number_format ( $totalrecaudadoaguaeste,  0 ,"." , "," ));



    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A5:C'.$fila)->applyFromArray($estiloTitulos);
    //FACTURADO - RECAUDADO ALCANTARILLADO ESTE

    $fila = 5;
    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAlcEste($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"USO"));
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        $totalfacturadoalceste += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('E'.$fila, $des_uso)
            ->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));

        $fila++;
    }oci_free_statement($registros);


    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAlcEsteSolar($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        $totalfacturadoalceste += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('E'.$fila, 'Solares')
            ->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAlcEsteZFC($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        if($facturado == '')$facturado = 0;
        $totalfacturadoalceste += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('E'.$fila, 'Zona Franca Comercial')
            ->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAlcEsteZFI($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        if($facturado == '')$facturado = 0;
        $totalfacturadoalceste += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('E'.$fila, 'Zona Franca Industrial')
            ->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $objPHPExcel->setActiveSheetIndex(2)->getStyle("E".$fila.":G".$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('E'.$fila, 'Total')
        ->setCellValue('F'.$fila, number_format ( $totalfacturadoalceste,  0 ,"." , "," ));


    $fila = 5;
    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAlcEste($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        $totalrecaudadoalceste += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('G'.$fila, number_format ( $recaudado,  0 ,"." , "," ));

        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAlcEsteSolar($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        if($recaudado == '')$recaudado = 0;
        $totalrecaudadoalceste += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('G'.$fila, number_format ( $recaudado,  0 ,"." , "," ));

        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAlcEsteZFC($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        if($recaudado == '')$recaudado = 0;
        $totalrecaudadoalceste += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('G'.$fila,number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAlcEsteZFI($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        if($recaudado == '')$recaudado = 0;
        $totalrecaudadoalceste += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('G'.$fila,number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('G'.$fila,number_format ( $totalrecaudadoalceste,  0 ,"." , "," ));


    $objPHPExcel->setActiveSheetIndex(2)->getStyle('E5:G'.$fila)->applyFromArray($estiloTitulos);

    //FACTURADO - RECAUDADO AGUA NORTE

    $objPHPExcel->setActiveSheetIndex(2)->getStyle("A19:G28".$fila)->getFont()->setBold(false);

    $fila = 19;
    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAguaNorte($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"USO"));
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        $totalfacturadoaguanorte += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A'.$fila, $des_uso)
            ->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));

        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAguaNorteSolar($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        $totalfacturadoaguanorte += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A'.$fila, 'Solares')
            ->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAguaNorteZFC($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        $totalfacturadoaguanorte += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A'.$fila, 'Zona Franca Comercial')
            ->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAguaNorteZFI($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        $totalfacturadoaguanorte += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A'.$fila, 'Zona Franca Industrial')
            ->setCellValue('B'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $objPHPExcel->setActiveSheetIndex(2)->getStyle("A29:C29".$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A'.$fila, 'Total')
        ->setCellValue('B'.$fila, number_format ( $totalfacturadoaguanorte,  0 ,"." , "," ));


    $fila = 19;
    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAguaNorte($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        $totalrecaudadoaguanorte += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAguaNorteSolar($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        $totalrecaudadoaguanorte += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAguaNorteZFC($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        if($recaudado == '') $recaudado = 0;
        $totalrecaudadoaguanorte += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAguaNorteZFI($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        if($recaudado == '') $recaudado = 0;
        $totalrecaudadoaguanorte += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('C'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('C'.$fila, number_format ( $totalrecaudadoaguanorte,  0 ,"." , "," ));


    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A19:C'.$fila)->applyFromArray($estiloTitulos);
    //FACTURADO - RECAUDADO ALCANTARILLADO NORTE

    $fila = 19;
    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAlcNorte($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"USO"));
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        $totalfacturadoalcnorte += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('E'.$fila, $des_uso)
            ->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);


    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAlcNorteSolar($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        $totalfacturadoalcnorte += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('E'.$fila, 'Solares')
            ->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAlcNorteZFC($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        if($facturado == '')$facturado = 0;
        $totalfacturadoalcnorte += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('E'.$fila, 'Zona Franca Comercial')
            ->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->FacturacionUsoAlcNorteZFI($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $facturado=utf8_decode(oci_result($registros,"FACTURADO"));
        if($facturado == '')$facturado = 0;
        $totalfacturadoalcnorte += $facturado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('E'.$fila, 'Zona Franca Industrial')
            ->setCellValue('F'.$fila, number_format ( $facturado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('E'.$fila, 'Total')
        ->setCellValue('F'.$fila, number_format ( $totalfacturadoalcnorte,  0 ,"." , "," ));


    $objPHPExcel->setActiveSheetIndex(2)->getStyle("E".$fila.":G".$fila)->getFont()->setBold(true);

    $fila = 19;
    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAlcNorte($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        $totalrecaudadoalcnorte += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('G'.$fila, number_format ( $recaudado,  0 ,"." , "," ));

        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAlcNorteSolar($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        if($recaudado == '')$recaudado = 0;
        $totalrecaudadoalcnorte += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('G'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAlcNorteZFC($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        if($recaudado == '')$recaudado = 0;
        $totalrecaudadoalcnorte += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('G'.$fila, number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $c=new ReportesFacRecDetUso();
    $registros=$c->RecaudoUsoAlcNorteZFI($proyecto, $ano, $mes, $dia);
    while (oci_fetch($registros)) {
        $recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
        if($recaudado == '')$recaudado = 0;
        $totalrecaudadoalcnorte += $recaudado;
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('G'.$fila,number_format ( $recaudado,  0 ,"." , "," ));
        $fila++;
    }oci_free_statement($registros);

    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('G'.$fila, number_format ( $totalrecaudadoalcnorte,  0 ,"." , "," ));


    $objPHPExcel->setActiveSheetIndex(2)->getStyle('E19:G'.$fila)->applyFromArray($estiloTitulos);
    //mostrafr la hoja q se abrira
    $objPHPExcel->setActiveSheetIndex(0);

	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	$nomarch="../../temp/Facturacion_Recaudo_Detallado_Uso-".$proyecto.'-'.$periodo.".xlsx";
	$objWriter->save($nomarch);
	echo $nomarch;
?>