<?php
include_once '../clases/class.reportes_emitefac.php';
$rutaArchivo      = '../archivos_facturacion/';

/*Recepción de peticiones*/
    $proyecto = $_POST['proyecto'];
    $zonini   = $_POST['zonini'];
    $perini   = $_POST['perini'];
    $usoini   = $_POST['usoini'];
    $tipo = $_POST['tipo'];
/*Fin de recepción de peticiones*/

/*Preparación del archivo*/
if(!is_dir($rutaArchivo)){
    mkdir($rutaArchivo);
}

$fecha 		= 	 date('Y-M-D');
$archivo 	=   $rutaArchivo.strtoupper($proyecto.$zonini.$perini).'.txt';

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
        "Si necesita que su factura tenga valor fiscal favor de solicitarlo enviándonos un correo a catastro@caasdoriental.com.";

} else {
    $mensaje_valor_fiscal_factura =
        "Si necesita que su factura tenga valor fiscal favor de solicitarlo al" .
        " 809-523-6616 ext. 104 y 105.";

}
/*Fin de preparación del archivo*/

/*Declaración de funciones*/
    function search($array,$needle,$key = ''){

    if($key == '') return false;

    $found = array();
    for($i=0;$i<count($array);$i++){

        if($array[$i][$key] == $needle )
            array_push($found,$array[$i]);

    }

    return $found;
}
    /*function dibujarServiciosPorInmueble($arreglo){

        if(count($arreglo) == 0) return false;


    }*/
/*Fin de declaración de funciones*/



/*Datos generales de facturación*/

    $reportesEmiteFac = new ReportesEmiteFac();
    $datosArchivo     = $reportesEmiteFac->DatosArchivoOptimizado($perini,$zonini,$proyecto,$usoini);
    $datosGenerales   = array();
    while ($fila = oci_fetch_assoc($datosArchivo)){
     $id_proceso       = $fila['ID_PROCESO'];
     $codigo_inm       = $fila['CODIGO_INM'];
     $catastro         = $fila['CATASTRO'];
     $alias            = $fila['ALIAS'];
     $direccion        = $fila['DIRECCION'];
     $desc_urbanizacion = $fila['DESC_URBANIZACION'];
     $consec_factura   = $fila['CONSEC_FACTURA'];
     $id_zona          = $fila['ID_ZONA'];
     $fecexp           = $fila['FECEXP'];
     $periodo          = $fila['PERIODO'];
     $medidor          = $fila['MEDIDOR'];
     $calibre          = $fila['CALIBRE'];
     $serial           = $fila['SERIAL'];
     $fecant           = $fila['FECANT'];
     $lecant           = $fila['LECANT'];
     $fecant           = $fila['FECACT'];
     $consumo          = $fila['CONSUMO'];
     $consumo_minimo   = $fila['CONSUMO_MINIMO'];
     $metodo           = $fila['METODO'];
     $saldo_favor      = $fila['SALDOFAVOR'];
     $diferido         = $fila['DIFERIDO'];
     $valorcon         = $fila['VALORCON'];
     $valorotros       = $fila['VALOROTROS'];
     $deuda            = $fila['DEUDA'];
     $facpend          = $fila['FACPEND'];
     $total            = $fila['TOTAL'];
     $fecvcto          = $fila['FECVCTO'];
     $codbarra         = $fila['CODBARRA'];
     $desc_uso         = $fila['DESC_USO'];
     $ncf              = $fila['NCF'];
     $ncf_msj          = $fila['NCF_MSJ'];
     $feccorte         = $fila['FECCORTE'];
     $mora             = $fila['MORA'];
     $id_estado        = $fila['ID_ESTADO'];
     $facgen           = $fila['FACGEN'];
     $mora_periodo     = $fila['MORA_PERIODO'];
     $msj_periodo      = $fila['MSJ_PERIODO'];
     $msj_alerta       = $fila['MSJ_ALERTA'];
     $msj_buro         = $fila['MSJ_BURO'];
     $msj_factura      = $fila['MSJ_FACTURA'];
     $fecvence         = $fila['FECVENCE'];
     $tipo_doc         = $fila['TIPO_DOC'];

     $arr = array(  'ID_PROCESO'=>$id_proceso, 'CODIGO_INM' => $codigo_inm, 'CATASTRO' => $catastro,'ALIAS'=>$alias,
                    'DIRECCION'=>$direccion, 'DESC_URBANIZACION'=>$desc_urbanizacion, 'CONSEC_FACTURA'=>$consec_factura,
                    'ID_ZONA'=>$id_zona, 'FECEXP'=>$fecexp, 'PERIODO'=>$periodo, 'MEDIDOR'=>$medidor, 'CALIBRE'=>$calibre,
                    'SERIAL' =>$serial, 'FECANT'=>$fecant, 'LECANT'=>$lecant, 'CONSUMO'=>$consumo,
                    'CONSUMO_MINIMO'=>$consumo_minimo,'METODO' => $metodo, 'SALDO_FAVOR'=>$saldo_favor,
                    'DIFERIDO'=>$diferido, 'VALORCON'=>$valorcon, 'VALOROTROS'=>$valorotros, 'DEUDA'=>$deuda,
                    'FACPEND'=>$facpend, 'TOTAL'=>$total, 'FECVCTO'=>$fecvcto, 'CODBARRA'=>$codbarra,
                    'DESC_USO'=>$desc_uso, 'NCF'=>$ncf, 'NCF_MSJ'=>$ncf_msj, 'FECCORTE'=>$feccorte,'MORA'=>$mora,
                    'ID_ESTADO'=>$id_estado, 'FACGEN'=>$facgen, 'MORA_PERIODO'=>$mora_periodo, 'MSJ_PERIODO'=>$msj_periodo,
                    'MSJ_ALERTA'=>$msj_alerta, 'MSJ_BURO'=>$msj_buro, 'MSJ_FACTURA'=>$msj_factura,
                    'FECVENCE'=>$fecvence, 'TIPO_DOC'=>$tipo_doc);

     array_push($datosGenerales,$arr);


}

/*Fin de datos generales de facturación*/

/*Datos servicio*/
    /*include_once '../clases/class.reportes_emitefac.php';*/
    $reportesEmiteFac = new ReportesEmiteFac();
    $datosServicios     = $reportesEmiteFac->DatosServiciosOptimizado($proyecto,$zonini);
    $arrayDatosServicio = array();
    while ($fila = oci_fetch_assoc($datosServicios)){
      $codigo_inm      = $fila['CODIGO_INM'];
      $desc_servicio   = $fila['DESC_SERVICIO'];
      $codigo_tarifa   = $fila['CODIGO_TARIFA'];
      $unidades_hab    = $fila['UNIDADES_HAB'];
      $consec_tarifa   = $fila['CONSEC_TARIFA'];

      $arr = array('CODIGO_INM'=>$codigo_inm, 'DESC_SERVICIO'=>$desc_servicio, 'CODIGO_TARIFA'=>$codigo_tarifa, 'UNIDADES_HAB'=>$unidades_hab,
                    'CONSEC_TARIFA'=>$consec_tarifa);
      array_push($arrayDatosServicio,$arr);
    }
/*Fin de datos servicio*/

/*Datos rango conceptos*/
    /*include_once '../clases/class.reportes_emitefac.php';*/
    $reportesEmiteFac = new ReportesEmiteFac();
    $datosRangoConcepto = $reportesEmiteFac->DatosRangosConceptosOptimizado($proyecto,$perini,$zonini);
    $arrayRangoConcepto = array();
    while($fila = oci_fetch_assoc($datosRangoConcepto)){

        $concepto      = $fila['CONCEPTO'];
        $desc_servicio = $fila['DESC_SERVICIO'];
        $rango         = $fila['RANGO'];
        $descrango     = $fila['DESCRANGO'];
        $unidades      = $fila['UNIDADES'];
        $precio        = $fila['PRECIO'];
        $valor         = $fila['VALOR'];
        $codigo_inm    = $fila['CODIGO_INM'];

        $arr = array(   'CONCEPTO'=>$concepto, 'DESC_SERVICIO'=>$desc_servicio, 'RANGO'=>$rango, 'DESCRANGO'=>$descrango,
                        'UNIDADES'=>$unidades, 'PRECIO'=>$precio, 'VALOR'=>$valor, 'CODIGO_INM'=>$codigo_inm);

        array_push($arrayRangoConcepto, $arr);
    }
/*Fin datos rango conceptos*/

/*Datos otros conceptos*/
    /*include_once '../clases/class.reportes_emitefac.php';*/
    $reportesEmiteFac = new ReportesEmiteFac();
    $datosRangoOtrosConceptos = $reportesEmiteFac->DatosRangosOtrosConceptosOptimizado($proyecto,$periodo,$zonini);
    $arrayRangoOtrosConceptos = array();
    while ($fila = oci_fetch_assoc($datosRangoOtrosConceptos)){
       $concepto      = $fila['CONCEPTO'];
       $desc_servicio = $fila['DESC_SERVICIO'];
       $valor         = $fila['VALOR'];
       $codigo_inm    = $fila['CODIGO_INM'];

       $arr = array('CONCEPTO'=>$concepto, 'DESC_SERVICIO'=>$desc_servicio, 'VALOR'=>$valor, 'CODIGO_INM'=>$codigo_inm);
       array_push($arrayRangoOtrosConceptos,$arr);
    }
/*Fin de datos otros conceptos*/

$fp=fopen($archivo,"w");
/*Muestra de los datos*/
foreach ($datosGenerales as $datoGeneral){
    $codigo_inmueble = $datoGeneral['CODIGO_INM'];
    $consumo         = $datoGeneral['CONSUMO'];
    $consumomin      = $datoGeneral['CONSUMO_MIN'];
    $urba            = $datoGeneral['DESC_URBANIZACION'];
    $rnc             = $datoGeneral['RNC'];
    $fecact          = $datoGeneral['FECACT'];
    $saldofavor      = $datoGeneral['SALDOFAVOR'];
    $saldodif        = $datoGeneral['DIFERIDO'];
    $valcon          = $datoGeneral['VALORCON'];
    $valotros        = $datoGeneral['VALOROTROS'];
    $deudatotal      = $datoGeneral['DEUDA'];
    $debeperiodo     = $datoGeneral['MSJ_PERIODO'];

    $serviciosInmueble      = search($arrayDatosServicio,$codigo_inmueble,'CODIGO_INM');
    $conceptosInmueble      = search($arrayRangoConcepto,$codigo_inmueble,'CODIGO_INM');
    $otrosConceptosInmueble = search($arrayRangoOtrosConceptos,$codigo_inmueble,'CODIGO_INM');

    /*Dibujar reporte*/
    if($msjfac != '') $msjfac = $msjfac.' '.$feccorte;

    if($lecant == -1) $lecant = '';
    if($lecact == -1) $lecact = '';

    $linea2 = ''; $linea3 = ''; $linea4 = ''; $servactual = '';

    foreach ($serviciosInmueble as $servicioInmueble){
        if($servicio == 'Alcantarillado Red'  || $servicio == 'Alcantarillado Pozo') $servicio = 'Alcantarillado';
        if ($servactual != $servicio){
            $codtarifa   = $servicioInmueble['CODIGO_TARIFA'];
            $unidadtotal = $servicioInmueble['UNIDADES_HAB'];
            $linea2 .= $servicio.'*'.$uso.'*'.$codtarifa.'*'.$unidadtotal.'*';

            $cmobasico = $consumomin;
            //$totalcmo = $cmobasico*$valormt;
            if($consumo < $consumomin) $consumo = $consumomin;
        }
    }

    $linea2 = substr($linea2,0,strlen($linea2)-1);
    $conceptoactual = '';
    foreach ($conceptosInmueble as $conceptoInmueble){
        $desconcepto = $conceptoInmueble['DESC_SERVICIO'];
        $desrango    = $conceptoInmueble['DESCRANGO'];
        $precio      = $conceptoInmueble['PRECIO'];

        if($desconcepto == 'Alcantarillado Red'  || $desconcepto == 'Alcantarillado Pozo') $desconcepto = 'Alcantarillado';
        if ($conceptoactual != $desconcepto){
            $linea3 .= $desconcepto.'*'.$desrango.'*'.$unidades.'*'.$precio.'*'.$valor.'*';
        }

        $conceptoactual = $desconcepto;
    }

    $linea3 = substr($linea3,0,strlen($linea3)-1);
    $conceptoactual = '';

    foreach ($otrosConceptosInmueble as $otroConceptoInmueble){
        if($desconcepto == 'Mora') $desconcepto = 'Intereses de Mora';
        $linea4 .= $desconcepto.'*'.$valor.'*';
    }

    $linea4 = str_replace('SF','Saldo a Favor',$linea4);
    $linea4 = substr($linea4,0,strlen($linea4)-1);

    $agno = substr($periodo,0,4);
    $mesi = substr($periodo,4,2);
    if($mesi == '01') $mesi = 'Ene'; if($mesi == '02') $mesi = 'Feb'; if($mesi == '03') $mesi = 'Mar'; if($mesi == '04') $mesi = 'Abr'; if($mesi == '05') $mesi = 'May';
    if($mesi == '06') $mesi = 'Jun'; if($mesi == '07') $mesi = 'Jul'; if($mesi == '08') $mesi = 'Ago'; if($mesi == '09') $mesi = 'Sep'; if($mesi == '10') $mesi = 'Oct';
    if($mesi == '11') $mesi = 'Nov'; if($mesi == '12') $mesi = 'Dic';
    if($consumo < $consumomin) $consumo = $consumomin;
    $texto = "";

    if($metodo =="Promedio" || $medidor =="Sin Medidor") $metodo="  "; //Cambiar esto cualquier cosa
    if($tipodoc =="Cédula" || count($rnc)==11 ){
        $rnc = "  ";
        // $ncf = '*';
    }

    if($tipo == 'T'){
        $linea = $proceso.'*'.$inmueble.'*'.$catastro.'*'.$cliente.'*'.$direccion.'*'.$urba.'*'.$factura.'*'.$zona.'*'.$fecexp.'*'.$mesi.'/'.$agno.'*'.$medidor.'*'.$calibre.'*'.$serial.'*'.$fecant.'*'.$lecant.'*'.$fecact.'*'.$lecact.'*'.$consumo.'*'.$metodo.'*'.$saldofavor.'*'.$saldodif.'*'.$valcon.'*'.$valotros.'*'.$deudatotal.'*'.$facpend.'*'.$totalfac.'*'.$fecvto.'**'.$factura.'**'.$uso.'*'.$ncf.'*'.$msjncf.'*'.$rnc.'*'.$texto.'*'.$debeperiodo.'.'.$mensaje_valor_fiscal_factura.'*'.$msjfac.'*'.$msjalerta.'*'.$msjburo.'*'.$fecvence."\r\n".$linea2."\r\n".$linea3."\r\n".$linea4."\r\n";
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
    $cont++;

    /*Fin de dibujo de reporte*/
    /*var_dump($codigo_inmueble);
    var_dump($servicioInmueble);
    return true;*/
}

fputs($fp,$cont);
fclose($fp);
ini_set('memory_limit','4096M');
echo $archivo;
exit;
/*Fin de la muestra de los datos*/