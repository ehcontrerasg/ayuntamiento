<?php 
	/*header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');*/
	/*************************************
	*
	*	@Author : Luis Miguel Alcántara
	*	@Fecha  : 23/03/2017
	*
	*************************************/

	session_start();
	$_SESSION['tiempo']=time();
	require'../clases/class.archivo.php';
	$Documento = new Documento();
	echo($Documento->getArchivos(null));



	
 ?>