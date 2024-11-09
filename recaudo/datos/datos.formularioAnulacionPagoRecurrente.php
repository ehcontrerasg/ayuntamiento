<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once "../../clases/class.cliente.php";
include_once "../../recaudo/clases/class.domiciliacion.php";
include_once "../../clases/class.encript-decript.php";

session_start();
$nombre_usuario = $_SESSION["nombre"];
$c = new Cliente();
$codigo_inmueble = $_POST["codigo_inmueble"];
$codigo_inmueble = 203050;
$datos_cliente_res = $c->getDatosCliente($codigo_inmueble);
$datos_cliente = oci_fetch_assoc($datos_cliente_res);
$imagen = "";

$img_width       =  "8%";
$img_margin_left =  "47px";
$img_margin_top  =  "3%";
$img_height      =  "90px";

if($datos_cliente["SIGLA_PROYECTO"]=="CAASD"){
    $imagen = "../../images/LogoCaasd.png";
    $fax="(809) 598-1716";
    $acueducto='Santo Domingo';
    $mensajeCompromiso="<b>La CAASD SE COMPROMETE CON USTED </b>
 Estimado cliente, una vez se recepcione en nuestras oficinas su solicitud, nos comprometemos a darle respuesta en un plazo no
 mayor a quince días a partir de la fecha de recepción. Si transcurre dicho plazo y usted no ha recibido respuesta, favor llamar al
 teléfono (809) 598-1722 opción 2.";
} else{
    $img_width       =  "17%";
    $img_margin_left =  "21px";
    $img_margin_top  =  "2%";
    $img_height      =  "6%";
    $imagen = "../../images/logo_coraabo.jpg";
    $fax="()";
    $acueducto='Boca chica';
    $mensajeCompromiso="<b>CORAABO SE COMPROMETE CON USTED </b>
 Estimado cliente, una vez se recepcione en nuestras oficinas su solicitud, nos comprometemos a darle respuesta en un plazo no
 mayor a quince días a partir de la fecha de recepción. Si transcurre dicho plazo y usted no ha recibido respuesta, favor llamar al
 teléfono (809) 523-6616. Opcion 2";
}

function formatPhoneNumber($phoneNumber='') {
    $phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);

    if(strlen($phoneNumber) > 10) {
        $countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
        $areaCode = substr($phoneNumber, -10, 3);
        $nextThree = substr($phoneNumber, -7, 3);
        $lastFour = substr($phoneNumber, -4, 4);

        $phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
    }
    else if(strlen($phoneNumber) == 10) {
        $areaCode = substr($phoneNumber, 0, 3);
        $nextThree = substr($phoneNumber, 3, 3);
        $lastFour = substr($phoneNumber, 6, 4);

        $phoneNumber = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
    }
    else if(strlen($phoneNumber) == 7) {
        $nextThree = substr($phoneNumber, 0, 3);
        $lastFour = substr($phoneNumber, 3, 4);

        $phoneNumber = $nextThree.'-'.$lastFour;
    }

    return $phoneNumber;

}

function formatCedula($cedula='') {


    if(strlen($cedula) == 11) {
        $areaCode = substr($cedula, 0, 3);
        $nextSeven = substr($cedula, 3, 7);
        $lastOne = substr($cedula, 10, 1);

        $cedula = $areaCode.'-'.$nextSeven.'-'.$lastOne;
    }


    return $cedula;
}

$t = new Domiciliacion();
$datos_tarjeta_res = $t->getDatosTarjeta($codigo_inmueble);
$datos_tarjeta     = oci_fetch_assoc($datos_tarjeta_res);

$ed = new EncriptDecript();
$numero_tarjeta = $t->enmascararTarjeta($ed->decryption($datos_tarjeta["NUMERO_TARJETA"]));

$html = "
<html lang='en'><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=Edge'>
        <link rel='stylesheet' type='text/css' href='../../css/sweetalert.css' />
       <script type='text/javascript' src='../../js/sweetalert.min.js '></script>
    <link rel='stylesheet' type='text/css' href='../../css/bootstrap.min.css'>
        <script src='../../js/jquery-3.2.1.min.js'></script>
            <script src='../../js/bootstrap.min.js'></script>
</head>

<body style='margin: 0;'  cz-shortcut-listen='true'>
                <script >
 $( document ).ready(function() {


//imprimirPDF('formularioPDF');
 
printDiv('formularioPDF');

      function printDiv(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML = 
              \"<html><head><title></title></head><body>\" + 
              divElements + \"</body>\";

            //Print Page
  
            setTimeout(function() {
        window.print();
         window.location.reload();
        window.close();
    }, 250);
          
        }

function imprimirPDF(elem){


    var mywindow = window.open('', 'PRINT' ); // para mostrar en una nuevva pestalla
    mywindow.document.write(document.getElementById(elem).outerHTML);
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/


    setTimeout(function() {
        mywindow.print();
        mywindow.close();
        window.location.reload();
    }, 250);
    

    return true;

 }
});
                </script>
<div id='p1' class='pageArea' style='overflow: hidden; position: relative; width: 935px; height: 1210px; margin-top:20px; margin-left:auto; margin-right:auto; background-color: white;'>

    <!-- Begin shared CSS values -->
    <style class='shared-css' type='text/css'>
        .t {
            -webkit-transform-origin: bottom left;
            -ms-transform-origin: bottom left;
            transform-origin: bottom left;
            -webkit-transform: scale(0.25);
            -ms-transform: scale(0.25);
            transform: scale(0.25);
            z-index: 2;
            position: absolute;
            white-space: pre;
            overflow: visible;
        }
    </style>
    <!-- End shared CSS values -->


    <!-- Begin inline CSS -->
    <style type='text/css'>
        #t1_1{left:60px;bottom:585px;word-spacing:1px;   border:  3px solid black; padding-left: 116%; padding-right: 116%; padding-bottom: 3%; padding-top: 3%; }
        #t2_1{left:68px;bottom:246px;letter-spacing:0.1px;word-spacing:0.4px; border:  3px solid black; text-align: center; padding-left: 2%; padding-right: 2%; padding-bottom: 2%;}
        #t3_1{left:72px;bottom:298px;letter-spacing:0.1px;word-spacing:0.2px;}
        #t4_1{left:75px;bottom:280px;letter-spacing:0.1px;word-spacing:0.2px;}
        #t5_1{left:326px;bottom:262px;letter-spacing:0.1px;word-spacing:0.2px;}
        #t6_1{left:233px;bottom:1124px;letter-spacing:-0.1px;word-spacing:-0.3px;}
        #t7_1{left:416px;bottom:1102px;}
        #t8_1{left:112px;bottom:1039px;word-spacing:-0.2px;}
        #t9_1{left:261px;bottom:1039px;word-spacing:-0.2px;}
        #ta_1{left:89px;bottom:994px;word-spacing:-0.2px;}
        #tb_1{left:43px;bottom:930px;letter-spacing:-0.1px;word-spacing:0.1px;}
        #tc_1{left:43px;bottom:909px;letter-spacing:-0.1px;word-spacing:-0.2px;}
        #td_1{left:43px;bottom:862px;letter-spacing:-0.1px;}
        #te_1{left:581px;bottom:861px;letter-spacing:-0.2px;word-spacing:-0.5px;}
        #tf_1{left:43px;bottom:820px;letter-spacing:-0.1px;word-spacing:-0.2px;}
        #tg_1{left:43px;bottom:779px;letter-spacing:-0.1px;word-spacing:0.2px;}
        #th_1{left:43px;bottom:737px;letter-spacing:-0.1px;}
        #ti_1{left:581px;bottom:780px;letter-spacing:-0.1px;}
        #tj_1{left:43px;bottom:696px;letter-spacing:-0.1px;word-spacing:0.2px;}
        #tk_1{left:43px;bottom:655px;letter-spacing:-0.1px;word-spacing:-1.7px;}
        #tl_1{left:566px;bottom:655px;letter-spacing:-0.1px;word-spacing:-1px;}
        #tm_1{left:71px;bottom:522px;letter-spacing:0.2px;word-spacing:-0.4px;}
        #tn_1{left:71px;bottom:481px;letter-spacing:0.2px;word-spacing:-0.4px;}
        #to_1{left:425px;bottom:524px;word-spacing:0.3px;}
        #tp_1{left:513px;bottom:550px;letter-spacing:0.2px;}
        #tq_1{left:426px;bottom:478px;letter-spacing:0.2px;word-spacing:-0.4px;}
        #tr_1{left:108px;bottom:441px;letter-spacing:0.1px;word-spacing:0.2px;font-size:64px;}
        #ts_1{left:401px;bottom:422px;letter-spacing:0.1px;word-spacing:0.2px;font-size:64px;}
        #tt_1{left:71px;bottom:371px;letter-spacing:0.2px;word-spacing:-1.9px;}
        #tu_1{left:525px;bottom:371px;letter-spacing:0.1px;}
        #tv_1{left:71px;bottom:209px;word-spacing:0.1px;}
        #tw_1{left:415px;bottom:209px;letter-spacing:0.1px;}
        #tx_1{left:506px;bottom:209px;letter-spacing:0.1px;}
        #ty_1{left:71px;bottom:153px;letter-spacing:0.1px;word-spacing:0.4px;}
        #tz_1{left:43px;bottom:71px;letter-spacing:0.1px;word-spacing:0.2px;}
        #t10_1{left:43px;bottom:53px;letter-spacing:0.1px;word-spacing:0.2px;}
        #t11_1{left:778px;bottom:1163px;letter-spacing:0.1px;word-spacing:0.2px;}
#campoFirmaUsuario{ border-bottom:  3px solid black;}
        .s1{
            FONT-SIZE: 60.9px;
            FONT-FAMILY: Tahoma-Bold_5r;
            color: rgb(0,0,0);
        }

        .s2{
            FONT-SIZE: 60.9px;
            FONT-FAMILY: MyriadPro-Bold_5n;
            color: rgb(0,0,0);
        }

        .s3{
            FONT-SIZE: 60.9px;
            FONT-FAMILY: MyriadPro-Regular_5p;
            color: rgb(0,0,0);
        }

        .s4{
            FONT-SIZE: 67.5px;
            FONT-FAMILY: Tahoma-Bold_5r;
            color: rgb(0,0,0);
        }

        .s5{
            FONT-SIZE: 73.3px;
            FONT-FAMILY: Tahoma-Bold_5r;
            color: rgb(0,0,0);
        }

        .s6{
            FONT-SIZE: 73.3px;
            FONT-FAMILY: Tahoma_5v;
            color: rgb(0,0,0);
        }

        #form1_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 316px;	top: 712px;	width: 54px;	height: 26px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 18px Arial, Helvetica, sans-serif;}
        #form2_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 261px;	top: 712px;	width: 54px;	height: 26px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 18px Arial, Helvetica, sans-serif;}
        #form3_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 730px;	top: 538px;	width: 161px;	height: 25px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 19px Arial, Helvetica, sans-serif;}
        #form4_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 125px;	top: 332px;	width: 243px;	height: 25px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 19px Arial, Helvetica, sans-serif;}
        #form5_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 249px;	top: 538px;	width: 298px;	height: 25px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 19px Arial, Helvetica, sans-serif;}
        #form6_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 579px;	top: 455px;	width: 312px;	height: 25px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 19px Arial, Helvetica, sans-serif;}
        #form7_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 139px;	top: 455px;	width: 312px;	height: 25px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 19px Arial, Helvetica, sans-serif;}
        #form8_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 553px;	top: 714px;	width: 316px;	height: 26px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 21px Arial, Helvetica, sans-serif;}
        #form9_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 552px;	top: 332px;	width: 339px;	height: 25px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 19px Arial, Helvetica, sans-serif;}
        #form10_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 248px;	top: 671px;	width: 439px;	height: 26px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 21px 'Courier New', Courier, monospace;}
        #form11_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 220px;	top: 805px;	width: 287px;	height: 43px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 35px Arial, Helvetica, sans-serif;}
        #form12_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 591px;	top: 805px;	width: 301px;	height: 43px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 35px Arial, Helvetica, sans-serif;}
        #form13_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 235px;	top: 1041px;	width: 628px;	height: 25px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 19px Arial, Helvetica, sans-serif;}
        #form14_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 235px;	top: 497px;	width: 656px;	height: 25px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 19px Arial, Helvetica, sans-serif;}
        #form15_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 235px;	top: 373px;	width: 656px;	height: 25px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 19px Arial, Helvetica, sans-serif;}
        #form16_1{	z-index: 2;	padding: 0px;	position: absolute;	left: 235px;	top: 414px;	width: 656px;	height: 25px;	color: rgb(0,0,0);	text-align: left;	background: transparent;	border: none;	font: normal 19px Arial, Helvetica, sans-serif;}

    </style>
    <!-- End inline CSS -->

    <!-- Begin embedded font definitions -->
    <style id='fonts1' type='text/css'>

        /*@font-face {
            font-family: MyriadPro-Bold_5n;
            src: url('fonts/MyriadPro-Bold_5n.woff') format('woff');
        }

        @font-face {
            font-family: MyriadPro-Regular_5p;
            src: url('fonts/MyriadPro-Regular_5p.woff') format('woff');
        }

        @font-face {
            font-family: Tahoma-Bold_5r;
            src: url('fonts/Tahoma-Bold_5r.woff') format('woff');
        }

        @font-face {
            font-family: Tahoma_5v;
            src: url('fonts/Tahoma_5v.woff') format('woff');
        }*/
input {
  border-width: 0 0 2px;
  border-color: black;
}

    </style>
    <!-- End embedded font definitions -->

    <!-- Begin page background -->
    <div id='pg1Overlay' style='width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;'></div>
<!--    <div id='pg1' style='-webkit-user-select: none;'><object width='935' height='1210' data='./form_files/1.svg' type='image/svg+xml' id='pdf1' style='width:935px; height:1210px; -moz-transform:scale(1); z-index: 0;'></object></div>
-->    <!-- End page background -->


    <!-- Begin text definitions (Positioned/styled in CSS) -->
    <div id='t1_1' class='t s1' style='background-color: #C0C0C0'> <b>INFORMACIÓN DE LA TARJETA  </b></div>
    <div id='t2_1' class='t s2'>$mensajeCompromiso</div>
          

 
     
    <img src='".$imagen."' style='width:".$img_width.";     margin-left:".$img_margin_left." ; margin-top:".$img_margin_top."; height: ".$img_height."'>
    <div id='t6_1' class='t s4' style='font-weight: bold; font-size: 70px;'>Corporación de Acueducto y Alcantarillado de ".$acueducto."</div>
    <div id='t7_1' class='t s5'><b>".$datos_cliente["SIGLA_PROYECTO"]."</b></div>
    <!--<div id='t8_1' class='t s5'><b>¡Ya no hagas filas!</b> </div>-->
   <!-- <div id='t9_1' class='t s6'>y no pagues recargos para realizar el pago de tu factura del Agua. </div>-->
    <div id='ta_1' class='t s5'><b>AUTORIZACIÓN DE CANCELACIÓN DE SERVICIO DE PAGO RECURRENTE</b></div>
    <div id='tb_1' class='t s4'><b>Por este medio autorizo a que se cancele el servicio de cargo automático a mi tarjeta (abajo indicada), el pago del </div>
    <div id='tc_1' class='t s4'>servicio contratado a ".$datos_cliente["SIGLA_PROYECTO"].": </b></div>
    <div id='td_1' class='t s4'><b>Fecha: </b>".date("d/m/Y h:i:s")."</div>
    <div id='te_1' class='t s4'><b>Código Sistema:</b> ".$codigo_inmueble."</div>
    <div id='tf_1' class='t s4'><b>Nombre y Apellido:</b> ".$datos_cliente["NOMBRE_CLIENTE"]."</div>
    <div id='tg_1' class='t s4'><b>Cédula de identidad: </b>".formatCedula($datos_cliente["DOCUMENTO"])."</div>
    <div id='th_1' class='t s4'><b>Télefono:</b> ".formatPhoneNumber($datos_cliente["TELEFONO"])." </div>
    <div id='ti_1' class='t s4'><b>Celular: </b>".formatPhoneNumber($datos_cliente["CELULAR"])."</div>
    <div id='tj_1' class='t s4'><b>Correo Electrónico:</b> ".$datos_cliente["EMAIL"]." </div>
    <div id='tm_1' class='t s1'><b>Número de Tarjeta:</b> ".$numero_tarjeta."</div>
    <div id='tn_1' class='t s1'><b>Fecha de vencimiento:</b> ".$datos_tarjeta["FECHA_EXPIRACION"]."</div>
    <div id='to_1' class='t s3'><b>Tipo de Tarjeta: </b>".$datos_tarjeta["TIPO_TARJETA"]."</div>
    <div id='tq_1' class='t s1'><b>Banco Emisor:</b> ".$datos_tarjeta["BANCO_EMISOR"]."</div>
    <!--<div id='tr_1' class='t s3'>En caso de que los datos suministrados anteriormente sufran cambios, me comprometo a comunicarlo a las oficinas </div>
     <div id='ts_1' class='t s3'>comerciales de ".$datos_cliente["SIGLA_PROYECTO"]."</div>-->
    
    <div id='tt_1' class='t s1'><b>Firma del Usuario: <input type='text'  size='25'/> </b></div>
    <div id='tu_1' class='t s1'><b>Cédula: </b>".formatCedula($datos_cliente["DOCUMENTO"])."</b></div>
    <div id='ty_1' class='t s1'><b>Preparado por: </b> ".$nombre_usuario."</div>
    <div id='tz_1' class='t s3'>Código: FO-REC-25 </div>
    <div id='t10_1' class='t s3'>Edición No.: 04 </div>

    <!-- End text definitions -->


    <!-- Begin Form Data -->
    <form>
        <input id='form1_1' type='text' tabindex='16' value='' data-objref='210 0 R' data-field-name='Año'>
        <input id='form2_1' type='text' tabindex='15' value='' data-objref='209 0 R' data-field-name='Mes'>
        <input id='form3_1' type='text' tabindex='9' value='' data-objref='203 0 R' title='Monto del pago' data-field-name='Monto del pago'>
        <input id='form4_1' type='text' tabindex='1' value='' data-objref='195 0 R' title='Fecha' data-field-name='Fecha'>
        <input id='form5_1' type='text' tabindex='8' value='' data-objref='202 0 R' title='Duración del servicio' data-field-name='Duración del servicio'>
        <input id='form6_1' type='text' tabindex='6' value='' data-objref='200 0 R' title='Celular' data-field-name='Celular'>
        <input id='form7_1' type='text' tabindex='5' value='' data-objref='199 0 R' title='Télefono' data-field-name='Télefono'>
        <input id='form8_1' type='text' tabindex='11' value='' data-objref='205 0 R' title='Banco Emisor' data-field-name='Banco Emisor'>
        <input id='form9_1' type='text' tabindex='2' value='' data-objref='196 0 R' title='Código Sistema' data-field-name='Código Sistema'>
        <input id='form10_1' type='text' tabindex='10' maxlength='16' value='' data-objref='204 0 R' title='Tipo de Tarjeta VISA' data-field-name='Tipo de Tarjeta VISA'>
        <input id='form11_1' type='text' tabindex='12' value='' data-objref='206 0 R' title='Firma del Usuario' data-field-name='Firma del Usuario'>
        <input id='form12_1' type='text' tabindex='13' value='' data-objref='207 0 R' title='Cédula' data-field-name='Cédula'>
        <input id='form13_1' type='text' tabindex='14' value='' data-objref='208 0 R' title='Preparado por' data-field-name='Preparado por'>
        <input id='form14_1' type='text' tabindex='7' value='' data-objref='201 0 R' title='Correo Electrónico' data-field-name='Correo Electrónico'>
        <input id='form15_1' type='text' tabindex='3' value='' data-objref='197 0 R' title='Nombre y Apellido' data-field-name='Nombre y Apellido'>
        <input id='form16_1' type='text' tabindex='4' value='' data-objref='198 0 R' title='Cedula de identidad' data-field-name='Cedula de identidad'>

    </form>
    <!-- End Form Data -->

</div>

<!--<a download='FO-REC-25._PAGO_RECURRENTE_CAASD_V.4.pdf' href='https://cloud.idrsolutions.com/online-converter/output/54a8d782-051b-42a3-9961-a1c2978f4668/FO-REC-25._PAGO_RECURRENTE_CAASD_V.4/form.html'></a>-->
</body></html>";

echo $html;





