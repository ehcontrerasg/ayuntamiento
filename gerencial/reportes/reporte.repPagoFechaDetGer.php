<?php
/**
 * Created by PhpStorm.
 * User: Jesus Gutierrez
 * Date: 16/04/2020
 * Time: 7:51 pm
 */
setlocale(LC_MONETARY, 'es_DO');
$proyecto = $_POST['proyecto'];
$entini = $_POST['entini'];
$entfin = $_POST['entfin'];
$punini = $_POST['punini'];
$punfin = $_POST['punfin'];
$cajini = $_POST['cajini'];
$cajfin = $_POST['cajfin'];
$fecini = $_POST['fecini'];
$fecfin = $_POST['fecfin'];
ini_set('memory_limit', '-1');

include "../clases/class.reportes_pagos.php";
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';

$fecha1= new DateTime("$fecini");
$fecha2= new DateTime("$fecfin");
$diff = $fecha1->diff($fecha2) ;
$dias = $diff->days + 1;
$date = date("$fecini");

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle(utf8_encode("Reporte Pagos Por Fecha Detallado ") . $proyecto)
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Recaudo");
$estiloTitulos = array(
    'borders'   => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => '000000'),
        ),
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
    ),
);

$estiloCajas = array(
    'borders'   => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => '000000'),
        ),
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,
    ),
);

$h = 65;
$d = 65;
for ($i = 72; $i < 72+$dias; $i++) {
    $letter = chr($i);
    if($d<=90) {
        if ($letter > chr(90)) {
            $letter = chr($h) . chr($d);
            $d++;
        }
    }
    if($d>90){
        $d=65; $h++;
    }
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:'.$letter.'1');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:'.$letter.'1')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:'.$letter.'1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', strtoupper('PAGOS POR FECHA DETALLADO '.$proyecto.' DEL '.$fecini.' AL '.$fecfin));
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:'.$letter.'2')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:'.$letter.'2')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'ENTIDAD');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'PUNTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'CAJA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'TIPO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', 'MEDIO PAGO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', 'No PAGOS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', 'IMP. TOTAL');
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(40);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(31);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(17);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(14);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(15);

$h = 65;
$d = 65;
$cont = 0;
for ($i = 72; $i < 72+$dias; $i++) {
    $letter = chr($i);
    if($d<=90) {
        if ($letter > chr(90)) {
            $letter = chr($h) . chr($d);
            $d++;
        }
    }
    if($d>90){
        $d=65; $h++;
    }
    $mod_date = strtotime($date . "+ $cont day");
    $dia_fecha = date("d/m/Y", $mod_date);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter.'2', $dia_fecha);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($letter)->setWidth(13);

    $cont++;
}

$total_caja = 0;
$total_caja_dia = 0;
$total_punto = 0;
$total_entidad = 0;
$fila = 3;
$entidadant = ''; $puntoant = ''; $cajaant=''; $tipoant=''; $cod_entidad_ant='';
$c=new Reportes();
$registros=$c->seleccionaPagosEntidad($proyecto,$entini,$entfin,$fecini,$fecfin,$punini,$punfin,$cajini,$cajfin);
while (oci_fetch($registros)) {
    $entidad = oci_result($registros, 'ENTIDAD');
    $punto = oci_result($registros, 'PUNTO');
    $caja = oci_result($registros, 'DESCRIPCION');
    $numpagos = oci_result($registros, 'CANTIDAD');
    $importe = oci_result($registros, 'IMPORTE');
    $medPago = oci_result($registros, 'MEDIO');
    $tipo = oci_result($registros, 'TIPO');
    $puntocomp=oci_result($registros, 'PPAGOCOMP');
    $cajacomp=oci_result($registros, 'ID_CAJA');
    $cod_entidad = oci_result($registros, 'COD_ENTIDAD');
    $cod_caja =oci_result($registros, 'ID_CAJA');
    $cod_medio = oci_result($registros, 'CODIGO');
    $date_x = date("$fecini");
    $h = 65;
    $d = 65;
    $cont = 0;

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:'.$letter.$fila)->applyFromArray($estiloTitulos);

    if($fila > 3){
        if($cajaant <> $cajacomp) {
            $contcaja = 0;
            $date_y = date("$fecini");
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':'.$letter.$fila)->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':'.$letter.$fila)->getFont()->getColor()->setRGB('326CBA');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $fila . ':F' . $fila);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $fila, '');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $fila, '');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $fila, 'TOTAL CAJA:');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $fila, 'RD$ '.number_format($total_caja,'2',',','.'));

            for ($i = 72; $i < 72+$dias; $i++) {
                $letter = chr($i);
                if ($d <= 90) {
                    if ($letter > chr(90)) {
                        $letter = chr($h) . chr($d);
                        $d++;
                    }
                }
                if ($d > 90) {
                    $d = 65;
                    $h++;
                }
                $mod_date_y = strtotime($date_y . "+ $contcaja day");
                $dia_fecha_y = date("d/m/Y", $mod_date_y);

                $e = new Reportes();
                $registrosCaja = $e->seleccionaEntidadPuntoCaja($proyecto, $cod_entidad_ant, $dia_fecha_y, $puntoant, $cajaant);
                while (oci_fetch($registrosCaja)) {
                    $importe_dia_caja = oci_result($registrosCaja, 'IMPORTE_DIA');
                    $total_dia_caja += $importe_dia_caja;
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($letter . $fila, 'RD$ '.number_format($total_dia_caja,'2',',','.'));
                }oci_free_statement($registrosCaja);
                $total_dia_caja = 0;
                $contcaja++;

            }//fin for importe por caja y dia

            $fila++;
            $total_caja = 0;
        }
        if($puntoant <> $puntocomp) {
            $contpunto = 0;
            $date_y = date("$fecini");
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':'.$letter.$fila)->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':'.$letter.$fila)->getFont()->getColor()->setRGB('186619');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $fila . ':F' . $fila);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $fila, '');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $fila, 'TOTAL PUNTO:');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $fila, 'RD$ '.number_format($total_punto,'2',',','.'));

            for ($i = 72; $i < 72+$dias; $i++) {
                $letter = chr($i);
                if ($d <= 90) {
                    if ($letter > chr(90)) {
                        $letter = chr($h) . chr($d);
                        $d++;
                    }
                }
                if ($d > 90) {
                    $d = 65;
                    $h++;
                }
                $mod_date_y = strtotime($date_y . "+ $contpunto day");
                $dia_fecha_y = date("d/m/Y", $mod_date_y);

                $e = new Reportes();
                $registrosPunto = $e->seleccionaEntidadPunto($proyecto, $cod_entidad_ant, $dia_fecha_y, $puntoant);
                while (oci_fetch($registrosPunto)) {
                    $importe_dia_punto = oci_result($registrosPunto, 'IMPORTE_DIA');
                    $total_dia_punto += $importe_dia_punto;
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($letter . $fila, 'RD$ '.number_format($total_dia_punto,'2',',','.'));
                }oci_free_statement($registrosPunto);
                $total_dia_punto = 0;
                $contpunto++;

            }//fin for importe por punto y dia

            $fila++;
            $total_punto = 0;
        }
        if($entidadant <> $entidad) {
            $contentidad = 0;
            $date_y = date("$fecini");
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':'.$letter.$fila)->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':'.$letter.$fila)->getFont()->getColor()->setRGB('832B16');
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $fila . ':F' . $fila);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $fila, 'TOTAL ENTIDAD:');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $fila, 'RD$ '.number_format($total_entidad,'2',',','.'));

            for ($i = 72; $i < 72+$dias; $i++) {
                $letter = chr($i);
                if ($d <= 90) {
                    if ($letter > chr(90)) {
                        $letter = chr($h) . chr($d);
                        $d++;
                    }
                }
                if ($d > 90) {
                    $d = 65;
                    $h++;
                }
                $mod_date_y = strtotime($date_y . "+ $contentidad day");
                $dia_fecha_y = date("d/m/Y", $mod_date_y);

                $e = new Reportes();
                $registrosEntidad = $e->seleccionaEntidad($proyecto, $cod_entidad_ant, $dia_fecha_y);
                while (oci_fetch($registrosEntidad)) {
                    $importe_dia_entidad = oci_result($registrosEntidad, 'IMPORTE_DIA');
                    $total_dia_entidad += $importe_dia_entidad;
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($letter . $fila, 'RD$ '.number_format($total_dia_entidad,'2',',','.'));
                }oci_free_statement($registrosEntidad);
                //$total_general += $total_dia_entidad;
                $total_dia_entidad = 0;
                $contentidad++;

            }//fin for importe por punto y dia
            $fila++;
            $total_entidad = 0;
        }
    }

    if($entidadant <> $entidad ) {
        //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:A8');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $fila, $entidad);
        $entidadant = $entidad;
        $cod_entidad_ant = $cod_entidad;
    }
    if (($puntoant <> $puntocomp)) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $fila, $punto . ' - ' . $puntocomp);
        $puntoant = $puntocomp;
    }
    if (($cajaant <> $cajacomp)) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $fila, $caja . ' - ' . $cajacomp);
        $cajaant = $cajacomp;
    }

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $fila, $tipo);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $fila, $cod_medio.' - '.$medPago);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $fila, $numpagos);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $fila, 'RD$ '.number_format($importe,'2',',','.'));
    $total_caja += $importe;
    $total_punto += $importe;
    $total_entidad += $importe;

    for ($i = 72; $i < 72+$dias; $i++) {
        $letter = chr($i);
        if ($d <= 90) {
            if ($letter > chr(90)) {
                $letter = chr($h) . chr($d);
                $d++;
            }
        }
        if ($d > 90) {
            $d = 65;
            $h++;
        }
        $mod_date_x = strtotime($date_x . "+ $cont day");
        $dia_fecha_x = date("d/m/Y", $mod_date_x);

        $e = new Reportes();
        if ($tipo == 'PAGO') {
            $registrosDiaPago = $e->seleccionaPagosEntidadDia($proyecto, $cod_entidad, $dia_fecha_x, $puntocomp, $cod_caja, $cod_medio);
            while (oci_fetch($registrosDiaPago)) {
                $importe_dia_pago = oci_result($registrosDiaPago, 'IMPORTE_DIA');
                $total_caja_dia_pago += $importe_dia_pago;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($letter . $fila, 'RD$ '.number_format($total_caja_dia_pago,'2',',','.'));
            }oci_free_statement($registrosDiaPago);
            $total_caja_dia += $importe_dia_pago;
            $total_caja_dia_pago = 0;
        }
        $d = new Reportes();
        if ($tipo == 'OTRO RECAUDO') {
            $registrosDiaOtro = $d->seleccionaOtrosEntidadDia($proyecto, $cod_entidad, $dia_fecha_x, $puntocomp, $cod_caja, $cod_medio);
            while (oci_fetch($registrosDiaOtro)) {
                $importe_dia_otro = oci_result($registrosDiaOtro, 'VALOR_DIA');
                $total_caja_dia_otro += $importe_dia_otro;
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($letter . $fila, 'RD$ '.number_format($total_caja_dia_otro,'2',',','.'));
            }oci_free_statement($registrosDiaOtro);
            $total_caja_dia += $importe_dia_otro;
            $total_caja_dia_otro = 0;
        }

        $cont++;
    }//fin for importe por dia
    $fila++;

}oci_free_statement($registros);
if($fila > 3){
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:'.$letter.$fila)->applyFromArray($estiloCajas);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':'.$letter.$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':'.$letter.$fila)->getFont()->getColor()->setRGB('326CBA');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $fila . ':F' .  $fila);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $fila, '');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $fila, '');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $fila, 'TOTAL CAJA:');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $fila, 'RD$ '.number_format($total_caja,'2',',','.'));

    $contcaja = 0;
    $date_y = date("$fecini");
    for ($i = 72; $i < 72+$dias; $i++) {
        $letter = chr($i);
        if ($d <= 90) {
            if ($letter > chr(90)) {
                $letter = chr($h) . chr($d);
                $d++;
            }
        }
        if ($d > 90) {
            $d = 65;
            $h++;
        }
        $mod_date_y = strtotime($date_y . "+ $contcaja day");
        $dia_fecha_y = date("d/m/Y", $mod_date_y);

        $e = new Reportes();
        $registrosCaja = $e->seleccionaEntidadPuntoCaja($proyecto, $cod_entidad_ant, $dia_fecha_y, $puntoant, $cajaant);
        while (oci_fetch($registrosCaja)) {
            $importe_dia_caja = oci_result($registrosCaja, 'IMPORTE_DIA');
            $total_dia_caja += $importe_dia_caja;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($letter . $fila, 'RD$ '.number_format($total_dia_caja,'2',',','.'));
        }oci_free_statement($registrosCaja);
        $total_dia_caja = 0;
        $contcaja++;

    }//fin for importe por caja y dia
    $fila++;
    $total_caja = 0;

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:'.$letter.$fila)->applyFromArray($estiloCajas);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':'.$letter.$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':'.$letter.$fila)->getFont()->getColor()->setRGB('186619');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $fila . ':F' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $fila, '');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $fila, 'TOTAL PUNTO:');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $fila, 'RD$ '.number_format($total_punto,'2',',','.'));

    $contpunto = 0;
    $date_y = date("$fecini");
    for ($i = 72; $i < 72+$dias; $i++) {
        $letter = chr($i);
        if ($d <= 90) {
            if ($letter > chr(90)) {
                $letter = chr($h) . chr($d);
                $d++;
            }
        }
        if ($d > 90) {
            $d = 65;
            $h++;
        }
        $mod_date_y = strtotime($date_y . "+ $contpunto day");
        $dia_fecha_y = date("d/m/Y", $mod_date_y);

        $e = new Reportes();
        $registrosPunto = $e->seleccionaEntidadPunto($proyecto, $cod_entidad_ant, $dia_fecha_y, $puntoant);
        while (oci_fetch($registrosPunto)) {
            $importe_dia_punto = oci_result($registrosPunto, 'IMPORTE_DIA');
            $total_dia_punto += $importe_dia_punto;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($letter . $fila, 'RD$ '.number_format($total_dia_punto,'2',',','.'));
        }oci_free_statement($registrosPunto);
        $total_dia_punto = 0;
        $contpunto++;

    }//fin for importe por punto y dia

    $fila++;
    $total_punto = 0;

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:'.$letter.$fila)->applyFromArray($estiloCajas);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':'.$letter.$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':'.$letter.$fila)->getFont()->getColor()->setRGB('832B16');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $fila . ':F' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $fila, 'TOTAL ENTIDAD:');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $fila, 'RD$ '.number_format($total_entidad,'2',',','.'));

    $contentidad = 0;
    $date_y = date("$fecini");

    for ($i = 72; $i < 72+$dias; $i++) {
        $letter = chr($i);
        if ($d <= 90) {
            if ($letter > chr(90)) {
                $letter = chr($h) . chr($d);
                $d++;
            }
        }
        if ($d > 90) {
            $d = 65;
            $h++;
        }
        $mod_date_y = strtotime($date_y . "+ $contentidad day");
        $dia_fecha_y = date("d/m/Y", $mod_date_y);

        $e = new Reportes();
        $registrosEntidad = $e->seleccionaEntidad($proyecto, $cod_entidad_ant, $dia_fecha_y);
        while (oci_fetch($registrosEntidad)) {
            $importe_dia_entidad = oci_result($registrosEntidad, 'IMPORTE_DIA');
            $total_dia_entidad += $importe_dia_entidad;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($letter . $fila, 'RD$ '.number_format($total_dia_entidad,'2',',','.'));
        }oci_free_statement($registrosEntidad);
        $total_dia_entidad = 0;
        $contentidad++;

    }//fin for importe por punto y dia

    $fila++;
    $total_entidad = 0;
}


//$total_general += $total_dia_entidad;

$objPHPExcel->getActiveSheet()->setTitle('Pagos');
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch   = '../../temp/PagosPorFechaDetallado'. time() . '.xlsx';
$objWriter->save($nomarch);
echo $nomarch;