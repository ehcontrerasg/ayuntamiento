<?php	
	/************************************************/
	/* PROGRAMA PARA CREAR EL MENU - FLUIDOS        */
	/*  AGUAZUL - BOGOTA                            */
	/* CREADO POR :  JESUS GUTIERREZ ORTIZ          */
	/* FECHA DE CREACION : 30/04/2008               */
	/************************************************/
	session_start();
	include_once ('../../../include.php');
	$loguser = $_SESSION['usuario'];
	$passuser = $_SESSION['contrasena'];
	$coduser = $_SESSION['codigo']; 
	$nomuser = $_SESSION['nombre'];
	
	$Cnn = new OracleConn(UserGeneral, PassGeneral);
	$link = $Cnn->link;
		
	echo "var MENU_ITEMS = [";									
	$sql = "SELECT M.ID_MENU, M.DESC_MENU
	FROM SGC_TP_MENUS M, SGC_TP_PERFILES P 
	WHERE M.ID_MENU = P.ID_MENU AND M.ID_PADRE = 0 
	AND P.ID_USUARIO = '$coduser' AND M.ACTIVO = 'S' 
	AND (M.ID_MODULO = 2) ORDER BY 1";
	$stid = oci_parse($link, $sql);
	oci_execute($stid, OCI_DEFAULT);
	while (oci_fetch($stid)) {
		$cod_menu = oci_result($stid, 'ID_MENU');
		$des_menu = oci_result($stid, 'DESC_MENU');
		echo "['".$des_menu."','', {'tw' : 'content'},";				
		$sql2 = "SELECT DISTINCT M.DESC_MENU, M.URL, M.ORDEN 
		FROM SGC_TP_MENUS M, SGC_TP_PERFILES P  
		WHERE M.URL IS NOT NULL AND M.ID_PADRE = $cod_menu AND  P.ID_MENU=M.ID_MENU AND P.ID_USUARIO='$coduser'
		ORDER BY ORDEN ASC";
		$stida = oci_parse($link, $sql2);
		oci_execute($stida, OCI_DEFAULT);		    
		$entra = 0;
		while (oci_fetch($stida)){
			$detalle = oci_result($stida, 'DESC_MENU');
			$url = oci_result($stida, 'URL');
			echo "['".$detalle."','".$url."', {'tw' : 'jobFrame'}],";
			$entra = 1;
			unset($detalle, $url);
		}oci_free_statement($stida);
		if($entra == 1)echo "],";
			unset($cod_menu, $des_menu);
  	}oci_free_statement($stid);
	echo utf8_encode("['MENÚ PRINCIPAL','../../../index2.php/', {'tw' : '_top'}],");
	echo "['SALIR','../../../index.php', {'tw' : '_top'}],";
	echo "];";
?>