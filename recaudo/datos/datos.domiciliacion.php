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

   include_once "../../recaudo/clases/class.domiciliacion.php";

    $instancia_domiciliacion = new Domiciliacion();
    $datos = $instancia_domiciliacion->getDatosDomiciliacion();
    $data = [];

    while($row = oci_fetch_assoc($datos)){

        array_push($data,[$row["INMUEBLE"],$row["NOMBRE_CLIENTE"],$row["MONTO_PENDIENTE"]] );
    };

    echo json_encode($data);
}

if($tip == "errorPagoRecurrente"){


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

    session_start();
    $id_usuario = $_SESSION["codigo"];
    include_once "../../recaudo/clases/class.domiciliacion.php";
    $cargosAnulan = [9,110, 111, 200, 201];

    $instancia_domiciliacion = new Domiciliacion();
    $datos = $instancia_domiciliacion->getTarjetas($codigo_inmueble);

    $data = [];
    $encript_decript = new EncriptDecript();

    $id_cargo = getCargoUsuario($id_usuario);

    while($row = oci_fetch_assoc($datos)){
        if(in_array($id_cargo,$cargosAnulan))
            array_push($data,[$row["ID"],$row["INMUEBLE"],$row["NOMBRE_CLIENTE"],enmascararTarjeta($encript_decript->decryption($row["NUMERO_TARJETA"])),$row["FECHA_EXPIRACION"],$row["TIPO_TARJETA"],$row["BANCO_EMISOR"],'<input type="button" class="btn btn-danger btnEliminar" value="Anular" style="font-size: 13px;"> <input type="button" class="btn btn-primary btnVerFormulario" value="Formulario" style="font-size: 13px;">'] );
        else
            array_push($data,[$row["ID"],$row["INMUEBLE"],$row["NOMBRE_CLIENTE"],enmascararTarjeta($encript_decript->decryption($row["NUMERO_TARJETA"])),$row["FECHA_EXPIRACION"],$row["TIPO_TARJETA"],$row["BANCO_EMISOR"],'<input type="button" class="btn btn-primary btnVerFormulario" value="Formulario" style="font-size: 13px;">'] );

    };

    echo json_encode($data);
}

if($tip == "getDatosTarjeta"){

   include_once "../../recaudo/clases/class.domiciliacion.php";

    $instancia_domiciliacion = new Domiciliacion();
    $datos = $instancia_domiciliacion->getDatosTarjeta($id_registro);

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

    include_once "../../recaudo/clases/class.domiciliacion.php";
    $instancia_domiciliacion = new Domiciliacion();
    $encript_decript         = new EncriptDecript();

    $numero_tarjeta          =  $encript_decript->encryption(eliminarEspacios($numero_tarjeta));
    $tipo_tarjeta            =  strtoupper($tipo_tarjeta);
    session_start();
    $usuario_alta            =  $_SESSION['codigo'];
    $telefono                =  str_replace('-','',$telefono);
    $celular                 =  str_replace('-','',$celular);

    $d_domiciliacion         = $instancia_domiciliacion->registrarTarjeta($codigo_inmueble, $numero_tarjeta, $fecha_expiracion,$cvv, $tipo_tarjeta,$banco_emisor,$usuario_alta,$correo_electronico,$telefono,$celular);

    echo json_encode($d_domiciliacion);
}


if($tip == "eliminarTarjeta"){

    include_once "../../recaudo/clases/class.domiciliacion.php";
    $instancia_domiciliacion = new Domiciliacion();

    session_start();
    $usuario_baja = $_SESSION["codigo"];
    $d_domiciliacion = $instancia_domiciliacion ->eliminarTarjeta($id_registro,$usuario_baja,$motivo);

    echo json_encode($d_domiciliacion);
}

if($tip == "getDatosAnulacion"){

    $data = json_decode($_POST["datos_pago"],true);
    extract($data);
    $id_pago = '1';
    include_once "../../recaudo/clases/class.domiciliacion.php";
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

if($tip == "verificarDirectorio") {

    //Se obtiene el directorio donde están los archivos de un inmueble.
    if(!is_dir($ruta)){
        mkdir($ruta,0777,true);
    }
    $directorio = opendir($ruta);
    //Arreglo que guarda los nombres de los archivos.
    $data = [];
    
    while ($registro = readdir($directorio)) {
        //Se buscan los archivos que tienen de pagos recurrentes y se inserta en el arreglo.
        if(strstr($registro,'PAGOS_RECURRENTES')){
            array_push($data, $registro);
        }
    }

    //Se cuentan los archivos de pagos recurrentes.
    $archivo_reciente = count($data);
    $nombre_archivo   = "";
    
    if($archivo_reciente>0){
        $archivo_reciente-=1;
        //Se obtiene el archivo de pago recurrente más reciente. 
        $nombre_archivo   = $data[$archivo_reciente];
    }

    
    echo json_encode($nombre_archivo);

}

if($tip == "verificarCargo"){
    session_start();
    $id_usuario = $_SESSION["codigo"];

    include_once '../../clases/class.AreasYCargos.php';

    $c = new AreasYCargos();

    $datos = $c->getCargoByUser($id_usuario);

    $row  = oci_fetch_assoc($datos);

    $id_cargo = $row["ID_CARGO"];

    echo json_encode($id_cargo);
}


function enmascararTarjeta($numero_tarjeta_in){

    $numero_tarjeta_in       = str_replace(' ','',$numero_tarjeta_in);                                //Eliminar los espacios.
    $numero_tarjeta_parte1  = "****-****-****-";
    $numero_tarjeta_parte2  = substr($numero_tarjeta_in,-4);
    $numero_tarjeta         = $numero_tarjeta_parte1.$numero_tarjeta_parte2;
    return $numero_tarjeta;

}

function eliminarEspacios($numero_tarjeta_in){
    $numero_tarjeta = str_replace(' ','',$numero_tarjeta_in);
    return $numero_tarjeta;
}

function getCargoUsuario($id_usuario){
    include_once '../../clases/class.AreasYCargos.php';

    $c = new AreasYCargos();
    $datos = $c->getCargoByUser($id_usuario);
    $row  = oci_fetch_assoc($datos);
    $id_cargo = $row["ID_CARGO"];
    return $id_cargo;
}