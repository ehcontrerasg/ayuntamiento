<?php
/**
 * Created by PhpStorm.
 * User: Jesus Gutierrez
 * Date: 19/09/2019
 * Time: 03:35 PM
 */
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$tipo = $_POST['tip'];
//session_start();
//$cod=$_SESSION['codigo'];

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


if($tipo=='obtDatCAASD') {
    include_once '../../clases/class.factura.php';
    $l = new Factura();
    $datos = $l->getDatCalendarioFacturacionCAASD();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='obtDatCORAABO') {
    include_once '../../clases/class.factura.php';
    $l = new Factura();
    $datos = $l->getDatCalendarioFacturacionCORAABO();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='obtDatCie') {
    include_once '../../clases/class.factura.php';
    $l = new Factura();
    $datos = $l->getDatCieCalendarioFacturacion();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}
