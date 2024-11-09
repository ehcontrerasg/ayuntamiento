<?php
ini_set('memory_limit', '250M');
$agno = $_GET['agno'];
$mes = $_GET['mes'];
$nomrepo = $_GET['nomrepo'];
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=reporte excel.xls");
header("Pragma: no-cache");
header("Expires: 0");
echo $_POST['datos_a_enviar'];
?>