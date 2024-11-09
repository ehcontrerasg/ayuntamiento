<?php
/**
 * Created by PhpStorm.
 * User: Edwin
 * Date: 23/06/2016
 * Time: 3:21 PM
 */
//error_reporting(E_ALL);
session_start();
$tip=$_POST["tip"];
date_default_timezone_set('America/Santo_Domingo');
setlocale(LC_MONETARY, 'es_DO');



if($tip=="acu")
{
    include '../clases/class.reportes_pagos.php';
    $l=new Reportes();
    $datos = $l->seleccionaAcueducto();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $acu[$i]=$row;
        $i++;
    }
    echo json_encode($acu);
}


if($tip=="con")
{
    include '../clases/classPagos.php';
    $l=new Pagos();
    $datos = $l->seleccionaConceptoTotal();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}
