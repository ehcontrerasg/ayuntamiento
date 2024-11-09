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

       	$sql = "SELECT DISTINCT ID_MOTIVO_REC, DESC_MOTIVO_REC
		FROM SGC_TP_MOTIVO_RECLAMOS
		WHERE ID_MOTIVO_REC LIKE '$user_input2%' 
		ORDER BY ID_MOTIVO_REC";
		//echo $sql;
		$stid = oci_parse($link, $sql);
		oci_execute($stid, OCI_DEFAULT);
		while (oci_fetch($stid)) {
        
			$json_arr["id"] = oci_result($stid, 'ID_MOTIVO_REC') ;
			$json_arr["value"] = oci_result($stid, 'DESC_MOTIVO_REC');
			$json_arr["label"] =  oci_result($stid, 'DESC_MOTIVO_REC');
			array_push($display_json, $json_arr);
        	
        }oci_free_statement($stid);
		
			
$jsonWrite = json_encode($display_json); //encode that search data
print $jsonWrite;

		
?>

