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

if($tipo=='selPer'){
    include_once '../../clases/class.periodo.php';
    $l=new Periodo();
    $datos = $l->obtenerPeriodo();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}


if($_GET["tipo"]=="report"){
    $proyecto  = $_GET['proyecto'];
    $estado = $_GET['estado'];
    $periodo = $_GET['periodo'];
    include_once  "../../clases/class.inmueble.php";
    include_once  "../../clases/class.uso.php";
    $u = new Uso();
    $datos_usos = $u->getUsos();
    $data=array();

    $total_este=0;
    $total_norte=0;
    $total_totales=0;
    $total_unidades=0;
     while(oci_fetch($datos_usos)){
        $cod_uso = oci_result($datos_usos,'CODIGO');
        $desc_uso = oci_result($datos_usos,'DESCRIPCION');
         $inmuebles_gerencia_este=0;
         $inmuebles_gerencia_norte=0;
         $cantidad_unidades_este=0;
         $cantidad_unidades_norte=0;
         $cantidad_unidades=0;
         $suma_este_norte=0;
         $i = new Inmueble();
         $datos_gerencia_este = $i->getEstadosInmuebles($proyecto,"E",$estado,$cod_uso,$periodo);
         $i1 = new Inmueble();
         $datos_gerencia_norte = $i1->getEstadosInmuebles($proyecto,"N",$estado,$cod_uso,$periodo);

         while(oci_fetch($datos_gerencia_este)){
             $inmuebles_gerencia_este = oci_result($datos_gerencia_este,"CANTIDAD");
         }oci_free_statement($datos_gerencia_este);

         while(oci_fetch($datos_gerencia_norte)){
             $inmuebles_gerencia_norte = oci_result($datos_gerencia_norte,"CANTIDAD");
         }oci_free_statement($datos_gerencia_norte);

         $i2 = new Inmueble();
         $datos_unidades_este = $i2->getUnidadesPorUso($proyecto,$estado,$cod_uso,'E', $periodo);
         $i3 = new Inmueble();
         $datos_unidades_norte = $i2->getUnidadesPorUso($proyecto,$estado,$cod_uso,'N', $periodo);

         while(oci_fetch($datos_unidades_este)){
             $cantidad_unidades_este=oci_result($datos_unidades_este,"UNIDADES");
         }oci_free_statement($datos_unidades_este);
         while(oci_fetch($datos_unidades_norte)){
             $cantidad_unidades_norte=oci_result($datos_unidades_norte,"UNIDADES");
         }oci_free_statement($datos_unidades_norte);

         $cantidad_unidades += $cantidad_unidades_este+$cantidad_unidades_norte;

         $suma_este_norte+=$inmuebles_gerencia_este+$inmuebles_gerencia_norte;
         $arr= [$desc_uso,$inmuebles_gerencia_este,$inmuebles_gerencia_norte,$suma_este_norte,$cantidad_unidades_este,$cantidad_unidades_norte,$cantidad_unidades];
         array_push($data,$arr);
         $total_este+=$inmuebles_gerencia_este;
         $total_norte+=$inmuebles_gerencia_norte;
         $total_totales+=$suma_este_norte;
         $total_uni_este+=$cantidad_unidades_este;
         $total_uni_norte+=$cantidad_unidades_norte;
         $total_unidades+=$cantidad_unidades;

   }oci_free_statement($datos_usos);

     $arr=["<strong>TOTAL</strong>","<strong>".$total_este."</strong>","<strong>".$total_norte."</strong>", "<strong>".$total_totales."</strong>",  "<strong>".$total_uni_este."</strong>", "<strong>".$total_uni_norte."</strong>","<strong>".$total_unidades."</strong>"];
    array_push($data,$arr);

     echo json_encode($data);
}



