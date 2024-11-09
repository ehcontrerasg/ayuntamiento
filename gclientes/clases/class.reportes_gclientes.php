<?php
include_once '../../clases/class.conexion.php';
class ReportesGerencia extends ConexionClass
{
    private $id_proyecto;
    private $id_fecini;
    private $id_fecfin;

    public function __construct()
    {
        parent::__construct();
        $this->id_proyecto = "";
        $this->id_fecini   = "";
        $this->id_fecfin   = "";
    }

    public function seleccionaAcueducto()
    {
        $sql = "SELECT ID_PROYECTO, SIGLA_PROYECTO
        FROM SGC_TP_PROYECTOS
        ORDER BY SIGLA_PROYECTO";
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

    public function seleccionaGrupo()
    {
        $sql = "SELECT G.COD_GRUPO, G.DESC_GRUPO
        FROM SGC_TP_GRUPOS G
        WHERE G.ACTIVO = 'S'
        ORDER BY G.DESC_GRUPO";
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

    /////////////////////CONSULTAS REPORTES DEUDA ACTUAL OFICIALES

    public function DeudaOficialVencidas($proyecto, $periodo, $periodof)
    {
        $sql = "SELECT C.GRUPO, G.DESC_GRUPO, SUM(F.TOTAL) FACTURADO, COUNT(F.CONSEC_FACTURA) CANTIDADFACVE
        FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL, SGC_TP_GRUPOS G, SGC_TT_FACTURA F
        WHERE C.GRUPO = G.COD_GRUPO
        AND I.CODIGO_INM = C.CODIGO_INM
        AND C.CODIGO_CLI = CL.CODIGO_CLI
        AND F.INMUEBLE = I.CODIGO_INM
        AND C.GRUPO IS NOT NULL
        AND F.FACTURA_PAGADA IN ('N')
        AND C.GRUPO > 0
        AND C.FECHA_FIN IS NULL
        AND F.FEC_EXPEDICION IS NOT NULL
        AND F.FEC_VCTO < SYSDATE
        --AND F.PERIODO<>$periodo
        AND F.PERIODO NOT BETWEEN $periodo AND $periodof
        AND I.ID_PROYECTO = '$proyecto'
        AND ACTIVO = 'S' AND OFICIAL = 'S'
        GROUP BY C.GRUPO, G.DESC_GRUPO
        ORDER BY 1 ASC";
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

    public function DeudaOficialPeriodo($proyecto, $periodo, $periodof, $cod_grupo)
    {
        $sql = "SELECT SUM(F.TOTAL) FACTURADOPER
        FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL, SGC_TP_GRUPOS G, SGC_TT_FACTURA F
        WHERE C.GRUPO = G.COD_GRUPO
        AND I.CODIGO_INM = C.CODIGO_INM
        AND C.CODIGO_CLI = CL.CODIGO_CLI
        AND F.INMUEBLE = I.CODIGO_INM
        AND C.GRUPO IS NOT NULL
        AND F.FACTURA_PAGADA IN ('N')
        AND C.GRUPO > 0
        AND C.FECHA_FIN IS NULL
        AND F.PERIODO BETWEEN $periodo AND $periodof
        --AND F.PERIODO = $periodo
        AND C.GRUPO = $cod_grupo
        AND I.ID_PROYECTO = '$proyecto'
        AND ACTIVO = 'S' AND OFICIAL = 'S'
        ORDER BY 1 ASC";
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

    /////////////////////CONSULTAS REPORTES GRANDES CLIENTES
    //    /// SELECT I.ID_ZONA, I.CODIGO_INM, C.ALIAS, SUM(DF.UNIDADES)METROS,
    //SUM(DF2.UNIDADES)METROSANT,
    //A.ID_USO
    //FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL, SGC_TT_DETALLE_FACTURA DF,
    //SGC_TT_DETALLE_FACTURA DF2,
    //SGC_TP_ACTIVIDADES A
    //WHERE I.CODIGO_INM = C.CODIGO_INM
    //AND C.CODIGO_CLI = CL.CODIGO_CLI
    //AND I.CODIGO_INM = DF.COD_INMUEBLE
    //AND DF.COD_INMUEBLE = DF2.COD_INMUEBLE
    //AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
    //AND C.FECHA_FIN IS NULL
    //AND I.ID_TIPO_CLIENTE = 'GC'
    //AND I.ID_PROYECTO = '$proyecto'
    //AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
    //AND DF.PERIODO = $periodo
    //AND DF2.PERIODO = TO_CHAR(ADD_MONTHS(to_date('$periodo','YYYYMM'),-1),'YYYYMM')
    //AND DF.CONCEPTO IN (1,3)
    //AND DF2.CONCEPTO =DF.CONCEPTO
    //GROUP BY I.ID_ZONA,I.CODIGO_INM, C.ALIAS, CL.NOMBRE_CLI, A.ID_USO
    //ORDER BY CODIGO_INM
    ///
    ///
    ///

    public function RepGrandesClientes($proyecto, $periodo,$estado)
    {

        $where = "";
        if($estado!=""){
            $where = "AND I.ID_ESTADO IN ('CC','CT','CB','CK','DH')";
        }else{
            $where = "AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK','DH')";
        }
        //Cuando seleccione 'deshabitados o cancelados' va a poner 'AND I.ID_ESTADO IN ('CC','CT','CB','CK','DH')'
        // Cuando no lo seleccione va a poner: AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK','DH')


        $sql = "SELECT I.ID_ZONA,
                DECODE(DF.CONCEPTO, 1, 'Red', 3, 'Pozo') SUMINISTRO,
                I.CODIGO_INM,
                C.ALIAS,
                SUM(DF.UNIDADES) METROS,
                A.ID_USO,
                NVL(MI.SERIAL, 'Sin medidor') SERIAL,
                NVL(CAL.DESC_CALIBRE, 'N/A') DIAMETRO
        FROM SGC_TT_INMUEBLES        I,
                SGC_TT_CONTRATOS        C,
                SGC_TT_CLIENTES         CL,
                SGC_TT_DETALLE_FACTURA  DF,
                SGC_TP_ACTIVIDADES      A,
                SGC_TT_MEDIDOR_INMUEBLE MI,
                SGC_TP_CALIBRES         CAL
        WHERE I.CODIGO_INM = C.CODIGO_INM
            AND C.CODIGO_CLI = CL.CODIGO_CLI
            AND I.CODIGO_INM = DF.COD_INMUEBLE
            AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
            AND C.FECHA_FIN IS NULL
            AND I.ID_TIPO_CLIENTE = 'GC'
            AND I.ID_PROYECTO = '$proyecto'
            ".$where."
            AND DF.PERIODO = $periodo
            AND DF.CONCEPTO IN (1, 3)
            AND DF.UNIDADES > 0
            AND A.ID_USO <> 'O'
            AND I.CODIGO_INM = MI.COD_INMUEBLE(+)
            AND MI.FECHA_BAJA(+) IS NULL
            AND CAL.COD_CALIBRE(+) = MI.COD_CALIBRE
        GROUP BY I.ID_ZONA,
                DF.CONCEPTO,
                I.CODIGO_INM,
                C.ALIAS,
                CL.NOMBRE_CLI,
                A.ID_USO,
                MI.SERIAL,
                CAL.DESC_CALIBRE
        ORDER BY CODIGO_INM";

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

    public function RepGrandesClientesFacturadoPeriodo($cod_inm, $periodo)
    {
        $sql = "SELECT SUM(F.TOTAL)FACTURADO, case when (sum(f.total) <> sum(f.total_ori)) then sum(f.total_ori) else 0 end FACT_ORI
        FROM  SGC_TT_FACTURA F
        WHERE F.PERIODO = $periodo
        AND F.INMUEBLE = $cod_inm ";
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

    public function RepGrandesClientesFacturadoPeriodoAnt($cod_inm, $periodo)
    {
        $sql = "SELECT SUM(F.TOTAL)FACTURADO
        FROM  SGC_TT_FACTURA F
        WHERE F.PERIODO = TO_CHAR(ADD_MONTHS(TO_DATE($periodo,'YYYYMM'),-1),'YYYYMM')
        AND F.INMUEBLE = $cod_inm ";
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

    public function RepGrandesClientesFacturadoDeuda($cod_inm, $periodo)
    {
        $sql = "SELECT SUM(F.TOTAL - F.TOTAL_PAGADO + F.TOTAL_DEBITO - F.TOTAL_CREDITO) FACTURADODEU
                FROM SGC_TT_FACTURA F
                WHERE F.PERIODO < $periodo
                    AND F.FACTURA_PAGADA = 'N'
                    AND F.INMUEBLE = $cod_inm";

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

    public function RepGrandesClientesFacturadoDeudaTotal($cod_inm, $periodo)
    {
        $sql = "SELECT SUM(F.TOTAL - F.TOTAL_PAGADO + F.TOTAL_DEBITO - F.TOTAL_CREDITO)DEUDATOT
        FROM SGC_TT_FACTURA F
        WHERE F.INMUEBLE = $cod_inm
        AND F.FACTURA_PAGADA = 'N'
        AND F.PERIODO <= $periodo";

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

    public function RepGrandesClientesRecaudo($cod_inm, $periodo)
    {
        $sql = "SELECT NVL(SUM(P.IMPORTE),0)IMPORTE
        FROM SGC_TT_PAGOS P
        WHERE P.INM_CODIGO = $cod_inm AND
        P.ESTADO<>'I'
        AND SUBSTR (FECIND,0,6) = $periodo";
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

    /////////////////////CONSULTAS REPORTES OFICIALES Y SIN FIN DE LUCRO

    public function RepOficialSinFinDeLucro($proyecto, $periodo, $perfin, $grupo, $zona, $documento)
    {
        if($grupo <> ''){
            $cmplsql = "C.GRUPO = $grupo AND";
        }
        if($zona <> ''){
            $cmplsqlx = "I.ID_ZONA = '$zona' AND";
        }
        if($documento <> ''){
            $cmplsqlz = "CL.DOCUMENTO = '$documento' AND";
        }
        $sql = "
            SELECT
              I.CODIGO_INM
              ,I.ID_ZONA
              ,C.ALIAS
              ,SUM(DF.UNIDADES)METROS
              ,A.ID_USO
            FROM
              SGC_TT_INMUEBLES I
              ,SGC_TT_CONTRATOS C
              ,SGC_TT_CLIENTES CL
              ,SGC_TT_DETALLE_FACTURA DF
              ,SGC_TT_FACTURA F
              ,SGC_TP_ACTIVIDADES A
            WHERE
              I.CODIGO_INM = C.CODIGO_INM(+) AND
              C.CODIGO_CLI = CL.CODIGO_CLI(+) AND
              I.CODIGO_INM = F.INMUEBLE(+) AND
              F.CONSEC_FACTURA = DF.FACTURA(+) AND
              A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD AND
              C.FECHA_FIN(+) IS NULL AND
              A.ID_USO IN ('O') AND
              I.ID_PROYECTO = '$proyecto' AND
              DF.PERIODO (+) BETWEEN $periodo  AND $perfin AND
                  $cmplsql
                  $cmplsqlx
                  $cmplsqlz
              DF.CONCEPTO (+)IN (1,3)
            GROUP BY
              I.CODIGO_INM, I.ID_ZONA, C.ALIAS, CL.NOMBRE_CLI, A.ID_USO
            ORDER BY
              CODIGO_INM
        ";
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

    public function RepOficialSinFinDeLucroFacturadoPeriodo($cod_inm, $periodo)
    {
        $sql = "SELECT SUM(F.TOTAL)FACTURADO
        FROM  SGC_TT_FACTURA F
        WHERE F.PERIODO = $periodo
        AND F.INMUEBLE = $cod_inm ";
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

    public function RepOficialSinFinDeLucroFacturadoDeuda($cod_inm, $periodo)
    {
        $sql = "SELECT SUM(F.TOTAL - F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO)FACTURADODEU
        FROM  SGC_TT_FACTURA F
        WHERE
        F.PERIODO <= $periodo
        AND F.FACTURA_PAGADA = 'N'
        AND F.INMUEBLE = $cod_inm";
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

    public function RepOficialSinFinDeLucroFacturadoDeudaTotal($cod_inm, $periodo)
    {
        $sql = "SELECT SUM(F.TOTAL - F.TOTAL_PAGADO+F.TOTAL_DEBITO-F.TOTAL_CREDITO)DEUDATOT
        FROM  SGC_TT_FACTURA F
        WHERE
        F.PERIODO <= $periodo
        AND F.FACTURA_PAGADA = 'N'
        AND F.INMUEBLE = $cod_inm";
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

    public function RepOficialSinFinDeLucroRecaudo($cod_inm, $periodo)
    {
        $sql = "SELECT NVL(SUM(P.IMPORTE),0)IMPORTE
        FROM SGC_TT_PAGOS P
        WHERE P.INM_CODIGO = $cod_inm
        AND SUBSTR (FECIND,0,6) = $periodo
        AND ESTADO IN 'A'";
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

    public function RepOficialSinFinDeLucroFactPend($cod_inm)
    {
        $sql = "SELECT COUNT(1) FACTPEN
                  FROM SGC_TT_FACTURA F
                 WHERE F.INMUEBLE = $cod_inm
                   AND F.FACTURA_PAGADA = 'N'
                   AND F.TOTAL > 0";
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

    public function RepOficialSinFinDeLucroGrupoInm($cod_inm)
    {
        $sql = "SELECT G.DESC_GRUPO GRUPO
                  FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TP_GRUPOS G
                 WHERE I.CODIGO_INM = C.CODIGO_INM
                   AND I.CODIGO_INM = $cod_inm
                   AND G.COD_GRUPO = C.GRUPO";
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
    public function RepOficialSinFinDeLucroMed($cod_inm)
    {
        $sql = "
        SELECT I.CODIGO_INM, NVL(MM.ESTADO_MED, 'N') MEDIDO
            FROM SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TT_INMUEBLES I, SGC_TP_MEDIDORES MM
        WHERE MI.COD_INMUEBLE(+) = I.CODIGO_INM
            AND MI.FECHA_BAJA(+) IS NULL
            AND MM.CODIGO_MED(+) = MI.COD_MEDIDOR
            AND I.CODIGO_INM = $cod_inm";
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
    /////////////////////CONSULTAS REPORTES RECAUDO GRANDES CLIENTES

    public function RepRecaudoGrandesClientes($proyecto, $fechaini, $fechafin, $uso = '')
    {
        $where = '';
        if( $uso != '')
            $where = " AND ACT.ID_USO = '$uso'";

        $sql = "SELECT I.CODIGO_INM, I.ID_ESTADO, I.ID_PROCESO, I.CATASTRO, C.ALIAS, I.DIRECCION, U.DESC_URBANIZACION, SUM(M.VALOR) IMPORTE, MAX(P.ID_PAGO) PAGO,
       MAX(TO_CHAR(P.FECHA_PAGO,'DD/MM/YYYY HH24:MI:SS')) FECPAGO, F.DESCRIPCION FORMAPAGO, USO.DESC_USO
        FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TP_URBANIZACIONES U, SGC_TT_PAGOS P, SGC_TP_ACTIVIDADES ACT, SGC_TP_USOS USO, SGC_TT_MEDIOS_PAGO M, SGC_TP_FORMA_PAGO F
        WHERE I.CODIGO_INM = C.CODIGO_INM(+) AND
      M.ID_PAGO = P.ID_PAGO AND
        I.CONSEC_URB = U.CONSEC_URB AND
        I.CODIGO_INM = P.INM_CODIGO AND
        F.CODIGO = M.ID_FORM_PAGO AND
        I.ID_PROYECTO = '$proyecto' AND
        I.ID_TIPO_CLIENTE = 'GC' AND
        C.FECHA_FIN IS NULL
          AND P.ESTADO NOT IN ('I')
          AND P.FECHA_PAGO BETWEEN TO_DATE ('$fechaini 00:00:00','YYYY-MM-DD HH24:MI:SS')
                  AND TO_DATE ('$fechafin 23:59:59','YYYY-MM-DD HH24:MI:SS')
          AND ACT.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
          AND USO.ID_USO =  ACT.ID_USO
          ".$where."
        GROUP BY I.CODIGO_INM, I.ID_ESTADO, I.ID_PROCESO, I.CATASTRO, C.ALIAS, I.DIRECCION, U.DESC_URBANIZACION, F.DESCRIPCION, USO.DESC_USO
        ORDER BY 1 ASC";

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

    public function RepCuePorCob($proyecto, $fecha)
    {
        $sql = "
            SELECT CODIGO_INM, NOMBRE, FAC_PENDIENTE, VAL_PENDIENTE, FAC_PEN_MORA, VAL_PEN_MORA, FAC_PEN_S_MORA, VAL_PEN_S_MORA
            FROM SGC_TH_DEUDA_X_COBRAR
            WHERE ID_PROYECTO = '$proyecto'
            AND FECHA_REGISTRO BETWEEN TO_DATE('$fecha 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$fecha 23:59:59','YYYY-MM-DD HH24:MI:SS')
            ORDER BY FAC_PENDIENTE DESC
        ";
       // echo $sql;
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
    
    //Reporte Impresion Recibos Grandes Clientes
    public function RecGranClientes($proyecto, $cod_inm='', $periodoInicial='', $periodoFinal='', $grupo='', $documento='')
    {
        $where = '';
        if ($grupo != '') {
            $where .= " AND C.GRUPO = '$grupo' ";
        }
        if($periodoInicial != '' && $periodoFinal != ''){
            $where .= " AND SUBSTR(FECIND, 0, 6) >= '$periodoInicial' AND SUBSTR(FECIND, 0, 6) <= '$periodoFinal'";
        }
        if($documento != ''){
            $where .= " AND CL.DOCUMENTO = '$documento'";
        }
        if($cod_inm != ''){
            $where .= "AND P.INM_CODIGO = '$cod_inm'";
        }

        $sql = "
        SELECT P.ID_PAGO    ID_PAGO,
               P.ACUEDUCTO  PROYECTO,
               P.INM_CODIGO CODIGO_INM
            FROM SGC_TT_PAGOS P, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL
           WHERE C.CODIGO_INM = P.INM_CODIGO
            AND CL.CODIGO_CLI = C.CODIGO_CLI
            AND P.ESTADO <> 'I'
            AND P.ACUEDUCTO = '$proyecto'
            AND C.FECHA_FIN IS NULL
            $where 
        ";

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
