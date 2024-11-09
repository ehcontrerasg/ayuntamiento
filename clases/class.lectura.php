<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
include_once "class.conexion.php";



class Lectura extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }


    public function getCalByPerObsLec($per,$obslec,$pro){
        $sql = "select * from (
                  select
                    OL.DESCRIPCION,
                    DESC_CALIBRE
                  from
                    sgc_tt_registro_lecturas rl, SGC_TP_OBSERVACIONES_LECT OL, SGC_TT_INMUEBLES INM, SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TP_CALIBRES CAL
                  WHERE
                    RL.FECHA_LECTURA_ORI is not null and
                    OL.CODIGO=RL.OBSERVACION_ACTUAL AND
                    INM.CODIGO_INM=RL.COD_INMUEBLE AND
                    MI.COD_INMUEBLE=INM.CODIGO_INM AND
                    CAL.COD_CALIBRE=MI.COD_CALIBRE AND
                     MI.FECHA_BAJA is null AND
                    OL.MEDIDOR_DAGNADO='S' AND
                    INM.ID_PROYECTO='$pro' AND
                    RL.PERIODO=$per
                )
                pivot
                (
                   count(DESCRIPCION)
                   for DESCRIPCION in ($obslec))";
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


    public function getSecObsLecByPerSec($per,$pro){
        $per=addslashes($per);
        $pro=addslashes($pro);
        $sql = "SELECT
                  SECTOR
                from
                  sgc_tt_registro_lecturas rl,SGC_TP_OBSERVACIONES_LECT OL,SGC_TT_INMUEBLES INM, SGC_TT_MEDIDOR_INMUEBLE MI
                where
                  OL.CODIGO=RL.OBSERVACION_ACTUAL AND
                  MI.COD_INMUEBLE=INM.CODIGO_INM AND
                  MI.FECHA_BAJA IS NULL AND
                  INM.CODIGO_INM=RL.COD_INMUEBLE AND
                  INM.ID_PROYECTO='$pro' AND
                  OL.MEDIDOR_DAGNADO='S' AND
                  RL.PERIODO='$per'
                group by
                  sector";
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
            echo "false getSecObsLecByPerSec";
            return false;
        }

    }



    public function gtRendLctByProFec ($proycto,$fecIni,$fecFin)
    {

         $sql="SELECT
  U.LOGIN,COUNT(1) VISITADOS,
  (SELECT COUNT(1) FROM SGC_TT_REGISTRO_LECTURAS RL2,SGC_TP_OBSERVACIONES_LECT OL
    WHERE
      RL2.FECHA_LECTURA BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
      TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
      RL2.COD_LECTOR_ORI=RL.COD_LECTOR_ORI AND
        RL2.OBSERVACION=OL.CODIGO AND
        OL.LEER='S'
  ) LEIDOS,
  (SELECT COUNT(1) FROM SGC_TT_REGISTRO_LECTURAS RL2,SGC_TP_OBSERVACIONES_LECT OL
  WHERE
    RL2.FECHA_LECTURA BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
    TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
    RL2.COD_LECTOR_ORI=RL.COD_LECTOR_ORI AND
    RL2.OBSERVACION=OL.CODIGO AND
    OL.LEER='S' AND
      RL2.LECTURA_ACTUAL<>RL2.LECTURA_ORIGINAL
  ) ERRORES_LECT,
 ROUND((SELECT COUNT(1) FROM SGC_TT_REGISTRO_LECTURAS RL2,SGC_TP_OBSERVACIONES_LECT OL
  WHERE
    RL2.FECHA_LECTURA BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
    TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
    RL2.COD_LECTOR_ORI=RL.COD_LECTOR_ORI AND
    RL2.OBSERVACION=OL.CODIGO AND
    OL.LEER='S'
  )/
  DIF_DIASLAB2(TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS'),
               TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')),2) PROMEDIO_DIA


FROM SGC_TT_USUARIOS U, SGC_TT_REGISTRO_LECTURAS RL, SGC_TT_INMUEBLES inm
  WHERE U.ID_USUARIO=RL.COD_LECTOR_ORI  AND
    U.CONTRATISTA=1 AND
    inm.CODIGO_INM=rl.COD_INMUEBLE and 
    inm.ID_PROYECTO='$proycto' AND
    RL.FECHA_LECTURA BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
    TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
GROUP BY U.LOGIN, RL.COD_LECTOR_ORI";

        $resultado = oci_parse($this->_db,
            $sql);


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


    public function gtRendFacByProFec ($proyecto,$fecIni,$fecFin)
    {

         $sql="SELECT
  U.LOGIN,COUNT(1) VISITADOS,
  (SELECT COUNT(1) FROM SGC_TT_REGISTRO_ENTREGA_FAC RL2
    WHERE
      RL2.FECHA_ASIGNA BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
      TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
      RL2.USR_EJE=RL.USR_EJE AND 
      RL2.ENTREGADO = 'S'
  ) ENTREGADOS,
  (SELECT  COUNT(1) FROM SGC_TT_PQRS P, SGC_TT_REGISTRO_ENTREGA_FAC RL2
  WHERE
    P.FECHA_PQR BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
    TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
    RL2.FECHA_ASIGNA BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
    TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
    P.MOTIVO_PQR = 41 
    AND P.COD_INMUEBLE(+) = RL2.COD_INMUEBLE
    AND RL2.USR_EJE=RL.USR_EJE 
  )QUEJAS,
 ROUND((SELECT COUNT(1) FROM SGC_TT_REGISTRO_ENTREGA_FAC RL2
  WHERE
    RL2.FECHA_EJECUCION BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
    TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
    RL2.USR_EJE=RL.USR_EJE 
  )/
  DIF_DIASLAB2(TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS'),
               TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')),2) PROMEDIO_DIA


FROM SGC_TT_USUARIOS U, SGC_TT_REGISTRO_ENTREGA_FAC RL, SGC_TT_INMUEBLES inm
  WHERE U.ID_USUARIO=RL.USR_EJE  AND
    U.CONTRATISTA=1 AND
    inm.CODIGO_INM=rl.COD_INMUEBLE and 
    inm.ID_PROYECTO='$proyecto' AND
    RL.FECHA_ASIGNA BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
    TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
    AND RL.ANULADO = 'N'
GROUP BY U.LOGIN, RL.USR_EJE";

        $resultado = oci_parse($this->_db,
            $sql);


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



    public function gtRendCatByProFec ($proyecto,$fecIni,$fecFin)
    {

        $sql="SELECT
  U.LOGIN,
  (SELECT COUNT(1) FROM SGC_TT_ASIGNACION A2
    WHERE
      A2.FECHA_ASIG BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
      TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
      A2.ID_OPERARIO=A.ID_OPERARIO 
  ) ASIGNADOS,
  (SELECT COUNT(1) FROM SGC_TT_ASIGNACION A2
    WHERE
      A2.FECHA_ASIG BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
      TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
      A2.ID_OPERARIO=A.ID_OPERARIO AND
      A2.FECHA_FIN IS NOT NULL
  ) VISITADOS,
 
 ROUND((SELECT COUNT(1) FROM SGC_TT_ASIGNACION A2
  WHERE
    A2.FECHA_ASIG BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
    TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS') AND
    A2.ID_OPERARIO=A.ID_OPERARIO AND
      A2.FECHA_FIN IS NOT NULL 
  )/
  DIF_DIASLAB2(TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS'),
               TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')),2) PROMEDIO_DIA

FROM SGC_TT_USUARIOS U, SGC_TT_ASIGNACION A, SGC_TT_INMUEBLES I
  WHERE U.ID_USUARIO=A.ID_OPERARIO  AND
    U.CONTRATISTA=1 AND
    I.CODIGO_INM=A.ID_INMUEBLE and 
    I.ID_PROYECTO='$proyecto' AND
    A.FECHA_ASIG BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
    TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')
GROUP BY U.LOGIN, A.ID_OPERARIO";

        $resultado = oci_parse($this->_db,
            $sql);


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



    public function getObsLecByPerPro($per,$pro){
         $sql = "SELECT distinct 
                  OL.DESCRIPCION
                FROM
                  sgc_tt_registro_lecturas rl,
                  SGC_TP_OBSERVACIONES_LECT OL,
                  SGC_TT_INMUEBLES INM,
                  SGC_TT_MEDIDOR_INMUEBLE MI,
                  SGC_TP_CALIBRES CAL
                WHERE
                  RL.FECHA_LECTURA_ORI is not null and
                  OL.CODIGO=RL.OBSERVACION_ACTUAL AND
                  INM.CODIGO_INM=RL.COD_INMUEBLE AND
                  MI.COD_INMUEBLE=INM.CODIGO_INM AND
                  CAL.COD_CALIBRE=MI.COD_CALIBRE AND
                  OL.MEDIDOR_DAGNADO='S' AND
                  MI.FECHA_BAJA IS NULL AND
                  
                  INM.ID_PROYECTO='$pro' AND
                  RL.PERIODO=$per
               GROUP BY DESCRIPCION";
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

    public function getUltLectByInm($inm){
        $sql = "SELECT
                  RL.LECTURA_ACTUAL,
                  RL.OBSERVACION_ACTUAL
                FROM
                  SGC_TT_REGISTRO_LECTURAS RL
                WHERE RL.COD_INMUEBLE=$inm AND
                  RL.PERIODO=
                  (
                    SELECT
                      MAX(R.PERIODO)
                    FROM
                      SGC_TT_REGISTRO_LECTURAS R
                    WHERE
                      R.COD_INMUEBLE=$inm
                  )";
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


    public function getObsLecByPerCal($per,$cal,$pro){
        $sql = "select * from (
                  select
                    OL.DESCRIPCION,
                    RL.OBSERVACION_ACTUAL ,
                    DESC_CALIBRE
                  from
                    sgc_tt_registro_lecturas rl, SGC_TP_OBSERVACIONES_LECT OL, SGC_TT_INMUEBLES INM, SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TP_CALIBRES CAL
                  WHERE
                    RL.FECHA_LECTURA_ORI is not null and
                    OL.CODIGO=RL.OBSERVACION_ACTUAL AND
                    INM.CODIGO_INM=RL.COD_INMUEBLE AND
                    MI.COD_INMUEBLE=INM.CODIGO_INM AND
                    CAL.COD_CALIBRE=MI.COD_CALIBRE AND
                     MI.FECHA_BAJA is null AND
                    OL.MEDIDOR_DAGNADO='S' AND
                    INM.ID_PROYECTO='$pro' AND
                    RL.PERIODO=$per
                )
                pivot
                (
                   count(DESC_CALIBRE)
                   for DESC_CALIBRE in ($cal))";
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
            echo "false getObsLecByPerCal";
            return false;
        }

    }


    public function getCalByPerPer($per,$pro){
        $sql = "SELECT
                  CAL.DESC_CALIBRE
                FROM
                  sgc_tt_registro_lecturas rl,
                  SGC_TP_OBSERVACIONES_LECT OL,
                  SGC_TT_INMUEBLES INM,
                  SGC_TT_MEDIDOR_INMUEBLE MI,
                  SGC_TP_CALIBRES CAL
                WHERE
                  RL.FECHA_LECTURA_ORI is not null and
                  OL.CODIGO=RL.OBSERVACION_ACTUAL AND
                  INM.CODIGO_INM=RL.COD_INMUEBLE AND
                  MI.COD_INMUEBLE=INM.CODIGO_INM AND
                  CAL.COD_CALIBRE=MI.COD_CALIBRE AND
                  OL.MEDIDOR_DAGNADO='S' AND
                   MI.FECHA_BAJA is null AND
                  INM.ID_PROYECTO='$pro' AND
                  RL.PERIODO=$per
                  GROUP BY CAL.DESC_CALIBRE";
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
            echo "false getCalByPerPer";
            return false;
        }

    }



    public function getObsLecByPerSec($per,$sec,$pro){
        $sql = "select * from (
                  select
                    OBSERVACION_ACTUAL ,
                    OL.DESCRIPCION,
                    SECTOR
                  from
                    sgc_tt_registro_lecturas rl,
                    SGC_TP_OBSERVACIONES_LECT OL,
                    SGC_TT_INMUEBLES INM,
                    sgc_tt_MEDIDOR_INMUEBLE MI
                  WHERE
                    MI.COD_INMUEBLE=INM.CODIGO_INM AND
                    MI.FECHA_BAJA IS NULL AND
                    RL.FECHA_LECTURA_ORI is not null and
                    INM.CODIGO_INM=RL.COD_INMUEBLE AND
                    INM.ID_PROYECTO='$pro' AND
                    OL.CODIGO=RL.OBSERVACION_ACTUAL AND
                    OL.MEDIDOR_DAGNADO='S' AND
                    RL.PERIODO='$per'
                )
                pivot
                (
                   count(SECTOR)
                   for SECTOR in ($sec))";
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
            echo "false getObsLecByPerSec";
            return false;
        }

    }


    public function getLecturaByFactFlexy ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
                          select CASE RL.LECTURA_ORIGINAL WHEN -1 THEN NULL ELSE RL.LECTURA_ORIGINAL END LECTURA_ORIGINAL, RL.FECHA_LECTURA,RL.OBSERVACION_ACTUAL ,U.LOGIN, RL.CONSUMO  from sgc_tt_registro_lecturas rl, SGC_TT_FACTURA f, sgc_tt_usuarios u
                          where
                          RL.COD_INMUEBLE=F.INMUEBLE
                          and RL.PERIODO=F.PERIODO
                          and U.ID_USUARIO=RL.COD_LECTOR
                   $where
                   $sort
                   )where  rownum<13
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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

    public function getObsLecMed(){
        $sql = "select OL.CODIGO, OL.DESCRIPCION from sgc_tp_observaciones_lect ol
                    WHERE OL.muestra_medidores='S'
                    ORDER BY OL.DESCRIPCION";
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

    public function getMaxPeriodoLecByInm ($inmueble){
        $sql = "SELECT MAX(RL.PERIODO)PERIODO
		FROM SGC_TT_REGISTRO_LECTURAS RL
		WHERE RL.COD_INMUEBLE = $inmueble ";
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
    public function getUltimasTresLec ($inmueble, $periodo){
        $sql = " SELECT RL.PERIODO, RL.LECTURA_ACTUAL
        FROM SGC_TT_REGISTRO_LECTURAS RL
        WHERE RL.COD_INMUEBLE = $inmueble AND RL.PERIODO BETWEEN $periodo-3 AND $periodo-1
        ORDER BY PERIODO DESC";
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


    public function datosLecturaPdf($codinm, $periodo){
        $sql = "SELECT LECTURA_ACTUAL, TO_CHAR(FECHA_LECTURA_ORI,'DD/MM/YYYY')FECLEC  FROM SGC_TT_REGISTRO_LECTURAS R
		WHERE COD_INMUEBLE = '$codinm'
		AND PERIODO >= TO_CHAR(ADD_MONTHS(TO_DATE($periodo,'YYYYMM'),-1),'YYYYMM') 
		AND PERIODO <= TO_CHAR(ADD_MONTHS(TO_DATE($periodo,'YYYYMM'),+0),'YYYYMM') ";
        $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


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
