<?php
session_start();
	include '../clases/classPqrs.php';
	$coduser = $_SESSION['codigo'];
	$tipo_pqr = $_GET['tipo_pqr'];
	$proyecto = $_GET['proyecto'];
	$secini = $_GET['secini'];
	$secfin = $_GET['secfin'];
	$rutini = $_GET['rutini'];
	$rutfin = $_GET['rutfin'];
	$fecini = $_GET['fecini'];
	$fecfin = $_GET['fecfin'];
	$cod_inmueble = $_GET['cod_inmueble'];
	$area_user = $_GET['area_user'];
	$page = $_POST['page'];
	$rp = $_POST['rp'];
	$sortname = $_POST['sortname'];
	$sortorder = $_POST['sortorder'];
	$query = $_POST['query'];
	$qtype = $_POST['qtype'];

	if (!$sortname) $sortname = "TO_DATE(TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS'),'DD/MM/YYYY hh24:mi:ss')";
	if (!$sortorder) $sortorder = "DESC";

	$sort = "ORDER BY $sortname $sortorder";

	if (!$page) $page = 1;
	if (!$rp) $rp = 30;

	$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
	$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
	$fname ="P.CODIGO_PQR";
	$tname="SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M, SGC_TP_AREAS A, SGC_TT_INMUEBLES I";
	

	if ($query) $where = "AND UPPER($qtype) LIKE UPPER('$query%') ";
	
	$l=new PQRs();
	$valores=$l->CantidadRegistrosPqr($proyecto, $tipo_pqr, $secini, $secfin, $rutini, $rutfin, $fecini, $fecfin, $cod_inmueble, $where, $area_user);
	while (oci_fetch($valores)) {
		$total = oci_result($valores, 'CANTIDAD');
	}oci_free_statement($valores);
	
	/*$l=new PQRs();
	$valores=$l->CantidadRegistrosPqrCat ($tipo_pqr, $fecini, $fecfin, $where, $area_user);
	while (oci_fetch($valores)) {
		$totalcat = oci_result($valores, 'CANTIDAD');
	}oci_free_statement($valores);*/

	$l=new PQRs();
	$registros=$l->obtenerDatosPQRs($proyecto, $tipo_pqr, $secini, $secfin, $rutini, $rutfin, $fecini, $fecfin, $cod_inmueble, $start, $end, $where, $area_user);

	
	$json = "";
	$json .= "{\n";
	$json .= "page: $page,\n";
	$json .= "total: $total,\n";
	$json .= "rows: [";
	$rc = false;
	while (oci_fetch($registros)) {
		$numero=oci_result($registros, 'RNUM');
		$codigo_pqr = oci_result($registros, 'CODIGO_PQR');
		$fecha_pqr = oci_result($registros, 'FECHAPQR');
		$cod_inm = oci_result($registros, 'COD_INMUEBLE');
		$motivo_pqr = oci_result($registros, 'MOTIVO');
		$entidad_pqr = oci_result($registros, 'COD_ENTIDAD');
		$user_pqr = oci_result($registros, 'LOGIN');
		$area_asig = oci_result($registros, 'DESC_AREA');
		$fecha_max = oci_result($registros, 'FECHAMAX');
		$proceso_inm = oci_result($registros, 'ID_PROCESO');
		$dias_faltan = oci_result($registros, 'PORVENCER');
		$dias_vencidos = oci_result($registros, 'VENCIDOS');
		
		if ($rc) $json .= ",";
		$json .= "\n{";
		$json .= "id:'".$codigo_pqr."',";
		$json .= "cell:['<b>" .$numero."</b>'";
		$json .= ",'".$codigo_pqr."'";
		$json .= ",'".$cod_inm."'";
		$json .= ",'".addslashes($fecha_pqr)."'";
		$json .= ",'".addslashes($motivo_pqr)."'";
		$json .= ",'".addslashes($entidad_pqr)."'";
		$json .= ",'".addslashes($user_pqr)."'";
		$json .= ",'".addslashes($fecha_max)."'";
		$json .= ",'".addslashes($proceso_inm)."'";
		$json .= ",'".addslashes($area_asig)."'";
		if($dias_faltan > 0)$json .= ",'<b><font color=#00CC00>".$dias_faltan."</font></b>'";
		else $json .= ",'<b><font color=#FF0000>".$dias_faltan."</font></b>'";
		$json .= ",'".$dias_vencidos."'";
		$json .= ",'<a href=javascript:edita_pqr($codigo_pqr);><img src=../../images/edit1.png width=15px height=15px title=Resoluci&oacute;n /></a>'";
		//$json .= ",'<a href=javascript:sigue_pqr($codigo_pqr);><img src=../../images/search1.png width=15px height=15px/></a>'";
		if($area_user == '9' || $area_user == '6'){
		$json .= ",'<a href=javascript:close_pqr($codigo_pqr);><img src=../../images/lock1.png width=15px height=15px title=Cierre /></a>'";
		}
		$json .= ",'<a href=javascript:documento_pqr($codigo_pqr);><img src=../../images/file1.png width=15px height=15px title=Documento /></a>'";
		$json .= "]}";
		$rc = true;
	}
	$json .= "]\n";
	$json .= "}";
	echo $json;
?>
