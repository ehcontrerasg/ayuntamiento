<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 7/14/2016
 * Time: 1:59 PM
 */
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];
//$tipo = 'pagCaj';
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

if($tipo=='pagCaj'){
    include_once "../clases/classPagos.php";
    $caja=$_POST['caja'];
    $fecIni=$_POST['fecIni'];
    $fecFin=$_POST['fecFin'];
	$proyecto=$_POST['proyecto'];
    $i=new Pagos();
    echo $i->getPagosByFechaCaja($fecIni,$fecFin,$caja,$proyecto);
}

if($tipo=='recCaj'){
    include_once "../clases/classPagos.php";
    $caja=$_POST['caja'];
    $fecIni=$_POST['fecIni'];
    $fecFin=$_POST['fecFin'];
	$proyecto=$_POST['proyecto'];
    $i=new Pagos();
    echo $i->getRecaudosByFechaCaja($fecIni,$fecFin,$caja,$proyecto);
}
