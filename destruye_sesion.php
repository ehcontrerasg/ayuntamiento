 <?php
/*$_SESSION["codigo"]=""; 
header("Location: index.php");*/
$segundos = 1800; //si pasa este tiempo se detecta la inactividad del usuario en el sitio 
if(($_SESSION['tiempo']+$segundos) < time()) {
	session_destroy();
   	echo'<script type="text/javascript">alert("Su sesion ha expirado por inactividad'; 
   //	echo', vuelva a logearse para continuar");top.location.replace("../../index.php");</script>';
}else 
   $_SESSION['tiempo']=time(); 
?>