<?php
include './correo.php';

$l=new correo();
$l->enviarcorreo("edwin_contrerass@hotmail.com", "0000", "activo");
?>