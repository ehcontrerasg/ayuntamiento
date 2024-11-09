<?php
    /* Programa que anula automáticamente a las solicitudes que han sido desaprobadas por el departamento de desarrollo y 
    no fue modificada por el solicitante. */

    require_once '../Clases/clase.solicitudes.php';
    $claseSolicitudes = new Solicitudes();
    $statement = $claseSolicitudes->solicitudesParaAnular();
        
    while ($fila = oci_fetch_assoc($statement)) {
        $codigoSolicitud = $fila["ID_SCMS"];
        $comentario = "La solicitud fue anulada automáticamente por motivo de desaprobación para desarrollo.";
        $solicitudAnulada = $claseSolicitudes->anulaSCMS($codigoSolicitud,$comentario);

        if ($solicitudAnulada) { $claseSolicitudes->EnviarCorreo($codigoSolicitud,'M'); }
    }