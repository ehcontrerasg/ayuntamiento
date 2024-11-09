<?php
include_once ('../clases/class.PermisosURL.php');
session_start();
$tip=$_POST['tip'];
if ($tip=='sess'){
    $segundos = 1800; //si pasa este tiempo se detecta la inactividad del usuario en el sitio
    if(($_SESSION['tiempo']+$segundos) < time()) {
        session_destroy();
        echo "false";
    }else{
        if($_SESSION["codigo"]) {
            $_SESSION['tiempo'] = time();

            echo "true";
        }else{
            echo "false";
        }
    }
}
if($tip=='iniSes'){
    $usu=$_POST['usu'];
    $pas=$_POST['pas'];
    include_once'../clases/class.usuario.php';
    $l = new Usuario();
    $datos = $l->getUsrByusuPas($usu,$pas);
    $i=0;
    if (oci_fetch($datos)) {
        $cod_usuario = oci_result($datos, 'USUARIO') ;
        $nom_usuario = oci_result($datos, 'NOMBRE') ;
        $ape_usuario = oci_result($datos, 'APELLIDO') ;
        $_SESSION["usuario"] = $user;
        $_SESSION["codigo"] = $cod_usuario;
        $_SESSION["nombre"] = $nom_usuario." ".$ape_usuario;
        $_SESSION['tiempo']=time();
        echo "true";
    }else{
        echo "false";
    }

}




