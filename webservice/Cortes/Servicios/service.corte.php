<?php

header('Content-Type: text/html; charset=UTF-8');
require_once './../Clases/class.conexion.php';
require_once './../Clases/class.corte.php';
require_once './../Clases/class.observacion.php';

$idinmueble=utf8_encode($_REQUEST['idinmueble']);
$lectura=utf8_encode($_REQUEST['lectura']);
$imposibilidad=utf8_encode($_REQUEST['impocorte']);
$latitud= utf8_encode($_REQUEST['latitud']);
$longitud=utf8_encode($_REQUEST['longitud']);
$fechalect=utf8_encode($_REQUEST['fechalect']);
$periodo= utf8_encode($_REQUEST['periodo']);
$usreje= utf8_encode($_REQUEST['idusuario']);
$tipocorte=utf8_encode($_REQUEST['tipocorte']);
$impolec=utf8_encode($_REQUEST['impolect']);
$orden=utf8_encode($_REQUEST['orden']);
$obsgen=utf8_encode($_REQUEST['obsgen']);
$obsmat=utf8_encode($_REQUEST['obsmat']);


if($longitud=='(sin_datos)' || $longitud=="SIN_DATOS"){
	 $longitud="0";
	
}else{}

if($latitud=='(sin_datos)'||$latitud=="SIN_DATOS"){
	 $latitud="0";
	
}
else{}
if($impolec==""||$impolec==" "){
	$tipoimpolec="";
	$impolec="";
}else{
	$tipoimpolec="L";
}
if($imposibilidad==""||$imposibilidad==" "){
	$tipoimpocor="";
	$imposibilidad="";
}else{
	$tipoimpocor="C";
}

$b= new corte();
$b->setusrviejo($idusrviejo);
$b->setusr($usreje);
$b->settipoimpolec($tipoimpolec);
$b->settipoimpocor($tipoimpocor);
$b->settipocorte($tipocorte);
$b->setperiodo($periodo);
$b->setolectura($lectura);
$b->setlongitud($longitud);
$b->setlatitud($latitud);
$b->setinmueble($idinmueble);
$b->setimposicor($imposibilidad);
$b->setimpolec($impolec);
$b->setfecha($fechalect);
$b->setorden($orden);
$b->setobsmat($obsmat);
$b->setobsgen($obsgen);
$result=$b->guardacorte();
if($result){
	$bandera=array(array('bandera'=>'1'));
	echo json_encode($bandera);
}

else{
	echo "error al intentar guardar cortes";
}


