<?php
error_reporting(E_ERROR);

session_start();
include_once ('../../include.php');
include('../../destruye_sesion.php');
include('../../clases/class.reconexion.php');
$proyecto = $_GET['proyecto'];
$sector = $_GET['sector'];
$fecini = $_GET['fecini'];
$fecfin = $_GET['fecfin'];
$contratista = $_GET['selCon'];

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];


if (!$sortname) $sortname = "TO_DATE(TO_CHAR(C.FECHA_PLANIFICACION,'DD/MM/YYYY'),'DD/MM/YYYY')";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 300;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH

$fname = "C.ID_INMUEBLE";
$tname = "SGC_TT_REGISTRO_RECONEXION C, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I";

$where = " AND I.CODIGO_INM=C.ID_INMUEBLE  AND I.ID_PROYECTO='$proyecto' ";

if ( $fecini != "" && $fecfin != ""){
    $where .= " AND C.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS') ";
}

if ($sector != ""){
    $where .= " AND I.ID_SECTOR = '$sector'";
}
if($contratista !="") {
    $where .= " AND U.CONTRATISTA = '$contratista'";
}
if ($query) $where .= " AND $qtype LIKE UPPER('$query%') ";

$t=new Reconexion();
$cant=$t->getCantRutRecFlexy($tname,$where);
while (oci_fetch($cant)) {
    $total = oci_result($cant, 'TOTAL');
}oci_free_statement($cant);

$l=new Reconexion();
$stid=$l->getResumenRutRecFlexy($tname, $where, $sort, $start, $end);

$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
//$json .= "page: 1,\n";
$json .= "total: $total,\n";
//$json .= "total: 1,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($stid)) {
    $nom_completo = oci_result($stid, 'NOM_COMPLETO');
    $cod_operario = oci_result($stid, 'COD_LECTOR');
    $fecha_asig = oci_result($stid, 'FECHA_ASIG');
    $predio_total = oci_result($stid, 'TOTAL');
    $cod_ruta = oci_result($stid, 'RUTA');
    $predio_noleido = oci_result($stid, 'NOLEIDOS');
    $numero = oci_result($stid, 'RNUM');
    $predio_leido = $predio_total - $predio_noleido;
    $porc_leidos = round(($predio_leido / $predio_total)*100,2);
    $porc_noleidos = round(($predio_noleido / $predio_total)*100,2);

    if($predio_leido == 0 || $predio_leido == 1 || $predio_leido == 2) {
        $hora_total = "00:00:00";
        $min_promedio = "0";
        $predio_promedio_hora = "0";
    }
    else{
        $dif = 0;
        $a=new Reconexion();
        $stidb=$a->getFechasEjeRecByRutFec($periodo, $cod_ruta, $fecini, $fecfin);
        while (oci_fetch($stidb)) {
            $cant_reg = oci_result($stidb, 'CANTIDAD');
            $dia_calc = oci_result($stidb, 'DIA');

            $f=new Rendimiento();
            $stida=$f->getFechMaxEjeInsByRutFec($periodo, $cod_ruta, $dia_calc, $fecini, $fecfin);
            while (oci_fetch($stida)) {
                $fec_max = oci_result($stida, 'FEC_MAX');
            }oci_free_statement($stida);

            $f=new Rendimiento();
            $stida=$f->getFechMinEjeInsByRutFec($periodo, $cod_ruta, $dia_calc, $fecini, $fecfin);
            while (oci_fetch($stida)) {
                $fec_min = oci_result($stida, 'FEC_MIN');
            }oci_free_statement($stida);

            $fecha_max = substr($fec_max,0,10);
            $horai=substr($fec_max,11,2);
            $mini=substr($fec_max,14,2);
            $segi=substr($fec_max,17,2);

            $fecha_min = substr($fec_min,0,10);
            $horaf=substr($fec_min,11,2);
            $minf=substr($fec_min,14,2);
            $segf=substr($fec_min,17,2);

            $difdias=$fecha_max-$fecha_min;
            $difdias = (24*$difdias);
            $diferencia_dias = $fecha_max-$fecha_min;

            $ini=((($horai*60)*60)+($mini*60)+$segi);
            $fin=((($horaf*60)*60)+($minf*60)+$segf);

            $dif+=$ini-$fin;
        }oci_free_statement($stidb);

        $difh=floor($dif/3600);
        $difm=floor(($dif-($difh*3600))/60);
        $difs=$dif-($difm*60)-($difh*3600);

        if($difh < 10){ $difh = '0'.$difh; }
        if($difm < 10){ $difm = '0'.$difm; }
        if($difs < 10){ $difs = '0'.$difs; }

        $hora_total = $difh.":".$difm.":".$difs;

        $hora_prom = 60*$difh;
        $min_prom = $hora_prom+$difm;

        $min_promedio = round($min_prom/$predio_leido,2);
        $predio_promedio_hora = @round(60/$min_promedio);
    }

    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$cod_operario.' '.$proyecto.' '.$periodo.' '.$cod_ruta.' '.$fecini.' '.$fecfin.' '.$fecha_asig."',";
    $json .= "cell:['<b>" .$numero."</b>'";
    $json .= ",'<b>".addslashes($cod_operario)."</b>'";
    $json .= ",'".addslashes($nom_completo)."'";
    $json .= ",'".addslashes($cod_ruta)."'";
    $json .= ",'".addslashes($fecha_asig)."'";
    $json .= ",'".addslashes($predio_total)."'";
    $json .= ",'".addslashes($predio_leido)."'";
    $json .= ",'".addslashes($porc_leidos)." %'";
    $json .= ",'".addslashes($predio_noleido)."'";
    $json .= ",'".$porc_noleidos." %'";
    $json .= ",'".addslashes($hora_total)."'";
    $json .= ",'".addslashes($min_promedio)." min'";
    $json .= ",'".$predio_promedio_hora."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
oci_free_statement($stid);
?>