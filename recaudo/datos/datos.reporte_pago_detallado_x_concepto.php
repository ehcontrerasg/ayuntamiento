<?php
$tipo = $_POST['tip'];
session_start();
$codUser=$_SESSION['codigo'];

if ($tipo=="report")
{

    include '../../clases/class.pago.php';

    $proyecto = $_POST['proyecto'];
    $concepto  = $_POST['concepto'];
    $fechaIn = $_POST['fechaIn'];
    $fechaFn = $_POST['fechaFn'];

    $date=date_create("$fechaIn");
    $periodo = date_format($date,"Ym");


    $l=new Pago();
    $registros=$l->obtenerRepDetxCon($proyecto,$concepto,$fechaIn,$fechaFn,$periodo);
    $data = array();

    while (oci_fetch($registros)) {
        //$cont++;
        $pago = oci_result($registros, 'CODIGO');
        $fechaPago  = oci_result($registros, 'FECPAGO');
        $fecha  = oci_result($registros, 'FEREG');
        $estado = oci_result($registros, 'ESTADO');
        $fechaCorte = oci_result($registros, 'FECHA_CORTE');
        $tipoCorte = oci_result($registros, 'TIPO_CORTE');
        $importe = oci_result($registros, 'IMPORTE');
        $codigoInm = oci_result($registros, 'CODIGO_INM');
       // $nombreCliente = oci_result($registros, 'NOMBRE_CLI');
        $alias = oci_result($registros, 'ALIAS');
        $direccion = oci_result($registros, 'DIRECCION');


        $arr = array($pago,$fechaPago,$fecha,$estado,$fechaCorte,$tipoCorte,$importe,$codigoInm,$alias,$direccion);
        array_push($data,$arr);
    }

    oci_free_statement($registros);
    echo json_encode($data);

}


if($tipo=="selPro")
{
    include '../../clases/class.proyecto.php';
    $l=new Proyecto();
    $datos = $l->obtenerProyecto($codUser);
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