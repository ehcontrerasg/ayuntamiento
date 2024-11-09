<?php
include_once '../../facturacion/mail/class.phpmailer.php';
include_once '../../clases/class.factura.php';

class correo extends PHPMailer{

    function __construct(){
        parent::__construct();
    }

    public function enviar($correo = array('cuerpo' => null, 'remitente' => null,'remitente_alias' => null, 'destinatario' => null, 'destinatario_alias' => null, 'asunto' => null, 'cc' => array()), $smtp = array()){

        //Configuración STMP.
        $this->isSMTP();
        $this->SMTPDebug=$smtp['debug'];
        $this->SMTPAuth=true;
        $this->Host=$smtp['host'];
        $this->Port=$smtp['port'];
        $this->Username=$smtp['username'];
        $this->Password=$smtp['password'];

        $this->setFrom($correo['remitente']);
        if(isset($correo['remitente_alias']))
            $this->setFrom($correo['remitente'],$correo['remitente_alias']);        

        $this->Subject= utf8_decode($correo['asunto']);
        $this->msgHTML(utf8_decode($correo['cuerpo']));
        $this->addAddress($correo['destinatario']);      
        if(isset($correo['destinatario_alias']))
            $this->addAddress($correo['destinatario'], $correo['destinatario_alias']);   
         
        if(isset($correo['cc'])){
            for($i = 0; $i < count($correo['cc']); $i++){
                $this->addCC($correo['cc'][$i]);
            }
        }

        if(!$this->send()){
//            echo false;
            return false;
        }

//        echo true;
        return true;
    }

    public function enviarcorreo($email,$acueducto,$asunto,$mensaje,$factura){
        //try {

            //echo $email.$acueducto.$asunto.$mensaje.$factura;
            if($acueducto == 'SD'){
                $company = "Caasdenlinea";
            }
    
            if($acueducto == 'BC'){
                $company = 'factura@coraaboenlinea.com';
            }
    
            //Se verifica la cantidad de correos enviados en el día actual, si se acerca a las 210, pues se utiliza el otro cliente de correos
            //$cantidad = new Factura();
            //$jsonEnvioCorreos = $cantidad->intentosFacturasPorCorreo();

            $intentosEnvioCorreo = json_decode($jsonEnvioCorreos,true)["payload"]["intentos"];

            //print_r($intentosEnvioCorreo);

            //if($intentosEnvioCorreo < 210){
                $host = "premium130.web-hosting.com";
                $port = 465;
                $smtpSecure = "ssl";
                $username = "no-reply@caasdenlinea.com";
                $password = "Aceasoft2015";
            //}


            /*if($intentosEnvioCorreo >= 210){
                $host = "smtpout.aceainternational.com";
                $port = "465";
                $smtpSecure = "ssl";
                $username = "factura@aceainternational.com";
                $password = "Aceasoft2021";
            }*/

            //return false;


            //****************************************************************
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPDebug=0;
            $mail->SMTPAuth=true;
            $mail->SMTPSecure=$smtpSecure;
            $mail->Host=$host;
            $mail->Port=$port;
            $mail->Username=$username;
            $mail->Password=$password;
            //$mail->Username="no-reply@caasdenlinea.com";
            //$mail->Password="acea4421";
            //$mail->Username="info@caasdenlinea.com";
            //$mail->Password="Aceasoft2015";

            //****************************************************************
            $mail->setFrom('no-reply@caasdenlinea.com', $company);
            $mail->Subject= $asunto;
            $mail->msgHTML($mensaje);
            $mail->addAddress(strtolower($email),"Estimado Usuario");

            if ($factura != "") $archivo = '../../temp/'.$factura.'.pdf';
            
            $mail->AddAttachment($archivo);
            //error_log("\n-------------------------------".$email, 3, "/logs/errores_envio_factura_digital.log");
            if(!$mail->send()){
                //error_log("\n-------------------------------".$mail->ErrorInfo, 3,"../../logs/envio_factura_digital.log");
                echo "Error al enviar: ".$mail->ErrorInfo;
                return false;
            }
            else{                
                //error_log("\n-------------------------------\n"."Email: ".$email."\n Mensaje".$mensaje, 3,"../../logs/envio_factura_digital.log");
                echo "Mensaje enviado";
                return true;
            }
        //} catch (Exception $th) {
            //error_log("\n-------------------------------\n ".$th->getMessage(), 3,"../../logs/envio_factura_digital.log");
            //error_log("\n-------------------------------\n ".$th->getMessage(), 1,"holguinjean1@gmail.com");
            //return false;
       // }
		
	}


    public function enviarencuesta($email,$acueducto,$asunto,$mensaje){
        if($acueducto == 'SD'){
            $company = 'facturacion@caasd.com';
            $flyerimg = 'facturas_digital/encuesta.png';
        }

       /* if($acueducto == 'BC'){
            $company = 'info@coraaboenlinea.com';
            $flyer = 'facturas_digital/CORAABO.jpg';
        }*/

        //****************************************************************
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug=4;
        $mail->SMTPAuth=true;
        $mail->SMTPSecure="ssl";
        $mail->Host="premium130.web-hosting.com";
        $mail->Port='465';
        //$mail->Username="info@caasdenlinea.com";
        //$mail->Password="Aceasoft2015";
        $mail->Username="no-reply@caasdenlinea.com";
        $mail->Password="Aceasoft2015";
        //$mail->Username="factura@caasdenlinea.com";
        //$mail->Password="Aceasoft2015";
        //****************************************************************
        //$mail->setFrom('facturacion@caasd.com','facturacion@caasd.com');
        $mail->setFrom($company,$company);
        $mail->Subject= $asunto;
        $mail->msgHTML($mensaje);
        $mail->addAddress($email,"Estimado Usuario");
        //$archivo = 'facturas_digital/'.$factura.'.pdf';

        //$mail->AddAttachment($archivo);
        //$mail->AddAttachment($flyerimg);
        if(!$mail->send()){
            echo "Error al enviar: ".$mail->ErrorInfo;
            return false;
        }
        else{
            echo "Mensaje enviado";
            return true;
        }
    }


    public function enviarcorreoflyer($email,$acueducto,$asunto,$mensaje){
        if($acueducto == 'SD') $company = 'facturacion@caasd.com';
        if($acueducto == 'BC') $company = 'info@coraaboenlinea.com';
        //if($acueducto == 'BC') $company = 'facturacion@caasd.com';

        //****************************************************************
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug=4;
        $mail->SMTPAuth=true;
        $mail->SMTPSecure="ssl";
        $mail->Host="premium130.web-hosting.com";
        $mail->Port='465';
        //$mail->Username="info@caasdenlinea.com";
        //$mail->Password="Aceasoft2015";
        //$mail->Username="no-reply@caasdenlinea.com";
        //$mail->Password="acea4421";
        $mail->Username="no-reply@caasdenlinea.com";
        $mail->Password="Aceasoft2015";
        //****************************************************************
        //$mail->setFrom('facturacion@caasd.com','facturacion@caasd.com');
        $mail->setFrom($company,$company);
        $mail->Subject= $asunto;
        $mail->msgHTML($mensaje);


        $array = explode(";", $email);
        foreach ($array as $valor) {
            $mail->addAddress($valor,"");
        }

        //$mail->addAddress($email,"Estimado Usuario");
        //$archivo = 'facturas_digital/'.$factura.'.pdf';
        //$mail->AddAttachment($archivo,$archivo);
        if(!$mail->send()){
            echo "Error al enviar: ".$mail->ErrorInfo;
            return false;
        }
        else{
            echo "Mensaje enviado";
            return true;
        }
    }

	public function reciboPagoRecurrente($email,$datos){

	    $asunto = utf8_decode('Recibo de su pago a CAASD');

	    echo $asunto;
        $mensaje = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

 <head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>CAASD En Linea</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

</head>

<body style="margin: 0; padding: 0; font-family: verdana;">
                    <div class="login-header" id="payment-header">
                        <h3>Ha realizado un nuevo pago</h3>
                    </div>

                    <div class="box-container" id="box-payment">
                        <h2>Pago de servicio de agua CAASD</h2>
                        <div class="payment-info">
                            <div class="reg-info">
                                <p style="font-family: arial, sans-serif;
                                         color: #2980b9;
                                         font-weight: bold;
                                         margin-left: 1024px;">Recibo</p>

                                <hr style="height: 3px;background-color: #2980b9;"/>
                                <p style="margin: 0 0 10px;
                                      font-family: arial, sans-serif;
                                      margin-top: 20px;;
                                      font-weight: bold;
                                      color: #2980b9;">Resumen de la transacción: </p>
                                <table width="100%">
                                    <thead style="border-bottom: 1px solid black;">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Número de tarjeta</th>
                                        <th>Id de Transacción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>'.date('d/m/Y').'</td>
                                        <td>'.$datos["cardNumber"].'</td>
                                        <td>'.$datos["transactionId"].'</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p style="margin: 0 0 10px;
                                          font-family: arial, sans-serif;
                                          margin-top: 20px;;
                                          font-weight: bold;
                                          color: #2980b9;">Detalles de la transacción: </p>
                                <table width="100%"">
                                <thead style="border-bottom: 1px solid black;">
                                <tr>
                                    <th>Código de sistema</th>
                                    <th id="thTitular">Titular</th>
                                    <th id="thNumeroPago">No. de Pago</th>
                                    <th id="thMonto">Monto</th>
                                </tr>
                                </thead>
                                <tbody>
                                 <tr>
                                    <td>'.$datos['inmueble'].'</td>
                                    <td>'.$datos['nombre'].'</td>
                                    <td>'.$datos['numPago'].'</td>
                                    <td>'.money_format("RD%.2n",$datos['montoPen']).'</td>
                                 </tr>
                                                  </tbody>
                                </table>
                                <div class="btn-pay">
                                    <a href="../" class="btn btn-reg" style="color: white; margin-top: 30px;">Volver Atrás</a>
                                </div>
                            </div>
                        </div>
                    </div>
  
</body>

</html>';

        //$mail = new PHPMailer();
        $this->isSMTP();
        $this->SMTPDebug=4;
        $this->SMTPAuth=true;
        $this->SMTPSecure="ssl";
        $this->Host="premium130.web-hosting.com";
        $this->Port='465';
        //$mail->Username="info@caasdenlinea.com";
        //$mail->Password="Aceasoft2015";
        //$mail->Username="no-reply@caasdenlinea.com";
        //$mail->Password="acea4421";
        $this->Username="no-reply@caasdenlinea.com";
        $this->Password="Aceasoft2015";
        //****************************************************************
        //$mail->setFrom('facturacion@caasd.com','facturacion@caasd.com');
        $this->setFrom('no-replay@caasdenlinea.com','no-replay@caasdenlinea.com');
        $this->Subject= $asunto;
        $this->msgHTML($mensaje);
        $this->addAddress($email,"Estimado Usuario");
        /*$archivo = 'facturas_digital/'.$factura.'.pdf';
        $mail->AddAttachment($archivo,$archivo);*/
        if(!$this->send()){
            echo "Error al enviar: ".$this->ErrorInfo;
            return false;
        }
        else{
            echo "Mensaje enviado";
            return true;
        }
    }

	public function errorPago($email,$datos){

	    $asunto = utf8_decode('Recibo de su pago a CAASD');
        $mensaje =   '<div class="login-header">
                        <h3>Error aplicando su pago</h3>
                 </div>
                 <div class="box-container" id="box-payment">
                  
                    <div class="reg-info">
                        <h1 style="text-align: center;">Ocurrió un error aplicando su pago</h1>
                        <p>Un error ocurrió mientras registrabamos su pago, los cargos fueron aplicados pero ocurrió un error registrando el pago en nuestro sistema.Comuniquese con nosotros.</a></p>
                        <p style="color: #2980b9"><b>No se aplicó el pago a los siguientes contratos:</b></p>
                            <div class="btn-pay fact-body">
                                <a href="../" class="btn btn-reg" style="color: white;">Volver Atrás</a>
                            </div>
                        </div>

                  </div>';

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug=4;
        $mail->SMTPAuth=true;
        $mail->SMTPSecure="ssl";
        $mail->Host="premium130.web-hosting.com";
        $mail->Port='465';
        //$mail->Username="info@caasdenlinea.com";
        //$mail->Password="Aceasoft2015";
        //$mail->Username="no-reply@caasdenlinea.com";
        //$mail->Password="acea4421";
        $mail->Username="no-replay@caasdenlinea.com";
        $mail->Password="Aceasoft2015";
        //****************************************************************
        //$mail->setFrom('facturacion@caasd.com','facturacion@caasd.com');
        $mail->setFrom('no-replay@caasdenlinea.com','no-replay@caasdenlinea.com');
        $mail->Subject= $asunto;
        $mail->msgHTML($mensaje);
        $mail->addAddress($email,"Estimado Usuario");
        /*$archivo = 'facturas_digital/'.$factura.'.pdf';
        $mail->AddAttachment($archivo,$archivo);*/
        if(!$mail->send()){
            echo "Error al enviar: ".$mail->ErrorInfo;
            return false;
        }
        else{
            echo "Mensaje enviado";
            return true;
        }
    }

	public function errorPagoRecurrente($email,$datos){


	    $asunto = utf8_decode('Problema al efectuar pago de CAASD');

	   // echo $asunto;
        $mensaje =   '<div class="login-header">
                        <h3>Error aplicando su pago</h3>
                 </div>
                 <div class="box-container" id="box-payment">
                  
                    <div class="reg-info">
                        <h1 style="text-align: center;">Ocurrió un error aplicando su pago</h1>
                        <p>No pudimos completar su último pago. </p>
                         <table id="tblPaymentError">
                                <thead>
                                <tr>
                                    <th>Inmueble</th>
                                    <th>Nombre del cliente</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>'. $datos['detCliente']['inmueble'].'</td>
                                        <td>'. $datos['detCliente']['nombre'] .'</td>
                                    </tr>
                                    </tbody>
                               </table>
                            <div class="btn-pay fact-body">
                                <a href="../" class="btn btn-reg" style="color: white;">Volver Atrás</a>
                            </div>
                        </div>

                  </div>';

       // $mail = new PHPMailer();
        $this->isSMTP();
        $this->SMTPDebug=4;
        $this->SMTPAuth=true;
        $this->SMTPSecure="ssl";
        $this->Host="premium130.web-hosting.com";
        $this->Port='465';
        //$mail->Username="info@caasdenlinea.com";
        //$mail->Password="Aceasoft2015";
        //$mail->Username="no-reply@caasdenlinea.com";
        //$mail->Password="acea4421";
        $this->Username="no-replay@caasdenlinea.com";
        $this->Password="Aceasoft2015";
        //****************************************************************
        //$mail->setFrom('facturacion@caasd.com','facturacion@caasd.com');
       $this->setFrom('no-replay@caasdenlinea.com','no-replay@caasdenlinea.com');
       $this->Subject= $asunto;
       $this->msgHTML($mensaje);
       $this->addAddress($email,"Estimado Usuario");
        /*$archivo = 'facturas_digital/'.$factura.'.pdf';
        $mail->AddAttachment($archivo,$archivo);*/
        if(!$this->send()){
            echo "Error al enviar: ".$this->ErrorInfo;
            return false;
        }
        else{
            echo "Mensaje enviado";
            return true;
        }
    }
}