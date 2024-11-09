<?php
include_once '../../clases/class.conexion.php';
class ReportesHistoricoFac extends ConexionClass
{
    private $id_proyecto;
    private $id_zonini;
    private $id_zonfin;
    private $id_perfin;
    private $id_perini;

    public function __construct()
    {
        parent::__construct();
        $this->id_proyecto = "";
        $this->id_zonini   = "";
        $this->id_zonfin   = "";
        $this->id_perfin   = "";
        $this->id_perini   = "";
    }

    public function CantidadZonas($perini, $zonini, $zonfin, $canper, $proyecto, $inm, $grupo, $proini, $profin, $catini, $catfin, $urbaniza, $tipovia, $estado, $estado_inm, $metodo)
    {
        $where = "";
        $from  = "";

        if (trim($inm) != '') {
            $where = " AND I.CODIGO_INM=$inm";
        }

        if (trim($grupo) != '') {
            $where = " AND CLI.COD_GRUPO=$grupo ";
        }

        if ($proini == '' || $profin == '') {
            $proini = '00000000000';
            $profin = '99999999999';
        }

        if ($catini == '' || $catfin == '') {
            $catini = '00000000000000000000';
            $catfin = '99999999999999999999';
        }

        if ($proini != '') {
            $where .= " AND I.ID_PROCESO BETWEEN '$proini' AND '$profin'";
        }

        if ($catini != '') {
            $where .= " AND I.CATASTRO BETWEEN '$catini' AND '$catfin'";
        }

        if ($urbaniza != '') {
            $where .= " AND I.CONSEC_URB = $urbaniza";
        }

        if ($tipovia != '') {
            $where .= " AND TV.ID_TIPO_VIA = $tipovia AND TV.ID_PROYECTO = I.ID_PROYECTO";
            $from .= " , SGC_TP_TIPO_VIA TV";
        }
        if ($metodo == 'T') {
            $where .= " AND I.FACTURAR IN ('D','P')";
        }

        if ($metodo == 'M') {
            $where .= " AND I.FACTURAR IN ('D')";
        }

        if ($metodo == 'N') {
            $where .= " AND I.FACTURAR IN ('P')";
        }

        if ($estado == 'T') {
            $where .= " AND EI.INDICADOR_ESTADO IN ('A','I')";
        }

        if ($estado == 'A') {
            $where .= " AND EI.INDICADOR_ESTADO IN ('A')";
        }

        if ($estado == 'I') {
            $where .= " AND EI.INDICADOR_ESTADO IN ('I')";
        }

        if ($estado_inm != '') {
            $where .= " AND I.ID_ESTADO IN ('$estado_inm')";
        }

        $sql = "SELECT I.ID_ZONA
        FROM SGC_TP_PERIODOS P, SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_CLIENTES CLI, SGC_TT_CONTRATOS CON, SGC_TP_ESTADOS_INMUEBLES EI $from
        WHERE F.INMUEBLE = I.CODIGO_INM + 0
        AND EI.ID_ESTADO = I.ID_ESTADO
        AND CLI.CODIGO_CLI(+)=CON.CODIGO_CLI
        AND I.CODIGO_INM=CON.CODIGO_INM(+)
        AND CON.FECHA_FIN (+) IS NULL
        AND F.PERIODO = P.ID_PERIODO + 0
        AND I.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER ('$zonfin')
        AND I.ID_PROYECTO = '$proyecto'
        $where
        AND TO_DATE(P.ID_PERIODO, 'YYYYMM') BETWEEN ADD_MONTHS(TO_DATE($perini, 'YYYYMM'), -$canper + 1)
        AND ADD_MONTHS(TO_DATE($perini, 'YYYYMM'), 0)
        GROUP BY I.ID_ZONA
        ORDER BY I.ID_ZONA";

        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function InmueblesZona($perini, $zona, $inm, $grupo, $proini, $profin, $catini, $catfin, $urbaniza, $tipovia, $estado, $estado_inm, $metodo, $observacion)
    {
        $where = "";
        $from  = "";

        if (trim($inm) != '') {
            $where = " AND I.CODIGO_INM=$inm";
        }

        if (trim($grupo) != '') {
            $where = " AND CLI.COD_GRUPO=$grupo";
        }

        if ($proini == '' || $profin == '') {
            $proini = '00000000000';
            $profin = '99999999999';
        }

        if ($catini == '' || $catfin == '') {
            $catini = '00000000000000000000';
            $catfin = '99999999999999999999';
        }

        if ($proini != '') {
            $where .= " AND I.ID_PROCESO BETWEEN '$proini' AND '$profin'";
        }

        if ($catini != '') {
            $where .= " AND I.CATASTRO BETWEEN '$catini' AND '$catfin'";
        }

        if ($urbaniza != '') {
            $where .= " AND I.CONSEC_URB = $urbaniza";
        }

        if ($tipovia != '') {
            $where .= " AND TV.ID_TIPO_VIA = $tipovia AND TV.ID_PROYECTO = I.ID_PROYECTO";
            $from .= " , SGC_TP_TIPO_VIA TV";
        }
        if ($metodo == 'T') {
            $where .= " AND I.FACTURAR IN ('D','P')";
        }

        if ($metodo == 'M') {
            $where .= " AND I.FACTURAR IN ('D')";
        }

        if ($metodo == 'N') {
            $where .= " AND I.FACTURAR IN ('P')";
        }

        if ($estado == 'T') {
            $where .= " AND EI.INDICADOR_ESTADO IN ('A','I')";
        }

        if ($estado == 'A') {
            $where .= " AND EI.INDICADOR_ESTADO IN ('A')";
        }

        if ($estado == 'I') {
            $where .= " AND EI.INDICADOR_ESTADO IN ('I')";
        }

        if ($estado_inm != '') {
            $where .= " AND I.ID_ESTADO IN ('$estado_inm') ";
        }
        if ($observacion != '') {
            $where .= " AND R.COD_INMUEBLE = F.INMUEBLE
                        AND R.PERIODO = F.PERIODO
                        AND R.OBSERVACION ='$observacion' ";
            $from .= ", SGC_TT_REGISTRO_LECTURAS R";
        }

        $sql = "SELECT /*+ INDEX_JOIN(F) */ I.CODIGO_INM
                  FROM SGC_TT_INMUEBLES I,
                       SGC_TT_FACTURA F,
                        SGC_TT_CONTRATOS CON,
                        SGC_TT_CLIENTES CLI,
                        SGC_TP_ESTADOS_INMUEBLES EI
                        $from
                 WHERE I.CODIGO_INM = F.INMUEBLE
                  AND EI.ID_ESTADO = I.ID_ESTADO
                   AND I.ID_ZONA = '$zona' || SUBSTR(UID, 1, 0)
                   AND F.PERIODO = $perini
                   AND CLI.CODIGO_CLI(+)=CON.CODIGO_CLI
                    AND I.CODIGO_INM=CON.CODIGO_INM(+)
                    AND CON.FECHA_FIN (+) IS NULL
                   $where
                 ORDER BY I.CODIGO_INM";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function DatosInmueble($inmueble)
    {
        $sql = "SELECT /*+ USE_MERGE(S,C,U) */ C.ALIAS,
                       U.DESC_URBANIZACION,
                       I.DIRECCION,
                       I.ID_ESTADO,
                       A.ID_USO,
                       I.ID_PROCESO,
                       I.CATASTRO,
                       S.UNIDADES_TOT,
                       S.CONSUMO_MINIMO,
                       T.CODIGO_TARIFA,
                       M.COD_MEDIDOR,
                       M.SERIAL,
                       M.COD_EMPLAZAMIENTO
                  FROM SGC_TT_INMUEBLES I,
                       SGC_TT_MEDIDOR_INMUEBLE M,
                       SGC_TP_TARIFAS T,
                       SGC_TP_ACTIVIDADES A,
                       SGC_TP_URBANIZACIONES U,
                       SGC_TT_CONTRATOS C,
                       SGC_TT_SERVICIOS_INMUEBLES S
                 WHERE I.CODIGO_INM = C.CODIGO_INM (+)
                   AND S.COD_INMUEBLE = I.CODIGO_INM
                   AND I.CODIGO_INM = M.COD_INMUEBLE (+)
                   AND C.FECHA_FIN (+) IS NULL
                   AND M.FECHA_BAJA (+) IS NULL
                   AND U.CONSEC_URB = I.CONSEC_URB + 0
                   AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD + 0
                   AND S.CONSEC_TARIFA = T.CONSEC_TARIFA
                   AND (S.COD_SERVICIO + 0 = 1
                         OR S.COD_SERVICIO + 0 = 3)
                   AND I.CODIGO_INM = '$inmueble'";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function PeriodosInmueble($perini, $inmueble, $canper, $observacion)
    {
        $sql = "SELECT F.PERIODO,
                       TO_CHAR(FEC_EXPEDICION, 'DD/MM/YYYY') FECHA,
                       R.LECTURA_ACTUAL,
                       I.ID_RUTA,
                       R.CONSUMO,
                       DECODE(M.COD_MEDIDOR, '', 'P', 'D') METODO,
                       R.COD_LECTOR,
                       R.OBSERVACION,
                       F.TOTAL,
                       TO_CHAR(F.FECHA_PAGO, 'DD/MM/YYYY') FECPAGO,
                       F.TOTAL_PAGADO
                  FROM SGC_TT_INMUEBLES I,
                       SGC_TT_REGISTRO_LECTURAS R,
                       SGC_TT_MEDIDOR_INMUEBLE M,
                       SGC_TT_FACTURA F
                 WHERE F.INMUEBLE = I.CODIGO_INM + 0
                   AND R.COD_INMUEBLE (+) = F.INMUEBLE
                   AND R.PERIODO (+) = F.PERIODO
                   AND M.COD_INMUEBLE (+) = F.INMUEBLE
                   AND M.FECHA_BAJA(+) IS NULL
                   AND TO_DATE(F.PERIODO, 'YYYYMM') BETWEEN ADD_MONTHS(TO_DATE($perini, 'YYYYMM'), -$canper + 1) AND ADD_MONTHS(TO_DATE($perini, 'YYYYMM'), 0)
                   AND F.INMUEBLE + 0 = '$inmueble' || SUBSTR(UID, 1, 0)
                   AND I.CODIGO_INM = '$inmueble' || SUBSTR(UID, 1, 0) ";
        if ($observacion != '') {$sql .= " AND R.OBSERVACION ='$observacion' ";}

        $sql .= " ORDER BY F.INMUEBLE, F.PERIODO";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }
    public function Totalhisfac($perini, $inmueble, $canper, $zona){
        $sql = "SELECT F.PERIODO, SUM(F.TOTAl) TOTAL
                    FROM SGC_TT_INMUEBLES         I,
                         SGC_TT_REGISTRO_LECTURAS R,
                         SGC_TT_MEDIDOR_INMUEBLE  M,
                         SGC_TT_FACTURA           F
            WHERE F.INMUEBLE = I.CODIGO_INM + 0
              AND R.COD_INMUEBLE(+) = F.INMUEBLE
              AND R.PERIODO(+) = F.PERIODO
              AND M.COD_INMUEBLE(+) = F.INMUEBLE
              AND M.FECHA_BAJA(+) IS NULL
              AND TO_DATE(F.PERIODO, 'YYYYMM') BETWEEN
                  ADD_MONTHS(TO_DATE($perini, 'YYYYMM'), -$canper + 1) AND
                  ADD_MONTHS(TO_DATE($perini, 'YYYYMM'), 0)
              AND I.ID_ZONA = '$zona'
            GROUP BY F.PERIODO
       ORDER BY F.PERIODO";

        $resultado = oci_parse($this->_db, $sql);
            
        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }
}
