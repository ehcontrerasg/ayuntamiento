<?php
if (file_exists('../../clases/class.conexion.php')) {
    require_once '../../clases/class.conexion.php';}

class Reportes extends ConexionClass
{
    private $id_proyecto;
    private $id_secini;
    private $id_secfin;
    private $id_rutini;
    private $id_rutfin;
    private $id_fecini;
    private $id_fecfin;

    public function __construct()
    {
        parent::__construct();
        $this->id_proyecto = "";
        $this->id_secini   = "";
        $this->id_secfin   = "";
        $this->id_rutini   = "";
        $this->id_rutfin   = "";
        $this->id_fecini   = "";
        $this->id_fecfin   = "";
    }

    public function setidproyecto($valor)
    {
        $this->id_proyecto = $valor;
    }

    public function setidsectorini($valor)
    {
        $this->id_secini = $valor;
    }

    public function setidsectorfin($valor)
    {
        $this->id_secfin = $valor;
    }

    public function setidrutaini($valor)
    {
        $this->id_rutini = $valor;
    }

    public function setidrutafin($valor)
    {
        $this->id_rutfin = $valor;
    }

    public function setidfechaini($valor)
    {
        $this->id_fecini = $valor;
    }

    public function setidfechafin($valor)
    {
        $this->id_fecfin = $valor;
    }

    /*public function getcodresult(){
    return $this->codresult;
    }

    public function getmsgresult(){
    return $this->msgresult;
    }*/

    public function obtenerProyecto($codUser)
    {
         $sql = "SELECT
                PR.ID_PROYECTO CODIGO,
                PR.SIGLA_PROYECTO DESCRIPCION
              FROM
                SGC_TP_PROYECTOS PR,
                SGC_TT_PERMISOS_USUARIO PU
              WHERE
                PR.ID_PROYECTO = PU.ID_PROYECTO AND
                PU.ID_USUARIO = '$codUser'
              ORDER BY 2  ";
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

    public function seleccionaAcueducto()
    {
        $resultado = oci_parse($this->_db, "SELECT ID_PROYECTO, SIGLA_PROYECTO
				 FROM SGC_TP_PROYECTOS");

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

    public function obtenerSectores($proyecto, $secini, $secfin)
    {
        $resultado = oci_parse($this->_db, "SELECT ID_SECTOR
		FROM SGC_TT_INMUEBLES
		WHERE ID_SECTOR BETWEEN $secini AND $secfin AND ID_PROYECTO = '$proyecto'
		GROUP BY ID_SECTOR
		ORDER BY ID_SECTOR
		");

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

    public function obtenerRutas($proyecto, $sector, $rutini, $rutfin)
    {
        $resultado = oci_parse($this->_db, "SELECT ID_RUTA
		FROM SGC_TT_INMUEBLES
		WHERE ID_SECTOR = $sector AND ABS(ID_RUTA) BETWEEN $rutini AND $rutfin AND ID_PROYECTO = '$proyecto'
		GROUP BY ID_RUTA
		ORDER BY ID_RUTA
		");

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

    public function obtenerRegistros($proyecto, $sector, $ruta)
    {
        $resultado = oci_parse($this->_db, "SELECT  SUBSTR(CATASTRO,3,3)MANZANA, COUNT(CODIGO_INM)CANTIDAD
		FROM SGC_TT_INMUEBLES
		WHERE ID_SECTOR = $sector AND ABS(ID_RUTA) = $ruta AND ID_PROYECTO = '$proyecto'
		GROUP BY SUBSTR(CATASTRO,3,3)
		ORDER BY MANZANA
		");

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

    public function obtenerInmueblesInc($proyecto, $secini, $secfin, $fecini, $fecfin)
    {
        $sql = "SELECT
                  I.CODIGO_INM,
                  U.DESC_URBANIZACION||' '||I.DIRECCION DIRECCION,
                  I.ID_PROCESO,
                  SUBSTR(I.ID_PROCESO,0,2) SECTOR,
                  SUBSTR(I.ID_PROCESO,3,2) RUTA,
                  ALIAS, I.ID_ESTADO,
		          TO_CHAR(I.FECHA_CREACION,'DD/MM/YYYY')FECHA_INICIO,
		          I.ID_SECTOR,
		          A.ID_USO,
		          S.ID_GERENCIA,
		          (SELECT T.DESC_TARIFA FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS T WHERE SI.COD_INMUEBLE = I.CODIGO_INM AND SI.COD_SERVICIO IN (1,3) AND T.CONSEC_TARIFA = SI.CONSEC_TARIFA )TARIFA,
		          (SELECT T.CODIGO_TARIFA FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS T WHERE SI.COD_INMUEBLE = I.CODIGO_INM AND SI.COD_SERVICIO IN (1,3) AND T.CONSEC_TARIFA = SI.CONSEC_TARIFA )CODIGO_TARIFA,
		          (SELECT SI.UNIDADES_HAB FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS T WHERE SI.COD_INMUEBLE = I.CODIGO_INM AND SI.COD_SERVICIO IN (1,3) AND T.CONSEC_TARIFA = SI.CONSEC_TARIFA )UNIDADES
		FROM SGC_TT_CONTRATOS C, SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TP_ACTIVIDADES A, SGC_TP_SECTORES S, SGC_TT_CLIENTES CL
		WHERE I.CODIGO_INM = C.CODIGO_INM(+) AND I.CONSEC_URB = U.CONSEC_URB(+)
		AND  A.SEC_ACTIVIDAD(+) = I.SEC_ACTIVIDAD AND
		CL.CODIGO_CLI(+)=C.CODIGO_CLI
		AND C.FECHA_FIN(+) IS NULL
		AND S.ID_SECTOR = I.ID_SECTOR AND I.ID_SECTOR BETWEEN $secini AND $secfin
		AND I.FECHA_CREACION BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND I.ID_PROYECTO = '$proyecto' 
		ORDER BY I.ID_SECTOR, I.ID_PROCESO";
        $resultado = oci_parse($this->_db, $sql);

        //echo $sql;
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

    public function obtenerContratos($proyecto, $secini, $secfin, $fecini, $fecfin)
    {
        $resultado = oci_parse($this->_db, "SELECT I.CODIGO_INM, U.DESC_URBANIZACION||' '||I.DIRECCION DIRECCION, I.ID_PROCESO, C.ALIAS, I.ID_ESTADO, I.ID_SECTOR,
		C.FECHA_INICIO
		FROM SGC_TT_INMUEBLES I, SGC_TP_URBANIZACIONES U, SGC_TT_CONTRATOS C
		WHERE I.CONSEC_URB = U.CONSEC_URB AND C.CODIGO_INM = I.CODIGO_INM AND
		I.ID_SECTOR BETWEEN $secini AND $secfin AND C.FECHA_INICIO BETWEEN TO_DATE('$fecini','YYYY-MM-DD') AND TO_DATE('$fecfin','YYYY-MM-DD') AND I.ID_PROYECTO = '$proyecto'
		ORDER BY I.ID_SECTOR, C.FECHA_INICIO");

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

    public function obtenerAltas($proyecto, $tipo, $fecini, $fecfin, $secini, $secfin, $usoini, $usofin)
    {
        $where = "";
        if ($fecini != '' && $fecfin != '' && $tipo == 'A') {
            $where .= " AND C.FECHA_INICIO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD hh24:mi:ss') AND C.FECHA_SOLICITUD IS NOT NULL ";
        }

        if ($fecini != '' && $fecfin != '' && $tipo == 'B') {
            $where .= " AND C.FECHA_FIN BETWEEN TO_DATE('$fecini 00:00','YYYY-MM-DD hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD hh24:mi:ss') AND I.ID_ESTADO IN ('CC','CT','CK') ";
        }

        if ($fecini == '' && $fecfin == '' && $tipo == 'A') {
            $where .= " AND  C.FECHA_SOLICITUD IS NOT NULL ";
        }

        if ($fecini == '' && $fecfin == '' && $tipo == 'B') {
            $where .= " AND  I.ID_ESTADO IN ('CC','CT','CK') ";
        }

        if ($secini != '' && $secfin != '') {
            $where .= " AND I.ID_SECTOR BETWEEN '$secini' AND '$secfin' ";
        }

        if ($usoini != '') {
            $where .= " AND A.ID_USO BETWEEN '$usoini' ";
        }

        $sql = "SELECT I.CODIGO_INM, I.ID_SECTOR, I.ID_ZONA, I.PER_ALTA, I.PER_BAJA, U.DESC_URBANIZACION, I.DIRECCION, C.ALIAS, I.ID_PROCESO,
		I.ID_ESTADO, C.FECHA_CREACION FEC_ALTA, I.FEC_BAJA, A.ID_USO, T.DESC_TARIFA
		FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TP_URBANIZACIONES U, SGC_TP_ACTIVIDADES A, SGC_TT_SERVICIOS_INMUEBLES S, SGC_TP_TARIFAS T
		WHERE I.CODIGO_INM = C.CODIGO_INM AND I.CONSEC_URB = U.CONSEC_URB AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
		AND I.CODIGO_INM = S.COD_INMUEBLE AND S.CONSEC_TARIFA = T.CONSEC_TARIFA
		AND I.ID_PROYECTO = '$proyecto'
		AND (S.COD_SERVICIO = 1 OR S.COD_SERVICIO = 3)  $where 
		ORDER BY I.ID_SECTOR, I.ID_ZONA";
        $resultado = oci_parse($this->_db, $sql);
        //echo $sql;

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

    public function seleccionaTipo()
    {
        $resultado = oci_parse($this->_db, "SELECT ID_TIPO, DESC_TIPO
				 FROM SGC_TP_TIPO_REPORTE");

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

    public function obtenerInmEstadoII($proyecto, $proIni, $proFin, $manIni, $manFin, $estIni, $estFin, $usoIni, $actividad,
                                       $servicio, $idzona, $cliente, $requestStart='',$requestLenght='')
    {

        $where = "";
        if ($proIni != '') {
            $where .= "AND I.ID_PROCESO >= '$proIni'";
        }

        if ($proFin != '') {
            $where .= "AND I.ID_PROCESO <= '$proFin'";
        }

        if ($manIni != '') {
            $where .= "AND SUBSTR(I.CATASTRO,3,3) >= '$manIni'";
        }

        if ($manFin != '') {
            $where .= "AND SUBSTR(I.CATASTRO,3,3) <= '$manFin'";
        }

        if ($estIni != '') {
            $where .= "AND I.ID_ESTADO  >= '$estIni'";
        }

        if ($estFin != '') {
            $where .= "AND I.ID_ESTADO  <= '$estFin'";
        }

        if ($usoIni != '') {
            $where .= " AND A.ID_USO = '$usoIni'";
        }

        if ($actividad != '') {
            $where .= " AND A.SEC_ACTIVIDAD = '$actividad'";
        }

        if ($servicio != '') {
            $where .= " AND SI.COD_SERVICIO = '$servicio'";
        }

        if ($idzona != '') {
            $where .= " AND I.ID_ZONA = '$idzona'";
        }

        if ($cliente != '') {
            $where .= " AND I.ID_TIPO_CLIENTE = '$cliente'";
        }



        if($requestStart!=""||$requestLenght!=""){
             $sql = "select * from (
              select a.*, ROWNUM rnum from (SELECT I.ID_SECTOR,I.ID_RUTA RUTA, I.ID_ZONA, I.CODIGO_INM, U.DESC_URBANIZACION, I.DIRECCION,
                 NVL(CASE (C.CODIGO_CLI)
                  WHEN 9999999 THEN C.ALIAS
                  ELSE CLI.NOMBRE_CLI||
                  (
                    SELECT
                    CASE NVL(TRIM(C.ALIAS),'00')
                      WHEN '00' THEN ''
                      ELSE '('||C.ALIAS||')'
                    END
                    FROM DUAL
                  )
                END,'N/A')
                ALIAS, I.ID_PROCESO, I.CATASTRO,
		NVL(M.COD_MEDIDOR,'SM')MEDIDOR, NVL(M.SERIAL,'SIN SERIAL') SERIAL, NVL(M.COD_EMPLAZAMIENTO,'SIN DEFINIR') COD_EMPLAZAMIENTO, NVL(D.DESC_CALIBRE,'SIN DEFINIR') DESC_CALIBRE, SER.DESC_SERVICIO DESC_SUMINISTRO, A.ID_USO, A.DESC_ACTIVIDAD,
		I.TOTAL_UNIDADES, NVL(C.ID_CONTRATO,'SIN CONTRATO') ID_CONTRATO, I.ID_ESTADO,
		 TAR.DESC_TARIFA,
		 (
		    SELECT count(1) CANTIDAD_FAC FROM SGC_TT_FACTURA F
            WHERE F.FEC_EXPEDICION IS NOT NULL
            AND F.FACTURA_PAGADA='N'
            AND F.FECHA_PAGO IS NULL
            AND F.INMUEBLE=I.CODIGO_INM
          )FAC_PEND,
          NVL((select SUM(VALOR)from SGC_TT_DETALLE_FACTURA DF
          WHERE  DF.CONCEPTO=SI.COD_SERVICIO  AND DF.COD_INMUEBLE=I.CODIGO_INM AND DF.PAGADO = 'N'),0) DEUDA,
            SI.CUPO_BASICO,
            SI.CONSUMO_MINIMO

		FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TP_URBANIZACIONES U, SGC_TP_ACTIVIDADES A, SGC_TT_MEDIDOR_INMUEBLE M,
		SGC_TP_MET_SUMINISTRO S, SGC_TP_CALIBRES D, SGC_TT_CLIENTES CLI, sgc_Tt_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS TAR,
		SGC_TP_SERVICIOS SER
		WHERE I.CODIGO_INM = C.CODIGO_INM(+)
		AND SER.COD_SERVICIO(+)=SI.COD_SERVICIO
		AND CLI.CODIGO_CLI(+)=C.CODIGO_CLI
		AND SI.COD_INMUEBLE(+)=I.CODIGO_INM/*
        AND SI.COD_SERVICIO(+) IN (1,3)*/
        AND TAR.CONSEC_TARIFA(+)=SI.CONSEC_TARIFA
		AND C.FECHA_FIN (+)IS NULL
		AND I.CONSEC_URB = U.CONSEC_URB
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
		AND M.COD_INMUEBLE(+) = I.CODIGO_INM
		AND M.METODO_SUMINISTRO = S.COD_SUMINISTRO(+)
		AND D.COD_CALIBRE(+) = M.COD_CALIBRE
		AND M.FEChA_BAJA(+) IS NULL
		AND I.ID_PROYECTO = '$proyecto' $where
                                               ) a 
        where rownum <=$requestStart) where rnum >=$requestLenght+1";
        }else{
             $sql = "select * from (
              select a.*, ROWNUM rnum from (SELECT I.ID_SECTOR SECTOR,I.ID_RUTA RUTA, I.ID_ZONA, I.CODIGO_INM, U.DESC_URBANIZACION, I.DIRECCION,
                 NVL(CASE (C.CODIGO_CLI)
                  WHEN 9999999 THEN C.ALIAS
                  ELSE CLI.NOMBRE_CLI||
                  (
                    SELECT
                    CASE NVL(TRIM(C.ALIAS),'00')
                      WHEN '00' THEN ''
                      ELSE '('||C.ALIAS||')'
                    END
                    FROM DUAL
                  )
                END,'N/A')
                ALIAS, I.ID_PROCESO, I.CATASTRO,
		NVL(M.COD_MEDIDOR,'SM')MEDIDOR,NVL(M.SERIAL,'SIN SERIAL') SERIAL, NVL(M.COD_EMPLAZAMIENTO,'SIN DEFINIR') COD_EMPLAZAMIENTO, NVL(D.DESC_CALIBRE,'SIN DEFINIR') DESC_CALIBRE, SER.DESC_SERVICIO DESC_SUMINISTRO, A.ID_USO, A.DESC_ACTIVIDAD,
		I.TOTAL_UNIDADES, C.ID_CONTRATO, I.ID_ESTADO,
		 TAR.DESC_TARIFA,
		 (
		    SELECT count(1) CANTIDAD_FAC FROM SGC_TT_FACTURA F
            WHERE F.FEC_EXPEDICION IS NOT NULL
            AND F.FACTURA_PAGADA='N'
            AND F.FECHA_PAGO IS NULL
            AND F.INMUEBLE=I.CODIGO_INM
          )FAC_PEND,
          NVL((select SUM(VALOR)from SGC_TT_DETALLE_FACTURA DF
               WHERE  DF.CONCEPTO=SI.COD_SERVICIO  AND DF.COD_INMUEBLE=I.CODIGO_INM AND DF.PAGADO = 'N'),0) DEUDA,
            SI.CUPO_BASICO,
            SI.CONSUMO_MINIMO

		FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TP_URBANIZACIONES U, SGC_TP_ACTIVIDADES A, SGC_TT_MEDIDOR_INMUEBLE M,
		SGC_TP_MET_SUMINISTRO S, SGC_TP_CALIBRES D, SGC_TT_CLIENTES CLI, sgc_Tt_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS TAR,
		SGC_TP_SERVICIOS SER
		WHERE I.CODIGO_INM = C.CODIGO_INM(+)
		AND SER.COD_SERVICIO(+)=SI.COD_SERVICIO
		AND CLI.CODIGO_CLI(+)=C.CODIGO_CLI
		AND SI.COD_INMUEBLE(+)=I.CODIGO_INM/*
        AND SI.COD_SERVICIO(+) IN (1,3)*/
        AND TAR.CONSEC_TARIFA(+)=SI.CONSEC_TARIFA
		AND C.FECHA_FIN (+)IS NULL
		AND I.CONSEC_URB = U.CONSEC_URB
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
		AND M.COD_INMUEBLE(+) = I.CODIGO_INM
		AND M.METODO_SUMINISTRO = S.COD_SUMINISTRO(+)
		AND D.COD_CALIBRE(+) = M.COD_CALIBRE
		AND M.FEChA_BAJA(+) IS NULL
		AND I.ID_PROYECTO = '$proyecto' $where
                                               ) a 
        where rownum >=0) where rnum >=0";

        }
        //echo $sql;

        /*) WHERE ROWNUM<$request*/
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


    public function obtenerInmEstado($proyecto, $proIni, $proFin, $manIni, $manFin, $estIni, $estFin, $usoIni, $actividad, $servicio)
    {
        $where = "";
        if ($proIni != '') {
            $where .= " AND I.ID_PROCESO >= '$proIni'";
        }

        if ($proFin != '') {
            $where .= " AND I.ID_PROCESO <= '$proFin'";
        }

        if ($manIni != '') {
            $where .= " AND SUBSTR(I.CATASTRO,3,3) >= '$manIni'";
        }

        if ($manFin != '') {
            $where .= " AND SUBSTR(I.CATASTRO,3,3) <= '$manFin'";
        }

        if ($estIni != '') {
            $where .= " AND I.ID_ESTADO  >= '$estIni'";
        }

        if ($estFin != '') {
            $where .= " AND I.ID_ESTADO  <= '$estFin'";
        }

        if ($usoIni != '') {
            $where .= " AND A.ID_USO = '$usoIni'";
        }

        if ($actividad != '') {
            $where .= " AND A.SEC_ACTIVIDAD = '$actividad'";
        }

        if ($servicio != '') {
            $where .= " AND SER.COD_SERVICIO = $servicio";
        }

        $sql = "SELECT I.ID_SECTOR,I.ID_RUTA RUTA, I.ID_ZONA, I.CODIGO_INM, U.DESC_URBANIZACION, I.DIRECCION,
                 NVL(CASE (C.CODIGO_CLI)
                  WHEN 9999999 THEN C.ALIAS
                  ELSE CLI.NOMBRE_CLI||
                  (
                    SELECT
                    CASE NVL(TRIM(C.ALIAS),'00')
                      WHEN '00' THEN ''
                      ELSE '('||C.ALIAS||')'
                    END
                    FROM DUAL
                  )
                END,'N/A')
                ALIAS, I.ID_PROCESO, I.CATASTRO,
		NVL(M.COD_MEDIDOR,'SM')MEDIDOR, NVL(M.SERIAL,'SIN SERIAL') SERIAL, NVL(M.COD_EMPLAZAMIENTO,'SIN DEFINIR') COD_EMPLAZAMIENTO, NVL(D.DESC_CALIBRE,'SIN DEFINIR') DESC_CALIBRE, SER.DESC_SERVICIO DESC_SUMINISTRO, A.ID_USO, A.DESC_ACTIVIDAD,
		I.TOTAL_UNIDADES, C.ID_CONTRATO, I.ID_ESTADO,
		 TAR.DESC_TARIFA,
		 (
		    SELECT count(1) CANTIDAD_FAC FROM SGC_TT_FACTURA F
            WHERE F.FEC_EXPEDICION IS NOT NULL
            AND F.FACTURA_PAGADA='N'
            AND F.FECHA_PAGO IS NULL
            AND F.INMUEBLE=I.CODIGO_INM
          )FAC_PEND,

          (SELECT SUM(F.TOTAL-F.TOTAL_PAGADO) CANTIDAD_FAC FROM SGC_TT_FACTURA F
            WHERE F.FEC_EXPEDICION IS NOT NULL
            AND F.FACTURA_PAGADA='N'
            AND F.FECHA_PAGO IS NULL
            AND F.INMUEBLE=I.CODIGO_INM) DEUDA
            ,
            SI.CUPO_BASICO,
            SI.CONSUMO_MINIMO

		FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TP_URBANIZACIONES U, SGC_TP_ACTIVIDADES A, SGC_TT_MEDIDOR_INMUEBLE M,
		SGC_TP_MET_SUMINISTRO S, SGC_TP_CALIBRES D, SGC_TT_CLIENTES CLI, sgc_Tt_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS TAR,
		SGC_TP_SERVICIOS SER
		WHERE I.CODIGO_INM = C.CODIGO_INM(+)
		AND SER.COD_SERVICIO(+)=SI.COD_SERVICIO
		AND CLI.CODIGO_CLI(+)=C.CODIGO_CLI
		AND SI.COD_INMUEBLE(+)=I.CODIGO_INM
        AND SI.COD_SERVICIO(+) IN (1,3)
        AND TAR.CONSEC_TARIFA(+)=SI.CONSEC_TARIFA
		AND C.FECHA_FIN (+)IS NULL
		AND I.CONSEC_URB = U.CONSEC_URB
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
		AND M.COD_INMUEBLE(+) = I.CODIGO_INM
		AND M.METODO_SUMINISTRO = S.COD_SUMINISTRO(+)
		AND D.COD_CALIBRE(+) = M.COD_CALIBRE
		AND M.FEChA_BAJA(+) IS NULL
		AND I.ID_PROYECTO = '$proyecto' $where
		ORDER BY I.ID_SECTOR, I.ID_ZONA";
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

    public function cantidadInmEstado($proyecto, $proIni, $proFin, $manIni, $manFin, $estIni, $estFin, $usoIni, $actividad,
                                      $servicio, $idzona, $cliente){

        $where = "";
        if ($proIni != '') {
            $where .= "AND I.ID_PROCESO >= '$proIni'";
        }

        if ($proFin != '') {
            $where .= "AND I.ID_PROCESO <= '$proFin'";
        }

        if ($manIni != '') {
            $where .= "AND SUBSTR(I.CATASTRO,3,3) >= '$manIni'";
        }

        if ($manFin != '') {
            $where .= "AND SUBSTR(I.CATASTRO,3,3) <= '$manFin'";
        }

        if ($estIni != '') {
            $where .= "AND I.ID_ESTADO  >= '$estIni'";
        }

        if ($estFin != '') {
            $where .= "AND I.ID_ESTADO  <= '$estFin'";
        }

        if ($usoIni != '') {
            $where .= " AND A.ID_USO = '$usoIni'";
        }

        if ($actividad != '') {
            $where .= " AND A.SEC_ACTIVIDAD = '$actividad'";
        }

        if ($servicio != '') {
            $where .= " AND SI.COD_SERVICIO = '$servicio'";
        }

        if ($idzona != '') {
            $where .= " AND I.ID_ZONA = '$idzona'";
        }

        if ($cliente != '') {
            $where .= " AND I.ID_TIPO_CLIENTE = '$cliente'";
        }



          $sql = "select count(*) from (SELECT I.ID_SECTOR,I.ID_RUTA RUTA, I.ID_ZONA, I.CODIGO_INM, U.DESC_URBANIZACION, I.DIRECCION,
                 NVL(CASE (C.CODIGO_CLI)
                  WHEN 9999999 THEN C.ALIAS
                  ELSE CLI.NOMBRE_CLI||
                  (
                    SELECT
                    CASE NVL(TRIM(C.ALIAS),'00')
                      WHEN '00' THEN ''
                      ELSE '('||C.ALIAS||')'
                    END
                    FROM DUAL
                  )
                END,'N/A')
                ALIAS, I.ID_PROCESO, I.CATASTRO,
		NVL(M.COD_MEDIDOR,'SM')MEDIDOR, NVL(M.SERIAL,'SIN SERIAL') SERIAL, NVL(M.COD_EMPLAZAMIENTO,'SIN DEFINIR') COD_EMPLAZAMIENTO,NVL(D.DESC_CALIBRE,'SIN DEFINIR') DESC_CALIBRE, SER.DESC_SERVICIO DESC_SUMINISTRO, A.ID_USO, A.DESC_ACTIVIDAD,
		I.TOTAL_UNIDADES, C.ID_CONTRATO, I.ID_ESTADO,
		 TAR.DESC_TARIFA,
		 (
		    SELECT count(1) CANTIDAD_FAC FROM SGC_TT_FACTURA F
            WHERE F.FEC_EXPEDICION IS NOT NULL
            AND F.FACTURA_PAGADA='N'
            AND F.FECHA_PAGO IS NULL
            AND F.INMUEBLE=I.CODIGO_INM
          )FAC_PEND,

          (SELECT SUM(F.TOTAL-F.TOTAL_PAGADO) CANTIDAD_FAC FROM SGC_TT_FACTURA F
            WHERE F.FEC_EXPEDICION IS NOT NULL
            AND F.FACTURA_PAGADA='N'
            AND F.FECHA_PAGO IS NULL
            AND F.INMUEBLE=I.CODIGO_INM) DEUDA
            ,
            SI.CUPO_BASICO,
            SI.CONSUMO_MINIMO

		FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TP_URBANIZACIONES U, SGC_TP_ACTIVIDADES A, SGC_TT_MEDIDOR_INMUEBLE M,
		SGC_TP_MET_SUMINISTRO S, SGC_TP_CALIBRES D, SGC_TT_CLIENTES CLI, sgc_Tt_SERVICIOS_INMUEBLES SI, SGC_TP_TARIFAS TAR,
		SGC_TP_SERVICIOS SER
		WHERE I.CODIGO_INM = C.CODIGO_INM(+)
		AND SER.COD_SERVICIO(+)=SI.COD_SERVICIO
		AND CLI.CODIGO_CLI(+)=C.CODIGO_CLI
		AND SI.COD_INMUEBLE(+)=I.CODIGO_INM/*
        AND SI.COD_SERVICIO(+) IN (1,3)*/
        AND TAR.CONSEC_TARIFA(+)=SI.CONSEC_TARIFA
		AND C.FECHA_FIN (+)IS NULL
		AND I.CONSEC_URB = U.CONSEC_URB
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD
		AND M.COD_INMUEBLE(+) = I.CODIGO_INM
		AND M.METODO_SUMINISTRO = S.COD_SUMINISTRO(+)
		AND D.COD_CALIBRE(+) = M.COD_CALIBRE
		AND M.FEChA_BAJA(+) IS NULL
		AND I.ID_PROYECTO = '$proyecto' $where
		ORDER BY I.ID_SECTOR, I.ID_ZONA)";
        // echo $sql;
        /*) WHERE ROWNUM<$request*/
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

    public function obtenerUsos()
    {
        $sql       = "SELECT U.ID_USO, U.DESC_USO FROM SGC_TP_USOS U WHERE U.VISIBLE = 'S'";
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

    public function obtenerActividad($uso)
    {
        $sql       = "SELECT ACT.SEC_ACTIVIDAD, ACT.DESC_ACTIVIDAD FROM SGC_TP_ACTIVIDADES ACT WHERE ACT.ID_USO = '$uso'";
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
