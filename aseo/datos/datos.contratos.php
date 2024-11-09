<?php
include '../../catastro/clases/class.contrato.php';

$inmueble=$_GET['cod_inmueble'];

function countRec($fname,$tname,$where,$sort) {
    $l=new Contrato();
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

if (!$sortname) $sortname = "CON.FECHA_INICIO";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="CON.FECHA_INICIO";
$tname="SGC_TT_CONTRATOS CON, SGC_TT_CLIENTES CLI";

//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if($inmueble!=''){
    if ($query)
    { $where = "AND CODIGO_INM='$inmueble'  AND UPPER($qtype) LIKE UPPER('$query%') ";
    }ELSE{
        $where = "AND CODIGO_INM='$inmueble'";
    }
}else{
    if ($query)
    { $where = "AND UPPER($qtype) LIKE UPPER('$query%') ";}
}


$l=new Contrato();
$registros=$l->Todos($where,$sort,$start,$end);

$total = countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $id_contrato = oci_result($registros, 'ID_CONTRATO');
    $codigo_inm = oci_result($registros, 'CODIGO_INM');
    $fecha_inicio = oci_result($registros, 'FECHA_INICIO');
    $fecha_fin = oci_result($registros, 'FECHA_FIN');
    if($fecha_fin==""){
        $fecha_fin="contrato activo";
    }
    $codigo_cli = oci_result($registros, 'CODIGO_CLI');
    $alias = oci_result($registros, 'NOMBRE_CLI');
    $nombre_cli = oci_result($registros, 'NOMBRE');
    $documento = oci_result($registros, 'DOCUMENTO');
    if($documento ==""){
        $documento="sin definir";
    }
    $numero = oci_result($registros, 'RNUM');
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$id_contrato."',";
    $json .= "cell:['<b>" .$numero."</b>'";
    $json .= ",'".addslashes($id_contrato)."'";
    $json .= ",'".addslashes($codigo_inm)."'";
    $json .= ",'".addslashes($fecha_inicio)."'";
    $json .= ",'".addslashes($fecha_fin)."'";
    $json .= ",'".addslashes($codigo_cli)."'";
    $json .= ",'".addslashes($alias)."'";
    $json .= ",'".addslashes($nombre_cli)."'";
    $json .= ",'".addslashes($documento)."'";


    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>

