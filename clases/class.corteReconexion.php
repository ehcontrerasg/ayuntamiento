<?php
include_once "class.conexion.php";

class Corte extends ConexionClass
{

    private $mesrror;
    private $coderror;

    public function __construct()
    {
        parent::__construct();
    }

    public function getMesrror()
    {
        return $this->mesrror;
    }

    public function getCoderror()
    {
        return $this->coderror;
    }

    public function reversaCorte($inmueble, $usr, $obs)
    {
        $inmueble = addslashes($inmueble);
        $usr      = addslashes($usr);
        $obs      = addslashes($obs);

        $sql       = "BEGIN SGC_P_REVERSA_CORTE($inmueble,'$usr','$obs',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 500);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);
        $bandera = oci_execute($resultado);
        oci_close($this->_db);

        if ($bandera == true) {
            if ($this->coderror > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function generaCorte($inmueble, $usr, $mot, $oper)
    {
        $inmueble = addslashes($inmueble);
        $usr      = addslashes($usr);
        $mot      = addslashes($mot);
        $oper     = addslashes($oper);

        $sql       = "BEGIN SGC_P_ORD_CORT_MAN($inmueble,'$usr','$oper','$mot',:PMSGRESULT, :PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 500);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);
        $bandera = oci_execute($resultado);
        oci_close($this->_db);

        if ($bandera == true) {
            if ($this->coderror > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function getCortAbiByPerZonFacMontUsoUni($pro, $zona, $factvenini, $factvenfin, $montoini, $montofin, $uso, $unini, $unfin)
    {
        $zona       = addslashes($zona);
        $factvenini = addslashes($factvenini);
        $factvenfin = addslashes($factvenfin);
        $montoini   = addslashes($montoini);
        $montofin   = addslashes($montofin);
        $uso        = addslashes($uso);
        $unini      = addslashes($unini);
        $unfin      = addslashes($unfin);
        $pro        = addslashes($pro);

        $sql = "SELECT
                  COUNT(I.CODIGO_INM)CANTIDAD,
                  CONCAT(I.ID_SECTOR,I.ID_RUTA)  RUTA,
                  NVL(ME.ESTADO_MED,'N') MEDIDO,
                  RC.USR_EJE
                FROM SGC_TT_REGISTRO_CORTES RC, SGC_TT_INMUEBLES I, SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TP_MEDIDORES ME";
        ///// uso
        if (trim($uso) != "") {
            $sql = $sql . " , SGC_TP_ACTIVIDADES ACT ";
        }

        $sql = $sql . " WHERE

                I.ID_ZONA='$zona' AND
                I.ID_PROYECTO='$pro' AND
                RC.FECHA_APERTURALOTE IS NOT NULL AND
                I.CODIGO_INM=MI.COD_INMUEBLE(+) AND
                RC.FECHA_ACUERDO IS NULL AND
                RC.FECHA_REVERSION IS NULL AND
                RC.FECHA_EJE IS NULL AND
                RC.PERVENC='N' AND
                ME.CODIGO_MED(+)=MI.COD_MEDIDOR AND
                MI.FECHA_BAJA(+) IS NULL AND
                I.CODIGO_INM=RC.ID_INMUEBLE
                 AND RC.ID_INMUEBLE NOT IN
            (SELECT PQR.COD_INMUEBLE FROM SGC_TT_PQRS PQR
            WHERE PQR.TIPO_PQR IN(1,4)
            AND PQR.DIAGNOSTICO IS NULL
            AND PQR.COD_INMUEBLE=RC.ID_INMUEBLE)";
        //// fecturas vecidas inicial

        if (trim($factvenini) != "") {
            $sql = $sql . " AND I.CODIGO_INM IN (SELECT F.INMUEBLE FROM SGC_TT_FACTURA F WHERE F.INMUEBLE=I.CODIGO_INM
                    and F.FACTURA_PAGADA='N'
                    AND F.FEC_VCTO<=SYSDATE
                    group by(F.INMUEBLE)
                    having count(f.inmueble)>=$factvenini)";
        }
        //// fecturas vecidas final
        if (trim($factvenfin) != "") {
            $sql = $sql . " AND I.CODIGO_INM IN (SELECT F.INMUEBLE FROM SGC_TT_FACTURA F WHERE F.INMUEBLE=I.CODIGO_INM
                    and F.FACTURA_PAGADA='N'
                    AND F.FEC_VCTO<=SYSDATE
                    group by(F.INMUEBLE)
                    having count(f.inmueble)<=$factvenfin)";
        }
        //// monto inicial deuda
        if (trim($montoini) != "") {
            $sql = $sql . " and i.codigo_inm in (select  F2.INMUEBLE FROM SGC_TT_FACTURA F2 WHERE F2.INMUEBLE=I.CODIGO_INM
                    and F2.FACTURA_PAGADA='N'
                    AND F2.FEC_VCTO<=SYSDATE
                    group by(F2.INMUEBLE)
                    having sum(f2.total-total_pagado )>=$montoini ) ";
        }
        //// monto inicial deuda
        if (trim($montofin) != "") {
            $sql = $sql . "  and i.codigo_inm in (select  F2.INMUEBLE FROM SGC_TT_FACTURA F2 WHERE F2.INMUEBLE=I.CODIGO_INM
                and F2.FACTURA_PAGADA='N'
                AND F2.FEC_VCTO<=SYSDATE
                group by(F2.INMUEBLE)
                having sum(f2.total-total_pagado )<=$montofin ) ";
        }
        ///// uso
        if (trim($uso) != "") {

            $sql = $sql . " AND ACT.ID_USO='$uso'   AND ACT.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD";
        }
        ///// unidades ini
        if (trim($unini) != "") {
            $sql = $sql . " AND I.UNIDADES_HAB>=$unini";
        }
        if (trim($unfin) != "") {
            $sql = $sql . " AND I.UNIDADES_HAB<=$unfin";
        }

        $sql = $sql . "

         GROUP BY I.ID_SECTOR,I.ID_RUTA,NVL(ME.ESTADO_MED,'N'),RC.USR_EJE
        ORDER BY I.ID_SECTOR,I.ID_RUTA,NVL(ME.ESTADO_MED,'N') ASC ";

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

    public function asignaCorteByZonRutOperFacDeuUsoUniMed($zona, $ruta, $operario, $asignador, $facvenmin, $facvenmax, $montoini, $montofin, $uso, $unidadesini, $unidadesfin, $medidor, $fechapla, $usuViejo)
    {
        $sql       = "BEGIN SGC_P_ASIGCORTE('$zona','$ruta','$operario','$asignador','$facvenmin','$facvenmax','$montoini','$montofin','$uso','$unidadesini','$unidadesfin','$medidor','$fechapla','$usuViejo',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msresult, 10000);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, 123);

        if (oci_execute($resultado)) {
            oci_close($this->_db);
            if ($this->codresult > 0) {
                return false;
            } else {
                return $resultado;
            }

        } else {
            oci_close($this->_db);
            return false;
        }

    }

    public function abreLoteCorte($zona, $periodo, $usr)
    {

        $sql       = "BEGIN SGC_P_APLOT_CORTE('$zona',$periodo,'$usr',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->msresult, 123);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->codresult, 123);
        if (oci_execute($resultado)) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "error consulta lotecorte";
            return false;
        }
    }

    public function recMonGerPer($periodo, $tipo, $gerencia)
    {
        $sql = "
               SELECT SUM(ORE.IMPORTE) CANTIDAD  from sgc_tt_registro_reconexion rr, SGC_TT_INMUEBLES INM, sgc_tp_sectores s,SGC_TT_OTROS_RECAUDOS ORE,
                sgc_tt_medidor_inmueble mi, SGC_TP_MEDIDORES ME where
                INM.CODIGO_INM=RR.ID_INMUEBLE
                and INM.ID_SECTOR=S.ID_SECTOR
                AND RR.ID_OTRO_RECAUDO is not null
                and MI.COD_INMUEBLE(+)=INM.CODIGO_INM
                and MI.FECHA_BAJA(+) is null
                AND ME.CODIGO_MED(+)=MI.COD_MEDIDOR
                AND ORE.CODIGO=RR.ID_OTRO_RECAUDO
                AND NVL(ME.ESTADO_MED,'N')='$tipo'
                and RR.ANULADO='N'
                AND S.ID_GERENCIA='$gerencia'
                and TO_CHAR(RR.FECHA_ACUERDO,'YYYYMM')=$periodo
               ";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getCortReaByFecRutSecPer($fecIni, $fecFin, $ruta, $periodo, $usr,$contratista )
    {
        $fecIni  = addslashes($fecIni);
        $fecFin  = addslashes($fecFin);
        $ruta    = addslashes($ruta);
        $periodo = addslashes($periodo);
        $usr     = addslashes($usr);
        $sql     = "SELECT
                RC.ID_INMUEBLE,
                I.ID_PROCESO,
                I.CATASTRO,
                NVL(C.ALIAS,CLI.NOMBRE_CLI)NOMBRE,
                I.DIRECCION,
                NVL(MI.SERIAL,'N/A') SERIAL,
                RC.LECTURA LECTURA,
                RC.TIPO_CORTE,
                NVL(RC.IMPO_CORTE,'N/A') IMPO_COTE,
                NVL(RC.OBS_GENERALES,'N/A') OBSERVACIONES,
                TO_CHAR(RC.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHA
              FROM
                SGC_TT_REGISTRO_CORTES RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_CONTRATOS C,
                SGC_TT_CLIENTES CLI,
                SGC_TT_MEDIDOR_INMUEBLE MI,
                SGC_TT_USUARIOS U
              WHERE
                I.CODIGO_INM=RC.ID_INMUEBLE AND
                RC.USR_EJE=U.ID_USUARIO AND
                U.CONTRATISTA=$contratista AND
                C.CODIGO_INM(+)=I.CODIGO_INM AND
                CLI.CODIGO_CLI(+)=C.CODIGO_CLI AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                MI.FECHA_BAJA(+) IS NULL AND
                RC.FECHA_EJE BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
                RC.USR_EJE='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta'
               -- RC.ID_PERIODO='$periodo'
                ORDER BY RC.FECHA_EJE ASC
               ";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }
    public function getCoordCortReaByFecRutSecPer($fecIni, $fecFin, $ruta, $periodo, $usr)
    {
        $fecIni  = addslashes($fecIni);
        $fecFin  = addslashes($fecFin);
        $ruta    = addslashes($ruta);
        $periodo = addslashes($periodo);
        $usr     = addslashes($usr);
        $sql     = "SELECT
                RC.LATITUD lat,
                RC.LONGITUD lgn,
                RC.ID_INMUEBLE,
                TO_CHAR(RC.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHA
              FROM
                SGC_TT_REGISTRO_CORTES RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=RC.ID_INMUEBLE AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                MI.FECHA_BAJA(+) IS NULL AND
                RC.FECHA_EJE BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
                RC.USR_EJE='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta' AND
                RC.LATITUD<>0 AND
                RC.LONGITUD<>0
               -- RC.ID_PERIODO='$periodo'
                ORDER BY RC.FECHA_EJE ASC
               ";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function getCantRutCorPerEje($pro, $sector, $fecini, $fecfin,$contratista)
    {
        $pro    = addslashes($pro);
        $sector = addslashes($sector);
        $fecini = addslashes($fecini);
        $fecfin = addslashes($fecfin);

        $where = '';

        if (trim($sector) != '') {
            $where .= " AND I.ID_SECTOR='$sector' ";
        }

        if (trim($fecini) != '') {
            $where .= " and RC.FECHA_EJE >=TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecfin) != '') {
            $where .= " and RC.FECHA_EJE <=TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql = "SELECT
                COUNT(*) TOTAL
              FROM (
                SELECT
                  count(1)
                FROM
                  SGC_TT_REGISTRO_CORTES RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                  RC.ID_INMUEBLE=I.CODIGO_INM
                  AND US.ID_USUARIO=RC.USR_EJE 
                  AND US.CONTRATISTA=$contratista AND
                  RC.FECHA_EJE IS NOT NULL AND
                  RC.FECHA_PLANIFICACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where
                GROUP BY
                  RC.USR_EJE,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA,
                  RC.ID_PERIODO,
                  TO_CHAR(RC.FECHA_PLANIFICACION,'DD-MM-YYYY'))";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'TOTAL');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getCantFotCorByDiaInm($fecha, $inm,$contratista)
    {
        $fecha = addslashes($fecha);
        $inm   = addslashes($inm);

        $sql = "SELECT
                COUNT(1)TOTAL
              FROM
                SGC_TT_FOTOS_CORTE FC, SGC_TT_USUARIOS U
              WHERE
                TO_CHAR(FC.FECHA,'yyyy-mm-dd')=TO_CHAR(to_DATE('$fecha','DD/MM/YYYY HH24:MI:SS'),'yyyy-mm-dd')
                AND U.CONTRATISTA=$contratista
                AND FC.ID_INMUEBLE='$inm'";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'TOTAL');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function obtenerResumenRutasCort($sort, $start, $end, $pro, $sec, $fecIni, $fecFin,$contratista)
    {

        $pro    = addslashes($pro);
        $sec    = addslashes($sec);
        $fecIni = addslashes($fecIni);
        $fecFin = addslashes($fecFin);

        $where = '';

        if (trim($sec) != '') {
            $where .= " AND I.ID_SECTOR='$sec' ";
        }

        if (trim($fecIni) != '') {
            $where .= " and RC.FECHA_PLANIFICACION >=TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecFin) != '') {
            $where .= " and RC.FECHA_PLANIFICACION <=TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (
                  SELECT
                RC.USR_EJE ID_USUARIO,
                  US.NOM_USR||' '||US.APE_USR NOMBRE,
                  I.ID_SECTOR||I.ID_RUTA RUTA,
                  RC.ID_PERIODO PERIODO,
                  TO_CHAR(RC.FECHA_PLANIFICACION,'DD-MM-YYYY') FECHA_PLA,
                  TO_CHAR(MAX(RC.FECHA_EJE),'YYYY/MM/DD HH24:MI:SS') FIN,
                  TO_CHAR(MIN(RC.FECHA_EJE),'YYYY/MM/DD HH24:MI:SS') INICIO,
                   SUBSTR('0'||TRUNC((max(RC.FECHA_EJE)-MIN(RC.FECHA_EJE))*24,0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_EJE)-MIN(RC.FECHA_EJE))*24*60,0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_EJE)-MIN(RC.FECHA_EJE))*24*60*60,0),60),-2) TIEMPO,

                   SUBSTR('0'||TRUNC((max(RC.FECHA_EJE)-MIN(RC.FECHA_EJE))*24/count(1),0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_EJE)-MIN(RC.FECHA_EJE)))*24*60/count(1),0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_EJE)-MIN(RC.FECHA_EJE)))*24*60*60/count(1),0),60),-2) TIEMPO_PRO,
                    case (((max(RC.FECHA_EJE)-MIN(RC.FECHA_EJE)))*24*60/count(1)) when
                   0 then 0
                   else
                   round(60/(((max(RC.FECHA_EJE)-MIN(RC.FECHA_EJE)))*24*60/count(1)),1)
                   end PRE_HORA,


                  count(1) CANTIDAD
                FROM
                  SGC_TT_REGISTRO_CORTES RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                  RC.ID_INMUEBLE=I.CODIGO_INM AND
                  US.ID_USUARIO=RC.USR_EJE AND
                  US.CONTRATISTA=$contratista AND
                  --RC.FECHA_EJE IS NOT NULL AND
                  RC.FECHA_PLANIFICACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where

                GROUP BY
                  RC.USR_EJE,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA,
                  RC.ID_PERIODO,
                  TO_CHAR(RC.FECHA_PLANIFICACION,'DD-MM-YYYY')
                   $sort)a
        WHERE rownum <= $start ) WHERE rnum >= $end+1";
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

    public function getCortByInmFlexy($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        select * from (
                        SELECT RC.ORDEN,TO_CHAR(RC.FECHA_PLANIFICACION,'DD/MM/YYYY HH24:MI') FECHA_PLANIFICACION ,TO_CHAR(RC.FECHA_EJE,'DD/MM/YYYY HH24:MI') FECHA_EJE,RC.DESCRIPCION,RC.TIPO_CORTE, NVL(US2.LOGIN,US.LOGIN) LOGIN,
                        RC.OBS_REVERSION OBS,RC.IMPO_CORTE, RC.REVERSADO, NVL(US1.LOGIN,US2.LOGIN) USU_REV, RC.FECHA_REVERSION
                          FROM SGC_TT_REGISTRO_CORTES RC, SGC_TT_USUARIOS US, SGC_TT_USUARIOS US2, SGC_TT_USUARIOS US1
                        WHERE
                         US.ID_USUARIO(+)=RC.USR_EJE
                        AND US2.ID_USUARIO(+)=RC.USR_ULT_CORTE
                        AND US1.ID_USUARIO (+)= RC.USR_REVERSION
                        $where
                        $sort
                   )where  rownum<1000
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db, $sql
        );

        $banderas = oci_execute($resultado);
        if ($banderas == true) {

            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getPriPuntCoorByRutFecUsu($ruta, $fecini, $fecfin, $usuario)
    {

        $sql = "   SELECT * FROM (
                 SELECT COR.LONGITUD, COR.LATITUD, COR.ID_INMUEBLE ,TO_CHAR(COR.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
                 FROM SGC_TT_REGISTRO_cortes cor, SGC_TT_INMUEBLES INM
                 WHERE
                 INM.CODIGO_INM=COR.ID_INMUEBLE
                 AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
                 AND COR.USR_EJE='$usuario'
                 AND COR.FECHA_EJE BETWEEN TO_DATE('$fecini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'yyyy-mm-dd hh24:mi:ss')
                 AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
                 ORDER BY COR.FECHA_EJE ASC)
                 WHERE ROWNUM = 1";
        //echo $sql;
        $resultdo = oci_parse($this->_db, $sql);

        if (oci_execute($resultdo)) {
            oci_close($this->_db);
            return $resultdo;
        } else {
            oci_close($this->_db);
            echo "error consulta primer punto";
            return false;
        }
    }

    public function getUltPuntCoorByRutFecUsu($ruta, $fecini, $fecfin, $usuario)
    {

        $sql = "   SELECT * FROM (
                 SELECT COR.LONGITUD, COR.LATITUD, COR.ID_INMUEBLE COD_INMUEBLE,TO_CHAR(COR.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
                 FROM SGC_TT_REGISTRO_cortes cor, SGC_TT_INMUEBLES INM
                 WHERE
                 INM.CODIGO_INM=COR.ID_INMUEBLE
                 AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
                 AND COR.USR_EJE='$usuario'
                 AND COR.FECHA_EJE BETWEEN TO_DATE('$fecini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'yyyy-mm-dd hh24:mi:ss')
                 AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
                 ORDER BY COR.FECHA_EJE DESC)
        WHERE ROWNUM = 1";
        //echo $sql;
        $resultdo = oci_parse($this->_db, $sql);

        if (oci_execute($resultdo)) {
            oci_close($this->_db);
            return $resultdo;
        } else {
            oci_close($this->_db);
            echo "error consulta ultimo punto";
            return false;
        }
    }

    public function getResPuntCoorByRutFecUsu($ruta, $fecini, $fecfin, $usuario, $codini, $codfin)
    {

        $sql = "
         SELECT COR.LONGITUD, COR.LATITUD, COR.ID_INMUEBLE COD_INMUEBLE ,TO_CHAR(COR.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
        FROM SGC_TT_REGISTRO_cortes cor, SGC_TT_INMUEBLES INM
         WHERE
        INM.CODIGO_INM=COR.ID_INMUEBLE
        AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
        AND COR.USR_EJE='$usuario'
        AND COR.FECHA_EJE BETWEEN TO_DATE('$fecini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'yyyy-mm-dd hh24:mi:ss')
        AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'

        AND COR.ID_INMUEBLE<>'$codini'
        AND COR.ID_INMUEBLE<>'$codfin'
        ORDER BY FECHA_EJE ASC";
        //echo $sql;
        $resultdo = oci_parse($this->_db, $sql);

        if (oci_execute($resultdo)) {
            oci_close($this->_db);
            return $resultdo;
        } else {
            oci_close($this->_db);
            echo "error consulta resto de  puntos";
            return false;
        }
    }

    public function getTotPuntCoorByRutFecUsu($ruta, $fecini, $fecfin, $usuario)
    {

        $sql = " SELECT COR.LONGITUD, COR.LATITUD, COR.ID_INMUEBLE COD_INMUEBLE ,TO_CHAR(COR.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
        FROM SGC_TT_REGISTRO_cortes cor, SGC_TT_INMUEBLES INM
         WHERE
        INM.CODIGO_INM=COR.ID_INMUEBLE
        AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
        AND COR.USR_EJE='$usuario'
        AND COR.FECHA_EJE BETWEEN TO_DATE('$fecini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'yyyy-mm-dd hh24:mi:ss')
        AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'

        ORDER BY FECHA_EJE ASC";
        //echo $sql;
        $resultdo = oci_parse($this->_db, $sql);

        if (oci_execute($resultdo)) {
            oci_close($this->_db);
            return $resultdo;
        } else {
            oci_close($this->_db);
            echo "error consulta resto de  puntos";
            return false;
        }
    }

    public function getTipoCortePorSec($periodo, $proy)
    {
        $periodo = addslashes($periodo);
        $proy    = addslashes($proy);
        $sql     = "SELECT *
                      from (SELECT I.ID_SECTOR, RC.TIPO_CORTE
                              from sgc_tt_registro_cortes rc, SGC_TT_INMUEBLES I
                             WHERE I.CODIGO_INM = RC.ID_INMUEBLE(+)
                               AND I.ID_PROYECTO = '$proy'
                               AND RC.FECHA_EJE(+) BETWEEN TO_DATE('$periodo' || '01', 'YYYYMMDD') AND
                                   LAST_DAY(TO_DATE('$periodo' || '012359', 'YYYYMMDDHH24MI'))
                               AND TRIM(RC.IMPO_CORTE) IS NULL
                               AND RC.REVERSADO = 'N'
                            --and REVERSADO(+) = 'N' || SUBSTR(UID, 1, 0)
                            ) pivot(count(TIPO_CORTE) for TIPO_CORTE in('TP1',
                                                                        'TP2',
                                                                        'TP3',
                                                                        'TP4',
                                                                        'TP5')) V
                     --order by ID_SECTOR";
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

    public function getCantSecPorPer($periodo, $pro)
    {
        $sql = "

SELECT I.ID_SECTOR,
       (SELECT COUNT(1)
          FROM sgc_tt_registro_cortes R2, SGC_TT_INMUEBLES I2
         WHERE I2.CODIGO_INM = R2.ID_INMUEBLE + 0
           AND I2.ID_SECTOR = I.ID_SECTOR
           AND R2.FECHA_PLANIFICACION BETWEEN
               TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') and
               LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))) PLANILLADOS,
       (SELECT COUNT(1)
          FROM SGC_TT_INMUEBLES I2, SGC_TT_REGISTRO_CORTES RC2
         WHERE I2.ID_SECTOR = I.ID_SECTOR
           AND i2.codigo_inm = rc2.id_inmueble
           AND RC2.FECHA_EJE BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') and
               LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))) VISITADOS,
       (select count(1) inspecciones
          from sgc_tt_inspecciones_cortes ip, sgc_tt_inmuebles i2
         where ip.codigo_inm = i2.codigo_inm
           and i2.id_sector = i.id_sector
           and ip.fecha_eje BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD')
           and LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))) inspecciones,
       (SELECT COUNT(1)
                  FROM SGC_TT_REGISTRO_CORTES  RC2,
                       SGC_TT_INMUEBLES        I2,
                       SGC_TP_SECTORES         S,
                       SGC_TT_MEDIDOR_INMUEBLE MI,
                       SGC_TP_MEDIDORES        ME
                 WHERE I2.CODIGO_INM = RC2.ID_INMUEBLE
                   AND I2.ID_SECTOR = I.ID_SECTOR
                   AND RC2.FECHA_EJE(+) BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') AND
                        LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                   AND MI.COD_INMUEBLE(+) = I2.CODIGO_INM
                   AND ME.CODIGO_MED(+) = MI.COD_MEDIDOR
                   AND MI.FECHA_BAJA(+) IS NULL
                   AND RC2.REVERSADO = 'N'
                   AND TRIM(RC2.IMPO_CORTE) IS NULL
                   AND S.ID_SECTOR = I2.ID_SECTOR) CORTESEFECTIVOS
  FROM sgc_tt_registro_cortes R, SGC_TT_INMUEBLES I
 WHERE I.CODIGO_INM = R.ID_INMUEBLE(+) + 0
   AND I.ID_PROYECTO = '$pro'
   AND R.FECHA_PLANIFICACION(+) IS NOT NULL
--AND TO_CHAR(R.FECHA_APERTURALOTE(+), 'YYYYMM') = '$periodo'
 group by I.id_sector
 order by I.id_sector
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

    public function getCantInsPorPer($periodo, $pro)
    {
        $sql = "SELECT i.id_sector,
       (select count(1)
          from sgc_tt_inspecciones_cortes ic,
               sgc_tt_inmuebles           i2
         where i2.codigo_inm = ic.codigo_inm
           and i2.id_sector = i.id_sector
           and ic.fecha_eje BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD')
           and LAST_DAY(TO_DATE('$periodo' || '012359', 'YYYYMMDDHH24MI'))
           and i2.id_proyecto = '$pro'
           and ic.anulada = 'N') visitados,
        (select count(1)
           from sgc_tt_inspecciones_cortes ic5, sgc_tt_inmuebles i6
          where i6.codigo_inm = ic5.codigo_inm
            and i6.id_sector = i.id_sector
            and i6.id_proyecto = '$pro'
            and ic5.fecha_eje BETWEEN
                TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') and
                LAST_DAY(TO_DATE('$periodo' || '012359', 'YYYYMMDDHH24MI'))
            and ic5.fecha_planificacion <
                TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD')) anterior,
       (select count(1)
          from sgc_tt_inspecciones_cortes ic2,
               sgc_tt_inmuebles           i3
         where i3.codigo_inm = ic2.codigo_inm
           and i3.id_sector = i.id_sector
           and i3.id_proyecto = '$pro'
           and ic2.fecha_planificacion BETWEEN
               TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') and
               LAST_DAY(TO_DATE('$periodo' || '012359', 'YYYYMMDDHH24MI'))) planillados,
        (SELECT COUNT(1) CANTIDAD
          FROM SGC_TT_PAGOS        P,
               SGC_TP_CAJAS_PAGO   C,
               SGC_TP_ENTIDAD_PAGO E,
               SGC_TP_PUNTO_PAGO   R,
               SGC_TT_INMUEBLES    I4
         WHERE C.ID_CAJA = P.ID_CAJA
           AND C.ID_PUNTO_PAGO = R.ID_PUNTO_PAGO
           AND R.ENTIDAD_COD = E.COD_ENTIDAD
           AND E.VALIDA_REPORTES = 'S'
           AND P.ESTADO = 'A'
           AND I4.CODIGO_INM = P.INM_CODIGO
           and i4.id_proyecto = '$pro'
           AND I4.ID_SECTOR = i.id_sector
           AND P.FECHA_PAGO BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') AND
               LAST_DAY(TO_DATE('$periodo' || '012359', 'YYYYMMDDHH24MI'))
           and P.INM_CODIGO IN
               (SELECT RC2.ID_INMUEBLE
                  FROM SGC_TT_REGISTRO_CORTES RC2, sgc_tt_inspecciones_cortes ic
                 WHERE I4.CODIGO_INM = RC2.ID_INMUEBLE
                   AND RC2.FECHA_EJE IS NOT NULL
                   AND TRIM(RC2.IMPO_CORTE) IS NULL
                   AND RC2.REVERSADO = 'N'
                   and rc2.orden = ic.orden_corte
                   AND ic.fecha_eje BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') AND
               LAST_DAY(TO_DATE('$periodo' || '012359', 'YYYYMMDDHH24MI')))) pagos,
           (SELECT sum(p1.importe) --COUNT(1)CANTIDAD
                FROM SGC_TT_PAGOS        P1,
                       SGC_TP_CAJAS_PAGO   C1,
                       SGC_TP_ENTIDAD_PAGO E1,
                       SGC_TP_PUNTO_PAGO   R1,
                       SGC_TT_INMUEBLES    I5
                 WHERE C1.ID_CAJA = P1.ID_CAJA
                   AND C1.ID_PUNTO_PAGO = R1.ID_PUNTO_PAGO
                   AND R1.ENTIDAD_COD = E1.COD_ENTIDAD
                   AND E1.VALIDA_REPORTES = 'S'
                   AND P1.ESTADO = 'A'
                   AND I5.CODIGO_INM = P1.INM_CODIGO
                   and i5.id_proyecto = '$pro'
                   AND I5.ID_SECTOR = i.id_sector
                   AND P1.FECHA_PAGO BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') AND
                       LAST_DAY(TO_DATE('$periodo' || '012359', 'YYYYMMDDHH24MI'))
                   and P1.INM_CODIGO IN
                       (SELECT RC2.ID_INMUEBLE
                          FROM SGC_TT_REGISTRO_CORTES RC2, sgc_tt_inspecciones_cortes ic
                         WHERE I5.CODIGO_INM = RC2.ID_INMUEBLE
                           AND RC2.FECHA_EJE IS NOT NULL
                           AND TRIM(RC2.IMPO_CORTE) IS NULL
                           AND RC2.REVERSADO = 'N'
                           and rc2.orden = ic.orden_corte
                           AND ic.fecha_eje BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') AND
                       LAST_DAY(TO_DATE('$periodo' || '012359', 'YYYYMMDDHH24MI')))) importe
  FROM sgc_tt_registro_cortes R, SGC_TT_INMUEBLES I
 WHERE I.CODIGO_INM = R.ID_INMUEBLE(+) + 0
   AND TO_CHAR(R.FECHA_APERTURALOTE(+), 'YYYYMM') = '$periodo'
   AND I.ID_PROYECTO = '$pro'
   AND R.FECHA_PLANIFICACION(+) IS NOT NULL
 group by I.id_sector
 order by I.id_sector
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

    public function getCantGerTipo($periodo, $tipo, $gerencia, $pro)
    {
        $sql = "SELECT COUNT(1) CANTIDAD
                  FROM SGC_TT_REGISTRO_CORTES  RC,
                       SGC_TT_INMUEBLES        I,
                       SGC_TP_SECTORES         S,
                       SGC_TT_MEDIDOR_INMUEBLE MI,
                       SGC_TP_MEDIDORES        ME
                 WHERE I.CODIGO_INM = RC.ID_INMUEBLE
                   AND I.ID_PROYECTO = '$pro'
                   AND S.ID_GERENCIA = '$gerencia'
                   AND RC.FECHA_EJE(+) BETWEEN TO_DATE('$periodo' || '01', 'YYYYMM' || 'DD') AND
                        LAST_DAY(TO_DATE('$periodo' || '012359','YYYYMMDDHH24MI'))
                   AND MI.COD_INMUEBLE(+) = I.CODIGO_INM
                   AND ME.CODIGO_MED(+) = MI.COD_MEDIDOR
                   AND MI.FECHA_BAJA(+) IS NULL
                   AND RC.REVERSADO = 'N'
                   AND TRIM(RC.IMPO_CORTE) IS NULL
                   AND S.ID_SECTOR = I.ID_SECTOR
                   AND NVL(ME.ESTADO_MED, 'N') = '$tipo'
               ";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            oci_fetch($resultado);
            return oci_result($resultado, 'CANTIDAD');
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getUltCortByInm($inmueble)
    {
        $sql = "select * from (
                select TO_CHAR(RC.FECHA_EJE,'DD/MM/yyyy') FECHA ,RC.TIPO_CORTE from SGC_TT_REGISTRO_CORTES RC
                WHERE RC.ID_INMUEBLE='$inmueble'
                AND RC.CORTE_EFECTIVO='S'
                AND RC.FECHA_REVERSION IS NULL
                AND RC.REVERSADO='N'
                ORDER BY (RC.FECHA_EJE) DESC) where rownum<4";
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getInmACorByProSecZonUsu($proy, $sec, $zon, $oper)
    {
        $sql = "SELECT R.ORDEN, to_char(SYSDATE,'DD/MM/YYYY') FECHACT, CONCAT(CONCAT(U.NOM_USR,' '),U.APE_USR) OPERARIO, TO_CHAR(R.FECHA_ACUERDO,'DD/MM/YYYY') FECACUERDO,I.CODIGO_INM,I.DIRECCION,URB.DESC_URBANIZACION,
        NVL(C.ALIAS,CLI.NOMBRE_CLI) NOMBRE,S.DESC_SERVICIO, NVL(CLI.TELEFONO,I.TELEFONO) TELEFONO,I.ID_PROCESO, I.CATASTRO, R.TIPO_CORTE, ME.DESC_MED, CA.DESC_CALIBRE, MI.SERIAL,DESC_USO,
        (SELECT DC.DESC_CALIBRE FROM SGC_TP_CALIBRES DC WHERE DC.COD_CALIBRE=I.COD_DIAMETRO) DIAMETRO
        FROM SGC_TT_REGISTRO_CORTES R, SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CLI, SGC_TP_ACTIVIDADES AC,
        SGC_TP_USOS USO, SGC_TP_URBANIZACIONES URB, SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TP_MEDIDORES ME,SGC_TP_CALIBRES CA,
        sgc_tt_servicios_inmuebles si, SGC_TP_SERVICIOS S
              WHERE I.CODIGO_INM=R.ID_INMUEBLE
              AND C.CODIGO_INM=I.CODIGO_INM
              AND CLI.CODIGO_CLI=C.CODIGO_CLI
              AND SI.COD_INMUEBLE=I.CODIGO_INM
              AND SI.COD_SERVICIO IN (1,3)
              AND SI.COD_SERVICIO=S.COD_SERVICIO
              AND AC.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD
              AND MI.COD_INMUEBLE(+)=I.CODIGO_INM
              AND MI.FECHA_BAJA (+) IS NULL
              AND ME.CODIGO_MED(+)=MI.COD_MEDIDOR
              AND CA.COD_CALIBRE(+)=MI.COD_CALIBRE
              AND USO.ID_USO=AC.ID_USO
              AND URB.CONSEC_URB=I.CONSEC_URB
              AND C.FECHA_FIN (+) IS NULL
              AND R.USR_EJE=U.ID_USUARIO
              AND R.USR_EJE IS NOT NULL
              AND R.FECHA_EJE IS NULL
            and R.PERVENC='N'
            AND R.FECHA_ACUERDO IS NULL

              AND R.REVERSADO='N'
              and R.FECHA_PLANIFICACION is not null
              ";

        if (trim($proy)) {
            $sql = $sql . " AND I.ID_PROYECTO='$proy' ";
        }
        if (trim($sec) != "") {
            $sql = $sql . " AND I.ID_SECTOR=$sec ";
        }
        if (trim($zon) != "") {
            $sql = $sql . " AND I.ID_zona='$zon' ";
        }

        if (trim($oper) != "") {
            $sql = $sql . " AND R.USR_EJE='$oper' ";
        }

        $sql = $sql . " ORDER BY R.USR_EJE, ID_PROCESO ";

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

    public function getCantInmueblesACor($proy, $sec, $zon, $oper)
    {
        $sql = "SELECT COUNT(1) NUMERO       FROM SGC_TT_REGISTRO_CORTES R, SGC_TT_INMUEBLES I
            WHERE I.CODIGO_INM=R.ID_INMUEBLE
            AND R.USR_EJE IS NOT NULL
            AND R.FECHA_EJE IS NULL
            AND R.REVERSADO='N'
            and R.PERVENC='N'
            AND R.FECHA_ACUERDO IS NULL
              ";

        if (trim($proy)) {
            $sql = $sql . " AND I.ID_PROYECTO='$proy' ";
        }
        if (trim($sec) != "") {
            $sql = $sql . " AND I.ID_SECTOR=$sec ";
        }
        if (trim($zon) != "") {
            $sql = $sql . " AND I.ID_zona='$zon' ";
        }

        if (trim($oper) != "") {
            $sql = $sql . " AND R.USR_EJE='$oper' ";
        }

        $sql = $sql . " ORDER BY R.USR_EJE, ID_PROCESO ";

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

    public function getCortAsigGroupSectorByFech($fecha, $proyecto, $gerencia,$contratista)
    {
        $where = "";
        if (trim($gerencia) != "") {
            $where = " AND S.ID_GERENCIA='$gerencia'";
        }
        $sql = "select  I.ID_SECTOR, COUNT(1) CANTIDAD
              from sgc_tt_registro_cortes rc, SGC_tT_INMUEBLES I,
              SGC_TP_SECTORES S, SGC_TT_USUARIOS U
              where
              I.CODIGO_INM=RC.ID_INMUEBLE
              AND RC.USR_EJE=U.ID_USUARIO 
              AND I.ID_PROYECTO='$proyecto'
              AND
              S.ID_SECTOR=I.ID_SECTOR
              AND
              RC.USR_EJE is not null
              and RC.USUARIO_ASIGNADOR is not null
              and TO_CHAR(RC.FECHA_PLANIFICACION,'YYYY-MM-DD')='$fecha'
              AND U.CONTRATISTA='$contratista'
              $where
              group by (I.ID_SECTOR) ";
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

    public function getInspAsigGroupSectorByFech($fecha, $proyecto, $gerencia,$contratista)
    {
        $where = "";
        if (trim($gerencia) != "") {
            $where = " AND S.ID_GERENCIA='$gerencia'";
        }
        $sql = "SELECT I.ID_SECTOR, COUNT(1) CANTIDAD
                  from SGC_TT_INSPECCIONES_CORTES IC, SGC_tT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TT_USUARIOS U
                 where I.CODIGO_INM = IC.CODIGO_INM
                   AND I.ID_PROYECTO = '$proyecto'
                   AND S.ID_SECTOR = I.ID_SECTOR
                   AND U.CONTRATISTA='$contratista'
                    AND IC.USR_ASIG =U.ID_USUARIO
                   AND IC.FECHA_EJE is not null
                   and IC.USR_APER is not null
                   and TO_CHAR(IC.FECHA_PLANIFICACION, 'YYYY-MM-DD') = '$fecha'
                    $where
                 group by (I.ID_SECTOR)";
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

    public function getCortAsigGroupMedByFech($fecha, $proyecto, $gerencia,$contratista)
    {
        $where = "";
        if (trim($gerencia) != "") {
            " AND S.ID_GERENCIA='$gerencia'";
        }
        $sql = "select    NVL(ME.ESTADO_MED,'N') MEDIDOR, COUNT(1) CANTIDAD
              from sgc_tt_registro_cortes rc, SGC_tT_INMUEBLES I, sgc_tt_medidor_inmueble mi, sgc_tp_medidores me,
              SGC_TP_SECTORES S, SGC_TT_USUARIOS U
              where
              I.CODIGO_INM=RC.ID_INMUEBLE
              AND RC.USR_EJE=U.ID_USUARIO 
              and MI.COD_INMUEBLE(+)=I.CODIGO_INM
              AND ME.CODIGO_MED(+)=MI.COD_MEDIDOR
              AND MI.FECHA_BAJA (+)IS NULL
              AND I.ID_PROYECTO='$proyecto'
              and RC.USR_EJE is not null
              and RC.USUARIO_ASIGNADOR is not null
              AND U.CONTRATISTA='$contratista'
              and TO_CHAR(RC.FECHA_PLANIFICACION,'YYYY-MM-DD')='$fecha'
              AND S.ID_SECTOR=I.ID_SECTOR
              $where
              group by ME.ESTADO_MED";
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

    public function getInspAsigGroupMedByFech($fecha, $proyecto, $gerencia,$contratista)
    {
        $where = "";
        if (trim($gerencia) != "") {
            " AND S.ID_GERENCIA='$gerencia'";
        }
        $sql = "SELECT NVL(ME.ESTADO_MED, 'N') MEDIDOR, COUNT(1) CANTIDAD
                  from SGC_TT_INSPECCIONES_CORTES IC, SGC_TT_USUARIOS U,
                       SGC_tT_INMUEBLES           I,
                       sgc_tt_medidor_inmueble    mi,
                       sgc_tp_medidores           me,
                       SGC_TP_SECTORES            S
                 where I.CODIGO_INM = IC.CODIGO_INM
                   and MI.COD_INMUEBLE(+) = I.CODIGO_INM
                   AND ME.CODIGO_MED(+) = MI.COD_MEDIDOR
                   AND MI.FECHA_BAJA(+) IS NULL
                   AND I.ID_PROYECTO = '$proyecto'
                  AND U.CONTRATISTA='$contratista'
                    AND IC.USR_ASIG =U.ID_USUARIO
                   and IC.FECHA_EJE is not null
                   and IC.USR_APER is not null
                   and TO_CHAR(IC.FECHA_PLANIFICACION, 'YYYY-MM-DD') = '$fecha'
                   AND S.ID_SECTOR = I.ID_SECTOR
                    $where
                 group by ME.ESTADO_MED";
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

    public function getCantCortGroupSecRutByFecAsig($fecha, $asignado, $asignador, $proyecto, $gerencia,$contratista)
    {

        $where = "";
        if (trim($gerencia) != "") {
            $where = " AND S.ID_GERENCIA='$gerencia'";
        }
        $sql = "select I.ID_SECTOR, I.ID_RUTA, COUNT(1) CANTIDAD
              from sgc_tt_registro_cortes rc, SGC_tT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TT_USUARIOS U
                where
                I.CODIGO_INM=RC.ID_INMUEBLE AND
                S.ID_SECTOR=I.ID_SECTOR
                AND RC.USR_EJE=U.ID_USUARIO AND
                RC.USR_EJE is not null
                and RC.USUARIO_ASIGNADOR is not null
                AND I.ID_PROYECTO='$proyecto'
                and TO_CHAR(RC.FECHA_PLANIFICACION,'YYYY-MM-DD')='$fecha'
                AND RC.USR_EJE='$asignado'
                AND RC.USUARIO_ASIGNADOR='$asignador'
                AND U.CONTRATISTA='$contratista'
                $where
                group by (I.ID_SECTOR, I.ID_RUTA)";
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

    public function getCantInspGroupSecRutByFecAsig($fecha, $asignado, $asignador, $proyecto, $gerencia,$contratista)
    {

        $where = "";
        if (trim($gerencia) != "") {
            $where = " AND S.ID_GERENCIA='$gerencia'";
        }
        $sql = "SELECT I.ID_SECTOR, I.ID_RUTA, COUNT(1) CANTIDAD
                  from sgc_tt_inspecciones_cortes ic, SGC_tT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TT_USUARIOS U
                   where I.CODIGO_INM = IC.CODIGO_INM
                  AND U.CONTRATISTA='$contratista'
                    AND IC.USR_ASIG =U.ID_USUARIO
                   AND S.ID_SECTOR = I.ID_SECTOR
                   AND IC.FECHA_EJE is not null
                   and IC.USR_APER is not null
                   AND I.ID_PROYECTO = '$proyecto'
                   and TO_CHAR(IC.FECHA_PLANIFICACION, 'YYYY-MM-DD') = '$fecha'
                   AND IC.USR_ASIG = '$asignado'
                   AND IC.USR_APER = '$asignador'
                   $where
                 group by (I.ID_SECTOR, I.ID_RUTA)";
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

    public function getDatDiarReconexionFlexy($where, $sort, $start, $end, $fecIni, $fecFin)
    {

        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT  USR.NOM_USR NOMBRE,RC.USR_EJE CEDULA, INM.ID_SECTOR SECTOR, to_char(MIN(FECHA_EJE),'DD/MM/YYYY HH24:MI:SS') FECINI,to_char(MAX(FECHA_EJE),'DD/MM/YYYY HH24:MI:SS') FECMAX , INM.ID_RUTA RUTA, COUNT(INM.ID_SECTOR) CANTIDAD1 
                        FROM  SGC_TT_REGISTRO_RECONEXION RC, SGC_TT_INMUEBLES INM,SGC_TT_USUARIOS USR
                        WHERE RC.ID_INMUEBLE=INM.CODIGO_INM
                        AND USR.ID_USUARIO=RC.USR_EJE
                        AND USR.ID_USUARIO<>'1024525260'

                        AND FECHA_EJE BETWEEN  TO_DATE('$fecIni 00:00:00', 'YYYY-MM-DD hh24:mi:ss') AND TO_DATE('$fecFin 23:59:59', 'YYYY-MM-DD hh24:mi:ss')
                        and $where GROUP BY (INM.ID_SECTOR, INM.ID_RUTA,RC.USR_EJE,USR.NOM_USR)  $sort
                        )a WHERE rownum <= $start
                    ) WHERE rnum >= $end+1";
        $resultado = oci_parse(
            $this->_db, $sql
        );

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

    public function getCantDatDiarReconexionFlexy($fname, $tname, $where, $fecIni, $fecFin)
    {

        $sql = "SELECT COUNT(1)CANTIDAD FROM ( SELECT count($fname) CANTIDAD FROM $tname WHERE
                USR.ID_USUARIO=RC.USR_EJE
                AND USR.ID_USUARIO<>'1024525260'
                AND
                FECHA_EJE BETWEEN TO_DATE('$fecIni 00:00:00', 'YYYY-MM-DD hh24:mi:ss') AND TO_DATE('$fecFin 23:59:59', 'YYYY-MM-DD hh24:mi:ss') and $where
                GROUP BY (INM.ID_SECTOR, INM.ID_RUTA,RC.USR_EJE,USR.NOM_USR)) ";

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

    public function getDetRutCorByFecUsuRutFlexy($where, $sort, $start, $end)
    {
        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
        FROM (
        SELECT INM.CODIGO_INM, INM.CATASTRO,O.ALIAS,C.TIPO_CORTE,C.IMPO_CORTE,  MI.SERIAL, C.LECTURA, C.OBS_GENERALES, C.LATITUD,C.LONGITUD, INM.ID_PROCESO,INM.DIRECCION, to_char(C.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHA_EJECUCION, L.NOMBRE_CLI
        FROM SGC_TT_REGISTRO_CORTES C, SGC_TT_INMUEBLES INM,SGC_TT_MEDIDOR_INMUEBLE MI,
        SGC_TT_CONTRATOS O, SGC_TT_CLIENTES L, SGC_TT_USUARIOS U
        WHERE 
        O.CODIGO_INM(+)=INM.CODIGO_INM
        AND INM.CODIGO_INM=C.ID_INMUEBLE
    
        AND O.FECHA_FIN (+) IS NULL
        AND MI.COD_INMUEBLE(+)=INM.CODIGO_INM
        AND O.CODIGO_CLI = L.CODIGO_CLI
        AND MI.FECHA_BAJA (+)  IS NULL
        $where $sort
        )a WHERE rownum <= $start
        ) WHERE rnum >= $end+1";

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

    public function getCanDetRutCorByFecUsuRutFlexy($fname, $tname, $where)
    {

        $sql = "SELECT COUNT(INM.CODIGO_INM)CANTIDAD  FROM SGC_TT_REGISTRO_CORTES C, SGC_TT_INMUEBLES INM, SGC_TT_USUARIOS U
        WHERE INM.CODIGO_INM=C.ID_INMUEBLE $where";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);

        if ($banderas == true) {
            while (oci_fetch($resultado)) {
                $cantidad = oci_result($resultado, 'CANTIDAD');
            }oci_free_statement($resultado);
            oci_close($this->_db);
            return $cantidad;

        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getExisteFotoCorByInmFecUsu($inmueble, $fecini, $fecfin, $operario)
    {
        $fecini = substr($fecini, 0, 10);
        $fecfin = substr($fecfin, 0, 10);
        $sql    = "SELECT count(1) CANTIDAD FROM SGC_TT_FOTOS_CORTE FC,SGC_TT_REGISTRO_CORTES C
        WHERE C.ID_INMUEBLE=FC.ID_INMUEBLE
        AND C.USR_EJE='$operario'
        AND FC.ID_INMUEBLE='$inmueble'
        AND C.USR_EJE<>'1024525260'
        AND FC.FECHA BETWEEN TO_DATE('$fecini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'yyyy-mm-dd hh24:mi:ss' )  ";

        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            while (oci_fetch($resultado)) {
                $cantidad = oci_result($resultado, 'CANTIDAD');
            }oci_free_statement($resultado);
            if ($cantidad == 0) {
                $existe = false;
            } else {
                $existe = true;
            }

            oci_close($this->_db);
            return $existe;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getExiCoorCorByInmFecUsu($inmueble, $fechaini, $fechafin, $operario)
    {
        $sql = "SELECT COUNT(*) CANTIDAD FROM  SGC_TT_REGISTRO_CORTES C
        where
        C.USR_EJE='$operario'
        
        AND C.USR_EJE<>'1024525260'
        AND C.ID_INMUEBLE='$inmueble'
        AND C.FECHA_EJE BETWEEN TO_DATE('$fechaini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fechafin 23:59:59', 'yyyy-mm-dd hh24:mi:ss')
        AND C.LATITUD>0 ";

        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            while (oci_fetch($resultado)) {
                $cantidad = oci_result($resultado, 'CANTIDAD');
            }oci_free_statement($resultado);
            if ($cantidad == 0) {
                $existe = false;
            } else {
                $existe = true;
            }

            oci_close($this->_db);
            return $existe;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getUrlFotCorByInmFec($inmueble, $fecini, $fecfin)
    {
       echo $sql = "SELECT DISTINCT URL_FOTO  FROM SGC_TT_FOTOS_CORTE FC,SGC_TT_REGISTRO_CORTES C
        WHERE C.ID_INMUEBLE=FC.ID_INMUEBLE
        AND FC.ID_INMUEBLE='$inmueble'
        AND FC.FECHA BETWEEN TO_DATE('$fecini 00:00:00','dd/mm/yyyy hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'dd/mm/yyyy hh24:mi:ss')   ";

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

    public function getCantCortEfeByProyProcFecUsu($proyecto, $procesoIni, $procesoFin, $fecIni, $fecFin, $usuario)
    {
        $proyecto   = addslashes($proyecto);
        $procesoIni = addslashes($procesoIni);
        $procesoFin = addslashes($procesoFin);
        $fecIni     = addslashes($fecIni);
        $fecFin     = addslashes($fecFin);
        $usuario    = addslashes($usuario);

        $sql = "SELECT
                count(1) CANTIDAD
              FROM
                SGC_TT_INMUEBLES I,
                SGC_TT_REGISTRO_CORTES RC
              WHERE
                RC.ID_INMUEBLE=I.CODIGO_INM AND
                I.ID_PROYECTO='$proyecto' and
                I.ID_ESTADO='SS' AND
                I.ID_PROCESO>='$procesoIni' AND
                I.ID_PROCESO<='$procesoFin' AND
                RC.FECHA_EJE BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
                RC.USR_EJE='$usuario'";

        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getDatCortEfeByProyProcFecUsu($proyecto, $procesoIni, $procesoFin, $fecIni, $fecFin, $usuario, $cant)
    {
        $proyecto   = addslashes($proyecto);
        $procesoIni = addslashes($procesoIni);
        $procesoFin = addslashes($procesoFin);
        $fecIni     = addslashes($fecIni);
        $fecFin     = addslashes($fecFin);
        $usuario    = addslashes($usuario);
        $cant       = addslashes($cant);

        $sql = "SELECT * FROM(
                SELECT
                    I.CODIGO_INM,
                    I.ID_PROCESO,
                    I.CATASTRO,
                    NVL(C.ALIAS,CLI.NOMBRE_CLI) NOMBRE,
                    I.DIRECCION,
                    I.ID_ESTADO,
                    AC.DESC_ACTIVIDAD,
                    AC.ID_USO,
                    NVL(MI.COD_MEDIDOR,'N/A') COD_MEDIDOR ,
                    NVL(MI.SERIAL,'N/A') SERIAL ,
                    (SELECT COUNT(1) FROM SGC_TT_FACTURA F WHERE F.INMUEBLE=I.CODIGO_INM AND F.FACTURA_PAGADA='N' AND F.FEC_EXPEDICION IS NOT NULL AND F.FECHA_PAGO IS NULL) FACTPEND,
                    (SELECT NVL(SUM(F.TOTAL-F.TOTAL_PAGADO),0) FROM SGC_TT_FACTURA F WHERE F.INMUEBLE=I.CODIGO_INM AND F.FACTURA_PAGADA='N' AND F.FEC_EXPEDICION IS NOT NULL AND F.FECHA_PAGO IS NULL) MTOPEN,
                    RC.TIPO_CORTE,
                    NVL(RC.OBS_GENERALES,'N/A') OBS_GENERALES,
                    RC.LECTURA,
                    RC.USR_EJE,
                    U.NOM_USR||' '||U.APE_USR  NOMBREUSU
                FROM
                    SGC_TT_INMUEBLES I,
                    SGC_TT_CONTRATOS C,
                    SGC_TT_CLIENTES CLI,
                    SGC_TP_ACTIVIDADES AC,
                    SGC_TT_MEDIDOR_INMUEBLE MI,
                    SGC_TT_REGISTRO_CORTES RC,
                    SGC_TT_USUARIOS U
                WHERE
                    I.CODIGO_INM=C.CODIGO_INM(+) AND
                    C.CODIGO_CLI=CLI.CODIGO_CLI(+) AND
                    U.ID_USUARIO=RC.USR_EJE AND
                    C.FECHA_FIN (+) IS NULL AND
                    AC.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD AND
                    MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                    MI.FECHA_BAJA(+) IS NULL AND
                    RC.ID_INMUEBLE=I.CODIGO_INM AND
                    I.ID_PROYECTO='$proyecto' and
                    I.ID_ESTADO='SS' AND
                    I.ID_PROCESO>='$procesoIni' AND
                    I.ID_PROCESO<='$procesoFin' AND
                    RC.FECHA_EJE BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                    TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
                    AND RC.USR_EJE='$usuario'
                   ORDER BY dbms_random.value
                  )
                  WHERE  ROWNUM<=$cant
                  ORDER BY ID_PROCESO"
        ;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getDatCortPagRecByProFecOperProc($proyecto, $fechaIni, $fechaFin, $operario, $procesoIni, $procesoFin, $contratista)
    {
        $proyecto   = addslashes($proyecto);
        $fechaIni   = addslashes($fechaIni);
        $fechaFin   = addslashes($fechaFin);
        $operario   = addslashes($operario);
        $procesoIni = addslashes($procesoIni);
        $procesoFin = addslashes($procesoFin);

        $where = "";
        if (trim($operario) != "") {
            $where = " AND NVL(RC.USR_ULT_CORTE,RC.USR_EJE)='$operario'
                            AND RC.FECHA_EJE IS NOT NULL";
        }

        $sql = "SELECT
                ORE.FECHA_PAGO,
                RC.ORDEN,
                REC.ORDEN ORDENREC,
                ORE.INMUEBLE,

                 CASE(NVL(TO_CHAR(RC.FECHA_EJE),'0'))
                    WHEN '0' THEN

                    (CASE(NVL(TO_CHAR(RC.FEC_GEN_APER),'0'))
                    WHEN '0' THEN 'NO VISITADO'
                    ELSE 'VISITADO'
                    END)

                    ELSE TO_CHAR(NVL(RC.FECHA_EJE_ACT, RC.FECHA_EJE))END
                    FECHA_EJE,
                NVL(CON.ALIAS,CLI.NOMBRE_CLI) NOMBRE,
                INM.ID_SECTOR,
                INM.ID_RUTA,
                ORE.IMPORTE,

                CASE(NVL(TO_CHAR(RC.FECHA_EJE),'0'))
                WHEN '0' THEN 'NO EJECUTADO'
                ELSE ( NVL(NVL(TRIM(US2.NOM_USR||' '||US2.APE_USR),(TRIM(US.NOM_USR||' '||US.APE_USR) )),'NO EJECUTADO'))
                END OPERARIO,


                 CASE(NVL(TO_CHAR(RC.FECHA_EJE),'0'))
                WHEN '0' THEN 'ZZZZ'
                ELSE ( NVL(NVL(TRIM(US2.NOM_USR||' '||US2.APE_USR),(TRIM(US.NOM_USR||' '||US.APE_USR) )),'NO EJECUTADO'))
                END OPERARIO2,

                CASE(NVL(TO_CHAR(RC.FECHA_EJE),'0'))
                WHEN '0' THEN ''
                ELSE RC.TIPO_CORTE
                END TIPOCORTE,
                ore.IMPORTE IMPORTE_OTROREC,
                NVL(RC.OBS_ORIG_APER,'---') OBS_ORIG_APER,
                NVL(TRIM(US3.NOM_USR||' '||US3.APE_USR),'---') OPER_ORIGIN_APER,
                RC.FEC_GEN_APER


              FROM
                SGC_TT_OTROS_RECAUDOS ORE,
                SGC_TT_CONTRATOS CON,
                SGC_TT_CLIENTES CLI,
                SGC_TT_INMUEBLES INM ,
                SGC_TT_USUARIOS US,
                SGC_TT_USUARIOS US2,
                SGC_TT_USUARIOS US3,
                SGC_TT_REGISTRO_RECONEXION REC,
                sgc_tt_registro_cortes rc
              WHERE
                CON.CODIGO_INM(+)=INM.CODIGO_INM AND
                CON.FECHA_FIN(+) IS NULL AND
                CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                RC.ORDEN=REC.ORDEN(+) AND
                INM.CODIGO_INM=ORE.INMUEBLE AND
                INM.ID_PROYECTO='$proyecto' AND
                RC.ID_OTRO_RECAUDO(+)=ORE.CODIGO AND
                RC.USR_EJE=US.ID_USUARIO(+) AND
                RC.OPER_ORIGIN_APER=US3.ID_USUARIO(+) AND
                RC.USR_ULT_CORTE=US2.ID_USUARIO(+) AND
                NVL(US2.CONTRATISTA,NVL(US3.CONTRATISTA,US.CONTRATISTA))='$contratista' AND 
                ORE.ESTADO<>'I' AND
                ORE.CONCEPTO=20 AND
                ORE.FECHA_PAGO BETWEEN TO_DATE('$fechaIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')AND
                INM.ID_PROCESO>='$procesoIni' AND
                INM.ID_PROCESO<='$procesoFin'
                $where

              ORDER BY OPERARIO2 ASC"
        ;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getDatCortPagRecVisByProFecOperProc($proyecto, $fechaIni, $fechaFin, $operario, $procesoIni, $procesoFin)
    {
        $proyecto   = addslashes($proyecto);
        $fechaIni   = addslashes($fechaIni);
        $fechaFin   = addslashes($fechaFin);
        $operario   = addslashes($operario);
        $procesoIni = addslashes($procesoIni);
        $procesoFin = addslashes($procesoFin);

        $sql = "SELECT
                COUNT(1) CANTIDAD

              FROM
                SGC_TT_OTROS_RECAUDOS ORE,

                SGC_TT_INMUEBLES INM ,

                sgc_tt_registro_cortes rc
              WHERE


                INM.CODIGO_INM=ORE.INMUEBLE AND
                INM.ID_PROYECTO='$proyecto' AND
                RC.ID_OTRO_RECAUDO(+)=ORE.CODIGO AND
                ORE.ESTADO<>'I' AND
                ORE.CONCEPTO=20 AND
                ORE.FECHA_PAGO BETWEEN TO_DATE('$fechaIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')AND
                INM.ID_PROCESO>='$procesoIni' AND
                INM.ID_PROCESO<='$procesoFin' AND
                RC.FECHA_EJE IS NULL
                AND RC.FEC_GEN_APER IS NOT NULL
                AND RC.OPER_ORIGIN_APER='$operario'"
        ;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getCantCortPagRecGrouOperByProFecOperProc($proyecto, $fechaIni, $fechaFin, $operario, $procesoIni, $procesoFin, $contratista)
    {
        $proyecto   = addslashes($proyecto);
        $fechaIni   = addslashes($fechaIni);
        $fechaFin   = addslashes($fechaFin);
        $operario   = addslashes($operario);
        $procesoIni = addslashes($procesoIni);
        $procesoFin = addslashes($procesoFin);

        $where = "";
        if (trim($operario) != "") {
            $where = " AND NVL(RC.USR_ULT_CORTE,RC.USR_EJE)='$operario'
                            AND RC.FECHA_EJE IS NOT NULL";
        }

        $sql = "
             SELECT
                COUNT(1) CANTIDAD,
                CASE(NVL(TO_CHAR(RC.FECHA_EJE),'0'))
                WHEN '0' THEN (
                    CASE(NVL(TO_CHAR(RC.FEC_GEN_APER),'0'))
                    WHEN '0' THEN 'NO VISITADO'
                    ELSE 'VISITADO'
                    END
                )
                ELSE ( NVL(NVL(TRIM(US2.NOM_USR||' '||US2.APE_USR),(TRIM(US.NOM_USR||' '||US.APE_USR) )),'NO VISITADO'))
                END OPERARIO ,


                SUM(ore.IMPORTE) IMPORTE,
                CASE(NVL(TO_CHAR(RC.FECHA_EJE),'0'))
                WHEN '0' THEN (
                    CASE(NVL(TO_CHAR(RC.FEC_GEN_APER),'0'))
                    WHEN '0' THEN 'ZZZZAAAAA'
                    ELSE 'ZZA'
                    END
                )
                ELSE ( NVL(NVL(TRIM(US2.ID_USUARIO),(TRIM(US.ID_USUARIO) )),'ZZZZAAAAA'))
                END OPERARIO2

              FROM
                SGC_TT_OTROS_RECAUDOS ORE,
                SGC_TT_CONTRATOS CON,
                SGC_TT_CLIENTES CLI,
                SGC_TT_INMUEBLES INM ,
                SGC_TT_USUARIOS US,
                SGC_TT_USUARIOS US2,
                SGC_TT_REGISTRO_RECONEXION REC,
                sgc_tt_registro_cortes rc
              WHERE
                CON.CODIGO_INM(+)=INM.CODIGO_INM AND
                CON.FECHA_FIN(+) IS NULL AND
                CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                RC.ORDEN=REC.ORDEN(+) AND
                INM.CODIGO_INM=ORE.INMUEBLE AND
                INM.ID_PROYECTO='$proyecto' AND
                RC.ID_OTRO_RECAUDO(+)=ORE.CODIGO AND
                RC.USR_EJE=US.ID_USUARIO(+) AND
                RC.USR_ULT_CORTE=US2.ID_USUARIO(+) AND
             /*   (US.CONTRATISTA= OR US2.CONTRATISTA= ) AND*/
                NVL(US2.CONTRATISTA,US.CONTRATISTA)='$contratista' AND
                ORE.ESTADO<>'I' AND
                ORE.CONCEPTO=20 AND
                ORE.FECHA_PAGO BETWEEN TO_DATE('$fechaIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                TO_DATE('$fechaFin 23:59:59','YYYY-MM-DD HH24:MI:SS')AND
                INM.ID_PROCESO>='$procesoIni' AND
                INM.ID_PROCESO<='$procesoFin'
                $where

                  GROUP BY  CASE(NVL(TO_CHAR(RC.FECHA_EJE),'0'))
                WHEN '0' THEN (
                    CASE(NVL(TO_CHAR(RC.FEC_GEN_APER),'0'))
                    WHEN '0' THEN 'NO VISITADO'
                    ELSE 'VISITADO'
                    END
                )
                ELSE ( NVL(NVL(TRIM(US2.NOM_USR||' '||US2.APE_USR),(TRIM(US.NOM_USR||' '||US.APE_USR) )),'NO VISITADO'))
                END  ,

               CASE(NVL(TO_CHAR(RC.FECHA_EJE),'0'))
                WHEN '0' THEN (
                    CASE(NVL(TO_CHAR(RC.FEC_GEN_APER),'0'))
                    WHEN '0' THEN 'ZZZZAAAAA'
                    ELSE 'ZZA'
                    END
                )
                ELSE ( NVL(NVL(TRIM(US2.ID_USUARIO),(TRIM(US.ID_USUARIO) )),'ZZZZAAAAA'))
                END

                ORDER BY CASE(NVL(TO_CHAR(RC.FECHA_EJE),'0'))
                WHEN '0' THEN (
                    CASE(NVL(TO_CHAR(RC.FEC_GEN_APER),'0'))
                    WHEN '0' THEN 'ZZZZAAAAA'
                    ELSE 'ZZA'
                    END
                )
                ELSE ( NVL(NVL(TRIM(US2.ID_USUARIO),(TRIM(US.ID_USUARIO) )),'ZZZZAAAAA'))
                END ASC"
        ;
        $resultado = oci_parse($this->_db, $sql);
        $banderas  = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getInspByTipoServ($fecha, $sector)
    {
        $sql = "SELECT *
  from (select count(1) medido
          from sgc_tt_inspecciones_cortes ip, sgc_tt_inmuebles i
         where ip.codigo_inm = i.codigo_inm
           and i.id_proyecto = '$sector'
           and ip.fecha_eje BETWEEN TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD')
           and LAST_DAY(TO_DATE('$fecha' || '012359', 'YYYYMMDDHH24MI'))
           and i.facturar <> 'P'
           and ip.anulada = 'N'),
       (select count(1) no_medido
          from sgc_tt_inspecciones_cortes ip, sgc_tt_inmuebles i
         where ip.codigo_inm = i.codigo_inm
           and i.id_proyecto = '$sector'
           and ip.fecha_eje BETWEEN TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD')
           and LAST_DAY(TO_DATE('$fecha' || '012359', 'YYYYMMDDHH24MI'))
           and i.facturar = 'P'
           and ip.anulada = 'N'),
       (select sum(p.importe) Pago_medido
             FROM SGC_TT_PAGOS        P,
                   SGC_TP_CAJAS_PAGO   C,
                   SGC_TP_ENTIDAD_PAGO E,
                   SGC_TP_PUNTO_PAGO   R,
                   SGC_TT_INMUEBLES    I4
             WHERE C.ID_CAJA = P.ID_CAJA
               AND C.ID_PUNTO_PAGO = R.ID_PUNTO_PAGO
               AND R.ENTIDAD_COD = E.COD_ENTIDAD
               AND E.VALIDA_REPORTES = 'S'
               AND P.ESTADO = 'A'
               AND I4.CODIGO_INM = P.INM_CODIGO
               and i4.id_proyecto = '$sector'
               and i4.facturar <> 'P'
               AND P.FECHA_PAGO BETWEEN TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD') AND
                   LAST_DAY(TO_DATE('$fecha' || '012359', 'YYYYMMDDHH24MI'))
               and P.INM_CODIGO IN
                   (SELECT RC2.ID_INMUEBLE
                      FROM SGC_TT_REGISTRO_CORTES RC2, sgc_tt_inspecciones_cortes ic
                     WHERE I4.CODIGO_INM = RC2.ID_INMUEBLE
                       AND RC2.FECHA_EJE IS NOT NULL
                       AND TRIM(RC2.IMPO_CORTE) IS NULL
                       AND RC2.REVERSADO = 'N'
                       and rc2.orden = ic.orden_corte
                       AND ic.fecha_eje BETWEEN
                           TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD') AND
                           LAST_DAY(TO_DATE('$fecha' || '012359', 'YYYYMMDDHH24MI')))),
       (select sum(p1.importe) Pago_no_medido
            FROM SGC_TT_PAGOS        P1,
                   SGC_TP_CAJAS_PAGO   C1,
                   SGC_TP_ENTIDAD_PAGO E1,
                   SGC_TP_PUNTO_PAGO   R1,
                   SGC_TT_INMUEBLES    I5
             WHERE C1.ID_CAJA = P1.ID_CAJA
               AND C1.ID_PUNTO_PAGO = R1.ID_PUNTO_PAGO
               AND R1.ENTIDAD_COD = E1.COD_ENTIDAD
               AND E1.VALIDA_REPORTES = 'S'
               AND P1.ESTADO = 'A'
               AND I5.CODIGO_INM = P1.INM_CODIGO
               and i5.id_proyecto = '$sector'
               and i5.facturar = 'P'
               AND P1.FECHA_PAGO BETWEEN TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD') AND
                   LAST_DAY(TO_DATE('$fecha' || '012359', 'YYYYMMDDHH24MI'))
               and P1.INM_CODIGO IN
                   (SELECT RC2.ID_INMUEBLE
                      FROM SGC_TT_REGISTRO_CORTES RC2, sgc_tt_inspecciones_cortes ic
                     WHERE I5.CODIGO_INM = RC2.ID_INMUEBLE
                       AND RC2.FECHA_EJE IS NOT NULL
                       AND TRIM(RC2.IMPO_CORTE) IS NULL
                       AND RC2.REVERSADO = 'N'
                       and rc2.orden = ic.orden_corte
                       AND ic.fecha_eje BETWEEN
                           TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD') AND
                           LAST_DAY(TO_DATE('$fecha' || '012359', 'YYYYMMDDHH24MI'))))
";
        $result   = oci_parse($this->_db, $sql);
        $banderas = oci_execute($result);

        if ($banderas) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function getInspByZona($fecha, $sector, $zona)
    {
        $sql = "SELECT *
                    from (select count(1) medido
                            from sgc_tt_inspecciones_cortes ip,
                                 sgc_tt_inmuebles           i,
                                 sgc_tp_sectores            s
                           where ip.codigo_inm = i.codigo_inm
                             and i.id_proyecto = '$sector'
                             and ip.fecha_eje BETWEEN TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD')
                             and LAST_DAY(TO_DATE('$fecha' || '012359', 'YYYYMMDDHH24MI'))
                             and i.id_sector = s.id_sector
                             and s.id_gerencia = '$zona'
                             and i.facturar <> 'P'
                             AND ip.anulada = 'N'),
                         (select count(1) no_medido
                            from sgc_tt_inspecciones_cortes ip,
                                 sgc_tt_inmuebles           i,
                                 sgc_tp_sectores            s
                           where ip.codigo_inm = i.codigo_inm
                             and i.id_proyecto = '$sector'
                             and ip.fecha_eje BETWEEN TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD')
                             and LAST_DAY(TO_DATE('$fecha' || '012359', 'YYYYMMDDHH24MI'))
                             and i.id_sector = s.id_sector
                             and s.id_gerencia = '$zona'
                             and i.facturar = 'P'
                             and ip.anulada = 'N'),
                         (select sum(p.importe) Pago_medido
                             FROM SGC_TT_PAGOS        P,
                                   SGC_TP_CAJAS_PAGO   C,
                                   SGC_TP_ENTIDAD_PAGO E,
                                   SGC_TP_PUNTO_PAGO   R,
                                   SGC_TT_INMUEBLES    I,
                                   SGC_TP_SECTORES     S
                             WHERE C.ID_CAJA = P.ID_CAJA
                               AND C.ID_PUNTO_PAGO = R.ID_PUNTO_PAGO
                               AND R.ENTIDAD_COD = E.COD_ENTIDAD
                               AND E.VALIDA_REPORTES = 'S'
                               AND P.ESTADO = 'A'
                               AND I.CODIGO_INM = P.INM_CODIGO
                               and i.id_proyecto = '$sector'
                               --AND I.ID_SECTOR = 26
                              and i.facturar <> 'P'
                              and i.id_sector = s.id_sector
                              and s.id_gerencia = '$zona'
                               AND P.FECHA_PAGO BETWEEN TO_DATE($fecha || '01', 'YYYYMM' || 'DD')
                               AND LAST_DAY(TO_DATE('$fecha' || '012359', 'YYYYMMDDHH24MI'))
                               and P.INM_CODIGO IN
                                   (SELECT RC2.ID_INMUEBLE
                                      FROM SGC_TT_REGISTRO_CORTES RC2, sgc_tt_inspecciones_cortes ic
                                     WHERE I.CODIGO_INM = RC2.ID_INMUEBLE
                                       AND RC2.FECHA_EJE IS NOT NULL
                                       AND TRIM(RC2.IMPO_CORTE) IS NULL
                                       AND RC2.REVERSADO = 'N'
                                       AND ic.fecha_eje BETWEEN
                                           TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD') AND
                                           LAST_DAY(TO_DATE('$fecha' || '012359', 'YYYYMMDDHH24MI'))
                                       and rc2.orden = ic.orden_corte)),
                         (select sum(p1.importe) Pago_no_medido
                              FROM SGC_TT_PAGOS        P1,
                                   SGC_TP_CAJAS_PAGO   C1,
                                   SGC_TP_ENTIDAD_PAGO E1,
                                   SGC_TP_PUNTO_PAGO   R1,
                                   SGC_TT_INMUEBLES    I3,
                                   SGC_TP_SECTORES     S3
                             WHERE C1.ID_CAJA = P1.ID_CAJA
                               AND C1.ID_PUNTO_PAGO = R1.ID_PUNTO_PAGO
                               AND R1.ENTIDAD_COD = E1.COD_ENTIDAD
                               AND E1.VALIDA_REPORTES = 'S'
                               AND P1.ESTADO = 'A'
                               AND I3.CODIGO_INM = P1.INM_CODIGO
                               and i3.id_proyecto = '$sector'
                                  --AND I.ID_SECTOR = 26
                               and i3.facturar = 'P'
                               and i3.id_sector = s3.id_sector
                               and s3.id_gerencia = '$zona'
                               AND P1.FECHA_PAGO BETWEEN
                                   TO_DATE($fecha || '01', 'YYYYMM' || 'DD') AND
                                   LAST_DAY(TO_DATE('$fecha' || '012359', 'YYYYMMDDHH24MI'))
                               and P1.INM_CODIGO IN
                                   (SELECT RC2.ID_INMUEBLE
                                      FROM SGC_TT_REGISTRO_CORTES     RC2,
                                           sgc_tt_inspecciones_cortes ic
                                     WHERE I3.CODIGO_INM = RC2.ID_INMUEBLE
                                       AND RC2.FECHA_EJE IS NOT NULL
                                       AND TRIM(RC2.IMPO_CORTE) IS NULL
                                       AND RC2.REVERSADO = 'N'
                                       AND ic.fecha_eje BETWEEN
                                           TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD') AND
                                           LAST_DAY(TO_DATE('$fecha' || '012359',
                                                            'YYYYMMDDHH24MI'))
                                       and rc2.orden = ic.orden_corte)),
                           (select count(1) n_pagos_medido
                              FROM SGC_TT_PAGOS        P4,
                                   SGC_TP_CAJAS_PAGO   C4,
                                   SGC_TP_ENTIDAD_PAGO E4,
                                   SGC_TP_PUNTO_PAGO   R4,
                                   SGC_TT_INMUEBLES    I4,
                                   SGC_TP_SECTORES     S4
                             WHERE C4.ID_CAJA = P4.ID_CAJA
                               AND C4.ID_PUNTO_PAGO = R4.ID_PUNTO_PAGO
                               AND R4.ENTIDAD_COD = E4.COD_ENTIDAD
                               AND E4.VALIDA_REPORTES = 'S'
                               AND P4.ESTADO = 'A'
                               AND I4.CODIGO_INM = P4.INM_CODIGO
                               and i4.id_proyecto = '$sector'
                                  --AND I.ID_SECTOR = 26
                               and i4.facturar <> 'P'
                               and i4.id_sector = s4.id_sector
                               and s4.id_gerencia = '$zona'
                               AND P4.FECHA_PAGO BETWEEN
                                   TO_DATE($fecha || '01', 'YYYYMM' || 'DD') AND
                                   LAST_DAY(TO_DATE('$fecha' || '012359', 'YYYYMMDDHH24MI'))
                               and P4.INM_CODIGO IN
                                   (SELECT RC2.ID_INMUEBLE
                                      FROM SGC_TT_REGISTRO_CORTES     RC2,
                                           sgc_tt_inspecciones_cortes ic
                                     WHERE I4.CODIGO_INM = RC2.ID_INMUEBLE
                                       AND RC2.FECHA_EJE IS NOT NULL
                                       AND TRIM(RC2.IMPO_CORTE) IS NULL
                                       AND RC2.REVERSADO = 'N'
                                       AND ic.fecha_eje BETWEEN
                                           TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD') AND
                                           LAST_DAY(TO_DATE('$fecha' || '012359',
                                                            'YYYYMMDDHH24MI'))
                                       and rc2.orden = ic.orden_corte)),
                           (select count(1) n_pagos_no_medido
                              FROM SGC_TT_PAGOS        P5,
                                   SGC_TP_CAJAS_PAGO   C5,
                                   SGC_TP_ENTIDAD_PAGO E5,
                                   SGC_TP_PUNTO_PAGO   R5,
                                   SGC_TT_INMUEBLES    I5,
                                   SGC_TP_SECTORES     S5
                             WHERE C5.ID_CAJA = P5.ID_CAJA
                               AND C5.ID_PUNTO_PAGO = R5.ID_PUNTO_PAGO
                               AND R5.ENTIDAD_COD = E5.COD_ENTIDAD
                               AND E5.VALIDA_REPORTES = 'S'
                               AND P5.ESTADO = 'A'
                               AND I5.CODIGO_INM = P5.INM_CODIGO
                               and i5.id_proyecto = '$sector'
                                  --AND I.ID_SECTOR = 26
                               and i5.facturar = 'P'
                               and i5.id_sector = s5.id_sector
                               and s5.id_gerencia = '$zona'
                               AND P5.FECHA_PAGO BETWEEN
                                   TO_DATE($fecha || '01', 'YYYYMM' || 'DD') AND
                                   LAST_DAY(TO_DATE('$fecha' || '012359', 'YYYYMMDDHH24MI'))
                               and P5.INM_CODIGO IN
                                   (SELECT RC2.ID_INMUEBLE
                                      FROM SGC_TT_REGISTRO_CORTES     RC2,
                                           sgc_tt_inspecciones_cortes ic
                                     WHERE I5.CODIGO_INM = RC2.ID_INMUEBLE
                                       AND RC2.FECHA_EJE IS NOT NULL
                                       AND TRIM(RC2.IMPO_CORTE) IS NULL
                                       AND RC2.REVERSADO = 'N'
                                       AND ic.fecha_eje BETWEEN
                                           TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD') AND
                                           LAST_DAY(TO_DATE('$fecha' || '012359',
                                                            'YYYYMMDDHH24MI'))
                                       and rc2.orden = ic.orden_corte))";

        $result   = oci_parse($this->_db, $sql);
        $banderas = oci_execute($result);

        if ($banderas) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function getInspByZona2($fecha, $sector, $zona)
    {
        $sql = "SELECT *
                    from (select count(1) medido
                            from sgc_tt_inspecciones_cortes ip,
                                 sgc_tt_inmuebles           i,
                                 sgc_tp_sectores            s
                           where ip.codigo_inm = i.codigo_inm
                             and i.id_proyecto = '$sector'
                             and ip.fecha_eje BETWEEN TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD')
                             and  LAST_DAY(TO_DATE('$fecha' || '012359','YYYYMMDDHH24MI'))
                             and i.id_sector = s.id_sector
                             and s.id_gerencia = '$zona'
                             and i.facturar <> 'P'),
                         (select count(1) no_medido
                            from sgc_tt_inspecciones_cortes ip,
                                 sgc_tt_inmuebles           i,
                                 sgc_tp_sectores            s
                           where ip.codigo_inm = i.codigo_inm
                             and i.id_proyecto = '$sector'
                             and ip.fecha_eje BETWEEN TO_DATE('$fecha' || '01', 'YYYYMM' || 'DD')
                             and  LAST_DAY(TO_DATE('$fecha' || '012359','YYYYMMDDHH24MI'))
                             and i.id_sector = s.id_sector
                             and s.id_gerencia = '$zona'
                             and i.facturar = 'P')";

        $result   = oci_parse($this->_db, $sql);
        $banderas = oci_execute($result);

        if ($banderas) {
            oci_close($this->_db);
            return $result;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function getCorElimPorFec($proy, $fecIni, $fecFin, $tipo)
    {
        $sql = "SELECT RC.ID_INMUEBLE INMUEBLE,
                       CONCAT(I.ID_SECTOR, I.ID_RUTA) RUTA,
                       I.ID_ZONA ZONA,
                       ACT.ID_USO USO,
                       CT.ALIAS NOMBRE,
                       CLI.TELEFONO TELEFONO,
                       (SELECT SIM.COD_SERVICIO
                          FROM SGC_TT_SERVICIOS_INMUEBLES SIM
                         WHERE SIM.COD_INMUEBLE = I.CODIGO_INM
                           AND SIM.COD_SERVICIO in (1, 3)) SUMINISTRO,
                       TO_CHAR(RC.FECHA_REVERSION, 'YYYY-MM-DD') FECHA,
                       (SELECT ROUND(TR.VALOR_TARIFA) VALOR_TARIFA
                          FROM SGC_TP_TARIFAS_RECONEXION TR,
                               SGC_TT_MEDIDOR_INMUEBLE   MI,
                               SGC_TT_INMUEBLES          I2,
                               SGC_TP_ACTIVIDADES        AC,
                               SGC_TP_MEDIDORES          M
                         WHERE TR.CODIGO_DIAMETRO = I2.COD_DIAMETRO
                           AND I2.CODIGO_INM      = I.CODIGO_INM
                           AND MI.COD_INMUEBLE(+) = I2.CODIGO_INM
                           AND M.CODIGO_MED(+) = MI.COD_MEDIDOR
                           AND AC.SEC_ACTIVIDAD = I2.SEC_ACTIVIDAD
                           AND MI.FECHA_BAJA(+) IS NULL
                           AND TR.CODIGO_CALIBRE = NVL(MI.COD_CALIBRE, 0)
                           AND TR.CODIGO_USO = AC.ID_USO
                           AND TR.MEDIDOR = NVL(M.ESTADO_MED, 'N')
                           AND TR.PROYECTO = '$proy') IMPORTE,
                       RC.OBS_REVERSION OBSERVACION,
                       USR.NOM_USR USUARIO
                   FROM SGC_TT_REGISTRO_CORTES RC,
                   SGC_TT_INMUEBLES       I,
                   SGC_TT_CLIENTES CLI,
                   SGC_TT_CONTRATOS CT,
                   SGC_TT_USUARIOS USR,
                   SGC_TP_ACTIVIDADES     ACT
                 WHERE RC.ID_INMUEBLE = I.CODIGO_INM
                   AND RC.USR_REVERSION = USR.ID_USUARIO
                   AND I.CODIGO_INM = CT.CODIGO_INM
                   AND CT.CODIGO_CLI = CLI.CODIGO_CLI
                   AND I.SEC_ACTIVIDAD = ACT.SEC_ACTIVIDAD
                   AND CT.FECHA_FIN IS NULL
                   AND I.ID_PROYECTO = '$proy'";

        if ($tipo == 1) {
            $sql .= " AND I.FACTURAR <> 'P'";
        }
        if ($tipo == 2) {
            $sql .= " AND I.FACTURAR = 'P'";
        }
        if (!is_null($fecIni)) {
            $sql .= " AND RC.FECHA_REVERSION >= TO_DATE('$fecIni', 'YYYY-MM-DD')";
        }

        if (!is_null($fecFin)) {
            $sql .= " AND RC.FECHA_REVERSION <= TO_DATE('$fecFin ' || '23:59', 'YYYY-MM-DD HH24:SS')";
        }

        $sql .= "  AND RC.REVERSADO = 'S'
                 ORDER BY I.CODIGO_INM";

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
}