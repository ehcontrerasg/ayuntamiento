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

if($tipo=='selOpe'){
    include_once '../../clases/class.usuario.php';
    $cont=$_POST["cont"];
    $l=new Usuario();
    $datos = $l->getUsuariosByContratista($cont);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='impOrd'){
   //include '../clases/class.medidor_inmueble.php';
    $proyecto = $_POST["proyecto"];
    $proIni   = $_POST["proini"];
    $proFin   = $_POST["profin"];
    $codSis   = $_POST["codsis"];
    $manIni   = $_POST["manini"];
    $manFin   = $_POST["manfin"];
    $medidor  = $_POST["medido"];
    $estInm   = $_POST["estado"];
    $motivo   = $_POST["motivo"];
    $usr_asignado   = $_POST["usr_asignado"];
    $desc   = $_POST["desc"];
    echo("1");
//    $l=new Medidor_inmueble();
//    $bandera=$l->generaOrdenesCambioInstMedi($proyecto,$proIni,$proFin,$codSis,$manIni,$manFin,$medidor,$estInm,$cod,$motivo,$usr_asignado,$cod,$desc);
//    if($bandera){
//        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"true");
//    }else{
//        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"false");
//    }
//    echo json_encode($miArray);
}
