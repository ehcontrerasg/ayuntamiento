<?php
/**
 * Created by PhpStorm.
 * User: Edwin
 * Date: 3/06/2016
 * Time: 11:18 AM
 */
date_default_timezone_set('America/Bogota');
require_once("class.fpdp.php");
session_start();




class PdfRepRecConc extends FPDF {

    private $idConcepto;
    private $fechPagoIni;
    private $fechPagoFin;
    private $descConcepto;
	private $proyecto;


    public function setIdConcepto($idConcepto)
    {
        $this->idConcepto = $idConcepto;
    }

    public function setDescConcepto($descConcepto)
    {
        $this->descConcepto = $descConcepto;
    }


    public function setFechPagoIni($fechPagoIni)
    {
        $this->fechPagoIni = $fechPagoIni;
    }

    public function setFechPagoFin($fechPagoFin)
    {
        $this->fechPagoFin = $fechPagoFin;
    }

	function Header() {
		
			$this->SetFont('times',"B",19);
			$this->Text(60,15,utf8_decode("Reporte de recaudos por conceptos(agrupado)"));
			$this->SetFont('times',"",12);
			$this->Text(60,20,utf8_decode("Fecha de pago: ".$this->fechPagoIni." - ".$this->fechPagoFin.", Conceptos: ".$this->descConcepto));
		
    }

    function Footer() {
        $this->SetFont('times',"",12);
        $this->Text(171,284,utf8_decode("Page ".$this->PageNo()));

    }
}



