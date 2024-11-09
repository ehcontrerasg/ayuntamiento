<?
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
include_once ('../../clases/class.correo.php');
include_once ('fpdf.php');
include_once ('../../clases/class.factura.php');

//$a=new facturas();
$a=new Factura();
$registros=$a->facDigitalPendientes();
while(oci_fetch($registros)){
	$factura = oci_result($registros, 'CONSEC_FACTURA');
	$per_cierre = oci_result($registros, 'PERIODO');
	$email = strtolower(oci_result($registros, 'EMAIL'));
	$acueducto = oci_result($registros, 'ACUEDUCTO');
	$intentos = oci_result($registros, 'INTENTOS_ENVIO');
	$alias = oci_result($registros, 'ALIAS');
	$codinm = oci_result($registros, 'INMUEBLE');
	$intentos ++;

    $agno = substr($per_cierre,0,4);
    $mes = substr($per_cierre,4,2);
    if($mes == '01'){$mes = 'Ene'; $messi = 'Enero';} if($mes == '02'){$mes = 'Feb';$messi = 'Febrero';} if($mes == '03'){$mes = 'Mar';$messi = 'Marzo';} if($mes == '04'){$mes = 'Abr';$messi = 'Abril';}
    if($mes == '05'){$mes = 'May'; $messi = 'Mayo';} if($mes == '06'){$mes = 'Jun';$messi = 'Junio';} if($mes == '07'){$mes = 'Jul';$messi = 'Julio';} if($mes == '08'){$mes = 'Ago';$messi = 'Agosto';}
    if($mes == '09'){$mes = 'Sep'; $messi = 'Septiembre';} if($mes == '10'){$mes = 'Oct';$messi = 'Octubre';} if($mes == '11'){$mes = 'Nov';$messi = 'Noviembre';} if($mes == '12'){$mes = 'Dic';$messi = 'Diciembre';}


    if($acueducto == 'SD'){
		$empresa = utf8_decode('Corporación del Acueducto y Alcantarillado de Santo Domingo CAASD');
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'><b>Estimado(a): ".$alias."</font></b><br><br>");
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'>Anexo a este correo estamos enviando un archivo con un duplicado de tu factura de agua potable y alcantarillado del mes de ".$messi.", correspondiente al inmueble <b>". $codinm."</b></font>");
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'>, la cual puedes <a href='https://caasdenlinea.com.do'>Pagar aquí</a>.<br><br>Ahorra tiempo y gestiona tus servicios desde la comodidad de tu hogar u oficina entrando al portal <a href='https://caasdenlinea.com.do/servicios'>caasdenlínea</a>, a través del cual podrás:</font><br><br>");
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'>*   Descargar y pagar tu factura.<br>*  Consultar tus balances.<br>*    Buzón de quejas y sugerencias.</font><br><br>");
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'>En adición, para pagar tu factura puedes llamar al 809-598-1722 (opción 2), visitar cualquiera de nuestras estafetas o puntos de pago.</font>");
        $mensaje .= utf8_decode("<br><br><br><font size='4' face='Helvetica'><b>Corporación del Acueducto y Alcantarillado de Santo Domingo</b></font>");
	}
	if($acueducto == 'BC'){
		$empresa = utf8_decode('Corporación del Acueducto y Alcantarillado de Bocachica CORAABO');
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'><b>Estimado(a): ".$alias."</b></font><br><br>");
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'>Anexo a este correo estamos enviando un archivo con un duplicado de tu factura de agua potable y alcantarillado del mes de ".$messi.", correspondiente al inmueble <b>". $codinm."</b></font>");
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'>, la cual puedes <a href='https://www.coraaboenlinea.com'>Pagar aquí</a>.<br><br>Ahorra tiempo y gestiona tus servicios desde la comodidad de tu hogar u oficina entrando al portal <a href='https://coraaboenlinea.com/servicios'>coraaboenlínea</a>, a través del cual podrás:</font><br><br>");
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'>*   Descargar y pagar tu factura.<br>*  Consultar tus balances.<br>*    Buzón de quejas y sugerencias.</font><br><br>");
        $mensaje .= utf8_decode("<font size='4' face='Helvetica'>En adición, para pagar tu factura puedes llamar al 809-523-6616 (opción 2), visitar cualquiera de nuestras estafetas o puntos de pago.</font>");
        $mensaje .= utf8_decode("<br><br><br><font size='4' face='Helvetica'><b>Corporación de Acueducto y Alcantarillado de Boca Chica</b></font>");
	}
		
	$asunto = utf8_decode('Facturacion Electrónica ').$empresa;
	//$mensaje = 'Factura digital No '.$factura.' correspondiente al periodo '.$per_cierre;
		
	$z=new correo();
	$bandera = $z->enviarcorreo($email,$acueducto,$asunto,$mensaje,$factura);
		
	if($bandera == false){
		$error='No se pudo enviar el correo en el intento '.$intentos;
		$enviado = 'N';
		$e=new Factura();
		$e->datosEnvioFacDigPend($factura, $per_cierre, $enviado, $error, $intentos);
	}
	else if($bandera == true){
		$error='Envio satisfactorio en el intento '.$intentos;
		$enviado = 'S';
		unlink("facturas_digital/".$factura.".pdf");
		$e=new Factura();
		$e->datosEnvioFacDigPend($factura, $per_cierre, $enviado, $error, $intentos);
	}
			
	sleep(5);
	unset($factura,$per_cierre,$email,$acueducto,$empresa,$asunto,$mensaje,$intentos,$alias,$codinm,$messi,$mes);

}oci_free_statement($registros);	
?>