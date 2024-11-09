<?php
session_start();
include_once '../include.php';
$coduser     = $_SESSION['codigo'];
$cod_sistema = ($_GET['cod_sistema']);
$periodoI    = ($_GET['periodo']);
$periodoF    = ($_GET['periodoF']);
// Establecemos la conexión
$Cnn  = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#E0E0E0">
  <table align="center" width="70%" cellspacing="1" cellpadding="0" bgcolor="#000000">
    <tr class="titulo" height="20" bgcolor="#CCCCCC">
      <th>FOTOGRAFIAS INMUEBLE <?php echo $cod_sistema ?></th>
    </tr>
    <?php
/*consulta de fotos del predio en particular*/
if (is_null($periodoF)) {
    $sql = "SELECT URL_FOTO FROM SGC_TT_FOTOS_MANTENIMIENTO WHERE ID_INMUEBLE = $cod_sistema AND ID_PERIODO = $periodoI";
} else {
    $sql = "SELECT URL_FOTO FROM SGC_TT_FOTOS_MANTENIMIENTO WHERE ID_INMUEBLE = $cod_sistema AND ID_PERIODO BETWEEN $periodoI AND $periodoF";
}
$stid = oci_parse($link, $sql);
oci_execute($stid, OCI_DEFAULT);
//echo $sql."<br>";
while (oci_fetch($stid)) {
    $url_foto = oci_result($stid, 'URL_FOTO');
    $url_foto = substr($url_foto, 3);
    // echo($url_foto);
    ?>
    <tr class="titulo" bgcolor="#DCDCDA">
      <td align="center" ><img src="../../webservice/fotos_sgc/<?echo $url_foto; ?>"></td>
    </tr>
    <?
}
oci_free_statement($stid);
?>
  </table>
</body>
</html>