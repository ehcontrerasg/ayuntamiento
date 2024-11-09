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


if($tipo=='lleDat'){
    include_once '../../clases/class.inmueble.php';
    $inm=$_POST["inm"];
    $l=new Inmueble();
    $datos = $l->getInfoInmMedByInm($inm);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='camDat'){
    include_once '../../clases/class.medidor.php';
    $inm=$_POST["codSis"];
    $med=$_POST["med"];
    $cal=$_POST["cal"];
    $emp=$_POST["emp"];
    $ser=$_POST["ser"];
    $mot=$_POST["Mot"];
    $l=new Medidor();

    $result = $l->actInfoMed($inm,$med,$cal,$emp,$ser,$mot,$cod);
    if($result){
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"false");
    }
    echo json_encode($miArray);
}


if($tipo=='selCal'){
    include_once './../../clases/class.calibre.php';
    $l=new Calibre();
    $datos = $l->getCalibres();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selEmp'){
    include_once './../../clases/class.emplazamiento.php';
    $l=new Emplazamiento();
    $datos = $l->getEmplazamiento();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selMed'){
    include_once './../../clases/class.marcaMedidor.php';
    $l=new MarcaMedidor();
    $datos = $l->getMarcMed();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


