<?php
require('../clases/pdf_js.php');
require'../clases/classPagos.php';
$cod_inmueble=$_GET['cod_inmueble'];
$id_rec=$_GET['id_rec'];
$num_caja=$_GET['num_caja'];
$des_punto=$_GET['des_punto'];
$des_ent=$_GET['des_ent'];
$nom_cli=$_GET['nom_cli'];
$concepto=$_GET['concepto'];
$fecha=$_GET['fecha'];
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
$resultado = $c->obtenerDescConcepto($concepto);
while (oci_fetch($resultado)) {
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
$pdf=new PDF_AutoPrint('P','mm','A7');
//$pdf->SetMargins(10, 10 , 5);
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Text(5, 5, 'CAASD. Gerencia Comercial');
$pdf->Text(0, 13, 'Comprobante de Pago # '.$id_rec);
$pdf->Text(0, 16, '______________________________');
$pdf->Ln(7);
$pdf->Cell(20,8,'Cod Sistema: ',0,0,'R');
$pdf->SetFont('Arial','',12);
$pdf->Cell(10,8,$cod_inmueble,0,0,'L');
$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,8,'Cliente:',0,0,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(10,8,$nom_cli,0,0,'L');
$pdf->SetFont('Arial','B',12);
$pdf->Text(0, 29, '______________________________');
$pdf->Ln(8);
$pdf->Cell(10,8,'Fecha:',0,0,'R');
$pdf->SetFont('Arial','',12);
$pdf->Cell(10,8,$fecha,0,0,'L');
$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(10,8,'Entidad:',0,0,'R');
$pdf->SetFont('Arial','',12);
$pdf->Cell(10,8,$des_ent,0,0,'L');
$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(10,8,'Punto:',0,0,'R');
$pdf->SetFont('Arial','',12);
$pdf->Cell(10,8,$des_punto,0,0,'L');
$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(10,8,'Caja:',0,0,'R');
$pdf->SetFont('Arial','',12);
$pdf->Cell(10,8,$num_caja,0,0,'L');
$pdf->Text(0, 53, '______________________________');
$pdf->SetFont('Arial','B',10);
$pdf->Text(0, 58, 'CONCEPTO PAGO EN ESTE RECIBO');
//$pdf->Ln(13);
//$pdf->SetFont('Arial','B',10);
$pdf->Text(0,64,'Concepto');
$pdf->Text(40,64,'Valor');
$pdf->Text(55,64,'Pagado');
//$pdf->Text(54,64,'Restante');
$pdf->SetFont('Arial','',10);
$px = 69;

$pdf->Text(0,$px,$desconcepto);
$pdf->Text(40,$px,$importe);
$pdf->Text(55,$px,$importe);
$px = $px+5;
$pdf->SetFont('Arial','B',10);
$pdf->Text(0,$px,'Importe Total');
$pdf->Text(55,$px -4, '________');
$pdf->SetFont('Arial','',10);
$pdf->Text(55,$px,number_format($importe,0,'.',','));

$px = $px + 5;
if($medio == 1 || $medio == 5 || $medio == 6){
	$pdf->Text(0,$px,'Monto '.number_format($monto,0,'.',','));
	$pdf->Text(37,$px,'Cambio '.number_format($vuelta,0,'.',','));
}
if($medio == 2 || $medio == 3 || $medio == 4){
	$pdf->Text(0,$px,'Monto '.number_format($importe,0,'.',','));
	$pdf->Text(37,$px,'Cambio '.number_format(0,0,'.',','));
}
$pdf->SetFont('Arial','B',12);
$pdf->Text(0, $px + 2, '______________________________');
$pdf->Text(0, $px + 7, 'FORMA DE PAGO');
$pdf->Text(0, $px + 12,'Tipo');
$pdf->Text(17,$px + 12,'Descripcion');
$pdf->SetFont('Arial','',12);
$pdf->Text(0, $px + 17,$medio);
$pdf->Text(17, $px + 17,$des_medio);
$pdf->Ln(130);
$pdf->Rect(0.5,$px+20,69.5,18);
$pdf->Image('../../images/logo_caasd.jpg',2,$px+21,15);
$pdf->SetFont('Arial','B',16);
$pdf->SetTextColor(19,30,181);
$pdf->Text(30, $px + 27,'PAGADO');
$pdf->SetFont('Arial','B',13);
$pdf->SetTextColor(64,65,68);
$pdf->Text(22, $px + 32,$fecha);
$pdf->SetFont('Arial','B',8);
$pdf->Text(25, $px + 36,'CAASD, Gerencia Comercial');
//Open the print dialog
$pdf->AutoPrint(true);
$pdf->Output();
/*$file=tempnam('/tmp','Cotizacion');
$file_2=$file;
$file.='.pdf';
$pdf->Output($file,"F");
$com = "lpr -H ip_impresora -P impresora_ticket";

$var=$com." ".$file; 
exec($var);
unlink($file);
unlink($file_2);
echo "<script>javascript:history.back()</script>"; */
?>