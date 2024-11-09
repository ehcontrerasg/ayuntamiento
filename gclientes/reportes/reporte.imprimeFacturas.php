<?
include ('../clases/fpdf.php');
include ('../clases/class.impFacturas.php');
require_once ('../../funciones/barcode/barcode.inc.php');
include ('../../clases/class.parametros.php');
$proyecto=$_GET['proyecto'];
$grupo=$_GET['grupo'];
$periodoInicial=$_GET['periodo_inicial'];
$periodoFinal=$_GET['periodo_final'];
$zona=$_GET['zona'];
$rnc=$_GET['rnc'];

$pdf=new FPDF();


$l=new facturas() ;
$stida=$l-> datosFacturaPdf($proyecto, $periodoInicial,$periodoFinal,$rnc,$grupo,$zona);
$lecturas=0;
while (oci_fetch($stida)) {
    unset($totalfactura, $totalservicios, $totalotrosconceptos);
    $codinm=oci_result($stida, 'CODIGO_INM');
    $tipodoc=oci_result($stida, 'TIPODOC');
    $fecha_ncf=oci_result($stida, 'VENCE_NCF');
    $ncffac = oci_result($stida, 'NCF');
    $msjNcf = oci_result($stida, 'MSJ_NCF');
    $catastro=oci_result($stida, 'CATASTRO');
    $alias=oci_result($stida, 'NOMBRE_CLIENTE');
    $direccion=oci_result($stida, 'DIRECCION');
    $urbaniza=oci_result($stida, 'DESC_URBANIZACION');
    $zona=oci_result($stida, 'ID_ZONA');
    $fecexp=oci_result($stida, 'FECEXP');
    $periodo=oci_result($stida, 'PERIODO');
    $proceso=oci_result($stida, 'ID_PROCESO');
    $gerencia=oci_result($stida, 'GERENCIA');
    $marmed=oci_result($stida, 'DESC_MED');
    $calmed=oci_result($stida, 'COD_CALIBRE');
    $serial=oci_result($stida, 'SERIAL');
    $fecvcto=oci_result($stida, 'FEC_VCTO');
    $fecorte=oci_result($stida, 'FECCORTE');
    $tipocli=oci_result($stida, 'ID_TIPO_CLIENTE');
    $factura=oci_result($stida, 'CONSEC_FACTURA');
    $rnc = oci_result($stida, 'RNC');
    $estado = oci_result($stida, 'ID_ESTADO');
    $acueducto = oci_result($stida, 'ID_PROYECTO');
    $facpend = oci_result($stida, 'FACPEND');
    $facgen = oci_result($stida, 'FACGEN');
    $mora = oci_result($stida, 'MORA');
    $deudatotal = oci_result($stida, 'DEUDA');
    $documento=oci_result($stida, 'RNC');

    new barCodeGenrator($factura,1,'../../temp/'.$factura.'.gif',100,70,0);

    if ($marmed == "") $marmed = "Sin Medidor";


    ////////////////////////INICIO AVISOS DE DEUDA
    if($estado != 'SS' && $facpend >= 1){
        $debeperiodo = 'Le informamos que adicional al cargo del mes, usted tiene un monto vencido de RD$ '.number_format($deudatotal,2,'.',',').'. Evite el corte presentandose en nuestras oficinas para acordar el pago del atraso.';
        $msjfac = 'Fecha de Corte de Servicio '.$feccorte;
        $msjalerta = 'Evite el Cargo por Corte y Reconexion';
        $msjburo = '';
    }

    if($estado == 'SS'){
        $valsinmora = ($deudatotal-$mora);
        $porc_opcion2 = ($mora * 0.05);
        $porc_opcion3 = ($mora * 0.1);
        $porc_opcion4 = ($mora * 0.5);

        $valor_opcion2 = ($valsinmora + $porc_opcion2);
        $valor_opcion3 = ($valsinmora + $porc_opcion3);
        $valor_opcion4 = ($valsinmora + $porc_opcion4);

        $inicial_opcion2 = ($valor_opcion2 * 0.2);
        $inicial_opcion3 = ($valor_opcion3 * 0.2);
        $inicial_opcion4 = ($valor_opcion4 * 0.2);

        $falta_opcion2 = (($valor_opcion2 - $inicial_opcion2)/5);
        $falta_opcion3 = (($valor_opcion3 - $inicial_opcion3)/12);
        $falta_opcion4 = (($valor_opcion4 - $inicial_opcion4)/19);

        $rebaja_opcion2 = ($deudatotal - $valor_opcion2);
        $rebaja_opcion3 = ($deudatotal - $valor_opcion3);
        $rebaja_opcion4 = ($deudatotal - $valor_opcion4);

        /*$debeperiodo = 'CANCELA TU DEUDA DE RD$'.number_format($deudatotal,2,'.',',').'. Y AHORRA ELIGIENDO TU OPCION DE PAGO*A)*Pago unico de RD$'.number_format($valsinmora,0,'.',',').' con una rebaja de RD$'.number_format($mora,0,'.',',').'.*B)*Inicial de RD$'.number_format($inicial_opcion2,0,'.',',').' + 5 cuotas mensuales de RD$'.number_format($falta_opcion2,0,'.',',').' con una rebaja de RD$'.number_format($rebaja_opcion2,0,'.',',').'.*C)*Inicial de RD$'.number_format($inicial_opcion3,0,'.',',').' + 12 cuotas mensuales de RD$'.number_format($falta_opcion3,0,'.',',').' con una rebaja de RD$'.number_format($rebaja_opcion3,0,'.',',').'.*D)*Inicial de RD$'.number_format($inicial_opcion4,0,'.',',').' + 19 cuotas mensuales de RD$'.number_format($falta_opcion4,0,'.',',').' con una rebaja de RD$'.number_format($rebaja_opcion4,0,'.',',').'.**'.chr(10).'NOTA: Para todas las cuotas a pagar tiene que ser agregado el consumo del mes. Info. Tel.: 598-1722';*/
        $debeperiodo = 'Le informamos que adicional al cargo del mes, usted tiene un monto vencido de RD$ '.number_format($deudatotal,2,'.',',').'. Evite el corte presentandose en nuestras oficinas para acordar el pago del atraso.';
        $msjfac = 'Servicio Suspendido el '.$feccorte;
        $msjalerta = '';
        $msjburo = 'Evite la incorporacion a DataCredito';
    }
    if($facpend == 0 && $facgen > 1){
        $debeperiodo = 'Estimado Cliente: Muchas gracias por mantener su cuenta al d�a y ayudarnos a brindarle un mejor servicio';
        $msjfac = 'Fecha de Corte de Servicio '.$feccorte;
        $msjalerta = 'Evite el Cargo por Corte y Reconexion';
        $msjburo = '';
    }

    if($facgen <= 1 && $rnc == ''){
        $debeperiodo = 'Estimado cliente: Le invitamos a formalizar su contrato de servicio de Agua con la CAASD dirigiendose a las oficinas comerciales ubicadas en la Av. Las Americas Esq. c/Masoneria, Ens. Ozama. Muchas gracias por su colaboraci�n.';
        $msjfac = 'Fecha de Corte de Servicio '.$feccorte;
        $msjburo = '';
    }

    ///////////////////////////////FIN AVISOS DE DEUDA

    $agno = substr($periodo,0,4);
    $mes = substr($periodo,4,2);
    if($mes == '01'){$mes = Ene;} if($mes == '02'){$mes = Feb;} if($mes == '03'){$mes = Mar;} if($mes == '04'){$mes = Abr;}
    if($mes == '05'){$mes = May;} if($mes == '06'){$mes = Jun;} if($mes == '07'){$mes = Jul;} if($mes == '08'){$mes = Ago;}
    if($mes == '09'){$mes = Sep;} if($mes == '10'){$mes = Oct;} if($mes == '11'){$mes = Nov;} if($mes == '12'){$mes = Dic;}
    //CABECERA FACTURA

    /*if($acueducto == 'SD'){
        $a= new Parametro();
        $std2=$a->getParametroByNomPar('RNC_CAASD');
        oci_fetch($std2);
        $rcn=oci_result($std2, 'VAL_PARAMETRO');
        $pdf->SetFont('times','B',10);
        $pdf->SetFont('times','B',6);
    }*/

    $pdf->AddPage();

    $pdf->SetFont('Arial','B',9);
    $pdf->Text(142,43,$msjNcf);
    $pdf->Text(142,47,'NCF '.$ncffac);
    $pdf->SetFont('Arial','B',8);
    if(trim($fecha_ncf)<>''){
        $pdf->Text(142,151,'Vencimiento secuencia: '.$fecha_ncf);
    }
   $pdf->SetFont('Arial','B',10);
    $pdf->SetFont('Arial','B',10);
    $pdf->Text(10,46,"FECHA DE EMISION ".$fecexp);
    $pdf->Text(10,50,utf8_decode($tipodoc  .' '.$documento));
    $pdf->Ln(18);
    $pdf->SetFont('Arial','B',10);
    $pdf->Text(10,54,utf8_decode(substr($alias,0,31)),0,0,'L');
    $pdf->Text(10,58,utf8_decode($direccion),0,0,'C');
    $pdf->Text(10,62,utf8_decode($urbaniza),0,0,'C');
    $pdf->SetFont('Arial','B',9);
    $pdf->Text(10,66,utf8_decode('Código Sistema'));
    $pdf->Text(65,66,$codinm);
    $pdf->Ln(13);
    $pdf->Text(10,70,utf8_decode('Código de Inmueble'));
    $pdf->Text(65,70,$catastro);
    $pdf->Ln(10);
    $posy = 62;
    $l=new facturas();
    $stid=$l->datosServiciosPdf($codinm);
    while (oci_fetch($stid)) {
        $servicio=oci_result($stid, 'DESC_SERVICIO');
        $uso=oci_result($stid, 'DESC_USO');
        $tarifa=oci_result($stid, 'CODIGO_TARIFA');
        $unidades=oci_result($stid, 'UNIDADES_TOT');
        if($servicio == 'Agua' || $servicio == 'Agua de Pozo' || $servicio == 'Alcantarillado Red' || $servicio == 'Alcantarillado Pozo'){
            $pdf->SetFont('Arial','B',8);
            $pdf->Text(117,($posy),$servicio);
            $pdf->Text(146,($posy),$uso);
            $pdf->Text(172,($posy),$tarifa);
            $pdf->Text(190,($posy),$unidades);
            $posy = $posy+4;
        }
    }oci_free_statement($stid);

    $lect=new facturas();
    $stidLectura=$lect->datosLecturaPdf($codinm, $periodo);
    $posy = 90;
    while (oci_fetch($stidLectura)) {
        $lecturas=oci_result($stidLectura, 'LECTURA_ACTUAL');
        $fechas_lec=oci_result($stidLectura, 'FECLEC');
        $pdf->SetFont('Arial','',9);
        $pdf->Text(68,$posy,$fechas_lec);
        $pdf->Text(90,$posy,$lecturas);
        $posy = $posy+4;
        $consumo -= $lecturas;
        $consumo = $consumo * (-1);
    }oci_free_statement($stidLectura);
    // CUERPO DE LA FACTURA
    // SECCION SERVICIOS Y DATOS DE FACTURA

    $pdf->Cell(4,7,'',0,0,'C',false);
    $pdf->SetTextColor(0,0,0);
    $pdf->Ln(12);
    $pdf->Cell(93,7,'',0,0,'C',false);
    $pdf->Cell(4,7,'',0,0,'C',false);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(28,5,utf8_decode(''),0,0,'L',false);
    $pdf->SetFont('Arial','',9);
    $pdf->Text(144,86,$factura);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(19,5,'',0,0,'L',false);
    $pdf->SetFont('Arial','',9);
    $pdf->Text(189, 86,$zona);
    $pdf->Ln(7);
    $pdf->Cell(93,7,'',0,0,'C',false);
    $pdf->Cell(4,7,'',0,0,'C',false);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(28,5,utf8_decode(''),0,0,'L',false);
    $pdf->SetFont('Arial','',9);
    $pdf->Text(146,91,$fecexp,0,0,'R',false);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(19,5,'',0,0,'L',false);
    $pdf->SetFont('Arial','',9);
    $pdf->Text(185,91,$mes.'/'.$agno,0,0,'R',false);
    $pdf->Ln(8);
    $pdf->SetFont('Arial','B',9);

    //SECCION DATOS MEDIDOR, DATOS MEDICION E INFORMACION ADICIONAL
    $pdf->Cell(40,7,'',0,0,'C',false);
    $pdf->Cell(53,7,'',0,0,'C',false);
    $pdf->Cell(4,7,'',0,0,'C',false);
    $pdf->Cell(92,7,'',0,0,'C',false);
    $pdf->Ln(7);
    $pdf->Cell(40,25,'',0,0,'C',false);
    $pdf->Cell(53,25,'',0,0,'C',false);
    $pdf->Cell(4,25,'',0,0,'C',false);
    $pdf->Cell(92,25,'',0,0,'C',false);
    $pdf->SetFont('Arial','B',9);
    $pdf->Text(12,87,'');
    $pdf->Text(12,94,'');
    $pdf->Text(12,101,'');
    $pdf->SetFont('Arial','',9);
    $pdf->Text(25,89,$marmed);
    $pdf->Text(25,94,$calmed);
    $pdf->Text(25,99,$serial);

    $pdf->SetFont('Arial','B',9);
    $pdf->Text(71,86,'');
    $pdf->Text(88,86,'');

    $pdf->Text(52,92,'');
    $pdf->Text(52,97,'');
    if ($marmed == "Sin Medidor") $pdf->Text(84,103,'Promedio');
    else $pdf->Text(72,103,'Diferencia Lectura');
    $pdf->Text(91,98,$consumo);
    $pdf->SetFont('Arial','',10);
    $pdf->SetFont('Arial','B',10);
    $pdf->Text(112,88,'');
    $pdf->Text(112,98,'');
    $pdf->Ln(28);
    $pdf->SetFont('Arial','B',8);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFillColor(0, 128, 192);

    //SECCION DETALLE SERVICIOS DOMICILIARIOS Y OTROS CONCEPTOS
    $pdf->Cell(92.6,6,'',0,0,'C',false);
    $pdf->Cell(4.8,6,'',0,0,'C',false);
    $pdf->Cell(91.4,6,'',0,0,'C',false);

    $pdf->Ln(6);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','B',9);

    $pdf->Cell(30,6,'',0,0,'C',false);
    $pdf->Cell(23,6,'',0,0,'C',false);
    $pdf->Cell(23,6,'',0,0,'C',false);
    $pdf->Cell(17,6,'',0,0,'C',false);
    $pdf->Cell(4.8,6,'',0,0,'C',false);
    $pdf->Cell(70,6,'',0,0,'L',false);
    $pdf->Cell(32,6,'',0,0,'L',false);

    $pxotrocon = 119;
    //$pxcon = 6;
    $conceptoini = '';
    $l=new facturas();
    $stid=$l->detalleFacturaPdf ($factura);
    $pdf->Ln(2);
    while (oci_fetch($stid)) {
        $concepto=oci_result($stid, 'CONCEPTO');
        $rango=oci_result($stid, 'RANGO');
        $unidades=oci_result($stid, 'UNIDADES');
        $valor=oci_result($stid, 'VALOR');
        $codservicio=oci_result($stid, 'COD_SERVICIO');


        if ($concepto != $conceptoini && ($concepto == 'Agua' || $concepto == 'Agua de Pozo')){
            $pdf->Cell(2,6,'',0,0,'R',false);
            $pdf->Cell(30,6,$concepto,0,0,'L',false);
            $conceptoini = $concepto;
        }

        if ($concepto == 'Agua' || $concepto == 'Agua de Pozo'){
            $pdf->SetFont('Arial','',9);

            if ($rango == 0) {
                $f=new facturas();
                $stidb=$f->valorRangosPdf ($codservicio,1, $codinm);
                while (oci_fetch($stidb)) {
                    $valor_mt=oci_result($stidb, 'VALOR_METRO');
                }oci_free_statement($stidb);
                $valor_mt2 = $valor_mt;
                $totalservicios += $valor;
            }
            else{
                $f=new facturas();
                $stidb=$f->valorRangosPdf ($codservicio,$rango, $codinm);
                while (oci_fetch($stidb)) {
                    $valor_mt=oci_result($stidb, 'VALOR_METRO');
                }oci_free_statement($stidb);
                $totalservicios += $valor;
            }


            if($rango == 0){
                $pdf->Ln(5);
                $pdf->Cell(3,5,'',0,0,'L',false);
                $pdf->Cell(35,5,'Consumo Basico',0,0,'L',false);
                $pdf->Cell(10,5,$unidades,0,0,'R');
                $pdf->Cell(20,5,$valor_mt,0,0,'R',false);
                $pdf->Cell(20,5,$valor,0,0,'R',false);
            }

            if($rango == 1){
                $pxr1 = 4;
                $pdf->Ln($pxr1);
                $pdf->Cell(3,5,'',0,0,'L',false);
                $pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L',false);
                $pdf->Cell(10,5,$unidades,0,0,'R',false);
                $pdf->Cell(20,5,$valor_mt,0,0,'R',false);
                $pdf->Cell(20,5,$valor,0,0,'R',false);
            }

            if($rango == 2){
                $pxr2 = 4;
                $pdf->Ln($pxr2);
                $pdf->Cell(3,5,'',0,0,'L');
                $pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L',false);
                $pdf->Cell(10,5,$unidades,0,0,'R',false);
                $pdf->Cell(20,5,$valor_mt,0,0,'R',false);
                $pdf->Cell(20,5,$valor,0,0,'R',false);
            }

            if($rango == 3){
                $pxr3 = 4;
                $pdf->Ln($pxr3);
                $pdf->Cell(3,5,'',0,0,'L',false);
                $pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L',false);
                $pdf->Cell(10,5,$unidades,0,0,'R',false);
                $pdf->Cell(20,5,$valor_mt,0,0,'R',false);
                $pdf->Cell(20,5,$valor,0,0,'R',false);
            }

            if($rango == 4){
                $pxr4 = 4;
                $pdf->Ln($pxr4);
                $pdf->Cell(3,5,'',0,0,'L',false);
                $pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L',false);
                $pdf->Cell(10,5,$unidades,0,0,'R',false);
                $pdf->Cell(20,5,$valor_mt,0,0,'R',false);
                $pdf->Cell(20,5,$valor,0,0,'R',false);
            }

            if($rango == 5){
                $pxr5 = 4;
                $pdf->Ln($pxr5);
                $pdf->Cell(3,5,'',0,0,'L',false);
                $pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L',false);
                $pdf->Cell(10,5,$unidades,0,0,'R',false);
                $pdf->Cell(20,5,$valor_mt,0,0,'R',false);
                $pdf->Cell(20,5,$valor,0,0,'R',false);
            }
        }
        if ($concepto != $conceptoini && ($concepto == 'Alcantarillado Red' || $concepto == 'Alcantarillado Pozo')){
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial','B',9);
            $pxcalc = 5;
            $pdf->Ln($pxcalc);
            $pdf->Cell(2,6,'',0,0,'R',false);
            $pdf->Cell(30,6,$concepto,0,0,'R',false);
            $conceptoini = $concepto;
        }
        if ($concepto == 'Alcantarillado Red' || $concepto == 'Alcantarillado Pozo'){
            $pdf->SetFont('Arial','',9);
            if($rango >= 0){
                $f=new facturas();
                $stidb=$f->valorRangosPdf ($codservicio,$rango, $codinm);
                while (oci_fetch($stidb)) {
                    $valor_mt=oci_result($stidb, 'VALOR_METRO');
                }oci_free_statement($stidb);
                $valor_mt = $valor_mt/* Se removíó esta multiplicación porque estaba sumando el valor acumulado del servicio de agua con el de alcantarillado * $valor_mt2*/;
                $paxalcde = 5;
                $pdf->Ln($paxalcde);
                $pdf->Cell(3,5,'',0,0,'L',false);
                $pdf->Cell(35,5,'Consumo Basico',0,0,'L',false);
                $pdf->Cell(10,5,$unidades,0,0,'R',false);
                $pdf->Cell(20,5,$valor_mt,0,0,'R',false);
                $pdf->Cell(20,5,$valor,0,0,'R',false);
                $totalservicios += $valor;
            }
        }

        if ($concepto != $conceptoini && ($concepto != 'Alcantarillado Red' && $concepto != 'Alcantarillado Pozo' && $concepto != 'Agua' && $concepto != 'Agua de Pozo')){
            $pdf->SetFont('Arial','B',9);
            $pdf->Text(117,$pxotrocon,$concepto);
            $pdf->Text(196,$pxotrocon,$valor);
            $pxotrocon = $pxotrocon + 5;
            $conceptoini = $concepto;
            $totalotrosconceptos += $valor;
        }

    }oci_free_statement($stid);

    $pdf->Ln(56 - $pxr1 - $pxr2 - $pxr3 - $pxr4 - $pxr5 - $pxcalc - $paxalcde);
    $pdf->SetFont('Arial','B',9);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(72.6,6,'',0,0,'L',false);
    $pdf->Cell(18,9,$totalservicios,0,0,'R',false);
    $pdf->Cell(2,6,'',0,0,'R',false);
    $pdf->Cell(4.8,6,'',0,0,'C',false);
    $pdf->Cell(72.6,6,'',0,0,'L',false);
    $pdf->Cell(17,9,$totalotrosconceptos,0,0,'R',false);
    $pdf->Cell(6,6,'',0,0,'R',false);



    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','B',9);

    $pdf->SetFont('Arial','B',11);

    $pdf->SetFont('Arial','B',9);
    if($uso != 'Oficial' and $tipocli != 'GC')
        $pdf->Text(28,187,'Fecha de corte de servicio '.$fecorte);

    $pdf->SetFont('Arial','B',11);
    $totalfactura = $totalservicios + $totalotrosconceptos;

    $pdf->Text(181,195,$fecvcto);
    $pdf->Ln(9.3);
    $pdf->Cell(97.3,18.7,'',0,0,'C',false);
    $pdf->Cell(56,18.7,'',0,0,'C',false);
    $pdf->Cell(35.4,18.7,'',0,0,'C',false);
    $pdf->SetFont('Arial','B',18);
    $pdf->Text(183,184,$totalfactura);
    $pdf->Ln(45);
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(0,3,$debeperiodo,0,'C',false);

    //TALON DE FACTURA

    $pdf->SetFont('Arial','B',10);
    $pdf->Image('../../temp/'.$factura.".gif",33,260,80,20);
    $pdf->SetFont('Arial','B',8);
    $pdf->SetFont('Arial','',8);
    $pdf->Text(145,252,$factura);
    $pdf->Text(145,255,$mes."/".$agno);
    $pdf->Text(145,259,$catastro);
    $pdf->Text(145,262,$proceso);
    $pdf->Text(190,252,$fecexp);
    $pdf->Text(190,255,$codinm);
    $pdf->Text(60,286,"*".$factura."*");
    $pdf->SetFont('Arial','B',9);
    $pdf->Text(38,290,$codinm);
    $pdf->Text(60,290,utf8_decode($alias));
    $pdf->Text(160,290,utf8_decode($urbaniza),0,0,'C');
    $pdf->Text(189,272,$totalfactura);
    $pdf->Text(183,279,$fecvcto);

    unset($totalfactura, $totalservicios, $totalotrosconceptos);
}oci_free_statement($stida);
$pdf->Output("Facturas_$grupo_$periodo.pdf",'I');
?>