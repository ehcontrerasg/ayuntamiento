<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */

function cabeceraZona($zona,$yIni){
    global $pdf;

    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('times','B',15);
    $pdf->Text(20,$yIni+5,$zona);

    $pdf->SetDrawColor(0,0,0);
    $pdf->SetFillColor(64,128,191);

    $pdf->SetLineWidth(0.3);
    $pdf->SetY($yIni);
    $pdf->SetX(4);
    $pdf->Cell(15,6,"Zona",1,3,'C',true);

    $pdf->SetY($yIni+6);
    $pdf->SetX(1);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('times','B',8);
    $pdf->Cell(410,6,utf8_decode('Codigo                          Dirección                                              Urb.                                                Cliente                                                                                                                  Documento               Proceso              Catastro                            Med.         Serial              Emp.   Ger.  Cal.      Uso      Act.   Uni.       Sum            Contrato            Estado      Fecha'),1,3,'L',true);


}


function pieZona($Total,$y){
    global $pdf;

    $pdf->SetTextColor(255,255,255);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetFillColor(64,128,191);
    $pdf->SetLineWidth(0.3);
    $pdf->SetY($y-4);
    $pdf->SetX(4);
    $pdf->SetFont('times','B',11);
    $pdf->Cell(45,6,"Total Inmuebles Por zona",1,3,'C',true);
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(50,$y+1,utf8_decode($Total));

}

function pieGral($Total,$y){
    global $pdf;

    $pdf->SetTextColor(255,255,255);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetFillColor(64,128,191);
    $pdf->SetLineWidth(0.3);
    $pdf->SetY($y-4);
    $pdf->SetX(4);
    $pdf->SetFont('times','B',11);
    $pdf->Cell(70,6,"Total General",1,3,'C',true);
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(80,$y+1,utf8_decode($Total));

}



include "../../clases/class.pdfRep.php";
include "../../clases/class.medidor.php";
$factura = $_POST["fac"];

$b= new Medidor();
$datos2=$b->getFecMinMaxInsByFact($factura);
if(oci_fetch($datos2)){
    $fecMax=oci_result($datos2,"MAXIMA");
    $fecMin=oci_result($datos2,"MINIMA");
}


$a =new Medidor();
$datos=$a->getMedInmByFact($factura);
$pdf = new PdfHojFacCaasd('P',  'mm',   'A3');
$pdf->setFechMax($fecMax);
$pdf->setFechMin($fecMin);
$pdf->setFechaGen($fecMin);
$pdf->SetTextColor(0,0,0);
$pdf->AliasNbPages();
$pdf->AddPage('L');
$zonaAnt='0';
$totZona;
$totGral=0;
$y=40;
while (oci_fetch($datos)){

    $zona=oci_result($datos,"ID_ZONA");
    $codSis=oci_result($datos,"CODIGO_INM");
    $direccion=oci_result($datos,"DIRECCION");
    $urb=oci_result($datos,"DESC_URBANIZACION");
    $cliente=oci_result($datos,"NOMBRE");
    $documento=oci_result($datos,"DOCUMENTO");
    $proceso=oci_result($datos,"ID_PROCESO");
    $catastro=oci_result($datos,"CATASTRO");
    $medidor=oci_result($datos,"MARCA_MEDNUEVO") ;
    $serial=oci_result($datos,"SERIAL_MEDNUEVO");
    $emplazamiento=oci_result($datos,"DESC_EMPLAZAMIENTO");
    $gerencia=oci_result($datos,"ID_GERENCIA");
    $calibre=oci_result($datos,"DESC_CALIBRE");
    $uso= oci_result($datos,"ID_USO");
    $act=oci_result($datos,"ID_ACTIVIDAD");
    $unidades =oci_result($datos,"UNIDADES_HAB");
    $suministro=oci_result($datos,"SUMINISTRO");
    $contrato=      oci_result($datos,"CONTRATO");
    $estado=      oci_result($datos,"ID_ESTADO");
    $fecha=       oci_result($datos,"FECHA_REEALIZACION");

    //$fecha = date('d-m-Y',strtotime($fecha));
    $pdf->setFechaGen($fecha);
    if($zonaAnt<>$zona){
        if($y>180){
            $pdf->AddPage('L');
            $y=40;
        }
        if($zonaAnt<>'0'){
            if($y==40){pieZona($totZona,$y+4);
                $totZona=0;
                $y+=12;
            }else
            {
                pieZona($totZona,$y);
                $totZona=0;
                $y+=8;
            }
        }

        cabeceraZona($zona,$y);
        $zonaAnt=$zona;
        $y+=15;
    }else{
        if($y>200){
            $pdf->AddPage('L');
            $y=40;
            cabeceraZona($zona,$y);
            $y+=15;
        }
    }
    $totZona+=1;
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(3,$y,utf8_decode("$codSis"));
    $pdf->Text(20.5,$y,ucwords(strtolower(utf8_decode("$direccion"))) );
    $pdf->Text(70,$y,ucwords(strtolower(utf8_decode("$urb"))));
    $pdf->Text(110,$y,substr(ucwords(strtolower(utf8_decode("$cliente"))),0,100) );
  //  $pdf->Text(70,$y,substr(ucwords(strtolower(utf8_decode("$cliente"))),0,30) );
    $pdf->Text(203,$y,utf8_decode("$documento"));
 //   $pdf->Text(112,$y,utf8_decode("$documento"));
    $pdf->Text(224,$y,utf8_decode("$proceso"));
 //   $pdf->Text(131,$y,utf8_decode("$proceso"));
    $pdf->Text(245,$y,utf8_decode("$catastro"));
  //  $pdf->Text(149,$y,utf8_decode("$catastro"));
    $pdf->Text(275,$y,utf8_decode("$medidor"));
   // $pdf->Text(174,$y,utf8_decode("$medidor"));
    $pdf->Text(288,$y,utf8_decode("$serial"));
 //   $pdf->Text(182,$y,utf8_decode("$serial"));
    $pdf->Text(305,$y,utf8_decode("$emplazamiento"));
   // $pdf->Text(195,$y,utf8_decode("$emplazamiento"));
    $pdf->Text(315,$y,utf8_decode("$gerencia"));
   // $pdf->Text(207,$y,utf8_decode("$gerencia"));
    $pdf->Text(320,$y,utf8_decode("$calibre"));
 //   $pdf->Text(214,$y,utf8_decode("$calibre"));
    $pdf->Text(330,$y,utf8_decode("$uso"));
  //  $pdf->Text(222,$y,utf8_decode("$uso"));
    $pdf->Text(340,$y,utf8_decode("$act"));
  //  $pdf->Text(228,$y,utf8_decode("$act"));
    $pdf->Text(348,$y,utf8_decode("$unidades"));
 //   $pdf->Text(235,$y,utf8_decode("$unidades"));
    $pdf->Text(355,$y,utf8_decode("$suministro"));
  //  $pdf->Text(242,$y,utf8_decode("$suministro"));
    $pdf->Text(365,$y,utf8_decode("$contrato"));
 //   $pdf->Text(252,$y,utf8_decode("$contrato"));
    $pdf->Text(390,$y,utf8_decode("$estado"));
   // $pdf->Text(277,$y,utf8_decode("$estado"));
    $pdf->Text(400,$y,utf8_decode("$fecha"));
    //$pdf->Text(285,$y,utf8_decode("$fecha"));

    $y+=5;
    $totGral++;

}
if($y>=190){
    $pdf->AddPage('L');
    $y=40;
    cabeceraZona($zona,$y);
    $y+=15;
}
pieZona($totZona,$y+4);
pieGral($totGral,$y+15);
$nomarch="../../temp/FacturaMedCaasd".time().".pdf";
$pdf->Output($nomarch,'F');
echo $nomarch;