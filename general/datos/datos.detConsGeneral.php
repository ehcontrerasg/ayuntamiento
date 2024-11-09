<?php
/**
 * Created by PhpStorm.
 * User: Ehcontrerasg
 * Date: 7/18/2016
 * Time: 12:14 PM
 */

$tipo = $_REQUEST['tip'];
session_start();
$coduser = $_SESSION['codigo'];

if ($tipo == 'flexyInm') {

    include_once "../../clases/class.inmueble.php";
    $proyecto    = $_REQUEST['proyecto'];
    $codinmueble = $_REQUEST['codSis'];
    $zonini      = $_REQUEST['zonIni'];
    $zonfin      = $_REQUEST['zonFin'];
    $procini     = $_REQUEST['procesoIni'];
    $procfin     = $_REQUEST['procesoFin'];
    $urbaniza    = $_REQUEST['urb'];
    $tipovia     = $_REQUEST['tipVia'];
    $nomvia      = $_REQUEST['NomVia'];
    $numcasa     = $_REQUEST['numCasa'];
    $estado      = $_REQUEST['estadoInm'];
    $estado_inm  = $_REQUEST['codEstInn'];
    $codcliente  = $_REQUEST['codCli'];
    $nomcliente  = $_REQUEST['nomCli'];
    $numdoc      = $_REQUEST['cedula'];
    $grupo       = $_REQUEST['grupoCli'];
    $tipocli     = $_REQUEST['tipoCli'];
    $numcon      = $_REQUEST['contrato'];
    $fecinicon   = $_REQUEST['fecIniCon'];
    $fecfincon   = $_REQUEST['fecFinCon'];
    $marca       = $_REQUEST['marcaMed'];
    $serial      = $_REQUEST['serial'];
    $emplaza     = $_REQUEST['emplazamiento'];
    $metodo      = $_REQUEST['suministro'];
    $fecinsini   = $_REQUEST['fecInsMedIni'];
    $fecinsfin   = $_REQUEST['fecInsMedFin'];
    $mora        = $_REQUEST['mora'];
    $totalizador = $_REQUEST['totalizadores'];
    $concepto    = $_REQUEST['concepto'];
    $uso         = $_REQUEST['uso'];
    $actividad   = $_REQUEST['actividad'];
    $tarifa      = $_REQUEST['tarifa'];
    $numfac      = $_REQUEST['factura'];
    $tipofac     = $_REQUEST['tipoFac'];
    $fecinipag   = $_REQUEST['ultPagIni'];
    $fecfinpag   = $_REQUEST['ultPagFin'];
    $catastroIni = $_REQUEST['catastroIni'];
    $catastroFin = $_REQUEST['catastroFin'];

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "I.ID_SECTOR, I.ID_ZONA, I.CODIGO_INM";
    }

    if (!$sortorder) {
        $sortorder = "ASC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 10;
    }

    $periodo = date('Ym');
    $end     = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start   = ($page) * $rp; // MIN_ROW_TO_FETCH

    //if ($query) $wherea = " AND $qtype LIKE UPPER('$query%') ";

    if ($proyecto != '') {
        $where .= " AND I.ID_PROYECTO = '$proyecto'";
    }

    if ($codinmueble != '') {
        $where .= " AND I.CODIGO_INM = '$codinmueble' ";
    }

    if ($secini != '' && $secfin == '') {
        $secfin = $secini;
    }

    if ($secini == '' && $secfin != '') {
        $secini = $secfin;
    }

    if ($secini != '' && $secfin != '') {
        $where .= " AND I.ID_SECTOR BETWEEN '$secini' AND '$secfin'";
    }

    if ($zonini != '' && $zonfin == '') {
        $zonfin = $zonini;
    }

    if ($zonini == '' && $zonfin != '') {
        $zonini = $zonfin;
    }

    if ($zonini != '' && $zonfin) {
        $where .= " AND I.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
    }

    if ($procini != '' && $procfin == '') {
        $procfin = $procini;
    }

    if ($procini == '' && $procfin != '') {
        $procini = $procfin;
    }

    if ($procini != '' && $procfin != '') {
        $where .= " AND I.ID_PROCESO BETWEEN '$procini' AND '$procfin'";
    }

    if ($catastroIni != '') {
        $where .= "  AND SUBSTR(I.CATASTRO,0,15)  >=  '$catastroIni' ";
    }

    if ($catastroFin != '') {
        $where .= "  AND SUBSTR(I.CATASTRO,0,15)  <=  '$catastroFin' ";
    }

    if ($tarifa != '') {
        $where .= "  AND SI.CONSEC_TARIFA='$tarifa' ";
    }
    if ($urbaniza != '') {
        $where .= " AND I.CONSEC_URB = '$urbaniza'";
    }

    if ($tipovia != '') {
        $where .= " AND I.CALLE = '$tipovia'";
    }

    if ($nomvia != '') {
        $where .= " AND I.NOM_CALLE = '$nomvia'";
    }

    if ($numcasa != '') {
        $where .= " AND I.NUMERO = '$numcasa'";
    }

    if ($estado == 'A') {
        $where .= " AND E.INDICADOR_ESTADO = 'A' AND C.FECHA_FIN IS NULL";
    }

    if ($estado == 'I') {
        $where .= " AND E.INDICADOR_ESTADO = 'I'";
    }

    /*if($estado == 'T') $where .= " AND C.FECHA_FIN IS NULL";*/
    if ($estado_inm != '') {
        $where .= " AND I.ID_ESTADO = '$estado_inm'";
    }

    if ($codcliente != '') {
        $where .= " AND C.CODIGO_CLI LIKE '$codcliente%'";
    }

    if ($nomcliente != '') {
        $where .= " AND UPPER(C.ALIAS) LIKE UPPER('%$nomcliente%')";
    }

    if ($numdoc != '') {
        $where .= " AND REPLACE(L.DOCUMENTO,'-','') LIKE REPLACE(UPPER('%$numdoc%'),'-','')";
    }

    if ($grupo != '') {
        $where .= " AND L.COD_GRUPO = '$grupo'";
    }

    if ($tipocli == 'GC') {
        $where .= " AND I.ID_TIPO_CLIENTE = 'GC'";
    }

    if ($tipocli == 'CN') {
        $where .= " AND I.ID_TIPO_CLIENTE = 'CN'";
    }

    if ($fecinicon != '' && $fecfincon == '') {
        $fecfincon = $fecinicon;
    }

    if ($fecinicon == '' && $fecfincon != '') {
        $fecinicon = $fecfincon;
    }

    if ($fecinicon != '' && $fecfincon) {
        $where .= " AND C.FECHA_INICIO BETWEEN TO_DATE('$fecinicon','YYYY-MM-DD') AND TO_DATE('$fecfincon','YYYY-MM-DD')";
    }

    if ($marca != '') {
        $where .= " AND M.COD_MEDIDOR = '$marca'";
    }

    if ($serial != '') {
        $where .= " AND M.SERIAL LIKE UPPER('%$serial%')";
    }

    if ($emplaza != '') {
        $where .= " AND M.COD_EMPLAZAMIENTO = '$emplaza'";
    }

    if ($metodo == 'R') {
        $where .= "  AND SI.COD_INMUEBLE = I.CODIGO_INM
        AND SI.COD_SERVICIO = 1";
        $from = ", SGC_TT_SERVICIOS_INMUEBLES SI";
    }
    if ($metodo == 'P') {
        $where .= "  AND SI.COD_INMUEBLE = I.CODIGO_INM
        AND SI.COD_SERVICIO = 3";
        $from = ", SGC_TT_SERVICIOS_INMUEBLES SI";
    }
    if ($fecinsini != '' && $fecinsfin == '') {
        $fecinsfin = $fecinsini;
    }

    if ($fecinsini == '' && $fecinsfin != '') {
        $fecinsini = $fecinsfin;
    }

    if ($fecinsini != '' && $fecinsfin) {
        $where .= " AND M.FECHA_INSTALACION BETWEEN TO_DATE('$fecinsini','YYYY-MM-DD') AND TO_DATE('$fecinsfin','YYYY-MM-DD')";
    }

    if ($mora == 'M') {
        $where .= " AND F.INMUEBLE = I.CODIGO_INM
        AND F.DEBE_MORA = 'S'
        AND F.PERIODO >= TO_CHAR(ADD_MONTHS(TO_DATE($periodo,'YYYYMM'),-1),'YYYYMM')";
        $from = ", SGC_TT_FACTURA F";
    }
    if ($mora == 'S') {
        $where .= " AND F.INMUEBLE = I.CODIGO_INM
        AND F.DEBE_MORA = 'N'
        AND F.PERIODO >= TO_CHAR(ADD_MONTHS(TO_DATE($periodo,'YYYYMM'),-1),'YYYYMM')";
        $from = ", SGC_TT_FACTURA F";
    }
    //aca va totalizador
    if ($uso != '') {
        $where .= " AND A.ID_USO = '$uso'";
    }

    if ($actividad != '') {
        $where .= " AND A.SEC_ACTIVIDAD = '$actividad'";
    }

    //aca va tarifa
    if ($numfac != '') {
        $where .= " AND F.INMUEBLE = I.CODIGO_INM AND F.CONSEC_FACTURA = '$numfac'";
        $from = ", SGC_TT_FACTURA F";
    }
    if ($tipofac == 'V') {
        $where .= " AND F.INMUEBLE = I.CODIGO_INM AND F.FEC_VCTO < SYSDATE AND F.FACTURA_PAGADA = 'N' AND F.PERIODO = $periodo";
        $from = ", SGC_TT_FACTURA F";
    }
    if ($fecinipag != '' && $fecfinpag == '') {
        $fecfinpag = $fecinipag;
    }

    if ($fecinipag == '' && $fecfinpag != '') {
        $fecinipag = $fecfinpag;
    }

    if ($fecinipag != '' && $fecfinpag) {
        $where .= " AND F.FECHA_PAGO BETWEEN TO_DATE('$fecinipag','YYYY-MM-DD') AND TO_DATE('$fecfinpag','YYYY-MM-DD')";
        $from = ", SGC_TT_FACTURA F";
    }
    if ($query) {
        $where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    // echo 'proyecto '. $proyecto;
    $a        = new Inmueble();
    $cantidad = $a->cantidadDatInmConsGen($where, $from);
    while (oci_fetch($cantidad)) {
        $total = oci_result($cantidad, 'CANTIDAD');
    }
    $l         = new Inmueble();
    $registros = $l->getDatInmConsGen($where, $sort, $start, $end, $from);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero         = oci_result($registros, 'RNUM');
        $inmueble       = oci_result($registros, 'CODIGO_INM');
        $zona           = oci_result($registros, 'ID_ZONA');
        $tipocli        = oci_result($registros, 'TIPO_CLIENTE');
        $direccion      = oci_result($registros, 'DIRECCION');
        $urbaniza       = oci_result($registros, 'DESC_URBANIZACION');
        $tipo_cliente   = oci_result($registros, 'ID_TIPO_CLIENTE');
        $estado         = oci_result($registros, 'ID_ESTADO');
        $catastro       = oci_result($registros, 'CATASTRO');
        $proceso        = oci_result($registros, 'ID_PROCESO');
        $contrato       = oci_result($registros, 'ID_CONTRATO');
        $codcliente     = oci_result($registros, 'CODIGO_CLI');
        $alias          = oci_result($registros, 'ALIAS');
        $nomcliente     = oci_result($registros, 'NOMBRE_CLI');
        $doccliente     = oci_result($registros, 'DOCUMENTO');
        $telefono       = oci_result($registros, 'TELEFONO');
        $serialmed      = oci_result($registros, 'SERIAL');
        $calibremed     = oci_result($registros, 'DESC_CALIBRE');
        $emplazamiento  = oci_result($registros, 'COD_EMPLAZAMIENTO');
        $fecinstalacion = oci_result($registros, 'FECHA_INSTALACION');
        $uso            = oci_result($registros, 'ID_USO');
        $acueducto      = oci_result($registros, 'ID_PROYECTO');
        $fecalta        = oci_result($registros, 'FEC_ALTA');
        $categoria      = oci_result($registros, 'CATEGORIA');
        $cupo           = oci_result($registros, 'CUPO_BASICO');
        $codservicio    = oci_result($registros, 'COD_SERVICIO');
        $metodo         = oci_result($registros, 'METODO_SUMINISTRO');

        if ($alias == '') {
            $alias = $nomcliente;
        }

        //BORRAR ESTO UNA VEZ SE ORGANICEN LAS TARIFAS
        /*        if ($codservicio == 1) {
        $metodo = 'R';
        }

        if ($codservicio == 3) {
        $metodo = 'P';
        }

        if ($uso == 'R') {
        if ($metodo == 'R') {
        if ($cupo >= 0 && $cupo <= 9.9) {
        $categoria = 'R1';
        }

        if ($cupo >= 10 && $cupo <= 15.9) {
        $categoria = 'R2';
        }

        if ($cupo >= 16 && $cupo <= 21.9) {
        $categoria = 'R3';
        }

        if ($cupo >= 22 && $cupo <= 31.9) {
        $categoria = 'R4';
        }

        if ($cupo >= 32) {
        $categoria = 'R5';
        }

        }
        if ($metodo == 'P') {
        if ($cupo >= 0 && $cupo <= 59.9) {
        $categoria = 'R1';
        }

        if ($cupo >= 60 && $cupo <= 95.9) {
        $categoria = 'R2';
        }

        if ($cupo >= 96 && $cupo <= 131.9) {
        $categoria = 'R3';
        }

        if ($cupo >= 132 && $cupo <= 191.9) {
        $categoria = 'R4';
        }

        if ($cupo >= 192) {
        $categoria = 'R5';
        }

        }
        }*/
        /////////////////////////////////////////////
        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $inmueble . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($acueducto) . "'";
        $json .= ",'" . addslashes($tipocli) . "'";
        $json .= ",'" . addslashes($inmueble) . "'";
        $json .= ",'" . addslashes($zona) . "'";
        $json .= ",'" . addslashes($urbaniza) . "'";
        $json .= ",'" . addslashes($direccion) . "'";
        $json .= ",'" . addslashes($estado) . "'";
        $json .= ",'" . addslashes($catastro) . "'";
        $json .= ",'" . addslashes($proceso) . "'";
        $json .= ",'" . addslashes($uso) . "'";
        $json .= ",'" . addslashes($categoria) . "'";
        $json .= ",'" . addslashes($codcliente) . "'";
        $json .= ",'" . addslashes($alias) . "'";
        $json .= ",'" . addslashes($doccliente) . "'";
        $json .= ",'" . addslashes($serialmed) . "'";
        $json .= ",'" . addslashes($calibremed) . "'";
        $json .= ",'" . addslashes($fecinstalacion) . "'";
        $json .= ",'" . addslashes($fecalta) . "'";
        $json .= ",'" . addslashes($metodo) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}
if ($tipo == 'flexyMed') {

    include '../../clases/class.medidor.php';
    $codinmueble = $_REQUEST['inm'];

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "FECHA_INSTALACION";
    }

    if (!$sortorder) {
        $sortorder = "DESC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 100;
    }

//$periodo = date('Ym');
    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $where = " AND M.COD_INMUEBLE = '$codinmueble'";

    if ($query) {
        $where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
    }

    $l         = new Medidor();
    $registros = $l->getDatMedFlexy($where, $sort, $start, $end);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
//$json .= "total: 1,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero        = oci_result($registros, 'RNUM');
        $marcamed      = oci_result($registros, 'DESC_MED');
        $emplazamiento = oci_result($registros, 'DESC_EMPLAZAMIENTO');
        $calibremed    = oci_result($registros, 'DESC_CALIBRE');
        $serialmed     = oci_result($registros, 'SERIAL');
        $fecinstal     = oci_result($registros, 'FECHA');
        $fecbaja       = oci_result($registros, 'FECBAJA');
        $metodosum     = oci_result($registros, 'DESC_SUMINISTRO');
        $estadomed     = oci_result($registros, 'DESCRIPCION');
        $lecinstal     = oci_result($registros, 'LECTURA_INSTALACION');
        $obsins        = oci_result($registros, 'OBS_INS');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $serialmed . "',";
        $json .= "title:'" . $obsins . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($serialmed) . "'";
        $json .= ",'" . addslashes($marcamed) . "'";
        $json .= ",'" . addslashes($calibremed) . "'";
        $json .= ",'" . addslashes($emplazamiento) . "'";
        $json .= ",'" . addslashes($fecinstal) . "'";
        $json .= ",'" . addslashes($fecbaja) . "'";
        $json .= ",'" . addslashes($estadomed) . "'";
        $json .= ",'" . addslashes($metodosum) . "'";
        $json .= ",'" . addslashes($lecinstal) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'flexyMedHij') {

    include '../../clases/class.medidor.php';
    $codinmueble = $_REQUEST['inm'];

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "FECHA_INSTALACION";
    }

    if (!$sortorder) {
        $sortorder = "DESC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 100;
    }

//$periodo = date('Ym');
    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $where = " AND IT.COD_INM_PADRE = '$codinmueble'";

    if ($query) {
        $where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
    }

    $l         = new Medidor();
    $registros = $l->getDatMedHijFlexy($where, $sort, $start, $end);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
//$json .= "total: 1,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero        = oci_result($registros, 'RNUM');
        $inmHij        = oci_result($registros, 'COD_INMUEBLE');
        $marcamed      = oci_result($registros, 'DESC_MED');
        $emplazamiento = oci_result($registros, 'DESC_EMPLAZAMIENTO');
        $calibremed    = oci_result($registros, 'DESC_CALIBRE');
        $serialmed     = oci_result($registros, 'SERIAL');
        $fecinstal     = oci_result($registros, 'FECHA');
        $metodosum     = oci_result($registros, 'DESC_SUMINISTRO');
        $estadomed     = oci_result($registros, 'DESCRIPCION');
        $lecinstal     = oci_result($registros, 'LECTURA_INSTALACION');
        $obsins        = oci_result($registros, 'OBS_INS');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $serialmed . "',";
        $json .= "title:'" . $obsins . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($inmHij) . "'";
        $json .= ",'" . addslashes($serialmed) . "'";
        $json .= ",'" . addslashes($marcamed) . "'";
        $json .= ",'" . addslashes($calibremed) . "'";
        $json .= ",'" . addslashes($emplazamiento) . "'";
        $json .= ",'" . addslashes($fecinstal) . "'";
        $json .= ",'" . addslashes($estadomed) . "'";
        $json .= ",'" . addslashes($metodosum) . "'";
        $json .= ",'" . addslashes($lecinstal) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'flexyServ') {

    include '../../clases/class.inmueble.php';
    $codinmueble = $_REQUEST['inm'];

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "COD_SERVICIO";
    }

    if (!$sortorder) {
        $sortorder = "ASC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 100;
    }

//$periodo = date('Ym');
    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $where = " AND S.COD_INMUEBLE = '$codinmueble'";

    if ($query) {
        $where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
    }

    $l         = new Inmueble();
    $registros = $l->getSerByInmFlexy($where, $sort, $start, $end);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
//$json .= "total: 1,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero      = oci_result($registros, 'RNUM');
        $cod_ser     = oci_result($registros, 'COD_SERVICIO');
        $des_ser     = oci_result($registros, 'DESC_SERVICIO');
        $tarifa      = oci_result($registros, 'DESC_TARIFA');
        $uni_tot     = oci_result($registros, 'UNIDADES_TOT');
        $uni_hab     = oci_result($registros, 'UNIDADES_HAB');
        $uni_des     = oci_result($registros, 'UNIDADES_DESH');
        $cupo_basico = oci_result($registros, 'CUPO_BASICO');
        $promedio    = oci_result($registros, 'PROMEDIO');
        $consumo_min = oci_result($registros, 'CONSUMO_MINIMO');
        $calculo     = oci_result($registros, 'DESC_CALCULO');
        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $cod_ser . "',";
        $json .= "cell:['" . $cod_ser . "'";
        $json .= ",'" . addslashes($des_ser) . "'";
        $json .= ",'" . addslashes($tarifa) . "'";
        $json .= ",'" . addslashes($uni_tot) . "'";
        $json .= ",'" . addslashes($uni_hab) . "'";
        $json .= ",'" . addslashes($uni_des) . "'";
        $json .= ",'" . addslashes($cupo_basico) . "'";
        $json .= ",'" . addslashes($promedio) . "'";
        $json .= ",'" . addslashes($consumo_min) . "'";
        $json .= ",'" . addslashes($calculo) . "'";

        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'flexyFact') {

    include '../../clases/class.inmueble.php';
    include '../../clases/class.factura.php';
    $inmueble = $_REQUEST['inm'];
//$fecini = '2015-08-06';
    //$fecfin = '2015-08-06';

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "PERIODO";
    }

    if (!$sortorder) {
        $sortorder = "desc";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $fname = "PERIODO";
    $tname = "SGC_TT_FACTURA";
    $where = " AND INMUEBLE='$inmueble'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = " AND COD_INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new Inmueble();
    $registros = $l->getFacByInmFlexy($where, $sort, $start, $end);
    $total     = 111;
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero      = oci_result($registros, 'RNUM');
        $factura     = oci_result($registros, 'CONSEC_FACTURA');
        $periodo     = oci_result($registros, 'PERIODO');
        $expedicion  = oci_result($registros, 'FEC_EXPEDICION');
        $total       = oci_result($registros, 'TOTAL');
        $ncf         = oci_result($registros, 'NCF');
        $totalpagado = oci_result($registros, 'TOTAL_PAGADO');
        $feclectura  = oci_result($registros, 'FECHA_LECTURA');
        $fecpago     = oci_result($registros, 'FECHA_PAGO');
        $diaspago    = oci_result($registros, 'DIAS');
        $lectura     = oci_result($registros, 'LECTURA');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $factura . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($factura) . "'";
        $json .= ",'" . addslashes($periodo) . "'";
        $json .= ",'" . addslashes($feclectura) . "'";
        $json .= ",'" . addslashes($lectura) . "'";
        $json .= ",'" . addslashes($expedicion) . "'";
        $json .= ",'" . addslashes($ncf) . "'";
        $json .= ",'" . addslashes("$" . $total) . "'";
        $json .= ",'" . addslashes("$" . $totalpagado) . "'";
        $json .= ",'" . addslashes($fecpago) . "'";
        $json .= ",'" . addslashes($diaspago) . "'";
        $f = new Factura();
        if ($f->verificarel($factura)) {
            $json .= ",'" . "<b><a href=\"JAVASCRIPT:rel(" . $factura . ");\">" . "<img src=\"../../images/reliquidacion.jpg\" width=\"15\" height=\"15\"/>" . " </a></b>" . "'";
        } else {
            $json .= ",'" . "NO REL" . "'";
        }
        if ($f->VerificarNota($factura)) {
            $json .= ",'" . "<b><a href=\"JAVASCRIPT:NotaCreditoPDF(" . $factura . ");\">" . "<img src=\"../../images/reliquidacion.jpg\" width=\"15\" height=\"15\"/>" . " </a></b>" . "'";
        } else {
            $json .= ",'" . "NO NOTA" . "'";
        }      
        $json .= ",'" . "<b><a href=\"JAVASCRIPT:EnvioEmail(". $factura .", ". $inmueble .");\">" . "Envio Factura" . "</a></b>" . "'";


        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'flexyDetFac') {

    include '../../clases/class.factura.php';
    $factura = $_REQUEST['fac'];
    $codinm  = $_REQUEST['inm'];

    function countRec($fname, $tname, $where, $sort)
    {

        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "CONCEPTO";
    }

    if (!$sortorder) {
        $sortorder = "ASC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $fname = "CONCEPTO";
    $tname = "SGC_TT_DETALLE_FACTURA";
//$where = " AND FACTURA='$factura'";
    //$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = " AND FACTURA='$factura' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }

    $l         = new Factura();
    $registros = $l->getDetFacByFacFlexy($where, $sort, $start, $end, $factura);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;

    while (oci_fetch($registros)) {
        $numero      = oci_result($registros, 'RNUM');
        $concepto    = oci_result($registros, 'CONCEPTO');
        $rango       = oci_result($registros, 'RANGO');
        $unidades    = oci_result($registros, 'UNIDADES');
        $valor       = oci_result($registros, 'VALOR');
        $codservicio = oci_result($registros, 'COD_SERVICIO');

        if ($concepto == 'Agua' || $concepto == 'Agua de Pozo') {
            if ($rango == 0) {
                $f     = new Factura();
                $stidb = $f->getValRangServByServRangInm($codservicio, 1, $codinm);
                while (oci_fetch($stidb)) {
                    $valor_mt = oci_result($stidb, 'VALOR_METRO');
                }
                oci_free_statement($stidb);
            } else {
                $f     = new Factura();
                $stidb = $f->getValRangServByServRangInm2($codservicio, $rango, $codinm);
                while (oci_fetch($stidb)) {
                    $valor_mt = oci_result($stidb, 'VALOR_METRO');
                }
                oci_free_statement($stidb);
            }
            $valor_mt_alc = $valor_mt * 0.2;
        }
        if ($concepto == 'Alcantarillado Red' || $concepto == 'Alcantarillado Pozo') {
            $valor_mt = $valor_mt_alc;
        }
        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $factura . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($concepto) . "'";
        $json .= ",'" . addslashes($rango) . "'";
        $json .= ",'" . addslashes($unidades) . "'";
        $json .= ",'" . addslashes($valor_mt) . "'";
        $json .= ",'<b style=color:#990000>" . addslashes("RD$ " . $valor) . "</b>'";
        $json .= "]}";
        $rc = true;
        unset($valor_mt);
    }
    oci_free_statement($registros);
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'flexyLec') {

    include '../../clases/class.inmueble.php';

    $codinmueble = $_REQUEST['inm'];

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "PERIODO";
    }

    if (!$sortorder) {
        $sortorder = "DESC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 100;
    }

//$periodo = date('Ym');
    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    //$where = " WHERE R.COD_INMUEBLE = '$codinmueble'";

    if ($query) {
        $where .= " AND UPPER($qtype) LIKE UPPER('$query%') ";
    }

    $l         = new Inmueble();
    $registros = $l->getLecByInmFlexy($where, $sort, $start, $end, $codinmueble);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
//$json .= "total: 1,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero      = oci_result($registros, 'RNUM');
        $periodo     = oci_result($registros, 'PERIODO');
        $lec_actual  = oci_result($registros, 'LECTURA_ACTUAL');
        $fec_lec     = oci_result($registros, 'FECLEC');
        $observacion = oci_result($registros, 'OBSERVACION_ACTUAL');
        $cod_lector  = oci_result($registros, 'LECTOR');
        $consumo     = oci_result($registros, 'CONSUMO');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $periodo . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($periodo) . "'";
        $json .= ",'" . addslashes($lec_actual) . "'";
        $json .= ",'" . addslashes($consumo) . "'";
        $json .= ",'" . addslashes($fec_lec) . "'";
        $json .= ",'" . addslashes($observacion) . "'";
        $json .= ",'" . addslashes($cod_lector) . "'";

        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}
if ($tipo == 'flexyFacLecEnt') {

    include '../../clases/class.factura.php';
    include '../../clases/class.lectura.php';
    $factura = $_REQUEST['fac'];

    function countRec($fname, $tname, $where, $sort)
    {
        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "LOGIN";
    }

    if (!$sortorder) {
        $sortorder = "ASC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $where = " AND F.CONSEC_FACTURA='$factura'";

    if ($query) {
        $where = " AND F.CONSEC_FACTURA='$factura' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new Lectura();
    $registros = $l->getLecturaByFactFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {

        $lectura     = oci_result($registros, 'LECTURA_ORIGINAL');
        $observacion = oci_result($registros, 'OBSERVACION_ACTUAL');
        $login       = oci_result($registros, 'LOGIN');
        $consumo     = oci_result($registros, 'CONSUMO');
        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $factura . "',";
        $json .= "cell:['$lectura'";
        $json .= ",'" . addslashes($observacion) . "'";
        $json .= ",'" . addslashes($login) . "'";
        $json .= ",'" . addslashes($consumo) . "'";
        $json .= "]},";
        $rc = true;
    }

    if ($rc) {
        $json .= "";
    }

    $json .= "\n{";
    $json .= "id:'" . 'TotFacturas' . "',";
    $json .= "cell:['<font class=\'titulo1\' style=\'color:#000000\'><b>" . 'Entrega' . "</b></font>'";
    $json .= ",'<font class=\'titulo1\' style=\'color:#000000\'><b>" . addslashes('Observaci&oacute;n') . "</b></font>'";
    $json .= ",'<font class=\'titulo1\' style=\'color:#000000\'><b>" . addslashes("Operario") . "</b></font>'";
    $json .= ",'<font class=\'titulo1\' style=\'color:#000000\'><b>" . addslashes('Fecha') . "</b></font>'";

    $json .= "]},";
    $rc = true;

    $where = " AND F.CONSEC_FACTURA='$factura'";


    if ($query) {
        $where = " AND F.CONSEC_FACTURA='$factura' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }

    $l         = new Factura();
    $registros = $l->getDatEntreFactByFactFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);

    $rc = false;
    while (oci_fetch($registros)) {

        $entregado   = oci_result($registros, 'ENTREGADO');
        $login       = oci_result($registros, 'LOGIN');
        $observacion = oci_result($registros, 'OBS_NOENTREGA');
        $fechaeje = oci_result($registros, 'FECHA_EJECUCION');
        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $factura . "',";
        $json .= "cell:['$entregado'";
        $json .= ",'" . addslashes($observacion) . "'";
        $json .= ",'" . addslashes($login) . "'";
        $json .= ",'" . addslashes($fechaeje) . "'";
        $json .= "]}";
        $rc = true;

    } /**/
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'fielSetInm') {
    include_once '../../clases/class.inmueble.php';
    $l        = new Inmueble();
    $inmueble = $_REQUEST['inm'];
    $datos    = $l->GetDatInmByCod($inmueble);
    $i        = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}

if ($tipo == 'fotosInm') {
    include_once '../../clases/class.inmueble.php';
    $l        = new Inmueble();
    $inmueble = $_REQUEST['inm'];
    $datos    = $l->getFotTotByInm($inmueble);
    $i        = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}

if ($tipo == 'saldFav') {
    include_once '../../clases/class.inmueble.php';
    $l        = new Inmueble();
    $inmueble = $_REQUEST['inm'];
    $datos    = $l->getSaldoFavorByInm($inmueble);
    $i        = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}

if ($tipo == 'difPend') {
    include_once '../../clases/class.inmueble.php';
    $l        = new Inmueble();
    $inmueble = $_REQUEST['inm'];
    $datos    = $l->getDiferidosByInm($inmueble);
    $i        = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}

if ($tipo == 'recValPend') {
    include_once '../../clases/class.reconexion.php';
    $l        = new Reconexion();
    $inmueble = $_REQUEST['inm'];
    $datos    = $l->getValRecByByInmConsGen($inmueble);
    $i        = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $con[$i] = $row;
        $i++;
    }
    echo json_encode($con);
}

if ($tipo == 'tipTot') {
    include_once '../../clases/class.medidor.php';
    $inmueble = $_REQUEST['inm'];
    $l        = new Medidor();
    $hijo     = $l->getSiHijoTotByInm($inmueble);

    $l     = new Medidor();
    $padre = $l->getSiPadreTotByInm($inmueble);
    if ($hijo > 0) {
        $resultado = "Hijo";
    } else if ($padre > 0) {
        $resultado = "Padre";
    } else {
        $resultado = "N/A";
    }

    echo $resultado;
}

if ($tipo == 'flexDatDif') {

    include '../../clases/class.inmueble.php';
    $inmueble = $_REQUEST['inm'];

    function countRec($fname, $tname, $where, $sort)
    {

        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "CODIGO";
    }

    if (!$sortorder) {
        $sortorder = "DESC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $fname = "numero_cuotas";
    $tname = "SGC_TT_DIFERIDOS";
    $where = " AND INMUEBLE='$inmueble'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = " AND INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new Inmueble();
    $registros = $l->getDifByInmFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero         = oci_result($registros, 'RNUM');
        $cod_diferido   = oci_result($registros, 'CODIGO');
        $des_diferido   = oci_result($registros, 'DESC_SERVICIO');
        $valor_diferido = oci_result($registros, 'VALOR_DIFERIDO');
        $numero_cuotas  = oci_result($registros, 'NUMERO_CUOTAS');
        $valor_cuota    = oci_result($registros, 'VALOR_CUOTA');
        $cuotas_pagadas = oci_result($registros, 'CUOTAS_PAGADAS');
        $valor_pagado   = oci_result($registros, 'VALOR_PAGADO');
        $pendiente      = oci_result($registros, 'PENDIENTE');
        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $cod_diferido . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($cod_diferido) . "'";
        $json .= ",'" . addslashes($des_diferido) . "'";
        $json .= ",'<b>" . addslashes("RD$ " . $valor_cuota) . "</b>'";

        $json .= ",'" . addslashes($numero_cuotas) . "'";
        $json .= ",'<b>" . addslashes("RD$ " . $valor_diferido) . "</b>'";
        $json .= ",'" . addslashes($cuotas_pagadas) . "'";
        $json .= ",'<b>" . addslashes("RD$ " . $valor_pagado) . "</b>'";
        $json .= ",'<b style=color:#990000>" . addslashes("RD$ " . $pendiente) . "</b>'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}
if ($tipo == 'flexDatPagAplicFct') {

    include '../../clases/class.pago.php';
    $factura = $_REQUEST['fac'];

    function countRec($fname, $tname, $where, $sort)
    {

        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "PA.FECHA_PAGO";
    }

    if (!$sortorder) {
        $sortorder = "DESC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $fname = "FECHA_PAGO";
    $tname = "SGC_TT_PAGO_FACTURAS PF, SGC_TT_PAGOS PA";
    $where = " AND PF.FACTURA='$factura'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = " AND PF.FACTURA='$factura' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new Pago();
    $registros = $l->getDetPagByFactFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $pago     = oci_result($registros, 'PAGO');
        $fecha    = oci_result($registros, 'FECHA');
        $aplicado = oci_result($registros, 'APLICADO');
        $valpago  = oci_result($registros, 'VALPAGO');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $pago . "',";
        $json .= "cell:['" . $pago . "'";
        $json .= ",'" . addslashes($fecha) . "'";
        $json .= ",'<b>" . addslashes("RD$ " . $aplicado) . "</b>'";
        $json .= ",'<b>" . addslashes("RD$ " . $valpago) . "</b>'";

        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'flexDatPdc') {

    include '../../clases/class.inmueble.php';
    $inmueble = $_REQUEST['inm'];

    function countRec($fname, $tname, $where, $sort)
    {

        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "ID_DEUDA_CERO";
    }

    if (!$sortorder) {
        $sortorder = "DESC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $fname = "total_diferido";
    $tname = "sgc_tt_deuda_cero";
    $where = " AND COD_INMUEBLE='$inmueble'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = " AND COD_INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new Inmueble();
    $registros = $l->getDeudCerByInmFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;

    while (oci_fetch($registros)) {

        $numero         = oci_result($registros, 'RNUM');
        $valor_diferido = oci_result($registros, 'TOTAL_DIFERIDO');
        $numero_cuotas  = oci_result($registros, 'TOTAL_CUOTAS');
        $cuotas_pagadas = oci_result($registros, 'TOTAL_CUOTAS_PAG');
        $valor_pagado   = oci_result($registros, 'TOTAL_PAGADO');
        $pendiente      = oci_result($registros, 'PENDIENTE');
        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $numero . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'<b>" . addslashes("RD$ " . $valor_diferido) . "</b>'";
        $json .= ",'" . addslashes($numero_cuotas) . "'";
        $json .= ",'" . addslashes($cuotas_pagadas) . "'";
        $json .= ",'<b>" . addslashes("RD$ " . $valor_pagado) . "</b>'";
        $json .= ",'<b style=color:#990000>" . addslashes("RD$ " . $pendiente) . "</b>'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'flexEstCuenta') {

    include '../../clases/class.pago.php';
    include '../../clases/class.otroRecaudo.php';
    include '../../clases/class.factura.php';
    $inmueble = $_REQUEST['inm'];

    function countRec()
    {
        return 100;
    }

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 10000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH

    $l         = new Factura();
    $registros = $l->getFacByInmAsc($inmueble);

    $o          = new Pago();
    $registros2 = $o->getPagosByInmAsc($inmueble);

    $p          = new OtroRecaudo();
    $registros3 = $p->getOtrosRecByInmAsc($inmueble);

    $total = countRec();
    $json  = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;

    $bandPago     = false;
    $bandOtrosR   = false;
    $bandFact     = false;
    $entrowhile   = false;
    $noentrowhile = true;

    $datospago = true;
    $datosotro = true;
    $datosfac  = true;

    $saldo = 0;
    while ($bandPago || $bandOtrosR || $bandFact || $noentrowhile) {

        if ($noentrowhile == false) {
            if ($fechcomp <= $fechcomp2 && $fechcomp <= $fechcomp3) {
                $saldo += $total;
                if ($rc) {
                    $json .= ",";
                }

                $json .= "\n{";
                $json .= "id:'" . $desc . "',";
                $json .= "cell:['" . $fecha . "'";
                $json .= ",'" . addslashes($desc) . "'";
                $json .= ",'" . addslashes("$" . $total) . "'";
                $json .= ",'" . addslashes("$" . "0") . "'";
                $json .= ",'" . addslashes("$" . $saldo) . "'";
                $json .= "]}";
                $rc       = true;
                $bandFact = false;

            } else if ($fechcomp2 <= $fechcomp && $fechcomp2 <= $fechcomp3) {
                $saldo -= $total2;
                if ($rc) {
                    $json .= ",";
                }

                $json .= "\n{";
                $json .= "id:'" . $desc2 . "',";
                $json .= "cell:['" . $fecha2 . "'";
                $json .= ",'" . addslashes($desc2) . "'";
                $json .= ",'" . addslashes("$" . "0") . "'";
                $json .= ",'" . addslashes("$" . $total2) . "'";
                $json .= ",'" . addslashes("$" . $saldo) . "'";
                $json .= "]}";
                $rc       = true;
                $bandPago = false;

            } else {
                $saldo -= $tota3;
                if ($rc) {
                    $json .= ",";
                }

                $json .= "\n{";
                $json .= "id:'" . $desc3 . "',";
                $json .= "cell:['" . $fecha3 . "'";
                $json .= ",'" . addslashes($desc3) . "'";
                $json .= ",'" . addslashes("$" . "0") . "'";
                $json .= ",'" . addslashes("$" . $tota3) . "'";
                $json .= ",'" . addslashes("$" . $saldo) . "'";
                $json .= "]}";
                $rc         = true;
                $bandOtrosR = false;

            }
        }

        $noentrowhile = false;
        if ($bandFact == false && $datosfac) {
            if (oci_fetch($registros)) {
                $fecha    = oci_result($registros, 'FEC_EXPEDICION');
                $desc     = oci_result($registros, 'DESCRIPCION');
                $total    = oci_result($registros, 'TOTAL');
                $fechcomp = oci_result($registros, 'FECHCOMP');
                $bandFact = true;

            } else {

                $fechcomp = 99999999;
                $bandFact = false;
                $datosfac = false;
            }
        }

        if ($bandPago == false && $datospago) {
            if (oci_fetch($registros2)) {
                $fecha2    = oci_result($registros2, 'FECHA_PAGO');
                $desc2     = oci_result($registros2, 'DESCRIPCION');
                $total2    = oci_result($registros2, 'IMPORTE');
                $fechcomp2 = oci_result($registros2, 'FECHCOMP');
                $bandPago  = true;
            } else {

                $fechcomp2 = 99999999;
                $bandPago  = false;
                $datospago = false;
            }
        }

        if ($bandOtrosR == false && $datosotro) {
            if (oci_fetch($registros3)) {
                $fecha3     = oci_result($registros3, 'FECHA');
                $desc3      = oci_result($registros3, 'DESCRIPCION');
                $total3     = oci_result($registros3, 'IMPORTE');
                $fechcomp3  = oci_result($registros3, 'FECHCOMP');
                $bandOtrosR = true;

            } else {

                $fechcomp3  = 99999999;
                $bandOtrosR = false;
                $datosotro  = false;
            }
        }

    }

    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'flexyDiferidos') {

    include '../../clases/class.inmueble.php';
    $inmueble = $_REQUEST['inm'];

    function countRec($fname, $tname, $where, $sort)
    {
        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "D.PER_INI";
    }

    if (!$sortorder) {
        $sortorder = "desc";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $fname = "DIF.PER_INI";
    $tname = "SGC_TT_DIFERIDOS";
    $where = " AND DIF.INMUEBLE='$inmueble'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = " AND D.INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new Inmueble();
    $registros = $l->getDifByInmFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero         = oci_result($registros, 'RNUM');
        $diferido       = oci_result($registros, 'CODIGO');
        $descripcion    = oci_result($registros, 'DESC_SERVICIO');
        $fecha_apertura = oci_result($registros, 'FECHA_APERTURA');
        $valordif       = oci_result($registros, 'VALOR_DIFERIDO');
        $activo         = oci_result($registros, 'ACTIVO');
        $cuotas_pag     = oci_result($registros, 'CUOTAS_PAGADAS');
        $valpag         = oci_result($registros, 'VALOR_PAGADO');
        $numcuo         = oci_result($registros, 'NUMERO_CUOTAS');
        $perini         = oci_result($registros, 'PER_INI');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $diferido . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($diferido) . "'";
        $json .= ",'" . addslashes($descripcion) . "'";
        $json .= ",'" . addslashes($fecha_apertura) . "'";
        $json .= ",'" . addslashes($valordif) . "'";
        $json .= ",'" . addslashes($activo) . "'";
        $json .= ",'" . addslashes($cuotas_pag) . "'";
        $json .= ",'" . addslashes($valpag) . "'";
        $json .= ",'" . addslashes($numcuo) . "'";
        $json .= ",'" . addslashes($perini) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'flexyCuotaDiferidos') {

    include '../../clases/class.diferido.php';
    $diferido = $_REQUEST['dif'];
//$fecini = '2015-08-06';
    //$fecfin = '2015-08-06';

    function countRec($fname, $tname, $where, $sort)
    {
        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "DD.PERIODO";
    }

    if (!$sortorder) {
        $sortorder = "desc";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $fname = "D.PER_INI";
    $tname = "sgc_tt_detalle_diferidos";
    $where = " AND DD.COD_DIFERIDO='$diferido'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = " AND DD.COD_DIFERIDO='$diferido' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new Diferido();
    $registros = $l->getCuotasDifByDifFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero    = oci_result($registros, 'RNUM');
        $numcuota  = oci_result($registros, 'NUM_CUOTA');
        $valor     = oci_result($registros, 'VALOR');
        $valpagado = oci_result($registros, 'VALOR_PAGADO');
        $periodo   = oci_result($registros, 'PERIODO');
        $fechpago  = oci_result($registros, 'FECHA_PAGO');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $diferido . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($numcuota) . "'";
        $json .= ",'" . addslashes($valor) . "'";
        $json .= ",'" . addslashes($valpagado) . "'";
        $json .= ",'" . addslashes($periodo) . "'";
        $json .= ",'" . addslashes($fechpago) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}
if ($tipo == 'flexyPagos') {

    include './../../clases/class.pago.php';
    $inmueble = $_REQUEST['inm'];
//$fecini = '2015-08-06';
    //$fecfin = '2015-08-06';

    function countRec($fname, $tname, $where, $sort)
    {

        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "FECHA_PAGO";
    }

    if (!$sortorder) {
        $sortorder = "desc";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $fname = "FECHA_PAGO";
    $tname = "SGC_TT_PAGOS";
    $where = " AND INM_CODIGO='$inmueble'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = " AND INM_CODIGO='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new Pago();
    $registros = $l->getPagByInmFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero       = oci_result($registros, 'RNUM');
        $codigo       = oci_result($registros, 'CODIGO');
        $fechapag     = oci_result($registros, 'FECHA_PAGO');
        $referencia   = oci_result($registros, 'REFERENCIA');
        $importe      = oci_result($registros, 'IMPORTE');
        $motivoanula  = oci_result($registros, 'MOTIVO_REV');
        $fechaanula   = oci_result($registros, 'FECHA_REV');
        $usuarioanula = oci_result($registros, 'USR_REV');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $codigo . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($codigo) . "'";
        $json .= ",'" . addslashes($fechapag) . "'";
        $json .= ",'" . addslashes($referencia) . "'";
        $json .= ",'RD$ " . addslashes($importe) . "'";
        $json .= ",'" . addslashes($motivoanula) . "'";
        $json .= ",'" . addslashes($fechaanula) . "'";
        $json .= ",'" . addslashes($usuarioanula) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'flexyFactPag') {

    include './../../clases/class.pago.php';
    $pago = $_REQUEST['pag'];
//$fecini = '2015-08-06';
    //$fecfin = '2015-08-06';

    function countRec($fname, $tname, $where, $sort)
    {
        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "PERIODO";
    }

    if (!$sortorder) {
        $sortorder = "desc";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $fname = "PERIODO";
    $tname = "SGC_TT_PAGO_FACTURAS";
    $where = " AND PF.ID_PAGO='$pago'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = "  AND PF.ID_PAGO='$pago' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new Pago();
    $registros = $l->getPagAplFacByPagFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero     = oci_result($registros, 'RNUM');
        $periodo    = oci_result($registros, 'PERIODO');
        $factura    = oci_result($registros, 'CONSEC_FACTURA');
        $totfac     = oci_result($registros, 'TOTAL');
        $importeapl = oci_result($registros, 'IMPORTE');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $periodo . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($periodo) . "'";
        $json .= ",'" . addslashes($factura) . "'";
        $json .= ",'" . addslashes($totfac) . "'";
        $json .= ",'" . addslashes($importeapl) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'flexyDifPag') {

    include './../../clases/class.pago.php';
    $pago = $_REQUEST['pag'];

    function countRec($fname, $tname, $where, $sort)
    {
        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "FECHA_PAGO";
    }

    if (!$sortorder) {
        $sortorder = "desc";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $fname = "FECHA_PAGO";
    $tname = "SGC_TT_PAGO_DETALLEFAC";
    $where = "  AND P.ID_PAGO='$pago'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = "  AND P.ID_PAGO='$pago' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new Pago();
    $registros = $l->getDifAplFacByPagFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero     = oci_result($registros, 'RNUM');
        $cdf        = oci_result($registros, 'CONCEPTO_DIF');
        $diferido   = oci_result($registros, 'CODIGO');
        $fecha      = oci_result($registros, 'FECHA_PAGO');
        $importeapl = oci_result($registros, 'PAGADO');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $numero . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($cdf) . "'";
        $json .= ",'" . addslashes($diferido) . "'";
        $json .= ",'" . addslashes($fecha) . "'";
        $json .= ",'" . addslashes($importeapl) . "'";
        $json .= "]}";
        $rc = true;
    }

    $json .= "]\n";
    $json .= "}";
    echo $json;
}
if ($tipo == 'flexyOtrosRec') {

    include '../../clases/class.otroRecaudo.php';
    $inmueble = $_REQUEST['inm'];

    function countRec($fname, $tname, $where, $sort)
    {
        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "ORE.FECHA";
    }

    if (!$sortorder) {
        $sortorder = "desc";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH

    $where = " AND ORE.INMUEBLE='$inmueble'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = "  AND ORE.INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new OtroRecaudo();
    $registros = $l->getOtrosRecByInmFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero    = oci_result($registros, 'RNUM');
        $codigo    = oci_result($registros, 'CODIGO');
        $fechaDig  = oci_result($registros, 'FECHADIG');
        $fechaPag  = oci_result($registros, 'FECHAPAG');
        $concepto  = oci_result($registros, 'DESC_SERVICIO');
        $importe   = oci_result($registros, 'IMPORTE');
        $usring    = oci_result($registros, 'LOGIN');
        $fechaAnul = oci_result($registros, 'FECHA_REV');
        $usrAnul   = oci_result($registros, 'USRREV');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $codigo . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($codigo) . "'";
        $json .= ",'" . addslashes($fechaDig) . "'";
        $json .= ",'" . addslashes($fechaPag) . "'";
        $json .= ",'" . addslashes($concepto) . "'";
        $json .= ",'RD$ " . addslashes($importe) . "'";
        $json .= ",'" . addslashes($usring) . "'";
        $json .= ",'" . addslashes($fechaAnul) . "'";
        $json .= ",'" . addslashes($usrAnul) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'flexyAplOreFac') {

    include '../../clases/class.otroRecaudo.php';
    $otroRec = $_REQUEST['ore'];

    function countRec($fname, $tname, $where, $sort)
    {
        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "F.PERIODO";
    }

    if (!$sortorder) {
        $sortorder = "desc";
    }

    $sort = "ORDER BY $sortname $sortorder ";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH

    $where = " AND AORE.ID_OTROREC='$otroRec'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = "  AND AORE.ID_OTROREC='$otroRec' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new OtroRecaudo();
    $registros = $l->getAplOreByOreFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $periodo = oci_result($registros, 'PERIODO');
        $factura = oci_result($registros, 'FACTURA');
        $importe = oci_result($registros, 'IMPORTE');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $periodo . "',";
        $json .= "cell:['" . $periodo . "'";
        $json .= ",'" . addslashes($factura) . "'";
        $json .= ",'" . addslashes($importe) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == "tipPago") {
    include './../../clases/class.pago.php';
    $pago  = $_REQUEST["pag"];
    $p     = new Pago();
    $datos = $p->getForPagByPag($pago);
    $i     = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $fpagos[$i] = $row;
        $i++;
    }
    echo json_encode($fpagos);
}

if ($tipo == "ubPago") {
    include './../../clases/class.pago.php';
    $pago  = $_REQUEST["pag"];
    $p     = new Pago();
    $datos = $p->getUbPagByPag($pago);
    $i     = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $fpagos[$i] = $row;
        $i++;
    }
    echo json_encode($fpagos);
}

if ($tipo == "tipForRec") {
    include '../../clases/class.otroRecaudo.php';
    $pago  = $_REQUEST["ore"];
    $p     = new OtroRecaudo();
    $datos = $p->getForOtrRecByOre($pago);
    $i     = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $fpagos[$i] = $row;
        $i++;
    }
    echo json_encode($fpagos);
}

if ($tipo == "ubiRec") {
    include '../../clases/class.otroRecaudo.php';
    $pago  = $_REQUEST["ore"];
    $p     = new OtroRecaudo();
    $datos = $p->getUbiOtrRecByOre($pago);
    $i     = 0;
    while ($row = oci_fetch_array($datos, OCI_ASSOC + OCI_RETURN_NULLS)) {
        $fpagos[$i] = $row;
        $i++;
    }
    echo json_encode($fpagos);
}

if ($tipo == "flexyCor") {
    include '../../clases/class.corte.php';
    $inmueble = $_REQUEST['inm'];
    //$inmueble=203050;
    //$fecini = '2015-08-06';
    //$fecfin = '2015-08-06';

    function countRec($fname, $tname, $where, $sort)
    {
        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "ORDEN";
    }

    if (!$sortorder) {
        $sortorder = "desc";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH

    $where = " AND  RC.ID_INMUEBLE='$inmueble'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = "  AND  RC.ID_INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new Corte();
    $registros = $l->getCortByInmFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero      = oci_result($registros, 'RNUM');
        $codigo      = oci_result($registros, 'ORDEN');
        $fechpla     = oci_result($registros, 'FECHA_PLANIFICACION');
        $fecRea      = oci_result($registros, 'FECHA_EJE');
        $descripcion = oci_result($registros, 'DESCRIPCION');
        $tipo        = oci_result($registros, 'TIPO_CORTE');
        $usr         = oci_result($registros, 'LOGIN');
        $reversado   = oci_result($registros, 'REVERSADO');
        $impoCort    = oci_result($registros, 'IMPO_CORTE');
        $usurev      = oci_result($registros, 'USU_REV');
        $fecrev      = oci_result($registros, 'FECHA_REVERSION');
        $obs         = oci_result($registros, 'OBS');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $codigo . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($codigo) . "'";
        $json .= ",'" . addslashes($fechpla) . "'";
        $json .= ",'" . addslashes($fecRea) . "'";
        $json .= ",'" . addslashes($descripcion) . "'";
        $json .= ",'" . addslashes($tipo) . "'";
        $json .= ",'" . addslashes($usr) . "'";
        $json .= ",'" . addslashes($impoCort) . "'";
        $json .= ",'" . addslashes($usurev) . "'";
        $json .= ",'" . addslashes($fecrev) . "'";
        $json .= ",'" . addslashes($obs) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}
if ($tipo == "flexRec") {

    include '../../clases/class.reconexion.php';
    $inmueble = $_REQUEST['inm'];
//$inmueble=203050;
    //$fecini = '2015-08-06';
    //$fecfin = '2015-08-06';

    function countRec($fname, $tname, $where, $sort)
    {
        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "FECHA_EJE";
    }

    if (!$sortorder) {
        $sortorder = "desc";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH

    $where = " AND RR.ID_INMUEBLE='$inmueble'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = "  AND RR.ID_INMUEBLE='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new Reconexion();
    $registros = $l->getRecByInmFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero   = oci_result($registros, 'RNUM');
        $fechapla = oci_result($registros, 'FECHA_PLANIFICACION');
        $fecharea = oci_result($registros, 'FECHA_EJE');
        $tipo     = oci_result($registros, 'TIPO_RECONEXION');
        $obs      = oci_result($registros, 'OBS_GENERALES');
        $fechaac  = oci_result($registros, 'FECHA_ACUERDO');
        $usueje   = oci_result($registros, 'LOGIN');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $numero . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($fechapla) . "'";
        $json .= ",'" . addslashes($fecharea) . "'";
        $json .= ",'" . addslashes($tipo) . "'";
        $json .= ",'" . addslashes($obs) . "'";
        $json .= ",'" . addslashes($fechaac) . "'";
        $json .= ",'" . addslashes($usueje) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;

}

if ($tipo == "flexObs") {

    include '../../clases/class.observacion.php';
    $inmueble = $_REQUEST['inm'];

    function countRec($fname, $tname, $where, $sort)
    {
        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "OI.FECHA";
    }

    if (!$sortorder) {
        $sortorder = "desc";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH

    $where = " AND INM_CODIGO='$inmueble'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = "  AND INM_CODIGO='$inmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new Observacion();
    $registros = $l->getObsByInmFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero      = oci_result($registros, 'RNUM');
        $codigo      = oci_result($registros, 'CODIGO_OBS');
        $asunto      = oci_result($registros, 'ASUNTO');
        $descripcion = oci_result($registros, 'DESCRIPCION');
        $fecha       = oci_result($registros, 'FECHA');
        $usuario     = oci_result($registros, 'LOGIN');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $codigo . "',";
        $json .= "title:'" . str_replace('\'', '', $descripcion) . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($codigo) . "'";
        $json .= ",'" . addslashes($asunto) . "'";
        $json .= ",'" . addslashes($descripcion) . "'";
        $json .= ",'" . addslashes($fecha) . "'";
        $json .= ",'" . addslashes($usuario) . "'";

        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;

}

if ($tipo == "flexDeudCero") {
    /**
     * Created by PhpStorm.
     * User: PC
     * Date: 10/20/2015
     * Time: 12:06 PM
     */
    include '../../clases/class.deudaCero.php';
    $codinmueble = $_REQUEST['inm'];
//$fecini = '2015-08-06';
    //$fecfin = '2015-08-06';

    function countRec($fname, $tname, $where, $sort)
    {
        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "DC.PERIODO_INI";
    }

    if (!$sortorder) {
        $sortorder = "DESC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH
    $fname = "DC.PERIODO_INI";
    $tname = "SGC_TT_DEUDA_CERO";
    $where = " AND DC.COD_INMUEBLE='$codinmueble'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = " AND DC.COD_INMUEBLE='$codinmueble' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new DeudaCero();
    $registros = $l->getDeudaCerByInmFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;

    while (oci_fetch($registros)) {
        $numero       = oci_result($registros, 'RNUM');
        $id_deudacero = oci_result($registros, 'ID_DEUDA_CERO');
        $perini       = oci_result($registros, 'PERIODO_INI');
        $totcuotas    = oci_result($registros, 'TOTAL_CUOTAS');
        $fecultpago   = oci_result($registros, 'FECH_ULTPAGO');
        $activo       = oci_result($registros, 'ACTIVA');
        $cuotas_pag   = oci_result($registros, 'TOTAL_CUOTAS_PAG');
        $totdif       = oci_result($registros, 'TOTAL_DIFERIDO');
        $totmora      = oci_result($registros, 'TOTAL_MORA');
        $nomcli       = oci_result($registros, 'NOMBRE_CLI');
        $perrev       = oci_result($registros, 'PERREV');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $id_deudacero . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($id_deudacero) . "'";
        $json .= ",'" . addslashes($perini) . "'";
        $json .= ",'" . addslashes($totcuotas) . "'";
        $json .= ",'" . addslashes($fecultpago) . "'";
        $json .= ",'" . addslashes($activo) . "'";
        $json .= ",'" . addslashes($cuotas_pag) . "'";
        $json .= ",'" . addslashes($totdif) . "'";
        $json .= ",'" . addslashes($totmora) . "'";
        $json .= ",'" . addslashes($nomcli) . "'";
        $json .= ",'" . addslashes($perrev) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;

}

if ($tipo == "flexiSaldos") {
    include '../../clases/class.saldoFavor.php';
    $codinmueble = $_REQUEST['inm'];
    $page        = $_REQUEST['page'];
    $rp          = $_REQUEST['rp'];
    $sortname    = $_REQUEST['sortname'];
    $sortorder   = $_REQUEST['sortorder'];
    $query       = $_REQUEST['query'];
    $qtype       = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "CODIGO";
    }

    if (!$sortorder) {
        $sortorder = "DESC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 100;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH

    $l         = new SaldoFavor();
    $registros = $l->getSaldFavByInmFlexy($codinmueble, $sort, $start, $end);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
//$json .= "total: 1,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $numero = oci_result($registros, 'RNUM');
        $cod_sf = oci_result($registros, 'CODIGO');
        $fec_sf = oci_result($registros, 'FECHA');
        $mot_sf = oci_result($registros, 'MOTIVO');
        $imp_sf = oci_result($registros, 'IMPORTE');
        $apl_sf = oci_result($registros, 'VALOR_APLICADO');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $cod_sf . "',";
        $json .= "cell:['" . $numero . "'";
        $json .= ",'" . addslashes($cod_sf) . "'";
        $json .= ",'" . addslashes($fec_sf) . "'";
        $json .= ",'" . addslashes($imp_sf) . "'";
        $json .= ",'" . addslashes($apl_sf) . "'";
        $json .= ",'" . addslashes($imp_sf - $apl_sf) . "'";
        $json .= ",'" . addslashes($mot_sf) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;

}

if ($tipo == 'flexyAplSaldos') {

    include '../../clases/class.saldoFavor.php';
    $saldo = $_REQUEST['sal'];

    function countRec($fname, $tname, $where, $sort)
    {
        return 100;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "F.PERIODO";
    }

    if (!$sortorder) {
        $sortorder = "desc";
    }

    $sort = "ORDER BY $sortname $sortorder ";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH

    $where = " AND DS.COD_SALDO='$saldo'";
//$fname = "USERNME";
    //$tname = "SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CLIENTES C, SGC_TP_ACTIVIDADES A, SGC_TT_ASIGNACION S";

    if ($query) {
        $where = "  AND DS.COD_SALDO='$saldo' AND UPPER($qtype) LIKE UPPER('$query%') ";
    }
    $l         = new SaldoFavor();
    $registros = $l->getAplSalBySalFlexy($where, $sort, $start, $end);
    $total     = countRec($fname, $tname, $where, $sort);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc = false;
    while (oci_fetch($registros)) {
        $periodo = oci_result($registros, 'PERIODO');
        $factura = oci_result($registros, 'CONSEC_FACTURA');
        $importe = oci_result($registros, 'IMPORTE');

        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $periodo . "',";
        $json .= "cell:['" . $periodo . "'";
        $json .= ",'" . addslashes($factura) . "'";
        $json .= ",'" . addslashes($importe) . "'";
        $json .= "]}";
        $rc = true;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

if ($tipo == 'flexyReclamos') {

    include '../../clases/class.pqr.php';
    $codinmueble = $_REQUEST['inm'];

    function countRec($codinmueble)
    {
        return 1000;
//        $l=new PQRs();
        //        $valores=$l->reclamosAnteriores ($codinmueble);
        //        while (oci_fetch($valores)) {
        //            $cantidad = oci_result($valores, 'CANTREC');
        //
        //        }oci_free_statement($valores);
        //        return $cantidad;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "P.CODIGO_PQR";
    }

    if (!$sortorder) {
        $sortorder = "DESC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH

    $l         = new Pqr();
    $registros = $l->getPqrByInmFlexy($codinmueble, $sort);
    $total     = countRec($codinmueble);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc     = false;
    $numero = 1;
    while (oci_fetch($registros)) {
        //$numero=oci_result($registros, 'CODIGO_PQR');
        $cod_pqr = oci_result($registros, 'CODIGO_PQR');
        $fec_pqr = oci_result($registros, 'FECHA_PQR');
        $tip_pqr = oci_result($registros, 'DESC_TIPO_RECLAMO');
        $mot_pqr = oci_result($registros, 'DESC_MOTIVO_REC');
        $est_pqr = oci_result($registros, 'CERRADO');
        $dia_pqr = oci_result($registros, 'DIAGNOSTICO');
        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $cod_pqr . "',";
        $json .= "cell:['<b>" . $numero . "</b>'";
        $json .= ",'" . addslashes($cod_pqr) . "'";
        $json .= ",'" . addslashes($fec_pqr) . "'";
        $json .= ",'" . addslashes($tip_pqr) . "'";
        $json .= ",'" . addslashes($mot_pqr) . "'";
        $json .= ",'" . addslashes($est_pqr) . "'";
        $json .= ",'" . addslashes($dia_pqr) . "'";
        $json .= ",'" . "<b><a href=\"JAVASCRIPT:reclamoPdf(" . $cod_pqr . ");\">" . "<img src=\"../../images/doc_pdf.png\" width=\"20\" height=\"20\"/>" . " </a></b>" . "'";
        $json .= "]}";
        $rc = true;
        $numero++;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}


if ($tipo == 'flexiDocumentos') {

    include '../../clases/class.pqr.php';
    $codinmueble = $_REQUEST['inm'];

    function countRec($codinmueble)
    {
        return 1000;
//        $l=new PQRs();
        //        $valores=$l->reclamosAnteriores ($codinmueble);
        //        while (oci_fetch($valores)) {
        //            $cantidad = oci_result($valores, 'CANTREC');
        //
        //        }oci_free_statement($valores);
        //        return $cantidad;
    }

    $page      = $_REQUEST['page'];
    $rp        = $_REQUEST['rp'];
    $sortname  = $_REQUEST['sortname'];
    $sortorder = $_REQUEST['sortorder'];
    $query     = $_REQUEST['query'];
    $qtype     = $_REQUEST['qtype'];

    if (!$sortname) {
        $sortname = "ID_REGISTRO";
    }

    if (!$sortorder) {
        $sortorder = "DESC";
    }

    $sort = "ORDER BY $sortname $sortorder";

    if (!$page) {
        $page = 1;
    }

    if (!$rp) {
        $rp = 1000;
    }

    $end   = ($page - 1) * $rp; // MAX_ROW_TO_FETCH
    $start = ($page) * $rp; // MIN_ROW_TO_FETCH

    $l         = new Pqr();
    $registros = $l->getDocByInmFlexy($codinmueble, $sort);
    $total     = countRec($codinmueble);
    $json      = "";
    $json .= "{\n";
    $json .= "page: $page,\n";
    $json .= "total: $total,\n";
    $json .= "rows: [";
    $rc     = false;
    $numero = 1;
    while (oci_fetch($registros)) {
        //$numero=oci_result($registros, 'CODIGO_PQR');
        $cod_reg = oci_result($registros, 'ID_REGISTRO');
        $cod_arc = oci_result($registros, 'CODIGO_ARCH');
        $tip_are = oci_result($registros, 'AREA');
        $tip_doc = oci_result($registros, 'TIPDOC');
        $fec_arc = oci_result($registros, 'FECHA');
        $obs_arc = oci_result($registros, 'OBSERVACION');
        $rut_arc = oci_result($registros, 'RUTA');
        if ($rc) {
            $json .= ",";
        }

        $json .= "\n{";
        $json .= "id:'" . $cod_pqr . "',";
        $json .= "cell:['<b>" . $numero . "</b>'";
        $json .= ",'" . addslashes($cod_reg) . "'";
        $json .= ",'" . addslashes($cod_arc) . "'";
        $json .= ",'" . addslashes($tip_are) . "'";
        $json .= ",'" . addslashes($tip_doc) . "'";
        $json .= ",'" . addslashes($fec_arc) . "'";
        $json .= ",'" . addslashes($obs_arc) . "'";
        $json .= ",'" . "<b><a target=\"_blank\" href=\"../../archivo". $rut_arc ." \" >" . "<img src=\"../../images/doc_pdf.png\" width=\"20\" height=\"20\"/>" . " </a></b>" . "'";
        $json .= "]}";
        $rc = true;
        $numero++;
    }
    $json .= "]\n";
    $json .= "}";
    echo $json;
}

