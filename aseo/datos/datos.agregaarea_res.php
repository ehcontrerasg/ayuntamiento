<?php
include '../clases/class.areares.php';
$motivo=$_POST['id_motivo'];
$valor=$_POST['valor'];
$ger_inm=$_GET['ger_inm'];
$cod_pro=$_GET['cod_pro'];
if($valor=='area_res')
{

    /*if($motivo == '104' && $cod_pro == 'SD'){
        $cod_area = 14;
        $des_area = 'GRANDES CLIENTES';
        $html .= '<option value="' . $cod_area . '">' . $des_area . '</option>';
        echo $html;
    }
    else {*/
    $p=new AreaRes();
    $stid = $p->obtenerareares($motivo, $ger_inm);
    while (oci_fetch($stid)) {
        $cod_area = oci_result($stid, 'AREA_PERTENECE');
        $des_area = oci_result($stid, 'DESC_AREA');
        $html .= '<option value="' . $cod_area . '">' . $des_area . '</option>';
    }
    oci_free_statement($stid);
    echo $html;
    //}
}


