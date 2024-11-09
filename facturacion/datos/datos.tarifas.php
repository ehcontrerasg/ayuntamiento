<?php
include '../clases/class.tarifa.php';

$proyecto=$_GET['proyecto'];

function countRec($fname,$tname,$where,$sort) {
    $l=new tarifa();
    $valores=$l->CantidadRegistros($fname, $tname,$where,$sort);

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

if (!$sortname) $sortname = "TAR.CONSEC_TARIFA";
if (!$sortorder) $sortorder = "ASC";

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 100;

$end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
$start = ($page) * $rp;  // MIN_ROW_TO_FETCH
$fname ="TAR.CONSEC_TARIFA";
$tname="SGC_TP_TARIFAS TAR";

//$fname = "USERNME";
//$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

if($proyecto!=''){
    if ($query)
    { $where = "AND TAR.COD_PROYECTO='$proyecto'  AND UPPER($qtype) LIKE UPPER('$query%') ";
    }ELSE{
        $where = "AND TAR.COD_PROYECTO='$proyecto'";
    }
}else{
    if ($query)
    { $where = "AND UPPER($qtype) LIKE UPPER('$query%') ";}
}


$l=new tarifa();
$registros=$l->obtenertarifas($where,$sort,$start,$end);

$total = countRec($fname, $tname, $where, $sort);
$json = "";
$json .= "{\n";
$json .= "page: $page,\n";
$json .= "total: $total,\n";
$json .= "rows: [";
$rc = false;
while (oci_fetch($registros)) {
    $id_tarifa = oci_result($registros, 'CONSEC_TARIFA');
    $desc_tarifa = oci_result($registros, 'DESC_TARIFA');
    $cod_servicio = oci_result($registros, 'COD_SERVICIO');
    $cod_uso = oci_result($registros, 'COD_USO');
    $codigo_tar = oci_result($registros, 'CODIGO_TARIFA');
    $cons_min = oci_result($registros, 'CONSUMO_MIN');
    $numero = oci_result($registros, 'RNUM');
    $rango1= oci_result($registros, 'RANGO_1');
    $valor1= oci_result($registros, 'VALOR_1');
    $rango2= oci_result($registros, 'RANGO_2');
    $valor2= oci_result($registros, 'VALOR_2');
    $rango3= oci_result($registros, 'RANGO_3');
    $valor3= oci_result($registros, 'VALOR_3');
    $rango4= oci_result($registros, 'RANGO_4');
    $valor4= oci_result($registros, 'VALOR_4');
    $rango5= oci_result($registros, 'RANGO_5');
    $valor5= oci_result($registros, 'VALOR_5');
    if ($rc) $json .= ",";
    $json .= "\n{";
    $json .= "id:'".$id_tarifa."',";
    $json .= "cell:['<b>" .$numero."</b>'";
    $json .= ",'".addslashes($id_tarifa)."'";
    $json .= ",'".addslashes($desc_tarifa)."'";
    $json .= ",'".addslashes($cod_servicio)."'";
    $json .= ",'".addslashes($cod_uso)."'";
    $json .= ",'".addslashes($codigo_tar)."'";
    if($cons_min==''){
        $json .= ",'".addslashes('ND')."'";
    }else{
        $json .= ",'".addslashes($cons_min)."'";
    }
    if($rango1=='') {
        $json .= ",'" . addslashes('MAX')."'";
        if($valor1<1)
        {
            $valor1 = $valor1 * 100;
            $json .= ",'" . addslashes($valor1 . '%') . "'";
        }else{
            $json .= ",'" . addslashes($valor1) . "'";
        }
    }else{
        $json .= ",'" . addslashes($rango1) . "'";
        if($valor1<1)
        {
            $valor1 = $valor1 * 100;
            $json .= ",'" . addslashes($valor1 . '%') . "'";
        }else{
            $json .= ",'" . addslashes($valor1) . "'";
        }

    }
    if($valor2=='') {
        $json .= ",'" . addslashes('ND') . "'";
        $json .= ",'" . addslashes('ND') . "'";
    }else{
        if($rango2==''){
            $json .= ",'" . addslashes('MAX') . "'";
            if($valor2<1)
            {
                $valor2 = $valor2 * 100;
                $json .= ",'" . addslashes($valor2 . '%') . "'";
            }else{
                $json .= ",'" . addslashes($valor2) . "'";
            }
        }else{
            $json .= ",'" . addslashes($rango2) . "'";
            if($valor2<1)
            {
                $valor2 = $valor2 * 100;
                $json .= ",'" . addslashes($valor2 . '%') . "'";
            }else{
                $json .= ",'" . addslashes($valor2) . "'";
            }
        }
    }
    if($valor3=='') {
        $json .= ",'" . addslashes('ND') . "'";
        $json .= ",'" . addslashes('ND') . "'";
    }else{
        if($rango3==''){
            $json .= ",'" . addslashes('MAX') . "'";
            $json .= ",'" . addslashes($valor3) . "'";
        }else{
            $json .= ",'" . addslashes($rango3) . "'";
            $json .= ",'" . addslashes($valor3) . "'";
        }
    }
    if($valor4=='') {
        $json .= ",'" . addslashes('ND') . "'";
        $json .= ",'" . addslashes('ND') . "'";
    }else{
        if($rango4==''){
            $json .= ",'" . addslashes('MAX') . "'";
            $json .= ",'" . addslashes($valor4) . "'";
        }else{
            $json .= ",'" . addslashes($rango4) . "'";
            $json .= ",'" . addslashes($valor4) . "'";
        }
    }
    if($valor5=='') {
        $json .= ",'" . addslashes('ND') . "'";
        $json .= ",'" . addslashes('ND') . "'";
    }else{
        if($rango5==''){
            $json .= ",'" . addslashes('MAX') . "'";
            $json .= ",'" . addslashes($valor5) . "'";
        }else{
            $json .= ",'" . addslashes($rango5) . "'";
            $json .= ",'" . addslashes($valor5) . "'";
        }
    }

    $json .= "]}";
    $rc = true;
}
$json .= "]\n";
$json .= "}";
echo $json;
?>