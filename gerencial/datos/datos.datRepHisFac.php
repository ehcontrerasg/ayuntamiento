<?php
mb_internal_encoding("UTF-8");
session_start();

include_once '../clases/class.reportes_gerenciales.php';
$tipo = $_POST['tip'];
$cod=$_SESSION['codigo'];
//$periodo = $_POST['periodo'];
//$proyecto = $_POST['proyecto'];
	

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
   $l=new ReportesGerencia();
    $datos = $l->seleccionaAcueducto($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


?>