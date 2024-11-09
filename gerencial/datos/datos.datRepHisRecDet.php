<?php
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];
error_reporting(E_ALL);
ini_set('display_errors', '1');

/*include_once '../../clases/class.reportes_gerenciales.php';
require_once '../clases/PHPExcel.php';*/

include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../../clases/class.reportes_gerenciales.php';
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
    $l=new ReportesGerencia();
    $datos = $l->seleccionaAcueducto($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='repHist'){

	$periodoini = $_POST['periodoini'];
	$periodofin = $_POST['periodofin'];
    $proyecto = $_POST['proyecto'];


$objPHPExcel = new PHPExcel();
$objPHPExcel->
	getProperties()
		->setCreator("AceaSoft")
		->setLastModifiedBy("AceaSoft")
		->setTitle("Histórico Detallado")
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

	$estiloLineaLateral = array(
		'borders' => array(
			'left' => array(
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
				'color' => array('rgb' => '000000')
			),
			'right' => array(
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
				'color' => array('rgb' => '000000')
			)
		)
	);

	$estiloNoLineas = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('rgb' => 'FFFFFF')
			)
		)
	);



    $estiloLineas = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => '000000')
            )
        )
    );

	$c=new ReportesGerencia();
	$registros=$c->getPeriodosRango($periodoini, $periodofin);
	$fila1='A';
	$fila2='K';


    $fila3='A';
    $fila4='C';

	while (oci_fetch($registros)) {
		$periodorango=utf8_decode(oci_result($registros,"ID_PERIODO"));
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells($fila1.'1:'.$fila2.'1');
        $objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($fila1.'1',"REPORTE HISTORICO RECAUDO POR SECTOR SD ".$proyecto."  " .$periodorango );
        $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila1.'1:'.$fila2.'1')->applyFromArray($estiloTitulos);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila1.'1:'.$fila2.'1')->getFont()->setBold(true);



        for ($i = 1; $i <= 11; $i++) {
            $fila1++;
            $fila2++;

            if($i==1) {



                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila3 . '2', "RECAUDO TOTAL");
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila3 . '3', "Sector");
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($temp . '3', "No Pagos");
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila4 . '3', "Recaudado");
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila4. '3')->getFont()->setBold(true);

                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerSec($proyecto,$periodorango,"");
                $col=3;

                $totalfacturas=0;
                $totalfacturado=0;

                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"SECTOR"));
                    $facturas=utf8_decode(oci_result($registros2,"FACTURAS"));
                    $facturados=utf8_decode(oci_result($registros2,"FACTURADO"));

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila4 . $col, $facturados);
                    $totalfacturas+=$facturas;
                    $totalfacturado+=$facturados;


                }
                $col++;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($temp . $col, $totalfacturas);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($fila3)->setWidth(7);
                $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($temp)->setWidth(11);
                $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($fila4)->setWidth(12);





            }



            if($i==5) {

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila3 . '2', "RECAUDADO NORTE");
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);



                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila3 . '3', "Sector");
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($temp . '3', "No Pagos");
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila4 . '3', "Recaudado");
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila4. '3')->getFont()->setBold(true);


                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerSec($proyecto,$periodorango,"N");
                $col=3;

                $totalfacturas=0;
                $totalfacturado=0;
                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"SECTOR"));
                    $facturas=utf8_decode(oci_result($registros2,"FACTURAS"));
                    $facturados=utf8_decode(oci_result($registros2,"FACTURADO"));

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila4 . $col, $facturados);

                    $totalfacturas+=$facturas;
                    $totalfacturado+=$facturados;

                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($temp . $col, $totalfacturas);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($fila3)->setWidth(7);
                $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($temp)->setWidth(11);
                $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($fila4)->setWidth(12);


            }


            if($i==9) {

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila3 . '2', "RECAUDO ESTE");
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila3 . '3', "Sector");
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($temp . '3', "No Pagos");
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila4 . '3', "Facturado");
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila4. '3')->getFont()->setBold(true);


                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerSec($proyecto,$periodorango,"E");
                $col=3;

                $totalfacturas=0;
                $totalfacturado=0;

                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"SECTOR"));
                    $facturas=utf8_decode(oci_result($registros2,"FACTURAS"));
                    $facturados=utf8_decode(oci_result($registros2,"FACTURADO"));

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila4 . $col, $facturados);

                    $totalfacturas+=$facturas;
                    $totalfacturado+=$facturados;

                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($temp . $col, $totalfacturas);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(0)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($fila3)->setWidth(7);
                $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($temp)->setWidth(11);
                $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($fila4)->setWidth(12);

            }

            $fila3++;
            $fila4++;



        }

        $fila1++;
        $fila2++;
        $fila3++;
        $fila4++;

	}oci_free_statement($registros);


	$objPHPExcel->setActiveSheetIndex(0);

	/// FIN PRIMERA HOJA
    ///
    ///
    ///
    ///
    ///
//////////////////// INICIA HOJA 2


    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Concepto');

    $objPHPExcel->addSheet($myWorkSheet, 1);




    $c=new ReportesGerencia();
    $registros=$c->getPeriodosRango($periodoini, $periodofin);
    $fila1='A';
    $fila2='K';


    $fila3='A';
    $fila4='C';

    while (oci_fetch($registros)) {
        $periodorango=utf8_decode(oci_result($registros,"ID_PERIODO"));
        $objPHPExcel->setActiveSheetIndex(1)->mergeCells($fila1.'1:'.$fila2.'1');
        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue($fila1.'1',"REPORTE HISTORICO RECAUDO POR CONCEPTO SD ".$proyecto."  " .$periodorango );
        $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila1.'1:'.$fila2.'1')->applyFromArray($estiloTitulos);
        $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila1.'1:'.$fila2.'1')->getFont()->setBold(true);



        for ($i = 1; $i <= 11; $i++) {
            $fila1++;
            $fila2++;

            if($i==1) {



                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila3 . '2', "RECAUDO TOTAL");
                $objPHPExcel->setActiveSheetIndex(1)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila3 . '3', "Numero");
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($temp . '3', "Descripcion");
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila4 . '3', "Recaudo");
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila4. '3')->getFont()->setBold(true);

                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerCon($proyecto,$periodorango,"");
                $col=3;


                $totalfacturado=0;

                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"CONCEPTO"));
                    $facturas=oci_result($registros2,"DESCRIPCION");
                    $facturados=utf8_decode(oci_result($registros2,"FACTURADO"));

                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila4 . $col, $facturados);

                    $totalfacturado+=$facturados;


                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension($fila3)->setWidth(8);
                $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension($temp)->setWidth(36);
                $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension($fila4)->setWidth(12);


            }

            if($i==5) {

                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila3 . '2', "RECAUDO NORTE");
                $objPHPExcel->setActiveSheetIndex(1)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);



                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila3 . '3', "Numero");
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($temp . '3', "Descripcion");
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila4 . '3', "Recaudo");
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila4. '3')->getFont()->setBold(true);


                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerCon($proyecto,$periodorango,"N");
                $col=3;
                $totalfacturado=0;
                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"CONCEPTO"));
                    $facturas=oci_result($registros2,"DESCRIPCION");
                    $facturados=utf8_decode(oci_result($registros2,"FACTURADO"));

                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila4 . $col, $facturados);

                    $totalfacturado+=$facturados;


                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension($fila3)->setWidth(8);
                $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension($temp)->setWidth(36);
                $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension($fila4)->setWidth(12);


            }


            if($i==9) {

                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila3 . '2', "RECAUDO ESTE");
                $objPHPExcel->setActiveSheetIndex(1)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila3 . '3', "Numero");
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($temp . '3', "Descripcion");
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila4 . '3', "Recaudo");
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila4. '3')->getFont()->setBold(true);


                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerCon($proyecto,$periodorango,"E");
                $col=3;
                $totalfacturado=0;
                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"CONCEPTO"));
                    $facturas= oci_result($registros2,"DESCRIPCION");
                    $facturados=oci_result($registros2,"FACTURADO");
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila4 . $col, $facturados);


                    $totalfacturado+=$facturados;


                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(1)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(1)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension($fila3)->setWidth(8);
                $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension($temp)->setWidth(36);
                $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension($fila4)->setWidth(12);

            }

            $fila3++;
            $fila4++;



        }

        $fila1++;
        $fila2++;
        $fila3++;
        $fila4++;

    }oci_free_statement($registros);

// FIN HOJA 2





//////////////////// INICIA HOJA 3


    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Uso');

    $objPHPExcel->addSheet($myWorkSheet, 2);




    $c=new ReportesGerencia();
    $registros=$c->getPeriodosRango($periodoini, $periodofin);
    $fila1='A';
    $fila2='K';


    $fila3='A';
    $fila4='C';

    while (oci_fetch($registros)) {
        $periodorango=utf8_decode(oci_result($registros,"ID_PERIODO"));
        $objPHPExcel->setActiveSheetIndex(2)->mergeCells($fila1.'1:'.$fila2.'1');
        $objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue($fila1.'1',"REPORTE HISTORICO RECAUDO POR USO SD ".$proyecto."  " .$periodorango );
        $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila1.'1:'.$fila2.'1')->applyFromArray($estiloTitulos);
        $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila1.'1:'.$fila2.'1')->getFont()->setBold(true);



        for ($i = 1; $i <= 11; $i++) {
            $fila1++;
            $fila2++;

            if($i==1) {



                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila3 . '2', "RECAUDO TOTAL");
                $objPHPExcel->setActiveSheetIndex(2)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila3 . '3', "Uso");
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($temp . '3', "No Pagos");
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila4 . '3', "Recaudo");
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila4. '3')->getFont()->setBold(true);

                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerUso($proyecto,$periodorango,"");
                $col=3;
                $totalfacturado=0;
                $totalfacturas=0;
                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"USO"));
                    $facturas=oci_result($registros2,"FACTURAS");
                    $facturados=utf8_decode(oci_result($registros2,"FACTURADO"));

                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila4 . $col, $facturados);
                    $totalfacturas+=$facturas;
                    $totalfacturado+=$facturados;

                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($temp . $col, $totalfacturas);
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);

                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension($fila3)->setWidth(17);
                $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension($temp)->setWidth(11);
                $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension($fila4)->setWidth(12);


            }

            if($i==5) {

                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila3 . '2', "RECAUDO NORTE");
                $objPHPExcel->setActiveSheetIndex(2)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);



                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila3 . '3', "Uso");
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($temp . '3', "No pagod");
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila4 . '3', "Recaudo");
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila4. '3')->getFont()->setBold(true);


                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerUso($proyecto,$periodorango,"N");
                $col=3;
                $totalfacturado=0;
                $totalfacturas=0;
                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"USO"));
                    $facturas=oci_result($registros2,"FACTURAS");
                    $facturados=utf8_decode(oci_result($registros2,"FACTURADO"));

                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila4 . $col, $facturados);

                    $totalfacturas+=$facturas;
                    $totalfacturado+=$facturados;

                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($temp . $col, $totalfacturas);
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);

                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension($fila3)->setWidth(17);
                $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension($temp)->setWidth(11);
                $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension($fila4)->setWidth(12);

            }


            if($i==9) {

                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila3 . '2', "RECAUDO ESTE");
                $objPHPExcel->setActiveSheetIndex(2)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila3 . '3', "Uso");
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($temp . '3', "No pagod");
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila4 . '3', "Recaudo");
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila4. '3')->getFont()->setBold(true);


                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerUso($proyecto,$periodorango,"E");
                $col=3;
                $totalfacturado=0;
                $totalfacturas=0;
                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"USO"));
                    $facturas= oci_result($registros2,"FACTURAS");
                    $facturados=oci_result($registros2,"FACTURADO");
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila4 . $col, $facturados);


                    $totalfacturas+=$facturas;
                    $totalfacturado+=$facturados;

                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($temp . $col, $totalfacturas);
                $objPHPExcel->setActiveSheetIndex(2)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);

                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(2)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension($fila3)->setWidth(17);
                $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension($temp)->setWidth(11);
                $objPHPExcel->setActiveSheetIndex(2)->getColumnDimension($fila4)->setWidth(12);


            }

            $fila3++;
            $fila4++;



        }

        $fila1++;
        $fila2++;
        $fila3++;
        $fila4++;

    }oci_free_statement($registros);

// FIN HOJA 3





//////////////////// INICIA HOJA 4


    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Medidos por Concepto');

    $objPHPExcel->addSheet($myWorkSheet, 3);




    $c=new ReportesGerencia();
    $registros=$c->getPeriodosRango($periodoini, $periodofin);
    $fila1='A';
    $fila2='K';


    $fila3='A';
    $fila4='C';

    while (oci_fetch($registros)) {
        $periodorango=utf8_decode(oci_result($registros,"ID_PERIODO"));
        $objPHPExcel->setActiveSheetIndex(3)->mergeCells($fila1.'1:'.$fila2.'1');
        $objPHPExcel->setActiveSheetIndex(3)
            ->setCellValue($fila1.'1',"REPORTE HISTORICO RECAUDO MEDIDOS POR CONCEPTO SD ".$proyecto."  " .$periodorango );
        $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila1.'1:'.$fila2.'1')->applyFromArray($estiloTitulos);
        $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila1.'1:'.$fila2.'1')->getFont()->setBold(true);



        for ($i = 1; $i <= 11; $i++) {
            $fila1++;
            $fila2++;

            if($i==1) {



                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila3 . '2', "RECAUDO TOTAL");
                $objPHPExcel->setActiveSheetIndex(3)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila3 . '3', "Numero");
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($temp . '3', "Descripcion");
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila4 . '3', "Recaudo");
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila4. '3')->getFont()->setBold(true);

                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerConMed($proyecto,$periodorango,"");
                $col=3;
                $totalfacturado=0;
                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"CONCEPTO"));
                    $facturas=oci_result($registros2,"DESCRIPCION");
                    $facturados=utf8_decode(oci_result($registros2,"FACTURADO"));

                    $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(3)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila4 . $col, $facturados);

                    $totalfacturado+=$facturados;

                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);

                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension($fila3)->setWidth(17);
                $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension($temp)->setWidth(11);
                $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension($fila4)->setWidth(12);




            $fila3++;
            $fila4++;



        }


            if($i==5) {

                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila3 . '2', "RECAUDO NORTE");
                $objPHPExcel->setActiveSheetIndex(3)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);



                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila3 . '3', "Numero");
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($temp . '3', "Descripcion");
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila4 . '3', "Recaudo");
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila4. '3')->getFont()->setBold(true);


                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerConMed($proyecto,$periodorango,"N");
                $col=3;
                $totalfacturado=0;
                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"CONCEPTO"));
                    $facturas=oci_result($registros2,"DESCRIPCION");
                    $facturados=utf8_decode(oci_result($registros2,"FACTURADO"));

                    $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(3)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila4 . $col, $facturados);


                    $totalfacturado+=$facturados;

                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension($fila3)->setWidth(17);
                $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension($temp)->setWidth(11);
                $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension($fila4)->setWidth(12);


            }


            if($i==9) {

                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila3 . '2', "RECAUDO ESTE");
                $objPHPExcel->setActiveSheetIndex(3)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila3 . '3', "Numero");
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($temp . '3', "Descripcion");
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila4 . '3', "Recaudo");
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila4. '3')->getFont()->setBold(true);


                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerConMed($proyecto,$periodorango,"E");
                $col=3;
                $totalfacturado=0;
                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"CONCEPTO"));
                    $facturas= oci_result($registros2,"DESCRIPCION");
                    $facturados=oci_result($registros2,"FACTURADO");
                    $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(3)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila4 . $col, $facturados);


                    $totalfacturado+=$facturados;

                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(3)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);

                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(3)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension($fila3)->setWidth(17);
                $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension($temp)->setWidth(11);
                $objPHPExcel->setActiveSheetIndex(3)->getColumnDimension($fila4)->setWidth(12);
            }

            $fila3++;
            $fila4++;



        }

        $fila1++;
        $fila2++;
        $fila3++;
        $fila4++;

    }oci_free_statement($registros);

// FIN HOJA 4






//////////////////// INICIA HOJA 4


    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'Concepto Agrupado');

    $objPHPExcel->addSheet($myWorkSheet, 4);




    $c=new ReportesGerencia();
    $registros=$c->getPeriodosRango($periodoini, $periodofin);
    $fila1='A';
    $fila2='K';


    $fila3='A';
    $fila4='C';

    while (oci_fetch($registros)) {
        $periodorango=utf8_decode(oci_result($registros,"ID_PERIODO"));
        $objPHPExcel->setActiveSheetIndex(4)->mergeCells($fila1.'1:'.$fila2.'1');
        $objPHPExcel->setActiveSheetIndex(4)
            ->setCellValue($fila1.'1',"REPORTE HISTORICO RECAUDO POR CONCEPTO AGRUPADO SD ".$proyecto."  " .$periodorango );
        $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila1.'1:'.$fila2.'1')->applyFromArray($estiloTitulos);
        $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila1.'1:'.$fila2.'1')->getFont()->setBold(true);



        for ($i = 1; $i <= 11; $i++) {
            $fila1++;
            $fila2++;

            if($i==1) {



                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila3 . '2', "RECAUDO TOTAL");
                $objPHPExcel->setActiveSheetIndex(4)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila3 . '3', "Concepto");
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($temp . '3', "Descripcion");
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila4 . '3', "Recaudo");
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila4. '3')->getFont()->setBold(true);

                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerConAgrup($proyecto,$periodorango,"");
                $col=3;
                $totalfacturado=0;
                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"CONCEPTO"));
                    $facturas=oci_result($registros2,"DESCRIPCION");
                    $facturados=utf8_decode(oci_result($registros2,"FACTURADO"));

                    $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(4)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila4 . $col, $facturados);


                    $totalfacturado+=$facturados;

                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);

                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getColumnDimension($fila3)->setWidth(17);
                $objPHPExcel->setActiveSheetIndex(4)->getColumnDimension($temp)->setWidth(11);
                $objPHPExcel->setActiveSheetIndex(4)->getColumnDimension($fila4)->setWidth(12);


            }

            if($i==5) {

                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila3 . '2', "RECAUDO NORTE");
                $objPHPExcel->setActiveSheetIndex(4)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);



                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila3 . '3', "Concepto");
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($temp . '3', "Descripcion");
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila4 . '3', "Recaudo");
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila4. '3')->getFont()->setBold(true);


                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerConAgrup($proyecto,$periodorango,"N");
                $col=3;
                $totalfacturado=0;
                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"CONCEPTO"));
                    $facturas=oci_result($registros2,"DESCRIPCION");
                    $facturados=utf8_decode(oci_result($registros2,"FACTURADO"));

                    $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(4)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila4 . $col, $facturados);


                    $totalfacturado+=$facturados;

                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);

                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getColumnDimension($fila3)->setWidth(17);
                $objPHPExcel->setActiveSheetIndex(4)->getColumnDimension($temp)->setWidth(11);
                $objPHPExcel->setActiveSheetIndex(4)->getColumnDimension($fila4)->setWidth(12);


            }


            if($i==9) {

                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila3 . '2', "RECAUDO ESTE");
                $objPHPExcel->setActiveSheetIndex(4)->mergeCells($fila3 . '2:' . $fila4 . '2');
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '2:' . $fila4 . '2')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '2:' . $fila4 . '2')->getFont()->setBold(true);


                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila3 . '3', "Concepto");
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '3')->getFont()->setBold(true);

                $temp=$fila3;
                $temp++;
                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($temp . '3', "Descripcion");
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($temp . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($temp . '3')->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila4 . '3', "Recaudo");
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila4 . '3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila4. '3')->getFont()->setBold(true);


                $c2=new ReportesGerencia();
                $registros2=$c2->getHistRecPerConAgrup($proyecto,$periodorango,"E");
                $col=3;
                $totalfacturado=0;
                while (oci_fetch($registros2)) {
                    $col++;
                    $sector=utf8_decode(oci_result($registros2,"CONCEPTO"));
                    $facturas= oci_result($registros2,"DESCRIPCION");
                    $facturados=oci_result($registros2,"FACTURADO");
                    $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila3 . $col, $sector);
                    $objPHPExcel->setActiveSheetIndex(4)->setCellValue($temp . $col, $facturas);
                    $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila4 . $col, $facturados);


                    $totalfacturado+=$facturados;

                }

                $col++;
                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila3 . $col, 'TOTAL');
                $objPHPExcel->setActiveSheetIndex(4)->setCellValue($fila4 . $col, $totalfacturado);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . $col.':' . $fila4 . $col)->getFont()->setBold(true);

                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '3:'.$fila4.$col)->applyFromArray($estiloLineas);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila4 . '4:'.$fila4.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($temp . '4:'.$temp.$col)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->setActiveSheetIndex(4)->getStyle($fila3 . '3:'.$fila4.'3')->applyFromArray($estiloTitulos);
                $objPHPExcel->setActiveSheetIndex(4)->getColumnDimension($fila3)->setWidth(17);
                $objPHPExcel->setActiveSheetIndex(4)->getColumnDimension($temp)->setWidth(11);
                $objPHPExcel->setActiveSheetIndex(4)->getColumnDimension($fila4)->setWidth(12);
            }

            $fila3++;
            $fila4++;



        }

        $fila1++;
        $fila2++;
        $fila3++;
        $fila4++;

    }oci_free_statement($registros);

// FIN HOJA 5



    $objPHPExcel->setActiveSheetIndex(0);




    //mostrar la hoja que se abrira
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle("Sector");
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Historico_rec_detallado".$proyecto."_".$periodoini."-".$periodofin.".xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;

}
?>