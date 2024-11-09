<?php
include '../clases/class.via.php';
include '../clases/class.sector.php';
include '../clases/class.ruta.php';
include '../clases/class.zona.php';
include '../clases/class.actividad.php';
include '../clases/class.uso.php';
include '../clases/class.tarifa.php';
$proyecto=$_POST['id_proyecto'];
$tipo=$_POST['tipo'];
$sector=$_POST['sector'];
$uso=$_POST['uso'];
$concepto=$_POST['concepto'];
$tarifa=$_POST['tarifa'];

if($tipo=='tipo_via')
{
	$p=new Via();
	$stid = $p->obtenervia($proyecto);
	$html .= '<option value=""></option>';
	while (oci_fetch($stid)) {
		$cod_via= oci_result($stid, 'ID_TIPO_VIA') ;
		$tvia = oci_result($stid, 'DESC_TIPO_VIA') ;
		$html .= '<option value="'.$cod_via.'">'.$tvia.'</option>';
	}oci_free_statement($stid);
	echo $html;
}

if($tipo=='sector')
{
	$p=new Sector();				   
	$stid = $p->obtenersectores($proyecto);
	$html .= '<option value=""></option>';
	while (oci_fetch($stid)) {
		$cod_sector= oci_result($stid, 'ID_SECTOR') ;
		$html .= '<option value="'.$cod_sector.'">'.$cod_sector.'</option>';
	}oci_free_statement($stid);
	echo $html;
}

if($tipo=='ruta')
{
	$p=new Ruta();				   
	$stid = $p->obtenerrutas($proyecto, $sector);
	$html .= '<option value=""></option>';
	while (oci_fetch($stid)) {
		$cod_ruta= oci_result($stid, 'ID_RUTA') ;	
		$html .= '<option value="'.$cod_ruta.'">'.$cod_ruta.'</option>';
	}oci_free_statement($stid);
	echo $html;
}

if($tipo=='zona')
{
	$p=new Zona();				 
	$stid = $p->obtenerzonas($proyecto, $sector);
	$html .= '<option value=""></option>';
	while (oci_fetch($stid)) 
	{
		$cod_zona= oci_result($stid, 'ID_ZONA') ;	
		$html .= '<option value="'.$cod_zona.'">'.$cod_zona.'</option>';
	}oci_free_statement($stid);
	echo $html;
}

if($tipo=='actividad')
{
	$p=new Actividad() ;				 
	$stid = $p->obteneractividades($uso);
	$html .= '<option value=""></option>';
	while (oci_fetch($stid)) {
		$desc_actividad= oci_result($stid, 'DESC_ACTIVIDAD') ;
		$id_actividad= oci_result($stid, 'SEC_ACTIVIDAD') ;
		$html .= '<option value="'.$id_actividad.'">'.$desc_actividad.'</option>';
	}oci_free_statement($stid);
	echo $html;
}

if($tipo=='uso')
{
	$p=new Uso() ;
	$stid = $p->obtenerusoespecifico($uso);
	while (oci_fetch($stid)) {
		$desc_uso= oci_result($stid, 'DESC_USO') ;
		$id_uso= oci_result($stid, 'ID_USO') ;
		$html .= '<option value="'.$id_uso.'">'.$desc_uso.'</option>';
	}oci_free_statement($stid);
	echo $html;
}

if($tipo=='tarifa')
{
	$p=new Tarifa();
	$p->setcodconcepto($concepto);
	$p->setcodproyecto($proyecto);
	$p->setcoduso($uso);			   
	$stid = $p->obtenertarifa();
	$html .= '<option value=""></option>';
	while (oci_fetch($stid)) {
		$desc_tarifa= oci_result($stid, 'DESC_TARIFA') ;
		$id_tarifa= oci_result($stid, 'CONSEC_TARIFA') ;
		$html .= '<option value="'.$id_tarifa.'">'.$desc_tarifa.'</option>';
	}oci_free_statement($stid);
	echo $html;
}

if($tipo=='cupobas')
{
	$p=new Tarifa();
	$p->settarifa($tarifa);
	$stid = $p->obtenercupobasico2();
	while (oci_fetch($stid))
	{
		$cupobasico= oci_result($stid, 'CONSUMO_MIN') ;
		
	}
	echo $cupobasico;
	oci_free_statement($stid);
}


