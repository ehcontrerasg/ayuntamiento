<?php 
$tipo = $_POST['tip'];
session_start();
switch ($tipo) {
	case 'ModDocArc':
		include_once'../clases/class.archivo.php';
	    $Documento = new Documento();
	    $IdRegistro=$_POST['IdRegistro'];
	    settype($IdRegistro, integer);

		$cod=$_POST['ingCodDoc'];
		settype($cod, integer);

	    $codArc=$_POST['codArc'];
	    //settype($codArc, string);

	    $dep=$_POST['departamento'];
	    settype($dep, integer);

	    $doc=$_POST['documento'];
	    settype($doc, integer);

	    $proye = $Documento->getProByINM($cod);
	    oci_fetch_all($proye, $proy);
	   	$pro = $proy['ID_PROYECTO'][0];
	    //$pro = 

	    $fDoc=$_POST['fechaDoc'];
	    settype($fDoc, string);

	    $usr= $_SESSION['codigo'];
	    settype($usr, string);

	    $arc=$_FILES['archivo_fls'];
	    settype($arc, string);

	    $obs=$_POST['observacion'];
	    settype($obs, string);

		/*CODIGO PARA SUBIR ARCHIVO*/
		//$nombreDirectorio = "../pdf/". $cod;
		$nombreFichero = $_FILES['archivo_fls']['name'];
		if ($pro=='SD') {
			$nombreDirectorio = "../pdf/SD/". $cod . "/";
		}else{
			$nombreDirectorio = "../pdf/BC/". $cod . "/";
		}
		if (!file_exists($nombreDirectorio)) 
		{
			mkdir($nombreDirectorio, 0777, true);
		}

		if (is_uploaded_file($_FILES['archivo_fls']['tmp_name']))
		{				
			//$_GLOBAL['nombreDirectorio' = "../pdf/". $cod . "/";		
		 	switch ($doc) {
			 	case '1':
			 		$nombreFichero = $cod . "(CAMBIOS)" ;
			 		break;
			 	case '2':
			 		$nombreFichero = $cod . "(CARTA)";
			 		break;
			 	case '3':
			 		$nombreFichero = $cod . "(CIRCULAR)";
			 		break;
			 	case '4':
			 		$nombreFichero = $cod . "(CONTRATO)";
			 		break;
			 	case '5':
			 		$nombreFichero = $cod . "(MEMO)";
			 		break;
			 	case '6':
			 		$nombreFichero = $cod . "(RECLAMO)";
			 		break;
			 	case '7':
			 		$nombreFichero = $cod . "(SOLICITUD)";
			 		break;
		 	}
			//ECHO $nombreDirectorio.$nombreFichero;
			// echo file_exists($nombreDirectorio.$nombreFichero);
			//echo file_exists($nombreDirectorio.$nombreFichero).'hola';
			$x = 1;
			
			if (file_exists($nombreDirectorio.$nombreFichero.'.pdf')) {
				//echo "existe";
				//echo $comp = file_exists($nombreDirectorio.$nombreFichero.$x);
				while(file_exists($nombreDirectorio.$nombreFichero.$x.'.pdf')) {
					$x++;
					//$comp = file_exists($nombreDirectorio.$nombreFichero.$x);
					//$nombreFichero .= "$x.pdf";
				}
				$nombreFichero .= "$x.pdf";
			}else{
				//echo "no existe";
				$nombreFichero .= '.pdf';
			}
			
			$subio = move_uploaded_file($_FILES['archivo_fls']['tmp_name'],$nombreDirectorio.$nombreFichero);
			if ($subio) {
				$arc = $nombreDirectorio.$nombreFichero;
				//echo($pro.'  '.'<br>');
			 	$result = $Documento->modDoc($IdRegistro,$cod,$codArc,$dep,$doc,$pro,$fDoc,$usr,$arc,$obs);
			    if($result){
			    	echo 'true';
			    	 
			      //echo  $miArray=array("error"=>$Documento->getMesrror(), "cod"=>$Documento->getCoderror(),"res"=>"true");
			    }else{
			       //$miArray=array("error"=>$Documento->getMesrror(), "cod"=>$Documento->getCoderror(),"res"=>"false");
			    	echo "false";
			    }	   		
			}
		 
		}
		break;
	case 'selAre':
		include_once '../../clases/class.areas.php';
	    $Area = new Area();
	    $datos = $Area->getAreas();
	    oci_fetch_all($datos, $result);
	    echo json_encode($result);
		break;
	case 'selDoc':
		include_once '../clases/class.archivo.php';
	    $Documento = new Documento();
	    $datos = $Documento->getDocumento();
	    oci_fetch_all($datos, $result);
	    echo json_encode($result);
		break;
	case 'selPro':
		include_once '../clases/class.archivo.php';
	    $Documento = new Documento();
	    $datos = $Documento->getProyecto();
	    oci_fetch_all($datos, $result);
	    echo json_encode($result);
		break;
}

?>