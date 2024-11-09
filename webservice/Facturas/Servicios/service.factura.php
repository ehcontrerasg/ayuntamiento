<?php

header('Content-Type: text/html; charset=UTF-8');

require_once './../Clases/class.conexion.php';
require_once './../Clases/class.observacion.php';
require_once './../Clases/class.factura.php';
$idinmueble=utf8_encode($_REQUEST['idinmueble']);
$observacionfact=utf8_encode($_REQUEST['observacionfact']);
$observacioncat=utf8_encode($_REQUEST['observacioncat']);
$latitud= utf8_encode($_REQUEST['latitud']);
$longitud=utf8_encode($_REQUEST['longitud']);
$fechalect=utf8_encode($_REQUEST['fechalect']);
$periodo= utf8_encode($_REQUEST['periodo']);
$usr= utf8_encode($_REQUEST['usr']);

if($longitud=='(sin_datos)' || $longitud=="SIN_DATOS"){
	 $longitud="0";
	
}

if($latitud=='(sin_datos)'||$latitud=="SIN_DATOS"){
	 $latitud="0";
	
}

		$b= new factura();
		$b->setinmueble($idinmueble);
		$b->setobservacioncat($observacioncat);
		$b->setobservacionfac($observacionfact);
		$b->setlatitud($latitud);
		$b->setlongitud($longitud);
		$b->setfecha($fechalect);
		$b->setperiodo($periodo);
		$b->setusr($usr);
		$b->guardafacturasnuevo();
		if($b->getCoderror()>0){
			echo $b->getMesrror();
		}else{
			$bandera=array(array('bandera'=>'1','bandera'=>'1'));
			echo json_encode($bandera);
		}
		
		
	


