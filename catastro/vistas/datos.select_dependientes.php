<?php

// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
$listadoSelects=array(
"proyecto"=>"lista_proyecto",
"urbaniza"=>"lista_urbaniza"
);

function validaSelect($selectDestino)
{
	// Se valida que el select enviado via GET exista
	global $listadoSelects;
	if(isset($listadoSelects[$selectDestino])) return true;
	else return false;
}

function validaOpcion($opcionSeleccionada)
{
	// Se valida que la opcion seleccionada por el usuario en el select tenga un valor numerico
	if(!is_numeric($opcionSeleccionada))return true;
	else return false;
}

$selectDestino=$_GET["select"]; $opcionSeleccionada=$_GET["opcion"];

if(validaSelect($selectDestino) && validaOpcion($opcionSeleccionada))
{
	//$tabla=$listadoSelects[$selectDestino];
	//Conectamos con la base de datos
	$Cnn = new OracleConn(UserGeneral, PassGeneral);
	$link = $Cnn->link;
	//include 'conexion.php';
	//conectar();
	//$consulta=mysql_query("SELECT id, opcion FROM $tabla WHERE relacion='$opcionSeleccionada'") or die(mysql_error());
	//desconectar();
	
	$sql = "SELECT ID_URBANIZACION, DESC_URBANIZACION
	FROM SGC_TP_URBANIZACIONES WHERE ID_PROYECTO = '$opcionSeleccionada'
	ORDER BY DESC_URBANIZACION";
	$stid = oci_parse($link, $sql);
	oci_execute($stid, OCI_DEFAULT);
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id)'>";
	echo "<option value='0'>Elige</option>";
	while (oci_fetch($stid)) {
												$id_urba = oci_result($stid, 'ID_URBANIZACION') ;
												$des_urba = oci_result($stid, 'DESC_URBANIZACION') ;	
												if($id_urba == $urbaniza) echo "<option value='$id_urba' selected>$des_urba</option>\n";
												else echo "<option value='$id_urba'>$des_urba</option>\n";
												//echo "<option value='".$id_urba."'>".$des_urba."</option>";
											}oci_free_statement($stid);
	
	
	/*while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
	}*/			
	echo "</select>";
}
?>