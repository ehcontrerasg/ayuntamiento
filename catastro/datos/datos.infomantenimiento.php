<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 7/18/2016
 * Time: 12:14 PM
 */
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$tipo = $_POST['tip'];
session_start();
$cod=$_SESSION['codigo'];


if($tipo=='selUso'){
    include_once '../../clases/class.uso.php';
    $l=new Uso();
    $datos = $l->getUsos();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}
if($tipo=='selAct'){
    include_once '../../clases/class.actividad.php';
    $l=new Actividad();
    $uso = $_POST['uso'];
    $datos = $l->getActividadesByUso($uso);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selEst'){
    include_once '../../clases/class.estadoInm.php';
    $l=new EstadoInm();
    $datos = $l->getEstadosByEst('');
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selEstSer'){
    include_once '../../clases/class.estadoInm.php';
    $l=new EstadoInm();
    $datos = $l->getEstadosSerByEst();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}



if($tipo=='obtFotMan'){
    include_once '../../clases/class.fotos.php';
    $inm = $_POST['inm'];
    $per = $_POST['per'];

    $l=new Foto();
    $datos = $l->getFotMantByInmPer($inm,$per);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selTarAgu'){
    include_once '../../clases/class.tarifa.php';
    $uso = $_POST['uso'];
    $servicio = $_POST['ser'];
    $proyecto = $_POST['pro'];


    $l=new Tarifa();
    $datos = $l->getTarByProUsoSer($proyecto,$uso,$servicio);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if ($tipo == 'datInmGen') {
    include_once '../../clases/class.inmueble.php';
    $l        = new Inmueble();
    $inmueble = $_REQUEST['inm'];
    $datos    = $l->GetDatInmByCod($inmueble);
    $i        = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}

if ($tipo == 'datInmGenMant') {
    include_once '../../clases/class.inmueble.php';
    $l        = new Inmueble();
    $inmueble = $_REQUEST['inm'];
    $periodo = $_REQUEST['per'];
    $datos    = $l->GetDatInmMantByCodPro($inmueble,$periodo);
    $i        = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}



if($tipo=='giarMant') {
    include_once'../../clases/class.inmueble.php';
    $inmueble=$_POST["inmueble"];
    $direccion=$_POST["direccion"];
    $uso=$_POST["uso"];
    $actividad=$_POST["actividad"];
    $unidadesH=$_POST["unidadesh"];
    $unidadesT=$_POST["unidadest"];
    $estado=$_POST["estado"];
    $tarifaAgua=$_POST["tarifaagua"];
    $telefono=$_POST["telefono"];
    $observaciones=$_POST["observaciones"];
    $tarifaAlc=$_POST["tarifaalcantarillado"];
    $periodo=$_POST["periodo"];
    $cupo=$_POST["cupo"];
    $estSer=$_POST["estServ"];

    $l = new Inmueble();
    $result = $l->actMantInm($inmueble,$direccion,$uso,$actividad,$unidadesH,$unidadesT,$estado,$tarifaAgua,$telefono,$observaciones,$tarifaAlc,$periodo,$cupo,$cod,$estSer);
    if($result){
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"false");

    }
    echo json_encode($miArray);

}

