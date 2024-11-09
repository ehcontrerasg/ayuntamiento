<?php
//IMPORTACION DE ARCHIVOS Y LIBRERIAS
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.SeguimientoInmuebles.php';

//DECLARACIÓN DE VARIABLES
$periodo                 = $_POST["periodo"];
$meses                   = [
            "01"=>"Ene.",
            "02"=>"Feb.",
            "03"=>"Mar.",
            "04"=>"Abr.",
            "05"=>"My.",
            "06"=>"Jun.",
            "07"=>"Jul.",
            "08"=>"Agt.",
            "09"=>"Sept.",
            "10"=>"Oct.",
            "11"=>"Nov.",
            "12"=>"Dic.",
         ];
$usuarios_activos        = 0;
$usuarios_catastrados    = 0;
$conexiones_con_medidor  = 0;
$medidores_leidos        = 0;
//$facturas_emitidas       = 0;
$valor_recaudado         = 0;
$valor_facturado         = 0;
$cuentas_pagadas         = 0;
//$cuentas_emitidas        = 0;
$cuentas_reclamadas      = 0;

//INSTANCIA DE LIBRERÍA
$objPHPExcel      = new PHPExcel();

$objPHPExcel
    ->getProperties()
    ->setCreator("AceaSoft")
    ->setLastModifiedBy("AceaSoft")
    ->setTitle("Seguimiento a inmuebles")
    ->setSubject("")
    ->setDescription("Documento generado con PHPExcel")
    ->setKeywords("usuarios phpexcel")
    ->setCategory("Reportes Gerenciales");

//ESTILOS
$estilos_titulos = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'ffffff'),
        'size'  => 10,
        'name'  => 'Arial'
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => '1f4e78')
    )

);
$bordes = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);
$bordes_inferiores = array(
    'borders' => array(
        'bottom' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);
$bordes_punteados = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_DOTTED
        )
    )
);
$formato_valores = array(
    'alignment' => array(
                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
));

$objPHPExcel->getActiveSheet()->getStyle('A2:B24')->applyFromArray($bordes_punteados);
$objPHPExcel->getActiveSheet()->getStyle('A2:B24')->applyFromArray($bordes);
$objPHPExcel->getActiveSheet()->getStyle('B2:B24')->applyFromArray($formato_valores);

for($indice=1;$indice<=24;$indice++){

    if($indice==4||$indice==11||$indice==14||$indice==21){
        $objPHPExcel->getActiveSheet()->getStyle('A'.$indice.":"."B".$indice)->applyFromArray( $bordes_inferiores);
    }
    if($indice==1||$indice==2||$indice==5||$indice==12||$indice==15||$indice==22){
        $objPHPExcel->getActiveSheet()->getStyle("A".$indice.":"."B".$indice)->getFont()->setBold(true);
    }
    if($indice==6||$indice==9||$indice==16||$indice==19||$indice==22){
        $objPHPExcel->getActiveSheet()->getStyle("B".$indice)->getFont()->setBold(true);
    }
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(47.14);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13.86);
$objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('A1:B1');

//CUERPO DEL ARCHIVO

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet(0)
    ->setTitle('Seguimiento a inmuebles');

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1','Indicadores seguimiento 14946 usuarios incorporados '.$meses[substr($periodo,-2)].' '.substr($periodo,0,4));
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2' ,'1.Indicador Catastro de usuarios')
            ->setCellValue('A3' ,'Usuarios Activos')
            ->setCellValue('A4' ,'Usuarios Catastrados')
            ->setCellValue('A5' ,'2. Indicadores de medición de consumo')
            ->setCellValue('A6' ,'2.1 Cobertura de medición de consumo')
            ->setCellValue('A7' ,'Número de conexiones con medidor')
            ->setCellValue('A8' ,'Número de conexiones registradas')
            ->setCellValue('A9' ,'2.2 Efectvidad e lectura')
            ->setCellValue('A10','Medidores leídos')
            ->setCellValue('A11','Número de conexiones con medidor')
            ->setCellValue('A12','3. Indicador cobertura de facturación')
            ->setCellValue('A13','Número de facturas emitidas')
            ->setCellValue('A14','Número de usuarios registrados')
            ->setCellValue('A15','4. Indicadores de cobranza')
            ->setCellValue('A16','4.1 Eficiencia de cobranza')
            ->setCellValue('A17','Valor recaudado')
            ->setCellValue('A18','Valor facturado')
            ->setCellValue('A19','4.2 Efectividad de pago')
            ->setCellValue('A20','Número de cuentas pagadas')
            ->setCellValue('A21','Número de cuentas emitidas')
            ->setCellValue('A22','5. Cobertura de Atención al cliente')
            ->setCellValue('A23','Número de cuentas reclamadas')
            ->setCellValue('A24','Número de cuentas registradas');


 $seguimiento = new SeguimientoInmuebles();
 $datos       = $seguimiento->UsuariosActivos();
 while(oci_fetch($datos)){
     $usuarios_activos = oci_result($datos,"USUARIOS_ACTIVOS");
 }oci_free_statement($datos);

 $datos = $seguimiento->UsuariosCatastrados();
 while(oci_fetch($datos)){
     $usuarios_catastrados = oci_result($datos,"USUARIOS_CATASTRADOS");
 }

 $datos = $seguimiento->ConexionesConMedidor();
  while(oci_fetch($datos)){
      $conexiones_con_medidor = oci_result($datos,"CONEXIONES_CON_MEDIDOR");
  }

  $datos = $seguimiento->MedidoresLeidos($periodo);
  while(oci_fetch($datos)){
      $medidores_leidos = oci_result($datos,"MEDIDORES_LEIDOS");
  }

 /* $datos = $seguimiento->FacturasEmitidas($periodo);
  while(oci_fetch($datos)){
      $facturas_emitidas = oci_result($datos,"FACTURAS_EMITIDAS");
  }*/

  $datos = $seguimiento->ValorRecaudado($periodo);
  while(oci_fetch($datos)){
      $valor_recaudado = oci_result($datos,"RECAUDOS");
  }

  $datos = $seguimiento->ValorFacturado($periodo);
  while(oci_fetch($datos)){
      $valor_facturado = oci_result($datos,"VALOR_FACTURADO");
  }

  $datos = $seguimiento->CuentasPagadas($periodo);
  while(oci_fetch($datos)){
      $cuentas_pagadas = oci_result($datos,"NUMERO_CUENTAS_PAGADAS");
  }

 /* $datos = $seguimiento->CuentasEmitidas();
  while(oci_fetch($datos)){
      $cuentas_emitidas = oci_result($datos,"NUMERO_CUENTAS_EMITIDAS");
  }*/

  $datos = $seguimiento->CuentasReclamadas($periodo);
  while(oci_fetch($datos)){
      $cuentas_reclamadas = oci_result($datos,"NUMERO_CUENTAS_RECLAMADAS");
  }

//IMPRESIÓN DEL REPORTE
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B2'  , $seguimiento->Porcentaje($usuarios_activos,$usuarios_catastrados))
            ->setCellValue('B3'  , number_format($usuarios_activos))
            ->setCellValue('B4'  , number_format($usuarios_catastrados))
            ->setCellValue('B6'  , $seguimiento->Porcentaje($conexiones_con_medidor,$usuarios_catastrados))
            ->setCellValue('B7'  , number_format($conexiones_con_medidor))
            ->setCellValue('B8'  , number_format($usuarios_catastrados))
            ->setCellValue('B9'  , $seguimiento->Porcentaje($medidores_leidos,$conexiones_con_medidor))
            ->setCellValue('B10' , number_format($medidores_leidos))
            ->setCellValue('B11' , number_format($conexiones_con_medidor))
            ->setCellValue('B12' , $seguimiento->Porcentaje($usuarios_activos,$usuarios_catastrados))
            ->setCellValue('B13' , number_format($usuarios_activos))
            ->setCellValue('B14' , number_format($usuarios_catastrados))
            ->setCellValue('B16' , $seguimiento->Porcentaje($valor_recaudado,$valor_facturado))
            ->setCellValue('B17' , number_format($valor_recaudado))
            ->setCellValue('B18' , number_format($valor_facturado))
            ->setCellValue('B19' , $seguimiento->Porcentaje($cuentas_pagadas,$usuarios_activos))
            ->setCellValue('B20' , number_format($cuentas_pagadas))
            ->setCellValue('B21' , number_format($usuarios_activos))
            ->setCellValue('B22' , $seguimiento->Porcentaje($cuentas_reclamadas,$usuarios_catastrados))
            ->setCellValue('B23' , number_format($cuentas_reclamadas))
            ->setCellValue('B24' , number_format($usuarios_catastrados));

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/seguimiento_inmuebles_".$meses[substr($periodo,-2)].'_'.substr($periodo,0,4).".xlsx";
$objWriter->save($nomarch);
echo $nomarch;
