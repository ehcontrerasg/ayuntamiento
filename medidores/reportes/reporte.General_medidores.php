<?php
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include_once '../../clases/class.lectura.php';
include_once '../../clases/class.medidor.php';
include_once  '../../clases/class.pdfRep.php';

/*ini_set('memory_limit', '-1');
ini_set('display_errors', 1);*/
$periodo=$_POST['periodo'];
$proyecto=$_POST['proyecto'];
$nombre_formulario = "";
$edicion_formulario = "";
$fecha_edicion = "";
$imagen = "";


$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo acea');
//$objDrawing->setPath('../../images/logo_acea.png');


$r = new Reporte();


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


$estiloLineaBaja = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
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
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        ),
        'right' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    )
);


$estiloLineaDelgada = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        )
    )
);

$estiloTitulos2 = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('rgb' => '000000')
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        'vertical'   =>PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);




$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:Z99')->applyFromArray(

    array(

        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => 'FFFFFF')
            )
        )
    )

);

$objPHPExcel->getProperties()->setCreator("Aceasoft");
$objPHPExcel->getProperties()->setLastModifiedBy("Aaceasoft");
$objPHPExcel->getProperties()->setTitle("Reporte General de Medidores");
$objPHPExcel->getProperties()->setSubject("Reporte");
$objPHPExcel->getProperties()->setDescription("Reporte general aceasoft");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:B6') ;
$objDrawing->setCoordinates('A1');
$objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));



$objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:Z99")->getFont()
    ->setName('Arial')
    ->setSize(12);


//TRAER LAS EDICIONES DEL FORMULARIO
$ediciones = $r->getFormDates('FO-MED-14');

while($row = oci_fetch_assoc($ediciones)){
    //echo date('Ym',strtotime($row["FECHA_EMISION"]))/*print_r($row)*/;
   // echo count($row);
    if(strtotime($periodo) >= strtotime(date('Ym',strtotime($row["FECHA_EMISION"])))){

        $nombre_formulario  =  $row["DESCRIPCION"];
        $edicion_formulario =  $row["EDICION"];
        $fecha_edicion      =  $row["FECHA_EMISION"];
        $imagen             =  $row["IMAGEN"];
        //echo $nombre_formulario;

    }
}

//$this->SetFont('times', "B", 19);

//$this->Text(54, 11, utf8_decode($nombre_formulario/*"Relación de inmuebles  "*/));
if($imagen != ""){
    $objDrawing->setWidth(2);
    $objDrawing->setHeight(100);
    $objDrawing->setPath($imagen/*'../../images/logo_acea.png'*/);

   // $this->Image($this->imagen, 7.5, 3.5, 17, 15.5);
}
/*$this->SetFont('times', "B", 10);
$this->Text(365, 10, utf8_decode("Código:"));
$this->Text(365, 13, utf8_decode("Edición No.:"));
$this->Text(365, 16, utf8_decode("Fecha de emisión:"));

$this->SetFont('times', "", 10);
$this->Text(394, 10, utf8_decode("FO-MED-05"));
$this->Text(394, 13, utf8_decode($edicion_formulario));
$this->Text(394, 16, utf8_decode(date('d-m-Y',strtotime($tfecha_edicion))));*/

$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C1', $nombre_formulario/*'REPORTE MENSUAL:VERIFICACIÓN MEDIDORES Y ACOMETIDAS'*/);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('N1', 'Código: FO-MED-14');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('N3', 'Edición No.: '. $edicion_formulario/*'04'*/);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('N5', 'Fecha de emisión: '. date('d-m-Y',strtotime($fecha_edicion))/*'10-07-2018'*/);
$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(28.57);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A8', 'PERIODO:');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A9', 'GERENCIA COMERCIAL');
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C8', $periodo);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C9', "ACEA DOMINICANA (ESTE-NORTE)");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H8', "CLIENTE:");
if($proyecto=='SD'){
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('I8', "CORPORACIÓN DE ACUEDUCTO Y ALCANTARILLADO DE SANTO DOMINGO");
}

if($proyecto=='BC'){
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('I8', "CORPORACIÓN DE ACUEDUCTO Y ALCANTARILLADO DE BOCA CHICA");
}

$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A11', "1. PLANIFICACION, VERIFICACION COMERCIAL Y PROCESAMIENTO EN EL SISTEMA DE GESTION COMERCIAL DE LAS ACTIVIDADES DE INSTALACION DE MEDIDORES Y MANTENIMIENTO, REPARACION Y RENOVACION DE MEDIDORES EXISTENTES");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A14', "1.1 SITUACION MEDIDORES RESULTANTES EN CAMPO");




$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C1:M6');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('N1:Q2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('N3:Q4');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('N5:Q6');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A8:B8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A9:B9');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C8:F8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C9:F9');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I8:Q8');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A11:O12');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A14:F14');


$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:B6')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C1:M6')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('N1:Q2')->applyFromArray($estiloTitulos2);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('N3:Q4')->applyFromArray($estiloTitulos2);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('N5:Q6')->applyFromArray($estiloTitulos2);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C9:E9')->applyFromArray($estiloLineaBaja);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C8:E8')->applyFromArray($estiloLineaBaja);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('I8:O8')->applyFromArray($estiloLineaBaja);



$objPHPExcel->setActiveSheetIndex(0)->getStyle("C1:M6")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("N1:O2")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("N3:O4")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("N5:O6")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A8:B9")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("C8:H8")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("B16:O17")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A11:O14")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A11:O12")->getAlignment()->setWrapText(true);

/////////////////////// obtenemos los sectores que tuvieron lecturas

$a= new Lectura();
$data=$a->getSecObsLecByPerSec($periodo,$proyecto);
$listaSectores='';
$pri=true;
while(oci_fetch($data)){
    $sector=oci_result($data,'SECTOR');
    if($pri){
        $pri=false;
        $listaSectores=$sector;
    }else{
        $listaSectores.=','.$sector;
    }
};
$sectores =explode( ',', $listaSectores );

$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A18', "OBS");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A18')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B18', "ESTADO MEDIDOR");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B18')->getFont()->setBold(true);

$lastColumn = 'B';

for($x=0;$x<count($sectores);$x++){
    $lastColumn++;
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension($lastColumn)->setWidth(11.0);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue($lastColumn.'18','Sector '. $sectores[$x]);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.'18')->getFont()->setBold(true);

}
$lastColumn++;
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue($lastColumn.'18','Total');
$objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.'18')->getFont()->setBold(true);


$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B16:'.$lastColumn.'17');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B16:'.$lastColumn.'17')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B16:'.$lastColumn.'17')->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A18:'.$lastColumn.'18')->applyFromArray($estiloLineaDelgada);

$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B16', "Resumen Medidores Resultante Dañados Por Sector");


$b=new Lectura();
$data=$b->getObsLecByPerSec($periodo,$listaSectores,$proyecto);
$fila=18;
$copiaFila=$fila;
$totales=[];
while(oci_fetch($data)){
    $fila++;
        $observacion=oci_result($data,'OBSERVACION_ACTUAL')." ";
        $desc=oci_result($data,'DESCRIPCION')." ";
        $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila, $observacion);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila)->applyFromArray($estiloLineaLateral);
        $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B'.$fila, $desc);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.$fila)->applyFromArray($estiloLineaLateral);
    $lastColumn = 'B';
    $totalObs=0;
    for($x=0;$x<count($sectores);$x++){
        $lastColumn++;
        $cant=oci_result($data,$sectores[$x]);
        $totales[$x] = 0;
        $totales[$x]+=$cant;
        $totalObs+=$cant;
        $objPHPExcel->setActiveSheetIndex(0)->SetCellValue($lastColumn.$fila, $cant+0);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.$fila)->applyFromArray($estiloLineaLateral);
    }
    $lastColumn++;
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue($lastColumn.$fila, $totalObs+0);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.$fila)->getFont()->setBold(true);
};

$lastColumn = 'B';
$fila++;
$filaSumaIn=$copiaFila+1;
$filaSumaFn=oci_num_rows($data)+$copiaFila;
$totalSect=0;
for($x=0;$x<count($sectores)+1;$x++){

    $lastColumn++;
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue($lastColumn.$fila, '=SUM('.$lastColumn.$filaSumaIn.':'.$lastColumn.$filaSumaFn.')');
   $objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.$fila)->getFont()->setBold(true);
   $objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.$fila)->applyFromArray($estiloLineaLateral);
}


$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':B'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila,'Total');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':'.$lastColumn.$fila)->applyFromArray($estiloLineaDelgada);

$fila+=2;
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila,'1.2 SITUACION DE MEDIDORES RESULTANTES EN CAMPO POR DIAMETRO');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila)->getFont()->setBold(true);


//////////////////////////////// tabla de observaciones por calibre

$a= new Lectura();
$data=$a->getCalByPerPer($periodo,$proyecto);
$listaCalibres='';
$pri=true;
while(oci_fetch($data)){
    $calbre=oci_result($data,'DESC_CALIBRE');
    if($pri){
        $pri=false;
        $listaCalibres="'".$calbre."'";
    }else{
        $listaCalibres.=",'".$calbre."'";
    }
};

$calibres =explode( ',', $listaCalibres );
$fila+=3;
$copiaFila=$fila;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':b'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila, "Estado medidor");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila)->getFont()->setBold(true);

$lastColumn = 'B';

for($x=0;$x<count($calibres);$x++){
    $lastColumn++;
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue($lastColumn.$fila,trim($calibres[$x],"'"));
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.$fila)->getFont()->setBold(true);
}
$lastColumn++;
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue($lastColumn.$fila,'Total');
$objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.$fila)->getFont()->setBold(true);


$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.($fila-1).':'.$lastColumn.($fila-1));

$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($fila-1).':'.$lastColumn.($fila-1))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.$lastColumn.($fila))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($fila-1).':'.$lastColumn.($fila-1))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($fila-1))->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C'.($fila-1), "Resumen Medidores Resultante Dañados Por Sector");


//////////////////////////////CUERPO DE LA TABLA


$b=new Lectura();
$data=$b->getObsLecByPerCal($periodo,$listaCalibres,$proyecto);
$totales=[];
while(oci_fetch($data)){
    $fila++;
    $desc=oci_result($data,'DESCRIPCION')." ";
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila, $desc);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila)->applyFromArray($estiloLineaLateral);
    $lastColumn = 'B';
    $totalObs=0;
    for($x=0;$x<count($calibres);$x++){
        $lastColumn++;
        $cant=oci_result($data,$calibres[$x]);
        $totales[$x] = 0;
        $totales[$x]+=$cant;
        $totalObs+=$cant;
        $objPHPExcel->setActiveSheetIndex(0)->SetCellValue($lastColumn.$fila, $cant+0);
        $objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.$fila)->applyFromArray($estiloLineaLateral);
    }
    $lastColumn++;
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue($lastColumn.$fila, $totalObs+0);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.$fila)->getFont()->setBold(true);





};

$lastColumn = 'B';
$fila++;
$filaSumaIn=$copiaFila+1;
$filaSumaFn=oci_num_rows($data)+$copiaFila;
$totalCal=0;
for($x=0;$x<count($calibres)+1;$x++){

    $lastColumn++;
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue($lastColumn.$fila, '=SUM('.$lastColumn.$filaSumaIn.':'.$lastColumn.$filaSumaFn.')');
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle($lastColumn.$fila)->applyFromArray($estiloLineaLateral);
}


$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':B'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila, "Total");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.$lastColumn.($fila))->applyFromArray($estiloLineaDelgada);


$fila+=4;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':H'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila, "2. RESUMEN ACTIVIDAD DE MANTENIMIENTO PRESENTADA");

$fila+=2;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.$fila.':D'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C'.$fila, "2. Actividades");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.$fila)->getAlignment()->setWrapText(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($fila).':'.'D'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($fila).':'.'D'.($fila))->applyFromArray($estiloLineaDelgada);
$fila++;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':B'.($fila+1));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.$fila.':C'.($fila+1));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$fila.':D'.($fila+1));
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'B'.($fila+1))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'B'.($fila+1))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('D'.($fila).':'.'D'.($fila+1))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('D'.($fila).':'.'D'.($fila+1))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($fila).':'.'C'.($fila+1))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($fila).':'.'C'.($fila+1))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':D'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila, "Estado medidor");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C'.$fila, "Mant. Correctivo");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D'.$fila, "Mant. Preventivo");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':D'.$fila)->getAlignment()->setWrapText(true);

$c=new Medidor();
$datos=$c->getCantMantByPerPro($periodo,$proyecto);
$fila+=1;
$canttotal=0;
while(oci_fetch($datos)){
    $fila+=1;
    $canttotal+=$cantidad=oci_result($datos,"CANTIDAD");
    $gerencia=oci_result($datos,"DESC_GERENCIA");
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':B'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila, $gerencia);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('D'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D'.$fila, $cantidad);
}

$c=new Medidor();
$datos=$c->getCantMantCorZonByPerPro($periodo,$proyecto);
$canttotalcorr=0;
while(oci_fetch($datos)){
    $fila+=1;
    $canttotalcorr+=$cantidad=oci_result($datos,"CANTIDAD");
    $gerencia=oci_result($datos,"DESC_GERENCIA");
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':B'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila, $gerencia);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C'.$fila, $cantidad);
}

$fila+=1;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':B'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':B'.$fila)->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila, "Total");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.$fila)->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C'.$fila, $canttotalcorr);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('D'.$fila)->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D'.$fila, $canttotal);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'D'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':D'.$fila)->getFont()->setBold(true);

$fila+=4;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':O'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':O'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila, "3. SOLICITUD DE  INSTALACION DE NUEVAS ACOMETIDAS DE ACUEDUCTO Y ALCANTARILLADO");

$fila+=2;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':O'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'O'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'O'.($fila))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':O'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila, "Informe Solicitud, Pago y Ejecución de los trabajos de Nueva Acometidas");
$fila+=1;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$fila.':C'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.$fila.':L'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':A'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.($fila+1).':B'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.($fila+1).':C'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.$fila.':D'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.$fila.':E'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.$fila.':G'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.($fila+1).':H'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I'.($fila+1).':I'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J'.($fila+1).':J'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('K'.($fila+1).':K'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('L'.($fila+1).':L'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('M'.($fila+1).':M'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('N'.$fila.':N'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('O'.$fila.':O'.($fila+2));

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'O'.($fila+2))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'O'.($fila+2))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':O'.($fila+2))->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':O'.($fila+2))->getAlignment()->setWrapText(true);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila, "Item");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B'.$fila, "Estado");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D'.$fila, "Cantidad");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('E'.$fila, "Cod. Sistema");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F'.$fila, "Proyecto");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H'.$fila, "Fecha");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('M'.$fila, "Resultados");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('N'.$fila, "Monto Pagado RD$");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('O'.$fila, "Total Cobrado RD$");

$fila+=1;
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B'.$fila, "Pendiente por:");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C'.$fila, "Solicitud");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H'.$fila, "Solicitud del Cliente");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('I'.$fila, "Envio");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('J'.$fila, "Aprobación Presupuesto");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('K'.$fila, "Pago Presupuesto");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('L'.$fila, "Solicitud Ejecucion");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('M'.$fila, "Ejecución de los Trabajos");

$fila+=2;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':A'.($fila+7));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($fila+8).':A'.($fila+10));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($fila+11).':A'.($fila+16));

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.$fila.':C'.($fila+7));
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'A'.($fila+17))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($fila).':'.'C'.($fila+17))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('D'.($fila).':'.'D'.($fila+17))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($fila).':'.'E'.($fila+16))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('H'.($fila).':'.'H'.($fila+16))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('J'.($fila).':'.'J'.($fila+16))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('L'.($fila).':'.'L'.($fila+16))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('N'.($fila).':'.'N'.($fila+16))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('O'.($fila).':'.'O'.($fila+16))->applyFromArray($estiloLineaLateral);

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila+7).':'.'A'.($fila+7))->applyFromArray($estiloLineaBaja);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($fila+7).':'.'O'.($fila+7))->applyFromArray($estiloLineaBaja);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila+10).':'.'O'.($fila+10))->applyFromArray($estiloLineaBaja);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila+16).':'.'O'.($fila+16))->applyFromArray($estiloLineaBaja);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila+17).':'.'D'.($fila+17))->applyFromArray($estiloLineaBaja);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila+17).':'.'D'.($fila+17))->getFont()->setBold(true);


$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$fila, "A");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.($fila+8), "B");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.($fila+11), "C");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.($fila+17), "D");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B'.($fila+17), "A+B+C");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C'.($fila+17), "Total Solicitudes");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($fila+17))->getAlignment()->setWrapText(true);

$fila+=19;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':O'.($fila));
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.($fila), "3.1 SOLICITUDES ACOMETIDAS PENDIENTE APROBAR");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$fila.':O'.($fila))->getFont()->setBold(true);

$fila+=2;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':A'.($fila+3));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$fila.':C'.($fila));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.($fila+1).':B'.($fila+3));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.($fila+1).':C'.($fila+3));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D'.($fila+1).':D'.($fila+3));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.($fila+1).':E'.($fila+3));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F'.($fila+1).':G'.($fila+3));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.($fila+1).':L'.($fila+1));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.($fila+2).':H'.($fila+3));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I'.($fila+2).':I'.($fila+3));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('J'.($fila+2).':J'.($fila+3));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('K'.($fila+2).':K'.($fila+3));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('L'.($fila+2).':L'.($fila+3));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('M'.($fila+2).':M'.($fila+3));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('N'.($fila+1).':N'.($fila+3));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('O'.($fila+1).':O'.($fila+3));


$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila+1).':'.'O'.($fila+3))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'A'.($fila+3))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('B'.($fila).':'.'C'.($fila))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'O'.($fila+3))->getFont()->setBold(true);

$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.($fila), "Item");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B'.($fila), "Estado");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('B'.($fila+1), "Pendiente por:");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C'.($fila+1), "Solicitud");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('D'.($fila+1), "Cantidad");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('E'.($fila+1), "Cod. Sistema");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('F'.($fila+1), "Proyecto");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H'.($fila+1), "Fecha");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('H'.($fila+2), "Solicitud del Cliente");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('I'.($fila+2), "Envio");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('J'.($fila+2), "Aprobación Presupuesto");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('K'.($fila+2), "Pago Presupuesto");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('L'.($fila+2), "Solicitud Ejecución");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('M'.($fila+1), "Resultados");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('M'.($fila+2), "Ejecución de los trabajos");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('N'.($fila+1), "Monto pagado RD$");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('O'.($fila+1), "Total Cobrado RD$");
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'O'.($fila+9))->getAlignment()->setWrapText(true);
$fila+=4;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.($fila).':A'.($fila+2));
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.($fila), "E");

$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'A'.($fila+3))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('C'.($fila).':'.'C'.($fila+3))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('D'.($fila).':'.'D'.($fila+3))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila+3).':'.'D'.($fila+3))->applyFromArray($estiloLineaBaja);

$objPHPExcel->setActiveSheetIndex(0)->getStyle('E'.($fila).':'.'E'.($fila+2))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('H'.($fila).':'.'H'.($fila+2))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('J'.($fila).':'.'J'.($fila+2))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('L'.($fila).':'.'L'.($fila+2))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('N'.($fila).':'.'N'.($fila+2))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('O'.($fila).':'.'O'.($fila+2))->applyFromArray($estiloLineaLateral);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila+2).':'.'O'.($fila+2))->applyFromArray($estiloLineaBaja);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('C'.($fila+3), "Total solicitudes");

$fila+=5;

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':E'.($fila));
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'E'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G'.$fila.':K'.($fila));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('L'.$fila.':O'.($fila));
$objPHPExcel->setActiveSheetIndex(0)->getStyle('G'.($fila).':'.'O'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.($fila), "OBSERVACIONES");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('G'.($fila), "CAUSAS DE DISMINUCIÓN/INCREMENTO");
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('L'.($fila), "ACCIONES A TOMAR");
$fila+=1;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$fila.':E'.($fila+17));
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.($fila).':'.'E'.($fila+17))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('G'.$fila.':K'.($fila+17));

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('L'.$fila.':O'.($fila+17));

$objPHPExcel->setActiveSheetIndex(0)->getStyle('G'.($fila).':'.'O'.($fila+17))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(0)->setTitle('RESUMEN');

//////////////////// INICIA HOJA 2


$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'MEDIDORES DAÑADOS');

$objPHPExcel->addSheet($myWorkSheet, 1);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('A1:T99')->applyFromArray(

    array(

        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => 'FFFFFF')
            )
        )
    )

);




$a= new Lectura();
$data=$a->getObsLecByPerPro($periodo,$proyecto);
$listaObs='';
$pri=true;
while(oci_fetch($data)){
    $obs=oci_result($data,'DESCRIPCION');
    if($pri){
        $pri=false;
        $listaObs="'".$obs."'";
    }else{
        $listaObs.=",'".$obs."'";
    }
};

$observaciones =explode( ',', $listaObs );
$fila=7;

$objPHPExcel->setActiveSheetIndex(1)->mergeCells('C7:D7');
$objPHPExcel->setActiveSheetIndex(1)->getStyle('C7:D7')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('C7:D7')->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('C7','Ø MEDIDOR');
$objPHPExcel->setActiveSheetIndex(1)->getStyle('C7')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('C7:D7')->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;


$lastColumn = 'D';

for($x=0;$x<count($observaciones);$x++){
    $lastColumn++;
    $objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->applyFromArray($estiloTitulos);
    $objPHPExcel->setActiveSheetIndex(1)->getColumnDimension($lastColumn)->setWidth(20);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->applyFromArray($estiloLineaDelgada);
    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue($lastColumn.$fila,trim($observaciones[$x],"'"));
    $objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '4080BF')
            )
        )
    );;
}
$lastColumn++;
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue($lastColumn.$fila,'Total');
$objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;


$objPHPExcel->setActiveSheetIndex(1)->mergeCells('C5:'.$lastColumn.'6');
$objPHPExcel->setActiveSheetIndex(1)->getStyle('C5:'.$lastColumn.'6')->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('C5:'.$lastColumn.'6')->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('C5:'.$lastColumn.'6')->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('C5', "Tabla Resumen Medidores Resultante Dañado $periodo");





$b=new Lectura();
$data=$b->getCalByPerObsLec($periodo,$listaObs,$proyecto);
$fila=7;
$copiaFila=$fila;
$totales=[];
$cant=0;
$contador_prueba = 0;
$fila1 = $fila;
while(oci_fetch($data)){
    $fila++;
    $calibre=oci_result($data,'DESC_CALIBRE')." ";
    $objPHPExcel->setActiveSheetIndex(1)->mergeCells('C'.$fila.':D'.$fila);
    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('C'.$fila, $calibre);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle('C'.$fila.':D'.$fila)->applyFromArray($estiloLineaLateral);
    $lastColumn = 'D';
    $totalCal=0;
    $totalCant=0;


    for($x=0/*,$x2=1*/;$x<count($observaciones);$x++/*,$x2++*/){
        $lastColumn++;
        $cant=oci_result($data,$observaciones[$x]);
        $totales[$x] = 0;
        $totales[$x]+=$cant;
        $totalCal+=$cant;
        $objPHPExcel->setActiveSheetIndex(1)->SetCellValue($lastColumn.$fila, $cant+0);
        $objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->applyFromArray($estiloLineaLateral);
        $sumatoria_columnas = 0;
 //     echo  print_r(oci_num_rows($data));

    }


    $lastColumn++;

    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue($lastColumn.$fila, $totalCal+0);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->getFont()->setBold(true);
}



$lastColumn = 'D';
$fila++;
$filaSumaIn=$copiaFila+1;
$filaSumaFn=oci_num_rows($data)+$copiaFila;
$totalObs=0;
for($x=0;$x<count($observaciones)+1;$x++){

    $lastColumn++;
    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue($lastColumn.$fila, '=SUM('.$lastColumn.$filaSumaIn.':'.$lastColumn.$filaSumaFn.')');
    $objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(1)->getStyle($lastColumn.$fila)->applyFromArray($estiloLineaLateral);
}


$objPHPExcel->setActiveSheetIndex(1)->mergeCells('C'.$fila.':D'.$fila);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('C'.$fila, "Total");
$objPHPExcel->setActiveSheetIndex(1)->getStyle('C'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('C'.($fila).':'.$lastColumn.($fila))->applyFromArray($estiloLineaDelgada);


$fila+=3;
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('E'.($fila).':F'.($fila));
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('G'.($fila).':H'.($fila));
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('I'.($fila).':J'.($fila));

$objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('A'.$fila,trim("No"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('B'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('B'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('B'.$fila,trim("CODIGO"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('B'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('B'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('C'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('C'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('C'.$fila,trim("SECTOR"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('C'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('C'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('D'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('D'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('D'.$fila,trim("RUTA"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('D'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('D'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('E'.($fila).':F'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('E'.($fila).':F'.($fila))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('E'.$fila,trim("CATASTRO"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('E'.($fila).':F'.($fila))->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('E'.($fila).':F'.($fila))->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('G'.($fila).':H'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('G'.($fila).':H'.($fila))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('G'.$fila,trim("CLIENTE"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('G'.($fila).':H'.($fila))->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('G'.($fila).':H'.($fila))->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('I'.($fila).':J'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('I'.($fila).':J'.($fila))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('I'.$fila,trim("DIRECCION"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('I'.($fila).':J'.($fila))->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('I'.($fila).':J'.($fila))->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('K'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('K'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('K'.$fila,trim("URB"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('K'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('K'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('L'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('L'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('L'.$fila,trim("ESTADO"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('L'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('L'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('M'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('M'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('M'.$fila,trim("USO"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('M'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('M'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('N'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('N'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('N'.$fila,trim("FEC INS"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('N'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('N'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('O'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('O'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('O'.$fila,trim("SERIAL"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('O'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('O'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('P'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('P'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('P'.$fila,trim("LECTURA"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('P'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('P'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('Q'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('Q'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('Q'.$fila,trim("CALIBRE"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('Q'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('Q'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('R'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('R'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('R'.$fila,trim("GERENCIA"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('R'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('R'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(1)->getStyle('S'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('S'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('S'.$fila,trim("OBSERLECT"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('S'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('S'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;
$objPHPExcel->setActiveSheetIndex(1)->getStyle('T'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('T'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(1)->SetCellValue('T'.$fila,trim("FACT. PEND"));
$objPHPExcel->setActiveSheetIndex(1)->getStyle('T'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(1)->getStyle('T'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$a=new Medidor();
$dat=$a->getDatMed($periodo,$proyecto);
$num=0;
$fila_ini=$fila+1;
while(oci_fetch($dat)){

    $fila++;
    $num++;

    $objPHPExcel->setActiveSheetIndex(1)->mergeCells('E'.($fila).':F'.($fila));
    $objPHPExcel->setActiveSheetIndex(1)->mergeCells('G'.($fila).':H'.($fila));
    $objPHPExcel->setActiveSheetIndex(1)->mergeCells('I'.($fila).':J'.($fila));


    $codigo=oci_result($dat,"CODIGO");
    $sector=oci_result($dat,"SECTOR");
    $ruta=oci_result($dat,"RUTA");
    $catastro=oci_result($dat,"CATASTRO");
    $cliente=oci_result($dat,"NOMBRE");
    $direccion=oci_result($dat,"DIRECCION");
    $urbanizacion=oci_result($dat,"URBANIZACION");
    $estado=oci_result($dat,"ESTADO");
    $uso=oci_result($dat,"USO");
    $fecIns=oci_result($dat,"FECHA_INSTALACION");
    $serial=oci_result($dat,"SERIAL");
    $lectura=oci_result($dat,"LECTURA");
    $calibre=oci_result($dat,"CALIBRE");
    $gerencia=oci_result($dat,"GERENCIA");
    $obsLec=oci_result($dat,"OBSERVACION");
    $facPend=oci_result($dat,"FACTURA");

    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('A'.$fila, $num);
   // $objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$fila)->applyFromArray($estiloLineaLateral);

    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('B'.$fila, $codigo);
    //$objPHPExcel->setActiveSheetIndex(1)->getStyle('B'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('C'.$fila, $sector);
   // $objPHPExcel->setActiveSheetIndex(1)->getStyle('C'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('D'.$fila, $ruta);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('D'.$fila)->applyFromArray($estiloLineaLateral);


    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('E'.$fila, $catastro);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('E'.$fila)->applyFromArray($estiloLineaLateral);

    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('G'.$fila, $cliente);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('G'.$fila)->applyFromArray($estiloLineaLateral);

    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('I'.$fila, $direccion);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('I'.$fila)->applyFromArray($estiloLineaLateral);


    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('K'.$fila, $urbanizacion);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('K'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('L'.$fila, $estado);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('L'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('M'.$fila, $uso);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('M'.$fila)->applyFromArray($estiloLineaLateral);

    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('N'.$fila, $fecIns);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('N'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('O'.$fila, $serial);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('O'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('P'.$fila, $lectura);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('P'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('Q'.$fila, $calibre);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('Q'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('R'.$fila, $gerencia);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('R'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('S'.$fila, $obsLec);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('S'.$fila)->applyFromArray($estiloLineaLateral);
    $objPHPExcel->setActiveSheetIndex(1)->SetCellValue('T'.$fila, $facPend);
//    $objPHPExcel->setActiveSheetIndex(1)->getStyle('T'.$fila)->applyFromArray($estiloLineaLateral);

}

    $objPHPExcel->setActiveSheetIndex(1)->getStyle('A'.$fila_ini.':T'.$fila)->applyFromArray($estiloLineaLateral);



///////////////////////////HOJA DE MANTENIMIENTOS CORRECTIVOS/////////////////////////////////

$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'MANTENIMIENTO CORRECTIVO');

$objPHPExcel->addSheet($myWorkSheet, 2);
//$objPHPExcel->setActiveSheetIndex(2)->getStyle('A1:T99')->applyFromArray(
//
//    array(
//
//        'borders' => array(
//            'allborders' => array(
//                'style' => PHPExcel_Style_Border::BORDER_THIN,
//                'color' => array('rgb' => 'FFFFFF')
//            )
//        )
//    )
//
//);



$fila=2;
$objPHPExcel->setActiveSheetIndex(2)->getStyle('A'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('A'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('A'.$fila,trim("No"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('A'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('A'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(2)->getStyle('B'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('B'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('B'.$fila,trim("CODIGO"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('B'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('B'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(2)->getStyle('C'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('C'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('C'.$fila,trim("SECTOR"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('C'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('C'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(2)->getStyle('D'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('D'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('D'.$fila,trim("RUTA"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('D'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('D'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(2)->getStyle('E'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('E'.($fila))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('E'.$fila,trim("NOMBRE"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('E'.($fila))->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('E'.($fila))->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(2)->getStyle('F'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('F'.($fila))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('F'.$fila,trim("DIRECCION"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('F'.($fila))->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('F'.($fila))->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(2)->getStyle('G'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('G'.($fila))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('G'.$fila,trim("SERIAL"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('G'.($fila))->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('G'.($fila))->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(2)->getStyle('H'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('H'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('H'.$fila,trim("MEDIDOR"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('H'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('H'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(2)->getStyle('I'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('I'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('I'.$fila,trim("OBS LECTURA"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('I'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('I'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(2)->getStyle('J'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('J'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('J'.$fila,trim("LECTURA MANTENIMIENTO"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('J'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('J'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(2)->getStyle('K'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('K'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('K'.$fila,trim("ULTIMA LECTURA"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('K'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('K'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(2)->getStyle('L'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('L'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('L'.$fila,trim("FECHA REALIZACION"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('L'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('L'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(2)->getStyle('M'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('M'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('M'.$fila,trim("CALIBRE"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('M'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('M'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(2)->getStyle('N'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('N'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(2)->SetCellValue('N'.$fila,trim("OBSERVACIONES MANTENIMIENTO"));
$objPHPExcel->setActiveSheetIndex(2)->getStyle('N'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(2)->getStyle('N'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;



$a=new Medidor();
$dat=$a->getMantCorrByProPer($periodo,$proyecto);
$num=0;
$fila_ini=$fila+1;
while(oci_fetch($dat)){

    $fila++;
    $num++;


    $numero=oci_result($dat,"ROWNUM");
    $codigo=oci_result($dat,"COD_INMUEBLE");
    $sector=oci_result($dat,"ID_SECTOR");
    $ruta=oci_result($dat,"ID_RUTA");
    $nombre=oci_result($dat,"ALIAS");
    $direccion=oci_result($dat,"DIRECCION");
    $serial=oci_result($dat,"SERIAL");
    $medidor=oci_result($dat,"DESC_MED");
    $obs_lectura=oci_result($dat,"OBSERVACION");
    $lectura_man=oci_result($dat,"LECTURA");
    $ultima_lectura=oci_result($dat,"LECTURA_ORIGINAL");
    $fecha_rea=oci_result($dat,"FECHA_REEALIZACION");
    $calibre=oci_result($dat,"DESC_CALIBRE");
    $obs_mant=oci_result($dat,"OBS");


    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('A'.$fila, $num);
    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('B'.$fila, $codigo);
    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('C'.$fila, $sector);
    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('D'.$fila, $ruta);
    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('E'.$fila, $nombre);
    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('F'.$fila, $direccion);
    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('G'.$fila, $serial);
    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('H'.$fila, $medidor);
    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('I'.$fila, $obs_lectura);
    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('J'.$fila, $lectura_man);
    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('K'.$fila, $ultima_lectura);
    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('L'.$fila, $fecha_rea);
    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('M'.$fila, $calibre);
    $objPHPExcel->setActiveSheetIndex(2)->SetCellValue('N'.$fila, $obs_mant);




}

$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("A")->setWidth(3.29);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("B")->setWidth(7.57);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("C")->setWidth(7);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("D")->setWidth(5.14);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("E")->setWidth(46.86);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("F")->setWidth(29.29);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("G")->setWidth(11.29);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("H")->setWidth(10.71);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("I")->setWidth(12.14);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("J")->setWidth(24.71);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("K")->setWidth(15.71);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("L")->setWidth(18.29);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("M")->setWidth(7.43);
$objPHPExcel->setActiveSheetIndex(2)->getColumnDimension("N")->setWidth(39.29);


///////////////////////////HOJA DE MANTENIMIENTOS PREVENTIVOS/////////////////////////////////

$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'MANTENIMIENTO PREVENTIVO');

$objPHPExcel->addSheet($myWorkSheet, 3);
//$objPHPExcel->setActiveSheetIndex(2)->getStyle('A1:T99')->applyFromArray(
//
//    array(
//
//        'borders' => array(
//            'allborders' => array(
//                'style' => PHPExcel_Style_Border::BORDER_THIN,
//                'color' => array('rgb' => 'FFFFFF')
//            )
//        )
//    )
//
//);



$fila=2;
$objPHPExcel->setActiveSheetIndex(3)->getStyle('A'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('A'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('A'.$fila,trim("No"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('A'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('A'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(3)->getStyle('B'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('B'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('B'.$fila,trim("CODIGO"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('B'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('B'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(3)->getStyle('C'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('C'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('C'.$fila,trim("SECTOR"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('C'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('C'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(3)->getStyle('D'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('D'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('D'.$fila,trim("RUTA"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('D'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('D'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(3)->getStyle('E'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('E'.($fila))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('E'.$fila,trim("NOMBRE"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('E'.($fila))->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('E'.($fila))->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(3)->getStyle('F'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('F'.($fila))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('F'.$fila,trim("DIRECCION"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('F'.($fila))->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('F'.($fila))->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(3)->getStyle('G'.($fila))->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('G'.($fila))->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('G'.$fila,trim("SERIAL"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('G'.($fila))->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('G'.($fila))->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(3)->getStyle('H'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('H'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('H'.$fila,trim("MEDIDOR"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('H'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('H'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(3)->getStyle('I'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('I'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('I'.$fila,trim("OBS LECTURA"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('I'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('I'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(3)->getStyle('J'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('J'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('J'.$fila,trim("LECTURA MANTENIMIENTO"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('J'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('J'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(3)->getStyle('K'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('K'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('K'.$fila,trim("ULTIMA LECTURA"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('K'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('K'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(3)->getStyle('L'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('L'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('L'.$fila,trim("FECHA REALIZACION"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('L'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('L'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(3)->getStyle('M'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('M'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('M'.$fila,trim("CALIBRE"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('M'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('M'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;

$objPHPExcel->setActiveSheetIndex(3)->getStyle('N'.$fila)->applyFromArray($estiloTitulos);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('N'.$fila)->applyFromArray($estiloLineaDelgada);
$objPHPExcel->setActiveSheetIndex(3)->SetCellValue('N'.$fila,trim("OBSERVACIONES MANTENIMIENTO"));
$objPHPExcel->setActiveSheetIndex(3)->getStyle('N'.$fila)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(3)->getStyle('N'.$fila)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4080BF')
        )
    )
);;



$a=new Medidor();
$dat=$a->getMantPreByProPer($periodo,$proyecto);
$num=0;
$fila_ini=$fila+1;
while(oci_fetch($dat)){

    $fila++;
    $num++;


    $numero=oci_result($dat,"ROWNUM");
    $codigo=oci_result($dat,"COD_INMUEBLE");
    $sector=oci_result($dat,"ID_SECTOR");
    $ruta=oci_result($dat,"ID_RUTA");
    $nombre=oci_result($dat,"ALIAS");
    $direccion=oci_result($dat,"DIRECCION");
    $serial=oci_result($dat,"SERIAL");
    $medidor=oci_result($dat,"DESC_MED");
    $obs_lectura=oci_result($dat,"OBSERVACION");
    $lectura_man=oci_result($dat,"LECTURA");
    $ultima_lectura=oci_result($dat,"LECTURA_ORIGINAL");
    $fecha_rea=oci_result($dat,"FECHA_REEALIZACION");
    $calibre=oci_result($dat,"DESC_CALIBRE");
    $obs_mant=oci_result($dat,"OBS");


    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('A'.$fila, $num);
    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('B'.$fila, $codigo);
    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('C'.$fila, $sector);
    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('D'.$fila, $ruta);
    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('E'.$fila, $nombre);
    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('F'.$fila, $direccion);
    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('G'.$fila, $serial);
    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('H'.$fila, $medidor);
    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('I'.$fila, $obs_lectura);
    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('J'.$fila, $lectura_man);
    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('K'.$fila, $ultima_lectura);
    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('L'.$fila, date("d/m/Y",strtotime($fecha_rea)));
    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('M'.$fila, $calibre);
    $objPHPExcel->setActiveSheetIndex(3)->SetCellValue('N'.$fila, $obs_mant);




}

$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("A")->setWidth(3.29);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("B")->setWidth(7.57);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("C")->setWidth(7);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("D")->setWidth(5.14);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("E")->setWidth(46.86);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("F")->setWidth(29.29);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("G")->setWidth(11.29);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("H")->setWidth(10.71);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("I")->setWidth(12.14);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("J")->setWidth(24.71);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("K")->setWidth(15.71);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("L")->setWidth(18.29);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("M")->setWidth(7.43);
$objPHPExcel->setActiveSheetIndex(3)->getColumnDimension("N")->setWidth(39.29);



$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/ResumenGeneralMedidores".$proyecto.'-'.time().".xlsx";

$objWriter->save($nomarch);
echo $nomarch;
