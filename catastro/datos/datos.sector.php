<?php
$project = $_POST['project'];
 
//Conectamos con la base de datos	
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;


       	$sql = "SELECT ID_SECTOR
	   	FROM SGC_TP_SECTORES
		WHERE ID_PROYECTO = '$project' ";
		//echo $sql;
		$stid = oci_parse($link, $sql);
		oci_execute($stid, OCI_DEFAULT);
		echo "
	<select name='sector' id='sector' class='btn btn-default btn-sm dropdown-toggle' required>
	<option value='' selected></option>"; 
		while (oci_fetch($stid)) {
        	$cod_sector = oci_result($stid, 'ID_SECTOR') ;	
			//$des_tipovia = oci_result($stid, 'DESC_TIPO_VIA') ;
			if($cod_sector == $sector) echo "<option value='$cod_sector' selected>$cod_sector</option>\n";
			else echo "<option value='$cod_sector'>$cod_sector</option>\n";
			
        }oci_free_statement($stid);
		
		echo "</select>";			
?>

