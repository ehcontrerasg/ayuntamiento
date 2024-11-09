<?php

$tip=$_REQUEST['tip'];
session_start();
$cod=$_SESSION['codigo'];

include_once"../../clases/class.corte.php";
if($tip=='flexy'){

    $fecini = ($_GET['fecini']);
    $fecfin = ($_GET['fecfin']);
    $contratista = ($_GET['contratista']);
    $acueducto = ($_GET['acueducto']);

    //$fecini = '2015-08-12';
    //$fecfin = '2015-08-12';

    function countRec($fname,$tname,$where,$sort,$fini,$ffin) {
        $l=new Corte();
        $valores=$l->getCantDatDiarCorteFlexy($fname, $tname,$where,$fini,$ffin);

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

    if (!$sortname) $sortname = "RC.USR_EJE";
    if (!$sortorder) $sortorder = "ASC";

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) $page = 1;
    if (!$rp) $rp = 1000;

    $end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
    $start = ($page) * $rp;  // MIN_ROW_TO_FETCH
    $fname ="USR.ID_USUARIO";
    $tname=" SGC_TT_REGISTRO_CORTES RC, SGC_TT_INMUEBLES INM,SGC_TT_USUARIOS USR";
    $where = " RC.ID_INMUEBLE=INM.CODIGO_INM AND USR.ID_USUARIO=RC.USR_EJE AND INM.ID_PROYECTO = '$acueducto' AND USR.CONTRATISTA=$contratista AND  FECHA_EJE BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD hh24:mi:ss')";
    //$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query)
    { $where = " AND FECHA_EJE BETWEEN  TO_DATE('$this->fecini 00:00:00', 'YYYY-MM-DD hh24:mi:ss') AND TO_DATE('$this->fecfin 23:59:59', 'YYYY-MM-DD hh24:mi:ss') AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l=new Corte();

    $registros=$l->getDatDiarCorteFlexy($where, $sort, $start, $end,$fecini,$fecfin);
    $total =countRec($fname, $tname, $where, $sort,$fecini,$fecfin);
    $json = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero=oci_result($registros, 'RNUM');
        $cod_viejo = oci_result($registros, 'CEDULA');
        $nombre = oci_result($registros, 'NOMBRE');
        $cedula = oci_result($registros, 'CEDULA');
        $sector = oci_result($registros, 'SECTOR');
        $ruta = oci_result($registros, 'RUTA');
        $cantidad1 = oci_result($registros, 'CANTIDAD1');
        $fecinicial = oci_result($registros, 'FECINI');
        $fecfinal = oci_result($registros, 'FECMAX');
        if ($rc) $json .= ",";
        $json .= "\n{";
        $json .= "id:'".$consecutivo."',";
        $json .= "cell:['<b>" .$numero."</b>'";
        $json .= ",'".addslashes($cod_viejo)."'";
        $json .= ",'".addslashes($nombre)."'";
        $json .= ",'".addslashes($cedula)."'";
        $json .= ",'"."<b><a href=\"JAVASCRIPT:ruteo(".$sector.$ruta.",\'".$fecini."\',\'".$fecfin."\',\'".$cedula."\');\">" .$sector.$ruta." </a></b>"."'";
        $json .= ",'".addslashes($fecinicial)."'";
        $json .= ",'".addslashes($fecfinal)."'";
        $json .= ",'"."<b><a href=\"JAVASCRIPT:upCliente(".$sector.$ruta.",\'".$fecini."\',\'".$fecfin."\',\'".$cedula."\');\">" .$cantidad1." </a></b>"."'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;

}
if($tip=='selCon'){
    include_once '../../clases/class.contratista.php';
    $l=new Contratista();
    $datos = $l->getContratistas($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);

}

if($tip=='selAcu'){
    include_once '../../clases/class.proyecto.php';
    $p=new Proyecto();
    $datosa = $p->obtenerProyecto($cod);
    $i=0;
    while ($row = oci_fetch_array($datosa, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $pro[$i]=$row;
        $i++;
    }
    echo json_encode($pro);
}
?>

