<?
include ('../clases/fpdf.php');
include ('../clases/class.facturas2.php');
require_once ('../../funciones/barcode/barcode.inc.php');


$pdf = new FPDF();
$pdf->AliasNbPages();


	$l=new facturas();
	$resultado=$l->datosFacturaPdf();
	while (oci_fetch($resultado)) {
	$pdf->AddPage();
		$codinm=oci_result($resultado, 'CODIGO_INM');
		$ncffac = oci_result($resultado, 'NCF');
		$catastro=oci_result($resultado, 'CATASTRO');
		$alias=oci_result($resultado, 'ALIAS');
		$direccion=oci_result($resultado, 'DIRECCION');
		$urbaniza=oci_result($resultado, 'DESC_URBANIZACION');
		$zona=oci_result($resultado, 'ID_ZONA');
		$fecexp=oci_result($resultado, 'FECEXP');
		$periodo=oci_result($resultado, 'PERIODO');
		$proceso=oci_result($resultado, 'ID_PROCESO');
		$gerencia=oci_result($resultado, 'GERENCIA');
		$marmed=oci_result($resultado, 'DESC_MED');
		$calmed=oci_result($resultado, 'COD_CALIBRE');
		$serial=oci_result($resultado, 'SERIAL');
		$fecvcto=oci_result($resultado, 'FEC_VCTO');
		$fecorte=oci_result($resultado, 'FECCORTE');
		$factura=oci_result($resultado, 'CONSEC_FACTURA');

		new barCodeGenrator($factura,1,$factura.'.gif',100,70,0);
	
	
		if ($marmed == "") $marmed = "Sin Medidor";
		
		$posy = 52;
		$a=new facturas();
		$stid1=$a->datosServiciosPdf($codinm);
		while (oci_fetch($stid1)) {
			$servicio=oci_result($stid1, 'DESC_SERVICIO');
			$uso=oci_result($stid1, 'DESC_USO');
			$tarifa=oci_result($stid1, 'CODIGO_TARIFA');	
			$unidades=oci_result($stid1, 'UNIDADES_TOT');
			if($servicio == 'Agua' || $servicio == 'Agua de Pozo' || $servicio == 'Alcantarillado Red' || $servicio == 'Alcantarillado Pozo'){
				$pdf->SetFont('Arial','B',8);
				$pdf->Text(10,$posy,$servicio);
				$pdf->Text(45,$posy,$uso);
				$pdf->Text(80,$posy,$tarifa);
				$pdf->Text(92,$posy,$unidades);
				$posy = $posy+4;
			}
		}oci_free_statement($stid1);
		
		$a=new facturas();
		$stid1=$a->datosLecturaPdf($codinm, $periodo);
		$posy = 77;
		while (oci_fetch($stid1)) {
			$lecturas=oci_result($stid1, 'LECTURA_ACTUAL');
			$fechas_lec=oci_result($stid1, 'FECLEC');
			$pdf->SetFont('Arial','',9);
			$pdf->Text(68,$posy,$fechas_lec);
			$pdf->Text(90,$posy,$lecturas);
			$posy = $posy+4;
			$consumo = $consumo - $lecturas;
			$consumo = $consumo * (-1);
		}oci_free_statement($stid1);
		$agno = substr($periodo,0,4);
		$mes = substr($periodo,4,2);
		if($mes == '01'){$mes = Ene;} if($mes == '02'){$mes = Feb;} if($mes == '03'){$mes = Mar;} if($mes == '04'){$mes = Abr;}
		if($mes == '05'){$mes = May;} if($mes == '06'){$mes = Jun;} if($mes == '07'){$mes = Jul;} if($mes == '08'){$mes = Ago;}
		if($mes == '09'){$mes = Sep;} if($mes == '10'){$mes = Oct;} if($mes == '11'){$mes = Nov;} if($mes == '12'){$mes = Dic;}
		
		//CABECERA FACTURA
	
		$pdf->SetFont('Arial','B',10);
		$pdf->Text(18,14, utf8_decode('Código Sistema'));
		$pdf->SetFont('Arial','B',16);
		$pdf->Text(22,21,$codinm);
		$pdf->SetFont('Arial','B',10);
		$pdf->Text(160,14,'NCF '.$ncffac);
		$pdf->Text(160,18,utf8_decode('Código de Inmueble'));
		$pdf->Text(160,22,$catastro);
		$pdf->Ln(18);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(90,10,utf8_decode(substr($alias,0,31)),0,0,'L',false);
		$pdf->Cell(50,10,utf8_decode($direccion),0,0,'C',false);
		$pdf->Cell(50,10,utf8_decode($urbaniza),0,0,'C',false);
		$pdf->Ln(10);
		
		// CUERPO DE LA FACTURA
		// SECCION SERVICIOS Y DATOS DE FACTURA
	
		$pdf->Cell(4,7,'',0,0,'C',false);
		$pdf->SetTextColor(0,0,0);
		$pdf->Ln(12);
		$pdf->Cell(93,7,'',0,0,'C',false);
		$pdf->Cell(4,7,'',0,0,'C',false);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(28,5,utf8_decode(''),0,0,'L',false);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(33,5,$factura,0,0,'R',false);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(19,5,'',0,0,'L',false);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(18,5,$zona,0,0,'R',false);
		$pdf->Ln(7);
		$pdf->Cell(93,7,'',0,0,'C',false);
		$pdf->Cell(4,7,'',0,0,'C',false);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(28,5,utf8_decode(''),0,0,'L',false);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(33,5,$fecexp,0,0,'R',false);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(19,5,'',0,0,'L',false);
		$pdf->SetFont('Arial','',9);
		$pdf->Cell(18,5,$mes.'/'.$agno,0,0,'R',false);
		$pdf->Ln(8);
		$pdf->SetFont('Arial','B',9);
		
		//SECCION DATOS MEDIDOR, DATOS MEDICION E INFORMACION ADICIONAL
		$pdf->Cell(40,7,'',0,0,'C',false);
		$pdf->Cell(53,7,'',0,0,'C',false);
		$pdf->Cell(4,7,'',0,0,'C',false);
		$pdf->Cell(92,7,'',0,0,'C',false);
		$pdf->Ln(7);
		$pdf->Cell(40,25,'',0,0,'C',false);
		$pdf->Cell(53,25,'',0,0,'C',false);
		$pdf->Cell(4,25,'',0,0,'C',false);
		$pdf->Cell(92,25,'',0,0,'C',false);
		$pdf->SetFont('Arial','B',9);
		$pdf->Text(12,87,'');
		$pdf->Text(12,94,'');
		$pdf->Text(12,101,'');
		$pdf->SetFont('Arial','',9);
		$pdf->Text(25,78,$marmed);
		$pdf->Text(25,83,$calmed);
		$pdf->Text(25,88,$serial);
		
		$pdf->SetFont('Arial','B',9);
		$pdf->Text(71,86,'');
		$pdf->Text(88,86,'');
	
		$pdf->Text(52,92,'');
		$pdf->Text(52,97,'');
		if ($marmed == "Sin Medidor") $pdf->Text(82,89,'Promedio');
		else $pdf->Text(72,90,'Diferencia Lectura');
		$pdf->Text(91,85,$consumo);
		$pdf->SetFont('Arial','',10);
		$pdf->SetFont('Arial','B',10);
		$pdf->Text(112,88,'');
		$pdf->Text(112,98,'');
		$pdf->Ln(28);
		$pdf->SetFont('Arial','B',8);
		$pdf->SetTextColor(255,255,255);
		$pdf->SetFillColor(0, 128, 192);
		
		//SECCION DETALLE SERVICIOS DOMICILIARIOS Y OTROS CONCEPTOS
		$pdf->Cell(92.6,6,'',0,0,'C',false);
		$pdf->Cell(4.8,6,'',0,0,'C',false);
		$pdf->Cell(91.4,6,'',0,0,'C',false);
		
		$pdf->Ln(6);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','B',9);
		
		$pdf->Cell(30,6,'',0,0,'C',false);
		$pdf->Cell(23,6,'',0,0,'C',false);
		$pdf->Cell(23,6,'',0,0,'C',false);
		$pdf->Cell(17,6,'',0,0,'C',false);
		$pdf->Cell(4.8,6,'',0,0,'C',false);
		$pdf->Cell(70,6,'',0,0,'L',false);
		$pdf->Cell(32,6,'',0,0,'L',false);
	
		$pxotrocon = 109;
		//$pxcon = 6;
		$conceptoini = '';
		$a=new facturas();
		$stid1=$a->detalleFacturaPdf ($factura);
		$pdf->Ln(2);
		while (oci_fetch($stid1)) {
			$concepto=oci_result($stid1, 'CONCEPTO');
			$rango=oci_result($stid1, 'RANGO');
			$unidades=oci_result($stid1, 'UNIDADES');
			$valor=oci_result($stid1, 'VALOR');
			$codservicio=oci_result($stid1, 'COD_SERVICIO');
			
			
			if ($concepto != $conceptoini && ($concepto == 'Agua' || $concepto == 'Agua de Pozo')){
				$pdf->Cell(2,6,'',0,0,'R',false);
				$pdf->Cell(30,6,$concepto,0,0,'L',false);
				$conceptoini = $concepto;
			}
			
			if ($concepto == 'Agua' || $concepto == 'Agua de Pozo'){
				$pdf->SetFont('Arial','',9);
				
				if ($rango == 0) {
					$f=new facturas();
					$stidb=$f->valorRangosPdf ($codservicio,1, $codinm);
					while (oci_fetch($stidb)) {
						$valor_mt=oci_result($stidb, 'VALOR_METRO');	
					}oci_free_statement($stidb);
					$valor_mt2 = $valor_mt;
					$totalservicios += $valor;
				}
				else{
					$f=new facturas();
					$stidb=$f->valorRangosPdf ($codservicio,$rango, $codinm);
					while (oci_fetch($stidb)) {
						$valor_mt=oci_result($stidb, 'VALOR_METRO');	
					}oci_free_statement($stidb);
					$totalservicios += $valor;
				}
				
				
				if($rango == 0){	
					$pdf->Ln(5);
					$pdf->Cell(3,5,'',0,0,'L',false);
					$pdf->Cell(35,5,'Consumo Basico',0,0,'L',false);
					$pdf->Cell(10,5,$unidades,0,0,'R');
					$pdf->Cell(20,5,$valor_mt,0,0,'R',false);
					$pdf->Cell(20,5,$valor,0,0,'R',false);
				}
				
				if($rango == 1){
					$pxr1 = 4;
					$pdf->Ln($pxr1);
					$pdf->Cell(3,5,'',0,0,'L',false);
					$pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L',false);
					$pdf->Cell(10,5,$unidades,0,0,'R',false);
					$pdf->Cell(20,5,$valor_mt,0,0,'R',false);
					$pdf->Cell(20,5,$valor,0,0,'R',false);
				}
				
				if($rango == 2){
					$pxr2 = 4;
					$pdf->Ln($pxr2);
					$pdf->Cell(3,5,'',0,0,'L');
					$pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L',false);
					$pdf->Cell(10,5,$unidades,0,0,'R',false);
					$pdf->Cell(20,5,$valor_mt,0,0,'R',false);
					$pdf->Cell(20,5,$valor,0,0,'R',false);
				}
				
				if($rango == 3){
					$pxr3 = 4;
					$pdf->Ln($pxr3);
					$pdf->Cell(3,5,'',0,0,'L',false);
					$pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L',false);
					$pdf->Cell(10,5,$unidades,0,0,'R',false);
					$pdf->Cell(20,5,$valor_mt,0,0,'R',false);
					$pdf->Cell(20,5,$valor,0,0,'R',false);
				}
				
				if($rango == 4){
					$pxr4 = 4;
					$pdf->Ln($pxr4);
					$pdf->Cell(3,5,'',0,0,'L',false);
					$pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L',false);
					$pdf->Cell(10,5,$unidades,0,0,'R',false);
					$pdf->Cell(20,5,$valor_mt,0,0,'R',false);
					$pdf->Cell(20,5,$valor,0,0,'R',false);
				}
				
				if($rango == 5){
					$pxr5 = 4;
					$pdf->Ln($pxr5);
					$pdf->Cell(3,5,'',0,0,'L',false);
					$pdf->Cell(35,5,'Consumo Adicional '.$rango,0,0,'L',false);
					$pdf->Cell(10,5,$unidades,0,0,'R',false);
					$pdf->Cell(20,5,$valor_mt,0,0,'R',false);
					$pdf->Cell(20,5,$valor,0,0,'R',false);
				}
			}
			if ($concepto != $conceptoini && ($concepto == 'Alcantarillado Red' || $concepto == 'Alcantarillado Pozo')){
				$pdf->SetTextColor(0,0,0);
				$pdf->SetFont('Arial','B',9);
				$pxcalc = 5;
				$pdf->Ln($pxcalc);
				$pdf->Cell(2,6,'',0,0,'R',false);
				$pdf->Cell(30,6,$concepto,0,0,'R',false);
				$conceptoini = $concepto;
			}
			if ($concepto == 'Alcantarillado Red' || $concepto == 'Alcantarillado Pozo'){
				$pdf->SetFont('Arial','',9);
				if($rango >= 0){
					$f=new facturas();
					$stidb=$f->valorRangosPdf ($codservicio,$rango, $codinm);
					while (oci_fetch($stidb)) {
						$valor_mt=oci_result($stidb, 'VALOR_METRO');	
					}oci_free_statement($stidb);
					$valor_mt = $valor_mt * $valor_mt2;
					$paxalcde = 5;
					$pdf->Ln($paxalcde);
					$pdf->Cell(3,5,'',0,0,'L',false);
					$pdf->Cell(35,5,'Consumo Basico',0,0,'L',false);
					$pdf->Cell(10,5,$unidades,0,0,'R',false);
					$pdf->Cell(20,5,$valor_mt,0,0,'R',false);
					$pdf->Cell(20,5,$valor,0,0,'R',false);
					$totalservicios += $valor;
				}
			}
			
			if ($concepto != $conceptoini && ($concepto != 'Alcantarillado Red' && $concepto != 'Alcantarillado Pozo' && $concepto != 'Agua' && $concepto != 'Agua de Pozo')){
				$pdf->SetFont('Arial','B',9);
				$pdf->Text(117,$pxotrocon,$concepto);
				$pdf->Text(191,$pxotrocon,$valor);
				$pxotrocon = $pxotrocon + 5;
				$conceptoini = $concepto;
				$totalotrosconceptos += $valor;
			}
			
		}oci_free_statement($stid1);
		
		$pdf->Ln(48 - $pxr1 - $pxr2 - $pxr3 - $pxr4 - $pxr5 - $pxcalc - $paxalcde);
		$pdf->SetFont('Arial','B',9);
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(72.6,6,'',0,0,'L',false);
		$pdf->Cell(18,6,$totalservicios,0,0,'R',false);
		$pdf->Cell(2,6,'',0,0,'R',false);
		$pdf->Cell(4.8,6,'',0,0,'C',false);
		$pdf->Cell(72.6,6,'',0,0,'L',false);
		$pdf->Cell(17,6,$totalotrosconceptos,0,0,'R',false);
		$pdf->Cell(6,6,'',0,0,'R',false);
	
		
	
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','B',9);
	
		$pdf->SetFont('Arial','B',11);
	
		$pdf->SetFont('Arial','B',9);
		if($uso != 'Oficial')
			$pdf->Text(28,187,'Fecha de corte de servicio '.$fecorte);
		
		$pdf->SetFont('Arial','B',11);
		$totalfactura = $totalservicios + $totalotrosconceptos;
	
		$pdf->Text(181,193,$fecvcto);
		
		$pdf->Ln(9.3);
		$pdf->Cell(97.3,18.7,'',0,0,'C',false);
		$pdf->Cell(56,18.7,'',0,0,'C',false);
		$pdf->Cell(35.4,18.7,'',0,0,'C',false);
		$pdf->SetFont('Arial','B',18);
		$pdf->Text(182,182,$totalfactura);
		//$pdf->SetFont('Arial','B',11);
		//TALON DE FACTURA
		//$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','B',10);
	
		$pdf->Image($factura.".gif",33,263,80,20);
		$pdf->Ln(85);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(17,5,'',0,0,'L');
		$pdf->Cell(83,5,'',0,0,'C',false);
		$pdf->SetFont('Arial','B',8);
	
		
		$pdf->SetFont('Arial','',8);
		$pdf->Text(142,256,$factura);
		$pdf->Text(142,259,$mes."/".$agno);
		$pdf->Text(142,263,$catastro);
		$pdf->Text(142,267,$proceso);
		$pdf->Text(187,255,$fecexp);
		$pdf->Text(187,258,$codinm);
		$pdf->Text(60,286,"*".$factura."*");
		
		$pdf->SetFont('Arial','B',10);
	
	
		$pdf->SetFont('Arial','B',9);
		$pdf->Text(38,287,$codinm);
		$pdf->Text(60,290,utf8_decode($alias));
		
		$pdf->Ln(21);
		$pdf->Cell(157,8,'',0,0,'R',false);
		$pdf->Cell(32,8,$totalfactura,0,0,'R',false);
	
		
		$pdf->Ln(7);
		$pdf->Cell(157,7,'',0,0,'R',false);
		$pdf->Cell(32,7,$fecvcto,0,0,'R');
		$pdf->Text(110,277,$gerencia);
		//$pdf->AddPage();
		
	}	oci_free_statement($resultado);	
	//$pdf->Output("Facturas.pdf",'I');
    $pdf->Output("facturas_digital/Factura_$factura.pdf",'I');
    unlink($factura.'.gif');
	//$pdf->Output("facturas_pdf/Factura_$factura.pdf",'F'); 
?>