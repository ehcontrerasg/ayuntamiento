<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true):
    session_start();
    require('../clases/pdf_js.php');
    require'../clases/classPagos.php';
    $coduser = $_SESSION['codigo'];
    $cod_inmueble=$_GET['cod_inmueble'];
    $id_rec=$_GET['id_rec'];
    $num_caja=$_GET['num_caja'];
    $des_punto=$_GET['des_punto'];
    $des_ent=$_GET['des_ent'];
    $nom_cli=$_GET['nom_cli'];
    $importe1=$_GET['importe1'];
    $importe2=$_GET['importe2'];
    $importe3=$_GET['importe3'];
    $monto=$_GET['monto'];
    $vuelta=$_GET['vuelta'];
    $medio=$_GET['medio'];

    if($importe1 != '')$importe = $importe1;
    if($importe2 != '')$importe = $importe2;
    if($importe3 != '')$importe = $importe3;
    if($medio == 1) $des_medio = 'Efectivo';
    if($medio == 2) $des_medio = 'Cheque';
    if($medio == 3) $des_medio = 'Tarjeta';
    if($medio == 4) $des_medio = 'Transferencia';
    if($medio == 5) $des_medio = 'Efectivo y Tarjeta';
    if($medio == 6) $des_medio = 'Efectivo y Cheque';

    $piezas = explode(" ",$concepto);
    $concepto = $piezas[0];

    $c = new Pagos();
    $resultado = $c->obtenerFechaRecaudo($id_rec);
    while (oci_fetch($resultado)) {
        $fecha = oci_result($resultado, 'FECHA_PAGO');
        $login = oci_result($resultado, 'LOGIN');
        $desconcepto = oci_result($resultado, 'DESC_SERVICIO');
    }oci_free_statement($resultado);

    class PDF_AutoPrint extends PDF_JavaScript
    {
        function AutoPrint($dialog=false)
        {
            //Open the print dialog or start printing immediately on the standard printer
            $param=($dialog ? 'true' : 'false');
            $script="print($param);";
            $this->IncludeJS($script);
        }
        function AutoPrintToPrinter($server, $printer, $dialog=false)
        {
            //Print on a shared printer (requires at least Acrobat 6)
            $script = "var pp = getPrintParams();";
            if($dialog)
                $script .= "pp.interactive = pp.constants.interactionLevel.full;";
            else
                $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
            $script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
            $script .= "print(pp);";
            $this->IncludeJS($script);
        }
    }

    $c = new Pagos();
    $resultado = $c->obtieneProyecto($cod_inmueble);
    while (oci_fetch($resultado)) {
        $acueducto = oci_result($resultado, 'ID_PROYECTO');
    }oci_free_statement($resultado);
    if ($acueducto == 'SD') {
        $titulorec = 'CAASD. Gerencia Comercial';
        $footrec = 'CAASD, Gerencia Comercial';
        $posxtr = 7;
        $posxft = 17;
        $imagenrec = '../../images/caasd.jpg';
    }
    if ($acueducto == 'BC') {
        $titulorec = 'CORAABO. Gerencia Comercial';
        $footrec = 'CORAABO, Gerencia Comercial';
        $posxtr = 6;
        $posxft = 16;
        $imagenrec = '../../images/coraabo.jpg';
    }

    $largo = 240;
    $pdf=new PDF_AutoPrint('P','mm','A7',$largo);
    $pdf->AddPage();
    $pdf->SetFont('Arial','',8);
    $pdf->Text($posxtr, 2, $titulorec);
    $pdf->Text(5, 5, 'Comprobante Otros Rec # '.$id_rec);
    $pdf->Text(0, 6, '___________________________________________________________________');
    $pdf->Ln(-5);
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(6,6,utf8_decode('Código: '),0,0,'R');
    $pdf->Cell(30,6,$cod_inmueble,0,0,'L');
    $pdf->Ln(5);
    $pdf->Cell(6,2,'Cliente:',0,0,'R');
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(30,2,$nom_cli,0,0,'L');
    $pdf->SetFont('Arial','',7);
    $pdf->Text(0, 13, '________________________________________________________________________');
    $pdf->Ln(1);
    $pdf->Cell(4,8,'Fecha:',0,0,'R');
    $pdf->Cell(10,8,$fecha,0,0,'L');
    $pdf->Ln(3);
    $pdf->Cell(4,8,'Entidad:',0,0,'R');
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(10,8,$des_ent,0,0,'L');
    $pdf->Ln(3);
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(4,8,'Punto:',0,0,'R');
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(10,8,$des_punto,0,0,'L');
    $pdf->Ln(3);
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(4,8,'Caja:',0,0,'R');
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(8,8,$num_caja,0,0,'L');
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(8,8,'Usuario:',0,0,'R');
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(10,8,$login,0,0,'L');
    $pdf->Text(0, 26, '__________________________________________________');
    $pdf->SetFont('Arial','',7);
    $pdf->Text(5, 29, 'CONCEPTO PAGO EN ESTE RECIBO');
    $pdf->Text(1,32,'Concepto');
    $pdf->Text(26,32,'Valor');
    $pdf->Text(40,32,'Pagado');
    $pdf->SetFont('Arial','',6);
    $px = 35;
    $pdf->Text(1,$px,$desconcepto);
    $pdf->Text(27,$px,$importe);
    $pdf->Text(42,$px,$importe);
    $px = $px+5;
    $pdf->SetFont('Arial','',7);
    $pdf->Text(2,$px,'Importe Total');
    $pdf->Text(40,$px -4, '________');
    $pdf->SetFont('Arial','',7);
    $pdf->Text(42,$px,number_format($importe,0,'.',','));
    $px = $px + 4;
    if($medio == 1 || $medio == 5 || $medio == 6){
        $pdf->Text(2,$px,'Monto '.number_format($monto,0,'.',','));
        if ($vuelta>0){
            $pdf->Text(22,$px,'Cambio '.number_format($vuelta,0,'.',','));
        }

    }
    if($medio == 2 || $medio == 3 || $medio == 4){
        $pdf->Text(2,$px,'Monto '.number_format($importe,0,'.',','));
        if ($vuelta>0){
            $pdf->Text(22,$px,'Cambio '.number_format($vuelta,0,'.',','));
        }
    }
    $pdf->Text(0, $px + 2, '__________________________________________');
    $pdf->Text(2, $px + 5, 'FORMA DE PAGO');
    $pdf->Text(2, $px + 9,'Tipo');
    $pdf->Text(22,$px + 9,utf8_decode('Descripción'));
    $pdf->Text(2, $px + 13,$medio);
    $pdf->Text(22, $px + 13,$des_medio);
    $pdf->Image($imagenrec,3,$px+17,4);
    $pdf->Rect(0.5,$px+15,52,9);
    $pdf->SetTextColor(19,30,181);
    $pdf->Text(25, $px + 18,'PAGADO');
    $pdf->SetTextColor(64,65,68);
    $pdf->Text(19, $px + 21,$fecha);
    $pdf->SetFont('Arial','',6);
    $pdf->Text($posxft, $px + 23,$footrec);
//Open the print dialog
    $pdf->AutoPrint(true);
    $pdf->Output();
endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

