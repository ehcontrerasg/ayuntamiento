<?php
    /**
     * Archivo que autoaprueba las solicitudes que a los siete días
     * de su conclusión aún no han sido aprobadas por su solicitante.
    */

    include_once  "../Clases/clase.solicitudes.php";

    $mSolicitudes           = new Solicitudes();
    $solicitudesParaAprobar = $mSolicitudes->SolicitudesParaAprobar();

    while ($fila = oci_fetch_assoc($solicitudesParaAprobar)){
        $idScms            = $fila["ID_SCMS"];
        $solicitudAprobado = $mSolicitudes->SolicitanteAprueba($idScms);
        $code              = $solicitudAprobado["Code"];

        if($code == 00){
            $mSolicitudes->EnviarCorreo($idScms, "N");
        }
    }