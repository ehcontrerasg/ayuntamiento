<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
include ('../clases/fpdf.php');
include ('../clases/class.facturas.php');
include ('../clases/class.inmuebles.php');
include ('../clases/class.parametros.php');
require_once ('../../funciones/barcode/barcode.inc.php');
$factura=$_GET['factura'];
//$factura='97385709';

new barCodeGenrator($factura,1,'../../temp/'.$factura.'.gif',100,70,0);
$pdf=new FPDF();
$pdf->AddPage();
if($factura != ''){
    $l=new facturas();
    $stid=$l->datosFacturaAseoPdf($factura);
    while (oci_fetch($stid)) {
        $codinm =oci_result($stid, 'CODIGO_INM');
        $tipodoc=oci_result($stid, 'TIPODOC');
        $fecha_ncf=oci_result($stid, 'VENCE_NCF');
        $ncffac = oci_result($stid, 'NCF');
        $catastro=oci_result($stid, 'CATASTRO');
        $alias=oci_result($stid, 'ALIAS');
        $direccion=oci_result($stid, 'DIRECCION');
        $urbaniza=oci_result($stid, 'DESC_URBANIZACION');
        $zona=oci_result($stid, 'ID_ZONA');
        $fecexp=oci_result($stid, 'FECEXP');
        $periodo=oci_result($stid, 'PERIODO');
        $proceso=oci_result($stid, 'ID_PROCESO');
        $gerencia=oci_result($stid, 'GERENCIA');
        $marmed=oci_result($stid, 'DESC_MED');
        $calmed=oci_result($stid, 'DESC_CALIBRE');
        $serial=oci_result($stid, 'SERIAL');
        $fecvcto=oci_result($stid, 'FEC_VCTO');
        $fecorte=oci_result($stid, 'FECCORTE');
        $acueducto=oci_result($stid, 'ID_PROYECTO');
        $msjncf=oci_result($stid, 'MSJ_NCF');
        $msjperiodo=oci_result($stid, 'MSJ_PERIODO');
        $msjalerta=oci_result($stid, 'MSJ_ALERTA');
        $msjburo=oci_result($stid, 'MSJ_BURO');
        $documento=oci_result($stid, 'DOCUMENTO');
        $tipoCliente = oci_result($stid, 'ID_TIPO_CLIENTE');
        $totalFac = oci_result($stid, 'TOTAL');
    }	oci_free_statement($stid);

    $stid=$l->valorDeudaAseo($codinm);
    while (oci_fetch($stid)) {
        $deudaTotal = oci_result($stid, 'DEUDA');
    }oci_free_statement($stid);

    $stid=$l->categoriaInmuebleAseo($codinm);
    while (oci_fetch($stid)) {
        $categoria = oci_result($stid, 'CATEGORIA');
    }oci_free_statement($stid);

    $agno = substr($periodo,0,4);
    $mes = substr($periodo,4,2);
    if($mes == '01'){$mes = Ene;} if($mes == '02'){$mes = Feb;} if($mes == '03'){$mes = Mar;} if($mes == '04'){$mes = Abr;}
    if($mes == '05'){$mes = May;} if($mes == '06'){$mes = Jun;} if($mes == '07'){$mes = Jul;} if($mes == '08'){$mes = Ago;}
    if($mes == '09'){$mes = Sep;} if($mes == '10'){$mes = Oct;} if($mes == '11'){$mes = Nov;} if($mes == '12'){$mes = Dic;}

    if($acueducto == 'SD' && $periodo >= 202112){
        //CABECERA FACTURA
        $pdf->Image('../../images/LogoAseo.jpeg',5,5,35);
        $pdf->SetTextColor(32,128,2);
        $pdf->SetFont('Helvetica','',16);
        $pdf->Text(50,21,utf8_decode('AYUNTAMIENTO') ,0,0,'C');
        $pdf->Text(40,28,utf8_decode('SANTO DOMINGO ESTE') ,0,0,'C');
        $pdf->SetFont('Helvetica','',10);
        $pdf->Text(115,24,utf8_decode('FACTURA DE SERVICIOS Y RENTAS MUNICIPALES') ,0,0,'C');

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',9);
        $pdf->Text(8,48,utf8_decode('RNC:') ,0,0,'C');

        $pdf->SetFillColor(237,140,137);
        $pdf->Rect(165, 39, 39.5, 10, 'F');
        $pdf->SetFont('Arial','B',9);
        $pdf->Text(70,48,utf8_decode('NCF:') ,0,0,'C');
        $pdf->Text(143,45,utf8_decode('Inmueble No.') ,0,0,'C');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(170,45,utf8_decode($codinm),0,0,'C');
        $pdf->Text(83,48,utf8_decode($ncffac),0,0,'C');
        $pdf->Text(23,48,utf8_decode($documento),0,0,'C');

        $pdf->SetFillColor(32,128,2);
        $pdf->Rect(5, 50, 200, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(8,54,utf8_decode('Información general') ,0,0,'C');

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Text(8,62,utf8_decode('Nombre:') ,0,0,'C');
        $pdf->Text(8,68,utf8_decode('Dirección:') ,0,0,'C');
        $pdf->Text(8,74,utf8_decode('Ruta:') ,0,0,'C');
        $pdf->Text(8,80,utf8_decode('Registro catastral:') ,0,0,'C');

        $pdf->SetDrawColor(37,170,213);
        $pdf->Line(136,57,136,82);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Text(138,62,utf8_decode('Uso') ,0,0,'C');
        $pdf->Text(172,62,utf8_decode('Actividad') ,0,0,'C');
        $pdf->Line(170,57,170,82);
        $pdf->Text(138,73,utf8_decode('Tarifa/unid/aseo') ,0,0,'C');
        $pdf->Text(172,73,utf8_decode('No. Unidades') ,0,0,'C');
        $pdf->Line(204.5,57,204.5,82);

        $pdf->SetFillColor(37,170,213);
        $pdf->Rect(5, 83, 200, 0.5, 'F');

        $pdf->SetFillColor(32,128,2);
        $pdf->Rect(5, 87, 99, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(8,91,utf8_decode('Datos de la factura') ,0,0,'C');

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Text(8,98,utf8_decode('Factura No.') ,0,0,'C');
        $pdf->Text(57,98,utf8_decode('Zona facturación') ,0,0,'C');
        $pdf->Text(8,109,utf8_decode('Fecha de emisión') ,0,0,'C');
        $pdf->Text(57,109,utf8_decode('Periodo facturado') ,0,0,'C');

        $pdf->SetFillColor(32,128,2);
        $pdf->Rect(106, 87, 99, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(109,91,utf8_decode('Información adicional') ,0,0,'C');

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Text(109,98,utf8_decode('Saldo a favor') ,0,0,'C');
        $pdf->Text(158,98,utf8_decode('Fecha último pago') ,0,0,'C');
        $pdf->Text(109,109,utf8_decode('Saldo pendiente') ,0,0,'C');
        $pdf->Text(158,109,utf8_decode('Monto último pago') ,0,0,'C');

        $pdf->SetDrawColor(37,170,213);
        $pdf->Line(5,94,5,119);
        $pdf->Line(53,94,53,119);
        $pdf->Line(104,94,104,119);
        $pdf->Line(106,94,106,119);
        $pdf->Line(154,94,154,119);
        $pdf->Line(204.5,94,204.5,119);

        $pdf->SetFillColor(37,170,213);
        $pdf->Rect(5, 120, 200, 0.5, 'F');

        $pdf->SetFillColor(32,128,2);
        $pdf->Rect(5, 123, 99, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(8,127,utf8_decode('Detalles servicios') ,0,0,'C');

        $pdf->SetFillColor(32,128,2);
        $pdf->Rect(106, 123, 99, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(109,127,utf8_decode('Otros conceptos') ,0,0,'C');

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',8);
        $pdf->Text(45,62,utf8_decode(substr($alias,0,31)),0,0,'L');
        $pdf->Text(45,68,utf8_decode($direccion),0,0,'C');
        $pdf->Text(45,74,utf8_decode('073-10-14-096'),0,0,'C');
        $pdf->Text(45,80,utf8_decode($catastro),0,0,'C');
        $pdf->Text(10,103,utf8_decode($factura),0,0,'C');
        $pdf->Text(10,115,utf8_decode($fecexp),0,0,'C');
        $pdf->Text(59,103,utf8_decode($zona),0,0,'C');
        $pdf->Text(59,115,utf8_decode($periodo),0,0,'C');
        $pdf->Text(111,115,utf8_decode('272,345.00'),0,0,'C');

        $pdf->Text(10,135,utf8_decode('SERVICIO DE ASEO') ,0,0,'C');
        $pdf->Text(90,135,utf8_decode($totalFac) ,0,0,'C');

        $pdf->SetDrawColor(37,170,213);
        $pdf->Line(5,130,5,155);
        $pdf->Line(70,130,70,155);
        $pdf->Line(104,130,104,155);
        $pdf->Line(106,130,106,155);
        $pdf->Line(171,130,171,155);
        $pdf->Line(204.5,130,204.5,155);

        $pdf->SetFillColor(37,170,213);
        $pdf->Rect(5, 156, 200, 0.5, 'F');

        $pdf->SetFillColor(140,197,114);
        $pdf->Rect(70, 158, 34, 8, 'F');
        $pdf->Rect(70, 167, 34, 8, 'F');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(43,163,utf8_decode('Total servicios:') ,0,0,'R');
        $pdf->Text(46,172,utf8_decode('Vencimiento:') ,0,0,'R');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(80,163,utf8_decode($totalFac) ,0,0,'R');
        $pdf->Text(78,172,utf8_decode($fecvcto) ,0,0,'R');

        $pdf->SetFillColor(140,197,114);
        $pdf->Rect(171, 158, 34, 8, 'F');
        $pdf->Rect(171, 167, 34, 8, 'F');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(132,163,utf8_decode('Total otros conceptos:') ,0,0,'R');
        $pdf->Text(132,172,utf8_decode('Total factura mensual:') ,0,0,'R');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(180,172,utf8_decode($totalFac) ,0,0,'R');

        $pdf->SetFillColor(32,128,2);
        $pdf->Rect(5, 176, 200, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(8,180,utf8_decode('Información') ,0,0,'C');

        $pdf->SetFillColor(32,128,2);
        $pdf->Rect(5, 206, 200, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(8,210,utf8_decode('Deuda anterior') ,0,0,'C');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Text(8,217,utf8_decode('Nombre') ,0,0,'C');
        $pdf->Text(8,223,utf8_decode('Factura No.') ,0,0,'C');
        $pdf->Text(8,229,utf8_decode('Inmueble No.') ,0,0,'C');

        $pdf->SetFont('Arial','B',8);
        $pdf->Text(45,217,utf8_decode($alias),0,0,'C');
        $pdf->Text(45,223,utf8_decode($factura),0,0,'C');
        $pdf->Text(45,229,utf8_decode($codinm),0,0,'C');



        $pdf->SetFont('Arial','B',10);
        $pdf->Text(140,217,utf8_decode('Total deuda anterior') ,0,0,'C');
        $pdf->SetFillColor(140,197,114);
        $pdf->Rect(140, 219, 34, 8, 'F');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(147,224,utf8_decode('268,345.00') ,0,0,'R');
        $pdf->Image('../../images/LogoAseo.jpeg',180,219,12);
        $pdf->SetFont('Arial','',7);
        $pdf->Text(121,231,utf8_decode('Favor no colocar sello sobre el código de barras') ,0,0,'C');
        $pdf->Image('../../temp/'.$factura.".gif",133,235,50,8);
        $pdf->SetFillColor(37,170,213);
        $pdf->Rect(5, 246, 200, 0.5, 'F');

        $pdf->SetFillColor(32,128,2);
        $pdf->Rect(5, 248, 200, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(8,252,utf8_decode('Factura del mes') ,0,0,'C');

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Text(8,259,utf8_decode('Nombre') ,0,0,'C');
        $pdf->Text(8,265,utf8_decode('Factura No.') ,0,0,'C');
        $pdf->Text(8,271,utf8_decode('Periodo') ,0,0,'C');
        $pdf->Text(8,277,utf8_decode('Inmueble No.') ,0,0,'C');

        $pdf->SetFont('Arial','B',8);
        $pdf->Text(45,259,utf8_decode($alias),0,0,'C');
        $pdf->Text(45,265,utf8_decode($factura),0,0,'C');
        $pdf->Text(45,271,utf8_decode($periodo),0,0,'C');
        $pdf->Text(45,277,utf8_decode($codinm),0,0,'C');

        $pdf->SetFont('Arial','B',10);
        $pdf->Text(109,259,utf8_decode('Vencimiento') ,0,0,'C');
        $pdf->Text(139,259,utf8_decode('Total factura del mes') ,0,0,'C');
        $pdf->SetFillColor(140,197,114);
        $pdf->Rect(140, 261, 34, 8, 'F');
        $pdf->Rect(102, 261, 34, 8, 'F');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(111,266,utf8_decode($fecvcto) ,0,0,'R');
        $pdf->Text(152,266,utf8_decode($totalFac) ,0,0,'R');
        $pdf->Image('../../images/LogoAseo.jpeg',180,261,12);
        $pdf->SetFont('Arial','',7);
        $pdf->Text(121,272,utf8_decode('Favor no colocar sello sobre el código de barras') ,0,0,'C');
        $pdf->Image('../../temp/'.$factura.".gif",133,280,50,8);
        $pdf->SetFillColor(37,170,213);
        $pdf->Rect(5, 294, 200, 0.5, 'F');

        $l=new facturas();
        $stid=$l->datosValorMetroAseoPdf($codinm);
        while (oci_fetch($stid)) {
            $valor_met = oci_result($stid, 'VALOR_METRO');
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial','',8);
        }oci_free_statement($stid);


        $l=new facturas();
        $stid=$l->datosServiciosPdf($codinm);
        while (oci_fetch($stid)) {
            $servicio=oci_result($stid, 'DESC_SERVICIO');
            $uso=oci_result($stid, 'DESC_USO');
            $tarifa=oci_result($stid, 'CODIGO_TARIFA');
            $unidades=oci_result($stid, 'UNIDADES_TOT');
            $operaCorte = oci_result($stid, 'OPERA_CORTE');
            $cupoBasico = oci_result($stid, 'CUPO_BASICO');

            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial','',8);
            $pdf->Text(140,67,utf8_decode($uso),0,0,'C');
            $pdf->Text(174,67,utf8_decode(''),0,0,'C');
            $pdf->Text(140,78,utf8_decode($valor_met),0,0,'C');
            $pdf->Text(174,78,utf8_decode($unidades),0,0,'C');
        }oci_free_statement($stid);
    }
    if($acueducto == 'BC' && $periodo >= 202112){
        //CABECERA FACTURA
        $pdf->Image('../../images/AyuntamientoBocachica.jpeg',3,2,43);
        $pdf->SetTextColor(18,30,135);
        $pdf->SetFont('Helvetica','',16);
        $pdf->Text(50,21,utf8_decode('AYUNTAMIENTO') ,0,0,'C');
        $pdf->Text(55,28,utf8_decode('BOCACHICA') ,0,0,'C');
        $pdf->SetFont('Helvetica','',10);
        $pdf->Text(115,24,utf8_decode('FACTURA DE SERVICIOS Y RENTAS MUNICIPALES') ,0,0,'C');

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',9);
        $pdf->Text(8,48,utf8_decode('RNC:') ,0,0,'C');

        $pdf->SetFillColor(234,128,124);
        //$pdf->SetFillColor(140,197,114);
        $pdf->Rect(165, 39, 39.5, 10, 'F');
        $pdf->SetFont('Arial','B',9);
        $pdf->Text(70,48,utf8_decode('NCF:') ,0,0,'C');
        $pdf->Text(143,45,utf8_decode('Inmueble No.') ,0,0,'C');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(170,45,utf8_decode($codinm),0,0,'C');
        $pdf->Text(83,48,utf8_decode($ncffac),0,0,'C');
        $pdf->Text(23,48,utf8_decode($documento),0,0,'C');

        $pdf->SetFillColor(18,30,135);
        //$pdf->SetFillColor(249,15,5);
        //$pdf->SetFillColor(32,128,2);
        $pdf->Rect(5, 50, 200, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(8,54,utf8_decode('Información general') ,0,0,'C');

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Text(8,62,utf8_decode('Nombre:') ,0,0,'C');
        $pdf->Text(8,68,utf8_decode('Dirección:') ,0,0,'C');
        $pdf->Text(8,74,utf8_decode('Ruta:') ,0,0,'C');
        $pdf->Text(8,80,utf8_decode('Registro catastral:') ,0,0,'C');

        $pdf->SetDrawColor(37,170,213);
        $pdf->Line(136,57,136,82);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Text(138,62,utf8_decode('Uso') ,0,0,'C');
        $pdf->Text(172,62,utf8_decode('Actividad') ,0,0,'C');
        $pdf->Line(170,57,170,82);
        $pdf->Text(138,73,utf8_decode('Tarifa/unid/aseo') ,0,0,'C');
        $pdf->Text(172,73,utf8_decode('No. Unidades') ,0,0,'C');
        $pdf->Line(204.5,57,204.5,82);


        $pdf->SetFillColor(37,170,213);
        $pdf->Rect(5, 83, 200, 0.5, 'F');

        $pdf->SetFillColor(18,30,135);
        //$pdf->SetFillColor(32,128,2);
        $pdf->Rect(5, 87, 99, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(8,91,utf8_decode('Datos de la factura') ,0,0,'C');

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Text(8,98,utf8_decode('Factura No.') ,0,0,'C');
        $pdf->Text(57,98,utf8_decode('Zona facturación') ,0,0,'C');
        $pdf->Text(8,109,utf8_decode('Fecha de emisión') ,0,0,'C');
        $pdf->Text(57,109,utf8_decode('Periodo facturado') ,0,0,'C');

        $pdf->SetFillColor(18,30,135);
        //$pdf->SetFillColor(32,128,2);
        $pdf->Rect(106, 87, 99, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(109,91,utf8_decode('Información adicional') ,0,0,'C');

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Text(109,98,utf8_decode('Saldo a favor') ,0,0,'C');
        $pdf->Text(158,98,utf8_decode('Fecha último pago') ,0,0,'C');
        $pdf->Text(109,109,utf8_decode('Saldo pendiente') ,0,0,'C');
        $pdf->Text(158,109,utf8_decode('Monto último pago') ,0,0,'C');

        $pdf->SetDrawColor(37,170,213);
        $pdf->Line(5,94,5,119);
        $pdf->Line(53,94,53,119);
        $pdf->Line(104,94,104,119);
        $pdf->Line(106,94,106,119);
        $pdf->Line(154,94,154,119);
        $pdf->Line(204.5,94,204.5,119);

        $pdf->SetFillColor(37,170,213);
        $pdf->Rect(5, 120, 200, 0.5, 'F');

        $pdf->SetFillColor(18,30,135);
        //$pdf->SetFillColor(32,128,2);
        $pdf->Rect(5, 123, 99, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(8,127,utf8_decode('Detalles servicios') ,0,0,'C');

        $pdf->SetFillColor(18,30,135);
        //$pdf->SetFillColor(32,128,2);
        $pdf->Rect(106, 123, 99, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(109,127,utf8_decode('Otros conceptos') ,0,0,'C');

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',8);
        $pdf->Text(45,62,utf8_decode(substr($alias,0,31)),0,0,'L');
        $pdf->Text(45,68,utf8_decode($direccion),0,0,'C');
        $pdf->Text(45,74,utf8_decode('073-10-14-096'),0,0,'C');
        $pdf->Text(45,80,utf8_decode($catastro),0,0,'C');
        $pdf->Text(10,103,utf8_decode($factura),0,0,'C');
        $pdf->Text(10,115,utf8_decode($fecexp),0,0,'C');
        $pdf->Text(59,103,utf8_decode($zona),0,0,'C');
        $pdf->Text(59,115,utf8_decode($periodo),0,0,'C');
        $pdf->Text(111,115,utf8_decode('272,345.00'),0,0,'C');

        $l=new facturas();
        $stid=$l->datosValorMetroAseoPdf($codinm);
        while (oci_fetch($stid)) {
            $valor_met = oci_result($stid, 'VALOR_METRO');
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial','',8);
        }oci_free_statement($stid);

        $pdf->Text(10,135,utf8_decode('SERVICIO DE ASEO') ,0,0,'C');
        $pdf->Text(90,135,utf8_decode($totalFac) ,0,0,'C');

        $pdf->SetDrawColor(37,170,213);
        $pdf->Line(5,130,5,155);
        $pdf->Line(70,130,70,155);
        $pdf->Line(104,130,104,155);
        $pdf->Line(106,130,106,155);
        $pdf->Line(171,130,171,155);
        $pdf->Line(204.5,130,204.5,155);

        $pdf->SetFillColor(37,170,213);
        $pdf->Rect(5, 156, 200, 0.5, 'F');

        $pdf->SetFillColor(234,128,124);
        //$pdf->SetFillColor(140,197,114);
        $pdf->Rect(70, 158, 34, 8, 'F');
        $pdf->Rect(70, 167, 34, 8, 'F');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(43,163,utf8_decode('Total servicios:') ,0,0,'R');
        $pdf->Text(46,172,utf8_decode('Vencimiento:') ,0,0,'R');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(80,163,utf8_decode($totalFac) ,0,0,'R');
        $pdf->Text(78,172,utf8_decode($fecvcto) ,0,0,'R');

        $pdf->SetFillColor(234,128,124);
        //$pdf->SetFillColor(140,197,114);
        $pdf->Rect(171, 158, 34, 8, 'F');
        $pdf->Rect(171, 167, 34, 8, 'F');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(132,163,utf8_decode('Total otros conceptos:') ,0,0,'R');
        $pdf->Text(132,172,utf8_decode('Total factura mensual:') ,0,0,'R');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(180,172,utf8_decode($totalFac) ,0,0,'R');

        $pdf->SetFillColor(18,30,135);
        //$pdf->SetFillColor(32,128,2);
        $pdf->Rect(5, 176, 200, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(8,180,utf8_decode('Información') ,0,0,'C');

        $pdf->SetFillColor(18,30,135);
        //$pdf->SetFillColor(32,128,2);
        $pdf->Rect(5, 206, 200, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(8,210,utf8_decode('Deuda anterior') ,0,0,'C');
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Text(8,217,utf8_decode('Nombre') ,0,0,'C');
        $pdf->Text(8,223,utf8_decode('Factura No.') ,0,0,'C');
        $pdf->Text(8,229,utf8_decode('Inmueble No.') ,0,0,'C');

        $pdf->SetFont('Arial','B',8);
        $pdf->Text(45,217,utf8_decode($alias),0,0,'C');
        $pdf->Text(45,223,utf8_decode($factura),0,0,'C');
        $pdf->Text(45,229,utf8_decode($codinm),0,0,'C');



        $pdf->SetFont('Arial','B',10);
        $pdf->Text(140,217,utf8_decode('Total deuda anterior') ,0,0,'C');
        $pdf->SetFillColor(234,128,124);
        //$pdf->SetFillColor(140,197,114);
        $pdf->Rect(140, 219, 34, 8, 'F');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(147,224,utf8_decode('268,345.00') ,0,0,'R');
        $pdf->Image('../../images/AyuntamientoBocachica.jpeg',180,219,20);
        $pdf->SetFont('Arial','',7);
        $pdf->Text(121,231,utf8_decode('Favor no colocar sello sobre el código de barras') ,0,0,'C');
        $pdf->Image('../../temp/'.$factura.".gif",133,235,50,8);
        $pdf->SetFillColor(37,170,213);
        $pdf->Rect(5, 246, 200, 0.5, 'F');

        $pdf->SetFillColor(18,30,135);
        //$pdf->SetFillColor(32,128,2);
        $pdf->Rect(5, 248, 200, 6, 'F');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('Arial','B',10);
        $pdf->Text(8,252,utf8_decode('Factura del mes') ,0,0,'C');

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',10);
        $pdf->Text(8,259,utf8_decode('Nombre') ,0,0,'C');
        $pdf->Text(8,265,utf8_decode('Factura No.') ,0,0,'C');
        $pdf->Text(8,271,utf8_decode('Periodo') ,0,0,'C');
        $pdf->Text(8,277,utf8_decode('Inmueble No.') ,0,0,'C');

        $pdf->SetFont('Arial','B',8);
        $pdf->Text(45,259,utf8_decode($alias),0,0,'C');
        $pdf->Text(45,265,utf8_decode($factura),0,0,'C');
        $pdf->Text(45,271,utf8_decode($periodo),0,0,'C');
        $pdf->Text(45,277,utf8_decode($codinm),0,0,'C');

        $pdf->SetFont('Arial','B',10);
        $pdf->Text(109,259,utf8_decode('Vencimiento') ,0,0,'C');
        $pdf->Text(139,259,utf8_decode('Total factura del mes') ,0,0,'C');
        $pdf->SetFillColor(234,128,124);
        //$pdf->SetFillColor(140,197,114);
        $pdf->Rect(140, 261, 34, 8, 'F');
        $pdf->Rect(102, 261, 34, 8, 'F');
        $pdf->SetFont('Arial','',10);
        $pdf->Text(111,266,utf8_decode($fecvcto) ,0,0,'R');
        $pdf->Text(152,266,utf8_decode($totalFac) ,0,0,'R');
        $pdf->Image('../../images/AyuntamientoBocachica.jpeg',180,261,20);
        $pdf->SetFont('Arial','',7);
        $pdf->Text(121,272,utf8_decode('Favor no colocar sello sobre el código de barras') ,0,0,'C');
        $pdf->Image('../../temp/'.$factura.".gif",133,280,50,8);
        $pdf->SetFillColor(37,170,213);
        $pdf->Rect(5, 294, 200, 0.5, 'F');


        $l=new facturas();
        $stid=$l->datosServiciosAseoPdf($codinm);
        while (oci_fetch($stid)) {
            $servicio=oci_result($stid, 'DESC_SERVICIO');
            $uso=oci_result($stid, 'DESC_USO');
            $actividad=oci_result($stid, 'DESC_ACTIVIDAD');
            $tarifa=oci_result($stid, 'CODIGO_TARIFA');
            $unidades=oci_result($stid, 'UNIDADES_TOT');
            $operaCorte = oci_result($stid, 'OPERA_CORTE');
            $cupoBasico = oci_result($stid, 'CUPO_BASICO');
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial','',8);
            $pdf->Text(140,67,utf8_decode($uso),0,0,'C');
            $pdf->Text(174,67,utf8_decode($actividad),0,0,'C');
            $pdf->Text(140,78,utf8_decode($valor_met),0,0,'C');
            $pdf->Text(174,78,utf8_decode($unidades),0,0,'C');
        }oci_free_statement($stid);

    }
    $pdf->title="Factura";
    $pdf->Output("facturas_digital/Factura_$factura.pdf",'I');
}

