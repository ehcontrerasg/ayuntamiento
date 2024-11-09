<?
session_start();
require_once '../clases/classPqrs.php';
require_once "../clases/fpdf.php";
//require_once('../../destruye_sesion_cierra.php');
$coduser = $_SESSION['codigo'];
$cod_pqr = $_GET['codigo_pqr'];

$pdf = new FPDF();
$pdf->AliasNbPages();
$pdf->title = "Formato PQRs";
$pdf->AddPage();

$c         = new PQRs();
$resultado = $c->generaDocPqr($cod_pqr);
while (oci_fetch($resultado)) {
    $inmueble      = oci_result($resultado, 'COD_INMUEBLE');
    $cliente       = oci_result($resultado, "NOM_CLIENTE");
    $dircliente    = oci_result($resultado, "DIR_CLIENTE");
    $telcliente    = oci_result($resultado, "TEL_CLIENTE");
    $descripcion   = oci_result($resultado, "DESCRIPCION");
    $gerencia      = oci_result($resultado, "GERENCIA");
    $medio         = oci_result($resultado, "MEDIO_REC_PQR");
    $desmedio      = oci_result($resultado, "DESC_MEDIO_REC");
    $tipo          = oci_result($resultado, "TIPO_PQR");
    $destipo       = oci_result($resultado, "DESC_TIPO_RECLAMO");
    $motivo        = oci_result($resultado, "MOTIVO_PQR");
    $desmotivo     = oci_result($resultado, "DESC_MOTIVO_REC");
    $fecmax        = oci_result($resultado, "FECMAX");
    $entidad       = oci_result($resultado, "COD_ENTIDAD");
    $descentidad   = oci_result($resultado, "DESC_ENTIDAD");
    $punto         = oci_result($resultado, "COD_PUNTO");
    $descpunto     = oci_result($resultado, "DESCPUNTO");
    $usuariorec    = oci_result($resultado, "USER_RECIBIO_PQR");
    $nomusuariorec = oci_result($resultado, "USUARIOREC");
    $fecreg        = oci_result($resultado, "FECREG");
    $proceso       = oci_result($resultado, "ID_PROCESO");
    $catastro      = oci_result($resultado, "CATASTRO");
    $email         = oci_result($resultado, "EMAIL_CLIENTE");
    $acueducto     = oci_result($resultado, "ACUEDUCTO");
    $lectura    = oci_result($resultado, "LECTURA");
    $serial   = oci_result($resultado, "SERIAL");

    $dt = new DateTime();
    $dt->modify("-6 hour");
    $fecmaxhor = $dt->format('d/m/Y h:i:s');

    if ($acueducto == 'SD') {
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Text(78, 15, utf8_decode("AYUNTAMIENTO DE SANTO DOMINGO"));
        $pdf->Text(92, 22, "GERENCIA COMERCIAL");
        $pdf->Text(58, 29, utf8_decode("CONSTANCIA DE RECEPCIÓN DE RECLAMOS Y SOLICITUDES"));
        $pdf->Image("../../images/LogoAseo.jpeg", 14, 9, 26);
    }

    if ($acueducto == 'BC') {
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Text(82, 15, utf8_decode("AYUNTAMIENTO DE BOCACHICA"));
        $pdf->Text(92, 22, "GERENCIA COMERCIAL");
        $pdf->Text(58, 29, utf8_decode("CONSTANCIA DE RECEPCIÓN DE RECLAMOS Y SOLICITUDES"));
        $pdf->Image("../../images/AyuntamientoBocachica.jpeg", 14, 3, 35);
    }

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Text(10, 42, 'Reclamo No: ' . $cod_pqr);
    $pdf->Ln(24);
    $pdf->Cell(190, 5, $fecmaxhor, 0, 0, 'R');
    $pdf->Ln(5);
    $pdf->Cell(190, 5, utf8_decode("Pág:      " . $pdf->PageNo() . ' / {nb}'), 0, 0, 'R');
    $pdf->SetTextColor(255, 255, 255);
    if ($acueducto == 'SD'){
        $pdf->SetFillColor(32,128,2);
    }
    if ($acueducto == 'BC') {
        $pdf->SetFillColor(18, 30, 135);
    }
    $pdf->Ln(6);
    $pdf->Cell(190, 5, "DATOS DE LA PQR", 1, 0, 'C', true);
    $pdf->Rect(10, 50, 190, 35);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(7);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, utf8_decode("Fecha Recepción: "), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $fecreg, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "Tipo: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $tipo . '-' . $destipo, 0, 0, 'L');

    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, "Cod. Sistema: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $inmueble, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "Motivo: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, utf8_decode($motivo . '-' . $desmotivo), 0, 0, 'L');

    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, "Nombre: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $cliente, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "Medio: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $medio . '-' . $desmedio, 0, 0, 'L');

    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, "Catastro: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $catastro, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "Serial: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $serial, 0, 0, 'L');

    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, "Proceso: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $proceso, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "Ultima Lectura: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, $lectura, 0, 0, 'L');

    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 5, utf8_decode("Descripción: "), 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->SetFont('Arial', '', 8);
    $descrip1 = substr($descripcion, 0, 110);
    $descrip2 = substr($descripcion, 110, strlen($descripcion));
    $pdf->Cell(30, 5, utf8_decode($descrip1), 0, 0, 'L');
    $pdf->Ln(3);
    $pdf->Cell(30, 5, utf8_decode($descrip2), 0, 0, 'L');

    $pdf->Ln(9);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(190, 5, "DATOS DE QUIEN INTERPONE LA PQR", 1, 0, 'C', true);
    $pdf->Rect(10, 93, 190, 20);
    $pdf->Ln(7);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "Nombre: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(90, 5, $cliente, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "Telefono: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(50, 5, $telcliente, 0, 0, 'L');

    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, utf8_decode("Dirección: "), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(165, 5, $dircliente, 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(25, 5, "E-mail: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(165, 5, $email, 0, 0, 'L');

    $pdf->Ln(13);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Cell(190, 5, "DOCUMENTOS ANEXOS", 1, 0, 'C', true);
    $pdf->Rect(10, 121, 190, 15);

    $pdf->Ln(23);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(190, 5, utf8_decode("DATOS DE RECEPCIÓN"), 1, 0, 'C', true);
    $pdf->Rect(10, 144, 190, 14);
    $pdf->Ln(7);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(40, 5, "Oficina Comercial: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(150, 5, $descentidad . ' - ' . $descpunto, 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(40, 5, "Nombre Auxiliar: ", 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(150, 5, utf8_decode($nomusuariorec), 0, 0, 'L');

    $pdf->SetFillColor(220, 220, 220);
    $pdf->Rect(10, 158, 190, 23, true);
    $pdf->Rect(10, 158, 190, 23);
    $pdf->Rect(10, 181, 190, 25);

    $pdf->Ln(8);
    $pdf->SetFont('Arial', 'B', 8);
    if ($acueducto == 'SD') {
        $pdf->Cell(190, 5, "EL AYUNTAMIENTO DE SANTO DOMINGO SE COMPROMETE CON USTED", 0, 0, 'C');
    }
    if ($acueducto == 'BC') {
        $pdf->Cell(190, 5, "EL AYUNTAMIENTO DE BOCACHICA SE COMPROMETE CON USTED", 0, 0, 'C');
    }
    $pdf->Ln(4);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(190, 5, "Estimado cliente, una vez se recepcione en nuestras oficinas su solicitud, queja o reclamo, nos comprometemos a darle respuesta en un plazo no", 0, 0, 'L');
    $pdf->Ln(4);
    if ($motivo == 23 || $motivo == 25 || $motivo == 11 || $motivo == 83 || $motivo == 27) {

        $pdf->Cell(190, 5, utf8_decode("mayor  a tres (3) días laborables a  partir de la  fecha de recepción.  Si transcurre  dicho plazo y  usted no ha recibido  respuesta, favor llamar "), 0, 0, 'L');
    } else if ($motivo == 22) {

        $pdf->Cell(190, 5, utf8_decode("mayor  a dos (2) días laborables a  partir de la  fecha de recepción.  Si transcurre  dicho plazo y  usted no ha recibido  respuesta, favor llamar "), 0, 0, 'L');
    } else {

        $pdf->Cell(190, 5, utf8_decode("mayor  a quince (15) días a  partir de la  fecha de recepción.  Si transcurre  dicho plazo y  usted no ha recibido  respuesta, favor llamar "), 0, 0, 'L');
    }
    $pdf->Ln(4);
    $pdf->Cell(190, 5, utf8_decode("al  telefono (809) 598-1722 Opción 2."), 0, 0, 'L');
    $pdf->Ln(6);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(190, 5, utf8_decode("SU SATISFACCIÓN ES NUESTRO COMPROMISO"), 0, 1, 'C');

    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(40, 5, utf8_decode("Fecha Límite de Respuesta: "), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(150, 5, $fecmax, 0, 0, 'L');

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Ln(8);
    $pdf->Cell(95, 5, "_________________________________________", 0, 0, 'C');
    $pdf->Cell(95, 5, "_________________________________________", 0, 0, 'C');
    $pdf->Ln(3);
    $pdf->Cell(95, 5, "Firma y Sello Auxiliar", 0, 0, 'C');
    $pdf->Cell(95, 5, "Firma Cliente y/o Representante", 0, 0, 'C');
    $pdf->Ln(4);
    $pdf->Cell(95, 5, "", 0, 0, 'C');
    $pdf->Cell(95, 5, $dt->format('d/m/Y'), 0, 0, 'C');

    if ($acueducto == 'SD'){
        $pdf->SetFillColor(32,128,2);
    }
    if ($acueducto == 'BC') {
        $pdf->SetFillColor(18, 30, 135);
    }
    $pdf->SetTextColor(255, 255, 255);
    $pdf->Ln(14);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(190, 5, "SEGUIMIENTO", 1, 0, 'C', true);
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Rect(10, 218, 190, 50, true);
    $pdf->Rect(10, 218, 190, 50);

    $pdf->Ln(8);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(190, 5, utf8_decode("Fecha de Resolución:   _________________________________________________________ "), 0, 0, 'L');

    $pdf->Ln(13);
    $pdf->Cell(95, 5, "_________________________________________", 0, 0, 'C');
    $pdf->Cell(95, 5, "_________________________________________", 0, 0, 'C');
    $pdf->Ln(3);
    $pdf->Cell(95, 5, "Firma de Inspector", 0, 0, 'C');
    $pdf->Cell(95, 5, "Firma Supervisor / Encargado", 0, 0, 'C');

    $pdf->Ln(7);
    $pdf->Cell(190, 5, "Observaciones:   _______________________________________________________________________________________________________", 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->Cell(190, 5, "______________________________________________________________________________________________________________________", 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->Cell(190, 5, "______________________________________________________________________________________________________________________", 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->Cell(190, 5, "______________________________________________________________________________________________________________________", 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->Cell(190, 5, "______________________________________________________________________________________________________________________", 0, 0, 'L');

}
oci_free_statement($resultado);
$pdf->Output("Libro.pdf", 'I');
