<?php
include '../clases/class.inmuebles.php';
$coduser = $_SESSION['codigo'];
$proyecto = $_GET['proyecto'];
$codinmueble = $_GET['codinmueble'];
$secini = $_GET['secini'];
$secfin = $_GET['secfin'];
$zonini = $_GET['zonini'];
$zonfin = $_GET['zonfin'];
$procini = $_GET['procini'];
$procfin = $_GET['procfin'];
$urbaniza = $_GET['urbaniza'];
$tipovia = $_GET['tipovia'];
$nomvia = $_GET['nomvia'];
$estado = $_GET['estado'];
$estado_inm = $_GET['estado_inm'];
$codcliente = $_GET['codcliente'];
$nomcliente = $_GET['nomcliente'];
$numdoc = $_GET['numdoc'];
$grupo = $_GET['grupo'];
$tipocli = $_GET['tipocli'];
$numcon = $_GET['numcon'];
$fecinicon = $_GET['fecinicon'];
$fecfincon = $_GET['fecfincon'];
$marca = $_GET['marca'];
$serial = $_GET['serial'];
$emplaza = $_GET['emplaza'];
$metodo = $_GET['metodo'];
$fecinsini = $_GET['fecinsini'];
$fecinsfin = $_GET['fecinsfin'];
$mora = $_GET['mora'];
$totalizador = $_GET['totalizador'];
$concepto = $_GET['concepto'];
$uso = $_GET['uso'];
$actividad = $_GET['actividad'];
$tarifa = $_GET['tarifa'];
$numfac = $_GET['numfac'];
$tipofac = $_GET['tipofac'];
$fecinipag = $_GET['fecinipag'];
$fecfinpag = $_GET['fecfinpag'];

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
$query = $_POST['query'];
$qtype = $_POST['qtype'];

if (!$sortname) $sortname = "I.ID_SECTOR, I.ID_ZONA, I.CODIGO_INM";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$periodo = date('Ym');
$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH

//if ($query) $wherea = " AND $qtype LIKE UPPER('$query%') ";

if($proyecto != '') $where .= " AND I.ID_PROYECTO = '$proyecto'";
if($codinmueble != '') $where .= " AND I.CODIGO_INM = '$codinmueble' ";
if($secini != '' && $secfin == '') $secfin = $secini;
if($secini == '' && $secfin != '') $secini = $secfin;
if($secini != '' && $secfin != '') $where .= " AND I.ID_SECTOR BETWEEN '$secini' AND '$secfin'";
if($zonini != '' && $zonfin == '') $zonfin = $zonini;
if($zonini == '' && $zonfin != '') $zonini = $zonfin;
if($zonini != '' && $zonfin) $where .= " AND I.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
if($procini != '' && $procfin == '') $procfin = $procini;
if($procini == '' && $procfin != '') $procini = $procfin;
if($procini != '' && procfin != '') $where .= " AND I.ID_PROCESO BETWEEN '$procini' AND '$procfin'";
if($urbaniza != '') $where .= " AND I.CONSEC_URB = '$urbaniza'";
if($tipovia != '') $where .= " AND I.CALLE = '$tipovia'";
if($nomvia != '') $where .= " AND I.NOM_CALLE = '$nomvia'";
if($estado == 'A') $where .= " AND E.INDICADOR_ESTADO = 'A' AND C.FECHA_FIN IS NULL";
if($estado == 'I') $where .= " AND E.INDICADOR_ESTADO = 'I'";
/*if($estado == 'T') $where .= " AND C.FECHA_FIN IS NULL";*/
if($estado_inm != '') $where .= " AND I.ID_ESTADO = '$estado_inm'";
if($codcliente != '') $where .= " AND C.CODIGO_CLI LIKE '$codcliente%'";
if($nomcliente != '') $where .= " AND C.ALIAS LIKE UPPER('%$nomcliente%')";
if($numdoc != '') $where .= " AND L.DOCUMENTO LIKE UPPER('%$numdoc%')";
if($grupo != '') $where .= " AND L.COD_GRUPO = '$grupo'";
if($tipocli == 'G') $where .= " AND I.ID_TIPO_CLIENTE = 'GC'";
if($fecinicon != '' && $fecfincon == '') $fecfincon = $fecinicon;
if($fecinicon == '' && $fecfincon != '') $fecinicon = $fecfincon;
if($fecinicon != '' && $fecfincon) $where .= " AND C.FECHA_INICIO BETWEEN TO_DATE('$fecinicon','YYYY-MM-DD') AND TO_DATE('$fecfincon','YYYY-MM-DD')";
if($marca != '') $where .= " AND M.COD_MEDIDOR = '$marca'";
if($serial != '') $where .= " AND M.SERIAL LIKE UPPER('%$serial%')";
if($emplaza != '') $where .= " AND M.COD_EMPLAZAMIENTO = '$emplaza'";
if($metodo != '') $where .= " AND M.METODO_SUMINISTRO = '$metodo'";
if($fecinsini != '' && $fecinsfin == '') $fecinsfin = $fecinsini;
if($fecinsini == '' && $fecinsfin != '') $fecinsini = $fecinsfin;
if($fecinsini != '' && $fecinsfin) $where .= " AND M.FECHA_INSTALACION BETWEEN TO_DATE('$fecinsini','YYYY-MM-DD') AND TO_DATE('$fecinsfin','YYYY-MM-DD')";
if($mora == 'M') { 
	$where .= " AND F.INMUEBLE = I.CODIGO_INM
	AND F.DEBE_MORA = 'S'
    AND F.PERIODO >= TO_CHAR(ADD_MONTHS(TO_DATE($periodo,'YYYYMM'),-1),'YYYYMM')";
	$from = ", SGC_TT_FACTURA F";
}
if($mora == 'S') { 
	$where .= " AND F.INMUEBLE = I.CODIGO_INM
	AND F.DEBE_MORA = 'N'
    AND F.PERIODO >= TO_CHAR(ADD_MONTHS(TO_DATE($periodo,'YYYYMM'),-1),'YYYYMM')";
	$from = ", SGC_TT_FACTURA F";
}
//aca va totalizador
if($uso != '') $where .= " AND A.ID_USO = '$uso'";
if($actividad != '') $where .= " AND A.SEC_ACTIVIDAD = '$actividad'";
//aca va tarifa
if($numfac != '') {
	$where .= " AND F.INMUEBLE = I.CODIGO_INM AND F.CONSEC_FACTURA = '$numfac'";
	$from = ", SGC_TT_FACTURA F";
}
if($tipofac == 'V') {
	$where .= " AND F.INMUEBLE = I.CODIGO_INM AND F.FEC_VCTO < SYSDATE AND F.FACTURA_PAGADA = 'N' AND F.PERIODO = $periodo";
	$from = ", SGC_TT_FACTURA F";
}
if($fecinipag != '' && $fecfinpag == '') $fecfinpag = $fecinipag;
if($fecinipag == '' && $fecfinpag != '') $fecinipag = $fecfinpag;
if($fecinipag != '' && $fecfinpag) {
	$where .= " AND F.FECHA_PAGO BETWEEN TO_DATE('$fecinipag','YYYY-MM-DD') AND TO_DATE('$fecfinpag','YYYY-MM-DD')";
	$from = ", SGC_TT_FACTURA F";
}


if ($query){ 
	$where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
}
$a=new inmuebles();
$cantidad = $a->countRec($where,$from);
while (oci_fetch($cantidad)) {
	$total=oci_result($cantidad, 'CANTIDAD');
}
$l=new inmuebles();
$registros = $l->datgen($where,$sort,$start,$end,$from);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $numero=oci_result($registros, 'RNUM');
	$inmueble=oci_result($registros, 'CODIGO_INM');
    $zona=oci_result($registros, 'ID_ZONA');
    $direccion = oci_result($registros, 'DIRECCION');
    $urbaniza = oci_result($registros, 'DESC_URBANIZACION');
    $tipo_cliente = oci_result($registros, 'ID_TIPO_CLIENTE');
    $estado = oci_result($registros, 'ID_ESTADO');
	$catastro = oci_result($registros, 'CATASTRO');
	$proceso = oci_result($registros, 'ID_PROCESO');
	$contrato = oci_result($registros, 'ID_CONTRATO');
	$codcliente = oci_result($registros, 'CODIGO_CLI');
	$alias = oci_result($registros, 'ALIAS');
	$nomcliente = oci_result($registros, 'NOMBRE_CLI');
	$doccliente = oci_result($registros, 'DOCUMENTO');
	$telefono = oci_result($registros, 'TELEFONO');
	$serialmed = oci_result($registros, 'SERIAL');
	$calibremed = oci_result($registros, 'DESC_CALIBRE');
	$emplazamiento = oci_result($registros, 'COD_EMPLAZAMIENTO');
	$fecinstalacion = oci_result($registros, 'FECHA_INSTALACION');
	$metodo = oci_result($registros, 'METODO_SUMINISTRO');
	$uso = oci_result($registros, 'ID_USO');
	$acueducto = oci_result($registros, 'ID_PROYECTO');
	$fecalta = oci_result($registros, 'FEC_ALTA');
	
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$inmueble."',";
    $json .= "cell:['" .$numero."'";
	$json .= ",'".addslashes($acueducto)."'";
	$json .= ",'".addslashes($inmueble)."'";
    $json .= ",'".addslashes($zona)."'";
    $json .= ",'".addslashes($urbaniza)."'";
    $json .= ",'".addslashes($direccion)."'";
	$json .= ",'".addslashes($estado)."'";
	$json .= ",'".addslashes($catastro)."'";
	$json .= ",'".addslashes($proceso)."'";
	$json .= ",'".addslashes($codcliente)."'";
	$json .= ",'".addslashes($alias)."'";
	$json .= ",'".addslashes($doccliente)."'";
	$json .= ",'".addslashes($serialmed)."'";
	$json .= ",'".addslashes($calibremed)."'";
	$json .= ",'".addslashes($fecinstalacion)."'";
	$json .= ",'".addslashes($fecalta)."'";
	$json .= ",'".addslashes($metodo)."'";
	$json .= ",'".addslashes($uso)."'";
    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>