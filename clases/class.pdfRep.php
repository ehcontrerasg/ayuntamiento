<?php
/**
 * Created by PhpStorm.
 * User: Edwin
 * Date: 3/06/2016
 * Time: 11:18 AM
 */
date_default_timezone_set('America/Santo_Domingo');
require_once "class.fpdf.php";
include_once "class.conexion.php";
//include_once './reports.php';

session_start();

class PdfHojMantCorr extends FPDF
{

    private $proyecto;

    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function Header()
    {
        $this->DefOrientation = 'P';

        if ($this->proyecto == 'SD') {
            $pro  = 'SANTO DOMINGO';
            $pro2 = 'CAASD';
            $this->Image("./../../images/LogoCaasd.jpg", 7.5, 3.5, 11, 15.5);} else {
            $pro  = 'BOCA CHICA';
            $pro2 = 'CORAABO';
            $this->Image("./../../images/logo_coraabo.jpg", 7.5, 3.5, 11, 15.5);
        }

        $this->SetFont('times', "B", 12);

        $this->Text(30, 12, utf8_decode("CORPORACIÓN DEL ACUEDUCTO Y ALCANTARILLADO DE $pro"));
        $this->Text(92, 17, utf8_decode($pro2));
        $this->Text(60, 24, utf8_decode("MANTENIMIENTO AL MEDIDOR MC "));
        $this->SetFont('times', "", 10);

        if ($this->proyecto == 'SD') {
            $this->Text(5, 23, utf8_decode("CAASD Gerencia Comercial"));
        } elseif ($this->proyecto == 'BC') {
            $this->Text(5, 23, utf8_decode("CORAABO Gerencia Comercial"));
        }

        $this->Text(160, 23, utf8_decode(date('d-m-Y h:i A')));

    }

    public function Footer()
    {
        $this->SetFont('times', "", 12);
        $this->Text(3, 287, utf8_decode("Impordme-Santo Domingo"));

    }
}

class PdfHojRepTar extends FPDF
{

    private $proyecto;
    private $periodo;

    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;
    }

    public function Header()
    {
        $this->DefOrientation = 'P';

        if ($this->proyecto == 'SD') {
            $pro  = 'SANTO DOMINGO';
            $pro2 = 'CAASD';
            $this->Image("./../../images/LogoCaasd.jpg", 7.5, 3.5, 11, 15.5);} else {
            $pro  = 'BOCA CHICA';
            $pro2 = 'CORAABO';
            $this->Image("./../../images/coraabo.jpg", 7.5, 3.5, 11, 15.5);
        }

        $this->SetFont('times', "B", 12);

        $this->Text(30, 12, utf8_decode("CORPORACIÓN DEL ACUEDUCTO Y ALCANTARILLADO DE $pro"));
        $this->Text(92, 17, utf8_decode($pro2));
        $this->Text(60, 24, utf8_decode("Relación de Tarifas Acueducto de $pro $this->periodo"));
        $this->SetFont('times', "", 10);

        if ($this->proyecto == 'SD') {
            $this->Text(5, 23, utf8_decode("CAASD Gerencia Comercial"));
        } elseif ($this->proyecto == 'BC') {
            $this->Text(5, 23, utf8_decode("CORAABO Gerencia Comercial"));
        }

        $this->Text(178, 23, utf8_decode(date('d-m-Y h:i A')));

    }

    public function Footer()
    {
        $this->SetFont('times', "", 12);
        $this->Text(3, 287, utf8_decode("Impordme-Santo Domingo"));

    }
}

class PdfHojRutPdc extends FPDF
{

    private $proyecto;
    private $proIni;
    private $proFin;

    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function setProIni($proIni)
    {
        $this->proIni = $proIni;
    }

    public function setProFin($proFin)
    {
        $this->proFin = $proFin;
    }

    public function Header()
    {
        if ($this->proyecto == 'SD') {
            $this->Image("../../images/LogoCaasd.jpg", 7.5, 3.5, 11, 15.5);
        }
        if ($this->proyecto == 'BC') {
            $this->Image("../../images/coraabo.jpg", 7.5, 3.5, 11, 15.5);
        }

        $this->SetFont('times', "B", 19);

        $this->Text(54, 11, utf8_decode("Ruta brigada deuda cero  "));
        $this->SetFont('times', "", 13);
        $this->Text(58, 17, utf8_decode("Desde " . $this->proIni));
        $this->Text(98, 17, utf8_decode("Hasta " . $this->proFin));

        $this->SetFont('times', "", 10);
        if ($this->proyecto == 'SD') {
            $this->Text(5, 23, utf8_decode("CAASD Gerencia Comercial"));
        }
        if ($this->proyecto == 'BC') {
            $this->Text(5, 23, utf8_decode("CORAABO Gerencia Comercial"));
        }
        $this->Text(260, 27, utf8_decode(date('d/m/Y h:i A')));
        $this->Text(271, 30, utf8_decode('Pág ' . $this->PageNo() . ' de {nb}'));

        $this->SetFont('times', "B", 10);
        $this->Text(248, 10, utf8_decode("Código:"));
        $this->Text(248, 13, utf8_decode("Edición No.:"));
        $this->Text(248, 16, utf8_decode("Fecha de emisión:"));

        $this->SetY(33);

        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(64, 128, 191);

        $this->SetLineWidth(0.3);
        $this->SetX(1);
        $this->Cell(290, 6, utf8_decode('Codigo  Sector  Ruta  Proceso        Catastro                         Estado    Nombre                                                                      Direccion                                   Uso     Serial           Facturas           Deuda'), 1, 3, 'L', true);

    }

    public function Footer()
    {
        $this->SetFont('times', "", 12);
        $this->Text(3, 287, utf8_decode("Impordme-Santo Domingo"));

    }
}
class PdfRep3k extends FPDF
{

    private $proyecto;
    private $nombre_formulario;
    private $edicion_formulario;
    private $fecha_edicion;
    private $imagen;

    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function Header()
    {
        $r = new Reporte();
        $this->SetFont('times', "B", 19);

        $this->SetFont('times', "", 10);
        if ($this->proyecto == 'SD') {
            $this->Text(5, 23, utf8_decode("CAASD Gerencia Comercial"));
        }
        if ($this->proyecto == 'BC') {
            $this->Text(5, 23, utf8_decode("CORAABO Gerencia Comercial"));
        }
        $this->Text(260, 27, utf8_decode(date('d/m/Y h:i A')));
        $this->Text(271, 30, utf8_decode('Pág ' . $this->PageNo() . ' de {nb}'));

        //TRAER LAS EDICIONES DEL FORMULARIO
        $ediciones = $r->getFormDates('FO-MED-25');

        while($row = oci_fetch_assoc($ediciones)){
            if(strtotime(date('d-m-Y')) >= strtotime($row["FECHA_EMISION"])){

                $this->nombre_formulario  =  $row["DESCRIPCION"];
                $this->edicion_formulario =  $row["EDICION"];
                $this->fecha_edicion      =  $row["FECHA_EMISION"];
                $this->imagen             =  $row["IMAGEN"];
            }
        }

        if($this->imagen != null){
            $this->Image($this->imagen, 7.5, 3.5, 17, 15.5);
        }
        $this->SetFont('times', "B", 19);
        $this->Text(54, 11, utf8_decode($this->nombre_formulario/*"Medidores Lecturas Mayor 3k  "*/));
        $this->SetFont('times', "B", 10);
        $this->Text(248, 10, utf8_decode("Código: FO-MED-25"));
        $this->Text(248, 13, utf8_decode("Edición No.: ". $this->edicion_formulario /*"02"*/));
        $this->Text(248, 16, utf8_decode("Fecha de emisión: ".utf8_decode(date('d-m-Y',strtotime($this->fecha_edicion))) /*"04/04/2019"*/));

        $this->SetY(33);

        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(64, 128, 191);

        $this->SetLineWidth(0.3);
        $this->SetX(1);
        $this->Cell(290, 6, utf8_decode('Codigo  Urbanización  Dirección                                          Cliente                                       Proceso         Catastro                Medidor          Serial         Emplazamiento  Calibre  Uso  Estado  lectura'), 1, 3, 'L', true);

    }

    public function Footer()
    {
        $this->SetFont('times', "", 12);
        $this->Text(3, 287, utf8_decode("Impordme-Santo Domingo"));

    }
}

class pdfHojCor extends FPDF
{

    public function Header()
    {

    }

    public function Footer()
    {

    }
}

class pdfInsMan extends FPDF
{

    public function Header()
    {

    }

    public function Footer()
    {

    }
}

class pdfInsMov extends FPDF
{

    public function Header()
    {

    }

    public function Footer()
    {

    }
}

class pdfRecMan extends FPDF
{

    public function Header()
    {

    }

    public function Footer()
    {

    }
}

class pdfRecMov extends FPDF
{

    public function Header()
    {

    }

    public function Footer()
    {

    }
}


class pdfAsigCorDiar extends FPDF
{

    /*** ASIGNACIÓN DE CORTES*/

    private $fechaGen;
    private $proyecto;
    private $nombre_formulario;
    private $edicion_formulario;
    private $fecha_edicion;
    private $imagen;

    public function setFechaGen($valor)
    {
        $date           = new DateTime($valor);
        $this->fechaGen = $date->format("d-m-Y");
    }

    public function setProyecto($valor)
    {
        $this->proyecto = $valor;
    }

    public function Header()
    {
        $r  = new Reporte();
        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(64, 128, 191);
        $this->SetTextColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('times', 'B', 12);
        $this->SetY(15);
        $this->SetX(17);
        $this->Cell(149, 21, "", 1, 3, '', false);

        $this->SetY(15);
        $this->SetX(17);
        $this->Cell(19, 21, "", 1, 3, '', false);

        $this->SetY(15);
        $this->SetX(123);
        $this->Cell(43, 21, "", 1, 3, '', false);

        $this->SetY(22);
        $this->SetX(123);
        $this->Cell(43, 7, "", 1, 3, '', false);

        //TRAER LAS EDICIONES DEL FORMULARIO
        $ediciones = $r->getFormDates('FO-COR-01');

        while($row = oci_fetch_assoc($ediciones)){
            if(strtotime($this->fechaGen) >= strtotime($row["FECHA_EMISION"])){

                $this->nombre_formulario  =  $row["DESCRIPCION"];
                $this->edicion_formulario =  $row["EDICION"];
                $this->fecha_edicion      =  $row["FECHA_EMISION"];
                $this->imagen             =  $row["IMAGEN"];
            }
        }
        $this->fecha_edicion = strtotime($this->fecha_edicion);
        $this->fecha_edicion = date('d-m-Y',$this->fecha_edicion);

        if($this->imagen != null){
            $this->Image($this->imagen, 18.5, 17.5, 16, 17);
        }

        $this->SetFont('times', "B", 12);
        $this->Text(59, 25.5, utf8_decode($this->nombre_formulario));

        $this->SetFont('times', "B", 9);
        $this->Text(124, 20, utf8_decode("Codigo:"));
        $this->Text(124, 27, utf8_decode("Edicion No.:"));
        $this->Text(124, 34, utf8_decode("Fecha de emision:"));

        $this->SetFont('times', "", 9);
        $this->Text(135, 20, utf8_decode("FO-COR-01"));
        $this->Text(141, 27, utf8_decode($this->edicion_formulario));
        $this->Text(149, 34, utf8_decode($this->fecha_edicion));
        $this->Text(44, 40, utf8_decode("Fecha: " . $this->fechaGen));

    }

    public function Footer()
    {
        $this->SetFont('times', "", 9);
        $this->Text(171, 284, utf8_decode("Page " . $this->PageNo()));

    }
}

class pdfAsigRecDiar extends FPDF
{

    private $fechaGen;
    private $proyecto;
    private $nombre_formulario;
    private $edicion_formulario;
    private $fecha_edicion;
    private $imagen;

    public function setFechaGen($valor)
    {
        $date           = new DateTime($valor);
        $this->fechaGen = $date->format("d-m-Y");
    }

    public function setProyecto($valor)
    {
        $this->proyecto = $valor;
    }

    public function Header()
    {
        $r  = new Reporte();
        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(64, 128, 191);
        $this->SetTextColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('times', 'B', 12);
        $this->SetY(15);
        $this->SetX(17);
        $this->Cell(149, 21, "", 1, 3, '', false);

        $this->SetY(15);
        $this->SetX(17);
        $this->Cell(19, 21, "", 1, 3, '', false);

        $this->SetY(15);
        $this->SetX(123);
        $this->Cell(43, 21, "", 1, 3, '', false);

        $this->SetY(22);
        $this->SetX(123);
        $this->Cell(43, 7, "", 1, 3, '', false);

        //$this->Image("../../images/aceadom201904.jpg", 18.5, 17.5, 16, 17);

        //TRAER LAS EDICIONES DEL FORMULARIO
        $ediciones = $r->getFormDates('FO-COR-02');

        while($row = oci_fetch_assoc($ediciones)){
            if(strtotime($this->fechaGen) >= strtotime($row["FECHA_EMISION"])){

                $this->nombre_formulario  =  $row["DESCRIPCION"];
                $this->edicion_formulario =  $row["EDICION"];
                $this->fecha_edicion      =  $row["FECHA_EMISION"];
                $this->imagen             =  $row["IMAGEN"];
            }
        }
        $this->fecha_edicion = strtotime($this->fecha_edicion);
        $this->fecha_edicion = date('d-m-Y',$this->fecha_edicion);

        $this->SetFont('times', "B", 12);
        $this->Text(45, 25.5, utf8_decode($this->nombre_formulario/*"ASIGNACIÓN DE RECONEXIONES"*/));

        if($this->imagen != null){
            $this->Image($this->imagen, 18.5, 17.5, 16, 17);
        }

        $this->SetFont('times', "B", 9);
        $this->Text(124, 20, utf8_decode("Codigo:"));
        $this->Text(124, 27, utf8_decode("Edicion No.:"));
        $this->Text(124, 34, utf8_decode("Fecha de emision:"));

        $this->SetFont('times', "", 9);
        $this->Text(135, 20, utf8_decode("FO-COR-02"));
        $this->Text(141, 27, utf8_decode($this->edicion_formulario/*"04"*/));
        $this->Text(149, 34, utf8_decode($this->fecha_edicion/*"02-04-2019"*/));
        $this->Text(44, 40, utf8_decode("Fecha: " . $this->fechaGen));

    }

    public function Footer()
    {
        $this->SetFont('times', "", 9);
        $this->Text(171, 284, utf8_decode("Page " . $this->PageNo()));

    }
}

class pdfAsigInspDiar extends FPDF
{


    private $fechaGen;
    private $proyecto;
    private $nombre_formulario;
    private $edicion_formulario;
    private $fecha_edicion;
    private $imagen;


    public function setFechaGen($valor)
    {
        $date           = new DateTime($valor);
        $this->fechaGen = $date->format("d-m-Y");
    }

    public function setProyecto($valor)
    {
        $this->proyecto = $valor;
    }

    public function Header()
    {
        //REPORTE DE ASIGNACIÓN DE INSPECCIONES
        $r = new Reporte();
        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(64, 128, 191);
        $this->SetTextColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('times', 'B', 12);
        $this->SetY(15);
        $this->SetX(17);
        $this->Cell(149, 21, "", 1, 3, '', false);

        $this->SetY(15);
        $this->SetX(17);
        $this->Cell(19, 21, "", 1, 3, '', false);

        $this->SetY(15);
        $this->SetX(123);
        $this->Cell(43, 21, "", 1, 3, '', false);

        $this->SetY(22);
        $this->SetX(123);
        $this->Cell(43, 7, "", 1, 3, '', false);

        //TRAER LAS EDICIONES DEL FORMULARIO
        $ediciones = $r->getFormDates('FO-COR-07');

        while($row = oci_fetch_assoc($ediciones)){
            if(strtotime($this->fechaGen) >= strtotime($row["FECHA_EMISION"])){

                $this->nombre_formulario  =  $row["DESCRIPCION"];
                $this->edicion_formulario =  $row["EDICION"];
                $this->fecha_edicion      =  $row["FECHA_EMISION"];
                $this->imagen             =  $row["IMAGEN"];
            }
        }
        $this->fecha_edicion = strtotime($this->fecha_edicion);
        $this->fecha_edicion = date('d-m-Y',$this->fecha_edicion);

        if($this->imagen!= null){
            $this->Image($this->imagen, 18.5, 17.5, 16, 17);
        }

        $this->SetFont('times', "B", 12);
        $this->Text(39, 25.5, utf8_decode($this->nombre_formulario));

        $this->SetFont('times', "B", 9);
        $this->Text(124, 20, utf8_decode("Codigo:"));
        $this->Text(124, 27, utf8_decode("Edicion No.:"));
        $this->Text(124, 34, utf8_decode("Fecha de emision:"));

        $this->SetFont('times', "", 9);
        $this->Text(135, 20, utf8_decode("FO-COR-07"));
        $this->Text(141, 27, utf8_decode($this->edicion_formulario));
        $this->Text(149, 34, utf8_decode($this->fecha_edicion));
        $this->Text(44, 40, utf8_decode("Fecha: " . $this->fechaGen));

    }

    public function Footer()
    {
        $this->SetFont('times', "", 9);
        $this->Text(171, 284, utf8_decode("Page " . $this->PageNo()));

    }
}

class pdfTiempRec extends FPDF
{

    private $periodoGen;
    private $proyecto;

    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function setPeriodoGen($valor)
    {
        // $date = new DateTime($valor);
        // $this->periodoGen=$date->format("d/m/Y");
        $this->periodoGen = $valor;
    }

    public function Header()
    {

        if ($this->proyecto == 'SD') {
            $this->Image("../../images/LogoCaasd.jpg", 7.5, 3.5, 11, 15.5);
        } elseif ($this->proyecto == 'BC') {
            $this->Image("../../images/coraabo.jpg", 7.5, 3.5, 11, 15.5);
        }

        $this->SetTextColor(0, 0, 0);
        $this->SetFont('times', '', 30);
        $this->Text(55.5, 18, utf8_decode("Tiempo de Reconexión"));
        $this->SetFont('times', '', 14);
        $this->Text(90.5, 24, utf8_decode("Periodo: " . $this->periodoGen));

    }

    public function Footer()
    {
        $this->SetFont('times', "", 9);
        $this->Text(103, 285, utf8_decode("Page " . $this->PageNo()));

    }
}

class pdfRepMen extends FPDF
{

    private $periodoGen;
    private $proyecto;

    public function setPeriodoGen($valor)
    {
        $this->periodoGen = $valor;
    }

    public function setProyecto($valor)
    {
        $this->proyecto = $valor;
    }

    public function Header()
    {

        if ($this->proyecto == 'SD') {
            $this->Image("../../images/LogoCaasd.jpg", 7.5, 3.5, 11, 15.5);
        }

        if ($this->proyecto == 'BC') {
            $this->Image("../../images/coraabo.jpg", 7.5, 3.5, 11, 15.5);
        }

        $this->SetTextColor(0, 0, 0);
        $this->SetFont('times', '', 17);
        $this->Text(60, 13.5, utf8_decode("Resumen mensual corte y reconexión"));
        $this->SetFont('times', '', 10);
        $this->Text(180, 10, date('d/m/Y'));
        $this->SetFont('times', '', 13);
        $this->Text(82, 31.5, utf8_decode("Resultados Corte " . $this->periodoGen));

    }

    public function Footer()
    {
        $this->SetFont('times', "", 9);
        $this->Text(103, 285, utf8_decode("Page " . $this->PageNo()));

    }
}

class pdfRepInspMen extends FPDF
{

    private $periodoGen;
    private $proyecto;

    public function setPeriodoGen($valor)
    {
        $this->periodoGen = $valor;
    }

    public function setProyecto($valor)
    {
        $this->proyecto = $valor;
    }

    public function Header()
    {

        if ($this->proyecto == 'SD') {
            $this->Image("../../images/LogoCaasd.jpg", 7.5, 3.5, 11, 15.5);
        }

        if ($this->proyecto == 'BC') {
            $this->Image("../../images/coraabo.jpg", 7.5, 3.5, 11, 15.5);
        }

        $this->SetTextColor(0, 0, 0);
        $this->SetFont('times', '', 17);
        $this->Text(60, 13.5, utf8_decode("Resumen mensual de inspecciones"));
        $this->SetFont('times', '', 10);
        $this->Text(180, 10, date('d/m/Y'));
        $this->SetFont('times', '', 13);
        $this->Text(10, 31.5, utf8_decode("Resultados Inspecciones " . $this->periodoGen));

    }

    public function Footer()
    {
        $this->SetFont('times', "", 9);
        $this->Text(103, 285, utf8_decode("Page " . $this->PageNo()));

    }
}

class PdfHojRevAleCort extends FPDF
{

    private $proyecto;
    private $proIni;
    private $proFin;
    private $fecIni;
    private $fecFin;
    private $sector;
    private $ruta;
    private $inspector;

    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function setProIni($proIni)
    {
        $this->proIni = $proIni;
    }

    public function setProFin($proFin)
    {
        $this->proFin = $proFin;
    }
    public function setFecIni($fecIni)
    {
        $this->fecIni = $fecIni;
    }
    public function setFecFin($fecFin)
    {
        $this->fecFin = $fecFin;
    }

    public function setSector($sector)
    {
        $this->sector = $sector;
    }

    public function setRuta($ruta)
    {
        $this->ruta = $ruta;
    }
    public function setInspector($inspector)
    {
        $this->inspector = $inspector;
    }

    public function Header()
    {
        if ($this->proyecto == 'SD') {
            $this->Image("../../images/LogoCaasd.jpg", 7.5, 3.5, 11, 15.5);
        }
        if ($this->proyecto == 'BC') {
            $this->Image("../../images/coraabo.jpg", 7.5, 3.5, 11, 15.5);
        }

        $this->SetFont('times', "B", 19);

        $this->Text(54, 11, utf8_decode("Revisión en terreno Corte "));
        $this->SetFont('times', "", 13);
        $this->Text(58, 17, utf8_decode("Fecha de ralizacion: " . $this->fecIni));
        $this->Text(120, 17, utf8_decode("al: " . $this->fecFin));

        $this->SetFont('times', "", 10);
        if ($this->proyecto == 'SD') {
            $this->Text(5, 22, utf8_decode("CAASD Gerencia Comercial"));
        }
        if ($this->proyecto == 'BC') {
            $this->Text(5, 22, utf8_decode("CORAABO Gerencia Comercial"));
        }
        $this->Text(260, 27, utf8_decode(date('d/m/Y h:i A')));
        $this->Text(271, 30, utf8_decode('Pág ' . $this->PageNo() . ' de {nb}'));

        $this->SetFont('times', "B", 10);
        $this->Text(248, 10, utf8_decode("Código:"));
        $this->Text(248, 13, utf8_decode("Edición No.:"));
        $this->Text(248, 16, utf8_decode("Fecha de emisión:"));

        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(64, 128, 191);

        $this->SetLineWidth(0.3);
        $this->SetY(23);
        $this->SetX(1);
        $this->Cell(16, 5, utf8_decode('Sector:'), 1, 3, 'L', true);
        $this->Text(18, 27, utf8_decode($this->sector));

        $this->SetY(23);
        $this->SetX(26);
        $this->Cell(16, 5, utf8_decode('Ruta'), 1, 3, 'L', true);
        $this->Text(43, 27, utf8_decode($this->ruta));

        $this->SetY(28);
        $this->SetX(1);
        $this->Cell(16, 5, utf8_decode('Inspector:'), 1, 3, 'L', true);
        $this->Text(18, 32, utf8_decode($this->inspector));

        $this->SetY(33);

        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(64, 128, 191);

        $this->SetLineWidth(0.3);
        $this->SetX(1);
        $this->Cell(290, 5, utf8_decode('Codigo    Proceso      Catastro                    Nombre                                          Dirección                                    ET       Actividad                         U  MD   Serial      FP    Deuda         TC  L       OG    '), 1, 3, 'L', true);

    }

    public function Footer()
    {
        $this->SetFont('times', "", 12);
        $this->Text(3, 199, utf8_decode("Impordme-Santo Domingo"));

        $this->Text(55, 199, utf8_decode("ET=Estado;U=Uso;MD=Medidor;FP=Facturas pendientes;TC=Tipo corte; L=Lectura; OG=Observaciones generales"));

    }
}

class PdfHojCamb extends FPDF
{

    private $tipAccion;
    private $proyecto;
    private $fecha_gen;
    private $nombre_formulario;
    private $edicion_formulario;
    private $fecha_edicion;
    private $imagen;

    public function setTipAccion($tipAccion)
    {
        $this->tipAccion = $tipAccion;
    }

    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function setFechaGen($fecha){
        $this->fecha_gen = date('d-m-Y',strtotime($fecha));
    }

    public function Header()
    {
        $r = new Reporte();
        $this->DefOrientation = 'P';
        if ($this->proyecto == 'BC') {
            $this->Image("../../images/coraabo.jpg", 7.5, 3.5, 11, 15.5);
        }
        if ($this->proyecto == 'SD') {
            $this->Image("../../images/LogoCaasd.jpg", 7.5, 3.5, 11, 15.5);
        }

       /* if ($this->tipAccion == "C") {
            $tipoAccion = 'Cambio';
        } elseif ($this->tipAccion == "I") {
            $tipoAccion = 'Instalacion';
        }*/

        $this->SetFont('times', "", 10);
        if ($this->proyecto == 'BC') {
            $this->Text(5, 23, utf8_decode("CORAABO Gerencia Comercial"));
        }
        if ($this->proyecto == 'SD') {
            $this->Text(5, 23, utf8_decode("CAASD Gerencia Comercial"));
        }
        //TRAER LAS EDICIONES DEL FORMULARIO
        $ediciones = $r->getFormDates('FO-MED-12');


        while($row = oci_fetch_assoc($ediciones)){

            if(strtotime(date("d-M-Y")) >= strtotime($row["FECHA_EMISION"])){
                $this->nombre_formulario  =  $row["DESCRIPCION"];
                $this->edicion_formulario =  $row["EDICION"];
                $this->fecha_edicion      =  $row["FECHA_EMISION"];
            }
        }

        if($this->imagen != null){
            $this->Image($this->imagen, 7.5, 3.5, 17, 15.5);
        }
        $this->SetFont('times', "B", 16);
        $this->Text(60, 15, utf8_decode($this->nombre_formulario));
        $this->SetFont('times', "", 10);
        $this->Text(160, 9, utf8_decode("Codigo: FO-MED-12"));
        $this->Text(160, 13, utf8_decode("Edicion NO.: ".$this->edicion_formulario /*"04"*/));
        $this->Text(160, 17, utf8_decode("Fecha de emision: ".date('d-m-Y',strtotime($this->fecha_edicion)) /*"10-07-2018"*/));

    }

    public function Footer()
    {
        $this->SetFont('times', "", 12);
        $this->Text(3, 287, utf8_decode("Impordme-Santo Domingo"));

    }
}

class PdfHojMant extends FPDF
{

    private $tipAccion;
    private $proyecto;

    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function setTipAccion($tipAccion)
    {
        $this->tipAccion = $tipAccion;
    }

    public function Header()
    {
        $this->DefOrientation = 'P';

        if ($this->proyecto == 'SD') {$this->Image("../../images/LogoCaasd.jpg", 7.5, 3.5, 11, 15.5);} else { $this->Image("../../images/coraabo.jpg", 7.5, 3.5, 11, 15.5);}

        $this->SetFont('times', "B", 19);

        $this->Text(60, 15, utf8_decode("Mantenimiento al medidor " . $this->tipAccion));
        $this->SetFont('times', "", 10);

        if ($this->proyecto == 'SD') {$this->Text(5, 23, utf8_decode("CAASD Gerencia Comercial"));} else { $this->Text(5, 23, utf8_decode("CORAABO Gerencia Comercial"));}

        $this->Text(160, 23, utf8_decode(date('d-m-Y h:i A')));

    }

    public function Footer()
    {
        $this->SetFont('times', "", 12);
        $this->Text(3, 287, utf8_decode("Impordme-Santo Domingo"));

    }
}

class PdfHojFacCaasd extends FPDF
{

    private $fechMin;
    private $fechMax;
    private $fechaGen;
    private $nombre_formulario;
    private $edicion_formulario;
    private $fecha_edicion;
    private $imagen;


    public function setFechMin($fechMin)
    {
        $this->fechMin = $fechMin;
    }

    public function setFechMax($fechMax)
    {
        $this->fechMax = $fechMax;
    }

    public function setFechaGen($fecha){
        $this->fechaGen = date('d-m-Y',strtotime($fecha));
        //echo  $this->fechaGen;
    }

    public function Header()
    {
        $r = new Reporte();
        $this->SetTextColor(0, 0, 0);
        //$this->Image("../../images/aceadom201904.jpg", 7.5, 3.5, 17, 15.5);
        $this->SetFont('times', "B", 19);

        //$this->Text(54, 11, utf8_decode("Relación de inmuebles  "));
        $this->SetFont('times', "", 13);
        //$this->Text(58, 17, utf8_decode("Desde " . $this->fechMin));
        //$this->Text(98, 17, utf8_decode("Hasta " . $this->fechMax));
        $this->Text(12, 33, utf8_decode("Cliente"));
        $this->Text(9, 37, utf8_decode("Gerencia"));
        $this->Text(158, 33, utf8_decode("Fecha"));
        $this->Text(158, 37, utf8_decode("Tipo de instalacíon"));

        $this->SetFont('times', "", 10);
        $this->Text(5, 23, utf8_decode("CAASD Gerencia Comercial"));
        $this->Text(365, 27, utf8_decode(date('d/m/Y h:i A')));
        $this->Text(365, 30, utf8_decode('Pág ' . $this->PageNo() . ' de {nb}'));

        //TRAER LAS EDICIONES DEL FORMULARIO
        $ediciones = $r->getFormDates('FO-MED-05');

        while($row = oci_fetch_assoc($ediciones)){
            //echo print_r($row);
            if(strtotime($this->fechaGen) >= strtotime($row["FECHA_EMISION"])){

                $this->nombre_formulario  =  $row["DESCRIPCION"];
                $this->edicion_formulario =  $row["EDICION"];
                $this->fecha_edicion      =  $row["FECHA_EMISION"];
                $this->imagen             =  $row["IMAGEN"];
                $this->SetFont('times', "B", 19);

                $this->Text(54, 11, utf8_decode($this->nombre_formulario/*"Relación de inmuebles  "*/));
                if($this->imagen != null){
                    $this->Image($this->imagen, 7.5, 3.5, 17, 15.5);
                }
                $this->SetFont('times', "B", 10);
                $this->Text(365, 10, utf8_decode("Código:"));
                $this->Text(365, 13, utf8_decode("Edición No.:"));
                $this->Text(365, 16, utf8_decode("Fecha de emisión:"));

                $this->SetFont('times', "", 10);
                $this->Text(394, 10, utf8_decode("FO-MED-05"));
                $this->Text(394, 13, utf8_decode($this->edicion_formulario/*"03"*/));
                $this->Text(394, 16, utf8_decode(date('d-m-Y',strtotime($this->fecha_edicion)) /*"04/04/2019"*/));
            }
        }



    }

}

class pdfRepRecDia extends FPDF
{

    private $fechaGen;
    private $proyecto;

    public function setFechaGen($valor)
    {
        $date           = new DateTime($valor);
        $this->fechaGen = $date->format("d/m/Y");
    }

    public function setProyecto($valor)
    {
        $this->proyecto = $valor;
    }

    public function Header()
    {

        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(64, 128, 191);
        $this->SetTextColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('times', 'B', 12);
        $this->SetY(15);
        $this->SetX(17);
        $this->Cell(149, 21, "", 1, 3, '', false);

        $this->SetY(15);
        $this->SetX(17);
        $this->Cell(19, 21, "", 1, 3, '', false);

        $this->SetY(15);
        $this->SetX(123);
        $this->Cell(43, 21, "", 1, 3, '', false);

        $this->SetY(22);
        $this->SetX(123);
        $this->Cell(43, 7, "", 1, 3, '', false);
        if ($this->proyecto == 'SD') {
            $this->Image("../../images/LogoCaasd.jpg", 18.5, 17.5, 16, 17);
        } elseif ($this->proyecto == 'BC') {
            $this->Image("../../images/coraabo.jpg", 18.5, 17.5, 16, 17);
        }

        $this->SetFont('times', "B", 12);
        $this->Text(59, 25.5, utf8_decode("Reporte Pagos Reconexion"));

        $this->SetFont('times', "B", 9);
        $this->Text(124, 20, utf8_decode("Codigo:"));
        $this->Text(124, 27, utf8_decode("Edicion No.:"));
        $this->Text(124, 34, utf8_decode("Fecha de emision:"));

        $this->SetFont('times', "", 9);
//        $this->Text(135,20,utf8_decode("F0-COR-01"));
        //        $this->Text(141,27,utf8_decode("04"));
        //        $this->Text(149,34,utf8_decode("23-03-2016"));
        $this->Text(44, 40, utf8_decode("Fecha: " . $this->fechaGen));

    }

    public function Footer()
    {
        $this->SetFont('times', "", 9);
        $this->Text(171, 284, utf8_decode("Page " . $this->PageNo()));

    }
}

class PdfMaestromed extends FPDF
{

    private $proyecto;
    private $nombre_formulario;
    private $edicion_formulario;
    private $fecha_edicion;
    private $imagen;

    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function Header()
    {
        $r = new Reporte();
        $this->SetFont('times', "B", 19);

        $this->SetFont('times', "", 10);
        if ($this->proyecto == 'SD') {
            $this->Text(5, 23, utf8_decode("CAASD Gerencia Comercial"));
        }
        if ($this->proyecto == 'BC') {
            $this->Text(5, 23, utf8_decode("CORAABO Gerencia Comercial"));
        }
        $this->Text(260, 27, utf8_decode(date('d/m/Y h:i A')));
        $this->Text(271, 30, utf8_decode('Pág ' . $this->PageNo() . ' de {nb}'));

        //TRAER LAS EDICIONES DEL FORMULARIO
        $ediciones = $r->getFormDates('FO-MED-25');

        while ($row = oci_fetch_assoc($ediciones)) {
            if (strtotime(date('d-m-Y')) >= strtotime($row["FECHA_EMISION"])) {

                $this->nombre_formulario = $row["DESCRIPCION"];
                $this->edicion_formulario = $row["EDICION"];
                $this->fecha_edicion = $row["FECHA_EMISION"];
                $this->imagen = $row["IMAGEN"];
            }
        }

        if ($this->imagen != null) {
            $this->Image($this->imagen, 7.5, 3.5, 17, 15.5);
        }
        $this->SetFont('times', "B", 19);

        $this->Text(54, 11, utf8_decode($this->nombre_formulario));

        $this->SetFont('times', "B", 10);
        $this->Text(248, 10, utf8_decode("Código: FO-MED-25"));
        $this->Text(248, 13, utf8_decode("Edición No.: ".$this->edicion_formulario));
        $this->Text(248, 16, utf8_decode("Fecha de emisión: ". $this->fecha_edicion));

        $this->SetY(33);

        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(64, 128, 191);

        $this->SetLineWidth(0.3);
        $this->SetX(1);
        $this->Cell(290, 6, utf8_decode('Codigo  Urbanización  Dirección                                          Cliente                                       Proceso         Catastro                Medidor   Serial     Emplaza.          Calibre      Uso  Estado  lectura  obs Lec.'), 1, 3, 'L', true);

    }

    public function Footer()
    {
        $this->SetFont('times', "", 12);
        $this->Text(3, 287, utf8_decode("Impordme-Santo Domingo"));

    }
}

class PdfInmCortConLec extends FPDF
{

    private $proMin;
    private $proMax;
    private $proyecto;

    public function setProIni($proMin)
    {
        $this->proMin = $proMin;
    }
    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function setProFin($proMax)
    {
        $this->proMax = $proMax;
    }

    public function Header()
    {

        if ($this->proyecto == 'SD') {
            $this->Image("../../images/LogoCaasd.jpg", 7.5, 3.5, 11, 15.5);
        }
        if ($this->proyecto == 'BC') {
            $this->Image("../../images/coraabo.jpg", 7.5, 3.5, 11, 15.5);
        }

        $this->SetFont('times', "B", 19);

        $this->Text(54, 11, utf8_decode("Relación de inmuebles cortados con lectura  "));
        $this->SetFont('times', "", 13);
        $this->Text(58, 17, utf8_decode("Desde " . $this->proMin));
        $this->Text(98, 17, utf8_decode("Hasta " . $this->proMax));

//        $this->Text(12,33,utf8_decode("Cliente"));
        $this->Text(9, 37, utf8_decode("Gerencia"));
//        $this->Text(158,33,utf8_decode("Fecha"));
        $this->Text(158, 37, utf8_decode("Tipo de instalacíon"));

        $this->SetFont('times', "", 10);

        if ($this->proyecto == 'SD') {
            $this->Text(5, 23, utf8_decode("CAASD Gerencia Comercial"));
        }
        if ($this->proyecto == 'BC') {
            $this->Text(5, 23, utf8_decode("CORAABO Gerencia Comercial"));
        }

        $this->Text(260, 27, utf8_decode(date('d/m/Y h:i A')));
        $this->Text(271, 30, utf8_decode('Pág ' . $this->PageNo() . ' de {nb}'));

        $this->SetFont('times', "B", 10);
        $this->Text(248, 10, utf8_decode("Código:"));
        $this->Text(248, 13, utf8_decode("Edición No.:"));
        $this->Text(248, 16, utf8_decode("Fecha de emisión:"));

        $this->SetFont('times', "", 10);
        $this->Text(260, 10, utf8_decode(""));
        $this->Text(267, 13, utf8_decode(""));
        $this->Text(275, 16, utf8_decode(""));

        $this->SetY(33);
        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(64, 128, 191);
        $this->SetLineWidth(0.3);
        $this->SetX(1);
        $this->SetFont('times', "", 8);
        $this->Cell(290, 5, utf8_decode('CODIGO    PROCESO        CATASTRO                   SERIAL          LC            FEC. CORTE                US. CORTE              FEC. INS.         US. INSPECCION          PLAN         OLAN              LAN            PLAC         OLAC           LAC            DIF'), 1, 3, 'L', true);

    }

    public function Footer()
    {
        $this->SetFont('times', "", 12);
        // $this->Text(3,199,utf8_decode("Impordme-Santo Domingo"));
        $this->SetFont('times', "", 7);
        $this->Text(2, 199, utf8_decode("LC=Lectura corte;PLAN=periodo lectura anterior;OLAN=Obs. lectura anterior;LAN=Lectura anterior;PLAC=Periodo lectura actual; OLAC=Obs. LECTURA ACTUAL; LAC=Lectura actual"));

    }

}

class PdfInteraccDiar extends FPDF
{

    private $proyecto;

    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function Header()
    {

        if ($this->proyecto == 'SD') {
            $this->Image("../../images/LogoCaasd.jpg", 7.5, 3.5, 11, 15.5);
        }
        if ($this->proyecto == 'BC') {
            $this->Image("../../images/coraabo.jpg", 7.5, 3.5, 11, 15.5);
        }

        $this->SetFont('times', "B", 19);

        $this->Text(54, 11, utf8_decode("Reporte interacciones diarias "));
        $this->SetFont('times', "", 13);

        $this->SetFont('times', "", 10);

        if ($this->proyecto == 'SD') {
            $this->Text(5, 23, utf8_decode("CAASD Gerencia Comercial"));
        }
        if ($this->proyecto == 'BC') {
            $this->Text(5, 23, utf8_decode("CORAABO Gerencia Comercial"));
        }

        $this->Text(260, 27, utf8_decode(date('d/m/Y h:i A')));
        $this->Text(271, 30, utf8_decode('Pág ' . $this->PageNo() . ' de {nb}'));

        $this->SetFont('times', "B", 10);
        $this->Text(248, 10, utf8_decode("Código:"));
        $this->Text(248, 13, utf8_decode("Edición No.:"));
        $this->Text(248, 16, utf8_decode("Fecha de emisión:"));

        $this->SetFont('times', "", 10);
        $this->Text(260, 10, utf8_decode(""));
        $this->Text(267, 13, utf8_decode(""));
        $this->Text(275, 16, utf8_decode(""));

        $this->SetY(33);
        $this->SetDrawColor(0, 0, 0);
        $this->SetFillColor(64, 128, 191);
        $this->SetLineWidth(0.3);
        $this->SetX(1);
        $this->SetFont('times', "", 8);
        $this->Cell(290, 5, utf8_decode('No              Fecha                                      Asunto                                                                                                                            Texto                                                                                                                                                          Ususario           Inmueble '), 1, 3, 'L', true);

    }

    public function Footer()
    {
        $this->SetFont('times', "", 12);
        // $this->Text(3,199,utf8_decode("Impordme-Santo Domingo"));
        $this->SetFont('times', "", 7);
//        $this->Text(2,199,utf8_decode("LC=Lectura corte;PLAN=periodo lectura anterior;OLAN=Obs. lectura anterior;LAN=Lectura anterior;PLAC=Periodo lectura actual; OLAC=Obs. LECTURA ACTUAL; LAC=Lectura actual"));

    }

}

class PdfNotaCreditoRep extends FPDF
{
    private $proyecto;
    private $rnc;
    private $fechaEmision;
    private $ncf;
    private $tipoNota;


    public function SetProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function setRNC($rncProyecto)
    {
        $this->rnc = $rncProyecto;

    }

    public function SetFechaEmision($fechaEmision)
    {
        $this->fechaEmision = $fechaEmision;

    }

    public function SetNCF($ncf)
    {
         $this->ncf = $ncf;

    }

    public function SetTipoNota($tipoNota)
    {
        $this->tipoNota = $tipoNota;
    }


    public function Header()
    {
        $posX = 15;
        $this->SetFont('times', "B", 20);
        if ($this->proyecto == 'SD') {
            $this->Text($posX, 15, 'CAASD');
        }
        if ($this->proyecto == 'BC') {
            $this->Text($posX, 15, 'CORAABO');
        }
        $this->SetFont('times', "", 12);
        $this->Text($posX, 20, "RNC: " . $this->rnc);
        $this->Text($posX, 24, "FECHA: " . $this->fechaEmision);
        $this->SetFont('times', "", 16);
        $this->Text(115, 35, utf8_decode($this->tipoNota));


    }

    public function Footer()
    {
        $posX=165;
        $this->SetFont("Arial",'',8);
        $this->Text($posX, 185, "Original: Cliente");
        $this->Text($posX, 189, "Copia: Vendedor");
    }

    function CuerpoNotaCredito()
    {
        $posX=160;
        $this->SetFont('times', "B", 10);
        $this->Text($posX,20,"NCF:".$this->ncf);




    }
}

class Reporte extends ConexionClass{

    function getFormDates($nombre_formulario){

        $sql = "SELECT F.CODIGO,F.DESCRIPCION, FE.EDICION,FE.FECHA_EMISION,FE.IMAGEN
FROM SGC_TP_FORMULARIO F,SGC_TP_FORMULARIO_EDICION FE
WHERE F.CODIGO = FE.CODIGO_FORMULARIO
      AND   F.CODIGO = '$nombre_formulario' and
  EDICION=(select max(EDICION) from SGC_TP_FORMULARIO_EDICION FE2
  where fe2.CODIGO_FORMULARIO=f.CODIGO
  )
ORDER BY FE.FECHA_EMISION ASC";

        $resultado = oci_parse($this->_db,$sql);
        $bandera   = oci_execute($resultado);

        if($bandera){
            oci_close($this->_db);
         //   echo 'entro';
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

}