<?php

include_once '../../clases/class.conexion.php';

class Rendimiento extends ConexionClass{



    public function __construct()
    {
        parent::__construct();

    }


    public function obtenerTotalRutas ($tname, $where)
    {
        $sql = "SELECT COUNT(*) TOTAL FROM (SELECT  COUNT(L.COD_INMUEBLE) 
        FROM $tname
        WHERE U.ID_USUARIO = L.USR_EJE
        AND L.FECHA_PLANIFICACION IS NOT NULL
        $where
        GROUP BY L.USR_EJE, NOM_USR, APE_USR, TO_CHAR(L.FECHA_PLANIFICACION,'DD/MM/YYYY'), L.RUTA)";
        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }



    public function obtenerResumenRutas ($tname, $where, $sort, $start, $end)
    {
        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM ( 
		SELECT  /*+ USE_NL(U,L) ORDERED */L.USR_EJE, (U.NOM_USR||' '||U.APE_USR)NOM_COMPLETO, TO_DATE(TO_CHAR(L.FECHA_PLANIFICACION,'DD/MM/YYYY'),'DD/MM/YYYY')FECHA_ASIG,
		COUNT(L.COD_INMUEBLE) TOTAL, L.RUTA, SUM(DECODE(L.FECHA_EJECUCION, '', 1, 0))NOLEIDOS
		FROM $tname
		WHERE U.ID_USUARIO = L.USR_EJE
		AND L.FECHA_PLANIFICACION IS NOT NULL
		$where
		GROUP BY L.USR_EJE, NOM_USR, APE_USR, TO_CHAR(L.FECHA_PLANIFICACION,'DD/MM/YYYY'), L.RUTA
		$sort)a 
		WHERE rownum <= $start ) WHERE rnum >= $end+1";
        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function obtenerFechas ($periodo, $cod_ruta, $fecini, $fecfin)
    {
        if($periodo != "") $where = "L.PERIODO = $periodo ";
        if($fecini != "" && $fecfin != "")
            $where = "L.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
        $sql = "SELECT COUNT(*)CANTIDAD, TO_CHAR(L.FECHA_EJECUCION,'DD/MM/YYYY')DIA
		FROM SGC_TT_REGISTRO_ENT_FAC_ASEO L
		WHERE $where
		AND L.RUTA = '$cod_ruta'  
		GROUP BY TO_CHAR(l.FECHA_EJECUCION,'DD/MM/YYYY') ";
        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function obtenerFechaMax ($periodo, $cod_ruta, $dia_calc, $fecini, $fecfin)
    {
        if($periodo != "") $where = "L.PERIODO = $periodo ";
        if($fecini != "" && $fecfin != "")
            $where = "L.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
        $sql = "SELECT MAX(TO_CHAR(L.FECHA_EJECUCION,'DD/MM/YYYY HH24:MI:SS')) FEC_MAX
            FROM SGC_TT_REGISTRO_ENT_FAC_ASEO L
            WHERE $where AND L.RUTA = '$cod_ruta' 
            AND TO_CHAR(L.FECHA_EJECUCION,'DD/MM/YYYY') = '$dia_calc'";
        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function obtenerFechaMin ($periodo, $cod_ruta, $dia_calc, $fecini, $fecfin)
    {
        if($periodo != "") $where = "L.PERIODO = $periodo ";
        if($fecini != "" && $fecfin != "")
            $where = "L.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
        $sql = "SELECT MIN(TO_CHAR(L.FECHA_EJECUCION,'DD/MM/YYYY HH24:MI:SS')) FEC_MIN
        	FROM SGC_TT_REGISTRO_ENT_FAC_ASEO L
        	WHERE $where AND L.RUTA = '$cod_ruta' 
       		AND TO_CHAR(L.FECHA_EJECUCION,'DD/MM/YYYY') = '$dia_calc'";
        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }


    public function obtenerTotalDetalle ($periodo, $fecini, $fecfin, $cod_ruta, $cod_operario)
    {
        if($periodo != "") $where = "AND L.PERIODO = $periodo ";
        if($fecini != "" && $fecfin != "")
            $where = "AND L.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
        $sql = "SELECT COUNT(*)TOTAL
        FROM SGC_TT_REGISTRO_ENT_FAC_ASEO L, SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = L.COD_INMUEBLE AND L.FECHA_EJECUCION IS NOT NULL AND L.RUTA = '$cod_ruta' AND L.USR_EJE = '$cod_operario' 
        $where";
        $resultado = oci_parse($this->_db,$sql);

        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }


    public function TodosDetalleLec ($sort,$start,$end,$cod_ruta,$periodo,$cod_operario,$fecini,$fecfin)
    {
        if($periodo != "") $where = "AND L.PERIODO = $periodo ";
        if($fecini != "" && $fecfin != "")
            $where = "AND L.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
        $sql="SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
		FROM (
		SELECT I.CODIGO_INM, L.OBS_NOENTREGA, L.LATITUD, L.LONGITUD, I.ID_PROCESO, I.DIRECCION, 
		TO_CHAR(L.FECHA_EJECUCION,'DD/MM/YYYY HH24:MI:SS') FECHA_EJECUCION
		FROM SGC_TT_REGISTRO_ENT_FAC_ASEO L, SGC_TT_INMUEBLES I
		WHERE I.CODIGO_INM = L.COD_INMUEBLE AND L.FECHA_EJECUCION IS NOT NULL AND L.RUTA = '$cod_ruta' AND L.USR_EJE = '$cod_operario' 
		$where $sort
		)a WHERE rownum <= $start
		) WHERE rnum >= $end+1";

        //echo $sql;
        $resultado = oci_parse($this->_db,$sql);



        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }


    public function existefotolec($codigo_inm,$cod_operario,$periodo,$fecini,$fecfin)
    {
        if($periodo != "") $where = " AND FL.ID_PERIODO = $periodo ";
        if($fecini != "" && $fecfin != "")
            $where = " AND FL.FECHA BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
        $sql="SELECT COUNT(*) CANTIDAD FROM SGC_TT_FOTOS_FACTURA FL,SGC_TT_REGISTRO_ENT_FAC_ASEO RL
		WHERE FL.ID_INMUEBLE=RL.COD_INMUEBLE
		AND RL.USR_EJE='$cod_operario'
		AND RL.COD_INMUEBLE='$codigo_inm'
		$where";

        //echo $sql;
        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            while (oci_fetch($resultado)) {
                $cantidad = oci_result($resultado, 'CANTIDAD');
            }oci_free_statement($resultado);
            if($cantidad==0){
                $existe=false;
            }else{
                $existe=true;
            }

            oci_close($this->_db);
            return $existe;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }


    public function existecoordenadalec($codigo_inm,$periodo,$fecini,$fecfin,$cod_operario)
    {
        if($periodo != "") $where = " AND RL.PERIODO = $periodo ";
        if($fecini != "" && $fecfin != "")
            $where = " AND RL.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
        $sql="SELECT COUNT(*) CANTIDAD FROM  SGC_TT_REGISTRO_ENT_FAC_ASEO RL
		WHERE
		RL.USR_EJE='$cod_operario'
		AND RL.COD_INMUEBLE='$codigo_inm'
		AND RL.LATITUD>0 $where";


        //echo $sql;
        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            while (oci_fetch($resultado)) {
                $cantidad = oci_result($resultado, 'CANTIDAD');
            }oci_free_statement($resultado);
            if($cantidad==0){
                $existe=false;
            }else{
                $existe=true;
            }

            oci_close($this->_db);
            return $existe;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }


    public function urlfotolec($inmueble,$fecini,$fecfin,$periodo)
    {
        if($periodo != "") $where = " AND FL.ID_PERIODO = $periodo ";
        if($fecini != "" && $fecfin != "")
            $where = " AND FL.FECHA BETWEEN TO_DATE('$fecini 00:00:00', 'YYYYMMDD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYYMMDD HH24:MI:SS')";
        $sql="SELECT DISTINCT URL_FOTO  FROM SGC_TT_FOTOS_FACTURA FL
		WHERE FL.ID_INMUEBLE='$inmueble' $where";

        //echo $sql;
        $resultado = oci_parse($this->_db,$sql);



        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

}