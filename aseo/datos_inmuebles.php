<?php
session_start();
include('../destruye_sesion.php');

//$operario = ($_GET['operario']);
//$ruta = ($_GET['ruta']);
//$periodo = ($_GET['periodo']);

//$operario = '223-0035860-7';
//$ruta = 1765;
//$periodo = 201411;

$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;

function countRec($fname,$tname,$where) {
    //$operario = ($_GET['operario']);
    //$ruta = ($_GET['ruta']);
    //$periodo = ($_GET['periodo']);

    //$operario = '223-0035860-7';
    //$ruta = 1765;
    //$periodo = 201411;

    $Cnn = new OracleConn(UserGeneral, PassGeneral);
    $link = $Cnn->link;
    $sql = "SELECT COUNT($fname)CANTIDAD
	FROM $tname
	WHERE I.CONSEC_URB = U.CONSEC_URB(+) AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)  
	AND I.ID_ESTADO = E.ID_ESTADO
	  AND C.CODIGO_INM(+)=I.CODIGO_INM
 AND C.FECHA_FIN (+) IS NULL
 AND CLI.CODIGO_CLI(+)=C.CODIGO_CLI
 AND E.ESTADO_ASEO = 'A'
	 $where $sort";
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

if (!$sortname) $sortname = "I.CODIGO_INM";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH

$fname = "I.CODIGO_INM";
$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TP_ACTIVIDADES A, SGC_TP_ESTADOS_INMUEBLES E, SGC_TT_CONTRATOS C,
SGC_TT_CLIENTES CLI";

if ($query) $where = " AND $qtype LIKE UPPER('$query%') ";

$sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (
SELECT I.CODIGO_INM, I.ID_ZONA, U.DESC_URBANIZACION, I.DIRECCION, I.ID_ESTADO , I.ID_TIPO_CLIENTE, I.ID_PROCESO, 
A.ID_USO, A.DESC_ACTIVIDAD, I.TOTAL_UNIDADES, I.UNIDADES_HAB, I.UNIDADES_DES, E.INDICADOR_ESTADO, I.CATASTRO,
CLI.DOCUMENTO, NVL(C.ALIAS,CLI.NOMBRE_CLI) NOMBRE
FROM $tname
WHERE  U.CONSEC_URB(+) = I.CONSEC_URB  AND  A.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD   
AND I.ID_ESTADO = E.ID_ESTADO
 AND C.CODIGO_INM(+)=I.CODIGO_INM
 AND C.FECHA_FIN (+) IS NULL
 AND CLI.CODIGO_CLI(+)=C.CODIGO_CLI
 AND E.ESTADO_ASEO = 'A'
 $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ";
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
    $codigo_inm = oci_result($stid, 'CODIGO_INM');
    $id_zona = oci_result($stid, 'ID_ZONA');
    $urbanizacion = oci_result($stid, 'DESC_URBANIZACION');
    $direccion = oci_result($stid, 'DIRECCION');
    //$estado = oci_result($stid, 'ID_ESTADO');
    $tipo_cliente = oci_result($stid, 'ID_TIPO_CLIENTE');
    $proceso = oci_result($stid, 'ID_PROCESO');
    $uso = oci_result($stid, 'ID_USO');
    $actividad = oci_result($stid, 'DESC_ACTIVIDAD');
    $unidades = oci_result($stid, 'TOTAL_UNIDADES');
    $unid_hab = oci_result($stid, 'UNIDADES_HAB');
    $unid_des = oci_result($stid, 'UNIDADES_DES');
    $indicador = oci_result($stid, 'INDICADOR_ESTADO');
    $catastro = oci_result($stid, 'CATASTRO');
    $numero = oci_result($stid, 'RNUM');
    $documento = oci_result($stid, 'DOCUMENTO');
    $nombre = oci_result($stid, 'NOMBRE');

    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$codigo_inm."',";
    $json .= "cell:['<b>" .$numero."</b>'";
    $json .= ",'"."<b><a href=\"JAVASCRIPT:detalle(".$codigo_inm.");\">" .$codigo_inm." </a></b>"."'";
    $json .= ",'".addslashes($id_zona)."'";
    $json .= ",'".addslashes($urbanizacion)."'";
    $json .= ",'".addslashes($direccion)."'";
    //$json .= ",'".addslashes($estado)."'";
    $json .= ",'".addslashes($tipo_cliente)."'";
    $json .= ",'".addslashes($documento)."'";
    $json .= ",'".addslashes($nombre)."'";
    $json .= ",'".addslashes($proceso)."'";
    $json .= ",'".addslashes($catastro)."'";
    $json .= ",'".addslashes($uso)."'";
    $json .= ",'".addslashes($actividad)."'";
    $json .= ",'".addslashes($unidades)."'";
    $json .= ",'".addslashes($unid_hab)."'";
    $json .= ",'".addslashes($unid_des)."'";
    if($indicador == 'I'){
        $json .= ",'"."<img src=\"../images/cancel.png\" width=\"10\" height=\"10\"/>"."']";
    }
    else{
        $json .= ",'"."<img src=\"../images/check.png\" width=\"10\" height=\"10\"/>"."']";
    }
    $json .= "}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
oci_free_statement($stid);
?>