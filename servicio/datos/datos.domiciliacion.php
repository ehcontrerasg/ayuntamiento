<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include_once  "../../clases/class.encript-decript.php";
extract($_POST);

$merchant_id    = "349000000";
$terminal_id    = "88888888";
$environment    = "ECommerce";
$currency       = "214";
$ipClient       = $_SERVER['REMOTE_ADDR'];
$nombre_cliente = "";

    if($tip == "getDatosDomiciliacion"){

    //include_once "../clases/class.domiciliacion.php";

    $instancia_domiciliacion = new Domiciliacion();
    $datos = $instancia_domiciliacion->getDatosDomiciliacion();
    $data = [];

    while($row = oci_fetch_assoc($datos)){

        array_push($data,[$row["INMUEBLE"],$row["NOMBRE_CLIENTE"],$row["MONTO_PENDIENTE"]] );
    };

    echo json_encode($data);
}
/*
if($tip == "getParametrosPago"){

   // header('Content-Type: application/json');
    include_once "../clases/class.domiciliacion.php";
    $instancia_domiciliacion = new Domiciliacion();
    $data = [];


    $datos = $instancia_domiciliacion->getParametrosPago($codigo_inmueble);

    while($row = oci_fetch_assoc($datos)){

        $data = ["idempotency-key"  => $ikey,
                 "merchant-id"      => $merchant_id,
                 "terminal-id"      => $terminal_id,
                 "card-number"      => $row["NUMERO_TARJETA"],
                 "environment"      => $environment,
                 "expiration-date"  => $row["FECHA_EXPIRACION"],
                 "cvv"              => $row["CVV"],
                 "amount"           => $row["MONTO_PENDIENTE"],
                 "currency"         => $currency,
                 "invoice-number"   => $codigo_inmueble,
                 "client-ip"        => $ipClient,
                 "reference-number" => $row["ID_PAGO"],
                 "token"            => $ikey,
                 "tax"              => 0,
                 "tip"              => 0
        ];
    };

    echo json_encode($data);
}*/

/*if($tip == "setPago"){

    include_once "../clases/class.domiciliacion.php";
    $instancia_domiciliacion = new Domiciliacion();

    $parametros_pago = json_decode($_POST["parametros_pago"],true) ;

    extract($parametros_pago);

    $datos = $instancia_domiciliacion->ingresaPago($contrato, $monto,$idemptency_key,
        $internal_response_code,$response_code_desc,$response_code_source,$approval_code,$pnref);

    echo json_encode($datos);
}*/

if($tip == "enviarCorreo"){

    $datos_pago      = json_decode($parametros_pago,true);
    $codigo_inmueble = $datos_pago["invoice-number"];

    include_once '../../facturacion/clases/class.correo.php';
    include_once '../../clases/class.cliente.php';

    $c             = new Cliente();
    $data          = $c->getDatosCliente($codigo_inmueble);
    $datos_cliente = oci_fetch_assoc($data);

    $correo         = new correo();
    $correo_cliente = "holguinjean1@gmail.com";
    $correo -> enviarCorreoPagoRecurrente($datos_cliente,$datos_pago);

    return $correo;

}

if($tip == "errorPagoRecurrente"){

    /*include_once '../../clases/class.correo.php';

    $correo = new correo();

    $correo -> errorPagoRecurrente($correo_cliente,$parametros_pago);*/

    $datos_pago      = json_decode($parametros_pago,true);
    $codigo_inmueble = $datos_pago["invoice-number"];

    include_once '../../facturacion/clases/class.correo.php';
    include_once '../../clases/class.cliente.php';

    $c              = new Cliente();
    $data           = $c->getDatosCliente($codigo_inmueble);
    $datos_cliente  = oci_fetch_assoc($data);

    $correo         = new correo();
    $correo -> errorPagoRecurrente($datos_cliente/*,$datos_pago*/);

    return $correo;

}

if($tip == "errorCobroTarjeta"){

    /*include_once '../../clases/class.correo.php';

    $correo = new correo();

    $correo -> errorPagoRecurrente($correo_cliente,$parametros_pago);*/

    $datos_pago      = json_decode($parametros_pago,true);
    $codigo_inmueble = $datos_pago["invoice-number"];

    include_once '../../facturacion/clases/class.correo.php';
    include_once '../../clases/class.cliente.php';

    $c               = new Cliente();
    $data            = $c->getDatosCliente($codigo_inmueble);
    $datos_cliente   = oci_fetch_assoc($data);

    $correo          = new correo();
    $correo -> errorCobroTarjeta($datos_cliente/*,$datos_pago*/);

    return $correo;

}

if($tip == "getTarjetas"){

    include_once "../clases/class.domiciliacion.php";

    $instancia_domiciliacion = new Domiciliacion();
    $datos = $instancia_domiciliacion->getTarjetas();
    $data = [];
    $encript_decript = new EncriptDecript();

    while($row = oci_fetch_assoc($datos)){

        array_push($data,[$row["ID"],$row["INMUEBLE"],$row["NOMBRE_CLIENTE"],enmascararTarjeta($encript_decript->decryption($row["NUMERO_TARJETA"])),$row["FECHA_EXPIRACION"],$row["CVV"],$row["TIPO_TARJETA"],$row["BANCO_EMISOR"],'<input type="button" class="btn btn-remove btnEliminar" value="Eliminar">'] );
    };

    echo json_encode($data);
}

if($tip == "getDatosTarjeta"){

    include_once "../clases/class.domiciliacion.php";

    $instancia_domiciliacion = new Domiciliacion();
    $datos = $instancia_domiciliacion->getDatosTarjeta($id_registro);
    /*$data_1 = [
                "id_registro"      => "N/A"  ,
                "inmueble"         => "N/A"  ,
                "numero_tarjeta"   => "N/A" ,
                "fecha_expiracion" => "N/A" ,
                "cvv"              => "N/A",
                "tipo_tarjeta"     => "N/A",
             ];*/

    while($row = oci_fetch_assoc($datos)){
        $data= [
                    "id_registro"      => $row["ID"]  ,
                    "inmueble"         => $row["INMUEBLE"]  ,
                    "numero_tarjeta"   => $row["NUMERO_TARJETA"] ,
                    "fecha_expiracion" => $row["FECHA_EXPIRACION"] ,
                    "cvv"              => $row["CVV"],
                    "tipo_tarjeta"     => $row["TIPO_TARJETA"],
                  ];
    };

    echo json_encode($data);
}

if($tip == "insertarTarjeta"){

    include_once "../clases/class.domiciliacion.php";
    $instancia_domiciliacion = new Domiciliacion();
    $encript_decript         = new EncriptDecript();

    $numero_tarjeta          =  $encript_decript->encryption(eliminarEspacios($numero_tarjeta));
    $tipo_tarjeta            =  strtoupper($tipo_tarjeta);
    session_start();
    $usuario_alta            =  $_SESSION['codigo'];

    $d_domiciliacion         = $instancia_domiciliacion->registrarTarjeta($codigo_inmueble, $numero_tarjeta, $fecha_expiracion,$cvv, $tipo_tarjeta,$banco_emisor,$usuario_alta,$correo_electronico,$telefono,$celular);

    echo json_encode($d_domiciliacion);
}

if($tip == "actualizarTarjeta"){

    $tipo_tarjeta =  strtoupper($tipo_tarjeta);
    //include_once "../clases/class.domiciliacion.php";


    $numero_tarjeta =  enmascararTarjeta($numero_tarjeta);

    $instancia_domiciliacion = new Domiciliacion();
    $d_domiciliacion         = $instancia_domiciliacion->actualizarTarjeta($id_registro,$codigo_inmueble, $numero_tarjeta, $fecha_expiracion,$cvv, $tipo_tarjeta);
    echo json_encode($d_domiciliacion);
}

if($tip == "eliminarTarjeta"){

    include_once "../clases/class.domiciliacion.php";
    $instancia_domiciliacion = new Domiciliacion();

    session_start();
    $usuario_baja = $_SESSION["codigo"];
    $d_domiciliacion = $instancia_domiciliacion ->eliminarTarjeta($id_registro,$usuario_baja);

    echo json_encode($d_domiciliacion);
}

if($tip == "getDatosAnulacion"){

    $data = json_decode($_POST["datos_pago"],true);
    extract($data);
    $id_pago = '1';
   //include_once "../clases/class.domiciliacion.php";
    $instancia_domiciliacion = new Domiciliacion();
    $d_anulacion = $instancia_domiciliacion->getDatosAnulacion($codigo_inmueble,$id_pago);

    $data = [];
    if($d_anulacion != false){
        while($row = oci_fetch_assoc($d_anulacion)){
            $data = [
                        "amount"          => $row["MONTO"],
                        "currency"        => $currency,
                        "environment"     => $environment,
                        "idempotency-key" => $row["IDEMPOTENCY_KEY"],
                        "merchant-id"     => $merchant_id,
                        "pnRef"           => $row["PNREF"],
                        "terminal-id"     => $terminal_id,
                        "token"           => $row["IDEMPOTENCY_KEY"]
                    ];
        }

    }else{
        $data = [
                    "coderror" => "02",
                    "msgerror" => "Error en el servidor."
                ];
    }

    echo json_encode($data);

}

if($tip == "getBancoEmisor"){
    include_once "../../clases/class.pago.php";

    $p = new Pago();

    $bancos = $p->getBancos();
    $data   = [];

    while($row = oci_fetch_assoc($bancos)){

        $arr  = [$row["CODIGO"],$row["DESCRIPCION"]];
        array_push($data,$arr);
    }

    echo json_encode($data);

}

if($tip == "getDatosCliente"){
    include_once "../../clases/class.cliente.php";

    $c = new Cliente();
    $datos_cliente_res = $c->getDatosCliente($codigo_inmueble);
    //$data = [];

   $datos_cliente = oci_fetch_assoc($datos_cliente_res);


    echo json_encode($datos_cliente);
}

function enmascararTarjeta($numero_tarjeta_in){

    $numero_tarjeta_in       = str_replace(' ','',$numero_tarjeta_in);                                //Eliminar los espacios.
    //$numero_tarjeta_parte1 = substr($numero_tarjeta_in,0,6);                                        //Se toman los primeros seis digitos.

   // $numero_tarjeta_parte2 = "************-";                                                       //Se forma la segunda parte del numero de tarjeta.
    //$longitud_partes       = strlen($numero_tarjeta_parte1)+strlen($numero_tarjeta_parte2);         //Se determina la longitud total del número de la tarjeta.
    //$longitud_parte3       = (strlen($numero_tarjeta_in)-$longitud_partes)+1;                       //Se determina la longitud de la tercera parte de la numeración.
    //$numero_tarjeta_parte3 = substr($numero_tarjeta_in,$longitud_partes,$longitud_parte3) ;         //Se toman los últimos digitos de la tarjeta.
    //$numero_tarjeta        = $numero_tarjeta_parte1.$numero_tarjeta_parte2.$numero_tarjeta_parte3;  //Se concatena cada una de las partes.

    $numero_tarjeta_parte1  = "****-****-****-";
    $numero_tarjeta_parte2  = substr($numero_tarjeta_in,-4);
    $numero_tarjeta         = $numero_tarjeta_parte1.$numero_tarjeta_parte2;
    return $numero_tarjeta;

}

function eliminarEspacios($numero_tarjeta_in){
    $numero_tarjeta = str_replace(' ','',$numero_tarjeta_in);
    return $numero_tarjeta;
}