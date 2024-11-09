<?
include ('../clases/fpdf.php');
include ('../clases/class.facturas.php');
include ('../clases/class.inmuebles.php');
include ('../../clases/class.parametros.php');
require_once ('../../funciones/barcode/barcode.inc.php');
$factura=$_GET['factura'];

new barCodeGenrator($factura,1,'../../temp/'.$factura.'.gif',100,70,0);
$pdf=new FPDF();
$pdf->AddPage();

if($factura != '') {
    $l = new facturas();
    $stid = $l->datosFacturaPdf($factura);
    $codinm ='';
    while (oci_fetch($stid)) {
        $codinm = oci_result($stid, 'CODIGO_INM');
        $tipodoc = oci_result($stid, 'TIPODOC');
        $fecha_ncf = oci_result($stid, 'VENCE_NCF');
        $ncffac = oci_result($stid, 'NCF');
        $catastro = oci_result($stid, 'CATASTRO');
        $alias = oci_result($stid, 'ALIAS');
        $direccion = oci_result($stid, 'DIRECCION');
        $urbaniza = oci_result($stid, 'DESC_URBANIZACION');
        $zona = oci_result($stid, 'ID_ZONA');
        $fecexp = oci_result($stid, 'FECEXP');
        $periodo = oci_result($stid, 'PERIODO');
        $proceso = oci_result($stid, 'ID_PROCESO');
        $gerencia = oci_result($stid, 'GERENCIA');
        $marmed = oci_result($stid, 'DESC_MED');
        $calmed = oci_result($stid, 'DESC_CALIBRE');
        $serial = oci_result($stid, 'SERIAL');
        $fecvcto = oci_result($stid, 'FEC_VCTO');
        $fecorte = oci_result($stid, 'FECCORTE');
        $acueducto = oci_result($stid, 'ID_PROYECTO');
        $msjncf = oci_result($stid, 'MSJ_NCF');
        $msjperiodo = oci_result($stid, 'MSJ_PERIODO');
        $msjfactura = oci_result($stid, 'MSJ_FACTURA');
        $msjalerta = oci_result($stid, 'MSJ_ALERTA');
        $msjburo = oci_result($stid, 'MSJ_BURO');
        $documento = oci_result($stid, 'DOCUMENTO');
    }
    oci_free_statement($stid);

    $s2 = new inmuebles();
    $data = $s2->SaldoFavor($codinm);
    while (oci_fetch($data)) {
        $saldofavor = oci_result($data, 'SALDO');
    }
    oci_free_statement($data);

    $s2 = new inmuebles();
    $data = $s2->DiferidoDeud($codinm);
    while (oci_fetch($data)) {
        $difdeuda = oci_result($data, 'DIFERIDO');
    }
    oci_free_statement($data);

    if ($marmed == "") $marmed = "Sin Medidor";

    $posy = 62;
    $l = new facturas();
    $stid = $l->datosServiciosPdf($codinm);
    while (oci_fetch($stid)) {
        $servicio = oci_result($stid, 'DESC_SERVICIO');
        $uso = oci_result($stid, 'DESC_USO');
        $tarifa = oci_result($stid, 'CODIGO_TARIFA');
        $unidades = oci_result($stid, 'UNIDADES_TOT');
        if ($servicio == 'Agua' || $servicio == 'Agua de Pozo' || $servicio == 'Alcantarillado Red' || $servicio == 'Alcantarillado Pozo') {
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Text(114, ($posy - $posYCabecera), $servicio);
            $pdf->Text(145, ($posy - $posYCabecera), $uso);
            $pdf->Text(168, ($posy - $posYCabecera), $tarifa);
            $pdf->Text(190, ($posy - $posYCabecera), $unidades);
            $posy = $posy + 4;
        }
    }
    oci_free_statement($stid);

    $l = new facturas();
    $stid = $l->datosLecturaPdf($codinm, $periodo);
    $posy = 90;
    while (oci_fetch($stid)) {
        $lecturas = oci_result($stid, 'LECTURA_ACTUAL');
        $fechas_lec = oci_result($stid, 'FECLEC');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(68, $posy, $fechas_lec);
        /* $pdf->Text(90,$posy,$lecturas);*/
        $posy = $posy + 5;
        $consumo = $consumo - $lecturas;
        $consumo = $consumo * (-1);
    }
    oci_free_statement($stid);
    $agno = substr($periodo, 0, 4);
    $mes = substr($periodo, 4, 2);
    if ($mes == '01') {
        $mes = Ene;
    }
    if ($mes == '02') {
        $mes = Feb;
    }
    if ($mes == '03') {
        $mes = Mar;
    }
    if ($mes == '04') {
        $mes = Abr;
    }
    if ($mes == '05') {
        $mes = May;
    }
    if ($mes == '06') {
        $mes = Jun;
    }
    if ($mes == '07') {
        $mes = Jul;
    }
    if ($mes == '08') {
        $mes = Ago;
    }
    if ($mes == '09') {
        $mes = Sep;
    }
    if ($mes == '10') {
        $mes = Oct;
    }
    if ($mes == '11') {
        $mes = Nov;
    }
    if ($mes == '12') {
        $mes = Dic;
    }

    //CABECERA FACTURA
    $posYCabecera = 4; //Determina la posición de Y sumando o restando al valor actual
    $posXCabecera = 8; //Determina la posición de X sumando o restando al valor actual
    if ($acueducto == 'SD') {
        $mensaje_valor_fiscal_factura =
            "Si necesita que su factura tenga valor fiscal favor de solicitarlo al" .
            " 809-598-1722 ext. 249 y 251 o envíenos un e-mail a catastro@caasdoriental.com.";
    } else {
        $mensaje_valor_fiscal_factura =
            "Si necesita que su factura tenga valor fiscal favor de solicitarlo al" .
            " 809-523-6616 ext. 104 y 105.";

    }

    /*   if($acueducto == 'SD')
           $pdf->Image('../../images/logo_caasd.jpg',5,1,25);
       else $pdf->Image('../../images/logo_coraabo.jpg',11,5,75,20);
       if($acueducto == 'SD'){
           $pdf->SetFont('times','B',31);
           $pdf->Text(32,17,'C');
           $pdf->Text(42,17,'A');
           $pdf->SetFont('times','B',37);
           $pdf->Text(52,17,'A');
           $pdf->SetFont('times','B',31);
           $pdf->Text(64,17,'S');
           $pdf->Text(73,17,'D');
       }*/
    /*  $pdf->SetFont('times','',6);
      if($acueducto == 'SD'){
          $pdf->Text(12,28,'CORPORACION DEL ACUEDUCTO Y ALCANTARILLADO DE SANTO DOMINGO');
          $a= new Parametro();
          $std2=$a->getParametroByNomPar('RNC_CAASD');
          oci_fetch($std2);
          $rcn=oci_result($std2, 'VAL_PARAMETRO');
          $pdf->SetFont('times','B',8);
          $pdf->Text(12,32,'RNC ' .$rcn);
          $pdf->SetFont('times','B',6);
      }
      else {
          $pdf->Text(12,27,'CORPORACION DEL ACUEDUCTO Y ALCANTARILLADO DE BOCA CHICA');
          $a= new Parametro();
          $std2=$a->getParametroByNomPar('RNC_CORAABO');
          oci_fetch($std2);
          $rcn=oci_result($std2, 'VAL_PARAMETRO');
          $pdf->SetFont('times','B',8);
          $pdf->Text(12,32,'RNC ' .$rcn);
          $pdf->SetFont('times','B',6);
      }*/
    /*$pdf->SetFont('Arial','B',10);
    $pdf->Text(10,15,utf8_decode('Código Sistema'));
    $pdf->SetFont('Arial','B',16);
    $pdf->Text(10,25,$codinm);*/
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Text((18 - $posXCabecera), (45 - $posYCabecera), utf8_decode('FECHA DE EMISION '), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Text((55 - $posXCabecera), (45 - $posYCabecera), $fecexp, 0, 0, 'C');
    $pdf->SetFont('Arial', 'B', 11);


    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Text(137, 44, $msjncf);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Text(137, 47, 'NCF: ' . $ncffac);
    $cabeceraNCF = substr($ncffac, 0, 3);

    //Este bloque fue modificado por una solicitud de Camilo para mostrar el RNC del inmueble 1048710
    /* if ($cabeceraNCF=='B02') {
         $tipodoc = '';
         $documento = '';

     }*/

    $pdf->Text((18 - $posXCabecera), (52 - $posYCabecera), utf8_decode($tipodoc) . ' ' . $documento);
    $pdf->SetFont('Arial', 'B', 8);
    if (trim($fecha_ncf) <> '') {
        $pdf->Text(137, 50, 'Vencimiento del NCF: ' . $fecha_ncf);
    }


    $pdf->Ln(38);
    //$pdf->Rect(10,35,190,15);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Text((18 - $posXCabecera), (57 - $posYCabecera), utf8_decode(substr($alias, 0, 31)), 0, 0, 'L');
    $pdf->Text((18 - $posXCabecera), (62 - $posYCabecera), utf8_decode($direccion), 0, 0, 'C');
    $pdf->Text((18 - $posXCabecera), (67 - $posYCabecera), utf8_decode($urbaniza), 0, 0, 'C');

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Text((18 - $posXCabecera), (72 - $posYCabecera), utf8_decode('Código Sistema'));
    /*$pdf->SetFont('Arial','B',9);*/
    $pdf->Text((91 - $posXCabecera), (72 - $posYCabecera), $codinm);
    $pdf->Ln(13);
    /*$pdf->SetFont('Arial','B',10);*/
    $pdf->Text((18 - $posXCabecera), (77 - $posYCabecera), utf8_decode('Código de Inmueble'));
    $pdf->Text((72 - $posXCabecera), (77 - $posYCabecera), $catastro);


    // CUERPO DE LA FACTURA
    // SECCION SERVICIOS Y DATOS DE FACTURA

    $posYDatosFactura = 6; //Establece el valor a restar de Y
    // $posXDatosFactura=10; //Establece el valor a restar de X

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetTextColor(255, 255, 255);

    $pdf->Ln(-10);
    $pdf->Cell(97, 7, '', 0, 0, 'C', false);
    $pdf->SetFillColor(0, 126, 192);
    /*    $pdf->Cell(93,4,'SERVICIO                        USO                      TARIFA           UNIDADES',0,0,'R',true);*/
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
    $pdf->SetFont('Arial', 'B', 9);

    //SECCION DATOS MEDIDOR, DATOS MEDICION E INFORMACION ADICIONAL


    $pdf->SetFont('Arial', 'B', 8);

    /* $pdf->Cell(40,5,'DATOS DEL MEDIDOR',1,0,'C',true);*/
    /*$pdf->SetFillColor(0, 128, 192);*/
    /*$pdf->Cell(53,5,'DATOS DE MEDICION',1,0,'C',true);*/
    $pdf->Cell(4, 2, '', 0, 0, 'C');
    /*$pdf->Cell(92,5,'DATOS DE LA FACTURA',1,0,'C',true);*/
    $pdf->Ln(5);
    /*  $pdf->Cell(40,25,'',1,0,'C');
      $pdf->Cell(53,25,'',1,0,'C');
      $pdf->Cell(4,25,'',0,0,'C');
      $pdf->Cell(92,25,'',1,0,'C');*/
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetTextColor(0, 0, 0);
    /*$pdf->Text(12,84,'Marca:');
    $pdf->Text(12,89,'Calibre:');
    $pdf->Text(12,94,'Serial:');*/
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Text((25 - $posXDatosFactura), (92 - $posYDatosFactura), $marmed);
    $pdf->Text((25 - $posXDatosFactura), (97 - $posYDatosFactura), $calmed);
    $pdf->Text((25 - $posXDatosFactura), (102 - $posYDatosFactura), $serial);

    /* $pdf->SetFont('Arial','',8);
     $pdf->Text(76,82,'Fecha');
     $pdf->Text(92,82,'Lectura');*/
    /*$pdf->Line(50,83,103,83);
    $pdf->Text(52,89,'ANTERIOR');
    $pdf->Text(52,93,'ACTUAL');
    $pdf->Text(52,97,'CONSUMO A FACTURADO');
    $pdf->Text(52,101,'OBS:');*/
    $pdf->SetFont('Arial', 'B', 9);
    if ($marmed == "Sin Medidor") $pdf->Text(78, (108 - $posYDatosFactura), 'Promedio');
    else $pdf->Text(63, (108 - $posYDatosFactura), 'Diferencia Lectura');

    $pdf->Text((95 - $posXDatosFactura), (95 - $posYDatosFactura), $consumo);
    $pdf->SetFont('Arial', '', 9);
    if ($saldofavor == '') $saldofavor = 0;
    if ($difdeuda == '') $difdeuda = 0;
    /*  $pdf->Text(109,83,utf8_decode('Factura número:'));
      $pdf->Text(109,88,utf8_decode('Fecha de emisión:'));
      $pdf->Text(164,83,utf8_decode('Ciclo:'));
      $pdf->Text(164,88,utf8_decode('Período:'));
      $pdf->Text(109,95,'Pendiente Saldos a Favor:');
      $pdf->Text(109,100,'Pendiente Diferidos:');*/
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Text(144, (92 - $posYDatosFactura), $factura);
    $pdf->Text(142, (96 - $posYDatosFactura), $fecexp);

    $pdf->Text(189, (92 - $posYDatosFactura), $zona);
    $pdf->Text(185, (96 - $posYDatosFactura), $mes . "/" . $agno);


    $pdf->Text(158, (102 - $posYDatosFactura), $saldofavor);
    $pdf->Text(158, (106 - $posYDatosFactura), $difdeuda);

    $pdf->Ln(25);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFillColor(0, 128, 192);

    //SECCION DETALLE SERVICIOS DOMICILIARIOS Y OTROS CONCEPTOS
    /*$pdf->Cell(93,6,'DETALLE SERVICIOS DOMICILIARIOS',1,0,'C',true);*/
    $pdf->Cell(4, 6, '', 0, 0, 'C');
    /*$pdf->Cell(92,6,'OTROS CONCEPTOS',1,0,'C',true);*/

    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 9);

    /* $pdf->Cell(26,6,'Servicios',0,0,'C');
     $pdf->Cell(29,6,'Cantidad',0,0,'C');
     $pdf->Cell(20,6,'Precio',0,0,'C');
     $pdf->Cell(17,6,'Importe',0,0,'C');
     $pdf->Line(10,113,103,113);
     $pdf->Cell(4.8,6,'',0,0,'C');
     $pdf->Cell(70,6,'Concepto',0,0,'L');
     $pdf->Cell(32,6,'Importe',0,0,'L');
     $pdf->Line(107,113,199,113);*/
    $pxotrocon = 121;
    $pxcon = 6;
    $conceptoini = '';
    $l = new facturas();
    $stid = $l->detalleFacturaPdf($factura);
    $pdf->Ln(7);
    $mueveadicion = 0;
    while (oci_fetch($stid)) {
        $concepto = oci_result($stid, 'CONCEPTO');
        $rango = oci_result($stid, 'RANGO');
        $unidades = oci_result($stid, 'UNIDADES');
        $valor = oci_result($stid, 'VALOR');
        $codservicio = oci_result($stid, 'COD_SERVICIO');
        $pdf->SetFont('Arial', 'B', 9);

        if ($concepto != $conceptoini && ($concepto == 'Agua' || $concepto == 'Agua de Pozo')) {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(4, 6, '', 0, 0, 'R');
            $pdf->Cell(30, 6, $concepto, 0, 0, 'L');
            $conceptoini = $concepto;
        }

        if ($concepto == 'Agua' || $concepto == 'Agua de Pozo') {
            $pdf->SetFont('Arial', '', 9);

            $totalservicios += $valor;
            if ($rango == 0) {
                $pdf->Ln(5);
                $pdf->Cell(4, 4, '', 0, 0, 'L');
                $pdf->Cell(35, 5, 'Consumo Basico', 0, 0, 'L');
                $pdf->Cell(10, 5, $unidades, 0, 0, 'R');
                if ($unidades > 0) {
                    if ($acueducto == 'SD') $valor_mts = round(($valor / $unidades), 0);
                    if ($acueducto == 'BC') $valor_mts = round(($valor / $unidades), 4);
                } else {
                    $valor_mts = 0;
                }

                if ($unidades > 0)
                    $pdf->Cell(20, 5, $valor_mts, 0, 0, 'R');
                else
                    $pdf->Cell(20, 5, 0, 0, 0, 'R');
                $pdf->Cell(20, 5, $valor, 0, 0, 'R');
            }

            if ($rango == 1) {
                $pxr1 = 3;
                $pdf->Ln($pxr1);
                $pdf->Cell(3, 3, '', 0, 0, 'L');
                $pdf->Cell(35, 5, 'Consumo Adicional ' . $rango, 0, 0, 'L');
                $pdf->Cell(10, 5, $unidades, 0, 0, 'R');
                if ($unidades == 0) {
                    $valor_mts = 0;
                } else {
                    if ($acueducto == 'SD') $valor_mts = round(($valor / $unidades), 0);
                    if ($acueducto == 'BC') $valor_mts = round(($valor / $unidades), 4);
                }


                if ($unidades > 0)
                    $pdf->Cell(20, 5, $valor_mts, 0, 0, 'R');
                else
                    $pdf->Cell(20, 5, 0, 0, 0, 'R');
                $pdf->Cell(20, 5, $valor, 0, 0, 'R');
            }

            if ($rango == 2) {
                $pxr2 = 3;
                $pdf->Ln($pxr2);
                $pdf->Cell(3, 3, '', 0, 0, 'L');
                $pdf->Cell(35, 5, 'Consumo Adicional ' . $rango, 0, 0, 'L');
                $pdf->Cell(10, 5, $unidades, 0, 0, 'R');


                if ($unidades > 0) {
                    if ($acueducto == 'SD') $valor_mts = round(($valor / $unidades), 0);
                    if ($acueducto == 'BC') $valor_mts = round(($valor / $unidades), 4);
                    $pdf->Cell(20, 5, $valor_mts, 0, 0, 'R');
                } else
                    $pdf->Cell(20, 5, 0, 0, 0, 'R');
                $pdf->Cell(20, 5, $valor, 0, 0, 'R');
            }

            if ($rango == 3) {
                $pxr3 = 3;
                $pdf->Ln($pxr3);
                $pdf->Cell(3, 3, '', 0, 0, 'L');
                $pdf->Cell(35, 5, 'Consumo Adicional ' . $rango, 0, 0, 'L');
                $pdf->Cell(10, 5, $unidades, 0, 0, 'R');
                if ($acueducto == 'SD') $valor_mts = round(($valor / $unidades), 0);
                if ($acueducto == 'BC') $valor_mts = round(($valor / $unidades), 4);
                if ($unidades > 0)
                    $pdf->Cell(20, 5, $valor_mts, 0, 0, 'R');
                else
                    $pdf->Cell(20, 5, 0, 0, 0, 'R');
                $pdf->Cell(20, 5, $valor, 0, 0, 'R');
            }

            if ($rango == 4) {
                $pxr4 = 3;
                $pdf->Ln($pxr4);
                $pdf->Cell(3, 3, '', 0, 0, 'L');
                $pdf->Cell(35, 5, 'Consumo Adicional ' . $rango, 0, 0, 'L');
                $pdf->Cell(10, 5, $unidades, 0, 0, 'R');
                if ($acueducto == 'SD') $valor_mts = round(($valor / $unidades), 0);
                if ($acueducto == 'BC') $valor_mts = round(($valor / $unidades), 4);
                if ($unidades > 0)
                    $pdf->Cell(20, 5, $valor_mts, 0, 0, 'R');
                else
                    $pdf->Cell(20, 5, 0, 0, 0, 'R');
                $pdf->Cell(20, 5, $valor, 0, 0, 'R');
            }

            if ($rango == 5) {
                $pxr5 = 3;
                $pdf->Ln($pxr5);
                $pdf->Cell(3, 3, '', 0, 0, 'L');
                $pdf->Cell(35, 5, 'Consumo Adicional ' . $rango, 0, 0, 'L');
                $pdf->Cell(10, 5, $unidades, 0, 0, 'R');
                if ($acueducto == 'SD') $valor_mts = round(($valor / $unidades), 0);
                if ($acueducto == 'BC') $valor_mts = round(($valor / $unidades), 4);
                if ($unidades > 0)
                    $pdf->Cell(20, 5, $valor_mts, 0, 0, 'R');
                else
                    $pdf->Cell(20, 5, 0, 0, 0, 'R');
                $pdf->Cell(20, 5, $valor, 0, 0, 'R');
            }
        }
        if ($concepto != $conceptoini && ($concepto == 'Alcantarillado Red' || $concepto == 'Alcantarillado Pozo')) {
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', 'B', 9);
            $pxcalc = 7;
            $pdf->Ln($pxcalc);
            $pdf->Cell(4, 6, '', 0, 0, 'R');
            $pdf->Cell(30, 6, $concepto, 0, 0, 'R');
            $conceptoini = $concepto;
            $pdf->Ln(1);
        }

        if ($concepto == 'Alcantarillado Red' || $concepto == 'Alcantarillado Pozo') {
            $pdf->SetFont('Arial', '', 9);
            if ($rango >= 0) {
                $f = new facturas();
                $stidb = $f->valorRangosPdf($codservicio, $rango, $codinm);
                while (oci_fetch($stidb)) {
                    $valor_mt = oci_result($stidb, 'VALOR_METRO');
                }
                oci_free_statement($stidb);
                $valor_mt = $valor_mt * $valor_mt2;
                $paxalcde = 5;
                $pdf->Ln($paxalcde);
                $pdf->Cell(4, 4, '', 0, 0, 'L');
                if ($rango == 0) {
                    $mueveadicion += 5;
                    $pdf->Cell(35, 5, 'Consumo Basico', 0, 0, 'L');
                }
                if ($rango == 1) {
                    $pdf->Cell(35, 5, 'Consumo Adicional 1', 0, 0, 'L');
                    $mueveadicion += 5;
                }
                if ($rango == 2) {
                    $pdf->Cell(35, 5, 'Consumo Adicional 2', 0, 0, 'L');
                    $mueveadicion += 5;
                }
                if ($rango == 3) {
                    $pdf->Cell(35, 5, 'Consumo Adicional 3', 0, 0, 'L');
                    $mueveadicion += 5;
                }
                if ($rango == 4) {
                    $pdf->Cell(35, 5, 'Consumo Adicional 4', 0, 0, 'L');
                    $mueveadicion += 5;
                }
                $pdf->Cell(10, 5, $unidades, 0, 0, 'R');
                if ($unidades > 0)
                    $pdf->Cell(20, 5, round(($valor / $unidades), 1), 0, 0, 'R');
                else
                    $pdf->Cell(20, 5, 0, 0, 0, 'R');
                $pdf->Cell(20, 5, $valor, 0, 0, 'R');
                $totalservicios += $valor;

                // $l->ref($consAFacturado,$unidades);
            }
        }

        if ($concepto != $conceptoini && ($concepto != 'Alcantarillado Red' && $concepto != 'Alcantarillado Pozo' && $concepto != 'Agua' && $concepto != 'Agua de Pozo')) {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Text(110, $pxotrocon, $concepto);
            $pdf->Text(187, $pxotrocon, round($valor));
            $pxotrocon = $pxotrocon + 5;
            $conceptoini = $concepto;
            $totalotrosconceptos += $valor;
        }

    }
    oci_free_statement($stid);


    if ($mueveadicion == 0) $mueveadicion = 50;
    if ($mueveadicion == 5) $mueveadicion = 46; //Estoy aqui
    if ($mueveadicion == 10) $mueveadicion = 34;
    if ($mueveadicion == 15) $mueveadicion = 31;
    if ($mueveadicion == 20) $mueveadicion = 28;

    $pdf->Ln($mueveadicion - $pxr1 - $pxr2 - $pxr3 - $pxr4 - $pxr5 - $pxcalc - $paxalcde);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(72.6, 6, '            ', 0, 0, 'L');
    /*$pdf->Image('../../images/A.jpg',11,164,8);*/
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(18, 7, round($totalservicios), 0, 0, 'R');
    $pdf->Cell(2, 6, '', 0, 0, 'R');
    $pdf->Cell(4.8, 6, '', 0, 0, 'C');
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(72.6, 6, '            ', 0, 0, 'L');
    /*$pdf->Image('../../images/B.jpg',108,164,8);*/
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(13, 7, round($totalotrosconceptos), 0, 0, 'R');
    $pdf->Cell(6, 6, '', 0, 0, 'R');
    /*$pdf->Rect(10,109,93,61);
    $pdf->Rect(107,109,92,61);*/

    //NOTAS Y TOTAL DE LA FACTURA MENSUAL

    /*$pdf->Rect(9.8,173,93,24);
    $pdf->Rect(107,173,92,24);*/
    $pdf->SetTextColor(0, 0, 0);
    if ($zona <> '52A' and $zona <> '52B' and $zona <> '52C' and $zona <> '60A' and $zona <> '60B' and $zona <> '26C' and $zona <> '26D' and $zona <> '27D') {

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Text(50, 183, 'Notas');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Text(48.5, 188, 'AVISO');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Text(28, 193, 'Fecha de corte de servicio ' . $fecorte);
    }

    $pdf->SetFont('Arial', 'B', 11);
    $totalfactura = round($totalservicios + $totalotrosconceptos);
    /*$pdf->Text(108,179,'TOTAL FACTURA MENSUAL');
    $pdf->Text(128,184,'( A + B )');
    $pdf->Text(109,192,'Vencimiento:');*/
    $pdf->Text(176, 199, $fecvcto);

    $pdf->Ln(9.3);
    $pdf->Cell(97.3, 18.7, '', 0, 0, 'C');
    $pdf->Cell(56, 18.7, '', 0, 0, 'C');
    $pdf->Cell(35.4, 12, '', 0, 0, 'C');
    /*$pdf->Line(107,186,199,186);*/
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Text(189, 191, $totalfactura);

    //CUADRO MENSAJES DE FACTURA
    $pdf->SetDrawColor(209, 58, 58);
    $pdf->SetLineWidth(1);
    /*$pdf->Rect(9.8,203,189.2,40);*/
    $pdf->SetFont('Arial', '', 11);

    $pdf->Ln(20); //Se cambió esto OJO
    $pdf->Cell(189, 12, $msjncf, 0, 0, 'C');

    //$pdf->Text(11,216,$msjperiodo);
    $msjperiodopartes = explode('*', $msjperiodo);
    $pdf->SetFont('Arial', 'B', 12);
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

    $subparte1 = explode(':', $parte1);
    $subparte1a = explode('Evite', $parte1);
    $subparte1b = explode(" ", $parte1);
    //MENSAJE DE DEUDA Y ADVERTENCIA
    $pdf->Ln(7);
    if ($subparte1[1] == '' && $subparte1a[1] == '' && $parte1 != "") {
        $pdf->Cell(189, 5, $parte1, 0, 0, 'C');
    }
    if ($subparte1[1] == '' && $subparte1a[1] != '') {
        $pdf->Cell(189, 5, $subparte1a[0], 0, 0, 'C');
        $pdf->Ln(7);
        $pdf->Cell(189, 5, 'Evite' . $subparte1a[1], 0, 0, 'C');
        $pdf->Ln(7);
    }
    if ($subparte1[1] != '' && $subparte1a[1] == '') {


        $pdf->Cell(189, 5, $subparte1[0], 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->SetFillColor(0, 126, 192);
        $pdf->MultiCell(185, 10, utf8_decode($subparte1[1]), 0);
    }
    /* $pdf->Ln(10);
     if($subparte1[1] == '' && $subparte1a[1] == '' ){
         $pdf->Cell(189,5,$parte1,0,0,'C');
     }
     if($subparte1[1] == '' && $subparte1a[1] != ''){
         $pdf->Cell(189,5,$subparte1a[0],0,0,'C');
         $pdf->Ln(12);
         $pdf->Cell(189,5,'Evite'.$subparte1a[1],0,0,'C');
         $pdf->Ln(-12);
     }
     if($subparte1[1] != '' && $subparte1a[1] == ''){
         $pdf->Cell(189,5,$subparte1[0],0,0,'C');
         $pdf->Ln(5);
         //$o=0;

         // $pdf->Cell(150,5,utf8_decode($subparte1b[2]),0,0,'C');
         $pdf->SetFillColor(0, 126, 192);
         $pdf->MultiCell(185,5,utf8_decode($subparte1[1]),0);



         $pdf->Ln(-12);
     }*/

    $pdf->MultiCell(185, 5, utf8_decode($mensaje_valor_fiscal_factura), 0);
    //opcion de pago A y C
    $pdf->Ln(5);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(12, 5, '', 0, 0, 'L');
    $subparte3 = explode('rebaja', $parte3);
    if ($subparte3[0] != '') $mensual = 'rebaja';
    $pdf->Cell(30, 5, $parte2 . ' ' . $subparte3[0] . $mensual, 0, 0, 'L');
    $pdf->Cell(60, 5, '', 0, 0, 'L');
    $subparte7 = explode('mensuales', $parte7);
    if ($subparte7[0] != '') $mensual = 'mensuales';
    $pdf->Cell(30, 5, $parte6 . ' ' . $subparte7[0] . $mensual, 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(11, 5, '', 0, 0, 'L');
    $pdf->Cell(30, 5, $subparte3[1], 0, 0, 'L');
    $pdf->Cell(60, 5, '', 0, 0, 'L');
    $pdf->Cell(30, 5, $subparte7[1], 0, 0, 'L');

    //opcion de pago B y D
    $pdf->Ln(5);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(12, 5, '', 0, 0, 'L');
    $subparte5 = explode('mensuales', $parte5);
    if ($subparte5[0] != '') $mensual = 'mensuales';
    $pdf->Cell(30, 5, $parte4 . ' ' . $subparte5[0] . $mensual, 0, 0, 'L');
    $pdf->Cell(60, 5, '', 0, 0, 'L');
    $subparte9 = explode('mensuales', $parte9);
    if ($subparte9[0] != '') $mensual = 'mensuales';
    $pdf->Cell(30, 5, $parte8 . ' ' . $subparte9[0] . $mensual, 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(11, 5, '', 0, 0, 'L');
    $pdf->Cell(30, 5, $subparte5[1], 0, 0, 'L');
    $pdf->Cell(60, 5, '', 0, 0, 'L');
    $pdf->Cell(30, 5, $subparte9[1], 0, 0, 'L');

    $pdf->Ln(8);
    if ($parte10 != '') $asterisco = '*';
    $pdf->Cell(189, 5, $asterisco . ' ' . $parte10, 0, 0, 'C');
    //TALON DE FACTURA
    /* if($acueducto == 'SD')
         $pdf->Image('../../images/logo_caasd.jpg',6,250,25);
     else $pdf->Image('../../images/coraabo.jpg',11,250,20);*/
    //$pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial', '', 10);
    /*$pdf->Text(13,277,'TALON');
    $pdf->Text(16.5,282,'DE');
    $pdf->Text(14,287,'PAGO');*/
    $pdf->Image('../../temp/' . $factura . ".gif", 33, 263, 60, 8);
    /*$pdf->Image('../../images/rec.jpg',109.3,250,90,20);*/
    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(17, 5, '', 0, 0, 'L');
    /*$pdf->Text(29,253,'FAVOR NO COLOCAR SELLOS SOBRE EL CODIGO DE BARRAS');*/
    /*$pdf->SetFont('Arial','',8);
    $pdf->Text(111,255,utf8_decode('Factura Número:'));
    $pdf->Text(111,259,'Periodo:');
    $pdf->Text(111,263,utf8_decode('Código Inmueble:'));
    $pdf->Text(111,267,utf8_decode('Código Proceso:'));
    $pdf->Text(154,255,'Fecha de Exp:');
    $pdf->Text(154,259,utf8_decode('Código Sistema:'));*/

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Text(141, 253, $factura);
    $pdf->Text(141, 257, $mes . "/" . $agno);
    $pdf->Text(141, 261, $catastro);
    $pdf->Text(141, 265, $proceso);
    $pdf->Text(187, 253, $fecexp);
    $pdf->Text(187, 257, $codinm);
    $pdf->Text(49, 276, "*" . $factura . "*");

    $pdf->SetFont('Arial', 'B', 10);
    /* $pdf->Text(33,259,'Estafeta:');
     $pdf->Text(62,259,'Fecha de Pago:');*/

    $pdf->Text(78, 290, utf8_decode($alias));
    $pdf->Text(145, 290, utf8_decode($urbaniza), 0, 0, 'C');

    $pdf->Text(30, 283, $codinm);
    $pdf->SetFont('Arial', '', 9);
    /*$pdf->Text(110,275,'Sello y Firma:');*/
    /*$pdf->Text(35,293,utf8_decode("Código Sistema"));*/


    /*$pdf->Text(138,278,'TOTAL FACTURA:');
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(0);*/
    /*$pdf->Rect(168,273,31,7,true);*/
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Text(183, 274, 'RD$ ' . $totalfactura);
    /*$pdf->Rect(136,273,63,12);*/
    $pdf->SetFont('Arial', 'B', 9);
    /*$pdf->Text(139,283,'Vencimiento:');*/
    $pdf->Text(180, 278, $fecvcto);
    $pdf->Text(110, 277, $gerencia);
    $pdf->Output("facturas_digital/Factura_$factura.pdf", 'I');
}
?>