<?php

extract($_POST);

/*Resumen general*/
if($tip == 'facturacionPorPromedio'){

    //$proyecto = 'SD'; //ELIMINAR ESTA VARIABLE, VALOR FIJO TEMP
    require_once('../clases/class.reportes_resgen.php');

    $data = array();
    $reportesFacturacion = new ReportesFacturacion();
    $resource = $reportesFacturacion->CantidadPromedios($periodoDesde,$zonaDesde,$zonaHasta,$periodoHasta,$proyecto);

    while($fila = oci_fetch_assoc($resource)){

        //var_dump($fila);
        $cantidad  = $fila['CANTIDAD'];
        $consumo   = $fila['CONSUMO'];
        $facturado = $fila['FACTURADO'];
        $recaudado = $fila['RECAUDADO'];
        $pendiente = $fila['PENDIENTE'];

        $array = array("cantidad"=>$cantidad,'consumo'=>$consumo,'facturado'=>$facturado,'recaudado'=>$recaudado,'pendiente'=>$pendiente);

        array_push($data,$array);
    }

    echo json_encode($data);
}

if($tip == 'facturacionPorDiferencia'){

    //$proyecto = 'SD'; //ELIMINAR ESTA VARIABLE, VALOR FIJO TEMP
    require_once('../clases/class.reportes_resgen.php');

    $data = array();
    $reportesFacturacion = new ReportesFacturacion();
    $resource = $reportesFacturacion->CantidadLeidos($periodoDesde,$zonaDesde,$zonaHasta,$periodoHasta,$proyecto);

    while($fila = oci_fetch_assoc($resource)){

        $cantidad  = $fila['CANTIDAD'];
        $consumo   = $fila['CONSUMO'];
        $facturado = $fila['FACTURADO'];
        $recaudado = $fila['RECAUDADO'];
        $pendiente = $fila['PENDIENTE'];

        $array = array("cantidad"=>$cantidad,'consumo'=>$consumo,'facturado'=>$facturado,'recaudado'=>$recaudado,'pendiente'=>$pendiente);

        array_push($data,$array);
    }

    echo json_encode($data);
}
/*Fin resumen general*/

/*Resumen por concepto, uso y tarifa*/

if($tip == 'conceptos'){

    //$proyecto = 'SD'; //ELIMINAR ESTA VARIABLE, VALOR FIJO TEMP
    require_once('../clases/class.reportes_resgen.php');

    $reportesFacturacion = new ReportesFacturacion();
    $resource = $reportesFacturacion->CantidadConceptosOptimizado($periodoDesde,$zonaDesde,$zonaHasta,$periodoHasta,$proyecto);

    $data = array();
    while($fila = oci_fetch_assoc($resource)){
        $concepto      = $fila['CONCEPTO'];
        $descServicio = $fila['DESC_SERVICIO'];

        $array = array  (
                            'concepto'      => $concepto,
                            'desc_servicio' => $descServicio
                        );

        array_push($data,$array);
    }

    echo json_encode($data);
}

if($tip == 'usos'){

    //$proyecto = 'SD'; //ELIMINAR ESTA VARIABLE, VALOR FIJO TEMP
    require_once('../clases/class.reportes_resgen.php');

    $reportesFacturacion = new ReportesFacturacion();
    $resource = $reportesFacturacion->CantidadUsosOptimizado($periodoDesde,$zonaDesde,$zonaHasta,$periodoHasta,$proyecto);

    $data = array();
    while($fila = oci_fetch_assoc($resource)){

        $concepto      = $fila['CONCEPTO'];
        $descServicio  = $fila['DESC_SERVICIO'];
        $descUso       = $fila['DESC_USO'];

        $array = array  (
            'concepto'      => $concepto,
            'desc_servicio' => $descServicio,
            'desc_uso'      => $descUso
        );

        array_push($data,$array);
    }

    echo json_encode($data);

}

if($tip == 'tarifas'){

    ////$proyecto = 'SD'; //ELIMINAR ESTA VARIABLE, VALOR FIJO TEMP
    require_once('../clases/class.reportes_resgen.php');

    $reportesFacturacion = new ReportesFacturacion();
    $resource = $reportesFacturacion->CantidadConceptoPorUsoOptimizado($periodoDesde,$zonaDesde,$zonaHasta,$periodoHasta,$proyecto);

    $data = array();
    while($fila = oci_fetch_assoc($resource)){

        $uso          = $fila['DESC_USO'];
        $codigoTarifa = $fila['CODIGO_TARIFA'];
        $descTarifa   = $fila['DESC_TARIFA'];
        $consumo      = $fila['CONSUMO'];
        $cantidad     = $fila['CANTIDAD'];
        $unidades     = $fila['UNIDADES'];
        $facturado    = $fila['FACTURADO'];
        $recaudado    = $fila['RECAUDADO'];
        $pendiente    = $fila['PENDIENTE'];
        $concepto     = $fila['CONCEPTO'];

        $array = array  (
            'uso'           => $uso,
            'codigo_tarifa' => $codigoTarifa,
            'desc_tarifa'   => $descTarifa,
            'consumo'       => $consumo,
            'cantidad'      => $cantidad,
            'unidades'      => $unidades,
            'facturado'     => $facturado,
            'recaudado'     => $recaudado,
            'pendiente'      => $pendiente,
            'concepto'      => $concepto
        );

        array_push($data,$array);
    }

    echo json_encode($data);

}
/*Fin resumen por concepto, uso y tarifa*/