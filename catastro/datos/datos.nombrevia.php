<?php
include '../clases/class.nombrevia.php';
include '../clases/class.urbanizacion.php';

// Here, we will get user input data and trim it, if any space in that user input data
$user_input = trim($_REQUEST['term']);
$tipovia=$_GET["tipovia"];
$proyecto=$_GET["proyecto"];
//$tipovia=1;
// Define two array, one is to store output data and other is for display
$display_json = array();
$json_arr = array();
 


			$userinput2=strtoupper($user_input);
        $p=new NombreVia();
        $stid = $p->obtenernvia2($proyecto,$tipovia,$userinput2);
        while (oci_fetch($stid)) {
		
			$json_arr["id"] = oci_result($stid, 'DESC_NOM_VIA') ;
			$json_arr["value"] = oci_result($stid, 'DESC_NOM_VIA');
			$json_arr["label"] =  oci_result($stid, 'DESC_NOM_VIA');
			array_push($display_json, $json_arr);
        	
        }oci_free_statement($stid);
        $json_arr["value"] = 'Agregar Nombre Via';
        $json_arr["label"] = 'Agregar Nombre Via';
        array_push($display_json, $json_arr);
		
			
$jsonWrite = json_encode($display_json); //encode that search data
print $jsonWrite;

		

?>
