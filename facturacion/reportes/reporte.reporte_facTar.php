<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */

include_once "../../clases/class.pdfRep.php";
include_once "../../clases/class.tarifa.php";

$pdf      = new PdfHojRepTar();
$a        = new Tarifa();
$proyecto = $_POST["proyecto"];
$periodo  = $_POST["periodo"];

$pdf->AliasNbPages();
$pdf->setProyecto($proyecto);
$pdf->setPeriodo($periodo);
$pdf->AddPage();
$datos = $a->getServUsoByProyecto($proyecto);

$y = 32;
while (oci_fetch($datos)) {
    $uso         = oci_result($datos, "DESC_USO");
    $servicio    = oci_result($datos, "DESC_SERVICIO");
    $codServicio = oci_result($datos, "COD_SERVICIO");
    $codUso      = oci_result($datos, "COD_USO");

    $pdf->SetTextColor(0, 0, 0);

    $pdf->Text(4, $y, "Concepto");
    $pdf->Text(20, $y, ": $servicio");
    $y += 3;
    $pdf->Text(4, $y, "Uso");
    $pdf->Text(20, $y, ": $uso");

    $y += 6;
    $pdf->SetFillColor(64, 128, 191);
    $pdf->SetY($y);
    $pdf->SetX(6);
    $pdf->Cell(20, 5, utf8_decode("Código"), 1, 3, 'L', true);

    $pdf->SetY($y);
    $pdf->SetX(26);
    $pdf->Cell(100, 5, utf8_decode("Descripción"), 1, 3, 'L', true);

    $pdf->SetY($y);
    $pdf->SetX(126);
    $pdf->Cell(15, 5, utf8_decode("Precio 1"), 1, 3, 'L', true);

    $pdf->SetY($y);
    $pdf->SetX(141);
    $pdf->Cell(15, 5, utf8_decode("Precio 2"), 1, 3, 'L', true);

    $pdf->SetY($y);
    $pdf->SetX(156);
    $pdf->Cell(15, 5, utf8_decode("Precio 3"), 1, 3, 'L', true);

    $pdf->SetY($y);
    $pdf->SetX(171);
    $pdf->Cell(15, 5, utf8_decode("Precio 4"), 1, 3, 'L', true);

    $pdf->SetY($y);
    $pdf->SetX(186);
    $pdf->Cell(15, 5, utf8_decode("Precio 5"), 1, 3, 'L', true);

    $b      = new Tarifa();
    $datos2 = $b->getValRangosByPerProServUso($periodo, $proyecto, $codServicio, $codUso);
    $y += 5;
    while (oci_fetch($datos2)) {
        $codTar  = oci_result($datos2, "CODIGO_TARIFA");
        $descTar = oci_result($datos2, "DESC_TARIFA");
        $rango1  = oci_result($datos2, "PRECIO1");
        $rango2  = oci_result($datos2, "PRECIO2");
        $rango3  = oci_result($datos2, "PRECIO3");
        $rango4  = oci_result($datos2, "PRECIO4");
        $rango5  = oci_result($datos2, "PRECIO5");

        $pdf->SetY($y);
        $pdf->SetX(6);
        $pdf->Cell(20, 5, utf8_decode("$codTar"), 1, 3, 'L', false);

        $pdf->SetY($y);
        $pdf->SetX(26);
        $pdf->Cell(100, 5, utf8_decode("$descTar"), 1, 3, 'L', false);

        $pdf->SetY($y);
        $pdf->SetX(126);
        $pdf->Cell(15, 5, utf8_decode(round("$rango1", 2)), 1, 3, 'L', false);

        $pdf->SetY($y);
        $pdf->SetX(141);
        $pdf->Cell(15, 5, utf8_decode(round("$rango2", 2)), 1, 3, 'L', false);

        $pdf->SetY($y);
        $pdf->SetX(156);
        $pdf->Cell(15, 5, utf8_decode(round("$rango3", 2)), 1, 3, 'L', false);

        $pdf->SetY($y);
        $pdf->SetX(171);
        $pdf->Cell(15, 5, utf8_decode(round("$rango4", 2)), 1, 3, 'L', false);

        $pdf->SetY($y);
        $pdf->SetX(186);
        $pdf->Cell(15, 5, utf8_decode(round("$rango5", 2)), 1, 3, 'L', false);

        $y += 5;
        if ($y > 280) {
            $pdf->AliasNbPages();
            $pdf->setProyecto($proyecto);
            $pdf->setPeriodo($periodo);
            $pdf->AddPage();
            $y = 32;
        }
    }

    $y += 18;

    if ($y > 270) {
        $pdf->AliasNbPages();
        $pdf->setProyecto($proyecto);
        $pdf->setPeriodo($periodo);
        $pdf->AddPage();
        $y = 32;
    }
}

$a     = new Tarifa();
$datos = $a->getUsos($proyecto);

while (oci_fetch($datos)) {
    $uso      = oci_result($datos, "ID_USO");
    $desc_uso = oci_result($datos, "DESC_USO");

    /*
    $codServicio = oci_result($datos,"desc_calibre");
    $codUso = oci_result($datos,"COD_USO");
     */

    $pdf->SetTextColor(0, 0, 0);

    $pdf->Text(4, $y, "Concepto");
    $pdf->Text(20, $y, utf8_decode(": Tarifas Cortes y Reconexión"));
    $y += 3;
    $pdf->Text(4, $y, "Uso");
    $pdf->Text(20, $y, ": $desc_uso");

    $y += 6;
    $pdf->SetFillColor(64, 128, 191);
    $pdf->SetY($y);
    $pdf->SetX(6);
    $pdf->Cell(35, 5, utf8_decode("Diametro"), 1, 3, 'L', true);

    $pdf->SetY($y);
    $pdf->SetX(30);
    $pdf->Cell(35, 5, utf8_decode("Calibre"), 1, 3, 'L', true);

    $pdf->SetY($y);
    $pdf->SetX(54);
    $pdf->Cell(15, 5, utf8_decode("Tarifa"), 1, 3, 'L', true);

    $pdf->SetY($y);
    $pdf->SetX(69);
    $pdf->Cell(15, 5, utf8_decode("Medidor"), 1, 3, 'L', true);

    /*
    $pdf->SetY($y);
    $pdf->SetX(156);
    $pdf->Cell(15,5,utf8_decode("Medidor"),1,3,'L',true);
     */

    $b      = new Tarifa();
    $datos2 = $b->getValTarReconexiones($proyecto, $uso);
    $y += 5;
    while (oci_fetch($datos2)) {

        $codigo_calibre = oci_result($datos2, "CODIGO_CALIBRE");
        $medidor        = oci_result($datos2, "MEDIDOR");
        $valor_tarifa   = oci_result($datos2, "VALOR_TARIFA");
        $desc_calibre   = oci_result($datos2, "DESC_CALIBRE");

        if (empty($codigo_calibre) && empty($medidor) && empty($valor_tarifa) && empty($desc_calibre)) {
            break;
        }

        $pdf->SetY($y);
        $pdf->SetX(6);
        $pdf->Cell(24, 5, utf8_decode("$codigo_calibre"), 1, 3, 'L', false);

        $pdf->SetY($y);
        $pdf->SetX(30);
        $pdf->Cell(24, 5, utf8_decode("$desc_calibre"), 1, 3, 'L', false);

        $pdf->SetY($y);
        $pdf->SetX(54);
        $pdf->Cell(15, 5, utf8_decode(round("$valor_tarifa", 2)), 1, 3, 'L', false);

        $pdf->SetY($y);
        $pdf->SetX(69);
        $pdf->Cell(15, 5, utf8_decode("$medidor"), 1, 3, 'L', false);

        $y += 5;
        if ($y > 280) {
            $pdf->AliasNbPages();
            $pdf->setProyecto($proyecto);
            $pdf->setPeriodo($periodo);
            $pdf->AddPage();
            $y = 32;
        }
    }

    $y += 18;

    if ($y > 270) {
        $pdf->AliasNbPages();
        $pdf->setProyecto($proyecto);
        $pdf->setPeriodo($periodo);
        $pdf->AddPage();
        $y = 32;
    }
}

$nomarch = "../../temp/RepTar" . time() . ".pdf";
$pdf->Output($nomarch, 'F');
echo $nomarch;
