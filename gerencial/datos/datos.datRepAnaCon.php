<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

mb_internal_encoding("UTF-8");
session_start();


$tipo = $_POST['tip'];
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
    include_once '../clases/class.reportes_gerenciales.php';
    $l=new ReportesGerencia();
    $datos = $l->seleccionaAcueducto($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=="selSec")
{include_once '../clases/class.reportes_gerenciales.php';
    $pro=$_POST['pro'];
    $h=new ReportesGerencia();
    $datosa = $h->seleccionaSector($pro);
    $i=0;
    while ($row = oci_fetch_array($datosa, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $sector[$i]=$row;
        $i++;
    }
    echo json_encode($sector);
}


if($tipo=="selRut")
{
    include_once '../clases/class.reportes_gerenciales.php';
    $sec=$_POST['sec'];
    $h=new ReportesGerencia();
    $datosa = $h->seleccionaRuta($sec);
    $i=0;
    while ($row = oci_fetch_array($datosa, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $ruta[$i]=$row;
        $i++;
    }
    echo json_encode($ruta);
}

if($tipo=="selUso")
{
    include_once '../clases/class.reportes_gerenciales.php';
    //$sec=$_POST['sec'];
    //$rut=$_POST['rut'];
    $h=new ReportesGerencia();
    $datosa = $h->seleccionaUso();
    $i=0;
    while ($row = oci_fetch_array($datosa, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $usos[$i]=$row;
        $i++;
    }
    echo json_encode($usos);
}


if($tipo=="selDia")
{
    include_once '../clases/class.reportes_gerenciales.php';
    $h=new ReportesGerencia();
    $datosa = $h->seleccionaDiametro();
    $i=0;
    while ($row = oci_fetch_array($datosa, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $diametros[$i]=$row;
        $i++;
    }
    echo json_encode($diametros);
}


if($tipo=="selCon")
{
    include_once '../../clases/class.contratista.php';
    $h=new Contratista();
    $datosa = $h->getContratistas($cod);
    $i=0;
    while ($row = oci_fetch_array($datosa, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $cont[$i]=$row;
        $i++;
    }
    echo json_encode($cont);
}

?>