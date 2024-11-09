<?php
mb_internal_encoding("UTF-8");
$tipo = $_POST['tip'];

include_once '../clases/classPqrs.php';
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
    $l=new PQRs();
    $datos = $l->seleccionaAcueducto();
    $i=0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC+OCI_RETURN_NULLS)) {
        $con[$i]=$row;
        $i++;
    }
    echo json_encode($con);
}

if($tipo=='DataCredito'){
	$fecini = $_POST['fecini'];
	$fecfin = $_POST['fecfin'];
    $proyecto = $_POST['proyecto'];
	header('Content-type: application/vnd.ms-excel');
	//header("Content-Disposition: attachment; filename=Inmuebles_Data_Credito".$proyecto.".xls");
	echo "<table class='scroll' border='1' bordercolor='#CCCCCC'>";
	echo "<tr>";
	echo "<th colspan='14' style='border:1px solid #DEDEDE; background-color:#336699; color:#FFFFFF' align='center'>INMUEBLES DATA CREDITO</th>";
	echo "</tr>";
	echo "</table>";
	echo "<table class='scroll' border='0' bordercolor='#CCCCCC'>";
		echo "<tr>";
		echo "<td colspan='1'>";
			echo "<table  border='1' bordercolor='#CCCCCC'>";
			echo "<tr>";
			echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Inmueble</th>";
			echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Nombre</th>";
			echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Documento</th>";
			echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Sector</th>";
			echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Ruta</th>";
			echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Direcci&oacute;n</th>";
			echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Urbanizaci&oacute;n</th>";
			echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Telefono</th>";
			echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Uso</th>";
			echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Estado</th>";
			echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Ultima Fecha Pago</th>";
			echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Reclamo Abierto</th>";
            echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Facturas pagadas</th>";
            echo "<th style='border-right:1px solid #DEDEDE; color:#FFFFFF; background-color:#006699; text-align:center'>Monto Pagado</th>";

            echo "</tr>";
			$c=new PQRs();
			$registros=$c->DataCredito($proyecto,$fecini,$fecfin);
			while (oci_fetch($registros)) {
				$inmueble=utf8_decode(oci_result($registros,"CODIGO_INM"));
				$alias=utf8_decode(oci_result($registros,"ALIAS"));
				$documento=utf8_decode(oci_result($registros,"DOCUMENTO"));
				$sector=utf8_decode(oci_result($registros,"ID_SECTOR"));
				$ruta=utf8_decode(oci_result($registros,"ID_RUTA"));
				$direccion=utf8_decode(oci_result($registros,"DIRECCION"));
				$urbanizacion=utf8_decode(oci_result($registros,"DESC_URBANIZACION"));
				$telefono=utf8_decode(oci_result($registros,"TELEFONO"));
				$uso=utf8_decode(oci_result($registros,"ID_USO"));
				$estado=utf8_decode(oci_result($registros,"ID_ESTADO"));
				$fecpago=utf8_decode(oci_result($registros,"FECHA_PAGO"));
				$reclamo=utf8_decode(oci_result($registros,"RECLAMO_ABIERTO"));
               $facturasPagadas=utf8_decode(oci_result($registros,"FACTURAS_PAGADAS"));
                $montoPagado=utf8_decode(oci_result($registros,"MONTO_PAGADO"));


				
					echo "<tr>";
					echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$inmueble</td>";
					echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$alias</td>";
					echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$documento</td>";
					echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$sector</td>";
					echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$ruta</td>";
					echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$direccion</td>";
					echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$urbanizacion</td>";
					echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$telefono</td>";
					echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$uso</td>";
					echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$estado</td>";
					echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$fecpago</td>";
					echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$reclamo</td>";
                    echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$facturasPagadas</td>";
                    echo "<td style='border-right:1px solid #DEDEDE; color:#000000; background-color:#FFFFFF; text-align:center'>$montoPagado</td>";

                echo "</tr>";
				
			}oci_free_statement($registros);
			echo "</table>";
		echo "</td>";	
		echo "</tr>";
	echo "</table>";
}