<?php
//include_once ('../../include.php');
require'../clases/class.Migracion.php';
$project = $_POST['project'];
 
echo "
<select name='sector' id='sector' class='btn btn-default btn-sm dropdown-toggle' required onChange='load2(this.value)'>
<option value='' selected disabled>Seleccione sector...</option>"; 

$c = new Migracion();
$resultado = $c->seleccionaSector($project);
while (oci_fetch($resultado)) {
	$cod_sector = oci_result($resultado, 'ID_SECTOR') ;	
	if($cod_sector == $sector) echo "<option value='$cod_sector' selected>$cod_sector</option>\n";
	else echo "<option value='$cod_sector'>$cod_sector</option>\n";
}oci_free_statement($resultado);
		
echo "</select>";			
?>

