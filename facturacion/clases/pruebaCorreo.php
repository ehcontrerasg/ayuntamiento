<?php
require_once(__DIR__."../../clases/class.correo.php");
$correo = new correo();

$email = "holguinjean1@gmail.com";
$mensaje = "<p>Esta es una prueba</p>";
echo $correo->enviarcorreo($email,"SD", "Correo de prueba",$mensaje);