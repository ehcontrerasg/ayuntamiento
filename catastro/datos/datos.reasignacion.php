<?php
session_start();

$proyecto = ($_GET['proyecto']);
$sector = ($_GET['sector']);
$ruta = ($_GET['ruta']);
$periodo = ($_GET['periodo']);
$operario = ($_GET['operario']);
	
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;	

function countRec($fname,$tname,$where) {
	$proyecto = ($_GET['proyecto']);
	$sector = ($_GET['sector']);
	$ruta = ($_GET['ruta']);
	$periodo = ($_GET['periodo']);
	$operario = ($_GET['operario']);
	$Cnn = new OracleConn(UserGeneral, PassGeneral);
	$link = $Cnn->link;
	$sql = "SELECT COUNT($fname)CANTIDAD 
	FROM $tname 
	WHERE I.CONSEC_URB = U.CONSEC_URB AND C.CODIGO_CLI = O.CODIGO_CLI AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD AND O.CODIGO_INM = I.CODIGO_INM 
	AND S.ID_INMUEBLE = I.CODIGO_INM AND I.ID_SECTOR = '$sector' AND I.ID_RUTA = '$ruta'  AND S.ID_PERIODO = '$periodo' 
	AND ID_TIPO_CLIENTE <> 'CC' $where $sort";	
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

$fname = "S.ID_INMUEBLE";
$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S, SGC_TT_CONTRATOS O";

if ($query) $where = " AND $qtype LIKE UPPER('$query%') ";


$sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM ( 
SELECT I.ID_ZONA, I.CODIGO_INM , O.ID_CONTRATO, I.DIRECCION, U.DESC_URBANIZACION, C.CODIGO_CLI, C.NOMBRE_CLI, C.TIPO_DOC, C.DOCUMENTO, 
C.TELEFONO, I.ID_PROCESO, I.CATASTRO, A.ID_USO, A.ID_ACTIVIDAD, I.TOTAL_UNIDADES, I.ID_TIPO_CLIENTE, I.ID_ESTADO, I.ID_PROYECTO  
FROM $tname 
WHERE I.CONSEC_URB = U.CONSEC_URB AND C.CODIGO_CLI = O.CODIGO_CLI AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD AND O.CODIGO_INM = I.CODIGO_INM  
AND S.ID_INMUEBLE = I.CODIGO_INM AND I.ID_SECTOR = '$sector' AND I.ID_RUTA = '$ruta'  AND S.ID_PERIODO = '$periodo' 
AND ID_TIPO_CLIENTE <> 'CC' $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ";
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
	$cod_zona = oci_result($stid, 'ID_ZONA');
	$codigo_inm = oci_result($stid, 'CODIGO_INM');
	$cod_contrato = oci_result($stid, 'ID_CONTRATO');
	$direccion = oci_result($stid, 'DIRECCION');	
	$desc_urbanizacion = oci_result($stid, 'DESC_URBANIZACION');
	$id_cliente = oci_result($stid, 'CODIGO_CLI');
	$nom_cliente = oci_result($stid, 'NOMBRE_CLI');
	$tipo_doc = oci_result($stid, 'TIPO_DOC');
	$documento = oci_result($stid, 'DOCUMENTO');
	$telefono = oci_result($stid, 'TELEFONO');
	$id_proceso = oci_result($stid, 'ID_PROCESO');
	$catastro = oci_result($stid, 'CATASTRO');
	$id_uso = oci_result($stid, 'ID_USO');	
	$id_actividad = oci_result($stid, 'ID_ACTIVIDAD');
	$unidades = oci_result($stid, 'TOTAL_UNIDADES');
	$tipo_cliente = oci_result($stid, 'ID_TIPO_CLIENTE');
	$estado = oci_result($stid, 'ID_ESTADO');
	$id_proyecto = oci_result($stid, 'ID_PROYECTO');
	$numero = oci_result($stid, 'RNUM');
	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "ID:'".$id_proceso."',";	
	$json .= "cell:['<b>" .$numero."</b>'";
	$json .= ",'".addslashes($cod_zona)."'";	
	$json .= ",'".addslashes($codigo_inm)."'";	
	$json .= ",'".addslashes($cod_contrato)."'";	
	$json .= ",'".addslashes($direccion)."'";	
	$json .= ",'".addslashes($desc_urbanizacion)."'";
	$json .= ",'".addslashes($nom_cliente)."'";
	$json .= ",'".addslashes($tipo_doc)."'";
	$json .= ",'".addslashes($documento)."'";
	$json .= ",'".addslashes($telefono)."'";
	$json .= ",'".addslashes($id_proceso)."'";
	$json .= ",'".addslashes($catastro)."'";
	$json .= ",'".addslashes($id_uso)."'";
	$json .= ",'".addslashes($id_actividad)."'";
	$json .= ",'".addslashes($unidades)."'";
	$json .= ",'".$estado."']";
	$json .= "}";
	$rc = true; 
}
$json .= "]\n";
$json .= "}";
echo $json;
oci_free_statement($stid);	
?>