<?php
include_once 'class.conexion.php';

/**
 * Clase para gestionar los reportes.
 */
class Reports extends ConexionClass
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDepartment()
    {
        $sql = "SELECT M.ID_AREA, M.DESC_AREA
                    FROM SGC_TP_AREAS M
                    WHERE M.VISIBLE = 'S'
                    ORDER BY M.ID_AREA";

        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            $data = array();

            while (oci_fetch($result)) {
                $id   = oci_result($result, 'ID_AREA');
                $desc = oci_result($result, 'DESC_AREA');

                $data[$id] = $desc;
            }
            oci_close($this->_db);

            return $data;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getDataReport($dep, $fec_ini, $fec_fin)
    {
        $sql = "SELECT S.ID_SCMS,
                       U.NOM_USR || ' ' || U.APE_USR SOLICITADOR,
                       A.DESC_AREA DEPARTAMENTO,
                       TS.DESC_REQUERIMIENTO TIPO,
                       S.ESTADO,
                       DECODE(TS.PRIORIDAD_REQUERI,
                              'A',
                              'Alta',
                              'M',
                              'Media',
                              'B',
                              'Baja',
                              'En espera') PRIORIDAD,
                       TO_CHAR(S.FECHA_SOLICITUD, 'DD/MM/YYYY HH:MI:SS AM') FECHA
                  FROM SGC_TT_USUARIOS  U,
                       SGC_TT_SCMS      S,
                       SGC_TP_AREAS     A,
                       SGC_TP_TIPO_SCMS TS
                 WHERE S.FECHA_SOLICITUD BETWEEN TO_DATE('$fec_ini', 'YYYY-MM-DD') AND
                       TO_DATE('$fec_fin' || ' 23:59', 'YYYY-MM-DD HH24:MI')
                       AND S.DESARROLLADOR IS NOT NULL";
        if ($dep != 0) {
            $sql .= " AND S.ID_AREA = $dep";
        }

        $sql .= " AND S.SOLICITADO = U.ID_USUARIO
                   AND S.ID_AREA = A.ID_AREA
                   AND S.ID_TIPO_SCMS = TS.ID_CODIGO
                   AND S.VALIDA_CALIDAD = 'S'
                 ORDER BY S.FECHA_SOLICITUD DESC";

        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getDataReportPdf($id)
    {
        $sql = "SELECT S.ID_SCMS ID,
                     TO_CHAR(S.FECHA_SOLICITUD, 'DD/MM/YYYY HH:MI:HH AM') FECHA_SOLICITUD,
                     TO_CHAR(S.FECHA_COMPROMISO, 'DD/MM/YYYY HH:MI:HH AM') FECHA_COMPROMISO,
                     TO_CHAR(S.FECHA_CONCLUCION, 'DD/MM/YYYY HH:MI:HH AM') FECHA_CONCLUCION,
                     TO_CHAR(S.FECHA_CALIDAD, 'DD/MM/YYYY HH:MI:HH AM') FECHA_CALIDAD,
                     S.USR_CALIDAD,
                     INITCAP(LOWER(M.DESC_MENU)) MODULO,
                     INITCAP(LOWER(A.DESC_AREA)) DEPARTAMENTO,
                     PS.ID_CODIGO PRIORIDAD,
                     TS.ID_CODIGO REQUERIMIENTO,
                     S.DESCRIPCION,
                      (SELECT INITCAP(LOWER(U.NOM_USR || ' ' || U.APE_USR))
                        FROM SGC_TT_USUARIOS U
                       WHERE U.ID_USUARIO = S.SOLICITADO) SOLICITADOR,
                     (SELECT INITCAP(LOWER(U.NOM_USR || ' ' || U.APE_USR))
                        FROM SGC_TT_USUARIOS U
                       WHERE U.ID_USUARIO = S.DESARROLLADOR) DESARROLLADOR
                FROM SGC_TT_SCMS           S,
                     SGC_TP_MENUS          M,
                     SGC_TP_AREAS          A,
                     SGC_TP_PRIORIDAD_SCMS PS,
                     SGC_TP_TIPO_SCMS      TS
               WHERE S.ID_MODULO = M.ID_MENU
                 AND S.ID_AREA = A.ID_AREA
                 AND S.ID_TIPO_SCMS = TS.ID_CODIGO
                 AND TS.PRIORIDAD_REQUERI = PS.CODIGO_PRIORIDAD
                 AND S.ID_SCMS = $id";

        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            oci_close($this->_db);
            return oci_fetch_array($result);
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function compEdition($id, $fecha)
    {
        $sql = "SELECT VERFECSOLIC($id, '$fecha') STATUS FROM DUAL";

        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            oci_close($this->_db);
            return oci_fetch_array($result)['STATUS'];
        } else {
            oci_close($this->_db);
            return false;
        }
    }
}
