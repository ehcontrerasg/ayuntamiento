<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 9/21/2018
 * Time: 8:41 AM
 */

$tipo = $_POST['tip'];
session_start();
$codUser=$_SESSION['codigo'];

if ($tipo=="report")
{

    include '../../clases/class.averias.php';

    $motivo = $_POST['motivo'];
    $concepto  = $_POST['concepto'];
    $fechaIn = $_POST['fechaIn'];
    $fechaFn = $_POST['fechaFn'];

    $date=date_create("$fechaIn");
    $periodo = date_format($date,"Ym");


    $a=new Averias();
    $registros=$a->obtenerAverias($motivo,$fechaIn,$fechaFn);
    $data = array();

    while (oci_fetch($registros)) {
        //$cont++;
        $id = oci_result($registros, 'ID');
        $observacion = oci_result($registros, 'OBSERVACION');
        $nombre  = oci_result($registros, 'NOMBRE');
        $telefono = oci_result($registros, 'TELEFONO');
        $direccion = oci_result($registros, 'DIRECCION');
        $latitud = oci_result($registros, 'LATITUD');
        $longitud = oci_result($registros, 'LONGITUD');
        $descr = oci_result($registros, 'DESCRIPCION');
        $fecha = oci_result($registros, 'FECHA');
        $email = oci_result($registros, 'EMAIL');

        $b=new Averias();
        $datos=$b->obtenerfOTOS($id);

        while (oci_fetch($datos)) {
            $urlFoto = oci_result($datos, 'URL_FOTO');
            $arr = array($id,$observacion,$fecha,$nombre,$telefono,$direccion,$email,$latitud,$longitud,$descr,$urlFoto);

        }

        array_push($data,$arr);
    }

    oci_free_statement($registros);
    echo json_encode($data);

}


if($tipo=="selMot")
{
    include '../../clases/class.averias.php';
    $l=new Averias();
    $datos = $l->obtenerMotivo();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $acu[$i]=$row;
        $i++;
    }
    echo json_encode($acu);
}

/*
if($tipo=="con")
{
    include '../clases/classPagos.php';
    $l=new Pagos();
    $datos = $l->seleccionaConceptoTotal();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}*/