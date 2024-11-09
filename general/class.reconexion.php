<?php
include_once "class.conexion.php";


class Reconexion extends ConexionClass{
    private $mesrror;
    private $coderror;

    public function getMesrror()
    {
        return $this->mesrror;
    }

    public function getCoderror()
    {
        return $this->coderror;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getRecDiaByUsrProcProyFech($usr,$proIni,$proFin,$proy,$fechIni,$fechFin)
    {
        $usr=addslashes($usr);
        $proIni=addslashes($proIni);
        $proFin=addslashes($proFin);
        $proy=addslashes($proy);
        $fechIni=addslashes($fechIni);
        $fechFin=addslashes($fechFin);

        $sql="SELECT
                ORE.FECHA_PAGO,
                RC.ORDEN,
                REC.ORDEN,
                ORE.INMUEBLE,
                NVL(CON.ALIAS,CLI.NOMBRE_CLI) NOMBRE,
                INM.ID_SECTOR,INM.ID_RUTA,
                ORE.IMPORTE,
                CASE
                  (NVL(TO_CHAR(RC.FECHA_EJE),'0'))
                  WHEN '0' THEN ''
                  ELSE (US.NOM_USR||' '||US.APE_USR)
                END ,

                CASE
                  (NVL(TO_CHAR(RC.FECHA_EJE),'0'))
                  WHEN '0' THEN ''
                  ELSE RC.TIPO_CORTE
                END TIPOCORTE
              FROM
                SGC_TT_OTROS_RECAUDOS ORE,
                SGC_TT_CONTRATOS CON,
                SGC_TT_CLIENTES CLI,
                SGC_TT_INMUEBLES INM ,
                SGC_TT_USUARIOS US,
                SGC_TT_REGISTRO_RECONEXION REC,
                sgc_tt_registro_cortes rc
              WHERE
                CON.CODIGO_INM(+)=INM.CODIGO_INM AND
                CON.FECHA_FIN(+) IS NULL AND
                CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                RC.ORDEN=REC.ORDEN(+) and
                INM.CODIGO_INM=ORE.INMUEBLE AND
                INM.ID_PROYECTO='$proy' AND
                RC.ID_OTRO_RECAUDO(+)=ORE.CODIGO AND
                RC.USR_EJE=US.ID_USUARIO(+) AND
                ORE.ESTADO<>'I' AND
                ORE.CONCEPTO=20 AND
                ORE.FECHA_PAGO BETWEEN TO_DATE('$fechIni 00:00:00','DD/MM/YYYY HH24:MI:SS') AND
                TO_DATE('$fechFin 23:59:59','DD/MM/YYYY HH24:MI:SS') AND
                RC.USR_EJE='$usr' AND
                INM.ID_PROCESO>='$proIni' AND
                INM.ID_PROCESO<='$proFin' AND";

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }


    }

    public function getRecByInmFlexy ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						select * from (
						SELECT  TO_CHAR(RR.FECHA_PLANIFICACION,'DD/MM/YYYY HH24:MI') FECHA_PLANIFICACION ,
						TO_CHAR(RR.FECHA_EJE,'YYYY/MM/DD HH24:MI') FECHA_EJE,
						RR.TIPO_RECONEXION,
						replace(replace(RR.OBS_GENERALES,chr(10),''),chr(13),'') OBS_GENERALES,TO_CHAR(RR.FECHA_ACUERDO,'DD/MM/YYYY HH24:MI') FECHA_ACUERDO,U.LOGIN FROM SGC_tT_REGISTRO_RECONEXION RR,
                        SGC_TT_USUARIOS U
                        WHERE U.ID_USUARIO(+)=RR.USR_EJE
                        $where
                        $sort
                   )where  rownum<1000
						)a WHERE  ROWNUM<=$start
					) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse(
            $this->_db,$sql
        );


        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {

            return $resultado;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function setAsignaReco($coduser,$operario_asignado,$sector,$ruta,$fecini,$fecfin,$tipo,$fecPla){
        if($fecini != '') $fecini = "TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        else $fecini = "''";
        if($fecfin != '') $fecfin = "TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        else $fecfin = "''";
        if($fecPla != '') $fecPla = "TO_DATE('$fecPla','YYYY-MM-DD')";
        else $fecPla = "''";
        $sql="BEGIN SGC_P_ASIGNA_RECONEXION('$coduser','$operario_asignado','$sector','$ruta',$fecini,$fecfin,'$tipo',$fecPla,:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,"123");
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror,"123");
        $bandera=oci_execute($resultado);
        //echo $sql."<br>";
        if($bandera){
            if($this->coderror==0){
                return true;
            }
            else{
                oci_close($this->_db);
                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }

    public function setDesasignaReco($sector,$fecini,$fecfin,$tipo,$desasignar_loc){
        if($fecini != '') $fecini = "TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        else $fecini = "''";
        if($fecfin != '') $fecfin = "TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        else $fecfin = "''";
        $sql="BEGIN SGC_P_DESASIGNA_RECONEXION('$sector',$fecini,$fecfin,'$tipo','$desasignar_loc',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        //echo $sql."<br>";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,"123");
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult,"123");
        $bandera=oci_execute($resultado);

        if($bandera){
            if($this->codresult==0){
                return true;
            }
            else{
                oci_close($this->_db);
                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }

    public function getPriCoorByRutFecUsu($ruta,$fecini,$fecfin,$usuario,$asig)
    {

        $sql="   SELECT * FROM (
				 SELECT COR.LONGITUD, COR.LATITUD, COR.ID_INMUEBLE ,TO_CHAR(COR.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
				 FROM SGC_TT_REGISTRO_RECONEXION cor, SGC_TT_INMUEBLES INM
				 WHERE
				 INM.CODIGO_INM=COR.ID_INMUEBLE
				 AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
				 AND COR.USR_EJE='$usuario'
				 AND COR.FECHA_EJE BETWEEN TO_DATE('$fecini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'yyyy-mm-dd hh24:mi:ss')
				 AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
				 ORDER BY COR.FECHA_EJE ASC)
				 WHERE ROWNUM = 1";
         $sql;
        $resultdo=oci_parse($this->_db,$sql);

        if(oci_execute($resultdo)){
            oci_close($this->_db);
            return $resultdo;
        }
        else{
            oci_close($this->_db);
            echo "error consulta primer punto";
            return false;
        }
    }

    public function getUltCoorByRutFecUsu($ruta,$fecini,$fecfin,$usuario)
    {

         $sql="   SELECT * FROM (
				 SELECT COR.LONGITUD, COR.LATITUD, COR.ID_INMUEBLE COD_INMUEBLE,TO_CHAR(COR.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
				 FROM SGC_TT_REGISTRO_RECONEXION cor, SGC_TT_INMUEBLES INM
				 WHERE
				 INM.CODIGO_INM=COR.ID_INMUEBLE
				 AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
				 AND COR.USR_EJE='$usuario'
				 AND COR.FECHA_EJE BETWEEN TO_DATE('$fecini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'yyyy-mm-dd hh24:mi:ss')
				 AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
				 ORDER BY COR.FECHA_EJE DESC)
		WHERE ROWNUM = 1";
        //echo $sql;
        $resultdo=oci_parse($this->_db,$sql);

        if(oci_execute($resultdo)){
            oci_close($this->_db);
            return $resultdo;
        }
        else{
            oci_close($this->_db);
            echo "error consulta ultimo punto";
            return false;
        }
    }

    public function getResCoorByRutFecUsu($ruta,$fecini,$fecfin,$usuario,$codini,$codfin)
    {

        $sql="
		 SELECT COR.LONGITUD, COR.LATITUD, COR.ID_INMUEBLE COD_INMUEBLE ,TO_CHAR(COR.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
		FROM SGC_TT_REGISTRO_RECONEXION cor, SGC_TT_INMUEBLES INM
		 WHERE
		INM.CODIGO_INM=COR.ID_INMUEBLE
		AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
		AND COR.USR_EJE='$usuario'
		AND COR.FECHA_EJE BETWEEN TO_DATE('$fecini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'yyyy-mm-dd hh24:mi:ss')
		AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'

		AND COR.ID_INMUEBLE<>'$codini'
		AND COR.ID_INMUEBLE<>'$codfin'
		ORDER BY FECHA_EJE ASC" ;
        //echo $sql;
        $resultdo=oci_parse($this->_db,$sql);

        if(oci_execute($resultdo)){
            oci_close($this->_db);
            return $resultdo;
        }
        else{
            oci_close($this->_db);
            echo "error consulta resto de  puntos";
            return false;
        }
    }

    public function getTotCoorByRutFecUsu($ruta,$fecini,$fecfin,$usuario)
    {

        $sql=" SELECT COR.LONGITUD, COR.LATITUD, COR.ID_INMUEBLE COD_INMUEBLE ,TO_CHAR(COR.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
		FROM SGC_TT_REGISTRO_RECONEXION cor, SGC_TT_INMUEBLES INM
		 WHERE
		INM.CODIGO_INM=COR.ID_INMUEBLE
		AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
		AND COR.USR_EJE='$usuario'
		AND COR.FECHA_EJE BETWEEN TO_DATE('$fecini', 'yyyy-mm-dd') AND TO_DATE('$fecfin', 'yyyy-mm-dd')
		AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'

		ORDER BY FECHA_EJE ASC" ;
        //echo $sql;
        $resultdo=oci_parse($this->_db,$sql);

        if(oci_execute($resultdo)){
            oci_close($this->_db);
            return $resultdo;
        }
        else{
            oci_close($this->_db);
            echo "error consulta resto de  puntos";
            return false;
        }
    }

    public function getInmARecByProySecZonUsu($proy,$sec,$zon,$oper,$fecha){
        $sql="SELECT  CONCAT(CONCAT(U.NOM_USR,' '),U.APE_USR) OPERARIO, TO_CHAR(R.FECHA_ACUERDO,'DD/MM/YYYY') FECACUERDO,I.CODIGO_INM,I.DIRECCION,URB.DESC_URBANIZACION,
        NVL(C.ALIAS,CLI.NOMBRE_CLI) NOMBRE,NVL(CLI.TELEFONO,I.TELEFONO) TELEFONO,I.ID_PROCESO, I.CATASTRO, R.TIPO_CORTE, ME.DESC_MED, CA.DESC_CALIBRE, MI.SERIAL
        FROM SGC_TT_REGISTRO_RECONEXION R, SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CLI, SGC_TP_ACTIVIDADES AC,
        SGC_TP_USOS USO, SGC_TP_URBANIZACIONES URB, SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TP_MEDIDORES ME,SGC_TP_CALIBRES CA
              WHERE I.CODIGO_INM=R.ID_INMUEBLE
              AND C.CODIGO_INM(+)=I.CODIGO_INM
              AND CLI.CODIGO_CLI(+)=C.CODIGO_CLI
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
              ";

        if(trim($proy)){
            $sql=$sql." AND I.ID_PROYECTO='$proy' ";
        }
        if(trim($sec)!=""){
            $sql=$sql." AND I.ID_SECTOR=$sec ";
        }
        if(trim($zon)!=""){
            $sql=$sql." AND I.ID_zona='$zon' ";
        }

        if(trim($oper)!=""){
            $sql=$sql." AND R.USR_EJE='$oper' ";
        }

        if (trim($fecha)) {
            $sql .= "AND TO_CHAR(R.FECHA_ASIGNACION, 'YYYY-MM-DD') = '$fecha' ";
        }

        $sql=$sql." ORDER BY R.USR_EJE, ID_PROCESO ";


        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    public function getValRecByByInm($inm){
        $inm=addslashes($inm);
        $sql="SELECT
                ROUND(TR.VALOR_TARIFA) VALOR_TARIFA
              FROM
                SGC_TP_TARIFAS_RECONEXION TR,
                SGC_TT_INMUEBLES I,
                SGC_TT_MEDIDOR_INMUEBLE MI,
                SGC_TP_ACTIVIDADES AC,
                SGC_TP_MEDIDORES M
              WHERE
                TR.CODIGO_DIAMETRO=I.COD_DIAMETRO AND
                I.CODIGO_INM='$inm' AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                M.CODIGO_MED(+)=MI.COD_MEDIDOR AND
                AC.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD AND
                MI.FECHA_BAJA(+) IS NULL AND
                TR.CODIGO_CALIBRE=NVL(MI.COD_CALIBRE,0) AND
                TR.CODIGO_USO=AC.ID_USO AND
                TR.MEDIDOR=NVL(M.ESTADO_MED,'N') AND
                TR.PROYECTO=I.ID_PROYECTO
              ";

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    public  function getCantSecRutRecByFecAsig($fecha,$asignado,$asignador,$proyecto,$gerencia,$contratista){
        $where="";
        if(trim($gerencia)<>""){
            $where=" AND S.ID_GERENCIA='$gerencia'";
        }

        $sql="select I.ID_SECTOR, I.ID_RUTA, COUNT(1) CANTIDAD
              from sgc_tt_registro_RECONEXION rc, SGC_tT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TT_USUARIOS U
                where
                I.CODIGO_INM=RC.ID_INMUEBLE
                AND
                S.ID_SECTOR=I.ID_SECTOR
                AND
                RC.USR_EJE is not null
               AND RC.USR_EJE=U.ID_USUARIO
              AND U.CONTRATISTA='$contratista'
                AND I.ID_PROYECTO='$proyecto'
                and RC.USUARIO_ASIGNACION is not null
                and TO_CHAR(RC.FECHA_PLANIFICACION,'YYYY-MM-DD')='$fecha'
                AND RC.USR_EJE='$asignado'
                AND RC.USUARIO_ASIGNACION='$asignador'
                $where
                group by (I.ID_SECTOR, I.ID_RUTA)";
        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    public  function getRecAsigGroupMedByFecha($fecha,$proyecto,$gerencia,$contratista){
        $where="";
        if(trim($gerencia)<>""){
            " AND S.ID_GERENCIA='$gerencia'";
        }
        $sql="select    NVL(ME.ESTADO_MED,'N') MEDIDOR, COUNT(1) CANTIDAD
              from sgc_tt_registro_RECONEXION rc, SGC_tT_INMUEBLES I, sgc_tt_medidor_inmueble mi, sgc_tp_medidores me, SGC_TT_USUARIOS U,
              SGC_TP_SECTORES S
              where
              I.CODIGO_INM=RC.ID_INMUEBLE
              and MI.COD_INMUEBLE(+)=I.CODIGO_INM
               AND RC.USR_EJE=U.ID_USUARIO
              AND U.CONTRATISTA='$contratista'
              AND ME.CODIGO_MED(+)=MI.COD_MEDIDOR
              AND I.ID_PROYECTO='$proyecto'
              AND MI.FECHA_BAJA (+)IS NULL
              and RC.USR_EJE is not null
              and RC.USUARIO_ASIGNACION is not null
              and TO_CHAR(RC.FECHA_PLANIFICACION,'YYYY-MM-DD')='$fecha'
               AND S.ID_SECTOR=I.ID_SECTOR
              $where
              group by ME.ESTADO_MED";
        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    public function getTiempoRecMesByPerPro($periodo,$pro){
        $sql="SELECT RR.FECHA_EJE,RR.FECHA_ACUERDO,
                ROUND(DIF_DIASLAB(TO_DATE(TO_CHAR(RR.FECHA_ACUERDO ,'YYYYMMDD'),'YYYYMMDD'),TO_DATE(TO_CHAR(RR.FECHA_EJE,'YYYYMMDD'),'YYYYMMDD')) )  DIAS,RR.ID_INMUEBLE,rr.ID_OTRO_RECAUDO,
                CONCAT(CONCAT(US.NOM_USR,' '),US.APE_USR) NOMBRE
                FROM SGC_TT_REGISTRO_RECONEXION RR,SGC_TT_USUARIOS US, SGC_TT_INMUEBLES INM
                WHERE US.ID_USUARIO=RR.USR_EJE
                AND RR.FECHA_EJE IS NOT NULL
                AND INM.CODIGO_INM=RR.ID_INMUEBLE
                AND INM.ID_PROYECTO='$pro'
                AND TO_CHAR(RR.FECHA_EJE,'YYYYMM')=$periodo
                order by FECHA_EJE"
        ;

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    public function getRecAnulMesByPerPro($periodo,$pro){
        $sql="SELECT
                RC.FECHA_ACUERDO,RC.ID_INMUEBLE,RC.ID_OTRO_RECAUDO,RC.FECHA_EJE,
                RC.FECHA_EJE- RC.FECHA_ACUERDO DIAS,(SELECT U.NOM_USR||' '||U.APE_USR  FROM SGC_TT_USUARIOS U WHERE U.ID_USUARIO=RC.USR_EJE) NOMBRE
                FROM SGC_TT_REGISTRO_RECONEXION RC, SGC_TT_INMUEBLES INM
                WHERE RC.ANULADO='S'
                AND INM.CODIGO_INM=RC.ID_INMUEBLE
                AND INM.ID_PROYECTO='$pro'
                AND TO_CHAR(RC.FECHA_ACUERDO,'YYYYMM')=$periodo
                ORDER BY RC.FECHA_ACUERDO";

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    public function getRecPendMesByPerPro($periodo,$proy){
        $sql="SELECT  RC.FECHA_ACUERDO,RC.ID_INMUEBLE,RC.ID_OTRO_RECAUDO,RC.FECHA_EJE,

                ROUND(DIF_DIASLAB(TO_DATE(to_char(RC.FECHA_ACUERDO,'DD/MM/YYYY'),'DD/MM/YYYY') , TO_DATE(to_char(SYSDATE,'DD/MM/YYYY'),'DD/MM/YYYY') ) ) DIAS ,(SELECT U.NOM_USR||' '||U.APE_USR  FROM SGC_TT_USUARIOS U WHERE U.ID_USUARIO=RC.USR_EJE) NOMBRE
                FROM SGC_TT_REGISTRO_RECONEXION RC, SGC_TT_INMUEBLES INM
                WHERE RC.FECHA_EJE IS NULL
                AND INM.CODIGO_INM=RC.ID_INMUEBLE
                AND RC.ANULADO='N'
                AND INM.ID_PROYECTO='$proy'
                AND TO_CHAR(RC.FECHA_ACUERDO,'YYYYMM')<=$periodo
                ORDER BY RC.FECHA_ACUERDO";
        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    public function getCantRecPorDiaByPerPro($periodo,$pro){
        $sql="  SELECT
                   TO_DATE(TO_CHAR(RC.FECHA_EJE,'YYYYMMDD'),'YYYYMMDD') FECHA_EJE,
                    COUNT(1) CANTIDAD
                FROM
                    SGC_TT_REGISTRO_RECONEXION RC,sgc_tt_INMUEBLES I
                WHERE
                    RC.ANULADO='N'
                    AND TO_CHAR(RC.FECHA_EJE,'YYYYMM')='$periodo'
                     AND RC.USR_EJE IS NOT NULL
                    AND I.CODIGO_INM=RC.ID_INMUEBLE
                    AND I.ID_PROYECTO='$pro'
                    GROUP BY TO_CHAR(RC.FECHA_EJE,'YYYYMMDD')
                ORDER BY TO_CHAR(RC.FECHA_EJE,'YYYYMMDD')";
        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    public function getCantRutRecFlexy ($tname, $where)
    {
        $sql = "SELECT COUNT(*) TOTAL FROM (SELECT  COUNT(C.ID_INMUEBLE)
        FROM $tname
        WHERE U.ID_USUARIO = C.USR_EJE
        AND C.FECHA_PLANIFICACION IS NOT NULL
        $where
        GROUP BY C.USR_EJE, U.NOM_USR, U.APE_USR, TO_CHAR(C.FECHA_PLANIFICACION,'DD/MM/YYYY'), I.ID_RUTA)";
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

    public function getResumenRutRecFlexy ($tname, $where, $sort, $start, $end)
    {
        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (
		SELECT  /*+ USE_NL(U,L) ORDERED */C.USR_EJE COD_LECTOR, (U.NOM_USR||' '||U.APE_USR)NOM_COMPLETO, TO_CHAR(C.FECHA_PLANIFICACION,'DD/MM/YYYY') FECHA_ASIG,
		COUNT(C.ID_INMUEBLE) TOTAL,CONCAT(I.ID_SECTOR,I.ID_RUTA) RUTA, SUM(DECODE(C.FECHA_EJE, '', 1, 0))NOLEIDOS
		FROM $tname
		WHERE U.ID_USUARIO = C.USR_EJE
		AND C.FECHA_PLANIFICACION IS NOT NULL
		$where
		GROUP BY C.USR_EJE, U.NOM_USR, U.APE_USR, TO_CHAR(C.FECHA_PLANIFICACION,'DD/MM/YYYY'), CONCAT(I.ID_SECTOR,I.ID_RUTA)
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

    public function getFechasEjeRecByRutFec ($periodo, $cod_ruta, $fecini, $fecfin)
    {
        if($periodo != "") $where = "C.ID_PERIODO = $periodo ";
        if($fecini != "" && $fecfin != "")
            $where = "C.FECHA_PLANIFICACION  BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
        $sql = "SELECT COUNT(*)CANTIDAD, TO_CHAR(C.FECHA_EJE,'DD/MM/YYYY')DIA
		FROM SGC_TT_REGISTRO_RECONEXION C,SGC_TT_INMUEBLES I
		WHERE $where
		AND  C.ID_INMUEBLE=I.CODIGO_INM
		AND I.ID_RUTA = '$cod_ruta'
		GROUP BY TO_CHAR(C.FECHA_EJE,'DD/MM/YYYY') ";
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

    public function getFechMaxEjeInsByRutFec ($periodo, $cod_ruta, $dia_calc, $fecini, $fecfin)
    {
        if($periodo != "") $where = "C.ID_PERIODO = $periodo ";
        if($fecini != "" && $fecfin != "")
            $where = "C.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
        $sql = "SELECT MAX(TO_CHAR(c.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS')) FEC_MAX
            FROM SGC_TT_REGISTRO_RECONEXION C, SGC_TT_INMUEBLES I
            WHERE $where AND  C.ID_INMUEBLE=I.CODIGO_INM AND I.ID_RUTA = '$cod_ruta'
            AND TO_CHAR(C.FECHA_EJE,'DD/MM/YYYY') = '$dia_calc'";
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

    public function getFechMinEjeInsByRutFec ($periodo, $cod_ruta, $dia_calc, $fecini, $fecfin)
    {
        if($periodo != "") $where = "C.ID_PERIODO = $periodo ";
        if($fecini != "" && $fecfin != "")
            $where = "C.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
        $sql = "SELECT MIN(TO_CHAR(C.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS')) FEC_MIN
        	FROM SGC_TT_REGISTRO_RECONEXION C, SGC_tt_INMUEBLES I
        	WHERE $where AND
        	 C.ID_INMUEBLE=I.CODIGO_INM AND
        	 I.ID_RUTA = '$cod_ruta'
       		AND TO_CHAR(c.FECHA_EJE,'DD/MM/YYYY') = '$dia_calc'";
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


    public function getCantDetalleRec ($fname,$tname,$where)
    {

        $sql="SELECT COUNT(1) CANTIDAD FROM (
		SELECT  INM.CODIGO_INM  FROM SGC_TT_REGISTRO_RECONEXION C, SGC_TT_INMUEBLES INM
		WHERE INM.CODIGO_INM=C.ID_INMUEBLE $where
		)";
        //echo $sql;
        $resultado = oci_parse($this->_db,$sql);
        if(oci_execute($resultado))
        {
            while (oci_fetch($resultado)) {
                $cantidad = oci_result($resultado, 'CANTIDAD');
            }oci_free_statement($resultado);
            oci_close($this->_db);
            return $cantidad;



        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }



    public function getTodosDetalleRec ($where,$sort,$start,$end)
    {
        $sql="SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
		FROM (
		SELECT INM.CODIGO_INM, INM.CATASTRO,O.ALIAS,C.TIPO_RECONEXION,C.IMPO_REC,  MI.SERIAL, C.LECTURA, C.OBS_GENERALES, C.LATITUD,C.LONGITUD, INM.ID_PROCESO,INM.DIRECCION, to_char(C.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHA_EJECUCION
		FROM SGC_TT_REGISTRO_RECONEXION C, SGC_TT_INMUEBLES INM,SGC_TT_MEDIDOR_INMUEBLE MI,
		SGC_TT_CONTRATOS O
		WHERE
		O.CODIGO_INM(+)=INM.CODIGO_INM
		AND INM.CODIGO_INM=C.ID_INMUEBLE
		AND O.FECHA_FIN (+) IS NULL
		AND MI.COD_INMUEBLE(+)=INM.CODIGO_INM
		AND MI.FECHA_BAJA (+)  IS NULL
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

    public function getExisteFotoRec($inmueble,$fechaini,$fechafin,$operario)
    {
        $sql="SELECT count(1) CANTIDAD FROM SGC_TT_FOTOS_RECONEXION FC,SGC_TT_REGISTRO_RECONEXION C
		WHERE C.ID_INMUEBLE=FC.ID_INMUEBLE
		AND C.USR_EJE='$operario'
		AND FC.ID_INMUEBLE='$inmueble'
		AND C.USR_EJE<>'1024525260'
		AND TO_CHAR(FC.FECHA,'YYYYMMDD')>= TO_CHAR(TO_DATE('$fechaini', 'DD/MM/YYYY hh24:mi:ss'),'YYYYMMDD') AND TO_CHAR(FC.FECHA,'YYYYMMDD')<= TO_CHAR(TO_DATE('$fechafin', 'DD/MM/YYYY hh24:mi:ss'),'YYYYMMDD')  ";

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

    public function getExisteCoordenadaRec($inmueble,$fechaini,$operario)
    {
          $sql="SELECT COUNT(*) CANTIDAD FROM  SGC_TT_REGISTRO_RECONEXION C
		where
		C.USR_EJE='$operario'
		AND C.USR_EJE<>'1024525260'
		AND C.ID_INMUEBLE='$inmueble'
		AND TO_CHAR(C.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS')='$fechaini'
		AND C.LATITUD>0 ";


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


    public function getUrlFotoRec($inmueble,$fechaini,$fechafin)
    {
        $sql="SELECT DISTINCT URL_FOTO  FROM SGC_TT_FOTOS_RECONEXION FC,SGC_TT_REGISTRO_RECONEXION C
		WHERE C.ID_INMUEBLE=FC.ID_INMUEBLE
		AND FC.ID_INMUEBLE='$inmueble'
		AND TO_CHAR(FC.FECHA,'YYYYMMDD')>= TO_CHAR(TO_DATE('$fechaini', 'DD/MM/YYYY hh24:mi:ss'),'YYYYMMDD') AND TO_CHAR(FC.FECHA,'YYYYMMDD')<= TO_CHAR(TO_DATE('$fechafin', 'DD/MM/YYYY hh24:mi:ss'),'YYYYMMDD')
		  ";

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
