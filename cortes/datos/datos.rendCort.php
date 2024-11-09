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


if($tipo=='selCon'){
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

if($tipo=='corrCor'){
    include_once '../../clases/class.corte.php';
    $l=new Corte();
    $fecini   = $_POST["fecIni"];
    $fecfin   = $_POST["fecFin"];
    $ruta   = $_POST["ruta"];
    $periodo   = $_POST["periodo"];
    $usr   = $_POST["usr"];

    $datos = $l->getCoordCortReaByFecRutSecPer($fecini,$fecfin,$ruta,$periodo,$usr);
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
    include_once '../../clases/class.corte.php';

    $proyecto = $_POST['proyecto'];
    $sector = $_POST['sector'];
    $fecini = $_POST['fecIni'];
    $fecfin = $_POST['fecFin'];
    $contratista=$_POST['contratista'];


    $page = $_POST['page'];
    $rp = $_POST['rp'];
    $sortname = $_POST['sortname'];
    $sortorder = $_POST['sortorder'];
    $query = $_POST['query'];
    $qtype = $_POST['qtype'];

    if (!$sortname) $sortname = " TO_CHAR(MIN(RC.FECHA_EJE),'DD/MM/YYYY HH24:MI:SS') ";
    if (!$sortorder) $sortorder = "DESC";

    $sort = "ORDER BY $sortname $sortorder";
    if (!$page) $page = 1;
    if (!$rp) $rp = 300;
    $end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
    $start = ($page) * $rp;  // MIN_ROW_TO_FETCH

    //$contratista='2';

    $t=new Corte();
    $total=$t->getCantRutCorPerEje($proyecto,$sector,$fecini,$fecfin,$contratista);


    $l=new Corte();
    $stid=$l->obtenerResumenRutasCort($sort,$start,$end,$proyecto,$sector,$fecini,$fecfin,$contratista);

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
        $periodo = oci_result($stid, 'PERIODO');
        $fecha_asig = oci_result($stid, 'FECHA_PLA');
        $inicio = oci_result($stid, 'INICIO');
        $fin= oci_result($stid, 'FIN');
        $predio_total = oci_result($stid, 'CANTIDAD');
        $tiemorec=oci_result($stid, 'TIEMPO');
        $tiemPro=oci_result($stid, 'TIEMPO_PRO');
        $preHor=oci_result($stid, 'PRE_HORA');




        if ($rc) $json .= ",";
        $json .= "\n{";
        $json .= "id:'".$cod_operario.' '.$proyecto.' '.$periodo.' '.$cod_ruta.' '.$fecini.' '.$fecfin."',";
        $json .= "cell:['<b>" .$numero."</b>'";
        $json .= ",'".addslashes($nom_completo)."'";
        $json .= ",'".addslashes($cod_ruta)."'";
        $json .= ",'".addslashes($periodo)."'";
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
    include '../../clases/class.corte.php';
    $fecini   = $_POST["fecIni"];
    $fecfin   = $_POST["fecFin"];
    $ruta   = $_POST["ruta"];
    $periodo   = $_POST["periodo"];
    $usr   = $_POST["usr"];
    $contratista=$_POST['contratista'];

    $l=new Corte();
    $registros=$l->getCortReaByFecRutSecPer($fecini,$fecfin,$ruta,$periodo,$usr,$contratista);

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
        $lectura = oci_result($registros, 'LECTURA');
        $tipoCorte = oci_result($registros, 'TIPO_CORTE');
        $impoCorte = oci_result($registros, 'IMPO_COTE');
        $observacion = oci_result($registros, 'OBSERVACIONES');
        $fechCorte = oci_result($registros, 'FECHA');

        $c=new Corte();
        $totFot=$c->getCantFotCorByDiaInm($fechCorte,$inmueble,$contratista);

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
        $json2 .= ",'".addslashes($lectura)."'";
        $json2 .= ",'".addslashes($tipoCorte)."'";
        $json2 .= ",'".addslashes($impoCorte)."'";
        $json2 .= ",'".addslashes($observacion)."'";
        $json2 .= ",'".addslashes($fechCorte)."'";
        if($totFot > 0){
            $json2 .= ",'"."<b><a href=\"JAVASCRIPT:fotos(".$inmueble.",\'".$fechCorte."\',\'".$fechCorte."\');\">" ."<img src=\"../../images/camara.ico\" width=\"15\" height=\"15\"/>"." </a></b>"."'";
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




















