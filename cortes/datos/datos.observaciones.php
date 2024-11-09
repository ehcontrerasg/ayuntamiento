<?php 
	require_once '../clases/class.cortes.php';
	$cortes = new cortes();
	foreach ($_GET as $key => $value) {
		$cortes->{$key} = $value;
	}
	$data = $cortes->execQuery($cortes->getQuery($_GET['caso']));
	//oci_fetch_all($result, $datos);
	oci_fetch_all($data, $datos);
	switch ($_GET['caso']) {
        case 1:
            //var_dump($datos);
            // echo($caso);
            if (oci_num_rows($cortes->parse)) {
                $row = count($datos['CONSECUTIVO']);
                $result = '{"data":[';
                    for ($i=0; $i<$row; $i++) { 
                        $result .= "{";
                            $result .= ' "CONSECUTIVO" : "'. addslashes(preg_replace("[\n|\r|\n\r|\t]", '', $datos['CONSECUTIVO'][$i])).'"';
                            $result .= ',"CODIGO_OBS"  : "'. addslashes(preg_replace("[\n|\r|\n\r|\t]", '', $datos['CODIGO_OBS'][$i])).'"';
                            $result .= ',"ASUNTO"      : "'. addslashes(preg_replace("[\n|\r|\n\r|\t]", '', $datos['ASUNTO'][$i])).'"';
                            $result .= ',"DESCRIPCION" : "'. addslashes(preg_replace("[\n|\r|\n\r|\t]", '', $datos['DESCRIPCION'][$i])).'"';
                            $result .= ',"FECHA"       : "'. addslashes(preg_replace("[\n|\r|\n\r|\t]", '', $datos['FECHA'][$i])).'"';
                            $result .= ',"LOGIN"       : "'. addslashes(preg_replace("[\n|\r|\n\r|\t]", '', $datos['LOGIN'][$i])).'"';
                            $result .= ',"CODIGO_INM"  : "'.$datos['CODIGO_INM'][$i].'"';
                        if ($i != $row-1) {
                            $result .= '},';
                        }else{
                            $result .= '}';
                        }
                    }
                $result .= ']}' ;    
            }else{
                $result .= '{
                                "data" : [{   
                                        "CONSECUTIVO" : "No se encontró",
                                        "CODIGO_OBS"  : "No se encontró",
                                        "ASUNTO"      : "No se encontró",
                                        "DESCRIPCION" : "No se encontró",
                                        "FECHA"       : "No se encontró",
                                        "LOGIN"       : "No se encontró",
                                        "CODIGO_INM"  : "No se encontró"
                                    }]
                            }';
                        
            }           
            echo $result;
            break;
        default:
            echo(json_encode($datos));
            break;
    }
	//echo(json_encode($datos));

 ?>