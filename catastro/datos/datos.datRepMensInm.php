<?php
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];
include_once "../clases/class.inmueble.php";

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


if($tipo=='repFac'){
    $periodo = $_POST['per'];
    $proyecto = $_POST['pro'];
    $i=new Inmnueble();
    $dat=$i->obtenerInmueblesFacturadosPerdiodo($periodo,$proyecto);
    $fp = fopen( 'php://temp/maxmemory:'. (24*1024*1024) , 'r+' );
    $fila[0]=utf8_decode("Inmueble");
    $fila[1]=utf8_decode("Cliente");
    $fila[2]=utf8_decode("Proceso");
    fputcsv( $fp, $fila );
    while(oci_fetch($dat)){

        $fila[0]=utf8_decode(oci_result($dat,"INMUEBLE"));
        $fila[1]=utf8_decode(oci_result($dat,"NOMBRE"));
        $fila[2]=utf8_decode(oci_result($dat,"PROCESO"));
        fputcsv( $fp, $fila );
    }

    rewind( $fp );
    $output = stream_get_contents( $fp );
    // cerrar archivo
    fclose( $fp );
    // cabeceras HTTP:
    // tipo de archivo y codificación
    header('Content-Type: text/csv; charset=utf-8');
    // forzar descarga del archivo con un nombre de archivo determinado
    header('Content-Disposition: attachment; filename=InmueblesFacturados-'.$proyecto.'-'.  $periodo .'.csv' );
    // indicar tamaño del archivo
    header('Content-Length: '. strlen($output) );
    // enviar archivo
    echo $output;
    exit;
}

if($tipo=='repFac'){
    $periodo = $_POST['per'];
    $proyecto = $_POST['pro'];
    $i=new Inmnueble();
    $dat=$i->obtenerInmueblesFacturadosPerdiodo($periodo,$proyecto);
    $fp = fopen( 'php://temp/maxmemory:'. (24*1024*1024) , 'r+' );
    $fila[0]=utf8_decode("Inmueble");
    $fila[1]=utf8_decode("Cliente");
    $fila[2]=utf8_decode("Proceso");
    fputcsv( $fp, $fila );
    while(oci_fetch($dat)){

        $fila[0]=utf8_decode(oci_result($dat,"INMUEBLE"));
        $fila[1]=utf8_decode(oci_result($dat,"NOMBRE"));
        $fila[2]=utf8_decode(oci_result($dat,"PROCESO"));
        fputcsv( $fp, $fila );
    }

    rewind( $fp );
    $output = stream_get_contents( $fp );
    // cerrar archivo
    fclose( $fp );
    // cabeceras HTTP:
    // tipo de archivo y codificación
    header('Content-Type: text/csv; charset=utf-8');
    // forzar descarga del archivo con un nombre de archivo determinado
    header('Content-Disposition: attachment; filename=InmueblesFacturados-'.$proyecto.'-'.  $periodo .'.csv' );
    // indicar tamaño del archivo
    header('Content-Length: '. strlen($output) );
    // enviar archivo
    echo $output;
    exit;
}

if($tipo=='repCat'){
    $proyecto = $_POST['pro'];
    $i=new Inmnueble();
    $dat=$i->obtenerInmueblesCatastrados($proyecto);
    $fp = fopen( 'php://temp/maxmemory:'. (24*1024*1024) , 'r+' );
    $fila[0]=utf8_decode("Inmueble");
    $fila[1]=utf8_decode("Cliente");
    $fila[2]=utf8_decode("Proceso");
    fputcsv( $fp, $fila );
    while(oci_fetch($dat)){

        $fila[0]=utf8_decode(oci_result($dat,"INMUEBLE"));
        $fila[1]=utf8_decode(oci_result($dat,"NOMBRE"));
        $fila[2]=utf8_decode(oci_result($dat,"PROCESO"));
        fputcsv( $fp, $fila );
    }

    rewind( $fp );
    $output = stream_get_contents( $fp );
    // cerrar archivo
    fclose( $fp );
    // cabeceras HTTP:
    // tipo de archivo y codificación
    header('Content-Type: text/csv; charset=utf-8');
    // forzar descarga del archivo con un nombre de archivo determinado
    header('Content-Disposition: attachment; filename=InmueblesCatastrados-'.$proyecto.'.csv' );
    // indicar tamaño del archivo
    header('Content-Length: '. strlen($output) );
    // enviar archivo
    echo $output;
    exit;
}
//$tipo='repInc';
if($tipo=='repInc'){
    $periodo = $_POST['per'];
    $proyecto = $_POST['pro'];
    //$periodo = 201605;
    $i=new Inmnueble();
    $dat=$i->obtenerInmueblesIncorporadosPerdiodo($periodo,$proyecto);
    $fp = fopen( 'php://temp/maxmemory:'. (24*1024*1024) , 'r+' );
    $fila[0]=utf8_decode("Inmueble");
    $fila[1]=utf8_decode("Cliente");
    $fila[2]=utf8_decode("Fecha");
    $fila[3]=utf8_decode("Proceso");
    $fila[4]=utf8_decode("Sector");
    $fila[5]=utf8_decode("Ruta");
    $fila[6]=utf8_decode("Tarifa");
    $fila[7]=utf8_decode("Codigo Tarifa");
    $fila[8]=utf8_decode("Uso");
    fputcsv( $fp, $fila );
    while(oci_fetch($dat)){

        $fila[0]=utf8_decode(oci_result($dat,"INMUEBLE"));
        $fila[1]=utf8_decode(oci_result($dat,"NOMBRE"));
        $fila[2]=utf8_decode(oci_result($dat,"FECHA"));
        $fila[3]=utf8_decode(oci_result($dat,"PROCESO"));
        $fila[4]=utf8_decode(oci_result($dat,"SECTOR"));
        $fila[5]=utf8_decode(oci_result($dat,"RUTA"));
        $fila[6]=utf8_decode(oci_result($dat,"TARIFA"));
        $fila[7]=utf8_decode(oci_result($dat,"CODIGO_TARIFA"));
        $fila[8]=utf8_decode(oci_result($dat,"USO"));
        fputcsv( $fp, $fila );
    }

    rewind( $fp );
    $output = stream_get_contents( $fp );
    // cerrar archivo
    fclose( $fp );
    // cabeceras HTTP:
    // tipo de archivo y codificación
    header('Content-Type: text/csv; charset=utf-8');
    // forzar descarga del archivo con un nombre de archivo determinado
    header('Content-Disposition: attachment; filename=InmueblesIncorporados-'.$proyecto.'-'. $periodo .'.csv' );
    // indicar tamaño del archivo
    header('Content-Length: '. strlen($output) );
    // enviar archivo
    echo $output;
    exit;
}



//$tipo='repInc';
if($tipo=='repRes'){
    $periodo = $_POST['per'];
    $proyecto = $_POST['pro'];
    $fp = fopen( 'php://temp/maxmemory:'. (24*1024*1024) , 'r+' );
    fputs( $fp,"TIPO\t\t\tGERENCIA\tINMUEBLES");
    fputs($fp,"\n--------------------------------------");
    $i=new Inmnueble();
    $totf=0;
    $totf+=$tot=$i->obtenerCantInmCatGer($proyecto,'E');
    fputs( $fp,"\nCATASTRADOS\t\tE\t\t\t$tot");
    $i=new Inmnueble();
    $totf+=$tot=$i->obtenerCantInmCatGer($proyecto,'N');
    fputs( $fp,"\nCATASTRADOS\t\tN\t\t\t$tot");
    fputs($fp,"\n--------------------------------------");
    fputs($fp,"\n\t\t\t\tTOTAL:\t\t$totf");
    fputs($fp,"\n");


    fputs($fp,"\n--------------------------------------");
    $i=new Inmnueble();
    $totf=0;
    $totf+=$tot=$i->obtenerCantInmFacGer($proyecto,'E',$periodo);
    fputs( $fp,"\nFACTURADOS\t\tE\t\t\t$tot");
    $i=new Inmnueble();
    $totf+=$tot=$i->obtenerCantInmFacGer($proyecto,'N',$periodo);
    fputs( $fp,"\nFACTURADOS\t\tN\t\t\t$tot");
    fputs($fp,"\n--------------------------------------");
    fputs($fp,"\n\t\t\t\tTOTAL:\t\t$totf");
    fputs($fp,"\n");


    fputs($fp,"\n--------------------------------------");
    $i=new Inmnueble();
    $totf=0;
    $totf+=$tot=$i->obtenerCantInmIncGer($proyecto,'E',$periodo);
    fputs( $fp,"\nINCORPORADOS\tE\t\t\t$tot");
    $i=new Inmnueble();
    $totf+=$tot=$i->obtenerCantInmIncGer($proyecto,'N',$periodo);
    fputs( $fp,"\nINCORPORADOS\tN\t\t\t$tot");
    fputs($fp,"\n--------------------------------------");
    fputs($fp,"\n\t\t\t\tTOTAL:\t\t$totf");
    fputs($fp,"\n");
    fputs($fp,"\n--------------------------------------");
    rewind( $fp );
    $output = stream_get_contents( $fp );
    // cerrar archivo
    fclose( $fp );
    // cabeceras HTTP:
    // tipo de archivo y codificación
    header('Content-Type: text/txt; charset=utf-8');
    // forzar descarga del archivo con un nombre de archivo determinado
    header('Content-Disposition: attachment; filename=ResumenGeneralInmuebles-'.$proyecto.'-'. $periodo .'.txt' );
    // indicar tamaño del archivo
    header('Content-Length: '. strlen($output) );
    // enviar archivo
    echo $output;
    exit;
}

if($tipo=='selPro'){
    include_once '../clases/class.proyecto.php';
    $l=new Proyecto();
    $datos = $l->obtenerproyectos($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}



