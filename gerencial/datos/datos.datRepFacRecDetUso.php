<?php
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../clases/class.reportes_gerenciales.php';
ini_set('memory_limit', '-1');
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


if($tipo=='FacRecDetUso'){
	//require_once '../clases/PHPExcel.php';
	$periodo = $_POST['periodo'];
    $proyecto = $_POST['proyecto'];
	
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
		->setTitle("Historico Recaudacion ".$proyecto." ".$fecini." ".$fecfin)
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
	
	
	//HOJA FACTURACION Y RECAUDO DETALLADO POR USO
	    
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:C2');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E2:G2');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A17:C17');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E17:G17');
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:G3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:G3")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'FACTURACION Y RECAUDO '.$periodo.' DETALLADO POR USO')
		->setCellValue('A2', 'AGUA - ESTE '.$periodo)
		->setCellValue('E2', 'ALCANTARILLADO - ESTE '.$periodo)	
		->setCellValue('A3', 'Uso')
		->setCellValue('B3', utf8_encode('Facturación'))
    	->setCellValue('C3', utf8_encode('Recaudación'))
		->setCellValue('E3', 'Uso')
		->setCellValue('F3', utf8_encode('Facturación'))
    	->setCellValue('G3', utf8_encode('Recaudación'));
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A17:G18')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A17:G18")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A17', 'AGUA - NORTE '.$periodo)
		->setCellValue('E17', 'ALCANTARILLADO - NORTE '.$periodo)	
		->setCellValue('A18', 'Uso')
		->setCellValue('B18', utf8_encode('Facturación'))
    	->setCellValue('C18', utf8_encode('Recaudación'))
		->setCellValue('E18', 'Uso')
		->setCellValue('F18', utf8_encode('Facturación'))
    	->setCellValue('G18', utf8_encode('Recaudación'));
			
       
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(20);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(2);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(20);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(12);
	
	//FACTURADO - RECAUDADO AGUA ESTE
	
	$fila = 4;
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAguaEste($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $des_uso)
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAguaEsteSolar($proyecto, $periodo);
	while (oci_fetch($registros)) {
		//$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A11', 'Solares')
			->setCellValue('B11', $facturado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAguaEsteZFC($proyecto, $periodo);
	while (oci_fetch($registros)) {
		//$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A12', 'Zona Franca Comercial')
			->setCellValue('B12', $facturado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAguaEsteZFI($proyecto, $periodo);
	while (oci_fetch($registros)) {
		//$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A13', 'Zona Franca Industrial')
			->setCellValue('B13', $facturado);
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A14:C14".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A14', 'Total')
			->setCellValue('B14', $totalfacturadoaguaeste);
	
	$fila = 4;		
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAguaEste($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalrecaudadoaguaeste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, $recaudado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAguaEsteSolar($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalrecaudadoaguaeste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C11', $recaudado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAguaEsteZFC($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '') $recaudado = 0;
		$totalrecaudadoaguaeste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C12', $recaudado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAguaEsteZFI($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '') $recaudado = 0;
		$totalrecaudadoaguaeste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C13', $recaudado);
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C14', $totalrecaudadoaguaeste);	
			
	//FACTURADO - RECAUDADO ALCANTARILLADO ESTE	
	
	$fila = 4;
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAlcEste($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoalceste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, $des_uso)
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAlcEsteSolar($proyecto, $periodo);
	while (oci_fetch($registros)) {
		//$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoalceste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E11', 'Solares')
			->setCellValue('F11', $facturado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAlcEsteZFC($proyecto, $periodo);
	while (oci_fetch($registros)) {
		//$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		if($facturado == '')$facturado = 0;
		$totalfacturadoalceste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E12', 'Zona Franca Comercial')
			->setCellValue('F12', $facturado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAlcEsteZFI($proyecto, $periodo);
	while (oci_fetch($registros)) {
		//$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		if($facturado == '')$facturado = 0;
		$totalfacturadoalceste += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E13', 'Zona Franca Industrial')
			->setCellValue('F13', $facturado);
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("E14:G14")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E14', 'Total')
			->setCellValue('F14', $totalfacturadoalceste);
			
	$fila = 4;		
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAlcEste($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalrecaudadoalceste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila, $recaudado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAlcEsteSolar($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '')$recaudado = 0;
		$totalrecaudadoalceste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G11', $recaudado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAlcEsteZFC($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '')$recaudado = 0;
		$totalrecaudadoalceste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G12', $recaudado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAlcEsteZFI($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '')$recaudado = 0;
		$totalrecaudadoalceste += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G13', $recaudado);
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G14', $totalrecaudadoalceste);		
			
			
			
	//FACTURADO - RECAUDADO AGUA NORTE
	
	$fila = 19;
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAguaNorte($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguanorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $des_uso)
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAguaNorteSolar($proyecto, $periodo);
	while (oci_fetch($registros)) {
		//$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguanorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A26', 'Solares')
			->setCellValue('B26', $facturado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAguaNorteZFC($proyecto, $periodo);
	while (oci_fetch($registros)) {
		//$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguanorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A27', 'Zona Franca Comercial')
			->setCellValue('B27', $facturado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAguaNorteZFI($proyecto, $periodo);
	while (oci_fetch($registros)) {
		//$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguanorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A28', 'Zona Franca Industrial')
			->setCellValue('B28', $facturado);
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A29:C29".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A29', 'Total')
			->setCellValue('B29', $totalfacturadoaguanorte);
	
	$fila = 19;		
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAguaNorte($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalrecaudadoaguanorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, $recaudado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAguaNorteSolar($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalrecaudadoaguanorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C26', $recaudado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAguaNorteZFC($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '') $recaudado = 0;
		$totalrecaudadoaguanorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C27', $recaudado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAguaNorteZFI($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '') $recaudado = 0;
		$totalrecaudadoaguanorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C28', $recaudado);
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C29', $totalrecaudadoaguanorte);	
			
	//FACTURADO - RECAUDADO ALCANTARILLADO NORTE
	
	$fila = 19;
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAlcNorte($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoalcnorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, $des_uso)
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAlcNorteSolar($proyecto, $periodo);
	while (oci_fetch($registros)) {
		//$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoalcnorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E26', 'Solares')
			->setCellValue('F26', $facturado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAlcNorteZFC($proyecto, $periodo);
	while (oci_fetch($registros)) {
		//$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		if($facturado == '')$facturado = 0;
		$totalfacturadoalcnorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E27', 'Zona Franca Comercial')
			->setCellValue('F27', $facturado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FacturacionUsoAlcNorteZFI($proyecto, $periodo);
	while (oci_fetch($registros)) {
		//$des_uso=utf8_decode(oci_result($registros,"USO"));
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		if($facturado == '')$facturado = 0;
		$totalfacturadoalcnorte += $facturado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E28', 'Zona Franca Industrial')
			->setCellValue('F28', $facturado);
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("E29:G29")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E29', 'Total')
			->setCellValue('F29', $totalfacturadoalcnorte);
			
	$fila = 19;		
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAlcNorte($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalrecaudadoalcnorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila, $recaudado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAlcNorteSolar($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '')$recaudado = 0;
		$totalrecaudadoalcnorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G26', $recaudado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAlcNorteZFC($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '')$recaudado = 0;
		$totalrecaudadoalcnorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G27', $recaudado);
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->RecaudoUsoAlcNorteZFI($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		if($recaudado == '')$recaudado = 0;
		$totalrecaudadoalcnorte += $recaudado;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G28', $recaudado);
	}oci_free_statement($registros);
	
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G29', $totalrecaudadoalcnorte);						
	$objPHPExcel->getActiveSheet()->setTitle('Uso');
	
	//HOJA F - R POR CONCEPTO NORTE Y ESTE
	
	$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Concepto Oficiales');
	$objPHPExcel->addSheet($myWorkSheet, 1);
	    
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:K1');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A2:C2');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('E2:G2');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('I2:K2');
	
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A1:K3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:K3")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('A1', 'FACTURACION Y RECAUDO '.$periodo.' POR CONCEPTO OFICIALES')
		->setCellValue('A2', 'ESTE + NORTE '.$periodo)	
		->setCellValue('A3', 'Concepto')
		->setCellValue('B3', utf8_encode('Facturación'))
    	->setCellValue('C3', utf8_encode('Recaudación'));	
       
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("A")->setWidth(45);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("B")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("C")->setWidth(12);
	
	$fila = 4;
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAcometida($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Acometida')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAgua($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Agua')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAguaPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Agua Pozo')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAlca($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Alcantarillado')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesRecoIlegal($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Cargo por Rec. Ilegal.')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesChequeDev($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Cargos Cheq. Dev.')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesCargoDif($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Cargos diferidos')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesCorteReco($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Corte y rec.')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDanoMed($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Danos a medidores')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesExpPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Derecho de explotacion poz.')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDerInc($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Derecho de incorporacion agua ')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDerIncRes($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Derecho de incorporacion agua residuales')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesByPass($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Eliminacion by pass')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesEmpalme($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Empalme')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);

	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesEmpalmeAR($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Empalme agua residual')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesFianza($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Fianza')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesFianzaPres($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Fianza Presupuesto')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesIncPdc($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Incumplimiento PDC')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesInstRMed($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Inst.-R')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMantAcu($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Mant. Acuif.')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMantMed($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Mant. Med.')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMedidor($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Medidor')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMora($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Mora')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMultaCheqDev($proyecto, $periodo);
	while (oci_fetch($registros)) {;
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Multa cheque devuelto')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesOtrosC($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Otros cargos')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesOtrosS($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Otros servicios')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesPenaFraude($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Penalidad fraude')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesRevDF($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Reversion DF')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSaldoFavor($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Saldo a favor')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSolAcom($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Solicit. de acometida')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSupervision($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Supervision')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesTramTardia($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A'.$fila, 'Tramitacion tardia')
			->setCellValue('B'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	
	$fila = 4;
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAcometida1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAgua1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAguaPozo1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAlca1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesRecoIlegal1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesChequeDev1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesCargoDif1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesCorteReco1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDanoMed1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesExpPozo1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDerInc1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDerIncRes1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesByPass1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesEmpalme1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);

	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesEmpalmeAR1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesFianza1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesFianzaPres1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesIncPdc1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesInstRMed1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMantAcu1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMantMed1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMedidor1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMora1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMultaCheqDev1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesOtrosC1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesOtrosS1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesPenaFraude1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesRevDF1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSaldoFavor1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSolAcom1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSupervision1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesTramTardia1($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('C'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	// HOJA F-R NORTE
	
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('E2', 'NORTE '.$periodo)	
		->setCellValue('E3', 'Concepto')
		->setCellValue('F3', utf8_encode('Facturación'))
    	->setCellValue('G3', utf8_encode('Recaudación'));	
       
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("E")->setWidth(45);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("F")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("G")->setWidth(12);
	
	$fila = 4;
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAcometidaN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Acometida')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAguaN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Agua')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAguaPozoN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Agua Pozo')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAlcaN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Alcantarillado')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesRecoIlegalN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Cargo por Rec. Ilegal.')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesChequeDevN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Cargos Cheq. Dev.')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesCargoDifN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Cargos diferidos')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesCorteRecoN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Corte y rec.')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDanoMedN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Danos a medidores')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesExpPozoN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Derecho de explotacion poz.')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDerIncN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Derecho de incorporacion agua ')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDerIncResN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Derecho de incorporacion agua residuales')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesByPassN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Eliminacion by pass')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesEmpalmeN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Empalme')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);

	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesEmpalmeARN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Empalme agua residual')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesFianzaN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Fianza')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesFianzaPresN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Fianza Presupuesto')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesIncPdcN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Incumplimiento PDC')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesInstRMedN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Inst.-R')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMantAcuN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Mant. Acuif.')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMantMedN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Mant. Med.')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMedidorN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Medidor')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMoraN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Mora')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMultaCheqDevN($proyecto, $periodo);
	while (oci_fetch($registros)) {;
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Multa cheque devuelto')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesOtrosCN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Otros cargos')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesOtrosSN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Otros servicios')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesPenaFraudeN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Penalidad fraude')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesRevDFN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Reversion DF')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSaldoFavorN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Saldo a favor')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSolAcomN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Solicit. de acometida')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSupervisionN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Supervision')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesTramTardiaN($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, 'Tramitacion tardia')
			->setCellValue('F'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	
	$fila = 4;
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAcometida1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAgua1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAguaPozo1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAlca1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesRecoIlegal1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesChequeDev1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesCargoDif1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesCorteReco1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDanoMed1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesExpPozo1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDerInc1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDerIncRes1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesByPass1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesEmpalme1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);

	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesEmpalmeAR1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesFianza1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesFianzaPres1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesIncPdc1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesInstRMed1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMantAcu1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMantMed1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMedidor1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMora1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMultaCheqDev1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesOtrosC1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesOtrosS1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesPenaFraude1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesRevDF1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSaldoFavor1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSolAcom1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSupervision1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesTramTardia1N($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	
	
	// HOJA F-R ESTE
	
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('I2', 'ESTE '.$periodo)	
		->setCellValue('I3', 'Concepto')
		->setCellValue('J3', utf8_encode('Facturación'))
    	->setCellValue('K3', utf8_encode('Recaudación'));	
       
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("I")->setWidth(45);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("J")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("K")->setWidth(12);
	
	$fila = 4;
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAcometidaE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Acometida')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAguaE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Agua')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAguaPozoE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Agua Pozo')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAlcaE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Alcantarillado')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesRecoIlegalE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Cargo por Rec. Ilegal.')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesChequeDevE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Cargos Cheq. Dev.')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesCargoDifE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Cargos diferidos')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesCorteRecoE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Corte y rec.')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDanoMedE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Danos a medidores')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesExpPozoE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Derecho de explotacion poz.')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDerIncE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Derecho de incorporacion agua ')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDerIncResE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Derecho de incorporacion agua residuales')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesByPassE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Eliminacion by pass')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesEmpalmeE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Empalme')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);

	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesEmpalmeARE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Empalme agua residual')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesFianzaE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Fianza')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesFianzaPresE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Fianza Presupuesto')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesIncPdcE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Incumplimiento PDC')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesInstRMedE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Inst.-R')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMantAcuE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Mant. Acuif.')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMantMedE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Mant. Med.')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMedidorE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Medidor')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMoraE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Mora')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMultaCheqDevE($proyecto, $periodo);
	while (oci_fetch($registros)) {;
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Multa cheque devuelto')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesOtrosCE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Otros cargos')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesOtrosSE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Otros servicios')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesPenaFraudeE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Penalidad fraude')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesRevDFE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Reversion DF')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSaldoFavorE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Saldo a favor')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSolAcomE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Solicit. de acometida')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSupervisionE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Supervision')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesTramTardiaE($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('I'.$fila, 'Tramitacion tardia')
			->setCellValue('J'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	
	$fila = 4;
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAcometida1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAgua1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAguaPozo1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesAlca1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesRecoIlegal1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesChequeDev1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesCargoDif1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesCorteReco1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDanoMed1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesExpPozo1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDerInc1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesDerIncRes1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesByPass1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesEmpalme1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);

	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesEmpalmeAR1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesFianza1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesFianzaPres1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesIncPdc1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesInstRMed1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMantAcu1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMantMed1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('G'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMedidor1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMora1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesMultaCheqDev1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesOtrosC1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesOtrosS1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesPenaFraude1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesRevDF1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSaldoFavor1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSolAcom1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesSupervision1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->FRConceptoOficialesTramTardia1E($proyecto, $ano, $mes, $dia);
	while (oci_fetch($registros)) {
		$facturado=utf8_decode(oci_result($registros,"RECAUDADO"));
		$totalfacturadoaguaeste += $facturado;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('K'.$fila, $facturado);
		$fila++;
	}oci_free_statement($registros);
	
	//mostrar la hoja q se abrira	
	$objPHPExcel->setActiveSheetIndex(0);	
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename='Facturacion_Recaudo_Detallado_Por_Uso".$proyecto."_".$periodo.".xls'");
	header("Cache-Control: max-age=0");
	//ini_set('memory_limit','500M');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}
?>