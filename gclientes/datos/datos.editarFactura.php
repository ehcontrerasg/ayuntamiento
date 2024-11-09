<?php

$tip=$_POST["tip"];

if($tip=="obtenerDatosFactura"){

    $factura=$_POST["factura"];
   // echo $factura;
    include_once "../../clases/class.factura.php";
    $f = new Factura();

    $datos=$f->datosFactura($factura);

    $data=[];
    while(oci_fetch($datos)){
        $tipo_documento = oci_result($datos,"TIPODOC");
        $numero_documento = oci_result($datos,"DOCUMENTO");
        $nombre_usuario = oci_result($datos,"ALIAS");
        $ncf = oci_result($datos,"NCF");
        $titulo_comprobante = oci_result($datos,"MSJ_NCF");
        $fecha_vencimiento_ncf = oci_result($datos,"VENCE_NCF");
        $arr=array($tipo_documento,$numero_documento,$nombre_usuario,$ncf,$titulo_comprobante,$fecha_vencimiento_ncf);
        array_push($data,$arr);
    }



    echo json_encode($data);
}

if($tip=="getTiposDocumento"){

    include_once "../../catastro/clases/class.tipodoc.php";

    $d=new Documento();

    $datos= $d->Todos();

    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}