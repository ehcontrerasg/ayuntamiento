<?php
session_start();
require'../clases/classPagos.php';
	
	function countRec($fname,$tname,$where,$sort) {
		$l=new Pagos();
		$valores=$l->CantidadPagosTransito($fname, $tname,$where,$sort);

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

	if (!$sortname) $sortname = "P.ID_PAGO";
	if (!$sortorder) $sortorder = "DESC";

	$sort = "ORDER BY $sortname $sortorder";

	if (!$page) $page = 1;
	if (!$rp) $rp = 10;

	$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
	$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
	$fname ="P.ID_PAGO";
	$tname="SGC_TT_PAGOS_TRANSITO P";
	$where="P.ESTADO_PAGO = 'P'";

	if ($query) $where = "AND UPPER($qtype) LIKE UPPER('$query%') ";
	

	$total = countRec($fname, $tname, $where, $sort);
	$json = "";
	$json .= "{\n";
	$json .= "page: $page,\n";
	$json .= "total: $total,\n";
	$json .= "rows: [";
	$rc = false;
	$l=new Pagos();
	$registros=$l->DatosPagosTransito($sort, $start, $end);
	while (oci_fetch($registros)) {
		$numero=oci_result($registros, 'RNUM');
		$id_pago = oci_result($registros, 'ID_PAGO');
		$cod_fac = oci_result($registros, 'CODIGO');
		$inmueble = oci_result($registros, 'ID_INMUEBLE');
		$entidad = oci_result($registros, 'DESC_ENTIDAD');
		$punto = oci_result($registros, 'DESCRIPCION');
		$caja = oci_result($registros, 'ID_CAJA');
		$fecpago = oci_result($registros, 'FECHA_PAGO');
		$fecregistro = oci_result($registros, 'FECHA_REGISTRO');
		$importe = oci_result($registros, 'VALOR_IMPORTE');
		$observacion = oci_result($registros, 'OBSERVACION');
		
		$p=new Pagos();
		$registrosC=$p->ClientePagosTransito($inmueble);
		while (oci_fetch($registrosC)) {
			$cliente=oci_result($registrosC, 'ALIAS');
			$nomcliente=oci_result($registrosC, 'NOMBRE_CLI');
		}oci_free_statement($registrosC);
		
		if($cliente == '') $cliente = $nomcliente;
		if ($rc) $json .= ",";
		$json .= "\n{";
		$json .= "id:'".$id_pago."',";
		$json .= "title:'".$observacion."',";
		$json .= "cell:['<b>" .$numero."</b>'";
		$json .= ",'".addslashes($cod_fac)."'";
		$json .= ",'".addslashes($inmueble)."'";
		$json .= ",'".addslashes($cliente)."'";
		$json .= ",'".addslashes($importe)."'";
		$json .= ",'".addslashes($fecpago)."'";
		$json .= ",'".addslashes($fecregistro)."'";
		$json .= ",'".addslashes($entidad)."'";
		$json .= ",'".addslashes($punto)."'";
		$json .= ",'".addslashes($caja)."'";
		$json .= ",'".addslashes($observacion)."'";
		$json .= "]}";
		$rc = true;
	}oci_free_statement($registros);
	$json .= "]\n";
	$json .= "}";
	echo $json;
?>
