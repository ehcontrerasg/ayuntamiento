<?php
require('../clases/FacturaInstantanea.php');
require('../test/test.facturaInstantanea.php');
require('../../librerias/qrcode/qrcode.class.php');
require("../datos/datos.facturaInstantanea.php");

/* Declaraciones */
$facturaInstantanea = new FacturaInstantanea('P','in',array(4,7));
$datosFacturaInstantanea = new DatosFacturaInstantanea();
$consecutivoFactura = $datosFacturaInstantanea->consecutivoFactura($_GET['inmueble'], $_GET['periodo']);
$qrUrl = "https://caasdenlinea.com/historico/fact/".$consecutivoFactura;
$datosFactura = $datosFacturaInstantanea->datosFactura($consecutivoFactura);
/* Fin de Declaraciones */

/* Cabecera */
$facturaInstantanea->ncf = $datosFactura['ncf'];
$documento = ($datosFactura['tipo_documento'] != 'Cédula') ? $datosFactura['documento'] : null;
$facturaInstantanea->rnc = ($documento == null) ? ' ' : $documento;
$facturaInstantanea->numeroFactura = $consecutivoFactura;
$facturaInstantanea->fechaEmision = $datosFactura["fecha_expedicion"];
/* Fin de cabecera */

$facturaInstantanea->AddPage(); //Agregar la página donde se mostrará la información.

/* Datos del cliente */
$facturaInstantanea->SetFont('Arial','B',8);

$facturaInstantanea->SetXY(16.1908,110.122);
$nombreCliente = utf8_decode($datosFactura["alias"]);
$facturaInstantanea->Cell(0,0,$nombreCliente); //Nombre del cliente

$facturaInstantanea->SetXY(16.1935,120.789);
$direccion = utf8_decode($datosFactura["direccion"]);
$facturaInstantanea->Cell(0,0,$direccion); //Dirección

$facturaInstantanea->SetXY(16.1935,130.389);
$urbanizacion = utf8_decode($datosFactura["desc_urbanizacion"]);
$facturaInstantanea->Cell(0,0,$urbanizacion); //Urbanización

$facturaInstantanea->SetXY(16.1935,152.789);
$codigoInmueble = $datosFactura["codigo_inmueble"];
$facturaInstantanea->Cell(0,0,utf8_decode("Código de Sistema: ".$codigoInmueble)); //Código de sistema

$facturaInstantanea->SetXY(16.1935,163.453);
$catastro = $datosFactura["catastro"];
$facturaInstantanea->Cell(0,0,utf8_decode("Código de Inmueble: ".$catastro)); //Código de inmueble
/* Fin de datos del cliente */

/* Datos de la factura */
$facturaInstantanea->SetFont('Arial','',8);

$facturaInstantanea->SetXY(260.183,137.793);
$idZona = utf8_decode($datosFactura["id_zona"]);
$facturaInstantanea->Cell(0,0,$idZona); /* Ciclo */

$facturaInstantanea->SetXY(271.119,147.393);
setlocale(LC_TIME,'esp_esp');
$agno = substr($datosFactura["periodo"],0,4);
$mes = substr($datosFactura["periodo"],(strlen($datosFactura["periodo"]) - 1),-2);
$periodo = strftime("%b/%G",mktime(0,0,0,$mes,0,$agno));
$periodo = strtoupper($periodo);
$facturaInstantanea->Cell(0,0,$periodo); /* Periodo */

$saldoFavor = $datosFacturaInstantanea->saldoFavor($codigoInmueble);
$facturaInstantanea->SetXY(346.255,163.132);
$facturaInstantanea->Cell(0,0,$saldoFavor); /* Pendiente saldos a favor */

$facturaInstantanea->SetXY(319.591,172.732);
$diferido = $datosFacturaInstantanea->diferido($codigoInmueble);
$facturaInstantanea->Cell(0,0,$diferido); /* Pendiente diferidos*/
/* Fin de datos de la factura */

/* Servicios*/ 
$tamanoCajaServicios = 23.49;  //Tamaño que tiene el cuadro de servicio de inmueble, medido en pulgadas.
$servicios   = $datosFacturaInstantanea->servicios($codigoInmueble); //Cantidad de servicios que tiene el inmueble 
$cantidadServicios   = count($servicios); //Cantidad de servicios que tiene el inmueble 
$fuenteServicios = 8; // Tamaño de fuente predeterminada 
$espacioEntreLineas = $tamanoCajaServicios / $cantidadServicios;
if ( $espacioEntreLineas < $fuenteServicios) { $fuenteServicios = $espacioEntreLineas; }

$xServicios = 13.90; //Donde empieza la escritura de servicios en la coordenada X.
$yServicios = 214.88; //Donde empieza la escritura de servicios en la coordenada Y.
$h = $espacioEntreLineas / 96; //De pixeles a pulgadas.
$facturaInstantanea->SetXY($xServicios,$yServicios);

$operaCorte = false;
foreach ($servicios as $key => $servicio) {
    $facturaInstantanea->SetFont('Arial','B',$fuenteServicios);
    $facturaInstantanea->SetX($xServicios);

    //operaCorte se utiliza para mostrar notas en la factura, si se encuentra al menos un servicio el cual opera corte pues se marca como verdadero.
    if (!$operaCorte && $servicio['opera_corte'] == 'S') { $operaCorte = true; } 
    if ($servicio["codigo_servicio"] > 4) {  continue; }

    $facturaInstantanea->CellFit(0.46,$h,$servicio['servicio'],0,0,'',false,'',true,false);
    $facturaInstantanea->CellFit(0.46,$h,$servicio['uso'],0,0,'',false,'',true,false);
    $facturaInstantanea->CellFit(0.46,$h,$servicio['tarifa'],0,0,'',false,'',true,false);
    $facturaInstantanea->CellFit(0.46,$h,$servicio['unidades'],0,0,'',false,'',true,false);
    $facturaInstantanea->Ln($h);
}
/*Fin de servicios */

/* Datos del medidor */
$facturaInstantanea->SetXY(265.42,220.458);
$facturaInstantanea->SetFont('Arial','',8);
$serial = $datosFactura["serial"];
$facturaInstantanea->Cell(0,0,$serial); //Serial
 /*Fin de Datos del medidor */

/* Detalle servicios domiciliarios */
$hDetalleServiciosDomiciliarios = 72; //Altura de la caja de detalle de servicios domiciliarios.
$detalleServicios = $datosFacturaInstantanea->detalleServiciosDomiciliados($consecutivoFactura); //Datos del detalle de servicios domiciliarios.
$serviciosDomiciliarios = $detalleServicios['servicios_domiciliarios'];
$cantidadDetalleServiciosDomiciliarios = count($serviciosDomiciliarios); //Cantidad de detalle.

$tamanoFuenteDetalleServiciosDomiciliarios = ($cantidadDetalleServiciosDomiciliarios > 0 ) ? $hDetalleServiciosDomiciliarios / $cantidadDetalleServiciosDomiciliarios : $hDetalleServiciosDomiciliarios;
if ($tamanoFuenteDetalleServiciosDomiciliarios > 6.67) { $tamanoFuenteDetalleServiciosDomiciliarios = 7.67; }

$facturaInstantanea->SetFontSize($tamanoFuenteDetalleServiciosDomiciliarios);

$xServicios = 14.86;
$yDetalleServicios = 273.8;
$facturaInstantanea->SetXY($xServicios,$yDetalleServicios);
$totalServiciosDomiciliarios = 0;
foreach ($serviciosDomiciliarios as $key => $servicio) {
    $h = $tamanoFuenteDetalleServiciosDomiciliarios / 96; //Para convertir de pixeles a pulgadas.   
    if (!isset($concepto) || $concepto != $servicio["concepto"]) {  
        $concepto = $servicio["concepto"];
        $facturaInstantanea->SetFont('Arial','B',$tamanoFuenteDetalleServiciosDomiciliarios);
        $facturaInstantanea->SetX($xServicios);
        $facturaInstantanea->Cell(1.84,$h,$concepto);
        $facturaInstantanea->Ln($h);        
    }

    $facturaInstantanea->SetFont('Arial','',$tamanoFuenteDetalleServiciosDomiciliarios);

    $rango = $servicio["rango"];
    $consumo = ($rango == 0) ? 'Consumo básico' : 'Consumo adicional '.$rango; //Tipo de consumo (básico o adicional)   
    $consumo = utf8_decode($consumo);

    $facturaInstantanea->SetX($xServicios);
    $facturaInstantanea->CellFit(0.46,$h,$consumo,0,0,'',false,'',true,false);
    $facturaInstantanea->CellFit(0.46,$h,$servicio["unidades"],0,0,'',false,'',true,false);

    $importe = $servicio['importe'];
    $unidades = ($servicio["unidades"] == 0) ? 1 : $servicio["unidades"];
    $valor_metros = round($importe / $unidades,1);
    $facturaInstantanea->CellFit(0.46,$h,$valor_metros,0,0,'',false,'',true,false);
    $facturaInstantanea->CellFit(0.46,$h,$importe,0,0,'',false,'',true,false);
    
    $facturaInstantanea->Ln($h);    
    $totalServiciosDomiciliarios += $importe;
}

$facturaInstantanea->SetXY(158.857,339.133);
$facturaInstantanea->CellFit(0.51,0.08,$totalServiciosDomiciliarios,0,0,'',false,'',true,false); /* Total servicios */
/* Fin de detalle servicios domiciliarios */

/* Datos de medición */
$datosLectura = $datosFacturaInstantanea->datosLecturas($_GET['inmueble'], $_GET['periodo']);

$facturaInstantanea->SetFont('Arial','',8);

$facturaInstantanea->SetXY(267.55,284.46);
$facturaInstantanea->Cell(0,0,$datosLectura[0]['feclec']); //Fecha anterior
$facturaInstantanea->SetXY(317,284.46);
$facturaInstantanea->Cell(0,0,$datosLectura[0]['lectura_actual']); //Lectura anterior

$facturaInstantanea->SetXY(267.55,296.46);
$facturaInstantanea->Cell(0,0,$datosLectura[1]['feclec']); //Fecha actual
$facturaInstantanea->SetXY(317,296.46);
$facturaInstantanea->Cell(0,0,$datosLectura[1]['lectura_actual']); //Lectura actual

$facturaInstantanea->SetXY(315.71,315.122);
$consumo = $datosFacturaInstantanea->consumo($consecutivoFactura);
$facturaInstantanea->Cell(0,0,$consumo); //Consumo facturado

$facturaInstantanea->SetXY(274.861,327.122);
$observacionLectura = ($datosFactura["descripcion_medidor"] != 'Sin Medidor') ? 'Diferencia de lectura': '';
$facturaInstantanea->Cell(0,0,$observacionLectura); //OBS
/* Fin de datos de medición */

/* Otros conceptos */
$xOtrosConceptos = 15.3815;
$yOtrosConceptos = 400.943;
$tamanoCajaOtrosConceptos = 54.4;
$fuenteOtrosConceptos = 8;

$otrosConceptos = $detalleServicios["otros_conceptos"];
$cantidadOtrosConceptos = (count($otrosConceptos) == 0) ? 1 : count($otrosConceptos);
$totalOtrosConceptos = 0;

$hOtrosConceptos = $tamanoCajaOtrosConceptos / $cantidadOtrosConceptos;

if ($hOtrosConceptos > $fuenteOtrosConceptos) { $hOtrosConceptos = $fuenteOtrosConceptos; }

$facturaInstantanea->SetXY($xOtrosConceptos,$yOtrosConceptos);
foreach ($otrosConceptos as $key => $otroConcepto) {    
    $h = $hOtrosConceptos / 96;
    $facturaInstantanea->CellFit(1.38,$h,$otroConcepto['concepto'],0,0,'L',false,'',true,false);
    $facturaInstantanea->CellFit(0.92,$h,$otroConcepto['importe'],0,0,'L',false,'',true,false);
    $facturaInstantanea->Ln($h);

    if($otroConcepto['importe'] != null) $totalOtrosConceptos += $otroConcepto['importe'];
}
$facturaInstantanea->SetXY(159.381,464.943);
$facturaInstantanea->CellFit(0.51,0.08,$totalOtrosConceptos,0,0,'',false,'',true,false); /* Total otros conceptos */
/* Fin de otros conceptos */

/* Código QR */
$qrCode = new QRcode($qrUrl,'H');
$qrCode->disableBorder();

$xQRCode = 261.715 / 96;
$yQRCode = 370.35 / 96;
$wQRCode = 100 / 96;
$qrCode->displayFPDF($facturaInstantanea, $xQRCode, $yQRCode, $wQRCode);
/* Fin Código QR */

/* Total factura */
$facturaInstantanea->SetFont('Arial','',9.33);

$facturaInstantanea->SetXY(163.572,507.681);
$totalFactura = round($totalServiciosDomiciliarios + $totalOtrosConceptos,0);
$facturaInstantanea->Cell(0,0,$totalFactura); //A + B

$facturaInstantanea->SetXY(158.86,551.68);
$fechaVencimiento = $datosFactura["fec_vcto"];
$facturaInstantanea->Cell(0,0,$fechaVencimiento); //Fecha de vencimiento
/* Total factura */

/* Notas  */
$tipoCliente = $datosFactura['id_tipo_cliente'];
if ($tipoCliente != 'GC' && $operaCorte) {
    $facturaInstantanea->SetFont('Arial','B',6.67);
    $facturaInstantanea->SetXY(292.04,519.038);
    $facturaInstantanea->Cell(0,0,'Notas');

    $facturaInstantanea->SetFont('Arial','B',10.67);
    $facturaInstantanea->SetXY(285.515,528.803);
    $facturaInstantanea->Cell(0,0,'AVISO');

    $facturaInstantanea->SetFont('Arial','B',6.67);
    $facturaInstantanea->SetXY(242.94,539.838);
    $fechaCorte = $datosFactura['feccorte'];
    $facturaInstantanea->Cell(0,0,'Fecha de corte de servicio '. $fechaCorte); //Fecha de corte
}
/* Fin de notas */

/* Cuadro de mensaje */
$facturaInstantanea->mensaje_ncf = $datosFactura["msj_ncf"];
$facturaInstantanea->mensaje_periodo = $datosFactura["msj_periodo"];
$facturaInstantanea->correo_proyecto = ($datosFactura["id_proyecto"] == 'SD') ? 'catastro@caasdoriental.com.' : 'soporte@coraaboenlinea.com.';
/* Fin de cuadro de mensaje */

/* Salida del PDF */
$facturaInstantanea->output('I','Factura instantánea del inmueble '.$codigoInmueble,true);