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


if($tipo=='bajMed') {
    include_once'../../clases/class.medidor.php';
    $inm =    $_POST['codSis'];
    $mot =   $_POST['Mot'];
    $i = new Medidor();
    $result = $i->bajMedInm($mot,$cod,$inm);
    if($result){
        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"false");
   }
    echo json_encode($miArray);
    
}


