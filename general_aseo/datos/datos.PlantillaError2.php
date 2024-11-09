<?

include_once ('../../clases/class.PermisosURL.php');
include ("../../webServices/ws.login2.php");
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true):
 header("Location:".$GLOBALS['url']);
endif;
if ($verificarPermisos==false):
    $_SESSION['usuario']=null;
    header("Location:../../index.php");
endif; ?>


