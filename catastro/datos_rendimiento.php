<?php
session_start();
include '../destruye_sesion.php';
$proyecto    = ($_GET['proyecto']);
$periodo_ini = ($_GET['periodoI']);
$periodo_fin = ($_GET['periodoF']);
$sector      = ($_GET['sector']);

$Cnn  = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;

function countRec($fname, $tname, $where)
{
    $proyecto    = ($_GET['proyecto']);
    $periodo_ini = ($_GET['periodoI']);
    $periodo_fin = ($_GET['periodoF']);
    $sector      = ($_GET['sector']);

    $Cnn  = new OracleConn(UserGeneral, PassGeneral);
    $link = $Cnn->link;
    $sql  = "SELECT COUNT(*)CANTIDAD FROM (SELECT COUNT($fname)
    FROM $tname
	WHERE U.ID_USUARIO = A.ID_OPERARIO AND A.ID_INMUEBLE = I.CODIGO_INM AND A.ID_TIPO_RUTA = 1
	AND A.ID_PERIODO BETWEEN $periodo_ini and $periodo_fin AND I.ID_PROYECTO = '$proyecto'";
    if ($sector != '') {$sql .= " AND A.ID_SECTOR = $sector";}
    $sql .= " GROUP BY A.ID_SECTOR, A.ID_RUTA)  $where $sort";
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
    $sortname = "TO_CHAR(A.FECHA_ASIG,'DD/MM/YYYY')";
}

if (!$sortorder) {
    $sortorder = "ASC";
}

$sort = "ORDER BY $sortname $sortorder";

if (!$page) {
    $page = 1;
}

if (!$rp) {
    $rp = 100;
}

$end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
$start = ($page) * $rp; // MIN_ROW_TO_FETCH

$fname = "U.ID_USUARIO";
$tname = "SGC_TT_USUARIOS U, SGC_TT_ASIGNACION A, SGC_TT_INMUEBLES I";

if ($query) {
    $where = " AND $qtype LIKE UPPER('$query%') ";
}

$sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (
SELECT  /*+ USE_NL(U,A,I) ORDERED */ U.ID_USUARIO, (U.NOM_USR||' '||U.APE_USR)NOM_COMPLETO, A.ID_SECTOR||A.ID_RUTA RUTA, TO_CHAR(A.FECHA_ASIG,'DD/MM/YYYY')FECHA_ASIG, 
COUNT(*)TOTAL,MIN(TO_CHAR(A.FECHA_FIN,'DD/MM/YYYY HH24:MI:SS')) FECINICIO,MAX(TO_CHAR(A.FECHA_FIN,'DD/MM/YYYY HH24:MI:SS')) FECFINAL, 
SUM(DECODE(A.FECHA_FIN, '', 1, 0)) NOLEIDOS,A.ANULADO
FROM $tname
WHERE U.ID_USUARIO = A.ID_OPERARIO AND A.ID_INMUEBLE = I.CODIGO_INM AND A.ID_TIPO_RUTA = 1
AND A.ID_PERIODO BETWEEN $periodo_ini and $periodo_fin AND I.ID_PROYECTO = '$proyecto'";
if ($sector != '') {$sql .= " AND A.ID_SECTOR = $sector";}
$sql .= " GROUP BY U.ID_USUARIO, NOM_USR, APE_USR, A.ID_SECTOR||A.ID_RUTA, TO_CHAR(A.FECHA_ASIG,'DD/MM/YYYY'), A.ANULADO $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ";
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
    $cod_operario   = oci_result($stid, 'ID_USUARIO');
    $nom_completo   = oci_result($stid, 'NOM_COMPLETO');
    $cod_ruta       = oci_result($stid, 'RUTA');
    $fecha_asig     = oci_result($stid, 'FECHA_ASIG');
    $fecha_ini      = oci_result($stid, 'FECINICIO');
    $fecha_fin      = oci_result($stid, 'FECFINAL');
    $predio_total   = oci_result($stid, 'TOTAL');
    $predio_noleido = oci_result($stid, 'NOLEIDOS');
    $numero         = oci_result($stid, 'RNUM');
    $anulado        = oci_result($stid, 'ANULADO');
    $predio_leido   = $predio_total - $predio_noleido;
    $porc_leidos    = round(($predio_leido / $predio_total) * 100, 2);
    $porc_noleidos  = round(($predio_noleido / $predio_total) * 100, 2);
    if ($predio_leido == 0 || $predio_leido == 1 || $predio_leido == 2) {
        $hora_total           = "00:00:00";
        $min_promedio         = "0";
        $predio_promedio_hora = "0";
    } else {
        $query = "SELECT COUNT(*)CANTIDAD, TO_CHAR(FECHA_FIN,'DD/MM/YYYY')DIA
		FROM SGC_TT_ASIGNACION
		WHERE ID_PERIODO BETWEEN $periodo_ini and $periodo_fin AND CONCAT(ID_SECTOR,ID_RUTA) = '$cod_ruta'
		GROUP BY TO_CHAR(FECHA_FIN,'DD/MM/YYYY') ";
        $stidb = oci_parse($link, $query);
        oci_execute($stidb, OCI_DEFAULT);
        $dif = 0;
        while (oci_fetch($stidb)) {
            $cant_reg = oci_result($stidb, 'CANTIDAD');
            $dia_calc = oci_result($stidb, 'DIA');
            $sqla     = "SELECT MAX(TO_CHAR(FECHA_FIN,'DD/MM/YYYY HH24:MI:SS')) FEC_MAX
        	FROM SGC_TT_ASIGNACION
        	WHERE ID_PERIODO BETWEEN $periodo_ini and $periodo_fin AND CONCAT(ID_SECTOR,ID_RUTA) = '$cod_ruta'
       		AND TO_CHAR(FECHA_FIN,'DD/MM/YYYY') = '$dia_calc'";
            $stida = oci_parse($link, $sqla);
            oci_execute($stida, OCI_DEFAULT);
            while (oci_fetch($stida)) {
                $fec_max = oci_result($stida, 'FEC_MAX');
            }
            oci_free_statement($stida);

            $sqla = "SELECT MIN(TO_CHAR(FECHA_FIN,'DD/MM/YYYY HH24:MI:SS')) FEC_MIN
        	FROM SGC_TT_ASIGNACION
        	WHERE ID_PERIODO BETWEEN $periodo_ini and $periodo_fin AND CONCAT(ID_SECTOR,ID_RUTA) = '$cod_ruta'
       		AND TO_CHAR(FECHA_FIN,'DD/MM/YYYY') = '$dia_calc'";
            $stida = oci_parse($link, $sqla);
            oci_execute($stida, OCI_DEFAULT);
            while (oci_fetch($stida)) {
                $fec_min = oci_result($stida, 'FEC_MIN');
            }
            oci_free_statement($stida);

            $fecha_max = substr($fec_max, 0, 10);
            $horai     = substr($fec_max, 11, 2);
            $mini      = substr($fec_max, 14, 2);
            $segi      = substr($fec_max, 17, 2);

            $fecha_min = substr($fec_min, 0, 10);
            $horaf     = substr($fec_min, 11, 2);
            $minf      = substr($fec_min, 14, 2);
            $segf      = substr($fec_min, 17, 2);

            $difdias         = $fecha_max - $fecha_min;
            $difdias         = (24 * $difdias);
            $diferencia_dias = $fecha_max - $fecha_min;

            $ini = ((($horai * 60) * 60) + ($mini * 60) + $segi);
            $fin = ((($horaf * 60) * 60) + ($minf * 60) + $segf);

            $dif += $ini - $fin;
        }
        oci_free_statement($stidb);

        $difh = floor($dif / 3600);
        $difm = floor(($dif - ($difh * 3600)) / 60);
        $difs = $dif - ($difm * 60) - ($difh * 3600);

        if ($difh < 10) {$difh = '0' . $difh;}
        if ($difm < 10) {$difm = '0' . $difm;}
        if ($difs < 10) {$difs = '0' . $difs;}

        $hora_total            = $difh . ":" . $difm . ":" . $difs;
        $hora_prom             = 60 * $difh;
        $min_prom              = $hora_prom + $difm;
        $min_promedio          = round($min_prom / $predio_leido, 2);
        @$predio_promedio_hora = round(60 / $min_promedio);

    }

    if ($rc) {
        $json .= ",";
    }

    $json .= "\n{";
    $json .= "ID:'" . $nom_completo . "',";
    $json .= "cell:['<b>" . $numero . "</b>'";
    $json .= ",'" . "<b><a href=\"JAVASCRIPT:upCliente(" . $periodo_ini . "," . $periodo_fin . "," . $cod_ruta . ");\">" . $cod_operario . " </a></b>" . "'";
    $json .= ",'" . addslashes($nom_completo) . "'";
    $json .= ",'" . "<b><a href=\"JAVASCRIPT:ruteo(" . $cod_ruta . "," . $periodo_ini . "," . $periodo_fin . ");\">" . $cod_ruta . " </a></b>" . "'";
    $json .= ",'" . addslashes($fecha_asig) . "'";
    $json .= ",'" . addslashes($fecha_ini) . "'";
    $json .= ",'" . addslashes($fecha_fin) . "'";
    $json .= ",'" . addslashes($predio_total) . "'";
    $json .= ",'" . addslashes($predio_leido) . "'";
    $json .= ",'" . addslashes($porc_leidos) . " %'";
    $json .= ",'" . addslashes($predio_noleido) . "'";
    $json .= ",'" . addslashes($porc_noleidos) . " %'";
    $json .= ",'" . addslashes($hora_total) . "'";
    $json .= ",'" . addslashes($min_promedio) . " min'";
    $json .= ",'" . addslashes($predio_promedio_hora) . "'";
    $json .= ",'" . addslashes($anulado ). "']";
    $json .= "}";
    $rc = true;
    //}
}
$json .= "]\n";
$json .= "}";
echo $json;
oci_free_statement($stid);
