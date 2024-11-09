<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 7/18/2016
 * Time: 12:14 PM
 */

$tipo = $_POST['tip'];
session_start();
$cod=$_SESSION['codigo'];

if($tipo=='genOrd') {
    include_once'../../clases/class.corte.php';
    $l = new Corte();

    $mot=$_POST["motivo"];
    $inm=$_POST["inmueble"];
    $oper=$_POST["operario"];


    $result = $l->generaCorte($inm,$cod,$mot,$oper);
    if($result){
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"false");

    }
    echo json_encode($miArray);

}

if($tipo=='selUsu') {
    $pro=$_POST["pro"];

        include_once '../../clases/class.usuario.php';
        $l=new Usuario();
        $datos = $l->getOperariosCorteByPro($pro);
        $i=0;
        while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $con[$i]=$row;
            $i++;
        }
        echo json_encode($con);


}

if($tipo=='selPro'){
    include_once '../../clases/class.proyecto.php';
    $l=new Proyecto();
    $datos = $l->obtenerProyecto($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


