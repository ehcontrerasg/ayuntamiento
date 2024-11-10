<?php 
include_once'../clases/class.archivo.php';
$tipo = $_POST['tip'];
session_start();
switch ($tipo) {
	case 'IdRegistro':
	    $Documento = new Documento();
	    $IdRegistro=$_POST['IdRegistro'];

	   	settype($IdRegistro, integer);  

	    $datos = $Documento->infoObs($IdRegistro);

	    oci_fetch_all($datos, $result);
	    echo json_encode($result);
		break;
	case 'codigo_inm':
			//include_once'../clases/class.archivo.php';
		    $Documento = new Documento();
		    $codigo_inm=$_POST['codigo_inm'];
		   	settype($codigo_inm, integer);  

		    $datos = $Documento->buscarPDF($codigo_inm);

		    oci_fetch_all($datos, $result);
		    echo json_encode($result);
		break;
	case 'existReg':
		$Documento = new Documento();
	    $codigo_inm=$_POST['codigo_inm'];
	   	settype($codigo_inm, 'integer');  

	    $datos = $Documento->existReg($codigo_inm);

	    oci_fetch_all($datos, $result);
	    if ($result['EXISTE'][0] > 0) {
	    	echo "true";
	    }else{
	    	echo "false";
	    }
	    //echo json_encode($result);
		break;
	/*case 'existReg':
		$Documento = new Documento();
	    $codigo_inm=$_POST['codigo_inm'];
	   	settype($codigo_inm, integer);  

	    $datos = $Documento->buscarPDF($codigo_inm);

	    oci_fetch_all($datos, $result);
	    echo json_encode($result);
		break;*/

}

?>

