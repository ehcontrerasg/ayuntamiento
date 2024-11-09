<?php

$tipo=$_REQUEST["tipo"];
$periodo=$_GET["periodo"];
$proy=$_GET["pro"];
$contratista=$_GET["selCon"];
session_start();
$cod=$_SESSION['codigo'];

if($tipo=="rep"){
    $diasPermitidos=2;
    
    include_once "../../clases/class.pdfRep.php";
    include_once "../../clases/class.reconexion.php";
    /**
     * Created by PhpStorm.
     * User: Edwin
     * Date: 3/06/2016
     * Time: 11:01 AM
     */

    function cabeceraTiemRec(){

        global $pdf;
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(64,128,191);
        $pdf->SetLineWidth(0.3);
        $pdf->SetY(33.5);
        $pdf->SetX(6);
        $pdf->Cell(196,5.5,"",1,3,'',true);
        $pdf->SetFont('times','',17);
        $pdf->Text(71,32.5,utf8_decode("Reconexiones Realizadas"));
        $pdf->SetFont('times','',12);
        $pdf->SetTextColor(0,0,0);
        $pdf->Text(6.5,37,utf8_decode("Fecha Reconexón"));
        $pdf->Text(40,37,utf8_decode("Fecha pago"));
        $pdf->Text(63,37,utf8_decode("Dias"));
        $pdf->Text(75,37,utf8_decode("Cod Sistema"));
        $pdf->Text(98,37,utf8_decode("Cod pago"));
        $pdf->Text(118.5,37,utf8_decode("Nombre"));


    }
    function cabeceraAnulmRec(){

        global $pdf;
        $pdf->SetFont('times','',17);
        $pdf->Text(48.5,33,utf8_decode("Reconexiones Anuladas"));

        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(64,128,191);
        $pdf->SetLineWidth(0.3);
        $pdf->SetY(34);
        $pdf->SetX(6.5);
        $pdf->Cell(147,5.5,"",1,3,'',true);
        $pdf->SetFont('times','',12);
        $pdf->Text(7,38,utf8_decode("Periodo"));
        $pdf->Text(42,38,utf8_decode("Fech Pago"));
        $pdf->Text(80,38,utf8_decode("Cod Sistema"));
        $pdf->Text(120,38,utf8_decode("Cod Pago"));



    }
    function cabeceraRecPend(){

        global $pdf;
        $pdf->SetFont('times','',17);
        $pdf->Text(48.5,33,utf8_decode("Reconexiones Pendientes"));
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(64,128,191);
        $pdf->SetLineWidth(0.3);
        $pdf->SetY(34);
        $pdf->SetX(6.5);
        $pdf->Cell(147,5.5,"",1,3,'',true);
        $pdf->SetFont('times','',12);
        $pdf->Text(7,38,utf8_decode("Periodo"));
        $pdf->Text(35,38,utf8_decode("Fech Pago"));
        $pdf->Text(65,38,utf8_decode("Dias"));
        $pdf->Text(80,38,utf8_decode("Cod Sistema"));
        $pdf->Text(120,38,utf8_decode("Cod Pago"));
    }
    function compruebaSaltoHoja($pdf){

        global  $contPy;
        global  $proy;
        if($contPy>=279){
            $pdf->setProyecto($proy);
            $pdf->AddPage();
            $contPy=42;
            cabeceraTiemRec();

        }
    }
    function compruebaSaltoHoja2($pdf){

        global  $contPy;
        global  $proy;

        if($contPy>=279){
            $pdf->setProyecto($proy);
            $pdf->AddPage();
            $contPy=42;
            cabeceraAnulmRec();

        }
    }
    function compruebaSaltoHoja3($pdf){

        global  $contPy;
        global  $proy;
        if($contPy>=279){
            $pdf->setProyecto($proy);
            $pdf->AddPage();
            $contPy=42;
            cabeceraRecPend();

        }
    }

    $pdf = new pdfTiempRec();
    $pdf->AliasNbPages();
    $pdf->setPeriodoGen($periodo);
    $pdf->setProyecto($proy);
    $pdf->AddPage();
    cabeceraTiemRec();
    $a=new Reconexion();
    $stid =$a->getTiempoRecMesByPerPro($periodo,$proy,$contratista);
    $contPy=42;
    $pdf->SetFont('times','',12);
    $cantRec=0;
    $cantMay2=0;
    $cantMenIg2=0;
    $totDias=0;
    while (oci_fetch($stid))
    {
        compruebaSaltoHoja($pdf);
        $fecRec=oci_result($stid, 'FECHA_EJE') ;
        $fechPago=oci_result($stid, 'FECHA_ACUERDO') ;
        $dias=oci_result($stid, 'DIAS') ;
        $codSis=oci_result($stid, 'ID_INMUEBLE') ;
        $idPag=oci_result($stid, 'ID_OTRO_RECAUDO') ;
        $oper=oci_result($stid, 'NOMBRE') ;

        $pdf->Text(6,$contPy,utf8_decode($fecRec));
        $pdf->Text(40,$contPy,utf8_decode($fechPago));
        $pdf->Text(63,$contPy,utf8_decode($dias));
        $pdf->Text(75,$contPy,utf8_decode($codSis));
        $pdf->Text(98,$contPy,utf8_decode($idPag));
        $pdf->Text(118,$contPy,utf8_decode($oper));
        $contPy+=5;
        $cantRec++;
        $totDias+=$dias;
        if($dias<=$diasPermitidos){
            $cantMenIg2++;
        }else{
            $cantMay2++;
        }
    }
    $pdf->AddPage();

    cabeceraAnulmRec();

    $a= new Reconexion();
    $stid =$a->getRecAnulMesByPerPro($periodo,$proy,$contratista);
    $contPy=43;
    $pdf->SetFont('times','',12);
    $cantAnul=0;
    while (oci_fetch($stid))
    {
        compruebaSaltoHoja2($pdf);
        $fecRec=oci_result($stid, 'FECHA_EJE') ;
        $fechPago=oci_result($stid, 'FECHA_ACUERDO') ;
        $dias=oci_result($stid, 'DIAS') ;
        $codSis=oci_result($stid, 'ID_INMUEBLE') ;
        $idPag=oci_result($stid, 'ID_OTRO_RECAUDO') ;
        $oper=oci_result($stid, 'NOMBRE') ;

        $pdf->Text(8,$contPy,utf8_decode($periodo));
        $pdf->Text(43,$contPy,utf8_decode($fecRec));
        //$pdf->Text(85,$contPy,utf8_decode($fechPago));
        //$pdf->Text(85,$contPy,utf8_decode($dias));
        $pdf->Text(80,$contPy,utf8_decode($codSis));
        $pdf->Text(120,$contPy,utf8_decode($idPag));
       // $pdf->Text(85,$contPy,utf8_decode($oper));
        $cantAnul++;
        $contPy+=5;
    }
    $pdf->AddPage();
    cabeceraRecPend();

    $a= new Reconexion();
    $stid =$a->getRecPendMesByPerPro($periodo,$proy,$contratista);
    $contPy=43;
    $pdf->SetFont('times','',12);
    $cantPend=0;
    while (oci_fetch($stid))
    {
        compruebaSaltoHoja3($pdf);
        $fecRec=oci_result($stid, 'FECHA_EJE') ;
        $fechPago=oci_result($stid, 'FECHA_ACUERDO') ;
        $dias=oci_result($stid, 'DIAS') ;
        $codSis=oci_result($stid, 'ID_INMUEBLE') ;
        $idPag=oci_result($stid, 'ID_OTRO_RECAUDO') ;
        $oper=oci_result($stid, 'NOMBRE') ;

        $pdf->Text(8,$contPy,utf8_decode($periodo));
        //$pdf->Text(85,$contPy,utf8_decode($fecRec));
        $pdf->Text(35,$contPy,utf8_decode($fechPago));
        $pdf->Text(65,$contPy,utf8_decode($dias));
        $pdf->Text(80,$contPy,utf8_decode($codSis));
        $pdf->Text(120,$contPy,utf8_decode($idPag));
        $contPy+=5;
        //$pdf->Text(85,$contPy,utf8_decode($oper));
        $cantPend++;
    }
    $pdf->AddPage();
    $pdf->SetY(26.5);
    $pdf->SetX(6);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetFillColor(64,128,191);
    $pdf->SetLineWidth(0.3);
    $pdf->SetFont('times','B',14);
    $pdf->Cell(107,7,"Datos",1,3,'C',true);

    $pdf->SetFont('times','',12);

    $pdf->Text(7,39,utf8_decode("Cant. de Reconexiones:"));
    $pdf->Text(7,44,utf8_decode("Cant. Anuladas:"));
    $pdf->Text(7,49,utf8_decode("Cant. Pendientes:"));
    $pdf->Text(7,54,utf8_decode("Cant. de Reconexiones <= a 2 dias:"));
    $pdf->Text(7,59,utf8_decode("Cant. de Reconexiones > a 2 dias:"));
    $pdf->Text(7,64,utf8_decode("% de Reconexiones <= a 2 dias:"));
    $pdf->Text(7,69,utf8_decode("% de Reconexiones > a 2 dias:"));

    $pdf->Text(7,74,utf8_decode("Cant. de Reconexiones Pend. <= a 2 dias:"));
    $pdf->Text(7,79,utf8_decode("Cant. de Reconexiones Pend. > a 2 dias:"));
    $pdf->Text(7,84,utf8_decode("Tiempo Promedio de Reconexiones:"));

    $pdf->SetFont('times','B',12);
    $pdf->Text(77,39,utf8_decode($cantRec));
    $pdf->Text(77,44,utf8_decode($cantAnul));
    $pdf->Text(77,49,utf8_decode($cantPend));
    $pdf->Text(77,54,utf8_decode($cantMenIg2));
    $pdf->Text(77,59,utf8_decode($cantMay2));



    try {
        if(($cantMay2+$cantMenIg2)==0){
            throw new Exception('division por cero 3');
        }
        $porcMenIg2=$cantMenIg2/($cantMay2+$cantMenIg2)*100;

    } catch (Exception $e) {
        $porcMenIg2=0;
    }

    try {
        if(($cantMay2+$cantMenIg2)==0){
            throw new Exception('division por cero');
        }

        $cantMay2=$cantMay2/($cantMay2+$cantMenIg2)*100;
    } catch (Exception $e) {
     //  echo $e->getMessage();
        $cantMay2=0;
    }



        if(($cantRec)==0){
            $prom=0;
        }else{
            $prom=$totDias/$cantRec;
        }

      //  echo $e->getMessage();


    $pdf->Text(70,64,utf8_decode($porcMenIg2."%"));
    $pdf->Text(70,69,utf8_decode($cantMay2."%"));
    $pdf->Text(70,84,utf8_decode($prom));

    $pdf->SetY(89);
    $pdf->SetX(8);
    $pdf->SetFont('times','',12);
    $pdf->Cell(50,5.5,"Fech Reconexion    Cantidad",1,3,'C',true);


    $a= new Reconexion();
    $stid =$a->getCantRecPorDiaByPerPro($periodo,$proy,$contratista);
    $contPy=89+5.5;
    $pdf->SetFont('times','',12);
    while (oci_fetch($stid))
    {
        $contPy+=5.5;
        $fecRec=oci_result($stid, 'FECHA_EJE') ;
        $cantidad=oci_result($stid, 'CANTIDAD') ;
        $pdf->SetY($contPy-5.5);
        $pdf->SetX(8);
        $pdf->Cell(50,5.5,"",1,3,'C',false);
        $pdf->Text(13,$contPy,utf8_decode($fecRec));
        $pdf->Text(45,$contPy,utf8_decode($cantidad));

    }

    $pdf->SetY(26.5);
    $pdf->SetX(6);
    $pdf->Cell(107,$contPy-26.5+8,"",1,3,'C',false);

    $pdf->Output("Libro.pdf",'I');

}
if($tipo=="per"){
    include_once "../../clases/class.periodo.php";
    $q=new Periodo();
    $datos = $q->getPeriodo();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $periodos[$i]=$row;
        $i++;
    }
    echo json_encode($periodos);

}

if($tipo=="pro"){
    include_once "../../clases/class.proyecto.php";
    $q=new Proyecto();
    $datos = $q->obtenerProyecto($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $proyectos[$i]=$row;
        $i++;
    }
    echo json_encode($proyectos);

}

