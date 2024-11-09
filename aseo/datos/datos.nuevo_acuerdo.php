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
    //$cuotas=$_POST["cuo"];
    $datos = $l->getDatAseoInmAcuByCod($codSis);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='obtAcu') {
    include_once'../../clases/class.inmueble.php';
    $l = new Inmueble();
    //$codSis=$_POST["inm"];
    $cuotas=$_POST["cuo"];
    $categoria=$_POST["cat"];
    $acueducto=$_POST["acu"];
    $datos = $l->getDatAcuConByCod($cuotas, $categoria, $acueducto);
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
    $mot='23';
    $ent=$_POST["ent"];
    $pun=$_POST["pun"];
    $caj=$_POST["caj"];
    $ger=$_POST["ger"];
    $are='2';
    $deu=$_POST["deu"];
    $ini=$_POST["ini"];
    $rec=$_POST["rec"];
    $cuo=$_POST["cuo"];
    $pag=$_POST["pag"];
    $val=$_POST["val"];
    $mes=$_POST["mes"];
    $des=$_POST["des"];
    $sal=$_POST["sal"];
    $deuf = number_format($deu,2,',','.');
    $recf = number_format($rec,2,',','.');
    $valf = number_format($val,2,',','.');
    $mesf = number_format($mes,2,',','.');
    $salf = number_format($sal,2,',','.');
    $desf = number_format($des,2,',','.');
    $des='DEUDA $RD '.$deuf.' DESCUENTO $RD '.$desf.' INICIAL $RD '.$valf.' RECONEXION $RD '.$recf.' RESTANTE DE $RD '.$salf.' A PAGAR EN '.$cuo.' CUOTAS DE $RD '.$mesf.' SR(A): '.$ali.' CED: '.$doc.' TEL: '.$tel;
    $telnuevo = '';
    $mailnuevo = '';
    $i=new PQRs();
    $result = $i->IngresaPqr($fecha, $inm, $ali, $doc, $dir, $tel, $ema, $med, $pqr, $mot, $fecha, $des, $ent, $pun, $caj, $cod, $ger, $are, $telnuevo, $mailnuevo);

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

if($tipo=='ingDif') {
    include '../../facturacion/clases/class.facturas.php';
    $inm=$_POST["inm"];
    $pag=$_POST["pag"];
    $cuo=$_POST["cuo"];
    $ini=$_POST["ini"];
    $val=$_POST["val"];
    //$cuotaInicial = round(($pag * $ini)/100);
    //$deu=$_POST["deu"];
    //$rec=$_POST["rec"];

    $i=new facturas();
    $result = $i->aplicaDiferido($inm,$pag,$cuo,$cod,'50',$val);

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


?>
