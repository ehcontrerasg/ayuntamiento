<?php
//include_once "../../clases/class.conexion.php";
require "../../clases/class.conexion.php";

class Documento extends ConexionClass
{

    public function setDoc($cod, $codArc, $dep, $doc, $pro, $fDoc, $usr, $arc, $obs)
    {

        $sql = " BEGIN
                sgc_pk_archivos.interaciones_archivos(pCODIGO_INM      => $cod,
                                                      pCODIGO_ARCH     => '$codArc',
                                                      pID_AREA         => $dep,
                                                      pTIP_DOCUMENTOS  => $doc,
                                                      pID_PROYECTO     => '$pro',
                                                      pFECHA_DOCUMENTO => '$fDoc',
                                                      pUSR_CREACION    => '$usr',
                                                      pOBSERVACION     => '$obs',
                                                      pRUTA_ARCHIVO    => '$arc'
                );
            END;";
        //echo $sql;
        //msj_error        =>

        $resultado = oci_parse($this->_db, $sql);
        if (!$resultado) {
            $e = oci_error($this->_db);
            //echo $e['message'];
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        /*// $fDoc = '16/03/2017';
        //TO_DATE('$fEsc','DD/MM/YYYY HH24:MI:SS')
        oci_bind_by_name($resultado, ":cod",     $cod);
        oci_bind_by_name($resultado, ":codArc",  $codArc);
        oci_bind_by_name($resultado, ":dep",     $dep);
        oci_bind_by_name($resultado, ":doc",     $doc);
        oci_bind_by_name($resultado, ":pro",     $pro);
        oci_bind_by_name($resultado, ":fDoc",    $fDoc);
        oci_bind_by_name($resultado, ":usr",     $usr);
        oci_bind_by_name($resultado, ":obs",     $obs);
        oci_bind_by_name($resultado, ":arc",     $arc);*/
        // oci_bind_by_name($resultado,":vMsj_error", $this->vMsj_error);
        //echo $fDoc;
        $bandera = oci_execute($resultado);
        if (!$bandera) {
            $e = oci_error($resultado);
            //echo $e['message'];
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            oci_close($this->_db);
            oci_free_statement($resultado);
        }
        /*oci_close($this->_db);
        oci_free_statement($resultado);*/
        if ($bandera) {
            return true; /*
        if($this->vMsj_error != null){
        return $this->vMsj_error;
        }else{
        return true;
        }*/
        } else {
            return $this->vMsj_error;
        }

    }

    public function setDocCont($claDoc,$tipDoc,$codDoc,$codSeg,$fecDoc,$ben,$ban,$emp,$fRep,$asu,$per,$codFac,$cuenta,$aproba,$arc, $usr)
    {

        $sql = " BEGIN SGC_P_ING_DOCUMENTOS_CONTABLES($claDoc,$tipDoc,'$codDoc','$codSeg','$fecDoc','$ben',$ban,$emp,'$fRep','$asu','$per','$codFac','$cuenta','$aproba','$arc','$usr',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msresult, 10000);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, 123);
        if (oci_execute($resultado)) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "error consulta lotecorte";
            return false;
        }
        /*if (!$resultado) {
            $e = oci_error($this->_db);
            echo $e['message'];
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $bandera = oci_execute($resultado);
        if (!$bandera) {
            $e = oci_error($this->_db);
            echo $e['message'];
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            oci_close($this->_db);
            oci_free_statement($resultado);
        }

        if ($bandera) {
            return true;
        } else {
            return $this->vMsj_error;
        }*/
    }

    public function modDoc($IdRegistro, $cod, $codArc, $dep, $doc, $pro, $fDoc, $usr, $arc, $obs)
    {

        $sql = "BEGIN
                    sgc_pk_archivos.add_archivo(pCODIGO_INM      => $cod,
                                                pCODIGO_ARCH     => '$codArc',
                                                pTIP_DOCUMENTOS  => $doc,
                                                pFECHA_DOCUMENTO => '$fDoc',
                                                pRUTA_ARCHIVO    => '$arc',
                                                pUSR_CREACION    => '$usr',
                                                pID_PROYECTO     => '$pro',
                                                pOBSERVACION     => '$obs',
                                                pMsgError        => :vMsgError);
                END;";

        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ':vMsgError', $this->vMsj_error);
        $bandera = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_free_statement($resultado);
            return true;
        } else {
            oci_close($this->_db);
            return $this->vMsj_error;
        }

    }

    public function infoObsCont($codDocumento)
    {
        $sql = "SELECT A.CODIGO_DOCUMENTO, TO_CHAR(A.FECHA_EMISION,'DD/MM/YYYY')FECHA_EMISION, A.BENEFICIARIO, B.DESCRIPCION BANCO, E.DESC_EMPRESA EMPRESA
        FROM SGC_TT_REGISTRO_ARCHIVO A, SGC_TP_BANCOS B, SGC_TP_EMPRESAS E
        WHERE A.BANCO = B.CODIGO
        AND A.EMPRESA = E.ID_EMPRESA 
        AND A.CODIGO_DOCUMENTO =  $codDocumento";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function infoObsComunicacion($codDocumento)
    {
        $sql = "SELECT A.CODIGO_DOCUMENTO, TO_CHAR(A.FECHA_EMISION,'DD/MM/YYYY')FECHA_EMISION, A.BENEFICIARIO, E.DESC_EMPRESA EMPRESA,
        TO_CHAR(A.FECHA_RECEPCION,'DD/MM/YYYY')FECHA_RECEPCION, ASUNTO
        FROM SGC_TT_REGISTRO_ARCHIVO A, SGC_TP_EMPRESAS E
        WHERE A.EMPRESA = E.ID_EMPRESA 
        AND A.CODIGO_DOCUMENTO =  $codDocumento";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function infoObsEntradas($codDocumento)
    {
        $sql = "SELECT A.CODIGO_DOCUMENTO, A.PERIODO
        FROM SGC_TT_REGISTRO_ARCHIVO A
        WHERE  A.CODIGO_DOCUMENTO =  $codDocumento";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function infoObsFacturas($codDocumento)
    {
        $sql = "SELECT A.CODIGO_DOCUMENTO, TO_CHAR(A.FECHA_EMISION,'DD/MM/YYYY')FECHA_EMISION, A.BENEFICIARIO, E.DESC_EMPRESA EMPRESA
        FROM SGC_TT_REGISTRO_ARCHIVO A, SGC_TP_EMPRESAS E
        WHERE A.EMPRESA = E.ID_EMPRESA 
        AND A.CODIGO_DOCUMENTO =  $codDocumento";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }


    public function infoObsNotas($codDocumento)
    {
        $sql = "SELECT A.CODIGO_DOCUMENTO, TO_CHAR(A.FECHA_EMISION,'DD/MM/YYYY')FECHA_EMISION, E.DESC_EMPRESA EMPRESA, A.CODIGO_FACTURA
        FROM SGC_TT_REGISTRO_ARCHIVO A, SGC_TP_EMPRESAS E
        WHERE A.EMPRESA = E.ID_EMPRESA 
        AND A.CODIGO_DOCUMENTO =  $codDocumento";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function infoObsPagos($codDocumento)
    {
        $sql = "SELECT A.CODIGO_DOCUMENTO, TO_CHAR(A.FECHA_EMISION,'DD/MM/YYYY')FECHA_EMISION, A.BENEFICIARIO, B.DESCRIPCION BANCO, E.DESC_EMPRESA EMPRESA,
        A.CUENTA, A.NUM_APROBACION
        FROM SGC_TT_REGISTRO_ARCHIVO A, SGC_TP_BANCOS B, SGC_TP_EMPRESAS E
        WHERE A.BANCO = B.CODIGO
        AND A.EMPRESA = E.ID_EMPRESA 
        AND A.CODIGO_DOCUMENTO =   $codDocumento";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }



    public function infoObs($codSistema)
    {
        $sql = "SELECT
                FECHA_CREACION, USR_CREACION, OBSERVACION
                from
                    sgc_tt_registro
                where
                    codigo_inm = $codSistema";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function buscarPDF($codigo_inm)
    {
        $sql = "SELECT t.id_registro,
                   t.codigo_inm,
                   t.codigo_arch,
                   t.id_area,
                   t.tip_documentos,
                   t.id_proyecto,
                   t.fecha_documento,
                   t.fecha_creacion,
                   t.usr_creacion,
                   t.observacion,
                   t.ruta_archivo,
                   t.valida,
                   (select d.desc_documento from sgc_tp_tip_documentos d where d.tip_documentos = t.tip_documentos) desc_documento
              FROM sgc_tt_registro t
             WHERE t.codigo_inm = $codigo_inm AND t.VALIDA = 'S'";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }


    public function buscarPDFCont($codigo_doc)
    {
        $sql = "SELECT RA.CODIGO_DOCUMENTO, I.DESC_TIPO_INGRESO, T.DESC_DOCUMENTO, RA.RUTA_DOCUMENTO
                FROM SGC_TT_REGISTRO_ARCHIVO RA, SGC_TP_TIPO_INGRESOS_DOC I, SGC_TP_TIP_DOCUMENTOS T
                WHERE I.ID_TIPO_INGRESO = RA.TIPO_INGRESO 
                AND T.TIP_DOCUMENTOS = RA.TIPO_DOCUMENTO
                AND RA.CODIGO_DOCUMENTO = $codigo_doc";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getDocumento()
    {
        $sql = "SELECT
                TIP_DOCUMENTOS CODIGO,
                DESC_DOCUMENTO DESCRIPCION
            from
                sgc_tp_tip_documentos
            where
                VALIDA = 'S'
                AND CONTABLE = 'N'";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }

    }

    public function getDocumentoCont($clase)
    {
        $sql = "SELECT
                TIP_DOCUMENTOS CODIGO,
                DESC_DOCUMENTO DESCRIPCION
            from
                sgc_tp_tip_documentos
            where
                VALIDA = 'S'
                AND CONTABLE = 'S'
                AND TIPO = $clase";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }

    }

    public function getBancos()
    {
        $sql = "SELECT
                CODIGO,
                DESCRIPCION
            FROM
                SGC_TP_BANCOS";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }

    }

    public function getEmpresas()
    {
        $sql = "SELECT
                ID_EMPRESA CODIGO,
                DESC_EMPRESA DESCRIPCION
            FROM
                SGC_TP_EMPRESAS";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }

    }

    public function getProyecto()
    {
        $sql = "SELECT
                ID_PROYECTO CODIGO,
                DESC_PROYECTO DESCRIPCION
            from
                SGC_TP_PROYECTOS";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }

    }

    public function getProByINM($codigo_inm)
    {
        $sql = "SELECT
                ID_PROYECTO
            from
                sgc_tp_Registro WHERE CODIGO_INM = $codigo_inm";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            //  oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }

    }

    public function getArchivos($codigo_inm)
    {
        //$codigo_inm = trim($codigo_inm);

        // settype($codigo_inm, 'string');

        $sql = "SELECT r.CODIGO_INM,
                       r.CODIGO_ARCH,
                       r.ID_PROYECTO
                 FROM  sgc_tp_Registro r";

        // Preparar la sentencia --to_char(r.FECHA_DOCUMENTO,'dd-mm-yyyy') FECHA_DOCUMENTO,
        $parse = oci_parse($this->_db, $sql);
        if (!$parse) {
            $e = oci_error($this->_db);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        // Realizar la lógica de la consulta
        $r = oci_execute($parse);
        if (!$r) {
            $e = oci_error($parse);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        //$result = oci_fetch_array($parse);
        oci_fetch_all($parse, $datos);
        //var_dump($datos);

        $row    = count($datos['CODIGO_INM']);
        $result = '{"data":[';

        for ($i = 0; $i < $row; $i++) {
            $result .= "{";
            $result .= '"id"       : "' . $i . '"';
            //$result .= ' "ID_REGISTRO"      : "'.$datos['ID_REGISTRO'][$i].'"';
            $result .= ',"CODIGO_INM"       : "' . $datos['CODIGO_INM'][$i] . '"';
            $result .= ',"CODIGO_ARCH"      : "' . $datos['CODIGO_ARCH'][$i] . '"';
            // $result .= ',"DESC_AREA"        : "'.$datos['DESC_AREA'][$i].'"';
            // $result .= ',"DESC_DOCUMENTO"   : "'.$datos['DESC_DOCUMENTO'][$i].'"';
            $result .= ',"ID_PROYECTO"      : "' . $datos['ID_PROYECTO'][$i] . '"';
            //$result .= ',"FECHA_DOCUMENTO"  : "'.$datos['FECHA_DOCUMENTO'][$i].'"';
            // $result .= ',"OBSERVACION"      : "'.$datos['OBSERVACION'][$i].'"';
            // $result .= ',"RUTA_ARCHIVO"     : "'.$datos['RUTA_ARCHIVO'][$i].'"';
            if ($i != $row - 1) {
                $result .= '},';
            } else {
                $result .= '}';
            }
            //
        }
        $result .= ']}';

        oci_close($this->_db);
        oci_free_statement($parse);
        return $result;
    }

    public function getArchivosContables()
    {
        $sql = "SELECT D.DESC_TIPO_INGRESO,
                       T.DESC_DOCUMENTO,
                       R.CODIGO_DOCUMENTO,
                       R.COD_SEGMENTO,
                       TO_CHAR(R.FECHA_CARGUE_DOC,'DD/MM/YYYY')FECHA_CARGUE,
                       U.NOM_USR||' '||U.APE_USR USUARIO_CARGUE,
                       T.BOTON_INFO BOTONES
                 FROM  SGC_TT_REGISTRO_ARCHIVO R, SGC_TP_TIPO_INGRESOS_DOC D, SGC_TP_TIP_DOCUMENTOS T, SGC_TT_USUARIOS U
                 WHERE D.ID_TIPO_INGRESO = R.TIPO_INGRESO
                 AND U.ID_USUARIO = USUARIO_CARGUE
                 AND T.TIP_DOCUMENTOS = R.TIPO_DOCUMENTO";

        // Preparar la sentencia -
        $parse = oci_parse($this->_db, $sql);
        if (!$parse) {
            $e = oci_error($this->_db);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        // Realizar la lógica de la consulta
        $r = oci_execute($parse);
        if (!$r) {
            $e = oci_error($parse);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        oci_fetch_all($parse, $datos);


        $row    = count($datos['CODIGO_DOCUMENTO']);
        $result = '{"data":[';

        for ($i = 0; $i < $row; $i++) {
            $result .= "{";
            $result .= '"id"       : "' . $i . '"';
            $result .= ',"DESC_TIPO_INGRESO"   : "' . $datos['DESC_TIPO_INGRESO'][$i] . '"';
            $result .= ',"DESC_DOCUMENTO"      : "' . $datos['DESC_DOCUMENTO'][$i] . '"';
            $result .= ',"CODIGO_DOCUMENTO"    : "' . $datos['CODIGO_DOCUMENTO'][$i] . '"';
            $result .= ',"COD_SEGMENTO"        : "' . $datos['COD_SEGMENTO'][$i] . '"';
            $result .= ',"FECHA_CARGUE"        : "' . $datos['FECHA_CARGUE'][$i] . '"';
            $result .= ',"USUARIO_CARGUE"      : "' . $datos['USUARIO_CARGUE'][$i] . '"';
            $result .= ',"BOTONES"             : "' . $datos['BOTONES'][$i] . '"';
            if ($i != $row - 1) {
                $result .= '},';
            } else {
                $result .= '}';
            }
            //
        }
        $result .= ']}';

        oci_close($this->_db);
        oci_free_statement($parse);
        return $result;
    }

    public function existReg($codigo_inm)
    {
        $sql = "SELECT count(id_registro) existe
              FROM sgc_tt_registro
              WHERE codigo_inm = $codigo_inm
                AND VALIDA = 'S'";
        $resultado = oci_parse($this->_db, $sql);
        /* if (!$resultado) {
        $e = oci_error($this->_db);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }*/
        $bandera = oci_execute($resultado);
        /*if (!$bandera) {
        $e = oci_error($this->_db);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }*/
        oci_close($this->_db);
        //oci_free_statement($resultado);
        if ($bandera) {
            return $resultado;
        } else {
            return false;
        }
    }

    public function getArchivosConsulta($codigo_inm, $sort)
    {
        $sql = "SELECT ID_REGISTRO, CODIGO_ARCH, A.DESC_AREA, TD.DESC_DOCUMENTO, TO_CHAR(FECHA_DOCUMENTO,'DD/MM/YYYY') FECHA_DOC, RUTA_ARCHIVO
        FROM SGC_TT_REGISTRO R, SGC_TP_TIP_DOCUMENTOS TD, SGC_TP_AREAS A
        WHERE TD.TIP_DOCUMENTOS = R.TIP_DOCUMENTOS
        AND A.ID_AREA = R.ID_AREA
        AND R.CODIGO_INM = '$codigo_inm'
        $sort";
        //echo $sql;

        // Preparar la sentencia -
        $parse = oci_parse($this->_db, $sql);
        if (!$parse) {
            $e = oci_error($this->_db);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        // Realizar la lógica de la consulta
        $r = oci_execute($parse);
        if (!$r) {
            $e = oci_error($parse);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        oci_fetch_all($parse, $datos);


        $row    = count($datos['ID_REGISTRO']);
        $result = '{"data":[';

        for ($i = 0; $i < $row; $i++) {
            $result .= "{";
            $result .= '"id"       : "' . $i . '"';
            $result .= ',"ID_REGISTRO"   : "' . $datos['ID_REGISTRO'][$i] . '"';
            $result .= ',"CODIGO_ARCH"   : "' . $datos['CODIGO_ARCH'][$i] . '"';
            $result .= ',"DESC_AREA"      : "' . $datos['DESC_AREA'][$i] . '"';
            $result .= ',"DESC_DOCUMENTO"    : "' . $datos['DESC_DOCUMENTO'][$i] . '"';
            $result .= ',"FECHA_DOC"        : "' . $datos['FECHA_DOC'][$i] . '"';
            if ($i != $row - 1) {
                $result .= '},';
            } else {
                $result .= '}';
            }
            //
        }
        $result .= ']}';

        oci_close($this->_db);
        oci_free_statement($parse);
        return $result;
    }


    /*public function existArch($codigo_inm){
$sql = "SELECT t.id_registro,
t.codigo_inm,
t.codigo_arch,
t.id_area,
t.tip_documentos,
t.id_proyecto,
t.fecha_documento,
t.fecha_creacion,
t.usr_creacion,
t.observacion,
t.ruta_archivo,
t.valida,
(select d.desc_documento
from sgc_tp_tip_documentos d
where d.tip_documentos = t.tip_documentos) desc_documento
FROM sgc_tt_registro t
WHERE VALIDA = 'S'
AND t.fecha_documento between to_date('$fecha1', 'dd/mm/YYYY') and to_date('$fecha2', 'dd/mm/YYYY')";

$resultado= oci_parse($this->_db,$sql);
if (!$resultado) {
$e = oci_error($this->_db);
trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$bandera=oci_execute($resultado);
if (!$bandera) {
$e = oci_error($stid);
trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
oci_close($this->_db);
oci_free_statement($resultado);
if($bandera){
return true;
}else{
return false;
}
}
/*
public function reportMensual($fecha1, $fecha2){
$sql = "SELECT t.id_registro,
t.codigo_inm,
t.codigo_arch,
t.id_area,
t.tip_documentos,
t.id_proyecto,
t.fecha_documento,
t.fecha_creacion,
t.usr_creacion,
t.observacion,
t.ruta_archivo,
t.valida,
(select d.desc_documento
from sgc_tp_tip_documentos d
where d.tip_documentos = t.tip_documentos) desc_documento
FROM sgc_tt_registro t
WHERE VALIDA = 'S'
AND t.fecha_documento between to_date('$fecha1', 'dd/mm/YYYY') and to_date('$fecha2', 'dd/mm/YYYY')";

$resultado= oci_parse($this->_db,$sql);
if (!$resultado) {
$e = oci_error($this->_db);
trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$bandera=oci_execute($resultado);
if (!$bandera) {
$e = oci_error($stid);
trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
oci_close($this->_db);
oci_free_statement($resultado);
if($bandera){
return true;
}else{
return false;
}
}*/
}
