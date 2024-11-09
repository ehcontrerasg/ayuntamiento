<?php
$proyecto = $_GET['proyecto'];
$periodo = $_GET['periodo'];
$cabecera = $_GET['cabecera'];
$formato = $_GET['formato'];
$periodoFin = $_GET['periodoFin'];

include '../clases/class.reportes_lectura.php';
include '../clases/class.reportes_dgii.php';

$separador = '|';

$agno = substr($periodo,0,4);
$mes = substr($periodo,4,2);

if ($mes == '01' or $mes == '03' or $mes == '05' or $mes == '07' or $mes == '08' or $mes == '10' or $mes == '12'){
    $dia = 31;
}
else if ($mes == '04' or $mes == '06' or $mes == '09' or $mes == '11'){
    $dia = 30;
}
else{
    $dia = 28;
}

//echo $agno.'-'.$mes;

if($formato == 1){
    $c=new Reportes_Dgii();
    $dat=$c->obtieneRegistros($proyecto,$periodo,$cabecera,$formato,$agno,$mes,$dia,$periodoFin);

    $fp = fopen( 'php://temp/maxmemory:'. (24*1024*1024) , 'r+' );
    $fila[0]=utf8_decode('IDPRO');
    $fila[1]=utf8_decode('CODSISTEMA');
    $fila[2]=utf8_decode('RNC/CEDULA');
    $fila[3]=utf8_decode('FACTURA');
    $fila[4]=utf8_decode('FECHAFACTURA');
    $fila[5]=utf8_decode('NCF');
    $fila[6]=utf8_decode('NOMBRE');
    $fila[7]=utf8_decode('MONTOFACTURA');
    $fila[8]=utf8_decode('ITBIS');
    $fila[9]=utf8_decode('Tipo de Ingreso');
    $fila[10]=utf8_decode('Tipo Identificacion');
    $fila[11]=utf8_decode('Efectivo');
    $fila[12]=utf8_decode('Cheque');
    $fila[13]=utf8_decode('Tarjeta');

    fputcsv( $fp, $fila );
    while(oci_fetch($dat)){
        $fila[0]=utf8_decode(oci_result($dat,'ID_PROCESO'));
        $fila[1]=utf8_decode(oci_result($dat,'CODIGO_INM'));
        $fila[2]=utf8_decode(oci_result($dat,'DOCUMENTO'));
        $fila[3]=utf8_decode(oci_result($dat,'CONSEC_FACTURA'));
        $fila[4]=utf8_decode(oci_result($dat,'FECHA_COMPROBANTE'));
        $fila[5]=utf8_decode(oci_result($dat,'NCF'));
        $fila[6]=utf8_decode(oci_result($dat,'ALIAS'));
        $fila[7]=utf8_decode(oci_result($dat,'TOTAL'));
        $fila[8]=utf8_decode(oci_result($dat,'ITBIS'));
        $fila[9]=1;
        $fila[10]=utf8_decode(oci_result($dat,'TIPODOC'));

        $numfac=utf8_decode(oci_result($dat,'CONSEC_FACTURA'));


        $cabecerancf=substr($fila[5],0,3);


        if($cabecerancf=='B02' && $fila[2]=='9999999'){
            $fila[2]='';
        }

        if($fila[7]==0){
            $a=new Reportes_Dgii();
            $registrosa=$a->obtieneTotal($numfac);
            while (oci_fetch($registrosa)) {
                $fila[7] = oci_result($registrosa, 'SUMADETALLE');
                $fila[7] = str_pad($fila[7],14,'0', STR_PAD_LEFT).'.00';
            }oci_free_statement($registrosa);
        }

        unset($numpago);

        $a=new Reportes_Dgii();
        $registrosa=$a->obtienePago($numfac);
        while (oci_fetch($registrosa)) {
            $numpago = oci_result($registrosa, 'PAGO');
        }oci_free_statement($registrosa);

        if( isset($numpago)) {
            $a = new Reportes_Dgii();
            $registrosa = $a->obtieneMediosPago($numpago);
            while (oci_fetch($registrosa)) {
                $idmediopago = oci_result($registrosa, 'ID_FORM_PAGO');
                $valormediopago = oci_result($registrosa, 'VALOR');
                if ($idmediopago == 1) {
                    $fila[11]=$valormediopago;
                    $fila[12]=0;
                    $fila[13]=0;
                }
                if ($idmediopago == 2) {
                    $fila[11]=0;
                    $fila[12]=$valormediopago;
                    $fila[13]=0;
                }
                if ($idmediopago == 3) {
                    $fila[11]=0;
                    $fila[12]=0;
                    $fila[13]=$valormediopago;;
                }
            }
        }
        else{
            $fila[11]=0;
            $fila[12]=0;
            $fila[13]=0;
        }
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
    header('Content-Disposition: attachment; filename=Reporte_DGII_'.$proyecto.'_'.$periodo.'.csv');
    // indicar tamaño del archivo
    header('Content-Length: '. strlen($output));
    // enviar archivo
    echo $output;
    exit;
}
else if($formato == 2){
    $c=new Reportes_Dgii();
    $dat=$c->obtieneRegistros($proyecto,$periodo,$cabecera,$formato,$agno,$mes,$dia,$periodoFin);

    $fp = fopen( 'php://temp/maxmemory:'. (24*1024*1024) , 'r+' );
    $linea = 'IDPRO'.$separador.'CODIGOSISTEMA'.$separador.'RNC/CEDULA'.$separador.'NUMEROFACTURA'.$separador.'FECHAFACTURA'.$separador.'NCF'.$separador.'NOMBRE'.$separador.'MONTOFACTURA'."\r\n";
    fputs($fp, $linea);

    while(oci_fetch($dat)){
        $idpro=utf8_decode(oci_result($dat,'ID_PROCESO'));
        $codsis=utf8_decode(oci_result($dat,'CODIGO_INM'));
        $rnc=utf8_decode(oci_result($dat,'DOCUMENTO'));
        $factura=utf8_decode(oci_result($dat,'CONSEC_FACTURA'));
        $fecfac=utf8_decode(oci_result($dat,'FECHA_COMPROBANTE'));
        $ncf=utf8_decode(oci_result($dat,'NCF'));
        $nombre=utf8_decode(oci_result($dat,'ALIAS'));
        $valor=utf8_decode(oci_result($dat,'TOTAL'));
        $numfac=utf8_decode(oci_result($dat,'CONSEC_FACTURA'));
        $tipodoc=utf8_decode(oci_result($dat,'TIPODOC'));

        $cabecerancf=substr($ncf,0,3);

        if($cabecerancf=='B02' && $rnc=='9999999'){
            $rnc='';
        }
        if($tipodoc == ''){
            $rnc='';
        }

        if($valor==0){
            $a=new Reportes_Dgii();
            $registrosa=$a->obtieneTotal($numfac);
            while (oci_fetch($registrosa)) {
                $valor = oci_result($registrosa, 'SUMADETALLE');
                $valor = str_pad($fila[7],14,'0', STR_PAD_LEFT).'.00';
            }oci_free_statement($registrosa);
        }

        $linea = $idpro.$separador.$codsis.$separador.$rnc.$separador.$factura.$separador.$fecfac.$separador.$ncf.$separador.$nombre.$separador.$valor."\r\n";
        fputs($fp, $linea);
    }

    rewind( $fp );
    $output = stream_get_contents( $fp );
    // cerrar archivo
    fclose( $fp );
    // cabeceras HTTP:
    // tipo de archivo y codificación
    header('Content-Type: text/csv; charset=utf-8');
    // forzar descarga del archivo con un nombre de archivo determinado
    header('Content-Disposition: attachment; filename=ACEA'.$periodo.'_F.txt');
    // indicar tamaño del archivo
    header('Content-Length: '. strlen($output));
    // enviar archivo
    echo $output;
    exit;
}

else if($formato == 3){
    $c=new Reportes_Dgii();
    $dat=$c->obtieneNotas($proyecto,$periodo,$periodoFin);

    $fp = fopen( 'php://temp/maxmemory:'. (24*1024*1024) , 'r+' );
    $linea = 'IDPRO'.$separador.'CODIGOSISTEMA'.$separador.'RNC/CEDULA'.$separador.'NC/ND'.$separador.'FECHADOC'.$separador.'NCF'.$separador.'VALOR'.$separador.'NUMEROFACTURA'.$separador.'NCFMODIFICADO'.$separador.'NOMBRE'."\r\n";
    fputs($fp, $linea);

    while(oci_fetch($dat)) {

        $idpro=utf8_decode(oci_result($dat,'ID_PROCESO'));
        $codsis=utf8_decode(oci_result($dat,'CODIGO_INM'));
        $cedula=utf8_decode(oci_result($dat,'DOCUMENTO'));
        $numnot=utf8_decode(oci_result($dat,'ID_NOTA'));
        $fechadoc=utf8_decode(oci_result($dat,'FECNOTA'));
        $ncf=utf8_decode(oci_result($dat,'NCF'));
        $valor=utf8_decode(oci_result($dat,'VALOR'));
        $numfac=utf8_decode(oci_result($dat,'FACTURA_APLICA'));
        $ncfmod=utf8_decode(oci_result($dat,'NCF_MOD'));
        $nombre=utf8_decode(oci_result($dat,'NOMBRE_CLIENTE'));
        $tipodoc=utf8_decode(oci_result($dat,'TIPO_DOC'));

        $linea = $idpro.$separador.$codsis.$separador.$cedula.$separador.$numnot.$separador.$fechadoc.$separador.$ncf.$separador.$valor.$separador.$numfac.$separador.$ncfmod.$separador.$nombre.$separador.$tipodoc."\r\n";
        fputs($fp, $linea);
    }

    rewind( $fp );
    $output = stream_get_contents( $fp );
    // cerrar archivo
    fclose( $fp );
    // cabeceras HTTP:
    // tipo de archivo y codificación
    header('Content-Type: text/csv; charset=utf-8');
    // forzar descarga del archivo con un nombre de archivo determinado
    header('Content-Disposition: attachment; filename=ACEA'.$periodo.'_N.txt');
    // indicar tamaño del archivo
    header('Content-Length: '. strlen($output));
    // enviar archivo
    echo $output;
    exit;
}

else if($formato == 4){
    $c=new Reportes_Dgii();
    $dat=$c->obtieneRecibos($proyecto,$periodo);

    $fp = fopen( 'php://temp/maxmemory:'. (24*1024*1024) , 'r+' );
    $linea = 'IDPRO'.$separador.'CODIGOSISTEMA'.$separador.'NUMERORECIBO'.$separador.'FECHARECIBO'.$separador.'VALOR'.$separador.'NUMEROFACTURA'.$separador.'NOMBRE'."\r\n";
    fputs($fp, $linea);

    while(oci_fetch($dat)){
        $idpro=utf8_decode(oci_result($dat,'ID_PROCESO'));
        $codsis=utf8_decode(oci_result($dat,'CODIGO_INM'));
        $numrec=utf8_decode(oci_result($dat,'ID_PAGO'));
        $fecrec=utf8_decode(oci_result($dat,'FECRECIBO'));
        $valrec=utf8_decode(oci_result($dat,'IMPORTE'));
        $numfac=utf8_decode(oci_result($dat,'FACTURA'));
        $nombre=utf8_decode(oci_result($dat,'ALIAS'));

        $linea = $idpro.$separador.$codsis.$separador.$numrec.$separador.$fecrec.$separador.$valrec.$separador.$numfac.$separador.$nombre."\r\n";
        fputs($fp, $linea);
    }

    rewind( $fp );
    $output = stream_get_contents( $fp );
    // cerrar archivo
    fclose( $fp );
    // cabeceras HTTP:
    // tipo de archivo y codificación
    header('Content-Type: text/csv; charset=utf-8');
    // forzar descarga del archivo con un nombre de archivo determinado
    header('Content-Disposition: attachment; filename=ACEA'.$periodo.'_R.txt');
    // indicar tamaño del archivo
    header('Content-Length: '. strlen($output));
    // enviar archivo
    echo $output;
    exit;
}
?>

