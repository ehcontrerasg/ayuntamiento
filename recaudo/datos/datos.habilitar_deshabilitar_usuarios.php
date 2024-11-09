<?php
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 8/17/2018
 * Time: 9:34 AM
 */

$tipo=$_POST['tipo'];



if ($tipo=="dtUsrCajas")
{
    include '../../clases/class.usuario.php';

    $l=new Usuario();
    $registros=$l->getUsuariosCajas();
    $data = array();

    while (oci_fetch($registros)) {
        //$cont++;
        $nom = oci_result($registros, 'NOM_USR');
        $ape = oci_result($registros, 'APE_USR');
        $idUsr = oci_result($registros, 'ID_USUARIO');
        $estado= oci_result($registros, 'ESTADO');


        $arr = array($nom,$ape,$idUsr,$estado);
        array_push($data,$arr);

    }


    oci_free_statement($registros);
    echo json_encode($data);



}

if ($tipo=="actualiza") {

    include '../../clases/class.usuario.php';
    $idUsuario = $_POST['idusuario'];
    $estado = $_POST['estado'];


    $l = new Usuario();
    $registros = $l->actEstadoCajeras($idUsuario, $estado);


}
