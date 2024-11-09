<?php
include '../Clases/reports.php';
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
//$tipo = $_REQUIRE['tipo'];

//var_dump($_POST);
if ($_POST['tipo'] == 'department') {

    session_start();
    $user = $_SESSION["codigo"];
    $r       = new Reports();
    include_once '../../clases/class.AreasYCargos.php';
    $c = new AreasYCargos();
    $idCargo = oci_fetch_assoc($c->getCargoByUser($user))["ID_CARGO"];
    if($idCargo == 600 || ($idCargo == 111 || $idCargo == 112 || $idCargo == 9)){
        $reporte = $r->getDepartment();
    }else{
        $reporte = $r->getDepartment($user);
    }


    echo json_encode($reporte);

}
if ($_GET['tipo'] == 'report') {
    $dep     = $_GET['department'];
    $fec_in  = $_GET['fecha_ini'];
    $fec_fin = $_GET['fecha_fin'];
    $estado  = $_GET['estado'];

    $r = new Reports();

    $reporte = $r->getDataReport($dep, $fec_in, $fec_fin,$estado);
    $data    = array();
    while (oci_fetch($reporte)) {
        $id                  = oci_result($reporte, 'ID_SCMS');
        $solicitante         = oci_result($reporte, 'SOLICITADOR');
        $depart              = oci_result($reporte, 'DEPARTAMENTO');
        $tip                 = oci_result($reporte, 'TIPO');
        $priori              = oci_result($reporte, 'PRIORIDAD');
        $fecha               = oci_result($reporte, 'FECHA');
        $estado              = oci_result($reporte, 'ESTADO');
        $valida_solicitante  = oci_result($reporte, 'VALIDA_SOLICITANTE');

        $arr = array(
            $id,
            $solicitante,
            $depart,
            $tip,
            $priori,
            $estado,
            $fecha,
            $valida_solicitante,
            "<a href='#' id='$id' class='reportId' data-toggle='modal' data-target='#modal-report'><span class='glyphicon glyphicon-eye-open' aria-hidden='true'></span></a>",

        );
        array_push($data, $arr);
    }
    oci_free_statement($reporte);

    echo json_encode($data);
}

/*if($_GET['tipo'] == 'getEstados'){


    include_once ("../Clases/clase.solicitudes.php");
    $s = new Solicitudes();


    $estados = $s->getEstadosScms();
    $dataEstados = [];
    while($fila = oci_fetch_assoc($estados)){

        $arrEstados = [$fila["CODIGO"],$fila["DESCRIPCION"]];
        array_push($dataEstados,$arrEstados);

    }

    echo json_encode($dataEstados);
}*/
