<?php

session_start();
require_once '../Clases/clase.solicitudes.php';

if (isset($_POST['type'])) {

    extract($_POST);
    $type = $_POST['type'];

    $s = new Solicitudes();

    if ($type == 'programmer') {
        $desarrollador_asignado =  $s->asigDesarollador($id_scms, $programer, $valida, $_SESSION["codigo"]);
        echo $desarrollador_asignado;
    } elseif ($type == 'status') {
        $cambio_estado = $s->cambiaStado($scms, $status);
            if($cambio_estado){
                $s->EnviarCorreo($scms,"J");
            }

        echo $cambio_estado;
    } elseif ($type == 'validate') {
         $res = $s->respCalidad($scms, $comment, $resp);

         if($res == 1){
             if($resp == 'N')
                 $s->EnviarCorreo($scms,"B");
             else if($resp == 'S'){
                 $s->EnviarCorreo($scms,"C"); //Envía el correo al solicitante de que su solicitud ha sido aprobada.
                 $s->EnviarCorreo($scms,"D"); //Envía el correo al departamento de desarrollo para que la solicitud sea asignada a un desarrollador.
             }

         }

        echo $res;
    } elseif ($type == 'validaUsr') {

        $solicitante_aprueba =  $s->SolicitanteAprueba($scms);
        $code = $solicitante_aprueba["Code"];
        if($code == 00){
            $s->EnviarCorreo($scms,"I");
        }

        echo $solicitante_aprueba;
    } elseif ($type == 'valFin') {
        echo $s->valFinal($scms, $comentario);
    }elseif ($type == 'estadosSCMS') {
        if(isset($tipo_estado)){
         $datos = $s->getEstadosScms($tipo_estado);
        }else{
            $datos = $s->getEstadosScms();
        }
        $estadosSCMS = [];

        while ($filas = oci_fetch_assoc($datos)){
            $arr =  [
                        "CODIGO"=>$filas["CODIGO"],
                        "DESCRIPCION"=>$filas["DESCRIPCION"]
                    ];
            array_push($estadosSCMS,$arr);
        }
        echo json_encode($estadosSCMS);
    } elseif ($type == "getUserData"){
        $userData = $s->getUserData($_SESSION["codigo"]);
        echo json_encode($userData);
    }
     elseif($type == "getSCMS"){

        if (!isset($fechaDesde) ||  !isset($fechaHasta)) {
            $fechaDesde = ''; 
            $fechaHasta = '';
        }
        
        $SCMS = $s->getScms($tipoSCMS,$codigoSolicitud ,$estado,$filaDesde,$filaHasta, $fechaDesde,$fechaHasta);

        $dataSCMS = [];
        while($filas = oci_fetch_assoc($SCMS)){
            $arr = [
                    "ID_SCMS"                       => $filas["ID_SCMS"],
                    "ID_SOLICITADOR"                => $filas["ID_SOLICITADOR"],
                    "SOLICITADOR"                   => $filas["SOLICITADOR"],
                    "ID_AREA"                       => $filas["ID_AREA"],
                    "ID_MODULO"                     => $filas["ID_MODULO"],
                    "MODULO"                        => $filas["MODULO"],
                    "FECHA_SOLICITUD"               => $filas["FECHA_SOLICITUD"],
                    "DESCRIPCION"                   => $filas["DESCRIPCION"],
                    "FECHA_COMPROMISO"              => $filas["FECHA_COMPROMISO"],
                    "ID_DESARROLLADOR"              => $filas["ID_DESARROLLADOR"],
                    "DESARROLLADOR"                 => $filas["DESARROLLADOR"],
                    "ID_PANTALLA"                   => $filas["ID_PANTALLA"],
                    "ESTADO"                        => $filas["ESTADO"],
                    "ID_ESTADO"                     => $filas["ID_ESTADO"],
                    "PRIORIDAD_REQUERI"             => $filas["PRIORIDAD_REQUERI"],
                    "DESC_REQUERIMIENTO"            => $filas["DESC_REQUERIMIENTO"],
                    "DESC_PRIORIDAD"                => $filas["DESC_PRIORIDAD"],
                    "PANTALLA"                      => $filas["PANTALLA"],
                    "VALIDA_CALIDAD"                => $filas["VALIDA_CALIDAD"],
                    "MENSAJE_CALIDAD"               => $filas["MENSAJE_CALIDAD"],
                    "FECHA_MENSAJE_CALIDAD"         => $filas["FECHA_MENSAJE_CALIDAD"],
                    "VALIDA_SOLICITANTE"            => $filas["VALIDA_SOLICITANTE"],
                    "USR_REVISION"                  => $filas["USR_REVISION"],
                    "COMENT_DESAPRUEBA_SOLICITANTE" => $filas["COMENT_DESAPRUEBA_SOLICITANTE"],
                    "FECHA_COMENT_SOLICITANTE"      => $filas["FECHA_COMENT_SOLICITANTE"],
                    "COMENT_DESAPRUEBA_TI"          => $filas["COMENT_DESAPRUEBA_TI"],
                    "USUARIO_COMENT_ANULA_TI"       => $filas["USUARIO_COMENT_ANULA_TI"],
                    "FECHA_COMENT_ANULA_TI"         => $filas["FECHA_COMENT_ANULA_TI"],
                    "FECHA_INICIO"                  => $filas["FECHA_INICIO"],
                    "FECHA_CONCLUSION"              => $filas["FECHA_CONCLUSION"],
                    "ARCHIVOS"                      => getArchivos($filas["ID_SCMS"])
                   ];

            array_push($dataSCMS,$arr);
        }
        echo json_encode($dataSCMS);
    }elseif($type == "getHistoricoSCMS"){

        $historicoSCMS = $s->getHistoricoSolicitud($id_scms);
        $dataSCMS = [];
        while($filas = oci_fetch_assoc($historicoSCMS)){
            $arr = [
                "ID_SCMS"               => $filas["ID_SCMS"],
                "FECHA_SOLICITUD"       => $filas["FECHA_SOLICITUD"],
                "DESCRIPCION"           => $filas["DESCRIPCION"],
                "FECHA_MODIFICACION"    => $filas["FECHA_MODIFICACION"],
                "PANTALLA"              => $filas["PANTALLA"],
                "USUARIO_SOLICITADOR"   => $filas["USUARIO_SOLICITADOR"],
                "DESARROLLADOR"         => $filas["DESARROLLADOR"],
                "ESTADO"                => $filas["ESTADO"],
                "VALIDA_SOLICITANTE"    => $filas["VALIDA_SOLICITANTE"],
                "ID_MODIFICACION"       => $filas["ID_MODIFICACION"],
                "VALIDA_CALIDAD"        => $filas["VALIDA_CALIDAD"],
                "FECHA_CALIDAD"         => $filas["FECHA_CALIDAD"],
                "FECHA_INICIO"          => $filas["FECHA_INICIO"],
                "FECHA_CONCLUSION"      => $filas["FECHA_CONCLUSION"],
                "FECHA_COMPROMISO"      => $filas["FECHA_COMPROMISO"],
                "FECHA_PREAPROBACION_DESARROLLO"      => $filas["FECHA_PREAPROBACION_DESARROLLO"],
                "ARCHIVOS"              => getArchivos($id_scms)
            ];

            array_push($dataSCMS,$arr);
        }
        echo json_encode($dataSCMS);
    }elseif ($type == "anularSCMS"){
        $anulacionSCMS =  $s->anulaSCMS($id_scms,$comment);
        if($anulacionSCMS == 1){
          $s->EnviarCorreo($id_scms,"E");
        }
        echo json_encode($anulacionSCMS);
    }elseif($type == "invalidaUsr"){
        $desaprobacionSCMS =  $s->desapruebaSCMS($scms,$comment);
        if($desaprobacionSCMS == 1){
            $s->EnviarCorreo($scms,"H");
        }
        echo json_encode($desaprobacionSCMS);
    }elseif($type == "getComentarios"){
        $comentarios = $s->getComentarios($id_scms, $id_modificacion);

        $dataSCMS = [];
        while($filas = oci_fetch_assoc($comentarios)){

            if(isset($filas["FECHA_CONCLUSION"])){
                $fechaConclusion = $filas["FECHA_CONCLUSION"];
            }else{
                $fechaConclusion = "-";
            }

            if(isset($filas["FECHA_DESAPROBACION"])){
                $fechaDesaprobracion = $filas["FECHA_DESAPROBACION"];
            }else{
                $fechaDesaprobracion = "-";
            }

            $arr = [
                    "COMENTARIO"          => $filas["COMENTARIO"],
                    "USUARIO_COMENTA"     => $filas["USUARIO_COMENTA"],
                    "FECHA_CREACION"      => $filas["FECHA_CREACION"],
                    "FECHA_CONCLUSION"    => $fechaConclusion,
                    "FECHA_DESAPROBACION" => $fechaDesaprobracion,
                   ];

            array_push($dataSCMS,$arr);
        }
        echo json_encode($dataSCMS);
    }elseif($type == "cierre_scms"){
        $cierre_scms = $s->cierre_scms($id_scms);
        if($cierre_scms){
            $s->EnviarCorreo($id_scms,"G");
        }
        echo json_encode($cierre_scms);
    }elseif($type == "getSCMSData"){
        $SCMSData = $s->getSCMSData($idSCMS);
        $SCMSData["ARCHIVOS"] = getArchivos($idSCMS);;
        echo json_encode($SCMSData);
    }elseif($type=="enviarCorreo"){
        echo $s->EnviarCorreo($idSCMS,$tipoSolicitud);
    }elseif ($type == "deleteFile") {
        $directory = "../../archivo/pdf/SCMS/".$id_scms."/".$filename;
        $archivoEliminado = eliminarArchivo($directory);

        echo ($archivoEliminado) ? "true" : "false"; 
    }elseif ($type == "validarDesarrollo") {
       echo json_encode($s->validarDesarrolloScms($id_solicitud,$_SESSION["codigo"]));
    }elseif ($type == "getDesarrolladores") {       
        $resultado = $s->getProgrammers();
        $arr = array();
        while ($fila = oci_fetch_assoc($resultado)) {

            array_push($arr,array(
                'id_usuario' => $fila['ID_USUARIO'],
                'id_cargo' => $fila['ID_CARGO'],
                'nombre' => $fila['NOMBRE']
            ));
        }

        echo json_encode($arr);
    }elseif ($type== "desaprobarDesarrollo") {
        $usuario_desaprueba = $_SESSION["codigo"];
        $desaprobado = $s->desaprobarDesarrollo($id_Solicitud, $usuario_desaprueba, $comentario);

        if ($desaprobado) {
            echo json_encode(array(
                'codigo' => 0,
                'mensaje' => 'Solicitud desaprobada para desarrollo satisfactoriamente.'
            ));            
            return;
        }

        echo json_encode(array(
            'codigo' => 1,
            'mensaje' => 'Solicitud no fue desaprobada para desarrollo satisfactoriamente.'
        ));
        return;
    }
}

function getArchivos($idScms){
    $directory = "../../archivo/pdf/SCMS/".$idScms;

    if (!is_dir($directory)) { return array(); }

    $filesInDirectory = scandir($directory);

    $files = array();
    foreach ($filesInDirectory as $filename) {

        $fileType = tipoArchivo($filename);        
        if ($fileType == null) { continue; }

        $icono  = iconoArchivo($fileType);

        $file = array(
            'nombre' => $filename,
            'tipo' => $fileType,
            'ruta' => $directory."/".$filename,
            'icono' => $icono
        );

        array_push($files, $file);
    }

    return $files;
}

function tipoArchivo($nombreArchivo){
    $partes =  explode('.',$nombreArchivo);
    $ultimoIndex = count($partes) - 1;

    return ($partes[$ultimoIndex] != "") ? $partes[$ultimoIndex] : null;
}

function iconoArchivo($fileType){
    $icons = array(
        array(
            'tipo'=>'png',
            'imagen'=> '/images/icono_imagen.png'
        ),
        array(
            'tipo'=>'jpg',
            'imagen'=> '/images/icono_imagen.png'
        ),
        array(
            'tipo'=>'jpeg',
            'imagen'=> '/images/icono_imagen.png'
        ),
        array(
            'tipo'=>'pdf',
            'imagen'=> '/images/pdf_ico.png'
        ),
        array(
            'tipo'=>'doc',
            'imagen'=> '/images/doc.png'
        ),
        array(
            'tipo'=>'docx',
            'imagen'=> '/images/doc.png'
        ),
        array(
            'tipo'=>'txt',
            'imagen'=> '/images/txt.png'
        ),
        array(
            'tipo'=>'csv',
            'imagen'=> '/images/excel.png'
        ),
        array(
            'tipo'=>'xls',
            'imagen'=> '/images/excel.png'
        ),
        array(
            'tipo'=>'xlsx',
            'imagen'=> '/images/excel.png'
        )
    );

    $arrayIndex = array_search($fileType,array_column($icons, 'tipo'));
    return $icons[$arrayIndex]['imagen'];
}

function eliminarArchivo($filename){
    return unlink($filename);
}