<?php
require_once(dirname(__FILE__) . '/class.validacionPassActual.php');
require_once(dirname(__FILE__) . '/class.validacionExpresionPass.php');
require_once(dirname(__FILE__) . '/class.validacionPassActualPassNuevo.php');
class CambioPass{
    public static function validar($args = array('codigo_usuario' => null, 'pass_actual' => null, 'pass_nuevo'=>null, 'confirmacion_pass' => null)){

        if(!ValidacionPassActual::validar($args)) return array('valido' => false, 'codigo'=> 1, 'mensaje' => 'La contraseña actual no coincide con la indicada.');
        if(!ValidacionExpresionPass::validar($args)) return array('valido' => false, 'codigo'=> 2, 'mensaje' => 'Las contraseñas deben ser de al menos 8 caracteres y deben contener al menos una letra en mayúscula y minúscula.');
        if(!ValidacionPassActualPassNuevo::validar($args)) return array('valido' => false, 'codigo'=> 3, 'mensaje' => 'La contraseña nueva y su confirmación no coinciden.');

        return array('valido' => true);
    }
}