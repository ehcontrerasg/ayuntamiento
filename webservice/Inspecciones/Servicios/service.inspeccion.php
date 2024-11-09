<?php

header('Content-Type: text/html; charset=UTF-8');
require_once '../Clases/class.inspecciones.php';


$usreje= utf8_encode($_REQUEST['idusuario']);
$secIns=utf8_encode($_REQUEST['secIns']);
$reconectado=utf8_encode($_REQUEST['reconectado']);
$obs=utf8_encode($_REQUEST['observacion']);
$longitud=utf8_encode($_REQUEST['longitud']);
$latitud= utf8_encode($_REQUEST['latitud']);
$fechaEje= utf8_encode($_REQUEST['fechaEje']);
$tipocorte= utf8_encode($_REQUEST['tipoCor']);

if($longitud=='(sin_datos)' || $longitud=="SIN_DATOS"){
	 $longitud="0";
}else{}

if($latitud=='(sin_datos)'||$latitud=="SIN_DATOS"){
	 $latitud="0";
}

$b= new Inspecciones();
$b->setUsr($usreje);
$b->setSecIns($secIns);
$b->setReconectado($reconectado);
$b->setObs($obs);
$b->setLongitud($longitud);
$b->setLatitud($latitud);
$b->setFechaEje($fechaEje);
$b->setTipoCor($tipocorte);

$result=$b->guardaIns();
if($result){
	$bandera=array(array('bandera'=>'1'));
	echo json_encode($bandera);
}
else{
	echo "error al intentar guardar la inspeccion ".$b->getMsError();
}


