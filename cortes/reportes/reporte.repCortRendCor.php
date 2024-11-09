<?

include_once "../../clases/class.corte.php";
/**
 * Created by PhpStorm.
 * User: Edwin
 * Date: 3/06/2016
 * Time: 11:01 AM
 */

$fechaIni=$_POST["FecIni"];
$fechaFin=$_POST["FecFin"];
$proyecto=$_POST["proyecto"];
$contratista=$_POST["contratista"];
$tip=$_POST["formato"];




if($tip=='xls' ){
    include '../../recursos/PHPExcel.php';
    include '../../recursos/PHPExcel/Writer/Excel2007.php';

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
    getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle(utf8_encode("Reporte de rendimiento ") . $proyecto)
        ->setSubject("")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("usuarios phpexcel")
        ->setCategory("Reportes Cortes");

    $estiloTitulos = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                'color' => array('rgb' => '000000')
            )
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:F2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:F2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Rendimiento de Corte')
        ->setCellValue('A2', 'Empleado')
        ->setCellValue('B2', 'Predios Visitados')
        ->setCellValue('C2', 'Cortes Raalizados')
        ->setCellValue('D2', '# Rec despues del corte o visita')
        ->setCellValue('E2', 'Prom. reconexiones diarias')
        ->setCellValue('F2', 'Total Promedio');

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(8);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(8);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(47);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(8);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(15);


    /*$fila = 3;
    $total=0;
    $a=new Corte();
    $stid =$a->getDatCortPagRecByProFecOperProc($proyecto,$fechaIni,$fechaFin,$usuario,$proIni,$proFin,$contratista);
    while (oci_fetch($stid))

    {
        $inmueble=oci_result($stid, 'INMUEBLE') ;
        $fechPag=oci_result($stid, 'FECHA_PAGO') ;
        $ordenCor=oci_result($stid, 'ORDEN') ;
        $cliente=oci_result($stid, 'NOMBRE') ;
        $sector=oci_result($stid, 'ID_SECTOR') ;
        $ruta=oci_result($stid, 'ID_RUTA') ;
        $importe=oci_result($stid, 'IMPORTE') ;
        $operario=oci_result($stid, 'OPERARIO') ;
        $tipCorte=oci_result($stid, 'TIPOCORTE') ;
        $fecCorte=oci_result($stid, 'FECHA_EJE') ;
        $impCort=oci_result($stid, 'OBS_ORIG_APER');
        $operVisito=oci_result($stid, 'OPER_ORIGIN_APER');
        $fecVisita=oci_result($stid, 'FEC_GEN_APER');



        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$fila, $inmueble)
            ->setCellValue('B'.$fila, $fechPag)
            ->setCellValue('C'.$fila, $ordenCor)
            ->setCellValue('D'.$fila, $cliente)
            ->setCellValue('E'.$fila, $sector)
            ->setCellValue('F'.$fila, $ruta)
            ->setCellValue('G'.$fila, $importe)
            ->setCellValue('H'.$fila, $operario)
            ->setCellValue('I'.$fila, $tipCorte)
            ->setCellValue('J'.$fila, $fecCorte)
            ->setCellValue('K'.$fila, $impCort)
            ->setCellValue('L'.$fila, $operVisito)
            ->setCellValue('M'.$fila, $fecVisita);
        $total+=$importe;
        $cantrec++;
        $fila++;



    }

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$fila, "cantidad")
        ->setCellValue('B'.$fila, $cantrec)
        ->setCellValue('F'.$fila, "Total")
        ->setCellValue('G'.$fila, $total);




    $fila+=3;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('D'.$fila, "Operario")
        ->setCellValue('E'.$fila, "Recaudo")
        ->setCellValue('F'.$fila, "Total Ejecutados")
        ->setCellValue('G'.$fila, "Total no ejecutados")
        ->setCellValue('H'.$fila, "Total Pagos");
    $fila++;
    $a=new Corte();
    $stid =$a->getCantCortPagRecGrouOperByProFecOperProc($proyecto,$fechaIni,$fechaFin,$usuario,$proIni,$proFin,$contratista);
    $asiviejo="";
    $total=0;
    $cantrec=0;
    while (oci_fetch($stid))
    {


        $cantidad=oci_result($stid, 'CANTIDAD') ;
        $importe=oci_result($stid, 'IMPORTE') ;
        $operario=oci_result($stid, 'OPERARIO') ;
        $operario2=oci_result($stid, 'OPERARIO2') ;


        $x=new Corte();
        $stida =$x->getDatCortPagRecVisByProFecOperProc($proyecto,$fechaIni,$fechaFin,$operario2,$proIni,$proFin);
        while (oci_fetch($stida))
        {
            $canVisita=oci_result($stida, 'CANTIDAD');
        }



        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D'.$fila, $operario)
            ->setCellValue('E'.$fila, $importe)
            ->setCellValue('F'.$fila, $cantidad)
            ->setCellValue('G'.$fila, $canVisita)
            ->setCellValue('H'.$fila, $canVisita+$cantidad);
        $fila++;







    }*/


    $objPHPExcel->getActiveSheet()->setTitle('Rendimiento');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Rendimiento_corte".time().".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;


}
