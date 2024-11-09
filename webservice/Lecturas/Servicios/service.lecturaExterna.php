<?php
header('Content-Type: text/html; charset=UTF-8');
require_once './../Clases/class.conexion.php';
require_once './../Clases/class.observacion.php';
require_once './../Clases/class.lectura.php';
$idinmueble=utf8_encode($_POST['INMUEBLE']);
$lectura=utf8_encode($_POST['LECTURA']);
$lectura1=utf8_encode($_POST['LECTURA']);
$lectura2=utf8_encode($_POST['LECTURA']);
$lectura3=utf8_encode($_POST['LECTURA']);
$intentos=utf8_encode($_POST['0']);
$observacion=utf8_encode($_POST['OBSERVCION']);
$latitud= utf8_encode($_POST['LATITUD']);
$longitud=utf8_encode($_POST['LONGITUD']);
$fechalect=utf8_encode($_POST['FECHA_LECT']);
$periodo= utf8_encode($_POST['PERIODO']);
$usuario=utf8_encode($_POST['USR_IN']);
$medio=utf8_encode($_POST['MEDIO_IN']);
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
$b->setMedio($medio);


	$resultado=$b->guardarlecturanuevo2();
	if($resultado){
		$bandera=array(array('bandera'=>'1','bandera'=>'1'));
		echo json_encode($bandera);
	}else{
		echo 'error';
	}


