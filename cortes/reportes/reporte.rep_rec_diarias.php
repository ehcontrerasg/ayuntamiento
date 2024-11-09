<?

/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
include_once "../../clases/class.corte.php";
/**
 * Created by PhpStorm.
 * User: Edwin
 * Date: 3/06/2016
 * Time: 11:01 AM
 */

$fechaIni=$_POST["fecIni"];
$fechaFin=$_POST["fecFin"];
$proyecto=$_POST["proyecto"];
$proIni=$_POST["proIni"];
$proFin=$_POST["proFin"];
$usuario=$_POST["usuario"];
$contratista=$_POST["contratista"];
$tip=$_POST["arch"];


if($tip=='pdf' ){
    include_once "../../clases/class.pdfRep.php";
    function compruebaSaltoHoja($pdf){

        global  $contPy;
        if($contPy>=208){
            $pdf->AddPage('L');
            cabeceraDet($pdf);
            $contPy=52;

        }
    }


    function compruebaSaltoHoja2($pdf){

        global  $contPy;
        if($contPy>=52){
            $pdf->AddPage('L');
            cabeceraDet($pdf);
            $contPy=52;

        }
    }

    function cabeceraDet($pdf){
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(64,128,191);

        $pdf->SetLineWidth(0.3);
        $pdf->SetY(42);
        $pdf->SetX(1);
        $pdf->Cell(280,5,utf8_decode('Codigo   Fecha Pago     Orden                                    Cliente                                              Sec  Rut    Imp                  Operario                          TC    Fecha Corte         Imp Corte   Fecha Visita        Oper Visita'),1,3,'L',true);

    }

    function cabeceraAgrup($pdf){
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(64,128,191);

        $pdf->SetLineWidth(0.3);
        $pdf->SetY(42);
        $pdf->SetX(5);
        $pdf->Cell(200,5,utf8_decode('Operario                                                                                     Recaudado               Total ejecutados       Total no ejecutados      Total Pagos'),1,3,'L',true);

    }




    $pdf = new pdfRepRecDia();


    $pdf->AliasNbPages();

    $pdf->setProyecto($proyecto);
    $pdf->AddPage('L');
    cabeceraDet($pdf);
    $a=new Corte();
    $stid =$a->getDatCortPagRecByProFecOperProc($proyecto,$fechaIni,$fechaFin,$usuario,$proIni,$proFin,$contratista);
    $contPy=52;
    $asiviejo="";
    $total=0;
    $cantrec=0;
    while (oci_fetch($stid))
    {

        compruebaSaltoHoja($pdf);

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
        $fecIns=oci_result($stid, 'FECHA_INS');
        $usrIns=oci_result($stid, 'USRINS');



        $pdf->SetTextColor(0,0,0);
        $pdf->Text(2,$contPy,utf8_decode($inmueble));
        $pdf->Text(15,$contPy,utf8_decode($fechPag));
        $pdf->Text(31,$contPy,utf8_decode($ordenCor));
        $pdf->Text(44,$contPy,utf8_decode(substr($cliente,0,30)));
        $pdf->Text(115,$contPy,utf8_decode($sector));
        $pdf->Text(121,$contPy,utf8_decode($ruta));
        $pdf->Text(126,$contPy,utf8_decode(money_format('%.2n', $importe)));
        $pdf->Text(140,$contPy,utf8_decode(substr($operario,0,18)));
        $pdf->Text(179,$contPy,utf8_decode($tipCorte));
        $pdf->Text(187,$contPy,utf8_decode($fecCorte));
        $pdf->Text(214,$contPy,utf8_decode($impCort));
        $pdf->Text(225,$contPy,utf8_decode($fecVisita));
        $pdf->Text(250,$contPy,utf8_decode($operVisito));


        $total+=$importe;
        $contPy+=4;
        $cantrec++;



    }

    $contPy+=3;
    $pdf->Text(115,$contPy,utf8_decode("Total:"));
    $pdf->Text(126,$contPy,utf8_decode(money_format('%.2n', $total)));

    $pdf->Text(2,$contPy,utf8_decode("Cantidad: "));
    $pdf->Text(15,$contPy,utf8_decode($cantrec));



    $pdf->AddPage();
    cabeceraAgrup($pdf);
    $a=new Corte();
    $stid =$a->getCantCortPagRecGrouOperByProFecOperProc($proyecto,$fechaIni,$fechaFin,$usuario,$proIni,$proFin,$contratista);
    $contPy=52;
    $asiviejo="";
    $total=0;
    $cantrec=0;
    while (oci_fetch($stid))
    {

        compruebaSaltoHoja($pdf);

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


        $pdf->SetTextColor(0,0,0);
        $pdf->Text(8,$contPy,utf8_decode($operario));
        $pdf->Text(85,$contPy,utf8_decode(money_format('%.2n', $importe)));
        $pdf->Text(118,$contPy,utf8_decode($cantidad));
        $pdf->Text(145,$contPy,utf8_decode($canVisita));
        $pdf->Text(175,$contPy,utf8_decode($canVisita+$cantidad));



        $contPy+=4;




    }
    $nomarch="../../temp/repcordiar".time().".pdf";
    $pdf->Output($nomarch,'F');
    echo $nomarch;
}



if($tip=='xls' ){
    include '../../recursos/PHPExcel.php';
    include '../../recursos/PHPExcel/Writer/Excel2007.php';

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->
    getProperties()
        ->setCreator("AceaSoft")
        ->setLastModifiedBy("AceaSoft")
        ->setTitle(utf8_encode("Reporte reconexiones diarias ") . $proyecto)
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

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:R1');

    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:R2')->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:R2")->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Reporte Pagos Reconexion
')
        ->setCellValue('A2', 'Código')
        ->setCellValue('B2', 'Fecha Pago')
        ->setCellValue('C2', 'Orden')
        ->setCellValue('D2', 'Cliente')
        ->setCellValue('E2', 'Sector')
        ->setCellValue('F2', 'Ruta')
        ->setCellValue('G2', 'Importe')
        ->setCellValue('H2', 'Operario')
        ->setCellValue('I2', 'Tipo Corte')
        ->setCellValue('J2', 'Fecha Corte')
        ->setCellValue('K2', 'Imposibilidad')
        ->setCellValue('L2', 'Fecha Visita')
        ->setCellValue('M2', 'Operario de visita')
        ->setCellValue('N2', 'Medido')
        ->setCellValue('O2', 'Gestion')
        ->setCellValue('p2', 'Facturas Pendientes')
        ->setCellValue('q2', 'Fecha Inspeccion')
        ->setCellValue('r2', 'Usuario Inspeccion')
    ;

    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(8);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(8);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(47);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(8);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(18);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(37);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(10);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(12);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(24);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(17);


    $fila = 3;
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
        $mdido=oci_result($stid, 'MEDIDOR');
        $stion=oci_result($stid, 'GESTION');
        $fac=oci_result($stid, 'FACTURAS');
        $fecIns=oci_result($stid, 'FECHA_INS');
        $usrIns=oci_result($stid, 'USRINS');



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
            ->setCellValue('M'.$fila, $fecVisita)
            ->setCellValue('N'.$fila, $mdido)
            ->setCellValue('O'.$fila, $stion)
            ->setCellValue('P'.$fila, $fac)
            ->setCellValue('q'.$fila, $fecIns)
            ->setCellValue('r'.$fila, $usrIns)
        ;
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







    }


    $objPHPExcel->getActiveSheet()->setTitle('Reconexiones');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Reconexiones_diarias".time().".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;


}
