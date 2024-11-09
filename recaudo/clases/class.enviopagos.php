<?PHP
/*
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);*/

session_start();

include_once ('../clases/classPagos.php');
include_once ('../clases/fpdf.php');
include_once ('../clases/classPqrs.php');
include_once '../../facturacion/mail/class.phpmailer.php';

$coduser = $_SESSION['codigo'];
$inmueble = $_GET['inmueble'];
$id_pago=$_GET['idpago'];

$user = $coduser;

$c = new PQRs();
$resultado = $c->obtenerDatosCliente($inmueble);
while (oci_fetch($resultado)) {
    $cod_inm = oci_result($resultado, 'CODIGO_INM');
    $dir_inm = oci_result($resultado, 'DIRECCION');
    $urb_inm = oci_result($resultado, 'DESC_URBANIZACION');
    $cod_cli = oci_result($resultado, 'CODIGO_CLI');
    $nom_cli = oci_result($resultado, 'ALIAS');
    $cod_pro = oci_result($resultado, 'ID_PROYECTO');
    $des_pro = oci_result($resultado, 'DESC_PROYECTO');
    $est_inm = oci_result($resultado, 'ID_ESTADO');
    $uso_inm = oci_result($resultado, 'ID_USO');
    $act_inm = oci_result($resultado, 'DESC_ACTIVIDAD');
    $cod_zon = oci_result($resultado, 'ID_ZONA');
    $uni_inm = oci_result($resultado, 'UNIDADES_HAB');
    $cmo_min = oci_result($resultado, 'CONSUMO_MINIMO');
    $ced_cli = oci_result($resultado, 'DOCUMENTO');
    $tel_cli = oci_result($resultado, 'TELEFONO');
    $mail_cli = oci_result($resultado, 'EMAIL');
    $ger_inm = oci_result($resultado, 'ID_GERENCIA');
    $cat_inm = oci_result($resultado, 'CATASTRO');
    $pro_inm = oci_result($resultado, 'ID_PROCESO');
    $ali_cli = oci_result($resultado, 'NOMBRE_CLI');
}
oci_free_statement($resultado);
$direccion = $dir_inm . ' ' . $urb_inm;

$dt = new DateTime();
$dt->modify("-6 hour");
$fecha = $dt->format('d/m/Y H:i:s');

$medio = 'TF';
$tipo = 2;
$motivo = 12;
$area_res = 9;

$descripcion = 'Sr/Sra. ' . $nom_cli . ' solicita envio de duplicado de comprobante de pago al correo ' . $mail_cli;
$cod_ent = 900;
$id_punto = 600;
$num_caja = 1;

$tel_cli_nuevo = $tel_cli ; 
$mail_cli_nuevo = $mail_cli;

$fecha_res = date('d/m/Y', strtotime("+1 day"));

$c = new PQRs();
$bandera = $c->IngresaPqr($fecha,$cod_inm,$nom_cli,$ced_cli,$direccion,$tel_cli,$mail_cli,$medio,$tipo,$motivo,$fecha_res,$descripcion,$cod_ent,$id_punto,$num_caja,$coduser,$ger_inm,$area_res,$tel_cli_nuevo,$mail_cli_nuevo);
if($bandera == false){
    $error=$c->getmsgresult();
    $coderror=$c->getcodresult();
    echo"
    <script type='text/javascript'>
        showDialog('Error Registrando PQR','C&oacute;digo de error: $coderror <br>Mensaje: $error','error');
    </script>";
}
else if ($bandera == true) {
    $error = $c->getmsgresult();
    echo "
    <script type='text/javascript'>
        showDialog('PQR Registrada','Se Registro la PQR para el Inmueble $cod_inm, con el codigo pqr $error','success');
    </script>
    ";
    $cod_pqr = $error;
}

if ($cod_pro == 'SD') {
    $mensaje = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

 <head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Envio De Comprobante Por Correo</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

</head>

<body style="margin: 0; padding: 0; font-family: verdana;">

  <table cellpadding="0" cellspacing="0" width="100%">
    <tr>
            <td>
                <table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
                    <tr>
                        <td bgcolor="#2980b9" align="center" style="padding: 20px 0 20px 0;">
                            <img src="../../images/caasd-logo-transparent-small-origin.png" alt="Logo Caasd" width="150"/>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 20px 30px 20px 30px">
                           <table cellspacing="0" cellpadding="0" style="border-collapse: collapse; margin: 24px 0 24px 0;">
                               <tr>
                                   <td style="font-weight: bold; color: #00BCD4"></td>
                               </tr>
                               <tr>
                                   <td colspan="3" style="padding: 10px 10px 10px 10px" >
                                      Estimado/a '.$nom_cli.' <br /><br />
                                      Este es un duplicado del comprobante solicitado en la oficina. <br />
                                   </td>
                               </tr>
                           </table>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#2980b9" align="center">
                            <p style="color: #ffffff; font-size: 12px;">Corporacion de Acueductos y Alcantarillado de Santo Domingo(CAASD)</p>
                            <p style="color: #ffffff; font-size: 10px;">Autopista las Americas, Esq. Calle Masoneria. Ensanche Ozama, Santo Domingo. Repúbica Dominicana.</p>

                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; padding-top: 1em;" align="center">
                            Si no ha solicitado un duplicado de este comprobante por favor ignorar este correo.
                        </td>
                    </tr>
                </table>
            </td>
    </tr>
  </table>
</body>
</html>';
} else {
    $mensaje = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

 <head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Envio De Comprobante Por Correo</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

</head>

<body style="margin: 0; padding: 0; font-family: verdana;">

  <table cellpadding="0" cellspacing="0" width="100%">
    <tr>
            <td>
                <table align="center" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
                    <tr>
                        <td bgcolor="#2980b9" align="center" style="padding: 20px 0 20px 0;">
                            <img src="../../images/LOGO-CORAABOIV.png" alt="Logo Coraabo" width="150"/>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 20px 30px 20px 30px">
                           <table cellspacing="0" cellpadding="0" style="border-collapse: collapse; margin: 24px 0 24px 0;">
                               <tr>
                                   <td style="font-weight: bold; color: #00BCD4"></td>
                               </tr>
                               <tr>
                                   <td colspan="3" style="padding: 10px 10px 10px 10px" >
                                      Estimado/a '.$nom_cli.' <br /><br />
                                      Este es un duplicado del comprobante solicitado en la oficina. <br />
                                   </td>
                               </tr>
                           </table>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#2980b9" align="center">
                            <p style="color: #ffffff; font-size: 12px;">Corporación del Acueducto y Alcantarillado de Boca Chica (CORAABO)</p>
                            <p style="color: #ffffff; font-size: 10px;">Autopista las Americas, Esq. Calle Masoneria. Ensanche Ozama, Santo Domingo. Repúbica Dominicana.</p>

                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; padding-top: 1em;" align="center">
                            Si no ha solicitado un duplicado de este comprobante por favor ignorar este correo.
                        </td>
                    </tr>
                </table>
            </td>
    </tr>
  </table>
</body>
</html>
';
}

if (is_numeric($id_pago) == true) {

    $id_pago=addslashes($id_pago);
    $coduser = "";

    if ($id_pago > 2000000) {
        $c = new Pagos();
        $resultado = $c->seleccionaDatosReciboPago($id_pago);
        while (oci_fetch($resultado)) {
            $cod_sistema = oci_result($resultado, 'INM_CODIGO');
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

        $c = new Pagos();
        $resultado = $c->obtieneProyecto($cod_sistema);
        while (oci_fetch($resultado)) {
            $acueducto = oci_result($resultado, 'ID_PROYECTO');
        }
        oci_free_statement($resultado);
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

        $date = date('d/m/Y');
        $largo = 240;
        $c = new Pagos();
        $resultado = $c->obtenerCantidadDatosPago($id_pago);
        while (oci_fetch($resultado)) {
            $cantRegistros = oci_result($resultado, 'CANTIDAD');
        }
        oci_free_statement($resultado);
        $aumento = (6 * $cantRegistros);
        if ($cantRegistros >= 40) $largo = 500;
        $totalHoja = $largo + $aumento;
        $pdf = new FPDF('P', 'mm', 'A7', $totalHoja);
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
        $pdf->Text(23, $px + 1, number_format($importe, 0, '.', ','));
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
            $medio .= oci_result($resultado, 'ID_FORM_PAGO');
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
        $pdf->Output("$id_pago.pdf", 'F');
    }
    if ($id_pago < 2000000) {
        $c = new Pagos();
        $resultado = $c->seleccionaDatosReciboPagoRec($id_pago);
        while (oci_fetch($resultado)) {
            $cod_sistema = oci_result($resultado, 'INMUEBLE');
            $cajero = oci_result($resultado, 'ID_USUARIO');
            $entidad = oci_result($resultado, 'DESC_ENTIDAD');
            $punto = oci_result($resultado, 'DESCRIPCION');
            $caja = oci_result($resultado, 'DESC_CAJA');
            $monto = oci_result($resultado, 'IMPORTE');
            $fecha = oci_result($resultado, 'FECHA_PAGO');
        }
        oci_free_statement($resultado);

        $c = new Pagos();
        $resultado = $c->obtieneProyecto($cod_sistema);
        while (oci_fetch($resultado)) {
            $acueducto = oci_result($resultado, 'ID_PROYECTO');
        }
        oci_free_statement($resultado);
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
        $date = date('d/m/Y');
        $largo = 240;
        $aumento = 6;
        $totalHoja = $largo + $aumento;
        $pdf = new FPDF('P', 'mm', 'A7', $totalHoja);
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 8);
        $pdf->Text($posxtr, 2, $titulorec);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Text(4, 5, 'Comprobante Otros Rec # ' . $id_pago);
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
        $pdf->Text(5, 29, 'CONCEPTO PAGO EN ESTE RECIBO');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Text(1, 32, 'Concepto');
        $pdf->Text(26, 32, 'Valor');
        $pdf->Text(40, 32, 'Pagado');
        $pdf->SetFont('Arial', '', 6);
        $px = 35;
        $c = new Pagos();
        $resultado = $c->obtenerDatosPagoRec($id_pago);
        while (oci_fetch($resultado)) {
            $concepto = oci_result($resultado, 'DESC_SERVICIO');
            $importe = oci_result($resultado, 'IMPORTE');
            $pdf->Text(1, $px, $concepto);
            $pdf->Text(27, $px, $importe);
            $pdf->Text(42, $px, $importe);
            $px = $px + 3;
            $importe += $pagado;
        }
        oci_free_statement($resultado);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Text(2, $px + 1, 'Importe Total');
        $pdf->Text(40, $px - 2, '_______');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Text(42, $px + 1, number_format($importe, 0, '.', ','));
        $px = $px + 3;
        $pdf->SetFont('Arial', '', 7);

        $c = new Pagos();
        $resultado = $c->ObtieneMedioPagoRecibo($id_pago);
        while (oci_fetch($resultado)) {
            $medio .= oci_result($resultado, 'ID_FORM_PAGO');
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
        $pdf->Output("$id_pago.pdf", 'F');
    }
}

echo $mensaje;

$c = new PQRs();
$resultado = $c->obtieneDatosPqr ($cod_pqr);
while (oci_fetch($resultado)) {
    $area_act = oci_result($resultado, 'AREA_ACTUAL');
    $orden = oci_result($resultado, 'ORDEN');
}oci_free_statement($resultado);

$resolucion = 'El Comprobante fue enviado';
$tipo_res = 12;
$diagnostico = 1;

$c = new PQRs();
$bandera = $c->CierraPqr($cod_inm,$cod_pqr,$resolucion,$area_act,$orden,$tipo_res,$diagnostico,$user);
if($bandera == false){
    $error=$c->getmsgresult();
    $coderror=$c->getcodresult();
    echo"
    <script type='text/javascript'>
        showDialog('Error Registrando Cierre de PQR','C&oacute;digo de error: $coderror <br>Mensaje: $error','error');
    </script>"; 
}
else if($bandera == true){
    echo "
    <script type='text/javascript'>
        showDialog('Cierre de PQR Registrado,'Se Registro el cierre de la PQR N&deg $cod_pqr,'success');
    </script>
    ";
}


$asunto = 'Envio duplicado de comprobante de pago';
$host = "smtpout.secureserver.net";
$port = 465;
$smtpSecure = "ssl";
$username = "factura@caasdenlinea.com";
$password = "Aceasoft2015";

if($cod_pro == 'SD'){
    $company = 'factura@caasd.com';
}
if($cod_pro == 'BC'){
    $company = 'factura@coraaboenlinea.com';
}

$mail = new PHPMailer();
$mail->isSMTP();
$mail->SMTPDebug=0;
$mail->SMTPAuth=true;
$mail->SMTPSecure=$smtpSecure;
$mail->Host=$host;
$mail->Port=$port;
$mail->Username=$username;
$mail->Password=$password;

$mail->setFrom('factura@caasdenlinea.com',$company);
$mail->Subject= $asunto;
$mail->msgHTML($mensaje);
$mail->addAddress(strtolower($mail_cli),"Estimado Usuario");
$archivo = $id_pago.'.pdf';
$mail->AddAttachment($archivo);
if(!$mail->send()){
    echo "Error al enviar: ".$mail->ErrorInfo;
    return false;
}
else{                
    echo "Mensaje enviado";
    return true;
}