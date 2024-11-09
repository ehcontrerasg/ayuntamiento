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
        //$referencia = "Pago transito aplicado al inmueble No. " . $inmueble;
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

        $deuda = $deuda;
        //////INICIO NUEVO CODIGO
        $f = new Pagos();
        $bandera = $f->IngresaPagoTrans2($importe, $id_caja, $inmueble, $origen, $coduser, $importe, $cod_pro, $deuda, $fecpago, $estado);
        $f->getmsgresult();


        if ($bandera == false) {
            $error = $f->getmsgresult();
            $coderror = $f->getcodresult();
            $malos++;
            $observacion = $error;
            $estado = 'P';
            $h = new Pagos();
            $sql = $h->ActualizaAplicaPagosTransito($num_pago, $observacion, $estado);
        }
        else if ($bandera == true) {
            $buenos++;
            $estado = 'A';
            $observacion = utf8_encode('Se aplico el pago en transito');
            $h = new Pagos();
            $sql = $h->ActualizaAplicaPagosTransito($num_pago, $observacion, $estado);
        }
        /////FIN NUEVO CODIGO
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