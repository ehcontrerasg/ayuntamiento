<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 7/18/2016
 * Time: 12:14 PM
 */

$tipo = $_POST['tip'];
$tip1 = $_POST['tip1'];
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


if($tipo=='selMot'){
    include_once '../clases/classMedidores.php';
    $l=new Medidores();
    $datos = $l->getMotMant();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='autComZon') {
    $proyecto = $_POST['proy'];
    include_once "../../clases/class.zona.php";
    $user_input = trim($_POST['term']);


    $display_json = array();
    $json_arr = array();
    $user_input = strtoupper($user_input);
    $l= new Zona();
    $stid=$l->getZonByPro($proyecto,$user_input);
    oci_execute($stid, OCI_DEFAULT);
    while (oci_fetch($stid)) {

        $json_arr["id"] = oci_result($stid, 'CODIGO');
        $json_arr["value"] = oci_result($stid, 'CODIGO');
        $json_arr["label"] = oci_result($stid, 'CODIGO');
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

if($tipo=='perMax'){
    include_once '../../clases/class.periodo.php';
    $zon=$_POST['zon'];
    $l=new Periodo();
    $datos = $l->getPerMantCorrByZon($zon);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='genOrd'){
    include_once '../../clases/class.corte.php';
    $zon=$_POST['zon'];
    $per=$_POST['per'];
    $l=new Corte();
    $datos = $l->abreLoteCorte($zon,$per,$cod);
    $i=0;
    if($datos){
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"false");

    }
    echo json_encode($miArray);
}





