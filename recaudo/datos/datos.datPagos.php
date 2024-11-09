<?php
/**
 * Created by PhpStorm.
 * User: Edwin
 * Date: 23/06/2016
 * Time: 3:21 PM
 */
//error_reporting(E_ALL);
session_start();
 $tip=$_REQUEST["tip"];
date_default_timezone_set('America/Santo_Domingo');
setlocale(LC_MONETARY, 'es_DO');


if($tip=="entPago")
{
    $ent=$_REQUEST["ent"];
    include '../clases/classPagos.php';
    $a= new Pagos();
    $datos=$a->obtenerDescEnt($ent);
    if(oci_fetch($datos)){
        $descent=oci_result($datos,"DESC_ENTIDAD");
    }
    echo $descent;
}


if($tip=="punPago")
{
    $ent=$_REQUEST["ent"];
    $pun=$_REQUEST["pun"];
    include '../clases/classPagos.php';
    $a= new Pagos();
    $datos=$a->obtenerDescPunto($ent,$pun);
    if(oci_fetch($datos)){
        $descpun=oci_result($datos,"DESCRIPCION");
    }
    echo $descpun;
}

if($tip=="tipoPago")
{
    include '../../clases/class.pago.php';
    $idPago= $_POST["idPago"];
    $recOPago = $_POST["rec"];
    $medioPagos= new Pago();
    $datos=$medioPagos->getMedioPagoByPago($idPago,$recOPago);
   /* $datos=$medioPagos->getMedioPagoByPagoOtrosRecaudos($idPago);*/
    /*$formaPago = '';*/
    if(oci_fetch($datos)){
    $formaPago=oci_result($datos,"DESCRIPCION");
    }
   /*  if($formaPago=='')
    {    $medioPagosOtrosRecaudos= new Pago();
        $datosOtrosRecaudos=$medioPagosOtrosRecaudos->getMedioPagoByPagoOtrosRecaudos($idPago);
        if(oci_fetch($datosOtrosRecaudos)){
            $formaPago=oci_result($datosOtrosRecaudos,"DESCRIPCION");
        }
           }*/

    echo $formaPago;

}

if($tip=="selTipoPago")
{
    include '../../clases/class.pago.php';
    $excluirTipoPago = $_POST["excluirTipoPago"];
    $medioPagos= new Pago();
    $datos = $medioPagos->getTiposPagos($excluirTipoPago);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $tiposPagos[$i]=$row;
        $i++;
    }
    echo json_encode($tiposPagos);

}

if ($tip=="getParametros")
{
    include '../../clases/class.pago.php';
    $pago= new Pago();
    $idMedPago = $_POST["idMedPago"];
    $parametrosPagos = $pago->getParametros($idMedPago);
    $i=0;
    while ($row = oci_fetch_array($parametrosPagos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $campos[$i]=$row;
        $i++;
    }
    echo json_encode($campos);
}

if ($tip=="selBancos")
{
    include '../../clases/class.pago.php';
    $pago= new Pago();
    $parametrosPagos = $pago->getBancos();
    $i=0;
    while ($row = oci_fetch_array($parametrosPagos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $campos[$i]=$row;
        $i++;
    }
    echo json_encode($campos);
}

if ($tip=="selTarjetas")
{
    include '../../clases/class.pago.php';
    $pago= new Pago();
    $parametrosPagos = $pago->getTarjetas();
    $i=0;
    while ($row = oci_fetch_array($parametrosPagos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $campos[$i]=$row;
        $i++;
    }
    echo json_encode($campos);
}



if ($tip=="procesar")
{
    include '../../clases/class.pago.php';
    $pago= new Pago();

    $contador=$_POST['controlesCreados'];
    $listaCod='';
    $listaVal='';
    $idPago = $_POST['idPago'];
    $rec = $_POST['rec'];
    $idFormaPago = $_POST['idFormaPago'];

    for( $i=0;$i<$contador;$i++){
        $listaCod.=$_POST['codigoPar'.$i];
        $listaVal.=$_POST['valorPar'.$i];

        if ($i+1!=$contador)
        {
            $listaCod.=',';
            $listaVal.=',';
        }

    }
    /*$listaCod = substr($listaCod,(1-strlen($listaCod)));*/
    //echo $idPago.$idFormaPago.$listaCod.$listaVal;
  $realizaEdicion = $pago->EditarFormaPago($idPago,$idFormaPago,$listaCod,$listaVal,$rec);
   echo json_encode($realizaEdicion);

}





