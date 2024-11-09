<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 6/15/2016
 * Time: 10:32 AM
 */
include_once("../clases/class.pagos.php");
$tipo=$_POST["tip"];
if($tipo=="tipPago"){

    $pago=$_POST["pag"];
    $p=new Pago();
    $datos = $p->formpago($pago);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $fpagos[$i]=$row;
        $i++;
    }
    echo json_encode($fpagos);
}


if($tipo=="ubPago"){

    $pago=$_POST["pag"];
    $p=new Pago();
    $datos = $p->lugPago($pago);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $upagos[$i]=$row;
        $i++;
    }
    echo json_encode($upagos);
}