<?php
session_start();

include '../clases/class.adminconceptos.php';
include '../../destruye_sesion.php';

$num_cuotas=$_GET['num_cuotas'];

$cantidad = count($num_cuotas);
echo 'cantidad '.$num_cuotas;
//$vacios=0;
for ($j = 0; $j < $cantidad; $j++){
	$cuota = $num_cuotas[$j];
	echo 'cuota= '.$cuota;
    if (trim($cuota)=="" ){  
		$vacios++;
   	}//cierra if
}//cierra for


?>