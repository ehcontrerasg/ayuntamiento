<?php
/**
 * Created by PhpStorm.
 * User: Jesus Gutierrez
 * Date: 16/11/2019
 * Time: 11:33 AM
 */

	/*header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');*/


	session_start();
	$_SESSION['tiempo']=time();
	require'../clases/class.archivo.php';
	$Documento = new Documento();
	echo($Documento->getArchivosContables());




 ?>