<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
include_once('../clases/class.conexion.php');
include_once('../clases/class.login.php');

$user = trim($_POST['username']);
$password = trim($_POST['password']);
global $URL;
$URL= $_POST["url"];
if ($user!=null and $password!=null) {
    $user = strtoupper(addslashes(trim($user)));
    $password = addslashes(trim($password));
    settype($user, 'string');
    settype($password, 'string');

    $Login = new Login($user, $password);

    oci_fetch_all($Login->data, $data);
    //var_dump($data);
    if ($Login->isUser($data)) {
        session_start();
        $Login->setSession($data);
        if(isset($URL))
        {   echo "Prueba URL:".$URL;
            header("location:".$URL);
        }
        else
        {

           // header("location:".$SERVER["REQUEST_URI"]);
            echo "Este es el link:".$_SERVER["REQUEST_URI"];
         //echo 'TRUE';
        }

    }else{
        include_once '../almacen/vista/vista.bienvenida.php';
    }
}

?>
<?php

/*

	include_once('../clases/class.usuario.php');
	$user = trim($_GET['user']);
	$password = trim($_GET['pass']);
	if ($user!=null and $password!=null) {
		$user = strtoupper(addslashes(trim($user)));
		$password = addslashes(trim($password));
		//echo 'holdfa';

		$Usuario = new Usuario();
		if ($dataUser = $Usuario->getUserPass('jgutierrez', 'campeon2012')) {
			//$dataUser;
			oci_fetch_all($dataUser, $data);
			$row = count($data['ID_USUARIO']);
			//echo $row;
			//var_dump($data);
			for ($i=0; $i < $row; $i++) {
				echo($data['ID_USUARIO'][$i]).',';
				//echo($data['PASS'][$i])." : ";
				echo(md5($data['PASS'][$i]).'<br>');
			}

			/*echo($data['NOM_USR'][0]).'<br>';
			echo($data['APE_USR'][0]).'<br>';


		}

	}//ID_USUARIO PASS

	/*$passmd5 = md5($password);
	echo $password .' : '.$passmd5.'<br>';
	echo "allendy".' : '.md5('allendymd5');
	if ($password != $passmd5) {
		echo '<br>no coinciden';
	}else{
		echo "los hash coinciden";
	}*/
/*
echo "<script>window.location.replace('./index2.php/');</script>";
       $_SESSION["usuario"] = $user;
       $_SESSION["contrasena"] = $pass;
       $_SESSION["codigo"] = $cod_usuario;
       $_SESSION["nombre"] = $nom_usuario." ".$ape_usuario;
*/


?>