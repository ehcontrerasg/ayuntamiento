<?php
$proyecto=$_GET['proyecto'];

// Here, we will get user input data and trim it, if any space in that user input data
$user_input = trim($_REQUEST['term']);

// Define two array, one is to store output data and other is for display
$display_json = array();
$json_arr = array();
 
//Conectamos con la base de datos	
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;

$user_input=strtoupper($user_input);

       $sql = "SELECT CODIGO, DESCRIPCION FROM SGC_TP_CONCEPTO_DIF
		WHERE CODIGO LIKE '$user_input%' ";
		//echo $sql;
		$stid = oci_parse($link, $sql);
		oci_execute($stid, OCI_DEFAULT);
		while (oci_fetch($stid)) {
		
        	$id = oci_result($stid, 'CODIGO') ;
			$value = oci_result($stid, 'CODIGO');
			$label = oci_result($stid, 'DESCRIPCION');
			$json_arr["id"] = $id;
			$json_arr["value"] = $value;
			$json_arr["label"] = $label;
			
			array_push($display_json, $json_arr);
        	
        }oci_free_statement($stid);
		
			
$jsonWrite = json_encode($display_json); //encode that search data
print $jsonWrite;

		

?>
