<?php
header('Content-Type: text/html; charset=UTF-8');
require '../Clases/class.medidor.php';


$orden=utf8_encode($_REQUEST['orden']);//
$actividad=utf8_encode($_REQUEST['actividad']);//




$a= new Medidor();
$resultado=$a->IngresaActManPre($actividad,$orden);


	if($resultado){
		$bandera=array(array('bandera'=>'1','bandera'=>'1'));
		echo json_encode($bandera);
	}else{
		echo 'error';
	}


