<?php
header('Content-Type: text/html; charset=UTF-8');
require '../Clases/class.medidor.php';

$usu=utf8_encode($_REQUEST['idusuario']);//
//$orden=utf8_encode($_REQUEST['orden']);//
$medio='M';
$secIns=utf8_encode($_REQUEST['secIns']);//
$observacion=utf8_encode($_REQUEST['observacion']);//
$longitud=utf8_encode($_REQUEST['longitud']);//
$latitud=utf8_encode($_REQUEST['latitud']);//
$fechaEje=utf8_encode($_REQUEST['fechaEje']);//


if($longitud=='(sin_datos)' || $longitud=="SIN_DATOS"){
	 $longitud="0";
	
}else{}

if($latitud=='(sin_datos)'||$latitud=="SIN_DATOS"){
	 $latitud="0";
	
}
	else{}


$a= new Medidor();
$resultado=$a->IngresaInsEstMed($secIns,$fechaEje,$observacion,$longitud,$latitud);



	if($resultado){
		$bandera=array(array('bandera'=>'1','bandera'=>'1'));
		echo json_encode($bandera);
	}else{
		echo 'error';
	}


