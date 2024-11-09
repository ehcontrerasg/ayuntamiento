<?php
header('Content-Type: text/html; charset=UTF-8');
require_once './../Clases/class.conexion.php';
require_once './../Clases/class.materiales.php';

$idinmueble=utf8_encode($_REQUEST['idinmueble']);
$tipocorte=utf8_encode($_REQUEST['tipocorte']);
$cod_material=utf8_encode($_REQUEST['material']);
$unidades=utf8_encode($_REQUEST['unidades']);
$periodo= utf8_encode($_REQUEST['periodo']);
$fechalect=utf8_encode($_REQUEST['fechalect']);
$orden=utf8_encode($_REQUEST['orden']);

$a=new materiales();
$resultadoc=$a->existemat($orden, $cod_material);
oci_fetch($resultadoc);
$nummat= oci_result($resultadoc, "CANTIDAD");

if($nummat==1){
	$bandera=array(array('bandera'=>'1'));
	echo json_encode($bandera);
}else{
$b= new materiales();
$b->setfecha($fechalect);
$b->setinmueble($idinmueble);
$b->setmaterial($cod_material);
$b->setorden($orden);
$b->setperiodo($periodo);
$b->settipocorte($tipocorte);
$b->setunidades($unidades);
$result=$b->guardamaterial();
if($result){
	$bandera=array(array('bandera'=>'1'));
	echo json_encode($bandera);
}
else{
	echo "error al intentar guardar cortes";
}
}