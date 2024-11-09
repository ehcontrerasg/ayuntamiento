<?php

///Parámetros de prueba
/*$merchant_id        = 349000000   ;
$terminal_id        = 88888888    ;
$url                = "https://lab.cardnet.com.do/api/payment/transactions/voids";*/

//Parámetros de producción
$merchant_id        = 349000000   ;
$terminal_id        = 88888888    ;
$url                = "https://ecommerce.cardnet.com.do/api/payment/transactions/voids";

//Parámetros generales
$environment        = "ECommerce" ;
$currency           = 214;

extract($_POST);
session_start();
$usuario = $_SESSION['codigo'];
include_once  '../../fsubmit-master/fsubmit.php';
include_once  "../clases/class.domiciliacion.php";

if($tip == "getPagosAutomaticos"){

    $d = new Domiciliacion();

    $datos = $d->getPagosAutomaticos($fechaDesde,$fechaHasta);
    $data  = [];

   // $boton_anular = "<button class='btnAnularPago btn btn-primary'>Anular Pago</button>";
    while($row = oci_fetch_assoc($datos)){

       $arr = [$row["CODIGO_REFERENCIA"], $row["INM_CODIGO"],$row["PROYECTO"],$row["MONTO_TOTAL"], $row["FECHA_PAGO"],$row["ID_PAGO"],$row["ID_RECAUDO"]/*,$boton_anular*/ ];

       array_push($data,$arr);
    }
    echo json_encode($data);


}

if($tip=="anularPago"){

    session_start();
    $usuario = $_SESSION["codigo"];

    $d = new Domiciliacion();
    $respuesta = $d->procesarAnulacion($codigo_referencia,$motivo,$usuario);

    echo json_encode($respuesta);
    //Conseguir los datos del pago
    /*$d = new Domiciliacion();
    $datos_anulacion = $d->getDatosAnulacion($codigo_referencia);
    $datos_anulacion = oci_fetch_assoc($datos_anulacion);

    $data = [
              "amount"          =>  $datos_anulacion["MONTO_TOTAL"]      ,
              "idempotency-key" =>  $datos_anulacion["IDEMPOTENCY_KEY"]  ,
              "token"           =>  $datos_anulacion["IDEMPOTENCY_KEY"]  ,
              "pnRef"           =>  $datos_anulacion["PNREF"]            ,
              "merchant-id"     =>  $merchant_id                         ,
              "environment"     =>  $environment                         ,
              "terminal-id"     =>  $terminal_id                         ,
              "currency"        =>  $currency
            ];

    //Enviar los datos por la pasarela de pagos
    $form         = new Fsubmit();
    $form->html   = "<form action='".$url."' method='POST' enctype='text/plain'></form>";
    $form->params = json_encode($data);
    $answer       = $form->submit();

    //Se consigue la respuesta desde la pasarela de pagos.
    $response_data = json_decode($answer['content'],TRUE);

    if($response_data["internal-response-code"]=="0000"){
    $d = new Domiciliacion();
        $data = $d->anularPagos($codigo_referencia,$motivo,$usuario);

        if($data["status"]==0){
            $result = [
                "status"=>0,
                "mensaje"=>"Pago anulado exitosamente. Respuesta de la plataforma de pago externa: ".$response_data["response-code-desc"]."."
            ];

        }else{
    $result = [
        "status" =>$data["status"],
        "mensaje"=>$data["mensaje"]
    ];
        }

    echo json_encode($result);
    }else{
        $result =   [
            "status"=>02,
            "mensaje"=>"Ocurrió un error al intentar anular el pago. Respuesta de la plataforma de pago externa: ".$response_data["response-code-desc"]."."
        ];

        echo json_encode($result );
    }*/





}