<?php
//header('Cache-Control: no-cache, must-revalidate');
//header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
//header('Content-type: application/json');
/*if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {*/

session_start();
echo (json_encode($_SESSION));
/*}else{
$resp = array('MSG' => "Solo puede acceder a esta data desde una peticion ajax autorizada");
echo(json_encode($resp));
}*/
//var_dump($_SERVER);
