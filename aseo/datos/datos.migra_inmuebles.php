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

if($tipo=='selSec'){
    include_once '../../clases/class.proyecto.php';
    $proyecto=$_POST["proyecto"];
    $l=new Proyecto();
    $datos = $l->obtenerSectores($proyecto);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selRut'){
    include_once '../../clases/class.proyecto.php';
    $sector=$_POST["sector"];
    $l=new Proyecto();
    $datos = $l->obtenerRutas($sector);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selCic'){
    include_once '../../clases/class.proyecto.php';
    $l=new Proyecto();
    $datos = $l->obtenerCiclos();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if ($tipo == 'selInmMig') {
    include_once '../../clases/Migra_inmuebles.php';
    $l = new Migracion();
    $proyecto = $_POST["pro"];
    $sector = $_POST["sec"];
    $ruta = $_POST["rut"];
    $manzana = $_POST["man"];
    $inmueble = $_POST["inm"];
    $sector2 = $_POST["sec2"];
    $ruta2 = $_POST["rut2"];
    $manzana2 = $_POST["man2"];
    $ciclo = $_POST["cic"];
    $datos = $l->obtieneInmueblesMigración($proyecto, $sector, $ruta, $manzana, $inmueble, $sector2, $ruta2, $manzana2, $ciclo);
    $i = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}

if ($tipo == 'selVerPro') {
    include_once '../../clases/Migra_inmuebles.php';
    $l = new Migracion();
    $inmueble = $_POST["inm"];
    $proceso = $_POST["pro"];
    $datos = $l->verificaProceso($inmueble, $proceso);
    $i = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}

if ($tipo == 'selValPro') {
    include_once '../../clases/Migra_inmuebles.php';
    $l = new Migracion();
    $inmueble = $_POST["inm"];
    $proceso = $_POST["pro"];
    $datos = $l->validaProceso($inmueble, $proceso);
    $i = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}

if ($tipo == 'selVerCat') {
    include_once '../../clases/Migra_inmuebles.php';
    $l = new Migracion();
    $inmueble = $_POST["inm"];
    $catastro = $_POST["cat"];
    $datos = $l->verificaCatastro($inmueble, $catastro);
    $i = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}

if ($tipo == 'selValCat') {
    include_once '../../clases/Migra_inmuebles.php';
    $l = new Migracion();
    $inmueble = $_POST["inm"];
    $catastro = $_POST["cat"];
    $datos = $l->validaCatastro($inmueble, $catastro);
    $i = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}

if ($tipo == 'guardMig') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include_once '../../clases/Migra_inmuebles.php';
    $l = new Migracion();
    $inm = $_POST["inm"];
    $zon = $_POST["zon"];
    $pro = $_POST["pro"];
    $cat = $_POST["cat"];

    $result = $l->guardaMigracion($inm, $zon, $pro, $cat, $cod);
    echo $result;
}