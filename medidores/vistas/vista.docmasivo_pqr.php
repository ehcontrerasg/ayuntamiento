<?
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true):
    session_start();
    require_once('../../clases/class.pqr.php');
    require_once("../../clases/class.fpdf.php");
    require_once("../../clases/class.lectura.php");
    $coduser = $_SESSION['codigo'];
    $area_user=$_GET['area_user'];
    $tipo_pqr=$_GET['tipo_pqr'];
    $proyecto=$_GET['proyecto'];
    $secini=$_GET['secini'];
    $secfin=$_GET['secfin'];
    $rutini=$_GET['rutini'];
    $rutfin=$_GET['rutfin'];
    $fecini=$_GET['fecini'];
    $fecfin=$_GET['fecfin'];
    $cod_inmueble=$_GET['cod_inmueble'];

    $posy = 105;
    $pdf = new FPDF();
    $pdf->AliasNbPages();
    $pdf->title = "Reclamos Medidores";
    $ultlec = 0;
    $c = new Pqr();
    $resultado = $c->generaDocMasivoPqr ($area_user,$tipo_pqr,$proyecto,$secini,$secfin,$rutini,$rutfin,$fecini,$fecfin,$cod_inmueble);
    while (oci_fetch($resultado)) {
       // unset ($ultlec);
        $pdf->AddPage();
        $inmueble = oci_result($resultado, 'COD_INMUEBLE') ;
        $cliente = oci_result($resultado,"NOM_CLIENTE");
        $dircliente = oci_result($resultado,"DIR_CLIENTE");
        $telcliente = oci_result($resultado,"TEL_CLIENTE");
        $descripcion = oci_result($resultado,"DESCRIPCION");
        $gerencia = oci_result($resultado,"GERENCIA");
        $medio = oci_result($resultado,"MEDIO_REC_PQR");
        $desmedio = oci_result($resultado,"DESC_MEDIO_REC");
        $tipo = oci_result($resultado,"TIPO_PQR");
        $destipo = oci_result($resultado,"DESC_TIPO_RECLAMO");
        $motivo = oci_result($resultado,"MOTIVO_PQR");
        $desmotivo = oci_result($resultado,"DESC_MOTIVO_REC");
        $fecmax = oci_result($resultado,"FECMAX");
        $entidad = oci_result($resultado,"COD_ENTIDAD");
        $descentidad = oci_result($resultado,"DESC_ENTIDAD");
        $punto = oci_result($resultado,"COD_PUNTO");
        $descpunto = oci_result($resultado,"DESCPUNTO");
        $usuariorec = oci_result($resultado,"USER_RECIBIO_PQR");
        $nomusuariorec = oci_result($resultado,"USUARIOREC");
        $fecreg = oci_result($resultado,"FECREG");
        $proceso = oci_result($resultado,"ID_PROCESO");
        $catastro = oci_result($resultado,"CATASTRO");
        $email = oci_result($resultado,"EMAIL_CLIENTE");
        $cod_pqr = oci_result($resultado,"CODIGO_PQR");
        $serial = oci_result($resultado,"SERIAL");
        $acueducto = oci_result($resultado,"ACUEDUCTO");

        $d = new Lectura();
        $query = $d->getMaxPeriodoLecByInm ($inmueble);
        if (oci_fetch($query)) $ultperiodo = oci_result($query, 'PERIODO') ;

        if($ultperiodo != ''){
            $d = new Lectura();
            $query = $d->getUltLectByInm($inmueble);
            if (oci_fetch($query)) $ultlec = oci_result($query, 'LECTURA_ACTUAL') ;
        }

        $dt = new DateTime();
        $dt->modify("-6 hour");
        $fecmaxhor = $dt->format('d/m/Y h:i:s');

        if($acueducto == 'SD'){
            $pdf->SetFont('Helvetica','B',11);
            $pdf->Text(38,15,utf8_decode("CORPORACIÓN DE ACUEDUCTO Y ALCANTARILLADO DE SANTO DOMINGO (CAASD)"));
            $pdf->Text(92,22,"GERENCIA COMERCIAL");
            $pdf->Text(58,29,utf8_decode("CONSTANCIA DE RECEPCIÓN DE RECLAMOS Y SOLICITUDES"));
            $pdf->Image("../../images/logo_caasd2.jpg",14,9,20);
        }

        if($acueducto == 'BC'){
            $pdf->SetFont('Helvetica','B',11);
            $pdf->Text(38,15,utf8_decode("CORPORACIÓN DE ACUEDUCTO Y ALCANTARILLADO DE BOCACHICA (CORAABO)"));
            $pdf->Text(92,22,"GERENCIA COMERCIAL");
            $pdf->Text(58,29,utf8_decode("CONSTANCIA DE RECEPCIÓN DE RECLAMOS Y SOLICITUDES"));
            $pdf->Image("../../images/logo_coraabo.jpg",10,16,40);
        }

        $pdf->SetFont('Arial','B',9);
        $pdf->Text(10, 42 ,'Reclamo No: '.$cod_pqr);
        $pdf->Ln(24);
        $pdf->Cell(190,5,$fecmaxhor,0,0,'R');
        $pdf->Ln(5);
        $pdf->Cell(190,5,utf8_decode("Pág:      ".$pdf->PageNo().' / {nb}'),0,0,'R');
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFillColor(0,128,192);
        $pdf->Ln(6);
        $pdf->Cell(190,5,"DATOS DE LA PQR",1,0,'C',true);
        $pdf->Rect(10,50,190,35);

        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(7);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(30,5,utf8_decode("Fecha Recepción: "),0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(65,5,$fecreg,0,0,'L');
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(25,5,"Tipo: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(65,5,$tipo.'-'.$destipo,0,0,'L');

        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(30,5,"Cod. Sistema: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(65,5,$inmueble,0,0,'L');
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(25,5,"Motivo: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(65,5,utf8_decode($motivo.'-'.$desmotivo),0,0,'L');

        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(30,5,"Nombre: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(65,5,utf8_decode($cliente),0,0,'L');
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(25,5,"Medio: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(65,5,$medio.'-'.$desmedio,0,0,'L');

        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(30,5,"Catastro: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(65,5,$catastro,0,0,'L');
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(25,5,"Serial: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(65,5,$serial,0,0,'L');

        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(30,5,"Proceso: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(65,5,$proceso,0,0,'L');
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(25,5,"Ultima Lectura: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(65,5,$ultlec,0,0,'L');

        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(30,5,utf8_decode("Descripción: "),0,0,'L');
        $pdf->Ln(4);
        $pdf->SetFont('Arial','',8);
        $descrip1 = substr($descripcion,0,110);
        $descrip2 = substr($descripcion,110,110);
        $descrip3 = substr($descripcion,220,strlen($descripcion));
        $pdf->Cell(30,5,utf8_decode($descrip1),0,0,'L');
        $pdf->Ln(3);
        $pdf->Cell(30,5,utf8_decode($descrip2),0,0,'L');
        $pdf->Ln(3);
        $pdf->Cell(30,5,utf8_decode($descrip3),0,0,'L');

        $pdf->Ln(6);
        $pdf->SetFont('Arial','B',9);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(190,5,"DATOS DE QUIEN INTERPONE LA PQR",1,0,'C',true);
        $pdf->Rect(10,93,190,20);
        $pdf->Ln(7);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(25,5,"Nombre: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(90,5,$cliente,0,0,'L');
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(25,5,"Telefono: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(50,5,$telcliente,0,0,'L');

        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(25,5,utf8_decode("Dirección: "),0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(135,5,$dircliente,0,0,'L');
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(25,5,"Lecturas Anteriores",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $posy = 105;
        $d = new Lectura();
        $query = $d->getUltimasTresLec($inmueble, $ultperiodo);
        while (oci_fetch($query)){
            $perant = oci_result($query, 'PERIODO') ;
            $lecant = oci_result($query, 'LECTURA_ACTUAL');
            //if($posy != 103) {
            $pdf->Text(172,$posy,$perant);
            $pdf->Text(188,$posy,$lecant);
            $posy += 3;
            //}
            //else $posy = 105;
        }
        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(25,5,"E-mail: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(135,5,$email,0,0,'L');



        $pdf->Ln(13);
        $pdf->SetFont('Arial','B',9);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(190,5,"DOCUMENTOS ANEXOS",1,0,'C',true);
        $pdf->Rect(10,121,190,15);

        $pdf->Ln(23);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(190,5,utf8_decode("DATOS DE RECEPCIÓN"),1,0,'C',true);
        $pdf->Rect(10,144,190,14);
        $pdf->Ln(7);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(40,5,"Oficina Comercial: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(150,5,$descentidad.' - '.$descpunto,0,0,'L');
        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(40,5,"Nombre Auxiliar: ",0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(150,5,utf8_decode($nomusuariorec),0,0,'L');

        $pdf->SetFillColor(220,220,220);
        $pdf->Rect(10,158,190,23,true);
        $pdf->Rect(10,158,190,23);
        $pdf->Rect(10,181,190,20);

        $pdf->Ln(8);
        $pdf->SetFont('Arial','B',8);
        if($acueducto == 'SD'){
            $pdf->Cell(190,5,"LA CAASD SE COMPROMETE CON USTED",0,0,'C');
        }
        if($acueducto == 'BC'){
            $pdf->Cell(190,5,"CORAABO SE COMPROMETE CON USTED",0,0,'C');
        }
        $pdf->Ln(4);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(190,5,utf8_decode("Estimado cliente, una vez se recepcione en nuestras oficinas su solicitud, queja o reclamo, nos comprometemos a darle respuesta en un plazo no"),0,0,'L');
        $pdf->Ln(4);
        $pdf->Cell(190,5,utf8_decode("mayor  a quince (15) días a  partir de la  fecha de recepción.  Si transcurre  dicho plazo y  usted no ha recibido  respuesta, favor llamar al  telefono"),0,0,'L');
        $pdf->Ln(4);
        $pdf->Cell(190,5,utf8_decode("(809) 598-1722 Opción 2."),0,0,'L');
        $pdf->Ln(6);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(190,5,utf8_decode("SU SATISFACCIÓN ES NUESTRO COMPROMISO"),0,1,'C');

        $pdf->Ln(3);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(40,5,utf8_decode("Fecha Límite de Respuesta: "),0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(150,5,$fecmax,0,0,'L');

        $pdf->SetFont('Arial','B',8);
        $pdf->Ln(8);
        $pdf->Cell(95,5,"_________________________________________",0,0,'C');
        $pdf->Cell(95,5,"_________________________________________",0,0,'C');
        $pdf->Ln(3);
        $pdf->Cell(95,5,"Firma y Sello Auxiliar",0,0,'C');
        $pdf->Cell(95,5,"Firma y Cliente",0,0,'C');

        $pdf->SetFillColor(0,128,192);
        $pdf->SetTextColor(255,255,255);
        $pdf->Ln(9);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(190,5,"SEGUIMIENTO",1,0,'C',true);
        $pdf->SetFillColor(220,220,220);
        $pdf->Rect(10,209,190,50,true);
        $pdf->Rect(10,209,190,50);

        $pdf->Ln(8);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(190,5,utf8_decode("Fecha de Resolución:   _________________________________________________________ "),0,0,'L');

        $pdf->Ln(13);
        $pdf->Cell(95,5,"_________________________________________",0,0,'C');
        $pdf->Cell(95,5,"_________________________________________",0,0,'C');
        $pdf->Ln(3);
        $pdf->Cell(95,5,"Firma de Inspector",0,0,'C');
        $pdf->Cell(95,5,"Firma Supervisor / Encargado",0,0,'C');

        $pdf->Ln(7);
        $pdf->Cell(190,5,"Observaciones:   _______________________________________________________________________________________________________",0,0,'L');
        $pdf->Ln(4);
        $pdf->Cell(190,5,"______________________________________________________________________________________________________________________",0,0,'L');
        $pdf->Ln(4);
        $pdf->Cell(190,5,"______________________________________________________________________________________________________________________",0,0,'L');
        $pdf->Ln(4);
        $pdf->Cell(190,5,"______________________________________________________________________________________________________________________",0,0,'L');
        $pdf->Ln(4);
        $pdf->Cell(190,5,"______________________________________________________________________________________________________________________",0,0,'L');
    }oci_free_statement($resultado);
    $pdf->Output("Libro.pdf",'I');
endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>

