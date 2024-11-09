<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */
setlocale(LC_MONETARY, 'es_DO');




include_once "../../clases/class.pdfRep.php";
include_once "../../clases/class.inmueble.php";
include_once '../clases/classPqrs.php';

$pro=$_POST["proyecto"];
$login=$_POST["login"];
$fecIni=$_POST["fecIni"];
$fecFin=$_POST["fecFin"];
$ofIni=$_POST["ofIni"];
$ofFin=$_POST["ofFin"];

$a=new PQRs();
$datos=$a->datosInteraccion($pro,$ofIni,$ofFin,$fecIni,$fecFin,$login,9999999999,0,$where);
$pdf = new PdfInteraccDiar();
$pdf->setProyecto($pro);
$pdf->AddPage('L');

$pdf->SetTextColor(0,0,0);
$pdf->AliasNbPages();


$y=45;
$secrut='0';
while (oci_fetch($datos)){


    $numero=oci_result($datos,"RNUM");
    $fecha=oci_result($datos,"FECREG");
    $asunto=oci_result($datos,"DESC_MOTIVO_REC");
    $texto=oci_result($datos,"DESCRIPCION");
    $usuario=oci_result($datos,"LOGIN");
    $inmueble=oci_result($datos,"COD_INMUEBLE");



    if($y>=200){

        $pdf->setProyecto($pro);
        $pdf->AddPage('L');
        $y=45;
    }

    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('times',"",8);
    $pdf->Text(2,$y,utf8_decode("$numero"));
    $pdf->Text(10,$y,utf8_decode("$fecha"));
    $pdf->Text(36,$y,utf8_decode("$asunto") );
    $pdf->Text(90,$y,substr(utf8_decode("$texto"),0,100));
    $pdf->Text(260,$y,utf8_decode("$usuario"));
    $pdf->Text(280,$y,utf8_decode("$inmueble"));


    $y+=5;

}

$nomarch="../../temp/repIntDiar".time().".pdf";
$pdf->Output($nomarch,'F');
echo $nomarch;