<?php 
$tipo = $_POST['tip'];
session_start();

/*switch ('formData') {
	case 'value':
		if (is_uploaded_file($_FILES['archivo_fls']['tmp_name']))
		{
		$proyecto
		$nombreDirectorio = "../pdf/caasd";
		$nombreFichero = $_FILES['archivo_fls']['name'];
		 
		$nombreCompleto = $nombreDirectorio . $nombreFichero;
		 
		if (is_file($nombreCompleto))
		 {
		 //$idUnico = time();
		 $nombreFichero = $cod . "(" . $nombreFichero .")";
		 }
		 
		move_uploaded_file($_FILES['archivo_fls']['tmp_name'], $nombreDirectorio.$nombreFichero);
		 
		}
		 
		else
		 print ("No se ha podido subir el fichero");

		$arc = $nombreDirectorio.$nombreFichero; 
		break;
}

<?php */
$tipo = $_POST['tip'];
session_start();
switch ($tipo) {
	case 'IngDocArc':
		include_once'../clases/class.archivo.php';
	    $Documento = new Documento();
		$cod=$_POST['ingCodDoc'];
		settype($cod, integer);

	    $codArc=$_POST['codArc'];
	    settype($codArc, integer);

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
	   // echo $_FILES['archivo_fls']['name'];
	    //var_dump($_FILES);

		/*CODIGO PARA SUBIR ARCHIVO*/

		//$carpeta = "../pdf/".$pro.'/'.$cod;
		if ($pro=='SD') {
			$nombreDirectorio = "../pdf/SD/". $cod . "/";
		}else{
			$nombreDirectorio = "../pdf/BC/". $cod . "/";
		}
		if (!file_exists($carpeta)) 
		{
			mkdir($nombreDirectorio, 0777, true);
				
		}

		if (is_uploaded_file($_FILES['archivo_fls']['tmp_name']))
		{
			//$nombreDirectorio = "../pdf/". $cod . "/";
			$nombreFichero = $_FILES['archivo_fls']['name'];

			$nombreCompleto = $nombreDirectorio . $nombreFichero;			

				if (is_file($nombreCompleto))
				 {
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
				
				 } 
			move_uploaded_file($_FILES['archivo_fls']['tmp_name'],$nombreDirectorio.$nombreFichero);
		 
		} else
		 echo ("No se ha podido subir el fichero");

		$arc = $nombreDirectorio.$nombreFichero; 
		

	 	$result = $Documento->setDoc($cod,$codArc,$dep,$doc,$pro,$fDoc,$usr,$arc,$obs);
	    
	    if($result){
	    	 echo 'true';
	    	 echo $arc;
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

/*
$target_dir = "../pdf/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Lo sentimos, el archivo no existe";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "pdf") {
    echo "Lo sentimos, este no es un archivo PDF.";
    $uploadOk = 0;
} 

else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

*/
?>



 ?>
