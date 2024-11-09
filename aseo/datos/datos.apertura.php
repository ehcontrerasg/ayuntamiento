<?php
/**
 * Created by PhpStorm.
 * User: ehcontrerasg
 * Date: 7/8/2016
 * Time: 11:01 AM
 */
$error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED;

error_reporting($error_reporting);
ini_set('display_errors', '1');

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
    oci_free_statement($datos);
    echo json_encode($con);
}

if($tipo=='maxPerNCF') {
    //$zona = $_POST['zona'];
    include_once "../clases/class.periodo.php";
    $l= new Periodo();
    //$stid=$l->obtenerMaxPerNCF($zona);
    $stid=$l->obtenerMaxPerNCF();
    $i=0;
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $res[$i]=$row;
        $i++;
    }
    oci_free_statement($stid);
    echo json_encode($res);
}



if($_GET['tip']=='autComZon') {
    $proyecto = $_GET['proyecto'];
    include_once "../../clases/class.zona.php";

// Here, we will get user input data and trim it, if any space in that user input data
    $user_input = trim($_REQUEST['term']);

// Define two array, one is to store output data and other is for display
    $display_json = array();
    $json_arr = array();

    $user_input = strtoupper($user_input);

    $l= new Zona();
    $stid=$l->obtieneZonAuto($proyecto,$user_input);
    oci_execute($stid, OCI_DEFAULT);

    while (oci_fetch($stid)) {

        $json_arr["id"] = oci_result($stid, 'ID_ZONA');
        $json_arr["value"] = oci_result($stid, 'ID_ZONA');
        $json_arr["label"] = oci_result($stid, 'ID_ZONA');
        array_push($display_json, $json_arr);

    }
    oci_free_statement($stid);
    $json_arr["id"] = "Seleccione";
    $json_arr["value"] = "Seleccione";
    $json_arr["label"] = "Seleccione";
    array_push($display_json, $json_arr);


    $jsonWrite = json_encode($display_json); //encode that search data
    print $jsonWrite;
}


if($tipo=='maxPer') {
    $zona = $_POST['zona'];
    include_once "../../clases/class.periodo.php";
    $l= new Periodo();
    $stid=$l->obtenerMaxperiodoAseo($zona);

    $i=0;
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $res[$i]=$row;
        $i++;
    }

    oci_free_statement($stid);
    echo json_encode($res);
}

if($tipo=='maxPerFecMod') {
    $zona = $_POST['zona'];
    include_once "../clases/class.periodo.php";
    $l= new Periodo();
    $stid=$l->obtenerMaxPerModFecha($zona);

    $i=0;
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $res[$i]=$row;
        $i++;
    }

    oci_free_statement($stid);
    echo json_encode($res);
}


if($tipo=='obtFec') {
    $zona = $_POST['zona'];
    //$periodo = $_POST['periodo'];
    include_once "../clases/class.periodo.php";
    $l= new Periodo();
    $stid=$l->obtenerFechas($zona);

    $i=0;
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $res[$i]=$row;
        $i++;
    }

    oci_free_statement($stid);
    echo json_encode($res);
}



if($tipo=='procPer') {
    include_once'../../clases/classAperturaZona.php';
    $zona = $_POST['zona'];
    $periodo = $_POST['periodo'];
    $i = new AperturaZona();
    $result = $i->AbrePeriodoAseo($zona, $periodo);
    if($result){
        $miArray=array("error"=>$i->getMsResult(), "cod"=>$i->getCodResult(),"res"=>"true");
    }else{
        $miArray=array("error"=>$i->getMsResult(), "cod"=>$i->getCodResult(),"res"=>"false");

    }
    echo json_encode($miArray);
}

if($tipo=='modFec') {
    include_once'../clases/classAperturaZona.php';
    $zona = $_POST['zona'];
    $periodo = $_POST['periodo'];
    $fecexp = $_POST['fecexp'];
    $fecven = $_POST['fecven'];
    $feccor = $_POST['feccor'];
    $i = new AperturaZona();
    $result = $i->ModificaFechas($zona, $periodo, $fecexp, $fecven, $feccor);
    if($result){
        $miArray=array("error"=>$i->getMsResult(), "cod"=>$i->getCodResult(),"res"=>"true");
    }else{
        $miArray=array("error"=>$i->getMsResult(), "cod"=>$i->getCodResult(),"res"=>"false");

    }
    echo json_encode($miArray);
}


if($tipo=='eliPer') {
    require'../clases/classAperturaZona.php';

    $zona=$_POST['zona'];
    $periodo=$_POST['periodo'];
    $acueducto=$_POST['proyecto'];

    $i= new AperturaZona();
    $bandera=$i->BorraPeriodo($zona,$periodo,$acueducto);
    if($bandera){
        $miArray=array("error"=>$i->getMsResult(), "cod"=>$i->getCodResult(),"res"=>"true");
    }else{
        $miArray=array("error"=>$i->getMsResult(), "cod"=>$i->getCodResult(),"res"=>"false");

    }
    echo json_encode($miArray);
}




if($tipo=='obtCantNcf') {
    $zona = $_POST['zona'];
    //$periodo = $_POST['periodo'];
    $proyecto = $_POST['proyecto'];
    include_once "../clases/class.periodo.php";
    $l= new Periodo();
    $stid=$l->obtenerCantidadNcf($zona,$proyecto);

    $i=0;
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $res[$i]=$row;
        $i++;
    }

    oci_free_statement($stid);
    echo json_encode($res);
}
