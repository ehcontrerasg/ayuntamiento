<?php
session_start();
include_once ('../include.php');
$coduser = $_SESSION['codigo'];
$cod_sistema = ($_GET['cod_sistema']);
$periodo = ($_GET['periodo']);
// Establecemos la conexión
$Cnn = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;

 /*saco todos los datos mas la lectura actual*/
$sql = "DELETE FROM SGC_TT_MANTENIMIENTO";
$stid = oci_parse($link, $query);
	$result = oci_execute($stid);
	oci_free_statement($stid);
   
  
   
?>
