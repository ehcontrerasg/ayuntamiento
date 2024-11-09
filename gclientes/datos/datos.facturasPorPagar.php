<?php
/**
 * Created by PhpStorm.
 * User: Jgutierrez
 * Date: 7/18/2016
 * Time: 12:14 PM
 */

$tipo = $_POST['tip'];
session_start();
$cod=$_SESSION['codigo'];


if($tipo=='selPro'){
    include_once '../../clases/class.proyecto.php';
    $l=new Proyecto();
    $datos = $l->obtenerProyecto($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selGru'){
    include_once '../../clases/class.grupo.php';
    $l=new Grupo();
    $datos = $l->getGrupos();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if ($tipo=='reporte')
{

    include_once '../../clases/class.factura.php';
    $l=new Factura();
    $proyecto = $_POST['proyecto'];
    $periodo_inicial = $_POST['periodo_inicial'];
    $periodo_final = $_POST['periodo_final'];
    $grupo = $_POST['grupo'];
    $codigo_sistema = $_POST['codigo_sistema'];
    $zona = $_POST['zona'];
    $documento = $_POST['documento'];

    $registros=$l->GetFactPendByProyPerGruZonDoc($proyecto,$periodo_inicial,$periodo_final,$grupo,$codigo_sistema,$zona,$documento);
    $data = array();

    while (oci_fetch($registros)) {
        //$cont++;
        $proceso = oci_result($registros, 'PROCESO');
        $codigo = oci_result($registros, 'CODIGO_INM');
        $nombre = oci_result($registros, 'NOMBRE');
        $fecha = oci_result($registros, 'FEC_EXPEDICION');
        $direccion = oci_result($registros, 'DIRECCION');
        $ncf = oci_result($registros, 'NCF');
        $factura = oci_result($registros, 'FACTURA');
        $total = oci_result($registros, 'TOTAL');


        $arr = array($proceso,$codigo,$nombre, $fecha, $direccion,$ncf,$factura,$total);
        array_push($data,$arr);

    }


    oci_free_statement($registros);
    echo json_encode($data);



}

if ($tipo=='reportePagadas')
{

    include_once '../../clases/class.factura.php';
    $l=new Factura();
    $proyecto = $_POST['proyecto'];
    $periodo_inicial = $_POST['periodo_inicial'];
    $periodo_final = $_POST['periodo_final'];
    $grupo = $_POST['grupo'];
    $codigo_sistema = $_POST['codigo_sistema'];
    $zona = $_POST['zona'];
    $documento = $_POST['documento'];

    $registros=$l->GetFactPagadasByProyPerGruZonDoc($proyecto,$periodo_inicial,$periodo_final,$grupo,$codigo_sistema,$zona,$documento);
    $data = array();

    while (oci_fetch($registros)) {
        //$cont++;
        $proceso = oci_result($registros, 'PROCESO');
        $codigo = oci_result($registros, 'CODIGO_INM');
        $nombre = oci_result($registros, 'NOMBRE');
        $fecha = oci_result($registros, 'FEC_EXPEDICION');
        $direccion = oci_result($registros, 'DIRECCION');
        $ncf = oci_result($registros, 'NCF');
        $factura = oci_result($registros, 'FACTURA');
        $total = oci_result($registros, 'TOTAL');
        $fechaPago = oci_result($registros, 'FECHA_PAGO');


        $arr = array($proceso,$codigo,$nombre, $fecha, $direccion,$ncf,$factura,$total,$fechaPago);
        array_push($data,$arr);

    }


    oci_free_statement($registros);
    echo json_encode($data);

}





















