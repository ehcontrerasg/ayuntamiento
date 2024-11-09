<?php
/**
 * Created by PhpStorm.
 * User: ehcontrerasg
 * Date: 7/8/2016
 * Time: 11:01 AM
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


if($tipo=='ingSf') {
    include_once'../clases/class.facturas.php';
    $fact =    $_POST['fac'];
    $saldo =   $_POST['sal'];
    $obs =   $_POST['obs'];
    $numfac =   $_POST['numFac'];
    $inm=   $_POST['inm'];


    $i = new facturas();
    $result = $i->IngresaSalFavFac($inm,$fact,$obs,$numfac,$cod,$saldo);
    if($result){
        $miArray=array("error"=>$i->getmsgresult(), "cod"=>$i->getcodresult(),"res"=>"true");
    }else{
        $miArray=array("error"=>$i->getmsgresult(), "cod"=>$i->getcodresult(),"res"=>"false");

    }
    echo json_encode($miArray);

}


if($tipo=='ingSfInm') {
    include_once'../clases/class.facturas.php';
    $val =    $_POST['val'];
    $obs =   $_POST['obs'];
    $numfac =   $_POST['numFac'];
    $inm=   $_POST['inm'];


    $i = new facturas();
    $result = $i->IngresaSalFavInm($val,$obs,463,$inm,'R',$cod,$val,'','');
    if($result){
        $miArray=array("error"=>$i->getmsgresult(), "cod"=>$i->getcodresult(),"res"=>"true");
    }else{
        $miArray=array("error"=>$i->getmsgresult(), "cod"=>$i->getcodresult(),"res"=>"false");

    }
    echo json_encode($miArray);

}



if($tipo=='flexy'){
    include '../clases/class.facturas.php';
    $inm = $_POST["inm"];

    $l=new facturas();
    $registros=$l->getFacPagByInm($inm);

    $rc = false;
    $numero=0;
    while (oci_fetch($registros)) {
        $numero++;
        $factura = oci_result($registros, 'CONSEC_FACTURA');
        $periodo = oci_result($registros, 'PERIODO');
        $fecha = oci_result($registros, 'FEC_EXPEDICION');
        $valor = oci_result($registros, 'TOTAL');
        $ncf = oci_result($registros, 'NCF_CONSEC');
        $sf= oci_result($registros, 'SALD_FAVOR');
        $obs= addslashes( oci_result($registros, 'OBSERVACION_REL'));

        if ($rc) $json2 .= ",";
        $json2 .= "\n{";
        $json2 .= "id:'".$factura."',";
       // $json2 .= "title:'".$obs."',";
        $json2 .= "cell:['<b>" .$numero."</b>'";
        $json2 .= ",'".addslashes($factura)."'";
        $json2 .= ",'".addslashes($periodo)."'";
        $json2 .= ",'".addslashes($fecha)."'";
        $json2 .= ",'".addslashes($ncf)."'";
        $json2 .= ",'".addslashes($valor)."'";
        $json2 .= ",'".addslashes($sf)."'";
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

if($tipo=='lleDat'){
    include_once '../clases/class.facturas.php';
    $fac=$_POST["fac"];
    $l=new facturas();
    $datos = $l->getValPerPagFactByRangFact($fac);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='lleDatInm'){
    include_once '../clases/class.facturas.php';
    $inm=$_POST["inm"];
    $l=new facturas();
    $datos = $l->getValPerFactPendByInm($inm);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


