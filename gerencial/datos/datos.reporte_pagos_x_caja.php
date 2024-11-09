<?php



$tipo=$_POST['tipo'];
session_start();
$cod=$_SESSION['codigo'];


if ($tipo=="report")
{
    include_once '../../clases/class.pago.php';
    $proyecto=$_POST['proyecto'];
    $fecha_ini=$_POST['fecha_ini'];
    $fecha_fin=$_POST['fecha_fin'];
    $entidad=$_POST['entidad'];
    $punto=$_POST['punto'];
    $caja=$_POST['caja'];


    $l=new Pago();
    $registros=$l->GetPagDiarioByProyFechEntPuntCaj($proyecto,$fecha_ini,$fecha_fin,$entidad,$punto,$caja);
    $data = array();

    while (oci_fetch($registros)) {
        //$cont++;


        $inmueble = oci_result($registros, 'INMUEBLE');
        $fecha_pago = oci_result($registros, 'FECHA_PAGO');
        $echa_ingreso = oci_result($registros, 'FECHA_INGRESO');
        $importe = oci_result($registros, 'IMPORTE');
        $aplicado = oci_result($registros, 'APLICADO');
        $caja = oci_result($registros, 'CAJA');
        $punto = oci_result($registros, 'PUNTO');
        $entidad = oci_result($registros, 'ENTIDAD');
        $tipo = oci_result($registros, 'TIPO');
        $suministro = oci_result($registros, 'SUMINISTRO');


        $arr = array($inmueble,$fecha_pago, $echa_ingreso, $importe,$aplicado,$caja,$punto,$entidad,$tipo,$suministro);
        array_push($data,$arr);

    }


    oci_free_statement($registros);
    echo json_encode($data);



}


if($tipo=='selPro'){
   //echo "codigo : ".$cod;
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



if($tipo=='selEnt'){

    include_once '../../clases/class.entidad.php';
    $proyecto=$_POST['proyecto'];
    $l=new Entidad();
    $datos = $l->getEntidadByProyecto($proyecto);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='selPunt'){

    include_once '../../clases/class.puntoPago.php';
    $entidad=$_POST['entidad'];
    $l=new PuntoPago();
    $datos = $l->getPuntoByEntidad($entidad);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

?>