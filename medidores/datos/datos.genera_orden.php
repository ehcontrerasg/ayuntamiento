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

if($tipo=='sess'){
    $segundos = 1800; //si pasa este tiempo se detecta la inactividad del usuario en el sitio
    if(($_SESSION['tiempo']+$segundos) < time()) {
        session_destroy();
        echo "false";
    }else{
        $_SESSION['tiempo']=time();
        echo "true";
    }
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


if($tipo=='selMot'){
    include_once '../../clases/class.medidor.php';
    $l=new Medidor();
    $datos = $l->getMotCambio();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='selCon'){
    include_once '../../clases/class.contratista.php';
    $l=new Contratista();
    $datos = $l->getContratistas($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selOpe'){
    include_once '../../clases/class.usuario.php';
    $cont=$_POST["cont"];
    $l=new Usuario;
    $datos = $l->getUsuariosByContratista($cont);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}





if($tipo=='flexy'){
    include '../../clases/class.medidor.php';
    $proyecto = $_POST["proyecto"];
    $proIni   = $_POST["proini"];
    $proFin   = $_POST["profin"];
    $codSis   = $_POST["codsis"];
    $manIni   = $_POST["manini"];
    $manFin   = $_POST["manfin"];
    $medidor  = $_POST["medido"];
    $estInm   = $_POST["estado"];

    $l=new Medidor();
    $registros=$l->getDatInmMedByAcuProManMedEst($proyecto,$proIni,$proFin,$codSis,$manIni,$manFin,$medidor,$estInm);

    $rc = false;
    $numero=0;
    while (oci_fetch($registros)) {
        $numero++;
        $zona = oci_result($registros, 'ID_ZONA');
        $estado = oci_result($registros, 'ID_ESTADO');
        $codSis = oci_result($registros, 'CODIGO_INM');
        $direccion = oci_result($registros, 'DIRECCION');
        $medidor = oci_result($registros, 'DESC_MED');
        $serial = oci_result($registros, 'SERIAL');
        $calibre = oci_result($registros, 'DESC_CALIBRE');
        $fechAlta = oci_result($registros, 'FECHA_INSTALACION');
        if ($rc) $json2 .= ",";
        $json2 .= "\n{";
        $json2 .= "id:'".$serial."',";
        $json2 .= "cell:['<b>" .$numero."</b>'";
        $json2 .= ",'".addslashes($zona)."'";
        $json2 .= ",'".addslashes($estado)."'";
        $json2 .= ",'".addslashes($codSis)."'";
        $json2 .= ",'".addslashes($direccion)."'";
        $json2 .= ",'".addslashes($medidor)."'";
        $json2 .= ",'".addslashes($serial)."'";
        $json2 .= ",'".addslashes($calibre)."'";
        $json2 .= ",'".addslashes($fechAlta)."'";
        $json2 .= "]}";
        $rc = true;
    }
    $json = "";
    $json .= "{\n";
    $json .= "page: 1,\n";
    $json .= "total: $numero,\n";
    $json .= "rp: $numero,\n";
    $json .= "rows: [";

    $json .= $json2;
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if($tipo=='genOrd'){
    include '../../clases/class.medidor.php';
    $proyecto = $_POST["proyecto"];
    $proIni   = $_POST["proini"];
    $proFin   = $_POST["profin"];
    $codSis   = $_POST["codsis"];
    $manIni   = $_POST["manini"];
    $manFin   = $_POST["manfin"];
    $medidor  = $_POST["medido"];
    $estInm   = $_POST["estado"];
    $motivo   = $_POST["motivo"];
    $aplFact  =  $_POST["aplFact"];
    $usr_asignado   = $_POST["usr_asignado"];
    $desc   = $_POST["desc"];

    $l=new Medidor();
    $bandera=$l->generaOrdenesCambioInstMedi($proyecto,$proIni,$proFin,$codSis,$manIni,$manFin,$medidor,$estInm,$cod,$motivo,$usr_asignado,$cod,$desc,$aplFact);
    if($bandera){
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$l->getMesrror(), "cod"=>$l->getCoderror(),"res"=>"false");
    }
    echo json_encode($miArray);
}
