<?php
session_start();
require'../clases/class.admin_pagos.php';
	
	function countRec($fname,$tname,$where,$sort) {
		$l=new AdminstraPagos();
		$valores=$l->CantidadCajeras($fname, $tname,$where,$sort);

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

	if (!$sortname) $sortname = "EP.COD_ENTIDAD, PP.COD_VIEJO, CP.NUM_CAJA";
	if (!$sortorder) $sortorder = "ASC";

	$sort = "ORDER BY $sortname $sortorder"; 

	if (!$page) $page = 1;
	if (!$rp) $rp = 10;

	$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
	$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
	$fname ="EP.COD_ENTIDAD";
	$tname="SGC_TP_ENTIDAD_PAGO EP, SGC_TP_PUNTO_PAGO PP, SGC_TP_CAJAS_PAGO CP, SGC_TT_USUARIOS U, SGC_TP_CARGOS CAR";
	$where="EP.COD_ENTIDAD(+) = PP.ENTIDAD_COD
                        AND CP.ID_USUARIO  = U.ID_USUARIO(+)
                        AND PP.ID_PUNTO_PAGO(+) = CP.ID_PUNTO_PAGO
                        AND PP.ACTIVO  (+) = 'S'
                        AND CP.PRIVADA (+) IN ('S')
                        AND CP.TIPO_ATENCION (+) IN ('C','A')
                        AND U.ID_CARGO=CAR.ID_CARGO(+) 
                       -- AND CAR.ID_AREA IN (9,4)
                         -- and u.ID_USUARIO not in (
    --select u.ID_USUARIO
    --from SGC_TT_USUARIOS u , SGC_TP_CAJAS_PAGO c
    --where u.ID_USUARIO=c.ID_USUARIO and c.PRIVADA='N')
    ";

	if ($query) $where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
	

	$total = countRec($fname, $tname, $where, $sort);
	$json = "";
	$json .= "{\n";
	$json .= "page: $page,\n";
	$json .= "total: $total,\n";
	$json .= "rows: [";
	$rc = false;
	$l=new AdminstraPagos();
	$registros=$l->listadoCajeras($tname,$where, $sort);
	while (oci_fetch($registros)) {
		//$numero=oci_result($registros, 'RNUM');
		$entidad = oci_result($registros, 'COD_ENTIDAD');
		$punto = oci_result($registros, 'COD_VIEJO');
		$estafeta = oci_result($registros, 'DESCRIPCION');
		$codviejo = oci_result($registros, 'COD_VIEJO');
		$idcaja = oci_result($registros, 'ID_CAJA');
		$numcaja = oci_result($registros, 'NUM_CAJA');
		$area = oci_result($registros, 'AREA');
		$iduser = oci_result($registros, 'ID_USUARIO');
		$nomuser = oci_result($registros, 'NOMBRE');
		
		
		if ($rc) $json .= ",";
		$json .= "\n{";
		$json .= "id:'".$idcaja."',";
		$json .= "cell:['<b>" .$idcaja."</b>'";
		$json .= ",'".addslashes($entidad)."'";
		$json .= ",'".addslashes($punto)."'";
		$json .= ",'".addslashes($estafeta)."'";
		$json .= ",'".addslashes($numcaja)."'";
		$json .= ",'".addslashes($area)."'";
		$json .= ",'".addslashes($nomuser)."'";
		$json .= "]}";
		$rc = true;
	}oci_free_statement($registros);
	$json .= "]\n";
	$json .= "}";
	echo $json;
?>
