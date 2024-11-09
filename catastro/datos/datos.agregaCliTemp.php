<?php
mb_internal_encoding("UTF-8");
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




if($tipo=='selDoc'){
    include_once '../clases/class.tipodoc.php';
    $l=new Documento();
    $datos = $l->Todos();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='agrNom'){
    include_once '../clases/class.inmueble.php';
    $inm    = $_POST["inm"];
    $nom    = $_POST["nom"];
    $tipDoc = $_POST["tipDoc"];
    $doc    = $_POST["doc"];
    $tel    = $_POST["tel"];
    $l=new Inmnueble();
    $bandera = $l->agregaCliTemp($inm,$nom,$tipDoc,$doc,$tel);
    if($bandera){
        echo "true";
    }else{
        if($l->getCodRes() >0){
            echo $l->getMsError();
        }else{
            echo "false";
        }
    }

}



