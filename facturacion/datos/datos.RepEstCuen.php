<?php
/**
 * Created by PhpStorm.
 * User: Edwin
 * Date: 26/05/2016
 * Time: 3:21 PM
 */
//error_reporting(E_ALL);
session_start();
$inm = $_REQUEST["inmueble"];
$tip = $_REQUEST["tip"];
date_default_timezone_set('America/Santo_Domingo');
setlocale(LC_MONETARY, 'es_DO');

if ($tip == "rep") {

    include '../clases/class.pagos.php';
    include '../clases/class.otrosRecaudos.php';
    include '../clases/class.facturas.php';
    include '../clases/class.pdfRep.php';
    include "../clases/class.inmuebles.php";

    function cabecera()
    {
        global $pdf;
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(64, 128, 191);
        $pdf->SetLineWidth(0.1);
        $pdf->SetY(47);
        $pdf->SetX(6);

        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(180, 5, utf8_decode(""), 1, 3, 'C', true);

        $pdf->Text(8, 50, utf8_decode("Fecha"));
        $pdf->Text(25, 50, utf8_decode("Concepto"));
        $pdf->Text(123, 50, utf8_decode("Debe"));
        $pdf->Text(145, 50, utf8_decode("Haber"));
        $pdf->Text(165, 50, utf8_decode("Saldo real"));
    }

    $pdf = new pdfEstCuenta();
    $pdf->AliasNbPages();
    $pdf->setCodSistema($inm);
    $a     = new inmuebles();
    $datos = $a->datosInmueble(" AND I.CODIGO_INM=$inm");

    if (oci_fetch($datos)) {
        $codcli = oci_result($datos, "CODIGO_CLI");
        $pdf->setCliente($codcli);
        $grupoCli = oci_result($datos, "COD_GRUPO");
        $pdf->setGrupo($grupoCli);

        $edificio = oci_result($datos, "DESC_URBANIZACION");
        $pdf->setUrbanizacion($edificio);

        $direccion = oci_result($datos, "DIRECCION");
        $pdf->setDireccion($direccion);

        $nombre = oci_result($datos, "NOMBRE");
        $pdf->setNombreCliente($nombre);

        $zona = oci_result($datos, "ID_ZONA");
        $pdf->setZona($zona);

        $catastro = oci_result($datos, "CATASTRO");
        $pdf->setCatastro($catastro);

        $proyecto = oci_result($datos, "PROYECTO");
        $pdf->setProyecto($proyecto);

        $proceso = oci_result($datos, "ID_PROCESO");
        $pdf->setProceso($proceso);
    }
    $pdf->AddPage();

    cabecera();

    $b     = new facturas();
    $datos = $b->estcon2(0, 999999, $inm);

    $fecha = getdate();
    $pdf->SetTextColor(0, 0, 0);

    $l         = new facturas();
    $registros = $l->todasFacturas($inm);

    $o          = new Pago();
    $registros2 = $o->todosPagos($inm);

    $p          = new OtrosRec();
    $registros3 = $p->todosOtrosRec($inm);

    $p          = new Pago();
    $registros4 = $p->todosSaldos($inm);

    $bandPago   = false;
    $bandOtrosR = false;
    $bandFact   = false;
    $bandSf     = false;

    $entrowhile   = false;
    $noentrowhile = true;

    $datospago = true;
    $datosotro = true;
    $datosfac  = true;
    $datosSf   = true;

    $saldo = 0;

    $totalDebe  = 0;
    $totalHaber = 0;
    $totalSaldo = 0;
    $posy       = 0;
    while ($bandPago || $bandOtrosR || $bandFact || $noentrowhile || $bandSf) {

        if ($noentrowhile == false) {
            if ($posy + 52 > 265) {
                $posy = 0;
                $pdf->AddPage();
                cabecera();
            }
            $pdf->SetY(52 + $posy);
            $pdf->SetX(6);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(180, 5, utf8_decode(""), 1, 0, 'C', false);
            if ($fechcomp <= $fechcomp2 && $fechcomp <= $fechcomp3 && $fechcomp <= $fechcomp4) {
                $saldo += $total;
                $pdf->Text(8, 56 + $posy, utf8_decode($fechRea));
                $pdf->Text(25, 56 + $posy, utf8_decode(strtolower($desc)));
                $pdf->Text(123, 56 + $posy, utf8_decode("RD" . money_format('%.2n', $total)));
                $pdf->Text(145, 56 + $posy, utf8_decode("RD" . money_format('%.2n', 0)));
                $pdf->Text(165, 56 + $posy, utf8_decode("RD" . money_format('%.1n', $saldo)));
                $totalDebe += $total;

                $bandFact = false;
                $posy += 5;
            } else if ($fechcomp2 <= $fechcomp && $fechcomp2 <= $fechcomp3 && $fechcomp2 <= $fechcomp4) {
                $saldo -= $total2;
                $pdf->Text(8, 56 + $posy, utf8_decode($fechRea2));
                $pdf->Text(25, 56 + $posy, utf8_decode(strtolower($desc2)));
                $pdf->Text(123, 56 + $posy, utf8_decode("RD" . money_format('%.2n', 0)));
                $pdf->Text(145, 56 + $posy, utf8_decode("RD" . money_format('%.2n', $total2)));
                $pdf->Text(165, 56 + $posy, utf8_decode("RD" . money_format('%.1n', $saldo)));
                $totalHaber += $total2;
                $bandPago = false;
                $posy += 5;

            } else if ($fechcomp4 <= $fechcomp && $fechcomp4 <= $fechcomp2 && $fechcomp4 <= $fechcomp3) {
                $saldo += $total4;
                $pdf->Text(8, 56 + $posy, utf8_decode($fechRea4));
                $pdf->Text(25, 56 + $posy, utf8_decode(strtolower($desc4)));
                $pdf->Text(123, 56 + $posy, utf8_decode("RD" . money_format('%.2n', $total4)));
                $pdf->Text(145, 56 + $posy, utf8_decode("RD" . money_format('%.2n', 0)));
                $pdf->Text(165, 56 + $posy, utf8_decode("RD" . money_format('%.1n', $saldo)));
                $totalDebe += $total4;
                $bandSf = false;
                $posy += 5;

            } else {
                //  echo "entro "+$posy;
                $totalHaber += $total3;
                $saldo -= $total3;
                $pdf->Text(8, 56 + $posy, utf8_decode($fechRea3));
                $pdf->Text(25, 56 + $posy, utf8_decode(strtolower($desc3)));
                $pdf->Text(123, 56 + $posy, utf8_decode("RD" . money_format('%.2n', 0)));
                $pdf->Text(145, 56 + $posy, utf8_decode("RD" . money_format('%.2n', $total3)));
                $pdf->Text(165, 56 + $posy, utf8_decode("RD" . money_format('%.1n', $saldo)));
                $bandOtrosR = false;
                $posy += 5;

                $pdf->SetY(52 + $posy);
                $pdf->SetX(6);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(180, 5, utf8_decode(""), 1, 0, 'C', false);

                $totalDebe += $total3;
                $saldo += $total3;
                $pdf->Text(8, 56 + $posy, utf8_decode($fechRea3));
                $pdf->Text(25, 56 + $posy, utf8_decode(strtolower($desc3 . ' (otro recaudo aplicado)')));
                $pdf->Text(145, 56 + $posy, utf8_decode("RD" . money_format('%.2n', 0)));
                $pdf->Text(123, 56 + $posy, utf8_decode("RD" . money_format('%.2n', $total3)));
                $pdf->Text(165, 56 + $posy, utf8_decode("RD" . money_format('%.1n', $saldo)));
                $bandOtrosR = false;
                $posy += 5;

            }
        }

        $noentrowhile = false;
        if ($bandFact == false && $datosfac) {
            if (oci_fetch($registros)) {
                $fecha    = oci_result($registros, 'FEC_EXPEDICION');
                $desc     = oci_result($registros, 'DESCRIPCION');
                $total    = oci_result($registros, 'TOTAL');
                $fechcomp = oci_result($registros, 'FECHCOMP');
                $fechRea  = oci_result($registros, 'FECHCOMP2');
                $bandFact = true;

            } else {

                $fechcomp = 99999999;
                $bandFact = false;
                $datosfac = false;
            }
        }

        if ($bandPago == false && $datospago) {
            if (oci_fetch($registros2)) {
                $fecha2    = oci_result($registros2, 'FECHA_PAGO');
                $desc2     = oci_result($registros2, 'DESCRIPCION');
                $total2    = oci_result($registros2, 'IMPORTE');
                $fechcomp2 = oci_result($registros2, 'FECHCOMP');
                $fechRea2  = oci_result($registros2, 'FECHCOMP2');
                $bandPago  = true;
            } else {

                $fechcomp2 = 99999999;
                $bandPago  = false;
                $datospago = false;
            }
        }

        if ($bandSf == false && $datosSf) {
            if (oci_fetch($registros4)) {
                $fecha4    = oci_result($registros4, 'FECHA');
                $desc4     = 'Saldo a favor por ' . oci_result($registros4, 'DESCRIPCION');
                $total4    = oci_result($registros4, 'IMPORTE');
                $fechcomp4 = oci_result($registros4, 'FECHCOMP');
                $fechRea4  = oci_result($registros4, 'FECHCOMP2');
                $bandSf    = true;
            } else {

                $fechcomp4 = 99999999;
                $bandSf    = false;
                $datosSf   = false;
            }
        }

        if ($bandOtrosR == false && $datosotro) {
            if (oci_fetch($registros3)) {
                $fecha3     = oci_result($registros3, 'FECHA');
                $desc3      = oci_result($registros3, 'DESCRIPCION');
                $total3     = oci_result($registros3, 'IMPORTE');
                $fechcomp3  = oci_result($registros3, 'FECHCOMP');
                $fechRea3   = oci_result($registros3, 'FECHCOMP2');
                $bandOtrosR = true;
            } else {
                $fechcomp3  = 99999999;
                $bandOtrosR = false;
                $datosotro  = false;
            }
        }
    }

    $pdf->Text(112, 57 + $posy, utf8_decode("Total: "));
    $pdf->Line(123, $posy + 53, 137, $posy + 53);
    $pdf->Text(123, 57 + $posy, utf8_decode("RD" . money_format('%.2n', $totalDebe)));

    $pdf->Line(145, $posy + 53, 159, $posy + 53);
    $pdf->Text(145, 57 + $posy, utf8_decode("RD" . money_format('%.2n', $totalHaber)));

    $pdf->Line(155, $posy + 53, 159, $posy + 53);
    $pdf->Text(165, 57 + $posy, utf8_decode("RD" . money_format('%.2n', $totalDebe - $totalHaber)));

    $pdf->Output("Libro.pdf", 'I');

}
