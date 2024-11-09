<?php
/**
 * Created by PhpStorm.
 * User: Tecnologia
 * Date: 27/6/2018
 * Time: 15:07
 */
/*include ('../../clases/class.fpdf.php');*/
include ('../../clases/class.pdfRep.php');
include "../../clases/class.notasFactura.php";
$nota = $_GET["factura"];


if ($nota!='')
{
    $pdf = new PdfNotaCreditoRep();
    $notaFactura= new NotasFactura();
    $cantidadNotas = $notaFactura->GetCantidadNotas($nota);
    oci_fetch($cantidadNotas);
    $cantidadNotas = oci_result($cantidadNotas,"CANTIDAD");

    $n= new NotasFactura();
    $notas = $n->GetNotasFactura($nota);
    $notasFactura = array();
    while(oci_fetch($notas))
    {
        $l = oci_result($notas,"ID_NOTA");
        array_push($notasFactura,$l);

    }

    for($i=0;$i<$cantidadNotas;$i++)
    {

        $notaFactura = new NotasFactura();
        $datosCliente = $notaFactura->GetDatosCliente($notasFactura[$i]);

        while(oci_fetch($datosCliente))
        {
            $proyecto = oci_result( $datosCliente,"ID_PROYECTO");
            $idnota = oci_result( $datosCliente,"ID_NOTA");
            $ncf      = oci_result( $datosCliente,"NCF");

            $ncfModificado = oci_result( $datosCliente,"NCF_MODIFICADO");
            $documento = oci_result( $datosCliente,"DOCUMENTO");
            $nombreCliente  = oci_result( $datosCliente,"NOMBRE_CLIENTE");
            $totalNota = oci_result( $datosCliente,"TOTAL_NOTA");
            $fechaEmision = oci_result( $datosCliente,"FECHA_EMISION");
            $totalNota = oci_result( $datosCliente,"TOTAL_NOTA");
            $tipoNota = oci_result( $datosCliente,"TIPO_NOTA");

        }
        /*echo $proyecto;*/
        $codProyecto=0;
        if ($proyecto=='SD')
        {
            $codProyecto =15;
        }
        else if($proyecto=='BC')
        {
            $codProyecto = 16;
        }

        $n= new NotasFactura();
        $rncProyecto = $n->GetRNCProyecto($codProyecto);
        oci_fetch($rncProyecto);
        $rncProyecto = oci_result($rncProyecto,"RNC");


        $pdf->SetProyecto($proyecto);
        $pdf->setRNC($rncProyecto);
        $pdf->SetFechaEmision($fechaEmision);
        $pdf->SetNCF($ncf);
        $pdf->SetTipoNota($tipoNota);


        //Cuerpo Nota de crédito
        //NCF y NCF Modificado

        $pdf->AddPage();
        $posXNCF=115;
        $posYNCF= 45;
        $pdf->SetFont('Arial','',12);
        $pdf->Text($posXNCF,$posYNCF-5,$idnota);
        $pdf->Text($posXNCF,$posYNCF,"NCF:".$ncf);

        $pdf->setTextColor(209, 58, 48);
        $pdf->SetFont('Arial','B',12);
        $pdf->Text($posXNCF,$posYNCF+4,"NCF Modificado:".$ncfModificado);


        //Datos del cliente
        $pdf->setTextColor(0,0,0);
        $pdf->SetFont('Arial','',12);
        $pdf->Line(15,75,195,75);
        $pdf->Text(22,85,"RNC CLIENTE: ".$documento);
        $pdf->Text(22,89,utf8_decode("NOMBRE O RAZÓN SOCIAL: ".$nombreCliente));
        $pdf->Line(15,95,195,95);

        //Detalle de la nota
        $pdf->Ln(95);
        $pdf->SetFillColor(61, 206, 182);
        $pdf->SetFont('Arial','B',12);
        $pdf->SettextColor(255, 255, 255);
        $pdf->Cell(7,10,'',0,0,'L');
        $pdf->Cell(10,10,'',0,0,'L',True);
        $pdf->Cell(15,10,'CANT.',0,0,'L',True);
        $pdf->Cell(20,10,'',0,0,'L',True);
        $pdf->Cell(30,10,utf8_decode('DESCRIPCION'),0,0,'L',True);
        $pdf->Cell(45,10,'',0,0,'L',True);
        $pdf->Cell(20,10,utf8_decode('ITBIS'),0,0,'L',True);
        $pdf->Cell(10,10,'',0,0,'L',True);
        $pdf->Cell(30,10,utf8_decode('VALOR'),0,0,'L',True);


        $nf = new NotasFactura();
        $detalleNota= $nf->GetDetalleFactura($notasFactura[$i]);

        $posYDetalleNota=125;
        while(oci_fetch($detalleNota))
        {
            $cant = oci_result($detalleNota,"CANT");
            $descripcion = oci_result($detalleNota,"DESCRIPCION");
            $itbis = oci_result($detalleNota,"ITBIS");
            $valor = oci_result($detalleNota,"VALOR");
            $pdf->SetFont('Arial','',12);
            $pdf->SettextColor(0, 0, 0);
            $pdf->Text(27,$posYDetalleNota,$cant);
            $pdf->Text(65,$posYDetalleNota, $descripcion);
            $pdf->Text(140,$posYDetalleNota, $itbis);
            $pdf->Text(170,$posYDetalleNota, "RD$ ".$valor);
            $posYDetalleNota+=6;


        }

        //TOTALES
        $posXTotales = 140;
        $posYTotales=  $posYDetalleNota+15;

        $pdf->Text($posXTotales,$posYTotales,"SUBTOTAL:      RD$ ".$totalNota);
        $pdf->Text($posXTotales,$posYTotales+4,"DESC.:              RD$ 0.00");
        $pdf->Text($posXTotales,$posYTotales+8,"ITBIS:                RD$ 0.00");
        $pdf->SetFont('Arial','B',12);
        $pdf->Text($posXTotales,$posYTotales+12,"TOTAL:");
        $pdf->SetFont('Arial','',12);
        $pdf->Text($posXTotales+30.5,$posYTotales+12,"RD$ ".$totalNota);

    }

    $pdf->Output('NOTA DE CREDITO','I');



}

