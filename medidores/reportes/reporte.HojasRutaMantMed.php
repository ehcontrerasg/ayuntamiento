<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */

include_once "../../clases/class.pdfRep.php";
include_once "../../clases/class.medidor.php";
include_once "../../clases/class.factura.php";
include_once "../../clases/class.lectura.php";
include_once "../../clases/class.medidor.php";

$pdf = new PdfHojMant();
$a =new Medidor();
$proyecto       = $_POST["proyecto"];
$proIni         = $_POST["proini"];
$proFin         = $_POST["profin"];
$codSis         = $_POST["codsis"];
$manIni         = $_POST["manini"];
$manFin         = $_POST["manfin"];
//$medidor        = $_POST["medido"];
//$estInm         = $_POST["estado"];
$usr_asignado   = $_POST["usr_asignado"];
$fecIni   = $_POST["fecIni"];
$fecFin   = $_POST["fecFin"];


$ac=new Medidor();
$da=$ac->getActMant();
$i=0;
while ($row = oci_fetch_array($da, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $actividades[$i]=$row;
    $i++;
}
json_encode($actividades);



$datos=$a->getOrdenesManByAcuProMan($proyecto,$proIni,$proFin,$codSis,$manIni,$manFin,$usr_asignado,$fecIni,$fecFin);
while (oci_fetch($datos)){
    $tipo=oci_result($datos,"MOTIVO_MANT");
    $uso=oci_result($datos,"ID_USO");
    $estado=oci_result($datos,"ID_ESTADO");
    $fech_planficacion=oci_result($datos,"FECHA");
    $cod_sis=oci_result($datos,"CODIGO_INM");
    $catastro=oci_result($datos,"CATASTRO");
    $proceso=oci_result($datos,"ID_PROCESO");
    $direccion=oci_result($datos,"DIRECCION");
    $cliente=oci_result($datos,"NOMBRE");
    $orden=oci_result($datos,"ID_ORDEN");
    $empEje= oci_result($datos,"LOGIN");
    $contEje=oci_result($datos,"CONTRATISTA");
    $EmpOrd =oci_result($datos,"LOGINGENORDEN");
    $contOrd=oci_result($datos,"LOGINCONT");
//
    $medidor=      oci_result($datos,"MEDIDOR");
    $calible=      oci_result($datos,"DESC_CALIBRE");
    $serial=       oci_result($datos,"SERIAL");
    $emplazamiento=oci_result($datos,"DESC_EMPLAZAMIENTO");
//
//    if($tipo=='C'){
//        $desctipo="CAMBIO";
//    }else{
//        $desctipo="INSTALACION";
//    }
    $pdf->SetTextColor(0,0,0);
    $pdf->setTipAccion($tipo);
    $pdf->AliasNbPages();
    $pdf->setProyecto($proyecto);
    $pdf->AddPage();
    $pdf->SetY(27);
    $pdf->SetX(3);
    $pdf->Cell(202,256,"",1,3,'C',false);


    $pdf->SetFont('times','B',10);
    $pdf->SetTextColor(64,128,191);
    $pdf->Text(7,32,"Fecha Solicitud:");
    $pdf->Text(82,32,"Contratista:");
    $pdf->Text(160,32,"Orden:");
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('times','B',12);
    $pdf->Text(32,32," $fech_planficacion");
    $pdf->Text(100,32," $contEje");
    $pdf->Text(172,32," $orden");

    $pdf->SetDrawColor(0,0,0);
    $pdf->SetFillColor(64,128,191);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetLineWidth(0.3);
    $pdf->SetY(34);
    $pdf->SetX(6);
    $tab="      ";
    $pdf->Cell(198,5,"Cod. sistema $tab Nombre $tab$tab$tab$tab$tab$tab$tab$tab$tab$tab$tab$tab$tab Uso $tab Estado",1,3,'L',true);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('times','',10);
    $pdf->SetY(39);
    $pdf->SetX(6);
    $pdf->Cell(198,5,"$cod_sis",1,3,'L',false);
    $pdf->Text(38,43," $cliente");
    $pdf->Text(138,43," $uso");
    $pdf->Text(153,43," $estado");

    $pdf->SetY(45);
    $pdf->SetX(6);
    $pdf->Cell(198,13,"",1,3,'L',true);
    $pdf->SetTextColor(255,255,255);
    $pdf->Text(8,50,"ID. Proceso:   ".$proceso);
    $pdf->Text(8,55,"ID. Inmueble: ".$catastro);

    $pdf->Text(75,50,utf8_decode("Dirección:                  ".$direccion));

    $a=new Factura();


    $pdf->Text(75,55,utf8_decode("Facturas Pendientes:  ".$a->getFacPendByInm($cod_sis)));


    $pdf->SetY(58);
    $pdf->SetX(6);
    $pdf->Cell(198,13,"",1,3,'L',false);
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(8,63,"Medidor:   ".$medidor);
    $pdf->Text(58,63,"Serial: ".$serial);
    $pdf->Text(108,63,"Calibre: ".$calible);

    $b=new Lectura();
    $dat=$b->getUltLectByInm($cod_sis);
    if(oci_fetch($dat)){
        $obslect=oci_result($dat,"OBSERVACION_ACTUAL");
        $lecAct=oci_result($dat,"LECTURA_ACTUAL");
    }
    $pdf->Text(158,63,"Obs Lectura: ".$obslect);

    $pdf->Text(8,68,"Lectura:   ".$lecAct);
    $pdf->Text(58,68,"Emplazamiento: ".$emplazamiento);
    $pdf->Text(108,68,"Obs Emplazamiento: "."");
    $pdf->Text(158,68,"Instalado: "."");


    $pdf->SetFont('times','B',13);
    $pdf->Text(7,77,utf8_decode("EJECUCIÓN"));
    $pdf->Text(103,77,"SOLICITUD");

    $pdf->SetFont('times','B',11);
    $pdf->SetTextColor(64,128,191);
    $pdf->Text(7,83,"Verificado por");
    $pdf->Text(7,87,"Contratista");

    $pdf->Text(103,83,"Empleado");
    $pdf->Text(103,87,"Contratista");

    $pdf->SetFont('times','',11);
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(32,83,":$empEje");
    $pdf->Text(27,87,":$contEje");

    $pdf->Text(123,83,":$EmpOrd");
    $pdf->Text(123,87,":$contOrd");



    $pdf->SetTextColor(255,255,255);
    $pdf->SetY(92);
    $pdf->SetX(6);
    $tab="      ";
    $pdf->Cell(198,5,"Actividades realizadas o encontradas",1,3,'C',true);

    $x=0;
    $y=0;
    $con=0;
    foreach($actividades as $obj){
        $idact = $obj{"CODIGO"};
        $desc = $obj{'DESCRIPCION'};
        $pdf->SetFont('times','',11);
        $pdf->SetTextColor(0,0,0);

        $pdf->Text(7+$x,103+$y,"$idact");
        $pdf->Text(13+$x,103+$y,utf8_decode($desc));
        $con++;
        $x+=70;
        if($con==3){
            $con=0;
            $x=0;
            $y+=5;
        }
    }
    $pdf->Text(7+$x,103+$y,utf8_decode("otro:"));
    $pdf->Line(15+$x,103+$y,50+$x,103+$y);

    $pdf->SetY(105+$y);
    $pdf->SetX(6);
    $pdf->SetTextColor(255,255,255);
//    $pdf->Cell(198,5,"Medidor:                               Provisional:                                  Permanente:",1,3,'C',true);
//    $pdf->SetFillColor(255,255,255);
//    $pdf->SetY(105.5+$y);
//    $pdf->SetX(115);
//    $pdf->Cell(4,4,"",1,3,'C',true);
//    $pdf->SetY(105.5+$y);
//    $pdf->SetX(165);
//    $pdf->Cell(4,4,"",1,3,'C',true);


    $pdf->SetTextColor(0,0,0);
   /* $pdf->Text(7,115+$y,utf8_decode("No. serial:"));
    $pdf->Line(24,115+$y,47,115+$y);


    $pdf->Text(50,115+$y,utf8_decode("Marca:"));
    $pdf->Line(61,115+$y,90,115+$y);

    $pdf->Text(95,115+$y,utf8_decode("Diametro:"));
    $pdf->Line(111,115+$y,125,115+$y);
*/
    $pdf->Text(7,115+$y,utf8_decode("Lectura:"));
    $pdf->Line(24,115+$y,47,115+$y);

    $pdf->Text(50,115+$y,utf8_decode("Fecha Lectura:"));
    $pdf->Line(78,115+$y,120,115+$y);

    $pdf->Line(3,119+$y,205,119+$y);

    $pdf->SetFont('times','B',15);
    $pdf->Text(6,125+$y,utf8_decode("Comentarios:"));

    $pdf->SetFont('times','B',10);
   /* $pdf->Text(90,125+$y,utf8_decode("Fecha:"));
    $pdf->Line(3,126+$y,205,126+$y);*/

    for($y;$y+132<260;$y=$y+6){
        $pdf->Line(5,132+$y,203,132+$y);
    }
    $pdf->SetFont('times','',11);
    $pdf->Line(8,270,64,270);
    $pdf->Text(26,274,utf8_decode("Firma usuario"));

    $pdf->Line(108,270,164,270);
    $pdf->Text(125,274,utf8_decode("Firma Supervisor"));


    $pdf->SetFont('times','',10);
    $pdf->SetY(278);

    $pdf->SetX(19);
    $pdf->Cell(4,4,"",1,3,'C',true);
    $pdf->Text(7,282,utf8_decode("Ausente"));
    $pdf->SetY(278);

    $pdf->SetX(53);
    $pdf->Cell(4,4,"",1,3,'C',true);
    $pdf->Text(29,282,utf8_decode("No quiere firmar"));




}
$nomarch="../../temp/LibroMantMed".time().".pdf";
$pdf->Output($nomarch,'F');
echo $nomarch;