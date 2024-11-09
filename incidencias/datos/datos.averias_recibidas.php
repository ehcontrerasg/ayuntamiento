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

    include '../../clases/class.incidencias.php';

    $motivo = $_POST['motivo'];
    $concepto  = $_POST['concepto'];
    $fechaIn = $_POST['fechaIn'];
    $fechaFn = $_POST['fechaFn'];
    $id=$_POST['id'];
    $estado=$_POST['estado'];

    $date=date_create("$fechaIn");
    $periodo = date_format($date,"Ym");


    $a=new Incidencias();
    $registros=$a->obtenerAverias($motivo,$fechaIn,$fechaFn,$id,$estado);
    $data = array();

    while (oci_fetch($registros)) {
        //$cont++;
        $codigo = oci_result($registros, 'CODIGO');
        $descrEst = oci_result($registros, 'ESTADO');
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

        if($nombre==null)
            $nombre="No suministrado";
        if($telefono==null)
            $telefono="No suministrado";
        if($email==null)
            $email="No suministrado";
        if($direccion==null)
            $direccion="No suministrada";

        $arr = array("CODIGO"=>$codigo,"OBSERVACION"=>$observacion,"FECHA"=>$fecha,"NOMBRE"=>$nombre,"TELEFONO"=>$telefono,"DIRECCION"=>$direccion,"EMAIL"=>$email,"LATITUD"=>$latitud,"LONGITUD"=>$longitud,"DESCRIPCION"=>$descr,"ID"=>$id,"ESTADO"=>$descrEst);
        array_push($data,$arr);

    }

    oci_free_statement($registros);
    // print_r($data);
    echo json_encode(array("data"=>$data));

}

if ($tipo=="fotos"){

    include '../../clases/class.incidencias.php';
    $id=$_POST['id'];
    $data = array();
    $i=0;
    $b=new Incidencias();
    $datos=$b->obtenerfOTOS($id);

    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $acu[$i]=$row;
        $i++;

    }

    oci_free_statement($datos);
    echo json_encode($acu);


}

if ($tipo=="permiso"){
    include '../../clases/class.incidencias.php';

    $i=0;
    $b=new Incidencias();
    $datos=$b->obtenerPermiso($codUser);

    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $acu=oci_result($datos,'ACTIVO');
        $i++;

    }

    oci_free_statement($datos);
    echo $acu;


}

if($tipo=="selMot")
{
    include '../../clases/class.incidencias.php';
    $l=new Incidencias();
    $datos = $l->obtenerMotivo();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $acu[$i]=$row;
        $i++;
    }
    echo json_encode($acu);

}if($tipo=="selAten")
{
    include '../../clases/class.incidencias.php';
    $l=new Incidencias();
    $datos = $l->obtenerEstado();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $acu[$i]=$row;
        $i++;
    }
    echo json_encode($acu);
}

if ($tipo=="actualiza") {

    include '../../clases/class.incidencias.php';
    $id= $_POST['id'];


    $l = new Incidencias();
    $registros = $l->actEstadoAveria($id,$codUser);

    //  echo json_encode($registros);
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