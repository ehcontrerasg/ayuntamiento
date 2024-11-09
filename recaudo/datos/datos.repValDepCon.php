<?php

/*
 *    Created by AMOSQUEA 27/11/2017.
 */
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/classPagos.php';

$proyecto = $_GET['proy'];
$concepto = $_GET['concepto'];
$periodo  = $_GET['periodo'];

if ($concepto == 11) {
    $cText = 'MANT MEDIDOR ';
    if ($proyecto == 'SD') {
        $cuenta = '160-111191-3';
    } else {
        $cuenta = '240-015993-3';
    }
} else if ($concepto == 20) {
    $cText = 'CORTE Y RECONEXION ';
    if ($proyecto == 'SD') {
        $cuenta = '160-106403-6';
    } else {
        $cuenta = '240-015994-1';
    }

}

$meses  = array('ENERO ', 'FEBRERO ', 'MARZO ', 'ABRIL ', 'MAYO ', 'JUNIO ', 'JULIO ', 'AGOSTO ', 'SEPTIEMBRE ', 'OCTUBRE ', 'NOVIEMBRE ', 'DICIEMBRE ');
$perMes = substr($periodo, 4, 2);
$mes    = $meses[$perMes - 1];
$year   = substr($periodo, 0, 4);

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo acea');
$objDrawing->setPath('../../images/logo_acea.png');
$objDrawing->setWidth(124);
$objDrawing->setHeight(82);

$objDrawing2 = new PHPExcel_Worksheet_Drawing();
$objDrawing2->setName('Logo');
if ($proyecto == 'SD') {
    $objDrawing2->setDescription('Logo CAASD');
    $objDrawing2->setPath('../../images/LogoCaasd.png');
    $objDrawing2->setWidth(60);
    $objDrawing2->setHeight(65);
    $cliente = 'CAASD';
} else {
    $objDrawing2->setDescription('Logo CORAABO');
    $objDrawing2->setPath('../../images/coraabo.jpg');
    $objDrawing2->setWidth(42);
    $objDrawing2->setHeight(55);
    $cliente = 'CORAABO';
}

$estiloTitulos = array(
    'borders'   => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => '000000'),
        ),
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    ),
);

$estiloTitulos2 = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    ),
);

$estiloTitulos3 = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    ),
);

$borderArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);
$estiloLineaAlta = array(
    'borders'   => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000'),
        ),
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    ),
);

$objPHPExcel = new PHPExcel();
$htmlHelper  = new \PHPExcel_Helper_HTML();

$objPHPExcel->getProperties()->setCreator("Aceasoft");
$objPHPExcel->getProperties()->setLastModifiedBy("Aaceasoft");
$objPHPExcel->getProperties()->setTitle("Reporte Valores Depositados Medidores");
$objPHPExcel->getProperties()->setSubject("Reporte");
$objPHPExcel->getProperties()->setDescription("Reporte general aceasoft");
$objDrawing->setCoordinates('B3');
$objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));

$objPHPExcel->setActiveSheetIndex(0)->getStyle("B2:N60")->getFont()
    ->setName('Tahoma')
    ->setSize(10);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B11:D11")->getFont()
    ->setName('Tahoma')
    ->setSize(11);

$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('E2', 'VALORES DEPOSITADOS');

$html      = "<b>C&oacute;digo:</b> FO-REC-13";
$rich_text = $htmlHelper->toRichTextObject($html);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('J2', $rich_text);

$html      = "<b>Edici&oacute;n No.:</b> 02";
$rich_text = $htmlHelper->toRichTextObject($html);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('J4', $rich_text);

$html      = "<b>Fecha de emisi&oacute;n:</b> 04-03-2016";
$rich_text = $htmlHelper->toRichTextObject($html);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('J6', $rich_text);

$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B10', 'VALORES DEPOSITADOS POR:');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F10', $cText . $mes . $year);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B11', 'Cuenta No.:');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D11', $cuenta);

$objDrawing2->setCoordinates('L9');
$objDrawing2->setWorksheet($objPHPExcel->setActiveSheetIndex(0));

$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B13', 'FECHA');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D13', 'REPORTE');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F13', 'FECHA DEPOSITO');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H13', 'COMPROBANTE');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('J13', 'MONTO');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('N13', 'DIFERENCIA');

//    EXTRAEMOS LOS DATOS SOBRE LOS PAGOS RECAUDADOS

$p        = new Pagos();
$pagos    = $p->getDataDeposito($periodo, $concepto, $proyecto);
$fila     = 15;
$totalRec = 0;
$verifDia = '';
$recaudo2 = 0;

while (oci_fetch($pagos)) {
    $fecha       = oci_result($pagos, 'FECHA_PAGO');
    $recaudo     = oci_result($pagos, 'TOTAL');
    $fechaD      = oci_result($pagos, 'FECHA_DEPOSITO');
    $comprobante = oci_result($pagos, 'COMPROBANTE');
    $monto       = oci_result($pagos, 'MONTO_DEPOSITO');

    $v        = new Pagos();
    $verifDia = $v->verificaDiaFestivo($fecha);
    $date     = strtotime($verifDia);
    $newFecha = date('d/m/Y', $date);
    //echo $newFecha . '==' . $fecha . ' ';
    if ($newFecha == $fecha) {
        $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B' . $fila, $fecha);
        $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D' . $fila, $recaudo);
        $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F' . $fila, $fechaD);
        $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H' . $fila, $comprobante);
        $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('J' . $fila, $monto);
        if ($monto == $recaudo) {
            $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('N' . $fila, '-');
        } else {
            $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('N' . $fila, '=J' . $fila . '-D' . $fila);
        }

        $fila++;
    }

    //$totalRec += $recaudo;

}
oci_free_statement($pagos);

$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B43', 'TOTAL');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H43', 'TOTAL');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D43', '=SUM(D15:D41)');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('J43', '=SUM(J15:J41)');

if ($proyecto == 'SD') {
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B45', 'MEMO PARA:');
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B47', 'Factura');
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('K47', 'Monto RD$');

    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B48', 'A');
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B49', 'B');
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B50', 'C');
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B51', 'B-C');

    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C48', 'Sistema');
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C49', 'Depositado auditado');
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C50', 'Cortes realizados antes de ago. 2007');
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C51', 'Monto corte  sobre el cual ACEA factura a CAASD');

    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('K48', '=+D43');
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('K49', '=+J43');
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('K50', '=+K55');
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('K51', '=K49-K50');

    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C53', 'Monto facturado a CAASD 90%');
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C55', 'Codigo cortes realizados antes ago. 2007');
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('K53', '=K51*90%');

    $p2  = new Pagos();
    $rca = $p2->recPorCorAnt($periodo, $concepto, $proyecto);

    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('K55', $rca);
}

$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F59', 'Revisado-' . $cliente);

// APLICA LAS DIMENSIONES A LAS COLUMNAS
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(3.01);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(13.86);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(2.86);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(12.15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(4.43);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(16.29);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(3.29);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(22.29);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(3.58);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(2.43);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(3.01);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(12.72);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(6.15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(13.86);

// APLICA LAS DIMENSIONES DE LAS FILAS
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(12.75);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(12.75);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(12.75);
$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(12.75);
$objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(12.75);
$objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(12.75);
$objPHPExcel->getActiveSheet()->getRowDimension('7')->setRowHeight(15.75);
$objPHPExcel->getActiveSheet()->getRowDimension('8')->setRowHeight(17.25);
$objPHPExcel->getActiveSheet()->getRowDimension('9')->setRowHeight(15);
$objPHPExcel->getActiveSheet()->getRowDimension('10')->setRowHeight(16.5);
$objPHPExcel->getActiveSheet()->getRowDimension('11')->setRowHeight(15.75);
for ($i = 12; $i <= 58; $i++) {
    $objPHPExcel->getActiveSheet()->getRowDimension("$i")->setRowHeight(12.75);
}

//  FUENTES EN BOLD
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B10:G11')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('E2')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B13:N13')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B43')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('H43')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('F59:K59')->getFont()->setBold(true);

if ($proyecto == 'SD') {
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B45')->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B47')->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('K47')->getFont()->setBold(true);
}

// Colores de las celdas
cellColor($objPHPExcel, 'B10:E10', 'CCCCFF');
cellColor($objPHPExcel, 'B11:C11', '99CCFF');
cellColor($objPHPExcel, 'B13', '99CCFF');
cellColor($objPHPExcel, 'D13', '99CCFF');
cellColor($objPHPExcel, 'F13', '99CCFF');
cellColor($objPHPExcel, 'H13', '99CCFF');
cellColor($objPHPExcel, 'J13:L13', '99CCFF');
cellColor($objPHPExcel, 'N13', '99CCFF');

//    ESTILOS DIVERSOS
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B2:N60')->applyFromArray(
    array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        ),
    )
);

$objPHPExcel->getActiveSheet()->getStyle('D15:D43')->getNumberFormat()->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle('J15:J43')->getNumberFormat()->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle('J47:L55')->getNumberFormat()->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle('J15:L42')->getNumberFormat()->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle('N14:N41')->getNumberFormat()->setFormatCode('#,##0.00');

$objPHPExcel->setActiveSheetIndex(0)->getStyle('J2')->applyFromArray($estiloTitulos2);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('J4')->applyFromArray($estiloTitulos2);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('J6')->applyFromArray($estiloTitulos2);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B11')->applyFromArray($estiloTitulos2);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('D15:D42')->applyFromArray($estiloTitulos3);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('J15:J42')->applyFromArray($estiloTitulos3);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('N15:N41')->applyFromArray($estiloTitulos3);

if ($proyecto == 'SD') {
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B44')->applyFromArray($estiloTitulos3);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B45:J45')->applyFromArray($borderArray);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B47')->applyFromArray($borderArray);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('K47:L47')->applyFromArray($borderArray);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B48:L51')->applyFromArray($borderArray);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B48:J55')->applyFromArray($estiloTitulos2);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('C53:L53')->applyFromArray($borderArray);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('C55:L55')->applyFromArray($borderArray);
}

$objPHPExcel->setActiveSheetIndex(0)->getStyle('F59:K59')->applyFromArray($estiloLineaAlta);
$objPHPExcel->getActiveSheet(0)->getStyle('J6')->getAlignment()->setWrapText(true);

$objPHPExcel->setActiveSheetIndex(0)->getStyle('B2:L7')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B10:H10')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B11:F11')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('J9:L11')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B15:B41')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('D15:D41')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('F15:F41')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('H15:H41')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('J15:L41')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('N15:N41')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B13')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('D13')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('F13')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('H13')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('J13:L13')->applyFromArray($borderArray);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('N13')->applyFromArray($borderArray);

$objPHPExcel->setActiveSheetIndex(0)->getStyle('D43')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('J43:L43')->applyFromArray($estiloTitulos);

//    COMBINA LAS CELDAS
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:D7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E2:I7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J2:L3');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J4:L5');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J6:L7');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B10:E10');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F10:H10');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B11:C11');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D11:F11');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J9:L11');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J13:L13');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J43:L43');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F59:K59');

if ($proyecto == 'SD') {
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C45:J45');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C48:J48');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C49:J49');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C50:J50');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C51:J51');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C53:J53');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C55:J55');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K47:L47');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K48:L48');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K49:L49');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K50:L50');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K51:L51');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K53:L53');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K55:L55');
}

for ($i = 15; $i < 42; $i++) {
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J' . $i . ':' . 'L' . $i);
}

//    CREA EL ARCHIVO GENERADO CON EXTENSION .xlsx
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch   = "../../temp/ValoresDepositadosPor-" . str_replace(' ', '', $cText) . '-' . $proyecto . '-' . $periodo . ".xlsx";

$objWriter->save($nomarch);
echo $nomarch;

function cellColor($obj, $cells, $color)
{

    $obj->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type'       => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
            'rgb' => $color,
        ),
    ));
}
