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

if($tipo=='selTipVia'){
    include_once '../../clases/class.tipoVia.php';
    $l=new TipoVia();
    $proyecto=$_POST['pro'];
    $datos = $l->getTipoViaByPro($proyecto);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}
if($tipo=='selEstInm'){
    include_once '../../clases/class.estadoInm.php';
    $l=new EstadoInm();
    $est=$_POST['est'];
    $datos = $l->getEstadosByEst($est);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selGrupoCli'){
    include_once '../../clases/class.cliente.php';
    $l=new Cliente();
    $datos = $l->getGruposCli();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}
if($tipo=='selTipoCli'){
    include_once '../../clases/class.cliente.php';
    $l=new Cliente();
    $datos = $l->getTiposCli();
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

if($tipo=='selSum'){
    include_once './../../clases/class.suministo.php';
    $l=new Suministro();
    $datos = $l->getSuministro();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selUso'){
    include_once './../../clases/class.uso.php';
    $l=new Uso();
    $datos = $l->getUsos();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selTarifas'){
    include_once './../../clases/class.tarifa.php';
    $l=new Tarifa();

    $proyecto=$_POST['proy'];
    $uso=$_POST['uso'];
    $datos = $l->getTarifaByUsoPro($proyecto,$uso);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selAct'){
    include_once './../../clases/class.actividad.php';
    $l=new Actividad();
    $uso=$_POST['uso'];
    $datos = $l->getActividadesByUso($uso);
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



if($tipo=='inpUrbDesc'){
    include_once '../../clases/class.urbanizacion.php';
    $l=new Urbanizacion();
    $urb=$_POST['urb'];
    $proyecto=$_POST['proy'];
    $descUrb = $l->getDescUrbByCod($urb,$proyecto);
    echo $descUrb;
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

if($tipo=='autComUrb') {
    $proyecto = $_POST['proy'];
    include_once "../../clases/class.urbanizacion.php";
    $user_input = trim($_POST['term']);


    $display_json = array();
    $json_arr = array();
    $user_input = strtoupper($user_input);
    $l= new Urbanizacion();
    $stid=$l->getUrbByProParc($proyecto,$user_input);
    oci_execute($stid, OCI_DEFAULT);
    while (oci_fetch($stid)) {

        $json_arr["id"] = oci_result($stid, 'CODIGO');
        $json_arr["value"] = oci_result($stid, 'CODIGO');
        $json_arr["label"] = oci_result($stid, 'DESCRIPCION');
        $json_arr["title"] = oci_result($stid, 'DESCRIPCION');
        array_push($display_json, $json_arr);

    }
    oci_free_statement($stid);
    $json_arr["id"] = "Seleccione";
    $json_arr["value"] = "Seleccione";
    $json_arr["label"] = "Seleccione";
    $json_arr["title"] = "Seleccione";
    array_push($display_json, $json_arr);


    $jsonWrite = json_encode($display_json); //encode that search data
    print $jsonWrite;
}

if($tipo=='autComNomVia') {
    $proyecto = $_POST['proy'];
    $tipVia = $_POST['tipVia'];
    include_once "../../clases/class.via.php";
    $user_input = trim($_POST['term']);


    $display_json = array();
    $json_arr = array();
    $user_input = strtoupper($user_input);
    $l= new Via();
    $stid=$l->getViaByProTipViaParc($proyecto,$user_input,$tipVia);
    oci_execute($stid, OCI_DEFAULT);
    while (oci_fetch($stid)) {

        $json_arr["id"] = oci_result($stid, 'CODIGO');
        $json_arr["value"] = oci_result($stid, 'CODIGO');
        $json_arr["label"] = oci_result($stid, 'DESCRIPCION');
        $json_arr["title"] = oci_result($stid, 'DESCRIPCION');
        array_push($display_json, $json_arr);

    }
    oci_free_statement($stid);
    $json_arr["id"] = "Seleccione";
    $json_arr["value"] = "Seleccione";
    $json_arr["label"] = "Seleccione";
    $json_arr["title"] = "Seleccione";
    array_push($display_json, $json_arr);


    $jsonWrite = json_encode($display_json); //encode that search data
    print $jsonWrite;
}











