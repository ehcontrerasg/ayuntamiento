<?php
header('Content-Type: text/html; charset=UTF-8');
require_once './../Clases/class.conexion.php';
require_once './../Clases/class.materiales.php';

$idinmueble=utf8_encode($_REQUEST['idinmueble']);
$tiporec=utf8_encode($_REQUEST['tiporec']);
$cod_material=utf8_encode($_REQUEST['material']);
$unidades=utf8_encode($_REQUEST['unidades']);
$periodo= utf8_encode($_REQUEST['periodo']);
$fechalect=utf8_encode($_REQUEST['fechalect']);
$orden=utf8_encode($_REQUEST['orden']);

$b= new materiales();
$b->setfecha($fechalect);
$b->setinmueble($idinmueble);
$b->setmaterial($cod_material);
$b->setorden($orden);
$b->setperiodo($periodo);
$b->settiporec($tiporec);
$b->setunidades($unidades);
$result=$b->guardamaterial();
if($result){
	$bandera=array(array('bandera'=>'1'));
	echo json_encode($bandera);
}
else{
	echo "error al intentar guardar cortes";
}