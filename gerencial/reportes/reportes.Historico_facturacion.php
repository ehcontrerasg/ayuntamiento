<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.repHisFac.php';
ini_set('memory_limit', '-1');
$periodo=$_POST['periodo'];
$proyecto=$_POST['proyecto'];

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
		->setTitle("Historico Facturacion ".$proyecto." ".$periodo)
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

if($cantidad == 0) {

    //HOJA FACTURADO POR SECTOR
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:K1');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:C2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E2:G2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('I2:K2');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:K1')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A2:C2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('E2:G2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('I2:K2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A3:C3')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('E3:G3')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('I3:K3')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:K1")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:K1")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:K2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A3:K3")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'REPORTE HISTORICO FACTURACION POR SECTOR ' . $proyecto . ' ' . $periodo)
        ->setCellValue('A2', 'FACTURADO TOTAL')
        ->setCellValue('E2', 'FACTURADO NORTE')
        ->setCellValue('I2', 'FACTURADO ESTE')
        ->setCellValue('A3', 'Sector')
        ->setCellValue('B3', 'No Facturas')
        ->setCellValue('C3', 'Facturado')
        ->setCellValue('E3', 'Sector')
        ->setCellValue('F3', 'No Facturas')
        ->setCellValue('G3', 'Facturado')
        ->setCellValue('I3', 'Sector')
        ->setCellValue('J3', 'No Facturas')
        ->setCellValue('K3', 'Facturado');
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(5);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(5);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(12);

    $c=new ReportesHisFac();
    $fila = 4;
    $registros=$c->historicoFacturasSectorTotal($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $sector    = utf8_decode(oci_result($registros,"SECTOR"));
        $facturas  = utf8_decode(oci_result($registros,"FACTURAS"));
        $facturado = utf8_decode(oci_result($registros,"FACTURADO"));
        $facturado = round($facturado);

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$fila, $sector)
            ->setCellValue('B'.$fila, $facturas)
            ->setCellValue('C'.$fila, $facturado);
        $totalfacturassector += $facturas;
        $totalfacturadosector += $facturado;
        $fila++;

    }oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":C".$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, 'Total')
        ->setCellValue('B'.$fila, $totalfacturassector)
        ->setCellValue('C'.$fila, $totalfacturadosector);

    $c=new ReportesHisFac();
    $fila = 4;
    $registros=$c->historicoFacturasSectorNorte($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $sectorn=utf8_decode(oci_result($registros,"SECTOR"));
        $facturasn=utf8_decode(oci_result($registros,"FACTURAS"));
        $facturadon=utf8_decode(oci_result($registros,"FACTURADO"));
        $facturadon = round($facturadon);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E'.$fila, $sectorn)
            ->setCellValue('F'.$fila, $facturasn)
            ->setCellValue('G'.$fila, $facturadon);
        $totalfacturassectorn += $facturasn;
        $totalfacturadosectorn += $facturadon;
        $fila++;
        if($proyecto == 'SD'){
            $i = new ReportesHisFac();
            $bandera = $i->InsertaDatosFacturacionSectorNorte($sectorn,$periodo,$facturasn,$facturadon,$proyecto);
            if($bandera == false){
                $error=$i->getmsgresult();
                $coderror=$i->getcodresult();
                // return $error;
            }
            else if($bandera == true) {
                //return;
            }
        }

    }oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("E".$fila.":G".$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('E'.$fila, 'Total')
        ->setCellValue('F'.$fila, $totalfacturassectorn)
        ->setCellValue('G'.$fila, $totalfacturadosectorn);

    $c=new ReportesHisFac();
    $fila = 4;
    $registros=$c->historicoFacturasSectorEste($proyecto, $periodo);
    while (oci_fetch($registros)) {
        $sectore=utf8_decode(oci_result($registros,"SECTOR"));
        $facturase=utf8_decode(oci_result($registros,"FACTURAS"));
        $facturadoe=utf8_decode(oci_result($registros,"FACTURADO"));
        $facturadoe = round($facturadoe);

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('I'.$fila, $sectore)
            ->setCellValue('J'.$fila, $facturase)
            ->setCellValue('K'.$fila, $facturadoe);
        $totalfacturassectore += $facturase;
        $totalfacturadosectore += $facturadoe;
        $fila++;
        $i = new ReportesHisFac();
        $bandera = $i->InsertaDatosFacturacionSectorEste($sectore,$periodo,$facturase,$facturadoe,$proyecto);
        if($bandera == false){
            $error=$i->getmsgresult();
            $coderror=$i->getcodresult();
            // return $error;
        }
        else if($bandera == true) {
            //return;
        }
    }oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("I".$fila.":K".$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('I'.$fila, 'Total')
        ->setCellValue('J'.$fila, $totalfacturassectore)
        ->setCellValue('K'.$fila, $totalfacturadosectore);

    $objPHPExcel->getActiveSheet()->setTitle('Sector');

    //HOJA FACTURADO POR CONCEPTO
    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Concepto');
    $objPHPExcel->addSheet($myWorkSheet, 1);
    $objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:K1');
    $objPHPExcel->setActiveSheetIndex(1)->mergeCells('A2:C2');
    $objPHPExcel->setActiveSheetIndex(1)->mergeCells('E2:G2');
    $objPHPExcel->setActiveSheetIndex(1)->mergeCells('I2:K2');

    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A1:K1')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A2:C2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('E2:G2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('I2:K2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A3:C3')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('E3:G3')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('I3:K3')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:K1")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle("A1:K1")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle("A2:K2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle("A3:K3")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(1)
        ->setCellValue('A1', 'REPORTE HISTORICO FACTURACION POR CONCEPTO ' . $proyecto . ' ' . $periodo)
        ->setCellValue('A2', 'FACTURADO TOTAL')
        ->setCellValue('E2', 'FACTURADO NORTE')
        ->setCellValue('I2', 'FACTURADO ESTE')
        ->setCellValue('A3', 'No')
        ->setCellValue('B3', 'Descripción')
        ->setCellValue('C3', 'Facturado')
        ->setCellValue('E3', 'No')
        ->setCellValue('F3', 'Descripción')
        ->setCellValue('G3', 'Facturado')
        ->setCellValue('I3', 'No')
        ->setCellValue('J3', 'Descripción')
        ->setCellValue('K3', 'Facturado');
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("A")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("B")->setWidth(24);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("C")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("D")->setWidth(5);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("E")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("F")->setWidth(24);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("G")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("H")->setWidth(5);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("I")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("J")->setWidth(24);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("K")->setWidth(12);
    $fila = 4;
    $a=new ReportesHisFac();
    $registrosg=$a->grupoConceptos();
    while (oci_fetch($registrosg)) {
        $id_grupo=utf8_decode(oci_result($registrosg,"ID_GRUPO"));
        $des_grupo=utf8_decode(oci_result($registrosg,"DES_GRUPO"));

        $c=new ReportesHisFac();
        $registros=$c->historicoFacturasConceptoTotal($proyecto, $periodo, $id_grupo);
        while (oci_fetch($registros)) {
            $facturado = utf8_decode(oci_result($registros,"FACTURADO"));
            $facturado = round($facturado);
            $objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('A'.$fila, $id_grupo)
                ->setCellValue('B'.$fila, utf8_decode($des_grupo))
                ->setCellValue('C'.$fila, $facturado);
            $totalfacturadoconcepto += $facturado;
        }oci_free_statement($registros);

    $c=new ReportesHisFac();
    $registros=$c->historicoFacturasConceptoNorte($proyecto, $periodo, $id_grupo);
    while (oci_fetch($registros)) {
        $facturadon = utf8_decode(oci_result($registros,"FACTURADO"));
        $facturadon = round($facturadon);
        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('E'.$fila, $id_grupo)
            ->setCellValue('F'.$fila, utf8_decode($des_grupo))
            ->setCellValue('G'.$fila, $facturadon);
        $totalfacturadoconcepton += $facturadon;
    }oci_free_statement($registros);

    if($proyecto == 'SD'){
        $i = new ReportesHisFac();
        $bandera = $i->InsertaDatosFacturacionConceptoNorte($id_grupo,$periodo,$facturadon,$proyecto);
        if($bandera == false){
            $error=$i->getmsgresult();
            $coderror=$i->getcodresult();
            // return $error;
        }
        else if($bandera == true) {
            //return;
        }
    }

    $c=new ReportesHisFac();
    $registros=$c->historicoFacturasConceptoEste($proyecto, $periodo, $id_grupo);
    while (oci_fetch($registros)) {
        $facturadoe = utf8_decode(oci_result($registros,"FACTURADO"));
        $facturadoe = round($facturadoe);
        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('I'.$fila, $id_grupo)
            ->setCellValue('J'.$fila, utf8_decode($des_grupo))
            ->setCellValue('K'.$fila, $facturadoe);
        $totalfacturadoconceptoe += $facturadoe;
        /*$fila++;
        if($fila==9||$fila==12){$fila++;}*/
    }oci_free_statement($registros);
      $i = new ReportesHisFac();
    $bandera = $i->InsertaDatosFacturacionConceptoEste($id_grupo,$periodo,$facturadoe,$proyecto);
    if($bandera == false){
        $error=$i->getmsgresult();
        $coderror=$i->getcodresult();
        // return $error;
    }
    else if($bandera == true) {
        //return;
    }

    $fila++;
   // if($fila==9||$fila==12){$fila++;}
}oci_free_statement($registrosg);
$objPHPExcel->setActiveSheetIndex(1)->getStyle("A".$fila.":k".$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)
    ->setCellValue('A'.$fila, 'Total')
    ->setCellValue('B'.$fila, '')
    ->setCellValue('C'.$fila, $totalfacturadoconcepto)
    ->setCellValue('E'.$fila, 'Total')
    ->setCellValue('F'.$fila, '')
    ->setCellValue('G'.$fila, $totalfacturadoconcepton)
    ->setCellValue('I'.$fila, 'Total')
    ->setCellValue('J'.$fila, '')
    ->setCellValue('K'.$fila, $totalfacturadoconceptoe);


    //HOJA FACTURADO POR USO
    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Uso');
    $objPHPExcel->addSheet($myWorkSheet, 2);
    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('A1:N1');
    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('A2:D2');
    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('F2:I2');
    $objPHPExcel->setActiveSheetIndex(2)->mergeCells('K2:N2');

    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A1:N1')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A2:D2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('F2:I2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('K2:N2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('A3:D3')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('F3:I3')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle('K3:N3')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle("A1:N1")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle("A1:N1")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle("A2:N2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)->getStyle("A3:N3")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A1', 'REPORTE HISTORICO FACTURACION POR USO ' . $proyecto . ' ' . $periodo)
        ->setCellValue('A2', 'FACTURADO TOTAL')
        ->setCellValue('F2', 'FACTURADO NORTE')
        ->setCellValue('K2', 'FACTURADO ESTE')
        ->setCellValue('A3', 'Uso')
        ->setCellValue('B3', 'No Facturas')
        ->setCellValue('C3', 'Facturado')
        ->setCellValue('D3', 'Fac. Sin Mora')
        ->setCellValue('F3', 'Uso')
        ->setCellValue('G3', 'No Facturas')
        ->setCellValue('H3', 'Facturado')
        ->setCellValue('I3', 'Fac. Sin Mora')
        ->setCellValue('K3', 'Uso')
        ->setCellValue('L3', 'No Facturas')
        ->setCellValue('M3', 'Facturado')
        ->setCellValue('N3', 'Fac. Sin Mora');
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("A")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("B")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("C")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("D")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("E")->setWidth(5);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("F")->setWidth(18);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("G")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("H")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("I")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("J")->setWidth(5);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("K")->setWidth(18);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("L")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("M")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("N")->setWidth(12);

    $fila = 4;
    $u = new ReportesHisFac();
    $usos = $u->grupoUsos();

    $totalfacturasusoTotal  = 0;
    $totalfacturadousoTotal = 0;
    $totalSinMoraUsoTotal   = 0;

    $totalfacturasuson      = 0;
    $totalfacturadouson     = 0;
    $totalSinMoraUsoNorte   = 0;

    $totalfacturasusoe      = 0;
    $totalfacturadousoe     = 0;
    $totalSinMoraUsoEste    = 0;

    $id_uso   = '';
    $desc_uso = '';

    while(oci_fetch($usos)){
        $id_uso    = oci_result($usos,"ID_USO");
        $desc_uso  = oci_result($usos,"DESC_USO");

        $c = new ReportesHisFac();
        $registros = $c->historicoFacturasDetalladoUsoTotal($proyecto, $periodo,$id_uso);

        while (oci_fetch($registros)) {
            $facturas  = utf8_decode(oci_result($registros, "FACTURAS"));
            $facturado = utf8_decode(oci_result($registros, "FACTURADO"));
            $facturado= round($facturado);
            $sinMora   = utf8_decode(oci_result($registros, "SIN_MORA"));

            $objPHPExcel->setActiveSheetIndex(2)
                ->setCellValue('A' . $fila, $desc_uso)
                ->setCellValue('B' . $fila, $facturas)
                ->setCellValue('C' . $fila, $facturado)
                ->setCellValue('D' . $fila, $sinMora);

            $totalfacturasusoTotal  += $facturas;
            $totalfacturadousoTotal += $facturado;
            $totalSinMoraUsoTotal   += $sinMora;

        }oci_free_statement($registros);

        $c=new ReportesHisFac();
        $registros=$c->historicoFacturasDetalladoSectorNorte($proyecto, $periodo,$id_uso);

        while (oci_fetch($registros)) {

            $facturasn  = utf8_decode(oci_result($registros,"FACTURAS"));
            $facturadon = utf8_decode(oci_result($registros,"FACTURADO"));
            $facturadon = round($facturadon);
            $sinMora    = utf8_decode(oci_result($registros,"SIN_MORA"));
            $objPHPExcel->setActiveSheetIndex(2)
                ->setCellValue('F'.$fila, $desc_uso)
                ->setCellValue('G'.$fila, $facturasn)
                ->setCellValue('H'.$fila, $facturadon)
                ->setCellValue('I'.$fila, $sinMora);

            $totalfacturasuson    += $facturasn;
            $totalfacturadouson   += $facturadon;
            $totalSinMoraUsoNorte += $sinMora;

            if($proyecto == 'SD'){
                $i = new ReportesHisFac();
                $bandera = $i->InsertaDatosFacturacionUsoNorte($desc_uso,$periodo,$facturasn,$facturadon,$proyecto);
                if($bandera == false){
                    $error=$i->getmsgresult();
                    $coderror=$i->getcodresult();
                    // return $error;
                }
                else if($bandera == true) {
                    //return;
                }
            }

        }oci_free_statement($registros);


        if($proyecto == 'SD'){
        null;

        }

        $c=new ReportesHisFac();
        $registros=$c->historicoFacturasDetalladoSectorEste($proyecto, $periodo,$id_uso);
        while (oci_fetch($registros)) {

            $facturase  = utf8_decode(oci_result($registros,"FACTURAS"));
            $facturadoe = utf8_decode(oci_result($registros,"FACTURADO"));
            $facturadoe = round($facturadoe);
            $sinMora    = utf8_decode(oci_result($registros,"SIN_MORA"));
            $objPHPExcel->setActiveSheetIndex(2)
                ->setCellValue('K'.$fila, $desc_uso)
                ->setCellValue('L'.$fila, $facturase)
                ->setCellValue('M'.$fila, $facturadoe)
                ->setCellValue('N'.$fila, $sinMora);

            $totalfacturasusoe   += $facturase;
            $totalfacturadousoe  += $facturadoe;
            $totalSinMoraUsoEste += $sinMora;
            $i = new ReportesHisFac();
            $bandera = $i->InsertaDatosFacturacionUsoEste($desc_uso,$periodo,$facturase,$facturadoe,$proyecto);
            if($bandera == false){
                $error=$i->getmsgresult();
                $coderror=$i->getcodresult();
                // return $error;
            }
            else if($bandera == true) {
                //return;
            }
        }oci_free_statement($registros);


        $fila++;
    }oci_free_statement($usos);

    $c = new ReportesHisFac();
    $registros = $c->historicoFacturasUsoTotalZF($proyecto, $periodo,$id_uso);
    while (oci_fetch($registros)) {
        $facturas  = utf8_decode(oci_result($registros, "FACTURAS"));
        $facturado = utf8_decode(oci_result($registros, "FACTURADO"));
        $facturado = round($facturado);
        $sinMora   = utf8_decode(oci_result($registros, "SIN_MORA"));
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A' . $fila, "Zonas Francas")
            ->setCellValue('B' . $fila, $facturas)
            ->setCellValue('C' . $fila, $facturado)
            ->setCellValue('D' . $fila, $sinMora);
        //$fila++;
        $totalfacturasusoTotal += $facturas;
        $totalfacturadousoTotal += $facturado;
        $totalSinMoraUsoTotal += $sinMora;
    }oci_free_statement($registros);

    $c=new ReportesHisFac();
    $registros=$c->historicoFacturasUsoNorteZF($proyecto, $periodo,$id_uso);
    while (oci_fetch($registros)) {
        //$uson=oci_result($registros,"USO");
        $facturasn  = utf8_decode(oci_result($registros,"FACTURAS"));
        $facturadon = utf8_decode(oci_result($registros,"FACTURADO"));
        $facturadon = round($facturadon);
        $sinMora    = utf8_decode(oci_result($registros,"SIN_MORA"));
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('F'.$fila, 'Zonas Francas')
            ->setCellValue('G'.$fila, $facturasn)
            ->setCellValue('H'.$fila, $facturadon)
            ->setCellValue('I'.$fila, $sinMora);

        // $fila++;
        $totalfacturasuson += $facturasn;
        $totalfacturadouson += $facturadon;
        $totalfacturaszfn += $facturasn;
        $totalfacturadozfn += $facturadon;
        $totalSinMoraUsoNorte += $sinMora;
    }oci_free_statement($registros);

    $i = new ReportesHisFac();
    $bandera = $i->InsertaDatosFacturacionUsoNorte('Zonas Francas',$periodo,$facturasn,$facturadon,$proyecto);
    if($bandera == false){
        $error=$i->getmsgresult();
        $coderror=$i->getcodresult();
        // return $error;
    }
    else if($bandera == true) {
        //return;
    }


    $c=new ReportesHisFac();
    $registros=$c->historicoFacturasUsoEsteZF($proyecto, $periodo,$id_uso);
    while (oci_fetch($registros)) {
        $facturase  = utf8_decode(oci_result($registros,"FACTURAS"));
        $facturadoe = utf8_decode(oci_result($registros,"FACTURADO"));
        $facturadoe = round($facturadoe);
        $sinMora    = utf8_decode(oci_result($registros,"SIN_MORA"));
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('K'.$fila, 'Zonas Francas')
            ->setCellValue('L'.$fila, $facturase)
            ->setCellValue('M'.$fila, $facturadoe)
            ->setCellValue('N'.$fila, $sinMora);

        $totalfacturasusoe   += $facturase;
        $totalfacturadousoe  += $facturadoe;
        $totalfacturaszfe    += $facturase;
        $totalfacturadozfe   += $facturadoe;
        $totalSinMoraUsoEste += $sinMora;

    }oci_free_statement($registros);



    $i = new ReportesHisFac();
    $bandera = $i->InsertaDatosFacturacionUsoEste('Zonas Francas',$periodo,$facturase,$facturadoe,$proyecto);
    if($bandera == false){
        $error=$i->getmsgresult();
        $coderror=$i->getcodresult();
        // return $error;
    }
    else if($bandera == true) {
        //return;
    }
    $fila++;
    $objPHPExcel->setActiveSheetIndex(2)->getStyle("A".$fila.":D".$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('A'.$fila, 'Total')
        ->setCellValue('B'.$fila, $totalfacturasusoTotal)
        ->setCellValue('C'.$fila, $totalfacturadousoTotal)
        ->setCellValue('D'.$fila, $totalSinMoraUsoTotal);

    $objPHPExcel->setActiveSheetIndex(2)->getStyle("F".$fila.":I".$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('F'.$fila, 'Total')
        ->setCellValue('G'.$fila, $totalfacturasuson)
        ->setCellValue('H'.$fila, $totalfacturadouson)
        ->setCellValue('I'.$fila, $totalSinMoraUsoNorte);

    $objPHPExcel->setActiveSheetIndex(2)->getStyle("K".$fila.":N".$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(2)
        ->setCellValue('K'.$fila, 'Total')
        ->setCellValue('L'.$fila, $totalfacturasusoe)
        ->setCellValue('M'.$fila, $totalfacturadousoe)
        ->setCellValue('N'.$fila, $totalSinMoraUsoEste);

	//HOJA FACTURADO USUARIOS MEDIDOS POR CONCEPTO
	$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Medidos Por Concepto');
	$objPHPExcel->addSheet($myWorkSheet, 3);
	$objPHPExcel->setActiveSheetIndex(3)->mergeCells('A1:K1');
	$objPHPExcel->setActiveSheetIndex(3)->mergeCells('A2:C2');
	$objPHPExcel->setActiveSheetIndex(3)->mergeCells('E2:G2');
	$objPHPExcel->setActiveSheetIndex(3)->mergeCells('I2:K2');
	
	$objPHPExcel->setActiveSheetIndex(3)->getStyle('A1:K1')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(3)->getStyle('A2:C2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(3)->getStyle('E2:G2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(3)->getStyle('I2:K2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(3)->getStyle('A3:C3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(3)->getStyle('E3:G3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(3)->getStyle('I3:K3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(3)->getStyle("A1:K1")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(3)->getStyle("A1:K1")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(3)->getStyle("A2:K2")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(3)->getStyle("A3:K3")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(3)
		->setCellValue('A1', 'REPORTE HISTORICO FACTURACION USUARIOS MEDIDOS POR CONCEPTO '.$proyecto.' '.$periodo)
		->setCellValue('A2', 'FACTURADO TOTAL')
		->setCellValue('E2', 'FACTURADO NORTE')
		->setCellValue('I2', 'FACTURADO ESTE')
    	->setCellValue('A3', 'No')
        ->setCellValue('B3', 'Descripción')
        ->setCellValue('C3', 'Facturado')
		->setCellValue('E3', 'No')
        ->setCellValue('F3', 'Descripción')
        ->setCellValue('G3', 'Facturado')
		->setCellValue('I3', 'No')
        ->setCellValue('J3', 'Descripción')
        ->setCellValue('K3', 'Facturado');
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("A")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("B")->setWidth(24);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("C")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("D")->setWidth(5);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("E")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("F")->setWidth(24);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("G")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("H")->setWidth(5);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("I")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("J")->setWidth(24);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("K")->setWidth(12);
	
	$fila = 4;
	$d=new ReportesHisFac();
	$registrosh=$d->grupoConceptos();

    $totalfacturadoconceptoM  = 0;
    $totalfacturadoconceptonM = 0;
    $totalfacturadoconceptoeM = 0;

	while (oci_fetch($registrosh)) {
		$id_grupo=utf8_decode(oci_result($registrosh,"ID_GRUPO"));
		$desc_grupo=utf8_decode(oci_result($registrosh,"DES_GRUPO"));

		$c=new ReportesHisFac();
		$registros=$c->historicoFacturasConceptoTotalMedido($proyecto, $periodo, $id_grupo);
		while (oci_fetch($registros)) {
			/*$id_grupo=utf8_decode(oci_result($registros,"ID_GRUPO"));
			$desc_grupo=utf8_decode(oci_result($registros,"DES_GRUPO"));*/
            $facturadoM=utf8_decode(oci_result($registros,"FACTURADO"));
            $facturadoM=round($facturadoM);
			$objPHPExcel->setActiveSheetIndex(3)
				->setCellValue('A'.$fila, $id_grupo)
				->setCellValue('B'.$fila, utf8_encode($desc_grupo))
				->setCellValue('C'.$fila, $facturadoM);
			$totalfacturadoconceptoM += $facturadoM;
          //  $fila++;
		}oci_free_statement($registros);
        // $fila = 4;
		$c=new ReportesHisFac();
		$registros=$c->historicoFacturasConceptoNorteMedido($proyecto, $periodo, $id_grupo);
		while (oci_fetch($registros)) {
			$facturadonM=utf8_decode(oci_result($registros,"FACTURADO"));
			$facturadonM=round($facturadonM);
			$objPHPExcel->setActiveSheetIndex(3)
				->setCellValue('E'.$fila, $id_grupo)
				->setCellValue('F'.$fila, utf8_encode($desc_grupo))
				->setCellValue('G'.$fila, $facturadonM);
			$totalfacturadoconceptonM += $facturadonM;
		}oci_free_statement($registros);

		$c=new ReportesHisFac();
		$registros=$c->historicoFacturasConceptoEsteMedido($proyecto, $periodo, $id_grupo);
		while (oci_fetch($registros)) {
			$facturadoeM=utf8_decode(oci_result($registros,"FACTURADO"));
			$facturadoeM=round($facturadoeM);
			$objPHPExcel->setActiveSheetIndex(3)
				->setCellValue('I'.$fila, $id_grupo)
				->setCellValue('J'.$fila, utf8_encode($desc_grupo))
				->setCellValue('K'.$fila, $facturadoeM);
			$totalfacturadoconceptoeM += $facturadoeM;

		}oci_free_statement($registros);
		$fila++;
	}oci_free_statement($registrosh);

	$objPHPExcel->setActiveSheetIndex(3)->getStyle("A".$fila.":K".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(3)
		->setCellValue('A'.$fila, 'Total')
		->setCellValue('B'.$fila, '')
		->setCellValue('C'.$fila, $totalfacturadoconceptoM)
		->setCellValue('E'.$fila, 'Total')
		->setCellValue('F'.$fila, '')
		->setCellValue('G'.$fila, $totalfacturadoconceptonM)
		->setCellValue('I'.$fila, 'Total')
		->setCellValue('J'.$fila, '')
		->setCellValue('K'.$fila, $totalfacturadoconceptoeM);
	
	//HOJA FACTURADO AGRUPADO POR CONCEPTO 
	
	$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Concepto Agrupado');
	$objPHPExcel->addSheet($myWorkSheet, 4);
	$objPHPExcel->setActiveSheetIndex(4)->mergeCells('A1:K1');
	$objPHPExcel->setActiveSheetIndex(4)->mergeCells('A2:C2');
	$objPHPExcel->setActiveSheetIndex(4)->mergeCells('E2:G2');
	$objPHPExcel->setActiveSheetIndex(4)->mergeCells('I2:K2');
	
	$objPHPExcel->setActiveSheetIndex(4)->getStyle('A1:K1')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(4)->getStyle('A2:C2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(4)->getStyle('E2:G2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(4)->getStyle('I2:K2')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(4)->getStyle('A3:C3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(4)->getStyle('E3:G3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(4)->getStyle('I3:K3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(4)->getStyle("A1:K1")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(4)->getStyle("A1:K1")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(4)->getStyle("A2:K2")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(4)->getStyle("A3:K3")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(4)
		->setCellValue('A1', 'REPORTE HISTORICO FACTURACION POR CONCEPTO AGRUPADO '.$proyecto.' '.$periodo)
		->setCellValue('A2', 'FACTURADO TOTAL')
		->setCellValue('E2', 'FACTURADO NORTE')
		->setCellValue('I2', 'FACTURADO ESTE')
    	->setCellValue('A3', 'Concepto')
        ->setCellValue('B3', 'Descripción')
        ->setCellValue('C3', 'Facturado')
		->setCellValue('E3', 'Concepto')
        ->setCellValue('F3', 'Descripción')
        ->setCellValue('G3', 'Facturado')
		->setCellValue('I3', 'Concepto')
        ->setCellValue('J3', 'Descripción')
        ->setCellValue('K3', 'Facturado');
	$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension("A")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension("B")->setWidth(24);
	$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension("C")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension("D")->setWidth(5);
	$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension("E")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension("F")->setWidth(24);
	$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension("G")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension("H")->setWidth(5);
	$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension("I")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension("J")->setWidth(24);
	$objPHPExcel->setActiveSheetIndex(4)->getColumnDimension("K")->setWidth(12);
	
	$fila = 4;
	$cont = 1;
	$d=new ReportesHisFac();
	$registrosh=$d->grupoNiveles();
	while (oci_fetch($registrosh)) {
		$des_nivel=utf8_decode(oci_result($registrosh,"NIVEL_AGRUPA"));
		$c=new ReportesHisFac();	
		$registros=$c->historicoFacturasConceptoAgrupado($proyecto, $periodo, $des_nivel);
		while (oci_fetch($registros)) {
			$facturado=utf8_decode(oci_result($registros,"FACTURADO"));
			$facturado=round($facturado);
			$objPHPExcel->setActiveSheetIndex(4)
				->setCellValue('A'.$fila, $cont)
				->setCellValue('B'.$fila, $des_nivel)
				->setCellValue('C'.$fila, $facturado);
			$totalfacturado += $facturado;
		}oci_free_statement($registros);
		
		$c=new ReportesHisFac();
		$registros=$c->historicoFacturasConceptoAgrupadoN($proyecto, $periodo, $des_nivel);
		while (oci_fetch($registros)) {
			$facturadon=utf8_decode(oci_result($registros,"FACTURADO"));
			$facturadon=round($facturadon);
			$objPHPExcel->setActiveSheetIndex(4)
				->setCellValue('E'.$fila, $cont)
				->setCellValue('F'.$fila, $des_nivel)
				->setCellValue('G'.$fila, $facturadon);
			$totalfacturadon += $facturadon;

            if($proyecto == 'SD'){
                $i = new ReportesHisFac();
                $bandera = $i->InsertaDatosFacturacionNorte($des_nivel,$periodo,$facturadon,$proyecto);
                if($bandera == false){
                    $error=$i->getmsgresult();
                    $coderror=$i->getcodresult();
                    // return $error;
                }
                else if($bandera == true) {
                    //return;
                }
            }

		}oci_free_statement($registros);
		
		$c=new ReportesHisFac();
		$registros=$c->historicoFacturasConceptoAgrupadoE($proyecto, $periodo, $des_nivel);
		while (oci_fetch($registros)) {
			$facturadoe=utf8_decode(oci_result($registros,"FACTURADO"));
			$facturadoe=round($facturadoe);
			$objPHPExcel->setActiveSheetIndex(4)
				->setCellValue('I'.$fila, $cont)
				->setCellValue('J'.$fila, $des_nivel)
				->setCellValue('K'.$fila, $facturadoe);
			$totalfacturadoe += $facturadoe;

            $i = new ReportesHisFac();
            $bandera = $i->InsertaDatosFacturacionEste($des_nivel,$periodo,$facturadoe,$proyecto);
            if($bandera == false){
                $error=$i->getmsgresult();
                $coderror=$i->getcodresult();
                // return $error;
            }
            else if($bandera == true) {
                //return;
            }
		}oci_free_statement($registros);
		$cont++;
		$fila++;
	}oci_free_statement($registrosh);
	$objPHPExcel->setActiveSheetIndex(4)->getStyle("A".$fila.":K".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(4)
		->setCellValue('A'.$fila, 'Total')
		->setCellValue('B'.$fila, '')
		->setCellValue('C'.$fila, $totalfacturado)
		->setCellValue('E'.$fila, 'Total')
		->setCellValue('F'.$fila, '')
		->setCellValue('G'.$fila, $totalfacturadon)	
		->setCellValue('I'.$fila, 'Total')
		->setCellValue('J'.$fila, '')
		->setCellValue('K'.$fila, $totalfacturadoe);

    //mostrafr la hoja q se abrira
	$objPHPExcel->setActiveSheetIndex(1);

	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	$nomarch="../../temp/Historico_Facturacion-".$proyecto.'-'.$periodo.".xlsx";
	
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