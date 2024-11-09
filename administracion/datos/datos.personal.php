<?php
extract($_POST);
session_start();
$usuario = $_SESSION["codigo"];

if( $tip == 'getContratistas'){
    require_once '../../clases/class.contratista.php';
    $contratista = new Contratista();
    $contratistas = $contratista->getContratistas($usuario);

    $json = array();
    while($fila = oci_fetch_assoc($contratistas)){
        $arr = array("codigo" => $fila["CODIGO"], "descripcion" => $fila["DESCRIPCION"]);
        array_push($json,$arr);
    }

    echo json_encode($json);
}

if($tip == "getProyectos"){
    require_once "../../clases/class.proyecto.php";
    $claseProyecto = new Proyecto();

    $proyectos = $claseProyecto->obtenerProyecto($usuario);
    $json = array();
    while($fila = oci_fetch_assoc($proyectos)){
        $arr = array("codigo" => $fila["CODIGO"], "descripcion" => $fila["DESCRIPCION"]);
        array_push($json,$arr);
    }

    echo json_encode($json);
}

if($tip == "getAreas"){
    require_once "../../clases/class.AreasYCargos.php";
    $claseAreasYCargos = new AreasYCargos();
    $areas = $claseAreasYCargos->getAreas();
    $json = array();
    while($fila = oci_fetch_assoc($areas)){
        $arr = array("codigo" => $fila["ID_AREA"], "descripcion" => $fila["DESC_AREA"]);
        array_push($json,$arr);
    }

    echo json_encode($json);
}

if($tip == "getCargosPorArea"){
    require_once "../../clases/class.AreasYCargos.php";
    $claseAreasYCargos = new AreasYCargos();
    $cargos = $claseAreasYCargos->getCargosPorArea($id_area);
    echo json_encode($cargos);
}

if($tip == "registrarEmpleado"){

    require_once "../../clases/class.usuario.php";
    $claseUsuario = new Usuario();

    echo json_encode($claseUsuario->registrar($cedula,$nombres,$apellidos,$cargo,$fecha_inicio,$proyecto,$contratista,$usuario));
}