<?php
include_once '../clases/class.fechares.php';
$motivo=$_POST['id_motivo'];
$valor=$_POST['valor'];

if($valor=='fecha_res')
{
    $p=new FechaRes();
    $stid = $p->obtenerfechares($motivo);
    while (oci_fetch($stid)) {
        $fecha_res= oci_result($stid, 'FEC_RESOL');
        $html .= '<option value="'.$fecha_res.'">'.$fecha_res.'</option>';
    }oci_free_statement($stid);
    echo $html;
}


