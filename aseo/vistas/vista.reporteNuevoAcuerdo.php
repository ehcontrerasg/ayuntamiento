<?php
/**
 * Created by PhpStorm.
 * User: JGutierrez
 * Date: 24/08/2019
 * Time: 11:51 AM
 */
error_reporting(E_ALL);
include_once ("../../clases/class.fpdf.php");
include_once ("../../facturacion/clases/class.facturas.php");
include_once '../clases/classPqrs.php';

$inmueble = $_POST["inm"];
$acueducto = $_POST["acu"];
$estado = $_POST["est"];
$direccion = $_POST["dir"];
$urbanizacion = $_POST["urb"];
$uso = $_POST["uso"];
$categoria = $_POST["cat"];
$cuotas = $_POST["cuo"];
$documento = $_POST["doc"];
$tipoDoc = $_POST["tdo"];
$cliente = $_POST["cli"];
$alias = $_POST["ali"];
$telefono = $_POST["tel"];
$email = $_POST["ema"];
$oficina = $_POST["ofi"];
$calidad = $_POST["cal"];
$deuda = $_POST["deu"];
$mora = $_POST["mor"];
$inicial = $_POST["ini"];
$exonerar = $_POST["exo"];
$descuento = $_POST["des"];
$pagar = $_POST["pag"];
$reconexion = $_POST["rec"];
$asistente = $_POST["asi"];
$docasiste = $_POST["das"];
$coduser = $_POST["das"];
$fecha = date('d/m/Y');
$cuotaIni = $_POST["val"];
$saldoPend = $_POST["sal"];
$cuotaMes = $_POST["mes"];
$total = $_POST["tot"];

if($calidad == 1) $calidad = 'PROPIETARIO';
if($calidad == 2) $calidad = 'INQUILINO';
if($calidad == 3) $calidad = 'REPRESENTANTE';
$deuda = number_format($deuda,2,',','.');

$mes = date('m');
if ($mes == '01') $mes = 'Enero'; if ($mes == '02') $mes = 'Febrero'; if ($mes == '03') $mes = 'Marzo'; if ($mes == '04') $mes = 'Abril';
if ($mes == '05') $mes = 'Mayo'; if ($mes == '06') $mes = 'Junio'; if ($mes == '07') $mes = 'Julio'; if ($mes == '08') $mes = 'Agosto';
if ($mes == '09') $mes = 'Septiembre'; if ($mes == '10') $mes = 'Octubre'; if ($mes == '11') $mes = 'Noviembre'; if ($mes == '12') $mes = 'Diciembre';

$pdf = new FPDF('P',  'mm', 'Legal');
$pdf->SetTextColor(0,0,0);
$pdf->AliasNbPages();
$pdf->AddPage('P');
$pdf->SetFont('arial', "B", 10);

if ($acueducto == 'SD') {
    $ciudad = "Santo Domingo";
    $pdf->Text(72, 15, utf8_decode("AYUNTAMIENTO DE SANTO DOMINGO"));
    //$pdf->Text(115, 22, utf8_decode("CAASD"));
    $pdf->Image("../../images/LogoAseo.jpeg", 10, 10, 30, 30);
}
if ($acueducto == 'BC') {
    $ciudad = "Boca Chica";
    $pdf->Text(74, 15, utf8_decode("AYUNTAMIENTO DE BOCACHICA "));
    //$pdf->Text(115, 22, utf8_decode("CORAABO"));
    $pdf->Image("../../images/AyuntamientoBocachica.jpeg", 10, 10, 30, 30);
}
$pdf->SetFont('arial', "B", 12);
$pdf->Text(100, 33, utf8_decode("ACUERDO DE PAGO"));
$pdf->SetFont('arial', "B", 10);
$pdf->Text(135, 48, utf8_decode("CODIGO DE SISTEMA "));
$pdf->SetY(44); $pdf->SetX(176);
$pdf->Cell(25, 5, utf8_decode($inmueble), 1, 3, 'C', false);
$pdf->SetFont('arial', "", 9);
$pdf->Text(10, 62, utf8_decode("En fecha "));
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(58); $pdf->SetX(25);
$pdf->Cell(20, 5, utf8_decode($fecha), 'B', 3, 'C', false);
$pdf->SetFont('arial', "", 9);
$pdf->Text(47, 62, utf8_decode("el(la) señor(a) "));
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(58); $pdf->SetX(68);
$pdf->Cell(90, 5, utf8_decode($alias), 'B', 3, 'C', false);
$pdf->SetFont('arial', "", 9);
$pdf->Text(159, 62, utf8_decode(", cédula"));
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(58); $pdf->SetX(172);
$pdf->Cell(30, 5, utf8_decode($documento), 'B', 3, 'C', false);

$pdf->SetFont('arial', "", 9);
$pdf->Text(10, 68, utf8_decode("en calidad de "));
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(64); $pdf->SetX(31);
$pdf->Cell(35, 5, utf8_decode($calidad), 'B', 3, 'C', false);
$pdf->SetFont('arial', "", 9);
$pdf->Text(70, 68, utf8_decode("del inmueble ubicado en la dirección"));
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(64); $pdf->SetX(125);
$pdf->Cell(77, 5, utf8_decode($direccion), 'B', 3, 'C', false);

$pdf->SetFont('arial', "", 9);
$pdf->Text(10, 74, utf8_decode("y el teléfono "));
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(70); $pdf->SetX(31);
$pdf->Cell(35, 5, utf8_decode($telefono), 'B', 3, 'C', false);
$pdf->SetFont('arial', "", 9);
if($acueducto == 'SD') {
    $pdf->Text(70, 74, utf8_decode(", y el Ayuntamiento suscriben el presente acuerdo de pago por los servicios prestados de esta."));
}
else{
    $pdf->Text(70, 74, utf8_decode(", y el Ayuntamiento suscriben el presente acuerdo de pago por los servicios prestados de esta."));
}

$pdf->SetFont('arial', "B", 9);
$pdf->Text(10, 85, utf8_decode("Primero: "));
$pdf->SetFont('arial', "", 9);
if($acueducto == 'SD') {
    $pdf->Text(25, 85, utf8_decode("El Deudor declara que adeuda al Ayuntamiento la suma de"));
}
else{
    $pdf->Text(25, 85, utf8_decode("El Deudor declara que adeuda al Ayuntamiento la suma de"));
}
$pdf->SetTextColor(255,0,0);
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(81); $pdf->SetX(105);
$pdf->Cell(40, 5, utf8_decode('RD$ '.$deuda), 'B', 3, 'C', false);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('arial', "", 9);
$pdf->Text(147, 85, utf8_decode("por los siguientes conceptos:"));

$pdf->SetFont('arial', "B", 9);
$pdf->Text(20, 95, utf8_decode("CONCEPTOS"));
$pdf->Text(70, 95, utf8_decode("DEUDA"));
$pdf->Text(120, 95, utf8_decode("DESCUENTO"));
$pdf->Text(170, 95, utf8_decode("A PAGAR"));

$b=new facturas();
$datos=$b->estcon3($inmueble);
$posy=0;
while(oci_fetch($datos)) {
    $pdf->SetFont('arial', "", 9);
    $concepto = oci_result($datos, "CONCEPTO");
    $pdf->SetY(98 + $posy); $pdf->SetX(15);
    $pdf->Cell(30, 5, utf8_decode($concepto), 0, 3, 'C', false);
    $valor = oci_result($datos, "VALOR");
    $valorf = number_format($valor,2,',','.');
    $pdf->SetY(98 + $posy); $pdf->SetX(59);
    $pdf->Cell(10, 5, utf8_decode('RD$ '), 0, 3, 'C', false);
    $pdf->SetY(98 + $posy); $pdf->SetX(68);
    $pdf->Cell(22, 5, $valorf, 0, 3, 'R', false);
    if($concepto == 'Mora'){
        $pdf->SetTextColor(255,0,0);
        $descuentof = number_format($descuento,2,',','.');
        $pdf->SetY(98 + $posy); $pdf->SetX(113);
        $pdf->Cell(10, 5, utf8_decode('RD$ '), 0, 3, 'C', false);
        $pdf->SetY(98 + $posy); $pdf->SetX(122);
        $pdf->Cell(22, 5, $descuentof, 0, 3, 'R', false);
        $pagara = $valor - $descuento;
        $pagarf = number_format($pagara,2,',','.');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetY(98 + $posy); $pdf->SetX(161);
        $pdf->Cell(10, 5, utf8_decode('RD$ '), 0, 3, 'C', false);
        $pdf->SetY(98 + $posy); $pdf->SetX(170);
        $pdf->Cell(22, 5, $pagarf, 0, 3, 'R', false);
    }
    if($concepto <> 'Mora'){
        $pdf->SetY(98 + $posy); $pdf->SetX(161);
        $pdf->Cell(10, 5, utf8_decode('RD$ '), 0, 3, 'C', false);
        $pdf->SetY(98 + $posy); $pdf->SetX(170);
        $pdf->Cell(22, 5, $valorf, 0, 3, 'R', false);
    }
    $posy += 6;
    $deudatotal += $valor;

}
$deudatotalf = number_format($deudatotal,2,',','.');
$totalpagar =  $pagar;
$totalpagarf = number_format($totalpagar,2,',','.');
$posy += 2;
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(15);
$pdf->Cell(30, 5, utf8_decode('TOTAL'), 0, 3, 'C', false);
$pdf->SetTextColor(255,0,0);
$pdf->SetY(98 + $posy); $pdf->SetX(59);
$pdf->Cell(10, 5, utf8_decode('RD$ '), 0, 3, 'C', false);
$pdf->SetY(98 + $posy); $pdf->SetX(68);
$pdf->Cell(22, 5, $deudatotalf, 0, 3, 'R', false);
$pdf->SetY(98 + $posy); $pdf->SetX(161);
$pdf->Cell(10, 5, utf8_decode('RD$ '), 0, 3, 'C', false);
$pdf->SetY(98 + $posy); $pdf->SetX(170);
$pdf->Cell(22, 5, $totalpagarf, 0, 3, 'R', false);
$pdf->SetTextColor(0,0,0);

$posy += 8;
$pdf->SetY(98 + $posy); $pdf->SetX(10);
$pdf->Cell(10, 5, utf8_decode('Segundo: '), 0, 3, 'L', false);
$pdf->SetFont('arial', "", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(27);
if($acueducto == 'SD') {
    $pdf->Cell(10, 5, utf8_decode('El Ayuntamiento acuerda reducir la deuda a RD$ ' . $totalpagarf), 0, 3, 'L', false);
}
else{
    $pdf->Cell(10, 5, utf8_decode('El Ayuntamiento acuerda reducir la deuda a RD$ ' . $totalpagarf), 0, 3, 'L', false);
}
$posy += 6;

$cuotaInif = number_format($cuotaIni,2,',','.');
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(10);
$pdf->Cell(10, 5, utf8_decode('Tercero: '), 0, 3, 'L', false);
$pdf->SetFont('arial', "", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(25);
$pdf->Cell(10, 5, utf8_decode('El  Deudor se  compromete a  amortizar la cantidad  mencionada con  pago inmediato  de '), 0, 3, 'L', false);
$pdf->SetTextColor(255,0,0);
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(160);
$pdf->Cell(15, 5, utf8_decode('RD$ '), 'B', 3, 'C', false);
$pdf->SetY(98 + $posy); $pdf->SetX(170);
$pdf->Cell(20, 5, utf8_decode($cuotaInif), 'B', 3, 'C', false);
$pdf->SetFont('arial', "", 9);
$pdf->SetTextColor(0,0,0);
$pdf->SetY(98 + $posy); $pdf->SetX(196);
$pdf->Cell(10, 5, utf8_decode('y/o '), 0, 3, 'L', false);
$posy += 6;
if ($reconexion == '') $reconexion = 0;
$reconexionf = number_format($reconexion,2,',','.');
$cuotaInif = number_format($cuotaIni,2,',','.');
$saldoPendf = number_format($saldoPend,2,',','.');
$cuotaMesf = number_format($cuotaMes,2,',','.');

if ($cuotas == 1) $descuota = 'cuota';
else $descuota = 'cuotas';
$pdf->SetY(98 + $posy); $pdf->SetX(10);
$pdf->Cell(10, 5, utf8_decode('cargo de reconexion de '), 0, 3, 'L', false);
$pdf->SetFont('arial', "B", 9);
$pdf->SetTextColor(255,0,0);
$pdf->SetY(98 + $posy); $pdf->SetX(46);
$pdf->Cell(10, 5, utf8_decode('RD$ '), 'B', 3, 'C', false);
$pdf->SetY(98 + $posy); $pdf->SetX(56);
$pdf->Cell(15, 5, utf8_decode($reconexionf), 'B', 3, 'C', false);
$pdf->SetFont('arial', "", 9);
$pdf->SetTextColor(0,0,0);
$pdf->SetY(98 + $posy); $pdf->SetX(73);
$pdf->Cell(10, 5, utf8_decode('y lo restante mediante '.$cuotas.' '.$descuota.' de'), 0, 3, 'L', false);
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(124);
$pdf->Cell(10, 5, utf8_decode('RD$ '), 'B', 3, 'C', false);
$pdf->SetY(98 + $posy); $pdf->SetX(134);
$pdf->Cell(15, 5, utf8_decode($cuotaMesf), 'B', 3, 'C', false);
$pdf->SetFont('arial', "", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(152);
$pdf->Cell(50, 5, utf8_decode('más las facturas que se produzcan'), 0, 3, 'L', false);
$posy += 6;
$pdf->SetY(98 + $posy); $pdf->SetX(10);
$pdf->Cell(50, 5, utf8_decode('en razón de los servicios prestados.'), 0, 3, 'L', false);
$posy += 6;
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(10);
$pdf->Cell(10, 5, utf8_decode('Cuarto: '), 0, 3, 'L', false);
$pdf->SetFont('arial', "", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(23);
if ($acueducto == 'SD') {
    $pdf->Cell(10, 5, utf8_decode('La violación  al presente convenio  faculta al Ayuntamiento a  suspender el servicio, requiriendo  para su reinstalación el pago total'), 0, 3, 'L', false);
}
else{
    $pdf->Cell(10, 5, utf8_decode('La  violación  al presente convenio  faculta al Ayuntamiento a  suspender el servicio, requiriendo  para su reinstalación el pago total'), 0, 3, 'L', false);
}
$posy += 6;
$pdf->SetY(98 + $posy); $pdf->SetX(10);
$pdf->Cell(10, 5, utf8_decode('de lo  adeudado sin el beneficio del  descuento de la mora más el cargo de la de la reconexión sin perjuicio de las acciones legales que'), 0, 3, 'L', false);
$posy += 6;
$pdf->SetY(98 + $posy); $pdf->SetX(10);
$pdf->Cell(10, 5, utf8_decode('sean pertinentes.'), 0, 3, 'L', false);
$posy += 6;

$totalf = number_format($total,2,',','.');
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(10);
$pdf->Cell(10, 5, utf8_decode('TOTAL  A PAGAR, PAGO INICIAL MÁS RECONEXIÓN:'), 0, 3, 'L', false);
$pdf->SetFont('arial', "", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(100);
$pdf->Cell(10, 5, utf8_decode('RD$ '), 0, 3, 'L', false);
$pdf->SetY(98 + $posy); $pdf->SetX(110);
$pdf->Cell(15, 5, utf8_decode($totalf), 0, 3, 'R', false);
$posy += 10;
$pdf->SetY(98 + $posy); $pdf->SetX(10);
$pdf->Cell(10, 5, utf8_decode('Hecho y  firmado el presente  convenio en un  original y  una copia de un mismo  tenor y efecto, uno para cada una de las partes, en la'), 0, 3, 'L', false);
$posy += 6;
$dia = strftime('%A');
$restdate = date('d, Y');

if($dia == 'Monday')$dia = 'Lunes'; if($dia == 'Tuesday')$dia = 'Martes'; if($dia == 'Wednesday')$dia = 'Miércoles'; if($dia == 'Thursday')$dia = 'Jueves';
if($dia == 'Friday')$dia = 'Viernes'; if($dia == 'Saturday')$dia = 'Sábado'; if($dia == 'Sunday')$dia = 'Domingo';

$pdf->SetY(98 + $posy); $pdf->SetX(10);
$pdf->Cell(10, 5, utf8_decode('ciudad de '.$ciudad.', R.D, el dia:'), 0, 3, 'L', false);
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(72);
$pdf->Cell(60, 5, utf8_decode($dia.', '.$mes.' '.$restdate), 'B', 3, 'C', false);

$posy += 30;
$pdf->SetY(98 + $posy); $pdf->SetX(22);
$pdf->Cell(60, 5, utf8_decode('Firma del Cliente y/o Representante'), 'T', 3, 'C', false);
$pdf->SetY(98 + $posy); $pdf->SetX(97);
if ($acueducto == 'SD') {
    $pdf->Cell(60, 5, utf8_decode('Por El Ayuntamiento'), 'T', 3, 'C', false);
}
else{
    $pdf->Cell(60, 5, utf8_decode('Por El Ayuntamiento'), 'T', 3, 'C', false);
}
$pdf->SetY(98 + $posy); $pdf->SetX(172);
$pdf->Cell(15, 5, utf8_decode('Oficina'), 'T', 3, 'C', false);

$posy += 4;
$pdf->SetFont('arial', "", 8);
$pdf->SetY(98 + $posy); $pdf->SetX(22);
$pdf->Cell(60, 5, utf8_decode($alias), 0, 3, 'C', false);
$posy += 4;
$pdf->SetY(98 + $posy); $pdf->SetX(22);
$pdf->Cell(60, 5, utf8_decode($documento), 0, 3, 'C', false);
$posy -= 4;
$pdf->SetFont('arial', "", 8);
$pdf->SetY(98 + $posy); $pdf->SetX(97);
$pdf->Cell(60, 5, utf8_decode($asistente), 0, 3, 'C', false);
$posy += 4;
$pdf->SetY(98 + $posy); $pdf->SetX(97);
$pdf->Cell(60, 5, utf8_decode(), 0, 3, 'C', false);

$posy -= 14;
$pdf->SetFont('arial', "", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(172);
$pdf->Cell(15, 5, utf8_decode($oficina), 0, 3, 'C', false);
$posy += 23;
$hoy = date('d/m/Y');
$pdf->SetY(98 + $posy); $pdf->SetX(20);
$pdf->Cell(15, 5, utf8_decode('Fecha de entrega del servicio: '), 0, 3, 'L', false);
$pdf->SetFont('arial', "B", 9);
$pdf->SetY(98 + $posy); $pdf->SetX(65);
$pdf->Cell(15, 5, utf8_decode($hoy), 0, 3, 'L', false);
$pdf->SetY(98 + $posy); $pdf->SetX(150);
$pdf->Cell(50, 5, utf8_decode('Autorizado Por'), 'T', 3, 'C', false);
$posy += 8;
$pdf->SetFont('arial', "BI", 8);
$pdf->SetY(98 + $posy); $pdf->SetX(10);
if ($acueducto == 'SD') {
    $pdf->Cell(15, 5, utf8_decode('Original Ayuntamiento acompañado del estado de cuenta. Duplicado al usuario. '), 0, 3, 'L', false);
}
else{
    $pdf->Cell(15, 5, utf8_decode('Original Ayuntamiento acompañado del estado de cuenta. Duplicado al usuario. '), 0, 3, 'L', false);
}

$posy += 5;
$pdf->SetFont('arial', "I", 7);
$pdf->SetY(98 + $posy); $pdf->SetX(20);
$pdf->Cell(15, 5, utf8_decode('Código: FO-SEC-12 '), 0, 3, 'L', false);
$pdf->SetY(98 + $posy); $pdf->SetX(50);
$pdf->Cell(15, 5, utf8_decode('Edición No.:02'), 0, 3, 'L', false);

///DESDE ACA GENERA LA SOLICITUD

$d = new PQRs();
$resultado = $d->getDatByPqrAcuerdo ($inmueble);
while (oci_fetch($resultado)) {
    $cod_pqr = oci_result($resultado, 'CODIGO');
}oci_free_statement($resultado);

$pdf->AddPage();
$c = new PQRs();
$resultado = $c->generaDocPqr($cod_pqr);
while (oci_fetch($resultado)) {
    $inmueble      = oci_result($resultado, 'COD_INMUEBLE');
    $cliente       = oci_result($resultado, "NOM_CLIENTE");
    $dircliente    = oci_result($resultado, "DIR_CLIENTE");
    $telcliente    = oci_result($resultado, "TEL_CLIENTE");
    $descripcion   = oci_result($resultado, "DESCRIPCION");
    $gerencia      = oci_result($resultado, "GERENCIA");
    $medio         = oci_result($resultado, "MEDIO_REC_PQR");
    $desmedio      = oci_result($resultado, "DESC_MEDIO_REC");
    $tipo          = oci_result($resultado, "TIPO_PQR");
    $destipo       = oci_result($resultado, "DESC_TIPO_RECLAMO");
    $motivo        = oci_result($resultado, "MOTIVO_PQR");
    $desmotivo     = oci_result($resultado, "DESC_MOTIVO_REC");
    $fecmax        = oci_result($resultado, "FECMAX");
    $entidad       = oci_result($resultado, "COD_ENTIDAD");
    $descentidad   = oci_result($resultado, "DESC_ENTIDAD");
    $punto         = oci_result($resultado, "COD_PUNTO");
    $descpunto     = oci_result($resultado, "DESCPUNTO");
    $usuariorec    = oci_result($resultado, "USER_RECIBIO_PQR");
    $nomusuariorec = oci_result($resultado, "USUARIOREC");
    $fecreg        = oci_result($resultado, "FECREG");
    $proceso       = oci_result($resultado, "ID_PROCESO");
    $catastro      = oci_result($resultado, "CATASTRO");
    $email         = oci_result($resultado, "EMAIL_CLIENTE");
    $acueducto     = oci_result($resultado, "ACUEDUCTO");

    $dt = new DateTime();
    //$dt->modify("-6 hour");
    $fecmaxhor = $dt->format('d/m/Y h:i:s');

    if ($acueducto == 'SD') {
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Text(58, 15,utf8_decode( "AYUNTAMIENTO DE SANTO DOMINGO"));
        $pdf->Text(92, 22, "GERENCIA COMERCIAL");
        $pdf->Text(58, 29, utf8_decode("CONSTANCIA DE RECEPCIÓN DE RECLAMOS Y SOLICITUDES"));
        $pdf->Image("../../images/LogoAseo.jpeg", 14, 9, 20);
    }

    if ($acueducto == 'BC') {
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Text(62, 15, utf8_decode("AYUNTAMIENTO DE BOCACHICA"));
        $pdf->Text(92, 22, "GERENCIA COMERCIAL");
        $pdf->Text(58, 29, utf8_decode("CONSTANCIA DE RECEPCIÓN DE RECLAMOS Y SOLICITUDES"));
        $pdf->Image("../../images/AyuntamientoBocachica.jpeg", 10, 15, 40);
    }

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Text(10, 42, 'Reclamo No: ' . $cod_pqr);
    $pdf->Ln(24);
    $pdf->Cell(190, 5, $fecmaxhor, 0, 0, 'R');
    $pdf->Ln(5);
    $pdf->Cell(190, 5,utf8_decode( "Pág:      " ). $pdf->PageNo() . ' / {nb}', 0, 0, 'R');
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFillColor(0, 128, 192);
    $pdf->Ln(6);
    $pdf->Cell(190, 5, "DATOS DE LA PQR", 1, 0, 'C', true);
    $pdf->Rect(10, 50, 190, 40);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(7);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, utf8_decode("Fecha Recepción: "), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $fecreg, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "Tipo: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $tipo . '-' . $destipo, 0, 0, 'L');

    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, "Cod. Sistema: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $inmueble, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "Motivo: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $motivo . '-' . $desmotivo, 0, 0, 'L');

    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, "Nombre: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $cliente, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "Medio: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $medio . '-' . $desmedio, 0, 0, 'L');

    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, "Catastro: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $catastro, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "Serial: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $serial, 0, 0, 'L');

    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, "Proceso: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $proceso, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "Ultima Lectura: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $lectura, 0, 0, 'L');

    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, utf8_decode("Descripción: "), 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->SetFont('Arial', '', 8);
    $descrip1 = substr($descripcion, 0, 116);
    $descrip2 = substr($descripcion, 116, 116);
    $descrip3 = substr($descripcion, 232, strlen($descripcion));
    $pdf->Cell(30, 5, $descrip1, 0, 0, 'L');
    $pdf->Ln(3);
    $pdf->Cell(30, 5, $descrip2, 0, 0, 'L');
    $pdf->Ln(3);
    $pdf->Cell(30, 5, $descrip3, 0, 0, 'L');
    $pdf->Ln(2);

    $pdf->Ln(9);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(190, 5, "DATOS DE QUIEN INTERPONE LA PQR", 1, 0, 'C', true);
    $pdf->Rect(10, 93, 190, 20);
    $pdf->Ln(7);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "Nombre: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(90, 5, $cliente, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(50, 5, $telcliente, 0, 0, 'L');

    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, utf8_decode("Dirección: "), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(165, 5, $dircliente, 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "E-mail: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(165, 5, $email, 0, 0, 'L');

    $pdf->Ln(13);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(190, 5, "DOCUMENTOS ANEXOS", 1, 0, 'C', true);
    $pdf->Rect(10, 121, 190, 15);

    $pdf->Ln(23);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(190, 5, utf8_decode("DATOS DE RECEPCIÓN"), 1, 0, 'C', true);
    $pdf->Rect(10, 144, 190, 14);
    $pdf->Ln(7);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(40, 5, "Oficina Comercial: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(150, 5, $descentidad . ' - ' . $descpunto, 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(40, 2, "Nombre Auxiliar: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(150, 2, $nomusuariorec, 0, 0, 'L');

    $pdf->SetFillColor(220, 220, 220);
    $pdf->Rect(10, 158, 190, 23, true);
    $pdf->Rect(10, 158, 190, 23);
    $pdf->Rect(10, 181, 190, 30);

    $pdf->Ln(8);
    $pdf->SetFont('Arial', 'B', 8);
    if ($acueducto == 'SD') {
        $pdf->Cell(190, 5, "EL AYUNTAMIENTO SE COMPROMETE CON USTED", 0, 0, 'C');
    }
    if ($acueducto == 'BC') {
        $pdf->Cell(190, 5, "EL AYUNTAMIENTO SE COMPROMETE CON USTED", 0, 0, 'C');
    }
    $pdf->Ln(4);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(190, 5, "Estimado cliente, una vez se recepcione en nuestras oficinas su solicitud, queja o reclamo, nos comprometemos a darle respuesta en un plazo no", 0, 0, 'L');
    $pdf->Ln(4);
    if ($motivo == 23 || $motivo == 25 || $motivo == 11 || $motivo == 83 || $motivo == 27) {

        $pdf->Cell(190, 5, utf8_decode("mayor  a tres (3) días laborables a  partir de la  fecha de recepción.  Si transcurre  dicho plazo y  usted no ha recibido  respuesta, favor llamar "), 0, 0, 'L');
    } else if ($motivo == 22) {

        $pdf->Cell(190, 5, utf8_decode("mayor  a dos (2) días laborables a  partir de la  fecha de recepción.  Si transcurre  dicho plazo y  usted no ha recibido  respuesta, favor llamar "), 0, 0, 'L');
    } else {

        $pdf->Cell(190, 5, utf8_decode("mayor  a quince (15) días laborables a  partir de la  fecha de recepción.  Si transcurre  dicho plazo y  usted no ha recibido  respuesta, favor llamar "), 0, 0, 'L');
    }
    $pdf->Ln(4);
    $pdf->Cell(190, 5, utf8_decode("al  telefono (809) 598-1722 Opción 2."), 0, 0, 'L');
    $pdf->Ln(6);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(190, 5, utf8_decode("SU SATISFACCIÓN ES NUESTRO COMPROMISO"), 0, 1, 'C');

    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(40, 5, utf8_decode("Fecha Límite de Respuesta: "), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(150, 5, $fecmax, 0, 0, 'L');

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Ln(8);
    $pdf->Cell(95, 5, "_________________________________________", 0, 0, 'C');
    $pdf->Cell(95, 5, "_________________________________________", 0, 0, 'C');
    $pdf->Ln(3);
    $pdf->Cell(95, 5, "Firma y Sello Auxiliar", 0, 0, 'C');
    $pdf->Cell(95, 5, "Firma Cliente y/o Representante", 0, 0, 'C');
    $pdf->Ln(4);
    $pdf->Cell(95, 5, "", 0, 0, 'C');
    $pdf->Cell(95, 5, $dt->format('d/m/Y'), 0, 0, 'C');

    $pdf->SetFillColor(0, 128, 192);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Ln(14);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(190, 5, "SEGUIMIENTO", 1, 0, 'C', true);
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Rect(10, 218, 190, 50, true);
    $pdf->Rect(10, 218, 190, 50);

    $pdf->Ln(8);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(190, 5,utf8_decode( "Fecha de Resolución:   _________________________________________________________ "), 0, 0, 'L');

    $pdf->Ln(13);
    $pdf->Cell(95, 5, "_________________________________________", 0, 0, 'C');
    $pdf->Cell(95, 5, "_________________________________________", 0, 0, 'C');
    $pdf->Ln(3);
    $pdf->Cell(95, 5, "Firma de Inspector", 0, 0, 'C');
    $pdf->Cell(95, 5, "Firma Supervisor / Encargado", 0, 0, 'C');

    $pdf->Ln(7);
    $pdf->Cell(190, 5, "Observaciones:   _______________________________________________________________________________________________________", 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->Cell(190, 5, "______________________________________________________________________________________________________________________", 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->Cell(190, 5, "______________________________________________________________________________________________________________________", 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->Cell(190, 5, "______________________________________________________________________________________________________________________", 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->Cell(190, 5, "______________________________________________________________________________________________________________________", 0, 0, 'L');

}
oci_free_statement($resultado);

$nomarch="../../temp/Acuerdo_Pago_Inmueble_".$inmueble.".pdf";
$pdf->Output($nomarch,'F');

echo $nomarch;
