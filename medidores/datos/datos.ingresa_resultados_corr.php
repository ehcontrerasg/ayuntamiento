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


if($tipo=='lleDat'){
    include_once '../../clases/class.inmueble.php';
    $inm=$_POST["inm"];
    $l=new Inmueble();
    $datos = $l->getMantMedCorrByInm($inm);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='ingOrd') {
    include_once'../../clases/class.medidor.php';
    $orden =    $_POST['orden'];
    $fecMan =   $_POST['fecMant'];
    $med =      $_POST['marcMed'];
    $cal =      $_POST['calMed'];
    $ser =      $_POST['serMed'];
    $lect =     $_POST['lect'];
    $emp =      $_POST['empMed'];
    $obIns=     $_POST['ObsMan'];
    $obLecIns=  $_POST['ObsGen'];
    $listAct =  $_POST['act'];
    $inm =  $_POST['codSis'];
    $array =json_decode($listAct,true);
    $i = new Medidor();
    $result = $i->IngresaOrdMantCorMed($cod,$orden,'W',$fecMan,$med,$cal,$ser,$lect,$emp,$obIns,$obLecIns,0,0,$inm,$listAct);
    if($result){
        foreach ($array as $subarray){
            $i2 = new Medidor();
            $result2=$i2->IngresaOrdMantActMedCor( $subarray['codigo'],$orden);
            if($result2){

            }
            else{
                $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"false");
                echo json_encode($miArray);
                return;

            }
        }
        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"false");

   }
    echo json_encode($miArray);




}


if($tipo=='obtListAct'){
    include_once '../../clases/class.medidor.php';
    $inm=$_POST["inm"];
    $l=new Medidor();
    $datos = $l->getActMantCor();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selCal'){
    include_once './../../clases/class.calibre.php';
    $l=new Calibre();
    $datos = $l->getCalibres();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selEmp'){
    include_once './../../clases/class.emplazamiento.php';
    $l=new Emplazamiento();
    $datos = $l->getEmplazamiento();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='selMed'){
    include_once './../../clases/class.marcaMedidor.php';
    $l=new MarcaMedidor();
    $datos = $l->getMarcMed();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

//
//if($tipo=='ingOrd') {
//    include_once'../clases/class.medidor_inmueble.php';
//    $orden =    $_POST['ord'];
//    $obsLec =   $_POST['obsLec'];
//    $fecMan =   $_POST['fecMan'];
//    $med =      $_POST['med'];
//    $cal =      $_POST['cal'];
//    $ser =      $_POST['ser'];
//    $lect =     $_POST['lect'];
//    $emp =      $_POST['emp'];
//    $entUsr =   $_POST['entUsr'];
//    $obIns=     $_POST['obIns'];
//    $obLecIns=  $_POST['obLecIns'];
//    $inm=       $_POST['inm'];
//    $listAct =  $_POST['listaAct'];
//    $array =json_decode($listAct,true);
//
//    $i = new Medidor_inmueble();
//    $result = $i->IngresaOrdMantMed($cod,$orden,'W',$fecIns,$med,$cal,$ser,$lect,$emp,$obIns,$obLecIns,0,0,$inm);
//    if($result){
//        foreach ($array as $subarray){
//            $i2 = new Medidor_inmueble();
//            $result2=$i2->IngresaOrdMantACTMed( $subarray['codigo'],$orden);
//            if($result2){
//
//            }
//            else{
//                $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"false");
//                return;
//            }
//        }
//        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"true");
//    }else{
//        $miArray=array("error"=>$i->getMesrror(), "cod"=>$i->getCoderror(),"res"=>"false");
//
//    }
//    echo json_encode($miArray);
//
//}
//
