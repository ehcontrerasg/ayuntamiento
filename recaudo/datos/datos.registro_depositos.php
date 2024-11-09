<?php
session_start();

include '../clases/classPagos.php';

$cod_usuario = $_SESSION['codigo'];
$tipo        = $_GET['tipo'];

if ($tipo == 'get') {
    $periodo     = $_GET['periodo'];
    $proyecto    = $_GET['proy'];
    $concepto    = $_GET['concepto'];
    $arrFecha    = array();
    $arrRecaudo  = array();
    $arrCantidad = array();

    $p     = new Pagos();
    $pagos = $p->getRecCon($proyecto, $concepto, $periodo);

    while (oci_fetch($pagos)) {
        $fecha    = oci_result($pagos, 'FECHAPAGO');
        $recaudo  = oci_result($pagos, 'TOTAL');
        $cantidad = oci_result($pagos, 'CANTIDAD');
        $v        = new Pagos();
        $verifDia = $v->verificaDiaFestivo($fecha);
        $date     = strtotime($verifDia);
        $newFecha = date('d/m/Y', $date);

        if ($newFecha != $fecha) {
            $recaudo2 += $recaudo;
            $cantidad2 += $cantidad;
        } else {
            array_push($arrFecha, $fecha);
            if ($recaudo2 > 0) {
                $recaudo2 += $recaudo;
                $cantidad2 += $cantidad;

                array_push($arrCantidad, $cantidad2);
                array_push($arrRecaudo, $recaudo2);
                $recaudo2  = 0;
                $cantidad2 = 0;
            } else {
                array_push($arrCantidad, $cantidad);
                array_push($arrRecaudo, $recaudo);
            }
        }

    }
    oci_free_statement($pagos);

    $data = array('FECHAPAGO' => $arrFecha, 'TOTAL' => $arrRecaudo, 'CANTIDAD' => $arrCantidad);

    echo (json_encode($data));
} elseif ($tipo == 'set') {
    $fecha       = $_GET['fecha'];
    $cantidad    = $_GET['cantidad'];
    $reporte     = $_GET['reporte'];
    $fechaDep    = $_GET['fechaDep'];
    $comprobante = $_GET['comprobante'];
    $monto       = $_GET['monto'];
    $concepto    = $_GET['concepto'];
    $proyecto    = $_GET['proy'];

    $p = new Pagos();
    if ($p->verificaDeposito($fecha, $concepto)) {
        $pagos = $p->setRegDepositosConceptos($fecha, $cantidad, $reporte, $fechaDep, $comprobante, $monto, $concepto, $cod_usuario, $proyecto);
        echo $pagos;
    } else {
        echo 4;
    }
} elseif ($tipo == 'his') {
    $periodo     = $_GET['periodo'];
    $concepto    = $_GET['concepto'];
    $proyecto    = $_GET['proy'];
    $fechaP      = array();
    $comprobante = array();
    $monto       = array();
    $fechaD      = array();

    $p         = new Pagos();
    $historial = $p->getHistorial($periodo, $concepto, $proyecto);

    while (oci_fetch($historial)) {
        array_push($fechaP, oci_result($historial, 'FECHA_PAGO'));
        array_push($fechaD, oci_result($historial, 'FECHA_DEPOSITO'));
        array_push($comprobante, oci_result($historial, 'COMPROBANTE'));
        array_push($monto, oci_result($historial, 'MONTO_DEPOSITO'));
    }
    oci_free_statement($historial);

    $data = array('fechaP' => $fechaP, 'fechaD' => $fechaD, 'comprobante' => $comprobante, 'monto' => $monto);
    echo json_encode($data);
} elseif ($tipo == 'upd') {
    $fecha       = $_GET['fecha'];
    $fechaDep    = $_GET['fechaDep'];
    $comprobante = $_GET['comprobante'];
    $monto       = $_GET['monto'];
    $concepto    = $_GET['concepto'];
    $proyecto    = $_GET['proy'];
    $periodo     = $_GET['periodo'];

    $p    = new Pagos();
    $data = $p->updateRegDepCon($fecha, $fechaDep, $comprobante, $monto, $concepto, $proyecto, $periodo, $cod_usuario);

    echo $data;
}
