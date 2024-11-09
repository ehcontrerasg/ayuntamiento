<?php

header('Content-Type: text/html; charset=UTF-8');
require_once './../Clases/class.conexion.php';
require_once './../Clases/class.reconexion.php';
require_once './../Clases/class.observacion.php';

$idinmueble=utf8_encode($_REQUEST['idinmueble']);
$lectura=utf8_encode($_REQUEST['lectura']);
$imposibilidad=utf8_encode($_REQUEST['imporec']);
$latitud= utf8_encode($_REQUEST['latitud']);
$longitud=utf8_encode($_REQUEST['longitud']);
$fechalect=utf8_encode($_REQUEST['fechalect']);
$usreje= utf8_encode($_REQUEST['idusuario']);
$tiporec=utf8_encode($_REQUEST['tiporec']);
$impolec=utf8_encode($_REQUEST['impolect']);
$orden=utf8_encode($_REQUEST['orden']);
$obsgen=utf8_encode($_REQUEST['obsgen']);
$obsmat=utf8_encode($_REQUEST['obsmat']);



if($longitud=='(sin_datos)' || $longitud=="SIN_DATOS"){
	 $longitud="0";
}
if($latitud=='(sin_datos)'||$latitud=="SIN_DATOS"){
	 $latitud="0";
}
$b= new reconexion();
$b->setusr($usreje);
$b->settiporec($tiporec);
$b->setolectura($lectura);
$b->setlongitud($longitud);
$b->setlatitud($latitud);
$b->setinmueble($idinmueble);
$b->setimposirec($imposibilidad);
$b->setimpolec($impolec);
$b->setfecha($fechalect);
$b->setorden($orden);
$b->setobsmat($obsmat);
$b->setobsgen($obsgen);

$result=$b->guardareconexion();
if($result){
	$bandera=array(array('bandera'=>'1'));
	echo json_encode($bandera);
}
else{
	echo "error al intentar guardar reconexion";
}


