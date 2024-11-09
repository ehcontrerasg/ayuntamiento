<?php
include '../clases/reports.php';

//$tipo = $_REQUIRE['tipo'];

if ($_POST['tipo'] == 'department') {

    $r       = new Reports();
    $reporte = $r->getDepartment();

    echo json_encode($reporte);

}
if ($_GET['tipo'] == 'report') {
    $dep     = $_GET['department'];
    $fec_in  = $_GET['fecha_ini'];
    $fec_fin = $_GET['fecha_fin'];

    $r = new Reports();

    $reporte = $r->getDataReport($dep, $fec_in, $fec_fin);
    $data    = array();
    while (oci_fetch($reporte)) {
        $id          = oci_result($reporte, 'ID_SCMS');
        $solicitante = oci_result($reporte, 'SOLICITADOR');
        $depart      = oci_result($reporte, 'DEPARTAMENTO');
        $tip         = oci_result($reporte, 'TIPO');
        $priori      = oci_result($reporte, 'PRIORIDAD');
        $fecha       = oci_result($reporte, 'FECHA');
        $estado      = oci_result($reporte, 'ESTADO');

        $arr = array(
            $id,
            $solicitante,
            $depart,
            $tip,
            $priori,
            $estado,
            $fecha,
            "<a href='#' id='$id' class='reportId' data-toggle='modal' data-target='#modal-report'><span class='glyphicon glyphicon-eye-open' aria-hidden='true'></span></a>",

        );
        array_push($data, $arr);
    }
    oci_free_statement($reporte);

    echo json_encode($data);
}
