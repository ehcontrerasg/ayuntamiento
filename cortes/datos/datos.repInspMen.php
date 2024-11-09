<?php
setlocale(LC_MONETARY, 'es_DO');
session_start();
$cod      = $_SESSION['codigo'];
$tipo     = $_REQUEST["tipo"];
$periodo  = $_GET["periodo"];
$proyecto = $_GET["pro"];
$contratista = $_GET["selCon"];
//echo "dfg";

if ($tipo == "rep") {
  //  echo "dfg";
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

    $pdf = new pdfRepInspMen();
    $pdf->setPeriodoGen($periodo);
    $pdf->setProyecto($proyecto);
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('times', 'B', 9);
    $pdf->SetFillColor(64, 128, 191);
    $pdf->SetY(38);
    $pdf->SetX(24);
    $pdf->Cell(88, 5, "RESUMEN TOTAL", 1, 3, 'C', true);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetFillColor(64, 128, 191);
    $pdf->SetLineWidth(0.3);

    $pdf->SetY(38);
    $pdf->SetX(10);
    $pdf->Cell(14, 10, "SECTOR", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(24);
    $pdf->Cell(18, 5, "P. Mes Ant", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(42);
    $pdf->Cell(14, 5, "Visitado", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(56);
    $pdf->Cell(18, 5, "Planificado", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(74);
    $pdf->Cell(14, 5, "Pagos", 1, 3, 'C', true);
    $pdf->SetY(43);
    $pdf->SetX(88);
    $pdf->Cell(24, 5, "$ Pagos", 1, 3, 'C', true);

    $b    = new Corte();
    $std  = $b->getCantInsPorPer($periodo, $proyecto,$contratista);
    $posy = 43;
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('times', '', 9);
    $pdf->SetFillColor(255, 255, 255);

    while (oci_fetch($std)) {
        $posy += 5;
        $sector                         = oci_result($std, 'ID_SECTOR');
        $totAnte += $anterior           = oci_result($std, 'ANTERIOR');
        $totVisitado += $visitado       = oci_result($std, "VISITADOS");
        $totPlanificado += $planificado = oci_result($std, "PLANILLADOS");
        $totPagos += $pagos             = oci_result($std, 'PAGOS');
        $totImporte += $importe         = oci_result($std, 'IMPORTE');

        $pdf->SetY($posy);
        $pdf->SetX(10);
        $pdf->Cell(14, 5, $sector, 1, 3, 'C', false);
        $pdf->SetY($posy);
        $pdf->SetX(24);
        $pdf->Cell(18, 5, $anterior, 1, 3, 'C', false);
        $pdf->SetY($posy);
        $pdf->SetX(42);
        $pdf->Cell(14, 5, $visitado, 1, 3, 'C', false);
        $pdf->SetY($posy);
        $pdf->SetX(56);
        $pdf->Cell(18, 5, $planificado, 1, 3, 'C', false);
        $pdf->SetY($posy);
        $pdf->SetX(74);
        $pdf->Cell(14, 5, $pagos, 1, 3, 'C', true);
        $pdf->SetY($posy);
        $pdf->SetX(88);
        $pdf->Cell(24, 5, 'RD$ ' . number_format($importe, 2), 1, 3, 'C', true);

    }
    $posy += 5;
    $pdf->SetY($posy);
    $pdf->SetX(10);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFillColor(64, 128, 191);
    $pdf->SetFont('times', 'B', 9);
    $pdf->Cell(14, 5, "Total", 1, 3, 'C', true);

    $pdf->SetY($posy);
    $pdf->SetX(24);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('times', '', 9);
    $pdf->Cell(18, 5, $totAnte, 1, 3, 'C', false);
    $pdf->SetY($posy);
    $pdf->SetX(42);
    $pdf->Cell(14, 5, $totVisitado, 1, 3, 'C', false);
    $pdf->SetY($posy);
    $pdf->SetX(56);
    $pdf->Cell(18, 5, $totPlanificado, 1, 3, 'C', false);
    $pdf->SetY($posy);
    $pdf->SetX(74);
    $pdf->Cell(14, 5, $totPagos, 1, 3, 'C', true);
    $pdf->SetY($posy);
    $pdf->SetX(88);
    $pdf->Cell(24, 5, 'RD$ ' . number_format($totImporte, 2), 1, 3, 'C', true);

    // Agregado resultado de inspecciones por tipo de servicios;
    $posy = $posy + 15;
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFillColor(64, 128, 191);
    $pdf->SetFont('times', 'B', 9);
    $pdf->SetY($posy);
    $pdf->SetX(10);
    $pdf->Cell(50, 5, "RESUMEN INSPECCIONES", 1, 3, 'C', true);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.3);
    $pdf->SetFont('times', '', 9);

    $a         = new Corte();
    $inspe     = $a->getInspByTipoServ($periodo, $proyecto,$contratista);
    $r         = oci_fetch_array($inspe);
    $medido    = $r['MEDIDO'];
    $noMedido  = $r['NO_MEDIDO'];
    $pMedido   = $r['PAGO_MEDIDO'];
    $pNoMedido = $r['PAGO_NO_MEDIDO'];

    $pdf->SetY($posy + 5);
    $pdf->SetX(10);
    $pdf->Cell(25, 5, "MEDIDO", 1, 3, 'C', true);
    $pdf->SetY($posy + 5);
    $pdf->SetX(35);
    $pdf->Cell(25, 5, "NO MEDIDO", 1, 3, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(255, 255, 255);

    $pdf->SetY($posy + 10);
    $pdf->SetX(10);
    $pdf->Cell(25, 5, $medido, 1, 3, 'C', true);
    $pdf->SetY($posy + 15);
    $pdf->SetX(10);
    $pdf->Cell(25, 5, '$RD ' . number_format($pMedido, 2), 1, 3, 'C', true);
    $pdf->SetY($posy + 10);
    $pdf->SetX(35);
    $pdf->Cell(25, 5, $noMedido, 1, 3, 'C', true);
    $pdf->SetY($posy + 15);
    $pdf->SetX(35);
    $pdf->Cell(25, 5, '$RD ' . number_format($pNoMedido, 2), 1, 3, 'C', true);

    $pdf->SetFillColor(64, 128, 191);
///////////////////////// GERENCIA ESTE/////////////////////////////////////

    $posy = 11.5;
    $pdf->SetFont('times', '', 12);
    $pdf->Text(116, $posy + 20, utf8_decode("RESUMEN DETALLADO POR GERENCIA"));
    $pdf->SetFont('times', '', 10);
    $pdf->Text(116, $posy + 30, utf8_decode("GERENCIA ESTE"));
    $pdf->Text(69 + 120, $posy + 30, utf8_decode("TOTAL"));
    $pdf->SetFont('times', '', 7);

    // Agregado de inspecciones a reportes

    $pdf->SetY($posy + 35);
    $pdf->SetX(116);
    $pdf->Cell(49, 5, utf8_decode("REPORTE INSPECCIONES MEDIDOS"), 1, 3, 'L', false);

    $b               = new Corte();
    $r               = oci_fetch_array($b->getInspByZona($periodo, $proyecto, 'E',$contratista));
    $medido          = $r['MEDIDO'];
    $noMedido        = $r['NO_MEDIDO'];
    $pMedido         = $r['PAGO_MEDIDO'];
    $pNoMedido       = $r['PAGO_NO_MEDIDO'];
    $nPagosMedidos   = $r['N_PAGOS_MEDIDO'];
    $nPagosNoMedidos = $r['N_PAGOS_NO_MEDIDO'];
    $total           = $medido + $noMedido;
    $pTotal          = $pMedido + $pNoMedido;
    $nPTotal         = $nPagosMedidos + $nPagosNoMedidos;
    $pdf->SetY($posy + 35);
    $pdf->SetX(165);
    $pdf->Cell(20, 5, $medido, 1, 3, 'L', false);
    $pdf->SetY($posy + 40);
    $pdf->SetX(116);
    $pdf->Cell(49, 5, utf8_decode("REPORTE INSPECCIONES NO MEDIDOS"), 1, 3, 'L', false);

    $pdf->SetY($posy + 40);
    $pdf->SetX(165);
    $pdf->Cell(20, 5, $noMedido, 1, 3, 'L', false);
    $pdf->SetY($posy + 35);
    $pdf->SetX(185);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, $total, 1, 3, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetY($posy + 50);
    $pdf->SetX(116);
    $pdf->Cell(49, 5, utf8_decode("PAGOS INSPECCIONES MEDIDOS"), 1, 3, 'L', false);
    $pdf->SetY($posy + 50);
    $pdf->SetX(165);
    $pdf->Cell(20, 5, $nPagosMedidos, 1, 3, 'L', false);

    $pdf->SetY($posy + 55);
    $pdf->SetX(116);
    $pdf->Cell(49, 5, utf8_decode("PAGOS INSPECCIONES NO MEDIDOS"), 1, 3, 'L', false);
    $pdf->SetY($posy + 55);
    $pdf->SetX(165);
    $pdf->Cell(20, 5, $nPagosNoMedidos, 1, 3, 'L', false);

    $pdf->SetY($posy + 50);
    $pdf->SetX(185);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, $nPTotal, 1, 3, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetY($posy + 65);
    $pdf->SetX(116);
    $pdf->Cell(49, 5, utf8_decode("RECAUDO INSPECCIONES MEDIDOS"), 1, 3, 'L', false);
    $pdf->SetY($posy + 65);
    $pdf->SetX(165);
    $pdf->Cell(20, 5, '$RD ' . number_format($pMedido, 2), 1, 3, 'L', false);

    $pdf->SetY($posy + 70);
    $pdf->SetX(116);
    $pdf->Cell(49, 5, utf8_decode("RECAUDO INSPECCIONES NO MEDIDOS"), 1, 3, 'L', false);
    $pdf->SetY($posy + 70);
    $pdf->SetX(165);
    $pdf->Cell(20, 5, '$RD ' . number_format($pNoMedido, 2), 1, 3, 'L', false);

    $pdf->SetY($posy + 65);
    $pdf->SetX(185);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, '$RD ' . number_format($pTotal, 2), 1, 3, 'C', true);

    // Fin Agregado zona Este

////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $posy = 66.5;
    $pdf->SetFont('times', '', 10);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Text(116, $posy + 30, utf8_decode("GERENCIA NORTE"));
    $pdf->Text(69 + 120, $posy + 30, utf8_decode("TOTAL"));
    $pdf->SetFont('times', '', 7);

    // Agregado de inspecciones a reportes
    $pdf->SetY($posy + 35);
    $pdf->SetX(116);
    $pdf->Cell(49, 5, utf8_decode("REPORTE INSPECCIONES MEDIDOS"), 1, 3, 'L', false);

    $b               = new Corte();
    $r               = oci_fetch_array($b->getInspByZona($periodo, $proyecto, 'N',$contratista));
    $medido          = $r['MEDIDO'];
    $noMedido        = $r['NO_MEDIDO'];
    $pMedido         = $r['PAGO_MEDIDO'];
    $pNoMedido       = $r['PAGO_NO_MEDIDO'];
    $nPagosMedidos   = $r['N_PAGOS_MEDIDO'];
    $nPagosNoMedidos = $r['N_PAGOS_NO_MEDIDO'];
    $total           = $medido + $noMedido;
    $pTotal          = $pMedido + $pNoMedido;
    $nPTotal         = $nPagosMedidos + $nPagosNoMedidos;
    $pdf->SetY($posy + 35);
    $pdf->SetX(165);
    $pdf->Cell(20, 5, $medido, 1, 3, 'L', false);
    $pdf->SetY($posy + 40);
    $pdf->SetX(116);
    $pdf->Cell(49, 5, utf8_decode("REPORTE INSPECCIONES NO MEDIDOS"), 1, 3, 'L', false);

    $pdf->SetY($posy + 40);
    $pdf->SetX(165);
    $pdf->Cell(20, 5, $noMedido, 1, 3, 'L', false);
    $pdf->SetY($posy + 35);
    $pdf->SetX(185);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, $total, 1, 3, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetY($posy + 50);
    $pdf->SetX(116);
    $pdf->Cell(49, 5, utf8_decode("PAGOS INSPECCIONES MEDIDOS"), 1, 3, 'L', false);
    $pdf->SetY($posy + 50);
    $pdf->SetX(165);
    $pdf->Cell(20, 5, $nPagosMedidos, 1, 3, 'L', false);

    $pdf->SetY($posy + 55);
    $pdf->SetX(116);
    $pdf->Cell(49, 5, utf8_decode("PAGOS INSPECCIONES NO MEDIDOS"), 1, 3, 'L', false);
    $pdf->SetY($posy + 55);
    $pdf->SetX(165);
    $pdf->Cell(20, 5, $nPagosNoMedidos, 1, 3, 'L', false);

    $pdf->SetY($posy + 50);
    $pdf->SetX(185);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, $nPTotal, 1, 3, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetY($posy + 65);
    $pdf->SetX(116);
    $pdf->Cell(49, 5, utf8_decode("RECAUDO INSPECCIONES MEDIDOS"), 1, 3, 'L', false);
    $pdf->SetY($posy + 65);
    $pdf->SetX(165);
    $pdf->Cell(20, 5, '$RD ' . number_format($pMedido, 2), 1, 3, 'L', false);

    $pdf->SetY($posy + 70);
    $pdf->SetX(116);
    $pdf->Cell(49, 5, utf8_decode("RECAUDO INSPECCIONES NO MEDIDOS"), 1, 3, 'L', false);
    $pdf->SetY($posy + 70);
    $pdf->SetX(165);
    $pdf->Cell(20, 5, '$RD ' . number_format($pNoMedido, 2), 1, 3, 'L', false);

    $pdf->SetY($posy + 65);
    $pdf->SetX(185);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(21, 10, '$RD ' . number_format($pTotal, 2), 1, 3, 'C', true);

    // Fin Agregado zona Norte

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
