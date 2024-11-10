<?php

$uso = $_POST['uso'];
 
//Conectamos con la base de datos	
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;


       	$sql = "SELECT SEC_ACTIVIDAD, DESC_ACTIVIDAD
	   	FROM SGC_TP_ACTIVIDADES
		WHERE ID_USO = '$uso' ";
		//echo $sql;
		$stid = oci_parse($link, $sql);
		oci_execute($stid, OCI_DEFAULT);
		echo "
	<select name='actividad' id='actividad' class='btn btn-default btn-sm dropdown-toggle' >
	<option value='' selected>Seleccione Actividad...</option>"; 
		while (oci_fetch($stid)) {
        	$cod_actividad = oci_result($stid, 'SEC_ACTIVIDAD') ;	
			$des_actividad = oci_result($stid, 'DESC_ACTIVIDAD') ;
			if($cod_actividad == $actividad) echo "<option value='$cod_actividad' selected>$des_actividad</option>\n";
			else echo "<option value='$cod_actividad'>$des_actividad</option>\n";
			
        }oci_free_statement($stid);
		
		echo "</select>";			
?>

