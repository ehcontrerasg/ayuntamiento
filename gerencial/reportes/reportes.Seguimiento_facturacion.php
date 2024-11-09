<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.repSegFac.php';
ini_set('memory_limit', '-1');
$periodo=$_POST['periodo'];
$proyecto=$_POST['proyecto'];

$v=new ReportesSegFac();
$registrosv=$v->verificaCierreZonas($proyecto, $periodo);
while (oci_fetch($registrosv)) {
    $cantidad=utf8_decode(oci_result($registrosv,"CANTIDAD"));
}oci_free_statement($registrosv);


$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Seguimiento Facturacion ".$proyecto." ".$periodo)
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

if($proyecto == 'SD') $acueducto = 'CAASD';
if($proyecto == 'BC') $acueducto = 'CORAABO';


//if($cantidad == 0) {

    //HOJA FACTURADO POR CLIENTE POR CONCEPTO
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:T1');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:A3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:B3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:D2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E2:E3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F2:F3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G2:G3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H2:H3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('I2:I3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J2:J3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K2:K3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('L2:L3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('M2:M3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('N2:N3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('O2:O3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('P2:P3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('Q2:Q3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('R2:R3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('S2:S3');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('T2:T3');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:T1')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:T3')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:T1")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:T3")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'REPORTE FACTURACION POR CLIENTE Y CONCEPTO '. $acueducto .' '. $periodo)
        ->setCellValue('A2', 'CODIGO INMUEBLE ')
        ->setCellValue('B2', 'ACOMETIDA')
        ->setCellValue('C2', 'AGUA')
        ->setCellValue('C3', 'Red')
        ->setCellValue('D3', 'Pozo')
        ->setCellValue('E2', 'ALCANTARILLADO')
        ->setCellValue('F2', 'CARGOS DIFERIDOS')
        ->setCellValue('G2', 'CORTE YRECONEXION')
        ->setCellValue('H2', 'DER. INCORPORACION')
        ->setCellValue('I2', 'FIANZA')
        ->setCellValue('J2', 'MANT. MEDIDOR')
        ->setCellValue('K2', 'MORA')
        ->setCellValue('L2', 'SOLICITUD ACOMETIDA')
        ->setCellValue('M2', 'EMPALME')
        ->setCellValue('N2', 'EMPALME AGUA RESIDUAL')
        ->setCellValue('O2', 'MEDIDOR')
        ->setCellValue('P2', 'SUPERVISION')
        ->setCellValue('Q2', 'SALDO A FAVOR')
        ->setCellValue('R2', 'PAGOS RECONEXION')
        ->setCellValue('S2', 'PAGOS DER.INCORPORACION')
        ->setCellValue('T2', 'PAGOS FIANZA');

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Q")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("R")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("S")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("T")->setWidth(20);

    $c=new ReportesSegFac();
    $fila = 4;
    $registros=$c->seguimientoFacturasClienteConcepto($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $inmueble    = utf8_decode(oci_result($registros,"CODIGO_INM"));
        $acometida  = utf8_decode(oci_result($registros,"21"));
        $red = utf8_decode(oci_result($registros,"1"));
        $pozo = utf8_decode(oci_result($registros,"3"));
        $alcantred    = utf8_decode(oci_result($registros,"2"));
        $alcantpozo    = utf8_decode(oci_result($registros,"4"));
        $diferido  = utf8_decode(oci_result($registros,"50"));
        $reconexion = utf8_decode(oci_result($registros,"20"));
        $derinco = utf8_decode(oci_result($registros,"93"));
        $fianza    = utf8_decode(oci_result($registros,"22"));
        $mantmedidor  = utf8_decode(oci_result($registros,"11"));
        $mora = utf8_decode(oci_result($registros,"10"));
        $solacom = utf8_decode(oci_result($registros,"101"));
        $empalme    = utf8_decode(oci_result($registros,"28"));
        $empalmeres  = utf8_decode(oci_result($registros,"128"));
        $medidor = utf8_decode(oci_result($registros,"27"));
        $supervision = utf8_decode(oci_result($registros,"30"));
        $saldo1    = utf8_decode(oci_result($registros,"421"));
        $saldo2    = utf8_decode(oci_result($registros,"403"));
        $saldo3    = utf8_decode(oci_result($registros,"424"));
        $saldo4    = utf8_decode(oci_result($registros,"404"));
        $saldo5    = utf8_decode(oci_result($registros,"402"));
        $saldo6    = utf8_decode(oci_result($registros,"502"));
        $saldo7    = utf8_decode(oci_result($registros,"593"));
        $saldo8    = utf8_decode(oci_result($registros,"450"));
        $saldo9    = utf8_decode(oci_result($registros,"594"));
        $saldo10    = utf8_decode(oci_result($registros,"528"));
        $saldo11    = utf8_decode(oci_result($registros,"428"));
        $saldo12    = utf8_decode(oci_result($registros,"494"));
        $saldo13    = utf8_decode(oci_result($registros,"503"));
        $saldo14    = utf8_decode(oci_result($registros,"425"));
        $saldo15    = utf8_decode(oci_result($registros,"411"));
        $saldo16    = utf8_decode(oci_result($registros,"427"));
        $saldo17    = utf8_decode(oci_result($registros,"410"));
        $saldo18    = utf8_decode(oci_result($registros,"499"));
        $saldo19    = utf8_decode(oci_result($registros,"453"));
        $saldo20    = utf8_decode(oci_result($registros,"595"));
        $saldo21    = utf8_decode(oci_result($registros,"452"));
        $saldo22    = utf8_decode(oci_result($registros,"555"));
        $saldo23    = utf8_decode(oci_result($registros,"501"));
        $saldo24    = utf8_decode(oci_result($registros,"430"));
        $saldo25    = utf8_decode(oci_result($registros,"401"));
        $pago_rec  = utf8_decode(oci_result($registros,"420"));
        $pago_der = utf8_decode(oci_result($registros,"413"));
        $pago_fia = utf8_decode(oci_result($registros,"412"));

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$fila, ($inmueble+0))
            ->setCellValue('B'.$fila, ($acometida+0))
            ->setCellValue('C'.$fila, ($red+0))
            ->setCellValue('D'.$fila, ($pozo+0))
            ->setCellValue('E'.$fila, ($alcantred + $alcantpozo))
            ->setCellValue('F'.$fila, ($diferido+0))
            ->setCellValue('G'.$fila, ($reconexion+0))
            ->setCellValue('H'.$fila, ($derinco+0))
            ->setCellValue('I'.$fila, ($fianza+0))
            ->setCellValue('J'.$fila, ($mantmedidor+0))
            ->setCellValue('K'.$fila, ($mora+0))
            ->setCellValue('L'.$fila, ($solacom+0))
            ->setCellValue('M'.$fila, ($empalme+0))
            ->setCellValue('N'.$fila, ($empalmeres+0))
            ->setCellValue('O'.$fila, ($medidor+0))
            ->setCellValue('P'.$fila, ($supervision+0))
            ->setCellValue('Q'.$fila, ($saldo1 + $saldo2 + $saldo3 + $saldo4 + $saldo5 + $saldo6 + $saldo7 + $saldo8 + $saldo9 + $saldo10 + $saldo11 + $saldo12 + $saldo13 + $saldo14 + $saldo15+ $saldo16 + $saldo17 + $saldo18 + $saldo19 + $saldo20 + $saldo21 + $saldo22 + $saldo23 + $saldo24 + $saldo25))
            ->setCellValue('R'.$fila, ($pago_rec+0))
            ->setCellValue('S'.$fila, ($pago_der+0))
            ->setCellValue('T'.$fila, ($pago_fia+0));
        $totalacom += $acometida;
        $totalred += $red;
        $totalpozo += $pozo;
        $totalalcan += ($alcantred + $alcantpozo);
        $totaldif += $diferido;
        $totalrec += $reconexion;
        $totalderinco += $derinco;
        $totalfianza += $fianza;
        $totalmantmed += $mantmedidor;
        $totalmora += $mora;
        $totalsolacom += $solacom;
        $totalempalme += $empalme;
        $totalempres += $empalmeres;
        $totalmedidor += $medidor;
        $totalsuper += $supervision;
        $totalsaldo += ($saldo1 + $saldo2 + $saldo3 + $saldo4 + $saldo5 + $saldo6 + $saldo7 + $saldo8 + $saldo9 + $saldo10 + $saldo11 + $saldo12 + $saldo13 + $saldo14 + $saldo15+ $saldo16 + $saldo17 + $saldo18 + $saldo19 + $saldo20 + $saldo21 + $saldo22 + $saldo23 + $saldo24 + $saldo25);
        $totalpagorec += $pago_rec;
        $totalpagoder += $pago_der;
        $totalpagofia += $pago_fia;

        $fila++;

    }oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":T".$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, 'Total')
        ->setCellValue('B'.$fila, $totalacom)
        ->setCellValue('C'.$fila, $totalred)
        ->setCellValue('D'.$fila, $totalpozo)
        ->setCellValue('E'.$fila, $totalalcan)
        ->setCellValue('F'.$fila, $totaldif)
        ->setCellValue('G'.$fila, $totalrec)
        ->setCellValue('H'.$fila, $totalderinco)
        ->setCellValue('I'.$fila, $totalfianza)
        ->setCellValue('J'.$fila, $totalmantmed)
        ->setCellValue('K'.$fila, $totalmora)
        ->setCellValue('L'.$fila, $totalsolacom)
        ->setCellValue('M'.$fila, $totalempalme)
        ->setCellValue('N'.$fila, $totalempres)
        ->setCellValue('O'.$fila, $totalmedidor)
        ->setCellValue('P'.$fila, $totalsuper)
        ->setCellValue('Q'.$fila, $totalsaldo)
        ->setCellValue('R'.$fila, $totalpagorec)
        ->setCellValue('S'.$fila, $totalpagoder)
        ->setCellValue('T'.$fila, $totalpagofia);

    $objPHPExcel->getActiveSheet()->setTitle('Cliente Por Concepto');

    //HOJA FACTURADO POR CONCEPTO

    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Concepto Agrupado');
    $objPHPExcel->addSheet($myWorkSheet, 1);
    $objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:C1');

    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A1:C1')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A2:C2')->applyFromArray($estiloTitulos);

    $objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:C1")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle("A2:C2")->getFont()->setBold(true);

    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A1', 'FACTURADO POR CONCEPTO '. $acueducto.' '.$periodo)
        ->setCellValue('A2', 'CODIGO')
        ->setCellValue('B2', 'CONCEPTO')
        ->setCellValue('C2', 'VALOR');
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("A")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("B")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("C")->setWidth(20);

    $fila = 3;
    $c=new ReportesSegFac();
        $registros=$c->seguimientoFacturasConcepto($proyecto, $periodo);
        while (oci_fetch($registros)) {
            $codigo = utf8_decode(oci_result($registros,"CONCEPTO"));
            $valfac = utf8_decode(oci_result($registros,"FACTURADO"));
            $valfac = round($valfac);
            if($codigo == '001') $descripcion = 'Agua';
            if($codigo == '003') $descripcion = 'Agua de Pozo';
            if($codigo == '007') $descripcion = 'Alcantarillado';
            if($codigo == '010') $descripcion = 'Mora';
            if($codigo == '011') $descripcion = 'Mantenimiento de Medidor';
            if($codigo == '020') $descripcion = 'Corte y Reconexión';
            if($codigo == '021') $descripcion = 'Acometida';
            if($codigo == '022') $descripcion = 'Fianza';
            if($codigo == '027') $descripcion = 'Medidor';
            if($codigo == '028') $descripcion = 'Empalme';
            if($codigo == '030') $descripcion = 'Supervisión';
            if($codigo == '050') $descripcion = 'Cargos Diferidos';
            if($codigo == '093') $descripcion = 'Derecho Incorporación';
            if($codigo == '101') $descripcion = 'Solicitud Acometida';
            if($codigo == '128') $descripcion = 'Empalme Agua Residual';
            if($codigo == '600') $descripcion = 'Saldo a Favor';
            if($codigo == '103' || $codigo == '155'){}
            else {
                $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('A' . $fila, '$codigo')
                    ->setCellValue('B' . $fila, $descripcion)
                    ->setCellValue('C' . $fila, $valfac);
                $totalfacturadoconcepto += $valfac;
                $fila++;
            }
        }oci_free_statement($registros);

    $objPHPExcel->setActiveSheetIndex(1)->getStyle("A".$fila.":C".$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A'.$fila, 'Total')
        ->setCellValue('B'.$fila, '')
        ->setCellValue('C'.$fila, $totalfacturadoconcepto)
        ;



    //mostrafr la hoja q se abrira
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Seguimiento_Facturacion-".$proyecto.'-'.$periodo.".xlsx";

    $objWriter->save($nomarch);
    echo $nomarch;
/*}
else{
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'NO SE PUEDE GENERAR EL ARCHIVO PORQUE EXISTEN SECTORES ABIERTOS PARA EL PERIODO '.$periodo);
    //mostrafr la hoja q se abrira
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Seguimiento_Facturacion-".$proyecto.'-'.$periodo.".xlsx";

    $objWriter->save($nomarch);
    echo $nomarch;
}*/
?>