<?php
include_once "class.conexion.php";

class Tarifa extends ConexionClass
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getTarifaByUsoPro($proyecto, $uso)
    {
        $proyecto = addslashes($proyecto);
        $uso      = addslashes($uso);
        $sql      = "SELECT
                TAR.CONSEC_TARIFA CODIGO,
                TAR.DESC_TARIFA DESCRIPCION
              FROM
                SGC_TP_TARIFAS TAR
              WHERE
                TAR.COD_USO='$uso' AND
                TAR.COD_PROYECTO='$proyecto'";

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
    public function getServUsoByProyecto($proyecto)
    {
        $proyecto = addslashes($proyecto);

        $sql = "SELECT
                SER.DESC_SERVICIO,
                U.DESC_USO,
                SER.COD_SERVICIO,
                T.COD_USO
              FROM
                SGC_TP_TARIFAS T,
                SGC_TP_SERVICIOS SER,
                SGC_TP_USOS U
              WHERE
                T.COD_PROYECTO='$proyecto' AND
                SER.COD_SERVICIO = T.COD_SERVICIO AND
                U.ID_USO=T.COD_USO
              GROUP BY
                SER.DESC_SERVICIO,
                U.DESC_USO,
                T.COD_SERVICIO,
                U.DESC_USO,
                SER.COD_SERVICIO,
                T.COD_USO
              ORDER BY
                T.COD_SERVICIO ASC,
                U.DESC_USO ASC";

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

    public function getTarByProUsoSer($proyecto,$uso,$serv)
    {
        $proyecto = addslashes($proyecto);
        $uso = addslashes($uso);
        $serv = addslashes($serv);

        $sql = "SELECT
                t.CONSEC_TARIFA CODIGO,
                t.DESC_TARIFA DESCRIPCION
              FROM
                SGC_TP_TARIFAS T
              WHERE
                T.COD_PROYECTO='$proyecto' and 
                t.COD_USO='$uso' and 
                T.COD_SERVICIO='$serv' AND 
                T.VISIBLE='S'
                
                ";

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


    public function getValRangosByPerProServUso($periodo, $proyecto, $Serv, $uso)
    {
        $periodo  = addslashes($periodo);
        $proyecto = addslashes($proyecto);
        $Serv     = addslashes($Serv);
        $uso      = addslashes($uso);

        $sql = "SELECT
                T.CODIGO_TARIFA,
                T.DESC_TARIFA,
                (
                 SELECT RT.VALOR_METRO
                      FROM SGC_TP_RANGOS_TARIFAS RT
                     WHERE
                     RT.RANGO        =0
                       AND RT.CONSEC_TARIFA = T.CONSEC_TARIFA
                       AND RT.PERIODO       = (SELECT MAX(T2.PERIODO)
                                                 FROM SGC_TP_RANGOS_TARIFAS T2
                                                WHERE T2.PERIODO      <= $periodo
                                                  AND T2.CONSEC_TARIFA = RT.CONSEC_TARIFA)
                ) precio1,
                (
                 SELECT RT.VALOR_METRO
                      FROM SGC_TP_RANGOS_TARIFAS RT
                     WHERE
                     RT.RANGO        =1
                       AND RT.CONSEC_TARIFA = T.CONSEC_TARIFA
                       AND RT.PERIODO       = (SELECT MAX(T2.PERIODO)
                                                 FROM SGC_TP_RANGOS_TARIFAS T2
                                                WHERE T2.PERIODO      <= $periodo
                                                  AND T2.CONSEC_TARIFA = RT.CONSEC_TARIFA)
                ) precio2,
                (
                 SELECT RT.VALOR_METRO
                      FROM SGC_TP_RANGOS_TARIFAS RT
                     WHERE
                     RT.RANGO        =2
                       AND RT.CONSEC_TARIFA = T.CONSEC_TARIFA
                       AND RT.PERIODO       = (SELECT MAX(T2.PERIODO)
                                                 FROM SGC_TP_RANGOS_TARIFAS T2
                                                WHERE T2.PERIODO      <= $periodo
                                                  AND T2.CONSEC_TARIFA = RT.CONSEC_TARIFA)
                ) precio3,
                (
                 SELECT RT.VALOR_METRO
                      FROM SGC_TP_RANGOS_TARIFAS RT
                     WHERE
                     RT.RANGO        =3
                       AND RT.CONSEC_TARIFA = T.CONSEC_TARIFA
                       AND RT.PERIODO       = (SELECT MAX(T2.PERIODO)
                                                 FROM SGC_TP_RANGOS_TARIFAS T2
                                                WHERE T2.PERIODO      <= $periodo
                                                  AND T2.CONSEC_TARIFA = RT.CONSEC_TARIFA)
                ) precio4,
                (
                 SELECT RT.VALOR_METRO
                      FROM SGC_TP_RANGOS_TARIFAS RT
                     WHERE
                     RT.RANGO        =4
                       AND RT.CONSEC_TARIFA = T.CONSEC_TARIFA
                       AND RT.PERIODO       = (SELECT MAX(T2.PERIODO)
                                                 FROM SGC_TP_RANGOS_TARIFAS T2
                                                WHERE T2.PERIODO      <= $periodo
                                                  AND T2.CONSEC_TARIFA = RT.CONSEC_TARIFA)
                ) precio5
                FROM
                  SGC_TP_TARIFAS T
                WHERE
                T.COD_PROYECTO='$proyecto' AND
                T.COD_SERVICIO=$Serv AND
                T.COD_USO='$uso' 
                ORDER BY T.CODIGO_TARIFA ASC";

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

    public function getUsos($proyecto)
    {
        //$proyecto=addslashes($proyecto);
        // $uso=addslashes($uso);
        $sql = "SELECT u.id_uso, u.desc_uso
              FROM sgc_tp_usos u
             WHERE EXISTS (SELECT *
                      FROM sgc_tp_tarifas_reconexion r
                     WHERE u.id_uso = r.codigo_uso AND r.proyecto = '$proyecto')
               AND u.visible = 'S'";

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

    public function getValTarReconexiones($proyecto, $uso)
    {
        $proyecto = addslashes($proyecto);
        $uso      = addslashes($uso);
        /*$sql      = "SELECT r.codigo_calibre,
        u.desc_uso,
        r.valor_tarifa,
        decode(c.desc_calibre,'SIN DEFINI', 'S/N',c.desc_calibre) desc_calibre,
        r.medidor
        FROM sgc_tp_tarifas_reconexion r, sgc_tp_calibres c, sgc_tp_usos u
        WHERE u.id_uso = r.codigo_uso
        AND u.id_uso = '$uso'
        AND u.visible = 'S'
        --AND c.cod_calibre != 0
        AND c.cod_calibre = r.codigo_calibre
        AND r.proyecto = '$proyecto'
        GROUP BY c.desc_calibre,
        r.medidor,
        u.desc_uso,
        r.valor_tarifa,
        c.cod_calibre,
        r.codigo_calibre
        ORDER BY r.medidor, c.cod_calibre ASC";*/
        $sql = "SELECT c2.desc_calibre codigo_calibre,
                       u.desc_uso,
                       r.valor_tarifa,
                       decode(c.desc_calibre, 'SIN DEFINI', 'S/N', c.desc_calibre) desc_calibre,
                       r.medidor
                  FROM sgc_tp_tarifas_reconexion r,
                       sgc_tp_calibres           c,
                       sgc_tp_usos               u,
                       sgc_tp_calibres           c2
                 WHERE u.id_uso = r.codigo_uso
                   and r.codigo_diametro = c2.cod_calibre
                   AND u.id_uso = '$uso'
                   AND u.visible = 'S'
                    --AND c.cod_calibre != 0
                   AND c.cod_calibre = r.codigo_calibre
                   AND r.proyecto = '$proyecto'
                 ORDER BY r.medidor, c.cod_calibre ASC";

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
    /*

CASE T.DESC_TARIFA
WHEN 'AGUA INDUSTRIALES ACT. 04 Y 16' THEN
'AGUA INDUSTRIALES'
WHEN 'TARIFA 1 ALC. RESIDENCIAL C.S 1,1 1.2 1.2' THEN
'ALC. RESIDENCIAL C.S 1,1 1.2 1.2'
WHEN 'TARIFA 1 ALC. RESIDENCIAL C.S 1,2' THEN
' ALC. RESIDENCIAL C.S 1,2'
WHEN 'TARIFA 2 ALC. RESIDENCIAL C.S 2,1' THEN
'ALC. RESIDENCIAL C.S 2,1'
WHEN 'TARIFA 2 ALC. RESIDENCIAL C.S 2,2' THEN
'ALC. RESIDENCIAL C.S 2,2'
WHEN 'TARIFA 3 ALC. RESIDENCIAL C.S 3,1' THEN
'ALC. RESIDENCIAL C.S 3,1'
WHEN 'TARIFA 3 ALC. RESIDENCIAL C.S 3,2' THEN
'ALC. RESIDENCIAL C.S 3,2'
WHEN 'TARIFA 4 ALC. RESIDENCIAL C.S 4,1' THEN
'ALC. RESIDENCIAL C.S 4,1'
WHEN 'TARIFA 4 ALC. RESIDENCIAL C.S 4,2' THEN
'ALC. RESIDENCIAL C.S 4,2'
WHEN 'TARIFA 5 ALC. RESIDENCIAL C.S 5,1' THEN
'ALC. RESIDENCIAL C.S 5,1'
WHEN 'TARIFA 5 ALC. RESIDENCIAL C.S 5,2' THEN
'ALC. RESIDENCIAL C.S 5,2'
WHEN 'TARIFA 6 ALC. RESIDENCIAL C.S 6,2' THEN
'ALC. RESIDENCIAL C.S 6,2'
ELSE
T.DESC_TARIFA
END
 */

}
