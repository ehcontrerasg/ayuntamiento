<?php


/*$tip= $_POST["tip"] ;

print_r($_POST);*/

extract($_POST);

if($tip == "getExtensiones"){
include_once '../../clases/class.extensiones.php';
$e = new Extensiones();
$extension_list = [];

$data = $e->GetExtensions();
while ($row= oci_fetch_assoc($data)){

    $arr = [$row["ID_EXTENSION"],$row["EXTENSION"],$row["DEPARTAMENTO"], $row["USUARIO"], $row["DESCRIPCION"],
        '<input type="button" class="btn btn-primary btnEditar" value="Editar"> <input type="button" class="btn btn-remove btnEliminar" value="Eliminar">'];
    array_push($extension_list,$arr);
}
echo json_encode($extension_list);
}

if($tip == "getAreas"){
    include_once '../../clases/class.AreasYCargos.php';

    $u    = new AreasYCargos();
    $areas = $u->getAreas();
    $data = [];

    while($row = oci_fetch_assoc($areas)){
        $arr=[$row["ID_AREA"],$row["DESC_AREA"]];
        array_push($data,$arr);
    }

    echo json_encode($data) ;

}

if($tip == "getUsuarios"){
    include_once '../../clases/class.usuario.php';

    $u    = new Usuario();
    $usuarios = $u->getUsuariosPorArea($area);
    $data  = [];

    while($row = oci_fetch_assoc($usuarios)){
        $arr = [$row["ID_USUARIO"],$row["NOMBRE_USUARIO"]];
        array_push($data,$arr );
    }

    echo json_encode($data) ;

}

if($tip == "insertarExtension"){
    extract($_POST);
    include_once '../../clases/class.extensiones.php';
    $e = new Extensiones();

    $data = $e->InsertarExtension($extension,$usuario,$area,$descripcion);

    echo json_encode($data);
}

if($tip == "actualizarExtension"){
    extract($_POST);
    include_once '../../clases/class.extensiones.php';
    $e = new Extensiones();

    $data = $e->EditarExtension($id_extension,$extension,$usuario,$area,$descripcion);


    echo json_encode($data);
}

if($tip == "eliminarExtension"){

    extract($_POST);

    include_once '../../clases/class.extensiones.php';
    $e = new Extensiones();

    $data = $e->EliminarExtension($id_extension);
    echo json_encode($data);
}