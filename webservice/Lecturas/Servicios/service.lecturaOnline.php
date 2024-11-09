<?php
header('Content-Type: text/html; charset=UTF-8');
require_once './../Clases/class.conexion.php';
require_once './../Clases/class.observacion.php';
require_once './../Clases/class.lectura.php';
$idinmueble=utf8_encode($_REQUEST['idinmueble']);
$lectura=utf8_encode($_REQUEST['lectura']);
$lectura1=utf8_encode($_REQUEST['lectura1']);
$lectura2=utf8_encode($_REQUEST['lectura2']);
$lectura3=utf8_encode($_REQUEST['lectura3']);
$intentos=utf8_encode($_REQUEST['intentos']);
$observacion=utf8_encode($_REQUEST['observacion']);
$latitud= utf8_encode($_REQUEST['latitud']);
$longitud=utf8_encode($_REQUEST['longitud']);
$fechalect=utf8_encode($_REQUEST['fechalect']);
$periodo= utf8_encode($_REQUEST['periodo']);
$usuario=utf8_encode($_REQUEST['idusuario']);

if($longitud=='(sin_datos)' || $longitud=="SIN_DATOS"){
	 $longitud="0";
	
}else{}

if($latitud=='(sin_datos)'||$latitud=="SIN_DATOS"){
	 $latitud="0";
	
}
	else{}


$a= new observacion();
$a->setdescripcion($observacion);
$codobs=$a->obtenecodigo();
$b= new lectura();
$b->setinmueble($idinmueble);
$b->setlectura1($lectura1);
$b->setlectura2($lectura2);
$b->setlectura3($lectura3);
$b->setintentos($intentos);

$b->setlectura($lectura);
$b->setobservacion($observacion);
$b->setlatitud($latitud);
$b->setlongitud($longitud);
$b->setfecha($fechalect);
$b->setperiodo($periodo);
$b->setoperario($usuario);


	$resultado=$b->guardarlecturaOnline();
	if($resultado){
		$bandera=array(array('bandera'=>'1','bandera'=>'1'));
		echo json_encode($bandera);
	}else{
		echo 'error';
	}


