<?php

extract($_POST);
session_start();
$usuario = $_SESSION['codigo'];
include_once  '../../clases/fsubmit.php';

if($tip == "getPagosAutomaticos"){
    //$tipo_pago = "no aplicados";
    include_once  "../clases/class.domiciliacion.php";
    $d = new Domiciliacion();
    $datos = $d->getPagosAutomaticos($fechaDesde,$fechaHasta,$tipoPago);
    $data  = [];

    //$boton_anular = "<button class='btnAnularPago btn btn-primary'>Anular Pago</button>";
    while($row = oci_fetch_assoc($datos)){

       $arr = [$row["CODIGO_REFERENCIA"], $row["INM_CODIGO"],$row["PROYECTO"],$row["MONTO"], $row["FECHA"],$row["ID_PAGO"],$row["ID_RECAUDO"],$row["MENSAJE_RESPUESTA"]/*,$row["ESTADO_PAGO"]*/ ];

       array_push($data,$arr);
    }
    echo json_encode($data);


}

if($tip == "getIntentoPagos" ){
    include_once  "../clases/class.domiciliacion.php";
    $d = new Domiciliacion();
    $datos = $d->getIntentosPagos($fechaDesde,$fechaHasta);

    $data = [];
    while ($row = oci_fetch_assoc($datos)){
        $codigo_referencia = $row["CODIGO_REFERENCIA"];
        $codigo_inmueble   = $row["INM_CODIGO"];
        $proyecto          = $row["PROYECTO"];
        $fecha_desde       = $row["FECHA"];
        $monto             = $row["MONTO"];
        $mensaje_respuesta = $row["MENSAJE_RESPUESTA"];

        $arr = [$codigo_referencia, $codigo_inmueble, $proyecto, $monto, $fecha_desde,'' ,'',$mensaje_respuesta];
        array_push($data,$arr);
        
    }
    
    echo json_encode($data);
}

if($tip=="anularPago") {
    session_start();
    $usuario = $_SESSION["codigo"];
    include_once "../clases/class.domiciliacion.php";
    $d = new Domiciliacion();
    $respuesta = $d->procesarAnulacion($codigo_referencia, $motivo, $usuario);

    echo json_encode($respuesta);
}