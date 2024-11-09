<?php
//include_once ('../../include.php');
require'../clases/class.Migracion.php';
$project2 = $_POST['project2'];
 
echo "
<select name='sector2' id='sector2' class='btn btn-default btn-sm dropdown-toggle' required onChange='load4(this.value)'>
<option value='' selected disabled>Seleccione sector...</option>"; 

$c = new Migracion();
$resultado = $c->seleccionaSector($project2);
while (oci_fetch($resultado)) {
	$cod_sector2 = oci_result($resultado, 'ID_SECTOR') ;	
	if($cod_sector2 == $sector2) echo "<option value='$cod_sector2' selected>$cod_sector2</option>\n";
	else echo "<option value='$cod_sector2'>$cod_sector2</option>\n";
}oci_free_statement($resultado);
		
echo "</select>";			
?>

