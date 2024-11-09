<?php
header('Content-Type: text/html; charset=UTF-8');
require_once './../Clases/class.conexion.php';
require_once './../Clases/class.ayuntamiento.php';
$cliente=utf8_encode($_REQUEST['cliente']);
$alto=utf8_encode($_REQUEST['alto']);
$ancho=utf8_encode($_REQUEST['ancho']);
$direccion=utf8_encode($_REQUEST['direccion']);
$observacion=utf8_encode($_REQUEST['observacion']);
$latitud= utf8_encode($_REQUEST['latitud']);
$longitud=utf8_encode($_REQUEST['longitud']);
$fechalect=utf8_encode($_REQUEST['fechalect']);
$usuario=utf8_encode($_REQUEST['idusuario']);
$tipoPublicidad=utf8_encode($_REQUEST['tipoPublicidad']);

if($longitud=='(sin_datos)' || $longitud=="SIN_DATOS"){
	 $longitud="0";
}
if($latitud=='(sin_datos)'||$latitud=="SIN_DATOS"){
	 $latitud="0";
}



$b= new ayuntamiento();
$b->setCliente($cliente);
$b->setAlto($alto);
$b->setAncho($ancho);
$b->setDireccion($direccion);
$b->setObservacion($observacion);
$b->setLatitud($latitud);
$b->setLongitud($longitud);
$b->setFechaEje($fechalect);
$b->setOperario($usuario);
$b->setTipoPublicidad($tipoPublicidad);


	$resultado=$b->guardarAyuntamiento();
	if($resultado){
		$bandera=array(array('bandera'=>'1','bandera'=>'1'));
		echo json_encode($bandera);
	}else{
		echo 'error';
	}


