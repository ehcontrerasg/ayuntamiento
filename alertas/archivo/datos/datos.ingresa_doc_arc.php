<?php 
$tipo = $_POST['tip'];
session_start();
switch ($tipo) {
case 'IngDocArc':
		include_once'../clases/class.archivo.php';
	    $Documento = new Documento();
		$cod=$_POST['ingCodDoc'];
		//settype($cod, integer);

	    $codArc=$_POST['codArc'];
	    settype($codArc, string);

	    $dep=$_POST['departamento'];
	    settype($dep, integer);

	    $doc=$_POST['documento'];
	    settype($doc, integer);

	    $pro=$_POST['proyecto'];
	    settype($pro, string);

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
		if ($pro=='SD') {
			$nombreDirectorio = "../pdf/SD/". $cod . "/";
		}else{
			$nombreDirectorio = "../pdf/BC/". $cod . "/";
		}
		if (!file_exists($nombreDirectorio)) 
		{
			//$oldmask = umask(0);
			//exec("sudo mkdir pdf/$nombreDirectorio 0777 true");
			mkdir($nombreDirectorio, 0777, true);
			//umask($oldmask);			
		}

		if (is_uploaded_file($_FILES['archivo_fls']['tmp_name']))
		{	
			$pro = strtoupper($pro);
			
			$nombreFichero = $_FILES['archivo_fls']['name'];
			

				 	switch ($doc) {
				 	case '1':
				 		 $nombreFichero = $cod . "(CAMBIOS)" . ".pdf";
				 		break;
				 	case '2':
				 		$nombreFichero = $cod . "(CARTA)" . ".pdf";
				 		break;
				 	case '3':
				 		$nombreFichero = $cod . "(CIRCULAR)" . ".pdf";
				 		break;
				 	case '4':
				 		$nombreFichero = $cod . "(CONTRATO)" . ".pdf";
				 		break;
				 	case '5':
				 		$nombreFichero = $cod . "(MEMO)" . ".pdf";
				 		break;
				 	case '6':
				 		$nombreFichero = $cod . "(RECLAMO)" . ".pdf";
				 		break;
				 	case '7':
				 		$nombreFichero = $cod . "(SOLICITUD)" . ".pdf";
				 		break;
				 	}

		move_uploaded_file($_FILES['archivo_fls']['tmp_name'],($nombreDirectorio.$nombreFichero));
		 
		} 

		$arc = $nombreDirectorio.$nombreFichero; 
	 	$result = $Documento->setDoc($cod,$codArc,$dep,$doc,$pro,$fDoc,$usr,$arc,$obs);
	    
	    if($result){
	    	 echo 'true';
	      //echo  $miArray=array("error"=>$Documento->getMesrror(), "cod"=>$Documento->getCoderror(),"res"=>"true");
	    }else{
	       //$miArray=array("error"=>$Documento->getMesrror(), "cod"=>$Documento->getCoderror(),"res"=>"false");
	    	echo $result;
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
