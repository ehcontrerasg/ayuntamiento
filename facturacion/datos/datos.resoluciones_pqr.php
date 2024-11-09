<?php
include '../clases/classPqrs.php';
 $cod_pqr=$_GET['cod_pqr'];
// $inmueble=70050;
//$fecini = '2015-08-06';
//$fecfin = '2015-08-06';

function countRec($cod_pqr) {
	$l=new PQRs();
	$valores=$l->resolucionesAnteriores ($cod_pqr);
	while (oci_fetch($valores)) {
			$cantidad = oci_result($valores, 'CANTRES');
				
	}oci_free_statement($valores);
	return $cantidad;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "P.CONSECUTIVO";
if (!$sortorder) $sortorder = "DESC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
//$fname ="F.PERIODO";
//$tname="SGC_TT_FACTURA F";
//$where = " AND F.INMUEBLE='$inmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

/*if ($query)
{ 
	$where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
}*/
$l=new PQRs();
$registros=$l->todosResolucionesAnteriores($cod_pqr,$sort);
$total =countRec($cod_pqr);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
$numero = 1;
while (oci_fetch($registros)) {
	$num_pqr = oci_result($registros, 'ORDEN');
	$con_pqr = oci_result($registros, 'CONSECUTIVO');
	$are_pqr = oci_result($registros, 'DESC_AREA');
	$fen_pqr = oci_result($registros, 'FECHA_ENTRADA'); 	
	$fsa_pqr = oci_result($registros, 'FECHA_SALIDA');
	$res_pqr = oci_result($registros, 'RESPUESTA');
	$usu_pqr = oci_result($registros, 'LOGIN');
	//$dia_pqr = oci_result($registros, 'DIAGNOSTICO');
	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "id:'".$con_pqr."',";	
	$json .= "title:'".$res_pqr."',";	
	$json .= "cell:['<b>" .$numero."</b>'";
	$json .= ",'".addslashes($are_pqr)."'";
	$json .= ",'".addslashes($fen_pqr)."'";
	$json .= ",'".addslashes($fsa_pqr)."'";
	$json .= ",'".addslashes($res_pqr)."'";
	$json .= ",'".addslashes($usu_pqr)."'";
	//$json .= ",'".addslashes($dia_pqr)."'";
	//$json .= ",'"."<b><a href=\"JAVASCRIPT:resolucionPdf(".$cod_pqr.");\">" ."<img src=\"../../images/search1.png\" width=\"20\" height=\"20\"/>"." </a></b>"."'";
	//$json .= ",'"."<b><a href=\"JAVASCRIPT:reclamoPdf(".$cod_pqr.");\">" ."<img src=\"../../images/pdf_ico.png\" width=\"20\" height=\"20\"/>"." </a></b>"."'";
	$json .= "]}";
	$rc = true; 
	$numero++;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>