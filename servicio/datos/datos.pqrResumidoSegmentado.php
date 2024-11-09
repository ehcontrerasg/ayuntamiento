<?php

extract($_GET);

if($tip == 'proyectos'){
    session_start();
    $usuario = $_SESSION["codigo"];

    require_once "../../clases/class.proyecto.php";
    $claseProyecto = new Proyecto();
    $proyectos = $claseProyecto->obtenerProyecto($usuario);
    $json = array();
    while($fila = oci_fetch_assoc($proyectos)){
        $arr = array('codigo' => $fila["CODIGO"], 'descripcion' => $fila["DESCRIPCION"]);
        array_push($json,$arr);
    }

    echo json_encode($json);
}

if($tip == "departamentos"){
    require_once "../../clases/class.AreasYCargos.php";
    $claseAreasYCargos = new AreasYCargos();
    $areas = $claseAreasYCargos->getAreas();
    $json = array();
    while($fila = oci_fetch_assoc($areas)){

        $arr = array('codigo' => $fila["ID_AREA"], 'descripcion' => ucfirst(strtolower($fila["DESC_AREA"])));

        if($arr["codigo"] == 1 || $arr["codigo"] == 2 || $arr["codigo"] == 3 || $arr["codigo"] == 4 || $arr["codigo"] == 9 || $arr["codigo"] == 10)
            array_push($json,$arr);

    }

    echo json_encode($json);
}

if($tip == "generarReporte"){
    require_once "../../clases/classPqrs.php";
    $clasePqr = new PQRs();
    $data = $clasePqr->pqrsSegmentado($fecha_inicio, $fecha_fin,$proyecto,$departamento);

    echo json_encode($data);
}