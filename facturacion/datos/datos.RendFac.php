<?php
include '../clases/class.reportes.php';
 $fecini = ($_GET['fecini']);
 $fecfin = ($_GET['fecfin']);
 $proyecto = ($_GET['proyecto']);

//$fecini = '2015-08-06';
//$fecfin = '2015-08-06';

function countRec($fname,$tname,$where,$sort) {
		$l=new Reportes();
		$valores=$l->CantidadRendFac($fname, $tname,$where,$sort);
	
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

if (!$sortname) $sortname = "USR.LOGIN";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="USR.LOGIN";
$tname="SGC_TT_USUARIOS USR, SGC_TT_INMUEBLES INM, SGC_TT_REGISTRO_ENTREGA_FAC FAC";
$where = " AND FAC.FECHA_EJECUCION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD hh24:mi:ss') and INM.ID_PROYECTO='$proyecto'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND FAC.FECHA_EJECUCION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD hh24:mi:ss') AND UPPER($qtype) LIKE UPPER('$query%') and INM.ID_PROYECTO='$proyecto' ";
}
$l=new Reportes();
$registros=$l->TodosRendFac($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
	$numero=oci_result($registros, 'RNUM');
	$cod_usuario = oci_result($registros, 'ID_USUARIO');
	$login = oci_result($registros, 'LOGIN'); 	
	$ruta = oci_result($registros, 'RUTA');
	$inicio = oci_result($registros, 'INICIO');
	$fin = oci_result($registros, 'FIN');
	$total = oci_result($registros, 'TOTAL');
	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "id:'".$consecutivo."',";	
	$json .= "cell:['<b>" .$numero."</b>'";
	$json .= ",'".addslashes($cod_usuario)."'";
	$json .= ",'".addslashes($login)."'";	
	$json .= ",'"."<b><a href=\"JAVASCRIPT:ruteo(".$ruta.",\'".str_replace(':','',str_replace('/','',str_replace(' ','',$inicio)))."\',\'".str_replace(':','',str_replace('/','',str_replace(' ','',$fin)))."\',\'".str_replace('','',$cod_usuario)."\');\">" .$ruta." </a></b>"."'";
	$json .= ",'".addslashes($inicio)."'";
	$json .= ",'".addslashes($fin)."'";
	$json .= ",'"."<b><a href=\"JAVASCRIPT:upCliente(".$ruta.",\'".str_replace(':','',str_replace('/','',str_replace(' ','',$inicio)))."\',\'".str_replace(':','',str_replace('/','',str_replace(' ','',$fin)))."\',\'".str_replace('','',$cod_usuario)."\');\">" .$total." </a></b>"."'";
	$json .= "]}";
	$rc = true; 
}
$json .= "]\n";
$json .= "}";
echo $json;
?>