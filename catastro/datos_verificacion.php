<?php
session_start();
include_once ('../include.php');

$periodo = ($_GET['periodo']);
$operario = ($_GET['operario']);
	
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;	

function countRec($fname,$tname,$where) {
	$periodo = ($_GET['periodo']);
	$operario = ($_GET['operario']);
	$Cnn = new OracleConn(UserGeneral, PassGeneral);
	$link = $Cnn->link;
 	$sql = "SELECT COUNT($fname)CANTIDAD FROM  $tname
WHERE M.ID_INMUEBLE = I.CODIGO_INM AND U.CONSEC_URB = I.CONSEC_URB AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
AND S.ID_INMUEBLE = I.CODIGO_INM AND C.CODIGO_CLI(+) = O.CODIGO_CLI AND O.CODIGO_INM(+) = I.CODIGO_INM AND M.ACTUALIZADO = 'N'
AND S.ID_OPERARIO = '$operario' AND M.ID_PERIODO = $periodo $where $sort";
	//echo $sql;
	$stid = oci_parse($link, $sql);
	oci_execute($stid, OCI_DEFAULT);
	while (oci_fetch($stid)) {
			$cantidad = oci_result($stid, 'CANTIDAD');
				
	}oci_free_statement($stid);
	return $cantidad;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "I.ID_PROCESO";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH

$fname = "M.ID_INMUEBLE";
$tname = " SGC_TT_MANTENIMIENTO M, SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TP_ACTIVIDADES A, SGC_TT_CLIENTES C, SGC_TT_ASIGNACION S, SGC_TT_CONTRATOS O";

if ($query) $where = " AND $qtype LIKE UPPER('$query%') ";

 $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (
SELECT DISTINCT M.ID_INMUEBLE, I.ID_SECTOR, I.ID_RUTA, U.DESC_URBANIZACION, I.DIRECCION, NVL(O.ALIAS,'SIN CONTRATO ASOCIADO') NOMBRE_CLI, I.ID_PROCESO, A.ID_USO, A.ID_ACTIVIDAD,
I.TOTAL_UNIDADES, I.ID_ESTADO
FROM  $tname
WHERE 
I.ID_ZONA NOT IN (
	SELECT PZ.ID_ZONA FROM SGC_TP_PERIODO_ZONA PZ
	WHERE PZ.FEC_APERTURA IS NOT NULL
	AND PZ.FEC_CIERRE IS NULL)
AND
M.ID_INMUEBLE = I.CODIGO_INM AND U.CONSEC_URB = I.CONSEC_URB AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD 
AND S.ID_INMUEBLE = I.CODIGO_INM AND C.CODIGO_CLI(+) = O.CODIGO_CLI AND O.CODIGO_INM(+) = I.CODIGO_INM AND M.ACTUALIZADO = 'N'
AND S.ID_OPERARIO = '$operario' AND S.ID_PERIODO=$periodo AND M.ID_PERIODO = $periodo $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ";
$stid = oci_parse($link, $sql);
oci_execute($stid, OCI_DEFAULT);
//echo $sql."<br>";

$total = countRec("$fname","$tname","$where");
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($stid)) {
	$codigo_inm = oci_result($stid, 'ID_INMUEBLE');
	$id_sector = oci_result($stid, 'ID_SECTOR');
	$id_ruta = oci_result($stid, 'ID_RUTA');
	$urbanizacion = oci_result($stid, 'DESC_URBANIZACION');
   	$direccion_ant = oci_result($stid, 'DIRECCION');
	$nombre_ant = oci_result($stid, 'NOMBRE_CLI');
	$id_proceso = oci_result($stid, 'ID_PROCESO');
	$uso_ant = oci_result($stid, 'ID_USO');
	$actividad_ant = oci_result($stid, 'ID_ACTIVIDAD');
	$unidades_ant = oci_result($stid, 'TOTAL_UNIDADES');
	$estado_ant = oci_result($stid, 'ID_ESTADO');
	$numero = oci_result($stid, 'RNUM');
	$ope_part = str_split('-', $operario);
	$o1 = $ope_part[0];
	$o2 = $ope_part[1];
	$o3 = $ope_part[2];
	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "ID:'".$codigo_inm."',";
	$json .= "cell:['<b>" .$numero."</b>'";		
	$json .= ",'"."<b><a href=\"JAVASCRIPT:upCliente(".$codigo_inm.",".$periodo.");\">" .$codigo_inm." </a></b>"."'";	
	$json .= ",'".addslashes($id_sector)."'";	
	$json .= ",'".addslashes($id_ruta)."'";
	$json .= ",'".addslashes($urbanizacion)."'";
	$json .= ",'".addslashes($direccion_ant)."'";
	$json .= ",'".addslashes($nombre_ant)."'";
	$json .= ",'".addslashes($id_proceso)."'";
	$json .= ",'".addslashes($uso_ant)."'";
	$json .= ",'".addslashes($actividad_ant)."'";
	$json .= ",'".addslashes($unidades_ant)."'";
	$json .= ",'".$estado_ant."']";
	$json .= "}";
	$rc = true; 
}
$json .= "]\n";
$json .= "}";
echo $json;
oci_free_statement($stid);	
?>