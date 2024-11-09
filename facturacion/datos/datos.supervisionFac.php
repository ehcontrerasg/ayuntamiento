<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/**
 * Created by PhpStorm.
 * User: ehcontrerasg
 * Date: 7/8/2016
 * Time: 11:01 AM
 */


$tipo = $_POST['tip'];
session_start();
$cod=$_SESSION['codigo'];

if($tipo=='sess'){
    $segundos = 1800; //si pasa este tiempo se detecta la inactividad del usuario en el sitio
    if(($_SESSION['tiempo']+$segundos) < time()) {
        session_destroy();
        echo "false";
    }else{
        $_SESSION['tiempo']=time();
        echo "true";
    }
}

if($tipo=='selPro'){
    include_once '../clases/class.proyecto.php';
    $l=new Proyecto();
    $datos = $l->obtenerproyectos($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}
if($tipo=='selZona'){
    include_once '../clases/class.Zona.php';
    $l=new Zona();
    $proyecto = $_POST['proy'];
    $datos = $l->getZonSupFacByPro($proyecto);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='selPer'){
    include_once '../clases/class.Zona.php';
    $zona = $_POST['zona'];
    $l=new Zona();
    $datos = $l->getPerSupByZona($zona);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='selRuta'){
    include_once '../clases/class.ruta.php';
    $zona = $_POST['zona'];
    $periodo = $_POST['per'];
    $l=new Ruta();
    $datos = $l->getRutaByZonPer($zona,$periodo);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}
if($tipo=='selOper'){
    include_once '../../clases/class.usuario.php';
    $l=new Usuario();
    $datos = $l->getOperariosFactura();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}



if($tipo=='maxPer') {
    $zona = $_POST['zona'];
    include_once "../clases/class.periodo.php";
    $l= new Periodo();
    $stid=$l->obtenerMaxperiodo($zona);

    $i=0;
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $res[$i]=$row;
        $i++;
    }

    oci_free_statement($stid);
    echo json_encode($res);
}

if($tipo=='genListado') {
    include_once'../clases/class.facturas.php';
    $zona=$_POST['zon'];
    $periodo=$_POST['per'];
    $ruta=$_POST['rut'];
    $muestra=$_POST['muestra'];
    $operario=$_POST['oper'];
    $asignador=$cod;
    $i = new facturas();
    $result = $i->asignaSupervision($zona,$periodo,$ruta,$muestra,$operario,$asignador);
    if($result){
        $miArray=array("error"=>$i->getmsgresult(), "cod"=>$i->getcodresult(),"res"=>"true");
    }else{
        $miArray=array("error"=>$i->getmsgresult(), "cod"=>$i->getcodresult(),"res"=>"false");

    }
    echo json_encode($miArray);
}
