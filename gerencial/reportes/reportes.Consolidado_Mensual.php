<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.repConsolidado.php';
include '../clases/class.repHisFac.php';
include '../clases/class.reportes_gerenciales.php';
ini_set('memory_limit', '-1');
$periodo=$_POST['periodo'];
$proyecto=$_POST['proyecto'];

$ano = substr($periodo,0,4);
$mes = substr($periodo,4,2);

/*if ($mes == '01') {$mesl = 'Ene.'; $meslan = 'Dic.';}
if ($mes == '02') $mesl = 'Feb.';
if ($mes == '03') $mesl = 'Mar.';if ($mes == '04') $mesl = 'Abr.';
if ($mes == '05') $mesl = 'May.';if ($mes == '06') $mesl = 'Jun.';
if ($mes == '07') $mesl = 'Jul.';if ($mes == '08') $mesl = 'Ago.';
if ($mes == '09') $mesl = 'Sep.';if ($mes == '10') $mesl = 'Oct.';
if ($mes == '11') $mesl = 'Nov.';if ($mes == '12') $mesl = 'Dic.';*/

if($mes == '01') $perini = ($ano-1).'12';
else $perini = $periodo -1;
$perfin = $periodo;

$v=new ReportesHisFac();
$registrosv=$v->verificaCierreZonas($proyecto, $periodo);
while (oci_fetch($registrosv)) {
    $cantidad=utf8_decode(oci_result($registrosv,"CANTIDAD"));
}oci_free_statement($registrosv);


$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Consolidado Mensual ".$proyecto." ".$perfin)
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Gerenciales");

$estiloTitulos = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => '000000')
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'   =>PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$estiloSubTitulos = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'   =>PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$estiloCuerpo = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        'vertical'   =>PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$estiloLaterales = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        'vertical'   =>PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$estiloLateralesSector = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'   =>PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$estiloLineaLateral = array(
    'borders' => array(
        'left' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => '000000')
        ),
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => '000000')
        )
    )
);

$estiloNoLineas = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => 'FFFFFF')
        )
    )
);

if($cantidad == 0) {
    //HOJA FACTURACION Y RECAUDO

    //DATOS CONCEPTO

    //Zona Norte
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:K2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B3:D3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F3:H3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J3:K3');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:K2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B3:D3')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('F3:H3')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('J3:K3')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A4:K4')->applyFromArray($estiloSubTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B5:K11')->applyFromArray($estiloCuerpo);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A5:A11')->applyFromArray($estiloLaterales);

    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:K2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A3:K3")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A4:K4")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'FACTURACIÓN Y RECAUDACIÓN POR CONCEPTO - ZONA NORTE ' . $perfin)
        ->setCellValue('B3', 'FACTURACIÓN')
        ->setCellValue('F3', 'RECAUDACIÓN')
        ->setCellValue('J3', 'PORCENTAJE RECAUDO')
        ->setCellValue('A4', 'Concepto')
        ->setCellValue('B4', $perini . ' RD$')
        ->setCellValue('C4', $perfin . ' RD$')
        ->setCellValue('D4', 'Variac. %')
        ->setCellValue('F4', $perini . ' RD$')
        ->setCellValue('G4', $perfin . ' RD$')
        ->setCellValue('H4', 'Variac. %')
        ->setCellValue('J4', $perini . ' %')
        ->setCellValue('K4', $perfin . ' %');
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(2);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(2);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(12);

    $fila = 5;
    $c = new ReportesConMen();
    $registros = $c->FacturacionNorteConceptoAgr($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $concepto = utf8_decode(oci_result($registros, "CONCEPTO"));
        $cant1fn = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2fn = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionfn = round((($cant2fn - $cant1fn) / $cant1fn) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $concepto)
            ->setCellValue('B' . $fila, $cant1fn)
            ->setCellValue('C' . $fila, $cant2fn)
            ->setCellValue('D' . $fila, $variacionfn);
        $fila++;
        $totalperinifn += $cant1fn;
        $totalperfinfn += $cant2fn;
        $totalvariafn = round((($totalperfinfn - $totalperinifn) / $totalperinifn) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":D" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, 'Total')
        ->setCellValue('B' . $fila, $totalperinifn)
        ->setCellValue('C' . $fila, $totalperfinfn)
        ->setCellValue('D' . $fila, $totalvariafn);

    $fila = 5;
    $c = new ReportesConMen();
    $registros = $c->RecaudacionNorteConceptoAgr($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $concepto = utf8_decode(oci_result($registros, "CONCEPTO"));
        $cant1rn = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2rn = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionrn = round((($cant2rn - $cant1rn) / $cant1rn) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F' . $fila, $cant1rn)
            ->setCellValue('G' . $fila, $cant2rn)
            ->setCellValue('H' . $fila, $variacionrn);
        $fila++;
        $totalperinirn += $cant1rn;
        $totalperfinrn += $cant2rn;
        $totalvariarn = round((($totalperfinrn - $totalperinirn) / $totalperinirn) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("F" . $fila . ":K" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F' . $fila, $totalperinirn)
        ->setCellValue('G' . $fila, $totalperfinrn)
        ->setCellValue('H' . $fila, $totalvariarn);

    for ($fila = 5; $fila <= 11; $fila++) {
        $val_recaudo_ini = $objPHPExcel->getActiveSheet()->getCell('F' . $fila)->getValue();
        $val_factura_ini = $objPHPExcel->getActiveSheet()->getCell('B' . $fila)->getValue();
        $porcentaje_rec_ini = $val_recaudo_ini / $val_factura_ini;
        $val_recaudo_fin = $objPHPExcel->getActiveSheet()->getCell('G' . $fila)->getValue();
        $val_factura_fin = $objPHPExcel->getActiveSheet()->getCell('C' . $fila)->getValue();
        $porcentaje_rec_fin = $val_recaudo_fin / $val_factura_fin;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J' . $fila, round($porcentaje_rec_ini * 100, 1))
            ->setCellValue('K' . $fila, round($porcentaje_rec_fin * 100, 1));
    }

    //Zona Este
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A13:K14');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B15:D15');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F15:H15');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J15:K15');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A13:K14')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B15:D15')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('F15:H15')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('J15:K15')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A16:K16')->applyFromArray($estiloSubTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B17:K23')->applyFromArray($estiloCuerpo);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A17:A23')->applyFromArray($estiloLaterales);

    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A13:K14")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A15:K15")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A16:K16")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A13', 'FACTURACIÓN Y RECAUDACIÓN POR CONCEPTO - ZONA ESTE ' . $perfin)
        ->setCellValue('B15', 'FACTURACIÓN')
        ->setCellValue('F15', 'RECAUDACIÓN')
        ->setCellValue('J15', 'PORCENTAJE RECAUDO')
        ->setCellValue('A16', 'Concepto')
        ->setCellValue('B16', $perini . ' RD$')
        ->setCellValue('C16', $perfin . ' RD$')
        ->setCellValue('D16', 'Variac. %')
        ->setCellValue('F16', $perini . ' RD$')
        ->setCellValue('G16', $perfin . ' RD$')
        ->setCellValue('H16', 'Variac. %')
        ->setCellValue('J16', $perini . ' %')
        ->setCellValue('K16', $perfin . ' %');


    $fila = 17;
    $c = new ReportesConMen();
    $registros = $c->FacturacionEsteConceptoAgr($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $concepto = utf8_decode(oci_result($registros, "CONCEPTO"));
        $cant1fe = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2fe = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionfe = round((($cant2fe - $cant1fe) / $cant1fe) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $concepto)
            ->setCellValue('B' . $fila, $cant1fe)
            ->setCellValue('C' . $fila, $cant2fe)
            ->setCellValue('D' . $fila, $variacionfe);
        $fila++;
        $totalperinife += $cant1fe;
        $totalperfinfe += $cant2fe;
        $totalvariafe = round((($totalperfinfe - $totalperinife) / $totalperinife) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":D" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, 'Total')
        ->setCellValue('B' . $fila, $totalperinife)
        ->setCellValue('C' . $fila, $totalperfinfe)
        ->setCellValue('D' . $fila, $totalvariafe);

    $fila = 17;
    $c = new ReportesConMen();
    $registros = $c->RecaudacionEsteConceptoAgr($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $concepto = utf8_decode(oci_result($registros, "CONCEPTO"));
        $cant1re = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2re = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionre = round((($cant2re - $cant1re) / $cant1re) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F' . $fila, $cant1re)
            ->setCellValue('G' . $fila, $cant2re)
            ->setCellValue('H' . $fila, $variacionre);
        $fila++;
        $totalperinire += $cant1re;
        $totalperfinre += $cant2re;
        $totalvariare = round((($totalperfinre - $totalperinire) / $totalperinire) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("F" . $fila . ":K" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F' . $fila, $totalperinire)
        ->setCellValue('G' . $fila, $totalperfinre)
        ->setCellValue('H' . $fila, $totalvariare);

    for ($fila = 17; $fila <= 23; $fila++) {
        $val_recaudo_ini = $objPHPExcel->getActiveSheet()->getCell('F' . $fila)->getValue();
        $val_factura_ini = $objPHPExcel->getActiveSheet()->getCell('B' . $fila)->getValue();
        $porcentaje_rec_ini = $val_recaudo_ini / $val_factura_ini;
        $val_recaudo_fin = $objPHPExcel->getActiveSheet()->getCell('G' . $fila)->getValue();
        $val_factura_fin = $objPHPExcel->getActiveSheet()->getCell('C' . $fila)->getValue();
        $porcentaje_rec_fin = $val_recaudo_fin / $val_factura_fin;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J' . $fila, round($porcentaje_rec_ini * 100, 1))
            ->setCellValue('K' . $fila, round($porcentaje_rec_fin * 100, 1));
    }

    //Gerencia Oriental Total
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A25:K26');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B27:D27');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F27:H27');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J27:K27');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A25:K26')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B27:D27')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('F27:H27')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('J27:K27')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A28:K28')->applyFromArray($estiloSubTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B29:K35')->applyFromArray($estiloCuerpo);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A29:A35')->applyFromArray($estiloLaterales);

    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A25:K26")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A27:K27")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A28:K28")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A25', 'FACTURACIÓN Y RECAUDACIÓN POR CONCEPTO - GERENCIA ORIENTAL ' . $perfin)
        ->setCellValue('B27', 'FACTURACIÓN')
        ->setCellValue('F27', 'RECAUDACIÓN')
        ->setCellValue('J27', 'PORCENTAJE RECAUDO')
        ->setCellValue('A28', 'Concepto')
        ->setCellValue('B28', $perini . ' RD$')
        ->setCellValue('C28', $perfin . ' RD$')
        ->setCellValue('D28', 'Variac. %')
        ->setCellValue('F28', $perini . ' RD$')
        ->setCellValue('G28', $perfin . ' RD$')
        ->setCellValue('H28', 'Variac. %')
        ->setCellValue('J28', $perini . ' %')
        ->setCellValue('K28', $perfin . ' %');


    $fila = 29;
    $c = new ReportesConMen();
    $registros = $c->FacturacionTotalConceptoAgr($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $concepto = utf8_decode(oci_result($registros, "CONCEPTO"));
        $cant1ft = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2ft = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionft = round((($cant2ft - $cant1ft) / $cant1ft) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $concepto)
            ->setCellValue('B' . $fila, $cant1ft)
            ->setCellValue('C' . $fila, $cant2ft)
            ->setCellValue('D' . $fila, $variacionft);
        $fila++;
        $totalperinift += $cant1ft;
        $totalperfinft += $cant2ft;
        $totalvariaft = round((($totalperfinft - $totalperinift) / $totalperinift) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":D" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, 'Total')
        ->setCellValue('B' . $fila, $totalperinift)
        ->setCellValue('C' . $fila, $totalperfinft)
        ->setCellValue('D' . $fila, $totalvariaft);

    $fila = 29;
    $c = new ReportesConMen();
    $registros = $c->RecaudacionTotalConceptoAgr($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $concepto = utf8_decode(oci_result($registros, "CONCEPTO"));
        $cant1rt = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2rt = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionrt = round((($cant2rt - $cant1rt) / $cant1rt) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F' . $fila, $cant1rt)
            ->setCellValue('G' . $fila, $cant2rt)
            ->setCellValue('H' . $fila, $variacionrt);
        $fila++;
        $totalperinirt += $cant1rt;
        $totalperfinrt += $cant2rt;
        $totalvariart = round((($totalperfinrt - $totalperinirt) / $totalperinirt) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("F" . $fila . ":K" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F' . $fila, $totalperinirt)
        ->setCellValue('G' . $fila, $totalperfinrt)
        ->setCellValue('H' . $fila, $totalvariart);

    for ($fila = 29; $fila <= 35; $fila++) {
        $val_recaudo_ini = $objPHPExcel->getActiveSheet()->getCell('F' . $fila)->getValue();
        $val_factura_ini = $objPHPExcel->getActiveSheet()->getCell('B' . $fila)->getValue();
        $porcentaje_rec_ini = $val_recaudo_ini / $val_factura_ini;
        $val_recaudo_fin = $objPHPExcel->getActiveSheet()->getCell('G' . $fila)->getValue();
        $val_factura_fin = $objPHPExcel->getActiveSheet()->getCell('C' . $fila)->getValue();
        $porcentaje_rec_fin = $val_recaudo_fin / $val_factura_fin;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J' . $fila, round($porcentaje_rec_ini * 100, 1))
            ->setCellValue('K' . $fila, round($porcentaje_rec_fin * 100, 1));
    }

    //DATOS USO

    //Zona Norte

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A37:V38');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B39:D39');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F39:H39');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J39:K39');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('M39:O39');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Q39:S39');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('U39:V39');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A37:V38')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B39:V39')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A40:V40')->applyFromArray($estiloSubTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B41:V51')->applyFromArray($estiloCuerpo);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A41:A51')->applyFromArray($estiloLaterales);

    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A37:V39")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A40:V40")->getFont()->setBold(true);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A37', 'FACTURACIÓN Y RECAUDACIÓN POR CATEGORIA DE USUARIOS- ZONA NORTE ' . $perfin)
        ->setCellValue('B39', 'FACTURACIÓN')
        ->setCellValue('F39', 'RECAUDACIÓN')
        ->setCellValue('J39', 'PORCENTAJE RECAUDO')
        ->setCellValue('M39', 'NUMERO DE FACTURAS EMITIDAS')
        ->setCellValue('Q39', 'NUMERO DE PAGOS RECIBIDOS')
        ->setCellValue('U39', 'PORCENTAJE PAGOS')
        ->setCellValue('A40', 'Categoria de Usuarios')
        ->setCellValue('B40', $perini . ' RD$')
        ->setCellValue('C40', $perfin . ' RD$')
        ->setCellValue('D40', 'Variac. %')
        ->setCellValue('F40', $perini . ' RD$')
        ->setCellValue('G40', $perfin . ' RD$')
        ->setCellValue('H40', 'Variac. %')
        ->setCellValue('J40', $perini . ' %')
        ->setCellValue('K40', $perfin . ' %')
        ->setCellValue('M40', $perini)
        ->setCellValue('N40', $perfin)
        ->setCellValue('O40', 'Variac. %')
        ->setCellValue('Q40', $perini)
        ->setCellValue('R40', $perfin)
        ->setCellValue('S40', 'Variac. %')
        ->setCellValue('U40', $perini . ' %')
        ->setCellValue('V40', $perfin . ' %');

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(2);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(2);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Q")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("R")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("S")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("T")->setWidth(2);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("U")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("V")->setWidth(12);

    $fila = 41;
    $c = new ReportesConMen();
    $registros = $c->FacturacionNorteUso($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $uso = utf8_decode(oci_result($registros, "USO"));
        $cant1fun = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2fun = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionfun = round((($cant2fun - $cant1fun) / $cant1fun) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $uso)
            ->setCellValue('B' . $fila, $cant1fun)
            ->setCellValue('C' . $fila, $cant2fun)
            ->setCellValue('D' . $fila, $variacionfun);
        $fila++;
        $totalperinifun += $cant1fun;
        $totalperfinfun += $cant2fun;
        $totalvariafun = round((($totalperfinfun - $totalperinifun) / $totalperinifun) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":D" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, 'Total')
        ->setCellValue('B' . $fila, $totalperinifun)
        ->setCellValue('C' . $fila, $totalperfinfun)
        ->setCellValue('D' . $fila, $totalvariafun);

    $fila = 41;
    $c = new ReportesConMen();
    $registros = $c->RecaudacionNorteUso($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $uso = utf8_decode(oci_result($registros, "USO"));
        $cant1run = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2run = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionrun = round((($cant2run - $cant1run) / $cant1run) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F' . $fila, $cant1run)
            ->setCellValue('G' . $fila, $cant2run)
            ->setCellValue('H' . $fila, $variacionrun);
        $fila++;
        $totalperinirun += $cant1run;
        $totalperfinrun += $cant2run;
        $totalvariarun = round((($totalperfinrun - $totalperinirun) / $totalperinirun) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("F" . $fila . ":K" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F' . $fila, $totalperinirun)
        ->setCellValue('G' . $fila, $totalperfinrun)
        ->setCellValue('H' . $fila, $totalvariarun);

    for ($fila = 41; $fila <= 49; $fila++) {
        $val_recaudo_uso_ini = $objPHPExcel->getActiveSheet()->getCell('F' . $fila)->getValue();
        $val_factura_uso_ini = $objPHPExcel->getActiveSheet()->getCell('B' . $fila)->getValue();
        $porcentaje_rec_uso_ini = $val_recaudo_uso_ini / $val_factura_uso_ini;
        $val_recaudo_uso_fin = $objPHPExcel->getActiveSheet()->getCell('G' . $fila)->getValue();
        $val_factura_uso_fin = $objPHPExcel->getActiveSheet()->getCell('C' . $fila)->getValue();
        $porcentaje_rec_uso_fin = $val_recaudo_uso_fin / $val_factura_uso_fin;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J' . $fila, round($porcentaje_rec_uso_ini * 100, 1))
            ->setCellValue('K' . $fila, round($porcentaje_rec_uso_fin * 100, 1));
    }

    $fila = 41;
    $c = new ReportesConMen();
    $registros = $c->FacturasNorteUso($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $cant1faun = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2faun = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionfaun = round((($cant2faun - $cant1faun) / $cant1faun) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('M' . $fila, $cant1faun)
            ->setCellValue('N' . $fila, $cant2faun)
            ->setCellValue('O' . $fila, $variacionfaun);
        $fila++;
        $totalperinifaun += $cant1faun;
        $totalperfinfaun += $cant2faun;
        $totalvariafaun = round((($totalperfinfaun - $totalperinifaun) / $totalperinifaun) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("M" . $fila . ":O" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('M' . $fila, $totalperinifaun)
        ->setCellValue('N' . $fila, $totalperfinfaun)
        ->setCellValue('O' . $fila, $totalvariafaun);

    $fila = 41;
    $c = new ReportesConMen();
    $registros = $c->PagosNorteUso($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $cant1paun = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2paun = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionpaun = round((($cant2paun - $cant1paun) / $cant1paun) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q' . $fila, $cant1paun)
            ->setCellValue('R' . $fila, $cant2paun)
            ->setCellValue('S' . $fila, $variacionpaun);
        $fila++;
        $totalperinipaun += $cant1paun;
        $totalperfinpaun += $cant2paun;
        $totalvariapaun = round((($totalperfinpaun - $totalperinipaun) / $totalperinipaun) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("Q" . $fila . ":V" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('Q' . $fila, $totalperinipaun)
        ->setCellValue('R' . $fila, $totalperfinpaun)
        ->setCellValue('S' . $fila, $totalvariapaun);

    for ($fila = 41; $fila <= 49; $fila++) {
        $val_recaudo_uso_ini = $objPHPExcel->getActiveSheet()->getCell('Q' . $fila)->getValue();
        $val_factura_uso_ini = $objPHPExcel->getActiveSheet()->getCell('M' . $fila)->getValue();
        $porcentaje_rec_uso_ini = $val_recaudo_uso_ini / $val_factura_uso_ini;
        $val_recaudo_uso_fin = $objPHPExcel->getActiveSheet()->getCell('R' . $fila)->getValue();
        $val_factura_uso_fin = $objPHPExcel->getActiveSheet()->getCell('N' . $fila)->getValue();
        $porcentaje_rec_uso_fin = $val_recaudo_uso_fin / $val_factura_uso_fin;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('U' . $fila, round($porcentaje_rec_uso_ini * 100, 1))
            ->setCellValue('V' . $fila, round($porcentaje_rec_uso_fin * 100, 1));
    }

    //Zona Este

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A53:V54');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B55:D55');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F55:H55');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J55:K55');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('M55:O55');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Q55:S55');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('U55:V55');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A53:V54')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B55:V55')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A56:V56')->applyFromArray($estiloSubTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B57:V67')->applyFromArray($estiloCuerpo);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A57:A67')->applyFromArray($estiloLaterales);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A53:V55")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A56:V56")->getFont()->setBold(true);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A53', 'FACTURACIÓN Y RECAUDACIÓN POR CATEGORIA DE USUARIOS- ZONA ESTE ' . $perfin)
        ->setCellValue('B55', 'FACTURACIÓN')
        ->setCellValue('F55', 'RECAUDACIÓN')
        ->setCellValue('J55', 'PORCENTAJE RECAUDO')
        ->setCellValue('M55', 'NUMERO DE FACTURAS EMITIDAS')
        ->setCellValue('Q55', 'NUMERO DE PAGOS RECIBIDOS')
        ->setCellValue('U55', 'PORCENTAJE PAGOS')
        ->setCellValue('A56', 'Categoria de Usuarios')
        ->setCellValue('B56', $perini . ' RD$')
        ->setCellValue('C56', $perfin . ' RD$')
        ->setCellValue('D56', 'Variac. %')
        ->setCellValue('F56', $perini . ' RD$')
        ->setCellValue('G56', $perfin . ' RD$')
        ->setCellValue('H56', 'Variac. %')
        ->setCellValue('J56', $perini . ' %')
        ->setCellValue('K56', $perfin . ' %')
        ->setCellValue('M56', $perini)
        ->setCellValue('N56', $perfin)
        ->setCellValue('O56', 'Variac. %')
        ->setCellValue('Q56', $perini)
        ->setCellValue('R56', $perfin)
        ->setCellValue('S56', 'Variac. %')
        ->setCellValue('U56', $perini . ' %')
        ->setCellValue('V56', $perfin . ' %');

    $fila = 57;
    $c = new ReportesConMen();
    $registros = $c->FacturacionEsteUso($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $uso = utf8_decode(oci_result($registros, "USO"));
        $cant1fue = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2fue = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionfue = round((($cant2fue - $cant1fue) / $cant1fue) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $uso)
            ->setCellValue('B' . $fila, $cant1fue)
            ->setCellValue('C' . $fila, $cant2fue)
            ->setCellValue('D' . $fila, $variacionfue);
        $fila++;
        $totalperinifue += $cant1fue;
        $totalperfinfue += $cant2fue;
        $totalvariafue = round((($totalperfinfue - $totalperinifue) / $totalperinifue) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":D" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, 'Total')
        ->setCellValue('B' . $fila, $totalperinifue)
        ->setCellValue('C' . $fila, $totalperfinfue)
        ->setCellValue('D' . $fila, $totalvariafue);

    $fila = 57;
    $c = new ReportesConMen();
    $registros = $c->RecaudacionEsteUso($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $uso = utf8_decode(oci_result($registros, "USO"));
        $cant1rue = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2rue = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionrue = round((($cant2rue - $cant1rue) / $cant1rue) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F' . $fila, $cant1rue)
            ->setCellValue('G' . $fila, $cant2rue)
            ->setCellValue('H' . $fila, $variacionrue);
        $fila++;
        $totalperinirue += $cant1rue;
        $totalperfinrue += $cant2rue;
        $totalvariarue = round((($totalperfinrue - $totalperinirue) / $totalperinirue) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("F" . $fila . ":K" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F' . $fila, $totalperinirue)
        ->setCellValue('G' . $fila, $totalperfinrue)
        ->setCellValue('H' . $fila, $totalvariarue);

    for ($fila = 57; $fila <= 65; $fila++) {
        $val_recaudo_uso_ini = $objPHPExcel->getActiveSheet()->getCell('F' . $fila)->getValue();
        $val_factura_uso_ini = $objPHPExcel->getActiveSheet()->getCell('B' . $fila)->getValue();
        $porcentaje_rec_uso_ini = $val_recaudo_uso_ini / $val_factura_uso_ini;
        $val_recaudo_uso_fin = $objPHPExcel->getActiveSheet()->getCell('G' . $fila)->getValue();
        $val_factura_uso_fin = $objPHPExcel->getActiveSheet()->getCell('C' . $fila)->getValue();
        $porcentaje_rec_uso_fin = $val_recaudo_uso_fin / $val_factura_uso_fin;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J' . $fila, round($porcentaje_rec_uso_ini * 100, 1))
            ->setCellValue('K' . $fila, round($porcentaje_rec_uso_fin * 100, 1));
    }

    $fila = 57;
    $c = new ReportesConMen();
    $registros = $c->FacturasEsteUso($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $cant1faue = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2faue = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionfaue = round((($cant2faue - $cant1faue) / $cant1faue) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('M' . $fila, $cant1faue)
            ->setCellValue('N' . $fila, $cant2faue)
            ->setCellValue('O' . $fila, $variacionfaue);
        $fila++;
        $totalperinifaue += $cant1faue;
        $totalperfinfaue += $cant2faue;
        $totalvariafaue = round((($totalperfinfaue - $totalperinifaue) / $totalperinifaue) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("M" . $fila . ":O" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('M' . $fila, $totalperinifaue)
        ->setCellValue('N' . $fila, $totalperfinfaue)
        ->setCellValue('O' . $fila, $totalvariafaue);

    $fila = 57;
    $c = new ReportesConMen();
    $registros = $c->PagosEsteUso($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $cant1paue = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2paue = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionpaue = round((($cant2paue - $cant1paue) / $cant1paue) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q' . $fila, $cant1paue)
            ->setCellValue('R' . $fila, $cant2paue)
            ->setCellValue('S' . $fila, $variacionpaue);
        $fila++;
        $totalperinipaue += $cant1paue;
        $totalperfinpaue += $cant2paue;
        $totalvariapaue = round((($totalperfinpaue - $totalperinipaue) / $totalperinipaue) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("Q" . $fila . ":V" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('Q' . $fila, $totalperinipaue)
        ->setCellValue('R' . $fila, $totalperfinpaue)
        ->setCellValue('S' . $fila, $totalvariapaue);

    for ($fila = 57; $fila <= 65; $fila++) {
        $val_recaudo_uso_ini = $objPHPExcel->getActiveSheet()->getCell('Q' . $fila)->getValue();
        $val_factura_uso_ini = $objPHPExcel->getActiveSheet()->getCell('M' . $fila)->getValue();
        $porcentaje_rec_uso_ini = $val_recaudo_uso_ini / $val_factura_uso_ini;
        $val_recaudo_uso_fin = $objPHPExcel->getActiveSheet()->getCell('R' . $fila)->getValue();
        $val_factura_uso_fin = $objPHPExcel->getActiveSheet()->getCell('N' . $fila)->getValue();
        $porcentaje_rec_uso_fin = $val_recaudo_uso_fin / $val_factura_uso_fin;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('U' . $fila, round($porcentaje_rec_uso_ini * 100, 1))
            ->setCellValue('V' . $fila, round($porcentaje_rec_uso_fin * 100, 1));
    }

    ///Gerencia Oriental

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A69:V70');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B71:D71');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F71:H71');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J71:K71');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('M71:O71');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Q71:S71');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('U71:V71');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A69:V70')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B71:V71')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A72:V72')->applyFromArray($estiloSubTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B73:V83')->applyFromArray($estiloCuerpo);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A73:A83')->applyFromArray($estiloLaterales);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A69:V71")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A72:V72")->getFont()->setBold(true);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A69', 'FACTURACIÓN Y RECAUDACIÓN POR CATEGORIA DE USUARIOS - GERENCIA ORIENTAL ' . $perfin)
        ->setCellValue('B71', 'FACTURACIÓN')
        ->setCellValue('F71', 'RECAUDACIÓN')
        ->setCellValue('J71', 'PORCENTAJE RECAUDO')
        ->setCellValue('M71', 'NUMERO DE FACTURAS EMITIDAS')
        ->setCellValue('Q71', 'NUMERO DE PAGOS RECIBIDOS')
        ->setCellValue('U71', 'PORCENTAJE PAGOS')
        ->setCellValue('A72', 'Categoria de Usuarios')
        ->setCellValue('B72', $perini . ' RD$')
        ->setCellValue('C72', $perfin . ' RD$')
        ->setCellValue('D72', 'Variac. %')
        ->setCellValue('F72', $perini . ' RD$')
        ->setCellValue('G72', $perfin . ' RD$')
        ->setCellValue('H72', 'Variac. %')
        ->setCellValue('J72', $perini . ' %')
        ->setCellValue('K72', $perfin . ' %')
        ->setCellValue('M72', $perini)
        ->setCellValue('N72', $perfin)
        ->setCellValue('O72', 'Variac. %')
        ->setCellValue('Q72', $perini)
        ->setCellValue('R72', $perfin)
        ->setCellValue('S72', 'Variac. %')
        ->setCellValue('U72', $perini . ' %')
        ->setCellValue('V72', $perfin . ' %');

    $fila = 73;
    $c = new ReportesConMen();
    $registros = $c->FacturacionTotalUso($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $uso = utf8_decode(oci_result($registros, "USO"));
        $cant1fut = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2fut = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionfut = round((($cant2fut - $cant1fut) / $cant1fut) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $uso)
            ->setCellValue('B' . $fila, $cant1fut)
            ->setCellValue('C' . $fila, $cant2fut)
            ->setCellValue('D' . $fila, $variacionfut);
        $fila++;
        $totalperinifut += $cant1fut;
        $totalperfinfut += $cant2fut;
        $totalvariafut = round((($totalperfinfut - $totalperinifut) / $totalperinifut) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":D" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, 'Total')
        ->setCellValue('B' . $fila, $totalperinifut)
        ->setCellValue('C' . $fila, $totalperfinfut)
        ->setCellValue('D' . $fila, $totalvariafut);

    $fila = 73;
    $c = new ReportesConMen();
    $registros = $c->RecaudacionTotalUso($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $uso = utf8_decode(oci_result($registros, "USO"));
        $cant1rut = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2rut = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionrut = round((($cant2rut - $cant1rut) / $cant1rut) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F' . $fila, $cant1rut)
            ->setCellValue('G' . $fila, $cant2rut)
            ->setCellValue('H' . $fila, $variacionrut);
        $fila++;
        $totalperinirut += $cant1rut;
        $totalperfinrut += $cant2rut;
        $totalvariarut = round((($totalperfinrut - $totalperinirut) / $totalperinirut) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("F" . $fila . ":K" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F' . $fila, $totalperinirut)
        ->setCellValue('G' . $fila, $totalperfinrut)
        ->setCellValue('H' . $fila, $totalvariarut);

    for ($fila = 73; $fila <= 81; $fila++) {
        $val_recaudo_uso_ini = $objPHPExcel->getActiveSheet()->getCell('F' . $fila)->getValue();
        $val_factura_uso_ini = $objPHPExcel->getActiveSheet()->getCell('B' . $fila)->getValue();
        $porcentaje_rec_uso_ini = $val_recaudo_uso_ini / $val_factura_uso_ini;
        $val_recaudo_uso_fin = $objPHPExcel->getActiveSheet()->getCell('G' . $fila)->getValue();
        $val_factura_uso_fin = $objPHPExcel->getActiveSheet()->getCell('C' . $fila)->getValue();
        $porcentaje_rec_uso_fin = $val_recaudo_uso_fin / $val_factura_uso_fin;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J' . $fila, round($porcentaje_rec_uso_ini * 100, 1))
            ->setCellValue('K' . $fila, round($porcentaje_rec_uso_fin * 100, 1));
    }

    $fila = 73;
    $c = new ReportesConMen();
    $registros = $c->FacturasTotalUso($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $cant1faut = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2faut = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionfaut = round((($cant2faut - $cant1faut) / $cant1faut) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('M' . $fila, $cant1faut)
            ->setCellValue('N' . $fila, $cant2faut)
            ->setCellValue('O' . $fila, $variacionfaut);
        $fila++;
        $totalperinifaut += $cant1faut;
        $totalperfinfaut += $cant2faut;
        $totalvariafaut = round((($totalperfinfaut - $totalperinifaut) / $totalperinifaut) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("M" . $fila . ":O" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('M' . $fila, $totalperinifaut)
        ->setCellValue('N' . $fila, $totalperfinfaut)
        ->setCellValue('O' . $fila, $totalvariafaut);

    $fila = 73;
    $c = new ReportesConMen();
    $registros = $c->PagosTotalUso($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $cant1paut = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2paut = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionpaut = round((($cant2paut - $cant1paut) / $cant1paut) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q' . $fila, $cant1paut)
            ->setCellValue('R' . $fila, $cant2paut)
            ->setCellValue('S' . $fila, $variacionpaut);
        $fila++;
        $totalperinipaut += $cant1paut;
        $totalperfinpaut += $cant2paut;
        $totalvariapaut = round((($totalperfinpaut - $totalperinipaut) / $totalperinipaut) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("Q" . $fila . ":V" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('Q' . $fila, $totalperinipaut)
        ->setCellValue('R' . $fila, $totalperfinpaut)
        ->setCellValue('S' . $fila, $totalvariapaut);

    for ($fila = 73; $fila <= 81; $fila++) {
        $val_recaudo_uso_ini = $objPHPExcel->getActiveSheet()->getCell('Q' . $fila)->getValue();
        $val_factura_uso_ini = $objPHPExcel->getActiveSheet()->getCell('M' . $fila)->getValue();
        $porcentaje_rec_uso_ini = $val_recaudo_uso_ini / $val_factura_uso_ini;
        $val_recaudo_uso_fin = $objPHPExcel->getActiveSheet()->getCell('R' . $fila)->getValue();
        $val_factura_uso_fin = $objPHPExcel->getActiveSheet()->getCell('N' . $fila)->getValue();
        $porcentaje_rec_uso_fin = $val_recaudo_uso_fin / $val_factura_uso_fin;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('U' . $fila, round($porcentaje_rec_uso_ini * 100, 1))
            ->setCellValue('V' . $fila, round($porcentaje_rec_uso_fin * 100, 1));
    }

    $fila = $fila + 3;

    //SECTOR NORTE
    $fila2 = $fila + 1;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $fila . ':V' . $fila2)
        ->setCellValue('A' . $fila, 'FACTURACIÓN Y RECAUDACIÓN POR SECTORES - ZONA NORTE ' . $perfin);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $fila . ':V' . $fila2)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $fila . ':V' . $fila2)->getFont()->setBold(true);
    $fila = $fila + 2;

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $fila . ':D' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F' . $fila . ':H' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J' . $fila . ':K' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('M' . $fila . ':O' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Q' . $fila . ':S' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('U' . $fila . ':V' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B' . $fila . ':V' . $fila)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $fila . ':V' . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B' . $fila, 'FACTURACIÓN')
        ->setCellValue('F' . $fila, 'RECAUDACIÓN')
        ->setCellValue('J' . $fila, 'PORCENTAJE RECAUDO')
        ->setCellValue('M' . $fila, 'NUMERO DE FACTURAS EMITIDAS')
        ->setCellValue('Q' . $fila, 'NUMERO DE PAGOS RECIBIDOS')
        ->setCellValue('U' . $fila, 'PORCENTAJE PAGOS');
    $fila = $fila + 1;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, 'Sector')
        ->setCellValue('B' . $fila, $perini . ' RD$')
        ->setCellValue('C' . $fila, $perfin . ' RD$')
        ->setCellValue('D' . $fila, 'Variac. %')
        ->setCellValue('F' . $fila, $perini . ' RD$')
        ->setCellValue('G' . $fila, $perfin . ' RD$')
        ->setCellValue('H' . $fila, 'Variac. %')
        ->setCellValue('J' . $fila, $perini . ' %')
        ->setCellValue('K' . $fila, $perfin . ' %')
        ->setCellValue('M' . $fila, $perini)
        ->setCellValue('N' . $fila, $perfin)
        ->setCellValue('O' . $fila, 'Variac. %')
        ->setCellValue('Q' . $fila, $perini)
        ->setCellValue('R' . $fila, $perfin)
        ->setCellValue('S' . $fila, 'Variac. %')
        ->setCellValue('U' . $fila, $perini . ' %')
        ->setCellValue('V' . $fila, $perfin . ' %');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $fila . ':V' . $fila)->applyFromArray($estiloSubTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $fila . ':V' . $fila)->getFont()->setBold(true);

    $fila = $fila + 1;
    $filaini = $fila;

    $c = new ReportesConMen();
    $registros = $c->FacturacionNorteSector($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $sector = utf8_decode(oci_result($registros, "SECTOR"));
        $cant1fns = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2fns = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionfns = round((($cant2fns - $cant1fns) / $cant1fns) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $sector)
            ->setCellValue('B' . $fila, $cant1fns)
            ->setCellValue('C' . $fila, $cant2fns)
            ->setCellValue('D' . $fila, $variacionfns);
        $fila++;
        $totalperinifns += $cant1fns;
        $totalperfinfns += $cant2fns;
        $totalvariafns = round((($totalperfinfns - $totalperinifns) / $totalperinifns) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":D" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, 'Total')
        ->setCellValue('B' . $fila, $totalperinifns)
        ->setCellValue('C' . $fila, $totalperfinfns)
        ->setCellValue('D' . $fila, $totalvariafns);

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B' . $filaini . ':V' . $fila)->applyFromArray($estiloCuerpo);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $filaini . ':A' . $fila)->applyFromArray($estiloLateralesSector);

    $fila = $filaini;

    $c = new ReportesConMen();
    $registros = $c->RecaudacionNorteSector($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $sector = utf8_decode(oci_result($registros, "SECTOR"));
        $cant1rns = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2rns = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionrns = round((($cant2rns - $cant1rns) / $cant1rns) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F' . $fila, $cant1rns)
            ->setCellValue('G' . $fila, $cant2rns)
            ->setCellValue('H' . $fila, $variacionrns);
        $fila++;
        $totalperinirns += $cant1rns;
        $totalperfinrns += $cant2rns;
        $totalvariarns = round((($totalperfinrns - $totalperinirns) / $totalperinirns) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("F" . $fila . ":K" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F' . $fila, $totalperinirns)
        ->setCellValue('G' . $fila, $totalperfinrns)
        ->setCellValue('H' . $fila, $totalvariarns);

    $filafin = $fila;

    for ($fila = $filaini; $fila <= $filafin; $fila++) {
        $val_recaudo_sec_ini = $objPHPExcel->getActiveSheet()->getCell('F' . $fila)->getValue();
        $val_factura_sec_ini = $objPHPExcel->getActiveSheet()->getCell('B' . $fila)->getValue();
        $porcentaje_rec_sec_ini = $val_recaudo_sec_ini / $val_factura_sec_ini;
        $val_recaudo_sec_fin = $objPHPExcel->getActiveSheet()->getCell('G' . $fila)->getValue();
        $val_factura_sec_fin = $objPHPExcel->getActiveSheet()->getCell('C' . $fila)->getValue();
        $porcentaje_rec_sec_fin = $val_recaudo_sec_fin / $val_factura_sec_fin;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J' . $fila, round($porcentaje_rec_sec_ini * 100, 1))
            ->setCellValue('K' . $fila, round($porcentaje_rec_sec_fin * 100, 1));
    }

    $fila = $filaini;

    $c = new ReportesConMen();
    $registros = $c->FacturasNorteSector($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $sector = utf8_decode(oci_result($registros, "SECTOR"));
        $cant1fans = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2fans = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionfans = round((($cant2fans - $cant1fans) / $cant1fans) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('M' . $fila, $cant1fans)
            ->setCellValue('N' . $fila, $cant2fans)
            ->setCellValue('O' . $fila, $variacionfans);
        $fila++;
        $totalperinifans += $cant1fans;
        $totalperfinfans += $cant2fans;
        $totalvariafans = round((($totalperfinfans - $totalperinifans) / $totalperinifans) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":V" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('M' . $fila, $totalperinifans)
        ->setCellValue('N' . $fila, $totalperfinfans)
        ->setCellValue('O' . $fila, $totalvariafans);

    $fila = $filaini;

    $c = new ReportesConMen();
    $registros = $c->PagosNorteSector($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $sector = utf8_decode(oci_result($registros, "SECTOR"));
        $cant1pans = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2pans = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionpans = round((($cant2pans - $cant1pans) / $cant1pans) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q' . $fila, $cant1pans)
            ->setCellValue('R' . $fila, $cant2pans)
            ->setCellValue('S' . $fila, $variacionpans);
        $fila++;
        $totalperinipans += $cant1pans;
        $totalperfinpans += $cant2pans;
        $totalvariapans = round((($totalperfinpans - $totalperinipans) / $totalperinipans) * 100, 1);
    }
    oci_free_statement($registros);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('Q' . $fila, $totalperinipans)
        ->setCellValue('R' . $fila, $totalperfinpans)
        ->setCellValue('S' . $fila, $totalvariapans);

    $filafin = $fila;

    for ($fila = $filaini; $fila <= $filafin; $fila++) {
        $val_recaudo_sec_ini = $objPHPExcel->getActiveSheet()->getCell('Q' . $fila)->getValue();
        $val_factura_sec_ini = $objPHPExcel->getActiveSheet()->getCell('M' . $fila)->getValue();
        $porcentaje_rec_sec_ini = $val_recaudo_sec_ini / $val_factura_sec_ini;
        $val_recaudo_sec_fin = $objPHPExcel->getActiveSheet()->getCell('R' . $fila)->getValue();
        $val_factura_sec_fin = $objPHPExcel->getActiveSheet()->getCell('N' . $fila)->getValue();
        $porcentaje_rec_sec_fin = $val_recaudo_sec_fin / $val_factura_sec_fin;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('U' . $fila, round($porcentaje_rec_sec_ini * 100, 1))
            ->setCellValue('V' . $fila, round($porcentaje_rec_sec_fin * 100, 1));
    }

    //SECTOR ESTE

    $fila = $fila + 1;
    $fila2 = $fila + 1;
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $fila . ':V' . $fila2)
        ->setCellValue('A' . $fila, 'FACTURACIÓN Y RECAUDACIÓN POR SECTORES - ZONA ESTE ' . $perfin);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $fila . ':V' . $fila2)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $fila . ':V' . $fila2)->getFont()->setBold(true);
    $fila = $fila + 2;

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $fila . ':D' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F' . $fila . ':H' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J' . $fila . ':K' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('M' . $fila . ':O' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Q' . $fila . ':S' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('U' . $fila . ':V' . $fila);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B' . $fila . ':V' . $fila)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $fila . ':V' . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B' . $fila, 'FACTURACIÓN')
        ->setCellValue('F' . $fila, 'RECAUDACIÓN')
        ->setCellValue('J' . $fila, 'PORCENTAJE RECAUDO')
        ->setCellValue('M' . $fila, 'NUMERO DE FACTURAS EMITIDAS')
        ->setCellValue('Q' . $fila, 'NUMERO DE PAGOS RECIBIDOS')
        ->setCellValue('U' . $fila, 'PORCENTAJE PAGOS');
    $fila = $fila + 1;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, 'Sector')
        ->setCellValue('B' . $fila, $perini . ' RD$')
        ->setCellValue('C' . $fila, $perfin . ' RD$')
        ->setCellValue('D' . $fila, 'Variac. %')
        ->setCellValue('F' . $fila, $perini . ' RD$')
        ->setCellValue('G' . $fila, $perfin . ' RD$')
        ->setCellValue('H' . $fila, 'Variac. %')
        ->setCellValue('J' . $fila, $perini . ' %')
        ->setCellValue('K' . $fila, $perfin . ' %')
        ->setCellValue('M' . $fila, $perini)
        ->setCellValue('N' . $fila, $perfin)
        ->setCellValue('O' . $fila, 'Variac. %')
        ->setCellValue('Q' . $fila, $perini)
        ->setCellValue('R' . $fila, $perfin)
        ->setCellValue('S' . $fila, 'Variac. %')
        ->setCellValue('U' . $fila, $perini . ' %')
        ->setCellValue('V' . $fila, $perfin . ' %');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $fila . ':V' . $fila)->applyFromArray($estiloSubTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $fila . ':V' . $fila)->getFont()->setBold(true);

    $fila = $fila + 1;
    $filaini = $fila;

    $c = new ReportesConMen();
    $registros = $c->FacturacionEsteSector($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $sector = utf8_decode(oci_result($registros, "SECTOR"));
        $cant1fes = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2fes = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionfes = round((($cant2fes - $cant1fes) / $cant1fes) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $sector)
            ->setCellValue('B' . $fila, $cant1fes)
            ->setCellValue('C' . $fila, $cant2fes)
            ->setCellValue('D' . $fila, $variacionfes);
        $fila++;
        $totalperinifes += $cant1fes;
        $totalperfinfes += $cant2fes;
        $totalvariafes = round((($totalperfinfes - $totalperinifes) / $totalperinifes) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":D" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, 'Total')
        ->setCellValue('B' . $fila, $totalperinifes)
        ->setCellValue('C' . $fila, $totalperfinfes)
        ->setCellValue('D' . $fila, $totalvariafes);

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('B' . $filaini . ':V' . $fila)->applyFromArray($estiloCuerpo);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A' . $filaini . ':A' . $fila)->applyFromArray($estiloLateralesSector);

    $fila = $filaini;

    $c = new ReportesConMen();
    $registros = $c->RecaudacionEsteSector($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $sector = utf8_decode(oci_result($registros, "SECTOR"));
        $cant1res = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2res = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionres = round((($cant2res - $cant1res) / $cant1res) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F' . $fila, $cant1res)
            ->setCellValue('G' . $fila, $cant2res)
            ->setCellValue('H' . $fila, $variacionres);
        $fila++;
        $totalperinires += $cant1res;
        $totalperfinres += $cant2res;
        $totalvariares = round((($totalperfinres - $totalperinires) / $totalperinires) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("F" . $fila . ":K" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F' . $fila, $totalperinires)
        ->setCellValue('G' . $fila, $totalperfinres)
        ->setCellValue('H' . $fila, $totalvariares);

    $filafin = $fila;

    for ($fila = $filaini; $fila <= $filafin; $fila++) {
        $val_recaudo_sec_ini = $objPHPExcel->getActiveSheet()->getCell('F' . $fila)->getValue();
        $val_factura_sec_ini = $objPHPExcel->getActiveSheet()->getCell('B' . $fila)->getValue();
        $porcentaje_rec_sec_ini = $val_recaudo_sec_ini / $val_factura_sec_ini;
        $val_recaudo_sec_fin = $objPHPExcel->getActiveSheet()->getCell('G' . $fila)->getValue();
        $val_factura_sec_fin = $objPHPExcel->getActiveSheet()->getCell('C' . $fila)->getValue();
        $porcentaje_rec_sec_fin = $val_recaudo_sec_fin / $val_factura_sec_fin;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('J' . $fila, round($porcentaje_rec_sec_ini * 100, 1))
            ->setCellValue('K' . $fila, round($porcentaje_rec_sec_fin * 100, 1));
    }

    $fila = $filaini;

    $c = new ReportesConMen();
    $registros = $c->FacturasEsteSector($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $sector = utf8_decode(oci_result($registros, "SECTOR"));
        $cant1faes = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2faes = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionfaes = round((($cant2faes - $cant1faes) / $cant1faes) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('M' . $fila, $cant1faes)
            ->setCellValue('N' . $fila, $cant2faes)
            ->setCellValue('O' . $fila, $variacionfaes);
        $fila++;
        $totalperinifaes += $cant1faes;
        $totalperfinfaes += $cant2faes;
        $totalvariafaes = round((($totalperfinfaes - $totalperinifaes) / $totalperinifaes) * 100, 1);
    }
    oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila . ":V" . $fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('M' . $fila, $totalperinifaes)
        ->setCellValue('N' . $fila, $totalperfinfaes)
        ->setCellValue('O' . $fila, $totalvariafaes);

    $fila = $filaini;

    $c = new ReportesConMen();
    $registros = $c->PagosEsteSector($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $sector = utf8_decode(oci_result($registros, "SECTOR"));
        $cant1paes = utf8_decode(oci_result($registros, "'$perini'"));
        $cant2paes = utf8_decode(oci_result($registros, "'$perfin'"));
        $variacionpaes = round((($cant2paes - $cant1paes) / $cant1paes) * 100, 1);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('Q' . $fila, $cant1paes)
            ->setCellValue('R' . $fila, $cant2paes)
            ->setCellValue('S' . $fila, $variacionpaes);
        $fila++;
        $totalperinipaes += $cant1paes;
        $totalperfinpaes += $cant2paes;
        $totalvariapaes = round((($totalperfinpaes - $totalperinipaes) / $totalperinipaes) * 100, 1);
    }
    oci_free_statement($registros);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('Q' . $fila, $totalperinipaes)
        ->setCellValue('R' . $fila, $totalperfinpaes)
        ->setCellValue('S' . $fila, $totalvariapaes);

    $filafin = $fila;

    for ($fila = $filaini; $fila <= $filafin; $fila++) {
        $val_recaudo_sec_ini = $objPHPExcel->getActiveSheet()->getCell('Q' . $fila)->getValue();
        $val_factura_sec_ini = $objPHPExcel->getActiveSheet()->getCell('M' . $fila)->getValue();
        $porcentaje_rec_sec_ini = $val_recaudo_sec_ini / $val_factura_sec_ini;
        $val_recaudo_sec_fin = $objPHPExcel->getActiveSheet()->getCell('R' . $fila)->getValue();
        $val_factura_sec_fin = $objPHPExcel->getActiveSheet()->getCell('N' . $fila)->getValue();
        $porcentaje_rec_sec_fin = $val_recaudo_sec_fin / $val_factura_sec_fin;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('U' . $fila, round($porcentaje_rec_sec_ini * 100, 1))
            ->setCellValue('V' . $fila, round($porcentaje_rec_sec_fin * 100, 1));
    }

    $objPHPExcel->getActiveSheet()->setTitle('Facturacion y recaudo');

    ////////////////////HOJA UNIDADES POR GERENCIA USO CONCEPTO
    // ZONA ESTE

    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Unidades Gerencia Uso Concepto');
    $objPHPExcel->addSheet($myWorkSheet, 1);

    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("A")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("B")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("C")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("D")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("E")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:E1');
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A1:E2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:E2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A1', 'TOTAL DE UNIDADES USUARIOS ACTIVOS POR GERENCIA, USO Y CONCEPTO '.$periodo)
        ->setCellValue('A2', 'Zona')
        ->setCellValue('B2', 'Uso')
        ->setCellValue('C2', 'Agua')
        ->setCellValue('D2', 'Agua Pozo')
        ->setCellValue('E2', 'Alcantarillado');

    $fila = 3;
    $filaini = $fila;
    $h=new ReportesGerencia();
    $registrosh=$h->UnidadesGerenciaUsoConceptoPeriodoUsos($proyecto, $periodo);
    while (oci_fetch($registrosh)) {
        $des_uso = utf8_decode(oci_result($registrosh, "USO"));
        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('B' . $fila, $des_uso);

        $c = new ReportesGerencia();
        $registros = $c->UnidadesGerenciaUsoConceptoPeriodoEsteAgua($proyecto, $periodo, $des_uso);
        while (oci_fetch($registros)) {
            $cantidad = utf8_decode(oci_result($registros, "UNIDADES"));
            $objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('C' . $fila, $cantidad);
            $totalaguaredeste += $cantidad;
        }oci_free_statement($registros);

        $c = new ReportesGerencia();
        $registros = $c->UnidadesGerenciaUsoConceptoPeriodoEstePozo($proyecto, $periodo, $des_uso);
        while (oci_fetch($registros)) {
            $cantidad = utf8_decode(oci_result($registros, "UNIDADES"));
            $objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('D' . $fila, $cantidad);
            $totalaguapozoeste += $cantidad;
        }oci_free_statement($registros);

        $c = new ReportesGerencia();
        $registros = $c->UnidadesGerenciaUsoConceptoPeriodoEsteAlc($proyecto, $periodo, $des_uso);
        while (oci_fetch($registros)) {
            $cantidad = utf8_decode(oci_result($registros, "UNIDADES"));
            $objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('E' . $fila, $cantidad);
            $totalalcantarilladoeste += $cantidad;
        }oci_free_statement($registros);

        $fila++;
    }oci_free_statement($registrosh);
    $filafin = $fila;
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('B'.$filafin.':E'.$filafin)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('B' . $filafin, 'Total Este')
        ->setCellValue('C' . $filafin, $totalaguaredeste)
        ->setCellValue('D' . $filafin, $totalaguapozoeste)
        ->setCellValue('E' . $filafin, $totalalcantarilladoeste);
    $objPHPExcel->setActiveSheetIndex(1)->mergeCells('A'.$filaini.':A'.$filafin);
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$filaini, 'Este');
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$filaini.':E'.$filafin)->applyFromArray($estiloLaterales);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$filaini)->getFont()->setBold(true);

    // ZONA NORTE

    $fila = $filafin+1;
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$fila.':E'.$fila)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$fila.':E'.$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$fila, 'Zona')
        ->setCellValue('B'.$fila, 'Uso')
        ->setCellValue('C'.$fila, 'Agua')
        ->setCellValue('D'.$fila, 'Agua Pozo')
        ->setCellValue('E'.$fila, 'Alcantarillado');

    $fila = $fila+1;
    $filaini = $fila;
    $h=new ReportesGerencia();
    $registrosh=$h->UnidadesGerenciaUsoConceptoPeriodoUsos($proyecto, $periodo);
    while (oci_fetch($registrosh)) {
        $des_uso = utf8_decode(oci_result($registrosh, "USO"));
        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('B' . $fila, $des_uso);

        $c = new ReportesGerencia();
        $registros = $c->UnidadesGerenciaUsoConceptoPeriodoNorteAgua2($proyecto, $periodo, $des_uso);
        while (oci_fetch($registros)) {
            $cantidad2 = utf8_decode(oci_result($registros, "UNIDADES"));
            $objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('C' . $fila, $cantidad2);
            $totalaguarednorte += $cantidad2;
        }oci_free_statement($registros);

        $c = new ReportesGerencia();
        $registros = $c->UnidadesGerenciaUsoConceptoPeriodoNortePozo2($proyecto, $periodo, $des_uso);
        while (oci_fetch($registros)) {
            $cantidad2 = utf8_decode(oci_result($registros, "UNIDADES"));
            $objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('D' . $fila, $cantidad2);
            $totalaguapozonorte += $cantidad2;
        }oci_free_statement($registros);

        $c = new ReportesGerencia();
        $registros = $c->UnidadesGerenciaUsoConceptoPeriodoNorteAlc2($proyecto, $periodo, $des_uso);
        while (oci_fetch($registros)) {
            $cantidad2 = utf8_decode(oci_result($registros, "UNIDADES"));
            $objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('E' . $fila, $cantidad2);
            $totalalcantarilladonorte += $cantidad2;
        }oci_free_statement($registros);

        $fila++;
    }oci_free_statement($registrosh);
    $filafin = $fila;
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('B'.$filafin.':E'.$filafin)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('B' . $filafin, 'Total Norte')
        ->setCellValue('C' . $filafin, $totalaguarednorte)
        ->setCellValue('D' . $filafin, $totalaguapozonorte)
        ->setCellValue('E' . $filafin, $totalalcantarilladonorte);
    $objPHPExcel->setActiveSheetIndex(1)->mergeCells('A'.$filaini.':A'.$filafin);
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$filaini, 'Norte');
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$filaini.':E'.$filafin)->applyFromArray($estiloLaterales);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$filaini)->getFont()->setBold(true);

    ////////////////////HOJA USUARIOS ALCANTARILLADO
    // ZONA ESTE

    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Usuarios Alcantarillado');
    $objPHPExcel->addSheet($myWorkSheet, 2);

    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("A")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("B")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("C")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('A1:C1');
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A1:C2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle("A1:C2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A1', 'USUARIOS ALCANTARILLADO POR ZONA Y USO '.$periodo)
        ->setCellValue('A2', 'Zona')
        ->setCellValue('B2', 'Uso')
        ->setCellValue('C2', 'Usuarios');

    $fila = 3;
    $filaini = $fila;
    $c=new ReportesGerencia();
    $registros=$c->UsuariosAlcGerenciaUsoConceptoPeriodoEste($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso = utf8_decode(oci_result($registros, "USO"));
        $cantidadusualceste = utf8_decode(oci_result($registros, "CANTIDAD"));
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('B' . $fila, $des_uso)
            ->setCellValue('C' . $fila, $cantidadusualceste);
        $totalusuarioseste += $cantidadusualceste;
        $fila++;
    }oci_free_statement($registros);

    $filafin = $fila;
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('B'.$filafin.':C'.$filafin)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('B' . $filafin, 'Total Este')
        ->setCellValue('C' . $filafin, $totalusuarioseste);
    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('A'.$filaini.':A'.$filafin);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A'.$filaini, 'Este');
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A'.$filaini.':C'.$filafin)->applyFromArray($estiloLaterales);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A'.$filaini)->getFont()->setBold(true);


    // ZONA NORTE
    $fila = $filafin+1;
    $filaini = $fila;
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A'.$fila.':C'.$fila)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A'.$fila.':C'.$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A'.$fila, 'Zona')
        ->setCellValue('B'.$fila, 'Uso')
        ->setCellValue('C'.$fila, 'Usuarios');

    $fila = $fila+1;

    $c=new ReportesGerencia();
    $registros=$c->UsuariosAlcGerenciaUsoConceptoPeriodoNorte($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso = utf8_decode(oci_result($registros, "USO"));
        $cantidadusualcnorte = utf8_decode(oci_result($registros, "CANTIDAD"));
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('B' . $fila, $des_uso)
            ->setCellValue('C' . $fila, $cantidadusualcnorte);
        $totalusuariosnorte += $cantidadusualcnorte;
        $fila++;
    }oci_free_statement($registros);

    $filafin = $fila;
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('B'.$filafin.':C'.$filafin)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('B' . $filafin, 'Total Norte')
        ->setCellValue('C' . $filafin, $totalusuariosnorte);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A'.$filaini.':C'.$filafin)->applyFromArray($estiloLaterales);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A'.$filaini)->getFont()->setBold(true);
    $filaini = $filaini+1;
    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('A'.$filaini.':A'.$filafin);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A'.$filaini)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A'.$filaini, 'Norte');
    $fila = $filafin+2;

    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('A'.$fila.':B'.$fila);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A'.$fila.':C'.$fila)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A'.$fila.':C'.$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A' . $fila, 'Total General')
        ->setCellValue('C' . $fila, $totalusuarioseste+$totalusuariosnorte);


    //HOJA M3 FACTURADOS RED Y POZO

    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'm3 facturados red-pozo');
    $objPHPExcel->addSheet($myWorkSheet, 3);


    $objPHPExcel->setActiveSheetIndex(3)->getStyle('K1:Z99')->applyFromArray($estiloNoLineas);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A10:J11')->applyFromArray($estiloNoLineas);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A21:J22')->applyFromArray($estiloNoLineas);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A32:J33')->applyFromArray($estiloNoLineas);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A43:J99')->applyFromArray($estiloNoLineas);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A4:J8')->applyFromArray($estiloLineaLateral);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A15:J19')->applyFromArray($estiloLineaLateral);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A26:J30')->applyFromArray($estiloLineaLateral);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A37:J41')->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('A1:J1');//
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('A2:A3');//
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('B2:D2');//
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('E2:G2');//
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('H2:J2');//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A1:J1')->applyFromArray($estiloTitulos);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A2:A3')->applyFromArray($estiloTitulos);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('B2:J2')->applyFromArray($estiloTitulos);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('B3:J3')->applyFromArray($estiloTitulos);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A9:J9')->applyFromArray($estiloTitulos);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A1:J1")->getFont()->setBold(true);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A2:A3")->getFont()->setBold(true);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("B2:J2")->getFont()->setBold(true);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("B3:J3")->getFont()->setBold(true);//
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A4:A8")->getFont()->setBold(true);//
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('A1', 'VOLUMEN AGUA DE RED FACTURADA EN M3 - USUARIOS CON MEDIDOR  '.$proyecto.' PERIODO '.$periodo)
        ->setCellValue('A2', 'Uso')
        ->setCellValue('B2', 'Zona Este')
        ->setCellValue('E2', 'Zona Norte')
        ->setCellValue('H2', 'Este + Norte')
        ->setCellValue('B3', $perini)
        ->setCellValue('C3', $perfin)
        ->setCellValue('D3', 'Variacion (%)')
        ->setCellValue('E3', $perini)
        ->setCellValue('F3', $perfin)
        ->setCellValue('G3', 'Variacion (%)')
        ->setCellValue('H3', $perini)
        ->setCellValue('I3', $perfin)
        ->setCellValue('J3', 'Variacion (%)')
        ->setCellValue('A4', 'Residencial')
        ->setCellValue('A5', 'Mixto')
        ->setCellValue('A6', 'Oficial')
        ->setCellValue('A7', 'Industrial')
        ->setCellValue('A8', 'Comercial');
       // ->setCellValue('A9', 'ZF Industrial')
       // ->setCellValue('A10', 'ZF Comercial');
    $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("A")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("B")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("C")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("D")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("E")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("F")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("G")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("H")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("I")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("J")->setWidth(12);

    $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorMedidosEste($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        if($des_uso == 'Residencial') $fila = 4;
        if($des_uso == 'Res. y Com.') $fila = 5;
        if($des_uso == 'Oficial') $fila = 6;
        if($des_uso == 'Industrial') $fila = 7;
        if($des_uso == 'Comercial') $fila = 8;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('B'.$fila, $cant1)
            ->setCellValue('C'.$fila, $cant2)
            ->setCellValue('D'.$fila, $variacion);
        $totalmedidoesteant += $cant1;
        $totalmedidoesteact += $cant2;
    }oci_free_statement($registros);

   /* $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorMedidosEsteZF($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'ZF Oficial') $fila = 4567;
        if($des_uso == 'ZF Industrial') $fila = 9; if($des_uso == 'ZF Comercial') $fila = 10;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('B'.$fila, $cant1)
            ->setCellValue('C'.$fila, $cant2)
            ->setCellValue('D'.$fila, $variacion);
        $totalmedidoesteant += $cant1;
        $totalmedidoesteact += $cant2;
    }oci_free_statement($registros);*/
    $totalvariacioneste = round((($totalmedidoesteact-$totalmedidoesteant)/$totalmedidoesteant)*100,2);

    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A9:J9")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('A9', 'Totales')
        ->setCellValue('B9', $totalmedidoesteant)
        ->setCellValue('C9', $totalmedidoesteact)
        ->setCellValue('D9', $totalvariacioneste);

    $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorMedidosNorte($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'Residencial') $fila = 4; if($des_uso == 'Res. y Com.') $fila = 5; if($des_uso == 'Oficial') $fila = 6; if($des_uso == 'Industrial') $fila = 7;
        if($des_uso == 'Comercial') $fila = 8;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('E'.$fila, $cant1)
            ->setCellValue('F'.$fila, $cant2)
            ->setCellValue('G'.$fila, $variacion);
        $totalmedidonorteant += $cant1;
        $totalmedidonorteact += $cant2;
    }oci_free_statement($registros);

    /*$c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorMedidosNorteZF($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'ZF Oficial') $fila = 4567;
        if($des_uso == 'ZF Industrial') $fila = 9; if($des_uso == 'ZF Comercial') $fila = 10;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('E'.$fila, $cant1)
            ->setCellValue('F'.$fila, $cant2)
            ->setCellValue('G'.$fila, $variacion);
        $totalmedidonorteant += $cant1;
        $totalmedidonorteact += $cant2;
    }oci_free_statement($registros);*/
    if ($totalmedidonorteant > 0){
        $totalvariacionnorte = round((($totalmedidonorteact-$totalmedidonorteant)/$totalmedidonorteant)*100,2);
    }
    else{
        $totalvariacionnorte = 0;
    }

    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('E9', $totalmedidonorteant)
        ->setCellValue('F9', $totalmedidonorteact)
        ->setCellValue('G9', $totalvariacionnorte);

    $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorMedidosTotal($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'Residencial') $fila = 4; if($des_uso == 'Res. y Com.') $fila = 5; if($des_uso == 'Oficial') $fila = 6; if($des_uso == 'Industrial') $fila = 7;
        if($des_uso == 'Comercial') $fila = 8;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('H'.$fila, $cant1)
            ->setCellValue('I'.$fila, $cant2)
            ->setCellValue('J'.$fila, $variacion);
        $totalmedidototalant += $cant1;
        $totalmedidototalact += $cant2;
    }oci_free_statement($registros);

   /* $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorMedidosTotalZF($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'ZF Oficial') $fila = 4567;
        if($des_uso == 'ZF Industrial') $fila = 9; if($des_uso == 'ZF Comercial') $fila = 10;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('H'.$fila, $cant1)
            ->setCellValue('I'.$fila, $cant2)
            ->setCellValue('J'.$fila, $variacion);
        $totalmedidototalant += $cant1;
        $totalmedidototalact += $cant2;
    }oci_free_statement($registros);*/
    $totalvariaciontotal = round((($totalmedidototalact-$totalmedidototalant)/$totalmedidototalant)*100,2);

    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('H9', $totalmedidototalant)
        ->setCellValue('I9', $totalmedidototalact)
        ->setCellValue('J9', $totalvariaciontotal);

    //////	USUARIOS NO MEDIDOS AGUA RED

    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('A12:J12');
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('A13:A14');
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('B13:D13');
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('E13:G13');
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('H13:J13');
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A12:J12')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A13:A14')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('B13:J13')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('B14:J14')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A20:J20')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A12:J12")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A13:A14")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("B13:J13")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("B14:J14")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A15:A20")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('A12', 'VOLUMEN AGUA DE RED FACTURADA EN M3 - USUARIOS SIN MEDIDOR  '.$proyecto.' PERIODO '.$periodo)
        ->setCellValue('A13', 'Uso')
        ->setCellValue('B13', 'Zona Este')
        ->setCellValue('E13', 'Zona Norte')
        ->setCellValue('H13', 'Este + Norte')
        ->setCellValue('B14', $perini)
        ->setCellValue('C14', $perfin)
        ->setCellValue('D14', 'Variacion (%)')
        ->setCellValue('E14', $perini)
        ->setCellValue('F14', $perfin)
        ->setCellValue('G14', 'Variacion (%)')
        ->setCellValue('H14', $perini)
        ->setCellValue('I14', $perfin)
        ->setCellValue('J14', 'Variacion (%)')
        ->setCellValue('A15', 'Residencial')
        ->setCellValue('A16', 'Mixto')
        ->setCellValue('A17', 'Oficial')
        ->setCellValue('A18', 'Industrial')
        ->setCellValue('A19', 'Comercial');
        //->setCellValue('A20', 'ZF Industrial')
        //->setCellValue('A21', 'ZF Comercial');

    $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorNoMedidosEste($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'Residencial') $fila = 15; if($des_uso == 'Res. y Com.') $fila = 16; if($des_uso == 'Oficial') $fila = 17; if($des_uso == 'Industrial') $fila = 18;
        if($des_uso == 'Comercial') $fila = 19;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('B'.$fila, $cant1)
            ->setCellValue('C'.$fila, $cant2)
            ->setCellValue('D'.$fila, $variacion);
        $totalnomedidoesteant += $cant1;
        $totalnomedidoesteact += $cant2;
    }oci_free_statement($registros);

  /*  $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorNoMedidosEsteZF($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'ZF Oficial') $fila = 4567;
        if($des_uso == 'ZF Industrial') $fila = 22; if($des_uso == 'ZF Comercial') $fila = 23;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('B'.$fila, $cant1)
            ->setCellValue('C'.$fila, $cant2)
            ->setCellValue('D'.$fila, $variacion);
        $totalnomedidoesteant += $cant1;
        $totalnomedidoesteact += $cant2;
    }oci_free_statement($registros);*/
    $totalvariacionnoeste = round((($totalnomedidoesteact-$totalnomedidoesteant)/$totalnomedidoesteant)*100,2);

    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A20:J20")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('A20', 'Totales')
        ->setCellValue('B20', $totalnomedidoesteant)
        ->setCellValue('C20', $totalnomedidoesteact)
        ->setCellValue('D20', $totalvariacionnoeste);

    $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorNoMedidosNorte($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'Residencial') $fila = 15; if($des_uso == 'Res. y Com.') $fila = 16; if($des_uso == 'Oficial') $fila = 17; if($des_uso == 'Industrial') $fila = 18;
        if($des_uso == 'Comercial') $fila = 19;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('E'.$fila, $cant1)
            ->setCellValue('F'.$fila, $cant2)
            ->setCellValue('G'.$fila, $variacion);
        $totalnomedidonorteant += $cant1;
        $totalnomedidonorteact += $cant2;
    }oci_free_statement($registros);

   /* $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorNoMedidosNorteZF($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'ZF Oficial') $fila = 4567;
        if($des_uso == 'ZF Industrial') $fila = 22; if($des_uso == 'ZF Comercial') $fila = 23;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('E'.$fila, $cant1)
            ->setCellValue('F'.$fila, $cant2)
            ->setCellValue('G'.$fila, $variacion);
        $totalnomedidonorteant += $cant1;
        $totalnomedidonorteact += $cant2;
    }oci_free_statement($registros);*/
    if ($totalnomedidonorteant > 0){
        $totalvariacionnonorte = round((($totalnomedidonorteact-$totalnomedidonorteant)/$totalnomedidonorteant)*100,2);
    }
    else{
        $totalvariacionnonorte = 0;
    }
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('E20', $totalnomedidonorteant)
        ->setCellValue('F20', $totalnomedidonorteact)
        ->setCellValue('G20', $totalvariacionnonorte);

    $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorNoMedidosTotal($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'Residencial') $fila = 15; if($des_uso == 'Res. y Com.') $fila = 16; if($des_uso == 'Oficial') $fila = 17; if($des_uso == 'Industrial') $fila = 18;
        if($des_uso == 'Comercial') $fila = 19;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('H'.$fila, $cant1)
            ->setCellValue('I'.$fila, $cant2)
            ->setCellValue('J'.$fila, $variacion);
        $totalnomedidototalant += $cant1;
        $totalnomedidototalact += $cant2;
    }oci_free_statement($registros);

   /* $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorNoMedidosTotalZF($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'ZF Oficial') $fila = 4567;
        if($des_uso == 'ZF Industrial') $fila = 22; if($des_uso == 'ZF Comercial') $fila = 23;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('H'.$fila, $cant1)
            ->setCellValue('I'.$fila, $cant2)
            ->setCellValue('J'.$fila, $variacion);
        $totalnomedidototalant += $cant1;
        $totalnomedidototalact += $cant2;
    }oci_free_statement($registros);*/
    $totalvariacionnototal = round((($totalnomedidototalact-$totalnomedidototalant)/$totalnomedidototalant)*100,2);

    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('H20', $totalnomedidototalant)
        ->setCellValue('I20', $totalnomedidototalact)
        ->setCellValue('J20', $totalvariacionnototal);

    //////	USUARIOS TOTAL AGUA RED

    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('A23:J23');
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('A24:A25');
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('B24:D24');
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('E24:G24');
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('H24:J24');
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A23:J23')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A24:A25')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('B24:J24')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('B25:J25')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A31:J31')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A23:J25")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A26:A30")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('A23', 'VOLUMEN AGUA DE RED FACTURADA EN M3 - TOTAL  '.$proyecto.' PERIODO '.$periodo)
        ->setCellValue('A24', 'Uso')
        ->setCellValue('B24', 'Zona Este')
        ->setCellValue('E24', 'Zona Norte')
        ->setCellValue('H24', 'Este + Norte')
        ->setCellValue('B25', $perini)
        ->setCellValue('C25', $perfin)
        ->setCellValue('D25', 'Variacion (%)')
        ->setCellValue('E25', $perini)
        ->setCellValue('F25', $perfin)
        ->setCellValue('G25', 'Variacion (%)')
        ->setCellValue('H25', $perini)
        ->setCellValue('I25', $perfin)
        ->setCellValue('J25', 'Variacion (%)')
        ->setCellValue('A26', 'Residencial')
        ->setCellValue('A27', 'Mixto')
        ->setCellValue('A28', 'Oficial')
        ->setCellValue('A29', 'Industrial')
        ->setCellValue('A30', 'Comercial');
        //->setCellValue('A35', 'ZF Industrial')
        //->setCellValue('A36', 'ZF Comercial');

    $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorTotalEste($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'Residencial') $fila = 26; if($des_uso == 'Res. y Com.') $fila = 27; if($des_uso == 'Oficial') $fila = 28; if($des_uso == 'Industrial') $fila = 29;
        if($des_uso == 'Comercial') $fila = 30;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('B'.$fila, $cant1)
            ->setCellValue('C'.$fila, $cant2)
            ->setCellValue('D'.$fila, $variacion);
        $totalmedidoestetotant += $cant1;
        $totalmedidoestetotact += $cant2;
    }oci_free_statement($registros);

   /* $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorTotalEsteZF($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'ZF Oficial') $fila = 4567;
        if($des_uso == 'ZF Industrial') $fila = 35; if($des_uso == 'ZF Comercial') $fila = 36;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('B'.$fila, $cant1)
            ->setCellValue('C'.$fila, $cant2)
            ->setCellValue('D'.$fila, $variacion);
        $totalmedidoestetotant += $cant1;
        $totalmedidoestetotact += $cant2;

    }oci_free_statement($registros);*/
    $totalvariaciontoteste = round((($totalmedidoestetotact-$totalmedidoestetotant)/$totalmedidoestetotant)*100,2);

    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A31:J31")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('A31', 'Totales')
        ->setCellValue('B31', $totalmedidoestetotant)
        ->setCellValue('C31', $totalmedidoestetotact)
        ->setCellValue('D31', $totalvariaciontoteste);

    $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorTotalNorte($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'Residencial') $fila = 26; if($des_uso == 'Res. y Com.') $fila = 27; if($des_uso == 'Oficial') $fila = 28; if($des_uso == 'Industrial') $fila = 29;
        if($des_uso == 'Comercial') $fila = 30;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('E'.$fila, $cant1)
            ->setCellValue('F'.$fila, $cant2)
            ->setCellValue('G'.$fila, $variacion);
        $totalmedidonortetotant += $cant1;
        $totalmedidonortetotact += $cant2;
    }oci_free_statement($registros);

   /* $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorTotalNorteZF($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        if($des_uso == 'ZF Industrial') $fila = 35; if($des_uso == 'ZF Comercial') $fila = 36;
        if($des_uso == 'ZF Oficial') $fila = 4567;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('E'.$fila, $cant1)
            ->setCellValue('F'.$fila, $cant2)
            ->setCellValue('G'.$fila, $variacion);
        $totalmedidonortetotant += $cant1;
        $totalmedidonortetotact += $cant2;

    }oci_free_statement($registros);*/
    if($totalmedidonortetotant > 0){
        $totalvariacionnortetot = round((($totalmedidonortetotact-$totalmedidonortetotant)/$totalmedidonortetotant)*100,2);
    }
    else{
        $totalvariacionnortetot = 0;
    }
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('E31', $totalmedidonortetotant)
        ->setCellValue('F31', $totalmedidonortetotact)
        ->setCellValue('G31', $totalvariacionnortetot);

    $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorTotalTotal($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'Residencial') $fila = 26; if($des_uso == 'Res. y Com.') $fila = 27; if($des_uso == 'Oficial') $fila = 28; if($des_uso == 'Industrial') $fila = 29;
        if($des_uso == 'Comercial') $fila = 30;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('H'.$fila, $cant1)
            ->setCellValue('I'.$fila, $cant2)
            ->setCellValue('J'.$fila, $variacion);
        $totalmedidototaltotant += $cant1;
        $totalmedidototaltotact += $cant2;
    }oci_free_statement($registros);

   /* $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorTotalTotalZF($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'ZF Oficial') $fila = 4567;
        if($des_uso == 'ZF Industrial') $fila = 35; if($des_uso == 'ZF Comercial') $fila = 36;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('H'.$fila, $cant1)
            ->setCellValue('I'.$fila, $cant2)
            ->setCellValue('J'.$fila, $variacion);
        $totalmedidototaltotant += $cant1;
        $totalmedidototaltotact += $cant2;

    }oci_free_statement($registros);*/
    $totalvariaciontotaltot = round((($totalmedidototaltotact-$totalmedidototaltotant)/$totalmedidototaltotant)*100,2);

    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('H31', $totalmedidototaltotant)
        ->setCellValue('I31', $totalmedidototaltotact)
        ->setCellValue('J31', $totalvariaciontotaltot);

    //  $objPHPExcel->getActiveSheet()->setTitle('Agua Red');

    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('A34:J34');
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('A35:A36');
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('B35:D35');
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('E35:G35');
    $objPHPExcel->setActiveSheetIndex(3)->mergeCells('H35:J35');
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A34:J34')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A35:A36')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('B35:J35')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('B36:J36')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle('A42:J42')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A34:J36")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A37:A41")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('A34', 'VOLUMEN AGUA DE POZO FACTURADA EN M3 - TOTAL  '.$proyecto.' PERIODO '.$periodo)
        ->setCellValue('A35', 'Uso')
        ->setCellValue('B35', 'Zona Este')
        ->setCellValue('E35', 'Zona Norte')
        ->setCellValue('H35', 'Este + Norte')
        ->setCellValue('B36', $perini)
        ->setCellValue('C36', $perfin)
        ->setCellValue('D36', 'Variacion (%)')
        ->setCellValue('E36', $perini)
        ->setCellValue('F36', $perfin)
        ->setCellValue('G36', 'Variacion (%)')
        ->setCellValue('H36', $perini)
        ->setCellValue('I36', $perfin)
        ->setCellValue('J36', 'Variacion (%)')
        ->setCellValue('A37', 'Residencial')
        ->setCellValue('A38', 'Mixto')
        ->setCellValue('A39', 'Oficial')
        ->setCellValue('A40', 'Industrial')
        ->setCellValue('A41', 'Comercial');
        //->setCellValue('A48', 'ZF Industrial')
        //->setCellValue('A49', 'ZF Comercial');

    $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorTotalEstePozo($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'Residencial') $fila = 37; if($des_uso == 'Res. y Com.') $fila = 38; if($des_uso == 'Oficial') $fila = 39; if($des_uso == 'Industrial') $fila = 40;
        if($des_uso == 'Comercial') $fila = 41;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('B'.$fila, $cant1)
            ->setCellValue('C'.$fila, $cant2)
            ->setCellValue('D'.$fila, $variacion);
        $totalmedidoestetotantpz += $cant1;
        $totalmedidoestetotactpz += $cant2;
    }oci_free_statement($registros);

   /* $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorTotalEsteZFPozo($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'ZF Oficial') $fila = 4567;
        if($des_uso == 'ZF Industrial') $fila = 48; if($des_uso == 'ZF Comercial') $fila = 49;

        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('B'.$fila, $cant1)
            ->setCellValue('C'.$fila, $cant2)
            ->setCellValue('D'.$fila, $variacion);
        $totalmedidoestetotantpz += $cant1;
        $totalmedidoestetotactpz += $cant2;

    }oci_free_statement($registros);*/
    $totalvariaciontotestepz = round((($totalmedidoestetotactpz-$totalmedidoestetotantpz)/$totalmedidoestetotantpz)*100,2);

    $objPHPExcel->setActiveSheetIndex(3)->getStyle("A42:J42")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('A42', 'Totales')
        ->setCellValue('B42', $totalmedidoestetotantpz)
        ->setCellValue('C42', $totalmedidoestetotactpz)
        ->setCellValue('D42', $totalvariaciontotestepz);

    $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorTotalNortePozo($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'Residencial') $fila = 37; if($des_uso == 'Res. y Com.') $fila = 38; if($des_uso == 'Oficial') $fila = 39; if($des_uso == 'Industrial') $fila = 40;
        if($des_uso == 'Comercial') $fila = 41;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('E'.$fila, $cant1)
            ->setCellValue('F'.$fila, $cant2)
            ->setCellValue('G'.$fila, $variacion);
        $totalmedidonortetotantpz += $cant1;
        $totalmedidonortetotactpz += $cant2;
    }oci_free_statement($registros);

   /* $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorTotalNorteZFPozo($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'ZF Oficial') $fila = 4567;
        if($des_uso == 'ZF Industrial') $fila = 48; if($des_uso == 'ZF Comercial') $fila = 49;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('E'.$fila, $cant1)
            ->setCellValue('F'.$fila, $cant2)
            ->setCellValue('G'.$fila, $variacion);
        $totalmedidonortetotantpz += $cant1;
        $totalmedidonortetotactpz += $cant2;

    }oci_free_statement($registros);*/
    if($totalmedidonortetotantpz > 0){
        $totalvariacionnortetotpz = round((($totalmedidonortetotactpz-$totalmedidonortetotantpz)/$totalmedidonortetotantpz)*100,2);
    }
    else{
        $totalvariacionnortetotpz =  0;
    }
    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('E42', $totalmedidonortetotantpz)
        ->setCellValue('F42', $totalmedidonortetotactpz)
        ->setCellValue('G42', $totalvariacionnortetotpz);

    $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorTotalTotalPozo($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'Residencial') $fila = 37; if($des_uso == 'Res. y Com.') $fila = 38; if($des_uso == 'Oficial') $fila = 39; if($des_uso == 'Industrial') $fila = 40;
        if($des_uso == 'Comercial') $fila = 41;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('H'.$fila, $cant1)
            ->setCellValue('I'.$fila, $cant2)
            ->setCellValue('J'.$fila, $variacion);
        $totalmedidototaltotantpz += $cant1;
        $totalmedidototaltotactpz += $cant2;
    }oci_free_statement($registros);

   /* $c=new ReportesGerencia();
    $registros=$c->MetrosMesAnteriorTotalTotalZFPozo($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $des_uso=utf8_decode(oci_result($registros,"DESC_USO"));
        $cant1=utf8_decode(oci_result($registros,"'$perini'"));
        $cant2=utf8_decode(oci_result($registros,"'$perfin'"));
        if($cant1 > 0)
            $variacion = round((($cant2-$cant1)/$cant1)*100,2);
        else $variacion = NULL;
        if($des_uso == 'ZF Oficial') $fila = 4567;
        if($des_uso == 'ZF Industrial') $fila = 48; if($des_uso == 'ZF Comercial') $fila = 49;
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue('H'.$fila, $cant1)
            ->setCellValue('I'.$fila, $cant2)
            ->setCellValue('J'.$fila, $variacion);
        $totalmedidototaltotantpz += $cant1;
        $totalmedidototaltotactpz += $cant2;
    }oci_free_statement($registros);*/
    $totalvariaciontotaltotpz = round((($totalmedidototaltotactpz-$totalmedidototaltotantpz)/$totalmedidototaltotantpz)*100,2);

    $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue('H42', $totalmedidototaltotantpz)
        ->setCellValue('I42', $totalmedidototaltotactpz)
        ->setCellValue('J42', $totalvariaciontotaltotpz);


    //mostrafr la hoja q se abrira
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/ConsolidadoMensual".$proyecto.'-'.$periodo.".xlsx";

    $objWriter->save($nomarch);
    echo $nomarch;
}
else{
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'NO SE PUEDE GENERAR EL ARCHIVO PORQUE EXISTEN SECTORES ABIERTOS PARA EL PERIODO '.$periodo);
    //mostrafr la hoja q se abrira
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Historico_Facturacion-".$proyecto.'-'.$periodo.".xlsx";

    $objWriter->save($nomarch);
    echo $nomarch;
}
?>