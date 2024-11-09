<?php

session_start();
$tipo = $_POST['tip'];
$cod=$_SESSION['codigo'];



if($tipo=='selPro'){
    include_once '../../clases/class.proyecto.php';
    $l=new Proyecto();
    $datos = $l->obtenerproyecto($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($tipo=='selEst'){
    include_once '../clases/class.inmuebles.php';
    $l=new inmuebles();
    $datos = $l->obtenerEstado();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if ($_GET["tipo"]=="report")
{
    include_once '../clases/class.inmuebles.php';

    $proyecto  = $_GET['proyecto'];
    $estado = $_GET['estado'];

    $inmuebles = new inmuebles();

    $reporte = $inmuebles->getEstadosInmuebles($proyecto,$estado);
    $data = array();
    $sumaE=0;
    $sumaN=0;
    while(oci_fetch($reporte))
    {
        $sector= oci_result($reporte,"ID_SECTOR");
        $idProyecto = oci_result($reporte,"ID_PROYECTO");
        $uso = oci_result($reporte,"USO");
        $e  = oci_result($reporte,"'E'");
        $n  = oci_result($reporte,"'N'");

        $sumaE+=$e;
        $sumaN+=$n;



        $arr = array($idProyecto,$uso,$e+$n);
        array_push($data,$arr);

    }
    $arr = array('','<strong>TOTAL</strong>',$sumaE+$sumaN);
    array_push($data,$arr);
    oci_free_statement($reporte);
    echo json_encode($data);



}
