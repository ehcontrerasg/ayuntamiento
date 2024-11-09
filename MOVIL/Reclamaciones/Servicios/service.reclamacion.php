<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 9/5/2018
 * Time: 3:44 PM
 */

require_once './../Clases/class.reclamacion.php';

$a=new Reclamacion();
$datos=$a->getTipoRecl();
$i=0;
while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $reclamacion[$i]=$row;
    $i++;
}
echo json_encode($reclamacion);
