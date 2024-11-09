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

if($tipo=='lleDat'){
    include_once '../../clases/class.medidor.php';
    $inm=$_POST["inm"];
    $l=new Medidor();
    $datos = $l->getDatInmMedByInm($inm);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selObs'){
    include_once '../../clases/class.lectura.php';
    $l=new Lectura();
    $datos = $l->getObsLecMed();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='selMot'){
    include_once '../../clases/class.medidor.php';
    $l=new Medidor();
    $datos = $l->getImpEjeOrd();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='selCal'){
    include_once '../../clases/class.calibre.php';
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
    include_once '../../clases/class.emplazamiento.php';
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
    include_once '../../clases/class.medidor.php';
    $l=new Medidor();
    $datos = $l->getMedidores();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='ingOrd') {
    include_once'../../clases/class.medidor.php';
    $orden =    $_POST['ord'];
    $lecRet =   $_POST['lecRet'];
    $obsLec =   $_POST['obsLec'];
    $motImp =   $_POST['motImp'];
    $fecIns =   $_POST['fecIns'];
    $med =      $_POST['med'];
    $cal =      $_POST['cal'];
    $ser =      $_POST['ser'];
    $lect =     $_POST['lect'];
    $emp =      $_POST['emp'];
    $entUsr =   $_POST['entUsr'];
    $obIns=     $_POST['obIns'];
    $obLecIns=  $_POST['obLecIns'];
    $inm=       $_POST['inm'];
    $fact=      $_POST['fact'];
    $i = new Medidor();
    $result = $i->IngresaOrdCambioMed($cod,$orden,'W',$lecRet,$obsLec,$motImp,$fecIns,$med,$cal,$ser,$lect,$emp,$entUsr,$obIns,$obLecIns,0,0,$inm,$fact);
    if($result){
        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"false");

    }
    echo json_encode($miArray);

}

