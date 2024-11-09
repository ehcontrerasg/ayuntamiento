<?php
// Array que vincula los IDs de los selects declarados en el HTML con el nombre de la tabla donde se encuentra su contenido
$listadoSelects=array(
"proyecto"=>"lista_proyecto",
"periodo"=>"lista_periodo",
"zona"=>"lista_zona"
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
	if($opcionSeleccionada) return true;
	//else return true;
}

$selectDestino=$_GET["select"]; $opcionSeleccionada=$_GET["opcion"];


if(validaSelect($selectDestino) && validaOpcion($opcionSeleccionada))
{
	//$tabla=$listadoSelects[$selectDestino];
	include_once ('../../include.php');
	$Cnn = new OracleConn(UserGeneral, PassGeneral);
	$link = $Cnn->link;
	
	if($selectDestino == "periodo"){
		$proyecto = $opcionSeleccionada;
		$sql = "SELECT PERIODO 
		FROM SGC_TT_REGISTRO_LECTURAS L, SGC_TP_ZONAS Z
		WHERE L.ID_ZONA = Z.ID_ZONA AND FECHA_LECTURA IS NULL AND Z.ID_PROYECTO = '$proyecto'
		GROUP BY PERIODO ORDER BY PERIODO DESC";
		
	}
	if($selectDestino == "zona"){
		$proyecto=$_POST['proyecto'];
		$zona = $opcionSeleccionada;
		$sql = "SELECT L.ID_ZONA
		FROM SGC_TT_REGISTRO_LECTURAS L, SGC_TP_ZONAS Z
		WHERE L.ID_ZONA = Z.ID_ZONA AND PERIODO = '$zona' --AND Z.ID_PROYECTO = '$proyecto' 
		GROUP BY L.ID_ZONA ORDER BY 1 ASC";
	}
	$stid = oci_parse($link, $sql);
	oci_execute($stid, OCI_DEFAULT);
	// Comienzo a imprimir el select
	echo "<select name='".$selectDestino."' id='".$selectDestino."' onChange='cargaContenido(this.id);' class='btn btn-default btn-sm dropdown-toggle' required>";
	echo "<option value='0'></option>";
	while (oci_fetch($stid)) {
		if($selectDestino == "periodo"){
			$cod_periodo = oci_result($stid, 'PERIODO') ;	
			if($cod_periodo == $periodo) echo "<option value='$cod_periodo' selected>$cod_periodo</option>\n";
			else echo "<option value='$cod_periodo'>$cod_periodo</option>\n";
		}
		if($selectDestino == "zona"){
			$cod_zona = oci_result($stid, 'ID_ZONA') ;	
			if($cod_zona == $zona) echo "<option value='$cod_zona' selected>$cod_zona</option>\n";
			else echo "<option value='$cod_zona'>$cod_zona</option>\n";
		}
	}oci_free_statement($stid);
	echo "</select>";
}


?>