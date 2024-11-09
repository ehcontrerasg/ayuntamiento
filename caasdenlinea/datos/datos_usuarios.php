<?php
include '../Clases/Usuarios.php';

function countRec($fname,$tname,$where,$sort) {
		$l=new Usuarios();
		$valores=$l->CantidadRegistros($fname, $tname,$where,$sort);
	
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

if (!$sortname) $sortname = "USR.USERNAME";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 30;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="USR.USERNAME";
$tname="TBL_USERS USR, ESTADOS EST";

//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query) $where = "AND UPPER($qtype) LIKE UPPER('$query%') ";
$l=new Usuarios();
$registros=$l->Todos($where,$sort,$start,$end);

$total = countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
	$contrato = oci_result($registros, 'USERNAME');
	$email = oci_result($registros, 'EMAIL');
	$fechacreacion = oci_result($registros, 'FECHA_CREACION');	
	$ultimavisita = oci_result($registros, 'ULTIMA_VISITA');
	if($ultimavisita=="1970/01/01 00:00:00"){
		$ultimavisita="nunca ha visitado";
	}
	$estado = oci_result($registros, 'DESCRIPCION');
	$superusuario = oci_result($registros, 'SUPERUSER');
	$numero = oci_result($registros, 'RNUM');
	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "ID:'".$contrato."',";	
	$json .= "cell:['<b>" .$numero."</b>'";
	$json .= ",'".addslashes($contrato)."'";
	$json .= ",'".addslashes($email)."'";	
	$json .= ",'".addslashes($fechacreacion)."'";	
	$json .= ",'".addslashes($ultimavisita)."'";	
	$json .= ",'".addslashes($estado)."'";
	
	
	if($estado=="Activo"){
		
	$json .= ",'"."<b><a href=\"./../funciones/cambiaestado.php?contratof=$contrato&tipocambio=$estado&correo=$email\" >"."<img src=\"../images/formularios/usr_activo.png\" width=\"15\" height=\"15\"/>"." </a></b>"."']";
	}
	else{
		$activar="1";
		$json .= ",'"."<b><a href=\"./../funciones/cambiaestado.php?contratof=$contrato&tipocambio=$estado&correo=$email\" >"."<img src=\"../images/formularios/usr_inactivo.png\" width=\"15\" height=\"15\"/>"." </a></b>"."']";
	}
	$json .= "}";
	$rc = true; 
}
$json .= "]\n";
$json .= "}";
echo $json;
oci_free_statement($registros);	


