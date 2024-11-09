<?php
include '../../facturacion/clases/class.reportes.php';
 $fecini = ($_GET['fecini']);
 $fecfin = ($_GET['fecfin']);

//$fecini = '07/29/2015';
//$fecfin = '07/29/2015';

function countRec($fname,$tname,$where,$sort) {
		$l=new Reportes();
		$valores=$l->CantidadRegistrosRepMatCor($fname, $tname,$where,$sort);
	
	while (oci_fetch($valores)) {
			$cantidad = oci_result($valores, 'CANTIDAD');
				
	}oci_free_statement($valores);
	return $cantidad;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "COR.ORDEN";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="COR.ORDEN";
$tname="";
$where = "AND COR.FECHA_EJE BETWEEN  TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD hh24:mi:ss')";

if ($query)
{ $where = "AND COR.FECHA_EJE BETWEEN  TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD hh24:mi:ss') AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new Reportes();
$registros=$l->TodosRepMatCor($where, $sort, $start, $end);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
	$numero=oci_result($registros, 'RNUM');
	$sector = oci_result($registros, 'ID_SECTOR');
	$ruta = oci_result($registros, 'ID_RUTA'); 	
	$catastro = oci_result($registros, 'CATASTRO');
	$nombre = oci_result($registros, 'NOMBRE');
	$documento = oci_result($registros, 'DOCUMENTO');
	$telefono = oci_result($registros, 'TELEFONO');
	$fechaeje = oci_result($registros, 'FECHA_EJE');
	$operario = oci_result($registros, 'OPERARIO');
	$tipocorte = oci_result($registros, 'TIPOCORTE');
	$obs = oci_result($registros, 'OBS_GENERALES');
	$arena = oci_result($registros, 'ARN');
	
	$cemento = oci_result($registros, 'CMG');
	$pct = oci_result($registros, 'PCT');
	$pmg = oci_result($registros, 'PMG');
	$pmp = oci_result($registros, 'PMP');
	$ta1 = oci_result($registros, 'TA1');
	$ta112 = oci_result($registros, 'TA112');
	$ta12 = oci_result($registros, 'TA12');
	$ta2 = oci_result($registros, 'TA2');
	$ta34 = oci_result($registros, 'TA34');
	$tan = oci_result($registros, 'TAN');
	if ($rc) $json .= ",";
	$json .= "\n{";
	$json .= "id:'".$consecutivo."',";	
	$json .= "cell:['<b>" .$numero."</b>'";
	$json .= ",'".addslashes($sector)."'";
	$json .= ",'".addslashes($ruta)."'";	
	$json .= ",'".addslashes($catastro)."'";	
	$json .= ",'".addslashes($nombre)."'";
	if($documento<>9999999){
		$json .= ",'".addslashes($documento)."'";
	}else{
		$json .= ",'".addslashes("")."'";
	}
	$json .= ",'".addslashes($telefono)."'";
	$json .= ",'".addslashes($fechaeje)."'";
	$json .= ",'".addslashes($operario)."'";
	$json .= ",'".addslashes($tipocorte)."'";
	$json .= ",'".addslashes(trim(str_replace(',',' ',$obs)))."'";
	if($arena>0){$json .= ",'".addslashes($arena)."'";}else{$json .= ",'".addslashes("")."'";}
	if($cemento>0){$json .= ",'".addslashes($cemento)."'";}else{$json .= ",'".addslashes("")."'";}
	if($pct>0){$json .= ",'".addslashes($pct)."'";}else{$json .= ",'".addslashes("")."'";}
	if($pmg>0){$json .= ",'".addslashes($pmg)."'";}else{$json .= ",'".addslashes("")."'";}
	if($pmp>0){$json .= ",'".addslashes($pmp)."'";}else{$json .= ",'".addslashes("")."'";}
	if($ta1>0){$json .= ",'".addslashes($ta1)."'";}else{$json .= ",'".addslashes("")."'";}
	if($ta112>0){$json .= ",'".addslashes($ta112)."'";}else{$json .= ",'".addslashes("")."'";}
	if($ta12>0){$json .= ",'".addslashes($ta12)."'";}else{$json .= ",'".addslashes("")."'";}
	if($ta2>0){$json .= ",'".addslashes($ta2)."'";}else{$json .= ",'".addslashes("")."'";}
	if($ta34>0){$json .= ",'".addslashes($ta34)."'";}else{$json .= ",'".addslashes("")."'";}
	if($tan>0){$json .= ",'".addslashes($tan)."'";}else{$json .= ",'".addslashes("")."'";}
	
	$json .= "]}";
	$rc = true; 
}
$json .= "]\n";
$json .= "}";
echo $json;
?>