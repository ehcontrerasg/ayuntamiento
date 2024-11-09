<?php
/**
 * Created by PhpStorm.
 * User: Edwin
 * Date: 26/05/2016
 * Time: 3:21 PM
 */

include "../../clases/class.inspeccionCorte.php";
include "../../clases/class.sector.php";
session_start();
$usr=$_SESSION["codigo"];
$tip=$_REQUEST["tipo"];
//$tip="M";
$proy=$_REQUEST["proy"];
$sec=$_REQUEST["sec"];
$zon=$_REQUEST["zon"];
$oper=$_REQUEST["oper"];
$fecha = $_GET['fechaAsig'];


if($tip=="proy")
{
    include_once "../../clases/class.proyecto.php";
    $q=new Proyecto();
    $datos = $q->obtenerProyecto($usr);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $proyectos[$i]=$row;
        $i++;
    }
    echo json_encode($proyectos);
}

if($tip=="sec")
{
    $q=new Sector();
    $datos = $q->getSecHojRecByPro($proy);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $sectores[$i]=$row;
        $i++;
    }
    echo json_encode($sectores);
}

if($tip=="zon")
{
    include_once "../../clases/class.zona.php";
    $q=new Zona();
    $datos = $q->obtenerZonHojRec($sec);
    $i=0;
    $zonas = [];
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $zonas[$i]=$row;
        $i++;
    }
    echo json_encode($zonas);
}

if($tip=="ope")
{
    include_once "../../clases/class.usuario.php";
    $q=new Usuario();
    $datos = $q->getOperRecAbiProSecZon($proy,$sec,$zon);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $operarios[$i]=$row;
        $i++;
    }
    echo json_encode($operarios);
}

if($tip=="M")
{
    include "../../clases/class.pdfRep.php";
    include_once "../../clases/class.reconexion.php";

    $pdf = new pdfRecMan();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $cont=0;
    $tamCuadro=0;
    $operAnt="";
    $secAnt="";
    $s=new Reconexion();
    $stid=$s->getInmARecByProySecZonUsu($proy,$sec,$zon,$oper,$fecha);
    while (oci_fetch($stid))
    {
        $operario=oci_result($stid, 'OPERARIO');
        $fecacue=oci_result($stid, 'FECACUERDO');
        $cod_sis=oci_result($stid, 'CODIGO_INM');
        $direccion=oci_result($stid, 'DIRECCION');
        $urbanizacion=oci_result($stid, 'DESC_URBANIZACION');
        $telefono=oci_result($stid, 'TELEFONO');
        $proceso=oci_result($stid, 'ID_PROCESO');
        $catastro=oci_result($stid, 'CATASTRO');
        $tipoCorte=oci_result($stid, 'TIPO_CORTE');
        $medidor=oci_result($stid, 'DESC_MED');
        $calibre=oci_result($stid, 'DESC_CALIBRE');
        $serial=oci_result($stid, 'SERIAL');
        $nombre=oci_result($stid, 'NOMBRE');

        if($cont+$tamCuadro>3){
            $cont=0;
            $pdf->AddPage();
        }elseif($operAnt<>$operario && $operAnt<>""){
            $cont=0;
            $pdf->AddPage();
        }elseif($secAnt<>substr($proceso,0,2) && $secAnt<>""){
            $cont=0;
            $pdf->AddPage();
        }

        if($cont==0){
            $coordx=0;
            $coordy=0;
        }
        else if($cont==1){
            $coordx=105;
            $coordy=0;
        }
        else if($cont==2){
            $coordx=0;
            $coordy=148;
        }
        else if($cont==3){
            $coordx=105;
            $coordy=148;
        }



        if($proy=='SD'){
            $pdf->Image("../../images/LogoCaasd.jpg",5+$coordx,2+$coordy,12,16);
        }

        if($proy=='BC'){
            $pdf->Image("../../images/coraabo.jpg",5+$coordx,2+$coordy,12,16);

        }
        $pdf->SetFont('times','B',7);
        $pdf->SetTextColor(51,102,204);
        $hoy = getdate();
        $minuto=$hoy[minutes];
        $hora=$hoy[hours];
        $dia=$hoy[mday];
        $mes=$hoy[mon];
        $agno=$hoy[year];

        $pdf->Text(63+$coordx,3+$coordy,"$agno/$mes/$dia $hora:$minuto");
        $pdf->Text(25+$coordx,8+$coordy,"CONSTANCIA DE RECONEXION DEL SERVICIO");
        $pdf->SetTextColor(0,0,0);

        $pdf->Text(25+$coordx,8+$coordy,"CONSTANCIA DE RECONEXION DEL SERVICIO");
        $pdf->SetTextColor(0,0,0);
        if($proy=='SD'){
            $pdf->Text(20+$coordx,11+$coordy,utf8_decode("Corporación del Acueducto y Alcantarillado de Santo Domingo"));
        }

        if($proy=='BC'){
            $pdf->Text(20+$coordx,11+$coordy,utf8_decode("Corporación del Acueducto y Alcantarillado de Boca Chica"));

        }


        $pdf->SetFont('times',"",7);
        $pdf->Text(23+$coordx,14+$coordy,utf8_decode("Hacemos constar que hemos efectuado la reconexión"));
        $pdf->Text(32+$coordx,17+$coordy,utf8_decode("del servicio de Acueducto al suscriptor"));
        $pdf->Text(5+$coordx,26+$coordy,utf8_decode("Asignado:"));
        $pdf->Text(22+$coordx,26+$coordy,utf8_decode($operario));

        $pdf->Text(60+$coordx,26+$coordy,utf8_decode("Fecha de acuerdo: ".$fecacue));
        $pdf->Text(5+$coordx,32+$coordy,utf8_decode("Dirección:"));
        $pdf->Text(22+$coordx,32+$coordy,utf8_decode($direccion));
        $pdf->Text(5+$coordx,36+$coordy,utf8_decode("Cod.Sistema:"));
        $pdf->SetFont('times',"B",7);
        $pdf->Text(22+$coordx,36+$coordy,utf8_decode($cod_sis));
        $pdf->SetFont('times',"",7);
        $pdf->Text(5+$coordx,40+$coordy,utf8_decode("Urbanización:"));
        $pdf->Text(22+$coordx,40+$coordy,utf8_decode($urbanizacion));
        $pdf->Text(5+$coordx,44+$coordy,utf8_decode("Cliente:"));
        $pdf->Text(22+$coordx,44+$coordy,utf8_decode($nombre));
        $pdf->Text(5+$coordx,48+$coordy,utf8_decode("Proceso:"));
        $pdf->Text(22+$coordx,48+$coordy,utf8_decode($proceso));
        $pdf->Text(5+$coordx,52+$coordy,utf8_decode("Catastro:"));
        $pdf->Text(22+$coordx,52+$coordy,utf8_decode($catastro));

        $pdf->SetFont('times',"",10);
        $pdf->Text(5+$coordx,56+$coordy,utf8_decode("Tipo de corte: ".$tipoCorte ));

        $pdf->SetY(66+$coordy);
        $pdf->SetX(9+$coordx);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(64,128,191);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetLineWidth(0.3);
        $pdf->Cell(75,4,"DESCRIPCION DEL MEDIDOR",1,3,'C',true);

        $pdf->SetY(70+$coordy);
        $pdf->SetX(9+$coordx);
        $pdf->Cell(25,3,"Marca",1,3,'C',true);

        $pdf->SetY(70+$coordy);
        $pdf->SetX(34+$coordx);
        $pdf->Cell(25,3,"Calibre",1,3,'C',true);

        $pdf->SetY(70+$coordy);
        $pdf->SetX(59+$coordx);
        $pdf->Cell(25,3,"Serial",1,3,'C',true);

        $pdf->SetY(73+$coordy);
        $pdf->SetX(9+$coordx);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('times',"B",7);
        $pdf->Cell(25,4,utf8_decode($medidor),1,3,'C',false);
        $pdf->SetFont('times',"",7);

        $pdf->SetY(73+$coordy);
        $pdf->SetX(34+$coordx);
        $pdf->Cell(25,4,utf8_decode($calibre),1,3,'C',false);

        $pdf->SetY(73+$coordy);
        $pdf->SetX(59+$coordx);
        $pdf->SetFont('times',"B",7);
        $pdf->Cell(25,4,utf8_decode($serial),1,3,'C',false);
        $pdf->SetFont('times',"",7);

        if($proy=='SD'){
            $pdf->Image("../../images/LogoCaasd.jpg",28+$coordx,85+$coordy,15,18);
        }

        if($proy=='BC'){
            $pdf->Image("../../images/coraabo.jpg",28+$coordx,85+$coordy,15,18);

        }
        $pdf->SetFont('times','',63);
        $pdf->Text(43+$coordx,101+$coordy,utf8_decode("RR"));
        $pdf->SetFont('times','B',9);
        $pdf->Text(43+$coordx,103+$coordy,utf8_decode("Reconexión Realizada"));
        $pdf->Line(32+$coordx,115+$coordy,62+$coordx,115+$coordy);
        $pdf->SetFont('times','',7);
        $pdf->Text(43+$coordx,117+$coordy,utf8_decode("Fecha"));

        $pdf->Line(26+$coordx,129+$coordy,71+$coordx,129+$coordy);
        if($proy=='SD'){
            $pdf->Text(29+$coordx,131+$coordy,utf8_decode("Nombre Legible Funcionario CAASD"));
        }

        if($proy=='BC'){
            $pdf->Text(29+$coordx,131+$coordy,utf8_decode("Nombre Legible Funcionario CORAABO"));
        }




        $cont=$cont+1;
        $operAnt=$operario;
        $secAnt=substr($proceso,0,2);

    }oci_free_statement($stid);
    $pdf->Output("Libro.pdf",'I');
}
if($tip=="P")
{
    include "../clases/class.pdfRec.php";
    include_once "../../clases/class.reconexion.php";

    $pdf = new pdfRecMan();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $cont=0;
    $tamCuadro=148;
    $operAnt="";
    $secAnt="";
    $s=new AsignaReconexiones();
    $stid=$s->getInmARecByProySecZonUsu($proy,$sec,$zon,$oper);
    while (oci_fetch($stid))
    {

        $operario=oci_result($stid, 'OPERARIO');
        $fecacue=oci_result($stid, 'FECACUERDO');
        $cod_sis=oci_result($stid, 'CODIGO_INM');
        $direccion=oci_result($stid, 'DIRECCION');
        $urbanizacion=oci_result($stid, 'DESC_URBANIZACION');
        $telefono=oci_result($stid, 'TELEFONO');
        $proceso=oci_result($stid, 'ID_PROCESO');
        $catastro=oci_result($stid, 'CATASTRO');
        $tipoCorte=oci_result($stid, 'TIPO_CORTE');
        $medidor=oci_result($stid, 'DESC_MED');
        $calibre=oci_result($stid, 'DESC_CALIBRE');
        $serial=oci_result($stid, 'SERIAL');
        $nombre=oci_result($stid, 'NOMBRE');
        if($cont+$tamCuadro>296){
            $cont=0;
            $pdf->AddPage();
        }elseif($operAnt<>$operario && $operAnt<>""){
            $cont=0;
            $pdf->AddPage();
        }elseif($secAnt<>substr($proceso,0,2) && $secAnt<>""){
            $cont=0;
            $pdf->AddPage();
        }

        if($proy=='SD'){
            $pdf->Image("../../images/LogoCaasd.jpg",7,2,12,16);
        }

        if($proy=='BC'){
            $pdf->Image("../../images/coraabo.jpg",7,2,12,16);
        }


        $pdf->SetFont('times','B',7);
        $pdf->SetTextColor(51,102,204);
        $pdf->SetTextColor(51,102,204);
        $hoy = getdate();
        $minuto=$hoy[minutes];
        $hora=$hoy[hours];
        $dia=$hoy[mday];
        $mes=$hoy[mon];
        $agno=$hoy[year];

        $pdf->Text(63,3+$cont,"$agno/$mes/$dia $hora:$minuto");

        $pdf->Text(32,8+$cont,"CONSTANCIA DE RECONEXION DEL SERVICIO");
        $pdf->SetTextColor(0,0,0);

        if($proy=='SD'){
            $pdf->Text(25,11+$cont,utf8_decode("Corporación del Acueducto y Alcantarillado de Santo Domingo"));
        }

        if($proy=='BC'){
            $pdf->Text(25,11+$cont,utf8_decode("Corporación del Acueducto y Alcantarillado de Boca Chica"));
        }

        $pdf->SetFont('times',"",7);
        $pdf->Text(33,14+$cont,utf8_decode("Hacemos constar que hemos efectuado la reconexión"));
        $pdf->Text(40,17+$cont,utf8_decode("del servicio de Acueducto al suscriptor"));
        $pdf->Text(5,26+$cont,utf8_decode("Asignado:"));
        $pdf->Text(21,26+$cont,utf8_decode($operario));
        $pdf->Text(63,26+$cont,utf8_decode("Fecha:"));
        $pdf->Text(73,26+$cont,utf8_decode($fecacue));
        $pdf->Text(5,32+$cont,utf8_decode("Dirección:"));
        $pdf->Text(23,32+$cont,utf8_decode($direccion));
        $pdf->Text(5,36+$cont,utf8_decode("Cod.Sistema:"));
        $pdf->SetFont('times',"B",7);
        $pdf->Text(23,36+$cont,utf8_decode($cod_sis));
        $pdf->SetFont('times',"",7);
        $pdf->Text(5,40+$cont,utf8_decode("Urbanización:"));
        $pdf->Text(23,40+$cont,utf8_decode($urbanizacion));
        $pdf->Text(5,44+$cont,utf8_decode("Cliente:"));
        $pdf->Text(23,44+$cont,utf8_decode($nombre));
        $pdf->Text(5,48+$cont,utf8_decode("Teléfono:"));
        $pdf->Text(23,48+$cont,utf8_decode($telefono));
        $pdf->Text(5,52+$cont,utf8_decode("Proceso:"));
        $pdf->Text(23,52+$cont,utf8_decode($proceso));
        $pdf->Text(5,56+$cont,utf8_decode("Catastro:"));
        $pdf->Text(23,56+$cont,utf8_decode($catastro));
        $pdf->Text(5,60+$cont,utf8_decode("Observación:"));
        $pdf->Text(23,60+$cont,utf8_decode(""));
        $pdf->Text(63,52+$cont,utf8_decode("Tipo Corte:"));
        $pdf->Text(80,52+$cont,utf8_decode($tipoCorte));

        $pdf->SetY(66+$cont);
        $pdf->SetX(5);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(64,128,191);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetLineWidth(0.3);
        $pdf->Cell(48,4,"DESCRIPCION DEL MEDIDOR",1,3,'C',true);

        $pdf->SetY(70+$cont);
        $pdf->SetX(5);
        $pdf->Cell(16,3,"Marca",1,3,'C',true);

        $pdf->SetY(70+$cont);
        $pdf->SetX(21);
        $pdf->Cell(16,3,"Calibre",1,3,'C',true);

        $pdf->SetY(70+$cont);
        $pdf->SetX(37);
        $pdf->Cell(16,3,"Serial",1,3,'C',true);

        $pdf->SetY(73+$cont);
        $pdf->SetX(5);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('times',"B",7);
        $pdf->Cell(16,3,utf8_decode($medidor),1,3,'C',false);
        $pdf->SetFont('times',"",7);
        $pdf->SetY(73+$cont);
        $pdf->SetX(21);
        $pdf->Cell(16,3,utf8_decode($calibre),1,3,'C',false);

        $pdf->SetY(73+$cont);
        $pdf->SetX(37);
        $pdf->SetFont('times',"B",7);
        $pdf->Cell(16,3,utf8_decode($serial),1,3,'C',false);
        $pdf->SetFont('times',"",7);
        $pdf->SetY(66+$cont);
        $pdf->SetX(66);
        $pdf->SetFillColor(64,128,191);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(19,3,utf8_decode("Fec.Realizacion"),1,3,'C',true);
        $pdf->SetY(66+$cont);
        $pdf->SetX(85);
        $pdf->Cell(18,3,utf8_decode("Tipo Corte"),1,3,'C',true);

        $l= new InspeccionCorte();
        $dat=$l->getUltCortByInm($cod_sis);
        $posy=66;
        while (oci_fetch($dat)){
            $posy=$posy+3;
            $tipoCor=oci_result($dat, 'TIPO_CORTE');
            $fechacor=oci_result($dat, 'FECHA');
            $pdf->SetY($posy+$cont);
            $pdf->SetX(66);
            $pdf->SetFillColor(64,128,191);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(19,3,utf8_decode($fechacor),1,3,'C',false);
            $pdf->SetY($posy+$cont);
            $pdf->SetX(85);
            $pdf->Cell(18,3,utf8_decode($tipoCor),1,3,'C',false);


        }oci_free_statement($dat);
        $pdf->Text(6,80+$cont,utf8_decode("Se encontró reconectado:"));
        $pdf->Text(36,80+$cont,utf8_decode("SI"));
        $pdf->SetY(77+$cont);
        $pdf->SetX(39);
        $pdf->Cell(3,3,"",1,3,'C',false);
        $pdf->Text(45,80+$cont,utf8_decode("NO"));
        $pdf->SetY(77+$cont);
        $pdf->SetX(49);
        $pdf->Cell(3,3,"",1,3,'C',false);

        $pdf->SetY(83+$cont);
        $pdf->SetX(6);
        $pdf->Cell(3,3,"",1,3,'C',false);

        $pdf->SetY(83+$cont);
        $pdf->SetX(46);
        $pdf->Cell(3,3,"",1,3,'C',false);

        $pdf->SetY(88+$cont);
        $pdf->SetX(6);
        $pdf->Cell(3,3,"",1,3,'C',false);

        $pdf->SetY(88+$cont);
        $pdf->SetX(46);
        $pdf->Cell(3,3,"",1,3,'C',false);


        $pdf->Text(10,86+$cont,utf8_decode("Reparación Pavimento"));
        $pdf->Text(50,86+$cont,utf8_decode("Reparación Anden Concreto"));
        $pdf->Text(10,91+$cont,utf8_decode("Inmueble con Doble Acometida"));
        $pdf->Text(50,91+$cont,utf8_decode("Inmueble sin Acometida"));
        $pdf->Text(6,97+$cont,utf8_decode("Otros:"));
        $pdf->Line(13,97+$cont,105,97+$cont);
        $pdf->Line(6,102+$cont,105,102+$cont);
        $pdf->Line(6,107+$cont,105,107+$cont);
        $pdf->Text(6,112+$cont,utf8_decode("Número Medidor Nuevo:"));
        $pdf->Line(33,112+$cont,44,112+$cont);
        $pdf->Text(45,112+$cont,utf8_decode("Lectura:"));
        $pdf->Line(54,112+$cont,64,112+$cont);
        $pdf->Text(65,112+$cont,utf8_decode("Calibre:"));
        $pdf->Line(74,112+$cont,84,112+$cont);
        $pdf->Text(85,112+$cont,utf8_decode("Marca:"));
        $pdf->Line(92.5,112+$cont,105,112+$cont);
        $pdf->Text(6,117+$cont,utf8_decode("Observación:"));
        $pdf->Line(21,117+$cont,105,117+$cont);
        $pdf->Line(6,122+$cont,105,122+$cont);
        $pdf->Line(6,127+$cont,105,127+$cont);
        $pdf->Line(6,134+$cont,22,134+$cont);
        $pdf->Line(23,134+$cont,62,134+$cont);
        $pdf->Line(63,134+$cont,105,134+$cont);
        $pdf->Text(11,138+$cont,utf8_decode("Fecha"));
        $pdf->Text(23,138+$cont,utf8_decode("Nombre Legible Usuario/Suscriptor"));
        if($proy=='SD'){
            $pdf->Text(63,138+$cont,utf8_decode("Nombre Legible Funcionario CAASD"));
        }

        if($proy=='BC'){
            $pdf->Text(63,138+$cont,utf8_decode("Nombre Legible Funcionario CORAABO"));
        }







        if($proy=='SD'){
            $pdf->Image("../../images/LogoCaasd.jpg",114,2+$cont,12,16);
        }

        if($proy=='BC'){
            $pdf->Image("../../images/coraabo.jpg",114,2+$cont,12,16);
        }

        $pdf->SetFont('times','B',7);
        $pdf->SetTextColor(51,102,204);

        $hoy = getdate();
        $minuto=$hoy[minutes];
        $hora=$hoy[hours];
        $dia=$hoy[mday];
        $mes=$hoy[mon];
        $agno=$hoy[year];

        $pdf->Text(63,3+$cont,"$agno/$mes/$dia $hora:$minuto");



        $pdf->Text(139,8+$cont,"CONSTANCIA DE RECONEXION DEL SERVICIO");
        $pdf->SetTextColor(0,0,0);
        if($proy=='SD'){
            $pdf->Text(132,11+$cont,utf8_decode("Corporación del Acueducto y Alcantarillado de Santo Domingo"));
        }

        if($proy=='BC'){
            $pdf->Text(132,11+$cont,utf8_decode("Corporación del Acueducto y Alcantarillado de Boca Chica"));
        }

        $pdf->SetFont('times',"",7);
        $pdf->Text(140,14+$cont,utf8_decode("Hacemos constar que hemos efectuado la reconexión"));
        $pdf->Text(147,17+$cont,utf8_decode("del servicio de Acueducto al suscriptor"));
        $pdf->Text(112,26+$cont,utf8_decode("Asignado:"));
        $pdf->Text(128,26+$cont,utf8_decode($operario));
        $pdf->Text(112,32+$cont,utf8_decode("Dirección:"));
        $pdf->Text(130,32+$cont,utf8_decode($direccion));
        $pdf->Text(112,36+$cont,utf8_decode("Cod.Sistema:"));
        $pdf->SetFont('times',"B",7);
        $pdf->Text(130,36+$cont,utf8_decode($cod_sis));
        $pdf->SetFont('times',"",7);
        $pdf->Text(112,40+$cont,utf8_decode("Urbanización:"));
        $pdf->Text(130,40+$cont,utf8_decode($urbanizacion));
        $pdf->Text(112,44+$cont,utf8_decode("Cliente:"));
        $pdf->Text(130,44+$cont,utf8_decode($nombre));
        $pdf->Text(112,48+$cont,utf8_decode("Proceso:"));
        $pdf->Text(130,48+$cont,utf8_decode($proceso));
        $pdf->Text(112,52+$cont,utf8_decode("Catastro:"));
        $pdf->Text(130,52+$cont,utf8_decode($catastro));

        $pdf->SetY(66+$cont);
        $pdf->SetX(112);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(64,128,191);
        $pdf->SetTextColor(255,255,255);
        $pdf->SetLineWidth(0.3);
        $pdf->Cell(75,4,"DESCRIPCION DEL MEDIDOR",1,3,'C',true);

        $pdf->SetY(70+$cont);
        $pdf->SetX(112);
        $pdf->Cell(25,3,"Marca",1,3,'C',true);

        $pdf->SetY(70+$cont);
        $pdf->SetX(137);
        $pdf->Cell(25,3,"Calibre",1,3,'C',true);

        $pdf->SetY(70+$cont);
        $pdf->SetX(162);
        $pdf->Cell(25,3,"Serial",1,3,'C',true);

        $pdf->SetY(73+$cont);
        $pdf->SetX(112);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('times',"B",7);
        $pdf->Cell(25,4,utf8_decode($medidor),1,3,'C',false);
        $pdf->SetFont('times',"",7);
        $pdf->SetY(73+$cont);
        $pdf->SetX(137);
        $pdf->Cell(25,4,utf8_decode($calibre),1,3,'C',false);

        $pdf->SetY(73+$cont);
        $pdf->SetX(162);
        $pdf->SetFont('times',"B",7);
        $pdf->Cell(25,4,utf8_decode($serial),1,3,'C',false);
        $pdf->SetFont('times',"",7);

        if($proy=='SD'){
            $pdf->Image("../../images/LogoCaasd.jpg",135,85+$cont,15,18);
        }

        if($proy=='BC'){
            $pdf->Image("../../images/coraabo.jpg",135,85+$cont,15,18);
        }
        $pdf->SetFont('times','',63);
        $pdf->Text(152,101+$cont,utf8_decode("RR"));
        $pdf->SetFont('times','B',9);
        $pdf->Text(152,103+$cont,utf8_decode("Reconexión Realizada"));
        $pdf->Line(140,115+$cont,170,115+$cont);
        $pdf->SetFont('times','',7);
        $pdf->Text(152,117+$cont,utf8_decode("Fecha"));

        $pdf->Line(134,129+$cont,179,129+$cont);
        if($proy=='SD'){
            $pdf->Text(139,131,utf8_decode("Nombre Legible Funcionario CAASD"));
        }

        if($proy=='BC'){
            $pdf->Text(139,131,utf8_decode("Nombre Legible Funcionario CORAABO"));
        }




        $cont=$cont+$tamCuadro;
        $operAnt=$operario;
        $secAnt=substr($proceso,0,2);

    }oci_free_statement($stid);
    $pdf->Output("Libro.pdf",'I');


    //echo "papel \n $proy \n $sec \n $zon \n $oper\n";
}

