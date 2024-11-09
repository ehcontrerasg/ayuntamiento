<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */

$tip=$_REQUEST["tipo"];
$proy=$_REQUEST["proyecto"];
$sec=$_POST["sector"];
$zon=$_POST["zona"];
$oper=$_POST["operario"];
$oper=$_POST["operario"];
$inm=$_POST["inmueble"];

if($tip=="M")
{
    include_once "../../clases/class.pdfRep.php";
    include_once "../../clases/class.inmueble.php";
    include_once "../../clases/class.pago.php";



    $pdf = new pdfHojCor();

    $pdf->AddPage();
    $pdf->AliasNbPages();
    $cont=0;
    $operAnt="";
    $rutAnt="";
    $s=new Inmueble();
    $stid=$s->getInmCortByProSecZonOper($proy,$sec,$zon,$oper,$inm);
    $numhoja=0;
    $tamCuadro=0;
    while (oci_fetch($stid))
    {
        $fecAct=oci_result($stid, 'FECHACT');
        $orden=oci_result($stid, 'ORDEN');
        $cod_sis=oci_result($stid, 'CODIGO_INM');
        $nombre=oci_result($stid, 'NOMBRE');
        $proceso=oci_result($stid, 'ID_PROCESO');
        $catastro=oci_result($stid, 'CATASTRO');
        $uso=oci_result($stid, 'DESC_USO');
        $telefono=oci_result($stid, 'TELEFONO');
        $direccion=oci_result($stid, 'DIRECCION');
        $urbanizacion=oci_result($stid, 'DESC_URBANIZACION');
        $medidor=oci_result($stid, 'DESC_MED');
        $calibre=oci_result($stid, 'DESC_CALIBRE');
        $serial=oci_result($stid, 'SERIAL');
        $calibre=oci_result($stid, 'DESC_CALIBRE');
        $servicio=oci_result($stid, 'DESC_SERVICIO');
        $operario=oci_result($stid, 'OPERARIO');
        $idUsr=oci_result($stid, 'ID_USUARIO');
        $numhoja ++;

        if($operAnt<>$operario ||$rutAnt<>substr($proceso,0,4)){
            $h=new Inmueble();
            $dat=$h->getCantInmueblesACorByProOperRuta($proy,$idUsr,substr($proceso,0,4));
            if(oci_fetch($dat)){
                $hojTota=oci_result($dat, 'NUMERO');
                $numhoja=1;
            }
        }


        if($cont+$tamCuadro>3){
            $cont=0;
            $pdf->AddPage();
        }elseif($operAnt<>$operario && $operAnt<>""){
            $cont=0;

            $pdf->AddPage();
        }elseif($rutAnt<>substr($proceso,0,4) && $rutAnt<>""){
            $cont=0;

            $pdf->AddPage();
        }

        if($cont==0){
            $posX=0;
            $posY=0;
        }
        else if($cont==1){
            $posX=105;
            $posY=0;
        }
        else if($cont==2){
            $posX=0;
            $posY=148;
        }
        else if($cont==3){
            $posX=105;
            $posY=148;
        }





        if($proy=='SD'){
            $pdf->Image("../../images/LogoCaasd.jpg",9+$posX,1+$posY,12,16);
        }

        if($proy=='BC'){
            $pdf->Image("../../images/coraabo.jpg",9+$posX,1+$posY,12,16);

        }

        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(64,128,191);
        $pdf->SetLineWidth(0.3);

        $pdf->SetTextColor(51,102,204);
        $pdf->SetFont('times','',22);

        if($proy=='SD'){
            $pdf->Text(43+$posX,10+$posY,utf8_decode("CA"));
            $pdf->Text(60.5+$posX,10+$posY,utf8_decode("SD"));
            $pdf->SetFont('times','',29);
            $pdf->Text(53+$posX,10+$posY,utf8_decode("A"));
        }

        if($proy=='BC'){
            $pdf->Text(43+$posX,10+$posY,utf8_decode("CORAABO"));

        }


        $pdf->SetFont('times','',9);

        $hoy = getdate();
        $minuto=$hoy[minutes];
        $hora=$hoy[hours];
        $dia=$hoy[mday];
        $mes=$hoy[mon];
        $agno=$hoy[year];

        $pdf->Text(63+$posX,3+$posY,"$agno/$mes/$dia $hora:$minuto");
        if($proy=='SD'){
            $pdf->Text(39+$posX,14+$posY,utf8_decode("Corporación del Acueducto"));
            $pdf->Text(34+$posX,16.5+$posY,utf8_decode("y Alcantarillado de Santo Domingo"));
        }

        if($proy=='BC'){
            $pdf->Text(39+$posX,14+$posY,utf8_decode("Corporación del Acueducto"));
            $pdf->Text(34+$posX,16.5+$posY,utf8_decode("y Alcantarillado de Boca Chica "));
        }


        $pdf->SetY(19+$posY);
        $pdf->SetX(9+$posX);
        $pdf->SetFont('times','',8.35);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(81.5,15.5,utf8_decode(""),1,3,'C',true);

        if($proy=='SD'){
            $pdf->Text(10+$posX,22+$posY,utf8_decode("La Coorporación del Acueducto y Alcantarillado de Santo Domingo"));
            $pdf->Text(10+$posX,25.5+$posY,utf8_decode("CAASD en  ejercicio de  sus atribuciones  legales, especificamente"));
            $pdf->Text(10+$posX,29+$posY,utf8_decode("las  conferidas  por  la  Ley 498  de  1973  y  el  reglamento 3402 de"));
            $pdf->Text(10+$posX,32.5+$posY,utf8_decode("1973 procede a suspender el servicio."));
        }

        if($proy=='BC'){
            $pdf->Text(10+$posX,22+$posY,utf8_decode("La Coorporación del Acueducto y Alcantarillado de Boca Chica"));
            $pdf->Text(10+$posX,25.5+$posY,utf8_decode("CORAABO en ejercicio de sus atribuciones legales y especificamente"));
            $pdf->Text(10+$posX,29+$posY,utf8_decode("las  conferidas  por  la  Ley 498  de  1973  y  el  reglamento 3402 de"));
            $pdf->Text(10+$posX,32.5+$posY,utf8_decode("1973 procede a suspender el servicio."));
        }


        $pdf->SetFont('times','B',15);
        $pdf->SetTextColor(0,0,0);
        $pdf->Text(23+$posX,39.5+$posY,utf8_decode("ACTA DE SUSPENSION"));

        $pdf->SetY(39.5+$posY);
        $pdf->SetX(9+$posX);
        $pdf->Cell(81.5,23,utf8_decode(""),1,3,'C',false);

        $pdf->SetY(39.5+$posY);
        $pdf->SetX(9+$posX);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('times','B',17);
       $pdf->Cell(81.5,8.5,utf8_decode("Datos Último Pago"),1,3,'C',true);


        $s2= new Pago();
        $datdb=$s2->getUltPagoByInm($cod_sis);
        $fechpago='sin pagos asociados';
        $importe=0;
        while (oci_fetch($datdb)){
            $importe=oci_result($datdb, 'IMPORTE');
            $fechpago=oci_result($datdb, 'FECHA_PAGO');

        }

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('times','B',10);
        $pdf->Text(11+$posX,51+$posY,utf8_decode("Fecha Ult. Pago:"));
//        $pdf->Text(11+$posX,55+$posY,utf8_decode("Mto. Ult. Pago:"));
        $pdf->Text(11+$posX,59+$posY,utf8_decode("Tel Oficina:"));
        $pdf->SetFont('times','',10);
       $pdf->Text(54+$posX,51+$posY,utf8_decode($fechpago));
 //       $pdf->Text(54+$posX,55+$posY,utf8_decode("RD".money_format('%.2n', $importe)));
        $pdf->Text(54+$posX,59+$posY,utf8_decode("809-598-1722"));


        $pdf->SetY(64+$posY);
        $pdf->SetX(9+$posX);
        $pdf->Cell(81.5,30,utf8_decode(""),1,3,'C',false);

        $pdf->SetTextColor(255,255,255);
        $pdf->SetY(64+$posY);
        $pdf->SetX(9+$posX);
        $pdf->Cell(81.5,8.5,utf8_decode("Observación:"),1,3,'C',true);

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('times','B',11);

        $pdf->Text(10+$posX,76+$posY,utf8_decode("Proceso:"));
        $pdf->Text(10+$posX,79.5+$posY,utf8_decode("Catastro:"));
//        $f= new LoteCorte();
//        $cantidad=$f->numFacPend($cod_sis);
//        $pdf->Text(10+$posX,90+$posY,utf8_decode("Facturas pendientes: ".$cantidad));

        $pdf->SetFont('times','',11);
        $pdf->Text(25+$posX,76+$posY,utf8_decode($proceso));
        $pdf->Text(25+$posX,79.5+$posY,utf8_decode($catastro));
        $pdf->SetFont('times','B',10);
//        $pdf->Text(62+$posX,76+$posY,utf8_decode("Monto Deuda RD"));
//        $pdf->Text(62+$posX,79.5+$posY,utf8_decode("RD".money_format('%.2n', $deuda)));

//        $s2= new LoteCorte();
//        $data=$s2->tarifaRec($cod_sis);
//        while(oci_fetch($data)){
//            $reconexion=oci_result($data,'VALOR_TARIFA');
//            $pdf->Text(62+$posX,83+$posY,utf8_decode("Reconexion"));
//            $pdf->Text(62+$posX,86.5+$posY,utf8_decode("RD".money_format('%.2n', $reconexion)));
//        }
//        $pdf->Text(62+$posX,90+$posY,utf8_decode("Total"));
//        $total=$deuda+$reconexion;
//        $pdf->Text(62+$posX,93.5+$posY,utf8_decode("RD".money_format('%.2n', $total)));
//        $deuda=0;
        $pdf->SetFont('times','',11);


        $pdf->Text(11+$posX,99+$posY,utf8_decode("Inmueble:"));
        $pdf->Text(11+$posX,104+$posY,utf8_decode("Marca:"));
        $pdf->Text(11+$posX,109+$posY,utf8_decode("Serial"));
        $pdf->SetFont('times','B',11);
        $pdf->Text(27+$posX,99+$posY,utf8_decode($cod_sis));
        $pdf->Text(27+$posX,104+$posY,utf8_decode($medidor));
        $pdf->Text(27+$posX,109+$posY,utf8_decode($serial));
        $pdf->SetFont('times','',11);
        $pdf->Text(50+$posX,99+$posY,utf8_decode("Lectura:"));
        $pdf->Text(50+$posX,104+$posY,utf8_decode("Fecha Corte:"));
        $pdf->Text(50+$posX,109+$posY,utf8_decode("Tipo de Corte:"));

        $pdf->Line(63+$posX,99+$posY,90+$posX,99+$posY);
        $pdf->Line(70+$posX,104+$posY,90+$posX,104+$posY);
        $pdf->Line(73+$posX,109+$posY,90+$posX,109+$posY);
        $pdf->SetFont('times','',9);
        $pdf->Text(11+$posX,115+$posY,utf8_decode($nombre));
        $pdf->Text(11+$posX,120+$posY,utf8_decode($direccion." ".$urbanizacion));
        $pdf->SetFont('times','',11);
        $pdf->Line(28+$posX,133+$posY,76+$posX,133+$posY);
        //$pdf->Rect(82+$posX,124+$posY,9,5);
        $pdf->Text(83+$posX,128+$posY,$numhoja.' /'. $hojTota);

        if($proy=='SD'){
            $pdf->Text(31+$posX,137+$posY,utf8_decode("Nombre funcionario CAASD"));
        }

        if($proy=='BC'){
            $pdf->Text(31+$posX,137+$posY,utf8_decode("Nombre funcionario CORAABO"));
        }


        $cont=$cont+1;
        $operAnt=$operario;
        $rutAnt=substr($proceso,0,4);
        $paginas++;
    }oci_free_statement($stid);
    $nomarch="../../temp/hojasCorte".time().".pdf";
    $pdf->Output($nomarch,'F');
    echo $nomarch;

}
if($tip=="P")
{
    include_once "../../clases/class.pdfRep.php";
    include_once "../../clases/class.inmueble.php";



    $pdf = new pdfHojCor();
    $pdf->AddPage();
    $cont=0;
    $rutAnt="";
    $operAnt="";
    $s=new Inmueble();
    $stid=$s->getInmCortByProSecZonOper($proy,$sec,$zon,$oper,$inm);
    $numhoja=0;
    while (oci_fetch($stid))
    {


        $fecAct=oci_result($stid, 'FECHACT');
        $orden=oci_result($stid, 'ORDEN');
        $cod_sis=oci_result($stid, 'CODIGO_INM');
        $nombre=oci_result($stid, 'NOMBRE');
        $proceso=oci_result($stid, 'ID_PROCESO');
        $catastro=oci_result($stid, 'CATASTRO');
        $uso=oci_result($stid, 'DESC_USO');
        $telefono=oci_result($stid, 'TELEFONO');
        $direccion=oci_result($stid, 'DIRECCION');
        $urbanizacion=oci_result($stid, 'DESC_URBANIZACION');
        $medidor=oci_result($stid, 'DESC_MED');
        $calibre=oci_result($stid, 'DESC_CALIBRE');
        $serial=oci_result($stid, 'SERIAL');
        $diametro=oci_result($stid, 'DIAMETRO');
        $servicio=oci_result($stid, 'DESC_SERVICIO');
        $operario=oci_result($stid, 'OPERARIO');
        $idUsr=oci_result($stid, 'ID_USUARIO');
        $numhoja ++;

        if($operAnt<>$operario ||$rutAnt<>substr($proceso,0,4)){
            $h=new Inmueble();
            $dat=$h->getCantInmueblesACorByProOperRuta($proy,$idUsr,substr($proceso,0,4));
            if(oci_fetch($dat)){
                $hojTota=oci_result($dat, 'NUMERO');
                $numhoja=1;
            }
        }


        if($cont+$tamCuadro>1){
            $cont=0;
            $pdf->AddPage();
        }elseif($operAnt<>$operario && $operAnt<>""){
            $cont=0;
            $pdf->AddPage();
        }elseif($rutAnt<>substr($proceso,0,4) && $rutAnt<>""){
            $cont=0;
            $pdf->AddPage();
        }
        if($cont==0){
            $posX=0;
            $posY=0;
        }
        else if($cont==1){
            $posX=0;
            $posY=148;
        }

        $pdf->SetY(1+$posY);
        $pdf->SetX(1+$posX);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(64,128,191);
        $pdf->SetLineWidth(0.3);
        $pdf->Cell(107,10,"",1,3,'C',true);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('times','B',15);
        $pdf->Text(4+$posX,6.5+$posY,utf8_decode("ACTA DE SUSPENSION"));
        $pdf->SetFont('times','',13);
        $pdf->Text(74+$posX,4.5+$posY,utf8_decode("Fecha:"));
        $pdf->SetFont('times','',12);
        $pdf->Text(87+$posX,4.5+$posY,utf8_decode($fecAct));
        $pdf->Text(80.5+$posX,9+$posY,utf8_decode($orden));

        $pdf->SetY(11+$posY);
        $pdf->SetX(1+$posX);
        $pdf->SetFont('times','',10);
        $pdf->Cell(67,4,"DATOS DEL INMUEBLE",1,3,'C',true);
        $pdf->SetY(11+$posY);
        $pdf->SetX(68+$posX);
        $pdf->Cell(40,4,"DATOS DEL MEDIDOR",1,3,'C',true);

        $pdf->SetY(15+$posY);
        $pdf->SetX(1+$posX);
        $pdf->Cell(107,29,"",1,3,'C',false);

        $pdf->SetFont('times','',9);
        $pdf->SetTextColor(0,0,0);
        $pdf->Text(3+$posX,18+$posY,utf8_decode("Inmueble:"));
        $pdf->Text(18+$posX,18+$posY,utf8_decode($cod_sis));
        $pdf->Text(3+$posX,21.5+$posY,utf8_decode("Cliente:"));
        $pdf->SetFont('times','',7);
        $pdf->Text(18+$posX,21.5+$posY,utf8_decode(substr($nombre,0,33)));
        $pdf->SetFont('times','',9);
        $pdf->Text(3+$posX,25+$posY,utf8_decode("Proceso:"));
        $pdf->Text(18+$posX,25+$posY,utf8_decode($proceso));
        $pdf->Text(3+$posX,28.5+$posY,utf8_decode("Catastro:"));
        $pdf->Text(18+$posX,28.5+$posY,utf8_decode($catastro));
        $pdf->Text(3+$posX,32+$posY,utf8_decode("Uso:"));
        $pdf->Text(18+$posX,32+$posY,utf8_decode($uso));
        $pdf->Text(3+$posX,35.5+$posY,utf8_decode("Teléfono:"));
        $pdf->Text(18+$posX,35.5+$posY,utf8_decode($telefono));
        $pdf->Text(3+$posX,39+$posY,utf8_decode("Direccion:"));
        $pdf->SetFont('times','',7);
        $pdf->Text(18+$posX,39+$posY,utf8_decode($direccion));
        $pdf->SetFont('times','',9);
        $pdf->Text(3+$posX,42.5+$posY,utf8_decode("Urb:"));
        $pdf->Text(18+$posX,42.5+$posY,utf8_decode($urbanizacion));

        $pdf->Text(68+$posX,18+$posY,utf8_decode("Marca:"));
        $pdf->Text(68+$posX,21.5+$posY,utf8_decode("Calibre:"));
        $pdf->Text(68+$posX,25+$posY,utf8_decode("Serial:"));
        $pdf->Text(68+$posX,28.5+$posY,utf8_decode("Diametro:"));
        $pdf->Text(68+$posX,32+$posY,utf8_decode("Concepto:"));

        $pdf->SetFont('times','B',11);
        $pdf->Text(84+$posX,18+$posY,utf8_decode($medidor));
        $pdf->Text(84+$posX,25+$posY,utf8_decode($serial));
        $pdf->SetFont('times','',11);
        $pdf->Text(84+$posX,28.5+$posY,utf8_decode($dia));
        $pdf->Text(84+$posX,32+$posY,utf8_decode($servicio));
        $pdf->Text(84+$posX,21.5+$posY,utf8_decode($calibre));

        $pdf->SetY(15+$posY);
        $pdf->SetX(68+$posX);
        $pdf->Cell(40,29,"",1,3,'C',false);

        $pdf->SetY(45+$posY);
        $pdf->SetX(1+$posX);
        $pdf->Cell(107,90,"",1,3,'C',false);

        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('times','B',10);
        $pdf->SetY(45+$posY);
        $pdf->SetX(1+$posX);
        $pdf->Cell(107,5,"DETALLE DE LOS RECIBOS PENDIENTES DE COBRO",1,3,'C',true);

        $cont=$cont+1;
        $operAnt=$operario;
        $rutAnt=substr($proceso,0,4);

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('times','',7);

//        $s2= new LoteCorte();
//        $datdb=$s2->factPend($cod_sis);
//        $posfac=0;
//        while (oci_fetch($datdb)){
//            $facDeuda=oci_result($datdb, 'DEUDA');
//            $perDeuda=oci_result($datdb, 'PERIODO');
//
//            $pdf->Text(4+$posX,53+$posfac+$posY,utf8_decode($perDeuda));
//            $pdf->Text(20+$posX,53+$posfac+$posY,utf8_decode($facDeuda));
//            $posfac+=3;
//        }

        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('times','',10);
        $pdf->SetY(87+$posY);
        $pdf->SetX(29+$posX);
        $pdf->Cell(40,4.5,"Detalle de Conceptos",1,3,'C',true);

        $pdf->SetY(87+$posY);
        $pdf->SetX(29+$posX);
        $pdf->Cell(40,20,"",1,3,'C',false);


//        $s2= new LoteCorte();
//        $datdb=$s2->consDeuda($cod_sis);
//        $posfac=0;
//        $pdf->SetTextColor(0,0,0);
//        $pdf->SetFont('times','',7);
//        $deuda=0;
//        while (oci_fetch($datdb)){
//            $cons=oci_result($datdb, 'CONCEPTO');
//            $valor=oci_result($datdb, 'VALOR');
//
//            $pdf->Text(30+$posX,94+$posfac+$posY,utf8_decode($cons));
//            $pdf->Text(52+$posX,94+$posfac+$posY,utf8_decode("RD".money_format('%.2n', $valor)));
//            $posfac+=3;
//            $deuda+=$valor;
//        }


        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('times','',10);
        $pdf->SetY(87+$posY);
        $pdf->SetX(74+$posX);
//        $pdf->Cell(20,4.5,"Total Deuda",1,3,'C',true);
//
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('times','',11);
        $pdf->SetY(91.5+$posY);
        $pdf->SetX(74+$posX);
//        $pdf->Cell(20,15.5,"RD$$deuda",1,3,'C',false);

        $pdf->SetY(108+$posY);
        $pdf->SetX(4+$posX);
        $pdf->Cell(40,20,"",1,3,'C',false);
        $pdf->SetY(108+$posY);
        $pdf->SetX(4+$posX);
        $pdf->SetFont('times','',7);
        $pdf->SetTextColor(255,255,255);
       $pdf->Cell(40,5,utf8_decode("Datos último pago"),1,3,'C',true);


        $s2= new Pago();
        $datdb=$s2->getUltPagoByInm($cod_sis);
        $posfac=0;
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('times','',7);
        $fechpago='';
        while (oci_fetch($datdb)){
            $importe=oci_result($datdb, 'IMPORTE');
            $fechpago=oci_result($datdb, 'FECHA_PAGO');

            $pdf->Text(5+$posX,116+$posY,utf8_decode("Fecha Ult. Pago"));
            $pdf->Text(27+$posX,116+$posfac+$posY,utf8_decode($fechpago));
            $pdf->Text(5+$posX,119+$posY,utf8_decode("Mto. Ult. Pago"));
            $pdf->Text(27+$posX,119+$posY,utf8_decode("RD".money_format('%.2n', $importe)));
        }


        $pdf->SetY(130+$posY);
        $pdf->SetX(5+$posX);
        $pdf->SetFont('times','',10);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(6,4,utf8_decode("$numhoja"),1,3,'C',false);

        $pdf->Text(13.5+$posX,133+$posY,utf8_decode("/"));

        $pdf->SetY(130+$posY);
        $pdf->SetX(16+$posX);
        $pdf->SetFont('times','',10);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(6,4,utf8_decode("$hojTota"),1,3,'C',false);

        $pdf->Text(45+$posX,111+$posY,utf8_decode("Cant. Tapones:"));
        $pdf->Text(79+$posX,111+$posY,utf8_decode("Lectura:"));
        $pdf->Text(45+$posX,119+$posY,utf8_decode("Tipo de Corte:"));
        $pdf->Text(79+$posX,119+$posY,utf8_decode("Fecha Corte:"));
        $pdf->Line(66+$posX,111+$posY,78+$posX,111+$posY);
        $pdf->Line(65+$posX,119+$posY,78+$posX,119+$posY);

        $pdf->Line(90+$posX,111+$posY,107+$posX,111+$posY);
        $pdf->Line(97+$posX,119+$posY,107+$posX,119+$posY);


        if($proy=='SD'){
            $pdf->Text(66+$posX,133+$posY,utf8_decode("Nombre funcionario Caasd"));
        }ELSE{

            $pdf->Text(66+$posX,133+$posY,utf8_decode("Nombre funcionario Coraabo"));
        }

        $pdf->Line(65+$posX,130+$posY,105+$posX,130+$posY);

        if($proy=='SD'){
            $pdf->Image("../../images/LogoCaasd.jpg",114+$posX,1+$posY,12,16);
        }ELSE{

            $pdf->Image("../../images/coraabo.jpg",114+$posX,1+$posY,12,16);
        }



        if($proy=='SD'){
            $pdf->SetTextColor(51,102,204);
            $pdf->SetFont('times','',22);
            $pdf->Text(148+$posX,10+$posY,utf8_decode("CA"));
            $pdf->Text(165.5+$posX,10+$posY,utf8_decode("SD"));
            $pdf->SetFont('times','',29);
            $pdf->Text(158+$posX,10+$posY,utf8_decode("A"));

        }ELSE{

            $pdf->SetTextColor(51,102,204);
            $pdf->SetFont('times','',22);
            $pdf->Text(148+$posX,10+$posY,utf8_decode("CORAABO"));


        }


        $pdf->SetFont('times','',9);
        $pdf->Text(144+$posX,14+$posY,utf8_decode("Corporación del Acueducto"));
        if($proy=='SD'){
            $pdf->Text(139+$posX,16.5+$posY,utf8_decode("y Alcantarillado de Santo Domingo"));
        }ELSE{

            $pdf->Text(139+$posX,16.5+$posY,utf8_decode("y Alcantarillado de Boca Chica"));
        }



        $pdf->SetY(19+$posY);
        $pdf->SetX(114+$posX);
        $pdf->SetFont('times','',8.35);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(81.5,15.5,utf8_decode(""),1,3,'C',true);

        if($proy=='SD'){
            $pdf->Text(115+$posX,22+$posY,utf8_decode("La Coorporación del Acueducto y Alcantarillado de Santo Domingo"));
            $pdf->Text(115+$posX,25.5+$posY,utf8_decode("CAASD en  ejercicio de  sus atribuciones  legales y especificamente"));
            $pdf->Text(115+$posX,29+$posY,utf8_decode("las  conferidas  por  la  Ley 498  de  1973  y  el  reglamento 3402 de"));
            $pdf->Text(115+$posX,32.5+$posY,utf8_decode("1973 procede a suspender el servicio."));
        }ELSE{

            $pdf->Text(115+$posX,22+$posY,utf8_decode("La Coorporación del Acueducto y Alcantarillado de Boca Chica"));
            $pdf->Text(115+$posX,25.5+$posY,utf8_decode("CORAABO en ejercicio de sus atribuciones legales y especificamente"));
            $pdf->Text(115+$posX,29+$posY,utf8_decode("las  conferidas  por  la  Ley 498  de  1973  y  el  reglamento 3402 de"));
            $pdf->Text(115+$posX,32.5+$posY,utf8_decode("1973 procede a suspender el servicio."));
        }


        $pdf->SetFont('times','B',15);
        $pdf->SetTextColor(0,0,0);
        $pdf->Text(128+$posX,39.5+$posY,utf8_decode("ACTA DE SUSPENSION"));

        $pdf->SetY(39.5+$posY);
        $pdf->SetX(114+$posX);
        $pdf->Cell(81.5,23,utf8_decode(""),1,3,'C',false);

        $pdf->SetY(39.5+$posY);
        $pdf->SetX(114+$posX);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFont('times','B',17);
//        $pdf->Cell(81.5,8.5,utf8_decode("Datos Último Pago"),1,3,'C',true);

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('times','B',15);
        $pdf->Text(121+$posX,53+$posY,utf8_decode("Fecha Ult. Pago:"));
        $pdf->Text(121+$posX,60+$posY,utf8_decode("Mto. Ult. Pago:"));
        $pdf->SetFont('times','',15);
//        $pdf->Text(164+$posX,53+$posY,utf8_decode($fechpago));
//        $pdf->Text(164+$posX,60+$posY,utf8_decode("RD".money_format('%.2n', $importe)));


        $pdf->SetY(64+$posY);
        $pdf->SetX(114+$posX);
        $pdf->Cell(81.5,30,utf8_decode(""),1,3,'C',false);

        $pdf->SetTextColor(255,255,255);
        $pdf->SetY(64+$posY);
        $pdf->SetX(114+$posX);
        $pdf->Cell(81.5,8.5,utf8_decode("Observación:"),1,3,'C',true);

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('times','',11);
        $pdf->Text(167+$posX,76+$posY,utf8_decode("Monto Deuda RD"));
//        $pdf->Text(167+$posX,79.5+$posY,utf8_decode("RD".money_format('%.2n', $deuda)));

//        $s2= new LoteCorte();
//        $data=$s2->tarifaRec($cod_sis);
//        while(oci_fetch($data)){
//            $reconexion=oci_result($data,'VALOR_TARIFA');
//            $pdf->Text(167+$posX,83+$posY,utf8_decode("Reconexion"));
//            $pdf->Text(167+$posX,86.5+$posY,utf8_decode("RD".money_format('%.2n', $reconexion)));
//        }
//        $pdf->Text(167+$posX,90+$posY,utf8_decode("Total"));
//        $total=$deuda+$reconexion;
//        $pdf->SetFont('times','B',11);
//        $pdf->Text(167+$posX,93.5+$posY,utf8_decode("RD".money_format('%.2n', $total)));
        $pdf->SetFont('times','',9);
        $deuda=0;

        $pdf->Text(116+$posX,98+$posY,utf8_decode("Clte: ".$nombre));

        $pdf->SetFont('times','',11);
        $pdf->Text(116+$posX,107+$posY,utf8_decode("Inmueble:"));
        $pdf->Text(116+$posX,112+$posY,utf8_decode("Marca:"));
        $pdf->Text(116+$posX,117+$posY,utf8_decode("Serial"));
        $pdf->SetFont('times','B',11);
        $pdf->Text(132+$posX,107+$posY,utf8_decode($cod_sis));
        $pdf->Text(132+$posX,112+$posY,utf8_decode($medidor));

        $pdf->Text(132+$posX,117+$posY,utf8_decode($serial));
        $pdf->SetFont('times','',11);

        $pdf->Text(155+$posX,107+$posY,utf8_decode("Lectura:"));
        $pdf->Text(155+$posX,115+$posY,utf8_decode("Fecha Corte:"));
        $pdf->Text(155+$posX,123+$posY,utf8_decode("Tipo de Corte:"));

        $pdf->Line(168+$posX,107+$posY,195+$posX,107+$posY);
        $pdf->Line(175+$posX,115+$posY,195+$posX,115+$posY);
        $pdf->Line(178+$posX,123+$posY,195+$posX,123+$posY);

        $pdf->Line(133+$posX,131+$posY,181+$posX,131+$posY);
        if($proy=='SD'){
            $pdf->Text(136+$posX,135+$posY,utf8_decode("Nombre funcionario Caasd"));
        }ELSE{

            $pdf->Text(136+$posX,135+$posY,utf8_decode("Nombre funcionario Coraabo"));
        }



    }oci_free_statement($stid);
    $nomarch="../../temp/hojasCorte".time().".pdf";
    $pdf->Output($nomarch,'F');
    echo $nomarch;

}

