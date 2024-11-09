<?PHP
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true):

    session_start();
    require('../../clases/pdf_js.php');
    require'../../clases/classPagos.php';
    $coduser = $_SESSION['codigo'];
    $cod_inmueble=$_GET['cod_inmueble'];
    $num_caja=$_GET['num_caja'];
    $des_punto=$_GET['des_punto'];
    $des_ent=$_GET['des_ent'];
    $nom_cli=$_GET['nom_cli'];
    $deuda=$_GET['deuda'];
    $monto=$_GET['monto'];
    $vuelta=$_GET['vuelta'];
    $medio=$_GET['medio'];
    $favor=$_GET['favor'];
    if($medio == 1) $des_medio = 'Efectivo';
    if($medio == 2) $des_medio = 'Cheque';
    if($medio == 3) $des_medio = 'Tarjeta de Credito';
    if($medio == 4) $des_medio = 'Transferencia';
    if($medio == 5) $des_medio = 'Efectivo y Tarjeta';
    if($medio == 6) $des_medio = 'Efectivo y Cheque';

    class PDF_AutoPrint extends PDF_Javascript
    {

        function AutoPrint($dialog=false)
        {

            //Launch the print dialog or start printing immediately on the standard printer
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
        $titulorec = 'Ayuntamiento De Santo Domingo';
        $footrec = 'Ayuntamiento De Santo Domingo';
        $posxtr = 6;
        $posxft = 17;
        $imagenrec = '../../images/LogoAseo.jpeg';
    }
    if ($acueducto == 'BC') {
        $titulorec = 'Ayuntamiento De Bocachica';
        $footrec = 'Ayuntamiento De Bocachica';
        $posxtr = 6;
        $posxft = 16;
        $imagenrec = '../../images/AyuntamientoBocachica.jpeg';
    }
    $largo = 240;
    $c = new Pagos();
    $resultado = $c->obtenerMaxPago($cod_inmueble);
    while (oci_fetch($resultado)) {
        $id_pago = oci_result($resultado, 'ID_PAGO');
    }oci_free_statement($resultado);
    $c = new Pagos();
    $resultado = $c->obtenerCantidadDatosPago($id_pago);
    while (oci_fetch($resultado)) {
        $cantRegistros = oci_result($resultado, 'CANTIDAD');
    }oci_free_statement($resultado);
    $aumento = (6*$cantRegistros);
    $descod = utf8_decode('Código: ');
    $desdes = utf8_decode('Descripción');
    if($cantRegistros >= 40) $largo = 500;
    $totalHoja = $largo + $aumento;
    $pdf=new PDF_AutoPrint('P','mm','A7',$totalHoja);
    $pdf->AddPage();
    $pdf->SetFont('Arial','',8);
    $pdf->Text($posxtr, 2, $titulorec);
    $pdf->Text(4, 5, 'Comprobante de Pago # '.$id_pago);
    $pdf->Text(0, 6, '___________________________________________________________________');
    $pdf->Ln(-5);
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(6,6,$descod,0,0,'R');
    $pdf->Cell(30,6,$cod_inmueble,0,0,'L');
    $pdf->Ln(5);
    $pdf->Cell(6,2,'Cliente:',0,0,'R');
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(30,2,$nom_cli,0,0,'L');
    $pdf->SetFont('Arial','',7);
    $pdf->Text(0, 13, '________________________________________________________________________');
    $pdf->Ln(1);

    $c = new Pagos();
    $resultado = $c->obtenerFechaPago($id_pago);
    while (oci_fetch($resultado)) {
        $fecha = oci_result($resultado, 'FECHA_PAGO');
    }oci_free_statement($resultado);

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

    $c = new Pagos();
    $resultado = $c->seleccionaDatosReciboPago($id_pago);
    while (oci_fetch($resultado)) {
        $login = oci_result($resultado, 'ID_USUARIO');
    }oci_free_statement($resultado);

    $pdf->Cell(8,8,'Usuario:',0,0,'R');
    $pdf->SetFont('Arial','',6);
    $pdf->Cell(10,8,$login,0,0,'L');
    $pdf->Text(0, 26, '__________________________________________________');
    $pdf->SetFont('Arial','',7);
    $pdf->Text(3, 29, 'FACTURAS PAGADAS EN ESTE RECIBO');
    $pdf->Text(1,32,'Periodo');
    $pdf->Text(13,32,'Total');
    $pdf->Text(23,32,'Pagado');
    $pdf->Text(35,32,'Comprobante');

    $pdf->SetFont('Arial','',6);
    $px = 35;
    $c = new Pagos();
    $resultado = $c->obtenerDatosPago($id_pago);
    while (oci_fetch($resultado)) {
        $periodo = oci_result($resultado, 'PERIODO');
        $pendiente = oci_result($resultado, 'PENDIENTE');
        $pagado = oci_result($resultado, 'PAGADO');
        $comprobante = oci_result($resultado, 'COMPROBANTE');
        $pdf->Text(2,$px,$periodo);
        $pdf->Text(14,$px,$pendiente);
        $pdf->Text(24,$px,$pagado);
        $pdf->Text(35,$px,$comprobante);
        $px = $px+3;
        $importe += $pagado;
    }oci_free_statement($resultado);
    $pdf->SetFont('Arial','',7);
    $pdf->Text(2,$px+1,'Importe Total');
    $pdf->Text(23,$px -2, '_______');
    $pdf->Text(23,$px+1,number_format($importe,0,'.',','));
    $px = $px+5;
    $pdf->Text(2,$px-1,'Restante');
    $pdf->Text(23,$px-1,number_format(round($deuda - $importe),0,'.',','));
    $px = $px + 2;
    if($medio == 1 || $medio == 5 || $medio == 6){
        $pdf->Text(2,$px,'Monto '.number_format($monto,0,'.',','));
        $pdf->Text(23,$px,'Cambio '.number_format($vuelta,0,'.',','));
    }
    if($medio == 2 || $medio == 3 || $medio == 4){
        $pdf->Text(2,$px,'Monto '.number_format($importe,0,'.',','));
        $pdf->Text(23,$px,'Cambio '.number_format(0,0,'.',','));
    }
    if($favor > 0){
        $pdf->Text(2,$px+3,'Saldo a Favor ');
        $pdf->Text(23,$px+3,number_format($favor,0,'.',','));
        $px = $px + 3;
    }
    $pdf->Text(0, $px + 1, '____________________________________________');
    $pdf->Text(2, $px + 4, 'FORMA DE PAGO');
    $pdf->Text(2, $px + 8,'Tipo');
    $pdf->Text(17,$px + 8,$desdes);
    $pdf->Text(2, $px + 11,$medio);
    $pdf->Text(17, $px + 11,$des_medio);
    $pdf->Image($imagenrec,3,$px+12.5,8);
    $pdf->Rect(0.5,$px+12,52,9);
    $pdf->SetTextColor(19,30,181);
    $pdf->Text(27, $px + 15,'PAGADO');
    $pdf->SetTextColor(64,65,68);
    $pdf->Text(21, $px + 18,$fecha);
    $pdf->SetFont('Arial','',6);
    $pdf->Text($posxft, $px + 20,$footrec);

//Open the print dialog
    $pdf->AutoPrint(false);
    $pdf->Output();

endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

