<?php

include_once '../../clases/class.ncf.php';

$tipo=$_POST['tipo'];

if ($tipo=='actualiza'){


$nfc = $_POST['nfc'];
$consecutivo = $_POST['consecutivo'];
$limite = $_POST['limite'];
$proyecto = $_POST['proyecto'];

$f=new NCF();

$f->actualizaNCF($nfc, $consecutivo, $limite,$proyecto);

}

if ($_POST["tipo"]=="report")
{


    $l=new NCF();
    $registros=$l->obtenerNCF();
    $data = array();

    while (oci_fetch($registros)) {
        //$cont++;
        $cod_ncf = oci_result($registros, 'ID_NCF');
        $con_ncf = oci_result($registros, 'CONSECUTIVO');
        $lim_ncf = oci_result($registros, 'LIMITE');
        $des_ncf = oci_result($registros, 'DESCRIPCION');
        $pro_ncf = oci_result($registros, 'PROYECTO');
        $restante = $lim_ncf-$con_ncf;


        $arr = array($cod_ncf,$pro_ncf, $con_ncf, $lim_ncf,$des_ncf,$restante);
        array_push($data,$arr);

    }


    oci_free_statement($registros);
    echo json_encode($data);



}

?>