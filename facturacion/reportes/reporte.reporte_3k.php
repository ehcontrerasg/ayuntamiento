<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 6/25/2016
 * Time: 11:51 AM
 */
setlocale(LC_MONETARY, 'es_DO');
$descarga=$_POST['descarga'];
$proyecto=$_POST['proyecto'];
$periodo=$_POST['periodo'];
$zona=$_POST['zona'];
$raiz=$_POST['raizNCF'];



include "../../clases/class.pdfRep.php";
include "../../clases/class.factura.php";
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';

$a =new Factura();
$datos=$a->getNCFbyRaizProPerZon($raiz,$periodo,$proyecto,$zona);

if($descarga == 'pdf'){

    $pdf = new PdfRep3k();
    $pdf->setProyecto($proyecto);

    $pdf->SetTextColor(0,0,0);
    $pdf->AliasNbPages();
    $pdf->AddPage('L');

    $y=45;
    $secrut='0';

    while (oci_fetch($datos)){
        $inmueble=oci_result($datos,"CODIGO_INM");
        $urbanizacion=oci_result($datos,"DESC_URBANIZACION");
        $direccion=oci_result($datos,"DIRECCION");
        $cliente=oci_result($datos,"CLIENTE");
        $proceso=oci_result($datos,"ID_PROCESO");
        $catastro=oci_result($datos,"CATASTRO") ;
        $medidor=oci_result($datos,"DESC_MED");
        $serial=oci_result($datos,"SERIAL");
        $emplazamiento=oci_result($datos,"DESC_EMPLAZAMIENTO");
        $calibre=oci_result($datos,"DESC_CALIBRE");
        $uso= oci_result($datos,"ID_USO");
        $estado=oci_result($datos,"ID_ESTADO");
        $lectura =oci_result($datos,"ULT_LECT");

        if($y>=200){
            $pdf->AddPage('L');
            $y=45;
        }

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('times',"",8);
        $pdf->Text(3,$y,utf8_decode("$inmueble"));
        $pdf->Text(15,$y,ucwords(strtolower(utf8_decode("$urbanizacion"))) );
        $pdf->Text(37,$y,ucwords(strtolower(utf8_decode("$direccion"))));
        $pdf->Text(90,$y,substr(ucwords(strtolower(utf8_decode("$cliente"))),0,30) );
        $pdf->Text(134,$y,utf8_decode("$proceso"));
        $pdf->Text(153,$y,utf8_decode("$catastro"));
        $pdf->Text(183,$y,utf8_decode("$medidor"));
        $pdf->Text(202,$y,utf8_decode("$serial"));
        $pdf->Text(222,$y,utf8_decode("$emplazamiento"));
        $pdf->Text(245,$y,utf8_decode("$calibre"));
        $pdf->Text(260,$y,utf8_decode("$uso"));
        $pdf->Text(270,$y,utf8_decode("$estado"));
        $pdf->Text(280,$y,utf8_decode("$lectura"));

        $y+=5;
    }oci_free_statement($datos);

    $nomarch="../../temp/Inmuebles_Mayor_3K_Metros".time().".pdf";
    $pdf->Output($nomarch,'F');
    echo $nomarch;
}
if($descarga == 'xls'){
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
    getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle(utf8_encode("Reporte Simulacion NCF ").$proyecto)
        ->setSubject("")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("usuarios phpexcel")
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

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:M1');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:M2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:M2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'SIMULACION DE NCF')
        ->setCellValue('A2', 'Inmueble')
        ->setCellValue('B2', 'Total')
        ->setCellValue('C2', 'Raíz')
        ->setCellValue('D2', 'Tipo de documento')
        ->setCellValue('E2', 'Documento')
        ->setCellValue('F2', 'Consecutivo')
        ->setCellValue('G2', 'Zona');

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(32);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(55);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(13);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(9);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(9);

    $fila = 3;
    $incrementoConsecutivo=0;
    while (oci_fetch($datos)){
        $inmueble=oci_result($datos,"INMUEBLE");
        $total=oci_result($datos,"TOTAL");
        $idNCF=oci_result($datos,"ID_NCF");
        $descTipDoc=oci_result($datos,"DESCRIPCION_TIPO_DOC");
        $documento=oci_result($datos,"DOCUMENTO") ;
        $consecutivo=oci_result($datos,"CONSECUTIVO")+$incrementoConsecutivo;
        $zona=oci_result($datos,"ID_ZONA");
        /*$emplazamiento=oci_result($datos,"DESC_EMPLAZAMIENTO");
        $calibre=oci_result($datos,"DESC_CALIBRE");
        $uso= oci_result($datos,"ID_USO");
        $estado=oci_result($datos,"ID_ESTADO");
        $lectura =oci_result($datos,"ULT_LECT");*/

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$fila, $inmueble)
            ->setCellValue('B'.$fila, $total)
            ->setCellValue('C'.$fila, $idNCF)
            ->setCellValue('D'.$fila, $descTipDoc)
            ->setCellValue('E'.$fila, $documento)
            ->setCellValue('F'.$fila, $consecutivo)
            ->setCellValue('G'.$fila, $zona);
           /* ->setCellValue('G'.$fila, $medidor)
            ->setCellValue('H'.$fila, $serial)
            ->setCellValue('I'.$fila, $emplazamiento)
            ->setCellValue('J'.$fila, $calibre)
            ->setCellValue('K'.$fila, $uso)
            ->setCellValue('L'.$fila, $estado)
            ->setCellValue('M'.$fila, $lectura);*/
        $fila++;
        $incrementoConsecutivo++;
    }oci_free_statement($datos);

    $objPHPExcel->getActiveSheet()->setTitle('Listado Inmubles');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/SIMULACION_NCF".time().".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;
}