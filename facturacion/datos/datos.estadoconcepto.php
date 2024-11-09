<?php

include '../clases/class.facturas.php';
 $inmueble=$_GET['inmueble'];
//$fecini = '2015-08-06';
//$fecfin = '2015-08-06';

function countRec($fname,$tname,$where,$sort) {
    return 100;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "DESC_SERVICIO";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="CONCEPTO";
$tname="SGC_TT_FACTURA";
$where = " AND DF.COD_INMUEBLE=$inmueble";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND DF.COD_INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new facturas();
$registros=$l->estcon($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $concepto=oci_result($registros, 'CONCEPTO');
    $numfac=oci_result($registros, 'NUMFAC');
    $valor = oci_result($registros, 'VALOR');

    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$factura."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($concepto)."'";
    $json .= ",'".addslashes($numfac)."'";
    $json .= ",'<b style=color:#990000 class=valores>".addslashes("RD$ ".$valor)."</b>'";
    $json .= "]}";
    $rc = true;
}

if ($rc) $json .= ",";
$json .= "\n{";
$json .= "id:'".'TotFacturas'."',";
$json .= "cell:['<class=th>".'Total<br/>Facturas'."</>'";
$json .= ",'<class=th>".addslashes('Periodo Inicio')."</>'";
$json .= ",'<class=th>".addslashes('Periodo Fin')."</>'";
$json .= ",'<class=th>".addslashes("Deuda")."</>'";
$json .= "]},";
$rc = true;




$fname ="CATIDAD";
$tname="SGC_TT_FACTURA";
$where = " AND F.INMUEBLE='$inmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND F.INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new facturas();
$registros=$l->numfacven($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);

$rc = false;
while (oci_fetch($registros)) {

    $factura=oci_result($registros, 'CANTIDAD');
    $periodomax = oci_result($registros, 'PERIODOMAX');
    $periodomin = oci_result($registros, 'PERIODOMIN');
    $deuda ="RD$ ".oci_result($registros, 'DEUDA');
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$factura."',";
    $json .= "cell:['$factura'";
    $json .= ",'".addslashes($periodomin)."'";
    $json .= ",'".addslashes($periodomax)."'";
    $json .= ",'<b style=color:#990000 class=valores>".$deuda."</b>'";
    $json .= "]}";
    $rc = true;
}






$json .= "]\n";
$json .= "}";
echo $json;
?>