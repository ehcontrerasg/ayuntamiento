<?php
require_once dirname(__FILE__) . '/iValidacion.php';
require_once dirname(__FILE__) . "/class.usuario.php";
class ValidacionPassActual implements iValidacion{

    public static function validar($args = array('codigo_usuario'=> null,  'pass_actual' => null )){

        $claseUsuario = new Usuario();
        $parametros = array('codigo' => $args["codigo_usuario"], 'pass1'=>$args["pass_actual"]);
        $usuario = (object)$claseUsuario->getUsuario($parametros);
        
        $cantidadUsuarios = count($usuario->data); 

        return $cantidadUsuarios > 0 ? true : false;
    }
}