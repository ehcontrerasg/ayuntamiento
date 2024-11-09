<?php
require'../clases/class.Migracion.php';
//$proyecto=$_POST['proyecto'];
$sectorin = $_POST['sectorin'];
 
echo "
<select name='ruta' id='ruta' class='btn btn-default btn-sm dropdown-toggle' required>
<option value='' selected disabled>Seleccione ruta...</option>"; 

$c = new Migracion();
$resultado = $c->seleccionaRuta($sectorin);
while (oci_fetch($resultado)) {
	$cod_ruta = oci_result($resultado, 'ID_RUTA') ;	
	if($cod_ruta == $ruta) echo "<option value='$cod_ruta' selected>$cod_ruta</option>\n";
	else echo "<option value='$cod_ruta'>$cod_ruta</option>\n";
}oci_free_statement($resultado);
		
echo "</select>";			
?>

