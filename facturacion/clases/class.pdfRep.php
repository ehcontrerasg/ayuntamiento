<?php
/**
 * Created by PhpStorm.
 * User: Edwin
 * Date: 3/06/2016
 * Time: 11:18 AM
 */
date_default_timezone_set('America/Santo_Domingo');
require_once "class.fpdp.php";
session_start();

class pdfEstConcepto extends FPDF
{

    private $grupo;
    private $cliente;
    private $codSistema;
    private $urbanizacion;
    private $nombreCliente;
    private $direccion;
    private $zona;
    private $catastro;
    private $proceso;
    private $boletin;
    private $proyecto;

    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    }

    public function setCliente($cliente)
    {
        $this->cliente = $cliente;
    }

    public function setCodSistema($codSistema)
    {
        $this->codSistema = $codSistema;
    }

    public function setUrbanizacion($urbanizacion)
    {
        $this->urbanizacion = $urbanizacion;
    }

    public function setNombreCliente($nombreCliente)
    {
        $this->nombreCliente = $nombreCliente;
    }

    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    public function setZona($zona)
    {
        $this->zona = $zona;
    }

    public function setCatastro($catastro)
    {
        $this->catastro = $catastro;
    }

    public function setProceso($proceso)
    {
        $this->proceso = $proceso;
    }

    public function setBoletin($boletin)
    {
        $this->boletin = $boletin;
    }
    public function setProyecto($proyecto)
    {
        $this->proyecto = $proyecto;
    }

    public function Header()
    {
        switch ($this->proyecto){
            case 'SD':
                $this->Image("../../images/logo_caasd2.jpg", 6, 2, 11, 16);
                $this->SetFont('times', "B", 16);
                $this->Text(41.5, 8.5, utf8_decode("Estados de Cuentas por Conceptos"));
                $this->SetFont('times', "", 8);
                $this->Text(6, 21.5, utf8_decode("CAASD, Gerencia Comercial"));
                break;
            case 'BC':
                $this->Image("../../images/coraabo.jpg", 6, 2, 11, 16);
                $this->SetFont('times', "B", 16);
                $this->Text(41.5, 8.5, utf8_decode("Estados de Cuentas por Conceptos"));
                $this->SetFont('times', "", 8);
                $this->Text(6, 21.5, utf8_decode("CORAABO, Gerencia Comercial"));
                break;
        }


        $fecha = getdate();
        $this->Text(155, 21.5, utf8_decode($fecha[mday] . "-" . $fecha[mon] . "-" . $fecha[year] . " " . $fecha[hours] . ":" . $fecha[minutes]));
        $this->Text(172, 25, utf8_decode("Pág: ") . $this->PageNo() . ' / {nb}', 0, 0, 'R');

        $this->SetFont('times', "B", 8);
        $this->Text(6, 30, utf8_decode("Grupo"));
        $this->Text(6, 35, utf8_decode("Cliente"));
        $this->Text(6, 40, utf8_decode("Cod.Sistema"));
        $this->Text(6, 45, utf8_decode("Edificio"));
        $this->Text(81.5, 45, utf8_decode("Boletin"));

        $this->Text(135, 35, utf8_decode("Zona"));
        $this->Text(135, 40, utf8_decode("Catastro"));
        $this->Text(135, 45, utf8_decode("Id.Proceso"));

        $this->SetFont('times', "", 8);
        $this->Text(154, 35, utf8_decode($this->zona));
        $this->Text(154, 40, utf8_decode($this->catastro));
        $this->Text(154, 45, utf8_decode($this->proceso));
        $this->Text(23, 30, utf8_decode($this->grupo));
        $this->Text(23, 45, utf8_decode($this->urbanizacion));
        $this->Text(51, 35, utf8_decode($this->nombreCliente));
        $this->Text(101, 45, utf8_decode($this->boletin));
        $this->Text(23, 35, utf8_decode($this->cliente));
        $this->Text(51, 40, utf8_decode($this->direccion));
        $this->Text(23, 40, utf8_decode($this->codSistema));

    }

    public function Footer()
    {
        $this->SetFont('times', "", 9);
        $this->SetTextColor(73, 133, 133);
        $this->Text(7, 272, utf8_decode("impcpto - Santo Domingo"));

    }
}

class pdfEstCuenta extends FPDF
{

    private $grupo;
    private $cliente;
    private $codSistema;
    private $urbanizacion;
    private $nombreCliente;
    private $direccion;
    private $zona;
    private $catastro;
    private $proceso;
    private $boletin;
    private $proyecto;

    public function setProyecto($proy)
    {
        $this->proyecto = $proy;
    }

    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    }

    public function setCliente($cliente)
    {
        $this->cliente = $cliente;
    }

    public function setCodSistema($codSistema)
    {
        $this->codSistema = $codSistema;
    }

    public function setUrbanizacion($urbanizacion)
    {
        $this->urbanizacion = $urbanizacion;
    }

    public function setNombreCliente($nombreCliente)
    {
        $this->nombreCliente = $nombreCliente;
    }

    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    public function setZona($zona)
    {
        $this->zona = $zona;
    }

    public function setCatastro($catastro)
    {
        $this->catastro = $catastro;
    }

    public function setProceso($proceso)
    {
        $this->proceso = $proceso;
    }

    public function setBoletin($boletin)
    {
        $this->boletin = $boletin;
    }

    public function Header()
    {

        switch ($this->proyecto){
            case 'SD':
                $this->Image("../../images/logo_caasd2.jpg", 6, 2, 11, 16);
                $this->SetFont('times', "B", 16);
                $this->Text(41.5, 8.5, utf8_decode("Estados de Cuentas"));
                $this->SetFont('times', "", 8);
                $this->Text(6, 21.5, utf8_decode("CAASD, Gerencia Comercial"));
                break;
            case 'BC':
                $this->Image("../../images/coraabo.jpg", 6, 2, 11, 16);
                $this->SetFont('times', "B", 16);
                $this->Text(41.5, 8.5, utf8_decode("Estados de Cuentas"));
                $this->SetFont('times', "", 8);
                $this->Text(6, 21.5, utf8_decode("CORAABO, Gerencia Comercial"));
                break;
        }
       /* if ($this->proyecto == 'SD') {
            $this->Image("../../images/logo_caasd2.jpg", 6, 2, 11, 16);
            $this->SetFont('times', "B", 16);
            $this->Text(41.5, 8.5, utf8_decode("Estados de Cuentas"));
            $this->SetFont('times', "", 8);
            $this->Text(6, 21.5, utf8_decode("CAASD, Gerencia Comercial"));
        } else {
            $this->Image("../../images/coraabo.jpg", 6, 2, 11, 16);
            $this->SetFont('times', "B", 16);
            $this->Text(41.5, 8.5, utf8_decode("Estados de Cuentas"));
            $this->SetFont('times', "", 8);
            $this->Text(6, 21.5, utf8_decode("CORAABO, Gerencia Comercial"));
        }*/

        /*$this->Image("../../images/logo_caasd2.jpg", 6, 2, 11, 16);
        $this->SetFont('times', "B", 16);
        $this->Text(41.5, 8.5, utf8_decode("Estados de Cuentas"));
        $this->SetFont('times', "", 8);
        $this->Text(6, 21.5, utf8_decode("CAASD, Gerencia Comercial"));*/
        $fecha = getdate();
        $this->Text(155, 21.5, utf8_decode($fecha[mday] . "-" . $fecha[mon] . "-" . $fecha[year] . " " . $fecha[hours] . ":" . $fecha[minutes]));
        $this->Text(172, 25, utf8_decode("Pág: ") . $this->PageNo() . ' / {nb}', 0, 0, 'R');

        $this->SetFont('times', "B", 8);
        $this->Text(6, 30, utf8_decode("Grupo"));
        $this->Text(6, 35, utf8_decode("Cliente"));
        $this->Text(6, 40, utf8_decode("Cod.Sistema"));
        $this->Text(6, 45, utf8_decode("Edificio"));
        $this->Text(81.5, 45, utf8_decode("Boletin"));

        $this->Text(135, 35, utf8_decode("Zona"));
        $this->Text(135, 40, utf8_decode("Catastro"));
        $this->Text(135, 45, utf8_decode("Id.Proceso"));

        $this->SetFont('times', "", 8);
        $this->Text(154, 35, utf8_decode($this->zona));
        $this->Text(154, 40, utf8_decode($this->catastro));
        $this->Text(154, 45, utf8_decode($this->proceso));
        $this->Text(23, 30, utf8_decode($this->grupo));
        $this->Text(23, 45, utf8_decode($this->urbanizacion));
        $this->Text(51, 35, utf8_decode($this->nombreCliente));
        $this->Text(101, 45, utf8_decode($this->boletin));
        $this->Text(23, 35, utf8_decode($this->cliente));
        $this->Text(51, 40, utf8_decode($this->direccion));
        $this->Text(23, 40, utf8_decode($this->codSistema));

    }

    public function Footer()
    {
        $this->SetFont('times', "", 9);
        $this->SetTextColor(73, 133, 133);
        $this->Text(7, 272, utf8_decode("impcpto - Santo Domingo"));

    }
}
