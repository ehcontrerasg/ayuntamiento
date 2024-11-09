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
    $sql = "SELECT M.URL_FOTO, U.LOGIN, TO_CHAR(A.FECHA_FIN,'DD/MM/YYYY HH24:MI') FECHA_FIN
            FROM SGC_TT_FOTOS_MANTENIMIENTO M, SGC_TT_ASIGNACION A, SGC_TT_USUARIOS U
            WHERE A.ID_INMUEBLE = M.ID_INMUEBLE
            AND  TO_CHAR(A.FECHA_FIN,'DDMMYYYY') = TO_CHAR(M.FECHA,'DDMMYYYY')
            AND U.ID_USUARIO = A.ID_OPERARIO
            AND M.ID_INMUEBLE = $cod_sistema 
            AND M.ID_PERIODO = $periodoI";
} else {
    $periodoI-=1;
    $periodoF+=1;
    $sql = "SELECT M.URL_FOTO, U.LOGIN, TO_CHAR(A.FECHA_FIN,'DD/MM/YYYY HH24:MI') FECHA_FIN
            FROM SGC_TT_FOTOS_MANTENIMIENTO M, SGC_TT_ASIGNACION A, SGC_TT_USUARIOS U
            WHERE A.ID_INMUEBLE = M.ID_INMUEBLE
            AND  TO_CHAR(A.FECHA_FIN,'DDMMYYYY') = TO_CHAR(M.FECHA,'DDMMYYYY')
            AND U.ID_USUARIO = A.ID_OPERARIO
            AND M.ID_INMUEBLE = $cod_sistema 
            AND M.ID_PERIODO BETWEEN $periodoI AND $periodoF";
}
$stid = oci_parse($link, $sql);
oci_execute($stid, OCI_DEFAULT);
//echo $sql."<br>";
while (oci_fetch($stid)) {
    $url_foto = oci_result($stid, 'URL_FOTO');
    $fecha_foto = oci_result($stid, 'FECHA_FIN');
    $usuario_m = oci_result($stid, 'LOGIN');
    $url_foto = substr($url_foto, 3);
    // echo($url_foto);
    ?>
    <tr class="titulo" bgcolor="#DCDCDA">
        <td>
            <h4 class="panel-heading" style="background-color:#336699;border-color:#336699"><center> <?php echo $cod_sistema.' /-/ '.$fecha_foto.' /-/ '.$usuario_m ?></center></h4>
        </td>
    </tr>
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