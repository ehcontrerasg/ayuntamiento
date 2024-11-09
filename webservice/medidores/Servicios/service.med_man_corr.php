<?php
header('Content-Type: text/html; charset=UTF-8');
require '../Clases/class.medidor.php';

$usu=utf8_encode($_REQUEST['usu']);//
$orden=utf8_encode($_REQUEST['orden']);//
$lecMan=utf8_encode($_REQUEST['lecman']);//
$fechaMan=utf8_encode($_REQUEST['fechaman']);//
$medMan=utf8_encode($_REQUEST['medman']);//
$calMan=utf8_encode($_REQUEST['calman']);//
$serMan=utf8_encode($_REQUEST['sermal']);//
$empMan=utf8_encode($_REQUEST['empman']);//
$obsMan=utf8_encode($_REQUEST['obsman']);//
$longitud=utf8_encode($_REQUEST['longitud']);//
$latitud=utf8_encode($_REQUEST['latitud']);//
$inm=utf8_encode($_REQUEST['inm']);//
$medio='M';


if($longitud=='(sin_datos)' || $longitud=="SIN_DATOS"){
	 $longitud="0";
	
}

if($latitud=='(sin_datos)'||$latitud=="SIN_DATOS"){
	 $latitud="0";
	
}


$a= new Medidor();
$resultado=$a->IngresaMedManCorr($usu,$orden,$medio,$fechaMan,$medMan,$calMan,$serMan,$lecMan,$empMan,$obsMan,$longitud,$latitud,$inm);

	if($resultado){
		$bandera=array(array('bandera'=>'1','bandera'=>'1'));
		echo json_encode($bandera);
	}else{
		echo 'error';
	}