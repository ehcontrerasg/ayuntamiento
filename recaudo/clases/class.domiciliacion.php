<?php
chdir(dirname(__FILE__)); //Para que el crontab
//include_once  '../../clases/class.conexion.php';
include_once  '../../clases/class.cliente.php';
include_once  '../../clases/class.encript-decript.php';
include_once  '../../clases/fsubmit.php';
include_once  '../../facturacion/clases/class.correo.php';
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

class Domiciliacion extends ConexionClass {

    private $_form;
    private $_cliente;
    private $_correo;
    private $ipClient;

    //Parámetros de prueba
    /*private $merchant_id          = 349000000   ;
    private $terminal_id          = 88888888    ;
    private $url_ikey             = "https://lab.cardnet.com.do/api/payment/idenpotency-keys";
    private $url_pagos            = "https://lab.cardnet.com.do/api/payment/transactions/sales";
    private $url_anulacion_pagos  = "https://lab.cardnet.com.do/api/payment/transactions/voids";*/


    //Parámetros de producción
    private $merchant_id         = 0/*349092439*/   ;
    private $terminal_id         = 0/*55100055 */   ;
    private $url_pagos           = "https://ecommerce.cardnet.com.do/api/payment/transactions/sales";
    private $url_ikey            = "https://ecommerce.cardnet.com.do/api/payment/idenpotency-keys";
    private $url_anulacion_pagos = "https://ecommerce.cardnet.com.do/api/payment/transactions/voids";

    //Parámetros generales
    private $environment   = "ECommerce" ;
    private $currency      = 214;

    public function __construct(){
        //echo "...";
        parent::__construct();
        $this->_form    = new Fsubmit();
        $this->_cliente = new Cliente();
        $this->_correo  = new correo();
        $this->ipClient = "127.0.0.1"/*$_SERVER["REMOTE_ADDR"]*/;
    }

    public function getDatosDomiciliacion(){

        $sql = "SELECT * FROM (SELECT CON.CODIGO_INM INMUEBLE,CON.ALIAS NOMBRE_CLIENTE,(SELECT MtoPenFactDigital(CON.CODIGO_INM) FROM dual) MONTO_PENDIENTE
                FROM   SGC_TT_CONTRATOS CON,SGC_TT_CLIENTES_DOMICILIACION DOM, SGC_TT_INMUEBLES INM
                WHERE  CON.FECHA_FIN IS NULL
                  AND  CON.CODIGO_INM = DOM.COD_INMUEBLE
                  AND  DOM.COD_INMUEBLE = INM.CODIGO_INM
                  --AND  INM.ID_PROYECTO IN ('SD') 
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

    //Función que inserta los pagos en la tabla de ACEASOFT.
    private function ingresaPago($contrato,$codigo_referencia ,$monto,$idemptency_key,
                                 $internal_response_code,$response_code,$response_code_desc,$response_code_source,$approval_code,$pnref,$invoice_number){


        $sql       = "BEGIN  SGC_P_APLICA_PAGO_RECURRENTE (  :CODIGO_INMUEBLE_IN        ,
                                                        :CODIGO_REFERENCIA_IN      ,
                                                        :IMPORTE_IN                , 
                                                        :ID_EMPOTENCY_KEY_IN       ,
                                                        :INTERNAL_RESPONSE_CODE_IN ,
                                                        :RESPONSE_CODE_IN ,
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
        oci_bind_by_name($result, ':RESPONSE_CODE_IN'           , $response_code         ,  15);
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
            $oci_error = oci_error($result);
            return [
                "coderror"=>'04',
                "msjerror"=>$oci_error['message']
            ];
        }
    }

    private function getParametrosPago($codigo_inmueble){

        $sql = "SELECT CON.CODIGO_INM,CON.ALIAS,
                    (SELECT MtoPenFactDigital(CON.CODIGO_INM) FROM dual) MONTO_PENDIENTE,
                    (SGC_S_CODIGO_REFERENCIA.nextval)   CODIGO_REFERENCIA,
                    (SGC_S_INVOICE_NUMBER.nextval)      CODIGO_FACTURA,
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
    function getTarjetas($codigoInmueble){

        $sql = "SELECT DOM.ID,DOM.COD_INMUEBLE INMUEBLE,CLI.NOMBRE_CLI NOMBRE_CLIENTE,DOM.NUMERO_TARJETA,DOM.FECHA_EXPIRACION,DOM.TIPO_TARJETA, B.DESCRIPCION BANCO_EMISOR
                FROM   SGC_TT_CLIENTES_DOMICILIACION DOM,SGC_TT_CONTRATOS CON,SGC_TP_BANCOS B,SGC_TT_CLIENTES CLI
                WHERE  CON.CODIGO_INM   = DOM.COD_INMUEBLE
                AND    CLI.CODIGO_CLI   = CON.CODIGO_CLI 
                AND    CON.FECHA_FIN    IS NULL
                AND    DOM.FECHA_BAJA   IS NULL
                AND    DOM.USR_BAJA     IS NULL
                AND    B.CODIGO         (+)= DOM.BANCO_EMISOR 
                AND    CON.CODIGO_INM   = '$codigoInmueble'";

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

    function getDatosTarjeta($codigo_inmueble){

        $sql = "SELECT DOM.ID,DOM.COD_INMUEBLE INMUEBLE,CON.ALIAS NOMBRE_CLIENTE,DOM.NUMERO_TARJETA,DOM.FECHA_EXPIRACION,
                       DOM.CVV,DOM.TIPO_TARJETA, B.DESCRIPCION BANCO_EMISOR
                FROM   SGC_TT_CLIENTES_DOMICILIACION DOM,SGC_TT_CONTRATOS CON,SGC_TP_BANCOS B
                WHERE  CON.CODIGO_INM = DOM.COD_INMUEBLE
                AND    CON.FECHA_FIN  IS NULL
                AND    DOM.FECHA_BAJA IS NULL
                AND    DOM.USR_BAJA   IS NULL
                AND    B.CODIGO      (+)= DOM.BANCO_EMISOR 
                AND    DOM.COD_INMUEBLE = '$codigo_inmueble' 
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

    function registrarTarjeta($codigo_inmueble, $numero_tarjeta, $fecha_expiracion,$cvv, $tipo_tarjeta,$banco_emisor,$usuario_alta,$correo_electronico, $telefono, $celular){


        $sql = "BEGIN SGC_P_REGISTRARTARJETA(
                                          :COD_INMUEBLE_IN      ,
                                          :NUMERO_TARJETA_IN    ,
                                          :FECHA_EXPIRACION_IN  ,
                                          :CVV_IN               ,
                                          :TIPO_TARJETA_IN      ,
                                          :BANCO_EMISOR_IN      ,
                                          :USUARIO_ALTA_IN      ,
                                          :CORREO_ELECTRONICO_IN,
                                          :TELEFONO_IN          ,          
                                          :CELULAR_IN           ,           
                                          :PMSGERROR            ,
                                          :PCODERROR           
                                       );   COMMIT;
                                          
           END;";

        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado,":COD_INMUEBLE_IN",      $codigo_inmueble,   10);
        oci_bind_by_name($resultado,":NUMERO_TARJETA_IN",    $numero_tarjeta,   200);
        oci_bind_by_name($resultado,":FECHA_EXPIRACION_IN",  $fecha_expiracion,   5);
        oci_bind_by_name($resultado,":CVV_IN",               $cvv,                4);
        oci_bind_by_name($resultado,":TIPO_TARJETA_IN",      $tipo_tarjeta,      50);
        oci_bind_by_name($resultado,":BANCO_EMISOR_IN",      $banco_emisor,      50);
        oci_bind_by_name($resultado,":USUARIO_ALTA_IN",      $usuario_alta,      50);
        oci_bind_by_name($resultado,":CORREO_ELECTRONICO_IN",$correo_electronico,120);
        oci_bind_by_name($resultado,":TELEFONO_IN",          $telefono,          21);
        oci_bind_by_name($resultado,":CELULAR_IN",           $celular,           21);
        oci_bind_by_name($resultado,":PMSGERROR",            $msgerror,         500);
        oci_bind_by_name($resultado,":PCODERROR",            $coderror,          10);

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

    function eliminarTarjeta($id_registro,$usuario_baja,$motivo){

        $sql = "BEGIN SGC_P_ELIMINARTARJETA(
                                            :ID_REGISTRO_IN,
                                            :USUARIO_BAJA_IN,
                                            :MOTIVO_IN,
                                            :PMSGERROR,
                                            :PCODERROR); COMMIT; END;";

        $resultado = oci_parse($this->_db,$sql);

        oci_bind_by_name($resultado,":ID_REGISTRO_IN",     $id_registro,      50);
        oci_bind_by_name($resultado,":USUARIO_BAJA_IN",    $usuario_baja,     50);
        oci_bind_by_name($resultado,":MOTIVO_IN",          $motivo,           2500);
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
               FROM   ACEASOFT.SGC_TT_INTENTOS_PAGOS_RECURR PA
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

        $numero_tarjeta_parte1  = "****-****-****-";
        $numero_tarjeta_parte2  = substr($numero_tarjeta_in,-4);
        $numero_tarjeta         = $numero_tarjeta_parte1.$numero_tarjeta_parte2;
        return $numero_tarjeta;

    }

    //Función que obtiene los pagos recurrentes hechos en un rango de fecha.
    function getPagosAutomaticos($fechaDesde,$fechaHasta,$tipo_pago= ''){
        /*,
                                 NVL((SELECT CASE P.ESTADO WHEN 'A' THEN 'ACTIVO'
                                                           WHEN  'I' THEN 'INACTIVO'
                                                           END
                                      FROM SGC_TT_PAGOS P
                                      WHERE P.ID_PAGO = (SELECT DPC.ID_PAGO
                                                        FROM SGC_TT_DET_PAGOS_RECURRENTES DPC
                                                        WHERE DPC.ID_PAGO_RECURRENTE = PA.CODIGO_REFERENCIA) ),'NO APLICADO') ESTADO_PAGO*/
        $where = "";
        if($tipo_pago == 'no aplicados'){
            $where = " AND PA.INTERNAL_RESPONSE_CODE != '0000' ";
        }else if($tipo_pago == "aplicados"){
            $where = " AND PA.INTERNAL_RESPONSE_CODE = '0000' ";
        }

        $sql = "SELECT PA.CODIGO_REFERENCIA,PA.INM_CODIGO,PRO.SIGLA_PROYECTO PROYECTO,
                        TO_CHAR(PA.FECHA_PAGO,'DD/MM/YYYY HH24:MI:SS') FECHA,PA.MONTO_TOTAL MONTO, 
                               (SELECT DPC.ID_PAGO
                                FROM SGC_TT_DET_PAGOS_RECURRENTES DPC
                                WHERE DPC.ID_PAGO_RECURRENTE = PA.CODIGO_REFERENCIA) ID_PAGO,
                        (SELECT DRC.ID_RECAUDO
                         FROM SGC_TT_DET_RECAUDO_RECURR DRC
                         WHERE DRC.ID_PAGO_RECURRENTE = PA.CODIGO_REFERENCIA) ID_RECAUDO,
                         NVL(PA.MENSAJE_SISTEMA,PA.RESPONSE_CODE_DESC) MENSAJE_RESPUESTA
                FROM SGC_TT_INTENTOS_PAGOS_RECURR PA,SGC_TT_INMUEBLES INM, SGC_TP_PROYECTOS PRO
                WHERE INM.CODIGO_INM  = PA.INM_CODIGO
                  AND INM.ID_PROYECTO = PRO.ID_PROYECTO
                  AND PA.FECHA_PAGO BETWEEN TO_DATE('$fechaDesde 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                                            TO_DATE('$fechaHasta 23:59:59','YYYY-MM-DD HH24:MI:SS') ".
            $where;

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

        $sql = "BEGIN SGC_P_ANULAR_PAGO_RECURRENTE(:IDPAGOAUTOMATICO_IN , 
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

    private function registrarIntentoPago($codigo_inmueble, $codigo_referencia,$monto_total,$idempotency_key,$internal_response_code,$response_code,$response_code_desc,$response_code_source,
                                          $approval_code,$pnRef,$invoice_number, $msjerror='',$coderror=''){

        $vacio = '';
        $sql   = "BEGIN SGC_P_INTENTO_PAGO_RECURRENTE(
                                                      :CODIGO_INMUEBLE_IN        ,
                                                      :CODIGO_REFERENCIA_IN      ,
                                                      :ID_PAGO_IN                ,
                                                      :ID_RECAUDO_IN             ,
                                                      :MONTO_TOTAL_IN            ,
                                                      :ID_EMPOTENCY_KEY_IN       ,
                                                      :INTERNAL_RESPONSE_CODE_IN ,
                                                      :RESPONSE_CODE_IN ,
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
        oci_bind_by_name($result,   ':RESPONSE_CODE_IN'         ,$response_code         , 15);
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

        $merchantParameters = $this->getMerchantParameters($datos_cliente["ID_PROYECTO"]);
        //Llenamos el arreglo que se enviará por la paarela de pagos
        $data = [
            "idempotency-key"  => $ikey,
            "merchant-id"      => $merchantParameters["merchant_id"] /*$this->merchant_id*/,
            "terminal-id"      => $merchantParameters["terminal_id"] /*$this->terminal_id*/,
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

        //Enviamos los datos por la pasarela de pagos.
        $html = "<form action='".$this->url_pagos."' method='POST' id='frmSales' enctype='text/plain'></form>";

        $this->_form->html    = $html;
        $this->_form->params  = json_encode($data);
        $answer               = $this->_form->submit();

        //Se consigue la respuesta desde la pasarela de pagos.
        $response_data        = json_decode($answer['content'],TRUE);

            //Se genera el bloque de texto que contiene la respuesta de la plataforma de pagos externa.
        $contenido_log_respuesta_plataforma_externa = "\n\n"."*** RESPUESTA PLATAFORMA EXTERNA DE DATOS *** \n".
            "INTERNAL RESPONSE CODE:". $response_data["internal-response-code"]."\n".
            "RESPONSE CODE:"         . $response_data["response-code"]."\n".
            "RESPONSE CODE DESC:"    . $response_data["response-code-desc"]."\n".
            "RESPONSE CODE SOURCE:"  . $response_data["response-code-source"]."\n".
            "APPROVAL CODE:"         . $response_data["approval-code"]."\n".
            "INVOICE NUMBER:"        . $datos_pagos["CODIGO_FACTURA"]."\n".
            "PNREF:"                 . $response_data["pnRef"]."\n".
            "MERCHANT_ID:"           . $merchantParameters["merchant_id"]."\n".
            "TERMINAL_ID:"           . $merchantParameters["terminal_id"]."\n".
            "FECHA:"                 . date("Y-m-d H:i:s")."\n"
        ;

        $contenido_log_respuesta_sistema = "";

        if(isset($response_data["response-code"])) {
            $objeto_domiciliacion = new Domiciliacion();
            $mensaje_cardnet = $objeto_domiciliacion->getMensajeCardnet($response_data["internal-response-code"], $response_data["response-code"]);
        }
        else{
            $objeto_domiciliacion = new Domiciliacion();
            $mensaje_cardnet = $objeto_domiciliacion->getMensajeCardnet($response_data["internal-response-code"]);
        }

        $descripcion_mensaje_cardnet     = $mensaje_cardnet["DESCRIPCION"];

        if($response_data["internal-response-code"]=="0000"){

            $objeto_domiciliacion = new Domiciliacion();
            $datos_pago_ingresado = $objeto_domiciliacion->ingresaPago($codigo_inmueble,$datos_pagos["CODIGO_REFERENCIA"],$datos_pagos["MONTO_PENDIENTE"],
                $ikey,$response_data["internal-response-code"],$response_data["response-code"],$descripcion_mensaje_cardnet,$response_data["response-code-source"],
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


            //Se verifica cuál fue la respuesta del procedimiento que registra los pagos al sistema.
            if($datos_pago_ingresado["coderror"] == 0 ){
                $datos_pagos["codigo_pago"]      = $datos_pago_ingresado["codigo_pago"];

                $this->_correo->reciboPagoRecurrente($datos_cliente,$datos_pagos);
                /* if(!$this->fechaExpiracionActualizado($data["expiration-date"])){
                     $this->_correo->correoVerificacionFechaExpiracion($datos_cliente);
                 }*/
            }else{
                //$objeto_domiciliacion = new Domiciliacion();
                //$objeto_domiciliacion->registrarIntentoPago($codigo_inmueble, $datos_pagos["CODIGO_REFERENCIA"],$datos_pagos["MONTO_PENDIENTE"],
                //    $ikey,$response_data["internal-response-code"],$response_data["response-code"],$response_data["response-code-desc"],$response_data["response-code-source"],
                //    $response_data["approval-code"],$response_data["pnRef"],$datos_pagos["CODIGO_FACTURA"],$datos_pago_ingresado["msjerror"],$datos_pago_ingresado["coderror"]);

                //Se envía el correo de error al registrar el pago en ACEASOFT.
                $this->_correo->errorPagoRecurrente($datos_cliente);

                /* if(!$this->fechaExpiracionActualizado($data["expiration-date"])){
                     $this->_correo->correoVerificacionFechaExpiracion($datos_cliente);
                 }*/
            }
        }else{


            //Se concatena la respuesta de la plataforma de pagos externa y la respuesta de ACEASOFT.
            /*$contenido_log = $contenido_log_respuesta_plataforma_externa;
            $this->escribirLog($contenido_log);*/

            $objeto_domiciliacion = new Domiciliacion();
            $objeto_domiciliacion->registrarIntentoPago($codigo_inmueble, $datos_pagos["CODIGO_REFERENCIA"],$datos_pagos["MONTO_PENDIENTE"],
                $ikey,$response_data["internal-response-code"],$response_data["response-code"],$descripcion_mensaje_cardnet,$response_data["response-code-source"],
                $response_data["approval-code"],$response_data["pnRef"],$datos_pagos["CODIGO_FACTURA"]/*,''$response_data["response-code-desc"],''$response_data["internal-response-code"]*/);

            //Se envía el correo de error de cobro de tarjeta.
            $this->_correo->errorCobroTarjeta($datos_cliente,$descripcion_mensaje_cardnet);


        }

        //Se escribe en el archivo de logs.
        $contenido_log = $contenido_log_respuesta_plataforma_externa.$contenido_log_respuesta_sistema;
        $this->escribirLog($contenido_log);

        //Se verifica si la tarjeta del usuario tiene la fecha actualizada.
        if(!$this->fechaExpiracionActualizado($data["expiration-date"])){

            $this->_correo->correoVerificacionFechaExpiracion($datos_cliente);
        }

    }

    public function setSaleII($codigo_inmueble)
    {


        //Conseguimos el IDEMPOTENCY_KEY, esta llave se genera cada vez que se realiza una transacción.
        $ikey = $this->getIkey();

        //Conseguimos los parámetros del pago
        $objeto_domiciliacion = new Domiciliacion();
        $datos_pagos_res = $objeto_domiciliacion->getParametrosPago($codigo_inmueble);
        $datos_pagos = oci_fetch_assoc($datos_pagos_res);

        //Desencriptamos el número de tarjeta.
        $encript_descript = new EncriptDecript();
        $numero_tarjeta = $encript_descript->decryption($datos_pagos["NUMERO_TARJETA"]);

        //Conseguimos los datos del cliente
        $datos_cliente_res = $this->_cliente->getDatosCliente($codigo_inmueble);
        $datos_cliente = oci_fetch_assoc($datos_cliente_res);

        $merchantParameters = $this->getMerchantParameters($datos_cliente["ID_PROYECTO"]);
        //Llenamos el arreglo que se enviará por la paarela de pagos
        $data = [
            "idempotency-key" => $ikey,
            "merchant-id" => $merchantParameters["merchant_id"] /*$this->merchant_id*/,
            "terminal-id" => $merchantParameters["terminal_id"] /*$this->terminal_id*/,
            "card-number" => $numero_tarjeta,
            "environment" => $this->environment,
            "expiration-date" => $datos_pagos["FECHA_EXPIRACION"],
            "cvv" => $datos_pagos["CVV"],
            "amount" => $datos_pagos["MONTO_PENDIENTE"],
            "currency" => $this->currency,
            "invoice-number" => $datos_pagos["CODIGO_FACTURA"],
            "client-ip" => $this->ipClient,
            "reference-number" => $datos_pagos["CODIGO_REFERENCIA"],
            "token" => $ikey,
            "tax" => 0,
            "tip" => 0
        ];

        print_r($data);

        return true;
    }

    //Función que escribe en el log.
    private function escribirLog($contenido_log){

        if(!is_dir('../../logs')) {
            mkdir('../../logs',0777);
        }
        error_log($contenido_log,3,'../../logs/intentos_de_pagos.log');
    }

    //Función que verifica si el cliente debe actualizar los datos de su tarjeta.
    private function fechaExpiracionActualizado($fecha_expiracion){


        $fecha_expiracion = DateTime::createFromFormat('m/y',$fecha_expiracion);
        $fecha_actual     = DateTime::createFromFormat('m/y',date('m/y'));

        $diferencia_meses = date_diff($fecha_expiracion,$fecha_actual);

        $cantidad_meses   = $diferencia_meses->format("%m");
        $cantidad_agnos   = $diferencia_meses->format("%y");

        if($cantidad_agnos == 0){
            if(($cantidad_meses>0 && $cantidad_meses<=2)){
                //echo "false";
                return false;
            }
        }

        /*
        if(/*$cantidad_agnos == 0 && ($cantidad_meses>0 && $cantidad_meses<=2)){
            return false;
        }*/

        return true;
    }

    public function procesarAnulacion($codigo_referencia,$motivo,$usuario){


        $d = new Domiciliacion();
        $datos_anulacion = $d->getDatosAnulacion($codigo_referencia);
        $datos_anulacion = oci_fetch_assoc($datos_anulacion);

        $data = [
            "amount"          =>  $datos_anulacion["MONTO_TOTAL"]      ,
            "idempotency-key" =>  $datos_anulacion["IDEMPOTENCY_KEY"]  ,
            "token"           =>  $datos_anulacion["IDEMPOTENCY_KEY"]  ,
            "pnRef"           =>  $datos_anulacion["PNREF"]            ,
            "merchant-id"     =>  $this->merchant_id                   ,
            "environment"     =>  $this->environment                   ,
            "terminal-id"     =>  $this->terminal_id                   ,
            "currency"        =>  $this->currency
        ];

        //echo json_encode($data);
        //print_r($data);
        //return true;

        //Enviar los datos por la pasarela de pagos
        $form         = new Fsubmit();
        $form->html   = "<form action='".$this->url_anulacion_pagos."' method='POST' enctype='text/plain'></form>";
        $form->params = json_encode($data);
        $answer       = $form->submit();

        //Se consigue la respuesta desde la pasarela de pagos.
        $response_data = json_decode($answer['content'],TRUE);

        //echo json_encode([$codigo_referencia,$motivo,$usuario]);

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
                    "mensaje" =>$data["mensaje"]
                ];
            }

            // echo json_encode($result);
        }else{
            $result =   [
                "status"=>02,
                "mensaje"=>"Ocurrió un error al intentar anular el pago. Respuesta de la plataforma de pago externa: ".$response_data["response-code-desc"]."."
            ];

        }
        return $result ;


    }

    private function getMensajeCardnet($internal_response_code, $response_code = ''){

        $where     = "";
        if($response_code =='')
            $where = "  AND RC.RESPONSE_CODE IS NULL";
        else
            $where = "  AND RC.RESPONSE_CODE = '$response_code'";

        $sql       = "SELECT  RC.INTERNAL_RESPONSE_CODE, RC.DESCRIPCION, RC.RESPONSE_CODE
                      FROM SGC_TP_RESPUESTAS_CARDNET RC
                      WHERE RC.INTERNAL_RESPONSE_CODE = '$internal_response_code'".
            $where;

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
            $data = oci_fetch_assoc($resultado);
            return $data;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    public function getIntentosPagos($fecha_desde,$fecha_hasta){

        $sql = "SELECT IPR.CODIGO_REFERENCIA,IPR.INM_CODIGO,PRO.SIGLA_PROYECTO PROYECTO,IPR.FECHA_PAGO FECHA, IPR.MONTO_TOTAL MONTO, NVL(IPR.MENSAJE_SISTEMA,IPR.RESPONSE_CODE_DESC) MENSAJE_RESPUESTA
                FROM SGC_TT_INTENTOS_PAGOS_RECURR IPR, SGC_TP_RESPUESTAS_CARDNET RC,SGC_TT_INMUEBLES INM,SGC_TP_PROYECTOS PRO
                WHERE IPR.INTERNAL_RESPONSE_CODE != '0000'
                AND   RC.INTERNAL_RESPONSE_CODE   = IPR.INTERNAL_RESPONSE_CODE
                AND   RC.RESPONSE_CODE            = IPR.RESPONSE_CODE
                AND   INM.CODIGO_INM              = IPR.INM_CODIGO
                AND   INM.ID_PROYECTO             = PRO.ID_PROYECTO
                AND   IPR.FECHA_PAGO BETWEEN TO_DATE('$fecha_desde 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                                             TO_DATE('$fecha_hasta 23:59:59','YYYY-MM-DD HH24:MI:SS')
                ORDER BY  FECHA DESC";

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

    private function getMerchantParameters($proyecto = "SD"){

        if($proyecto == "SD"){
            $this->merchant_id         = 349092439   ;
            $this->terminal_id         = 55100055    ;
        }else if($proyecto == "BC"){
            $this->merchant_id         = 349062185  ;
            $this->terminal_id         = 10311067   ;
        }

        return [
            "merchant_id"=>$this->merchant_id,
            "terminal_id"=>$this->terminal_id
        ];
    }

    public function getUsuarioAlta($codigoInmueble){
        $sql = "SELECT  (USR.NOM_USR||' '||USR.APE_USR) USUARIO_ALTA
                FROM SGC_TT_USUARIOS USR, SGC_TT_CLIENTES_DOMICILIACION DOM
                WHERE   DOM.USR_ALTA   = USR.ID_USUARIO
                AND     DOM.COD_INMUEBLE = $codigoInmueble
                AND     DOM.FECHA_BAJA IS NULL
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
}
