<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

extract($_POST);

if($tip == 'getArchivos'){

    require_once '../../clases/class.archivoGerencia.php';

    $classArchivo = new ArchivoGerencia();
    echo json_encode($classArchivo->obtenerTodos());
}

if($tip == 'getDatosArchivo'){
    require_once '../../clases/class.archivoGerencia.php';

    $classArchivo = new ArchivoGerencia();
    echo json_encode($classArchivo->getArchivo($id_archivo_gerencia));
}

if($tip == 'getHistorico') {
    //Consigue el histórico del archivo dado un número de protocolo.

    require_once '../../clases/class.archivoGerencia.php';
    $archivoGerenciaClass = new ArchivoGerencia();
    echo json_encode($archivoGerenciaClass->getHistorico($id_archivo));
}

if($tip == 'actualizarArchivo') {

    /**
     *  Casos de uso:
     *
     * Caso #1: El usuario modifica cualquier campo y no especifica un nuevo archivo.
     * Uso #1: Se debe mover el archivo subido previamente a la nueva ruta.
     *
     * Caso #2: El usuario solo modifica el archivo.
     * Uso #2: Se debe reemplazar el archivo previamente subido.
     *
     * Caso #3: El usuario modifica el archivo y otros campos.
     * Uso #3: Se debe eliminar el directorio anterior y se sube el archivo a la ruta especificada.
     */

    //Usuario
    session_start();
    $usuario = $_SESSION['codigo'];

    /*Se consigue el tipo de documento para luego ser puesto en el nombre del archivo cuando se suba al servidor.*/
    require_once '../../clases/class.documento.php';
    $documento = new Documento();
    $dataDocumento = $documento->getDocumentos(array($tipo_documento));
    $tipoDocumento = json_decode($dataDocumento, true)['data'][0]['descripcion'];
    $directorioServidor = '../pdf/GERENCIA/';

    /***Manejo de archivo***/
    $numero_protocolo = $id_archivo;

    // Para determinar si es un nuevo directorio se verifica la ruta previa y la nueva ruta.
    $antiguoDirectorio = getAntiguoDirectorio($ruta_archivo);
    $nuevoDirectorio = getNuevoDirectorio($numero_protocolo, $tipo_correspondencia, $id_empresa, $tipoDocumento, $carpeta, $subcarpeta);

    $esNuevoDirectorio = false;
    if ($antiguoDirectorio != $nuevoDirectorio)
        $esNuevoDirectorio = true;

    //Se determina si se envió un nuevo archivo
    $esNuevoArchivo = false;
    if(isset($_FILES['archivo'])){
        $inputFile = $_FILES['archivo'];
        $fileType = explode('.', $inputFile['name'])[1];
        $esNuevoArchivo = true;
    }

    //Si se sube un archivo nuevo se debe de formar la ruta del mismo.
    if ($esNuevoArchivo) {
        $nombreArchivo = getNombreArchivo($numero_protocolo,$tipoDocumento,$fileType);
        $ruta = $nuevoDirectorio.'/'.$nombreArchivo;
    }

    if($esNuevoDirectorio && !$esNuevoArchivo){
        /**Caso #1 (Ver descripción al inicio)*/
        $respuestaArchivo =  casoUno($ruta_archivo,$directorioServidor,$numero_protocolo,$tipoDocumento,$nuevoDirectorio);
    }elseif (!$esNuevoDirectorio && $esNuevoArchivo){
        /**Caso #2 (Ver descripción al inicio)*/
        $tempArchivo = $inputFile['tmp_name'];
        $respuestaArchivo = casoDos($tempArchivo,$directorioServidor,$antiguoDirectorio,$nombreArchivo);
    }elseif ($esNuevoDirectorio && $esNuevoArchivo){
        /**Caso #3 (Ver descripción al inicio)*/
        $tempArchivo = $inputFile['tmp_name'];
        $respuestaArchivo = casoTres($tempArchivo,$directorioServidor,$nuevoDirectorio,$nombreArchivo,$antiguoDirectorio);
    }elseif(!$esNuevoDirectorio && !$esNuevoArchivo)
        $respuestaArchivo = array('status'=>1,'data'=>array('ruta'=>$ruta_archivo,'archivo_cambia'=>'N'));

    /**Fin del Manejo de archivos*/

    if($respuestaArchivo['status'] == 1){
        require_once '../../clases/class.archivoGerencia.php';
        $archivoGerenciaClass = new ArchivoGerencia();

        $ruta = $respuestaArchivo['data']['ruta'];
        $archivoCambia = $respuestaArchivo['data']['archivo_cambia'];

        $parametros = array(
            'id_archivo' => $id_archivo,
            'tipo_correspondencia'=> $tipo_correspondencia,
            'tipo_documento'=> $tipo_documento,
            'fecha_documento'=> $fecha_documento,
            'ruta_archivo'=> $ruta,
            'archivo_cambia'=> $archivoCambia,
            'asunto'=> $asunto,
            'id_empresa'=> $id_empresa,
            'usuario'=> $usuario
        );
        $archivoActualizado = $archivoGerenciaClass->actualizar($parametros);

        if($archivoActualizado['message_code'] == 0)
            $json = array('status'=>200,'message'=>$archivoActualizado['message']);
        else
            $json = array('status'=>400,'message'=>$archivoActualizado['message']);
    }else
        $json = array('status'=>500,'message'=>$respuestaArchivo['message']);

    echo json_encode($json);
}

if($tip == 'eliminarArchivo'){
    $directorioServidor = '../pdf/GERENCIA/';

    //Obtener usuario logueado.
    session_start();
    $idUsuario = $_SESSION['codigo'];

    require_once '../../clases/class.archivoGerencia.php';
    $archivoGerenciaClass = new ArchivoGerencia();

    //Conseguimos la ruta del archivo para luego ser eliminado del servidor.
    $archivo = $archivoGerenciaClass->getArchivo($id_archivo);
    $archivoData = $archivo['data'];
    if(isset($archivoData))
        $rutaArchivo = $archivoData['ruta_archivo'];

    $parametros = array(
            'id_archivo'=>$id_archivo,
            'id_usuario'=>$idUsuario
    );
    $archivoEliminado = $archivoGerenciaClass->eliminar($parametros);

    if($archivoEliminado['message_code'] == 0){
        $directorio = getAntiguoDirectorio($rutaArchivo);
        deleteDirectory(($directorioServidor.$directorio));
    }

    echo json_encode($archivoEliminado);
}

/*Funciones*/

    function getAntiguoDirectorio($rutaArchivo){
        $directorio = '';
        $rutaArchivoSplit = explode('/',$rutaArchivo);
        $ultimoIndice = count($rutaArchivoSplit)-1;
        for($i = 0; $i < $ultimoIndice; $i++)
            $directorio .= $rutaArchivoSplit[$i].'/';


        //Eliminamos el ultimo slash(/).
        $directorio = substr($directorio,0,-1);

        return $directorio;
    }

    function getNombreAntiguoArchivo($rutaArchivo){

        $rutaArchivoExploded = explode('/',$rutaArchivo);
        $ultimoIndice = count($rutaArchivoExploded) - 1;
        $nombreArchivo = $rutaArchivoExploded[$ultimoIndice];

        return $nombreArchivo;
    }

    function getNuevoDirectorio($numeroProtocolo, $tipoCorrespondencia, $idEmpresa,$tipoDocumento ,$carpeta, $subcarpeta){

    //Función que forma el nombre del directorio donde se guardará el nuevo archivo.

    //Se consigue el nombre de la empresa, esto es para luego crear el directorio.
    require_once '../../clases/class.empresa.php';
    $classEmpresa = new Empresa();
    $nombreEmpresa = json_decode($classEmpresa->getEmpresa($idEmpresa), true)['data'][0]['desc_empresa'];

    //Se crea la ruta donde se guardará el archivo.
    $directorio = strtoupper($nombreEmpresa) . '/' . strtoupper($tipoCorrespondencia) . '/' . strtoupper($carpeta);
    if ($subcarpeta != ' ' || $subcarpeta!=null) {
        $directorio .= '/' . $subcarpeta;
    }
    $directorio .= '/'.$tipoDocumento.'/' . $numeroProtocolo;

    return $directorio;
}

    function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}

    function subirArchivo($tempArchivo, $directorio, $nombreArchivo){

        //Crear directorio donde se subirá el archivo.
        if(!is_dir($directorio)){
            if (!mkdir($directorio,0777,true))
                return array('status' => 0, 'message' => 'Hubo un error al intentar crear el directorio para subir el archivo.');
        }

        $archivoSubido = move_uploaded_file($tempArchivo,($directorio.'/'.$nombreArchivo));

        return array('status'=>$archivoSubido,'message'=>'Archivo subido exitosamente.');
    }

    function moverArchivo($antiguaRuta, $nuevaRuta,$nuevoDirectorio){

        if(!is_dir($nuevoDirectorio))
            mkdir($nuevoDirectorio,0777,true);

        return rename($antiguaRuta,$nuevaRuta);
    }

    function getNombreArchivo($numeroProtocolo,$tipoDocumento,$tipoArchivo){
        /**Función que forma el nombre y extensión de un archivo dependiendo del número de protocolo, tipo de documento y tipo de archivo.*/
        return $numeroProtocolo . '(' . $tipoDocumento . ').' . $tipoArchivo;
    }

    function getAntiguoArchivo($nombreArchivo){
        /**Función que obtiene el nombre y extensión del antiguo archivo.*/
        $nombreArchivoExploded = explode('.',$nombreArchivo);
        return array('nombre'=>$nombreArchivoExploded[0], 'extension'=>$nombreArchivoExploded[1]);
    }

    function eliminarCarpetaVacia($directorio){
        if(is_dir($directorio)){
            $cantidadArchivos = count(scandir($directorio));

            if($cantidadArchivos == 2)
                return rmdir($directorio);
        }

        return true;
    }

    function casoUno($rutaAntiguoArchivo,$directorioServidor,$numeroProtocolo,$tipoDocumento,$nuevoDirectorio){
        /**
         * Caso #1: El usuario modifica cualquier campo y no especifica un nuevo archivo.
         * Uso #1: Se debe mover el archivo subido previamente a la nueva ruta.
        */

        $nombreCompletoAngiguoArchivo = getNombreAntiguoArchivo($rutaAntiguoArchivo);

        //Obtener el nombre y extensión del antiguo archivo.
        $partesAntiguoArchivo = getAntiguoArchivo($nombreCompletoAngiguoArchivo);
        $nombreAntiguoArchivo = $partesAntiguoArchivo['nombre'];
        $extensionAntiguoArchivo = $partesAntiguoArchivo['extension'];

        //Antigua ruta
        $antiguoDirectorio = getAntiguoDirectorio($rutaAntiguoArchivo);
        $antiguaRuta = $directorioServidor.$antiguoDirectorio.'/'.$nombreAntiguoArchivo.'.'.$extensionAntiguoArchivo;

        //Nueva ruta
        $nuevoNombreArchivo = getNombreArchivo($numeroProtocolo,$tipoDocumento,$extensionAntiguoArchivo);
        $nuevaRuta = $directorioServidor.$nuevoDirectorio.'/'.$nuevoNombreArchivo;

        $archivoMovido =  moverArchivo($antiguaRuta,$nuevaRuta,($directorioServidor.$nuevoDirectorio));

        if($archivoMovido){
            //Cuando un archivo es movido es probable que el directorio se quede vacío, se elimina ese directorio.
            $directorio = $directorioServidor.$antiguoDirectorio;
            eliminarCarpetaVacia($directorio);
            return array('status'=>1,'data'=>array('ruta'=>($nuevoDirectorio.'/'.$nuevoNombreArchivo),'archivo_cambia'=>'N'));
        }

        return array('status'=>0,'message'=>'Hubo inconveniente al intentar mover el archivo.','error_message'=>'Hubo inconveniente al intentar mover el archivo.| Caso #1');
    }

    function casoDos($tempArchivo,$directorioServidor,$antiguoDirectorio,$nombreArchivo){
        /**Caso #2: El usuario solo modifica el archivo.
        Uso #2: Se debe reemplazar el archivo previamente subido.*/

        $archivoSubido =  subirArchivo($tempArchivo,($directorioServidor.$antiguoDirectorio),$nombreArchivo);

        return array('status'=>$archivoSubido['status'],'message'=>$archivoSubido['message'],'data'=>array('ruta'=>($antiguoDirectorio.'/'.$nombreArchivo),'archivo_cambia'=>'S'));
    }

    function casoTres($tempArchivo,$directorioServidor,$nuevoDirectorio,$nombreArchivo,$antiguoDirectorio){

        /**Caso #3: El usuario modifica el archivo y otros campos.
         Uso #3: Se debe eliminar el directorio anterior y se sube el archivo a la ruta especificada.*/

        $archivoSubido = subirArchivo($tempArchivo,($directorioServidor.$nuevoDirectorio),$nombreArchivo);

        if($archivoSubido['status'])
            deleteDirectory(($directorioServidor.$antiguoDirectorio));

        return array('status'=>$archivoSubido['status'],'message'=>$archivoSubido['message'], 'data'=>array('ruta'=>($nuevoDirectorio.'/'.$nombreArchivo),'archivo_cambia'=>'S'));
    }