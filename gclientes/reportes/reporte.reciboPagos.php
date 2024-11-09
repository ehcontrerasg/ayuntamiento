<?
include('../../recaudo/clases/classPagos.php');
include ('../clases/class.impFacturas.php');
include ('../clases/class.reportes_gclientes.php');
require ('../../clases/class.fpdf.php');


$proyecto=$_GET['proyecto'];
$grupo=$_GET['grupo'];
$periodoInicial=$_GET['periodo_inicial'];
$periodoFinal=$_GET['periodo_final'];
$documento=$_GET['rnc'];
$inmueble =$_GET['inmueble'];
$pdf = new FPDF('P', 'mm', 'A7');

$r= new ReportesGerencia();
$datos = $r-> RecGranClientes($proyecto, $inmueble, $periodoInicial, $periodoFinal, $grupo, $documento);
while (oci_fetch($datos)){
    $id_pago = oci_result($datos, 'ID_PAGO');
    $acueducto = oci_result($datos, 'PROYECTO');
    $cod_sistema = oci_result($datos, 'CODIGO_INM');

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

    $c = new Pagos();
        $resultado = $c->seleccionaDatosReciboPago($id_pago);
        while (oci_fetch($resultado)) {
            $cajero = oci_result($resultado, 'ID_USUARIO');
            $entidad = oci_result($resultado, 'DESC_ENTIDAD');
            $punto = oci_result($resultado, 'DESCRIPCION');
            $caja = oci_result($resultado, 'DESC_CAJA');
            $monto = oci_result($resultado, 'INGRESO_BRUTO');
            $deuda = oci_result($resultado, 'DEUDA');
            $vuelta = oci_result($resultado, 'CAMBIO');
            $fecha = oci_result($resultado, 'FECHA_PAGO');
        }
        oci_free_statement($resultado);

        $date = date('d/m/Y');

        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 8);
        $pdf->Text($posxtr, 2, $titulorec);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Text(4, 5, 'Comprobante de Pago # ' . $id_pago);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Text(0, 6, '___________________________________________________________________');
        $pdf->Ln(-5);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(8, 6, utf8_decode('Código: '), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(30, 6, $cod_sistema, 0, 0, 'L');
        $pdf->Ln(5);
        
        $c = new Pagos();
        $resultado = $c->ObtieneClientePago($cod_sistema);
        while (oci_fetch($resultado)) {
            $nom_cli = oci_result($resultado, 'ALIAS');
        }
        oci_free_statement($resultado);
        
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(8, 2, 'Cliente:', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 2, $nom_cli, 0, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Text(0, 13, '________________________________________________________________________');
        $pdf->Ln(1);
        $pdf->Cell(4, 8, 'Fecha:', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(10, 8, $fecha, 0, 0, 'L');
        $pdf->Ln(3);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(4, 8, 'Entidad:', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(10, 8, $entidad, 0, 0, 'L');
        $pdf->Ln(3);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(4, 8, 'Punto:', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(10, 8, $punto, 0, 0, 'L');
        $pdf->Ln(3);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(4, 8, 'Caja:', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(8, 8, $caja, 0, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(8, 8, 'Usuario:', 0, 0, 'R');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(10, 8, $cajero, 0, 0, 'L');
        $pdf->Text(0, 26, '__________________________________________________');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Text(3, 29, 'FACTURAS PAGADAS EN ESTE RECIBO');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Text(1, 32, 'Periodo');
        $pdf->Text(13, 32, 'Total');
        $pdf->Text(23, 32, 'Pagado');
        $pdf->Text(35, 32, 'Comprobante');
        $pdf->SetFont('Arial', '', 6);
        $px = 35;
        
        $c = new Pagos();
        $resultado = $c->obtenerDatosPago($id_pago);
        while (oci_fetch($resultado)) {
            $periodo = oci_result($resultado, 'PERIODO');
            $pendiente = oci_result($resultado, 'PENDIENTE');
            $pagado = oci_result($resultado, 'PAGADO');
            $comprobante = oci_result($resultado, 'COMPROBANTE');
            $pdf->Text(2, $px, $periodo);
            $pdf->Text(14, $px, $pendiente);
            $pdf->Text(24, $px, $pagado);
            $pdf->Text(35, $px, $comprobante);
            $px = $px + 3;
            $importe += $pagado;
        }
        oci_free_statement($resultado);
        
        
        $pdf->SetFont('Arial', '', 7);
        $pdf->Text(2, $px + 1, 'Importe Total');
        $pdf->Text(23, $px - 2, '_______');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Text(23, $px + 1, number_format($pagado, 0, '.', ','));
        $px = $px + 5;
        $pdf->SetFont('Arial', '', 7);
        $pdf->Text(2, $px - 1, 'Restante');
        if ($deuda >= 0) $pdf->Text(25, $px - 1, number_format(round($deuda), 0, '.', ','));
        if ($deuda < 0) {
            $favor = $deuda;
            $deuda = 0;
            $pdf->Text(23, $px - 1, number_format(round($deuda), 0, '.', ','));
            $pdf->Text(2, $px + 2, 'Saldo a Favor ');
            $pdf->Text(23, $px + 2, number_format($favor * -1, 0, '.', ','));
            $px = $px + 3;
        }
        
        $c = new Pagos();
        $resultado = $c->ObtieneMedioPagoRecibo($id_pago);
        while (oci_fetch($resultado)) {
            $medio = oci_result($resultado, 'ID_FORM_PAGO');
            $des_medio = oci_result($resultado, 'DESCRIPCION');
        }
        oci_free_statement($resultado);
        
        if ($medio == 1) $des_medio = 'Efectivo';
        if ($medio == 2) $des_medio = 'Cheque';
        if ($medio == 3) $des_medio = 'Tarjeta de Cr�dito';
        if ($medio == 4) $des_medio = 'Transferencia';
        if ($medio == 13) {
            $des_medio = 'Efectivo y Tarjeta';
            $medio = 5;
        }
        if ($medio == 12) {
            $des_medio = 'Efectivo y Cheque';
            $medio = 6;
        }

        $px = $px + 5;
        $pdf->Text(2, $px - 3, 'Monto ' . number_format($monto, 0, '.', ','));
        $pdf->Text(25, $px - 3, 'Cambio ' . number_format($vuelta, 0, '.', ','));
        $pdf->SetFont('Arial', '', 7);
        $pdf->Text(0, $px - 2, '______________________________________');
        $pdf->Text(2, $px + 2, 'FORMA DE PAGO');
        $pdf->Text(2, $px + 6, 'Tipo');
        $pdf->Text(17, $px + 6, utf8_decode('Descripción'));
        $pdf->SetFont('Arial', '', 7);
        $pdf->Text(2, $px + 9, $medio);
        $pdf->Text(17, $px + 9, $des_medio);
        $pdf->Image($imagenrec, 3, $px + 12, 4);
        $pdf->Rect(0.5, $px + 10, 52, 9);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(19, 30, 181);
        $pdf->Text(24, $px + 13, 'PAGADO');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(64, 65, 68);
        $pdf->Text(17, $px + 16, $fecha);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Text($posxft, $px + 18, $footrec);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Text(13, $px + 22, '*** DUPLICADO ***');
        $pdf->SetTextColor(0, 0, 0);
        $a = new Pagos();
        $pdf->Text(17, $px + 25, $a->obtieneLogin($coduser));
        $pdf->Text(19, $px + 28, $date);   
}oci_free_statement($datos);
$pdf->Output("Recibos_$periodo.pdf",'I');
?>

