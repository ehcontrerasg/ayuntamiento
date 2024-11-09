<?php
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];

include_once '../clases/class.reportes_gerenciales.php';
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

if($tipo=='selPro'){
    
    $l=new ReportesGerencia();
    $datos = $l->seleccionaAcueducto($cod);
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='relPag'){
	$fecini = $_POST['fecini'];
	$fecfin = $_POST['fecfin'];
    $proyecto = $_POST['proyecto'];
	$c=new ReportesGerencia();
	$registros=$c->relacionPagos($proyecto, $fecini, $fecfin);
	$fp = fopen( 'php://temp/maxmemory:'. (24*1024*1024) , 'r+' );
	$cont = 1;
	$fila[0]="No.";
	$fila[1]="Código Pago";
	$fila[2]="Código Inmueble";
	$fila[3]="Fecha Pago";
	$fila[4]="Importe Pagado";
	$fila[5]="Importe Aplicado";
	$fila[20]="Saldo Ant";
	$fila[6]="Facturas Pagas";
	$fila[7]="Total Facturado";
	$fila[18]="Periodo Inicial";
	$fila[19]="Periodo Final";
	$fila[8]="Diferencia";
	$fila[9]="Entidad";
	$fila[10]="Punto";
	$fila[11]="Caja";
	$fila[12]="Tipo";
	$fila[13]="Suministro";
	$fila[14]="Unidades";
	$fila[15]="Descripción";
	$fila[16]="Medidor";
	$fila[17]="Categoría";
	$fila[21]="Tipo Pago";
	fputcsv( $fp, $fila );
	while (oci_fetch($registros)) {
		$fila[0]=utf8_decode($cont);
		$fila[1]=utf8_decode(oci_result($registros,"COD_PAGO"));
		$fila[2]=utf8_decode(oci_result($registros,"INMUEBLE"));
		$fila[3]=utf8_decode(oci_result($registros,"FECHA_PAGO"));
		$fila[4]=utf8_decode(oci_result($registros,"IMPORTE_PAGADO"));
		$fila[5]=utf8_decode(oci_result($registros,"IMPORTE_APLICADO"));
		$fila[20]=utf8_decode(oci_result($registros,"SALDO"));
		$fila[6]=utf8_decode(oci_result($registros,"NUM_FACTURAS"));
		$fila[7]=utf8_decode(oci_result($registros,"TOTAL_FACTURADO"));
		$fila[18]=utf8_decode(oci_result($registros,"PERMIN"));
		$fila[19]=utf8_decode(oci_result($registros,"PERMAX"));
		$fila[8]=utf8_decode(oci_result($registros,"DIFERENCIA"));
		$fila[9]=utf8_decode(oci_result($registros,"COD_ENTIDAD"));
		$fila[10]=utf8_decode(oci_result($registros,"ID_PUNTO_PAGO"));
		$fila[11]=utf8_decode(oci_result($registros,"NUM_CAJA"));
		$fila[12]=utf8_decode(oci_result($registros,"TIPO"));
		$fila[13]=utf8_decode(oci_result($registros,"SUMINISTRO"));
		$fila[14]=utf8_decode(oci_result($registros,"UNIDADES"));
		$fila[15]=utf8_decode(oci_result($registros,"DESCRIPCION"));
		$fila[16]=utf8_decode(oci_result($registros,"MEDIDOR"));
		$fila[17]=utf8_decode(oci_result($registros,"CATEGORIA"));
		$concepto = (oci_result($registros,"CONCEPTO"))	;
		if($fila[20]==0) $fila[20] = '';
		if($fila[12] == 'Recaudo' && $fila[5] < $fila[7] && $fila[6] == 1 || ($concepto == 20 || $concepto == 21 || $concepto == 22 || $concepto == 28 || $concepto == 30 || 
		$concepto == 93 || $concepto == 101 || $concepto == 128 || $concepto == 193)){	
			$fila[21]="No Valido";
			fputcsv( $fp, $fila );
			$cont++;
		}
		else{
			$fila[21]="Valido";
			fputcsv( $fp, $fila );
			$cont++;
		}	
	}oci_free_statement($registros);
	
	rewind( $fp );
    $output = stream_get_contents( $fp );
    // cerrar archivo
    fclose( $fp );
    // cabeceras HTTP:
    // tipo de archivo y codificación
    header('Content-Type: text/csv; charset=utf-8');
    // forzar descarga del archivo con un nombre de archivo determinado
    header('Content-Disposition: attachment; filename=RelacionPagos_'.$proyecto.'_'.$fecini.'_al_'.$fecfin.'.csv' );
    // indicar tamaño del archivo
    header('Content-Length: '. strlen($output) );
    // enviar archivo
    echo $output;
    exit;

}