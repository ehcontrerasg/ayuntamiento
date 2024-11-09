<?php
require_once '../clases/class.observaciones.php';

if ($_POST) {
    $codigo      = $_POST['codigo'];
    $asunto      = htmlentities($_POST['asunto']);
    $descripcion = htmlentities($_POST['descripcion']);
    $coduser     = '999999999'; //$_POST['codUser'];
    $codInm      = $_POST['codInm'];

    $o     = new Observacion();
    $datos = $o->NuevaObs($coduser, $asunto, $descripcion, $codigo, $codInm);

    echo $datos;
}
