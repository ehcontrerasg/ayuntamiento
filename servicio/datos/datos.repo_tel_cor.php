<?php
session_start();
	include '../clases/classPqrs.php';
	$coduser = $_SESSION['codigo'];
	$proyecto = $_GET['proyecto'];
	$fecini = $_GET['fecini'];
	$fecfin = $_GET['fecfin'];

	$page = $_POST['page'];
	$rp = $_POST['rp'];
	$sortname = $_POST['sortname'];
	$sortorder = $_POST['sortorder'];
	$query = $_POST['query'];
	$qtype = $_POST['qtype'];

	if (!$sortname) $sortname = "U.LOGIN";
	if (!$sortorder) $sortorder = "ASC";

	$sort = "ORDER BY $sortname $sortorder";

	if (!$page) $page = 1;
	if (!$rp) $rp = 30;

	$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
	$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
	//$fname ="P.CODIGO_PQR";
	//$tname="SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TP_PUNTO_PAGO U,SGC_TP_MOTIVO_RECLAMOS R, SGC_TT_PQR_FLUJO F";
	

	if ($query) $where = " AND UPPER($qtype) LIKE UPPER('$query%') ";
	
	$l=new PQRs();
	$valores=$l->cantDatosTeleCorreo($proyecto,$fecini,$fecfin,$start,$end,$where);
	while (oci_fetch($valores)) {
		$total = oci_result($valores, 'CANTIDAD');
	}oci_free_statement($valores);

	$l=new PQRs();
	$registros=$l->datosTeleCorreo($proyecto,$fecini,$fecfin,$start,$end,$where);
	
	$json = "";
	$json .= "{\n";
	$json .= "page: $page,\n";
	$json .= "total: $total,\n";
	$json .= "rows: [";
	$rc = false;
	while (oci_fetch($registros)) {
		$numero=oci_result($registros, 'RNUM');
		$login = oci_result($registros, 'LOGIN');
		$usuario = oci_result($registros, 'USUARIO');
		$cant_tel = oci_result($registros, 'CANTTEL');
		$cant_email = oci_result($registros, 'CANTEMAIL');
	
		if ($rc) $json .= ",";
		$json .= "\n{";
		$json .= "id:'".$login."',";
		$json .= "cell:['<b>" .$numero."</b>'";
		$json .= ",'".$login."'";
		$json .= ",'".$usuario."'";
		$json .= ",'".addslashes($cant_tel)."'";
		$json .= ",'".addslashes($cant_email)."'";
		$json .= "]}";
		$rc = true;
	}
	$json .= "]\n";
	$json .= "}";
	echo $json;
?>
