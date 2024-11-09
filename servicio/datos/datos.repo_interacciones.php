<?php
session_start();
	include '../clases/classPqrs.php';
	$coduser = $_SESSION['codigo'];
	$proyecto = $_GET['proyecto'];
	$ofiini = $_GET['ofiini'];
	$ofifin = $_GET['ofifin'];
	$fecinirad = $_GET['fecinirad'];
	$fecfinrad = $_GET['fecfinrad'];
	$login = $_GET['login'];

	$page = $_POST['page'];
	$rp = $_POST['rp'];
	$sortname = $_POST['sortname'];
	$sortorder = $_POST['sortorder'];
	$query = $_POST['query'];
	$qtype = $_POST['qtype'];

	if (!$sortname) $sortname = "I.ID_PROCESO";
	if (!$sortorder) $sortorder = "DESC";

	$sort = "ORDER BY $sortname $sortorder";

	if (!$page) $page = 1;
	if (!$rp) $rp = 30;

	$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
	$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
	//$fname ="P.CODIGO_PQR";
	//$tname="SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TP_PUNTO_PAGO U,SGC_TP_MOTIVO_RECLAMOS R, SGC_TT_PQR_FLUJO F";
	

	if ($query) $where = "AND UPPER($qtype) LIKE UPPER('$query%') ";
	
	$l=new PQRs();
	$valores=$l->CantidadDatosInteraccion($proyecto,$ofiini,$ofifin,$fecinirad,$fecfinrad,$login,$start,$end,$where);
	while (oci_fetch($valores)) {
		$total = oci_result($valores, 'CANTIDAD');
	}oci_free_statement($valores);

	$l=new PQRs();
	$registros=$l->datosInteraccion($proyecto,$ofiini,$ofifin,$fecinirad,$fecfinrad,$login,$start,$end,$where);

	$json = "";
	$json .= "{\n";
	$json .= "page: $page,\n";
	$json .= "total: $total,\n";
	$json .= "rows: [";
	$rc = false;
	while (oci_fetch($registros)) {
		$numero=oci_result($registros, 'RNUM');
		$fecha = oci_result($registros, 'FECREG');
		$motivo = oci_result($registros, 'DESC_MOTIVO_REC');
		$descripcion = oci_result($registros, 'DESCRIPCION');
		$usuario = oci_result($registros, 'USUARIO');
		$inmueble = oci_result($registros, 'COD_INMUEBLE');
		$medrec = oci_result($registros, 'MEDIOREC');


		$longdesc = strlen($descripcion);
		$items = round($longdesc / 60)+1; 
		$linf = 0; 
		$lsup = 60; 
		
		$longtipo = strlen($motivo);
		$itemsm = round($longtipo / 25)+1; 
		$linfm = 0; 
		$lsupm = 25;
		
	
		if ($rc) $json .= ",";
		$json .= "\n{";
		$json .= "id:'".$motivo."',";
		$json .= "cell:['<b>" .$numero."</b>'";
		$json .= ",'".$fecha."'";
		
		if($longtipo > 0){
		$json .= ",'";
			for($i=0; $i<$itemsm; $i++){ 
				$cadenam[$i] = substr($motivo, $linfm, $lsupm); 
				$linfm += 25; 
				$json .= $cadenam[$i]. '<br />';
		  	}  
			$json .= "'";           
		}  
		
		if($longdesc > 0){
		$json .= ",'";
			for($i=0; $i<$items; $i++){ 
				$cadena[$i] = substr($descripcion, $linf, $lsup); 
				$linf += 60; 
				$json .= $cadena[$i]. '<br />';
		  	}  
			$json .= "'";           
		}  
		$json .= ",'".addslashes($usuario)."'";
		$json .= ",'".addslashes($inmueble)."'";
		$json .= ",'".addslashes($medrec)."'";
		$json .= "]}";
		$rc = true;
	}
	$json .= "]\n";
	$json .= "}";
	echo $json;
?>
