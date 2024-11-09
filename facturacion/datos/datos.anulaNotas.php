<?php
session_start();
include_once '../../clases/class.notasFactura.php';
;

$coduser = $_SESSION['codigo'];
$tipo=$_POST['tipo'];

if ($tipo=='elimina'){


$nota = $_POST['inmueble'];
$proyecto = $_POST['proyecto'];
$id_nota =$_POST['idnota'];
$f=new NotasFactura();

$f->anulaNota($id_nota,$coduser);
}

if ($_POST["tipo"]=="report")
{

    $inmueble = $_POST['inmueble'];
    $l=new NotasFactura();
    $registros=$l->GetNotasFacturas($inmueble);
    $data = array();

    while (oci_fetch($registros)) {
        //$cont++;
        $cod_nota = oci_result($registros, 'ID_NOTA');
        $fac_aplica = oci_result($registros, 'FACTURA_APLICA');
        $tipo_nota = oci_result($registros, 'TIPO_NOTA');
        $val_nota = oci_result($registros, 'TOTAL_NOTA');
        $tot_factura= oci_result($registros, 'TOTAL');


        $arr = array($cod_nota,$fac_aplica, $tipo_nota, $val_nota,$tot_factura);
        array_push($data,$arr);

    }


    oci_free_statement($registros);
    echo json_encode($data);



}

?>