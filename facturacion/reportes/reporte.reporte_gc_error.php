<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */
ini_set('memory_limit', '256M'); 

include_once "../../clases/class.pdfRep.php";
include_once '../clases/class.fn_oracle.php';


$pdf = new PdfHojRepTar();
$fn_oracle = new fn_oracle();
 
$proyecto       = 'SD';
$periodo        = 201709;


$pdf->AliasNbPages();
$pdf->setProyecto($proyecto);
$pdf->setPeriodo($periodo);
$pdf->AddPage();
$datos= $fn_oracle->execQuery($fn_oracle->getQuery(4));

$y=32;
$x = 1;
while (oci_fetch($datos)){

    $id_zona = oci_result($datos,"ID_ZONA");
    $codigo_inm=oci_result($datos,"CODIGO_INM");
    $cod_factura = oci_result($datos,"CONSEC_FACTURA");
    $total_factura = oci_result($datos,"TOTAL");

    $pdf->SetTextColor(0,0,0);
    $pdf->Text(4,$y,"Id Zona:");
    $pdf->Text(23,$y,": $id_zona");
    $y+=4;
    $pdf->Text(4,$y,"Cod. Sistema");
    $pdf->Text(23,$y,": $codigo_inm");
    $y+=4;
    $pdf->Text(4,$y,"Factura:");
    $pdf->Text(23,$y,": $cod_factura");

    $pdf->SetTextColor(255,255,255);
    $y+=6;
    $pdf->SetFillColor(64,128,191);
    $pdf->SetY($y);
    $pdf->SetX(6);
    $pdf->Cell(20,5,utf8_decode("No."),1,3,'L',true);


    $pdf->SetY($y);
    $pdf->SetX(26);
    $pdf->Cell(50,5,utf8_decode("Servicio"),1,3,'L',true);

    $pdf->SetY($y);
    $pdf->SetX(76);
    $pdf->Cell(22,5,utf8_decode("Unidades"),1,3,'L',true);

    $pdf->SetY($y);
    $pdf->SetX(98);
    $pdf->Cell(25,5,utf8_decode("Valor Metro"),1,3,'L',true);

    $pdf->SetY($y);
    $pdf->SetX(123);
    $pdf->Cell(30,5,utf8_decode("Valor facturado"),1,3,'L',true);

    $pdf->SetY($y);
    $pdf->SetX(153);
    $pdf->Cell(40,5,utf8_decode("Valor Debia Facturar"),1,3,'L',true);

    /*$pdf->SetY($y);
    $pdf->SetX(186);
    $pdf->Cell(15,5,utf8_decode("Precio 5"),1,3,'L',true);*/



    
    $y+=5;
    $fn_oracle->factura = $cod_factura;
    $datos2 = $fn_oracle->execQuery($fn_oracle->getQuery(5));
    oci_fetch_all($datos2, $reFacturas);
    $row = count($reFacturas['UNIDADES']);
    $valor_real_total = 0;
    $pdf->SetTextColor(0,0,0);
    for ($i=0; $i < $row; $i++) { 
     
      //  echo $i;
        $desc_servicio = $reFacturas['DESC_SERVICIO'][$i];
        $unidades = $reFacturas['UNIDADES'][$i];
        $valor_metro = $reFacturas['VALOR_METRO'][$i];
        $valor = $reFacturas['VALOR'][$i];

        $pdf->SetFillColor(64,128,191);
        $pdf->SetY($y);
        $pdf->SetX(6);
        $pdf->Cell(20,5,utf8_decode("$i"),1,3,'L',false);


        $pdf->SetY($y);
        $pdf->SetX(26);
        $pdf->Cell(50,5,utf8_decode("$desc_servicio"),1,3,'L',false);

        $pdf->SetY($y);
        $pdf->SetX(76);
        $pdf->Cell(22,5,utf8_decode("$unidades"),1,3,'L',false);

        $pdf->SetY($y);
        $pdf->SetX(98);
        $pdf->Cell(25,5,utf8_decode("$valor_metro"),1,3,'L',false);

        $pdf->SetY($y);
        $pdf->SetX(123);
        $pdf->Cell(30,5,utf8_decode(round("$valor")),1,3,'L',false);

        $valor_real =  $valor_metro * $unidades;
        $valor_real_total =  $valor_real_total + $valor_real;
        $pdf->SetY($y);
        $pdf->SetX(153);
        $pdf->Cell(40,5,utf8_decode(round("$valor_real")),1,3,'L',false);

        $y+=5;
        if($y>280){
            $pdf->AliasNbPages();
            $pdf->setProyecto($proyecto);
            $pdf->setPeriodo($periodo);
            $pdf->AddPage();
            $y=32;
        }
        
    }
    $pdf->SetTextColor(255,255,255);
    $pdf->SetY($y);
    $pdf->SetX(76);
    $pdf->Cell(30,5,utf8_decode('Total Factura:'),1,3,'L',true);

    $pdf->SetTextColor(0,0,0);
    $pdf->SetY($y);
    $pdf->SetX(106);
    $pdf->Cell(17,5,utf8_decode(round("$total_factura", 2)),1,3,'L',false);
    
    $pdf->SetTextColor(255,255,255);
    $pdf->SetY($y);
    $pdf->SetX(123);
    $pdf->Cell(45,5,utf8_decode('Total Debia Facturar:'),1,3,'L',true);

    $pdf->SetTextColor(0,0,0);
    $pdf->SetY($y);
    $pdf->SetX(168);
    $pdf->Cell(25,5,utf8_decode(round("$valor_real_total", 2)),1,3,'L',false);
    
    $y+=18;

    if($y>270){
        $pdf->AliasNbPages();
        $pdf->setProyecto($proyecto);
        $pdf->setPeriodo($periodo);
        $pdf->AddPage();
        $y=32;
    }    
}

$nomarch="../../temp/RepTar".time().".pdf";
$pdf->Output($nomarch,'F');
header ("Location: $nomarch"); 
//echo $nomarch;