<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */
setlocale(LC_MONETARY, 'es_DO');
$proyecto = $_POST['proyecto'];
$tipo    = $_POST['tipo'];
$fecini    = $_POST['fecini'];
$fecfin  = $_POST['fecfin'];
ini_set('memory_limit', '-1');

include "../../clases/class.pdfRep.php";
include "../../clases/class.medidor.php";
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
$reporte='';
$a = new Medidor();
if ($tipo == 'preventivo') {
    $datos = $a->getMantPreByFecIniFin($proyecto,$fecini,$fecfin);
    $reporte='Preventivo';
} elseif ($tipo == 'correctivo') {
    $datos = $a->getMantCorrByFecIniFin($proyecto,$fecini,$fecfin);
    $reporte='Correctivo';
}

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
        getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle(utf8_encode("Reporte Mantenimiento $reporte ") . $proyecto)
        ->setSubject("")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("usuarios phpexcel")
        ->setCategory("Reportes Medidores");

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

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:T1');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:T1')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:T2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', strtoupper('MANTENIMIENTOS '.$reporte.'S '))
        ->setCellValue('A2', 'Código')
        ->setCellValue('B2', 'Urbanizacion')
        ->setCellValue('C2', 'Dirección')
        ->setCellValue('D2', 'Nombre')
        ->setCellValue('E2', 'Serial')
        ->setCellValue('F2', 'Medidor')
        ->setCellValue('G2', 'Diametro')
        ->setCellValue('H2', 'Sector')
        ->setCellValue('I2', 'Ruta')
        ->setCellValue('J2', 'Proceso')
        ->setCellValue('K2', 'Catastro')
        ->setCellValue('L2', 'Fecha')
        ->setCellValue('M2', 'Operario')
        ->setCellValue('N2', 'Actividades realizadas')
        ->setCellValue('O2', 'Comentrarios ')
        ->setCellValue('P2', 'Observacion de lectura')
        ->setCellValue('Q2', 'Facturas pendientes');
        if($tipo == "preventivo"){
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('R2', 'Impedimento')
            ->setCellValue('S2', 'Foto Reducida');
        }else{
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('R2', 'Foto Reducida');
        }
    
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(6);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(8);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(8);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(5);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(4);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(11);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(18);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(19);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(60);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(4);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Q")->setWidth(8);
    if ($tipo == "preventivo") {
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("R")->setWidth(12);
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("S")->setWidth(40);
    }else{
        $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("R")->setWidth(40);
    }


    $fila = 3;
    while (oci_fetch($datos)) {



        $inmueble      = oci_result($datos, "CODIGO_INM");
        $urbanizacion =oci_result($datos, "DESC_URBANIZACION");
        $direccion  = oci_result($datos, "DIRECCION");
        $nombre     = oci_result($datos, "ALIAS");
        $serial       = oci_result($datos, "SERIAL");
        $marca= oci_result($datos, "DESC_MED");
        $diametro= oci_result($datos, "DESC_CALIBRE");
        $sector       = oci_result($datos, "ID_SECTOR");
        $ruta      = oci_result($datos, "ID_RUTA");
        $proceso =oci_result($datos, "ID_PROCESO");
        $catastro =oci_result($datos, "CATASTRO");

        $hora       = oci_result($datos, "HORA");
        if(  $hora=='0000')
        $fecha=oci_result($datos, "FECHA_REEALIZACION2");
    else
        $fecha=oci_result($datos, "FECHA_REEALIZACION");
        $operario = oci_result($datos, "LOGIN");

        $obs_act          = oci_result($datos, "OBS_GENERAL");
        $obs_oper          = oci_result($datos, "OBS_MANTENIMIENTO");
        $foto_ini          = oci_result($datos, "FOTO_INICIAL");
        $foto_fin          = oci_result($datos, "FOTO_FINAL");
        $factpend       = oci_result($datos, "FAC_PEND");
        $lectura       = oci_result($datos, "LECTURA");
        $orden       = oci_result($datos, "ID_ORDEN");
        $obs_imp        = oci_result($datos, "OBS");

        $foto_ini=str_replace('..','',$foto_ini);
        $foto_fin=str_replace('..','',$foto_fin);


        $b = new Medidor();
        $datos2 = $b->getActByMant($orden, $tipo);
        $listaAct='';
        while (oci_fetch($datos2)) {
            if ($$listaAct != '')
                $$listaAct      .=', '.oci_result($datos2, "DESCRIPCION");
            else
                $$listaAct      .= oci_result($datos2, "DESCRIPCION");
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $inmueble)
            ->setCellValue('B' . $fila, $urbanizacion)
            ->setCellValue('C' . $fila, $direccion)
            ->setCellValue('D' . $fila, $nombre)
            ->setCellValue('E' . $fila, $serial)
            ->setCellValue('F' . $fila, $marca)
            ->setCellValue('G' . $fila, $diametro)
            ->setCellValue('H' . $fila, $sector)
            ->setCellValue('I' . $fila, $ruta)
            ->setCellValue('J' . $fila, $proceso)
            ->setCellValue('K' . $fila, $catastro)
            ->setCellValue('L' . $fila, $fecha)
            ->setCellValue('M' . $fila, $operario)
            ->setCellValue('N' . $fila, $$listaAct)
            ->setCellValue('O' . $fila, $obs_oper)
            ->setCellValue('P' . $fila, $lectura)
            ->setCellValue('Q' . $fila, $factpend);
            
            if ($tipo == "preventivo") {
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('R' . $fila, $obs_imp)
                ->setCellValue('S' . $fila, 'https://aceasoft.com/medidores/vistas/vista.fotos_ManPre.php?orden='.$orden.'&tipo='.$tipo);
                $$listaAct      ='';
                $objPHPExcel->getActiveSheet()
                    ->getCell('S' . $fila)
                    ->getHyperlink()
                    ->setUrl( 'https://aceasoft.com/medidores/vistas/vista.fotos_ManPre.php?orden='.$orden.'&tipo='.$tipo)
                    ->setTooltip('Haz click aquí para ver la foto');
            }else{
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('R' . $fila, 'https://aceasoft.com/medidores/vistas/vista.fotos_ManPre.php?orden='.$orden.'&tipo='.$tipo);
                $$listaAct      ='';
                $objPHPExcel->getActiveSheet()
                    ->getCell('R' . $fila)
                    ->getHyperlink()
                    ->setUrl( 'https://aceasoft.com/medidores/vistas/vista.fotos_ManPre.php?orden='.$orden.'&tipo='.$tipo)
                    ->setTooltip('Haz click aquí para ver la foto');
            }            
        $fila++;
    }


    oci_free_statement($datos);

$objPHPExcel->getActiveSheet()->getStyle('N3:N'.$objPHPExcel->getActiveSheet()->getHighestRow())
    ->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('R3:R'.$objPHPExcel->getActiveSheet()->getHighestRow())
    ->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('S3:S'.$objPHPExcel->getActiveSheet()->getHighestRow())
    ->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('T3:T'.$objPHPExcel->getActiveSheet()->getHighestRow())
    ->getAlignment()->setWrapText(true);

$objPHPExcel->getActiveSheet()->setTitle('Mantenimiento '. $reporte);
    $objPHPExcel->setActiveSheetIndex(0);


    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch   = "../../temp/manteminiento ".$reporte. time() . ".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;
