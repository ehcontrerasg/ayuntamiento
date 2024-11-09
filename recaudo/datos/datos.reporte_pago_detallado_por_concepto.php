<?php
if($tip=="selPro")
{
    include '../../clases/class.proyecto.php';
    $l=new Reportes();
    $datos = $l->seleccionaAcueducto();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $acu[$i]=$row;
        $i++;
    }
    echo json_encode($acu);
}


if($tip=="con")
{
    include '../clases/classPagos.php';
    $l=new Pagos();
    $datos = $l->seleccionaConceptoTotal();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}