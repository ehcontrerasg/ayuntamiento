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

if($tipo=='selPer'){
    include_once '../../clases/class.periodo.php';
    $l=new Periodo();
    $pro=$_POST["pro"];
    $datos = $l->getPerAsiLecByPro($pro);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selZon'){
    include_once '../../clases/class.zona.php';
    $l=new Zona();
    $pro=$_POST["pro"];
    $per=$_POST["per"];
    $datos = $l->getZonAsiLecByProPer($pro,$per);
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
    $periodo=$_POST["periodo"];
    $zona=$_POST["zona"];
    $datos = $l->getUsrAsiLecByPerZon($periodo, $zona);
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
    $proyecto=$_POST["proyecto"];
    $datos = $l->getUsrLecByProy($proyecto);
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
    $result = $l->asignaUsrMantPre($usu,$cod,$rut);
    if($result){
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"false");

    }
    echo json_encode($miArray);



}