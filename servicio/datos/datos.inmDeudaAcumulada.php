<?php


include_once '../../clases/class.factura.php';

$tipo=$_POST['tipo'];


if ($_POST["tipo"]=="report")
{

    $codInm=$_POST['codInmuble'];
    $proyecto=$_POST['proyecto'];


    $l=new Factura();
    $registros=$l->obtenerDeudaAcumuldaInm($codInm,$proyecto);
    $data = array();
    $sumaTT =0;
    $sumaTC =0;
    $sumaTD =0;
    $sumaTP =0;

    $deudaacumulada=0;
    while (oci_fetch($registros)) {
        //$cont++;
        $deuda=0;
        $consec = oci_result($registros, 'CONSEC_FACTURA');
        $periodo = oci_result($registros, 'PERIODO');
        $total = oci_result($registros, 'TOTAL');
        $totalC = oci_result($registros, 'TOTAL_CREDITO');
        $totalD = oci_result($registros, 'TOTAL_DEBITO');
        $totalP = oci_result($registros, 'TOTAL_PAGADO');

        $sumaTT += oci_result($registros, 'TOTAL');
        $sumaTC += oci_result($registros, 'TOTAL_CREDITO');
        $sumaTD += oci_result($registros, 'TOTAL_DEBITO');
        $sumaTP += oci_result($registros, 'TOTAL_PAGADO');
        $deuda=$total-$totalP+$totalD-$totalC;
        $deudaacumulada+=$deuda;


        $arr = array($consec, $periodo,$total,$totalP, $totalC,$totalD,$deuda,$deudaacumulada);
        array_push($data,$arr);

    }



    oci_free_statement($registros);
    echo json_encode($data);



}

?>