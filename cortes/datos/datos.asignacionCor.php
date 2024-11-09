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

if($tipo=="selPer")
{
    include_once '../../clases/class.periodo.php';
    $q=new Periodo();
    $pro=$_POST['pro'];
    $datos = $q->getPerCortAbieByPer($pro);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $sectores[$i]=$row;
        $i++;
    }
    echo json_encode($sectores);
}


if($tipo=="selUso")
{
    include_once '../../clases/class.uso.php';
    $q=new Uso();
    $pro=$_POST['pro'];
    $per=$_POST['per'];
    $zon=$_POST['zon'];
    $datos = $q->getUsoCortByProPerZon($pro,$per,$zon);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $sectores[$i]=$row;
        $i++;
    }
    echo json_encode($sectores);
}


if($tipo=="selCat")
{
    include_once '../../clases/class.uso.php';
    $q=new Uso();
    $pro=$_POST['proyecto'];
    $per=$_POST['per'];
    $zon=$_POST['zon'];
    $datos = $q->getCatCortByProPerZon($pro,$per,$zon);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $sectores[$i]=$row;
        $i++;
    }
    echo json_encode($sectores);
}

if($tipo=="selZon")
{
    include_once '../../clases/class.zona.php';
    $q=new Zona();
    $pro=$_POST['pro'];
    $per=$_POST['per'];
    $datos = $q->getZonCortByProPer($pro,$per);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $zonas[$i]=$row;
        $i++;
    }
    echo json_encode($zonas);
}

if($tipo=='selCal'){
    include_once './../../clases/class.calibre.php';
    $pro=$_POST['pro'];
    $l=new Calibre();
    $datos = $l->getCalibresAll($pro);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=="genRut")
{
    include_once '../../clases/class.corte.php';
    $q=new Corte();
    $zon = $_POST['zona'];
    $facVencIni = $_POST['facturasIni'];
    $facVencFin = $_POST['facturasFin'];
    $deudaIni = $_POST['deudaIni'];
    $deudaFin = $_POST['deudaFin'];
    $uso = $_POST['uso'];
    $uniIni = $_POST['unidadesIni'];
    $uniFin = $_POST['unidadesFin'];
    $pro = $_POST['proyecto'];
    $calibre = $_POST['diametro'];
    $categoria = $_POST['selCat'];
    $datos = $q->getCortAbiByPerZonFacMontUsoUni($pro,$zon,$facVencIni,$facVencFin,$deudaIni,$deudaFin,$uso,$uniIni,$uniFin,$calibre,$categoria);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $zonas[$i]=$row;
        $i++;
    }
    echo json_encode($zonas);
}


if($tipo=="selOpe")
{
    include_once '../../clases/class.usuario.php';
    $q=new Usuario();
    $pro = $_POST['pro'];

    $datos = $q->getOperariosCorteByPro($pro);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $oper[$i]=$row;
        $i++;
    }
    echo json_encode($oper);
}


if($tipo=="asig")
{
    include_once '../../clases/class.corte.php';
    $q=new Corte();
    $pro = $_POST['proyecto'];
    $zon = $_POST['zona'];
    $uso = $_POST['uso'];
    $facIni = $_POST['facturasIni'];
    $facFin = $_POST['facturasFin'];
    $deudaIni = $_POST['deudaIni'];
    $deudaFin = $_POST['deudaFin'];
    $uniIni = $_POST['unidadesIni'];
    $uniFin = $_POST['unidadesFin'];
    $usu = $_POST['usu'];
    $rut = $_POST['rut'];
    $med = $_POST['med'];
    $fech = $_POST['fechPal'];
    $usuViejo = $_POST['usuViejo'];
    $calibre = $_POST['diametro'];
    $categoria = $_POST['cat'];


    $datos = $q->asignaCorteByZonRutOperFacDeuUsoUniMed($zon,$rut,$usu,$cod,$facIni,$facFin,$deudaIni,$deudaFin,$uso,$uniIni,$uniFin,$med,$fech,$usuViejo,$calibre,$categoria);
    if($datos){
        $miArray=array("error"=>$q->getMesrror(), "cod"=>$q->getCoderror(),"res"=>"true");
    }else{
        $miArray=array("error"=>$q->getMesrror(), "cod"=>$q->getCoderror(),"res"=>"false");
    }
    echo json_encode($miArray);
}

















