<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
include_once ('../clases/fpdf.php');
include_once ('../../clases/class.factura.php');
include_once ('../../clases/class.facturas.php');
include_once ('../../clases/class.inmuebles.php');
include_once ('../../clases/class.correo.php');
include_once ('../../clases/class.parametros.php');
include_once ('../../funciones/barcode/barcode.inc.php');


date_default_timezone_set('America/Santo_Domingo');
$hoy = date('d/m/Y');
$a=new Factura();
$registros=$a->zonasCerradas($hoy);
while(oci_fetch($registros)){
    $per_cierre = oci_result($registros, 'PERIODO');
    $zon_cierre = oci_result($registros, 'ID_ZONA');
    $b=new Factura();
    $datos=$b->facturaDigital($per_cierre, $zon_cierre);
    while(oci_fetch($datos)){
        $factura = oci_result($datos, 'CONSEC_FACTURA');
        $email = strtolower(oci_result($datos, 'EMAIL'));

        new barCodeGenrator($factura,1,'../../temp/'.$factura.'.gif',100,70,0);
        $pdf=new FPDF();
        $pdf->AddPage();
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

        if($acueducto == 'SD' && $periodo >= 202112){
            //CABECERA FACTURA
            $pdf->SetFillColor(18,48,108);
            $pdf->Rect(0, 0, 240, 30, 'F');
            $pdf->Image('../../images/logoCaasdFac.jpg',3,13,100);

            $mensaje_valor_fiscal_factura= "Si necesita que su factura tenga valor fiscal favor de solicitarlo enviándonos un correo a catastro@caasdoriental.com.";
            $pdf->SetTextColor(18,48,108);
            $pdf->SetFont('Arial','B',10);
            $pdf->Text(72,33,utf8_decode('Emisión') ,0,0,'C');
            $pdf->Text(12,38,utf8_decode('Código de sistema') ,0,0,'C');
            $pdf->Text(35,42,utf8_decode('RNC') ,0,0,'C');
            $pdf->Text(29,47,utf8_decode('Nombre') ,0,0,'C');
            $pdf->Text(26,52,utf8_decode('Dirección') ,0,0,'C');
            $pdf->Text(8,57,utf8_decode('Sector/Urbanización') ,0,0,'C');
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial','B',10);
            $pdf->Text(90,33,$fecexp,0,0,'C');
            $pdf->Text(45,38,$codinm);
            if($tipodoc!="Cédula"){
                $pdf->Text(45,42,$documento);
            }


            $pdf->SetTextColor(0,0,0);
            $pdf->Text(45,47,utf8_decode(substr($alias,0,31)),0,0,'L');
            $pdf->Text(45,52,utf8_decode($direccion),0,0,'C');
            $pdf->Text(45,57,utf8_decode($urbaniza),0,0,'C');
            $pdf->SetTextColor(18,48,108);
            $pdf->Text(127,39,utf8_decode('Tipo') ,0,0,'C');
            $pdf->Text(127,44,utf8_decode('NCF') ,0,0,'C');
            $pdf->Text(113,49,utf8_decode('Vencimiento') ,0,0,'C');
            $pdf->Text(121,53,utf8_decode('Periodo') ,0,0,'C');
            $pdf->Text(115,58,utf8_decode('No. Factura') ,0,0,'C');
            $pdf->SetTextColor(0,0,0);
            $pdf->Text(137,39,$msjncf);
            $pdf->Text(137,44,$ncffac);
            if(trim($fecha_ncf)<>''){
                $pdf->Text(137,49,$fecha_ncf);
            }
            $pdf->Text(137,53,$mes."/".$agno);
            $pdf->Text(137,58,$factura);
            $pdf->SetFillColor(18,48,108);
            $pdf->SetTextColor(255,255,255);
            $pdf->Rect(8, 59, 75, 7, 'F');
            $pdf->Text(12,64,utf8_decode('DETALLES SERVICIOS FACTURADOS') ,0,0,'C');
            $pdf->Rect(100, 59, 80, 7, 'F');
            $pdf->Text(140,64,utf8_decode('DATOS DE MEDICIÓN') ,0,0,'C');
            $pdf->SetTextColor(18,48,108);
            $pdf->Text(12,70,utf8_decode('Concepto         | Cantidad | Precio | Importe') ,0,0,'C');
            $pdf->Text(100,70,'Lectura anterior');
            $pdf->Text(100,75,'Fecha lec. anterior');
            $pdf->Text(100,80,'Serial');
            $pdf->Text(100,85,'Calibre');
            $pdf->Text(100,90,utf8_decode('Tipo Cálculo'));
            $pdf->Text(147,70,'Lectura actual');
            $pdf->Text(147,75,'Fecha lec. actual');
            $pdf->Text(147,80,'Consumo facturado');
            $pdf->Text(147,85,'Marca');

            $pdf->SetTextColor(0,0,0);

            $l=new facturas();
            $stid=$l->datosLecturaPdf($codinm, $periodo);
            $posx = 128;
            $posb = 131;
            while (oci_fetch($stid)) {
                $lecturas   = oci_result($stid, 'LECTURA_ACTUAL');
                $fechas_lec = oci_result($stid, 'FECLEC');
                if($lecturas == '') $lecturas = 0;
                $pdf->SetFont('Arial','',9);
                $pdf->Text($posx,70,$lecturas);
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
            $pdf->Text(181,80,$consumo);
            $pdf->Text(115,80,$serial);
            $pdf->Text(115,85,$calmed);
            if ($marmed == "") $marmed = "Sin Medidor";
            if ($marmed != "Sin Medidor") $pdf->Text(122,90,'Diferencia Lectura');
            $pdf->Text(160,85,$marmed);


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
            $pdf->SetFont('Arial','B',10);
            $pdf->SetTextColor(255,255,255);
            $pdf->Rect(100, 92, 80, 7, 'F');
            $pdf->Text(140,97,utf8_decode('DATOS DEL SERVICIO') ,0,0,'C');

            $pdf->SetTextColor(18,48,108);
            $pdf->Text(104,103,utf8_decode('Código de proceso'));
            $pdf->Text(104,108,utf8_decode('Código de inmueble'));
            $pdf->Text(104,112,'Uso');
            $pdf->Text(104,117,utf8_decode('Unidades'));
            $pdf->Text(104,122,'Tarifa');
            $pdf->Text(144,112,utf8_decode('Cupo básico(m3)'));
            $pdf->Text(144,117,'Suministro');
            $pdf->Text(144,122,'Estrato');

            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial','',9);
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

            $pdf->SetFont('Arial','B',10);
            $pdf->SetTextColor(255,255,255);
            $pdf->Rect(100, 125, 40, 7, 'F');
            $pdf->Text(115,130,utf8_decode('CICLO') ,0,0,'C');

            $pdf->Rect(141, 125, 40, 7, 'F');
            $pdf->Text(142,130,utf8_decode('FECHA VENCIMIENTO') ,0,0,'C');


            $pdf->Rect(100, 137, 80, 7, 'F');
            $pdf->Text(168,142,utf8_decode('AVISO') ,0,0,'C');

            $pdf->Rect(100, 197, 80, 7, 'F');
            $pdf->Text(167,202,utf8_decode('NOTAS') ,0,0,'C');


            $pdf->Rect(8, 137, 75, 7, 'F');
            $pdf->Text(45,142,utf8_decode('OTROS CONCEPTOS') ,0,0,'C');

            $pdf->Rect(8, 210, 75, 7, 'F');
            $pdf->Text(35,215,utf8_decode('INFORMACIÓN ADICIONAL') ,0,0,'C');

            $pdf->SetDrawColor(18,48,108);
            $pdf->SetTextColor(18,48,108);
            $pdf->Rect(10, 129, 7, 7, 'S');
            $pdf->Text(12,134,utf8_decode('A    TOTAL SERVICIO') ,0,0,'C');
            $pdf->Rect(10, 191, 7, 7, 'S');
            $pdf->Text(12,196,utf8_decode('B    TOTAL OTROS CONCEPTOS') ,0,0,'C');

            $pdf->Rect(68, 201, 7, 7, 'S');
            $pdf->Rect(58, 201, 7, 7, 'S');
            $pdf->Text(10,206,utf8_decode('TOTAL FACTURA DEL MES    A   +   B') ,0,0,'C');
            $pdf->Text(140,135,utf8_decode('|') ,0,0,'C');

            $pdf->Text(10,221,utf8_decode('Diferido') ,0,0,'C');
            $pdf->Text(10,226,utf8_decode('Fecha último pago') ,0,0,'C');
            $pdf->Text(10,231,utf8_decode('Importe último pago') ,0,0,'C');

            $pdf->SetFillColor(220,224,233);
            $pdf->Rect(2, 244,104,53, 'F');
            $pdf->Image('../../images/logoCasdFacFondAzulC.jpg',2,245,70);
            $pdf->SetFillColor(254,225,221);
            $pdf->Rect(106, 244,102,53, 'F');
            $pdf->Image('../../images/logoCasdFondRoj.jpg',106,245,70);

            $pdf->SetTextColor(18,48,108);
            $pdf->SetFont('Arial','B',8.5);
            $pdf->Text(6,258,utf8_decode('Nombre') ,0,0,'C');
            $pdf->Text(6,263,utf8_decode('Código de sistema') ,0,0,'C');
            $pdf->Text(6,267,utf8_decode('Código de proceso') ,0,0,'C');
            $pdf->Text(6,272,utf8_decode('Fecha de emisión') ,0,0,'C');
            $pdf->Text(6,277,utf8_decode('Factura No.') ,0,0,'C');
            $pdf->Text(6,281,utf8_decode('Periodo') ,0,0,'C');
            $pdf->Text(6,285,utf8_decode('Vencimiento') ,0,0,'C');
            $pdf->Text(6,292,utf8_decode('Sello y firma') ,0,0,'C');

            $pdf->Text(109,258,utf8_decode('Nombre') ,0,0,'C');
            $pdf->Text(109,262,utf8_decode('Código de sistema') ,0,0,'C');
            $pdf->Text(109,266,utf8_decode('Código de proceso') ,0,0,'C');
            $pdf->SetFont('Arial','B',6);
            $pdf->Text(55,267,utf8_decode('Favor no colocar sellos sobre el código de barras') ,0,0,'C');
            $pdf->Text(157,267,utf8_decode('Favor no colocar sellos sobre el código de barras') ,0,0,'C');

            $pdf->SetFont('Arial','B',12);
            $pdf->Text(70,286,utf8_decode('Total factura') ,0,0,'C');
            $pdf->Text(157,286,utf8_decode('Total deuda anterior') ,0,0,'C');
            $pdf->SetFont('Arial','B',20);
            $pdf->Text(8,243,utf8_decode('FACTURA DEL MES') ,0,0,'C');
            $pdf->SetTextColor(240,0,0);
            $pdf->Text(106,243,utf8_decode('DEUDA ANTERIOR') ,0,0,'C');
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial','',9);


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
            $pdf->Text(77,206,round($totalfactura));
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
        }
        else{
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

            if ($marmed == "") $marmed = "Sin Medidor";

            $posy = 61;
            $l=new facturas();
            $stid=$l->datosServiciosPdf($codinm);
            while (oci_fetch($stid)) {
                $servicio=oci_result($stid, 'DESC_SERVICIO');
                $uso=oci_result($stid, 'DESC_USO');
                $tarifa=oci_result($stid, 'CODIGO_TARIFA');
                $unidades=oci_result($stid, 'UNIDADES_TOT');
                $operaCorte = oci_result($stid, 'OPERA_CORTE');
                if($servicio == 'Agua' || $servicio == 'Agua de Pozo' || $servicio == 'Alcantarillado Red' || $servicio == 'Alcantarillado Pozo'){
                    $pdf->SetFont('Arial','B',8);
                    $pdf->Text(112,$posy,$servicio);
                    $pdf->Text(141,$posy,$uso);
                    $pdf->Text(168,$posy,$tarifa);
                    $pdf->Text(195,$posy,$unidades);
                    $posy = $posy+4;
                }
            }oci_free_statement($stid);

            $l=new  facturas();
            $stid=$l->datosConsumoFactPdf($factura);
            while (oci_fetch($stid)) {
                $consumo=oci_result($stid, 'CONSUMO');
            }oci_free_statement($stid);

            $l=new facturas();
            $stid=$l->datosLecturaPdf($codinm, $periodo);
            $posy = 89;
            while (oci_fetch($stid)) {
                $lecturas   = oci_result($stid, 'LECTURA_ACTUAL');
                $fechas_lec = oci_result($stid, 'FECLEC');
                $pdf->SetFont('Arial','',9);
                $pdf->Text(73,$posy,$fechas_lec);
                $pdf->Text(93,$posy,$lecturas);
                $posy      = $posy+5;
            }oci_free_statement($stid);

            $mensaje_valor_fiscal_factura="";
            //CABECERA FACTURA
            if($acueducto == 'SD'){
                $pdf->Image('../../images/logo_caasd.jpg',5,1,25);
                $mensaje_valor_fiscal_factura=
                    " Si necesita que su factura tenga valor fiscal favor de solicitarlo enviándonos un correo a catastro@caasdoriental.com.";
            } else{
                $pdf->Image('../../images/coraabo.jpg',11,5,75,20);
                //$pdf->Image('../../images/logo_coraabo_2.png',11,5,75,20);
                $mensaje_valor_fiscal_factura=
                    " Si necesita que su factura tenga valor fiscal favor de solicitarlo enviándonos un correo a soporte@coraaboenlinea.com";
            }
            if($acueducto == 'SD'){
                $pdf->SetFont('times','B',31);
                $pdf->Text(32,17,'C');
                $pdf->Text(42,17,'A');
                $pdf->SetFont('times','B',37);
                $pdf->Text(52,17,'A');
                $pdf->SetFont('times','B',31);
                $pdf->Text(64,17,'S');
                $pdf->Text(73,17,'D');
            }
            $pdf->SetFont('times','',6);
            if($acueducto == 'SD')
            {

                $pdf->Text(12,28,'CORPORACION DEL ACUEDUCTO Y ALCANTARILLADO DE SANTO DOMINGO');
                $a= new Parametro();
                $std2=$a->getParametroByNomPar('RNC_CAASD');
                oci_fetch($std2);
                $rcn=oci_result($std2, 'VAL_PARAMETRO');
                $pdf->SetFont('times','B',8);
                $pdf->Text(12,32,'RNC ' .$rcn);
                $pdf->SetFont('times','B',6);

            }
            else

            {

                $pdf->Text(12,27,'CORPORACION DEL ACUEDUCTO Y ALCANTARILLADO DE BOCA CHICA');
                $a= new Parametro();
                $std2=$a->getParametroByNomPar('RNC_CORAABO');
                oci_fetch($std2);
                $rcn=oci_result($std2, 'VAL_PARAMETRO');
                $pdf->SetFont('times','B',8);
                $pdf->Text(12,32,'RNC ' .$rcn);
                $pdf->SetFont('times','B',6);
            }
            /*$pdf->SetFont('Arial','B',10);
            $pdf->Text(10,15,utf8_decode('Código Sistema'));
            $pdf->SetFont('Arial','B',16);
            $pdf->Text(10,25,$codinm);*/
            $pdf->SetFont('Arial','B',10);
            $pdf->Text(12,36,utf8_decode('FECHA DE EMISION '),0,0,'L');
            $pdf->SetFont('Arial','B',10);
            $pdf->Text(53,36,$fecexp,0,0,'C');
            $pdf->SetFont('Arial','B',11);


            $pdf->SetFont('Arial','B',10);
            $pdf->Text(137,35,$msjncf);

            $pdf->SetFont('Arial','B',10);
            $pdf->Text(137,40,'NCF: '.$ncffac);
            $cabeceraNCF= substr($ncffac,0,3);

            //Este bloque fue modificado por una solicitud de Camilo para mostrar el RNC del inmueble 1048710
            /* if ($cabeceraNCF=='B02') {
                 $tipodoc = '';
                 $documento = '';

             }*/
            if($tipodoc!="Cédula"){
                $pdf->Text(15,44,utf8_decode($tipodoc).' '.$documento);
            }

            $pdf->SetFont('Arial','B',10);
            if(trim($fecha_ncf)<>''){
                $pdf->Text(137,45,'Vencimiento DEL NCF: '.$fecha_ncf);
            }


            $pdf->Ln(38);
            //$pdf->Rect(10,35,190,15);
            $pdf->SetFont('Arial','B',11);
            $pdf->Text(15,49,utf8_decode(substr($alias,0,31)),0,0,'L');
            $pdf->Text(15,53,utf8_decode($direccion),0,0,'C');
            $pdf->Text(15,57,utf8_decode($urbaniza),0,0,'C');

            $pdf->SetFont('Arial','B',10);
            $pdf->Text(15,64,utf8_decode('Código Sistema'));
            $pdf->SetFont('Arial','B',10);
            $pdf->Text(87,64,$codinm);
            $pdf->Ln(13);
            $pdf->SetFont('Arial','B',10);
            $pdf->Text(15,68,utf8_decode('Código de Inmueble'));
            $pdf->Text(66,68,$catastro);



            // CUERPO DE LA FACTURA
            // SECCION SERVICIOS Y DATOS DE FACTURA
            $pdf->SetFont('Arial','B',8);
            $pdf->SetTextColor(255,255,255);

            $pdf->Ln(-10);
            $pdf->Cell(97,7,'',0,0,'C',false);
            $pdf->SetFillColor(0, 126, 192);
            $pdf->Cell(93,4,'SERVICIO                        USO                      TARIFA           UNIDADES',0,0,'R',true);
            /*$pdf->Cell(4,7,'',0,0,'C',false);
            $pdf->Cell(93,6,'DATOS DE LA FACTURA',1,0,'C',true);
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(7);
            $pdf->Cell(93,7,'',0,0,'C');
            $pdf->Cell(4,7,'',0,0,'C');
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(28,5,utf8_decode('Factura Número:'),0,0,'L');
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(27,5,$factura,0,0,'C');
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(19,5,'Ciclo:',0,0,'L');
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(19,5,$zona,0,0,'L');
            $pdf->Ln(7);
            $pdf->Cell(93,7,'',0,0,'C');
            $pdf->Cell(4,7,'',0,0,'C');

            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(19,5,'Periodo:',0,0,'L');
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(19,5,$mes.'/'.$agno,0,0,'L');*/
            $pdf->Ln(22);
            $pdf->SetFont('Arial','B',9);

            //SECCION DATOS MEDIDOR, DATOS MEDICION E INFORMACION ADICIONAL
            $pdf->SetFont('Arial','B',8);

            $pdf->Cell(40,5,'DATOS DEL MEDIDOR',1,0,'C',true);
            $pdf->SetFillColor(0, 128, 192);
            $pdf->Cell(53,5,'DATOS DE MEDICION',1,0,'C',true);
            $pdf->Cell(4,2,'',0,0,'C');
            $pdf->Cell(92,5,'DATOS DE LA FACTURA',1,0,'C',true);
            $pdf->Ln(5);
            $pdf->Cell(40,25,'',1,0,'C');
            $pdf->Cell(53,25,'',1,0,'C');
            $pdf->Cell(4,25,'',0,0,'C');
            $pdf->Cell(92,25,'',1,0,'C');
            $pdf->SetFont('Arial','',9);
            $pdf->SetTextColor(0,0,0);
            $pdf->Text(12,84,'Marca:');
            $pdf->Text(12,89,'Calibre:');
            $pdf->Text(12,94,'Serial:');
            $pdf->SetFont('Arial','B',9);
            $pdf->Text(25,84,$marmed);
            $pdf->Text(25,89,$calmed);
            $pdf->Text(25,94,$serial);

            $pdf->SetFont('Arial','',8);
            $pdf->Text(76,82,'Fecha');
            $pdf->Text(92,82,'Lectura');
            $pdf->Line(50,83,103,83);
            $pdf->Text(52,89,'ANTERIOR');
            $pdf->Text(52,93,'ACTUAL');
            $pdf->Text(52,97,'CONSUMO FACTURADO');
            $pdf->Text(52,101,'OBS:');
            $pdf->SetFont('Arial','B',9);
            if ($marmed != "Sin Medidor") $pdf->Text(75,101,'Diferencia Lectura');
            // else $pdf->Text(62,102,'Diferencia Lectura');

            $pdf->Text(95,97,$consumo);
            $pdf->SetFont('Arial','',9);
            if($saldofavor == '') $saldofavor = 0;
            if($difdeuda == '') $difdeuda = 0;
            $pdf->Text(109,83,utf8_decode('Factura número:'));
            $pdf->Text(109,88,utf8_decode('Fecha de emisión:'));
            $pdf->Text(164,83,utf8_decode('Ciclo:'));
            $pdf->Text(164,88,utf8_decode('Período:'));
            $pdf->Text(109,95,'Pendiente Saldos a Favor:');
            $pdf->Text(109,100,'Pendiente Diferidos:');
            $pdf->SetFont('Arial','B',10);
            $pdf->Text(141,83,$factura);
            $pdf->Text(139,88,$fecexp);

            $pdf->Text(189,83,$zona);
            $pdf->Text(181,88,$mes."/".$agno);


            $pdf->Text(154,95,$saldofavor);
            $pdf->Text(154,100,$difdeuda);

            $pdf->Ln(25);
            $pdf->SetFont('Arial','B',8);
            $pdf->SetTextColor(255,255,255);
            $pdf->SetFillColor(0, 128, 192);

            //SECCION DETALLE SERVICIOS DOMICILIARIOS Y OTROS CONCEPTOS
            $pdf->Cell(93,6,'DETALLE SERVICIOS DOMICILIARIOS',1,0,'C',true);
            $pdf->Cell(4,6,'',0,0,'C');
            $pdf->Cell(92,6,'OTROS CONCEPTOS',1,0,'C',true);

            $pdf->Ln(5);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial','',9);

            $pdf->Cell(26,6,'Servicios',0,0,'C');
            $pdf->Cell(29,6,'Cantidad',0,0,'C');
            $pdf->Cell(20,6,'Precio',0,0,'C');
            $pdf->Cell(17,6,'Importe',0,0,'C');
            $pdf->Line(10,113,103,113);
            $pdf->Cell(4.8,6,'',0,0,'C');
            $pdf->Cell(70,6,'Concepto',0,0,'L');
            $pdf->Cell(32,6,'Importe',0,0,'L');
            $pdf->Line(107,113,199,113);
            $pxotrocon = 121;
            $pxcon = 6;
            $conceptoini = '';
            $l=new facturas();
            $stid=$l->detalleFacturaPdf ($factura);
            $pdf->Ln(7);
            $mueveadicion = 0;
            while (oci_fetch($stid)) {
                $concepto=oci_result($stid, 'CONCEPTO');
                $rango=oci_result($stid, 'RANGO');
                $unidades=oci_result($stid, 'UNIDADES');
                $valor=oci_result($stid, 'VALOR');
                $codservicio=oci_result($stid, 'COD_SERVICIO');
                $pdf->SetFont('Arial','B',9);

                if ($concepto != $conceptoini && ($concepto == 'Agua' || $concepto == 'Agua de Pozo')){
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Cell(1,6,'',0,0,'R');
                    $pdf->Cell(30,6,$concepto,0,0,'L');
                    $conceptoini = $concepto;
                }

                if ($concepto == 'Agua' || $concepto == 'Agua de Pozo'){
                    $pdf->SetFont('Arial','',9);

                    $totalservicios += $valor;
                    if($rango == 0){
                        $pdf->Ln(4);
                        $pdf->Cell(3,3,'',0,0,'L');
                        $pdf->Cell(35,5,'Consumo Basico',0,0,'L');
                        $pdf->Cell(10,5,$unidades,0,0,'R');
                        if($unidades>0){
                            if($acueducto == 'SD') $valor_mts = round(($valor/$unidades),2);
                            if($acueducto == 'BC') $valor_mts = round(($valor/$unidades),2);
                        }else{
                            $valor_mts=0;
                        }

                        if($unidades > 0)
                            $pdf->Cell(20,5,$valor_mts,0,0,'R');
                        else
                            $pdf->Cell(20,5,0,0,0,'R');
                        $pdf->Cell(20,5,$valor,0,0,'R');
                    }

                    if($rango == 1){
                        $pxr1 = 3;
                        $pdf->Ln($pxr1);
                        $pdf->Cell(3,3,'',0,0,'L');
                        $pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L');
                        $pdf->Cell(10,5,$unidades,0,0,'R');
                        if($unidades==0){
                            $valor_mts=0;
                        }else{
                            if($acueducto == 'SD') $valor_mts = round(($valor/$unidades),2);
                            if($acueducto == 'BC') $valor_mts = round(($valor/$unidades),2);
                        }


                        if($unidades > 0)
                            $pdf->Cell(20,5,$valor_mts,0,0,'R');
                        else
                            $pdf->Cell(20,5,0,0,0,'R');
                        $pdf->Cell(20,5,$valor,0,0,'R');
                    }

                    if($rango == 2){
                        $pxr2 = 3;
                        $pdf->Ln($pxr2);
                        $pdf->Cell(3,3,'',0,0,'L');
                        $pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L');
                        $pdf->Cell(10,5,$unidades,0,0,'R');


                        if($unidades > 0){
                            if($acueducto == 'SD') $valor_mts = round(($valor/$unidades),2);
                            if($acueducto == 'BC') $valor_mts = round(($valor/$unidades),2);
                            $pdf->Cell(20,5,$valor_mts,0,0,'R');
                        }

                        else
                            $pdf->Cell(20,5,0,0,0,'R');
                        $pdf->Cell(20,5,$valor,0,0,'R');
                    }

                    if($rango == 3){
                        $pxr3 = 3;
                        $pdf->Ln($pxr3);
                        $pdf->Cell(3,3,'',0,0,'L');
                        $pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L');
                        $pdf->Cell(10,5,$unidades,0,0,'R');
                        if($acueducto == 'SD') $valor_mts = round(($valor/$unidades),2);
                        if($acueducto == 'BC') $valor_mts = round(($valor/$unidades),2);
                        if($unidades > 0)
                            $pdf->Cell(20,5,$valor_mts,0,0,'R');
                        else
                            $pdf->Cell(20,5,0,0,0,'R');
                        $pdf->Cell(20,5,$valor,0,0,'R');
                    }

                    if($rango == 4){
                        $pxr4 = 3;
                        $pdf->Ln($pxr4);
                        $pdf->Cell(3,3,'',0,0,'L');
                        $pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L');
                        $pdf->Cell(10,5,$unidades,0,0,'R');
                        if($acueducto == 'SD') $valor_mts = round(($valor/$unidades),2);
                        if($acueducto == 'BC') $valor_mts = round(($valor/$unidades),2);
                        if($unidades > 0)
                            $pdf->Cell(20,5,$valor_mts,0,0,'R');
                        else
                            $pdf->Cell(20,5,0,0,0,'R');
                        $pdf->Cell(20,5,$valor,0,0,'R');
                    }

                    if($rango == 5){
                        $pxr5 = 3;
                        $pdf->Ln($pxr5);
                        $pdf->Cell(3,3,'',0,0,'L');
                        $pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L');
                        $pdf->Cell(10,5,$unidades,0,0,'R');
                        if($acueducto == 'SD') $valor_mts = round(($valor/$unidades),2);
                        if($acueducto == 'BC') $valor_mts = round(($valor/$unidades),2);
                        if($unidades > 0)
                            $pdf->Cell(20,5,$valor_mts,0,0,'R');
                        else
                            $pdf->Cell(20,5,0,0,0,'R');
                        $pdf->Cell(20,5,$valor,0,0,'R');
                    }
                }
                if ($concepto != $conceptoini && ($concepto == 'Alcantarillado Red' || $concepto == 'Alcantarillado Pozo')){
                    $pdf->SetTextColor(0,0,0);
                    $pdf->SetFont('Arial','B',9);
                    $pxcalc = 5;
                    $pdf->Ln($pxcalc);
                    $pdf->Cell(1,6,'',0,0,'R');
                    $pdf->Cell(30,6,$concepto,0,0,'R');
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
                        $pdf->Cell(3,3,'',0,0,'L');
                        if($rango == 0){
                            $mueveadicion += 5;
                            $pdf->Cell(35,5,'Consumo Basico',0,0,'L');
                        }
                        if($rango == 1){
                            $pdf->Cell(35,5,'Consumo Adicional 1',0,0,'L');
                            $mueveadicion += 5;
                        }
                        if($rango == 2){
                            $pdf->Cell(35,5,'Consumo Adicional 2',0,0,'L');
                            $mueveadicion += 5;
                        }
                        if($rango == 3){
                            $pdf->Cell(35,5,'Consumo Adicional 3',0,0,'L');
                            $mueveadicion += 5;
                        }
                        if($rango == 4){
                            $pdf->Cell(35,5,'Consumo Adicional 4',0,0,'L');
                            $mueveadicion += 5;
                        }
                        $pdf->Cell(10,5,$unidades,0,0,'R');
                        if($unidades > 0)
                            $pdf->Cell(20,5,round(($valor/$unidades),1),0,0,'R');
                        else
                            $pdf->Cell(20,5,0,0,0,'R');
                        $pdf->Cell(20,5,$valor,0,0,'R');
                        $totalservicios += $valor;

                        // $l->ref($consAFacturado,$unidades);
                    }
                }

                if ($concepto != $conceptoini && ($concepto != 'Alcantarillado Red' && $concepto != 'Alcantarillado Pozo' && $concepto != 'Agua' && $concepto != 'Agua de Pozo')){
                    $pdf->SetFont('Arial','B',9);
                    $pdf->Text(110,$pxotrocon,$concepto);
                    $pdf->Text(187,$pxotrocon,round($valor));
                    $pxotrocon = $pxotrocon + 5;
                    $conceptoini = $concepto;
                    $totalotrosconceptos += $valor;
                }

            }oci_free_statement($stid);



            if($mueveadicion == 0) $mueveadicion = 45;
            if($mueveadicion == 5) $mueveadicion = 44;
            if($mueveadicion == 10) $mueveadicion = 34;
            if($mueveadicion == 15) $mueveadicion = 31;
            if($mueveadicion == 20) $mueveadicion = 28;

            $pdf->Ln($mueveadicion - $pxr1 - $pxr2 - $pxr3 - $pxr4 - $pxr5 - $pxcalc - $paxalcde);
            $pdf->SetFont('Arial','B',9);
            $pdf->SetTextColor(255,255,255);
            $pdf->Cell(72.6,6,'            TOTAL SERVICIOS',0,0,'L',true);
            $pdf->Image('../../images/A.jpg',11,164,8);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(18,6,round($totalservicios),0,0,'R',true);
            $pdf->Cell(2,6,'',0,0,'R',true);
            $pdf->Cell(4.8,6,'',0,0,'C');
            $pdf->SetTextColor(255,255,255);
            $pdf->Cell(72.6,6,'            TOTAL OTROS CONCEPTOS',0,0,'L',true);
            $pdf->Image('../../images/B.jpg',108,164,8);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(13,6,round($totalotrosconceptos),0,0,'R',true);
            $pdf->Cell(6,6,'',0,0,'R',true);
            $pdf->Rect(10,109,93,61);
            $pdf->Rect(107,109,92,61);

            //NOTAS Y TOTAL DE LA FACTURA MENSUAL

            $pdf->Rect(9.8,173,93,24);
            $pdf->Rect(107,173,92,24);
            $pdf->SetTextColor(0,0,0);
            if($tipoCliente!="GC" && $operaCorte!="N"){

                $pdf->SetFont('Arial','B',9);
                $pdf->Text(50,183,'Notas');
                $pdf->SetFont('Arial','B',11);
                $pdf->Text(48.5,188,'AVISO');
                $pdf->SetFont('Arial','B',9);
                $pdf->Text(28,193,'Fecha de corte de servicio '.$fecorte);
            }

            $pdf->SetFont('Arial','B',11);
            $totalfactura = round($totalservicios + $totalotrosconceptos);
            $pdf->Text(108,179,'TOTAL FACTURA MENSUAL');
            $pdf->Text(128,184,'( A + B )');
            $pdf->Text(109,192,'Vencimiento:');
            $pdf->Text(173,192,$fecvcto);

            $pdf->Ln(9.3);
            $pdf->Cell(97.3,18.7,'',0,0,'C');
            $pdf->Cell(56,18.7,'',0,0,'C');
            $pdf->Cell(35.4,12,'',0,0,'C',true);
            $pdf->Line(107,186,199,186);
            $pdf->SetFont('Arial','B',12);
            $pdf->Text(174,182,$totalfactura);

            //CUADRO MENSAJES DE FACTURA
            $pdf->SetDrawColor(209, 58, 58);
            $pdf->SetLineWidth(1);
            $pdf->Rect(9.8,203,189.2,40);
            $pdf->SetFont('Arial','',11);

            $pdf->Ln(33);
            $pdf->Cell(189,12,$msjncf,0,0,'C');

            //$pdf->Text(11,216,$msjperiodo);
            $msjperiodopartes = explode('*',$msjperiodo);
            $pdf->SetFont('Arial','B',12);
            $parte1 = $msjperiodopartes[0];
            $parte2 = $msjperiodopartes[1];
            $parte3 = $msjperiodopartes[2];
            $parte4 = $msjperiodopartes[3];
            $parte5 = $msjperiodopartes[4];
            $parte6 = $msjperiodopartes[5];
            $parte7 = $msjperiodopartes[6];
            $parte8 = $msjperiodopartes[7];
            $parte9 = $msjperiodopartes[8];
            $parte10 = $msjperiodopartes[10];

            $subparte1 =explode(':',$parte1);
            $subparte1a =explode('Evite',$parte1);
            $subparte1b =explode(" ",$parte1);
            //MENSAJE DE DEUDA Y ADVERTENCIA
            $pdf->Ln(7);
            if($subparte1[1] == '' && $subparte1a[1] == '' && $parte1!=""){
                $pdf->Cell(189,5,$parte1,0,0,'C');
            }
            if($subparte1[1] == '' && $subparte1a[1] != ''){
                $pdf->Cell(189,5,$subparte1a[0],0,0,'C');
                $pdf->Ln(5);
                $pdf->Cell(189,5,'Evite'.$subparte1a[1],0,0,'C');
                $pdf->Ln(10);
            }
            if($subparte1[1] != '' && $subparte1a[1] == ''){


                $pdf->Cell(189,5,$subparte1[0],0,0,'C');
                $pdf->Ln(5);
                $pdf->SetFillColor(0, 126, 192);
                $pdf->MultiCell(185,10,utf8_decode($subparte1[1]),0);
            }
            $pdf->SetFont('Arial','B',12);

            $pdf->Ln(6);
            $pdf->MultiCell(187,5,utf8_decode($mensaje_valor_fiscal_factura),0);


            //opcion de pago A y C
            $pdf->Ln(5);
            //$pdf->SetFont('Arial','',9);
            $pdf->Cell(12,5,'',0,0,'L');
            $subparte3 =explode('rebaja',$parte3);
            if($subparte3[0] != '') $mensual = 'rebaja';
            $pdf->Cell(30,5,$parte2.' '.$subparte3[0].$mensual,0,0,'L');
            $pdf->Cell(60,5,'',0,0,'L');
            $subparte7 =explode('mensuales',$parte7);
            if($subparte7[0] != '') $mensual = 'mensuales';
            $pdf->Cell(30,5,$parte6.' '.$subparte7[0].$mensual,0,0,'L');
            $pdf->Ln(5);
            $pdf->Cell(11,5,'',0,0,'L');
            $pdf->Cell(30,5,$subparte3[1],0,0,'L');
            $pdf->Cell(60,5,'',0,0,'L');
            $pdf->Cell(30,5,$subparte7[1],0,0,'L');

            //opcion de pago B y D
            $pdf->Ln(5);
            $pdf->SetFont('Arial','',9);
            $pdf->Cell(12,5,'',0,0,'L');
            $subparte5 =explode('mensuales',$parte5);
            if($subparte5[0] != '') $mensual = 'mensuales';
            $pdf->Cell(30,5,$parte4.' '.$subparte5[0].$mensual,0,0,'L');
            $pdf->Cell(60,5,'',0,0,'L');
            $subparte9 =explode('mensuales',$parte9);
            if($subparte9[0] != '') $mensual = 'mensuales';
            $pdf->Cell(30,5,$parte8.' '.$subparte9[0].$mensual,0,0,'L');
            $pdf->Ln(5);
            $pdf->Cell(11,5,'',0,0,'L');
            $pdf->Cell(30,5,$subparte5[1],0,0,'L');
            $pdf->Cell(60,5,'',0,0,'L');
            $pdf->Cell(30,5,$subparte9[1],0,0,'L');

            $pdf->Ln(8);
            if($parte10 != '') $asterisco = '*';
            $pdf->Cell(189,5,$asterisco.' '.$parte10,0,0,'C');
            //TALON DE FACTURA
            if($acueducto == 'SD')
                $pdf->Image('../../images/logo_caasd.jpg',6,250,25);
            else $pdf->Image('../../images/coraabo.jpg',11,250,20);
            //$pdf->SetTextColor(0,0,0);
            $pdf->SetFont('Arial','',10);
            $pdf->Text(13,277,'TALON');
            $pdf->Text(16.5,282,'DE');
            $pdf->Text(14,287,'PAGO');
            $pdf->Image('../../temp/'.$factura.".gif",33,263,60,8); //Aquí iría el codigo QR
            /*if($acueducto == 'SD')
                $pdf->Image('../../images/codigoQRHistoricoFacturasCAASD.jpg',50,260,25,25); //Aquí iría el codigo QR
            else
                $pdf->Image('../../images/codigoQRHistoricoFacturasCORAABO.jpg',50,260,25,25); //Aquí iría el codigo QR*/
            $pdf->Image('../../images/rec.jpg',109.3,250,90,20);
            $pdf->Ln(3);
            $pdf->SetFont('Arial','B',7);
            $pdf->Cell(17,5,'',0,0,'L');
            $pdf->Text(29,253,'FAVOR NO COLOCAR SELLOS SOBRE EL CODIGO DE BARRAS');
            //$pdf->Text(29,253,utf8_decode('Escanee el código QR para visualizar sus facturas.'));
            $pdf->SetFont('Arial','',8);
            $pdf->Text(111,255,utf8_decode('Factura Número:'));
            $pdf->Text(111,259,'Periodo:');
            $pdf->Text(111,263,utf8_decode('Código Inmueble:'));
            $pdf->Text(111,267,utf8_decode('Código Proceso:'));
            $pdf->Text(154,255,'Fecha de Exp:');
            $pdf->Text(154,259,utf8_decode('Código Sistema:'));

            $pdf->SetFont('Arial','B',8);
            $pdf->Text(137,255,$factura);
            $pdf->Text(137,259,$mes."/".$agno);
            $pdf->Text(137,263,$catastro);
            $pdf->Text(137,267,$proceso);
            $pdf->Text(179,255,$fecexp);
            $pdf->Text(179,259,$codinm);
            //$pdf->Text(49,276,"*".$factura."*");

            $pdf->SetFont('Arial','B',10);
            $pdf->Text(33,259,'Estafeta:');
            $pdf->Text(62,259,'Fecha de Pago:');

            $pdf->Text(78,290,utf8_decode($alias));
            $pdf->Text(178,289,utf8_decode($urbaniza),0,0,'C');

            $pdf->Text(40,290,$codinm);
            $pdf->SetFont('Arial','',9);
            $pdf->Text(110,275,'Sello y Firma:');
            $pdf->Text(35,293,utf8_decode("Código Sistema"));

            $pdf->Text(138,278,'TOTAL FACTURA:');
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetLineWidth(0);
            $pdf->Rect(168,273,31,7,true);
            $pdf->SetFont('Arial','B',9);
            $pdf->Text(174,278,'RD$ '.$totalfactura);
            $pdf->Rect(136,273,63,12);
            $pdf->SetFont('Arial','B',9);
            $pdf->Text(139,283,'Vencimiento:');
            $pdf->Text(180,283,$fecvcto);
            $pdf->Text(110,277,$gerencia);
        }

        $pdf->Output("facturas_digital/$factura.pdf",'F');

        if($acueducto == 'SD'){
            $empresa = utf8_decode('Corporación del Acueducto y Alcantarillado de Santo Domingo CAASD');
            $mensaje .= utf8_decode('<font size="4" face="Helvetica"><b>Estimado(a): '.$alias.'</font></b><br><br>');
            $mensaje .= utf8_decode('<font size="4" face="Helvetica">Anexo a este correo estamos enviando un archivo con un duplicado de tu factura de agua potable y alcantarillado del mes de '.$messi.', correspondiente al inmueble <b>'. $codinm.'</b></font>');
            $mensaje .= utf8_decode('<font size="4" face="Helvetica">, la cual puedes <a href="https://caasdenlinea.com.do">Pagar aquí</a>.<br><br>Ahorra tiempo y gestiona tus servicios desde la comodidad de tu hogar u oficina entrando al portal <a href="https://caasdenlinea.com.do/servicios">caasdenlínea</a>, a través del cual podrás:</font><br><br>');
            $mensaje .= utf8_decode('<font size="4" face="Helvetica">*   Descargar y pagar tu factura.<br>*  Consultar tus balances.<br>*    Buzón de quejas y sugerencias.</font><br><br>');
            $mensaje .= utf8_decode('<font size="4" face="Helvetica">En adición, para pagar tu factura puedes llamar al 809-598-1722 (opción 2), visitar cualquiera de nuestras estafetas o puntos de pago.</font>');
            $mensaje .= utf8_decode('<br><br><br><font size="4" face="Helvetica"><b>Corporación del Acueducto y Alcantarillado de Santo Domingo</b></font>');

        }
        if($acueducto == 'BC'){
            $empresa = utf8_decode('Corporación del Acueducto y Alcantarillado de Bocachica CORAABO');
            $mensaje .= utf8_decode('<font size="4" face="Helvetica"><b>Estimado(a): '.$alias.'</font></b><br><br>');
            $mensaje .= utf8_decode('<font size="4" face="Helvetica">Anexo a este correo estamos enviando un archivo con un duplicado de tu factura de agua potable y alcantarillado del mes de '.$messi.', correspondiente al inmueble <b>'. $codinm.'</b></font>');
            $mensaje .= utf8_decode('<font size="4" face="Helvetica">, la cual puedes <a href="https://www.coraaboenlinea.com">Pagar aquí</a>.<br><br>Ahorra tiempo y gestiona tus servicios desde la comodidad de tu hogar u oficina entrando al portal <a href="https://coraaboenlinea.com/servicios">coraaboenlínea</a>, a través del cual podrás:</font><br><br>');
            $mensaje .= utf8_decode('<font size="4" face="Helvetica">*   Descargar y pagar tu factura.<br>*  Consultar tus balances.<br>*    Buzón de quejas y sugerencias.</font><br><br>');
            $mensaje .= utf8_decode('<font size="4" face="Helvetica">En adición, para pagar tu factura puedes llamar al 809-523-6616 (opción 2), visitar cualquiera de nuestras estafetas o puntos de pago.</font>');
            $mensaje .= utf8_decode('<br><br><br><font size="4" face="Helvetica"><b>Corporación de Acueducto y Alcantarillado de Boca Chica</b></font>');
        }
        $url="https://aceasoft.com/facturacion/clases/classFacturaPdf.php?factura=$factura";
        $mensaje .= utf8_decode('<font size="4" face="Helvetica">, Descarga tu factura aqui: <a href='.$url.'>DESCARGAR FACTURA</a></font>');

        $asunto = utf8_decode('Facturacion Electrónica ').$empresa;
       $z=new correo();
        $bandera = $z->enviarcorreo($email,$acueducto,$asunto,$mensaje,$factura);

        if($bandera == false){
            $error='No se pudo enviar el correo';
            $enviado = 'N';
            unlink('../../temp/'.$factura.".gif");
            $e=new Factura();
            $e->datosEnvioFacDig($factura, $per_cierre, $email, $enviado, $error, $acueducto);
        }
        else if($bandera == true){
            $error='Envio satisfactorio';
            $enviado = 'S';
            unlink('../../temp/'.$factura.".gif");
            unlink('facturas_digital/'.$factura.".pdf");
            $e=new factura();
            $e->datosEnvioFacDig($factura, $per_cierre, $email, $enviado, $error, $acueducto);
        }

        sleep(5);

        unset($codinm,$ncffac,$catastro,$alias,$direccion,$urbaniza,$zona,$fecexp,$periodo,$proceso,$gerencia,$marmed,$calmed,$serial,$fecvcto,$fecorte,$envio);
        unset($acueducto,$mensaje,$msjncf,$msjperiodo,$msjfactura,$msjalerta,$msjburo,$saldofavor,$difdeuda,$servicio,$uso,$tarifa,$unidades);
        unset($lecturas,$fechas_lec,$consumo,$concepto,$concepto_ini,$rango,$unidades,$valor,$valor_mts,$paxalcde,$mueveadicion,$codservicio);
        unset($totalservicios,$totalotrosconceptos,$marmed,$calmed,$serial,$posy,$pxr1,$pxr2,$pxr3,$pxr4,$pxr5,$pxcalc);
    }oci_free_statement($datos);
}oci_free_statement($registros);
?>