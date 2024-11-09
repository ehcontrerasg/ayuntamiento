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


if($tipo=='selPer'){
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    include_once '../../clases/class.periodo.php';
    $l=new Periodo();
    $datos = $l->obtenerperiodosMed();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selFac'){
    include_once '../../clases/class.medidor.php';
    $l=new Medidor();
    $pro=$_POST['pro'];
    $datos = $l->getFacInsByPro($pro);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}



if($tipo=='numPend'){
    include_once '../../clases/class.medidor.php';
    $l=new Medidor();
    $pro=$_POST['pro'];
    $datos = $l->getInsPendByPro($pro);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='genFac') {
    include_once'../../clases/class.medidor.php';
    $cantidad=    $_POST['can'];
    $proyecto=    $_POST['pro'];
    $periodo=    $_POST['per'];

    $i = new Medidor();
    $result = $i->GeneraFacCaasdManMed($proyecto,$cantidad,$cod,$periodo);
    if($result){
        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"false");

    }
    echo json_encode($miArray);

}