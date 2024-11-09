<?php
include ('fpdf.php');
include ('class.facturas.php');
include ('class.inmuebles.php');
include ('class.correo.php');
//require_once ('../../funciones/barcode/barcode.inc.php');

$a=new facturas();
$registros=$a->facDigitalPendientes();
while(oci_fetch($registros)){
	$factura = oci_result($registros, 'CONSEC_FACTURA');
	$per_cierre = oci_result($registros, 'PERIODO');
	$email = oci_result($registros, 'EMAIL');
	$acueducto = oci_result($registros, 'ACUEDUCTO');
	$intentos = oci_result($registros, 'INTENTOS_ENVIO');
	
	$intentos ++;
	
	if($acueducto == 'SD'){
		$empresa = utf8_decode('Corporación del Acueducto y Alcantarillado de Santo Domingo CAASD');
	}
	if($acueducto == 'BC'){
		$empresa = utf8_decode('Corporación del Acueducto y Alcantarillado de Bocachica CORAABO');
	}
		
	$asunto = utf8_decode('Facturacion Electrónica ').$empresa;
	$mensaje = 'Factura digital No '.$factura.' correspondiente al periodo '.$per_cierre;
		
	$z=new correo();
	$bandera = $z->enviarcorreo($email,$acueducto,$asunto,$mensaje,$factura,$per_cierre,$intentos);
		
	if($bandera == false){
		$error='No se pudo enviar el correo en el intento '.$intentos;
		$enviado = 'N';
		$e=new facturas();
		$e->datosEnvioFacDigPend($factura, $per_cierre, $enviado, $error, $intentos);
	}
	else if($bandera == true){
		$error='Envio satisfactorio en el intento '.$intentos;
		$enviado = 'S';
		unlink("facturas_digital/".$factura.".pdf");
		$e=new facturas();
		$e->datosEnvioFacDigPend($factura, $per_cierre, $enviado, $error, $intentos);
	}
			
	sleep(5);
	unset($factura,$per_cierre,$email,$acueducto,$empresa,$asunto,$mensaje,$intentos);

}oci_free_statement($registros);	
?>