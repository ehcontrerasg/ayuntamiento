<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 7/18/2016
 * Time: 12:14 PM
 */

$tipo = $_POST['tip'];
session_start();
$cod=$_SESSION['codigo'];


if($tipo=='selPro'){
    include_once '../../clases/class.proyecto.php';
    $l=new Proyecto();
    $datos = $l->obtenerProyecto($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='corrInstMed'){
    include_once '../../clases/class.medidor.php';
    $l=new Medidor();
    $fecha   = $_POST["fec"];
    $ruta   = $_POST["ruta"];
    $usr   = $_POST["usr"];

    $datos = $l->getCoordInstMedReaByFecRutSecPer($fecha,$ruta,$usr);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
      //  print_r($row);
        $con[$i]=$row;
        $i++;
    }
   // print_r($con);
   echo json_encode($con);
}



if($tipo=="selSec")
{
    include_once '../../clases/class.sector.php';
    $q=new Sector();
    $pro=$_POST['pro'];
    $datos = $q->getSecByPro($pro);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $sectores[$i]=$row;
        $i++;
    }
    echo json_encode($sectores);
}


if($tipo=="flexy")
{
    include_once '../../clases/class.medidor.php';

    $proyecto = $_POST['proyecto'];
    $sector = $_POST['sector'];
    $fecini = $_POST['fecIni'];
    $fecfin = $_POST['fecFin'];

    $page = $_POST['page'];
    $rp = $_POST['rp'];
    $sortname = $_POST['sortname'];
    $sortorder = $_POST['sortorder'];
    $query = $_POST['query'];
    $qtype = $_POST['qtype'];

    if (!$sortname) $sortname = " TO_CHAR(MIN(RC.FECHA_REEALIZACION),'DD/MM/YYYY HH24:MI:SS') ";
    if (!$sortorder) $sortorder = "DESC";

    $sort = "ORDER BY $sortname $sortorder";
    if (!$page) $page = 1;
    if (!$rp) $rp = 300;
    $end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
    $start = ($page) * $rp;  // MIN_ROW_TO_FETCH



    $t=new Medidor();
    $total=$t->getCantRutInstMedPerEje($proyecto,$sector,$fecini,$fecfin);


    $l=new Medidor();
    $stid=$l->obtenerResumenRutasInstMed($sort,$start,$end,$proyecto,$sector,$fecini,$fecfin);

    $json = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($stid)) {
        $numero = oci_result($stid, 'RNUM');
        $cod_operario = oci_result($stid, 'ID_USUARIO');
        $nom_completo = oci_result($stid, 'NOMBRE');
        $cod_ruta = oci_result($stid, 'RUTA');
        $fecha_asig = oci_result($stid, 'FECHA_PLA');
        $inicio = oci_result($stid, 'INICIO');
        $fin= oci_result($stid, 'FIN');
        $predio_total = oci_result($stid, 'CANTIDAD');
        $tiemorec=oci_result($stid, 'TIEMPO');
        $tiemPro=oci_result($stid, 'TIEMPO_PRO');
        $preHor=oci_result($stid, 'PRE_HORA');




        if ($rc) $json .= ",";
        $json .= "\n{";
        $json .= "id:'".$cod_operario.' '.$proyecto.' '.$cod_ruta.' '.$fecha_asig."',";
        $json .= "cell:['<b>" .$numero."</b>'";
        $json .= ",'".addslashes($nom_completo)."'";
        $json .= ",'".addslashes($cod_ruta)."'";
        $json .= ",'".addslashes($fecha_asig)."'";
        $json .= ",'".addslashes($inicio)."'";
        $json .= ",'".addslashes($fin)."'";
        $json .= ",'".addslashes($predio_total)."'";
        $json .= ",'".addslashes($tiemorec)."'";
        $json .= ",'".addslashes($tiemPro)."'";
        $json .= ",'".addslashes($preHor)."'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if($tipo=='flexyDetRend'){
    include '../../clases/class.medidor.php';
    $fec = $_POST["fec"];
    $ruta   = $_POST["ruta"];
    $usr   = $_POST["usr"];

    $l=new Medidor();
    $registros=$l->getInstMedReaByFecRutSecPer($fec,$ruta,$usr);

    $rc = false;
    $numero=0;
    while (oci_fetch($registros)) {
        $numero++;
        $inmueble = oci_result($registros, 'ID_INMUEBLE');
        $proceso = oci_result($registros, 'ID_PROCESO');
        $catastro = oci_result($registros, 'CATASTRO');
        $nombre = oci_result($registros, 'NOMBRE');
        $direccion = oci_result($registros, 'DIRECCION');
        $serial = oci_result($registros, 'SERIAL');
        $observacion = oci_result($registros, 'OBSERVACIONES');
        $fechCorte = oci_result($registros, 'FECHA');
        $orden = oci_result($registros, 'ORDEN');

        $c=new Medidor();
        $totFot=$c->getCantFotInstMedByFecInm($orden);

        if ($rc) $json2 .= ",";
        $json2 .= "\n{";
        $json2 .= "id:'".$serial."',";
        $json2 .= "cell:['<b>" .$numero."</b>'";
        $json2 .= ",'".addslashes($inmueble)."'";
        $json2 .= ",'".addslashes($proceso)."'";
        $json2 .= ",'".addslashes($catastro)."'";
        $json2 .= ",'".addslashes($nombre)."'";
        $json2 .= ",'".addslashes($direccion)."'";
        $json2 .= ",'".addslashes($serial)."'";
        $json2 .= ",'".addslashes($observacion)."'";
        $json2 .= ",'".addslashes($fechCorte)."'";
        if($totFot > 0){
            $json2 .= ",'"."<b><a href=\"JAVASCRIPT:fotos(".$orden.");\">" ."<img src=\"../../images/camara.ico\" width=\"15\" height=\"15\"/>"." </a></b>"."'";
        }
        else{
            $json2 .= ",'".$totFot."'";
        }
        $json2 .= "]}";
        $rc = true;
    }
    $json = "";
    $json .= "{\n";
    $json .= "page: 1,\n";
    $json .= "total: $numero,\n";
    $json .= "rp: $numero,\n";
    $json .= "rows: [";

    $json .= $json2;
    $json .= "]\n";
    $json .= "}";
    echo $json;
}




















