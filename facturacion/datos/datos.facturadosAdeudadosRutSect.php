<?php

$tip = $_POST["tip"];

if($tip=="getProyectos")
{
    include "../../clases/class.proyecto.php";
    session_start();
    $coduser      = $_SESSION['codigo'];
    $p= new Proyecto();
    $datos = $p->obtenerProyecto($coduser) ;
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);

}

if($tip=="getReporteFacturadosAdeudados")
{
  include "../../clases/class.factura.php" ;
  $f = new Factura();

  $proyecto = $_POST["proyecto"];
  $sector = $_POST["sector"];
    $ruta = $_POST["ruta"];
    $periodo = $_POST["periodo"];
  $datos = $f->getReporteFacturadosAdeudados($proyecto,$sector,$ruta,$periodo);
    $data    = array();
    while (oci_fetch($datos)) {
        $montoFacturado          = oci_result($datos, 'FACTURADO');
        $usuariosFacturados      = oci_result($datos, 'USUARIOS_FACTURADOS');
        $montoAdeudado      = oci_result($datos, 'ADEUDADO');
        $montoRecaudado = oci_result($datos, 'RECAUDOS');
        $sector      = oci_result($datos, 'ID_SECTOR');
        $ruta = oci_result($datos, 'ID_RUTA');

        $arr = array(
            $montoFacturado,
            $usuariosFacturados,
            $montoAdeudado,
            $montoRecaudado,
            $sector,
            $ruta
        );
        array_push($data, $arr);
    }
    oci_free_statement($datos);

    echo json_encode($data);
  /*echo json_encode($datos);*/

}