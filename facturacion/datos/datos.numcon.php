<?php
include_once ('../../include.php');
//$proyecto=$_GET['proyecto'];

// Here, we will get user input data and trim it, if any space in that user input data
$user_input = trim($_REQUEST['term']);

// Define two array, one is to store output data and other is for display
$display_json = array();
$json_arr = array();
 
//Conectamos con la base de datos	
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;

$user_input2=strtoupper($user_input);

       	$sql = "SELECT ID_CONTRATO
	   	FROM SGC_TT_CONTRATOS
		WHERE ID_CONTRATO LIKE '%$user_input2%' ";
		//echo $sql;
		$stid = oci_parse($link, $sql);
		oci_execute($stid, OCI_DEFAULT);
		while (oci_fetch($stid)) {
        
			$json_arr["id"] = oci_result($stid, 'ID_CONTRATO') ;
			$json_arr["value"] = oci_result($stid, 'ID_CONTRATO');
			$json_arr["label"] =  oci_result($stid, 'ID_CONTRATO');
			array_push($display_json, $json_arr);
        	
        }oci_free_statement($stid);
		
			
$jsonWrite = json_encode($display_json); //encode that search data
print $jsonWrite;

		
?>
