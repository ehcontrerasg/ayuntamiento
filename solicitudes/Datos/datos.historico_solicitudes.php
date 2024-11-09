<?php

extract($_REQUEST);

if ($type == "getResumenSolicitud") {
    require_once "../../clases/class.solicitudes.php";

    $classSolicitudes = new Solicitudes();
    $statement = $classSolicitudes->resumenSolicitud($id_solicitud);

    if (!$statement) {
        $error = oci_error($statement);
        echo json_encode(array(
            'code' => $error["code"],
            'message' => $error["message"]
        ));
        return;
    }

    $fila = oci_fetch_assoc($statement);    

    echo json_encode(array(
        'codigo_solicitud' => $fila["CODIGO_SOLICITUD"],
        'solicitante' => $fila["SOLICITANTE"],
        'prioridad' => $fila["PRIORIDAD"],
        'pantalla_modulo' => $fila["PANTALLA_MODULO"],
        'estado' => $fila["ESTADO"],
        'tipo_requerimiento' => $fila["TIPO_REQUERIMIENTO"],
        'fecha_solicitud' => $fila["FECHA_SOLICITUD"],
        'descripcion' => $fila["DESCRIPCION"],
        'desarrollador' => $fila["DESARROLLADOR"],
        'fecha_compromiso' => $fila["FECHA_COMPROMISO"],
        'fecha_inicio' => $fila["FECHA_INICIO"],
        'fecha_conclusion' => $fila["FECHA_CONCLUSION"],
        'archivos' => getArchivos($id_solicitud)
    ));
}

if ($type == "getHistoricoSolicitud") {
    require_once "../../clases/class.solicitudes.php";

    $classSolicitudes = new Solicitudes();
    $statement = $classSolicitudes->getHistoricoSolicitud($id_solicitud);

    if (!$statement) {
        $error = oci_error($statement);
        echo json_encode(array(
            'code' => $error["code"],
            'message' => $error["message"]
        ));
        return;
    }

    $array = array();
    while ($fila = oci_fetch_assoc($statement)) {
        array_push($array,array(
            'id_movimiento' => $fila["ID_MOVIMIENTO"],
            'fecha' => $fila["FECHA"],
            'estado_solicitud' => $fila["ESTADO_SOLICITUD"],
            'descripcion' => $fila["DESCRIPCION"],
            'usuario' => $fila["USUARIO"],
            'comentario' => $fila["COMENTARIO"]
        ));
    }

    echo json_encode($array);
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
