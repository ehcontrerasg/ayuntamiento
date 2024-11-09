<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

extract($_POST);

if($tip == 'getEmpresas'){
    require_once '../../clases/class.empresa.php';

    $empresa = new Empresa();
    echo $empresa->getEmpresas();
}

if($tip == 'getDocumentos'){
 require_once '../../clases/class.documento.php';

 $documento = new Documento();
 $documentosRequeridos = array(2,4,5,15,24,25); //Arreglo de todos los documentos que deben aparecer en la pantalla de ingreso de archivos de gerencia.
 echo $documento->getDocumentos($documentosRequeridos);
}

if($tip == 'ingresarArchivo'){

    require_once '../../clases/class.archivoGerencia.php';
    $archivo = new ArchivoGerencia();

    //Verificamos si el número de protocolo está repetido
    if($tipo_correspondencia != 'Otros'){
        $archivosPorNumeroProtocolo = $archivo->getArchivoPorNumeroProtocolo($numero_protocolo);
        if( $archivosPorNumeroProtocolo['status'] == 200 && $archivosPorNumeroProtocolo['encontrados'] > 0){
            echo json_encode(array('status'=>400,'message'=>'Ya existe un documento registrado con el número de protocolo '.$numero_protocolo.'.','error_message'=>'Ya existe un documento registrado con el número de protocolo '.$numero_protocolo.'.'));
            return false;
        }
    }


    $directorioServidor = '../pdf/GERENCIA/';

    session_start();
    $usuario = $_SESSION['codigo'];
    //Se obtiene el archivo enviado desde el formulario y el tipo de archivo.
    $inputFile = $_FILES['archivo'];
    $fileType = explode('.',$inputFile['name'])[1];

    /*Se consigue el tipo de documento para luego ser puesto en el nombre del archivo cuando se suba al servidor.*/
    require_once '../../clases/class.documento.php';
    $documento = new Documento();
    $dataDocumento =  $documento->getDocumentos(array($tipo_documento));
    $tipoDocumento = json_decode($dataDocumento,true)['data'][0]['descripcion'];

    $idArchivo = idArchivo();
    $nombreArchivo = $idArchivo.'('.$tipoDocumento.').'. $fileType;

    //Se consigue el nombre de la empresa, esto es para luego crear el directorio.
    require_once '../../clases/class.empresa.php';
    $classEmpresa = new Empresa();
    $nombreEmpresa = json_decode($classEmpresa->getEmpresa($empresa),true)['data'][0]['desc_empresa'];

    //Se crea la ruta donde se guardará el archivo.
    $ruta = strtoupper($nombreEmpresa).'/'.strtoupper($tipo_correspondencia).'/'.strtoupper($carpeta);
    if($subcarpeta!=' ') {$ruta .= '/'.$subcarpeta;}
    $ruta .= '/'.$tipoDocumento.'/'.$idArchivo;

    //Se insertan los datos en la base de datos.


    /*if(!isset($numero_protocolo) || $numero_protocolo == null || $numero_protocolo == '')
        $numero_protocolo = 0;*/


    $parametros = array(
        'numero_protocolo'=>$numero_protocolo,
        'tipo_correspondencia' => $tipo_correspondencia,
        'tipo_documento' => $tipo_documento,
        'fecha_documento' => $fecha_documento,
        'ruta_archivo' => ($ruta.'/'.$nombreArchivo),
        'asunto' => $asunto,
        'id_emoresa' => $empresa,
        'usuario_carga' => $usuario
    );

    $json =  $archivo->registrar($parametros);

    $array = json_decode($json,true);

    if($array['message_code'] == 0){
        $directorio = $directorioServidor.$ruta;
        echo subirArchivo($inputFile,$nombreArchivo,$directorio);
    }else{

        echo json_encode(array('status'=>400,'message'=>$array['message'],'error_message'=>$array['error_message']));
    }
}


function subirArchivo($inputFile,$nombreArchivo,$directorio){

    //Crear directorio donde se subirá el archivo.
    if(!is_dir($directorio)){
        if(!mkdir($directorio, 0700,true))
            return json_encode(array('status'=>400,'message'=>'Hubo un error al intentar crear el directorio para subir el archivo.','error_message'=>'Hubo un error al intentar crear el directorio para subir el archivo.'));
    }

    $tempFile = $inputFile['tmp_name'];
    $archivoSubido = move_uploaded_file($tempFile,($directorio.'/'.$nombreArchivo));

    if($archivoSubido)
        return json_encode(array('status'=>200,'message'=>'Archivo guardado exitosamente.'));
    else
        return json_encode(array('status'=>400,'message'=>'Hubo un error al intentar subir el archivo.'));
}

function idArchivo(){
    require_once '../../clases/class.archivoGerencia.php';

    $archivoGerencia = new ArchivoGerencia();
    $getIdArchivo = $archivoGerencia->getIdArchivo();

    if($getIdArchivo['status'] == 500)
        return 0;

    return $getIdArchivo['last_file_id'];
}