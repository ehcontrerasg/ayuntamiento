<?php

header('Content-Type: text/html; charset=UTF-8');
require_once './../Clases/UrbanizacionesClass.php';
require_once './../Clases/TipoDocumentoClass.php';
require_once './../Clases/UsosClass.php';
require_once './../Clases/EstadosClass.php';
require_once './../Clases/ActividadesClass.php';
require_once './../Clases/MantenimientoClass.php';

$idinmueble=str_replace("null", "", utf8_encode($_REQUEST['idinmueble']));
$direcionnueva=str_replace("null", "",utf8_encode($_REQUEST['direccion']));
$clientenuevo=str_replace("null", "",utf8_encode($_REQUEST['cliente']));
$tipo_documento= str_replace("null", "",utf8_encode($_REQUEST['tipodocumento']));
$documentonuevo=str_replace("null", "",utf8_encode($_REQUEST['documento']));
$telefononuevo=str_replace("null", "",utf8_encode($_REQUEST['telefono']));
$tipo_uso= str_replace("null", "",utf8_encode($_REQUEST['uso']));
$tipo_actividad=str_replace("null", "",utf8_encode($_REQUEST['actividad']));
$unidadesnueva=str_replace("null", "",utf8_encode($_REQUEST['unidades']));
$unidadesh=str_replace("null", "",utf8_encode($_REQUEST['unidadesd']));
$unidadesd=str_replace("null", "",utf8_encode($_REQUEST['unidadesh']));
$des_estado=str_replace("null", "",utf8_encode($_REQUEST['estado']));
$observaciones=str_replace("null", "",utf8_encode($_REQUEST['observaciones']));
$latitudnuevo=str_replace("null", "",utf8_encode($_REQUEST['latitud']));
$longitudnuevo=str_replace("null", "",utf8_encode($_REQUEST['longitud']));
$fechaejecucion=str_replace("null", "",utf8_encode($_REQUEST['fecha']));
$actualizo=str_replace("null", "",utf8_encode($_REQUEST['actualizo']));
$fueraruta=str_replace("null", "",utf8_encode($_REQUEST['fueraruta']));
$periodo=str_replace("null", "",utf8_encode($_REQUEST['periodo']));
$diassemagua=str_replace("null", "",utf8_encode($_REQUEST['diassemagua']));
$idusuario=str_replace("null", "",utf8_encode($_REQUEST['usuario']));
$condServ=str_replace("null", "",utf8_encode($_REQUEST['condServ']));

if (strlen ( $tipo_actividad )==1 ){
    $tipo_actividad='0'.$tipo_actividad;
}






$clasemantenimiento= new MantenimientoClass();
if(($actualizo=="S" && ($condServ!="" || $unidadesnueva!="" || $unidadesd!="" || $unidadesh!="" || $des_estado!="" ||$tipo_actividad!="" || $clientenuevo!="" || $telefononuevo!="" || $tipo_documento!="" || $direcionnueva!="" || $documentonuevo!="" || $observaciones!=""|| $diassemagua!="" ))
||$diassemagua!="" ){
	$a=new MantenimientoClass();
	if($a->existe_mant($idinmueble,$periodo)){
	}else
	{
	$clasemantenimiento->actualizardatos($periodo, $idinmueble, $unidadesnueva, 0, $unidadesnueva, $des_estado, $tipo_uso, $tipo_actividad, $clientenuevo, $telefononuevo, $tipo_documento, $direcionnueva, $fueraruta, $documentonuevo, $observaciones,$diassemagua,$condServ);
	}	
}

$clasemantenimiento->actualizarasignacion($fechaejecucion,$latitudnuevo,$longitudnuevo,"1",$idinmueble,$periodo);
$bandera=array(array('bandera'=>'1','bandera'=>'1'));
echo json_encode($bandera);
