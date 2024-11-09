<?php
session_start();
$cod      = $_SESSION['codigo'];
$tipo     = $_REQUEST["tipo"];
$periodo  = $_GET["periodo"];
$proyecto = $_GET["pro"];
$contratista = $_GET["selCon"];

setlocale(LC_MONETARY, 'es_DO');
if ($tipo == "rep") {

    include_once "../../clases/class.pdfRep.php";
    include_once "../../clases/class.corte.php";
    include_once "../../clases/class.pago.php";
    include_once "../../clases/class.reconexion.php";

    /**
     * Created by PhpStorm.
     * User: Edwin
     * Date: 3/06/2016
     * Time: 11:01 AM
     */

    $pdf = new pdfRepMen();
    $pdf->setPeriodoGen($periodo);
    $pdf->setProyecto($proyecto);
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(64, 128, 191);
    $pdf->SetLineWidth(0.3);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('times', 'B', 9);
    $pdf->SetY(38);
    $pdf->SetX(2);
    $pdf->Cell(15, 10, "SECTOR", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(17);
    $pdf->Cell(7, 5, "TP1", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(24);
    $pdf->Cell(7, 5, "TP2", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(31);
    $pdf->Cell(7, 5, "TP3", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(38);
    $pdf->Cell(7, 5, "TP4", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(45);
    $pdf->Cell(7, 5, "TP5", 1, 3, 'C', true);
    $pdf->SetY(38);
    $pdf->SetX(17);
    $pdf->Cell(35, 5, "TIPO DE CORTE", 1, 3, 'C', true);

    $a    = new Corte();
    $std  = $a->getTipoCortePorSecSoloCorte($periodo, $proyecto,$contratista);
    $posy = 43;
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('times', '', 9);
    $totTp1 = 0;
    $totTp2 = 0;
    $totTp3 = 0;
    $totTp4 = 0;
    $totTp5 = 0;

    while (oci_fetch($std)) {
        $posy += 5;
        $sector         = oci_result($std, 'ID_SECTOR');
        $totTp1 += $tp1 = oci_result($std, "'TP1'");
        $totTp2 += $tp2 = oci_result($std, "'TP2'");
        $totTp3 += $tp3 = oci_result($std, "'TP3'");
        $totTp4 += $tp4 = oci_result($std, "'TP4'");
        $totTp5 += $tp5 = oci_result($std, "'TP5'");

        $pdf->SetY($posy);
        $pdf->SetX(2);
        $pdf->Cell(15, 5, $sector, 1, 3, 'C', false);
        $pdf->SetY($posy);
        $pdf->SetX(17);
        $pdf->Cell(7, 5, $tp1, 1, 3, 'C', false);
        $pdf->SetY($posy);
        $pdf->SetX(24);
        $pdf->Cell(7, 5, $tp2, 1, 3, 'C', false);
        $pdf->SetY($posy);
        $pdf->SetX(31);
        $pdf->Cell(7, 5, $tp3, 1, 3, 'C', false);
        $pdf->SetY($posy);
        $pdf->SetX(38);
        $pdf->Cell(7, 5, $tp4, 1, 3, 'C', false);
        $pdf->SetY($posy);
        $pdf->SetX(45);
        $pdf->Cell(7, 5, $tp5, 1, 3, 'C', false);
    }
    $posy += 5;
    $pdf->SetY($posy);
    $pdf->SetX(2);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('times', 'B', 9);
    $pdf->Cell(15, 5, "Total", 1, 3, 'C', true);
    $pdf->SetY($posy);
    $pdf->SetX(17);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('times', '', 9);
    $pdf->Cell(7, 5, $totTp1, 1, 3, 'C', false);
    $pdf->SetY($posy);
    $pdf->SetX(24);
    $pdf->Cell(7, 5, $totTp2, 1, 3, 'C', false);
    $pdf->SetY($posy);
    $pdf->SetX(31);
    $pdf->Cell(7, 5, $totTp3, 1, 3, 'C', false);
    $pdf->SetY($posy);
    $pdf->SetX(38);
    $pdf->Cell(7, 5, $totTp4, 1, 3, 'C', false);
    $pdf->SetY($posy);
    $pdf->SetX(45);
    $pdf->Cell(7, 5, $totTp5, 1, 3, 'C', false);

    // Agregado resultado de inspecciones por tipo de servicios;
    $posy = $posy + 10;
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('times', 'B', 9);
    $pdf->SetY($posy);
    $pdf->SetX(2);
    $pdf->Cell(50, 5, "RESUMEN INSPECCIONES", 1, 3, 'C', true);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(64, 128, 191);
    $pdf->SetLineWidth(0.3);
    $pdf->SetFont('times', '', 9);

    //$a        = new Corte();
    //$inspe    = $a->getInspByTipoServ($periodo, $proyecto,$contratista);
    //$r        = oci_fetch_array($inspe);
    $medido   = $r['MEDIDO'];
    $noMedido = $r['NO_MEDIDO'];

    $pdf->SetY($posy + 5);
    $pdf->SetX(2);
    $pdf->Cell(25, 5, "MEDIDO", 1, 3, 'C', true);
    $pdf->SetY($posy + 5);
    $pdf->SetX(27);
    $pdf->Cell(25, 5, "NO MEDIDO", 1, 3, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);

    $pdf->SetY($posy + 10);
    $pdf->SetX(2);
    $pdf->Cell(25, 5, $medido, 1, 3, 'C', true);
    $pdf->SetY($posy + 10);
    $pdf->SetX(27);
    $pdf->Cell(25, 5, $noMedido, 1, 3, 'C', true);

    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('times', 'B', 9);
    $pdf->SetFillColor(64, 128, 191);
    $pdf->SetY(38);
    $pdf->SetX(73);
    $pdf->Cell(119, 5, "RESUMEN TOTAL", 1, 3, 'C', true);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(64, 128, 191);
    $pdf->SetLineWidth(0.3);

    $pdf->SetY(38);
    $pdf->SetX(59);
    $pdf->Cell(14, 10, "SECTOR", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(73);
    $pdf->Cell(12, 5, "Visitado", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(85);
    $pdf->Cell(16, 5, "Planificado", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(101);

    $pdf->Cell(12, 5, "Insp", 1, 3, 'C', true); // Campo agregado
    $pdf->SetY(43);
    $pdf->SetX(113);

    $pdf->Cell(13, 5, "Efectivos", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(126);
    $pdf->Cell(9, 5, "Pagos", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(135);
    $pdf->Cell(16, 5, "Pago Corte", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(151);
    $pdf->Cell(19, 5, "Reconexiones", 1, 3, 'C', true);

    $pdf->SetY(43);
    $pdf->SetX(170);
    $pdf->Cell(22, 5, "$ Reconexiones", 1, 3, 'C', true);

    $b    = new Corte();
    $std  = $b->getCantSecPorPerSoloCorte($periodo, $proyecto,$contratista);
    $posy = 43;
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('times', '', 9);
    $totVisitado    = 0;
    $totPlanificado = 0;
    $totCorteEfec   = 0;
    $totPagos       = 0;
    $totPagosCor    = 0;
    $totCantRec     = 0;
    $totMonRec      = 0;

    while (oci_fetch($std)) {
        $posy += 5;
        $sector                         = oci_result($std, 'ID_SECTOR');
        $totVisitado += $visitado       = oci_result($std, "VISITADOS");
        $totPlanificado += $planificado = oci_result($std, "PLANILLADOS");
        $totCorteEfec += $corteEfectivo = oci_result($std, "CORTESEFECTIVOS");

        $pdf->SetY($posy);
        $pdf->SetX(59);
        $pdf->Cell(14, 5, $sector, 1, 3, 'C', false);
        $pdf->SetY($posy);
        $pdf->SetX(73);
        $pdf->Cell(12, 5, $visitado, 1, 3, 'C', false);
        $pdf->SetY($posy);
        $pdf->SetX(85);
        $pdf->Cell(16, 5, $planificado, 1, 3, 'C', false);
        $pdf->SetY($posy);
        $pdf->SetX(101);

        $pdf->Cell(12, 5, $inspecciones, 1, 3, 'C', false); // Campo agregado
        $pdf->SetY($posy);
        $pdf->SetX(113);

        $pdf->Cell(13, 5, $corteEfectivo, 1, 3, 'C', false);

        $b = new Pago();
        $pdf->SetY($posy);
        $pdf->SetX(126);
        $totPagos += $pagos = $b->getPagoGroupSecPerBySecPerSoloCorte($periodo, $sector);
        $pdf->Cell(9, 5, $pagos, 1, 3, 'C', false);

        $b                        = new Pago();
        $totPagosCor += $pagosCor = $b->GerPagCorGroupSecPerBySecPerSoloCorte($periodo, $sector,$contratista);
        $pdf->SetY($posy);
        $pdf->SetX(135);
        $pdf->Cell(16, 5, $pagosCor, 1, 3, 'C', false);

        $b                      = new Pago();
        $totCantRec += $cantRec = $b->getPagRecByPerSecSoloCorte($periodo, $sector,$contratista);
        $pdf->SetY($posy);
        $pdf->SetX(151);
        $pdf->Cell(19, 5, $cantRec, 1, 3, 'C', false);

        $b                    = new Pago();
        $totMonRec += $monRec = $b->getTotValRecBySecPerSoloCorte($periodo, $sector,$contratista);
        $pdf->SetY($posy);
        $pdf->SetX(170);
        $pdf->Cell(22, 5, "RD" . money_format('%.2n', $monRec), 1, 3, 'C', false);
    }
    $posy += 5;
    $pdf->SetY($posy);
    $pdf->SetX(59);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('times', 'B', 9);
    $pdf->Cell(14, 5, "Total", 1, 3, 'C', true);

    $pdf->SetY($posy);
    $pdf->SetX(73);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('times', '', 9);
    $pdf->Cell(12, 5, $totVisitado, 1, 3, 'C', false);
    $pdf->SetY($posy);
    $pdf->SetX(85);
    $pdf->Cell(16, 5, $totPlanificado, 1, 3, 'C', false);
    $pdf->SetY($posy);
    $pdf->SetX(101);

    $pdf->Cell(12, 5, $totInsp, 1, 3, 'C', false); // Campo agregado
    $pdf->SetY($posy);
    $pdf->SetX(113);

    $pdf->Cell(13, 5, $totCorteEfec, 1, 3, 'C', false);
    $pdf->SetY($posy);
    $pdf->SetX(126);
    $pdf->Cell(9, 5, $totPagos, 1, 3, 'C', false);
    $pdf->SetY($posy);
    $pdf->SetX(135);
    $pdf->Cell(16, 5, $totPagosCor, 1, 3, 'C', false);

    $pdf->SetY($posy);
    $pdf->SetX(151);
    $pdf->Cell(19, 5, $totCantRec, 1, 3, 'C', false);
    $pdf->SetY($posy);
    $pdf->SetX(170);
    $pdf->Cell(22, 5, "RD" . money_format('%.2n', $totMonRec), 1, 3, 'C', false);

///////////////////////// GERENCIA ESTE/////////////////////////////////////

    $posy = $posy + 10;
    $pdf->SetFont('times', '', 12);
    $pdf->Text(59, $posy + 20, utf8_decode("RESUMEN DETALLADO POR GERENCIA"));
    $pdf->SetFont('times', '', 10);
    $pdf->Text(2, $posy + 30, utf8_decode("GERENCIA ESTE"));
    $pdf->Text(64, $posy + 30, utf8_decode("TOTAL"));
    $pdf->SetFont('times', '', 7);

    // Agregado de inspecciones a reportes

    $pdf->SetY($posy + 35);
    $pdf->SetX(2);
    $pdf->Cell(49, 5, utf8_decode("REPORTE INSPECCIONES MEDIDOS"), 1, 3, 'L', false);

    $b        = new Corte();
    //$r        = oci_fetch_array($b->getInspByZona2($periodo, $proyecto, 'E',$contratista));
    $medido   = $r['MEDIDO'];
    $noMedido = $r['NO_MEDIDO'];
    $total    = $medido + $noMedido;
    $pdf->SetY($posy + 35);
    $pdf->SetX(51);
    $pdf->Cell(20, 5, $medido, 1, 3, 'L', false);
    $pdf->SetY($posy + 40);
    $pdf->SetX(2);
    $pdf->Cell(49, 5, utf8_decode("REPORTE INSPECCIONES NO MEDIDOS"), 1, 3, 'L', false);

    $pdf->SetY($posy + 40);
    $pdf->SetX(51);
    $pdf->Cell(20, 5, $noMedido, 1, 3, 'L', false);
    $pdf->SetY($posy + 35);
    $pdf->SetX(71);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, $total, 1, 3, 'C', true);

    // Fin Agregado zona Este

    $posy = $posy + 15;
    $pdf->SetY($posy + 35);
    $pdf->SetX(2);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(49, 5, utf8_decode("CORTE EFECTIVO MEDIDOS"), 1, 3, 'L', false);

    $b     = new Corte();
    $total = 0;
    $pdf->SetY($posy + 35);
    $pdf->SetX(51);
    $total += $corEfecNoMeEste = $b->getCantGerTipoSoloCorte($periodo, 'S', 'E', $proyecto,$contratista);
    $pdf->Cell(20, 5, $corEfecNoMeEste, 1, 3, 'L', false);
    $pdf->SetY($posy + 40);
    $pdf->SetX(2);
    $pdf->Cell(49, 5, utf8_decode("CORTE EFECTIVO NO MEDIDOS"), 1, 3, 'L', false);

    $b                       = new Corte();
    $total += $corEfecMeEste = $b->getCantGerTipoSoloCorte($periodo, 'N', 'E', $proyecto,$contratista);
    $pdf->SetY($posy + 40);
    $pdf->SetX(51);
    $pdf->Cell(20, 5, $corEfecMeEste, 1, 3, 'L', false);
    $pdf->SetY($posy + 35);
    $pdf->SetX(71);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, $total, 1, 3, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetY($posy + 50);
    $pdf->SetX(2);
    $pdf->Cell(49, 5, utf8_decode("PAGO RECONEXIONES MEDIDAS"), 1, 3, 'L', false);
    $b     = new Pago();
    $total = 0;
    $pdf->SetY($posy + 50);
    $pdf->SetX(51);
    $total += $pagRecMedEste = $b->getCantRecPagByPerTipGerProSoloCorte($periodo, 'S', 'E', $proyecto,$contratista);
    $pdf->Cell(20, 5, $pagRecMedEste, 1, 3, 'L', false);
    $pdf->SetY($posy + 55);
    $pdf->SetX(2);
    $pdf->Cell(49, 5, utf8_decode("PAGO RECONEXIONES NO MEDIDAS"), 1, 3, 'L', false);
    $b                         = new Pago();
    $total += $pagRecNoMedEste = $b->getCantRecPagByPerTipGerProSoloCorte($periodo, 'N', 'E', $proyecto,$contratista);
    $pdf->SetY($posy + 55);
    $pdf->SetX(51);
    $pdf->Cell(20, 5, $pagRecNoMedEste, 1, 3, 'L', false);
    $pdf->SetY($posy + 50);
    $pdf->SetX(71);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, $total, 1, 3, 'C', true);

    $total2 = 0;
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetY($posy + 65);
    $pdf->SetX(2);
    $pdf->Cell(49, 5, utf8_decode("MONTO RECONEXIONES MEDIDAS"), 1, 3, 'L', false);
    $b     = new Pago();
    $total = 0;
    $pdf->SetY($posy + 65);
    $pdf->SetX(51);
    $total += $corEfecNoMeEste = $b->getValRecByPerTipGerProSoloCorte($periodo, 'S', 'E', $proyecto,$contratista);
    $pdf->Cell(20, 5, "RD" . money_format('%.2n', $corEfecNoMeEste), 1, 3, 'L', false);
    $pdf->SetY($posy + 70);
    $pdf->SetX(2);
    $pdf->Cell(49, 5, utf8_decode("MONTO RECONEXIONES NO MEDIDAS"), 1, 3, 'L', false);
    $b                       = new Pago();
    $total += $corEfecMeEste = $b->getValRecByPerTipGerProSoloCorte($periodo, 'N', 'E', $proyecto,$contratista);
    $pdf->SetY($posy + 70);
    $pdf->SetX(51);
    $pdf->Cell(20, 5, "RD" . money_format('%.2n', $corEfecMeEste), 1, 3, 'L', false);
    $pdf->SetY($posy + 65);
    $pdf->SetX(71);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, "RD" . money_format('%.2n', $total), 1, 3, 'C', true);
    $total2 += $total;

////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $posy = $posy - 15;
    $pdf->SetFont('times', '', 10);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Text(125, $posy + 30, utf8_decode("GERENCIA NORTE"));
    $pdf->Text(64 + 110, $posy + 30, utf8_decode("TOTAL"));
    $pdf->SetFont('times', '', 7);

    // Agregado de inspecciones a reportes
    $pdf->SetY($posy + 35);
    $pdf->SetX(2 + 110);
    $pdf->Cell(49, 5, utf8_decode("REPORTE INSPECCIONES MEDIDOS"), 1, 3, 'L', false);

    $b        = new Corte();
    //$r        = oci_fetch_array($b->getInspByZona($periodo, $proyecto, 'N',$contratista));
    $medido   = $r['MEDIDO'];
    $noMedido = $r['NO_MEDIDO'];
    $total    = $medido + $noMedido;
    $pdf->SetY($posy + 35);
    $pdf->SetX(51 + 110);
    $pdf->Cell(20, 5, $medido, 1, 3, 'L', false);
    $pdf->SetY($posy + 40);
    $pdf->SetX(2 + 110);
    $pdf->Cell(49, 5, utf8_decode("REPORTE INSPECCIONES NO MEDIDOS"), 1, 3, 'L', false);

    $pdf->SetY($posy + 40);
    $pdf->SetX(51 + 110);
    $pdf->Cell(20, 5, $noMedido, 1, 3, 'L', false);
    $pdf->SetY($posy + 35);
    $pdf->SetX(71 + 110);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, $total, 1, 3, 'C', true);

    // Fin Agregado zona Norte
    $posy = $posy + 15;
    $pdf->SetY($posy + 35);
    $pdf->SetX(2 + 110);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(49, 5, utf8_decode("CORTE EFECTIVO MEDIDOS"), 1, 3, 'L', false);
    $b     = new Corte();
    $total = 0;
    $pdf->SetY($posy + 35);
    $pdf->SetX(51 + 110);
    $total += $corEfecNoMeNorte = $b->getCantGerTipo($periodo, 'S', 'N', $proyecto,$contratista);
    $pdf->Cell(20, 5, $corEfecNoMeNorte, 1, 3, 'L', false);
    $pdf->SetY($posy + 40);
    $pdf->SetX(2 + 110);
    $pdf->Cell(49, 5, utf8_decode("CORTE EFECTIVO NO MEDIDOS"), 1, 3, 'L', false);
    $b                        = new Corte();
    $total += $corEfecMeNorte = $b->getCantGerTipoSoloCorte($periodo, 'N', 'N', $proyecto,$contratista);
    $pdf->SetY($posy + 40);
    $pdf->SetX(51 + 110);
    $pdf->Cell(20, 5, $corEfecMeNorte, 1, 3, 'L', false);
    $pdf->SetY($posy + 35);
    $pdf->SetX(71 + 110);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, $total, 1, 3, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetY($posy + 50);
    $pdf->SetX(2 + 110);
    $pdf->Cell(49, 5, utf8_decode("PAGO RECONEXIONES MEDIDAS"), 1, 3, 'L', false);
    $b     = new Pago();
    $total = 0;
    $pdf->SetY($posy + 50);
    $pdf->SetX(51 + 110);
    $total += $pagRecMedNor = $b->getCantRecPagByPerTipGerProSoloCorte($periodo, 'S', 'N', $proyecto,$contratista);
    $pdf->Cell(20, 5, $pagRecMedNor, 1, 3, 'L', false);
    $pdf->SetY($posy + 55);
    $pdf->SetX(2 + 110);
    $pdf->Cell(49, 5, utf8_decode("PAGO RECONEXIONES NO MEDIDAS"), 1, 3, 'L', false);
    $b                        = new Pago();
    $total += $pagRecNoMedNor = $b->getCantRecPagByPerTipGerProSoloCorte($periodo, 'N', 'N', $proyecto,$contratista);
    $pdf->SetY($posy + 55);
    $pdf->SetX(51 + 110);
    $pdf->Cell(20, 5, $pagRecNoMedNor, 1, 3, 'L', false);
    $pdf->SetY($posy + 50);
    $pdf->SetX(71 + 110);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, $total, 1, 3, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetY($posy + 65);
    $pdf->SetX(2 + 110);
    $pdf->Cell(49, 5, utf8_decode("MONTO RECONEXIONES MEDIDAS"), 1, 3, 'L', false);
    $b     = new Pago();
    $total = 0;
    $pdf->SetY($posy + 65);
    $pdf->SetX(51 + 110);
    $total += $corEfecNoMeEste = $b->getValRecByPerTipGerProSoloCorte($periodo, 'S', 'N', $proyecto,$contratista);
    $pdf->Cell(20, 5, "RD" . money_format('%.2n', $corEfecNoMeEste), 1, 3, 'L', false);
    $pdf->SetY($posy + 70);
    $pdf->SetX(2 + 110);
    $pdf->Cell(49, 5, utf8_decode("MONTO RECONEXIONES NO MEDIDAS"), 1, 3, 'L', false);
    $b                       = new Pago();
    $total += $corEfecMeEste = $b->getValRecByPerTipGerProSoloCorte($periodo, 'N', 'N', $proyecto,$contratista);
    $pdf->SetY($posy + 70);
    $pdf->SetX(51 + 110);
    $pdf->Cell(20, 5, "RD" . money_format('%.2n', $corEfecMeEste), 1, 3, 'L', false);
    $pdf->SetY($posy + 65);
    $pdf->SetX(71 + 110);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, "RD" . money_format('%.2n', $total), 1, 3, 'C', true);
    $total2 += $total;

    ////////////////////////////////////////////////////////
    $pdf->SetY($posy + 85);
    $pdf->SetX(85);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('times', '', 9);
    $pdf->Cell(26, 10, "RD" . money_format('%.2n', $total2), 1, 3, 'C', true);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Text(10, $posy + 90, utf8_decode("TOTAL RECAUDO POR CONCEPTO RECONEXION"));

    $pdf->Output("Libro.pdf", 'I');

}

if ($tipo == "per") {

    include_once "../../clases/class.periodo.php";
    $q     = new Periodo();
    $datos = $q->getPeriodo();
    $i     = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $periodos[$i] = $row;
        $i++;
    }
    echo json_encode($periodos);

}

if ($tipo == "pro") {
    include_once "../../clases/class.proyecto.php";
    $q     = new Proyecto();
    $datos = $q->obtenerProyecto($cod);
    $i     = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $proyectos[$i] = $row;
        $i++;
    }
    echo json_encode($proyectos);

}
