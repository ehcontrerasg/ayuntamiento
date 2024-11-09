<?php
header('Content-Type: text/html; charset=UTF-8');
require_once './../Clases/class.conexion.php';
$idinmueble=$_GET['inmueble'];
$periodo= $_GET['periodo'];



header('Location: ../../../facturacion/vistas/vista.facturaInstantanea.php?inmueble='.$idinmueble.'&periodo='.$periodo);




