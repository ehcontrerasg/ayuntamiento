<?php
extract($_POST);

session_start();

if(isset($_SESSION["codigo"])) 
    $usuario = $_SESSION["codigo"];
else
    $usuario = $codigo_usuario; //Si se envía el código de usuario por POST entonces se le asigna a la variable 'usuario'.

/* Funciones */
function randomPass(){
    require_once "../../clases/class.pass.php";    
    $passClass = new Pass();
    return $passClass->randomPassword(); //Obtener una contraseña random.
}

function plantillaCorreoRecuperacion($nombreSolicitante, $pass){
    require_once '../../clases/class.plantillasCorreo.php';
    $plantillasCorreoClass = new PlantillaCorreo();  
    return $plantillasCorreoClass->recuperacionPass($nombreSolicitante, $pass);
}
/* Funciones */


if($tip == "verificarPassActual"){
    require_once "../../clases/class.usuario.php";
    $classUsuario = new Usuario();

    $buscarPorIdUsuario = array('codigo' => $usuario);
    $respuesta = (object) $classUsuario->getUsuario($buscarPorIdUsuario);    
    
    //Si hubo algún error al intentar encontrar los datos se retorna el mensaje de error.
    if($respuesta->codigo != 200){ echo $respuesta->mensaje; return false; };
    
    $usuario = (object)$respuesta->data[0];
    $pass_actual = md5($pass_actual);
    
    if($usuario->pass == $pass_actual) { echo true; return true; }
    
    echo false;
}

if($tip == "cambiarPass"){
    require_once "../../clases/class.usuario.php";
    $classUsuario = new Usuario();

    require_once "../../clases/class.cambioPass.php";
    
    $validacion = (object)CambioPass::validar(array('codigo_usuario' => $usuario, 'pass_actual' => md5($pass_actual), 'pass_nuevo' => $pass_nuevo, 'confirmacion_pass' => $confirmacion_pass_nuevo));    

    if (!$validacion->valido){ 
        echo json_encode(array('codigo'=> $validacion->codigo, 'mensaje' => $validacion->mensaje));
        return;        
    }

    $respuesta = $classUsuario->cambiarPass($usuario,$pass_nuevo);
    echo json_encode($respuesta);
}

if($tip == "correoRecuperacion"){   
        
    
    require_once "../../clases/class.usuario.php";
    $usuarioClass = new Usuario();
    
    //Se obtienen los datos del usuario que ha solicitado la recuperación del acceso a ACEASOFT.
    $buscarPorLogin = array('login' => $login, 'fecha_fin' => 'IS NULL'); //Se busca por el login del usuario activo.
    $getUsuario = (object)$usuarioClass->getUsuario($buscarPorLogin) ;
    
    if($getUsuario->codigo != 200 ) { echo json_encode($getUsuario); return false; }     
    
    if(isset($getUsuario->data)){

        $cantidadUsuariosEncontrados = count($getUsuario->data[0]);
        //Si no se encontró el usuario se muestra el mensaje de que el nombre de login no se encontró
        if( $cantidadUsuariosEncontrados == 0){ 
            echo json_encode(array('codigo' => 404, 'mensaje' => 'Este usuario no existe, inserte un nombre de usuario válido.')); 
            return false; 
        }

        $usuarioSolicitante = (object)$getUsuario->data[0]; 
    }

    $randomPass = randomPass(); //Contraseña aleatoria.
    
    //Se procede a modificar la contraseña del usuario que solicitó la recuperación.
    $cambiarPass = (object)$usuarioClass->cambiarPass($usuarioSolicitante->id, $randomPass, 'S');
    if($cambiarPass->codigo != 0 ) { echo json_encode($cambiarPass); return false; } 
    
    /* Se obtiene los datos del supervisor del usuario que solicitó la recuperación de la contraseña.  
    Se toma el nombre completo y el email del supervisor. */    
    $buscarPor = array('id_area' => $usuarioSolicitante->id_area, 'descripcion_cargo' => 'encargado', 'proyecto' => $usuarioSolicitante->proyecto, 'fecha_fin' => 'IS NULL');
    $getUsuario = (object)$usuarioClass->getUsuario($buscarPor);
    if($getUsuario->codigo != 200 ) { echo json_encode($getUsuario); return false; }

    if(isset($getUsuario->data)){
        $cantidadUsuariosEncontrados = count($getUsuario->data[0]);
        //Si no se encontró el usuario se muestra el mensaje de que el nombre de login no se encontró
        if( $cantidadUsuariosEncontrados == 0){ 
            echo json_encode(array('codigo' => 404, 'mensaje' => 'No se encontró información acerca de su supervisor. Contacte al departamento de desarrollo.')); 
            return false; 
        }
        $usuarioSupervisor = (object)$getUsuario->data[0];    
    }

    $nombreCompletoSolicitante = ucwords(strtolower($usuarioSolicitante->nombre . ' ' . $usuarioSolicitante->apellido));
    $plantillaRecuperacionPass = plantillaCorreoRecuperacion($nombreCompletoSolicitante, $randomPass);  //Se consigue la plantilla del correo.     

    //Si el usuario no es encargado de departamento entonces se envía al correo de su encargado.
    $destinatario = $usuarioSolicitante->email;
    if(strtolower($usuarioSolicitante->cargo) != "encargado") $destinatario = $usuarioSupervisor->email;

    if($destinatario == null ) {
        echo json_encode(array('codigo' => 404, 'mensaje' => 'No se pudo enviar el correo de recuperación de contraseña porque no se encontró la dirección de correo.'));
        return false;
    }
    
    //Envío del correo de recuperación de contraseña.
    require_once '../../clases/class.correo.php';
    $configs = require_once '../../configuraciones/conf.correo.php' ;

    $configuracionSmtp = $configs["recuperacion_contraseña"];
    
    $configuracionSmtp['debug'] = 0; //Se coloca el 0 para que no muestre la información del envio del correo completa, porque lo que se espera es un json. De otra manera, se dispara un error.
    
    $parametrosCorreo = array('remitente'       => 'desarrollo@aceadom.local', 
                              'remitente_alias' => 'Departamento de desarrollo', 
                              'asunto'          => 'Recuperación de contraseña de ' . $nombreCompletoSolicitante, 
                              'cuerpo'          => $plantillaRecuperacionPass, 
                              'destinatario'    => $destinatario);
    
    $correoClass = new correo();
    $correoEnviado = $correoClass->enviar($parametrosCorreo,$configuracionSmtp);

    $nombreSupervisor = ucwords(strtolower($usuarioSupervisor->nombre . ' ' . $usuarioSupervisor->apellido));
    $json = array('codigo' => 200, 'mensaje' => "Se ha enviado el correo de recuperación de contraseña a ".$nombreSupervisor. ", supervisor de su departamento.");
    if(!$correoEnviado) $json = array('codigo' => 500, 'mensaje' => 'Hubo un error al intentar enviar el correo de activación, intentelo nuevamente.');

    echo json_encode($json);
}