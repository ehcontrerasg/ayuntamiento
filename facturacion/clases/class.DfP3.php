<?php
include ('../../clases/class.correo.php');
include ('fpdf.php');
include ('class.facturas.php');
//include ('class.inmuebles.php');
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
$a=new facturas();
$registros=$a->flayerPendientes('BC');
$z=new correo();
$z->IsHTML(true);
$z->AddEmbeddedImage('logo.jpg', 'logoimg', 'logo.jpg'); // attach file logo.jpg, and later link to it using identfier logoimg

$asunto = utf8_decode('PAGA TU FACTURA CORAABO DESDE CASA');
$mensaje = "
    <p>Estimado usuario, como parte de las medidas preventivas para reducir el riesgo de contagio masivo de nuestros trabajadores y usuarios, debido al estado de emergencia que atraviesa el país originado por la pandemia mundial Covid-19, a partir deL martes 24 de marzo de 2020 y hasta el 3 de abril, nuestras oficinas permanecerán cerradas.
    Continuarán habilitados nuestros canales de atención y pago en línea:
    <p>
    <h1>
    Servicios en línea:
    </h1>
    <h4>
    Acceda a nuestros servicios en línea leyendo el 
    código QR:
    </h4>
    <p>
    <img src=\"https://aceasoft.com/images/image005.jpg\" /></p>
    <h5>
    Pagos
    </h5>
    <h5>
    Histórico de facturas
    </h5>
    <h5>
     Buzón de quejas y sugerencias
    </h5>
    
    <h4>
     Teleagua:
    </h4>
    <p>
    Realice sus pagos de forma rápida y mayor comodidad llamando al teléfono:
    <p>
    809-523-6616 Opción 2
    <p>
    De lunes a viernes de 8:00 a.m. a 12:00 p.m.
    <p>
    (Horario temporal).
    <h4>
    Pago en línea:
</h4>
<p>

www.coraaboenlinea.com

    
    
    <img src=\"https://aceasoft.com/images/Flyer%20BC.jpg\" /></p>";
$email='';

while(oci_fetch($registros)){
    $email1 = oci_result($registros, 'EMAIL');
    $acueducto = oci_result($registros, 'ID_PROYECTO');
    //$per_cierre = oci_result($registros, 'PERIODO');
    //$factura = oci_result($registros, 'CONSEC_FACTURA');

    $email.=$email1.';';

}oci_free_statement($registros);
trim($email,';');
//echo 'ini: '.$email.' fin';
$bandera = $z->enviarcorreoflyer($email,$acueducto,$asunto,$mensaje);

if($bandera == false){
    echo "error al enviar";
}
else if($bandera == true){

    $e=new facturas();
    ///   $e->actEnvFlayer($factura, $per_cierre);
}
unset($email,$acueducto,$empresa,$asunto,$mensaje,$intentos);


?>