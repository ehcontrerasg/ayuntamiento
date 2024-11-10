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
            $e = oci_error($stid);
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

    public function getDocumento()
    {
        $sql = "SELECT
                TIP_DOCUMENTOS CODIGO,
                DESC_DOCUMENTO DESCRIPCION
            from
                sgc_tp_tip_documentos
            where
                VALIDA = 'S'";

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
