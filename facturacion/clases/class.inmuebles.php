<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/20/2015
 * Time: 12:14 PM
 */
include_once '../../clases/class.conexion.php';

class inmuebles extends ConexionClass
{
    public function __construct()
    {
        parent::__construct();
    }
////////////////// datos generales ///////////////////////

    public function datgen($where, $sort, $start, $end, $from)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT I.CODIGO_INM, I.ID_ZONA, U.DESC_URBANIZACION, I.DIRECCION, I.ID_TIPO_CLIENTE, I.ID_ESTADO, I.CATASTRO, I.ID_PROCESO, C.ID_CONTRATO, C.CODIGO_CLI,
                        C.ALIAS, L.NOMBRE_CLI, L.DOCUMENTO, L.TELEFONO, M.SERIAL, M.COD_CALIBRE, M.COD_EMPLAZAMIENTO, TO_CHAR(M.FECHA_INSTALACION,'DD/MM/YYYY')FECHA_INSTALACION,
                        M.METODO_SUMINISTRO, A.ID_USO, I.ID_PROYECTO, TO_CHAR(I.FEC_ALTA,'DD/MM/YYYY')FEC_ALTA, D.DESC_CALIBRE
                        FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES L, SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_ESTADOS_INMUEBLES E,
                        SGC_TP_GRUPOS G, SGC_TP_PROYECTOS P, SGC_TP_TIPO_CLIENTE T, SGC_TP_ACTIVIDADES A, SGC_TP_CALIBRES D $from
                        WHERE U.CONSEC_URB(+) = I.CONSEC_URB
                        AND C.CODIGO_INM(+) = I.CODIGO_INM
                        AND L.CODIGO_CLI(+) = C.CODIGO_CLI
                        AND M.COD_INMUEBLE(+) = I.CODIGO_INM
                        AND E.ID_ESTADO(+) = I.ID_ESTADO
                        AND P.ID_PROYECTO(+) = I.ID_PROYECTO
                        and C.FECHA_FIN (+) IS NULL
                        AND T.ID_TIPO_CLIENTE(+) = I.ID_TIPO_CLIENTE
                        AND G.COD_GRUPO(+) = L.COD_GRUPO
                        AND A.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD
                        AND M.FECHA_BAJA(+) IS NULL
                        AND D.COD_CALIBRE(+) = M.COD_CALIBRE
                        $where
                          $sort
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
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

////////////////////// cantidad datos/////////////////////////////////////////

    public function countRec($where, $from)
    {
        $sql = "SELECT COUNT(*) CANTIDAD
            FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES L, SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_ESTADOS_INMUEBLES E,
            SGC_TP_GRUPOS G, SGC_TP_PROYECTOS P, SGC_TP_TIPO_CLIENTE T, SGC_TP_ACTIVIDADES A, SGC_TP_CALIBRES D $from
            WHERE U.CONSEC_URB(+) = I.CONSEC_URB
            AND C.CODIGO_INM(+) = I.CODIGO_INM
            AND L.CODIGO_CLI(+) = C.CODIGO_CLI
            AND M.COD_INMUEBLE(+) = I.CODIGO_INM
            AND E.ID_ESTADO(+) = I.ID_ESTADO
            AND P.ID_PROYECTO(+) = I.ID_PROYECTO
            AND T.ID_TIPO_CLIENTE(+) = I.ID_TIPO_CLIENTE
            AND G.COD_GRUPO(+) = L.COD_GRUPO
            AND A.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD
            AND D.COD_CALIBRE(+) = M.COD_CALIBRE
            $where";
        //echo '<br>'.$sql.'<br>';
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

    /////// reconexion de inmueble

    public function tarifaRec($inm)
    {
        $sql = "
            SELECT
            TR.VALOR_TARIFA
            FROM SGC_TP_TARIFAS_RECONEXION TR, SGC_TT_INMUEBLES I, SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TP_ACTIVIDADES AC, SGC_TP_MEDIDORES M
            WHERE TR.CODIGO_DIAMETRO=I.COD_DIAMETRO
            AND I.CODIGO_INM=$inm
            AND MI.COD_INMUEBLE(+)=I.CODIGO_INM
            AND M.CODIGO_MED(+)=MI.COD_MEDIDOR
            AND AC.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD
            AND MI.FECHA_BAJA(+) IS NULL
            AND TR.CODIGO_CALIBRE=NVL(MI.COD_CALIBRE,0)
            AND TR.CODIGO_USO=AC.ID_USO
            AND TR.MEDIDOR=NVL(M.ESTADO_MED,'N')
            AND TR.PROYECTO=I.ID_PROYECTO
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

    public function SaldoFavor($inm)
    {
        $sql = "
            select sum(saldo) saldo from(

SELECT SUM(IMPORTE-VALOR_APLICADO)SALDO
            FROM SGC_TT_SALDO_FAVOR
            WHERE INM_CODIGO='$inm'
            AND ESTADO = 'A'

union

SELECT SUM(ORE.IMPORTE -ORE.APLICADO)SALDO
            FROM sgc_Tt_otros_recaudos ore
            WHERE ORE.INMUEBLE='$inm'
            AND ESTADO = 'A')";

       // echo $sql;

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

    public function DiferidoDeud($inm)
    {
        $sql = "
            SELECT SUM(D.VALOR_DIFERIDO-D.VALOR_PAGADO) DIFERIDO FROM SGC_TT_DIFERIDOS D
            WHERE D.INMUEBLE= '$inm'
                AND D.ACTIVO='S'";

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

//////////////////////datos generales inmuebles/////////////////////////////////////////

    public function datosInmueble($where)
    {
        $sql = "SELECT I.ID_ZONA, I.ID_PROYECTO PROYECTO, U.DESC_URBANIZACION, I.DIRECCION, TO_CHAR(I.FEC_ALTA,'DD/MM/YYYY')FEC_ALTA,  I.ID_ESTADO, I.CATASTRO, I.ID_PROCESO, C.CODIGO_CLI, C.ID_CONTRATO,
            C.ALIAS, L.DOCUMENTO, I.TELEFONO, M.SERIAL, C.EMAIL,L.COD_GRUPO, SUBSTR(C.ALIAS,0,47) NOMBRE, L.TELEFONO TEL2,
            (SELECT COUNT(1) FROM SGC_TT_FACTURA F
                WHERE F.INMUEBLE=I.CODIGO_INM  AND
                F.TOTAL>0
                AND F.TOTAL_PAGADO<F.TOTAL
                AND F.FECHA_PAGO IS NULL
                AND F.FACTURA_PAGADA='N'
                and F.FEC_EXPEDICION IS NOT NULL
            ) FACTURAS,

            (SELECT SUM(F.TOTAL-F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO ) FROM SGC_TT_FACTURA F
                WHERE F.INMUEBLE=I.CODIGO_INM  AND
                F.TOTAL>0
                AND F.TOTAL_PAGADO<F.TOTAL
                AND F.FECHA_PAGO IS NULL
                AND F.FACTURA_PAGADA='N'
                and F.FEC_EXPEDICION IS NOT NULL
            ) DEUDA

            FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES L, SGC_TT_MEDIDOR_INMUEBLE M
            WHERE U.CONSEC_URB(+) = I.CONSEC_URB
            AND M.COD_INMUEBLE(+) = I.CODIGO_INM
            AND C.CODIGO_INM(+) = I.CODIGO_INM
            AND L.CODIGO_CLI(+) = C.CODIGO_CLI
            AND C.FECHA_FIN(+) IS NULL
            $where";
        //echo $sql;
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

//////////////////////datos Medidor/////////////////////////////////////////

    public function datosMedidor($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT I.DESC_MED, E.DESC_EMPLAZAMIENTO, C.DESC_CALIBRE, M.SERIAL, TO_CHAR(M.FECHA_INSTALACION,'DD/MM/YYYY')FECHA, S.DESC_SUMINISTRO, ME.DESCRIPCION,
                        M.LECTURA_INSTALACION, M.OBS_INS
                        FROM SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_MEDIDORES I, SGC_TP_CALIBRES C, SGC_TP_EMPLAZAMIENTO E, SGC_TP_ESTADOS_MEDIDOR ME, SGC_TP_MET_SUMINISTRO S
                        WHERE M.COD_MEDIDOR = I.CODIGO_MED
                        AND C.COD_CALIBRE = M.COD_CALIBRE
                        AND E.COD_EMPLAZAMIENTO = M.COD_EMPLAZAMIENTO
                        AND ME.CODIGO = M.ESTADO_MEDIDOR
                        AND S.COD_SUMINISTRO = M.METODO_SUMINISTRO
                        $where
                          $sort
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
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

    //////////////////////datos Lectura/////////////////////////////////////////

    public function datosLectura($where, $sort, $start, $end, $codinmueble)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT R.PERIODO, R.LECTURA_ACTUAL, TO_CHAR(R.FECHA_LECTURA_ORI,'DD/MM/YYYY')FECLEC, R.OBSERVACION_ACTUAL, (U.NOM_USR||' '||U.APE_USR)LECTOR, R.CONSUMO_ACT CONSUMO
                        FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TT_USUARIOS U
                        WHERE R.COD_LECTOR = U.ID_USUARIO AND R.COD_INMUEBLE = '$codinmueble' $where
                          $sort
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
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

    //////////////////////datos Servicios/////////////////////////////////////////

    public function datosServicios($where, $sort, $start, $end)
    {
        $sql = "SELECT *
                FROM (
                    SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
                    FROM (
                        SELECT S.COD_SERVICIO, O.DESC_SERVICIO, T.DESC_TARIFA, S.UNIDADES_TOT, S.UNIDADES_HAB, S.UNIDADES_DESH, S.CUPO_BASICO, S.PROMEDIO, S.CONSUMO_MINIMO,
                        C.DESC_CALCULO
                        FROM SGC_TT_SERVICIOS_INMUEBLES S, SGC_TP_SERVICIOS O, SGC_TP_TARIFAS T, SGC_TP_CALCULO C
                        WHERE S.COD_SERVICIO = O.COD_SERVICIO
                        AND T.CONSEC_TARIFA(+) = S.CONSEC_TARIFA
                        AND S.CALCULO = C.COD_CALCULO
                        $where
                          $sort
                        )a WHERE  ROWNUM<=$start
                    ) WHERE rnum >= $end+1";
        //echo $sql;
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

    public function getEstadosInmuebles($proyecto,$estado){
        $sql = "SELECT *
from (SELECT  I.ID_PROYECTO,U.DESC_USO USO, S.ID_GERENCIA
      from SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A,
           SGC_TP_SECTORES S,
           SGC_TP_ESTADOS_INMUEBLES EI,
           SGC_TP_USOS U
      WHERE I.ID_SECTOR=S.ID_SECTOR
        AND A.ID_USO=U.ID_USO
        AND I.SEC_ACTIVIDAD=A.SEC_ACTIVIDAD
        AND I.ID_ESTADO =  EI.ID_ESTADO
        AND EI.INDICADOR_ESTADO='A'
     ) pivot(count(ID_GERENCIA) for ID_GERENCIA in
('E', 'N'))
order by ID_PROYECTO";

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
