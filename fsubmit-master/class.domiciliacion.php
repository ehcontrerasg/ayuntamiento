<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
include_once  '../../clases/class.cliente.php';
include_once  '../../clases/class.encript-decript.php';
include_once  '../../fsubmit-master/fsubmit.php';
include_once  '../../facturacion/clases/class.correo.php';


class Domiciliacion extends ConexionClass {

    private $_form;
    private $_cliente;
    private $_correo;
    private $ipClient;

    //Parámetros de prueba
    /*private $merchant_id = 349000000   ;
    private $terminal_id = 88888888    ;
    private $url_ikey    = "https://lab.cardnet.com.do/api/payment/idenpotency-keys";
    private $url         = "https://lab.cardnet.com.do/api/payment/transactions/sales";*/

    //Parámetros de producción
    private $merchant_id   = 349092439   ;
    private $terminal_id   = 55100055    ;
    private $url           = "https://ecommerce.cardnet.com.do/api/payment/transactions/sales";
    private $url_ikey    = "https://lab.cardnet.com.do/api/payment/idenpotency-keys";

    //Parámetros generales
    private $environment   = "ECommerce" ;
    private $currency      = 214;

    public function __construct(){
        parent::__construct();
        $this->_form    = new Fsubmit();
        $this->_cliente = new Cliente();
        $this->_correo  = new correo();
        $this->ipClient = $_SERVER["REMOTE_ADDR"];
    }

    public function getDatosDomiciliacion(){

        $sql = "SELECT * FROM (SELECT CON.CODIGO_INM INMUEBLE,CON.ALIAS NOMBRE_CLIENTE,(SELECT MtoPenFactDigital(CON.CODIGO_INM) FROM dual) MONTO_PENDIENTE
                FROM   SGC_TT_CONTRATOS CON,SGC_TT_CLIENTES_DOMICILIACION DOM
                WHERE  CON.FECHA_FIN IS NULL
                  AND  CON.CODIGO_INM = DOM.COD_INMUEBLE
                  AND  DOM.FECHA_BAJA IS NULL
                  AND  DOM.USR_BAJA   IS NULL)
                WHERE MONTO_PENDIENTE > 0";

        $resultado = oci_parse($this->_db, $sql);
        $bandera  = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    //Esta función no se utiliza.
    function getMontoPendiente($codigo_inmueble){
        $sql = "SELECT MtoPenFactDigital($codigo_inmueble) MONTO_PENDIENTE FROM dual";

        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    //Función que inserta los pagos en la tabla de ACEASOFT.
    private function ingresaPago($contrato,$codigo_referencia ,$monto,$idemptency_key,
                         $internal_response_code,$response_code_desc,$response_code_source,$approval_code,$pnref,$invoice_number){


         $sql       = "BEGIN  SGC_P_APLICA_PAGO_RECURRENTE (  :CODIGO_INMUEBLE_IN        ,
                                                        :CODIGO_REFERENCIA_IN      ,
                                                        :IMPORTE_IN                , 
                                                        :ID_EMPOTENCY_KEY_IN       ,
                                                        :INTERNAL_RESPONSE_CODE_IN ,
                                                        :RESPONSE_CODE_DESC_IN     ,
                                                        :RESPONSE_CODE_SOURCE_IN   ,
                                                        :APPROVAL_CODE_IN          ,
                                                        :PNREF_IN                  ,
                                                        :FORMA_PAGO_IN             ,
                                                        :INVOICE_NUMBER_IN         ,
                                                        :CODIGO_PAGO_OUT           ,
                                                        :PCODERROR                 ,
                                                        :PMSGERROR                 ); COMMIT; END;";

        $formaPago = 'Tarjeta';

        $result    = oci_parse($this->_db, $sql);
        oci_bind_by_name($result, ':CODIGO_INMUEBLE_IN'         , $contrato              ,   8);
        oci_bind_by_name($result, ':CODIGO_REFERENCIA_IN'       , $codigo_referencia     ,  15);
        oci_bind_by_name($result, ':IMPORTE_IN'                 , $monto                 ,  15);
        oci_bind_by_name($result, ':FORMA_PAGO_IN'              , $formaPago             ,  15);
        oci_bind_by_name($result, ':INTERNAL_RESPONSE_CODE_IN'  , $internal_response_code,  15);
        oci_bind_by_name($result, ':RESPONSE_CODE_DESC_IN'      , $response_code_desc    , 100);
        oci_bind_by_name($result, ':RESPONSE_CODE_SOURCE_IN'    , $response_code_source  , 100);
        oci_bind_by_name($result, ':APPROVAL_CODE_IN'           , $approval_code         ,  15);
        oci_bind_by_name($result, ':PNREF_IN'                   , $pnref                 , 100);
        oci_bind_by_name($result, ':ID_EMPOTENCY_KEY_IN'        , $idemptency_key        , 100);
        oci_bind_by_name($result, ':INVOICE_NUMBER_IN'          , $invoice_number        ,  15);
        oci_bind_by_name($result, ':CODIGO_PAGO_OUT'            , $codigo_pago           ,  15);
        oci_bind_by_name($result, ':PCODERROR'                  , $codError              ,  10);
        oci_bind_by_name($result, ':PMSGERROR'                  , $msjError              , 500);

        $bandera   = oci_execute($result);

        if($bandera){
                oci_close($this->_db);
                return [
                    "coderror"=>$codError,
                    "msjerror"=>$msjError,
                    "codigo_pago"=>$codigo_pago,
                ];
            }else{
                oci_close($this->_db);
                return [
                    "coderror"=>'04',
                    "msjerror"=>'Error en el servidor'
                ];
        }
    }

    private function getParametrosPago($codigo_inmueble){

          $sql = "SELECT CON.CODIGO_INM,CON.ALIAS,
                    (SELECT MtoPenFactDigital(CON.CODIGO_INM) FROM dual) MONTO_PENDIENTE,
                    (SGC_S_CODIGO_REFERENCIA.nextval)   CODIGO_REFERENCIA,
                    (SQ_FACTURA.nextval)                CODIGO_FACTURA,
                    DOM.NUMERO_TARJETA,DOM.FECHA_EXPIRACION,DOM.CVV
                  FROM   SGC_TT_CONTRATOS CON, SGC_TT_CLIENTES_DOMICILIACION DOM
                  WHERE  CON.CODIGO_INM = '$codigo_inmueble'
                    AND  CON.FECHA_FIN IS NULL
                    AND  CON.CODIGO_INM = DOM.COD_INMUEBLE
                    AND  DOM.FECHA_BAJA IS NULL
                    ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    //Función que trae los datos de las tarjetas registradas para pago recurrente.
    function getTarjetas(){

        $sql = "SELECT DOM.ID,DOM.COD_INMUEBLE INMUEBLE,CON.ALIAS NOMBRE_CLIENTE,DOM.NUMERO_TARJETA,DOM.FECHA_EXPIRACION,
                       DOM.CVV,DOM.TIPO_TARJETA, B.DESCRIPCION BANCO_EMISOR
                FROM   SGC_TT_CLIENTES_DOMICILIACION DOM,SGC_TT_CONTRATOS CON,SGC_TP_BANCOS B
                WHERE  CON.CODIGO_INM = DOM.COD_INMUEBLE
                AND    CON.FECHA_FIN  IS NULL
                AND    DOM.FECHA_BAJA IS NULL
                AND    DOM.USR_BAJA   IS NULL
                AND    B.CODIGO      (+)= DOM.BANCO_EMISOR 
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }

    }

    //Esta función no se utiliza
    function getDatosTarjeta($id_registro){

        $sql = "SELECT DOM.ID,DOM.COD_INMUEBLE INMUEBLE,CON.ALIAS NOMBRE_CLIENTE,DOM.NUMERO_TARJETA,DOM.FECHA_EXPIRACION,DOM.CVV,DOM.TIPO_TARJETA
                FROM   SGC_TT_CLIENTES_DOMICILIACION DOM,SGC_TT_CONTRATOS CON
                WHERE  CON.CODIGO_INM = DOM.COD_INMUEBLE
                AND    CON.FECHA_FIN IS NULL
                AND    DOM.FECHA_BAJA IS NULL
                AND    DOM.ID = $id_registro
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }

    }

    //Esta función no se utiliza
    function getDatosTarjetaByInmueble($codigo_inmueble){

        $sql = "SELECT DOM.ID,DOM.COD_INMUEBLE INMUEBLE,CON.ALIAS NOMBRE_CLIENTE,DOM.NUMERO_TARJETA,DOM.FECHA_EXPIRACION,DOM.CVV,DOM.TIPO_TARJETA
                FROM   SGC_TT_CLIENTES_DOMICILIACION DOM,SGC_TT_CONTRATOS CON
                WHERE  CON.CODIGO_INM = DOM.COD_INMUEBLE
                AND    CON.FECHA_FIN  IS NULL
                AND    DOM.FECHA_BAJA IS NULL
                AND    DOM.COD_INMUEBLE = $codigo_inmueble
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }

    }

    function registrarTarjeta($codigo_inmueble, $numero_tarjeta, $fecha_expiracion,$cvv, $tipo_tarjeta,$banco_emisor,$usuario_alta){


        $sql = "BEGIN SGC_P_REGISTRARTARJETA(
                                          :COD_INMUEBLE_IN     ,
                                          :NUMERO_TARJETA_IN   ,
                                          :FECHA_EXPIRACION_IN ,
                                          :CVV_IN              ,
                                          :TIPO_TARJETA_IN     ,
                                          :BANCO_EMISOR_IN     ,
                                          :USUARIO_ALTA_IN     ,
                                          :PMSGERROR           ,
                                          :PCODERROR           
                                       );
                                         COMMIT; 
           END;";

        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado,":COD_INMUEBLE_IN",    $codigo_inmueble,  10);
        oci_bind_by_name($resultado,":NUMERO_TARJETA_IN",  $numero_tarjeta,   200);
        oci_bind_by_name($resultado,":FECHA_EXPIRACION_IN",$fecha_expiracion, 5);
        oci_bind_by_name($resultado,":CVV_IN",             $cvv,              4);
        oci_bind_by_name($resultado,":TIPO_TARJETA_IN",    $tipo_tarjeta,     50);
        oci_bind_by_name($resultado,":BANCO_EMISOR_IN",    $banco_emisor,     50);
        oci_bind_by_name($resultado,":USUARIO_ALTA_IN",    $usuario_alta,     50);
        oci_bind_by_name($resultado,":PMSGERROR",          $msgerror,         500);
        oci_bind_by_name($resultado,":PCODERROR",          $coderror,         10);

        $bandera = oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            $datos = ["coderror"=>$coderror,
                      "msgerror"=>$msgerror
                     ];
            return $datos;
        }else{
            oci_close($this->_db);

            $datos = ["coderror"=>"01",
                      "msgerror"=>"Error del servidor."
                     ];
            return $datos;
        }
    }

    //Esta función no se utiliza.
    function actualizarTarjeta($id_registro,$codigo_inmueble, $numero_tarjeta, $fecha_expiracion,$cvv, $tipo_tarjeta){
        $sql = "BEGIN SGC_P_ACTUALIZARTARJETA (
                                            :ID_REGISTRO_IN      ,
                                            :COD_INMUEBLE_IN     ,
                                            :NUMERO_TARJETA_IN   ,
                                            :FECHA_EXPIRACION_IN ,
                                            :CVV_IN              ,
                                            :TIPO_TARJETA_IN     ,
                                            :PMSGERROR           ,
                                            :PCODERROR           ); COMMIT; END;";

        $resultado = oci_parse($this->_db,$sql);

        oci_bind_by_name($resultado,":ID_REGISTRO_IN",     $id_registro,      50);
        oci_bind_by_name($resultado,":COD_INMUEBLE_IN",    $codigo_inmueble,  10);
        oci_bind_by_name($resultado,":NUMERO_TARJETA_IN",  $numero_tarjeta,   25);
        oci_bind_by_name($resultado,":FECHA_EXPIRACION_IN",$fecha_expiracion, 5);
        oci_bind_by_name($resultado,":CVV_IN",             $cvv,              4);
        oci_bind_by_name($resultado,":TIPO_TARJETA_IN",    $tipo_tarjeta,     50);
        oci_bind_by_name($resultado,":PMSGERROR",          $msgerror,         500);
        oci_bind_by_name($resultado,":PCODERROR",          $coderror,         10);

        $bandera = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
            $datos = [
                      "coderror"=>$coderror,
                      "msgerror"=>$msgerror
                     ];
            return $datos;
        }else{
            oci_close($this->_db);
            $datos = [
                      "coderror"=>"2",
                      "msgerror" => "Error en el servidor."
                     ];
            return $datos;
        }

    }

    function eliminarTarjeta($id_registro,$usuario_baja){

        $sql = "BEGIN SGC_P_ELIMINARTARJETA(
                                            :ID_REGISTRO_IN,
                                            :USUARIO_BAJA_IN,
                                            :PMSGERROR,
                                            :PCODERROR); COMMIT; END;";

        $resultado = oci_parse($this->_db,$sql);

        oci_bind_by_name($resultado,":ID_REGISTRO_IN",     $id_registro,      50);
        oci_bind_by_name($resultado,":USUARIO_BAJA_IN",    $usuario_baja,     50);
        oci_bind_by_name($resultado,":PMSGERROR",          $msgerror,         500);
        oci_bind_by_name($resultado,":PCODERROR",          $coderror,         10);

        $bandera = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
            $datos = [
                "coderror"=>$coderror,
                "msgerror"=>$msgerror
            ];
            return $datos;
        }else{
            oci_close($this->_db);
            $datos = [
                "coderror"=>"3",
                "msgerror" => "Error en el servidor."
            ];
            return $datos;
        }
    }

    function getDatosAnulacion($codigo_referencia){
       $sql = "SELECT PA.MONTO_TOTAL, PA.IDEMPOTENCY_KEY,PA.PNREF
               FROM   SGC_TT_PAGOS_AUTOMATICOS PA
               WHERE    PA.CODIGO_REFERENCIA = '$codigo_referencia'";

        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }

    }

    //Función que enmascara el número de las tarjetas.
    function enmascararTarjeta($numero_tarjeta_in){

    $numero_tarjeta_in     = str_replace(' ','',$numero_tarjeta_in);                                //Eliminar los espacios.
    //$numero_tarjeta_parte1 = substr($numero_tarjeta_in,0,6);                                        //Se toman los primeros seis digitos.

   // $numero_tarjeta_parte2 = "************-";                                                              //Se forma la segunda parte del numero de tarjeta.
    //$longitud_partes       = strlen($numero_tarjeta_parte1)+strlen($numero_tarjeta_parte2);         //Se determina la longitud total del número de la tarjeta.
    //$longitud_parte3       = (strlen($numero_tarjeta_in)-$longitud_partes)+1;                       //Se determina la longitud de la tercera parte de la numeración.
    //$numero_tarjeta_parte3 = substr($numero_tarjeta_in,$longitud_partes,$longitud_parte3) ;         //Se toman los últimos digitos de la tarjeta.
    //$numero_tarjeta        = $numero_tarjeta_parte1.$numero_tarjeta_parte2.$numero_tarjeta_parte3;  //Se concatena cada una de las partes.

    $numero_tarjeta_parte1  = "****-****-****-";
    $numero_tarjeta_parte2  = substr($numero_tarjeta_in,-4);
    $numero_tarjeta         = $numero_tarjeta_parte1.$numero_tarjeta_parte2;
    return $numero_tarjeta;

}

    //Función que obtiene los pagos recurrentes hechos en un rango de fecha.
    function getPagosAutomaticos($fechaDesde,$fechaHasta){

          $sql = "SELECT PA.CODIGO_REFERENCIA,PA.INM_CODIGO,PRO.SIGLA_PROYECTO PROYECTO,
                        TO_CHAR(PA.FECHA_PAGO,'DD/MM/YYYY HH24:MI:SS') FECHA_PAGO,PA.MONTO_TOTAL, 
                               (SELECT DPC.ID_PAGO
                                FROM SGC_TT_DET_PAGOS_AUTOMATICOS DPC
                                WHERE DPC.ID_PAGO_AUTOMATICO = PA.CODIGO_REFERENCIA) ID_PAGO,
                        (SELECT DRC.ID_RECAUDO
                         FROM SGC_TT_DET_REC_AUTOMATICOS DRC
                         WHERE DRC.ID_PAGO_AUTOMATICO = PA.CODIGO_REFERENCIA) ID_RECAUDO
                FROM SGC_TT_PAGOS_AUTOMATICOS PA,SGC_TT_INMUEBLES INM, SGC_TP_PROYECTOS PRO
                WHERE INM.CODIGO_INM  = PA.INM_CODIGO
                  AND INM.ID_PROYECTO = PRO.ID_PROYECTO
                  AND PA.FECHA_PAGO BETWEEN TO_DATE('$fechaDesde 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaHasta 23:59:59','YYYY-MM-DD HH24:MI:SS')
                  AND PA.INTERNAL_RESPONSE_CODE = 0
                  AND PA.ESTADO = 'A'";

        $resultado = oci_parse($this->_db, $sql);

        $bandera    = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }

    }

    //Función que anula los pagos recurentes.
    function anularPagos($id_pago,$motivo,$usuario){

        $sql = "BEGIN SGC_P_ANULARPAGOSCARDNET(:IDPAGOAUTOMATICO_IN , 
                                               :MOTIVO_IN           ,
                                               :USR_IN              ,
                                               :PMSGRESULT          ,
                                               :PCODRESULT          );   COMMIT; END;";

        $resultado = oci_parse($this->_db, $sql);

        oci_bind_by_name($resultado,":IDPAGOAUTOMATICO_IN",$id_pago , 50);
        oci_bind_by_name($resultado,":MOTIVO_IN"          ,$motivo  ,500);
        oci_bind_by_name($resultado,":USR_IN"             ,$usuario , 20);
        oci_bind_by_name($resultado,":PMSGRESULT"         ,$msgerror,200);
        oci_bind_by_name($resultado,":PCODRESULT"         ,$coderror, 10);

        $bandera  = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
            return [
                        "status" => $coderror,
                        "mensaje"=> $msgerror
                    ];
        }else{
            oci_close($this->_db);
            return [
                      "status" => '02',
                      "mensaje"=> 'Error en el servidor.'
                   ];
        }

    }

    private function registrarIntentoPago($codigo_inmueble, $codigo_referencia,$monto_total,$idempotency_key,$internal_response_code,$response_code_desc,$response_code_source,
                                  $approval_code,$pnRef,$invoice_number,$msjerror,$coderror){

        $vacio = '';
        $sql   = "BEGIN SGC_P_INTENTO_PAGO_RECURRENTE(
                                                      :CODIGO_INMUEBLE_IN        ,
                                                      :CODIGO_REFERENCIA_IN      ,
                                                      :ID_PAGO_IN                ,
                                                      :ID_RECAUDO_IN             ,
                                                      :MONTO_TOTAL_IN            ,
                                                      :ID_EMPOTENCY_KEY_IN       ,
                                                      :INTERNAL_RESPONSE_CODE_IN ,
                                                      :RESPONSE_CODE_DESC_IN     ,
                                                      :RESPONSE_CODE_SOURCE_IN   ,
                                                      :APPROVAL_CODE_IN          ,
                                                      :PNREF_IN                  ,
                                                      :INVOICE_NUMBER_IN         ,
                                                      :PMSGERROR                 ,
                                                      :PCODERROR                
                                                    ); COMMIT; END;";


        $result = oci_parse($this->_db, $sql);

        oci_bind_by_name($result,   ':CODIGO_INMUEBLE_IN'       ,$codigo_inmueble       ,  8);
        oci_bind_by_name($result,   ':CODIGO_REFERENCIA_IN'     ,$codigo_referencia     , 15);
        oci_bind_by_name($result,   ':ID_PAGO_IN'               ,$vacio                 , 15);
        oci_bind_by_name($result,   ':ID_RECAUDO_IN'            ,$vacio                 , 15);
        oci_bind_by_name($result,   ':MONTO_TOTAL_IN'           ,$monto_total           , 15);
        oci_bind_by_name($result,   ':ID_EMPOTENCY_KEY_IN'      ,$idempotency_key       ,100);
        oci_bind_by_name($result,   ':INTERNAL_RESPONSE_CODE_IN',$internal_response_code, 15);
        oci_bind_by_name($result,   ':RESPONSE_CODE_DESC_IN'    ,$response_code_desc    ,100);
        oci_bind_by_name($result,   ':RESPONSE_CODE_SOURCE_IN'  ,$response_code_source  ,100);
        oci_bind_by_name($result,   ':APPROVAL_CODE_IN'         ,$approval_code         , 15);
        oci_bind_by_name($result,   ':PNREF_IN'                 ,$pnRef                 ,100);
        oci_bind_by_name($result,   ':INVOICE_NUMBER_IN'        ,$invoice_number        , 15);
        oci_bind_by_name($result,   ':PMSGERROR'                ,$msjerror              ,200);
        oci_bind_by_name($result,   ':PCODERROR'                ,$coderror              , 15);

        $bandera = oci_execute($result);

        if($bandera){
            oci_close($this->_db);
            return [
                "coderror"  =>    $coderror,
                "msgerror"  =>    $msjerror
            ];
        }else{
            oci_close($this->_db);
            return [
                "coderror"=>02,
                "msgerror"=>"Error en el servidor."
            ];
        }
    }

    //Función que genera el ID empotency key. Esta llave se genera cada vez que se procesa un pago.
    private function getIkey(){

        $html   = "<form action='".$this->url_ikey."' method='POST'></form>";

        $this->_form->html = $html;
        $answer = $this->_form->submit();

        $ikey_split = explode(":",$answer['content']);
        $ikey = $ikey_split[1];

        return $ikey;
    }

    //Función que procesa los pagos recurrentes
    public function setSale($codigo_inmueble){

        //Conseguimos el IDEMPOTENCY_KEY, esta llave se genera cada vez que se realiza una transacción.
        $ikey          = $this->getIkey();

        //Conseguimos los parámetros del pago
        $objeto_domiciliacion = new Domiciliacion();
        $datos_pagos_res      = $objeto_domiciliacion->getParametrosPago($codigo_inmueble);
        $datos_pagos          = oci_fetch_assoc($datos_pagos_res);

        //Desencriptamos el número de tarjeta.
        $encript_descript = new EncriptDecript();
        $numero_tarjeta   = $encript_descript->decryption($datos_pagos["NUMERO_TARJETA"]);

        //Conseguimos los datos del cliente
        $datos_cliente_res = $this->_cliente->getDatosCliente($codigo_inmueble);
        $datos_cliente     = oci_fetch_assoc($datos_cliente_res);

        //Llenamos el arreglo que se enviará por la paarela de pagos
        $data = [
            "idempotency-key"  => $ikey,
            "merchant-id"      => $this->merchant_id,
            "terminal-id"      => $this->terminal_id,
            "card-number"      => $numero_tarjeta,
            "environment"      => $this->environment,
            "expiration-date"  => $datos_pagos["FECHA_EXPIRACION"],
            "cvv"              => $datos_pagos["CVV"],
            "amount"           => $datos_pagos["MONTO_PENDIENTE"],
            "currency"         => $this->currency,
            "invoice-number"   => $datos_pagos["CODIGO_FACTURA"],
            "client-ip"        => $this->ipClient,
            "reference-number" => $datos_pagos["CODIGO_REFERENCIA"],
            "token"            => $ikey,
            "tax"              => 0,
            "tip"              => 0
        ];

        echo json_encode($data);

        //Enviamos los datos por la pasarela de pagos.
        $html = "<form action='".$this->url."' method='POST' id='frmSales' enctype='text/plain'></form>";

        $this->_form->html    = $html;
        $this->_form->params  = json_encode($data);
        $answer               = $this->_form->submit();

        //Se consigue la respuesta desde la pasarela de pagos.
        $response_data = json_decode($answer['content'],TRUE);

        //Se genera el bloque de texto que contiene la respuesta de la plataforma de pagos externa.
        $contenido_log_respuesta_plataforma_externa = "\n\n"."*** RESPUESTA PLATAFORMA EXTERNA DE DATOS *** \n".
                                                                  "INTERNAL RESPONSE CODE:". $response_data["internal-response-code"]."\n".
                                                                  "RESPONSE CODE:"         . $response_data["response-code"]."\n".
                                                                  "RESPONSE CODE DESC:"    . $response_data["response-code-desc"]."\n".
                                                                  "RESPONSE CODE SOURCE:"  . $response_data["response-code-source"]."\n".
                                                                  "APPROVAL CODE:"         . $response_data["approval-code"]."\n".
                                                                  "PNREF:"                 . $response_data["pnRef"]."\n".
                                                                  "FECHA:"              . date("Y-m-d H:i:s")."\n"
        ;



        print_r($data);
        if($response_data["internal-response-code"]=="0000"){

            $objeto_domiciliacion = new Domiciliacion();
            $datos_pago_ingresado = $objeto_domiciliacion->ingresaPago($codigo_inmueble,$datos_pagos["CODIGO_REFERENCIA"],$datos_pagos["MONTO_PENDIENTE"],
                                                                $ikey,$response_data["internal-response-code"],$response_data["response-code-desc"],$response_data["response-code-source"],
                                                                $response_data["approval-code"],$response_data["pnRef"],$datos_pagos["CODIGO_FACTURA"]);

            //Se genera el bloque de texto que contiene la respuesta de ACEASOFT.
            $contenido_log_respuesta_sistema   = "*** RESPUESTA DEL SISTEMA *** \n".
                                                     "IKEY:"               . $ikey."\n".
                                                     "CODIGO DE INMUEBLE:" . $codigo_inmueble."\n".
                                                     "CODIGO_REFERENCIA:"  . $datos_pagos["CODIGO_REFERENCIA"]."\n".
                                                     "MONTO:"              . $datos_pagos["MONTO_PENDIENTE"]."\n".
                                                     "STATUS:"             . $datos_pago_ingresado["coderror"]."\n".
                                                     "MENSAJE ACEASOFT:"   . $datos_pago_ingresado["msjerror"]."\n"

            ;

            //Se concatena la respuesta de la plataforma de pagos externa y la respuesta de ACEASOFT.
            $contenido_log = $contenido_log_respuesta_plataforma_externa.$contenido_log_respuesta_sistema;
            $this->escribirLog($contenido_log);
                //Se verifica cuál fue la respuesta del procedimiento que registra los pagos al sistema.
                if($datos_pago_ingresado["coderror"] == 0 ){
                     $datos_pagos["codigo_pago"]   = $datos_pago_ingresado["codigo_pago"];

                     $this->_correo->enviarCorreoPagoRecurrente($datos_cliente,$datos_pagos);
                     if(!$this->fechaExpiracionActualizado($data["FECHA_EXPIRACION"])){
                         $this->_correo->correoVerificacionFechaExpiracion($datos_cliente);
                     }
                }else{
                     $objeto_domiciliacion = new Domiciliacion();
                     $objeto_domiciliacion->registrarIntentoPago($codigo_inmueble, $datos_pagos["CODIGO_REFERENCIA"],$datos_pagos["MONTO_PENDIENTE"],
                         $ikey,$response_data["internal-response-code"],$response_data["response-code-desc"],$response_data["response-code-source"],
                         $response_data["approval-code"],$response_data["pnRef"],$datos_pagos["CODIGO_FACTURA"],$datos_pago_ingresado["msjerror"],$datos_pago_ingresado["coderror"]);

                     //Se envía el correo de error al registrar el pago en ACEASOFT.
                     $this->_correo->errorPagoRecurrente($datos_cliente);

                     if(!$this->fechaExpiracionActualizado($data["FECHA_EXPIRACION"])){
                         $this->_correo->correoVerificacionFechaExpiracion($datos_cliente);
                     }
                }
       }else{
            //Se concatena la respuesta de la plataforma de pagos externa y la respuesta de ACEASOFT.
            $contenido_log = $contenido_log_respuesta_plataforma_externa;
            $this->escribirLog($contenido_log);

            $objeto_domiciliacion = new Domiciliacion();
            $objeto_domiciliacion->registrarIntentoPago($codigo_inmueble, $datos_pagos["CODIGO_REFERENCIA"],$datos_pagos["MONTO_PENDIENTE"],
                $ikey,$response_data["internal-response-code"],$response_data["response-code-desc"],$response_data["response-code-source"],
                $response_data["approval-code"],$response_data["pnRef"],$datos_pagos["CODIGO_FACTURA"],$response_data["response-code-desc"],$response_data["internal-response-code"]);

            //Se envía el correo de error de cobro de tarjeta.
            $this->_correo->errorCobroTarjeta($datos_cliente);

            if(!$this->fechaExpiracionActualizado($data["FECHA_EXPIRACION"])){
                         $this->_correo->correoVerificacionFechaExpiracion($datos_cliente);
            }
        }

    }

    //Función que escribe en el log.
    private function escribirLog($contenido_log){

        if(!is_dir('../../logs')) {
            mkdir('../../logs',0777);
        }
        error_log($contenido_log,3,'../../logs/intentos_de_pagos.log');
    }

    //Función que verifica si el cliente debe actualizar los datos de su tarjeta.
    private function fechaExpiracionActualizado($cvv){

        $cvv_partes = explode("/",$cvv);
        $cvv_mes    = $cvv_partes[0];
        $cvv_agno   = $cvv_partes[1];

        $agno_actual = date("y") ;
        $mes_actual = date("m") ;

        if($cvv_agno == $agno_actual && ($cvv_mes-$mes_actual)<=2){
            return false;
        }

        return true;
    }

}
