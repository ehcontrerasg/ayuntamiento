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


if($tipo=='genFac') {
    include_once'../../clases/class.medidor.php';
    $cantidad=    $_POST['can'];
    $proyecto=    $_POST['pro'];

    $i = new Medidor();
    $result = $i->GeneraFacCaasdManMed($proyecto,$cantidad,$cod);
    if($result){
        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"false");

    }
    echo json_encode($miArray);

}

?>