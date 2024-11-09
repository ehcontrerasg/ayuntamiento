<?php

//ini_set("memory_limit","1024M"); //Script should use up to 32MB of memory
$tip = $_POST["tip"];


if($tip=="getProyectos"){
    include_once "../../clases/class.proyecto.php";
    session_start();
    $usr=$_SESSION["codigo"];
    $p= new Proyecto();
    $proyectos = $p->obtenerProyecto($usr);
    $data=[];

    while (oci_fetch($proyectos)){
        $id_proyecto = oci_result($proyectos,"CODIGO");
        $descripcion_proyecto = oci_result($proyectos,"DESCRIPCION");
        $arr=[$id_proyecto,$descripcion_proyecto];
        array_push($data,$arr);
    }
    echo json_encode($data);
}

if($tip=="getUsos"){
    include_once  "../clases/class.uso.php";
    $u = new Uso();
    $usos=$u->obtenerusos();
    $data=[];
    while(oci_fetch($usos)){
        $id_uso = oci_result($usos,"ID_USO");
        $descripcion_uso = oci_result($usos,"DESC_USO");
        $arr=[$id_uso,$descripcion_uso];
        array_push($data,$arr);
    };

    echo json_encode($data);
}

if($tip=="getTipoCliente"){

    include_once  "../../clases/class.cliente.php";
    $c = new Cliente();
    $cliente=$c->getTiposCli();
    $data=[];
    while(oci_fetch($cliente)){
        $id_cliente = oci_result($cliente,"CODIGO");
        $des_cliente = oci_result($cliente,"DESCRIPCION");

        $arr=[$id_cliente,$des_cliente];
        array_push($data,$arr);
    };

    echo json_encode($data);
}


if($tip=="getActividadPorUso"){
    include_once  "../../clases/class.actividad.php";
    $a = new Actividad();

    $uso= $_POST["uso"];
    $actividades = $a->getActividadesByUso($uso);
    $data=[];

    while(oci_fetch($actividades)){
        $id_actividad = oci_result($actividades,"CODIGO");
        $descripcion_actividad = oci_result($actividades,"DESCRIPCION");

        $arr=[$id_actividad,$descripcion_actividad];
        array_push($data,$arr);
    };

    echo json_encode($data);

}

if($tip=="getZonaPorProyecto"){
    include_once  "../clases/class.zona.php";
    $a = new Zona();

    $proyecto= $_POST["proyecto"];
    $zonas = $a->obtieneZonas($proyecto);
    $data=[];

    while(oci_fetch($zonas)){
        $id_zona = oci_result($zonas,"CODIGO");
        $des_zona = oci_result($zonas,"DESCRIPCION");

        $arr=[$id_zona,$des_zona];
        array_push($data,$arr);
    };

    echo json_encode($data);

}

if($tip=="getServicios"){
    include_once "../clases/class.servicio.php";

    $s= new Servicio();
    $servicios = $s->obtenerservicio();

    $data=[];
    while(oci_fetch($servicios)){
        $id_servicio = oci_result($servicios,"COD_SERVICIO");
        $descripcion_servicio = oci_result($servicios,"DESC_SERVICIO");
        $arr=[$id_servicio,$descripcion_servicio];
        array_push($data,$arr);
    };
    echo json_encode($data);
}

if($tip=="getReporte") {

    include_once "../clases/class.reportes_catastro.php";

    $requestData=$_REQUEST;

    $proyecto = $_POST["proyecto"];
    $proIni = $_POST["proceso_inicial"];
    $proFin = $_POST["proceso_final"];
    $manIni = $_POST["manzana_inicial"];
    $manFin = $_POST["manzana_final"];
    $estIni = strtoupper($_POST["estado_inicial"]);
    $estFin = strtoupper($_POST["estado_final"]);
    $uso = $_POST["uso"];
    $actividad = $_POST["actividad"];
    $servicio = $_POST["servicio"];
    $idzona = $_POST["idzona"];
    $tipcliente = $_POST["tipcliente"];



  //  $info = $_POST["info"];

  //  echo $info;
    $r = new Reportes();

    $requestDataStart  = $requestData["start"];
    $requestDataLenght = $requestData["length"];

    $page=$requestDataStart+10;
    $page=$page/10;

    $rp = 10;

    $end = ($page-1) * $rp;  // MAX_ROW_TO_FETCH
    $start = ($page) * $rp;  // MIN_ROW_TO_FETCH


    $reporte = $r->obtenerInmEstadoII($proyecto, $proIni, $proFin, $manIni, $manFin, $estIni, $estFin,
        $uso, $actividad, $servicio, $idzona, $tipcliente, $start , $end);

    include_once "../clases/class.reportes_catastro.php";
    $c = new Reportes();

    $cantidad = 0;
    $cantidad = $c->cantidadInmEstado($proyecto, $proIni, $proFin, $manIni, $manFin, $estIni, $estFin,
        $uso, $actividad, $servicio, $idzona , $tipcliente);

    $data = array();
    while ($row = oci_fetch_array($reporte, OCI_ASSOC)) {
        $nestedData = array();
        $nestedData[] = $row["ID_SECTOR"];
        $nestedData[] = $row["RUTA"];
        $nestedData[] = $row["ID_ZONA"];
        $nestedData[] = $row["CODIGO_INM"];
        $nestedData[] = $row["DESC_URBANIZACION"];
        $nestedData[] = $row["DIRECCION"];
        $nestedData[] = $row["ALIAS"];
        $nestedData[] = $row["ID_PROCESO"];
        $nestedData[] = $row["CATASTRO"];
        $nestedData[] = $row["MEDIDOR"];
        $nestedData[] = $row["SERIAL"];
        $nestedData[] = $row["COD_EMPLAZAMIENTO"];
        $nestedData[] = $row["DESC_CALIBRE"];
        $nestedData[] = $row["DESC_SUMINISTRO"];
        $nestedData[] = $row["ID_USO"];
        $nestedData[] = $row["DESC_ACTIVIDAD"];
        $nestedData[] = $row["TOTAL_UNIDADES"];
        $nestedData[] = $row["ID_CONTRATO"];
        $nestedData[] = $row["ID_ESTADO"];
        $nestedData[] = $row["DESC_TARIFA"];
        $nestedData[] = $row["FAC_PEND"];
        $nestedData[] = $row["DEUDA"];
        $nestedData[] = $row["CUPO_BASICO"];
        $nestedData[] = $row["CONSUMO_MINIMO"];
        $nestedData[] = $row["DESC_SERVICIO"];


        array_push($data,$nestedData);

    }
    $totalData = count($data);
    $cantidad = oci_fetch_array($cantidad)[0];
    $json_data = array(
       // "draw" => intval($requestData["data"]),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
        "recordsTotal"    => intval($cantidad),  // total number of records
        "recordsFiltered" => intval($cantidad), // total number of records after searching, if there is no searching then totalFiltered = totalData
        "data" => $data   // total data array
    );
    echo json_encode($json_data);
   // exportarReporte($proyecto, $proIni, $proFin, $manIni, $manFin, $estIni, $estFin, $uso, $actividad, $servicio,$start,$end);
}

if($tip=="exportarTabla"){
    include_once "../clases/class.reportes_catastro.php";
    include '../../recursos/PHPExcel.php';
    include '../../recursos/PHPExcel/Writer/Excel2007.php';


    $proyecto = $_POST["proyecto"];
    $proIni = $_POST["proceso_inicial"];
    $proFin = $_POST["proceso_final"];
    $manIni = $_POST["manzana_inicial"];
    $manFin = $_POST["manzana_final"];
    $estIni = strtoupper($_POST["estado_inicial"]);
    $estFin = strtoupper($_POST["estado_final"]);
    $uso = $_POST["uso"];
    $actividad = $_POST["actividad"];
    $servicio = $_POST["servicio"];
    $idzona = $_POST["idzona"];
    $tipcliente = $_POST["tipcliente"];


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
               // 'color' => array('rgb' => '000000'),
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
                //'color' => array('rgb' => '4f81bd'),
            ),
        ),
    );

    // Titulos de la hoja
    $objPHPExcel->setActiveSheetIndex(0)
        ->SetCellValue('A1', 'REPORTE INMUEBLES POR ESTADO')
        ->SetCellValue('A2', 'Radicacion')
        ->SetCellValue('I2', 'Cierre Departamento')
        ->SetCellValue('K2', 'Cierre Analista')
        ->SetCellValue('A3', 'SECTOR')
        ->SetCellValue('B3', 'RUTA')
        ->SetCellValue('C3', 'ZONA')
        ->SetCellValue('D3', 'INMUEBLE')
        ->SetCellValue('E3', 'URBANIZACION')
        ->SetCellValue('F3', 'DIRECCION')
        ->SetCellValue('G3', 'ALIAS')
        ->SetCellValue('H3', 'ID PROCESO')
        ->SetCellValue('I3', 'CATASTRO')
        ->SetCellValue('J3', 'MEDIDOR')
        ->SetCellValue('K3', 'SERIAL')
        ->SetCellValue('L3', 'COD. EMPLAZAMIENTO')
        ->SetCellValue('M3', 'DESC. CALIBRE')
        ->SetCellValue('N3', 'DESC. SUMINISTRO')
        ->SetCellValue('O3', 'ID_USO')
        ->SetCellValue('P3', 'DESC. ACTIVIDAD')
        ->SetCellValue('Q3', 'TOTAL UNIDADES')
        ->SetCellValue('R3', 'CONTRATO')
        ->SetCellValue('S3', 'ID ESTADO')
        ->SetCellValue('T3', 'DESC. TARIFA')
        ->SetCellValue('U3', 'FAC. PEND.')
        ->SetCellValue('V3', 'DEUDA')
        ->SetCellValue('W3', 'CUPO BASICO')
        ->SetCellValue('X3', 'CONSUMO MINIMO');

    //Insertamos los datos
    $r = new Reportes();
    $fila      = 4;
    $reporte = $r->obtenerInmEstadoII($proyecto, $proIni, $proFin, $manIni, $manFin, $estIni, $estFin,
        $uso, $actividad, $servicio, $idzona, $tipcliente);

    while ($row = oci_fetch_array($reporte, OCI_ASSOC)) {
           // $nestedData=array();
            $sector= $row["SECTOR"];
            $ruta= $row["RUTA"];
            $zona= $row["ID_ZONA"];
            $inmueble= $row["CODIGO_INM"];
            $urbanizacion= $row["DESC_URBANIZACION"];
            $direccion= $row["DIRECCION"];
            $alias= $row["ALIAS"];
            $proceso= $row["ID_PROCESO"];
            $catastro= $row["CATASTRO"];
            $medidor= $row["MEDIDOR"];
            $serial= $row["SERIAL"];
            $emplazamiento= $row["COD_EMPLAZAMIENTO"];
            $calibre= $row["DESC_CALIBRE"];
            $suministro= $row["DESC_SUMINISTRO"];
            $uso= $row["ID_USO"];
            $actividad= $row["DESC_ACTIVIDAD"];
            $unidades= $row["TOTAL_UNIDADES"];
            $contrato= $row["ID_CONTRATO"];
            $estado= $row["ID_ESTADO"];
            $tarifa= $row["DESC_TARIFA"];
            $facturas_pendientes= $row["FAC_PEND"];
            $deuda= $row["DEUDA"];
            $cupo_basico= $row["CUPO_BASICO"];
            $consumo_minimo= $row["CONSUMO_MINIMO"];


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $fila, $sector)
            ->setCellValue('B' . $fila, $ruta)
            ->setCellValue('C' . $fila, $zona)
            ->setCellValue('D' . $fila, $inmueble)
            ->setCellValue('E' . $fila, $urbanizacion)
            ->setCellValue('F' . $fila, $direccion)
            ->setCellValue('G' . $fila, $alias)
            ->setCellValue('H' . $fila, $proceso)
            ->setCellValue('I' . $fila, $catastro)
            ->setCellValue('J' . $fila, $medidor)
            ->setCellValue('K' . $fila, $serial)
            ->setCellValue('L' . $fila, $emplazamiento)
            ->setCellValue('M' . $fila, $calibre)
            ->setCellValue('N' . $fila, $suministro)
            ->setCellValue('O' . $fila, $uso)
            ->setCellValue('P' . $fila, $actividad)
            ->setCellValue('Q' . $fila, $unidades)
            ->setCellValue('R' . $fila, $contrato)
            ->setCellValue('S' . $fila, $estado)
            ->setCellValue('T' . $fila, $tarifa)
            ->setCellValue('U' . $fila, $facturas_pendientes)
            ->setCellValue('V' . $fila, $deuda)
            ->setCellValue('W' . $fila, $cupo_basico)
            ->setCellValue('X' . $fila, $consumo_minimo);

        $objPHPExcel->getActiveSheet()->getRowDimension($fila)->setRowHeight(25);

        $fila++;
    }
    oci_free_statement($reporte);

    //Bordes de las celdas
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A4:H' . $fila)->applyFromArray($borderArray1);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('I4:J' . $fila)->applyFromArray($borderArray1);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('K4:X' . $fila)->applyFromArray($borderArray1);

    // Ajustar el Texto al a celda
    $objPHPExcel->getActiveSheet(0)->getStyle('E3')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet(0)->getStyle('D4:D' . $fila)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet(0)->getStyle('H4:H' . $fila)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet(0)->getStyle('L4:L' . $fila)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet(0)->getStyle('M4:M' . $fila)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet(0)->getStyle('B3')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet(0)->getStyle('C3')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet(0)->getStyle('I3')->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet(0)->getStyle('N3:X3')->getAlignment()->setWrapText(true);

    // Width de las celdas
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("A")->setWidth(9.22);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("B")->setWidth(12.72);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("C")->setWidth(14.15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("D")->setWidth(27.58);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("E")->setWidth(34.86);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("F")->setWidth(34.86);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("G")->setWidth(34.86);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("H")->setWidth(29.86);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("I")->setWidth(29.86);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("J")->setWidth(15.15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("K")->setWidth(12.72);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("L")->setWidth(16.41);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("M")->setWidth(29.86);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("N")->setWidth(16.41);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("P")->setWidth(29.86);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("Q")->setWidth(15.15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("R")->setWidth(15.15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("S")->setWidth(15.15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("T")->setWidth(34.86);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("U")->setWidth(15.15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("V")->setWidth(15.15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("W")->setWidth(15.15);
    $objPHPExcel->setActiveSheetIndex(0)->getColumnDimension("X")->setWidth(15.15);
    // Height De las Celdas
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(49.5);
    $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
    $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(35.25);

    // Celdas Unificadas
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:X1');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:H2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('I2:J2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K2:N2');

    // Fuentes en Bold
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:X3')->getFont()->setBold(true);

    // Estilo y size de las fuentes
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1")->getFont()
        ->setName('Tahoma')
        ->setSize(18);
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A2:X2")->getFont()
        ->setName('Tahoma')
        ->setSize(11)
        ->getColor()->setRGB('ffffff');

    //Estilos para las celdas
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A3:X' . $fila)->applyFromArray(
        array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        )
    );
    $objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:X3')->applyFromArray($estiloTitulos);


    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Inmuebles por Estado');
    $objPHPExcel->setActiveSheetIndex(0);

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $nomarch="../../temp/Reporte_Inmuebles_por_estado.xlsx";
    $objWriter->save($nomarch);
    echo $nomarch;
   /* if(unlink($nomarch)){
        echo "Borrado.";
    }else{
        echo "Error Borrado.";
    }*/
}

//Para borrar el arhivo temporal generado
if($tip=="borrarTemporal"){
    $archivo = "../../temp/Reporte_Inmuebles_por_estado.xlsx"/*$_POST["rutaArchivo"]*/;

    unlink($archivo);
   /* if(unlink($archivo)){
        echo "Archivo  borrado.";
    }else{
        echo "Archivo no borrado.";
    }*/
}