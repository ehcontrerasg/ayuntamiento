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

if($tipo=="selUsu")
{
    include_once '../../clases/class.usuario.php';
    $q=new Usuario();
    $pro = $_POST['pro'];

    $datos = $q->getOperariosCorteByPro($pro);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $oper[$i]=$row;
        $i++;
    }
    echo json_encode($oper);
}