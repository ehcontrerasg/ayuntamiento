<?php
/**
 * Created by PhpStorm.
 * User: Jgutierrez
 * Date: 7/18/2016
 * Time: 12:14 PM
 */

$tipo = $_POST['tip'];
$cuotas=$_POST["cuo"];
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
    $datos = $l->getDatInmAmnByCod($codSis);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='obtAmn') {
    include_once'../../clases/class.inmueble.php';
    $l = new Inmueble();
    //$codSis=$_POST["inm"];
    $cuo=$_POST["cuo"];
    $categoria=$_POST["cat"];
    $acueducto=$_POST["acu"];
    $tarifa=$_POST["tar"];
    $actividad=$_POST["act"];
    $datos = $l->getDatAmnConByCod($cuo, $categoria, $acueducto, $tarifa, $actividad);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='obtRec') {
    include_once'../../clases/class.reconexion.php';
    $l = new Reconexion();
    $codSis=$_POST["inm"];
    $estado=$_POST["est"];

    if($estado == 'PC' || $estado == 'SS') {
        $datos = $l->getValRecByByInmConsGen($codSis);
        $i = 0;
        while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $con[$i] = $row;
            $i++;
        }
        echo json_encode($con);
    }
}


if($tipo=='obtOfi') {
    include_once'../clases/classPqrs.php';
    $l = new PQRs();
    $datos = $l->seleccionaUserAcuerdo($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
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

if($tipo=='selCal') {
    include_once '../../clases/class.documento.php';
    $l=new Documento();
    $datos = $l->obtenerEnCalidad();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='ingPqr') {
    include '../clases/classPqrs.php';
    $fecha = '';
    $inm=$_POST["inm"];
    $ali=$_POST["ali"];
    $doc=$_POST["doc"];
    $dir=$_POST["dir"];
    $tel=$_POST["tel"];
    $ema=$_POST["ema"];
    $med='PS';
    $pqr='2';
    $mot=108;
    $ent=$_POST["ent"];
    $pun=$_POST["pun"];
    $caj=$_POST["caj"];
    $ger=$_POST["ger"];
    $are='2';
    $deu=$_POST["deu"];
    $dpo=$_POST["dpo"];
    $ini=$_POST["ini"];
    $rec=$_POST["rec"];
    $cuo=$_POST["cuo"];
    $pag=$_POST["pag"];
    $apa=$_POST["apa"];
    $valorCuota = round($apa / $cuo);
    $cuotaTotal = $cuo - 1;
    $cuotaInicial = $valorCuota;
    if($cuotaTotal == 0){
        $valorCuota = 0;
    }
    $deuf = number_format($deu+$dpo,2,',','.');
    $recf = number_format($rec,2,',','.');
    $apaf = number_format($apa,2,',','.');
    $valorCuotaf = number_format($valorCuota,2,',','.');
    $cuotaInicialf = number_format($cuotaInicial,2,',','.');
    $des='DEUDA $RD '.$deuf.' REDUCIDA A $RD '.$apaf .' A PAGAR EN UNA CUOTA INICIAL DE $RD '.$cuotaInicialf.' MAS RECONEXION DE $RD '.$recf.' EL RESTANTE A PAGAR EN '.$cuotaTotal.' CUOTAS DE $RD '.$valorCuotaf.' SR(A): '.$ali.' CED: '.$doc.' TEL: '.$tel;

    $i=new PQRs();
    $result = $i->IngresaPqr($fecha, $inm, $ali, $doc, $dir, $tel, $ema, $med, $pqr, $mot, $fecha, $des, $ent, $pun, $caj, $cod, $ger, $are, $tel, $ema);

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

if($cuotas >= 1){
    if($tipo=='ingDif') {
        include '../../facturacion/clases/class.facturas.php';
        $inm=$_POST["inm"];
        $apa=$_POST["apa"];
        $cuo=$_POST["cuo"];
        $cuotaInicial = round($apa/$cuo);
        $cuotas = $cuo - 1;
        $total = $apa - $cuotaInicial;

        //if($cuotas >= 1){
            $i=new facturas();
            $result = $i->aplicaDiferidoAmnistia($inm,$total,$cuotas,$cod,'50',$cuotaInicial);

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
        //}
    }
}



?>
