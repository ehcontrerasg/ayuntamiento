<?php
include 'mail/class.phpmailer.php';

class correo {

public function enviarcorreo($email,$acueducto,$asunto,$mensaje,$factura){
    try{
        if($acueducto == 'SD') $company = 'facturacion@caasd.com';
		if($acueducto == 'BC') $company = 'facturacion@coraabo.com';
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->SMTPDebug=2;
		$mail->SMTPAuth=true;
		$mail->SMTPSecure="ssl";
		//$mail->Host="172.16.1.142";
        $mail->Host="premium130.web-hosting.com";
		$mail->Port='465';
		//$mail->Username="info@caasdenlinea.com";
        //$mail->Password="Aceasoft2015";
        $mail->Username="no-reply@coraaboenlinea.com";
        $mail->Password="Aceasoft2015";
		$mail->setFrom('no-reply@coraaboenlinea.com','facturacion@coraabo.com');
		$mail->Subject= $asunto;
		$mail->msgHTML($mensaje);
		$mail->addAddress($email,"Estimado Usuario");
		$archivo = 'facturas_digital/'.$factura.'.pdf';
        $mail->AddAttachment($archivo,$archivo);
		
		if(!$mail->send()){
            error_log("\n-------------------------------".$mail->ErrorInfo, 3,"../logs/ErroresEnvioFacturaDigital.log");
            //error_log("\n-------------------------------".$mail->ErrorInfo, 1, "holguinjean1@gmail.com");
			echo "Error al enviar: ".$mail->ErrorInfo;
			return false;
		}
		else{
            error_log("\n-------------------------------\n"."Email: ".$email."\n Mensaje".$mensaje, 3,"../logs/EnvioFacturaDigital.log");
            //error_log("\n-------------------------------\n"."Email: ".$email."\n Mensaje".$mensaje, 1,"holguinjean1@gmail.com");
			echo "Mensaje enviado";
			return true;
		}
    }catch(Exception $ex){
        error_log("\n-------------------------------\n ".$ex, 1,"../logs/EnvioFacturaDigital.log");
        //error_log("\n-------------------------------\n"."Email: ".$email."\n Mensaje".$mensaje." ".$ex, 1,"holguinjean1@gmail.com");
    }

		
	}

	//Función que envia correo indicándole al usuario que su pago automático se aplicó exitosmente
    public function reciboPagoRecurrente($datos_cliente,$datos_pago){

        include_once  "../../recaudo/clases/class.domiciliacion.php";
        include_once  "../../clases/class.encript-decript.php";

        $d = new Domiciliacion();
        $encript_descript = new EncriptDecript();
        $numero_tarjeta_desencriptada = $encript_descript->decryption($datos_pago["NUMERO_TARJETA"]);
        $numero_tarjeta = $d->enmascararTarjeta($numero_tarjeta_desencriptada);

        $email = $datos_cliente["EMAIL"];
        if ($email== null || $email ==" " || $email =="")
            $email = $datos_cliente["EMAIL2"];


        $asunto = utf8_decode('Recibo de su pago recurrente '.$datos_cliente["SIGLA_PROYECTO"]);

        $from   = "";
        $img    = "https://aceasoft.com/images/";
        $margin_left = "45%";
        if($datos_cliente["SIGLA_PROYECTO"] == "CAASD"){
            $from = "no-reply@caasdenlinea.com";
            $img  .= "LogoCaasd.png";

        }else if ($datos_cliente["SIGLA_PROYECTO"] == "CORAABO"){
            $from = "no-reply@caasdenlinea.com";
            $img .= "logo_coraabo.jpg";
            $margin_left = "37%";
        }

            $mensaje = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                               <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                               <title>Pagos Recurrentes</title>
                               <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                            </head>
                        <body style="margin: 0; padding: 0; font-family: verdana;">
                        <div class="box-container" id="box-payment">
                            <div id="logo" style="text-align: center;">
                             <img src='.$img.'  alt=logo'.$datos_cliente["SIGLA_PROYECTO"].' style="margin-top:-12px; position:absolute; height:100px;">
                            </div>                   
                            <h2 style="text-align: center">Pago recurrente de servicio de '.$datos_cliente["SIGLA_PROYECTO"].'</h2>
                            <div class="payment-info">
                                <div class="reg-info"> 
                                    <p style="font-family: arial,sans-serif;
                                          color: #2980b9;
                                          font-weight: bold;
                                          margin-left: 85%;
                                          padding: 0px;
                                          margin-top: -18px;
                                          position: absolute; ">Recibo</p>
                                <hr style="height: 3px;
                                           background-color: #2980b9;
                                           margin-top: 4px;"/>
                                <table width="100%">
                                    <thead style="border-bottom: 1px solid black;">
                                    <tr>
                                        <th>Código de sistema</th>
                                        <th>Nombre del cliente</th>
                                        <th>Número de tarjeta</th>
                                        <th id="thNumeroPago">No. de Pago</th>
                                        <th id="thNumeroPago">No. de Transacción</th>
                                        <th id="thMonto">Monto</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="text-align: center">'.$datos_cliente['INMUEBLE'].'</td>
                                        <td style="text-align: center">'.$datos_cliente['NOMBRE_CLIENTE'].'</td>
                                        <td style="text-align: center">'.$numero_tarjeta.'</td>
                                        <td style="text-align: center">'.$datos_pago["codigo_pago"].'</td>
                                        <td style="text-align: center">'.$datos_pago["CODIGO_REFERENCIA"].'</td>
                                        <td style="text-align: center">'.money_format("RD%.2n",$datos_pago['MONTO_PENDIENTE']).'</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </body>
                </html>';

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug=4;
        $mail->SMTPAuth=true;
        $mail->SMTPSecure="ssl";
        $mail->Host="premium130.web-hosting.com";
        $mail->Port='465';
        $mail->Username="no-reply@caasdenlinea.com";
        $mail->Password="Aceasoft2015";
        //****************************************************************
        $mail->setFrom($from,$from);
        $mail->Subject= $asunto;
        $mail->msgHTML($mensaje);
        $mail->addAddress($email,"Estimado Usuario");
        if(!$mail->send()){
            echo "Error al enviar: ".$mail->ErrorInfo;
            return false;
        }
        else{
            echo "Mensaje enviado";
            return true;
        }
    }

    //Función que envia un correo al usuario indicando que hubo un problema al intentar registrar el pago en nuestro sistema
    public function errorPagoRecurrente($datos_cliente){

        $asunto = utf8_decode('Problema al efectuar pago de ' . $datos_cliente["SIGLA_PROYECTO"]);

        $email = $datos_cliente["EMAIL"];
        if ($email== null || $email ==" " || $email =="")
            $email = $datos_cliente["EMAIL2"];

        $from   = "";
        if($datos_cliente["SIGLA_PROYECTO"] == "CAASD"){
            $from = "no-reply@caasdenlinea.com";
        }else if ($datos_cliente["SIGLA_PROYECTO"] == "CORAABO"){
            $from = "no-reply@caasdenlinea.com";
        }

        $mensaje = '
                            <div style="background-color: #fafafa; padding: 30px;">
                                 <table style="margin: 0px auto; background-color: white; width: 100%;">
                                    <thead>
                                        <tr>
                                            <td style="padding: 12px; background: linear-gradient(135deg, #2980b9 39%, #2853af 100%);color: white;font-size: 20px;text-align: center;">
                                                Error aplicando su pago recurrente
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="color: #0e0d0e;font-size: 19px; text-align: center; padding: 21px;">
                                                '.utf8_decode('Ocurrió aplicando su pago recurrente').'
                                             </td>
                                        </tr>
                                        <tr><td><img style="width: 15%;margin-left: 44%;" src="http://aceasoft.com/images/error-icon.png"/></td></tr>
                                        <tr>
                                            <td style="color: #0e0d0e; font-size: 16px; text-align: center; padding: 21px;">
                                                '.utf8_decode('Un error ocurrió mientras registrabamos su pago, los cargos fueron aplicados pero ocurrió un error registrando el pago en nuestro sistema. 
                                                Puede llamar al ').'<strong>(809) 598-1722 '.utf8_decode(' OPCIÓN 2').'</strong>                                             
                                            </td>
                                         </tr>
                                    </tbody>  
                                    <tfoot>
                                        <tr>
                                            <td bgcolor="#2980b9" align="center" style="background: linear-gradient(135deg, #2980b9 39%, #2853af 100%);">
                                               <p style="color: #ffffff; font-size: 12px;">Autopista las Americas, Esq. Calle Masoneria. Ensanche Ozama, Santo Domingo. ' . utf8_decode('Repúbica Dominicana.') . '</p>
                                               <p style="color: #ffffff; font-size: 12px;">(809) 598-1722</p>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        ';
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug=4;
        $mail->SMTPAuth=true;
        $mail->SMTPSecure="ssl";
        $mail->Host="premium130.web-hosting.com";
        $mail->Port='465';
        $mail->Username="no-reply@caasdenlinea.com";
        $mail->Password="Aceasoft2015";
        //****************************************************************
        $mail->setFrom($from,$from);
        $mail->Subject= $asunto;
        $mail->msgHTML($mensaje);
        $mail->addAddress($email,"Estimado Usuario");
        if(!$mail->send()){
            echo "Error al enviar: ".$mail->ErrorInfo;
            return false;
        }
        else{
            echo "Mensaje enviado";
            return true;
        }
    }

    //Función que envia un correo al usuario indicando que hubo un problema al intentar realizar el cobro a su tarjeta
    public function errorCobroTarjeta($datos_cliente,$mensaje_cardnet){

        $asunto = utf8_decode('Problema al efectuar pago de ' . $datos_cliente["SIGLA_PROYECTO"]);

        $email = $datos_cliente["EMAIL"];
                if ($email== null || $email ==" " || $email =="")
                    $email = $datos_cliente["EMAIL2"];

        $from   = "";
        if($datos_cliente["SIGLA_PROYECTO"] == "CAASD"){
            $from = "no-reply@caasdenlinea.com";
        }else if ($datos_cliente["SIGLA_PROYECTO"] == "CORAABO"){
            $from = "no-reply@caasdenlinea.com";
        }

        $mensaje = '
                        <html>
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            </head>
                            <body>
                                <div style="background-color: #fafafa; padding: 30px;">
                                 <table style="margin: 0px auto; background-color: white; width: 100%;">
                                    <thead>
                                        <tr>
                                            <td style="padding: 12px; background: linear-gradient(135deg, #2980b9 39%, #2853af 100%);color: white;font-size: 20px;text-align: center;">
                                                Error procesando su pago recurrente
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="color: #0e0d0e;font-size: 19px; text-align: center; padding: 21px;">
                                                '.utf8_decode('Ocurrió un error procesando su pago recurrente en la plataforma externa').' 
                                             </td>
                                        </tr>
                                        <tr><td><img style="width: 15%;margin-left: 44%;" src="https://aceasoft.com/images/error-icon.png"/></td></tr>
                                        <tr>
                                            <td style="color: #0e0d0e; font-size: 16px; text-align: center; padding: 21px;">
                                              Un error '.utf8_decode('ocurrió intentando realizar el cobro automático a su tarjeta asegúrese de que su tarjeta esté activa o que tenga balance suficiente. Se intentará realizar el cobro más tarde. ').
                                            ' Mensaje de la plataforma externa: '. $mensaje_cardnet .
                                            '</td>
                                         </tr>
                                    </tbody>  
                                    <tfoot>
                                        <tr>
                                            <td bgcolor="#2980b9" align="center" style="background: linear-gradient(135deg, #2980b9 39%, #2853af 100%);">
                                               <p style="color: #ffffff; font-size: 12px;">'.utf8_decode('Autopista las Américas').', Esq. Calle Masoneria. Ensanche Ozama, Santo Domingo. ' . utf8_decode('Repúbica Dominicana.') . '</p>
                                               <p style="color: #ffffff; font-size: 12px;">(809) 598-1722'. utf8_decode('Opción 2').'</p>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <!--<p style="color: gray; font-size: 14px; text-align: center;">
                                    Si no ha solicitado el servicio de pago recurrente por favor ignorar este correo.
                                </p>-->
                            </div>
                            </body>
                        </html>        
                   ';

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug=4;
        $mail->SMTPAuth=true;
        $mail->SMTPSecure="ssl";
        $mail->Host="premium130.web-hosting.com";
        $mail->Port='465';
        $mail->Username="no-reply@caasdenlinea.com";
        $mail->Password="Aceasoft2015";
        //****************************************************************
        $mail->setFrom($from,$from);
        $mail->Subject= $asunto;
        $mail->msgHTML($mensaje);
        $mail->addAddress($email,"Estimado Usuario");
        if(!$mail->send()){
            echo "Error al enviar: ".$mail->ErrorInfo;
            return false;
        }
        else{
            echo "Mensaje enviado";
            return true;
        }
    }

    public function correoVerificacionFechaExpiracion($datos_cliente){

        $asunto = utf8_decode('Actualice sus datos del servicio de pago recurrente de ' . $datos_cliente["SIGLA_PROYECTO"]);

        $email = $datos_cliente["EMAIL"];
        if ($email== null || $email ==" " || $email =="")
            $email = $datos_cliente["EMAIL2"];

        $from   = "";
        if($datos_cliente["SIGLA_PROYECTO"] == "CAASD"){
            $from = "no-replay@caasdenlinea.com";
        }else if ($datos_cliente["SIGLA_PROYECTO"] == "CORAABO"){
            $from = "no-replay@coraaboenlinea.com";
        }

        $mensaje = '        <!doctype html>
                                <head>
                                    
                                </head>
                                <body>
                                     
                                    <div style="background-color: #fafafa; padding: 30px;">
                                         <meta charset="utf-8"/>
                                        <table style="margin: 0px auto; background-color: white; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <td style="padding: 12px; background: linear-gradient(135deg, #2980b9 39%, #2853af 100%);color: white;font-size: 20px;text-align: center;">
                                                        '.utf8_decode('Advertencia de actualización de datos').' 
                                                    </td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="color: #0e0d0e;font-size: 19px; text-align: center; padding: 21px;">
                                                        '. utf8_decode('¡Advertencia!').' 
                                                     </td>
                                                </tr>
                                                <tr><td><img style="width: 15%;margin-left: 44%;" src="http://aceasoft.com/images/warning.png"/></td></tr>
                                                <tr>
                                                    <td style="color: #0e0d0e; font-size: 16px; text-align: center; padding: 21px;">
                                                       Su tarjeta registrada para el servicio de pagos recurrentes de '. $datos_cliente["SIGLA_PROYECTO"].utf8_decode( ' está próximo ').'a vencer, '.utf8_decode('dirígase').' a una de nuestras oficinas
                                                       para proceder con la '.utf8_decode('actualización').' de los datos o '.utf8_decode('comuníquese').' con nosotros <strong>(809) 598-1722 '.utf8_decode('opción 2').'</strong>.                                           
                                                    </td>
                                                </tr>
                                            </tbody>  
                                            <tfoot>
                                                <tr>
                                                    <td bgcolor="#2980b9" align="center" style="background: linear-gradient(135deg, #2980b9 39%, #2853af 100%);">
                                                       <p style="color: #ffffff; font-size: 12px;">Autopista las Americas, Esq. Calle Masoneria. Ensanche Ozama, Santo Domingo. ' . utf8_decode('Repúbica Dominicana.') . '</p>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <!--<p style="color: gray; font-size: 14px; text-align: center;">
                                                Si no ha solicitado el servicio de pago recurrente por favor ignorar este correo.
                                        </p>-->
                                    </div>
                                </body>
                            </html>    
                            
                        ';
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug=4;
        $mail->SMTPAuth=true;
        $mail->SMTPSecure="ssl";
        $mail->Host="smtpout.secureserver.net";
        $mail->Port='465';
        $mail->Username="factura@caasdenlinea.com";
        $mail->Password="Aceasoft2015";
        //****************************************************************
        $mail->setFrom($from,$from);
        $mail->Subject= $asunto;
        $mail->msgHTML($mensaje);
        $mail->addAddress($email,'Estimado Usuario');
        if(!$mail->send()){
            echo 'Error al enviar: '.$mail->ErrorInfo;
            return false;
        }
        else{
            echo 'Mensaje enviado';
            return true;
        }
    }

    public function enviarCorreoSolicitudes($cuerpoCorreo,$remitente,$destinatario,$asunto='',$cc=[]){
        //****************************************************************
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug=4;
        $mail->SMTPAuth=true;
        $mail->Host='172.16.1.247';
        $mail->Port='25';
        $mail->Username='desarrollo@aceadom.local';
        $mail->Password="123";
        //****************************************************************
        $mail->setFrom($remitente,$remitente);
        if($cc != ''){
            foreach ($cc as &$correoCopia){
                $mail->addCC($correoCopia,$correoCopia);
            }

            unset($correoCopia);
        }
        $mail->Subject= $asunto;
        $mail->msgHTML($cuerpoCorreo);
        $mail->addAddress($destinatario);
        if(!$mail->send()){
            echo "Error al enviar: ".$mail->ErrorInfo;
            return false;
        }
        else{
            echo "Mensaje enviado";
            return true;
        }
    }
}

