<?
include_once "../../clases/class.pdfRep.php";
include_once "../../clases/class.usuario.php";
include_once "../../clases/class.corte.php";
/**
 * Created by PhpStorm.
 * User: Edwin
 * Date: 3/06/2016
 * Time: 11:01 AM
 */

$fecha=$_POST["fecha"];
$proyecto=$_POST["proyecto"];
$gerencia=$_POST["gerencia"];
$contratista=$_POST["selCon"];


function compruebaSaltoHoja($pdf){

    global  $contPy;
    if($contPy>=284){
        $pdf->AddPage();
        $contPy=48;

    }
}


function compruebaSaltoHoja2($pdf){

    global  $contPy;
    if($contPy>=48){
        $pdf->AddPage();
        $contPy=48;

    }
}


//echo $fecha;

$pdf = new pdfAsigCorDiar();
//$edif=new Edificio();

$pdf->AliasNbPages();
$pdf->setFechaGen($fecha);
$pdf->setProyecto($proyecto);
$pdf->AddPage();
$a=new Usuario();
$stid =$a->getUsrAsignadoAsignadorCorteByFecha($fecha,$proyecto,$gerencia,$contratista);
$contPy=48;
$asiviejo="";
while (oci_fetch($stid))
{
    compruebaSaltoHoja($pdf);
    $empleado=oci_result($stid, 'USR_EJE') ;
    $asignador=oci_result($stid, 'USUARIO_ASIGNADOR') ;
    $idAsignador=oci_result($stid, 'ID_ASIGNADOR') ;
    $idAsignado=oci_result($stid, 'ID_ASIGNADO') ;
    $total=oci_result($stid, 'CANTIDAD') ;
    $pdf->SetFont('times',"B",10);
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(19,$contPy,utf8_decode("Empleado: ".substr($empleado,0,19) ));
    if($asiviejo==$asignador){
        $asiviejo=$asignador;
    }else{
        $pdf->Text(85,$contPy,utf8_decode("Nombre Asig: ".$asignador));
        $asiviejo=$asignador;
    }

    $contPy+=1;
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetFillColor(64,128,191);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetLineWidth(0.3);
    $pdf->SetY($contPy);
    $pdf->SetX(19);
    $pdf->Cell(145,5,"",1,3,'',true);

    $contPy+=4;

    $pdf->Text(19,$contPy,utf8_decode("Sector"));
    $pdf->Text(70,$contPy,utf8_decode("Ruta"));
    $pdf->Text(133,$contPy,utf8_decode("Cantidad"));
    $pdf->Text(151,$contPy,utf8_decode("Total"));

    $b= new Corte();
    $contPy+=4;
    $pdf->SetTextColor(0,0,0);
    $stid2=$b->getCantCortGroupSecRutByFecAsig($fecha,$idAsignado,$idAsignador,$proyecto,$gerencia,$contratista);
    $pdf->Text(151,$contPy,utf8_decode($total));
    while (oci_fetch($stid2)){
        $sector=oci_result($stid2, 'ID_SECTOR') ;
        $ruta=oci_result($stid2, 'ID_RUTA') ;
        $cant=oci_result($stid2, 'CANTIDAD') ;

        $pdf->Text(19,$contPy,utf8_decode($sector));
        $pdf->Text(70,$contPy,utf8_decode($ruta));
        $pdf->Text(133,$contPy,utf8_decode($cant));
        $contPy+=4;
        compruebaSaltoHoja($pdf);
    }
    $contPy+=2;
}
compruebaSaltoHoja2($pdf);

$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(64,128,191);
$pdf->SetTextColor(255,255,255);
$pdf->SetLineWidth(0.3);
$pdf->SetY(48);
$pdf->SetX(19);
$pdf->Cell(60,5,"TOTAL PLANIFICADO",1,3,'C',true);

$b= new Corte();
$pdf->SetTextColor(0,0,0);
$stid2=$b->getCortAsigGroupSectorByFech($fecha,$proyecto,$gerencia,$contratista);
$contPy1=48;
$total=0;
while (oci_fetch($stid2)){
    $sector=oci_result($stid2, 'ID_SECTOR') ;
    $cant=oci_result($stid2, 'CANTIDAD') ;
    $total+=$cant;
    $contPy1+=5;
    $pdf->SetY($contPy1);
    $pdf->SetX(19);
    $pdf->Cell(25,5,"SECTOR ".$sector,1,3,'C',false);
    $pdf->SetY($contPy1);
    $pdf->SetX(44);
    $pdf->Cell(35,5,$cant,1,3,'C',false);
}

$pdf->SetTextColor(255,255,255);
$pdf->SetY($contPy1+5);
$pdf->SetX(19);
$pdf->Cell(25,5,"Total",1,3,'C',true);
$pdf->SetTextColor(0,0,0);
$pdf->SetY($contPy1+5);
$pdf->SetX(44);
$pdf->Cell(35,5,$total,1,3,'C',false);



$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(64,128,191);
$pdf->SetTextColor(255,255,255);
$pdf->SetLineWidth(0.3);
$pdf->SetY(48);
$pdf->SetX(85);
$pdf->Cell(60,5,"TIPO DE USUARIOS",1,3,'C',true);

$b= new Corte();
$pdf->SetTextColor(0,0,0);
$stid2=$b->getCortAsigGroupMedByFech($fecha,$proyecto,$gerencia,$contratista);
$contPy=48;
$total=0;
while (oci_fetch($stid2)){
    $medidor=oci_result($stid2, 'MEDIDOR') ;
    $cant=oci_result($stid2, 'CANTIDAD') ;
    if($medidor=="S"){
        $medidor="MEDIDOS";
    }
    if($medidor=="N"){
        $medidor="NO MEDIDOS";
    }
    $total+=$cant;
    $contPy+=5;
    $pdf->SetY($contPy);
    $pdf->SetX(85);
    $pdf->Cell(25,5,$medidor,1,3,'C',false);
    $pdf->SetY($contPy);
    $pdf->SetX(110);
    $pdf->Cell(35,5,$cant,1,3,'C',false);
}

$pdf->SetTextColor(255,255,255);
$pdf->SetY($contPy+5);
$pdf->SetX(85);
$pdf->Cell(25,5,"Total",1,3,'C',true);
$pdf->SetTextColor(0,0,0);
$pdf->SetY($contPy+5);
$pdf->SetX(110);
$pdf->Cell(35,5,$total,1,3,'C',false);


$pdf->SetTextColor(0,0,0);
$pdf->Text(19,$contPy1+20,utf8_decode("Observaciones:"));
$pdf->SetY($contPy+25);
$pdf->SetX(19);
$pdf->Cell(149,30,"",1,3,'C',false);



$nomarch="../../temp/repcordiar".time().".pdf";
$pdf->Output($nomarch,'F');
echo $nomarch;


