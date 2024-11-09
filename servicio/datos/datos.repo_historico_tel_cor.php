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
	$valores=$l->cantDatosHistoricoTelCor($proyecto,$fecini,$fecfin,$start,$end,$where);
	while (oci_fetch($valores)) {
		$total = oci_result($valores, 'CANTIDAD');
	}oci_free_statement($valores);

	$l=new PQRs();
	$registros=$l->datosHistoricoTelCor($proyecto,$fecini,$fecfin,$start,$end);
	
	$json = "";
	$json .= "{\n";
	$json .= "page: $page,\n";
	$json .= "total: $total,\n";
	$json .= "rows: [";
	$rc = false;
	while (oci_fetch($registros)) {
		$numero=oci_result($registros, 'RNUM');
		$inmueble = oci_result($registros, 'CODIGO_INM');
		$nombre = oci_result($registros, 'NOMBRE');
		$telefono_ant = oci_result($registros, 'TELEFONO_ANT');
		$telefono_act = oci_result($registros, 'TELEFONO_ACT');
        $correo_ant = oci_result($registros, 'CORREO_ANT');
		$correo_act = oci_result($registros, 'CORREO_ACT');
		$fecha = oci_result($registros, 'FECHA_ACTUALIZACION');
		$usuario = oci_result($registros, 'USUARIO');
	
		if ($rc) $json .= ",";
		$json .= "\n{";
		$json .= "id:'".$numero."',";
		$json .= "cell:['<b>" .$numero."</b>'";
		$json .= ",'".$inmueble."'";
		$json .= ",'".$nombre."'";
        $json .= ",'".$telefono_ant."'";
		$json .= ",'".$telefono_act."'";
        $json .= ",'".$correo_ant."'";
		$json .= ",'".$correo_act."'";
        $json .= ",'".$fecha."'";
		$json .= ",'".$usuario."'";
		$json .= "]}";
		$rc = true;
	}
	$json .= "]\n";
	$json .= "}";
	echo $json;
?>
