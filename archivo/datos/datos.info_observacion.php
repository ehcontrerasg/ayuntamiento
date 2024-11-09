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
    case 'IdRegistroCont':
        $Documento = new Documento();
        $IdRegistro=$_POST['IdRegistro'];

        settype($IdRegistro, integer);

        $datos = $Documento->infoObsCont($IdRegistro);

        oci_fetch_all($datos, $result);
        echo json_encode($result);
        break;
    case 'IdComunicacion':
        $Documento = new Documento();
        $IdRegistro=$_POST['IdRegistro'];

        settype($IdRegistro, integer);

        $datos = $Documento->infoObsComunicacion($IdRegistro);

        oci_fetch_all($datos, $result);
        echo json_encode($result);
        break;
    case 'IdEntradas':
        $Documento = new Documento();
        $IdRegistro=$_POST['IdRegistro'];

        settype($IdRegistro, integer);

        $datos = $Documento->infoObsEntradas($IdRegistro);

        oci_fetch_all($datos, $result);
        echo json_encode($result);
        break;
    case 'IdFacturas':
        $Documento = new Documento();
        $IdRegistro=$_POST['IdRegistro'];

        settype($IdRegistro, integer);

        $datos = $Documento->infoObsFacturas($IdRegistro);

        oci_fetch_all($datos, $result);
        echo json_encode($result);
        break;
    case 'IdNotas':
        $Documento = new Documento();
        $IdRegistro=$_POST['IdRegistro'];

        settype($IdRegistro, integer);

        $datos = $Documento->infoObsNotas($IdRegistro);

        oci_fetch_all($datos, $result);
        echo json_encode($result);
        break;
    case 'IdPagos':
        $Documento = new Documento();
        $IdRegistro=$_POST['IdRegistro'];

        settype($IdRegistro, integer);

        $datos = $Documento->infoObsPagos($IdRegistro);

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
    case 'codigo_doc':
        //include_once'../clases/class.archivo.php';
        $Documento = new Documento();
        $codigo_doc=$_POST['codigo_doc'];
        settype($codigo_inm, integer);

        $datos = $Documento->buscarPDFCont($codigo_doc);

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

