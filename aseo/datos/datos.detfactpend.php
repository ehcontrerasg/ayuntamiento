<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:06 PM
 */


include '../../clases/class.facturas.php';
$factura=$_GET['factura'];

//$factura=75169582;
//$fecini = '2015-08-06';
//$fecfin = '2015-08-06';

function countRec($fname,$tname,$where,$sort) {

    return 100;
}

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "CONCEPTO";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 1000;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="CONCEPTO";
$tname="SGC_TT_DETALLE_FACTURA_ASEO";
//$where = " AND FACTURA='$factura'";
//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if ($query)
{ $where = " AND FACTURA='$factura' AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$l=new facturas();
$registros=$l->ObtieneInmueble($factura);
while (oci_fetch($registros)) {
	$codinm=oci_result($registros, 'INMUEBLE');
}oci_free_statement($registros);	

$l=new facturas();
$registros=$l->detfac($where, $sort, $start, $end, $factura);
$total =countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;

while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
    $concepto=oci_result($registros, 'CONCEPTO');
    $rango = 0;
    $unidades = 1;
    $valor = oci_result($registros, 'VALOR');
	$codservicio=oci_result($registros, 'COD_SERVICIO');
	
	if ($concepto == 'Agua' || $concepto == 'Agua de Pozo'){
		if ($rango == 0) {
			$f=new facturas();
			$stidb=$f->valorRangosPdf ($codservicio,1, $codinm);
			while (oci_fetch($stidb)) {
				$valor_mt=oci_result($stidb, 'VALOR_METRO');	
			}oci_free_statement($stidb);
		}
		else{
			$f=new facturas();
			$stidb=$f->valorRangosPdf ($codservicio,$rango, $codinm);
			while (oci_fetch($stidb)) {
				$valor_mt=oci_result($stidb, 'VALOR_METRO');	
			}oci_free_statement($stidb);
		}
		$valor_mt_alc=$valor_mt * 0.2;
	}
	if ($concepto == 'Alcantarillado Red' || $concepto == 'Alcantarillado Pozo'){
		$valor_mt = $valor_mt_alc;
	}
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$factura."',";
    $json .= "cell:['" .$numero."'";
    $json .= ",'".addslashes($concepto)."'";
    $json .= ",'".addslashes($rango)."'";
    $json .= ",'".addslashes($unidades)."'";
	$json .= ",'".addslashes($valor_mt)."'";
    $json .= ",'<b style=color:#990000>".addslashes("RD$ ".$valor)."</b>'";
    $json .= "]}";
    $rc = true;
	unset($valor_mt);
}oci_free_statement($registros);	
$json .= "]\n";
$json .= "}";
echo $json;
?>