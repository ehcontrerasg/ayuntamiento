<?php
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];

/*include_once '../../clases/class.reportes_gerenciales.php';
require_once '../clases/PHPExcel.php';*/

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


if($tipo=='MetMesAnt'){
	$periodo = $_POST['periodo'];
    $proyecto = $_POST['proyecto'];
	$anoini = substr($periodo,0,4) - 1;
	$mesini = substr($periodo,4,2);
	$perini = $anoini.$mesini;
	$perfin = $periodo;
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->
	getProperties()
		->setCreator("AceaSoft")
		->setLastModifiedBy("AceaSoft")
		->setTitle("Comparativo M3 Ano Anterior")
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
	
	$estiloLineaLateral = array(
		'borders' => array(
			'left' => array(
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
				'color' => array('rgb' => '000000')
			),
			'right' => array(
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
				'color' => array('rgb' => '000000')
			)
		)
	);
	
	$estiloNoLineas = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('rgb' => 'FFFFFF')
			)
		)
	);
	
	//VOLUMEN AGUA RED FACTURADA METROS CUBICOS EN UN PERIODO DADO
	////USUARIOS MEDIDOS AGUA RED
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('K1:Z99')->applyFromArray($estiloNoLineas);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A12:J13')->applyFromArray($estiloNoLineas);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A25:J26')->applyFromArray($estiloNoLineas);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A38:J99')->applyFromArray($estiloNoLineas);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4:J10')->applyFromArray($estiloLineaLateral);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A17:J23')->applyFromArray($estiloLineaLateral);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A30:J36')->applyFromArray($estiloLineaLateral);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:J1');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:A3');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:D2');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E2:G2');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H2:J2');
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:J1')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:A3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('B2:J2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('B3:J3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A11:J11')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:J1")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:A3")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("B2:J2")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("B3:J3")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A4:A10")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'VOLUMEN AGUA DE RED FACTURADA EN M3 - USUARIOS CON MEDIDOR  '.$proyecto.' PERIODO '.$periodo)
		->setCellValue('A2', 'Uso')
		->setCellValue('B2', 'Zona Este')
		->setCellValue('E2', 'Zona Norte')
    	->setCellValue('H2', 'Este + Norte')
        ->setCellValue('B3', $perini)
        ->setCellValue('C3', $perfin)
		->setCellValue('D3', 'Variacion (%)')
		->setCellValue('E3', $perini)
        ->setCellValue('F3', $perfin)
		->setCellValue('G3', 'Variacion (%)')
		->setCellValue('H3', $perini)
        ->setCellValue('I3', $perfin)
		->setCellValue('J3', 'Variacion (%)')
		->setCellValue('A4', 'Residencial')
		->setCellValue('A5', 'Mixto')
		->setCellValue('A6', 'Oficial')
		->setCellValue('A7', 'Industrial')
		->setCellValue('A8', 'Comercial')
		->setCellValue('A9', 'ZF Industrial')
		->setCellValue('A10', 'ZF Comercial');
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(12);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorMedidosEste($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		if($des_uso == 'Residencial') $fila = 4; 
		if($des_uso == 'Res. y Com.') $fila = 5; 
		if($des_uso == 'Oficial') $fila = 6; 
		if($des_uso == 'Industrial') $fila = 7;
		if($des_uso == 'Comercial') $fila = 8;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$fila, $cant1)
			->setCellValue('C'.$fila, $cant2)
			->setCellValue('D'.$fila, $variacion);
		$totalmedidoesteant += $cant1;
		$totalmedidoesteact += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorMedidosEsteZF($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 9; if($des_uso == 'ZF Comercial') $fila = 10; 
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$fila, $cant1)
			->setCellValue('C'.$fila, $cant2)
			->setCellValue('D'.$fila, $variacion);
		$totalmedidoesteant += $cant1;
		$totalmedidoesteact += $cant2;
	}oci_free_statement($registros);
	$totalvariacioneste = round((($totalmedidoesteact-$totalmedidoesteant)/$totalmedidoesteant)*100,2);
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A11:J11")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A11', 'Totales')
		->setCellValue('B11', $totalmedidoesteant)
		->setCellValue('C11', $totalmedidoesteact)
		->setCellValue('D11', $totalvariacioneste);	
		
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorMedidosNorte($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 4; if($des_uso == 'Res. y Com.') $fila = 5; if($des_uso == 'Oficial') $fila = 6; if($des_uso == 'Industrial') $fila = 7;
		if($des_uso == 'Comercial') $fila = 8;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, $cant1)
			->setCellValue('F'.$fila, $cant2)
			->setCellValue('G'.$fila, $variacion);
		$totalmedidonorteant += $cant1;
		$totalmedidonorteact += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorMedidosNorteZF($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 9; if($des_uso == 'ZF Comercial') $fila = 10; 
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, $cant1)
			->setCellValue('F'.$fila, $cant2)
			->setCellValue('G'.$fila, $variacion);
		$totalmedidonorteant += $cant1;
		$totalmedidonorteact += $cant2;
	}oci_free_statement($registros);
	if($totalmedidonorteant > 0){
		$totalvariacionnorte = round((($totalmedidonorteact-$totalmedidonorteant)/$totalmedidonorteant)*100,2);
	}
	else{
		$totalvariacionnorte = 0;
	}
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('E11', $totalmedidonorteant)
		->setCellValue('F11', $totalmedidonorteact)
		->setCellValue('G11', $totalvariacionnorte);
				
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorMedidosTotal($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 4; if($des_uso == 'Res. y Com.') $fila = 5; if($des_uso == 'Oficial') $fila = 6; if($des_uso == 'Industrial') $fila = 7;
		if($des_uso == 'Comercial') $fila = 8;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('H'.$fila, $cant1)
			->setCellValue('I'.$fila, $cant2)
			->setCellValue('J'.$fila, $variacion);
		$totalmedidototalant += $cant1;
		$totalmedidototalact += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorMedidosTotalZF($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 9; if($des_uso == 'ZF Comercial') $fila = 10; 
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('H'.$fila, $cant1)
			->setCellValue('I'.$fila, $cant2)
			->setCellValue('J'.$fila, $variacion);
		$totalmedidototalant += $cant1;
		$totalmedidototalact += $cant2;
	}oci_free_statement($registros);
	$totalvariaciontotal = round((($totalmedidototalact-$totalmedidototalant)/$totalmedidototalant)*100,2);
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('H11', $totalmedidototalant)
		->setCellValue('I11', $totalmedidototalact)
		->setCellValue('J11', $totalvariaciontotal);		
		
	//////	USUARIOS NO MEDIDOS AGUA RED
	
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A14:J14');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A15:A16');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B15:D15');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E15:G15');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H15:J15');
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A14:J14')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A15:A16')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('B15:J15')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('B16:J16')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A24:J24')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A14:J14")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A15:A16")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("B15:J15")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("B16:J16")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A17:A23")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A14', 'VOLUMEN AGUA DE RED FACTURADA EN M3 - USUARIOS SIN MEDIDOR  '.$proyecto.' PERIODO '.$periodo)
		->setCellValue('A15', 'Uso')
		->setCellValue('B15', 'Zona Este')
		->setCellValue('E15', 'Zona Norte')

    	->setCellValue('H15', 'Este + Norte')
        ->setCellValue('B16', $perini)
        ->setCellValue('C16', $perfin)
		->setCellValue('D16', 'Variacion (%)')
		->setCellValue('E16', $perini)
        ->setCellValue('F16', $perfin)
		->setCellValue('G16', 'Variacion (%)')
		->setCellValue('H16', $perini)
        ->setCellValue('I16', $perfin)
		->setCellValue('J16', 'Variacion (%)')
		->setCellValue('A17', 'Residencial')
		->setCellValue('A18', 'Mixto')
		->setCellValue('A19', 'Oficial')
		->setCellValue('A20', 'Industrial')
		->setCellValue('A21', 'Comercial')
		->setCellValue('A22', 'ZF Industrial')
		->setCellValue('A23', 'ZF Comercial');

	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorNoMedidosEste($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 17; if($des_uso == 'Res. y Com.') $fila = 18; if($des_uso == 'Oficial') $fila = 19; if($des_uso == 'Industrial') $fila = 20;
		if($des_uso == 'Comercial') $fila = 21;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$fila, $cant1)
			->setCellValue('C'.$fila, $cant2)
			->setCellValue('D'.$fila, $variacion);
		$totalnomedidoesteant += $cant1;
		$totalnomedidoesteact += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorNoMedidosEsteZF($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 22; if($des_uso == 'ZF Comercial') $fila = 23; 
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$fila, $cant1)
			->setCellValue('C'.$fila, $cant2)
			->setCellValue('D'.$fila, $variacion);
		$totalnomedidoesteant += $cant1;
		$totalnomedidoesteact += $cant2;
	}oci_free_statement($registros);
	$totalvariacionnoeste = round((($totalnomedidoesteact-$totalnomedidoesteant)/$totalnomedidoesteant)*100,2);
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A24:J24")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A24', 'Totales')
		->setCellValue('B24', $totalnomedidoesteant)
		->setCellValue('C24', $totalnomedidoesteact)
		->setCellValue('D24', $totalvariacionnoeste);	
		
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorNoMedidosNorte($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 17; if($des_uso == 'Res. y Com.') $fila = 18; if($des_uso == 'Oficial') $fila = 19; if($des_uso == 'Industrial') $fila = 20;
		if($des_uso == 'Comercial') $fila = 21;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, $cant1)
			->setCellValue('F'.$fila, $cant2)
			->setCellValue('G'.$fila, $variacion);
		$totalnomedidonorteant += $cant1;
		$totalnomedidonorteact += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorNoMedidosNorteZF($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 22; if($des_uso == 'ZF Comercial') $fila = 23; 
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, $cant1)
			->setCellValue('F'.$fila, $cant2)
			->setCellValue('G'.$fila, $variacion);
		$totalnomedidonorteant += $cant1;
		$totalnomedidonorteact += $cant2;
	}oci_free_statement($registros);
	if($totalnomedidonorteant > 0){
		$totalvariacionnonorte = round((($totalnomedidonorteact-$totalnomedidonorteant)/$totalnomedidonorteant)*100,2);
	}
	else{
		$totalvariacionnonorte = 0;
	}
	
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('E24', $totalnomedidonorteant)
		->setCellValue('F24', $totalnomedidonorteact)
		->setCellValue('G24', $totalvariacionnonorte);		
			
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorNoMedidosTotal($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 17; if($des_uso == 'Res. y Com.') $fila = 18; if($des_uso == 'Oficial') $fila = 19; if($des_uso == 'Industrial') $fila = 20;
		if($des_uso == 'Comercial') $fila = 21;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('H'.$fila, $cant1)
			->setCellValue('I'.$fila, $cant2)
			->setCellValue('J'.$fila, $variacion);
		$totalnomedidototalant += $cant1;
		$totalnomedidototalact += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorNoMedidosTotalZF($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 22; if($des_uso == 'ZF Comercial') $fila = 23; 
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('H'.$fila, $cant1)
			->setCellValue('I'.$fila, $cant2)
			->setCellValue('J'.$fila, $variacion);
		$totalnomedidototalant += $cant1;
		$totalnomedidototalact += $cant2;
	}oci_free_statement($registros);
	$totalvariacionnototal = round((($totalnomedidototalact-$totalnomedidototalant)/$totalnomedidototalant)*100,2);
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('H24', $totalnomedidototalant)
		->setCellValue('I24', $totalnomedidototalact)
		->setCellValue('J24', $totalvariacionnototal);		
		
	//////	USUARIOS TOTAL AGUA RED
	
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A27:J27');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A28:A29');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B28:D28');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E28:G28');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H28:J28');
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A27:J27')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A28:A29')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('B28:J28')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('B29:J29')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A37:J37')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A27:J29")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A30:A36")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A27', 'VOLUMEN AGUA DE RED FACTURADA EN M3 - TOTAL  '.$proyecto.' PERIODO '.$periodo)
		->setCellValue('A28', 'Uso')
		->setCellValue('B28', 'Zona Este')
		->setCellValue('E28', 'Zona Norte')
    	->setCellValue('H28', 'Este + Norte')
        ->setCellValue('B29', $perini)
        ->setCellValue('C29', $perfin)
		->setCellValue('D29', 'Variacion (%)')
		->setCellValue('E29', $perini)
        ->setCellValue('F29', $perfin)
		->setCellValue('G29', 'Variacion (%)')
		->setCellValue('H29', $perini)
        ->setCellValue('I29', $perfin)
		->setCellValue('J29', 'Variacion (%)')
		->setCellValue('A30', 'Residencial')
		->setCellValue('A31', 'Mixto')
		->setCellValue('A32', 'Oficial')
		->setCellValue('A33', 'Industrial')
		->setCellValue('A34', 'Comercial')
		->setCellValue('A35', 'ZF Industrial')
		->setCellValue('A36', 'ZF Comercial');
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorTotalEste($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 30; if($des_uso == 'Res. y Com.') $fila = 31; if($des_uso == 'Oficial') $fila = 32; if($des_uso == 'Industrial') $fila = 33;
		if($des_uso == 'Comercial') $fila = 34;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$fila, $cant1)
			->setCellValue('C'.$fila, $cant2)
			->setCellValue('D'.$fila, $variacion);
		$totalmedidoestetotant += $cant1;
		$totalmedidoestetotact += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorTotalEsteZF($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 35; if($des_uso == 'ZF Comercial') $fila = 36; 
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$fila, $cant1)
			->setCellValue('C'.$fila, $cant2)
			->setCellValue('D'.$fila, $variacion);
		$totalmedidoestetotant += $cant1;
		$totalmedidoestetotact += $cant2;
		
	}oci_free_statement($registros);
	$totalvariaciontoteste = round((($totalmedidoestetotact-$totalmedidoestetotant)/$totalmedidoestetotant)*100,2);
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A37:J37")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A37', 'Totales')
		->setCellValue('B37', $totalmedidoestetotant)
		->setCellValue('C37', $totalmedidoestetotact)
		->setCellValue('D37', $totalvariaciontoteste);	
		
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorTotalNorte($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 30; if($des_uso == 'Res. y Com.') $fila = 31; if($des_uso == 'Oficial') $fila = 32; if($des_uso == 'Industrial') $fila = 33;
		if($des_uso == 'Comercial') $fila = 34;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, $cant1)
			->setCellValue('F'.$fila, $cant2)
			->setCellValue('G'.$fila, $variacion);
		$totalmedidonortetotant += $cant1;
		$totalmedidonortetotact += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorTotalNorteZF($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		if($des_uso == 'ZF Industrial') $fila = 35; if($des_uso == 'ZF Comercial') $fila = 36;  
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, $cant1)
			->setCellValue('F'.$fila, $cant2)
			->setCellValue('G'.$fila, $variacion);
		$totalmedidonortetotant += $cant1;
		$totalmedidonortetotact += $cant2;
		
	}oci_free_statement($registros);
	
	if($totalmedidonortetotant > 0){
		$totalvariacionnortetot = round((($totalmedidonortetotact-$totalmedidonortetotant)/$totalmedidonortetotant)*100,2);
	}
	else{
		$totalvariacionnortetot = 0;
	}
	
	
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('E37', $totalmedidonortetotant)
		->setCellValue('F37', $totalmedidonortetotact)
		->setCellValue('G37', $totalvariacionnortetot);		
			
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorTotalTotal($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 30; if($des_uso == 'Res. y Com.') $fila = 31; if($des_uso == 'Oficial') $fila = 32; if($des_uso == 'Industrial') $fila = 33;
		if($des_uso == 'Comercial') $fila = 34;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('H'.$fila, $cant1)
			->setCellValue('I'.$fila, $cant2)
			->setCellValue('J'.$fila, $variacion);
		$totalmedidototaltotant += $cant1;
		$totalmedidototaltotact += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorTotalTotalZF($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 35; if($des_uso == 'ZF Comercial') $fila = 36; 
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('H'.$fila, $cant1)
			->setCellValue('I'.$fila, $cant2)
			->setCellValue('J'.$fila, $variacion);
		$totalmedidototaltotant += $cant1;
		$totalmedidototaltotact += $cant2;
		
	}oci_free_statement($registros);
	$totalvariaciontotaltot = round((($totalmedidototaltotact-$totalmedidototaltotant)/$totalmedidototaltotant)*100,2);
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('H37', $totalmedidototaltotant)
		->setCellValue('I37', $totalmedidototaltotact)
		->setCellValue('J37', $totalvariaciontotaltot);				
	$objPHPExcel->getActiveSheet()->setTitle('Agua Red');	
	//$objPHPExcel->setActiveSheetIndex(0);	
	
	//VOLUMEN AGUA POZO FACTURADA METROS CUBICOS EN UN PERIODO DADO
	////USUARIOS MEDIDOS AGUA POZO
	
	$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Agua Pozo');
	$objPHPExcel->addSheet($myWorkSheet, 1);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('K1:Z99')->applyFromArray($estiloNoLineas);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A12:J13')->applyFromArray($estiloNoLineas);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A25:J26')->applyFromArray($estiloNoLineas);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A38:J99')->applyFromArray($estiloNoLineas);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A4:J10')->applyFromArray($estiloLineaLateral);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A17:J23')->applyFromArray($estiloLineaLateral);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A30:J36')->applyFromArray($estiloLineaLateral);
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:J1');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A2:A3');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('B2:D2');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('E2:G2');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('H2:J2');
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A1:J1')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A2:A3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('B2:J2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('B3:J3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A11:J11')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:J1")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A2:A3")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("B2:J2")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("B3:J3")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A4:A10")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('A1', 'VOLUMEN AGUA DE POZO FACTURADA EN M3 - USUARIOS CON MEDIDOR  '.$proyecto.' PERIODO '.$periodo)
		->setCellValue('A2', 'Uso')
		->setCellValue('B2', 'Zona Este')
		->setCellValue('E2', 'Zona Norte')
    	->setCellValue('H2', 'Este + Norte')
        ->setCellValue('B3', $perini)
        ->setCellValue('C3', $perfin)
		->setCellValue('D3', 'Variacion (%)')
		->setCellValue('E3', $perini)
        ->setCellValue('F3', $perfin)
		->setCellValue('G3', 'Variacion (%)')
		->setCellValue('H3', $perini)
        ->setCellValue('I3', $perfin)
		->setCellValue('J3', 'Variacion (%)')
		->setCellValue('A4', 'Residencial')
		->setCellValue('A5', 'Mixto')
		->setCellValue('A6', 'Oficial')
		->setCellValue('A7', 'Industrial')
		->setCellValue('A8', 'Comercial')
		->setCellValue('A9', 'ZF Industrial')
		->setCellValue('A10', 'ZF Comercial');
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("A")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("B")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("C")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("D")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("E")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("F")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("G")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("H")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("I")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("J")->setWidth(12);

	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorMedidosEstePozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 4; 
		if($des_uso == 'Res. y Com.') $fila = 5; 
		if($des_uso == 'Oficial') $fila = 6; 
		if($des_uso == 'Industrial') $fila = 7;
		if($des_uso == 'Comercial') $fila = 8;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('B'.$fila, $cant1)
			->setCellValue('C'.$fila, $cant2)
			->setCellValue('D'.$fila, $variacion);
		$totalmedidoesteantpz += $cant1;
		$totalmedidoesteactpz += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorMedidosEsteZFPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 9; if($des_uso == 'ZF Comercial') $fila = 10; 
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('B'.$fila, $cant1)
			->setCellValue('C'.$fila, $cant2)
			->setCellValue('D'.$fila, $variacion);
		$totalmedidoesteantpz += $cant1;
		$totalmedidoesteactpz += $cant2;
	}oci_free_statement($registros);
	$totalvariacionestepz = round((($totalmedidoesteactpz-$totalmedidoesteantpz)/$totalmedidoesteantpz)*100,2);
	
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A11:J11")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('A11', 'Totales')
		->setCellValue('B11', $totalmedidoesteantpz)
		->setCellValue('C11', $totalmedidoesteactpz)
		->setCellValue('D11', $totalvariacionestepz);	
		
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorMedidosNortePozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 4; if($des_uso == 'Res. y Com.') $fila = 5; if($des_uso == 'Oficial') $fila = 6; if($des_uso == 'Industrial') $fila = 7;
		if($des_uso == 'Comercial') $fila = 8;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, $cant1)
			->setCellValue('F'.$fila, $cant2)
			->setCellValue('G'.$fila, $variacion);
		$totalmedidonorteantpz += $cant1;
		$totalmedidonorteactpz += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorMedidosNorteZFPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 9; if($des_uso == 'ZF Comercial') $fila = 10; 
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, $cant1)
			->setCellValue('F'.$fila, $cant2)
			->setCellValue('G'.$fila, $variacion);
		$totalmedidonorteantpz += $cant1;
		$totalmedidonorteactpz += $cant2;
	}oci_free_statement($registros);
	
	if($totalmedidonorteantpz > 0){
		$totalvariacionnortepz = round((($totalmedidonorteactpz-$totalmedidonorteantpz)/$totalmedidonorteantpz)*100,2);
	}
	else{
		$totalvariacionnortepz = 0;
	}
	
	
	
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('E11', $totalmedidonorteantpz)
		->setCellValue('F11', $totalmedidonorteactpz)
		->setCellValue('G11', $totalvariacionnortepz);
				
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorMedidosTotalPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 4; if($des_uso == 'Res. y Com.') $fila = 5; if($des_uso == 'Oficial') $fila = 6; if($des_uso == 'Industrial') $fila = 7;
		if($des_uso == 'Comercial') $fila = 8;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('H'.$fila, $cant1)
			->setCellValue('I'.$fila, $cant2)
			->setCellValue('J'.$fila, $variacion);
		$totalmedidototalantpz += $cant1;
		$totalmedidototalactpz += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorMedidosTotalZFPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 9; if($des_uso == 'ZF Comercial') $fila = 10; 
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('H'.$fila, $cant1)
			->setCellValue('I'.$fila, $cant2)
			->setCellValue('J'.$fila, $variacion);
		$totalmedidototalantpz += $cant1;
		$totalmedidototalactpz += $cant2;
	}oci_free_statement($registros);
	$totalvariaciontotalpz = round((($totalmedidototalactpz-$totalmedidototalantpz)/$totalmedidototalantpz)*100,2);
	
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('H11', $totalmedidototalantpz)
		->setCellValue('I11', $totalmedidototalactpz)
		->setCellValue('J11', $totalvariaciontotalpz);		
		
	//////	USUARIOS NO MEDIDOS AGUA POZO
	
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A14:J14');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A15:A16');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('B15:D15');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('E15:G15');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('H15:J15');
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A14:J14')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A15:A16')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('B15:J15')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('B16:J16')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A24:J24')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A14:J14")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A15:A16")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("B15:J15")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("B16:J16")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A17:A23")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('A14', 'VOLUMEN AGUA DE POZO FACTURADA EN M3 - USUARIOS SIN MEDIDOR  '.$proyecto.' PERIODO '.$periodo)
		->setCellValue('A15', 'Uso')
		->setCellValue('B15', 'Zona Este')
		->setCellValue('E15', 'Zona Norte')
    	->setCellValue('H15', 'Este + Norte')
        ->setCellValue('B16', $perini)
        ->setCellValue('C16', $perfin)
		->setCellValue('D16', 'Variacion (%)')
		->setCellValue('E16', $perini)
        ->setCellValue('F16', $perfin)
		->setCellValue('G16', 'Variacion (%)')
		->setCellValue('H16', $perini)
        ->setCellValue('I16', $perfin)
		->setCellValue('J16', 'Variacion (%)')
		->setCellValue('A17', 'Residencial')
		->setCellValue('A18', 'Mixto')
		->setCellValue('A19', 'Oficial')
		->setCellValue('A20', 'Industrial')
		->setCellValue('A21', 'Comercial')
		->setCellValue('A22', 'ZF Industrial')
		->setCellValue('A23', 'ZF Comercial');

	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorNoMedidosEstePozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 17; if($des_uso == 'Res. y Com.') $fila = 18; if($des_uso == 'Oficial') $fila = 19; if($des_uso == 'Industrial') $fila = 20;
		if($des_uso == 'Comercial') $fila = 21;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('B'.$fila, $cant1)
			->setCellValue('C'.$fila, $cant2)
			->setCellValue('D'.$fila, $variacion);
		$totalnomedidoesteantpz += $cant1;
		$totalnomedidoesteactpz += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorNoMedidosEsteZFPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 22; if($des_uso == 'ZF Comercial') $fila = 23; 
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('B'.$fila, $cant1)
			->setCellValue('C'.$fila, $cant2)
			->setCellValue('D'.$fila, $variacion);
		$totalnomedidoesteantpz += $cant1;
		$totalnomedidoesteactpz += $cant2;
	}oci_free_statement($registros);
	$totalvariacionnoestepz = round((($totalnomedidoesteactpz-$totalnomedidoesteantpz)/$totalnomedidoesteantpz)*100,2);
	
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A24:J24")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('A24', 'Totales')
		->setCellValue('B24', $totalnomedidoesteantpz)
		->setCellValue('C24', $totalnomedidoesteactpz)
		->setCellValue('D24', $totalvariacionnoestepz);	
		
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorNoMedidosNortePozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 17; if($des_uso == 'Res. y Com.') $fila = 18; if($des_uso == 'Oficial') $fila = 19; if($des_uso == 'Industrial') $fila = 20;
		if($des_uso == 'Comercial') $fila = 21;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, $cant1)
			->setCellValue('F'.$fila, $cant2)
			->setCellValue('G'.$fila, $variacion);
		$totalnomedidonorteantpz += $cant1;
		$totalnomedidonorteactpz += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorNoMedidosNorteZFPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 22; if($des_uso == 'ZF Comercial') $fila = 23; 
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, $cant1)
			->setCellValue('F'.$fila, $cant2)
			->setCellValue('G'.$fila, $variacion);
		$totalnomedidonorteantpz += $cant1;
		$totalnomedidonorteactpz += $cant2;
	}oci_free_statement($registros);
	
	if($totalnomedidonorteantpz > 0){
		$totalvariacionnonortepz = round((($totalnomedidonorteactpz-$totalnomedidonorteantpz)/$totalnomedidonorteantpz)*100,2);
	}
	else{
		$totalvariacionnonortepz = 0;
	}
	
	
	
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('E24', $totalnomedidonorteantpz)
		->setCellValue('F24', $totalnomedidonorteactpz)
		->setCellValue('G24', $totalvariacionnonortepz);		
			
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorNoMedidosTotalPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 17; if($des_uso == 'Res. y Com.') $fila = 18; if($des_uso == 'Oficial') $fila = 19; if($des_uso == 'Industrial') $fila = 20;
		if($des_uso == 'Comercial') $fila = 21;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('H'.$fila, $cant1)
			->setCellValue('I'.$fila, $cant2)
			->setCellValue('J'.$fila, $variacion);
		$totalnomedidototalantpz += $cant1;
		$totalnomedidototalactpz += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorNoMedidosTotalZFPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 22; if($des_uso == 'ZF Comercial') $fila = 23; 
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('H'.$fila, $cant1)
			->setCellValue('I'.$fila, $cant2)
			->setCellValue('J'.$fila, $variacion);
		$totalnomedidototalantpz += $cant1;
		$totalnomedidototalactpz += $cant2;
	}oci_free_statement($registros);
	$totalvariacionnototalpz = round((($totalnomedidototalactpz-$totalnomedidototalantpz)/$totalnomedidototalantpz)*100,2);
	
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('H24', $totalnomedidototalantpz)
		->setCellValue('I24', $totalnomedidototalactpz)
		->setCellValue('J24', $totalvariacionnototalpz);		
		
	//////	USUARIOS TOTAL AGUA POZO
	
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A27:J27');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A28:A29');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('B28:D28');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('E28:G28');
	$objPHPExcel->setActiveSheetIndex(1)->mergeCells('H28:J28');
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A27:J27')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A28:A29')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('B28:J28')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('B29:J29')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle('A37:J37')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A27:J29")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A30:A36")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('A27', 'VOLUMEN AGUA DE POZO FACTURADA EN M3 - TOTAL  '.$proyecto.' PERIODO '.$periodo)
		->setCellValue('A28', 'Uso')
		->setCellValue('B28', 'Zona Este')
		->setCellValue('E28', 'Zona Norte')
    	->setCellValue('H28', 'Este + Norte')
        ->setCellValue('B29', $perini)
        ->setCellValue('C29', $perfin)
		->setCellValue('D29', 'Variacion (%)')
		->setCellValue('E29', $perini)
        ->setCellValue('F29', $perfin)
		->setCellValue('G29', 'Variacion (%)')
		->setCellValue('H29', $perini)
        ->setCellValue('I29', $perfin)
		->setCellValue('J29', 'Variacion (%)')
		->setCellValue('A30', 'Residencial')
		->setCellValue('A31', 'Mixto')
		->setCellValue('A32', 'Oficial')
		->setCellValue('A33', 'Industrial')
		->setCellValue('A34', 'Comercial')
		->setCellValue('A35', 'ZF Industrial')
		->setCellValue('A36', 'ZF Comercial');
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorTotalEstePozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 30; if($des_uso == 'Res. y Com.') $fila = 31; if($des_uso == 'Oficial') $fila = 32; if($des_uso == 'Industrial') $fila = 33;
		if($des_uso == 'Comercial') $fila = 34;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('B'.$fila, $cant1)
			->setCellValue('C'.$fila, $cant2)
			->setCellValue('D'.$fila, $variacion);
		$totalmedidoestetotantpz += $cant1;
		$totalmedidoestetotactpz += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorTotalEsteZFPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 35; if($des_uso == 'ZF Comercial') $fila = 36; 

		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('B'.$fila, $cant1)
			->setCellValue('C'.$fila, $cant2)
			->setCellValue('D'.$fila, $variacion);
		$totalmedidoestetotantpz += $cant1;
		$totalmedidoestetotactpz += $cant2;
		
	}oci_free_statement($registros);
	
	if($totalmedidoestetotantpz > 0){
		$totalvariaciontotestepz = round((($totalmedidoestetotactpz-$totalmedidoestetotantpz)/$totalmedidoestetotantpz)*100,2);
	}
	else{
		$totalvariaciontotestepz = 0;
	}
	
	
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A37:J37")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('A37', 'Totales')
		->setCellValue('B37', $totalmedidoestetotantpz)
		->setCellValue('C37', $totalmedidoestetotactpz)
		->setCellValue('D37', $totalvariaciontotestepz);	
		
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorTotalNortePozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 30; if($des_uso == 'Res. y Com.') $fila = 31; if($des_uso == 'Oficial') $fila = 32; if($des_uso == 'Industrial') $fila = 33;
		if($des_uso == 'Comercial') $fila = 34;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, $cant1)
			->setCellValue('F'.$fila, $cant2)
			->setCellValue('G'.$fila, $variacion);
		$totalmedidonortetotantpz += $cant1;
		$totalmedidonortetotactpz += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorTotalNorteZFPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 35; if($des_uso == 'ZF Comercial') $fila = 36;  
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('E'.$fila, $cant1)
			->setCellValue('F'.$fila, $cant2)
			->setCellValue('G'.$fila, $variacion);
		$totalmedidonortetotantpz += $cant1;
		$totalmedidonortetotactpz += $cant2;
		
	}oci_free_statement($registros);
	
	if($totalmedidonortetotantpz > 0){
		$totalvariacionnortetotpz = round((($totalmedidonortetotactpz-$totalmedidonortetotantpz)/$totalmedidonortetotantpz)*100,2);
	}
	else{
		$totalvariacionnortetotpz = 0;
	}
	
	
	
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('E37', $totalmedidonortetotantpz)
		->setCellValue('F37', $totalmedidonortetotactpz)
		->setCellValue('G37', $totalvariacionnortetotpz);		
			
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorTotalTotalPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'Residencial') $fila = 30; if($des_uso == 'Res. y Com.') $fila = 31; if($des_uso == 'Oficial') $fila = 32; if($des_uso == 'Industrial') $fila = 33;
		if($des_uso == 'Comercial') $fila = 34;
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('H'.$fila, $cant1)
			->setCellValue('I'.$fila, $cant2)
			->setCellValue('J'.$fila, $variacion);
		$totalmedidototaltotantpz += $cant1;
		$totalmedidototaltotactpz += $cant2;
	}oci_free_statement($registros);
	
	$c=new ReportesGerencia();
	$registros=$c->MetrosAnoAnteriorTotalTotalZFPozo($proyecto, $periodo);
	while (oci_fetch($registros)) {
		$des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
		$cant1=utf8_decode(oci_result($registros,"'$perini'"));
		$cant2=utf8_decode(oci_result($registros,"'$perfin'"));
		if($cant1 > 0) 
			$variacion = round((($cant2-$cant1)/$cant1)*100,2);
		else $variacion = NULL;
		if($des_uso == 'ZF Industrial') $fila = 35; if($des_uso == 'ZF Comercial') $fila = 36; 
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('H'.$fila, $cant1)
			->setCellValue('I'.$fila, $cant2)
			->setCellValue('J'.$fila, $variacion);
		$totalmedidototaltotantpz += $cant1;
		$totalmedidototaltotactpz += $cant2;
	}oci_free_statement($registros);
	$totalvariaciontotaltotpz = round((($totalmedidototaltotactpz-$totalmedidototaltotantpz)/$totalmedidototaltotantpz)*100,2);
	
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('H37', $totalmedidototaltotantpz)
		->setCellValue('I37', $totalmedidototaltotactpz)
		->setCellValue('J37', $totalvariaciontotaltotpz);				
	
	$objPHPExcel->setActiveSheetIndex(0);	
	

	/*header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename='Comparativo_M3_Año_Anterior_".$proyecto."_".$periodo.".xls'");
	header("Cache-Control: max-age=0");
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;*/
    //mostrar la hoja que se abrira
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle("Comparativo_M3_Agno_Anterior");
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Comparativo_M3_Agno_Anterior_".$proyecto."_".$periodo.".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;
}
?>