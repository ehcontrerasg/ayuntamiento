<?php
/**
 * Created by PhpStorm.
 * User: JGutierrez
 * Date: 24/08/2019
 * Time: 11:51 AM
 */
include "../../clases/class.fpdf.php";

$idcontrato = $_POST["idc"];
$inmueble = $_POST["inm"];
$acueducto = $_POST["acu"];
$estado = $_POST["est"];
$proceso = $_POST["pro"];
$catastro = $_POST["cat"];
$sector = $_POST["sec"];
$ruta = $_POST["rut"];
$direccion = $_POST["dir"];
$urbanizacion = $_POST["urb"];
$uso = $_POST["uso"];
$unidades = $_POST["uni"];
$agua = $_POST["agu"];
$alcantarillado = $_POST["alc"];
$tipo = $_POST["tpo"];
$tarifa = $_POST["tar"];
$derecho = $_POST["der"];
$fianza = $_POST["fia"];
$total = $_POST["tot"];
$documento = $_POST["doc"];
$cliente = $_POST["cli"];
$alias = $_POST["ali"];
$telefono = $_POST["tel"];
$email = $_POST["ema"];
$oficina = $_POST["ofi"];
$cuotas = $_POST["cuo"];

$mes = date('m');
if ($mes == '01') $mes = 'Enero'; if ($mes == '02') $mes = 'Febrero'; if ($mes == '03') $mes = 'Marzo'; if ($mes == '04') $mes = 'Abril';
if ($mes == '05') $mes = 'Mayo'; if ($mes == '06') $mes = 'Junio'; if ($mes == '07') $mes = 'Julio'; if ($mes == '08') $mes = 'Agosto';
if ($mes == '09') $mes = 'Septiembre'; if ($mes == '10') $mes = 'Octubre'; if ($mes == '11') $mes = 'Noviembre'; if ($mes == '12') $mes = 'Diciembre';

$pdf = new FPDF('P',  'mm', 'Legal');
$pdf->SetTextColor(0,0,0);
$pdf->AliasNbPages();
$pdf->AddPage('P');
$pdf->SetFont('arial', "B", 13);

if ($acueducto == 'SD') {
    $empresa = "LA CAASD";
    $texto = "La Corporación del Acueducto Y Alcantarillado de Santo Domingo (CAASD)";
    $reglamento = "No.498 y su reglamento No.3402, de fechas 11 y 13 de abril de 1973";
    $ciudad = "Santo Domingo";
    $cod_cont = "SD - ";
    $pdf->Text(22, 15, utf8_decode("CORPORACIÓN DEL ACUEDUCTO Y ALCANTARILLADO DE SANTO DOMINGO"));
    $pdf->Image("../../images/LogoCaasd.jpg", 102, 19, 11, 15.5);
}
if ($acueducto == 'BC') {
    $empresa = "CORAABO";
    $texto = "La Corporación del Acueducto Y Alcantarillado de Bocachica (CORAABO)";
    $reglamento = "No.428-06 y sus reglamentos";
    $ciudad = "Boca Chica";
    $cod_cont = "BC - ";
    $pdf->Text(27, 15, utf8_decode("CORPORACIÓN DEL ACUEDUCTO Y ALCANTARILLADO DE BOCACHICA "));
    $pdf->Image("../../images/logo_coraabo.jpg", 82, 19, 52, 15.5);
}

$pdf->SetFont('arial', "B", 13);
$pdf->Text(80, 41, utf8_decode("CONTRATO DE SERVICIO"));
$pdf->SetFont('arial', "B", 8);
$pdf->Text(8, 46, utf8_decode("FO-SEC-33"));
$pdf->SetFont('arial', "B", 11);
$pdf->SetFillColor(255, 255, 255);
$pdf->Text(156, 35, utf8_decode("Contrato No."));
$pdf->SetY(31); $pdf->SetX(185);
$pdf->SetFont('arial', "B", 9);
$pdf->Cell(25, 5, utf8_decode($idcontrato), 1, 3, 'C', true);
$pdf->SetFont('arial', "B", 11);
$pdf->Text(156, 46, utf8_decode("Cod. Sistema"));
//$pdf->SetFillColor(255, 255, 255);
$pdf->SetY(42); $pdf->SetX(185);
$pdf->Cell(25, 5, utf8_decode($inmueble), 1, 3, 'C', true);
$pdf->Rect(8,48,202,148);
$pdf->SetFont('arial', "B", 8);
$pdf->SetY(50);
$pdf->SetX(19);
$pdf->Cell(60, 5, utf8_decode('IDENTIFICACIÓN DEL PROCESO'), 1, 3, 'C', true);
$pdf->SetFont('arial', "B", 6);
$pdf->SetY(55);
$pdf->SetX(19);
$pdf->Cell(10, 3, utf8_decode('SECT.'), 1, 3, 'C', true);
$pdf->SetY(55);
$pdf->SetX(29);
$pdf->Cell(10, 3, utf8_decode('RUTA'), 1, 3, 'C', true);
$pdf->SetY(55);
$pdf->SetX(39);
$pdf->Cell(25, 3, utf8_decode('SECUENCIA'), 1, 3, 'C', true);
$pdf->SetY(55);
$pdf->SetX(64);
$pdf->Cell(10, 3, utf8_decode('INQ.'), 1, 3, 'C', true);
$pdf->SetY(55);
$pdf->SetX(74);
$pdf->Cell(5, 3, utf8_decode('DV'), 1, 3, 'C', true);
$pdf->SetFont('arial', "", 10);
$pdf->SetY(58);
$pdf->SetX(19);
$pdf->Cell(10, 8, utf8_decode(substr($proceso,0,2)), 1, 3, 'C', true);
$pdf->SetY(58);
$pdf->SetX(29);
$pdf->Cell(10, 8, utf8_decode(substr($proceso,2,2)), 1, 3, 'C', true);
$pdf->SetY(58);
$pdf->SetX(39);
$pdf->Cell(25, 8, utf8_decode(substr($proceso,4,5)), 1, 3, 'C', true);
$pdf->SetY(58);
$pdf->SetX(64);
$pdf->Cell(10, 8, utf8_decode(substr($proceso,9,2)), 1, 3, 'C', true);
$pdf->SetY(58);
$pdf->SetX(74);
$pdf->Cell(5, 8, utf8_decode(substr($proceso,11,1)), 1, 3, 'C', true);

$pdf->SetFont('arial', "B", 8);
$pdf->SetY(50);
$pdf->SetX(79);
$pdf->Cell(60, 5, utf8_decode('IDENTIFICACIÓN DEL INMUEBLE'), 1, 3, 'C', true);
$pdf->SetFont('arial', "B", 6);
$pdf->SetY(55);
$pdf->SetX(79);
$pdf->Cell(14, 3, utf8_decode('MANZ.'), 1, 3, 'C', true);
$pdf->SetY(55);
$pdf->SetX(93);
$pdf->Cell(19, 3, utf8_decode('LOCAL.'), 1, 3, 'C', true);
$pdf->SetY(55);
$pdf->SetX(111.5);
$pdf->Cell(13.8, 3, utf8_decode('CONJ.'), 1, 3, 'C', true);
$pdf->SetY(55);
$pdf->SetX(125.5);
$pdf->Cell(13.5, 3, utf8_decode('SUB. LOC.'), 1, 3, 'C', true);
$pdf->SetFont('arial', "", 10);
$pdf->SetY(58);
$pdf->SetX(79);
$pdf->Cell(14, 8, utf8_decode(substr($catastro,0,4)), 1, 3, 'C', true);
$pdf->SetY(58);
$pdf->SetX(93);
$pdf->Cell(19, 8, utf8_decode(substr($catastro,4,6)), 1, 3, 'C', true);
$pdf->SetY(58);
$pdf->SetX(111.5);
$pdf->Cell(13.8, 8, utf8_decode(substr($catastro,10,4)), 1, 3, 'C', true);
$pdf->SetY(58);
$pdf->SetX(125.5);
$pdf->Cell(13.5, 8, utf8_decode(substr($catastro,14,10)), 1, 3, 'C', true);

$pdf->SetFont('arial', "B", 8);
$pdf->SetY(50);
$pdf->SetX(139);
$pdf->Cell(60, 5, utf8_decode('FECHA Y NÚMERO DE CONTRATO'), 1, 3, 'C', true);
$pdf->SetFont('arial', "B", 6);
$pdf->SetY(55);
$pdf->SetX(139);
$pdf->Cell(10, 3, utf8_decode('DIA'), 1, 3, 'C', true);
$pdf->SetY(55);
$pdf->SetX(149);
$pdf->Cell(10, 3, utf8_decode('MES'), 1, 3, 'C', true);
$pdf->SetY(55);
$pdf->SetX(159);
$pdf->Cell(20, 3, utf8_decode('AÑO'), 1, 3, 'C', true);
$pdf->SetY(55);
$pdf->SetX(179);
$pdf->Cell(20, 3, utf8_decode('OFICINA'), 1, 3, 'C', true);
$pdf->SetFont('arial', "", 10);
$pdf->SetY(58);
$pdf->SetX(139);
$pdf->Cell(10, 8, utf8_decode(date('d')), 1, 3, 'C', true);
$pdf->SetY(58);
$pdf->SetX(149);
$pdf->Cell(10, 8, utf8_decode(date('m')), 1, 3, 'C', true);
$pdf->SetY(58);
$pdf->SetX(159);
$pdf->Cell(20, 8, utf8_decode(date('Y')), 1, 3, 'C', true);
$pdf->SetY(58);
$pdf->SetX(179);
$pdf->Cell(20, 8, utf8_decode($oficina), 1, 3, 'C', true);

$pdf->Rect(9,68.5,200,54.5);
$pdf->SetFont('arial', "B", 8);
$pdf->SetY(70);
$pdf->SetX(10);
$pdf->Cell(100, 5, utf8_decode('NOMBRE DEL USUARIO'), 1, 3, 'C', true);
$pdf->SetY(70);
$pdf->SetX(110);
$pdf->Cell(49, 5, utf8_decode('TELÉFONO RESIDENCIAL'), 1, 3, 'C', true);
$pdf->SetY(70);
$pdf->SetX(159);
$pdf->Cell(49, 5, utf8_decode('CÉDULA / PASAPORTE'), 1, 3, 'C', true);
$pdf->SetFont('arial', "", 10);
$pdf->SetY(75);
$pdf->SetX(10);
$pdf->Cell(100, 10, utf8_decode($alias), 1, 3, 'C', true);
$pdf->SetY(75);
$pdf->SetX(110);
$pdf->Cell(49, 10, utf8_decode($telefono), 1, 3, 'C', true);
$pdf->SetY(75);
$pdf->SetX(159);
$pdf->Cell(49, 10, utf8_decode($documento), 1, 3, 'C', true);

$pdf->SetFont('arial', "B", 8);
$pdf->SetY(86);
$pdf->SetX(10);
$pdf->Cell(100, 5, utf8_decode('NOMBRE DEL PROPIETARIO'), 1, 3, 'C', true);
$pdf->SetY(86);
$pdf->SetX(110);
$pdf->Cell(49, 5, utf8_decode('TELÉFONO CELULAR'), 1, 3, 'C', true);
$pdf->SetY(86);
$pdf->SetX(159);
$pdf->Cell(49, 5, utf8_decode('RNC'), 1, 3, 'C', true);
$pdf->SetFont('arial', "", 10);
$pdf->SetY(91);
$pdf->SetX(10);
$pdf->Cell(100, 10, utf8_decode(), 1, 3, 'C', true);
$pdf->SetY(91);
$pdf->SetX(110);
$pdf->Cell(49, 10, utf8_decode(), 1, 3, 'C', true);
$pdf->SetY(91);
$pdf->SetX(159);
$pdf->Cell(49, 10, utf8_decode(), 1, 3, 'C', true);
$pdf->SetFont('arial', "B", 8);
$pdf->SetY(102);
$pdf->SetX(10);
$pdf->Cell(198, 5, utf8_decode('IDENTIFICACIÓN DEL INMUEBLE'), 1, 3, 'C', true);
$pdf->SetY(107);
$pdf->SetX(10);
$pdf->Cell(85, 5, utf8_decode('DIRECCIÓN'), 1, 3, 'C', true);
$pdf->SetY(107);
$pdf->SetX(95);
$pdf->Cell(65, 5, utf8_decode('URBANIZACIÓN'), 1, 3, 'C', true);
$pdf->SetY(107);
$pdf->SetX(160);
$pdf->Cell(24, 5, utf8_decode('USO'), 1, 3, 'C', true);
$pdf->SetY(107);
$pdf->SetX(184);
$pdf->Cell(24, 5, utf8_decode('UNIDADES'), 1, 3, 'C', true);
$pdf->SetFont('arial', "", 10);
$pdf->SetY(112);
$pdf->SetX(10);
$pdf->Cell(85, 10, utf8_decode($direccion), 1, 3, 'C', true);
$pdf->SetY(112);
$pdf->SetX(95);
$pdf->Cell(65, 10, utf8_decode($urbanizacion), 1, 3, 'C', true);
$pdf->SetY(112);
$pdf->SetX(160);
$pdf->Cell(24, 10, utf8_decode($uso), 1, 3, 'C', true);
$pdf->SetY(112);
$pdf->SetX(184);
$pdf->Cell(24, 10, utf8_decode($unidades), 1, 3, 'C', true);

$pdf->SetFont('arial', "B", 8);
$pdf->SetY(125);
$pdf->SetX(10);
$pdf->Cell(120, 5, utf8_decode('TARIFAS'), 1, 3, 'C', true);
$pdf->SetY(125);
$pdf->SetX(130);
$pdf->Cell(78, 5, utf8_decode('TIPO SUMINISTRO'), 1, 3, 'C', true);
$pdf->SetY(130);
$pdf->SetX(10);
$pdf->Cell(60, 5, utf8_decode('AGUA'), 1, 3, 'C', true);
$pdf->SetY(130);
$pdf->SetX(70);
$pdf->Cell(60, 5, utf8_decode('ALCANTARILLADO'), 1, 3, 'C', true);
$pdf->SetY(130);
$pdf->SetX(130);
$pdf->Cell(39, 5, utf8_decode('POZO'), 1, 3, 'C', true);
$pdf->SetY(130);
$pdf->SetX(169);
$pdf->Cell(39, 5, utf8_decode('RED'), 1, 3, 'C', true);

$pdf->SetFont('arial', "", 10);
$pdf->SetY(135);
$pdf->SetX(10);
$pdf->Cell(60, 10, utf8_decode('RD$ '.$agua.'.00'), 1, 3, 'C', true);
if ($alcantarillado == '') $alcantarillado = 0;
$pdf->SetY(135);
$pdf->SetX(70);
$pdf->Cell(60, 10, utf8_decode('RD$ '.$alcantarillado.'.00'), 1, 3, 'C', true);
$pdf->SetY(135);
$pdf->SetX(130);
if ($tipo == 'RED') $tipo1 = 'X';
else $tipo2 = 'X';
$pdf->Cell(39, 10, utf8_decode($tipo2), 1, 3, 'C', true);
$pdf->SetY(135);
$pdf->SetX(169);
$pdf->Cell(39, 10, utf8_decode($tipo1), 1, 3, 'C', true);

$pdf->Rect(9,147,200,47);
$pdf->SetFont('arial', "B", 8);
$pdf->SetY(149);
$pdf->SetX(10);
$pdf->Cell(198, 5, utf8_decode('PAGOS RECIBIDOS'), 0, 3, 'C', true);
$pdf->SetY(155);
$pdf->SetX(10);
$pdf->Cell(198, 5, utf8_decode('DESGLOSE DE COSTOS DE PROYECTOS'), 1, 3, 'C', true);
$pdf->SetFont('arial', "B", 6);
$pdf->SetY(160);
$pdf->SetX(10);
$pdf->Cell(28.3, 5, utf8_decode('D.I.'), 1, 3, 'C', true);
$pdf->SetY(160);
$pdf->SetX(38.3);
$pdf->Cell(28.3, 5, utf8_decode('FIANZA'), 1, 3, 'C', true);
$pdf->SetY(160);
$pdf->SetX(66.6);
$pdf->Cell(28.3, 5, utf8_decode('D. EXP. DE POZO'), 1, 3, 'C', true);
$pdf->SetY(160);
$pdf->SetX(94.9);
$pdf->Cell(28.3, 5, utf8_decode('COLOC. DE MEDIDORES'), 1, 3, 'C', true);
$pdf->SetY(160);
$pdf->SetX(123.2);
$pdf->Cell(28.3, 5, utf8_decode('SUP. CAASD'), 1, 3, 'C', true);
$pdf->SetY(160);
$pdf->SetX(151.5);
$pdf->Cell(28.3, 5, utf8_decode('T, TARDIA PLANOS'), 1, 3, 'C', true);
$pdf->SetY(160);
$pdf->SetX(179.8);
$pdf->Cell(28.2, 5, utf8_decode('TOTAL RECIBIDO'), 1, 3, 'C', true);
$pdf->SetFont('arial', "B", 8);
$pdf->SetY(165);
$pdf->SetX(10);
$pdf->Cell(28.3, 10, '', 1, 3, 'C', true);
$pdf->SetY(165);
$pdf->SetX(38.3);
$pdf->Cell(28.3, 10, '', 1, 3, 'C', true);
$pdf->SetY(165);
$pdf->SetX(66.6);
$pdf->Cell(28.3, 10, '', 1, 3, 'C', true);
$pdf->SetY(165);
$pdf->SetX(94.9);
$pdf->Cell(28.3, 10, '', 1, 3, 'C', true);
$pdf->SetY(165);
$pdf->SetX(123.2);
$pdf->Cell(28.3, 10, '', 1, 3, 'C', true);
$pdf->SetY(165);
$pdf->SetX(151.5);
$pdf->Cell(28.3, 10, '', 1, 3, 'C', true);
$pdf->SetY(165);
$pdf->SetX(179.8);
$pdf->Cell(28.2, 10, '', 1, 3, 'C', true);

$pdf->SetFont('arial', "B", 6);
$pdf->SetY(178);
$pdf->SetX(10);
$pdf->Cell(35, 5, utf8_decode('AGUA'), 1, 3, 'C', true);
$pdf->SetY(178);
$pdf->SetX(45);
$pdf->Cell(25, 5, utf8_decode('MORA'), 1, 3, 'C', true);
$pdf->SetY(178);
$pdf->SetX(70);
$pdf->Cell(25, 5, utf8_decode('ALC'), 1, 3, 'C', true);
$pdf->SetY(178);
$pdf->SetX(95);
$pdf->Cell(25, 5, utf8_decode('D. I.'), 1, 3, 'C', true);
$pdf->SetY(178);
$pdf->SetX(120);
$pdf->Cell(25, 5, utf8_decode('ACOM/CONEX'), 1, 3, 'C', true);
$pdf->SetY(178);
$pdf->SetX(145);
$pdf->Cell(30, 5, utf8_decode('FIANZA'), 1, 3, 'C', true);
$pdf->SetY(178);
$pdf->SetX(175);
$pdf->Cell(33, 5, utf8_decode('TOTAL'), 1, 3, 'C', true);

$pdf->SetFont('arial', "", 10);
$pdf->SetY(183);
$pdf->SetX(10);
$pdf->Cell(35, 10, utf8_decode('RD$ '.$agua.'.00'), 1, 3, 'C', true);
$pdf->SetY(183);
$pdf->SetX(45);
$pdf->Cell(25, 10, utf8_decode('RD$ '.$mora.'.00'), 1, 3, 'C', true);
$pdf->SetY(183);
$pdf->SetX(70);
$pdf->Cell(25, 10, utf8_decode('RD$ '.$alcantarilla.'.00'), 1, 3, 'C', true);
$pdf->SetY(183);
$pdf->SetX(95);
$pdf->Cell(25, 10, utf8_decode('RD$ '.$derecho.'.00'), 1, 3, 'C', true);
$pdf->SetY(183);
$pdf->SetX(120);
$pdf->Cell(25, 10, utf8_decode('RD$ '.$acom.'.00'), 1, 3, 'C', true);
$pdf->SetY(183);
$pdf->SetX(145);
$pdf->Cell(30, 10, utf8_decode('RD$ '.$fianza.'.00'), 1, 3, 'C', true);
$pdf->SetY(183);
$pdf->SetX(175);
$pdf->Cell(33, 10, utf8_decode('RD$ '.$total.'.00'), 1, 3, 'C', true);

//TEXTO DEL CONTRATO

$pdf->SetFont('arial', "B", 11);
$pdf->Text(55, 204, utf8_decode("CONTRATO PARA EL USO DE LOS SERVICIOS DE AGUA"));
$pdf->Text(67, 208, utf8_decode("POTABLE Y ALCANTARILLADO SANITARIO"));
$pdf->SetFont('arial', "B", 7);
$pdf->Text(8, 216, utf8_decode("ENTRE:"));
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(218); $pdf->SetX(8); //175
$pdf->MultiCell(202,4,utf8_decode("         De una parte, $texto, organismo autónomo del estado dominicano, creada y regida por la ley $reglamento, respectivamente, debidamente representada por su Director General, quien delega la firma de este contrato en el funcionario comercial correspondiente, y quien en lo adelante y para los fines del presente contrato se denominará $empresa y de la otra parte la persona física o moral solicitante, cuyos datos personales aparecen en los encasillados correspondientes de ese documento, quien en lo adelante y para los fines del presente contrato se denominará EL CLIENTE."),0,'J',1);
$pdf->SetFont('arial', "B", 8.5);
$pdf->Text(71, 242, utf8_decode("SE HA CONVENIDO Y PACTADO LO SIGUIENTE:"));

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(247); $pdf->SetX(7);
$pdf->MultiCell(34,4,utf8_decode("ARTÍCULO PRIMERO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(247); $pdf->SetX(35);
$pdf->MultiCell(174,4,utf8_decode(" $empresa se compromete a prestarle al CLIENTE el servicio de Agua  potable y/o Alcantarillado Sanitario, en el inmueble presente de este contrato, con"),0,'J',1);
$pdf->SetY(251); $pdf->SetX(7);
$pdf->MultiCell(202,4,utf8_decode("con una frecuencia que variará conforme lo determine la disponibilidad de la institución para ofrecerlo."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(257); $pdf->SetX(7);
$pdf->MultiCell(35,4,utf8_decode("ARTÍCULO SEGUNDO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(257); $pdf->SetX(37);
$pdf->MultiCell(173,4,utf8_decode("EL CLIENTE,  acepta  formalmente  recibir el  servicio  en las  condiciones  arriba  mencionadas, renunciando  a cualquier  reclamación de  los  cargos"),0,'J',1);
$pdf->SetY(261); $pdf->SetX(7);//221
$pdf->MultiCell(202,4,utf8_decode("mensuales facturados, por deficiencias de calidad, cantidad, presión o frecuencia del servicio."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(267); $pdf->SetX(7);//228
$pdf->MultiCell(34,4,utf8_decode("ARTÍCULO TERCERO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(267); $pdf->SetX(37);
$pdf->MultiCell(174,4,utf8_decode("A la firma del presente contrato, EL CLIENTE deberá depositar una fianza, la cual deberá ser menor al monto  de dos (2) mensualidades  del cargo fijo"),0,'J',1);
$pdf->SetY(271); $pdf->SetX(7);//232
$pdf->MultiCell(202,4,utf8_decode("mínimo (cupo básico), pudiendo la misma ser reajustada cuando se determine que el consumo promedio es superior al cupo básico. La fianza depositada garantizará las deudas del CLIENTE con $empresa originadas por los servicios que se suministren o por cualquier otro concepto. En caso de rescisión del presente contrato se hará una liquidación de fianza y si resultare un saldo favorable a $empresa, el CLIENTE se obliga a pagar su monto; en este caso inverso, $empresa reembolsará el saldo favorable al CLIENTE."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(285); $pdf->SetX(7);//251
$pdf->MultiCell(33,4,utf8_decode("ARTÍCULO CUARTO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(285); $pdf->SetX(34);
$pdf->MultiCell(175,4,utf8_decode("$empresa cobrará a  cada CLIENTE el o los  servicios que haya contratado en base a la estructura  tarifaria vigente. En caso de suspensión del servicio"),0,'J',1);
$pdf->SetY(289); $pdf->SetX(7);//255
$pdf->MultiCell(202,4,utf8_decode("por falta de pago y por cualquier otra causa no atribuible a $empresa, se facturará mensualmente el cargo mínimo (cupo básico) establecido en la estructura tarifaria vigente."),0,'J',1);
$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(295); $pdf->SetX(7);//266
$pdf->MultiCell(31,4,utf8_decode("ARTÍCULO QUINTO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(295); $pdf->SetX(34);
$pdf->MultiCell(175,4,utf8_decode("Queda entendido, que el Contrato por  los Servicios de Agua  potable y Alcantarillado Sanitario  queda formalizado entre las  partes cuando  el solicitante"),0,'J',1);
$pdf->SetY(299); $pdf->SetX(7);
$pdf->MultiCell(202,4,utf8_decode("haya firmado este contrato en su propio nombre o en el de su (s) representado (s)."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(305); $pdf->SetX(7);
$pdf->MultiCell(18,4,utf8_decode("PARRAFO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(305); $pdf->SetX(22);
$pdf->MultiCell(184,4,utf8_decode("Cuando se trate de condominio este Contrato se regirá por el Reglamento de Prestación de Servicio para condominios establecido por $empresa."),0,'J',1);

if ($cuotas > 1){
    $pdf->SetFont('arial', "BI", 8);
    $pdf->SetY(311); $pdf->SetX(7);
    $pdf->MultiCell(184,4,utf8_decode("CONTRATO CARGADO A ".$cuotas." CUOTAS."),0,'J',1);
}
if ($cuotas == 1){
    $pdf->SetFont('arial', "BI", 8);
    $pdf->SetY(311); $pdf->SetX(7);
    $pdf->MultiCell(184,4,utf8_decode("CONTRATO CARGADO A ".$cuotas." CUOTA."),0,'J',1);
}

$pdf->AddPage('P');

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(16); $pdf->SetX(7);
$pdf->MultiCell(30,4,utf8_decode("ARTÍCULO SEXTO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(16); $pdf->SetX(33);//288
$pdf->MultiCell(175,4,utf8_decode("En caso de rescisión del presente Contrato, únicamente será susceptible de reembolso la parte de la fianza no comprometida en el o los pagos pendientes"),0,'J',1);
$pdf->SetY(20); $pdf->SetX(7);//292
$pdf->MultiCell(202,4,utf8_decode("pendientes de todos los servicios suministrados, procediéndose a la liquidación, conforme a lo expresado en el Artículo 3 de este contrato."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(26); $pdf->SetX(7);//299
$pdf->MultiCell(33,4,utf8_decode("ARTÍCULO SEPTIMO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(26); $pdf->SetX(35);
$pdf->MultiCell(175,4,utf8_decode("A partir del momento en que entre en vigencia el Contrato de Servicio de agua potable y/o alcantarillado sanitario, el CLIENTE se obliga con $empresa"),0,'J',1);
$pdf->SetY(30); $pdf->SetX(7);//303
$pdf->MultiCell(202,4,utf8_decode("a:"),0,'J',1);

$pdf->SetY(36); $pdf->SetX(11);//310
$pdf->MultiCell(10,4,utf8_decode("a) "),0,'J',1);
$pdf->SetY(36); $pdf->SetX(17);
$pdf->MultiCell(192,4,utf8_decode("Pagar el (los) servicio (s) que haya recibido durante el mes, dentro de la fecha límite para el pago establecida en la factura, reservándose $empresa el derecho a cobrar los cargos financieros (mora) por el monto de la deuda y a suspender el servicio, si el pago no se realiza dentro del término establecido. "),0,'J',1);

$pdf->SetY(46); $pdf->SetX(11);//325
$pdf->MultiCell(10,4,utf8_decode("b) "),0,'J',1);
$pdf->SetY(46); $pdf->SetX(17);
$pdf->MultiCell(192,4,utf8_decode("Ofrecer a $empresa toda su colaboración para que ésta realice una efectiva administración, distribución, y cobro del servicio, informándole a tiempo sobre la ocurrencia de averías, mal uso del agua, conexiones clandestinas y destrucción de cualquier aditamento que se use para el servicio, así como permitir el acceso de sus empleados al patio de sus vivienda a realizar inspecciones, cateos, lecturas o cortes cuando sea necesario."),0,'J',1);
$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(60); $pdf->SetX(7);//16
$pdf->MultiCell(33,4,utf8_decode("ARTÍCULO OCTAVO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(60); $pdf->SetX(35);
$pdf->MultiCell(175,4,utf8_decode("En caso necesario el CLIENTE  permitirá que el  medidor de consumo de agua sea instalado dentro de su propiedad a fin de garantizar la  seguridad del"),0,'J',1);
$pdf->SetY(64); $pdf->SetX(7);//20
$pdf->MultiCell(202,4,utf8_decode("mismo, responsabilizándose por la preservación de éste, aunque el mismo se instale fuera del límite de su propiedad."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(70); $pdf->SetX(7);//27
$pdf->MultiCell(33,4,utf8_decode("ARTÍCULO NOVENO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(70); $pdf->SetX(35);
$pdf->MultiCell(175,4,utf8_decode("Si el CLIENTE no cumpliere con las obligaciones precedentemente descritas, $empresa tomará las siguientes medidas según sus reincidencias:"),0,'J',1);
$pdf->SetY(76); $pdf->SetX(11);
$pdf->MultiCell(10,4,utf8_decode("a) "),0,'J',1);
$pdf->SetY(76); $pdf->SetX(17);
$pdf->MultiCell(192,4,utf8_decode("Proceder a poner en mora al CLIENTE por los valores dejados de pagar en término prescrito en la facturación, corriendo por cuenta del mismo los gastos judiciales a que obligare el procedimiento legal, independientemente del recargo por atraso que establece este contrato."),0,'J',1);
$pdf->SetY(86); $pdf->SetX(11);
$pdf->MultiCell(10,4,utf8_decode("b) "),0,'J',1);
$pdf->SetY(86); $pdf->SetX(17);
$pdf->MultiCell(192,4,utf8_decode("Proceder a un primer corte utilizando el mecanismo de control establecido para tales fines."),0,'J',1);
$pdf->SetY(92); $pdf->SetX(11);
$pdf->MultiCell(10,4,utf8_decode("c) "),0,'J',1);
$pdf->SetY(92); $pdf->SetX(17);
$pdf->MultiCell(192,4,utf8_decode("En caso de que se compruebe la autoreconexión, se procederá a un segundo corte, levantando parte de la acometida."),0,'J',1);
$pdf->SetY(98); $pdf->SetX(11);
$pdf->MultiCell(10,4,utf8_decode("d) "),0,'J',1);
$pdf->SetY(98); $pdf->SetX(17);
$pdf->MultiCell(192,4,utf8_decode("Si se persistiere en autoreconexión, sin que medie ningún tipo de entendido para ello, se procederá a efectuar un corte definitivo, levantado la totalidad de la acometida, hasta la línea de distribución existente en la calle."),0,'J',1);
$pdf->SetY(108); $pdf->SetX(11);
$pdf->MultiCell(10,4,utf8_decode("e) "),0,'J',1);
$pdf->SetY(108); $pdf->SetX(17);
$pdf->MultiCell(192,4,utf8_decode("El CLIENTE responderá con sus bienes muebles e inmuebles por cualquier deuda que se genere en virtud del presente contrato. "),0,'J',1);
$pdf->SetY(113); $pdf->SetX(11);
$pdf->MultiCell(10,4,utf8_decode("f) "),0,'J',1);
$pdf->SetY(113); $pdf->SetX(17);
$pdf->MultiCell(192,4,utf8_decode("En caso de que EL CLIENTE no obtempere a los reclamos de $empresa, esta se reserva el derecho de reclamar por todas las vías legales el pago del monto adeudado procediendo en consecuencia a someter a la acción de la justicia a los infractores, amparada en los Artículos 434 y 460 del Código Penal; en la ley No. 498 que crea $empresa y su Reglamento de Aplicación Penal; en la Ley No. 847 sobre sustracción de Corriente Eléctrica y de Agua Potable"),0,'J',1);
$pdf->SetY(127); $pdf->SetX(11);
$pdf->MultiCell(10,4,utf8_decode("g) "),0,'J',1);
$pdf->SetY(127); $pdf->SetX(17);
$pdf->MultiCell(192,4,utf8_decode("Los cargos por las acciones descritas en los literales a, b, c y d del presente artículo aparecen en la estructura tarifaria vigente y estarán sujetos a revisión de acuerdo con las variaciones de los costos operacionales de $empresa."),0,'J',1);

$pdf->SetFont('arial', "B", 8.5);
$pdf->Text(67, 140, utf8_decode("(EN ESPERA DE OPINION DEL DEPARTAMENTO LEGAL)"));

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(144); $pdf->SetX(7);
$pdf->MultiCell(32,4,utf8_decode("ARTÍCULO DECIMO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(144); $pdf->SetX(34);
$pdf->MultiCell(175,4,utf8_decode("La estructura tarifaria para la facturación y el cobro de los  servicios es facultad exclusiva de $empresa, previo cumplimiento de las normas establecidas y la"),0,'J',1);
$pdf->SetY(148); $pdf->SetX(7);
$pdf->MultiCell(202,4,utf8_decode("y la misma puede ser variada de acuerdo al aumento del costo de los servicios."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(154); $pdf->SetX(7);
$pdf->MultiCell(47,4,utf8_decode("ARTÍCULO DECIMO-PRIMERO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(154); $pdf->SetX(46);
$pdf->MultiCell(164,4,utf8_decode("En  caso  de  transferencia  de  la  propiedad de  un  inmueble  afectado  de  deuda  por  los  servicios  de  agua  potable y  alcantarillado  sanitario,"),0,'J',1);
$pdf->SetY(158); $pdf->SetX(7);
$pdf->MultiCell(202,4,utf8_decode("sanitario, $empresa considerará que el nuevo adquirente ha aceptado la deuda dejada de pagar por el vendedor de dicho inmueble."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(164); $pdf->SetX(7);
$pdf->MultiCell(47,4,utf8_decode("ARTÍCULO DECIMO-SEGUNDO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(164); $pdf->SetX(46);
$pdf->MultiCell(164,4,utf8_decode("Queda  expresamente  establecido  que el  CLIENTE se  compromete a  renunciar al  empleo de  cualquier medio de reclamar  daños o perjuicios"),0,'J',1);
$pdf->SetY(168); $pdf->SetX(7);
$pdf->MultiCell(202,4,utf8_decode("perjuicios que pudiera sufrir por desperfecto de las instalaciones o deficiencias del servicio de agua."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(174); $pdf->SetX(7);
$pdf->MultiCell(47,4,utf8_decode("ARTÍCULO DECIMO-TERCERO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);

$pdf->SetY(174); $pdf->SetX(46);
$pdf->MultiCell(164,4,utf8_decode("$empresa  no ofrecerá  mantenimiento a las  instalaciones del  Acueducto que sobrepasen  el límite de la  propiedad. Se  exceptúa de  esta disposición"),0,'J',1);
$pdf->SetY(178); $pdf->SetX(7);
$pdf->MultiCell(202,4,utf8_decode("disposición todo lo atinente a medidores, siempre que se compruebe que el CLIENTE no es pasible de responsabilidad."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(184); $pdf->SetX(7);
$pdf->MultiCell(46,4,utf8_decode("ARTÍCULO DECIMO-CUARTO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(184); $pdf->SetX(45);
$pdf->MultiCell(166,4,utf8_decode("Queda expresamente prohibido al CLIENTE conectar directamente sus instalaciones con tanques de reservas de agua o de cualquier naturaleza"),0,'J',1);
$pdf->SetY(188); $pdf->SetX(7);
$pdf->MultiCell(202,4,utf8_decode("naturaleza que no estén equipados con válvulas automáticas de control de flujo que eviten que el agua pueda contaminar el sistema de distribución general."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(194); $pdf->SetX(7);
$pdf->MultiCell(46,4,utf8_decode("ARTÍCULO DECIMO-QUINTO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(194); $pdf->SetX(44);
$pdf->MultiCell(166,4,utf8_decode("El agua que $empresa suministra a un  inmueble es para el servicio  exclusivo de dicho  inmueble y  para las personas que lo habitan. Esta  prohibido"),0,'J',1);
$pdf->SetY(198); $pdf->SetX(7);
$pdf->MultiCell(202,4,utf8_decode("prohibido revenderla o pasarla por una tubería, manguera, o canales a otro inmueble. En caso de que se compruebe la existencia de estas anomalías, $empresa se reserva el derecho de cargarle al primero la deuda del segundo."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(208); $pdf->SetX(7);
$pdf->MultiCell(46,4,utf8_decode("ARTÍCULO DECIMO-SEXTO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(208); $pdf->SetX(44);
$pdf->MultiCell(158,4,utf8_decode("En caso de que el CLIENTE requiera servicios en otra residencia debe realizas los trámites para otro contrato."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(214); $pdf->SetX(7);
$pdf->MultiCell(47,4,utf8_decode("ARTÍCULO DECIMO-SEPTIMO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(214); $pdf->SetX(44);
$pdf->MultiCell(166,4,utf8_decode("$empresa se reserva el derecho a rescindir unilateralmente el presente contrato, en caso de incumplimiento por parte del CLIENTE, en cuyo caso"),0,'J',1);
$pdf->SetY(218); $pdf->SetX(7);
$pdf->MultiCell(202,4,utf8_decode("caso no incurre en responsabilidad."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(224); $pdf->SetX(7);
$pdf->MultiCell(46,4,utf8_decode("ARTÍCULO DECIMO-OCTAVO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(224); $pdf->SetX(44);
$pdf->MultiCell(166,4,utf8_decode("$empresa no está obligada a atender la reclamación de un CLIENTE por un pago no acreditado, cuando hayan transcurrido dos (2) años de haberlo"),0,'J',1);
$pdf->SetY(228); $pdf->SetX(7);
$pdf->MultiCell(202,4,utf8_decode("haberlo realizado."),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(234); $pdf->SetX(7);
$pdf->MultiCell(46,4,utf8_decode("ARTÍCULO DECIMO-NOVENO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(234); $pdf->SetX(44);
$pdf->MultiCell(166,4,utf8_decode("Para cancelar este contrato, el CLIENTE debe estar al día en el pago de los servicios y hacer la solicitud por escrito acompañado de una fotocopia"),0,'J',1);
$pdf->SetY(238); $pdf->SetX(7);
$pdf->MultiCell(202,4,utf8_decode("fotocopia de la cédula de identidad. Una vez cancelado el contrato la Gerencia Comercial procederá a realizar un corte drástico del servicio. "),0,'J',1);

$pdf->SetFont('arial', "BUI", 7);
$pdf->SetY(244); $pdf->SetX(7);
$pdf->MultiCell(35,4,utf8_decode("ARTÍCULO VIGESIMO:"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(244); $pdf->SetX(35);
$pdf->MultiCell(175,4,utf8_decode("Cualquier situación no prevista en el presente contrato, será resuelta de acuerdo al Reglamento para la Prestación y Cobro de los Servicios de Agua Potable"),0,'J',1);
$pdf->SetY(248); $pdf->SetX(7);
$pdf->MultiCell(202,4,utf8_decode("Potable y Alcantarillado Sanitario de $empresa."),0,'J',1);

$pdf->SetFont('arial', "B", 7);
$pdf->SetY(254); $pdf->SetX(7);
$pdf->MultiCell(29,4,utf8_decode("HECHO Y FIRMADO"),0,'J',1);
$pdf->SetFont('arial', "", 7.2);
$pdf->SetY(254); $pdf->SetX(32);
$pdf->MultiCell(178,4,utf8_decode("en dos (2) originales de un mismo tenor y efecto, uno para cada una de las partes, en la Ciudad de $ciudad Provincia de Republica Dominicana a los" .date('d')." dias"),0,'J',1);
$pdf->SetY(258); $pdf->SetX(7);
$pdf->MultiCell(202,4,utf8_decode("los ".date('d')." dias del mes de $mes del año ".date('Y')),0,'J',1);

$pdf->SetFont('arial', "B", 8);
$pdf->SetY(266); $pdf->SetX(7);
$pdf->MultiCell(29,4,utf8_decode("Por $empresa"),0,'J',1);

$pdf->SetFont('arial', "B", 9);
$pdf->Line(7,287,87,287);
$pdf->SetY(290); $pdf->SetX(7);
$pdf->MultiCell(80,4,utf8_decode("Encargado Oficina Comercial"),0,'C',1);
$pdf->Line(127,287,207,287);
$pdf->SetY(290); $pdf->SetX(127);
$pdf->MultiCell(80,4,utf8_decode("El Usuario"),0,'C',1);

$pdf->SetY(298); $pdf->SetX(98);
$pdf->MultiCell(50,4,utf8_decode("Cédula de Identidad No."),0,'C',1);
$pdf->Line(147,301,207,301);

$pdf->SetFont('arial', "", 8);
$pdf->SetY(307); $pdf->SetX(5);
$pdf->MultiCell(40,4,utf8_decode("REVISIÓN: 31/01/2007"),0,'C',1);


$nomarch="../../temp/Contrato_Inmueble_".$inmueble.".pdf";
$pdf->Output($nomarch,'F');

echo $nomarch;