<?php
/**
 * Created by PhpStorm.
 * User: Edwin
 * Date: 26/05/2016
 * Time: 3:21 PM
 */
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
session_start();
$inm=$_GET["inmueble"];
$tip=$_GET["tip"];
//date_default_timezone_set('America/Santo_Domingo');
//setlocale(LC_MONETARY, 'es_DO');

if($tip=="rep")
{
    include "../clases/class.pdfRep.php";
    include "../clases/class.inmuebles.php";
    include "../clases/class.facturas.php";
    $pdf = new pdfEstConcepto();
    $pdf->AliasNbPages();
    $pdf->setCodSistema($inm);
    $a=new inmuebles();
    $datos=$a->datosInmueble(" AND I.CODIGO_INM=$inm");

    if(oci_fetch($datos)){
        $codcli=oci_result($datos,"CODIGO_CLI");
        $pdf->setCliente($codcli);
        $grupoCli=oci_result($datos,"COD_GRUPO");
        $pdf->setGrupo($grupoCli);

        $edificio=oci_result($datos,"DESC_URBANIZACION");
        $pdf->setUrbanizacion($edificio);

        $direccion=oci_result($datos,"DIRECCION");
        $pdf->setDireccion($direccion);

        $nombre=oci_result($datos,"NOMBRE");
        $pdf->setNombreCliente($nombre);

        $zona=oci_result($datos,"ID_ZONA");
        $pdf->setZona($zona);

        $catastro=oci_result($datos,"CATASTRO");
        $pdf->setCatastro($catastro);
        
        $proceso=oci_result($datos,"ID_PROCESO");
        $pdf->setProceso($proceso);

        $proyecto=oci_result($datos,"PROYECTO");
        $pdf->setProyecto($proyecto);
    }
    $pdf->AddPage();
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetFillColor(64,128,191);
    $pdf->SetLineWidth(0.3);
    $pdf->SetY(47);
    $pdf->SetX(6);

    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(180,5,utf8_decode(""),1,3,'C',true);

    $pdf->Text(11,50,utf8_decode("Fecha"));
    $pdf->Text(30,50,utf8_decode("Conceptos"));
    $pdf->Text(100,50,utf8_decode("Numero facturas"));
    $pdf->Text(160,50,utf8_decode("Valor"));
    $b=new facturas();
    $datos=$b->estcon3($inm);
    $posy=0;
    $fecha=getdate();
    $pdf->SetTextColor(0,0,0);
    $total=0;
    while(oci_fetch($datos)){
        $pdf->Text(11,55+$posy,utf8_decode( $fecha[mday]."-".$fecha[mon]."-".$fecha[year]));
        $concepto=oci_result($datos,"CONCEPTO");
        $pdf->Text(30,55+$posy,utf8_decode($concepto));
        $numFac=oci_result($datos,"NUMFAC");
        $pdf->Text(100,55+$posy,utf8_decode($numFac));
        $valor=oci_result($datos,"VALOR");
        $pdf->Text(160,55+$posy,"RD".money_format('%.2n', $valor));
        $total+=$valor;
        $posy+=5;
    //unset($concepto,$valor,$numFac);
    }

    $pdf->Line(160,53+$posy,180,53+$posy);
    $pdf->Text(160,56+$posy,utf8_decode("RD".money_format('%.2n', $total)));

    $pdf->Text(150,56+$posy,utf8_decode("Total:"));






//    $cont=0;
//    $operAnt="";
//    $s=new LoteCorte();
//    $stid=$s->inmueblesACor($proy,$sec,$zon,$oper);
//    $numhoja=0;
//    $tamCuadro=0;
//    while (oci_fetch($stid))
//    {
//        $fecAct=oci_result($stid, 'FECHACT');
//        $orden=oci_result($stid, 'ORDEN');
//        $cod_sis=oci_result($stid, 'CODIGO_INM');
//        $nombre=oci_result($stid, 'NOMBRE');
//        $proceso=oci_result($stid, 'ID_PROCESO');
//        $catastro=oci_result($stid, 'CATASTRO');
//        $uso=oci_result($stid, 'DESC_USO');
//        $telefono=oci_result($stid, 'TELEFONO');
//        $direccion=oci_result($stid, 'DIRECCION');
//        $urbanizacion=oci_result($stid, 'DESC_URBANIZACION');
//        $medidor=oci_result($stid, 'DESC_MED');
//        $calibre=oci_result($stid, 'DESC_CALIBRE');
//        $serial=oci_result($stid, 'SERIAL');
//        $calibre=oci_result($stid, 'DESC_CALIBRE');
//        $servicio=oci_result($stid, 'DESC_SERVICIO');
//        $numhoja ++;
//
//
//        if($cont+$tamCuadro>3){
//            $cont=0;
//            $pdf->AddPage();
//        }elseif($operAnt<>$operario && $operAnt<>""){
//            $cont=0;
//            $pdf->AddPage();
//        }
//
//        if($cont==0){
//            $posX=0;
//            $posY=0;
//        }
//        else if($cont==1){
//            $posX=105;
//            $posY=0;
//        }
//        else if($cont==2){
//            $posX=0;
//            $posY=148;
//        }
//        else if($cont==3){
//            $posX=105;
//            $posY=148;
//        }
//
//
//        $s2= new LoteCorte();
//        $datdb=$s2->consDeuda($cod_sis);
//        $deuda=0;
//        while (oci_fetch($datdb)){
//            $cons=oci_result($datdb, 'CONCEPTO');
//            $valor=oci_result($datdb, 'VALOR');
//            $deuda+=$valor;
//        }
//
//
//        $pdf->Image("../../images/LogoCaasd.jpg",9+$posX,1+$posY,12,16);
//        $pdf->SetDrawColor(0,0,0);
//        $pdf->SetFillColor(64,128,191);
//        $pdf->SetLineWidth(0.3);
//
//        $pdf->SetTextColor(51,102,204);
//        $pdf->SetFont('times','',22);
//        $pdf->Text(43+$posX,10+$posY,utf8_decode("CA"));
//        $pdf->Text(60.5+$posX,10+$posY,utf8_decode("SD"));
//        $pdf->SetFont('times','',29);
//        $pdf->Text(53+$posX,10+$posY,utf8_decode("A"));
//
//        $pdf->SetFont('times','',9);
//        $pdf->Text(39+$posX,14+$posY,utf8_decode("Corporación del Acueducto"));
//        $pdf->Text(34+$posX,16.5+$posY,utf8_decode("y Alcantarillado de Santo Domingo"));
//
//        $pdf->SetY(19+$posY);
//        $pdf->SetX(9+$posX);
//        $pdf->SetFont('times','',8.35);
//        $pdf->SetTextColor(255,255,255);
//        $pdf->Cell(81.5,15.5,utf8_decode(""),1,3,'C',true);
//        $pdf->Text(10+$posX,22+$posY,utf8_decode("La Coorporación del Acueducto y Alcantarillado de Santo Domingo"));
//        $pdf->Text(10+$posX,25.5+$posY,utf8_decode("CAASD en  ejercicio de  sus atribuciones  legales y especificamente"));
//        $pdf->Text(10+$posX,29+$posY,utf8_decode("las  conferidas  por  la  Ley 498  de  1973  y  el  reglamento 3402 de"));
//        $pdf->Text(10+$posX,32.5+$posY,utf8_decode("1973 procede a suspender el servicio."));
//
//        $pdf->SetFont('times','B',15);
//        $pdf->SetTextColor(0,0,0);
//        $pdf->Text(23+$posX,39.5+$posY,utf8_decode("ACTA DE SUSPENSION"));
//
//        $pdf->SetY(39.5+$posY);
//        $pdf->SetX(9+$posX);
//        $pdf->Cell(81.5,23,utf8_decode(""),1,3,'C',false);
//
//        $pdf->SetY(39.5+$posY);
//        $pdf->SetX(9+$posX);
//        $pdf->SetTextColor(255,255,255);
//        $pdf->SetFont('times','B',17);
//        $pdf->Cell(81.5,8.5,utf8_decode("Datos Último Pago"),1,3,'C',true);
//
//
//        $s2= new LoteCorte();
//        $datdb=$s2->ultPago($cod_sis);
//        while (oci_fetch($datdb)){
//            $importe=oci_result($datdb, 'IMPORTE');
//            $fechpago=oci_result($datdb, 'FECHA_PAGO');
//
//            $pdf->Text(5+$posX,116+$posY,utf8_decode("Fecha Ult. Pago"));
//            $pdf->Text(27+$posX,116+$posfac+$posY,utf8_decode($fechpago));
//            $pdf->Text(5+$posX,119+$posY,utf8_decode("Mto. Ult. Pago"));
//            $pdf->Text(27+$posX,119+$posY,utf8_decode("RD$".$importe));
//        }
//
//        $pdf->SetTextColor(0,0,0);
//        $pdf->SetFont('times','B',15);
//        $pdf->Text(16+$posX,53+$posY,utf8_decode("Fecha Ult. Pago:"));
//        $pdf->Text(16+$posX,60+$posY,utf8_decode("Mto. Ult. Pago:"));
//        $pdf->SetFont('times','',15);
//        $pdf->Text(59+$posX,53+$posY,utf8_decode($fechpago));
//        $pdf->Text(59+$posX,60+$posY,utf8_decode($importe));
//
//
//        $pdf->SetY(64+$posY);
//        $pdf->SetX(9+$posX);
//        $pdf->Cell(81.5,30,utf8_decode(""),1,3,'C',false);
//
//        $pdf->SetTextColor(255,255,255);
//        $pdf->SetY(64+$posY);
//        $pdf->SetX(9+$posX);
//        $pdf->Cell(81.5,8.5,utf8_decode("Observación:"),1,3,'C',true);
//
//        $pdf->SetTextColor(0,0,0);
//        $pdf->SetFont('times','',11);
//        $pdf->Text(62+$posX,76+$posY,utf8_decode("Monto Deuda RD"));
//        $pdf->Text(62+$posX,79.5+$posY,utf8_decode("RD$".$deuda));
//
//        $s2= new LoteCorte();
//        $data=$s2->tarifaRec($cod_sis);
//        while(oci_fetch($data)){
//            $reconexion=oci_result($data,'VALOR_TARIFA');
//            $pdf->Text(62+$posX,83+$posY,utf8_decode("Reconexion"));
//            $pdf->Text(62+$posX,86.5+$posY,utf8_decode("RD$".$reconexion));
//        }
//        $pdf->Text(62+$posX,90+$posY,utf8_decode("Total"));
//        $total=$deuda+$reconexion;
//        $pdf->Text(62+$posX,93.5+$posY,utf8_decode("RD$".$total));
//        $deuda=0;
//
//        $pdf->Text(33+$posX,98+$posY,utf8_decode("Clte: ".$nombre));
//
//        $pdf->Text(11+$posX,107+$posY,utf8_decode("Inmueble:"));
//        $pdf->Text(11+$posX,112+$posY,utf8_decode("Marca:"));
//        $pdf->Text(11+$posX,117+$posY,utf8_decode("Serial"));
//
//        $pdf->Text(27+$posX,107+$posY,utf8_decode($cod_sis));
//        $pdf->Text(27+$posX,112+$posY,utf8_decode($medidor));
//        $pdf->Text(27+$posX,117+$posY,utf8_decode($serial));
//
//        $pdf->Text(45+$posX,107+$posY,utf8_decode("Lectura:"));
//        $pdf->Text(45+$posX,115+$posY,utf8_decode("Fecha Corte:"));
//        $pdf->Text(45+$posX,123+$posY,utf8_decode("Tipo de Corte:"));
//
//        $pdf->Line(58+$posX,107+$posY,90+$posX,107+$posY);
//        $pdf->Line(65+$posX,115+$posY,90+$posX,115+$posY);
//        $pdf->Line(68+$posX,123+$posY,90+$posX,123+$posY);
//
//        $pdf->Line(28+$posX,131+$posY,76+$posX,131+$posY);
//        $pdf->Text(31+$posX,135+$posY,utf8_decode("Nombre funcionario Caasd"));
//
//        $cont=$cont+1;
//        $operAnt=$operario;
//
//    }oci_free_statement($stid);
    //$pdf->Output("Estado_Concepto_".$inm.".pdf",'F');


}

$nomarch="../../temp/Estado_Concepto_".$inm."_".time().".pdf";
$pdf->Output($nomarch,'I');
echo $nomarch;

