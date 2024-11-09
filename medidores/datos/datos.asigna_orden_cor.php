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

if($tipo=='compPerf'){
    include_once '../../clases/class.usuario.php';
    $l=new Usuario();
    $url=$_POST["rut"];

    $datos = $l->getPerfUsrByusu($cod,$url);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selSec'){
    include_once '../../clases/class.sector.php';
    $l=new Sector();
    $pro=$_POST["pro"];
    $datos = $l->getSecMantCorByPro($pro);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selRutAsig'){
    include_once '../../clases/class.usuario.php';
    $l=new Usuario();
    $sec=$_POST["sec"];
    $datos = $l->getUsrMntMedBySec($sec);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='SelUsu'){
    include_once '../../clases/class.usuario.php';
    $l=new Usuario();
    $pro=$_POST["pro"];
    $datos = $l->getUsrMedByuProy($pro);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='asig'){
    include_once '../../clases/class.usuario.php';
    $l=new Usuario();
    $rut=$_POST["rut"];
    $usu=$_POST["usu"];
    $result = $l->asignaUsrMantCorr($usu,$cod,$rut);
    if($result){
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"false");

    }
    echo json_encode($miArray);



}
















