<?php
header('Content-Type: text/html; charset=UTF-8');
require '../Clases/class.medidor.php';

$usu=utf8_encode($_REQUEST['usu']);//
$orden=utf8_encode($_REQUEST['orden']);//
$medio='M';
$lecMedRet=utf8_encode($_REQUEST['lecMedRet']);//
$obsLec=utf8_encode($_REQUEST['obsLec']);//
$impo=utf8_encode($_REQUEST['impo']);//
$fechaIns=utf8_encode($_REQUEST['fechaIns']);//
$med=utf8_encode($_REQUEST['med']);//
$calibre= utf8_encode($_REQUEST['calibre']);//
$serial=utf8_encode($_REQUEST['serial']);//
$fechalect=utf8_encode($_REQUEST['fechalect']);//
$lectura= utf8_encode($_REQUEST['lectura']);///
$empla=utf8_encode($_REQUEST['empla']);//
$entUsr=utf8_encode($_REQUEST['entUsr']);//
$obs=utf8_encode($_REQUEST['obs']);//
$oblecIns=utf8_encode($_REQUEST['oblecIns']);//
$longitud=utf8_encode($_REQUEST['longitud']);//
$latitud=utf8_encode($_REQUEST['latitud']);//
$inm=utf8_encode($_REQUEST['inm']);//
//$fact=utf8_encode($_REQUEST['fact']);//
$fact='S';//

if($longitud=='(sin_datos)' || $longitud=="SIN_DATOS"){
	 $longitud="0";
	
}else{}

if($latitud=='(sin_datos)'||$latitud=="SIN_DATOS"){
	 $latitud="0";
	
}
	else{}


$a= new Medidor();
$resultado=$a->IngresaOrdCambioMed(
    $usu,
    $orden,
    $medio,
    $lecMedRet,
    $obsLec,
    $impo,
    $fechaIns,
    $med,
    $calibre,
    $serial,
    $lectura,
    $empla,
    $entUsr,
    $obs,
    $oblecIns,
    $longitud,
    $latitud,
    $inm,
    $fact);


	if($resultado){
		$bandera=array(array('bandera'=>'1','bandera'=>'1'));
		echo json_encode($bandera);
	}else{
		echo 'error';
	}


