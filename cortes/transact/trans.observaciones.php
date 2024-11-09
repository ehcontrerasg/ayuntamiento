<?php
	require_once '../clases/class.cortes.php';
	session_start();
	$caso = $_POST['caso'];
	$cortes = new cortes();

	switch ($caso) {
		case 1:
			//$cortes->user_creacion =  addslashes(trim($_SESSION["usuario"]));
			//echo "usuaario sesion: ".$_SESSION["usuario"];
			$result = $cortes->execQuery($cortes->getQuery(4));
			//oci_fetch_all($result, $user_creacion);
		//	$cortes->user_creacion = $user_creacion['ID_USUARIO'][0];
           $cortes->user_creacion = addslashes(trim($_SESSION["codigo"]));
			$cortes->asunto =  addslashes(trim($_POST['asunto']));
			$cortes->cod_observacion = addslashes(trim($_POST['cod_observacion']));
			$cortes->desc_opservacion = addslashes(trim( $_POST['desc_opservacion']));
			$cortes->cod_inm =  addslashes(trim($_POST['cod_inm']));
			$result = $cortes->execProcedure(1);
			if (is_null($cortes->cod_error)) {
				echo 'true';				
			}else{
				echo'{ 
						"codigo"  : "'.$cortes->cod_error.'",
						"mensaje" : "'.$cortes->vmsj_error.'"
					}';
			}
			break;
	}
?>