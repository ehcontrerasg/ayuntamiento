<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/classPqrs.php';

$fecini     = $_POST['fecini'];
$fecfin     = $_POST['fecfin'];
$proyecto   = $_POST['proyecto'];
$tipo_pqr   = $_POST['tipo_pqr'];
$motivo_pqr = $_POST['motivo_prq'];

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Estadistica PQRs Detallados")
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Servicio al Cliente");

$estiloTitulos = array(
    'borders'   => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000'),
        ),
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    ),
);

$borderArray1 = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '4f81bd'),
        ),
    ),
);
$borderArray2 = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => 'f79646'),
        ),
    ),
);
$borderArray3 = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '9bbb59'),
        ),
    ),
);

// Titulos de la hoja
$objPHPExcel->setActiveSheetIndex(0)
    ->SetCellValue('A1', 'Reporte Estadistico de PQRs Detallados')
    ->SetCellValue('A2', 'Radicacion')
    ->SetCellValue('I2', 'Cierre Departamento')
    ->SetCellValue('K2', 'Cierre Analista')
    ->SetCellValue('A3', 'Numero')
    ->SetCellValue('B3', 'Fecha Solicitud')
    ->SetCellValue('C3', 'Codigo Sistema')
    ->SetCellValue('D3', 'Cliente')
    ->SetCellValue('E3', 'Medio Recepcion')
    ->SetCellValue('F3', 'Zona')
    ->SetCellValue('G3', 'Gerencia')
    ->SetCellValue('H3', 'Descripcion')
    ->SetCellValue('I3', 'Fecha Respuesta')
    ->SetCellValue('J3', 'Estado')
    ->SetCellValue('K3', 'Fecha')
    ->SetCellValue('L3', 'Tipo')
    ->SetCellValue('M3', 'Respuesta')
    ->SetCellValue('N3', 'Tiempo Respuesta (días)')
    ->SetCellValue('O3', 'Oficina')
    ->SetCellValue('P3', 'Usuario')
;

//Insertamos los datos
$pqr       = new PQRs();
$fila      = 4;
$registros = $pqr->GetRepEstDet($proyecto, $tipo_pqr, $motivo_pqr, $fecini, $fecfin);

while (oci_fetch($registros)) {
    $numero     = oci_result($registros, 'CODIGO');
    $fechaPqr   = oci_result($registros, 'FECHA_PQR');
    $inm        = oci_result($registros, 'INMUEBLE');
    $cliente    = oci_result($registros, 'NOM_CLIENTE');
    $direccion  = oci_result($registros, 'DIRECCION');
    $tel        = oci_result($registros, 'TELEFONO');
    $medioRec   = oci_result($registros, 'MEDIO_REC_PQR');
    $zona       = oci_result($registros, 'ZONA');
    $gerencia   = oci_result($registros, 'GERENCIA');
    $decPqr     = oci_result($registros, 'DESC_PQR');
    $fechaDiag  = oci_result($registros, 'FECHA_DIAG');
    $descDiag   = oci_result($registros, 'DESC_DIAGNOSTICO');
    $fechaRes   = oci_result($registros, 'FECHA_RES');
    $tipoMot    = oci_result($registros, 'TIPO');
    $respuesta  = oci_result($registros, 'RESPUESTA');
    $oficina = oci_result($registros, 'OFICINA');
    $tiempo_res = oci_result($registros, 'TIEMPO_RESPUESTA');
    $usuario = oci_result($registros, 'USUARIO');
    //echo $tiempo_res;

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $fila, $numero)
        ->setCellValue('B' . $fila, $fechaPqr)
        ->setCellValue('C' . $fila, $inm)
        ->setCellValue('D' . $fila, $cliente . ' | ' . $direccion . ' | ' . $tel)
        ->setCellValue('E' . $fila, $medioRec)
        ->setCellValue('F' . $fila, $zona)
        ->setCellValue('G' . $fila, $gerencia)
        ->setCellValue('H' . $fila, $decPqr)
        ->setCellValue('I' . $fila, $fechaDiag)
        ->setCellValue('J' . $fila, $descDiag)
        ->setCellValue('K' . $fila, $fechaRes)
        ->setCellValue('L' . $fila, $tipoMot)
        ->setCellValue('M' . $fila, $respuesta)
        ->setCellValue('N' . $fila, $tiempo_res)
        ->setCellValue('O' . $fila, $oficina)
        ->setCellValue('P' . $fila, $usuario)
    ;


    $objPHPExcel->getActiveSheet()->getRowDimension($fila)->setRowHeight(45);

    $fila++;

}
oci_free_statement($registros);

//        Busca los PQRs Catastrales
if ($motivo_pqr == 64 || $motivo_pqr == 0) {
    $pqrc      = new PQRs();
    $registros = $pqrc->GetRepEstDetCat($proyecto, $tipo_pqr, $fecini, $fecfin);

    while (oci_fetch($registros)) {
        $numero     = oci_result($registros, 'CODIGO');
        $fechaPqr   = oci_result($registros, 'FECHA_PQR');
        $cliente    = oci_result($registros, 'NOM_CLIENTE');
        $direccion  = oci_result($registros, 'DIRECCION');
        $tel        = oci_result($registros, 'TELEFONO');
        $medioRec   = oci_result($registros, 'MEDIO_REC_PQR');
        $decPqr     = oci_result($registros, 'DESC_PQR');
        $fechaDiag  = oci_result($registros, 'FECHA_DIAG');
        $descDiag   = oci_result($registros, 'DESC_DIAGNOSTICO');
        $fechaRes   = oci_result($registros, 'FECHA_RES');
        $tipoMot    = oci_result($registros, 'TIPO');
        $respuesta  = oci_result($registros, 'RESPUESTA');
        $oficina = oci_result($registros, 'OFICINA');
        $tiempo_res = oci_result($registros, 'TIEMPO_RESPUESTA');

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $numero)
            ->setCellValue('B' . $fila, $fechaPqr)
            ->setCellValue('D' . $fila, $cliente . ' | ' . $direccion . ' | ' . $tel)
            ->setCellValue('E' . $fila, $medioRec)
            ->setCellValue('H' . $fila, $decPqr)
            ->setCellValue('I' . $fila, $fechaDiag)
            ->setCellValue('J' . $fila, $descDiag)
            ->setCellValue('K' . $fila, $fechaRes)
            ->setCellValue('L' . $fila, $tipoMot)
            ->setCellValue('M' . $fila, $respuesta)
            ->setCellValue('N' . $fila, $tiempo_res)
            ->setCellValue('O' . $fila, $oficina);


        $objPHPExcel->getActiveSheet()->getRowDimension($fila)->setRowHeight(45);

        $fila++;
    }
    oci_free_statement($registros);
}

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A' . $fila, 'Total Reclamos')
    ->setCellValue('C' . $fila, $fila - 4);

//Bordes de las celdas
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A4:H' . $fila)->applyFromArray($borderArray1);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('I4:J' . $fila)->applyFromArray($borderArray2);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('K4:P' . $fila)->applyFromArray($borderArray3);

// Ajustar el Texto al a celda
$objPHPExcel->getActiveSheet(0)->getStyle('E3')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet(0)->getStyle('D4:D' . $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet(0)->getStyle('H4:H' . $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet(0)->getStyle('L4:L' . $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet(0)->getStyle('M4:M' . $fila)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet(0)->getStyle('B3')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet(0)->getStyle('C3')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet(0)->getStyle('I3')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet(0)->getStyle('N3')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet(0)->getStyle('O3')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet(0)->getStyle('P3')->getAlignment()->setWrapText(true);

// Width de las celdas
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(9.22);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(27.58);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(14.15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(27.58);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(12.15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(9.22);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(9.22);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(29.86);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(27.58);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(15.15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(27.58);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(16.41);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(29.86);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(16.41);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(29.41);

// Height De las Celdas
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(49.5);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(35.25);

// Celdas Unificadas
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:O1');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:H2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I2:J2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('K2:P2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $fila . ':B' . $fila);

// Colores de la celda
/*cellColor($objPHPExcel, 'A2:H2', '4f81bd');
cellColor($objPHPExcel, 'I2:J2', 'f79646');
cellColor($objPHPExcel, 'K2:N2', '9bbb59');
cellColor($objPHPExcel, 'A3:H3', 'c5d9f1');
cellColor($objPHPExcel, 'I3:J3', 'fde9d9');
cellColor($objPHPExcel, 'K3:N3', 'ebf1de');
cellColor($objPHPExcel, 'A' . $fila, '4f81bd');*/

// Fuentes en Bold
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:O3')->getFont()->setBold(true);

// Estilo y size de las fuentes
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1")->getFont()
    ->setName('Tahoma')
    ->setSize(18);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:P2")->getFont()
    ->setName('Tahoma')
    ->setSize(11)
    ->getColor()->setRGB('ffffff');
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A4:P" . $fila)->getFont()
    ->setName('Tahoma')
    ->setSize(9);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $fila)->getFont()
    ->getColor()->setRGB('ffffff');

//Estilos para las celdas
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A3:P3' . $fila)->applyFromArray(
    array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        ),
    )
);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:P3')->applyFromArray($estiloTitulos);

/* header("Content-Type: application/vnd.ms-excel");
 header("Content-Disposition: attachment;filename='Reporte_Estaditica_Pqrs_ Detallados_" . $proyecto . "_" . $fecini . "_al_" . $fecfin . ".xls'");
 header("Cache-Control: max-age=0");
 ini_set('memory_limit', '250M');
 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
 $objWriter->save('php://output');
 exit;*/


$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Resoluciones de Gerencia');
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Reporte_Estaditica_Pqrs_ Detallados_" . $proyecto . "_" . $fecini . "_al_" . $fecfin . ".xlsx";
$objWriter->save($nomarch);
echo $nomarch;