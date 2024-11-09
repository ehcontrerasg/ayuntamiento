<?php
error_reporting(0);
include_once '../clases/classPagos.php';
$items = rtrim($_POST['items'],",");
$coduser = $_GET['codigo'];
$fecha_aplpago = $_GET['fec_aplpago'];
$c=new Pagos();
$c->setcodigo($items);

if($fecha_aplpago != ''){
	$fec_aplpago = explode('-',$fecha_aplpago);
	$ano = $fec_aplpago[0];
	$mes = $fec_aplpago[1];
	$dia = $fec_aplpago[2];
	
	$fecha_aplpago = $dia.'/'.$mes.'/'.$ano;
}

$total = count(explode(",",$items));
$buenos = 0; $malos = 0; $medio = 1;
for($i=0;$i<$total;$i++){
	$id_pago = explode(',',$items);
	$num_pago = $id_pago[$i];

	$d=new Pagos();
	$registros=$d->AplicaPagosTransito($num_pago);
	while (oci_fetch($registros)) {
        $inmueble = oci_result($registros, 'ID_INMUEBLE');
        $entidad = oci_result($registros, 'ID_ENTPAGO');
        $punto = oci_result($registros, 'ID_PTOPAGO');
        $id_caja = oci_result($registros, 'ID_CAJA');
        $fecpago = oci_result($registros, 'FECHA_PAGO');
        $fecregpago = oci_result($registros, 'FECHA_REGISTRO');
        $formapago = oci_result($registros, 'FORMA_PAGO');
        $importe = oci_result($registros, 'VALOR_IMPORTE');
        $usuariopago = oci_result($registros, 'USUARIO_REG');
        $cod_pro = oci_result($registros, 'ID_PROYECTO');
        $estado = oci_result($registros, 'ID_ESTADO');
        $referencia = "Pago transito aplicado al inmueble No. " . $inmueble;
        $origen = 'P';
        if ($entidad == 921) $id_caja = '68';
        if ($entidad == 902) $id_caja = '601';
        //if($entidad == 990) $id_caja = '620';
        if ($entidad == 905) $id_caja = '57';

        $g = new Pagos();
        $result = $g->obtieneDeuda($inmueble);
        while (oci_fetch($result)) {
            $deuda = oci_result($result, 'DEUDA');
        }
        oci_free_statement($result);

        if ($fecha_aplpago != '')
            $fecpago = $fecha_aplpago;

        //else $fecpago = $fecpago;

        if ($deuda > 0 && ($estado != 'SS' || $estado != 'PC')) {
            $f = new Pagos();
            $bandera = $f->IngresaPagoTrans($importe, $referencia, $id_caja, $inmueble, $origen, $coduser, $importe, $cod_pro, $deuda, $fecpago);
            $f->getmsgresult();
            if ($bandera == false) {
                $error = $f->getmsgresult();
                $coderror = $f->getcodresult();
                $malos++;
                $observacion = $error;
                $estado = 'P';
                $h = new Pagos();
                $sql = $h->ActualizaAplicaPagosTransito($num_pago, $observacion, $estado);
            } else if ($bandera == true) {
                $buenos++;
                $estado = 'A';
                $observacion = utf8_encode('Se aplic� el pago en transito');
                $h = new Pagos();
                $sql = $h->ActualizaAplicaPagosTransito($num_pago, $observacion, $estado);
                $c = new Pagos();
                $resultado = $c->seleccionaFecpago($inmueble);
                while (oci_fetch($resultado)) {
                    $fec_pago = oci_result($resultado, 'FEC_PAGO');
                }
                oci_free_statement($resultado);
                $c = new Pagos();
                $resultado = $c->seleccionaIdpago($fec_pago, $inmueble);
                while (oci_fetch($resultado)) {
                    $id_pago = oci_result($resultado, 'ID_PAGO');
                }
                oci_free_statement($resultado);
                $c = new Pagos();
                $resultado = $c->IngresaMedioPago($medio, $importe, $id_pago);

            }
        }

        else if ($deuda <= 0 && ($estado != 'SS' || $estado != 'PC')) {
            $a1 = 1;

            $fecha_pago = explode('/', $fecpago);
            $dia = $fecha_pago[0];
            $mes = $fecha_pago[1];
            $ano = $fecha_pago[2];

            $fecpago1 = $ano . '-' . $mes . '-' . $dia;
            //$fecpagoa = "2016-08-15";

            $c = new Pagos();
            $bandera = $c->IngresaOtroRecaudo($cod_pro, $a1, $importe, $coduser, $referencia, $inmueble, $id_caja, $fecpago1);
            if ($bandera == false) {
                $error = $c->getmsgresult();
                $coderror = $c->getcodresult();
                $malos++;
                $observacion = $error . ' ' . $fecpago1;
                $estado = 'P';
                $h = new Pagos();
                $sql = $h->ActualizaAplicaPagosTransito($num_pago, $observacion, $estado);
            } else if ($bandera == true) {
                $buenos++;
                $c = new Pagos();
                $resultado = $c->seleccionaIdRec($inmueble);
                while (oci_fetch($resultado)) {
                    $id_rec = oci_result($resultado, 'ID_REC');
                }
                oci_free_statement($resultado);

                $c = new Pagos();
                $resultado = $c->IngresaMedioRecaudo($medio, $importe, $id_rec);
                $estado = 'A';
                $observacion = utf8_encode('El inmueble no tiene deuda pendiente, se aplic� otro recaudo por concepto de agua');
                $h = new Pagos();
                $sql = $h->ActualizaAplicaPagosTransito($num_pago, $observacion, $estado);
            }
        }

        else if (($estado == 'SS' || $estado == 'PC')) {
            $c = new Pagos();
            $resultado = $c->getValRecByByInm($inmueble);
            while (oci_fetch($resultado)) {
                $valor_rec = oci_result($resultado, 'VALOR_TARIFA');
            }
            oci_free_statement($resultado);

            $total_deuda = $deuda + $valor_rec;

            if($total_deuda > 0 && $importe < $total_deuda){
                $estado = 'P';
                $observacion = utf8_encode('No se aplic� el pago, reconexion pendiente, importe insuficiente para cubrir el total');
                $h = new Pagos();
                $sql = $h->ActualizaAplicaPagosTransito($num_pago, $observacion, $estado);
            }
            else if ($total_deuda > 0 && $importe == $total_deuda){
                $a1 = 20;
                $fecha_pago = explode('/', $fecpago);
                $dia = $fecha_pago[0];
                $mes = $fecha_pago[1];
                $ano = $fecha_pago[2];
                $fecpago1 = $ano . '-' . $mes . '-' . $dia;

                $c = new Pagos();
                $bandera = $c->IngresaOtroRecaudo($cod_pro, $a1, $valor_rec, $coduser, $referencia, $inmueble, $id_caja, $fecpago1);
                if ($bandera == false) {
                    $error = $c->getmsgresult();
                    $coderror = $c->getcodresult();
                    $malos++;
                    $observacion = $error . ' ' . $fecpago1;
                    $estado = 'P';
                    $h = new Pagos();
                    $sql = $h->ActualizaAplicaPagosTransito($num_pago, $observacion, $estado);
                }
                else if ($bandera == true) {
                    $buenos++;
                    $c = new Pagos();
                    $resultado = $c->seleccionaIdRec($inmueble);
                    while (oci_fetch($resultado)) {
                        $id_rec = oci_result($resultado, 'ID_REC');
                    }
                    oci_free_statement($resultado);

                    $c = new Pagos();
                    $resultado = $c->IngresaMedioRecaudo($medio, $valor_rec, $id_rec);
                    $estado = 'R';
                    $observacion = utf8_encode('Se aplic� otro recaudo por concepto de reconexion');
                    $h = new Pagos();
                    $sql = $h->ActualizaAplicaPagosTransito($num_pago, $observacion, $estado);

                    $importe = $total_deuda - $valor_rec;

                    $f = new Pagos();
                    $bandera = $f->IngresaPagoTrans($importe, $referencia, $id_caja, $inmueble, $origen, $coduser, $importe, $cod_pro, $deuda, $fecpago);
                    $f->getmsgresult();
                    if ($bandera == false) {
                        $error = $f->getmsgresult();
                        $coderror = $f->getcodresult();
                        $malos++;
                        $observacion = $error;
                        $estado = 'R';
                        $h = new Pagos();
                        $sql = $h->ActualizaAplicaPagosTransito($num_pago, $observacion, $estado);
                    }
                    else if ($bandera == true) {
                        $buenos++;
                        $estado = 'A';
                        $observacion = $observacion.' / '.utf8_encode('Se aplic� pago en transito');
                        $h = new Pagos();
                        $sql = $h->ActualizaAplicaPagosTransito($num_pago, $observacion, $estado);
                        $c = new Pagos();
                        $resultado = $c->seleccionaFecpago($inmueble);
                        while (oci_fetch($resultado)) {
                            $fec_pago = oci_result($resultado, 'FEC_PAGO');
                        }
                        oci_free_statement($resultado);
                        $c = new Pagos();
                        $resultado = $c->seleccionaIdpago($fec_pago, $inmueble);
                        while (oci_fetch($resultado)) {
                            $id_pago = oci_result($resultado, 'ID_PAGO');
                        }
                        oci_free_statement($resultado);
                        $c = new Pagos();
                        $resultado = $c->IngresaMedioPago($medio, $importe, $id_pago);

                    }
                }

            }
            else if ($total_deuda > 0 && $importe > $total_deuda){
                $estado = 'P';
                $observacion = utf8_encode('No se aplic� el pago, importe mayor a la deuda, anule el pago e ingrese manualmente');
                $h = new Pagos();
                $sql = $h->ActualizaAplicaPagosTransito($num_pago, $observacion, $estado);
            }

        }

    }
	oci_free_statement($registros);
}
/*$result = runSQL($sql);
$total = mysql_affected_rows(); */
/// Line 18/19 commented for demo purposes. The MySQL query is not executed in this case. When line 18 and 19 are uncommented, the MySQL query will be executed. 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-type: text/x-json");
$json = "";
$json .= "{\n";
$json .= "query: '".$registros."',\n";
$json .= "total: $total,\n";
$json .= "buenos: $buenos,\n";
$json .= "malos: $malos,\n";
//$json .= "error: $error,\n";
$json .= "}\n";
echo $json;
 ?>