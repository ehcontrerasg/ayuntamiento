<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.reportes_gerenciales.php';
include '../clases/class.repHisRec.php';
ini_set('memory_limit', '-1');
//set_time_limit(3600);
$proyecto=$_POST['proyecto'];
$fecini=$_POST['fecini'];
$fecfin=$_POST['fecfin'];

$explode = explode("-", $fecini);
$agno = $explode[0];
$mes = $explode[1];

$periodo = $agno.$mes;

$v=new ReportesHisRec();
$registrosv=$v->verificaCierreZonas($proyecto, $periodo);
while (oci_fetch($registrosv)) {
    $cantidad=utf8_decode(oci_result($registrosv,"CANTIDAD"));
}oci_free_statement($registrosv);

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->
	getProperties()
		->setCreator("AceaSoft")
		->setLastModifiedBy("AceaSoft")
		->setTitle("Historico Recaudacion ".$proyecto." ".$fecini." ".$fecfin)
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


if($cantidad == 0){
	//HOJA RECAUDADO POR SECTOR
	    
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
		->setCellValue('A1', utf8_decode('REPORTE HISTORICO RECAUDACION POR SECTOR ').$proyecto.' DEL '.$fecini.' AL '.$fecfin)
		->setCellValue('A2', 'RECAUDADO TOTAL')
		->setCellValue('E2', 'RECAUDADO NORTE')
		->setCellValue('I2', 'RECAUDADO ESTE')
    	->setCellValue('A3', 'Sector')
        ->setCellValue('B3', 'No Pagos')
        ->setCellValue('C3', 'Recaudado')
		->setCellValue('E3', 'Sector')
        ->setCellValue('F3', 'No Pagos')
        ->setCellValue('G3', 'Recaudado')
		->setCellValue('I3', 'Sector')
        ->setCellValue('J3', 'No Pagos')
        ->setCellValue('K3', 'Recaudado');
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
		
	$c=new ReportesHisRec();
	$fila = 4;
	$registros=$c->historicoRecaudoSectorTotal($proyecto, $fecini, $fecfin);
	while (oci_fetch($registros)) {
		$sector=utf8_decode(oci_result($registros,"SECTOR"));
		$pagos=utf8_decode(oci_result($registros,"PAGOS"));
		$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
		//$recaudado = round($recaudado);
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $sector)
			->setCellValue('B'.$fila, $pagos)
			->setCellValue('C'.$fila, $recaudado);
		$fila++;
		$totalpagossector += $pagos;
		$totalrecaudadosector += $recaudado;
	}oci_free_statement($registros);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$fila.":C".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$fila, 'Total')
		->setCellValue('B'.$fila, round($totalpagossector))
		->setCellValue('C'.$fila, round($totalrecaudadosector));
    	
	$c=new ReportesHisRec();
	$fila = 4;
	$registros=$c->historicoRecaudoSectorNorte($proyecto, $fecini, $fecfin);
	while (oci_fetch($registros)) {
		$sectorn=utf8_decode(oci_result($registros,"SECTOR"));
		$pagosn=utf8_decode(oci_result($registros,"PAGOS"));
		$recaudadon=utf8_decode(oci_result($registros,"RECAUDADO"));
		//$recaudadon = round($recaudadon);
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, $sectorn)
			->setCellValue('F'.$fila, $pagosn)
			->setCellValue('G'.$fila, $recaudadon);
		$fila++;
		$totalpagossectorn += $pagosn;
		$totalrecaudadosectorn += $recaudadon;
        if($proyecto == 'SD'){
            $i = new ReportesHisRec();
            $bandera = $i->InsertaDatosRecaudoSectorNorte($sectorn,$periodo,$pagosn,$recaudadon,$proyecto);
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
		->setCellValue('F'.$fila, round($totalpagossectorn))
		->setCellValue('G'.$fila, round($totalrecaudadosectorn));
    	
	$c=new ReportesHisRec();
	$fila = 4;
	$registros=$c->historicoRecaudoSectorEste($proyecto, $fecini, $fecfin);
	while (oci_fetch($registros)) {
		$sectore=utf8_decode(oci_result($registros,"SECTOR"));
		$pagose=utf8_decode(oci_result($registros,"PAGOS"));
		$recaudadoe=utf8_decode(oci_result($registros,"RECAUDADO"));
		//$recaudadoe = round($recaudadoe);
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('I'.$fila, $sectore)
			->setCellValue('J'.$fila, $pagose)
			->setCellValue('K'.$fila, $recaudadoe);
		$fila++;
		$totalpagossectore += $pagose;
		$totalrecaudadosectore += $recaudadoe;

        $i = new ReportesHisRec();
        $bandera = $i->InsertaDatosRecaudoSectorEste($sectore,$periodo,$pagose,$recaudadoe,$proyecto);
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
		->setCellValue('J'.$fila, round($totalpagossectore))
		->setCellValue('K'.$fila, round($totalrecaudadosectore));
	
	
	$objPHPExcel->getActiveSheet()->setTitle('Sector');
	//$objPHPExcel->setActiveSheetIndex(0);
	
	//HOJA RECAUDADO POR CONCEPTO
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
		->setCellValue('A1', utf8_decode('REPORTE HISTORICO RECAUDACION POR CONCEPTO ').$proyecto.' DEL '.$fecini.' AL '.$fecfin)
		->setCellValue('A2', 'RECAUDADO TOTAL')
		->setCellValue('E2', 'RECAUDADO NORTE')
		->setCellValue('I2', 'RECAUDADO ESTE')
    	->setCellValue('A3', 'No')
        ->setCellValue('B3', 'Descripción')
        ->setCellValue('C3', 'Recaudado')
		->setCellValue('E3', 'No')
        ->setCellValue('F3', 'Descripción')
        ->setCellValue('G3', 'Recaudado')
		->setCellValue('I3', 'No')
        ->setCellValue('J3', 'Descripción')
        ->setCellValue('K3', 'Recaudado');
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("A")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("B")->setWidth(30);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("C")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("D")->setWidth(5);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("E")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("F")->setWidth(30);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("G")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("H")->setWidth(5);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("I")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("J")->setWidth(30);
	$objPHPExcel->setActiveSheetIndex(1)->getColumnDimension("K")->setWidth(12);
	
	$fila = 4;
	$a=new ReportesHisRec();
	$registrosh=$a->grupoConceptos();
	while (oci_fetch($registrosh)) {
		$id_grupo=utf8_decode(oci_result($registrosh,"ID_GRUPO"));
		$des_grupo=utf8_decode(oci_result($registrosh,"DES_GRUPO"));
		
		if($id_grupo <> '35'){
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoConceptoTotal($proyecto, $fecini, $fecfin, $id_grupo);
			while (oci_fetch($registros)) {
				$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
				//$recaudado = round($recaudado);
				$objPHPExcel->setActiveSheetIndex(1)
					->setCellValue('A'.$fila, $id_grupo)
					->setCellValue('B'.$fila, utf8_encode($des_grupo))
					->setCellValue('C'.$fila, $recaudado);
                if($id_grupo == '30'){
                    $totalsaldofavor = $recaudado;
                }
				$totalrecaudadoconcepto += $recaudado;

			}oci_free_statement($registros);
			
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoConceptoTotalN($proyecto, $fecini, $fecfin, $id_grupo);
			while (oci_fetch($registros)) {
				$recaudadon=utf8_decode(oci_result($registros,"RECAUDADO"));
				//$recaudadon = round($recaudadon);
				$objPHPExcel->setActiveSheetIndex(1)
					->setCellValue('E'.$fila, $id_grupo)
					->setCellValue('F'.$fila, utf8_encode($des_grupo))
					->setCellValue('G'.$fila, $recaudadon);
                if($id_grupo == '30'){
                    $totalsaldofavorn = $recaudadon;
                }
				$totalrecaudadoconcepton += $recaudadon;
			}oci_free_statement($registros);

            if($proyecto == 'SD'){
                $i = new ReportesHisRec();
                $bandera = $i->InsertaDatosRecaudoConceptoNorte($id_grupo,$periodo,$recaudadon,$proyecto);
                if($bandera == false){
                    $error=$i->getmsgresult();
                    $coderror=$i->getcodresult();
                    // return $error;
                }
                else if($bandera == true) {
                    //return;
                }
            }
			
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoConceptoTotalE($proyecto, $fecini, $fecfin, $id_grupo);
			while (oci_fetch($registros)) {
				$recaudadoe=utf8_decode(oci_result($registros,"RECAUDADO"));
				//$recaudadoe = round($recaudadoe);
				$objPHPExcel->setActiveSheetIndex(1)
					->setCellValue('I'.$fila, $id_grupo)
					->setCellValue('J'.$fila, utf8_encode($des_grupo))
					->setCellValue('K'.$fila, $recaudadoe);
                    if($id_grupo == '30'){
                        $totalsaldofavore = $recaudadoe;
                    }
				$totalrecaudadoconceptoe += $recaudadoe;
			}oci_free_statement($registros);

            $i = new ReportesHisRec();
            $bandera = $i->InsertaDatosRecaudoConceptoEste($id_grupo,$periodo,$recaudadoe,$proyecto);
            if($bandera == false){
                $error=$i->getmsgresult();
                $coderror=$i->getcodresult();
                // return $error;
            }
            else if($bandera == true) {
                //return;
            }
		}
		if($id_grupo == '30'){
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoSaldofavorTotal($proyecto, $fecini, $fecfin);
			while (oci_fetch($registros)) {
				$recaudado=utf8_decode(oci_result($registros,"RECAUDADO"));
				//$recaudado = round($recaudado);
				$objPHPExcel->setActiveSheetIndex(1)
					->setCellValue('A'.$fila, $id_grupo)
					->setCellValue('B'.$fila, utf8_encode($des_grupo))
					->setCellValue('C'.$fila, $recaudado + $totalsaldofavor);
				$totalrecaudadoconcepto += $recaudado;
			}oci_free_statement($registros);
			
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoSaldofavorTotalN($proyecto, $fecini, $fecfin);
			while (oci_fetch($registros)) {
				$recaudadon=utf8_decode(oci_result($registros,"RECAUDADO"));
				//$recaudadon = round($recaudadon);
				$objPHPExcel->setActiveSheetIndex(1)
					->setCellValue('E'.$fila, $id_grupo)
					->setCellValue('F'.$fila, utf8_encode($des_grupo))
					->setCellValue('G'.$fila, $recaudadon + $totalsaldofavorn);
				$totalrecaudadoconcepton += $recaudadon;
			}oci_free_statement($registros);

            if($proyecto == 'SD'){
                $i = new ReportesHisRec();
                $bandera = $i->InsertaDatosRecaudoConceptoNorte($id_grupo,$periodo,$recaudadon,$proyecto);
                if($bandera == false){
                    $error=$i->getmsgresult();
                    $coderror=$i->getcodresult();
                    // return $error;
                }
                else if($bandera == true) {
                    //return;
                }
            }
			
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoSaldofavorTotalE($proyecto, $fecini, $fecfin);
			while (oci_fetch($registros)) {
				$recaudadoe=utf8_decode(oci_result($registros,"RECAUDADO"));
				//$recaudadoe = round($recaudadoe);
				$objPHPExcel->setActiveSheetIndex(1)
					->setCellValue('I'.$fila, $id_grupo)
					->setCellValue('J'.$fila, utf8_encode($des_grupo))
					->setCellValue('K'.$fila, $recaudadoe + $totalsaldofavore);
				$totalrecaudadoconceptoe += $recaudadoe;
			}oci_free_statement($registros);
            $i = new ReportesHisRec();
            $bandera = $i->InsertaDatosRecaudoConceptoEste($id_grupo,$periodo,$recaudadoe,$proyecto);
            if($bandera == false){
                $error=$i->getmsgresult();
                $coderror=$i->getcodresult();
                // return $error;
            }
            else if($bandera == true) {
                //return;
            }
		}
		$fila++;
	}oci_free_statement($registrosh);		
	$objPHPExcel->setActiveSheetIndex(1)->getStyle("A".$fila.":K".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(1)
		->setCellValue('A'.$fila, 'Total')
		->setCellValue('B'.$fila, '')
		->setCellValue('C'.$fila, round($totalrecaudadoconcepto))
		->setCellValue('E'.$fila, 'Total')
		->setCellValue('F'.$fila, '')
		->setCellValue('G'.$fila, round($totalrecaudadoconcepton))
		->setCellValue('I'.$fila, 'Total')
		->setCellValue('J'.$fila, '')
		->setCellValue('K'.$fila, round($totalrecaudadoconceptoe));
	
	//HOJA RECAUDADO POR USO
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
		->setCellValue('A1', utf8_decode('REPORTE HISTORICO RECAUDACION POR USO ').$proyecto.' DEL '.$fecini.' AL '.$fecfin)
		->setCellValue('A2', 'RECAUDADO TOTAL')
		->setCellValue('F2', 'RECAUDADO NORTE')
		->setCellValue('K2', 'RECAUDADO ESTE')
    	->setCellValue('A3', 'Uso')
        ->setCellValue('B3', 'No Pagos')
        ->setCellValue('C3', 'Recaudado')
        ->setCellValue('D3', 'Rec. Sin Mora')
		->setCellValue('F3', 'Uso')
        ->setCellValue('G3', 'No Pagos')
        ->setCellValue('H3', 'Recaudado')
        ->setCellValue('I3', 'Rec. Sin Mora')
		->setCellValue('K3', 'Uso')
        ->setCellValue('L3', 'No Pagos')
        ->setCellValue('M3', 'Recaudado')
        ->setCellValue('N3', 'Rec. Sin Mora');
	$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("A")->setWidth(18);
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
	$c=new ReportesHisRec();	
	$registros=$c->historicoRecaudoUsoTotal($proyecto, $fecini, $fecfin);
	$totalRecaudadoSinMora = 0;
    $totalpagosuso = 0;
    $totalrecaudadouso = 0;
	while (oci_fetch($registros)) {
		$uso       = oci_result($registros,"DESC_USO");
		$pagos     = utf8_decode(oci_result($registros,"PAGOS"));
		$recaudado = utf8_decode(oci_result($registros,"RECAUDOS"));
		//$recaudado = round($recaudado);
		$sin_mora  = utf8_decode(oci_result($registros,"SIN_MORA"));
        //$sin_mora  = round($sin_mora);
		$objPHPExcel->setActiveSheetIndex(2)
			->setCellValue('A'.$fila, $uso)
			->setCellValue('B'.$fila, $pagos)
			->setCellValue('C'.$fila, $recaudado)
			->setCellValue('D'.$fila, $sin_mora);
		$fila++;
		$totalpagosuso += $pagos;
		$totalrecaudadouso += $recaudado;
		$totalRecaudadoSinMora += $sin_mora;
	}oci_free_statement($registros);

   /* BORRAR $fila = 4;
    $c=new ReportesHisRec();
    $registros=$c->historicoRecaudoUsoTotalSinMora($proyecto, $fecini, $fecfin);
    while (oci_fetch($registros)) {
        $recaudadosm=utf8_decode(oci_result($registros,"RECAUDADO"));
        $recaudadosm = round($recaudadosm);
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('D'.$fila, $recaudadosm);
        $fila++;
        $totalrecaudadousosm += $recaudadosm;
    }oci_free_statement($registros);*/

	$c=new ReportesHisRec();
	$registros=$c->historicoRecaudoUsoTotalZF($proyecto, $fecini, $fecfin);
	while (oci_fetch($registros)) {
		//$uso       = oci_result($registros,"USO");
		$pagos     = utf8_decode(oci_result($registros,"PAGOS"));
		$recaudado = utf8_decode(oci_result($registros,"RECAUDADO"));
		//$recaudado = round($recaudado);
		$sinMoraZF = utf8_decode(oci_result($registros,"SIN_MORA"));
		//$sinMoraZF = round($sinMoraZF);

		$objPHPExcel->setActiveSheetIndex(2)
			->setCellValue('A'.$fila, 'Zonas Francas')
			->setCellValue('B'.$fila, $pagos)
			->setCellValue('C'.$fila, $recaudado)
			->setCellValue('D'.$fila, $sinMoraZF);
        $fila++;
		$totalpagosuso += $pagos;
		$totalrecaudadouso += $recaudado;
        $totalRecaudadoSinMora+=$sinMoraZF;
	}oci_free_statement($registros);

	/* BORRAR $fila = 11;
    $c=new ReportesHisRec();
    $registros=$c->historicoRecaudoUsoTotalZFSinMora($proyecto, $fecini, $fecfin);
    while (oci_fetch($registros)) {
        $recaudadosm=utf8_decode(oci_result($registros,"RECAUDADO"));
        $recaudadosm = round($recaudadosm);
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('D'.$fila, $recaudadosm);
        $fila++;
        $totalrecaudadousosm += $recaudadosm;
    }oci_free_statement($registros);*/

	$objPHPExcel->setActiveSheetIndex(2)->getStyle("A".$fila.":D".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(2)
		->setCellValue('A'.$fila, 'Total')
		->setCellValue('B'.$fila, round($totalpagosuso))
		->setCellValue('C'.$fila, round($totalrecaudadouso))
        ->setCellValue('D'.$fila, round($totalRecaudadoSinMora));
		
	$fila = 4;
	$c=new ReportesHisRec();	
	$registros=$c->historicoRecaudoUsoNorte($proyecto, $fecini, $fecfin);
	$total_sin_mora_n       = 0;
    $total_pagos_uso_n      = 0;
    $total_recaudado_uso_n  = 0;
    $total_pagos_zf_n       = 0;
    $total_recaudado_zf_n   = 0;
    $total_sin_mora_zf_n    = 0;
    $sin_mora_zf_n          = 0;

	while (oci_fetch($registros)) {
		$uson=oci_result($registros,"DESC_USO");
		$pagosn=utf8_decode(oci_result($registros,"PAGOS"));
		$recaudadon=utf8_decode(oci_result($registros,"RECAUDOS"));
		//$recaudadon = round($recaudadon);
		$sinMora = utf8_decode(oci_result($registros,"SIN_MORA"));
        //$sinMora = round($sinMora);

		$objPHPExcel->setActiveSheetIndex(2)
			->setCellValue('F'.$fila, $uson)
			->setCellValue('G'.$fila, $pagosn)
			->setCellValue('H'.$fila, $recaudadon)
			->setCellValue('I'.$fila, $sinMora);
		$fila++;
        $total_pagos_uso_n += $pagosn;
		$total_recaudado_uso_n += $recaudadon;
        $total_sin_mora_n  += $sinMora;

        if($proyecto == 'SD'){
            $i = new ReportesHisRec();
            $bandera = $i->InsertaDatosRecaudoUsoNorte($uson,$periodo,$pagosn,$recaudadon,$proyecto);
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

 /*  BORRAR $fila = 4;
    $c=new ReportesHisRec();
    $registros=$c->historicoRecaudoUsoNorteSinMora($proyecto, $fecini, $fecfin);
    while (oci_fetch($registros)) {
        $recaudadonsm=utf8_decode(oci_result($registros,"RECAUDADO"));
        $recaudadonsm = round($recaudadonsm);
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('I'.$fila, $recaudadonsm);
        $fila++;
        $totalrecaudadousonsm += $recaudadonsm;
    }oci_free_statement($registros);*/

	$c=new ReportesHisRec();
	$registros=$c->historicoRecaudoUsoNorteZF($proyecto, $fecini, $fecfin);
	while (oci_fetch($registros)) {

		$pagosn=utf8_decode(oci_result($registros,"PAGOS"));
		$recaudadon=utf8_decode(oci_result($registros,"RECAUDADO"));
        $sin_mora = utf8_decode(oci_result($registros,"SIN_MORA"));
        /*$c=new ReportesHisRec();
        $registros=$c->historicoRecaudoUsoNorteZFSinMora($proyecto, $fecini, $fecfin);
        while (oci_fetch($registros)) {
            $sin_mora_zf_n=utf8_decode(oci_result($registros,"SIN_MORA"));
            $sin_mora_zf_n = round($recaudadonsm);
            $objPHPExcel->setActiveSheetIndex(2)
                ->setCellValue('I'.$fila, $sin_mora_zf_n);
            $total_sin_mora_zf_n += $recaudadonsm;
            $total_sin_mora_n    += $sin_mora_zf_n;
        }oci_free_statement($registros);*/

		$objPHPExcel->setActiveSheetIndex(2)
			->setCellValue('F'.$fila, 'Zonas Francas')
			->setCellValue('G'.$fila, $pagosn)
			->setCellValue('H'.$fila, $recaudadon)
			->setCellValue('I'.$fila, $sin_mora);
		$fila++;
        $total_pagos_uso_n += $pagosn;
		$total_recaudado_uso_n += $recaudadon;
        $total_pagos_zf_n += $pagosn;
        $total_recaudado_zf_n += $recaudadon;

        $total_sin_mora_n  += $total_sin_mora_zf_n;
	}oci_free_statement($registros);

    if($proyecto == 'SD'){
        $i = new ReportesHisRec();
        $bandera = $i->InsertaDatosRecaudoUsoNorte('Zonas Francas',$periodo,$totalpagoszfn,$totalrecaudadozfn,$proyecto);
        if($bandera == false){
            $error=$i->getmsgresult();
            $coderror=$i->getcodresult();
            // return $error;
        }
        else if($bandera == true) {
            //return;
        }
    }

	/* BORRAR $fila = 11;
    $c=new ReportesHisRec();
    $registros=$c->historicoRecaudoUsoNorteZFSinMora($proyecto, $fecini, $fecfin);
    while (oci_fetch($registros)) {
        $recaudadonsm=utf8_decode(oci_result($registros,"RECAUDADO"));
        $recaudadonsm = round($recaudadonsm);
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('I'.$fila, $recaudadonsm);
        $fila++;
        $totalrecaudadousonsm += $recaudadonsm;
    }oci_free_statement($registros);*/

	$objPHPExcel->setActiveSheetIndex(2)->getStyle("F".$fila.":I".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(2)
		->setCellValue('F'.$fila, 'Total')
		->setCellValue('G'.$fila, round($totalpagosuson))
		->setCellValue('H'.$fila, round($totalrecaudadouson))
        ->setCellValue('I'.$fila, round($totalSinMoraNorte));
		
	$fila = 4;
	$c=new ReportesHisRec();
	$registros=$c->historicoRecaudoUsoEste($proyecto, $fecini, $fecfin);
    $totalpagosusoe     = 0;
    $totalrecaudadousoe = 0;
    $totalSinMoraEste   = 0;
	while (oci_fetch($registros)) {
		$usoe=oci_result($registros,"DESC_USO");
		$pagose=utf8_decode(oci_result($registros,"PAGOS"));
		$recaudadoe=utf8_decode(oci_result($registros,"RECAUDOS"));
		//$recaudadoe = round($recaudadoe);
        $sinMora=utf8_decode(oci_result($registros,"SIN_MORA"));
		//$sinMora = round($sinMora);

		$objPHPExcel->setActiveSheetIndex(2)
			->setCellValue('K'.$fila, $usoe)
			->setCellValue('L'.$fila, $pagose)
			->setCellValue('M'.$fila, $recaudadoe)
			->setCellValue('N'.$fila, $sinMora);
		$fila++;
		$totalpagosusoe += $pagose;
		$totalrecaudadousoe += $recaudadoe;
        $totalSinMoraEste   += $sinMora;
        $i = new ReportesHisRec();
        $bandera = $i->InsertaDatosRecaudoUsoEste($usoe,$periodo,$pagose,$recaudadoe,$proyecto);
        if($bandera == false){
            $error=$i->getmsgresult();
            $coderror=$i->getcodresult();
            // return $error;
        }
        else if($bandera == true) {
            //return;
        }
	}oci_free_statement($registros);
    /*$fila = 4;
    $c=new ReportesHisRec();
    $registros=$c->historicoRecaudoUsoEsteSinMora($proyecto, $fecini, $fecfin);
    while (oci_fetch($registros)) {
        $recaudadoesm=utf8_decode(oci_result($registros,"RECAUDADO"));
        $recaudadoesm = round($recaudadoesm);
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('N'.$fila, $recaudadoesm);
        $fila++;
        $totalrecaudadousoesm += $recaudadoesm;
    }oci_free_statement($registros);*/

	$c=new ReportesHisRec();
	$registros=$c->historicoRecaudoUsoEsteZF($proyecto, $fecini, $fecfin);
	while (oci_fetch($registros)) {
		//$usoe=oci_result($registros,"USO");
		$pagose=utf8_decode(oci_result($registros,"PAGOS"));
		$recaudadoe=utf8_decode(oci_result($registros,"RECAUDADO"));
        //$recaudadoe = round($recaudadoe);
        $sinMora=utf8_decode(oci_result($registros,"SIN_MORA"));
        $sinMora = round($sinMora);
		$objPHPExcel->setActiveSheetIndex(2)
			->setCellValue('K'.$fila, 'Zonas Francas')
			->setCellValue('L'.$fila, $pagose)
			->setCellValue('M'.$fila, $recaudadoe)
			->setCellValue('N'.$fila, $sinMora);
		$fila++;
		$totalpagosusoe += $pagose;
		$totalrecaudadousoe += $recaudadoe;
        $totalpagoszfe += $pagose;
        $totalrecaudadozfe += $recaudadoe;
        $totalSinMoraEste   += $sinMora;
	}oci_free_statement($registros);

    $i = new ReportesHisRec();
    $bandera = $i->InsertaDatosRecaudoUsoEste('Zonas Francas',$periodo,$totalpagoszfe,$totalrecaudadozfe,$proyecto);
    if($bandera == false){
        $error=$i->getmsgresult();
        $coderror=$i->getcodresult();
        // return $error;
    }
    else if($bandera == true) {
        //return;
    }

	/*$fila = 11;
    $c=new ReportesHisRec();
    $registros=$c->historicoRecaudoUsoEsteZFSinMora($proyecto, $fecini, $fecfin);
    while (oci_fetch($registros)) {
        $recaudadoesm=utf8_decode(oci_result($registros,"RECAUDADO"));
        $recaudadoesm = round($recaudadoesm);
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('N'.$fila, $recaudadoesm);
        $fila++;
        $totalrecaudadousoesm += $recaudadoesm;
    }oci_free_statement($registros);*/

	$objPHPExcel->setActiveSheetIndex(2)->getStyle("K".$fila.":N".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(2)
		->setCellValue('K'.$fila, 'Total')
		->setCellValue('L'.$fila, round($totalpagosusoe))
		->setCellValue('M'.$fila, round($totalrecaudadousoe))
        ->setCellValue('N'.$fila, round($totalSinMoraEste));
		
		
	//HOJA RECAUDADO POR CONCEPTO MEDIDOS
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
		->setCellValue('A1', utf8_encode('REPORTE HISTORICO RECAUDACION USUARIOS MEDIDOS POR CONCEPTO ').$proyecto.' DEL '.$fecini.' AL '.$fecfin)
		->setCellValue('A2', 'RECAUDADO TOTAL')
		->setCellValue('E2', 'RECAUDADO NORTE')
		->setCellValue('I2', 'RECAUDADO ESTE')
    	->setCellValue('A3', 'No')
        ->setCellValue('B3', 'Descripción')
        ->setCellValue('C3', 'Recaudado')
		->setCellValue('E3', 'No')
        ->setCellValue('F3', 'Descripción')
        ->setCellValue('G3', 'Recaudado')
		->setCellValue('I3', 'No')
        ->setCellValue('J3', 'Descripción')
        ->setCellValue('K3', 'Recaudado');
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("A")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("B")->setWidth(30);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("C")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("D")->setWidth(5);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("E")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("F")->setWidth(30);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("G")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("H")->setWidth(5);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("I")->setWidth(12);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("J")->setWidth(30);
	$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("K")->setWidth(12);
	
	$fila = 4;
	$a=new ReportesHisRec();
	$registrosh=$a->grupoConceptos();
	while (oci_fetch($registrosh)) {
		$id_grupo=utf8_decode(oci_result($registrosh,"ID_GRUPO"));
		$des_grupo=utf8_decode(oci_result($registrosh,"DES_GRUPO"));
		
		if($id_grupo <> 30){
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoConceptoTotalMedido($proyecto, $fecini, $fecfin, $id_grupo);
			while (oci_fetch($registros)) {
				$recaudadoM=utf8_decode(oci_result($registros,"RECAUDADO"));
                //$recaudadoM = round($recaudadoM);
				$objPHPExcel->setActiveSheetIndex(3)
					->setCellValue('A'.$fila, $id_grupo)
					->setCellValue('B'.$fila, utf8_encode($des_grupo))
					->setCellValue('C'.$fila, $recaudadoM);
				$totalrecaudadoconceptoM += $recaudadoM;
			}oci_free_statement($registros);
			
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoConceptoNorteMedido($proyecto, $fecini, $fecfin, $id_grupo);
			while (oci_fetch($registros)) {
				$recaudadonM=utf8_decode(oci_result($registros,"RECAUDADO"));
                //$recaudadonM = round($recaudadonM);
				$objPHPExcel->setActiveSheetIndex(3)
					->setCellValue('E'.$fila, $id_grupo)
					->setCellValue('F'.$fila, utf8_encode($des_grupo))
					->setCellValue('G'.$fila, $recaudadonM);
				$totalrecaudadoconceptonM += $recaudadonM;
			}oci_free_statement($registros);
			
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoConceptoEsteMedido($proyecto, $fecini, $fecfin, $id_grupo);
			while (oci_fetch($registros)) {
				$recaudadoeM=utf8_decode(oci_result($registros,"RECAUDADO"));
                //$recaudadoeM = round($recaudadoeM);
				$objPHPExcel->setActiveSheetIndex(3)
					->setCellValue('I'.$fila, $id_grupo)
					->setCellValue('J'.$fila, utf8_encode($des_grupo))
					->setCellValue('K'.$fila, $recaudadoeM);
				$totalrecaudadoconceptoeM += $recaudadoeM;
			}oci_free_statement($registros);
		}
		
		if($id_grupo == 30){
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoSaldoFavorTotalMedido($proyecto, $fecini, $fecfin, $id_grupo);
			while (oci_fetch($registros)) {
				$recaudadoM=utf8_decode(oci_result($registros,"RECAUDADO"));
                //$recaudadoM = round($recaudadoM);
				$objPHPExcel->setActiveSheetIndex(3)
					->setCellValue('A'.$fila, $id_grupo)
					->setCellValue('B'.$fila, utf8_encode($des_grupo))
					->setCellValue('C'.$fila, $recaudadoM);
				$totalrecaudadoconceptoM += $recaudadoM;
			}oci_free_statement($registros);
			
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoSaldoFavorNorteMedido($proyecto, $fecini, $fecfin, $id_grupo);
			while (oci_fetch($registros)) {
				$recaudadonM=utf8_decode(oci_result($registros,"RECAUDADO"));
                //$recaudadonM = round($recaudadonM);
				$objPHPExcel->setActiveSheetIndex(3)
					->setCellValue('E'.$fila, $id_grupo)
					->setCellValue('F'.$fila, utf8_encode($des_grupo))
					->setCellValue('G'.$fila, $recaudadonM);
				$totalrecaudadoconceptonM += $recaudadonM;
			}oci_free_statement($registros);
			
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoSaldoFavorEsteMedido($proyecto, $fecini, $fecfin, $id_grupo);
			while (oci_fetch($registros)) {
				$recaudadoeM=utf8_decode(oci_result($registros,"RECAUDADO"));
                //$recaudadoeM = round($recaudadoeM);
				$objPHPExcel->setActiveSheetIndex(3)
					->setCellValue('I'.$fila, $id_grupo)
					->setCellValue('J'.$fila, utf8_encode($des_grupo))
					->setCellValue('K'.$fila, $recaudadoeM);
				$totalrecaudadoconceptoeM += $recaudadoeM;
			}oci_free_statement($registros);
		}
		$fila++;
	}oci_free_statement($registrosh);
	$objPHPExcel->setActiveSheetIndex(3)->getStyle("A".$fila.":K".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(3)
		->setCellValue('A'.$fila, 'Total')
		->setCellValue('B'.$fila, '')
		->setCellValue('C'.$fila, round($totalrecaudadoconceptoM))
		->setCellValue('E'.$fila, 'Total')
		->setCellValue('F'.$fila, '')
		->setCellValue('G'.$fila, round($totalrecaudadoconceptonM))
		->setCellValue('I'.$fila, 'Total')
		->setCellValue('J'.$fila, '')
		->setCellValue('K'.$fila, round($totalrecaudadoconceptoeM));
		
	//HOJA RECAUDADO POR CONCEPTO AGRUPADO	
	
	$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Concepto Agrupado');
	$objPHPExcel->addSheet($myWorkSheet,4);
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
		->setCellValue('A1', utf8_encode('REPORTE HISTORICO RECAUDACION POR CONCEPTO AGRUPADO').$proyecto.' DEL '.$fecini.' AL '.$fecfin)
		->setCellValue('A2', 'RECAUDADO TOTAL')
		->setCellValue('E2', 'RECAUDADO NORTE')
		->setCellValue('I2', 'RECAUDADO ESTE')
    	->setCellValue('A3', 'No')
        ->setCellValue('B3', 'Descripción')
        ->setCellValue('C3', 'Recaudado')
		->setCellValue('E3', 'No')
        ->setCellValue('F3', 'Descripción')
        ->setCellValue('G3', 'Recaudado')
		->setCellValue('I3', 'No')
        ->setCellValue('J3', 'Descripción')
        ->setCellValue('K3', 'Recaudado');
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
	$a=new ReportesHisRec();
	$registrosh=$a->grupoNiveles();
	while (oci_fetch($registrosh)) {
		$des_nivel=utf8_decode(oci_result($registrosh,"NIVEL_AGRUPA"));
		
		if($des_nivel <> 'Saldos a Favor'){
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoConceptoAgrupadoTotal($proyecto, $fecini, $fecfin, $des_nivel);
			while (oci_fetch($registros)) {
				$recaudadoagruptot=utf8_decode(oci_result($registros,"RECAUDADO"));
                //$recaudadoagruptot = round($recaudadoagruptot);
				$objPHPExcel->setActiveSheetIndex(4)
					->setCellValue('A'.$fila, $cont)
					->setCellValue('B'.$fila, $des_nivel)
					->setCellValue('C'.$fila, $recaudadoagruptot);
				if($des_nivel == 'Saldos a Favor'){
				    $totalsaldofavoragrup = $recaudadoagruptot;
                }
				$totalrecaudadoconceptoagrupadototal += $recaudadoagruptot;
			}oci_free_statement($registros);
			
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoConceptoAgrupadoNorte($proyecto, $fecini, $fecfin, $des_nivel);
			while (oci_fetch($registros)) {
				$recaudadoagrupnor=utf8_decode(oci_result($registros,"RECAUDADO"));
                //$recaudadoagrupnor = round($recaudadoagrupnor);
				$objPHPExcel->setActiveSheetIndex(4)
					->setCellValue('E'.$fila, $cont)
					->setCellValue('F'.$fila, $des_nivel)
					->setCellValue('G'.$fila, $recaudadoagrupnor);
                if($des_nivel == 'Saldos a Favor'){
                    $totalsaldofavoragrupn = $recaudadoagrupnor;
                }
				$totalrecaudadoconceptoagrupadonorte += $recaudadoagrupnor;

                if($proyecto == 'SD'){
                    $i = new ReportesHisRec();
                    $recaudadoagrupnor = $recaudadoagrupnor + $totalsaldofavoragrupn;
                    $bandera = $i->InsertaDatosRecaudoConAgrNorte($des_nivel,$periodo,$recaudadoagrupnor,$proyecto);
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
			
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoConceptoAgrupadoEste($proyecto, $fecini, $fecfin, $des_nivel);
			while (oci_fetch($registros)) {
				$recaudadoagrupest=utf8_decode(oci_result($registros,"RECAUDADO"));
                //$recaudadoagrupest = round($recaudadoagrupest);
				$objPHPExcel->setActiveSheetIndex(4)
					->setCellValue('I'.$fila, $cont)
					->setCellValue('J'.$fila, $des_nivel)
					->setCellValue('K'.$fila, $recaudadoagrupest);
                if($des_nivel == 'Saldos a Favor'){
                    $totalsaldofavoragrupe = $recaudadoagrupest;
                }
				$totalrecaudadoconceptoagrupadoeste += $recaudadoagrupest;

                $i = new ReportesHisRec();
                $recaudadoagrupest = $recaudadoagrupest + $totalsaldofavoragrupe;
                $bandera = $i->InsertaDatosRecaudoConAgrEste($des_nivel,$periodo,$recaudadoagrupest,$proyecto);
                if($bandera == false){
                    $error=$i->getmsgresult();
                    $coderror=$i->getcodresult();
                    // return $error;
                }
                else if($bandera == true) {
                    //return;
                }
			}oci_free_statement($registros);
		}
		
		if($des_nivel == 'Saldos a Favor'){
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoSaldofavorTotal($proyecto, $fecini, $fecfin);
			while (oci_fetch($registros)) {
				$recaudadoagruptot=utf8_decode(oci_result($registros,"RECAUDADO"));
                //$recaudadoagruptot = round($recaudadoagruptot);
				$objPHPExcel->setActiveSheetIndex(4)
					->setCellValue('A'.$fila, $cont)
					->setCellValue('B'.$fila, $des_nivel);
					//->setCellValue('C'.$fila, $recaudadoagruptot + $totalsaldofavoragrup);

				$h=new ReportesHisRec();
                $registrosh=$h->historicoRecaudoConceptoAgrupadoTotal($proyecto, $fecini, $fecfin, $des_nivel);
                while (oci_fetch($registrosh)) {
                    $recaudadoagruptoth = utf8_decode(oci_result($registrosh, "RECAUDADO"));
                    //$recaudadoagrupest = round($recaudadoagrupest);

                    $objPHPExcel->setActiveSheetIndex(4)
                        //->setCellValue('I'.$fila, $cont);
                        ->setCellValue('C' . $fila, $recaudadoagruptot + $recaudadoagruptoth);
                }oci_free_statement($registrosh);

				$totalrecaudadoconceptoagrupadototal += $recaudadoagruptot +$recaudadoagruptoth;
			}oci_free_statement($registros);
			
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoSaldofavorTotalN($proyecto, $fecini, $fecfin);
			while (oci_fetch($registros)) {
				$recaudadoagrupnor=utf8_decode(oci_result($registros,"RECAUDADO"));
                //$recaudadoagrupnor = round($recaudadoagrupnor);
				$objPHPExcel->setActiveSheetIndex(4)
					->setCellValue('E'.$fila, $cont)
					->setCellValue('F'.$fila, $des_nivel);
					//->setCellValue('G'.$fila, $recaudadoagrupnor + $totalsaldofavoragrupn);

                $h=new ReportesHisRec();
                $registrosh=$h->historicoRecaudoConceptoAgrupadoNorte($proyecto, $fecini, $fecfin, $des_nivel);
                while (oci_fetch($registrosh)) {
                    $recaudadoagrupnorh = utf8_decode(oci_result($registrosh, "RECAUDADO"));
                    //$recaudadoagrupest = round($recaudadoagrupest);

                    $objPHPExcel->setActiveSheetIndex(4)
                        //->setCellValue('I'.$fila, $cont);
                        ->setCellValue('G' . $fila, $recaudadoagrupnor + $recaudadoagrupnorh);
                }oci_free_statement($registrosh);

				$totalrecaudadoconceptoagrupadonorte += $recaudadoagrupnor + $recaudadoagrupnorh;

				if($proyecto == 'SD'){
                    $i = new ReportesHisRec();
                    $recaudadoagrupnor = $recaudadoagrupnor + $totalsaldofavoragrupn;
                    $bandera = $i->InsertaDatosRecaudoConAgrNorte($des_nivel,$periodo,$recaudadoagrupnor,$proyecto);
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
			
			$c=new ReportesHisRec();	
			$registros=$c->historicoRecaudoSaldofavorTotalE($proyecto, $fecini, $fecfin);
			while (oci_fetch($registros)) {
				$recaudadoagrupest=utf8_decode(oci_result($registros,"RECAUDADO"));
                //$recaudadoagrupest = round($recaudadoagrupest);
				$objPHPExcel->setActiveSheetIndex(4)
					->setCellValue('I'.$fila, $cont)
					->setCellValue('J'.$fila, $des_nivel);
					//->setCellValue('K'.$fila, $recaudadoagrupest + $totalsaldofavoragrupe);
				//$totalrecaudadoconceptoagrupadoeste += $recaudadoagrupest;

                $h=new ReportesHisRec();
                $registrosh=$h->historicoRecaudoConceptoAgrupadoEste($proyecto, $fecini, $fecfin, $des_nivel);
                while (oci_fetch($registrosh)) {
                    $recaudadoagrupesth = utf8_decode(oci_result($registrosh, "RECAUDADO"));
                    //$recaudadoagrupest = round($recaudadoagrupest);

                    $objPHPExcel->setActiveSheetIndex(4)
                        //->setCellValue('I'.$fila, $cont);
                        ->setCellValue('K' . $fila, $recaudadoagrupest + $recaudadoagrupesth);
                }oci_free_statement($registrosh);
                $totalrecaudadoconceptoagrupadoeste += $recaudadoagrupest + $recaudadoagrupesth;
                $i = new ReportesHisRec();
                $recaudadoagrupest = $recaudadoagrupest + $totalsaldofavoragrupe;
                $bandera = $i->InsertaDatosRecaudoConAgrEste($des_nivel,$periodo,$recaudadoagrupest,$proyecto);
                if($bandera == false){
                    $error=$i->getmsgresult();
                    $coderror=$i->getcodresult();
                    // return $error;
                }
                else if($bandera == true) {
                    //return;
                }
			}oci_free_statement($registros);
		}
		$cont++;
		$fila++;
	}oci_free_statement($registrosh);
	$objPHPExcel->setActiveSheetIndex(4)->getStyle("A".$fila.":K".$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(4)
		->setCellValue('A'.$fila, '')
		->setCellValue('B'.$fila, 'Total')
		->setCellValue('C'.$fila, round($totalrecaudadoconceptoagrupadototal))
		->setCellValue('E'.$fila, '')
		->setCellValue('F'.$fila, 'Total')
		->setCellValue('G'.$fila, round($totalrecaudadoconceptoagrupadonorte))
		->setCellValue('I'.$fila, '')
		->setCellValue('J'.$fila, 'Total')
		->setCellValue('K'.$fila, round($totalrecaudadoconceptoagrupadoeste));
		
	//mostrar la hoja q se abrira	
	$objPHPExcel->setActiveSheetIndex(0);	

	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	$nomarch="../../temp/Historico_Recaudacion-".$proyecto.'-'.' DEL '.$fecini.' AL '.$fecfin.".xlsx";
	
	$objWriter->save($nomarch);
	echo $nomarch;
}
else{
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'NO SE PUEDE GENERAR EL ARCHIVO PORQUE EXISTEN SECTORES ABIERTOS PARA EL PERIODO '.$periodo);
    //mostrafr la hoja q se abrira
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Historico_Recaudacion-".$proyecto.'-'.$periodo.".xlsx";

    $objWriter->save($nomarch);
    echo $nomarch;
}
?>