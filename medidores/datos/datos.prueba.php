<?php
/**
 * Created by PhpStorm.
 * User: Edwin Contreras
 * Date: 21/03/2018
 * Time: 10:17
 */
include_once '../../clases/class.prueba.php';
$cod = $_POST['codigo'];
$desc = $_POST['descripcion'];
$isUpdating = $_POST['isUpdating'];
$PRUEBA = new Prueba();

if($cod || $desc) {
    if($cod && $desc) {
        if($isUpdating) {
            $datos = $PRUEBA->updateDatosPrueba($cod, $desc);
            $i=0;
            while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
                $con[$i]=$row;
                $i++;
            }
            echo json_encode($con);
        } else {
            $datos = $PRUEBA->insertDatosPrueba($cod, $desc);
            $i=0;
            while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
                $con[$i]=$row;
                $i++;
            }
            echo json_encode($con);
        }
    } else if ($cod) {
        $datos = $PRUEBA->getDatosPrueba($cod);
        $i=0;
        while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $con[$i]=$row;
            $i++;
        }
        echo json_encode($con);
    }
} else {
    $datos = $PRUEBA->getCodes();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row['CODIGO'];
        $i++;
    }
    echo json_encode($con);
}