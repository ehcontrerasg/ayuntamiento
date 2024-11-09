<?php

//Obtener los datos del POST.
extract($_POST);

/*Obtener el código del usuario logueado*/
session_start();
$codigoUsuario = $_SESSION['codigo'];

if($tip == 'getProyectos'){
    /*Obtener los proyectos que llenarán el select de proyectos.*/

    require_once '../../clases/class.proyecto.php';

    $proyecto = new Proyecto();
    $resource = $proyecto->obtenerProyecto($codigoUsuario);

    $json = array();
    while($fila = oci_fetch_assoc($resource)){
        $array = array('CODIGO'=>$fila['CODIGO'],'DESCRIPCION'=>$fila['DESCRIPCION']);
        array_push($json,$array);
    }

    echo json_encode($json);
}

if($tip == 'getEncuestas'){
    //Se obtienen las respuestas de los clientes.

    require_once '../clases/class.EncuestaSatisfaccionUsuarios.php';

    $encuesta = new EncuestaSatisfaccionUsuarios();

    echo $encuesta->getEncuestas($proyecto,$fecha_desde,$fecha_hasta);
}

if($tip == 'getEncuesta'){
    /*Se obtienen los datos de una encuesta en particular.*/
    require_once '../clases/class.EncuestaSatisfaccionUsuarios.php';

    $encuesta = new EncuestaSatisfaccionUsuarios();

    echo $encuesta->getEncuesta($id_encuesta);
}

if($tip == 'getReporteParaImprimir'){
    /*Se obtienen los datos de todas las encuestas llenadas en un determinado intervalo de tiempo.*/
    require_once '../clases/class.EncuestaSatisfaccionUsuarios.php';

    $encuesta = new EncuestaSatisfaccionUsuarios();
    echo $encuesta->getEncuestaParaImprimir($proyecto,$fecha_desde,$fecha_hasta);
}

