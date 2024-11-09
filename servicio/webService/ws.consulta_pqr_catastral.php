<?php 
	/*************************************
	*
	*	@Author : Allendy Valdez Pillier
	*	@Fecha  : 20/02/2017
	*
	*************************************/

	//include('../../destruye_sesion.php');
	//pasamos variables por post
	session_start();
	$_SESSION['tiempo']=time();
	$coduser = $_SESSION['codigo']; //253877
	
	/*$proyecto = 'SD'
	$nom_cliente = 'RA';
	$codigo_pqr = '25';
	$doc_cliente = '';*/
	
	switch ($_POST['caso']) {
		case 'bsqPqr':
			require'../clases/classPqrs.php';
			$classPqrs = new PQRs();
			$proyecto = $_POST['proyecto'];
			$nom_cliente = $_POST['nom_cliente'];
			$codigo_pqr = $_POST['codigo_pqr'];
			$doc_cliente = $_POST['doc_cliente'];
			$tipo_sol = $_POST['tipo_sol'];
			echo(json_encode($classPqrs->getSolCerradas($proyecto, $nom_cliente, $codigo_pqr, $doc_cliente,$tipo_sol)));
			break;
		case 'proyecto':
			require'../../clases/class.proyecto.php';
			$Proyecto = new Proyecto();
			$lstProyecto = $Proyecto->obtenerProyecto($coduser);
			oci_fetch_all($lstProyecto, $result);
			echo(json_encode($result));
			break;

        case 'tipoSol':
            require'../../clases/class.solicitudes.php';
            $Proyecto = new Solicitudes();
            $lstProyecto = $Proyecto->getTipoSolCat();
            oci_fetch_all($lstProyecto, $result);
            echo(json_encode($result));
            break;
		default:
			# code...
			break;
	}
	

	
 ?>