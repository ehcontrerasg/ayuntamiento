<?php
session_start();
require '../clases/classPagos.php';
	
	function countRec($fname,$tname,$where,$sort) {
		$l=new Pagos();
		$valores=$l->CantidadDiasFestivos($fname, $tname,$where,$sort);

		while (oci_fetch($valores)) {
			$cantidad = oci_result($valores, 'CANTIDAD');

		}oci_free_statement($valores);
		return $cantidad;
	}
 
	$page = $_POST['page'];
	$rp = $_POST['rp'];
	$sortname = $_POST['sortname'];
	$sortorder = $_POST['sortorder'];
	$query = $_POST['query'];
	$qtype = $_POST['qtype'];

	if (!$sortname) $sortname = "TO_DATE(TO_CHAR(D.FECHA,'DD/MM/YYYY'),'DD/MM/YYYY') ";
	if (!$sortorder) $sortorder = "DESC";

	$sort = "ORDER BY $sortname $sortorder";

	if (!$page) $page = 1;
	if (!$rp) $rp = 10;

	$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
	$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
	$fname ="D.DIA";
	$tname="SGC_TP_DIAS_FESTIVOS D";
	$where=" FECHA IS NOT NULL";

	if ($query) $where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
	

	$total = countRec($fname, $tname, $where, $sort);
	$json = "";
	$json .= "{\n";
	$json .= "page: $page,\n";
	$json .= "total: $total,\n";
	$json .= "rows: [";
	$rc = false;
	$l=new Pagos();
	$registros=$l->DatosDiasFestivos($sort, $start, $end, $where);
	while (oci_fetch($registros)) {
		$numero=oci_result($registros, 'RNUM');
		$agno = oci_result($registros, 'AGNO');
		$mes = oci_result($registros, 'MES');
		$dia = oci_result($registros, 'DIA');
		$fecha = oci_result($registros, 'FECHA');
		$cod_fecha = oci_result($registros, 'CODIGO');
		
		if ($rc) $json .= ",";
		$json .= "\n{";
		$json .= "id:'".$cod_fecha."',";
		$json .= "cell:['<b>" .$numero."</b>'";
		$json .= ",'".addslashes($agno)."'";
		$json .= ",'".addslashes($mes)."'";
		$json .= ",'".addslashes($dia)."'";
		$json .= ",'".addslashes($fecha)."'";
		$json .= "]}";
		$rc = true;
	}oci_free_statement($registros);
	$json .= "]\n";
	$json .= "}";
	echo $json;
?>
