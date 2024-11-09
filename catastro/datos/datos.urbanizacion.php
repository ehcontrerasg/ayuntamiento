<?php

include '../clases/class.nombrevia.php';
include '../clases/class.urbanizacion.php';
$proy=$_GET["proy"];

// Here, we will get user input data and trim it, if any space in that user input data
$user_input = trim($_REQUEST['term']);

// Define two array, one is to store output data and other is for display
$display_json = array();
$json_arr = array();
 


		$userinput2=strtoupper($user_input);
        $p=new Urbanizacion();
        $stid = $p->obtenerurbanizacion2($proy, $userinput2);
        while (oci_fetch($stid)) {
		
			$json_arr["id"] = oci_result($stid, 'CONSEC_URB') ;
			$json_arr["value"] = oci_result($stid, 'CONSEC_URB');
			$json_arr["label"] =  oci_result($stid, 'DESC_URBANIZACION');
			array_push($display_json, $json_arr);
        	
        }oci_free_statement($stid);
        $json_arr["value"] = 'Agregar Urbanizacion';
        $json_arr["label"] = 'Agregar Urbanizacion';
   

        array_push($display_json, $json_arr);
		
			
$jsonWrite = json_encode($display_json); //encode that search data
print $jsonWrite;
