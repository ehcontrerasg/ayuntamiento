<?php

interface Archivo{
    public function registrar($parametros = array());
    public function actualizar($parametros = array());
    public function obtenerTodos($parametros = array());
    public function eliminar($parametros = array());
}