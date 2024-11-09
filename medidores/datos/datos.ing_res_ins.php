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


if($tipo=='selAre'){
    include_once '../../clases/class.areas.php';
    $l=new Area();
    $datos = $l->getAreas();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}





if($tipo=='obtDat') {
    include_once'../../clases/class.inmueble.php';
    $l = new Inmueble();
    $codSis=$_POST["inm"];
    $datos = $l->getInfoInmInsByCod($codSis);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);

}


if($tipo=='obtInsT') {
    include_once'../../clases/class.inmueble.php';
    $l = new Inmueble();
    $datos = $l->getInfoInmInsTot($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);

}

if($tipo=='ingIns') {
    include_once'../../clases/class.inmueble.php';
    $l = new Inmueble();
    $orden=$_POST["orden"];
    $resIns=$_POST["resultado"];
    $obs=$_POST["obs"];
    $lect=$_POST["lectura"];
    $pqr=$_POST["pqr"];
    $area=$_POST["dpto"];

    $result = $l->setInsMedInm($cod,$orden,$resIns,$obs,$lect,$pqr,$area);
    if($result){
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"false");

    }
    echo json_encode($miArray);

}


if($tipo=='IngObs') {
    include_once'../../clases/class.observacion.php';
    $l = new Observacion();

    $asunto=$_POST["asunto"];
    $codObs=$_POST["codObs"];
    $obs=$_POST["obs"];
    $inm=$_POST["inm"];

    $result = $l->setObsInm($asunto,$codObs,$obs,$cod,$inm);
    if($result){
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"false");

    }
    $miArray=array("error"=>"", "cod"=>"","res"=>"true");
    echo json_encode($miArray);

}


