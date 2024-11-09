<?php
include '../clases/class.reportes_emitefac.php';
$proyecto = $_POST['proyecto'];
$zonini = $_POST['zonini'];
$perini = $_POST['perini'];
$tipo = $_POST['tipo'];
$usoini = $_POST['usoini'];
$rutaArchivo='../archivos_facturacion/';
    if(!is_dir($rutaArchivo)){
        mkdir($rutaArchivo);
    }
    $zona   	=    $_REQUEST['zonini'];
    $acueducto  =    $_REQUEST['proyecto'];
    $periodo 	=    $_REQUEST['perini'];
    $tipo		=	 $_REQUEST['tipo'];
    //$uso   	=    $_REQUEST['usoini'];

    $fecha 		= 	 date('Y-M-D');
    $archivo 	=   $rutaArchivo.strtoupper($acueducto.$zona.$periodo).'.txt';

    header('Content-Description: File Transfer');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='.basename($archivo));
    header('Content-Transfer-Encoding: Binary');
    header('Expires:0');
    header('Cache-control:must-revalidate');
    header('Pragma:public');
    header('Content-Length'.filesize($archivo));

    ob_clean();
    flush();

    $mensaje_valor_fiscal="";
    if ($acueducto == 'SD') {
        $mensaje_valor_fiscal_factura =
            utf8_encode(". Si necesita que su factura tenga valor fiscal favor de solicitarlo enviandonos un correo a catastro@caasdoriental.com.");
    } else {
        $mensaje_valor_fiscal_factura =
            utf8_encode(". Si necesita que su factura tenga valor fiscal favor de solicitarlo enviandonos un correo a soporte@coraaboenlinea.com.");
    }

    $fp=fopen($archivo,"w");
    $c=new ReportesEmiteFac();
    $registrosC=$c->DatosArchivo($perini,$zonini,$proyecto,$tipo,$usoini);
    $cont = 1;

    while (oci_fetch($registrosC)) {
        $proceso = oci_result($registrosC, 'ID_PROCESO');
        $inmueble = oci_result($registrosC, 'CODIGO_INM');
        $catastro = oci_result($registrosC, 'CATASTRO');
        $cliente  = oci_result($registrosC, 'ALIAS');
        //$nomcliente = oci_result($registrosC, 'NOMBRE_CLI');
        $direccion = oci_result($registrosC, 'DIRECCION');
        $urba = oci_result($registrosC, 'DESC_URBANIZACION');
        $factura = oci_result($registrosC, 'CONSEC_FACTURA');
        $zona = oci_result($registrosC, 'ID_ZONA');
        $fecexp = oci_result($registrosC, 'FECEXP');
        $periodo = oci_result($registrosC, 'PERIODO');
        $medidor = oci_result($registrosC, 'MEDIDOR');
        $calibre = oci_result($registrosC, 'CALIBRE');
        $serial = oci_result($registrosC, 'SERIAL');
        $fecant = oci_result($registrosC, 'FECANT');
        $lecant = oci_result($registrosC, 'LECANT');
        $fecact = oci_result($registrosC, 'FECACT');
        $lecact = oci_result($registrosC, 'LECACT');
        $consumo = oci_result($registrosC, 'CONSUMO');
        $consumomin = oci_result($registrosC, 'CONSUMO_MIN');
        $metodo = oci_result($registrosC, 'METODO');
        $saldofavor = oci_result($registrosC, 'SALDOFAVOR');
        $saldodif = oci_result($registrosC, 'DIFERIDO');
        $valcon = oci_result($registrosC, 'VALORCON');
        $valotros = oci_result($registrosC, 'VALOROTROS');
        $deudatotal = oci_result($registrosC, 'DEUDA');
        $facpend = oci_result($registrosC, 'FACPEND');
        $totalfac = oci_result($registrosC, 'TOTAL');
        $fecvto = oci_result($registrosC, 'FECVCTO');
        $barras = oci_result($registrosC, 'CODBARRA');
        $uso = oci_result($registrosC, 'DESC_USO');
        $ncf = oci_result($registrosC, 'NCF');
        $msjncf = oci_result($registrosC, 'NCF_MSJ');
        $rnc = oci_result($registrosC, 'RNC');
        $feccorte = oci_result($registrosC, 'FECCORTE');
        $mora = oci_result($registrosC, 'MORA');
        $estado = oci_result($registrosC, 'ID_ESTADO');
        $facgen = oci_result($registrosC, 'FACGEN');
        $mora_periodo = oci_result($registrosC, 'MORA_PERIODO');
        $debeperiodo = oci_result($registrosC, 'MSJ_PERIODO');
        $msjalerta = oci_result($registrosC, 'MSJ_ALERTA');
        $msjburo = oci_result($registrosC, 'MSJ_BURO');
        $msjfac = oci_result($registrosC, 'MSJ_FACTURA');
        $fecvence = oci_result($registrosC, 'FECVENCE');
        $tipodoc  = oci_result($registrosC, 'TIPO_DOC');
        $cupoBasico  = oci_result($registrosC, 'CUPO_BASICO');
        $estrato  = oci_result($registrosC, 'ESTRATO');
        $suministro = oci_result($registrosC, 'DESC_SERVICIO');
        $fechaPag  = oci_result($registrosC, 'FECHA_ULTIMO_PAGO');
        $valPag  = oci_result($registrosC, 'VALOR_ULTIMO_PAGO');
        //if($cliente == '') $cliente = $nomcliente;

        $msjalerta = "";

        if($zona == '52A' || $zona == '52B' || $zona == '52C' || $zona == '52D' || $zona == '52E' || $zona == '52F' || $zona == '60A' ||
            $zona == '60B' || $zona == '60F' || $zona == '23D' || $zona == '26C' || $zona == '26D' || $zona == '27D' || $zona == '64A' ||
            $zona == '64B'){
            $feccorte = '';
        }

        if($msjfac != '') $msjfac = $msjfac.' '.$feccorte;

        if($lecant == -1) $lecant = '';
        if($lecact == -1) $lecact = '';

        $c=new ReportesEmiteFac();
        $registrosS=$c->DatosServicios($inmueble);
        $linea2 = ''; $linea3 = ''; $linea4 = ''; $servactual = '';

        while (oci_fetch($registrosS)) {
            $servicio = oci_result($registrosS, 'DESC_SERVICIO');
            $codtarifa = oci_result($registrosS, 'CODIGO_TARIFA');
            $unidadtotal = oci_result($registrosS, 'UNIDADES_HAB');
            $tarifa = oci_result($registrosS, 'CONSEC_TARIFA');
            if($servicio == 'Alcantarillado Red'  || $servicio == 'Alcantarillado Pozo') $servicio = 'Alcantarillado';
            if ($servactual != $servicio){
                $linea2 .= $servicio.'*'.$uso.'*'.$codtarifa.'*'.$unidadtotal.'*';
            }
            $cmobasico = $consumomin;
            $totalcmo = $cmobasico*$valormt;
            if($consumo < $consumomin) $consumo = $consumomin;
        }oci_free_statement($registrosS);

        $linea2 = substr($linea2,0,strlen($linea2)-1);


        $d=new ReportesEmiteFac();
        $registrosR=$d->DatosRangosConceptos($inmueble,$perini);
        $conceptoactual = '';
        while (oci_fetch($registrosR)) {
            $concepto = oci_result($registrosR, 'CONCEPTO');
            $desconcepto = oci_result($registrosR, 'DESC_SERVICIO');
            $rango = oci_result($registrosR, 'RANGO');
            $desrango = oci_result($registrosR, 'DESCRANGO');
            $unidades = oci_result($registrosR, 'UNIDADES');
            $precio = oci_result($registrosR, 'PRECIO');
            $valor = oci_result($registrosR, 'VALOR');
            if($desconcepto == 'Alcantarillado Red'  || $desconcepto == 'Alcantarillado Pozo') $desconcepto = 'Alcantarillado';
            if ($conceptoactual != $desconcepto){
                $linea3 .= $desconcepto.'*'.$desrango.'*'.$unidades.'*'.$precio.'*'.$valor.'*';
            }
            if ($conceptoactual == $desconcepto){
                $linea3 .= $desrango.'*'.$unidades.'*'.$precio.'*'.$valor.'*';
            }
            $conceptoactual = $desconcepto;
        }oci_free_statement($registrosR);

        $linea3 = substr($linea3,0,strlen($linea3)-1);

        $d=new ReportesEmiteFac();
        $registrosR=$d->DatosRangosOtrosConceptos($inmueble,$perini);
        $conceptoactual = '';
        while (oci_fetch($registrosR)) {
            $concepto = oci_result($registrosR, 'CONCEPTO');
            $desconcepto = oci_result($registrosR, 'DESC_SERVICIO');
            $valor = oci_result($registrosR, 'VALOR');
            if($desconcepto == 'Mora') $desconcepto = 'Intereses de Mora';
            $linea4 .= $desconcepto.'*'.$valor.'*';
        }oci_free_statement($registrosR);

        $linea4 = str_replace('SF','Saldo a Favor',$linea4);
        $linea4 = substr($linea4,0,strlen($linea4)-1);

        $agno = substr($periodo,0,4);
        $mesi = substr($periodo,4,2);
        if($mesi == '01') $mesi = 'Ene'; if($mesi == '02') $mesi = 'Feb'; if($mesi == '03') $mesi = 'Mar'; if($mesi == '04') $mesi = 'Abr'; if($mesi == '05') $mesi = 'May';
        if($mesi == '06') $mesi = 'Jun'; if($mesi == '07') $mesi = 'Jul'; if($mesi == '08') $mesi = 'Ago'; if($mesi == '09') $mesi = 'Sep'; if($mesi == '10') $mesi = 'Oct';
        if($mesi == '11') $mesi = 'Nov'; if($mesi == '12') $mesi = 'Dic';
        if($consumo > $consumomin) $consumo = $consumo;
        else $consumo = $consumomin;
        $texto = "";

        if($metodo =="Promedio" || $medidor =="Sin Medidor") $metodo="  "; //Cambiar esto cualquier cosa
        if($tipodoc =="Cédula" || count($rnc)==11 ){
            $rnc = "  ";
            // $ncf = '*';
        }
/*
        if($tipo == 'T'){
            $linea = $proceso.'*'.$inmueble.'*'.$catastro.'*'.$cliente.'*'.$direccion.'*'.$urba.'*'.$factura.'*'.$zona.'*'.$fecexp.'*'.$mesi.'/'.$agno.'*'.$medidor.'*'.$calibre.'*'.$serial.'*'.$fecant.'*'.$lecant.'*'.$fecact.'*'.$lecact.'*'.$consumo.'*'.$metodo.'*'.$saldofavor.'*'.$saldodif.'*'.$valcon.'*'.$valotros.'*'.$deudatotal.'*'.$facpend.'*'.$totalfac.'*'.$fecvto.'**'.$factura.'**'.$uso.'*'.$ncf.'*'.$msjncf.'*'.$rnc.'*'.$texto.'*'.$debeperiodo.$mensaje_valor_fiscal_factura.'*'.$msjfac.'*'.$msjalerta.'*'.$msjburo.'*'.$fecvence."\r\n".$linea2."\r\n".$linea3."\r\n".$linea4."\r\n";
            fputs($fp,utf8_decode($linea));
        }
        if($tipo == 'A'){
            if ($facpend <= 1){
                $linea = $proceso.'*'.$inmueble.'*'.$catastro.'*'.$cliente.'*'.$direccion.'*'.$urba.'*'.$factura.'*'.$zona.'*'.$fecexp.'*'.$mesi.'/'.$agno.'*'.$medidor.'*'.$calibre.'*'.$serial.'*'.$fecant.'*'.$lecant.'*'.$fecact.'*'.$lecact.'*'.$consumo.'*'.$metodo.'*'.$saldofavor.'*'.$saldodif.'*'.$valcon.'*'.$valotros.'*'.$deudatotal.'*'.$facpend.'*'.$totalfac.'*'.$fecvto.'**'.$factura.'**'.$uso.'*'.$ncf.'*'.$msjncf.'*'.$rnc.'*'.$texto.'*'.$debeperiodo.$mensaje_valor_fiscal_factura.'*'.$msjfac.'*'.$msjalerta.'*'.$msjburo.'*'.$cont.'*'.$fecvence."\r\n".$linea2."\r\n".$linea3."\r\n".$linea4."\r\n";
                fputs($fp,utf8_decode($linea));
            }
        }
        if($tipo == 'R'){
            if ($facpend >= 2){
                $linea = $proceso.'*'.$inmueble.'*'.$catastro.'*'.$cliente.'*'.$direccion.'*'.$urba.'*'.$factura.'*'.$zona.'*'.$fecexp.'*'.$mesi.'/'.$agno.'*'.$medidor.'*'.$calibre.'*'.$serial.'*'.$fecant.'*'.$lecant.'*'.$fecact.'*'.$lecact.'*'.$consumo.'*'.$metodo.'*'.$saldofavor.'*'.$saldodif.'*'.$valcon.'*'.$valotros.'*'.$deudatotal.'*'.$facpend.'*'.$totalfac.'*'.$fecvto.'**'.$factura.'**'.$uso.'*'.$ncf.'*'.$msjncf.'*'.$rnc.'*'.$texto.'*'.$debeperiodo.$mensaje_valor_fiscal_factura.'*'.$msjfac.'*'.$msjalerta.'*'.$msjburo.'*'.$cont.'*'.$fecvence."\r\n".$linea2."\r\n".$linea3."\r\n".$linea4."\r\n";
                fputs($fp,utf8_decode($linea));
            }
        }

*/
        if($tipo == 'T'){
            $linea = $proceso.'*'.$inmueble.'*'.$catastro.'*'.$cliente.'*'.$direccion.'*'.$urba.'*'.$factura.'*'.$zona.'*'.$fecexp.'*'.$mesi.'/'.$agno.'*'.$medidor.'*'.$calibre.'*'.$serial.'*'.$fecant.'*'.$lecant.'*'.$fecact.'*'.$lecact.'*'.$consumo.'*'.$metodo.'*'.$saldofavor.'*'.$saldodif.'*'.$valcon.'*'.$valotros.'*'.$deudatotal.'*'.$facpend.'*'.$totalfac.'*'.$fecvto.'**'.$factura.'**'.$uso.'*'.$ncf.'*'.$msjncf.'*'.$rnc.'*'.$texto.'*'.$debeperiodo.$mensaje_valor_fiscal_factura.'*'.$msjfac.'*'.$msjalerta.'*'.$msjburo.'*'.$fecvence.'*'.$cupoBasico.'*'.$estrato.'*'.$suministro.'*'.$fechaPag.'*'.$valPag."\r\n".$linea2."\r\n".$linea3."\r\n".$linea4."\r\n";
            fputs($fp,utf8_decode($linea));
        }
        if($tipo == 'A'){
            if ($facpend <= 1){
                $linea = $proceso.'*'.$inmueble.'*'.$catastro.'*'.$cliente.'*'.$direccion.'*'.$urba.'*'.$factura.'*'.$zona.'*'.$fecexp.'*'.$mesi.'/'.$agno.'*'.$medidor.'*'.$calibre.'*'.$serial.'*'.$fecant.'*'.$lecant.'*'.$fecact.'*'.$lecact.'*'.$consumo.'*'.$metodo.'*'.$saldofavor.'*'.$saldodif.'*'.$valcon.'*'.$valotros.'*'.$deudatotal.'*'.$facpend.'*'.$totalfac.'*'.$fecvto.'**'.$factura.'**'.$uso.'*'.$ncf.'*'.$msjncf.'*'.$rnc.'*'.$texto.'*'.$debeperiodo.$mensaje_valor_fiscal_factura.'*'.$msjfac.'*'.$msjalerta.'*'.$msjburo.'*'.$cont.'*'.$fecvence.'*'.$cupoBasico.'*'.$estrato.'*'.$suministro.'*'.$fechaPag.'*'.$valPag."\r\n".$linea2."\r\n".$linea3."\r\n".$linea4."\r\n";
                fputs($fp,utf8_decode($linea));
            }
        }
        if($tipo == 'R'){
            if ($facpend >= 2){
                $linea = $proceso.'*'.$inmueble.'*'.$catastro.'*'.$cliente.'*'.$direccion.'*'.$urba.'*'.$factura.'*'.$zona.'*'.$fecexp.'*'.$mesi.'/'.$agno.'*'.$medidor.'*'.$calibre.'*'.$serial.'*'.$fecant.'*'.$lecant.'*'.$fecact.'*'.$lecact.'*'.$consumo.'*'.$metodo.'*'.$saldofavor.'*'.$saldodif.'*'.$valcon.'*'.$valotros.'*'.$deudatotal.'*'.$facpend.'*'.$totalfac.'*'.$fecvto.'**'.$factura.'**'.$uso.'*'.$ncf.'*'.$msjncf.'*'.$rnc.'*'.$texto.'*'.$debeperiodo.$mensaje_valor_fiscal_factura.'*'.$msjfac.'*'.$msjalerta.'*'.$msjburo.'*'.$cont.'*'.$fecvence.'*'.$cupoBasico.'*'.$estrato.'*'.$suministro.'*'.$fechaPag.'*'.$valPag."\r\n".$linea2."\r\n".$linea3."\r\n".$linea4."\r\n";
                fputs($fp,utf8_decode($linea));
            }
        }
 /*       
        $cont++;
        /*if($cont==1){
            break;
        }*/

    }oci_free_statement($registrosC);
    fputs($fp,$cont);
    fclose($fp);
    ini_set('memory_limit','4096M');
    echo $archivo;
    exit;
?>