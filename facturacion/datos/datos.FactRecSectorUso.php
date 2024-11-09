<?php
$tip = $_POST["tip"];


if($tip=="getProyectos") {
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

if($tip=="getUsos"){
    include "../../clases/class.uso.php";
    $p= new Uso();
    $datos = $p->getUsos();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);

}

if($tip=="getReporteFacturadosAdeudados"){

    $periodo=$_POST["periodo"];
    $sector=$_POST["sector"];
    $proyecto=$_POST["proyecto"];
    $uso=$_POST["uso"];

    include "../../clases/class.factura.php";
    $f = new Factura();
    $facturas = $f->getReporteFacturasSectorUso($periodo,$sector,$proyecto,$uso);
    $data=array();
    /*$arr=[];*/

    while (oci_fetch($facturas)){
        $importe=0;
        $usuarios_recaudo=0;
        $facturado= oci_result($facturas,"FACTURADO");
        $polizas_afectadas = oci_result($facturas,"POLIZAS_AFECTADAS");
        $id_uso = oci_result($facturas,"ID_USO");
        $desc_uso = oci_result($facturas,"DESC_USO");

        $p= new Factura();
        $pago = $p->getReportePagosSectorUso($periodo,$sector,$id_uso);

        while(oci_fetch($pago)){
            $importe += oci_result($pago,"IMPORTE");
            $usuarios_recaudo += oci_result($pago,"POLIZAS_AFECTADAS");
        }oci_free_statement($pago);

        $r= new Factura();
        $recaudo = $r->getReporteRecaudoSectorUso($periodo,$sector,$id_uso);

        while(oci_fetch($recaudo)){
            $importe += oci_result($recaudo,"IMPORTE");
            $usuarios_recaudo += oci_result($recaudo,"POLIZAS_AFECTADAS");
        }oci_free_statement($recaudo);

        $arr= array(number_format($facturado,'2','.',','),$polizas_afectadas,number_format($importe,'2','.',','),$usuarios_recaudo,$desc_uso);
        array_push($data,$arr);
    }
    oci_free_statement($facturas);

    /*array_push($data,$arr);*/
    echo json_encode($data);

}