<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */
setlocale(LC_MONETARY, 'es_DO');
$proyecto=$_POST['proyecto'];
$proini=$_POST['proIni'];
$profin=$_POST['proFin'];





include "../../clases/class.pdfRep.php";
include "../../clases/class.inmueble.php";
$a =new Inmueble();
$datos=$a->getInmPdcDeudaByProceProy($proini,$proyecto,$profin);
$pdf = new PdfHojRutPdc();
$pdf->setProyecto($proyecto);
$pdf->setProIni($proini);
$pdf->setProFin($profin);

$pdf->SetTextColor(0,0,0);
$pdf->AliasNbPages();
$pdf->AddPage('L');

$y=45;
$secrut='0';
while (oci_fetch($datos)){


    $inmueble=oci_result($datos,"CODIGO_INM");
    $sector=oci_result($datos,"SECTOR");
    $ruta=oci_result($datos,"RUTA");
    $id_proceso=oci_result($datos,"ID_PROCESO");
    $catastro=oci_result($datos,"CATASTRO");
    $deudaCero=oci_result($datos,"DEUDACERO") ;
    $nombre=oci_result($datos,"NOMBRE");
    $direccion=oci_result($datos,"DIRECCION");
    $estado=oci_result($datos,"ID_ESTADO");
    $uso=oci_result($datos,"ID_USO");
    $serial= oci_result($datos,"SERIAL");
    $facturas=oci_result($datos,"FACTURAS");
    $deuda =oci_result($datos,"DEUDA");

    if($y>=200){
        $pdf->AddPage('L');
        $y=45;
    }else if($secrut<>'0' and $secrut<>$sector.$ruta){
        $pdf->AddPage('L');
        $y=45;
    }

    $secrut=$sector.$ruta;

    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('times',"",8);
    $pdf->Text(3,$y,utf8_decode("$inmueble"));
    $pdf->Text(19,$y,ucwords(strtolower(utf8_decode("$sector"))) );
    $pdf->Text(29,$y,ucwords(strtolower(utf8_decode("$ruta"))));
    $pdf->Text(35,$y,substr(ucwords(strtolower(utf8_decode("$id_proceso"))),0,30) );
    $pdf->Text(53,$y,utf8_decode("$catastro"));
    $pdf->Text(90,$y,utf8_decode("$estado"));

    if(trim($deudaCero)=='' && $facturas>9){
        $pdf->Text(102,$y,utf8_decode("$nombre"));
        $pdf->Text(175,$y,utf8_decode("$direccion"));

        $pdf->Text(225,$y,utf8_decode("$uso"));

        $pdf->Text(233,$y,utf8_decode("$serial"));
        $pdf->Text(260,$y,utf8_decode("$facturas"));
        $pdf->Text(275,$y,utf8_decode("RD".money_format('%.2n', $deuda)));
    }else{
        $pdf->SetTextColor(255,0,0);
        $pdf->Text(102,$y,utf8_decode("--------------ESTE INMUEBLE NO APLICA PARA UN PDC, EN CASO DE LLEGAR A OFICINA ESTE NO SE ACEPTARA----------"));
        $pdf->SetTextColor(0,0,0);
        $pdf->Text(260,$y,utf8_decode("$facturas"));
        $pdf->SetTextColor(255,0,0);
        $pdf->Text(275,$y,utf8_decode("---------"));
        $pdf->SetTextColor(0,0,0);

    }

    $y+=5;

}

$nomarch="../../temp/reprutadeudacero".time().".pdf";
$pdf->Output($nomarch,'F');
echo $nomarch;