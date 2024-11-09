<?php
require'../clases/class.Migracion.php';
//$proyecto=$_POST['proyecto'];
$sectorin2 = $_POST['sectorin2'];
 
echo "
<select name='ruta2' id='ruta2' class='btn btn-default btn-sm dropdown-toggle' required onchange='recarga();'>
<option value='' selected disabled>Seleccione ruta...</option>"; 

$c = new Migracion();
$resultado = $c->seleccionaRuta($sectorin2);
while (oci_fetch($resultado)) {
	$cod_ruta2 = oci_result($resultado, 'ID_RUTA') ;	
	if($cod_ruta2 == $ruta2) echo "<option value='$cod_ruta2' selected>$cod_ruta2</option>\n";
	else echo "<option value='$cod_ruta2'>$cod_ruta2</option>\n";
}oci_free_statement($resultado);
		
echo "</select>";			
?>

