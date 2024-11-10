<?php
session_start();
include '../clases/class.cliente.php';


function countRec($fname,$tname,$where,$sort) {
    $l=new Cliente();
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

if (!$sortname) $sortname = "CLI.CODIGO_CLI";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="CLI.CODIGO_CLI";
$tname="SGC_TT_CLIENTES CLI , SGC_TP_TIPODOC DOC, SGC_TP_GRUPOS GRU";


if ($query) $where = " AND UPPER($qtype) LIKE UPPER('$query%') ";
$l=new Cliente();
$registros=$l->Todos($where, $sort, $start, $end);

$total = countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $codigo = oci_result($registros, 'ID');
    $nombre = oci_result($registros, 'NOMBRE_CLI');
    $direccion = oci_result($registros, 'DIRECCION');
    if($direccion==""){
        $direccion="sin definir";
    }
    $telefono = oci_result($registros, 'TELEFONO');
    if($telefono==""){
        $telefono="sin definir";
    }
    $mail = oci_result($registros, 'EMAIL');
    if($mail==""){
        $mail="sin definir";
    }
    $tipodoc = oci_result($registros, 'TIPO_DOC');
    $documento = oci_result($registros, 'DOCUMENTO');
    if($documento==""){
        $documento="sin definir";
    }
    $grupo = oci_result($registros, 'DESC_GRUPO');
    $dircorres = oci_result($registros, 'DIR_CORRESPONDENCIA');
    if($dircorres==""){
        $dircorres="sin definir";
    }
    $correspondencia = oci_result($registros, 'CORRESPONDENCIA');
    if($correspondencia==""){
        $correspondencia="sin definir";
    }
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$codigo."',";
    $json .= "cell:['<b>" .$numero."</b>'";
    $json .= ",'".addslashes($codigo)."'";
    $json .= ",'".addslashes($nombre)."'";
    $json .= ",'".addslashes($direccion)."'";
    $json .= ",'".addslashes($telefono)."'";
    $json .= ",'".addslashes($mail)."'";
    $json .= ",'".addslashes($tipodoc)."'";
    $json .= ",'".addslashes($documento)."'";
    $json .= ",'".addslashes($grupo)."'";
    $json .= ",'".addslashes($dircorres)."'";
    $json .= ",'".addslashes($correspondencia)."']";
    $json .= "}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>
