<?php

header('Content-Type: text/html; charset=UTF-8');
require_once './../Clases/class.conexion.php';
require_once './../Clases/class.corte.php';
require_once './../Clases/class.observacion.php';

$idinmueble=utf8_encode($_REQUEST['idinmueble']);
$orden=utf8_encode($_REQUEST['orden']);
$imposibilidad=utf8_encode($_REQUEST['impocorte']);
$lectura= utf8_encode($_REQUEST['lectura']);
$latitud=utf8_encode($_REQUEST['latitud']);
$longitud=utf8_encode($_REQUEST['longitud']);
$fechalect= utf8_encode($_REQUEST['fechalect']);
$usreje= utf8_encode($_REQUEST['idusuario']);
$tipocorte=utf8_encode($_REQUEST['tipocorte']);
$impolec=utf8_encode($_REQUEST['impolect']);
$obs=utf8_encode($_REQUEST['obs']);



if($longitud=='(sin_datos)' || $longitud=="SIN_DATOS"){
	 $longitud="0";
	
}

if($latitud=='(sin_datos)'||$latitud=="SIN_DATOS"){
	 $latitud="0";
	
}

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
$b->setinmueble($idinmueble);
$b->setorden($orden);
$b->setimposicor($imposibilidad);
$b->setolectura($lectura);
$b->setlatitud($latitud);
$b->setlongitud($longitud);
$b->setfecha($fechalect);
$b->setusr($usreje);
$b->settipocorte($tipocorte);
$b->setimpolec($impolec);
$b->setobsgen($obs);
$b->settipoimpocor($tipoimpocor);
$b->settipoimpolec($tipoimpolec);

$result=$b->guardacorteRev();
if($result){
	$bandera=array(array('bandera'=>'1'));
	echo json_encode($bandera);
}

else{
	echo "error al intentar guardar revision cortes";
}


