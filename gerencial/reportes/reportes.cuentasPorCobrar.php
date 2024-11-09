<?php

//IMPORTACION DE ARCHIVOS Y LIBRERIAS
include '../../recursos/PHPExcel.php';
include '../../recursos/PHPExcel/Writer/Excel2007.php';
include '../clases/class.cuentasPorCobrar.php';
//ini_set('memory_limit', '-1');

//DECLARACIONES DE VARIABLES

$proyecto = $_POST["proyecto"];

$dias                  = array([" AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) = 1 "    ," "],
                               [" AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) <= 30 "  ," AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) >= 2  "],
                               [" AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) >= 31 "  ," AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) <= 59 "],
                               [" AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) >= 60 "  ," AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) <= 89 "],
                               [" AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) >= 90 "  ," AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) <= 119 "],
                               [" AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) >= 120 " ," AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) <= 179 "],
                               [" AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) >= 180 " ," AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) >= 364 "],
                               [" AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) >= 365 " ," AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) <= 1094 "],
                               [" AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) >= 1095 "," AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) <= 1824 "],
                               [" AND ROUND(TO_NUMBER(SYSDATE-VENC.FECHA_CORTE),0) > 1825 " ," "]
                              );

$where                            = "";
$total_vencido_por_uso            = 0;
$total_por_periodo                = 0;
$total_vencido                    = 0;
$arreglo_columnas                 = ["B","C","D","E","F","G","H","I","J","K"];
$columna                          = 0;
$fila                             = 5;

$deuda_por_uso                      = [
                                        "C" => 0,
                                        "D" => 0,
                                        "I" => 0,
                                        "L" => 0,
                                        "O" => 0,
                                        "PC"=> 0,
                                        "M" => 0,
                                        "R" => 0,
                                        "S" => 0,
                                        "ZF"=> 0
                                    ];

$total_deudas_por_uso                = [
                                           "C" => 0,
                                           "D" => 0,
                                           "I" => 0,
                                           "L" => 0,
                                           "O" => 0,
                                           "PC"=> 0,
                                           "M" => 0,
                                           "R" => 0,
                                           "S" => 0,
                                           "ZF"=> 0
                                       ];

$total_cantidad_usuarios_por_uso       = [
                                            "C" => 0,
                                            "D" => 0,
                                            "I" => 0,
                                            "L" => 0,
                                            "O" => 0,
                                            "PC"=> 0,
                                            "M" => 0,
                                            "R" => 0,
                                            "S" => 0,
                                            "ZF"=> 0
                                        ];

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

$estilos_cuerpo = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'c9c9c9')
    )
);



//CUERPO DEL ARCHIVO
$objPHPExcel           = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet(0)
            ->setTitle('Cuentas por cobrar');

$objPHPExcel
    ->getProperties()
                    ->setCreator("AceaSoft")
                    ->setLastModifiedBy("AceaSoft")
                    ->setTitle("CuentasPorCobrar")
                    ->setSubject("")
                    ->setDescription("Documento generado con PHPExcel")
                    ->setKeywords("usuarios phpexcel")
                    ->setCategory("Reportes Gerenciales");

//Columnas de cuadro de deuda
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2','Clasificación de cuentas por cobrar')
            ->setCellValue('A3','DEUDA')
            ->setCellValue('A4','Uso')
            ->setCellValue('B4','A Vencer (1 día)')
            ->setCellValue('C4','<=30 días')
            ->setCellValue('D4','31 a 59 días')
            ->setCellValue('E4','60 a 89 días')
            ->setCellValue('F4','90 a 119 días')
            ->setCellValue('G4','120 a 179 días')
            ->setCellValue('H4','180 a 364 días')
            ->setCellValue('I4','1 a 3 años')
            ->setCellValue('J4','3 a 5 años')
            ->setCellValue('K4','>5 años')
            ->setCellValue('L4','Total Vencido');

//Columnas de cuadro de cantidad de usuarios
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A17','CANTIDAD DE USUARIOS')
            ->setCellValue('A18','Uso')
            ->setCellValue('B18','A Vencer (1 día)')
            ->setCellValue('C18','<=30 días')
            ->setCellValue('D18','31 a 59 días')
            ->setCellValue('E18','60 a 89 días')
            ->setCellValue('F18','90 a 119 días')
            ->setCellValue('G18','120 a 179 días')
            ->setCellValue('H18','180 a 364 días')
            ->setCellValue('I18','1 a 3 años')
            ->setCellValue('J18','3 a 5 años')
            ->setCellValue('K18','>5 años')
            ->setCellValue('L18','Total Vencido');

//Estilos de títulos
$objPHPExcel->getActiveSheet()
            ->getstyle("A4:L4")
            ->applyFromArray($estilos_titulos);

$objPHPExcel->getActiveSheet()
    ->getstyle("A15:L15")
    ->applyFromArray($estilos_titulos);

$objPHPExcel->getActiveSheet()
    ->getstyle("A18:L18")
    ->applyFromArray($estilos_titulos);

$objPHPExcel->getActiveSheet()
    ->getstyle("A29:L29")
    ->applyFromArray($estilos_titulos);

//Estilos para cuerpo
$objPHPExcel->getActiveSheet()
    ->getstyle("A5:L14")
    ->applyFromArray($estilos_cuerpo);

$objPHPExcel->getActiveSheet()
    ->getstyle("A19:L28")
    ->applyFromArray($estilos_cuerpo);


$cc1 = new CuentasPorCobrar();
    $datos_usos = $cc1->grupoUsos();

    while(oci_fetch($datos_usos)) {
        $id_uso   = oci_result($datos_usos, "ID_USO");
        $desc_uso = oci_result($datos_usos, "DESC_USO");
        $deuda    = 0;

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $desc_uso)
            ->setCellValue('A' . ($fila+14), $desc_uso);

        $fila++;
    }
     $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue("A".$fila,'Zona Franca')
                 ->setCellValue("A".($fila+14),'Zona Franca');

    //Lógica para cargar los datos del cuadro de deudas
  foreach ($dias as &$valor){
            $where = $valor[0] . $valor[1];
            $fila  = 5;

            $cc2 = new CuentasPorCobrar();
            $datos_cuenta_por_cobrar= $cc2->GetCuentasPorCobrar($where,$proyecto);

            while(oci_fetch($datos_cuenta_por_cobrar)){

                $deuda = oci_result($datos_cuenta_por_cobrar,"DEUDA");
                $id_uso = oci_result($datos_cuenta_por_cobrar,"ID_USO");

                $deuda_por_uso[$id_uso]        = $deuda;
                $total_deudas_por_uso[$id_uso] += $deuda;
                $total_por_periodo       += $deuda;
            }

            $cc2 = new CuentasPorCobrar();
            $datos_cuenta_por_cobrar= $cc2->GetCuentasPorCobrarZF($where,$proyecto);

            while(oci_fetch($datos_cuenta_por_cobrar)){

                $deuda = oci_result($datos_cuenta_por_cobrar,"DEUDA");
                $id_uso = oci_result($datos_cuenta_por_cobrar,"ID_USO");

                $deuda_por_uso[$id_uso]        = $deuda;
                $total_deudas_por_uso[$id_uso] += $deuda;
                $total_por_periodo       += $deuda;
            }

            foreach($deuda_por_uso as $id_uso => $valor_deuda){
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue($arreglo_columnas[$columna].$fila,$valor_deuda);
                $fila++;
            }

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($arreglo_columnas[$columna].$fila,$total_por_periodo);

            $total_por_periodo=0;
            $columna++;
        }unset($valor);

        $fila = 5;
        foreach($total_deudas_por_uso as $id_uso => $valor){
            $total_vencido += $valor;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("L".$fila,$valor);
            $fila++;
        }unset($uso);

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("L15",$total_vencido);

        $total_vencido     = 0;
        $total_por_periodo = 0;
        $columna           = 0;
        $deuda_por_uso     = [
                              "C" => 0,
                              "D" => 0,
                              "I" => 0,
                              "L" => 0,
                              "O" => 0,
                              "PC"=> 0,
                              "M" => 0,
                              "R" => 0,
                              "S" => 0,
                              "ZF"=> 0
                             ];

 //Lógica para cargar los datos del cuadro de cantidad de usuarios
  foreach ($dias as &$valor){
      $where = $valor[0] . $valor[1];
      $fila  = 19;

      $cc2 = new CuentasPorCobrar();
      $datos_cuenta_por_cobrar= $cc2->GetCantidadUsuariosCuentasPorCobrar($where,$proyecto);

      while(oci_fetch($datos_cuenta_por_cobrar)){

          $deuda  = oci_result($datos_cuenta_por_cobrar,"CANTIDAD_USUARIOS");
          $id_uso = oci_result($datos_cuenta_por_cobrar,"ID_USO");

          $deuda_por_uso[$id_uso]                     = $deuda;
          $total_cantidad_usuarios_por_uso [$id_uso] += $deuda;
          $total_por_periodo                         += $deuda;
          $objPHPExcel->setActiveSheetIndex(0)
                      ->setCellValue($arreglo_columnas[$columna].$fila,$deuda);
      }

      $cc2 = new CuentasPorCobrar();
      $datos_cuenta_por_cobrar= $cc2->GetCantidadUsuariosCuentasPorCobrarZF($where,$proyecto);

      while(oci_fetch($datos_cuenta_por_cobrar)){

          $deuda = oci_result($datos_cuenta_por_cobrar,"CANTIDAD_USUARIOS");
          $id_uso = oci_result($datos_cuenta_por_cobrar,"ID_USO");

          $deuda_por_uso[$id_uso]                     = $deuda;
          $total_cantidad_usuarios_por_uso [$id_uso] += $deuda;
          $total_por_periodo                         += $deuda;

          $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue($arreglo_columnas[$columna].$fila,$deuda);
      }

      foreach($deuda_por_uso as $id_uso => $valor_deuda){
          $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue($arreglo_columnas[$columna].$fila,$valor_deuda);
          $fila++;
      }

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue($arreglo_columnas[$columna].$fila,$total_por_periodo);

      $total_por_periodo=0;
      $columna++;
  }unset($valor);

  $fila = 19;
  foreach($total_cantidad_usuarios_por_uso  as $id_uso => $valor){
      $total_vencido += $valor;
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue("L".$fila,$valor);
      $fila++;
  }unset($uso);

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A15','Total')
              ->setCellValue('A29','Total');

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue("L29",$total_vencido);

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$nomarch="../../temp/reporte_cuentas_por_cobrar".".xlsx";
$objWriter->save($nomarch);
echo $nomarch;