<?php
	header ("content-type: text/xml");
	session_start();
	
	/********************************************************************/
	/*  SOFTWARE DE GESTION COMERCIAL ACEA DOMINICANA       	        */
	/*  ACEA DOMINICANA - REPUBLICA DOMINICANA							*/
	/*  CREADO POR JESUS GUTIERREZ ORTIZ								*/
	/*  FECHA CREACION 23/09/2014										*/
	/********************************************************************/
	include_once ('../../../include.php');
	$loguser = $_SESSION['usuario'];
	$passuser = $_SESSION['contrasena'];
	$coduser = $_SESSION['codigo']; 
	$nomuser = $_SESSION['nombre'];
	
	// Establecemos la conexión
	$Cnn = new OracleConn(UserGeneral, PassGeneral);
	$link = $Cnn->link;
	 
 	function getNode($link, $cod_menu, $coduser) {
		
		
    	$sql = "SELECT M.ID_MENU, M.DESC_MENU, M.URL 
		FROM SGC_TP_MENUS M, SGC_TP_PERFILES P 
		WHERE M.ID_MENU = P.ID_MENU AND M.ID_PADRE = $cod_menu 
		AND P.ID_USUARIO = '$coduser' AND M.ACTIVO = 'S' 
		AND (M.ID_MODULO = 1) AND M.ID_MENU >= 1
		ORDER BY 1";	
		//echo $sql."<br>";
				
  		$stid = oci_parse($link, $sql);
		oci_execute($stid, OCI_DEFAULT);
		while (oci_fetch($stid)) {
			$cod_menu = oci_result($stid, 'ID_MENU');
			$detalle = oci_result($stid, 'DESC_MENU');
			$url = oci_result($stid, 'URL');
			//$detalle = utf8_decode($detalle);
			//echo "[$detalle]";
	 		if (strlen($url) > 0) @$xml .= "<link label=\"$detalle\" href=\"$url\" target=\"jobFrame\"/>\n";
      		else {
	    		@$xml .= "<node label=\"$detalle\" id=\"$cod_menu\">\n";
	 			$xml .= getNode($link, $cod_menu, $coduser) ."</node>\n";
			}
   		}
   		oci_free_statement($stid);  
		return $xml;
  	}
  	
  	
  	
  	
  	
	$salida = 'Cerrar Sesión';
	if ($link) {
    	echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
      	echo "<node id=\"tree\" label=\"~Root Folder\">\n";
      	echo getNode($link, 0, $coduser);  
	  	echo "<link label=\"$salida\" href=\"../../../index.php\" target=\"_top\"/>";
	
      	echo "</node>"; 
     	oci_close($link);
	} 

	
?>

