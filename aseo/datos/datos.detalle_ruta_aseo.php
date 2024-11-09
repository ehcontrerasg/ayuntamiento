<?php
session_start();
include_once '../../include.php';

$operario    = ($_GET['operario']);
$ruta        = ($_GET['ruta']);
$periodo     = ($_GET['periodoI']);
$periodo_fin = ($_GET['periodoF']);

//$operario = '223-0035860-7';
//$ruta = 1765;
//$periodo = 201411;

$Cnn  = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;

function countRec($fname, $tname, $where)
{
    $operario    = ($_GET['operario']);
    $ruta        = ($_GET['ruta']);
    $periodo     = ($_GET['periodoI']);
    $periodo_fin = ($_GET['periodoF']);

    //$operario = '223-0035860-7';
    //$ruta = 1765;
    //$periodo = 201411;

    $Cnn  = new OracleConn(UserGeneral, PassGeneral);
    $link = $Cnn->link;
    $sql  = "SELECT COUNT($fname)CANTIDAD
	FROM $tname
	WHERE A.ID_INMUEBLE = I.CODIGO_INM AND A.ID_PERIODO BETWEEN $periodo AND $periodo_fin AND A.FECHA_FIN IS NOT NULL AND
	A.ID_OPERARIO = '$operario' AND SUBSTR(I.ID_PROCESO,0,4) = $ruta $where $sort";
    //echo $sql;
    $stid = oci_parse($link, $sql);
    oci_execute($stid, OCI_DEFAULT);
    while (oci_fetch($stid)) {
        $cantidad = oci_result($stid, 'CANTIDAD');

    }
    oci_free_statement($stid);
    return $cantidad;
}

$page      = $_POST['page'];
$rp        = $_POST['rp'];
$sortname  = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query     = $_POST['query'];
$qtype     = $_POST['qtype'];

if (!$sortname) {
    $sortname = "I.ID_PROCESO";
}

if (!$sortorder) {
    $sortorder = "ASC";
}

$sort = "ORDER BY $sortname $sortorder";

if (!$page) {
    $page = 1;
}

if (!$rp) {
    $rp = 10;
}

$end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
$start = ($page) * $rp; // MIN_ROW_TO_FETCH

$fname = "A.ID_INMUEBLE";
$tname = "SGC_TT_ASIGNACION A, SGC_TT_INMUEBLES I";

if ($query) {
    $where = " AND $qtype LIKE UPPER('$query%') ";
}

$sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (
SELECT I.CODIGO_INM, I.ID_PROCESO, I.DIRECCION, TO_CHAR(A.FECHA_FIN,'DD/MM/YYYY HH24:MI:SS')FECHA_MANT
FROM SGC_TT_ASIGNACION A, SGC_TT_INMUEBLES I
WHERE A.ID_INMUEBLE = I.CODIGO_INM AND A.ID_PERIODO BETWEEN $periodo AND $periodo_fin AND A.FECHA_FIN IS NOT NULL AND
A.ID_OPERARIO = '$operario' AND SUBSTR(I.ID_PROCESO,0,4) = $ruta $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ";
$stid = oci_parse($link, $sql);
oci_execute($stid, OCI_DEFAULT);
//echo $sql."<br>";

$total = countRec("$fname", "$tname", "$where");
$json  = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($stid)) {
    $codigo_inm = oci_result($stid, 'CODIGO_INM');
    $id_proceso = oci_result($stid, 'ID_PROCESO');
    $direccion  = oci_result($stid, 'DIRECCION');
    $fecha_mant = oci_result($stid, 'FECHA_MANT');
    $numero     = oci_result($stid, 'RNUM');

    $sqla = "SELECT COUNT(CONSECUTIVO) CANTIDAD
    FROM SGC_TT_FOTOS_MANTENIMIENTO
    WHERE TO_CHAR(FECHA,'DDMMYYYY') BETWEEN TO_CHAR(TO_DATE('$fecha_mant','DD/MM/YYYY HH24:MI:SS'),'DDMMYYYY') AND TO_CHAR(TO_DATE('$fecha_mant','DD/MM/YYYY HH24:MI:SS'),'DDMMYYYY') AND ID_INMUEBLE = $codigo_inm";
    //echo $sqla;
    $stida = oci_parse($link, $sqla);
    oci_execute($stida, OCI_DEFAULT);
    while (oci_fetch($stida)) {
        $total_fotos = oci_result($stida, 'CANTIDAD');
    }
    oci_free_statement($stida);

    $sqla2 = "SELECT COUNT(1) CANTIDAD
	FROM SGC_TT_ASIGNACION
	WHERE ID_PERIODO BETWEEN $periodo AND $periodo_fin AND ID_INMUEBLE = $codigo_inm AND LONGITUD IS NOT NULL AND LONGITUD <> '(sin_datos)'";
    //echo $sqla;
    $stida = oci_parse($link, $sqla2);
    oci_execute($stida, OCI_DEFAULT);
    while (oci_fetch($stida)) {
        $coordenada = oci_result($stida, 'CANTIDAD');
    }
    oci_free_statement($stida);

    if ($rc) {
        $json .= ",";
    }

    $json .= "\n{";
    $json .= "id:'" . $codigo_inm . "',";
    $json .= "cell:['<b>" . $numero . "</b>'";
    $json .= ",'" . addslashes($codigo_inm) . "'";
    $json .= ",'" . addslashes($id_proceso) . "'";
    $json .= ",'" . addslashes($direccion) . "'";
    $json .= ",'" . addslashes($fecha_mant) . "'";
    if ($total_fotos > 0) {
        $json .= ",'" . "<b><a href=\"JAVASCRIPT:fotos(" . $codigo_inm . "," . $periodo . "," . $periodo_fin . ");\">" . "<img src=\"../../images/camara.ico\" width=\"15\" height=\"15\"/>" . " </a></b>" . "'";
    } else {
        $json .= ",'" . "<b></b>" . "'";
    }
    if ($coordenada > 0) {
        $json .= ",'" . "<b><a href=\"JAVASCRIPT:ubicacion(" . $codigo_inm . ");\">" . "<img src=\"../../images/mundo.ico\" width=\"15\" height=\"15\"/>" . " </a></b>" . "']";
    } else {
        $json .= ",'" . "<b></b>" . "']";
    }
    $json .= "}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
oci_free_statement($stid);
