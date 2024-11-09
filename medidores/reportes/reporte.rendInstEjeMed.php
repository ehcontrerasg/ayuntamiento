<?php
/**
 * Created by PhpStorm.
 * User: Jesus Gutierrez
 * Date: 26/02/2020
 * Time: 02:02 PM
 */

include "../../recursos/PHPExcel.php";
include "../../recursos/PHPExcel/Writer/Excel2007.php";
include "../../clases/class.medidor.php";
ini_set('memory_limit', '-1');
$acueducto = $_POST["proyecto"];
$sector = $_POST["sector"];
$fecini = $_GET["fecini"];
$fecfin = $_GET["fecfin"];

$objPHPExcel = new PHPExcel();
$objPHPExcel->
getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Rendimiento por Ejecución")
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("rendXeje phpexcel")
    ->setCategory("Reportes Medidores");

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

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:O1')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:O1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'No')
    ->setCellValue('B1', utf8_encode(utf8_decode('Código')))
    ->setCellValue('C1', utf8_encode(utf8_decode('Nombre')))
    ->setCellValue('D1', utf8_encode(utf8_decode('Dirección')))
    ->setCellValue('E1', utf8_encode(utf8_decode('Urbanización')))
    ->setCellValue('F1', utf8_encode('Sector'))
    ->setCellValue('G1', utf8_encode('Ruta'))
    ->setCellValue('H1', utf8_encode('Proceso'))
    ->setCellValue('I1', utf8_encode('Catastro'))
    ->setCellValue('J1', utf8_encode('Medidor'))
    ->setCellValue('K1', utf8_encode('Serial'))
    ->setCellValue('L1', utf8_encode('Calibre.'))
    ->setCellValue('M1', utf8_encode('Fec. Instala'))
    ->setCellValue('N1', utf8_encode(utf8_decode('Observación')))
    ->setCellValue('O1', utf8_encode(utf8_decode('Foto')));
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(5);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(30);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(10);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(8);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(15);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(30);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("O")->setWidth(30);

$fila = 2;
$cont = 1;

$a =new Medidor();
$datos=$a->obtenerDetalleRutasInstMed( $acueducto, $sector, $fecini, $fecfin);
while (oci_fetch($datos)) {
    $codSis=oci_result($datos,"CODIGO_INM");
    $nombre=oci_result($datos,"NOMBRE");
    $direccion=oci_result($datos,"DIRECCION");
    $urb=oci_result($datos,"DESC_URBANIZACION");
    $sec=oci_result($datos,"ID_SECTOR");
    $ruta=oci_result($datos,"ID_RUTA");
    $proceso=oci_result($datos,"ID_PROCESO");
    $catastro=oci_result($datos,"CATASTRO");
    $medidor=oci_result($datos,"MARCA_MEDNUEVO") ;
    $serial=oci_result($datos,"SERIAL_MEDNUEVO");
    $calibre=oci_result($datos,"CALIBRE_MEDNUEVO");
    $fecha=oci_result($datos,"FECHA_REEALIZACION");
    $observa= oci_result($datos,"OBSERVACIONES");
    $orden= oci_result($datos,"ID_ORDEN");
    $urlfoto = oci_result($datos,"URL_FOTO");
    if ($urlfoto <> '') {
       $urlfoto ="https://aceasoft.com/medidores/vistas/vista.fotos_InstMed.php?orden=$orden";
        //$urlfoto = "https://aceasoft.com/medidores/vistas/vista.fotos_InstMed.php?orden=$orden";
    }
    else {
        $urlfoto = "";
    }
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, $cont)
        ->setCellValue('B'.$fila, $codSis)
        ->setCellValue('C'.$fila, $nombre)
        ->setCellValue('D'.$fila, $direccion)
        ->setCellValue('E'.$fila, $urb)
        ->setCellValue('F'.$fila, $sec)
        ->setCellValue('G'.$fila, $ruta)
        ->setCellValue('H'.$fila, $proceso)
        ->setCellValue('I'.$fila, $catastro)
        ->setCellValue('J'.$fila, $medidor)
        ->setCellValue('K'.$fila, $serial)
        ->setCellValue('L'.$fila, $calibre)
        ->setCellValue('M'.$fila, date('d/m/Y',strtotime($fecha)))
        ->setCellValue('N'.$fila, $observa)
        ->setCellValue('O'.$fila, $urlfoto);
    $cont++;
    $fila++;
    unset($codSis, $nombre, $direccion,$urb,$sec,$ruta,$proceso,$catastro,$medidor,$serial,$calibre,$fecha,$observa);
}oci_free_statement($datos);

$objPHPExcel->getActiveSheet()->setTitle('Rendimiento x Ejecución');

//mostrar la hoja q se abrira
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/Rendimiento_X_Ejecucuion_Del_".$fecini."_al_".$fecfin.".xlsx";
$objWriter->save($nomarch);
echo $nomarch;
?>