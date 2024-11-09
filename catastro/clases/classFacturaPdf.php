<?
include_once ('../../include.php');
include ('../clases/fpdf.php');
require_once ('../../funciones/barcode/barcode.inc.php');
$factura=$_GET['factura'];

$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;
new barCodeGenrator($factura,1,$factura.'.gif',100,70,0);
$pdf=new FPDF();
$pdf->AddPage();

if($factura != ''){
	$sql="SELECT I.CODIGO_INM,  CONCAT(N.ID_NCF,F.NCF_CONSEC)NCF, I.CATASTRO, C.ALIAS, I.DIRECCION, U.DESC_URBANIZACION, I.ID_ZONA, 
    TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY')FECEXP, F.PERIODO, I.ID_PROCESO, (SELECT G.DESC_GERENCIA FROM SGC_TT_GERENCIA_ZONA Z, SGC_TP_GERENCIAS G 
    WHERE G.ID_GERENCIA = Z.ID_GERENCIA AND Z.ID_ZONA = I.ID_ZONA) GERENCIA, E.DESC_MED, M.COD_CALIBRE, M.SERIAL, TO_CHAR(F.FEC_VCTO,'DD/MM/YYYY')FEC_VCTO
    FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I, SGC_TP_NCF_USOS N, SGC_TT_CONTRATOS C, SGC_TP_URBANIZACIONES U, SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_MEDIDORES E
    WHERE I.CODIGO_INM(+) = F.INMUEBLE
    AND C.CODIGO_INM(+) = F.INMUEBLE
	AND M.COD_INMUEBLE(+) = I.CODIGO_INM
    AND F.NCF_ID = N.ID_NCF_USO
    AND U.CONSEC_URB = I.CONSEC_URB
	AND M.COD_MEDIDOR = E.CODIGO_MED(+)
	AND M.FECHA_BAJA IS NULL
    AND C.FECHA_FIN IS NULL AND F.CONSEC_FACTURA = '$factura'";
	$stid = oci_parse($link, $sql);
	oci_execute($stid, OCI_DEFAULT);
	while (oci_fetch($stid)) {
		$codinm=oci_result($stid, 'CODIGO_INM');
		$ncffac = oci_result($stid, 'NCF');
		$catastro=oci_result($stid, 'CATASTRO');
		$alias=oci_result($stid, 'ALIAS');
		$direccion=oci_result($stid, 'DIRECCION');
		$urbaniza=oci_result($stid, 'DESC_URBANIZACION');
		$zona=oci_result($stid, 'ID_ZONA');
		$fecexp=oci_result($stid, 'FECEXP');
		$periodo=oci_result($stid, 'PERIODO');
		$proceso=oci_result($stid, 'ID_PROCESO');
		$gerencia=oci_result($stid, 'GERENCIA');
		$marmed=oci_result($stid, 'DESC_MED');
		$calmed=oci_result($stid, 'COD_CALIBRE');
		$serial=oci_result($stid, 'SERIAL');
		$fecvcto=oci_result($stid, 'FEC_VCTO');
	}	oci_free_statement($stid);	
	
	if ($marmed == "") $marmed = "Sin Medidor";
	
	$sql="SELECT C.DESC_SERVICIO, U.DESC_USO, T.CODIGO_TARIFA, S.UNIDADES_TOT
	FROM SGC_TT_SERVICIOS_INMUEBLES S, SGC_TP_SERVICIOS C, SGC_TP_TARIFAS T, SGC_TP_USOS U
	WHERE C.COD_SERVICIO  = S.COD_SERVICIO 
	AND S.CONSEC_TARIFA = T.CONSEC_TARIFA
	AND T.COD_USO = U.ID_USO
	AND S.COD_INMUEBLE = '$codinm'
	AND S.ACTIVO = 'S'";
	$posy = 63;
	$stid = oci_parse($link, $sql);
	oci_execute($stid, OCI_DEFAULT);
	while (oci_fetch($stid)) {
		$servicio=oci_result($stid, 'DESC_SERVICIO');
		$uso=oci_result($stid, 'DESC_USO');
		$tarifa=oci_result($stid, 'CODIGO_TARIFA');	
		$unidades=oci_result($stid, 'UNIDADES_TOT');	 
		$pdf->SetFont('Arial','B',8);
		$pdf->Text(12,$posy,$servicio);
		$pdf->Text(37,$posy,$uso);
		$pdf->Text(66,$posy,$tarifa);
		$pdf->Text(91,$posy,$unidades);
		$posy = $posy+4;
	}oci_free_statement($stid);
	
	$sql = "SELECT LECTURA_ACTUAL, TO_CHAR(FECHA_LECTURA_ORI,'DD/MM/YYYY')FECLEC  FROM SGC_TT_REGISTRO_LECTURAS R
	WHERE COD_INMUEBLE = '$codinm'
	AND PERIODO >= TO_CHAR(ADD_MONTHS(TO_DATE($periodo,'YYYYMM'),-1),'YYYYMM') 
	AND PERIODO <= TO_CHAR(ADD_MONTHS(TO_DATE($periodo,'YYYYMM'),+0),'YYYYMM') ";
	$posy = 92;
	$stid = oci_parse($link, $sql);
	oci_execute($stid, OCI_DEFAULT);
	while (oci_fetch($stid)) {
		$lecturas=oci_result($stid, 'LECTURA_ACTUAL');
		$fechas_lec=oci_result($stid, 'FECLEC');
		$pdf->SetFont('Arial','',9);
		$pdf->Text(68,$posy,$fechas_lec);
		$pdf->Text(90,$posy,$lecturas);
		$posy = $posy+5;
		$consumo = $consumo - $lecturas;
		$consumo = $consumo * (-1);
	}
	$agno = substr($periodo,0,4);
	$mes = substr($periodo,4,2);
	if($mes == '01'){$mes = Ene;} if($mes == '02'){$mes = Feb;} if($mes == '03'){$mes = Mar;} if($mes == '04'){$mes = Abr;}
	if($mes == '05'){$mes = May;} if($mes == '06'){$mes = Jun;} if($mes == '07'){$mes = Jul;} if($mes == '08'){$mes = Ago;}
	if($mes == '09'){$mes = Sep;} if($mes == '10'){$mes = Oct;} if($mes == '11'){$mes = Nov;} if($mes == '12'){$mes = Dic;}
	
	//CABECERA FACTURA
	$pdf->Image('../../images/logo_caasd.jpg',40,7,25);
	$pdf->SetFont('times','B',31);
	$pdf->Text(87,22,'CA');
	$pdf->SetFont('times','B',37);
	$pdf->Text(103,22,'A');
	$pdf->SetFont('times','B',31);
	$pdf->Text(112,22,'SD');
	$pdf->SetFont('times','B',6);
	$pdf->Text(67,28,'CORPORACION DEL ACUEDUCTO Y ALCANTARILLADO DE SANTO DOMINGO');
	$pdf->SetFont('Arial','B',10);
	$pdf->Text(10,15,'Código Sistema');
	$pdf->SetFont('Arial','B',16);
	$pdf->Text(10,25,$codinm);
	$pdf->SetFont('Arial','B',10);
	$pdf->Text(150,15,'NCF '.$ncffac);
	$pdf->Text(150,20,'Código de Inmueble');
	$pdf->Text(150,25,$catastro);
	$pdf->Ln(25);
	$pdf->Rect(10,35,190,15);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(90,15,$alias,0,0,'L');
	$pdf->Cell(50,15,$direccion,0,0,'C');
	$pdf->Cell(50,15,$urbaniza,0,0,'C');
	$pdf->Ln(18);
	
	// CUERPO DE LA FACTURA
	// SECCION SERVICIOS Y DATOS DE FACTURA
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(0, 128, 192);
	$pdf->Cell(93,6,'SERVICIO                     USO                    TARIFA              UNIDADES',1,0,'L',true);
	$pdf->Cell(4,7,'',0,0,'C',false);
	$pdf->Cell(93,6,'DATOS DE LA FACTURA',1,0,'C',true);
	$pdf->SetTextColor(0,0,0);
	$pdf->Ln(7);
	$pdf->Cell(93,7,'',0,0,'C');
	$pdf->Cell(4,7,'',0,0,'C');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(28,5,'Factura Número:',0,0,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(27,5,$factura,0,0,'C');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(19,5,'Ciclo:',0,0,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(19,5,$zona,0,0,'L');
	$pdf->Ln(7);
	$pdf->Cell(93,7,'',0,0,'C');
	$pdf->Cell(4,7,'',0,0,'C');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(28,5,'Fecha de Emisión:',0,0,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(27,5,$fecexp,0,0,'C');
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(19,5,'Periodo:',0,0,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(19,5,$mes.'/'.$agno,0,0,'L');
	$pdf->Ln(8);
	$pdf->SetFont('Arial','B',9);
	
	//SECCION DATOS MEDIDOR, DATOS MEDICION E INFORMACION ADICIONAL
	$pdf->Cell(40,7,'DATOS DEL MEDIDOR',1,0,'C');
	$pdf->Cell(53,7,'DATOS DE MEDICION',1,0,'C');
	$pdf->Cell(4,7,'',0,0,'C');
	$pdf->Cell(92,7,'INFORMACION ADICIONAL A LA FACTURA',1,0,'C');
	$pdf->Ln(7);
	$pdf->Cell(40,25,'',1,0,'C');
	$pdf->Cell(53,25,'',1,0,'C');
	$pdf->Cell(4,25,'',0,0,'C');
	$pdf->Cell(92,25,'',1,0,'C');
	$pdf->SetFont('Arial','B',9);
	$pdf->Text(12,87,'Marca:');
	$pdf->Text(12,94,'Calibre:');
	$pdf->Text(12,101,'Serial:');
	$pdf->SetFont('Arial','',9);
	$pdf->Text(25,87,$marmed);
	$pdf->Text(25,94,$calmed);
	$pdf->Text(25,101,$serial);
	
	$pdf->SetFont('Arial','B',9);
	$pdf->Text(71,86,'Fecha');
	$pdf->Text(88,86,'Lectura');
	$pdf->Line(50,87,103,87);	
	$pdf->Text(52,92,'Anterior:');
	$pdf->Text(52,97,'Actual:');
	if ($marmed == "Sin Medidor") $pdf->Text(53,104,'Promedio');
	else $pdf->Text(53,104,'Diferencia Lectura');
	$pdf->Text(91,104,$consumo);
	$pdf->SetFont('Arial','',10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Text(112,88,'Pendiente Saldos a Favor:');
	$pdf->Text(112,98,'Pendiente Diferidos:');
	$pdf->Ln(28);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(0, 128, 192);
	
	//SECCION DETALLE SERVICIOS DOMICILIARIOS Y OTROS CONCEPTOS
	$pdf->Cell(92.6,6,'DETALLE SERVICIOS DOMICILIARIOS',0,0,'C',true);
	$pdf->Cell(4.8,6,'',0,0,'C');
	$pdf->Cell(91.4,6,'OTROS CONCEPTOS',0,0,'C',true);
	
	$pdf->Ln(6);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',9);
	
	$pdf->Cell(24,6,'Servicios',0,0,'C');
	$pdf->Cell(23,6,'Cantidad',0,0,'C');
	$pdf->Cell(23,6,'Precio',0,0,'C');
	$pdf->Cell(23,6,'Importe',0,0,'C');
	$pdf->Line(10,122,103,122);	
	$pdf->Cell(4.8,6,'',0,0,'C');
	$pdf->Cell(60,6,'Concepto',0,0,'L');
	$pdf->Cell(32,6,'Importe',0,0,'L');
	$pdf->Line(107,122,199,122);	
	$pdf->Ln(42.8);
	$pdf->SetTextColor(255,255,255);
	
	$pdf->Cell(92.6,6,'            TOTAL SERVICIOS',0,0,'L',true);
	$pdf->Image('../../images/A.jpg',11,159,8);
	$pdf->Cell(4.8,6,'',0,0,'C');
	$pdf->Cell(91.4,6,'            TOTAL OTROS CONCEPTOS',0,0,'L',true);
	$pdf->Image('../../images/B.jpg',108,159,8);
	$pdf->Rect(9.8,110,93,55);
	$pdf->Rect(107,110,92,55);
	
	$pdf->Rect(9.8,168,93,30);
	$pdf->Rect(107,168,92,30);
	$pdf->Rect(9.8,204,189.2,40);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',9);
	$pdf->Text(50,177,'Notas');
	$pdf->SetFont('Arial','B',11);
	$pdf->Text(48,182,'AVISO');
	$pdf->SetFont('Arial','B',9);
	$pdf->Text(28,187,'Fecha de corte de servicio '.$feccorte);
	
	$pdf->SetFont('Arial','B',11);
	$pdf->Text(108,178,'TOTAL FACTURA MENSUAL');
	$pdf->Text(128,183,'( A + B )');
	$pdf->Text(108,194,'Vencimiento:');
	$pdf->Text(175,194,$fecvcto);
	
	$pdf->Ln(9.3);
	$pdf->Cell(97.3,18.7,'',0,0,'C');
	$pdf->Cell(56,18.7,'',0,0,'C');
	$pdf->Cell(35.4,18.7,'',1,0,'C',true);
	$pdf->Line(107,187,199,187);
	
	//TALON DE FACTURA
	$pdf->Image('../../images/logo_caasd.jpg',6,250,25);
	//$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Text(13,277,'TALON');
	$pdf->Text(16.5,282,'DE');
	$pdf->Text(14,287,'PAGO');
	$pdf->Image($factura.".gif",33,263,80,20);
	$pdf->Image('../../images/rec.jpg',109.3,250,90,20);
	$pdf->Ln(85);
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(17,5,'',0,0,'L');
	$pdf->Cell(83,5,'FAVOR NO COLOCAR SELLOS SOBRE EL CODIGO DE BARRAS',0,0,'C');
	$pdf->SetFont('Arial','B',8);
	$pdf->Text(111,255,'Factura Número:');
	$pdf->Text(111,259,'Periodo:');
	$pdf->Text(111,263,'Código Inmueble:');
	$pdf->Text(111,267,'Código Proceso:');
	$pdf->Text(154,255,'Fecha de Exp:');
	$pdf->Text(154,259,'Código Sistema:');
	
	$pdf->SetFont('Arial','',8);
	$pdf->Text(137,255,$factura);
	$pdf->Text(137,259,$mes."/".$agno);
	$pdf->Text(137,263,$catastro);
	$pdf->Text(137,267,$proceso);
	$pdf->Text(179,255,$fecexp);
	$pdf->Text(179,259,$codinm);
	$pdf->Text(60,286,"*".$factura."*");
	
	$pdf->SetFont('Arial','B',10);
	$pdf->Text(33,261,'Estafeta:');
	$pdf->Text(62,261,'Fecha de Pago:');
	$pdf->Text(110,275,'Sello y Firma:');
	$pdf->SetFont('Arial','B',9);
	$pdf->Text(40,290,$codinm);
	$pdf->Text(60,290,$alias);
	
	$pdf->Ln(20);
	$pdf->Cell(157,8,'TOTAL FACTURA:',0,0,'R');
	$pdf->Cell(32,8,$total_fac,1,0,'R',true);
	$pdf->Rect(136,273,63,15);
	
	$pdf->Ln(8);
	$pdf->Cell(157,7,'Vencimiento:',0,0,'R');
	$pdf->Cell(32,7,$fec_venc,0,0,'R');
	$pdf->Text(110,277,$gerencia);
	$pdf->Output("Factura_$factura.pdf",'I'); 
	//$pdf->Output("facturas_pdf/Factura_$factura.pdf",'F'); 
}
?>