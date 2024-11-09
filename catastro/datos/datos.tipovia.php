<?php
include_once ('../../include.php');
$project = $_POST['project'];
 
//Conectamos con la base de datos	
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;


       	$sql = "SELECT ID_TIPO_VIA, DESC_TIPO_VIA
	   	FROM SGC_TP_TIPO_VIA
		WHERE ID_PROYECTO = '$project' ";
		//echo $sql;
		$stid = oci_parse($link, $sql);
		oci_execute($stid, OCI_DEFAULT);
		echo "
	<select name='tipovia' id='tipovia' class='btn btn-default btn-sm dropdown-toggle' required>
	<option value='' selected></option>"; 
		while (oci_fetch($stid)) {
        	$cod_tipovia = oci_result($stid, 'ID_TIPO_VIA') ;	
			$des_tipovia = oci_result($stid, 'DESC_TIPO_VIA') ;
			if($cod_tipovia == $tipovia) echo "<option value='$cod_tipovia' selected>$des_tipovia</option>\n";
			else echo "<option value='$cod_tipovia'>$des_tipovia</option>\n";
			
        }oci_free_statement($stid);
		
		echo "</select>";			
?>

