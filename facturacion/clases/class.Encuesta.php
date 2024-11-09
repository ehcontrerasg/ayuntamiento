<?php
include ('../../clases/class.correo.php');
include ('fpdf.php');
include ('class.facturas.php');
//include ('class.inmuebles.php');

$a=new facturas();
$registros=$a->EncuestasPendientes();
while(oci_fetch($registros)){
    //$factura = oci_result($registros, 'CONSEC_FACTURA');
    //$per_cierre = oci_result($registros, 'PERIODO');
    $email = oci_result($registros, 'EMAIL');
    $acueducto = oci_result($registros, 'ACUEDUCTO');
    //$intentos = oci_result($registros, 'INTENTOS_ENVIO');
    //$alias = oci_result($registros, 'ALIAS');
    $codinm = oci_result($registros, 'INMUEBLE');
    $intentos ++;

   /* $agno = substr($per_cierre,0,4);
    $mes = substr($per_cierre,4,2);
    if($mes == '01'){$mes = 'Ene'; $messi = 'Enero';} if($mes == '02'){$mes = 'Feb';$messi = 'Febrero';} if($mes == '03'){$mes = 'Mar';$messi = 'Marzo';} if($mes == '04'){$mes = 'Abr';$messi = 'Abril';}
    if($mes == '05'){$mes = 'May'; $messi = 'Mayo';} if($mes == '06'){$mes = 'Jun';$messi = 'Junio';} if($mes == '07'){$mes = 'Jul';$messi = 'Julio';} if($mes == '08'){$mes = 'Ago';$messi = 'Agosto';}
    if($mes == '09'){$mes = 'Sep'; $messi = 'Septiembre';} if($mes == '10'){$mes = 'Oct';$messi = 'Octubre';} if($mes == '11'){$mes = 'Nov';$messi = 'Noviembre';} if($mes == '12'){$mes = 'Dic';$messi = 'Diciembre';}
*/

    if($acueducto == 'SD'){
        //$empresa = utf8_decode('Corporación del Acueducto y Alcantar xillado de Santo Domingo CAASD');
        $mensaje .= utf8_decode("<font size='2' face='Helvetica'><b>Ayúdanos a brindar un mejor servicio, con tú opinión lo lograremos,  por favor ingresa al link del cuestionario:</font></b><br><br>");
        $mensaje .= utf8_decode("<font size='5' face='Helvetica'><a href='https://forms.gle/XeZrto5ei47pCBx3A'>CUESTIONARIO DE EXPECTATIVAS EN LOS SERVICIOS</a>.<br><br></font><br>");
        $mensaje .= utf8_decode("<img height='220px' width='440px' src='./facturas_digital/encuesta.png'><br><br>");
        $mensaje .= utf8_decode("<font size='2' face='Helvetica'>Como parte de nuestro proceso de Mejora de la Calidad, solicitamos tu colaboración.<br></font>");
        $mensaje .= utf8_decode("<font size='2' face='Helvetica'>Te tomará entre 7 a 10 minutos para ser completado y tus respuestas individuales serán confidenciales y anónimas.<br></font>");
        $mensaje .= utf8_decode("<font size='2' face='Helvetica'>Es importante responder y ser lo más honesto posible. Contamos con tu ayuda!<br></font>");
        $mensaje .= utf8_decode("<br><br><font size='2' face='Helvetica'>Gracias de antemano por tu participación.</font>");
        $mensaje .= utf8_decode("<br><br><font size='2' face='Helvetica'><b>Te invitamos a que compartas con otros este cuestionario. </b></font>");
        $mensaje .= utf8_decode("<br><br><font size='2' face='Helvetica'>Saludos cordiales.</font>");
    }
    /*if($acueducto == 'BC'){
        $empresa = utf8_decode('Corporación del Acueducto y Alcantarillado de Bocachica CORAABO');
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'><b>Estimado(a): ".$alias."</b></font><br><br>");
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'>Anexo a este correo estamos enviando un archivo con un duplicado de tu factura de agua potable y alcantarillado del mes de ".$messi.", correspondiente al inmueble <b>". $codinm."</b></font>");
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'>, la cual puedes <a href='https://www.coraaboenlinea.com'>Pagar aquí</a>.<br><br>Ahorra tiempo y gestiona tus servicios desde la comodidad de tu hogar u oficina entrando al portal <a href='https://coraaboenlinea.com/servicios'>Coraaboenlínea</a>, a través del cual podrás:</font><br><br>");
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'>*   Descargar y pagar tu factura.<br>*  Consultar tus balances.<br>*    Buzón de quejas y sugerencias.</font><br><br>");
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'>En adición, para pagar tu factura puedes llamar al 809-523-6616 (opción 2), visitar cualquiera de nuestras estafetas o puntos de pago.</font>");
        $mensaje .= utf8_decode("<br><br><br><font size='4' face='Helvetica'><b>Corporación de Acueducto y Alcantarillado de Boca Chica</b></font>");
    }*/

    $asunto = utf8_decode('¿Qué espera el Ciudadano Cliente de la Corporación del Acueducto y Alcantarillado de Santo Domingo?');
    //$mensaje = 'Factura digital No '.$factura.' correspondiente al periodo '.$per_cierre;

    $z=new correo();
    $bandera = $z->enviarencuesta($email,$acueducto,$asunto,$mensaje);

    if($bandera == false){
        //$error='No se pudo enviar el correo en el intento '.$intentos;
        $enviado = 'P';
        $e=new facturas();
        $e->datosEncuesta($codinm, $enviado);
    }
    else if($bandera == true){
        //$error='Envio satisfactorio en el intento '.$intentos;
        $enviado = 'E';
        //unlink("facturas_digital/".$factura.".pdf");
        $e=new facturas();
        $e->datosEncuesta($codinm, $enviado);
    }

    sleep(5);
    unset($email,$acueducto,$asunto,$mensaje,$intentos,$codinm);

}oci_free_statement($registros);
?>