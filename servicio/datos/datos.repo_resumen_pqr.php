<?php
//mb_internal_encoding("UTF-8");
session_start();
include '../clases/classPqrs.php';
$tipo = $_POST['tip'];
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
    $datos = $l->seleccionaAcueducto($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selMot'){
    
    $l=new PQRs();
    $datos = $l->seleccionaMotivosPqr();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($_GET['tip']=='autComZon') {
    $proyecto = $_GET['proyecto'];

	// Here, we will get user input data and trim it, if any space in that user input data
    $user_input = trim($_REQUEST['term']);

	// Define two array, one is to store output data and other is for display
    $display_json = array();
    $json_arr = array();

    $user_input = strtoupper($user_input);

    $l= new PQRs();
    $stid=$l->obtieneZonAuto($proyecto,$user_input);
    oci_execute($stid, OCI_DEFAULT);

    while (oci_fetch($stid)) {

        $json_arr["id"] = oci_result($stid, 'ID_ZONA');
        $json_arr["value"] = oci_result($stid, 'ID_ZONA');
        $json_arr["label"] = oci_result($stid, 'ID_ZONA');
        array_push($display_json, $json_arr);

    }
    oci_free_statement($stid);

    $jsonWrite = json_encode($display_json); //encode that search data
    print $jsonWrite;
}


if($_GET['tip']=='resPagPan'){
	$proyecto = $_GET['proyecto'];
	$zonini = $_GET['zonini'];
	$zonfin = $_GET['zonfin'];
	$secini = $_GET['secini'];
	$secfin = $_GET['secfin'];
	$rutini = $_GET['rutini'];
	$rutfin = $_GET['rutfin'];
	$ofiini = $_GET['ofiini'];
	$ofifin = $_GET['ofifin'];
	$recini = $_GET['recini'];
	$recfin = $_GET['recfin'];
	$motivo = $_GET['motivo'];
	$fecinirad = $_GET['fecinirad'];
	$fecfinrad = $_GET['fecfinrad'];
	$fecinires = $_GET['fecinires'];
	$fecfinres = $_GET['fecfinres'];
	$tipo_resol = $_GET['tipo_resol'];
	$tipo_estado = $_GET['tipo_estado'];

	$page = $_POST['page'];
	$rp = $_POST['rp'];
	$sortname = $_POST['sortname'];
	$sortorder = $_POST['sortorder'];
	$query = $_POST['query'];
	$qtype = $_POST['qtype'];

	if (!$page) $page = 1;
	if (!$rp) $rp = 30;

	$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
	$start = ($page) * $rp;  // MIN_ROW_TO_FETCH

	$l=new PQRs();
	$valores=$l->CantidadDatosResumenPqr($proyecto,$zonini,$zonfin,$secini,$secfin,$rutini,$rutfin,$tipo_resol,$tipo_estado,$ofiini,$ofifin,$motivo,$fecinirad,$fecfinrad,$fecinires,$fecfinres,$recini,$recfin,$start,$end,$where);
	while (oci_fetch($valores)) {
		$total = oci_result($valores, 'CANTIDAD');
	}oci_free_statement($valores);

	$l=new PQRs();
	$registros=$l->datosResumenPqr($proyecto,$zonini,$zonfin,$secini,$secfin,$rutini,$rutfin,$tipo_resol,$tipo_estado,$ofiini,$ofifin,$motivo,$fecinirad,$fecfinrad,$fecinires,$fecfinres,$recini,$recfin,$start,$end,$where);

	$json = "";
	$json .= "{\n";
	$json .= "page: $page,\n";
	$json .= "total: $total,\n";
	$json .= "rows: [";
	$rc = false;
	while (oci_fetch($registros)) {
		$numero=oci_result($registros, 'RNUM');
		$codigo_pqr = oci_result($registros, 'CODIGO_PQR');
		$fecha_pqr = oci_result($registros, 'FECRAD');
        $motivo = oci_result($registros, 'DESC_MOTIVO_REC');
        $cod_inm = oci_result($registros, 'COD_INMUEBLE');
        $zona = oci_result($registros, 'ID_ZONA');
		$gerencia = oci_result($registros, 'GERENCIA');
		$nom_cliente = oci_result($registros, 'NOM_CLIENTE');
		$medio_rec = oci_result($registros, 'MEDIO_REC_PQR');
		$oficina = oci_result($registros, 'COD_VIEJO');
		$descripcion = oci_result($registros, 'DESCRIPCION');
		$fecha_diag = oci_result($registros, 'FECDIAG');
		$diagnostico = oci_result($registros, 'DIAGNOSTICO');
		$fecha_resol = oci_result($registros, 'FECRESOL');
		$resolucion = oci_result($registros, 'RESOLUCION');
		$respuesta = oci_result($registros, 'RESPUESTA');

		$longdesc = strlen($descripcion);
		$items = round($longdesc / 27)+1;
		$linf = 0;
		$lsup = 27;

		$longresp = strlen($respuesta);
		$itemsr = round($longresp / 28)+1;
		$linfr = 0;
		$lsupr = 28;

		if ($rc) $json .= ",";
		$json .= "\n{";
		$json .= "id:'".$codigo_pqr."',";
		$json .= "cell:['".$numero."'";
		$json .= ",'".$codigo_pqr."'";
		$json .= ",'".$fecha_pqr."'";
		$json .= ",'".addslashes($cod_inm)."'";
		$json .= ",'".addslashes($nom_cliente)."'";
		$json .= ",'".addslashes($medio_rec)."'";
		$json .= ",'".addslashes($zona)."'";
		$json .= ",'".addslashes($gerencia)."'";
		$json .= ",'".addslashes($oficina)."'";
		if($longdesc > 0){
		$json .= ",'";
			for($i=0; $i<$items; $i++){
				$cadena[$i] = substr($descripcion, $linf, $lsup);
				$linf += 27;
				$json .= $cadena[$i]. '<br />';
		  	}
			$json .= "'";
		}
		$json .= ",'".addslashes($fecha_diag)."'";
		$json .= ",'".addslashes($diagnostico)."'";
		$json .= ",'".addslashes($fecha_resol)."'";
		$json .= ",'".addslashes($motivo)."'";
		$json .= ",'".addslashes($resolucion)."'";
		if($longresp > 0){
		$json .= ",'";
			for($i=0; $i<$itemsr; $i++){
				$cadenar[$i] = substr($respuesta, $linfr, $lsupr);
				$linfr += 28;
				$json .= $cadenar[$i]. '<br />';
		  	}
			$json .= "'";
		}
		else{
			$json .= ",'".addslashes($respuesta)."'";
		}
		$json .= "]}";
		$rc = true;
	}
	$json .= "\n]\n";
	$json .= "}";
	echo $json;
}

/*if($tipo=='ResPqrExl'){

	require_once '../clases/PHPExcel.php';
	$proyecto = $_POST['proyecto'];
	$zonini = $_POST['zonini'];
	$zonfin = $_POST['zonfin'];
	$secini = $_POST['secini'];
	$secfin = $_POST['secfin'];
	$rutini = $_POST['rutini'];
	$rutfin = $_POST['rutfin'];
	$ofiini = $_POST['ofiini'];
	$ofifin = $_POST['ofifin'];
	$recini = $_POST['recini'];
	$recfin = $_POST['recfin'];
	$motivo = $_POST['motivo'];
	$fecinirad = $_POST['fecinirad'];
	$fecfinrad = $_POST['fecfinrad'];
	$fecinires = $_POST['fecinires'];
	$fecfinres = $_POST['fecfinres'];
	$tipo_resol = $_POST['tipo_resol'];
	$tipo_estado = $_POST['tipo_estado'];

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->
	getProperties()
		->setCreator("AceaSoft")
		->setLastModifiedBy("AceaSoft")
		->setTitle("Resumen PQRs")
		->setSubject("")
		->setDescription("Documento generado con PHPExcel")
		->setKeywords("usuarios phpexcel")
		->setCategory("Reportes Servicio al Cliente");

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

	//HOJA RESUMEN PQRS

	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:P1');

	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:P2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:P2")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'REPORTE RESUMEN PQRs ')
		->setCellValue('A2', 'No')
		->setCellValue('B2', 'CODIGO PQR')
		->setCellValue('C2', 'FECHA RADICACION')
    	->setCellValue('D2', 'INMUEBLE')
        ->setCellValue('E2', 'CLIENTE')
        ->setCellValue('F2', 'MED. RECEP.')
		->setCellValue('G2', 'ZONA')
        ->setCellValue('H2', 'GERENCIA')
        ->setCellValue('I2', 'OFICINA')
		->setCellValue('J2', 'DESCRIPCION')
        ->setCellValue('K2', 'FECHA DIAG.')
        ->setCellValue('L2', 'DIAGNOSTICO')
		->setCellValue('M2', 'FECHA RESOL.')
		->setCellValue('N2', 'TIPO')
		->setCellValue('O2', 'RESOLUCION')
		->setCellValue('P2', 'RESPUESTA');
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(5);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(13);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(18);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(22);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(80);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(18);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(40);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(18);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(20);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(30);



	$l=new PQRs();
	$registros=$l->datosResumenPqr($proyecto,$zonini,$zonfin,$secini,$secfin,$rutini,$rutfin,$tipo_resol,$tipo_estado,$ofiini,$ofifin,$motivo,$fecinirad,$fecfinrad,$fecinires,$fecfinres,$recini,$recfin,10000,0,$where);
	$fila = 3;
	while (oci_fetch($registros)) {
		$numero=oci_result($registros, 'RNUM');
		$codigo_pqr = oci_result($registros, 'CODIGO_PQR');
		$fecha_pqr = oci_result($registros, 'FECRAD');
		$cod_inm = oci_result($registros, 'COD_INMUEBLE');
		$nom_cliente = oci_result($registros, 'NOM_CLIENTE');
		$medio_rec = oci_result($registros, 'MEDIO_REC_PQR');
		$zona = oci_result($registros, 'ID_ZONA');
		$gerencia = oci_result($registros, 'GERENCIA');
		$oficina = oci_result($registros, 'COD_VIEJO');
		$descripcion = oci_result($registros, 'DESCRIPCION');
		$fecha_diag = oci_result($registros, 'FECDIAG');
		$diagnostico = oci_result($registros, 'DIAGNOSTICO');
		$fecha_resol = oci_result($registros, 'FECRESOL');
		$motivo = oci_result($registros, 'DESC_MOTIVO_REC');
		$resolucion = oci_result($registros, 'RESOLUCION');
		$respuesta = oci_result($registros, 'RESPUESTA');

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $numero)
			->setCellValue('B'.$fila, $codigo_pqr)
			->setCellValue('C'.$fila, $fecha_pqr)
			->setCellValue('D'.$fila, $cod_inm)
			->setCellValue('E'.$fila, $nom_cliente)
			->setCellValue('F'.$fila, $medio_rec)
			->setCellValue('G'.$fila, $zona)
			->setCellValue('H'.$fila, $gerencia)
			->setCellValue('I'.$fila, $oficina)
			->setCellValue('J'.$fila, $descripcion)
			->setCellValue('K'.$fila, $fecha_diag)
			->setCellValue('L'.$fila, $diagnostico)
			->setCellValue('M'.$fila, $fecha_resol)
			->setCellValue('N'.$fila, $motivo)
			->setCellValue('O'.$fila, $resolucion)
			->setCellValue('P'.$fila, $respuesta);
		$fila++;
	}oci_free_statement($registros);

	//mostrafr la hoja q se abrira
	$objPHPExcel->setActiveSheetIndex(0);

	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename='Reporte_PQRs_".$proyecto.".xls'");
	header("Cache-Control: max-age=0");
	ini_set('memory_limit','250M');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;

	
}*/
?>
