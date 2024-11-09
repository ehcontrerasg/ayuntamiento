<?
/************************************************/
/*  AGUAZUL - BOGOTA                            */
/* FECHA DE CREACION : 13/05/2008               */
/************************************************/
session_start();
require_once("../Funciones/funciones.php");
$userora = $_SESSION["userora"];$passora = $_SESSION["passora"];
$cod_apli = $_SESSION["cod_apli"];$modulo = $_SESSION["modulo"];
$ingreso = $_SESSION["ingreso"];
// Establecemos la conexión
/*$ingreso = "tabla";
$modulo = 1;
$userora = "FELIPE";*/
if($ingreso == "tabla"){$user = $user_local;$pass = $pass_local;}
else {$user = $userora;$pass = $passora;}
if (!$conn = conectar($user,$pass)) {
?>
<script language="JavaScript" type="text/javascript">
<!--
  alert("Nombre de usuario o contraseña incorrecta !!\n Por favor vuelva a intentarlo");
  this.document.location.replace("Funciones/blank.php");
//-->
</script>
<?
}
//Fin de conexion

//header("Content-type: text/javascript");
echo "var MENU_ITEMS = [";
$sql = "SELECT distinct menu FROM menu WHERE usuario = '$userora' AND cod_modulo = '$modulo'";
$cursor = ora_open ($conn);$result = ora_parse ($cursor, $sql);$result = ora_exec ($cursor);
while(ora_fetch_into($cursor,&$value)) 
  {
  echo "['".$value[0]."','', {'tw' : 'content'},";
  $sql = "SELECT submenu, direccion FROM menu WHERE menu = '$value[0]' and usuario = '$userora' AND cod_modulo = '$modulo' ORDER BY submenu";
  $cursor1 = ora_open ($conn);$result = ora_parse ($cursor1, $sql);$result = ora_exec ($cursor1);
  $entra = 0;
  while(ora_fetch_into($cursor1,&$value1))
    {
	echo "['".$value1[0]."','".$value1[1]."', {'tw' : 'jobFrame'}],";
	$entra = 1;
	unset($value1);
	}
	if($entra == 1)echo "],";
  unset($value);
  }	
echo "['Salir','../', {'tw' : '_top'}],";
echo "];";
desconectar($conn);
?>