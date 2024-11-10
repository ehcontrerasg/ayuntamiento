<?php
session_start();

include '../clases/class.rendimiento.php';
//$fecini = ($_GET['fecini']);
//$fecfin = ($_GET['fecfin']);
$cod_operario = $_GET['cod_operario'];
$cod_ruta = $_GET['cod_ruta'];
$periodo = $_GET['periodo'];
$fecini = $_GET['fecini'];
$fecfin = $_GET['fecfin'];	



  //$fecini = '13072015000000';
 // $fecfin = '13072015235959';
//$cod_operario = '223-0007771-0';
//$cod_ruta = 1401;
//$periodo = 201512;

// $fecini = '12082015091452';
// $fecfin = '12082015100914';
// $operario = '223-0025595-1';
// $ruta = '2917';
// $periodo = ($_GET['periodo']);


/*function countRec($fname,$tname,$where) {
	
	$a=new Reportes();
	$cantidad=$a->CantidaddetalleLec($fname, $tname, $where);
	return $cantidad;
}*/

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "L.FECHA_LECTURA_ORI";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
//$fname = "L.FECHA_LECTURA";
//$tname = "SGC_TT_REGISTRO_LECTURAS L, SGC_TT_INMUEBLES INM";

//if ($query) $where = " AND $qtype LIKE UPPER('$query%') ";

$a=new Rendimiento();
$cant=$a->obtenerTotalDetalle ($periodo, $fecini, $fecfin, $cod_ruta, $cod_operario);
while (oci_fetch($cant)) {
	$total = oci_result($cant, 'TOTAL');
}oci_free_statement($cant);

$l=new Rendimiento();
$registros=$l->TodosDetalleLec($sort, $start, $end, $cod_ruta, $periodo, $cod_operario,$fecini,$fecfin);
//$total = countRec("$fname","$tname","$where");
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
	$codigo_inm = oci_result($registros, 'CODIGO_INM');
	$lectura = oci_result($registros, 'LECTURA_ACTUAL');
	$consumo = oci_result($registros, 'CONSUMO');
	$observacion = oci_result($registros, 'OBSERVACION_ACTUAL');
	$latitud = oci_result($registros, 'LATITUD');
	$longitud = oci_result($registros, 'LONGITUD');
   	$id_proceso = oci_result($registros, 'ID_PROCESO');
	$direccion = oci_result($registros, 'DIRECCION');
	$fecha_lect = oci_result($registros, 'FECHA_EJECUCION');
	$numero = oci_result($registros, 'RNUM');
	
	
	

	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "id:'".$codigo_inm."',";
	$json .= "cell:['<b>" .$numero."</b>'";	
	$json .= ",'".addslashes($codigo_inm)."'";
	$json .= ",'".addslashes($id_proceso)."'";
	$json .= ",'".addslashes($direccion)."'";
	$json .= ",'".addslashes($fecha_lect)."'";
	$json .= ",'"."<b><a href=\"JAVASCRIPT:hislec(".$codigo_inm.");\">" .$lectura." </a></b>"."'";
	$json .= ",'".addslashes($observacion)."'";
	$json .= ",'".addslashes($consumo)."'";
	
	
	$f=new Rendimiento();
	$total_fotos=$f->existefotolec($codigo_inm,$cod_operario,$periodo,$fecini,$fecfin);
	$a=new Rendimiento();
	$total_coordenada=$a->existecoordenadalec($codigo_inm,$periodo,$fecini,$fecfin,$cod_operario);
	
	if($periodo != ''){
		if($total_fotos == true){
			$json .= ",'"."<b><a href=\"JAVASCRIPT:fotosperiodo(".$codigo_inm.",".$periodo.");\">" ."<img src=\"../../images/camara.ico\" width=\"15\" height=\"15\"/>"." </a></b>"."'";
		}	
		else{
			$json .= ",'"."<b></b>"."'";
		}
	}
	else{
		if($total_fotos == true){
			$json .= ",'"."<b><a href=\"JAVASCRIPT:fotosfecha(".$codigo_inm.",".$fecini.",".$fecfin.");\">" ."<img src=\"../../images/camara.ico\" width=\"15\" height=\"15\"/>"." </a></b>"."'";
		}	
		else{
			$json .= ",'"."<b></b>"."'";
		}
	}
	
	
	if($total_coordenada==true){
		$json .= ",'"."<b><a href=\"JAVASCRIPT:ubicacion(".$latitud.",".$longitud.");\">" ."<img src=\"../../images/mundo.ico\" width=\"15\" height=\"15\"/>"." </a></b>"."'";
	}else{
		$json .= ",'"."<b></b>"."'";
	}
	$json .= "]}";
	$rc = true; 
}
$json .= "]\n";
$json .= "}";
echo $json;
?>