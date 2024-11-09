<?php

class ValidacionPassActualPassNuevo implements iValidacion{
    public static function validar($args = array('pass_nuevo' => null, 'confirmacion_pass' => null)){
        return ($args['pass_nuevo'] == $args['confirmacion_pass']) ? true : false;
    }
}