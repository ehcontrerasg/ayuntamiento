<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 9/6/2018
 * Time: 9:29 AM
 */
header('Content-Type: text/html; charset=UTF-8');

require_once './../Clases/class.reclamacion.php';
$id=utf8_encode($_REQUEST['ID']);
$nombre=utf8_encode($_REQUEST['NOMBRE']);
$telefono=utf8_encode($_REQUEST['TELEFONO']);
$direccion=utf8_encode($_REQUEST['DIRECCION']);
$url_foto=utf8_encode($_REQUEST['RUTA_FOTO']);
$observacion=utf8_encode($_REQUEST['OBSERVACION']);
$latitud= utf8_encode($_REQUEST['LATITUD']);
$longitud=utf8_encode($_REQUEST['LONGITUD']);
$fecha=utf8_encode($_REQUEST['FECHA']);
$id_reclamo= utf8_encode($_REQUEST['ID_RECLAMO']);


$b= new Reclamacion();
$b->setid($id);
$b->setnombre($nombre);
$b->settelefono($telefono);
$b->setdireccion($direccion);
$b->seturl_foto($url_foto);
$b->setobservacion($observacion);
$b->setlatitud($latitud);
$b->setlongitud($longitud);
$b->setfecha($fecha);
$b->setid_reclamo($id_reclamo);


$resultado=$b->guardarlecturanuevo();
if($resultado){
    $bandera=array(array('bandera'=>'1','bandera'=>'1'));
    echo json_encode($bandera);
}else{
    echo 'error';
}