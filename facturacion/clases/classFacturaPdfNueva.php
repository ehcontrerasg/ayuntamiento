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
new barCodeGenrator($factura,1,'../../temp/'.$factura.'.gif',100,70,0);
$pdf=new FPDF();
$pdf->AddPage();
if($factura != ''){
    $l=new facturas();
    $stid=$l->datosFacturaPdf($factura);
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
//      $msjfactura=oci_result($stid, 'MSJ_FACTURA');
        $msjalerta=oci_result($stid, 'MSJ_ALERTA');
        $msjburo=oci_result($stid, 'MSJ_BURO');
        $documento=oci_result($stid, 'DOCUMENTO');
        $tipoCliente = oci_result($stid, 'ID_TIPO_CLIENTE');
    }	oci_free_statement($stid);

    $stid=$l->valorDeuda($codinm);
    while (oci_fetch($stid)) {
        $deudaTotal = oci_result($stid, 'DEUDA');
    }oci_free_statement($stid);

    $stid=$l->categoriaInmueble($codinm);
    while (oci_fetch($stid)) {
        $categoria = oci_result($stid, 'CATEGORIA');
    }oci_free_statement($stid);


    $agno = substr($periodo,0,4);
    $mes = substr($periodo,4,2);
    if($mes == '01'){$mes = Ene;} if($mes == '02'){$mes = Feb;} if($mes == '03'){$mes = Mar;} if($mes == '04'){$mes = Abr;}
    if($mes == '05'){$mes = May;} if($mes == '06'){$mes = Jun;} if($mes == '07'){$mes = Jul;} if($mes == '08'){$mes = Ago;}
    if($mes == '09'){$mes = Sep;} if($mes == '10'){$mes = Oct;} if($mes == '11'){$mes = Nov;} if($mes == '12'){$mes = Dic;}

    //CABECERA FACTURA
    $pdf->Image('../../images/fondo_factura.jpeg',3,0,203);
    $mensaje_valor_fiscal_factura= "Si necesita que su factura tenga valor fiscal favor de solicitarlo enviándonos un correo a catastro@caasdoriental.com.";

    $pdf->SetFont('Arial','B',10);
    $pdf->Text(90,33,$fecexp,0,0,'C');
    $pdf->Text(45,38,$codinm);
    if($tipodoc!="Cédula"){
        $pdf->Text(45,42,$documento);
    }
    $pdf->Text(45,47,utf8_decode(substr($alias,0,31)),0,0,'L');
    $pdf->Text(45,52,utf8_decode($direccion),0,0,'C');
    $pdf->Text(45,57,utf8_decode($urbaniza),0,0,'C');
    $pdf->Text(137,39,$msjncf);
    $pdf->Text(137,44,$ncffac);
    if(trim($fecha_ncf)<>''){
        $pdf->Text(137,49,$fecha_ncf);
    }
    $pdf->Text(137,53,$mes."/".$agno);
    $pdf->Text(137,58,$factura);

    $l=new facturas();
    $stid=$l->datosLecturaPdf($codinm, $periodo);
    $posx = 130;
    $posb = 135;
    while (oci_fetch($stid)) {
        $lecturas   = oci_result($stid, 'LECTURA_ACTUAL');
        $fechas_lec = oci_result($stid, 'FECLEC');
        if($lecturas == '') $lecturas = 0;
        $pdf->SetFont('Arial','',9);
        $pdf->Text($posx,69,$lecturas);
        $pdf->Text($posb,75,$fechas_lec);
        $posx = $posx+43;
        $posb = $posb+45;
    }oci_free_statement($stid);

    $l=new  facturas();
    $stid=$l->datosConsumoFactPdf($factura);
    while (oci_fetch($stid)) {
        $consumo=oci_result($stid, 'CONSUMO');
    }oci_free_statement($stid);

    $pdf->SetFont('Arial','',9);
    $pdf->Text(180,80,$consumo);
    $pdf->Text(120,80,$serial);
    $pdf->Text(120,85,$calmed);
    if ($marmed == "") $marmed = "Sin Medidor";
    if ($marmed != "Sin Medidor") $pdf->Text(132,90,'Diferencia Lectura');
    $pdf->Text(165,85,$marmed);


    $posa = 11;
    $l=new facturas();
    $stid=$l->datosServiciosPdf($codinm);
    while (oci_fetch($stid)) {
        $servicio=oci_result($stid, 'DESC_SERVICIO');
        $uso=oci_result($stid, 'DESC_USO');
        $tarifa=oci_result($stid, 'CODIGO_TARIFA');
        $unidades=oci_result($stid, 'UNIDADES_TOT');
        $operaCorte = oci_result($stid, 'OPERA_CORTE');
        $cupoBasico = oci_result($stid, 'CUPO_BASICO');
        if($servicio == 'Agua' || $servicio == 'Agua de Pozo' /*|| $servicio == 'Alcantarillado Red' || $servicio == 'Alcantarillado Pozo'*/){
            //$pdf->SetFont('Arial','B',8);
            if($servicio == 'Agua') $servicio = 'Agua de Red';
            $pdf->Text(115,112,$uso);
            $pdf->Text(175,112,$cupoBasico);
            $pdf->Text(117,122,$tarifa);
            $pdf->Text(120,117,$unidades);
            $pdf->Text(164,117,$servicio);


            $posy = $posy+4;
        }
    }oci_free_statement($stid);
    $pdf->Text(160,122,$categoria);
    $pdf->Text(140,103,$proceso);
    $pdf->Text(140,108,$catastro);

    //$mensaje_valor_fiscal_factura="";

    $s2= new inmuebles();
    $data=$s2->SaldoFavor($codinm);
    while(oci_fetch($data)){
        $saldofavor=oci_result($data,'SALDO');
    }oci_free_statement($data);

    $s2= new inmuebles();
    $data=$s2->DiferidoDeud($codinm);
    while(oci_fetch($data)){
        $difdeuda=oci_result($data,'DIFERIDO');
    }oci_free_statement($data);

    $pdf->SetFont('Arial','B',9);

    //$pdf->SetFont('Arial','',9);
    if($saldofavor == '') $saldofavor = 0;
    if($difdeuda == '') $difdeuda = 0;

    $pdf->Text(117,135,$zona);
    $pdf->Text(150,135,$fecvcto);

    //$pdf->Text(154,95,$saldofavor);
    //$pdf->Text(154,100,$difdeuda);

    //$cabeceraNCF= substr($ncffac,0,3);

    //Este bloque fue modificado por una solicitud de Camilo para mostrar el RNC del inmueble 1048710
    /* if ($cabeceraNCF=='B02') {
         $tipodoc = '';
         $documento = '';

     }*/

    $pxotrocon = 121;
    $pxcon = 6;
    $conceptoini = '';
    $l=new facturas();
    $stid=$l->detalleFacturaPdf ($factura);
    $pdf->Ln(62);
    $mueveadicion = 0;
    $distancia = 68;
    $pyotro = 153;
    while (oci_fetch($stid)) {
        $concepto=oci_result($stid, 'CONCEPTO');
        $rango=oci_result($stid, 'RANGO');
        $unidades=oci_result($stid, 'UNIDADES');
        $valor=oci_result($stid, 'VALOR');
        $codservicio=oci_result($stid, 'COD_SERVICIO');
        $pdf->SetFont('Arial','B',9);

        if ($concepto != $conceptoini && ($concepto == 'Agua' || $concepto == 'Agua de Pozo')){
            $pdf->SetFont('Arial','B',9);
           // $pdf->Cell(1,6,'',1,0,'R');
            $pdf->Cell(27,5,$concepto,0,0,'L');
            $conceptoini = $concepto;
        }

        if ($concepto == 'Agua' || $concepto == 'Agua de Pozo'){
            $pdf->SetFont('Arial','',9);

            $totalservicios += $valor;
            if($rango == 0){
                $pdf->Ln(4);
                $pdf->Cell(2,5,'',0,0,'L');
                $pdf->Cell(33,5,'Consumo Basico',0,0,'L');
                $pdf->Cell(8,5,$unidades,0,0,'R');
                if($unidades>0){
                    if($acueducto == 'SD') $valor_mts = round(($valor/$unidades),2);
                    if($acueducto == 'BC') $valor_mts = round(($valor/$unidades),2);
                }else{
                    $valor_mts=0;
                }

                if($unidades > 0)
                    $pdf->Cell(14,5,$valor_mts,0,0,'R');
                else
                    $pdf->Cell(14,5,0,0,0,'R');

                $pdf->Cell(15,5,$valor,0,0,'R');
            }

            if($rango == 1){
                $distancia = $distancia - 5;
                $pxr1 = 3;
                $pdf->Ln($pxr1);
                $pdf->Cell(2,5,'',0,0,'L');
                $pdf->Cell(33,5,'Consumo Adicional '.$rango,0,0,'L');
                $pdf->Cell(8,5,$unidades,0,0,'R');
                if($unidades==0){
                    $valor_mts=0;
                }else{
                    if($acueducto == 'SD') $valor_mts = round(($valor/$unidades),2);
                    if($acueducto == 'BC') $valor_mts = round(($valor/$unidades),2);
                }


                if($unidades > 0)
                    $pdf->Cell(14,5,$valor_mts,0,0,'R');
                else
                    $pdf->Cell(14,5,0,0,0,'R');
                $pdf->Cell(15,5,$valor,0,0,'R');
            }

            if($rango == 2){
                $distancia = $distancia - 5;
                $pxr2 = 3;
                $pdf->Ln($pxr2);
                $pdf->Cell(2,5,'',0,0,'L');
                $pdf->Cell(33,5,'Consumo Adicional '.$rango,0,0,'L');
                $pdf->Cell(8,5,$unidades,0,0,'R');


                if($unidades > 0){
                    if($acueducto == 'SD') $valor_mts = round(($valor/$unidades),2);
                    if($acueducto == 'BC') $valor_mts = round(($valor/$unidades),2);
                    $pdf->Cell(14,5,$valor_mts,0,0,'R');
                }

                else
                    $pdf->Cell(14,5,0,0,0,'R');
                $pdf->Cell(15,5,$valor,0,0,'R');
            }

            if($rango == 3){
                $distancia = $distancia - 5;
                $pxr3 = 3;
                $pdf->Ln($pxr3);
                $pdf->Cell(2,5,'',0,0,'L');
                $pdf->Cell(33,5,'Consumo Adicional '.$rango,0,0,'L');
                $pdf->Cell(8,5,$unidades,0,0,'R');
                if($acueducto == 'SD') $valor_mts = round(($valor/$unidades),2);
                if($acueducto == 'BC') $valor_mts = round(($valor/$unidades),2);
                if($unidades > 0)
                    $pdf->Cell(14,5,$valor_mts,0,0,'R');
                else
                    $pdf->Cell(14,5,0,0,0,'R');
                $pdf->Cell(15,5,$valor,0,0,'R');
            }

            if($rango == 4){
                $distancia = $distancia - 5;
                $pxr4 = 3;
                $pdf->Ln($pxr4);
                $pdf->Cell(2,5,'',0,0,'L');
                $pdf->Cell(33,5,'Consumo Adicional '.$rango,0,0,'L');
                $pdf->Cell(8,5,$unidades,0,0,'R');
                if($acueducto == 'SD') $valor_mts = round(($valor/$unidades),2);
                if($acueducto == 'BC') $valor_mts = round(($valor/$unidades),2);
                if($unidades > 0)
                    $pdf->Cell(14,5,$valor_mts,0,0,'R');
                else
                    $pdf->Cell(14,5,0,0,0,'R');
                $pdf->Cell(15,5,$valor,0,0,'R');
            }

            if($rango == 5){
                $distancia = $distancia - 5;
                $pxr5 = 3;
                $pdf->Ln($pxr5);
                $pdf->Cell(2,5,'',0,0,'L');
                $pdf->Cell(33,5,'Consumo Adicional '.$rango,0,0,'L');
                $pdf->Cell(8,5,$unidades,0,0,'R');
                if($acueducto == 'SD') $valor_mts = round(($valor/$unidades),2);
                if($acueducto == 'BC') $valor_mts = round(($valor/$unidades),2);
                if($unidades > 0)
                    $pdf->Cell(14,5,$valor_mts,0,0,'R');
                else
                    $pdf->Cell(14,5,0,0,0,'R');
                $pdf->Cell(15,5,$valor,0,0,'R');
            }
        }
        if ($concepto != $conceptoini && ($concepto == 'Alcantarillado Red' || $concepto == 'Alcantarillado Pozo')){
            $distancia = $distancia - 5;
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial','B',9);
            $pxcalc = 5;
            $pdf->Ln($pxcalc);
            $pdf->Cell(27,5,$concepto,0,0,'R');
            $conceptoini = $concepto;
            $pdf->Ln(1);
        }

        if ($concepto == 'Alcantarillado Red' || $concepto == 'Alcantarillado Pozo'){
            $pdf->SetFont('Arial','',9);
            if($rango >= 0){
                $f=new facturas();
                $stidb=$f->valorRangosPdf ($codservicio,$rango, $codinm);
                while (oci_fetch($stidb)) {
                    $valor_mt=oci_result($stidb, 'VALOR_METRO');
                }oci_free_statement($stidb);
                $valor_mt = $valor_mt * $valor_mt2;
                $paxalcde = 5;
                $pdf->Ln($paxalcde);
                $pdf->Cell(2,5,'',0,0,'L');
                if($rango == 0){
                    $distancia = $distancia - 5;
                    $mueveadicion += 5;
                    $pdf->Cell(33,5,'Consumo Basico',0,0,'L');
                }
                if($rango == 1){
                    $distancia = $distancia - 5;
                    $pdf->Cell(33,5,'Consumo Adicional 1',0,0,'L');
                    $mueveadicion += 5;
                }
                if($rango == 2){
                    $distancia = $distancia - 5;
                    $pdf->Cell(33,5,'Consumo Adicional 2',0,0,'L');
                    $mueveadicion += 5;
                }
                if($rango == 3){
                    $distancia = $distancia - 5;
                    $pdf->Cell(33,5,'Consumo Adicional 3',0,0,'L');
                    $mueveadicion += 5;
                }
                if($rango == 4){
                    $distancia = $distancia - 5;
                    $pdf->Cell(33,5,'Consumo Adicional 4',0,0,'L');
                    $mueveadicion += 5;
                }
                $pdf->Cell(8,5,$unidades,0,0,'R');
                if($unidades > 0)
                    $pdf->Cell(14,5,round(($valor/$unidades),1),0,0,'R');
                else
                    $pdf->Cell(14,5,0,0,0,'R');
                $pdf->Cell(15,5,$valor,0,0,'R');
                $totalservicios += $valor;

                // $l->ref($consAFacturado,$unidades);
            }
        }

        if ($concepto != $conceptoini && ($concepto != 'Alcantarillado Red' && $concepto != 'Alcantarillado Pozo' && $concepto != 'Agua' && $concepto != 'Agua de Pozo')){
            $pdf->SetFont('Arial','',9);
            $pdf->Text(12,$pyotro,$concepto);
            $pdf->Text(76,$pyotro,round($valor));
            $conceptoini = $concepto;
            $totalotrosconceptos += $valor;
            $pyotro += 5;
        }

    }oci_free_statement($stid);
    $pdf->SetFont('Arial','B',9);
    $pdf->Text(75,135,round($totalservicios));
    $pdf->Text(75,196,round($totalotrosconceptos));
    $totalfactura = round($totalservicios + $totalotrosconceptos);
    $pdf->Text(75,206,round($totalfactura));
    $pdf->Ln($distancia);
    $pdf->Cell(98,5,'',0,'L');
    $pdf->Cell(80,5,$msjncf);
    $pdf->Ln(6);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(98,5,'',0,'L');
    $pdf->MultiCell(80,5,utf8_decode($msjperiodo));
    $pdf->Ln(6);
    $pdf->Cell(98,5,'',0,'L');
    $pdf->MultiCell(80,5,utf8_decode(trim($mensaje_valor_fiscal_factura)));

    if($tipoCliente!="GC" && $operaCorte!="N"){
        $pdf->SetFont('Arial','B',9);
        $pdf->Text(108,208,'Fecha de corte de servicio '.$fecorte);
    }


    $pdf->Image('../../temp/'.$factura.".gif",55,268,50,8);
    $pdf->Image('../../temp/'.$factura.".gif",156,268,50,8);//Aquí iría el codigo QR

    //$pdf->Image('../../images/codigoQRHistoricoFacturasCAASD.jpg',50,260,25,25); //Aquí iría el codigo QR

    $pdf->SetFont('Arial','B',8);
    $pdf->Text(23,258,utf8_decode($alias));
    $pdf->Text(127,258,utf8_decode($alias));
    $pdf->Text(35,263,$codinm);
    $pdf->Text(137,262,$codinm);
    $pdf->Text(35,267,$proceso);
    $pdf->Text(137,266,$proceso);
    $pdf->Text(35,272,$fecexp);
    $pdf->Text(24,277,$factura);
    $pdf->Text(24,281,$mes."/".$agno);
    $pdf->Text(24,285,$fecvcto);

    $pdf->Text(80,290,'RD$ '.$totalfactura);
    $deudaAnterior = $deudaTotal - $totalfactura;
    $pdf->Text(170,290,'RD$ '.$deudaAnterior);

    $pdf->title="Factura";
    $pdf->Output("facturas_digital/Factura_$factura.pdf",'I');
}