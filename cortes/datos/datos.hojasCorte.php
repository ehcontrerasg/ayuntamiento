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

if($tipo=="selSec")
{
    include_once '../../clases/class.sector.php';
    $q=new Sector();
    $pro=$_POST['pro'];
    $datos = $q->getSecCorByProy($pro);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $sectores[$i]=$row;
        $i++;
    }
    echo json_encode($sectores);
}

if($tipo=="selZon")
{
    include_once '../../clases/class.zona.php';
    $q=new Zona();
    $sec=$_POST['sec'];
    $datos = $q->getZonCorBySec($sec);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $zonas[$i]=$row;
        $i++;
    }
    echo json_encode($zonas);
}

if($tipo=="selOper")
{
    include_once '../../clases/class.usuario.php';
    $q=new Usuario();
    $pro=$_POST['pro'];
    $sec=$_POST['sec'];
    $zon=$_POST['zon'];
    $datos = $q->getOperCorByProSecZon($pro,$sec,$zon);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $zonas[$i]=$row;
        $i++;
    }
    echo json_encode($zonas);
}

















