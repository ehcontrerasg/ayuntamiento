<?php

class ValidacionExpresionPass implements iValidacion {
    public static function validar($args = array('pass_nuevo' => null)){
        return preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $args['pass_nuevo']);
    }
}   