<?
include_once ('../../include.php');
//require'../clases/classImpresion.php';
require_once("../clases/fpdf.php");
$proyecto = $_GET['proyecto'];
$periodo = $_GET['periodo'];
$zona = $_GET['zona'];

class PDF extends FPDF {
	function Header() {
		$enc = explode("|",$this->title);
		$proyecto = $enc[0];
	  	$zona = $enc[1];
	  	$periodo = $enc[2];
		$operario = $enc[3];
		$ruta = $enc[4];
		if ($proyecto == 'SD') $desproyecto = 'Santo Domingo';
		if ($proyecto == 'BC') $desproyecto = 'Bocachica';
		$agno = substr($periodo,0,4);
		$mes = substr($periodo,4,2);
		if($mes == '01'){$mes = Ene;} if($mes == '02'){$mes = Feb;} if($mes == '03'){$mes = Mar;} if($mes == '04'){$mes = Abr;}
		if($mes == '05'){$mes = May;} if($mes == '06'){$mes = Jun;} if($mes == '07'){$mes = Jul;} if($mes == '08'){$mes = Ago;}
		if($mes == '09'){$mes = Sep;} if($mes == '10'){$mes = Oct;} if($mes == '11'){$mes = Nov;} if($mes == '12'){$mes = Dic;}
	  	$this->SetFont('Helvetica','B',12);
      	$this->Cell(30,6,"HOJA DE LECTURA",0,0,'L');
		$this->Rect(11,15,190,18);
		$this->SetFont('Helvetica','B',10);
		$this->Text(15,20,"Acueducto:");
		$this->Text(40,20,$proyecto." ".$desproyecto);
		$this->Text(80,20,"Contratista:",1,0,'C');
		$this->Text(105,20,"ACEA DOMINICANA");
		$this->Text(155,20,"Pág:      ".$this->PageNo().' / {nb}',0,0,'R');	
		$this->Text(15,25,"Zona:"); 
		$this->Text(40,25,$zona);
		$this->Text(80,25,"Lector:");
		$this->Text(105,25,substr($operario,0,22));
		$this->Text(155,25,date('d-M-Y  H:i A'));
		$this->Text(15,30,"Periodo:");
		$this->Text(40,30,$mes." / ".$agno);
		$this->Text(105,30,$ruta);
	}	  
}  

$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;

set_time_limit(300);  
$pdf = new PDF();
$pdf->AliasNbPages();  

$agno2 = substr($periodo,0,4);
$mes2 = substr($periodo,4,2);

$sql = "SELECT I.CODIGO_INM, I.DIRECCION, U.DESC_URBANIZACION, I.CATASTRO, I.ID_PROCESO, C.ALIAS, E.DESC_EMPLAZAMIENTO, M.COD_MEDIDOR, A.DESC_CALIBRE, M.SERIAL, O.NOM_USR, 
O.APE_USR, L.COD_LECTOR, I.ID_PROYECTO, I.ID_ZONA, L.PERIODO, I.ID_RUTA
FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TT_MEDIDOR_INMUEBLE M, SGC_TT_REGISTRO_LECTURAS L, SGC_TP_URBANIZACIONES U, SGC_TP_EMPLAZAMIENTO E, SGC_TP_CALIBRES A, 
SGC_TT_USUARIOS O
WHERE I.CODIGO_INM = M.COD_INMUEBLE AND I.CODIGO_INM = C.CODIGO_INM AND I.CODIGO_INM = L.COD_INMUEBLE AND L.PERIODO = $periodo AND L.ID_ZONA = '$zona' AND C.FECHA_FIN IS NULL 
AND I.CONSEC_URB = U.CONSEC_URB AND M.COD_EMPLAZAMIENTO = E.COD_EMPLAZAMIENTO AND M.COD_CALIBRE = A.COD_CALIBRE AND I.ID_PROYECTO = '$proyecto' AND L.COD_LECTOR IS NOT NULL 
AND L.COD_LECTOR = O.ID_USUARIO
ORDER BY I.ID_PROCESO";	
//echo $sql; //exit();
$stid = oci_parse($link, $sql);
oci_execute($stid, OCI_DEFAULT);
$cont = 0;
$posxy = 38;
while (oci_fetch($stid)) {
	$inmueble = oci_result($stid, 'CODIGO_INM') ;	
	$direccion = oci_result($stid,"DIRECCION");	
	$urbanizacion = oci_result($stid,"DESC_URBANIZACION");
	$catastro = oci_result($stid,"CATASTRO");
	$proceso = oci_result($stid,"ID_PROCESO");	   
	$cliente = oci_result($stid,"ALIAS");	   	   	   	   	   	  
    $emplaza = oci_result($stid,"DESC_EMPLAZAMIENTO");
	$medidor = oci_result($stid,"COD_MEDIDOR");
	$calibre = oci_result($stid,"DESC_CALIBRE");
	$serial = oci_result($stid,"SERIAL");
	$nomope = oci_result($stid,"NOM_USR");
	$apeope = oci_result($stid,"APE_USR");
	$codope = oci_result($stid,"COD_LECTOR");
	$nombreop = $nomope." ".$apeope;
	$proyecto = oci_result($stid,"ID_PROYECTO");
	$zona = oci_result($stid,"ID_ZONA");
	$periodo = oci_result($stid,"PERIODO");
	$ruta = oci_result($stid,"ID_RUTA");
	// SALTO DE PAGINA POR CONTADOR U OPERARIO 
	if($cont % 6 == 0 && $cont > 0){
		$pdf->title = "$proyecto|$zona|$periodo|$nombreop|$ruta";
		$pdf->AddPage();
		$posxy = 38;
		$antope = $codope;
		//$cont = 0;
	}
	else{
		if ($codope != $antope) {
			$pdf->title = "$proyecto|$zona|$periodo|$nombreop|$ruta";	
			$pdf->AddPage(); 
			$antope = $codope;
			$posxy = 38;
			$cont = 0;
		}
	}
	
	$pdf->title = "$proyecto|$zona|$periodo|$nombreop|$ruta";
	//se generan los dos cuadros generales derecho e izquierdo
	$pdf->SetFont('Helvetica','',10);	
	$pdf->Rect(11,$posxy-5,120,40);
	$pdf->Rect(131,$posxy-5,70,40);
	
	//info general cuadro izquierdo
	$pdf->SetFont('Helvetica','B',8);
	$pdf->Text(14, $posxy ,utf8_decode($direccion)." ".utf8_decode($urbanizacion));
	$pdf->Text(80, $posxy ,'Inmueble');
	$pdf->Text(100, $posxy ,$inmueble);
	$pdf->SetFont('Helvetica','',8);
	$pdf->Text(14, $posxy+4 ,$catastro." / ".$proceso);
	$pdf->Text(14, $posxy+8 ,utf8_decode($cliente));
	$pdf->Text(14, $posxy+20 ,utf8_decode($emplaza));
	$pdf->Text(14, $posxy+30 ,utf8_decode($medidor));
	$pdf->Text(24, $posxy+30 ,utf8_decode($calibre));
	$pdf->Text(34, $posxy+30 ,utf8_decode($serial));
	
	//creamos el cuadro interno de lectura lado izquierdo
	$pdf->SetFont('Helvetica','B',12);
	$pdf->Rect(80,$posxy+2,48,30);	
	$pdf->Line(80,$posxy+12,128,$posxy+12);	
	$pdf->Line(80,$posxy+22,128,$posxy+22);	
	
	//info cuadro interno lectura lado izquierdo
	$pdf->SetFont('Helvetica','B',8);
	$pdf->Text(82, $posxy+5 ,'Lectura:');
	$pdf->Text(82, $posxy+15 ,'Fecha:');
	$pdf->Text(82, $posxy+25 ,'Obs:');
	$pdf->SetFont('Helvetica','',8);
	$pdf->Text(110, $posxy+20 ," / ".$mes2." / ".$agno2);
	
	//info general cuadro derecho
	$pdf->Image("../../images/logo_caasd.jpg",133,$posxy-3,12);
	$pdf->SetFont('Helvetica','B',9);
	$pdf->Text(150, $posxy ,'Comprobante de Lectura');
	$pdf->SetFont('Helvetica','',7);
	$pdf->Text(135, $posxy+12 ,$inmueble);
	$pdf->Text(135, $posxy+15 ,utf8_decode($direccion)." ".utf8_decode($urbanizacion));
	$pdf->Text(135, $posxy+18 ,utf8_decode($cliente));
	$pdf->Text(162, $posxy+5 ,$periodo);
	$pdf->Text(190, $posxy+4 ,utf8_decode($medidor));
	$pdf->Text(190, $posxy+7 ,utf8_decode($calibre));
	$pdf->Text(185, $posxy+10 ,utf8_decode($serial));
	
	//creamos el cuadro interno de lectura lado derecho
	$pdf->SetFont('Helvetica','B',12);
	$pdf->Rect(135,$posxy+19,25,14);
	$pdf->Rect(160,$posxy+19,15,14);
	$pdf->Rect(175,$posxy+19,25,14);	
	$pdf->Line(135,$posxy+22,200,$posxy+22);
	$pdf->SetFont('Helvetica','B',7);
	$pdf->Text(143, $posxy+21 ,'Lectura');	
	$pdf->Text(165, $posxy+21 ,'Obs.');	
	$pdf->Text(183, $posxy+21 ,'Fecha');	
	
	$posxy +=40;
	$cont++;
}// unset($values); 
$pdf->Output("Libro.pdf",'I'); 
?>