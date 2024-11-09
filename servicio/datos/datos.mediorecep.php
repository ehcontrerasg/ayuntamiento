<?php
session_start();
require'../clases/classAdminPqr.php';
	
	function countRec() {
		$l=new AdminPqr();
		$valores=$l->cantidadMedios();

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

	if (!$sortname) $sortname = "ID_MEDIO_REC";
	if (!$sortorder) $sortorder = "ASC";

	$sort = "ORDER BY $sortname $sortorder";

	if (!$page) $page = 1;
	if (!$rp) $rp = 10;

	$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
	$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
	//$fname ="D.DIA";
	//$tname="SGC_TP_DIAS_FESTIVOS D";
	//$where=" FECHA IS NOT NULL";

	if ($query) $where .= " WHERE UPPER($qtype) LIKE UPPER('$query%') ";
	

	$total = countRec();
	$json = "";
	$json .= "{\n";
	$json .= "page: $page,\n";
	$json .= "total: $total,\n";
	$json .= "rows: [";
	$rc = false;
	$l=new AdminPqr();
	$registros=$l->totalMedios ($sort, $start, $end, $where);
	while (oci_fetch($registros)) {
		$numero=oci_result($registros, 'RNUM');
		$codigo = oci_result($registros, 'ID_MEDIO_REC');
		$descripcion = oci_result($registros, 'DESC_MEDIO_REC');
		
		if ($rc) $json .= ",";
		$json .= "\n{";
		$json .= "id:'".$codigo."',";
		$json .= "cell:['<b>" .$numero."</b>'";
		$json .= ",'".addslashes($codigo)."'";
		$json .= ",'".addslashes($descripcion)."'";
		$json .= "]}";
		$rc = true;
	}oci_free_statement($registros);
	$json .= "]\n";
	$json .= "}";
	echo $json;
?>
