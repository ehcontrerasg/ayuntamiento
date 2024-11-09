<?php

     class DatosPagosRecaudosPorSectorYDiametro {

        function ObtenerDatosColumna($arreglo,$indice){

            $data =[];
            $longitudArreglo = count($arreglo);
            for($i = 0;$i<$longitudArreglo;$i++) {
                $valor = $arreglo[$i]["$indice"];

                $encontrado = in_array($valor,$data);
                if(!$encontrado){
                    array_push($data,$valor);
                }
            }
            return $data;
        }
    }

    extract($_POST);

    /*************************************************************************/

    if($tip == "selPro"){
        include_once  "../../clases/class.proyecto.php";
        session_start();
        $user = $_SESSION['codigo'];
        $p    = new Proyecto();
        $proyectos = $p->obtenerProyecto($user);

        $data = [];
        while($fila = oci_fetch_assoc($proyectos)){
            extract($fila);
            $arr = ["CODIGO"     =>$CODIGO,
                "DESCRIPCION"=>$DESCRIPCION];
            array_push($data,$arr);
        }

        echo json_encode($data);
    }

    if($tip == "ObtenerSectores"){
        require_once "../../clases/class.sector.php";

        $s = new Sector();
        $dataSector = $s->getSecByPro($proyecto);
        $respuesta = [];
        while($fila = oci_fetch_assoc($dataSector)){

            $descripcion = $fila["DESCRIPCION"];

            if($descripcion<10){
                $descripcion ='0'.$descripcion;
            }

            $descripcion = "Sector ".$descripcion;
            array_push($respuesta,$descripcion);
        }

        echo json_encode($respuesta);
    }

    if($tip == "ObtenerUsos"){
        require_once "../../clases/class.uso.php";

        $s = new Uso();
        $dataUso = $s->getUsos();
        $respuesta = [];
        while($fila = oci_fetch_assoc($dataUso)){

            $descripcion = $fila["DESCRIPCION"];

            array_push($respuesta,$descripcion);
        }

        //Añadir Clasificación zona franca.
        array_push($respuesta,'Zonas francas');

        echo json_encode($respuesta);
    }

    if($tip == "NumeroPagosPorSector"){

    include_once "../clases/class.PagosRecaudosPorSectorYDiametro.php";
    $m           = new PagosRecaudosPorSectorYDiametro();

        $resultado = $m->RecaudosYNumeroPagosPorSector($periodoDesde,$periodoHasta, $proyecto);

        $datosResultado = [];
        while($fila = oci_fetch_assoc($resultado)){
            $numeroPagos = $fila["NUMERO_PAGOS"];
            $recaudos    = $fila["RECAUDOS"];
            $periodo     = $fila["PERIODO"];
            $sector      = $fila["SECTOR"];
            $calibre     = $fila["CALIBRE"];

            $arr = ["VALOR"        => $numeroPagos,
                    "PERIODO"      => $periodo,
                    "CLASIFICACION"=> "Sector ".$sector,
                    "CALIBRE"      => $calibre];

            array_push($datosResultado,$arr);
        }


        $datosPagosRecaudosPorSectorYDiametro = new DatosPagosRecaudosPorSectorYDiametro();
        $columnaPeriodos        = $datosPagosRecaudosPorSectorYDiametro->ObtenerDatosColumna($datosResultado,"PERIODO");

        $data = [
                    "PERIODOS"         => $columnaPeriodos,
                    "DATA"             => $datosResultado
                ];
        echo json_encode($data);
    }

    if($tip == "RecaudosPorSector"){

    include_once "../clases/class.PagosRecaudosPorSectorYDiametro.php";
    $m           = new PagosRecaudosPorSectorYDiametro();

        $resultado = $m->RecaudosYNumeroPagosPorSector($periodoDesde,$periodoHasta, $proyecto);

        $datosResultado = [];
        while($fila = oci_fetch_assoc($resultado)){
            $numeroPagos = $fila["NUMERO_PAGOS"];
            $recaudos    = $fila["RECAUDOS"];
            $periodo     = $fila["PERIODO"];
            $sector      = $fila["SECTOR"];
            $calibre     = $fila["CALIBRE"];

            $arr = ["VALOR"        => round($recaudos),
                    "PERIODO"      => $periodo,
                    "CLASIFICACION"=> "Sector ".$sector,
                    "CALIBRE"      => $calibre];

            array_push($datosResultado,$arr);
        }


        $datosPagosRecaudosPorSectorYDiametro = new DatosPagosRecaudosPorSectorYDiametro();
        $columnaPeriodos        = $datosPagosRecaudosPorSectorYDiametro->ObtenerDatosColumna($datosResultado,"PERIODO");

        $data = [
                    "PERIODOS"         => $columnaPeriodos,
                    "DATA"             => $datosResultado
                ];

        echo json_encode($data);
    }

    if($tip == "NumeroPagosAgua"){

    include_once "../clases/class.PagosRecaudosPorSectorYDiametro.php";
    $m           = new PagosRecaudosPorSectorYDiametro();

        $resultado = $m->RecaudoYNumeroPagosPorUso($periodoDesde,$periodoHasta, $proyecto);

        $datosResultado = [];
        while($fila = oci_fetch_assoc($resultado)){
            $numeroPagos = $fila["NUMERO_PAGOS"];
            //$recaudos    = $fila["RECAUDOS"];
            $periodo     = $fila["PERIODO"];
            $uso         = $fila["DESC_USO"];
            $zonaFranca  = $fila["ZONA_FRANCA"];
            $calibre     = $fila["CALIBRE"];


            if($zonaFranca == "S"){
                $uso = "Zonas francas";
            }

            $arr = ["VALOR"         => $numeroPagos,
                    "PERIODO"       => $periodo,
                    "CLASIFICACION" => $uso,
                    "CALIBRE"       => $calibre];

            array_push($datosResultado,$arr);
        }


        $datosPagosRecaudosPorSectorYDiametro = new DatosPagosRecaudosPorSectorYDiametro();
        $columnaPeriodos        = $datosPagosRecaudosPorSectorYDiametro->ObtenerDatosColumna($datosResultado,"PERIODO");

        $data = [
                    "PERIODOS"         => $columnaPeriodos,
                    "DATA"             => $datosResultado
                ];
        echo json_encode($data);
    }

    if($tip == "RecaudosAgua"){

    include_once "../clases/class.PagosRecaudosPorSectorYDiametro.php";
    $m           = new PagosRecaudosPorSectorYDiametro();

        $resultado = $m->RecaudosAguaPorUso($periodoDesde,$periodoHasta, $proyecto);

        $datosResultado = [];
        while($fila = oci_fetch_assoc($resultado)){

            $recaudos    = $fila["RECAUDOS"];
            $periodo     = $fila["PERIODO"];
            $uso         = $fila["DESC_USO"];
            $zonaFranca  = $fila["ZONA_FRANCA"];
            $calibre     = $fila["CALIBRE"];


            if($zonaFranca == "S"){
                $uso = "Zonas francas";
            }

            $arr = ["VALOR"         => round($recaudos),
                    "PERIODO"       => $periodo,
                    "CLASIFICACION" => $uso,
                    "CALIBRE"       => $calibre];

            //print_r($arr);
            array_push($datosResultado,$arr);
        }


        $datosPagosRecaudosPorSectorYDiametro = new DatosPagosRecaudosPorSectorYDiametro();
        $columnaPeriodos        = $datosPagosRecaudosPorSectorYDiametro->ObtenerDatosColumna($datosResultado,"PERIODO");

        $data = [
                    "PERIODOS"         => $columnaPeriodos,
                    "DATA"             => $datosResultado
                ];
        echo json_encode($data);
    }

    if($tip == "ConsumoFacturadoRed"){

    include_once "../clases/class.PagosRecaudosPorSectorYDiametro.php";
    $m           = new PagosRecaudosPorSectorYDiametro();

        $resultado = $m->M3FacturadoAguaRed($periodoDesde,$periodoHasta, $proyecto);

        $datosResultado = [];
        while($fila = oci_fetch_assoc($resultado)){
            $unidades    = $fila["UNIDADES"];
            $periodo     = $fila["PERIODO"];
            $uso         = $fila["DESC_USO"];
            $zonaFranca  = $fila["ZONA_FRANCA"];
            $calibre     = $fila["CALIBRE"];


            if($zonaFranca == "S"){
                $uso = "Zonas francas";
            }

            $arr = ["VALOR"         => $unidades,
                    "PERIODO"       => $periodo,
                    "CLASIFICACION" => $uso,
                    "CALIBRE"       => $calibre];

            array_push($datosResultado,$arr);
        }


        $datosPagosRecaudosPorSectorYDiametro = new DatosPagosRecaudosPorSectorYDiametro();
        $columnaPeriodos        = $datosPagosRecaudosPorSectorYDiametro->ObtenerDatosColumna($datosResultado,"PERIODO");

        $data = [
                    "PERIODOS"         => $columnaPeriodos,
                    "DATA"             => $datosResultado
                ];
        echo json_encode($data);
    }

    if($tip == "ConsumoFacturadoPozo") {

        include_once "../clases/class.PagosRecaudosPorSectorYDiametro.php";
        $m = new PagosRecaudosPorSectorYDiametro();

        $resultado = $m->M3FacturadoAguaPozo($periodoDesde, $periodoHasta, $proyecto);

        $datosResultado = [];
        while ($fila = oci_fetch_assoc($resultado)) {
            $facturado = $fila["FACTURADO"];
            $periodo = $fila["PERIODO"];
            $uso = $fila["DESC_USO"];
            $zonaFranca = $fila["ZONA_FRANCA"];
            $calibre = $fila["CALIBRE"];


            if ($zonaFranca == "S") {
                $uso = "Zonas francas";
            }

            $arr = ["VALOR" => $facturado,
                "PERIODO" => $periodo,
                "CLASIFICACION" => $uso,
                "CALIBRE" => $calibre];

            array_push($datosResultado, $arr);
        }


        $datosPagosRecaudosPorSectorYDiametro = new DatosPagosRecaudosPorSectorYDiametro();
        $columnaPeriodos = $datosPagosRecaudosPorSectorYDiametro->ObtenerDatosColumna($datosResultado, "PERIODO");

        $data = [
            "PERIODOS" => $columnaPeriodos,
            "DATA" => $datosResultado
        ];

        echo json_encode($data);
    }