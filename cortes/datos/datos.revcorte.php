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

if($tipo=='revOrd') {
    include_once'../../clases/class.corte.php';
    $l = new Corte();

    $obs=$_POST["observacion"];
    $inm=$_POST["inmueble"];


    $result = $l->reversaCorte($inm,$cod,$obs);
    if($result){
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"false");

    }
    echo json_encode($miArray);

}

if($tipo=='gendif') {
    include_once'../../facturacion/clases/class.facturas.php';
    $l = new facturas();

    $valRec=$_POST["reconexion"];
    $inm=$_POST["inmueble"];


    $result = $l->aplicaDiferido2($inm,$valRec,2,$cod,'53','RD');
    if($result){
        $miArray=array("error"=>$l->getmsgresult(), "cod"=>$l->getcodresult(),"res"=>"true");
    }else{
        $miArray=array("error"=>$l->getmsgresult(), "cod"=>$l->getcodresult(),"res"=>"false");

    }
    echo json_encode($miArray);

}


