<?php
/**
 * Created by PhpStorm.
 * User: Jgutierrez
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


if($tipo=='obtDat') {
    include_once'../../clases/class.inmueble.php';
    $l = new Inmueble();
    $codSis=$_POST["inm"];
    $cuotas=$_POST["cuo"];
    $datos = $l->getDatInmConByCod($codSis);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='obtTar') {
    include_once'../../clases/class.inmueble.php';
    $l = new Inmueble();
    $codSis=$_POST["inm"];
    $urbaniza=$_POST["urb"];
    $medidor=$_POST["med"];
    $acueducto=$_POST["acu"];
    $datos = $l->getDatTarConByCod($codSis, $urbaniza, $medidor, $acueducto);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='obtOfi') {
    include_once'../clases/classPqrs.php';
    $l = new PQRs();
    $datos = $l->seleccionaUser($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='obtCli'){
    include_once'../../clases/class.cliente.php';
    $c = new Cliente();
    $codCli=$_POST["cli"];
    $datos = $c->getDatCliByCed($codCli);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='ingCon') {
    include '../../clases/class.contrato.php';
    $idc =   $_POST['idc'];
    $inm =   $_POST['inm'];
    $acu =   $_POST['acu'];
    $est =   $_POST['est'];
    $pro =   $_POST['pro'];
    $cat =   $_POST['cat'];
    $sec =   $_POST['sec'];
    $rut =   $_POST['rut'];
    $dir =   $_POST['dir'];
    $urb =   $_POST['urb'];
    $uso =   $_POST['uso'];
    $uni =   $_POST['uni'];
    $agu =   $_POST['agu'];
    $alc =   $_POST['alc'];
    $tpo =   $_POST['tpo'];
    $tar =   $_POST['tar'];
    $der =   $_POST['der'];
    $fia =   $_POST['fia'];
    $tot =   $_POST['tot'];
    $doc =   $_POST['doc'];
    $cli =   $_POST['cli'];
    $ali =   $_POST['ali'];
    $tel =   $_POST['tel'];
    $ema =   $_POST['ema'];
    $tdo =   $_POST['tdo'];
    $cuo =   $_POST['cuo'];
    $cup =   $_POST['cup'];
    $cta =   $_POST['cta'];
    $med =   $_POST['med'];

    $i = new Contrato();
    $result = $i->IngresaNuevoContrato($idc, $inm, $acu, $pro, $sec, $rut, $urb, $dir, $cli, $ali, $ema, $doc, $tdo, $tel, $cod, $tot, $cuo, $est, $der, $fia, $cta, $cup, $uni);

    if($result){
        if($i->getMesrror() == 0) {
            $miArray = array("error" => $i->getMesrror(), "cod" => $i->getCoderror(), "res" => "true");
        }
        else {
            $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"false");
        }
    }
    else{
        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"false");
    }
    echo json_encode($miArray);

}

if($tipo=='selDoc') {
    include_once '../../clases/class.documento.php';
    $l=new Documento();
    $datos = $l->obtenerTipoDoc();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

?>