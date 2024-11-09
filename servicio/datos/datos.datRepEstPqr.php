<?php
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];

include_once '../clases/classPqrs.php';
session_start();
$cod=$_SESSION['codigo'];

if($tipo=='sess'){
    $segundos = 1800; //si pasa este tiempo se detecta la inactividad del usuario en el sitio
    if(($_SESSION['tiempo']+$segundos) < time()) {
        session_destroy();
        echo "false";
    }else{
        $_SESSION['tiempo']=time();
        echo "true";
    }
}

if($tipo=='selPro'){
    $l=new PQRs();
    $datos = $l->seleccionaAcueducto();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='EstPqr'){
	require_once '../clases/PHPExcel.php';
	$fecini = $_POST['fecini'];
	$fecfin = $_POST['fecfin'];
    $proyecto = $_POST['proyecto'];
	
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->
	getProperties()
		->setCreator("AceaSoft")
		->setLastModifiedBy("AceaSoft")
		->setTitle("Estadistica PQRs")
		->setSubject("")
		->setDescription("Documento generado con PHPExcel")
		->setKeywords("usuarios phpexcel")
		->setCategory("Reportes Servicio al Cliente");
		
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
	
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:K1');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:K2');
	
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:K3')->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:K3")->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'REPORTE DE ESTADISTICA DE PQRS RESUMIDO '.$proyecto)
		->setCellValue('A2', 'RECLAMOS')
		->setCellValue('A3', 'Cod')
		->setCellValue('B3', 'Tipo')
		->setCellValue('C3', 'Generados')
		->setCellValue('D3', 'Pendientes')
        ->setCellValue('E3', 'Cerrados Proc')
        ->setCellValue('F3', 'Cerrados No Proc')
		->setCellValue('G3', 'Total Cerrados')
		->setCellValue('H3', 'Dentro del Tiempo')
        ->setCellValue('I3', 'Fuera del Tiempo')
		->setCellValue('J3', 'Tiempo Promedio')
        ->setCellValue('K3', '% Efectividad');
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(5);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(40);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(14);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(14);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(14);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(14);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(14);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(14);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(14);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(14);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(14);
	$cont = 0;
	$fila = 4;
	$l=new PQRs();
	$registros=$l->ObtienePqrs('1');
	while (oci_fetch($registros)) {
		$codmotivo=oci_result($registros, 'ID_MOTIVO_REC');
		$desmotivo=oci_result($registros, 'DESC_MOTIVO_REC');
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $codmotivo)
			->setCellValue('B'.$fila, $desmotivo);
		$a=new PQRs();
		$registrosa=$a->CantidadPqrMes($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_mes=oci_result($registrosa, 'CANTIDAD_MES');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, $cantidad_mes);
			$totalMes += $cantidad_mes;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->PendientesMes($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_pend=oci_result($registrosa, 'CANTIDAD_PEND');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('D'.$fila, $cantidad_pend);
			$totalPend += $cantidad_pend;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->CerradosProcedentes($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_proc=oci_result($registrosa, 'CANTIDAD_PROC');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, $cantidad_proc);
			$totalProc += $cantidad_proc;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->CerradosNoProcedentes($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_noproc=oci_result($registrosa, 'CANTIDAD_NOPROC');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('F'.$fila, $cantidad_noproc);
			$totalNoProc += $cantidad_noproc;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->CerradosTotal($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_cerrado=oci_result($registrosa, 'CANTIDAD_CERRADOS');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila, $cantidad_cerrado);
			$totalCerrado += $cantidad_cerrado;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->DentroTiempo($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_dentro=oci_result($registrosa, 'DENTRO_TIEMPO');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('H'.$fila, $cantidad_dentro);
			$totalDentro += $cantidad_dentro;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->FueraTiempo($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_fuera=oci_result($registrosa, 'FUERA_TIEMPO');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('I'.$fila, $cantidad_fuera);
			$totalFuera += $cantidad_fuera;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->TiempoPromedio($codmotivo, $proyecto, $fecini, $fecfin, $cantidad_cerrado);
		while (oci_fetch($registrosa)) {
			$promedio=oci_result($registrosa, 'PROMEDIO');
			if ($promedio == '') $promedio = '0';
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('J'.$fila, $promedio);
			$tiempoProm += $promedio;
		}oci_free_statement($registrosa);
		if ($cantidad_cerrado > 0){
			$efectividad = round(($cantidad_dentro * 100)/$cantidad_cerrado,2);
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('K'.$fila, $efectividad);
			$porcEfectivo += $efectividad;
		}
		else{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('K'.$fila, '0');
		}
		$fila++;
		$cont++;
	}oci_free_statement($registros);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila)->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$fila, 'Totales')
			->setCellValue('C'.$fila, $totalMes)
			->setCellValue('D'.$fila, $totalPend)
			->setCellValue('E'.$fila, $totalProc)
			->setCellValue('F'.$fila, $totalNoProc)
			->setCellValue('G'.$fila, $totalCerrado)
			->setCellValue('H'.$fila, $totalDentro)
			->setCellValue('I'.$fila, $totalFuera)
			->setCellValue('J'.$fila, round(($tiempoProm/$cont),2))
			->setCellValue('K'.$fila, round(($porcEfectivo/$cont),2));
	
	$fila = $fila + 3;
	$fila1 = $fila+1;
	$totalMes = 0;
	$totalPend = 0;
	$totalProc = 0;
	$totalNoProc = 0;
	$totalCerrado = 0;
	$totalDentro = 0;
	$totalFuera = 0;
	$tiempoProm = 0;
	$porcEfectivo = 0;
	
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':K'.$fila);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila1)->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila1)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$fila, 'SOLICITUDES')
		->setCellValue('A'.$fila1, 'Cod')
		->setCellValue('B'.$fila1, 'Tipo')
		->setCellValue('C'.$fila1, 'Generados')
        ->setCellValue('D'.$fila1, 'Pendientes')
        ->setCellValue('E'.$fila1, 'Cerrados Proc')
		->setCellValue('F'.$fila1, 'Cerrados No Proc')
        ->setCellValue('G'.$fila1, 'Total Cerrados')
		->setCellValue('H'.$fila1, 'Dentro del Tiempo')
        ->setCellValue('I'.$fila1, 'Fuera del Tiempo')
		->setCellValue('J'.$fila1, 'Tiempo Promedio')
        ->setCellValue('K'.$fila1, '% Efectividad');
	
	$cont = 0;
	$fila = $fila1 + 1;
	$l=new PQRs();
	$registros=$l->ObtienePqrs(2);
	while (oci_fetch($registros)) {
		$codmotivo=oci_result($registros, 'ID_MOTIVO_REC');
		$desmotivo=oci_result($registros, 'DESC_MOTIVO_REC');
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $codmotivo)
			->setCellValue('B'.$fila, $desmotivo);
			
		if($codmotivo == 64){
			$a=new PQRs();
			$registrosa=$a->CantidadPqrMesCatastral($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_mes=oci_result($registrosa, 'CANTIDAD_MES');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('C'.$fila, $cantidad_mes);
				$totalMes += $cantidad_mes;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->PendientesMesCatastral($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_pend=oci_result($registrosa, 'CANTIDAD_PEND');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D'.$fila, $cantidad_pend);
				$totalPend += $cantidad_pend;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->CerradosProcedentesCatastral($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_proc=oci_result($registrosa, 'CANTIDAD_PROC');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('E'.$fila, $cantidad_proc);
				$totalProc += $cantidad_proc;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->CerradosNoProcedentesCatastral($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_noproc=oci_result($registrosa, 'CANTIDAD_NOPROC');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('F'.$fila, $cantidad_noproc);
				$totalNoProc += $cantidad_noproc;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->CerradosTotalCatastral($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_cerrado=oci_result($registrosa, 'CANTIDAD_CERRADOS');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('G'.$fila, $cantidad_cerrado);
				$totalCerrado += $cantidad_cerrado;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->DentroTiempoCatastral($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_dentro=oci_result($registrosa, 'DENTRO_TIEMPO');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('H'.$fila, $cantidad_dentro);
				$totalDentro += $cantidad_dentro;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->FueraTiempoCatastral($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_fuera=oci_result($registrosa, 'FUERA_TIEMPO');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('I'.$fila, $cantidad_fuera);
				$totalFuera += $cantidad_fuera;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->TiempoPromedioCatastral($codmotivo, $proyecto, $fecini, $fecfin, $cantidad_cerrado);
			while (oci_fetch($registrosa)) {
				$promedio=oci_result($registrosa, 'PROMEDIO');
				if ($promedio == '') $promedio = '0';
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('J'.$fila, $promedio);
				$tiempoProm += $promedio;
			}oci_free_statement($registrosa);
			if ($cantidad_cerrado > 0){
				$efectividad = round(($cantidad_dentro * 100)/$cantidad_cerrado,2);
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('K'.$fila, $efectividad);
				$porcEfectivo += $efectividad;
			}
			else{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('K'.$fila, '0');
			}

		}
		if($codmotivo <> 64){	
			$a=new PQRs();
			$registrosa=$a->CantidadPqrMes($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_mes=oci_result($registrosa, 'CANTIDAD_MES');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('C'.$fila, $cantidad_mes);
				$totalMes += $cantidad_mes;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->PendientesMes($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_pend=oci_result($registrosa, 'CANTIDAD_PEND');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D'.$fila, $cantidad_pend);
				$totalPend += $cantidad_pend;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->CerradosProcedentes($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_proc=oci_result($registrosa, 'CANTIDAD_PROC');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('E'.$fila, $cantidad_proc);
				$totalProc += $cantidad_proc;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->CerradosNoProcedentes($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_noproc=oci_result($registrosa, 'CANTIDAD_NOPROC');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('F'.$fila, $cantidad_noproc);
				$totalNoProc += $cantidad_noproc;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->CerradosTotal($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_cerrado=oci_result($registrosa, 'CANTIDAD_CERRADOS');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('G'.$fila, $cantidad_cerrado);
				$totalCerrado += $cantidad_cerrado;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->DentroTiempo($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_dentro=oci_result($registrosa, 'DENTRO_TIEMPO');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('H'.$fila, $cantidad_dentro);
				$totalDentro += $cantidad_dentro;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->FueraTiempo($codmotivo, $proyecto, $fecini, $fecfin);
			while (oci_fetch($registrosa)) {
				$cantidad_fuera=oci_result($registrosa, 'FUERA_TIEMPO');
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('I'.$fila, $cantidad_fuera);
				$totalFuera += $cantidad_fuera;
			}oci_free_statement($registrosa);
			$a=new PQRs();
			$registrosa=$a->TiempoPromedio($codmotivo, $proyecto, $fecini, $fecfin, $cantidad_cerrado);
			while (oci_fetch($registrosa)) {
				$promedio=oci_result($registrosa, 'PROMEDIO');
				if ($promedio == '') $promedio = '0';
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('J'.$fila, $promedio);
				$tiempoProm += $promedio;
			}oci_free_statement($registrosa);
			if ($cantidad_cerrado > 0){
				$efectividad = round(($cantidad_dentro * 100)/$cantidad_cerrado,2);
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('K'.$fila, $efectividad);
				$porcEfectivo += $efectividad;
			}
			else{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('K'.$fila, '0');
			}
		}
		$fila++;
		$cont++;
	}oci_free_statement($registros);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila)->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('B'.$fila, 'Totales')
		->setCellValue('C'.$fila, $totalMes)
		->setCellValue('D'.$fila, $totalPend)
		->setCellValue('E'.$fila, $totalProc)
		->setCellValue('F'.$fila, $totalNoProc)
		->setCellValue('G'.$fila, $totalCerrado)
		->setCellValue('H'.$fila, $totalDentro)
		->setCellValue('I'.$fila, $totalFuera)
		->setCellValue('J'.$fila, round(($tiempoProm/$cont),2))
		->setCellValue('K'.$fila, round(($porcEfectivo/$cont),2));
		
		
	$fila = $fila + 3;
	$fila1 = $fila+1;
	$totalMes = 0;
	$totalPend = 0;
	$totalProc = 0;
	$totalNoProc = 0;
	$totalCerrado = 0;
	$totalDentro = 0;
	$totalFuera = 0;
	$tiempoProm = 0;
	$porcEfectivo = 0;
	
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':K'.$fila);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila1)->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila1)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$fila, 'QUEJAS / SUGERENCIAS')
		->setCellValue('A'.$fila1, 'Cod')
		->setCellValue('B'.$fila1, 'Tipo')
		->setCellValue('C'.$fila1, 'Generados')
        ->setCellValue('D'.$fila1, 'Pendientes')
        ->setCellValue('E'.$fila1, 'Cerrados Proc')
		->setCellValue('F'.$fila1, 'Cerrados No Proc')
        ->setCellValue('G'.$fila1, 'Total Cerrados')
		->setCellValue('H'.$fila1, 'Dentro del Tiempo')
        ->setCellValue('I'.$fila1, 'Fuera del Tiempo')
		->setCellValue('J'.$fila1, 'Tiempo Promedio')
        ->setCellValue('K'.$fila1, '% Efectividad');
	
	$cont = 0;
	$fila = $fila1 + 1;
	$l=new PQRs();
	$registros=$l->ObtienePqrs(3);
	while (oci_fetch($registros)) {
		$codmotivo=oci_result($registros, 'ID_MOTIVO_REC');
		$desmotivo=oci_result($registros, 'DESC_MOTIVO_REC');
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$fila, $codmotivo)
			->setCellValue('B'.$fila, $desmotivo);	
		$a=new PQRs();
		$registrosa=$a->CantidadPqrMes($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_mes=oci_result($registrosa, 'CANTIDAD_MES');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$fila, $cantidad_mes);
			$totalMes += $cantidad_mes;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->PendientesMes($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_pend=oci_result($registrosa, 'CANTIDAD_PEND');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('D'.$fila, $cantidad_pend);
			$totalPend += $cantidad_pend;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->CerradosProcedentes($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_proc=oci_result($registrosa, 'CANTIDAD_PROC');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('E'.$fila, $cantidad_proc);
			$totalProc += $cantidad_proc;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->CerradosNoProcedentes($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_noproc=oci_result($registrosa, 'CANTIDAD_NOPROC');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('F'.$fila, $cantidad_noproc);
			$totalNoProc += $cantidad_noproc;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->CerradosTotal($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_cerrado=oci_result($registrosa, 'CANTIDAD_CERRADOS');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('G'.$fila, $cantidad_cerrado);
			$totalCerrado += $cantidad_cerrado;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->DentroTiempo($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_dentro=oci_result($registrosa, 'DENTRO_TIEMPO');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('H'.$fila, $cantidad_dentro);
			$totalDentro += $cantidad_dentro;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->FueraTiempo($codmotivo, $proyecto, $fecini, $fecfin);
		while (oci_fetch($registrosa)) {
			$cantidad_fuera=oci_result($registrosa, 'FUERA_TIEMPO');
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('I'.$fila, $cantidad_fuera);
			$totalFuera += $cantidad_fuera;
		}oci_free_statement($registrosa);
		$a=new PQRs();
		$registrosa=$a->TiempoPromedio($codmotivo, $proyecto, $fecini, $fecfin, $cantidad_cerrado);
		while (oci_fetch($registrosa)) {
			$promedio=oci_result($registrosa, 'PROMEDIO');
			if ($promedio == '') $promedio = '0';
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('J'.$fila, $promedio);
			$tiempoProm += $promedio;
		}oci_free_statement($registrosa);
		if ($cantidad_cerrado > 0){
			$efectividad = round(($cantidad_dentro * 100)/$cantidad_cerrado,2);
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('K'.$fila, $efectividad);
			$porcEfectivo += $efectividad;
		}
		else{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('K'.$fila, '0');
		}
		$fila++;
		$cont++;
	}oci_free_statement($registros);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila)->applyFromArray($estiloTitulos);
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila)->getFont()->setBold(true);
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('B'.$fila, 'Totales')
		->setCellValue('C'.$fila, $totalMes)
		->setCellValue('D'.$fila, $totalPend)
		->setCellValue('E'.$fila, $totalProc)
		->setCellValue('F'.$fila, $totalNoProc)
		->setCellValue('G'.$fila, $totalCerrado)
		->setCellValue('H'.$fila, $totalDentro)
		->setCellValue('I'.$fila, $totalFuera)
		->setCellValue('J'.$fila, round(($tiempoProm/$cont),2))
		->setCellValue('K'.$fila, round(($porcEfectivo/$cont),2));


	//////////////////////
    $fila = $fila + 3;
    $fila1 = $fila+1;
    $totalMes = 0;
    $totalPend = 0;
    $totalProc = 0;
    $totalNoProc = 0;
    $totalCerrado = 0;
    $totalDentro = 0;
    $totalFuera = 0;
    $tiempoProm = 0;
    $porcEfectivo = 0;

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':K'.$fila);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila1)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila1)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, 'CONSULTAS SISTEMA')
        ->setCellValue('A'.$fila1, 'Cod')
        ->setCellValue('B'.$fila1, 'Tipo')
        ->setCellValue('C'.$fila1, 'Generados')
        ->setCellValue('D'.$fila1, 'Pendientes')
        ->setCellValue('E'.$fila1, 'Cerrados Proc')
        ->setCellValue('F'.$fila1, 'Cerrados No Proc')
        ->setCellValue('G'.$fila1, 'Total Cerrados')
        ->setCellValue('H'.$fila1, 'Dentro del Tiempo')
        ->setCellValue('I'.$fila1, 'Fuera del Tiempo')
        ->setCellValue('J'.$fila1, 'Tiempo Promedio')
        ->setCellValue('K'.$fila1, '% Efectividad');

    $cont = 0;
    $fila = $fila1 + 1;
    $l=new PQRs();
    $registros=$l->ObtienePqrs(5);
    while (oci_fetch($registros)) {
        $codmotivo=oci_result($registros, 'ID_MOTIVO_REC');
        $desmotivo=oci_result($registros, 'DESC_MOTIVO_REC');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$fila, $codmotivo)
            ->setCellValue('B'.$fila, $desmotivo);
        $a=new PQRs();
        $registrosa=$a->CantidadPqrMes($codmotivo, $proyecto, $fecini, $fecfin);
        while (oci_fetch($registrosa)) {
            $cantidad_mes=oci_result($registrosa, 'CANTIDAD_MES');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('C'.$fila, $cantidad_mes);
            $totalMes += $cantidad_mes;
        }oci_free_statement($registrosa);
        $a=new PQRs();
        $registrosa=$a->PendientesMes($codmotivo, $proyecto, $fecini, $fecfin);
        while (oci_fetch($registrosa)) {
            $cantidad_pend=oci_result($registrosa, 'CANTIDAD_PEND');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D'.$fila, $cantidad_pend);
            $totalPend += $cantidad_pend;
        }oci_free_statement($registrosa);
        $a=new PQRs();
        $registrosa=$a->CerradosProcedentes($codmotivo, $proyecto, $fecini, $fecfin);
        while (oci_fetch($registrosa)) {
            $cantidad_proc=oci_result($registrosa, 'CANTIDAD_PROC');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('E'.$fila, $cantidad_proc);
            $totalProc += $cantidad_proc;
        }oci_free_statement($registrosa);
        $a=new PQRs();
        $registrosa=$a->CerradosNoProcedentes($codmotivo, $proyecto, $fecini, $fecfin);
        while (oci_fetch($registrosa)) {
            $cantidad_noproc=oci_result($registrosa, 'CANTIDAD_NOPROC');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('F'.$fila, $cantidad_noproc);
            $totalNoProc += $cantidad_noproc;
        }oci_free_statement($registrosa);
        $a=new PQRs();
        $registrosa=$a->CerradosTotal($codmotivo, $proyecto, $fecini, $fecfin);
        while (oci_fetch($registrosa)) {
            $cantidad_cerrado=oci_result($registrosa, 'CANTIDAD_CERRADOS');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('G'.$fila, $cantidad_cerrado);
            $totalCerrado += $cantidad_cerrado;
        }oci_free_statement($registrosa);
        $a=new PQRs();
        $registrosa=$a->DentroTiempo($codmotivo, $proyecto, $fecini, $fecfin);
        while (oci_fetch($registrosa)) {
            $cantidad_dentro=oci_result($registrosa, 'DENTRO_TIEMPO');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('H'.$fila, $cantidad_dentro);
            $totalDentro += $cantidad_dentro;
        }oci_free_statement($registrosa);
        $a=new PQRs();
        $registrosa=$a->FueraTiempo($codmotivo, $proyecto, $fecini, $fecfin);
        while (oci_fetch($registrosa)) {
            $cantidad_fuera=oci_result($registrosa, 'FUERA_TIEMPO');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('I'.$fila, $cantidad_fuera);
            $totalFuera += $cantidad_fuera;
        }oci_free_statement($registrosa);
        $a=new PQRs();
        $registrosa=$a->TiempoPromedio($codmotivo, $proyecto, $fecini, $fecfin, $cantidad_cerrado);
        while (oci_fetch($registrosa)) {
            $promedio=oci_result($registrosa, 'PROMEDIO');
            if ($promedio == '') $promedio = '0';
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('J'.$fila, $promedio);
            $tiempoProm += $promedio;
        }oci_free_statement($registrosa);
        if ($cantidad_cerrado > 0){
            $efectividad = round(($cantidad_dentro * 100)/$cantidad_cerrado,2);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('K'.$fila, $efectividad);
            $porcEfectivo += $efectividad;
        }
        else{
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('K'.$fila, '0');
        }
        $fila++;
        $cont++;
    }oci_free_statement($registros);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':K'.$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'Totales')
        ->setCellValue('C'.$fila, $totalMes)
        ->setCellValue('D'.$fila, $totalPend)
        ->setCellValue('E'.$fila, $totalProc)
        ->setCellValue('F'.$fila, $totalNoProc)
        ->setCellValue('G'.$fila, $totalCerrado)
        ->setCellValue('H'.$fila, $totalDentro)
        ->setCellValue('I'.$fila, $totalFuera)
        ->setCellValue('J'.$fila, round(($tiempoProm/$cont),2))
        ->setCellValue('K'.$fila, round(($porcEfectivo/$cont),2));
		
/*	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;filename='Reporte_Estaditica_Pqrs_".$proyecto."_".$fecini."_al_".$fecfin.".xls'");
	header("Cache-Control: max-age=0");
	ini_set('memory_limit','250M');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;*/


    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Resoluciones de Gerencia');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Reporte_Estaditica_Pqrs_".$proyecto."_".$fecini."_al_".$fecfin.".xlsx'";
    $objWriter->save($nomarch);
    echo $nomarch;
}