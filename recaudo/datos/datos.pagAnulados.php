<?php 
	require_once '../clases/class.pagAnulados.php';
	/*$txtCodSistema = $_GET['txtCodSistema'];
	$dteFechPag = $_GET['dteFechPag'];
	$dteFechAnula = $_GET['dteFechAnula'];*/

	$pagAnulados = new pagAnulados();
	/*$pagAnulados->cod_inm = $txtCodSistema;
	$pagAnulados->fech_pago = $dteFechPag;
	$pagAnulados->fech_rev = $dteFechAnula;*/
	//$badChar = ['\\', '/']
	function formatFecha($str_fecha) {
		$date = date_create($str_fecha);
		$fecha = date_format($date,"Y-m-d");
		return $fecha;
	}
	

	$orclResp = $pagAnulados->_consultar($pagAnulados->_getQuery(1));
	oci_fetch_all($orclResp, $datos);
	$row = count($datos['FECHA_PAGO']);
	$result = '{"data":[';
        for ($i=0; $i<$row; $i++) { 
            $fecha_pago = formatFecha($datos['FECHA_PAGO'][$i]);
            $fecha_rev = formatFecha($datos['FECHA_REV'][$i]);
        	//echo $i.' : ';ucwords
        	//echo $motivo_rev = preg_replace("[\n|\r|\n\r|\]", "", $datos['MOTIVO_REV'][$i]);
        	//echo "<br>";*/
        	$motivo = ucfirst(strtolower(preg_replace("[\n|\r|\n\r]", "", $datos['MOTIVO_REV'][$i])));
            $id_usuario = ucwords(strtolower($datos['ID_USUARIO'][$i]));
            $usr_rev = ucwords(strtolower($datos['USR_REV'][$i]));
            $importe = number_format($datos['IMPORTE'][$i], 2);

            $result .= "{";
            	$result .= ' "ID_Pago"		: "'.$datos['ID_PAGO'][$i].'"';
                $result .= ',"INM_CODIGO"	: "'.$datos['INM_CODIGO'][$i].'"';
                $result .= ',"FECHA_PAGO"	: "'.$fecha_pago.'"';
                $result .= ',"IMPORTE"      : "'.$importe.'"';
                $result .= ',"ID_USUARIO"	: "'.$id_usuario.'"';
                $result .= ',"FECHA_REV"   	: "'.$fecha_rev.'"';
                $result .= ',"MOTIVO_REV"	: "'.$motivo.'"';
                $result .= ',"USR_REV"		: "'.$usr_rev.'"';
            if ($i != $row-1) {
                $result .= '},';
            }else{
                $result .= '}';
            }
        }
    $result .= ']}' ;
    echo $result;
 ?>