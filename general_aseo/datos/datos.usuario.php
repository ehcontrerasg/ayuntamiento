<?php 
extract($_REQUEST);

if($tip == 'getUsuario'){
    require_once '../../clases/class.usuario.php';
    $claseUsuario = new Usuario();

    $buscarPorId = array('codigo' => $codigo_usuario);
    $getUsuario = $claseUsuario->getUsuario($buscarPorId);
    echo json_encode($getUsuario);
}