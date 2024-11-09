<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */
setlocale(LC_MONETARY, 'es_DO');




include "../../clases/class.pdfRep.php";
include "../../clases/class.corte.php";

$pro=$_POST["proyecto"];
$procIni=$_POST["proIni"];
$procFin=$_POST["proFin"];
$fecIni=$_POST["fechIni"];
$fecFin=$_POST["fechFin"];
$usu=$_POST["usuario"];
$cant=$_POST["cant"];
$a =new Corte();
$datos=$a->getDatCortEfeByProyProcFecUsu($pro,$procIni,$procFin,$fecIni,$fecFin,$usu,$cant);
$pdf = new PdfHojRevAleCort();
$pdf->setProyecto($pro);
$pdf->setFecIni($fecIni);
$pdf->setFecFin($fecFin);
$pdf->setProIni($proini);
$pdf->setProFin($profin);

$pdf->SetTextColor(0,0,0);
$pdf->AliasNbPages();


$y=45;
$secrut='0';
while (oci_fetch($datos)){


    $inmueble=oci_result($datos,"CODIGO_INM");
    $id_proceso=oci_result($datos,"ID_PROCESO");
    $catastro=oci_result($datos,"CATASTRO");
    $nombre=oci_result($datos,"NOMBRE");
    $direccion=oci_result($datos,"DIRECCION");
    $estado=oci_result($datos,"ID_ESTADO");
    $actividad=oci_result($datos,"DESC_ACTIVIDAD");
    $uso=oci_result($datos,"ID_USO");
    $medidor=oci_result($datos,"COD_MEDIDOR");
    $serial=oci_result($datos,"SERIAL");
    $facturas=oci_result($datos,"FACTPEND");
    $deuda=oci_result($datos,"MTOPEN");
    $tipoCort=oci_result($datos,"TIPO_CORTE");
    $obsCorte=oci_result($datos,"OBS_GENERALES");
    $lectura=oci_result($datos,"LECTURA");
    $usuario=oci_result($datos,"USR_EJE");
    $nombreUsuario=oci_result($datos,"NOMBREUSU");

    if($secrut==0){

        $pdf->setInspector($nombreUsuario);
        $pdf->setRuta(substr($id_proceso, 2, 2));
        $pdf->setSector(substr($id_proceso, 0, 2));
        $pdf->AddPage('L');
    }
    if($y>=200){
        $pdf->setInspector($nombreUsuario);
        $pdf->setRuta(substr($id_proceso, 2, 2));
        $pdf->setSector(substr($id_proceso, 0, 2));
        $pdf->AddPage('L');
        $y=45;
    }else if($secrut<>'0' and $secrut<>substr($id_proceso, 0, 4)){
        $pdf->setInspector($nombreUsuario);
        $pdf->setRuta(substr($id_proceso, 2, 2));
        $pdf->setSector(substr($id_proceso, 0, 2));
        $pdf->AddPage('L');
        $y=45;
    }

    $secrut=substr($id_proceso, 0, 4);

    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('times',"",8);
    $pdf->Text(2,$y,utf8_decode("$inmueble"));
    $pdf->Text(14,$y,substr(ucwords(strtolower(utf8_decode("$id_proceso"))),0,30) );
    $pdf->Text(31,$y,utf8_decode("$catastro"));
    $pdf->Text(58,$y,utf8_decode("$nombre"));
    $pdf->Text(110,$y,utf8_decode("$direccion"));
    $pdf->Text(160,$y,utf8_decode("$estado"));
    $pdf->Text(165,$y,utf8_decode("$actividad"));
    $pdf->Text(208,$y,utf8_decode("$uso"));
    $pdf->Text(212,$y,utf8_decode("$medidor"));
    $pdf->Text(219,$y,utf8_decode("$serial"));
    $pdf->Text(236,$y,utf8_decode("$facturas"));
    $pdf->Text(241,$y,utf8_decode("RD".money_format('%.2n',$deuda)));
    $pdf->Text(260,$y,utf8_decode("$tipoCort"));
    $pdf->Text(267,$y,utf8_decode("$lectura"));
    $pdf->Text(272,$y,utf8_decode("$obsCorte"));




    $y+=5;

}

$nomarch="../../temp/revAleatoriaCorte".time().".pdf";
$pdf->Output($nomarch,'F');
echo $nomarch;