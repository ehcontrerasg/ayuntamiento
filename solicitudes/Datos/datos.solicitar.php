<?php
require_once '../Clases/clase.solicitar.php';

extract($_POST);
session_start();

if (isset($_POST['type'])) {

    $type = $_POST['type'];

    if ($type == 'menu') {
        $c = new Solicitar();

        $datosUsuario = json_decode($_POST['datosUsuario'],true) ;
        $data = $c->getMenus($datosUsuario);
        $json = array();
        while (oci_fetch($data)) {
            $id_menu = oci_result($data, 'ID_MENU');
            $desc    = oci_result($data, 'DESC_MENU');
            $menu    = array(
                'id'   => $id_menu,
                'desc' => $desc,
            );
            array_push($json, $menu);
        }

        echo json_encode($json);

    } elseif ($type == 'pantalla') {
        $id_menu = $_POST['menu'];
        $c       = new Solicitar();

        $usuario = $_SESSION["codigo"];

        $data = $c->getPantalla($usuario, $id_menu);
        $json = array();
        while (oci_fetch($data)) {
            $id_menu = oci_result($data, 'ID_MENU');
            $desc    = oci_result($data, 'DESC_MENU');
            $menu    = array(
                'id'   => $id_menu,
                'desc' => $desc,
            );
            array_push($json, $menu);
        }

        echo json_encode($json);

    } elseif ($type == 'submit') {
        
        $usuario = $_SESSION["codigo"];
        $c = new Solicitar();
        $data = $c->setSolicitud($usuario, $_POST);

        $idScms = $data['Data']['Id_scms'];

        //Subir los archivos.
        if ($data["Code"] == 00) { uploadFiles($_FILES["archivos"],$idScms); }

        echo json_encode($data);
    }elseif ($type == 'editar') {

        $c = new Solicitar();
        $solicitudEditada = $c->editaSolicitud($_POST);

        //Subir los archivos.
        if ($solicitudEditada == true) { uploadFiles($_FILES["archivos"],$idSCMS); }

        echo ($solicitudEditada == true) ? "true" : "false";
    }
}

function isUploadedFileTypeAllowed($uploadedFileType){
    $allowedDataType = array(
        /* Documentos Word */
        ".doc",
        ".docx",
        ".xml",
        "application/msword",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        /* PDF */
        "application/pdf",
        /* Excel */
        "application/vnd.ms-excel",
        ".csv",
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        "application/vnd.ms-excel",
        /* Imágenes */
        "jpg",
        "jpeg",
        "png",
    );

    return (in_array($uploadedFileType,$allowedDataType)) ? true : false ;
}

function uploadFiles($files,$idScms){
    $target_dir = "../../archivo/pdf/SCMS";
    $uploaded = true;
    $message = "Archivo subido satisfactoriamente";

    //Crear directorio si no existe.
    if (!is_dir($target_dir)) {
        mkdir($target_dir);   
    }

    $target_dir .= "/".$idScms;
    if (!is_dir($target_dir)) { mkdir($target_dir); }

    //Subir los archivos.
    for ($i=0; $i < count($files["name"]); $i++) { 
        $target_file = $target_dir ."/". basename($files["name"][$i]);
        $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
        if (!isUploadedFileTypeAllowed($fileType)) { 
            $message = "Tipo de archivo no admitido.";
            $uploaded = false;
        }
    
        if (!move_uploaded_file($files["tmp_name"][$i], $target_file)) { 
            $message = "No se pudo subir el archivo";  
            $uploaded = false; 
        }

    }

    if (!$uploaded) { $message = "Algunos archivos no pudieron subirse."; }

    return array('uploaded'=>$uploaded,'message'=>$message);
}
