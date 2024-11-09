
<?php
	//echo "hola";
	session_start();
	echo json_encode($_SESSION);
   /* $_SESSION['tiempo']=time();
    $hola = 1
    /*switch ($hola) {
    	case 1:
    		echo "hola";
    		break;
    	
    	default:
    		# code...
    		break;
    }
    /********************************************************************/
	/*  SOFTWARE DE GESTION COMERCIAL ACEA DOMINICANA       	        */
	/*  ACEA DOMINICANA - REPUBLICA DOMINICANA							*/
	/*  Allendy Valdez Pillier											*/
	/*  FECHA CREACION 31/03/2017										*/
	/********************************************************************/

    //require_once '../../include.php';
    

	/*switch ($_GET['caso']) {
		case 'login':
		/*echo "hola";
			$user = $_GET['txtUser'];
			$pass = $_GET['txtPass'];

			if (isset($user) != '' && isset($pass) != '')  {
				$user = addslashes(strtoupper($user)); 
				$pass = addslashes($pass);
				
				//$user=strtoupper($user); 
				$sql = 	"SELECT ID_USUARIO, NOM_USR, APE_USR FROM SGC_TT_USUARIOS WHERE LOGIN = '$user' AND PASS = '$pass' AND FEC_FIN IS NULL";
				//echo $sql;
				$datos = consultar($sql);
				var_dump($datos);
			}
			break;
		case 'logout':

			break;
		default:
			/*$_SESSION["codigo"]=""; 
			header("Location: index.php");*/
			/*$segundos = 1800; //si pasa este tiempo se detecta la inactividad del usuario en el sitio 
			if(($_SESSION['tiempo']+$segundos) < time()) {
				session_destroy();
			   	//	echo'<script type="text/javascript">alert("Su sesion ha expirado por inactividad'; 
			   	//echo', vuelva a logearse para continuar");top.location.replace("../../index.php");</script>';     
			}else {
			   $_SESSION['tiempo']=time(); 
			}
			break;
	}
	
	function consultar($sql) {
		$link = new OracleConn(UserGeneral, PassGeneral)->link;
		// Preparar la sentencia
		$parse = oci_parse($link, $sql);
		if (!$parse) {
		    $e = oci_error($this->_db);
		    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		// Realizar la lógica de la consulta
		$r = oci_execute($parse);
		if (!$r) {
		    $e = oci_error($parse);
		    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		//$result = oci_fetch_array($parse);
		oci_fetch_all($parse, $result);
		oci_close($this->_db);
		oci_free_statement($parse);
		return $result;
	}


		/*if (datos) {
			# code...
		}
		else{
	        echo "<script>window.location.replace('./index2.php/');</script>";
			$_SESSION["usuario"] = $user;
			$_SESSION["contrasena"] = $pass;
			$_SESSION["codigo"] = $cod_usuario;	
			$_SESSION["nombre"] = $nom_usuario." ".$ape_usuario;
		}
	*/
	
 ?>