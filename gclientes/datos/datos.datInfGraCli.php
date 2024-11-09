<?php
/**
 *     Modificado por AMOSQUEA 12/03/2018
 *     Para agregar el campo de Facturacion Original, cuando la
 *     facturacion actual ha sido modificada.
 */
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];

include_once '../clases/class.reportes_gclientes.php';
require_once '../clases/PHPExcel.php';
session_start();
$cod = $_SESSION['codigo'];

if ($tipo == 'sess') {
    $segundos = 1800; //si pasa este tiempo se detecta la inactividad del usuario en el sitio
    if (($_SESSION['tiempo'] + $segundos) < time()) {
        session_destroy();
        echo "false";
    } else {
        $_SESSION['tiempo'] = time();
        echo "true";
    }
}

if ($tipo == 'selPro') {
    $l     = new ReportesGerencia();
    $datos = $l->seleccionaAcueducto();
    $i     = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}



/*if ($tipo == 'InfGraCli') {

}*/
