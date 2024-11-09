<?php 
require '../../librerias/fpdf/cellfit.php';
class FacturaInstantanea extends  FPDF_CellFit {

    public $ncf =   null;
    public $rnc =   null;
    public $numeroFactura   = null;
    public $fechaEmision   = null;
    public $mensaje_ncf = null;
    public $mensaje_periodo = null;
    public $correo_proyecto = null;

    function __construct($orientation='P', $unit='mm', $size='A4'){
        parent::__construct($orientation ,$unit ,$size);
    }

    function Header(){
        $this->SetFont('Arial','',7.33333);
        $this->Text(241.143,27.5793,'FACTURA PARA CONSUMIDOR FINAL');

        $this->SetFont('Arial','B',7.06667);
        $this->Text(241.143,38.4483,'NCF:');        
        $this->SetFont('Arial','',7.06667);
        $this->SetXY(258.38,36.45);
        $this->CellFit(0.46,0,$this->ncf,0,0,'',false,'',true,false);
        
        $this->SetFont('Arial','B',7.06667);
        $this->Text(306.475,38.4483,'RNC:');
        $this->SetFont('Arial','',7.06667);
        $this->SetXY(324.59,36.45);
        $this->CellFit(0.46,0,$this->rnc,0,0,'',false,'',true,false);
                
        /* Número de factura */
        $this->SetFont('Arial','B',9.33333);
        $this->Text(118.721,88.958, $this->numeroFactura);     
        /* Fin de Número de factura */

        /* Fecha de emisión */
        $this->SetFont('Arial','B',9.33333);
        $this->Text(223.047,88.958, utf8_decode("Fecha de emisión:"));
        $this->SetFont('Arial','',9.33333);
        $this->Text(308.708,88.958, $this->fechaEmision);
        /* Fin de fecha de emisión */
    }

    function Footer(){
        $this->SetXY(111.58,603.682);
        $this->SetFont('','B',9.33);
        $this->Cell(0,0,$this->mensaje_ncf);
        $this->Ln(0.1);

        $this->SetFont('','I',8);
        $this->SetX(12.3815);
        $this->mensaje_periodo .= "\n\nSi necesita que su factura tenga valor fiscal favor de solicitarlo enviándonos un correo a ";
        $this->mensaje_periodo = utf8_decode($this->mensaje_periodo);
        $this->MultiCell(3.55,0.1,$this->mensaje_periodo,'C');
        $this->SetFont('','BUI',8);
        $this->Write(0.1,$this->correo_proyecto);
    }

    function Text($x, $y, $txt){
        $x /= 96;
        $y /= 96;
        parent::Text($x, $y, $txt);
    }

    function SetFontSize($size){
        $size *= 0.75;
        parent::SetFontSize($size);
    }

    function SetFont($family, $style = '', $size = 0){
        $size *= 0.75;
        parent::SetFont($family, $style, $size);
    }

     function SetX($x){
        $x /= 96;
        parent::SetX($x);
    }

    function SetY($y,$resetX=true){
        $y /= 96;
        parent::SetY($y,$resetX);
    }

    function SetXY($x, $y){
        $this->SetX($x);
        $this->SetY($y, false);
    } 
}