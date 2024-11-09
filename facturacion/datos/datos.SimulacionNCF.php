<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 7/18/2016
 * Time: 12:14 PM
 */

extract($_POST);

session_start();
$cod=$_SESSION['codigo'];


/*if($tipo=='selPro'){
    include_once '../../clases/class.proyecto.php';


    $l=new Proyecto();
    $datos = $l->obtenerProyecto($cod) ;
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}*/
    switch($tip)
    {
        case 'selPro':
            include_once '../../clases/class.proyecto.php';


            $l=new Proyecto();
            $datos = $l->obtenerProyecto($cod) ;
            $i=0;
            while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
                $con[$i]=$row;
                $i++;
            }
            echo json_encode($con);

            break;

        case 'selZon':
            include_once '../../clases/class.zona.php';


            $l=new Zona();
            $datos = $l->getZonByPro($pro,$parcial) ;
            $i=0;
            while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
                $con[$i]=$row;
                $i++;
            }
            echo json_encode($con);

            break;

        case 'selraizNCF':
            include_once '../../clases/class.ncf.php';


            $l=new NCF();
            $datos = $l->getRaizNCF() ;
            $i=0;
            while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
                $con[$i]=$row;
                $i++;
            }
            echo json_encode($con);

            break;


    }




