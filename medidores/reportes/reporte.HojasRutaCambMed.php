<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */

include "../../clases/class.pdfRep.php";
include "../../clases/class.medidor.php";


$pdf = new PdfHojCamb();
$a =new Medidor();
$proyecto = $_POST["proyecto"];
$proIni   = $_POST["proini"];
$proFin   = $_POST["profin"];
$fecIni   = $_POST["fecIni"];
$fecFin   = $_POST["fecFin"];
$codSis   = $_POST["codsis"];
$manIni   = $_POST["manini"];
$manFin   = $_POST["manfin"];
$medidor  = $_POST["medido"];
$estInm   = $_POST["estado"];
$usr_asignado   = $_POST["usr_asignado"];


$datos=$a->getOrdenesByAcuProManMedEst($proyecto,$proIni,$proFin,$codSis,$manIni,$manFin,$medidor,$estInm,$usr_asignado,$fecIni,$fecFin);
while (oci_fetch($datos)){
    $tipo=oci_result($datos,"TIPO_ORDEN");
    $orden=oci_result($datos,"ID_ORDEN");
    $fech_planficacion=oci_result($datos,"FECHA");
    $cod_sis=oci_result($datos,"CODIGO_INM");
    $catastro=oci_result($datos,"CATASTRO");
    $proceso=oci_result($datos,"ID_PROCESO");
    $direccion=oci_result($datos,"DIRECCION");
    $motivo=oci_result($datos,"MOTIVO_CAMB")." ".oci_result($datos,"DESCMOT") ;
    $descripcion=oci_result($datos,"DESCORDEN");
    $cliente=oci_result($datos,"NOMBRE");
    $inquilino=oci_result($datos,"NOMBRE");
    $telefono=oci_result($datos,"TELEFONO");

    $empEje= oci_result($datos,"LOGIN");
    $contEje=oci_result($datos,"CONTRATISTA");
    $EmpOrd =oci_result($datos,"LOGINGENORDEN");
    $contOrd=oci_result($datos,"LOGINCONT");

    $medidor=      oci_result($datos,"MEDIDOR");
    $calible=      oci_result($datos,"DESC_CALIBRE");
    $serial=       oci_result($datos,"SERIAL");
    $emplazamiento=oci_result($datos,"DESC_EMPLAZAMIENTO");

    if($tipo=='C'){
        $desctipo="CAMBIO";
    }else{
        $desctipo="INSTALACION";
    }
    $pdf->SetTextColor(0,0,0);
    $pdf->setTipAccion($tipo);
    $pdf->setProyecto($proyecto);
    $pdf->setFechaGen($fech_planficacion);
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetY(27);
    $pdf->SetX(3);
    $pdf->Cell(202,256,"",1,3,'C',false);


    $pdf->SetFont('times','B',10);
    $pdf->Text(7,32,"ORDEN DE $desctipo NRO. :");
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetFillColor(64,128,191);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetLineWidth(0.3);
    $pdf->SetY(28);
    $pdf->SetX(65);
    $pdf->Cell(27,5,$orden,1,3,'C',true);

    $pdf->SetTextColor(0,0,0);
    $pdf->Text(103,32,"FECHA DE PLANIFICACION:");
    $pdf->SetY(28);
    $pdf->SetX(154);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(30,5,$fech_planficacion,1,3,'C',true);


    $pdf->SetFont('times','B',11);
    $pdf->SetTextColor(64,128,191);
    $pdf->Text(7,39,"Codigo Sistema");
    $pdf->Text(7,44,"Id inmueble");
    $pdf->Text(7,49,"Id proceso");
    $pdf->Text(7,54,utf8_decode("Dirección"));
    $pdf->Text(7,59,"Motivo");
    $pdf->Text(7,64,utf8_decode("Descripcion"));

    $pdf->SetFont('times','',11);
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(34,39,":$cod_sis");
    $pdf->Text(34,44,":$catastro");
    $pdf->Text(34,49,":$proceso");
    $pdf->Text(34,54,utf8_decode(":$direccion"));
    $pdf->Text(34,59,":$motivo");
    $pdf->Text(34,64,utf8_decode(":$descripcion"));


    $pdf->SetFont('times','B',11);
    $pdf->SetTextColor(64,128,191);
    $pdf->Text(103,39,"Cliente");
    $pdf->Text(103,44,"Inquilino");
    $pdf->Text(103,49,"Telefono");

    $pdf->SetFont('times','',11);
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(122,39,utf8_decode($cliente));
    $pdf->Text(122,44,utf8_decode($inquilino));
    $pdf->Text(122,49,$telefono);

    $pdf->SetFont('times','B',13);
    $pdf->Text(7,73,utf8_decode("EJECUCIÓN"));
    $pdf->Text(103,73,"SOLICITUD");

    $pdf->SetFont('times','B',11);
    $pdf->SetTextColor(64,128,191);
    $pdf->Text(7,79,"Empleado");
    $pdf->Text(7,84,"Contratista");

    $pdf->Text(103,79,"Empleado");
    $pdf->Text(103,84,"Contratista");

    $pdf->SetFont('times','',11);
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(27,79,":$empEje");
    $pdf->Text(27,84,":$contEje");

    $pdf->Text(123,79,":$EmpOrd");
    $pdf->Text(123,84,":$contOrd");

    $pdf->SetY(88);
    $pdf->SetX(6);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(85,6,"MEDIDOR RETIRADO",1,3,'C',true);

    $pdf->SetY(88);
    $pdf->SetX(100);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(85,6,"MEDIDOR INSTALADO",1,3,'C',true);


    $pdf->SetFont('times','B',11);
    $pdf->SetTextColor(64,128,191);
    $pdf->Text(7,98,"Marca");
    $pdf->Text(7,103,"Calibre");
    $pdf->Text(7,108,"Serial");
    $pdf->Text(7,113,"Lectura");
    $pdf->Text(7,118,"Emplazamiento");

    $pdf->SetFont('times','',11);
    $pdf->SetTextColor(0,0,0);
    $pdf->Text(37,98,':'.$medidor);
    $pdf->Text(37,103,':'.$calible);
    $pdf->Text(37,108,':'.$serial);
    $pdf->Text(37,118,':'.$emplazamiento);

    $pdf->SetFont('times','B',11);
    $pdf->SetTextColor(64,128,191);
    $pdf->Text(100,98,"Marca");
    $pdf->Text(100,103,"Calibre");
    $pdf->Text(100,108,"Serial");
    $pdf->Text(100,113,"Lectura");
    $pdf->Text(100,118,"Emplazamiento");
    $pdf->Text(100,123,utf8_decode("Fecha de Instalación"));
    $pdf->Text(100,128,utf8_decode("Ubicación"));

    $pdf->SetFont('times','B',8);
    $pdf->SetY(125);
    $pdf->SetX(44);
    $pdf->Cell(4,4,"",1,3,'C',false);
    $pdf->SetY(125);
    $pdf->SetX(86);
    $pdf->Cell(4,4,"",1,3,'C',false);
    $pdf->Text(7,128,utf8_decode("ENTREGADO AL USUARIO"));
    $pdf->Text(50,128,utf8_decode("LLEVADO A LA EMPRESA"));

    $pdf->SetY(130);
    $pdf->SetX(4);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('times','B',11);
    $pdf->Cell(200,7,"CONTROL DE MATERIALES",1,3,'C',true);

    $pdf->SetFont('times','B',8);
    $pdf->SetY(137);
    $pdf->SetX(4);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(73,5,"Material",1,3,'C',false);

    $pdf->SetY(137);
    $pdf->SetX(77);
    $pdf->Cell(27,5,"Cantidad",1,3,'C',false);

    $pdf->SetY(137);
    $pdf->SetX(104);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(73,5,"Material",1,3,'C',false);

    $pdf->SetY(137);
    $pdf->SetX(177);
    $pdf->Cell(27,5,"Cantidad",1,3,'C',false);


    $pdf->SetY(142);
    $pdf->SetX(4);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(73,5,"",1,3,'C',false);

    $pdf->SetY(142);
    $pdf->SetX(77);
    $pdf->Cell(27,5,"",1,3,'C',false);

    $pdf->SetY(142);
    $pdf->SetX(104);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(73,5,"",1,3,'C',false);

    $pdf->SetY(142);
    $pdf->SetX(177);
    $pdf->Cell(27,5,"",1,3,'C',false);

    $pdf->SetY(147);
    $pdf->SetX(4);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(73,5,"",1,3,'C',false);

    $pdf->SetY(147);
    $pdf->SetX(77);
    $pdf->Cell(27,5,"",1,3,'C',false);

    $pdf->SetY(147);
    $pdf->SetX(104);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(73,5,"",1,3,'C',false);

    $pdf->SetY(147);
    $pdf->SetX(177);
    $pdf->Cell(27,5,"",1,3,'C',false);

    $pdf->SetY(152);
    $pdf->SetX(4);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(73,5,"",1,3,'C',false);

    $pdf->SetY(152);
    $pdf->SetX(77);
    $pdf->Cell(27,5,"",1,3,'C',false);

    $pdf->SetY(152);
    $pdf->SetX(104);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(73,5,"",1,3,'C',false);

    $pdf->SetY(152);
    $pdf->SetX(177);
    $pdf->Cell(27,5,"",1,3,'C',false);

    $pdf->SetY(158);
    $pdf->SetX(4);
    $pdf->SetFont('times','B',11);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(200,7,utf8_decode("CHECK LIST: VERIFICACIÓN DE INSTALACIÓN DE MEDIDORES"),1,3,'C',true);

    $pdf->SetY(165);
    $pdf->SetX(4);
    $pdf->MultiCell(31,5,utf8_decode("RESPONSABLE DE REVISIÓN"),1,3,'C',true);

    $pdf->SetY(165);
    $pdf->SetX(35);
    $pdf->Cell(128,10,"",1,3,'C',false);

    $pdf->SetY(165);
    $pdf->SetX(163);
    $pdf->Cell(41,5,utf8_decode("FECHA REVISIÓN"),1,3,'C',true);

    $pdf->SetY(170);
    $pdf->SetX(163);
    $pdf->Cell(41,5,utf8_decode("FECHA REVISIÓN"),1,3,'C',false);

    $pdf->SetY(175);
    $pdf->SetX(4);
    $pdf->SetFont('times','B',9);
    $pdf->Cell(31,5,utf8_decode("SERIAL MEDIDOR"),1,3,'C',true);

    $pdf->SetY(175);
    $pdf->SetX(35);
    $pdf->Cell(169,5,"",1,3,'C',false);



    $pdf->SetY(182);
    $pdf->SetX(4);
    $pdf->SetFont('times','B',11);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(15,5,utf8_decode("NO."),1,3,'C',true);

    $pdf->SetY(182);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("DESCRIPCIÓN"),1,3,'C',true);

    $pdf->SetY(182);
    $pdf->SetX(142);
    $pdf->Cell(8,5,utf8_decode("SI"),1,3,'C',true);

    $pdf->SetY(182);
    $pdf->SetX(150);
    $pdf->Cell(8,5,utf8_decode("NO"),1,3,'C',true);

    $pdf->SetY(182);
    $pdf->SetX(158);
    $pdf->Cell(46,5,utf8_decode("OBSERVACIÓN"),1,3,'C',true);

//////////////////////////
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('times','',10);
    $pdf->SetY(187);
    $pdf->SetX(4);
    $pdf->Cell(15,5,"01",1,3,'C',false);

    $pdf->SetY(187);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("Calidad de hormigón(1:2:3)"),1,3,'l',false);

    $pdf->SetY(187);
    $pdf->SetX(142);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(187);
    $pdf->SetX(150);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(187);
    $pdf->SetX(158);
    $pdf->Cell(46,5,"",1,3,'C',false);

    //////////////////////////
    $pdf->SetY(192);
    $pdf->SetX(4);
    $pdf->Cell(15,5,"02",1,3,'C',false);

    $pdf->SetY(192);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("Caja con medidor de 3 a 5 mm por encima de la cera"),1,3,'l',false);

    $pdf->SetY(192);
    $pdf->SetX(142);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(192);
    $pdf->SetX(150);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(192);
    $pdf->SetX(158);
    $pdf->Cell(46,5,"",1,3,'C',false);

    //////////////////////////
    $pdf->SetY(197);
    $pdf->SetX(4);
    $pdf->Cell(15,5,"03",1,3,'C',false);

    $pdf->SetY(197);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("Instalación perpendicular al predio"),1,3,'l',false);

    $pdf->SetY(197);
    $pdf->SetX(142);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(197);
    $pdf->SetX(150);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(197);
    $pdf->SetX(158);
    $pdf->Cell(46,5,"",1,3,'C',false);

    //////////////////////////
    $pdf->SetY(202);
    $pdf->SetX(4);
    $pdf->Cell(15,5,"04",1,3,'C',false);

    $pdf->SetY(202);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("Señalización de seguridad en la instalación"),1,3,'l',false);

    $pdf->SetY(202);
    $pdf->SetX(142);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(202);
    $pdf->SetX(150);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(202);
    $pdf->SetX(158);
    $pdf->Cell(46,5,"",1,3,'C',false);

    //////////////////////////
   /* $pdf->SetY(207);
    $pdf->SetX(4);
    $pdf->Cell(15,5,"05",1,3,'C',false);

    $pdf->SetY(207);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("Mínimo 3 cm de arena cubriendo tubería de PVC y accesorios"),1,3,'l',false);

    $pdf->SetY(207);
    $pdf->SetX(142);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(207);
    $pdf->SetX(150);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(207);
    $pdf->SetX(158);
    $pdf->Cell(46,5,"",1,3,'C',false);*/

    //////////////////////////
    $pdf->SetY(207);
    $pdf->SetX(4);
    $pdf->Cell(15,5,"05",1,3,'C',false);

    $pdf->SetY(207);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("Espesor de grava compactada"),1,3,'l',false);

    $pdf->SetY(207);
    $pdf->SetX(142);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(207);
    $pdf->SetX(150);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(207);
    $pdf->SetX(158);
    $pdf->Cell(46,5,"",1,3,'C',false);

    //////////////////////////
    $pdf->SetY(212);
    $pdf->SetX(4);
    $pdf->Cell(15,5,"06",1,3,'C',false);

    $pdf->SetY(212);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("Limpieza Final"),1,3,'l',false);

    $pdf->SetY(212);
    $pdf->SetX(142);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(212);
    $pdf->SetX(150);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(212);
    $pdf->SetX(158);
    $pdf->Cell(46,5,"",1,3,'C',false);

    //////////////////////////
    /*$pdf->SetY(222);
    $pdf->SetX(4);
    $pdf->Cell(15,5,"08",1,3,'C',false);

    $pdf->SetY(222);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("Molinado paralelo al bordillo del contén"),1,3,'l',false);

    $pdf->SetY(222);
    $pdf->SetX(142);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(222);
    $pdf->SetX(150);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(222);
    $pdf->SetX(158);
    $pdf->Cell(46,5,"",1,3,'C',false);*/

    //////////////////////////
    $pdf->SetY(217);
    $pdf->SetX(4);
    $pdf->Cell(15,5,"07",1,3,'C',false);

    $pdf->SetY(217);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("Medidor en funcionamiento"),1,3,'l',false);

    $pdf->SetY(217);
    $pdf->SetX(142);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(217);
    $pdf->SetX(150);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(217);
    $pdf->SetX(158);
    $pdf->Cell(46,5,"",1,3,'C',false);

    //////////////////////////
   /* $pdf->SetY(232);
    $pdf->SetX(4);
    $pdf->Cell(15,5,"10",1,3,'C',false);

    $pdf->SetY(232);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("Esta el medidor totalmente horizontal"),1,3,'l',false);

    $pdf->SetY(232);
    $pdf->SetX(142);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(232);
    $pdf->SetX(150);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(232);
    $pdf->SetX(158);
    $pdf->Cell(46,5,"",1,3,'C',false);*/

    //////////////////////////
    $pdf->SetY(222);
    $pdf->SetX(4);
    $pdf->Cell(15,5,"08",1,3,'C',false);

    $pdf->SetY(222);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("Fue retirada la capa vegetal (si aplica)"),1,3,'l',false);

    $pdf->SetY(222);
    $pdf->SetX(142);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(222);
    $pdf->SetX(150);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(222);
    $pdf->SetX(158);
    $pdf->Cell(46,5,"",1,3,'C',false);

    //////////////////////////
    /*$pdf->SetY(242);
    $pdf->SetX(4);
    $pdf->Cell(15,5,"12",1,3,'C',false);

    $pdf->SetY(242);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("Esta colocado el medidor en remate rectangular en hormigón 50x40x15 cm (si aplica) "),1,3,'l',false);

    $pdf->SetY(242);
    $pdf->SetX(142);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(242);
    $pdf->SetX(150);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(242);
    $pdf->SetX(158);
    $pdf->Cell(46,5,"",1,3,'C',false);*/

    //////////////////////////
    /*$pdf->SetY(247);
    $pdf->SetX(4);
    $pdf->Cell(15,5,"13",1,3,'C',false);

    $pdf->SetY(247);
    $pdf->SetX(19);
    $pdf->Cell(123,5,utf8_decode("Fueron repustos remate concretos producto del proceso de instalación"),1,3,'l',false);

    $pdf->SetY(247);
    $pdf->SetX(142);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(247);
    $pdf->SetX(150);
    $pdf->Cell(8,5,"",1,3,'C',false);

    $pdf->SetY(247);
    $pdf->SetX(158);
    $pdf->Cell(46,5,"",1,3,'C',false);*/

    $pdf->Line(8,268,64,268);
    $pdf->Line(137,268,194,268);

    $pdf->SetFont('times','',9);
    $pdf->Text(22,273,"FIRMA DEL CLIENTE");
    $pdf->Text(148,273,"FIRMA DEL SUPERVISOR");

    $pdf->Text(72,273,"AUSENTE");
    $pdf->Text(98,273,"NO QUIERE FIRMAR");


    $pdf->SetY(269.5);
    $pdf->SetX(87);
    $pdf->Cell(4,4,"",1,3,'C',false);
    $pdf->SetY(269.5);
    $pdf->SetX(129);
    $pdf->Cell(4,4,"",1,3,'C',false);

}
$nomarch="../../temp/LibroCambMed".time().".pdf";
$pdf->Output($nomarch,'F');
echo $nomarch;