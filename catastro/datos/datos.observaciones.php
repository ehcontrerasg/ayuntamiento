<?php
include_once '../clases/class.observaciones.php';
$inmueble = ($_GET['cod_inmueble']);
$tipo = ($_REQUEST['tip']);

session_start();
$coduser = $_SESSION['codigo'];

if($tipo=='flexy'){

    function countRec($fname,$tname,$where,$sort) {
        $l=new Observaciones();
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

    if (!$sortname) $sortname = "OBS.FECHA";
    if (!$sortorder) $sortorder = "DESC";

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) $page = 1;
    if (!$rp) $rp = 1000;

    $end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
    $start = ($page) * $rp;  // MIN_ROW_TO_FETCH
    $fname ="OBS.FECHA";
    $tname="SGC_TT_OBSERVACIONES_INM OBS, SGC_TT_USUARIOS USR";
    $where = " AND OBS.INM_CODIGO='$inmueble'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query)
    { $where = " AND OBS.INM_CODIGO='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l=new Observaciones();
    $registros=$l->Todos($where, $sort, $start, $end, $inmueble);

    $total = countRec($fname, $tname, $where, $sort);
    $json = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero=oci_result($registros, 'RNUM');
        $asunto = oci_result($registros, 'ASUNTO');
        $codigo_obs = oci_result($registros, 'CODIGO_OBS');
        $descripcion =  oci_result($registros, 'DESCRIPCION');
        $fecha = oci_result($registros, 'FECHA');
        $usuario_obs = oci_result($registros, 'LOGIN');
        $consecutivo = oci_result($registros, 'CONSECUTIVO');
        if ($rc) $json .= ",";
        $json .= "\n{";
        $json .= "id:'".$consecutivo."',";
        $json .= "cell:['<b>" .$numero."</b>'";
        $json .= ",'".addslashes($consecutivo)."'";
        $json .= ",'".addslashes($asunto)."'";
        $json .= ",'".addslashes($codigo_obs)."'";
        $json .= ",'".addslashes($descripcion)."'";
        $json .= ",'".addslashes($fecha)."'";
        $json .= ",'".addslashes($usuario_obs)."'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if($tipo=='selTipo'){
    include_once '../clases/class.observaciones.php';
    $l=new Observaciones();
    $datos = $l->seltiposObs();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='ingDatos'){
    include_once '../clases/class.observaciones.php';
    $usr=$coduser;
    $asunto=$_POST['asu'];
    $desc=$_POST['des'];
    $codOb=$_POST['cod'];
    $inmueble=$_POST['inm'];
    $l=new Observaciones();
    $datos = $l->NuevaObs($usr,$asunto,$desc,$codOb,$inmueble);
    echo $datos;
}
?>