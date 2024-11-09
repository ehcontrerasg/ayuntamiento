<?php
include_once "class.conexion.php";


class InspeccionCorte extends ConexionClass
{


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

    public function getPriCoorByRutFecUsu($ruta,$fecini,$fecfin,$usuario)
    {

        $sql="   SELECT * FROM (
				 SELECT IC.LONGITUD, IC.LATITUD, IC.CODIGO_INM ,TO_CHAR(IC.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
				 FROM SGC_TT_INSPECCIONES_CORTES IC, SGC_TT_INMUEBLES INM
				 WHERE
				 INM.CODIGO_INM=IC.CODIGO_INM
				 AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
				 AND IC.USR_ASIG='$usuario'
				 AND IC.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini', 'yyyy-mm-dd') AND TO_DATE('$fecfin', 'yyyy-mm-dd')
				 AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
				 ORDER BY IC.FECHA_EJE ASC)
				 WHERE ROWNUM = 1";
        //echo $sql;
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
				 SELECT IC.LONGITUD, IC.LATITUD, IC.CODIGO_INM COD_INMUEBLE,TO_CHAR(IC.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
				 FROM SGC_TT_INSPECCIONES_CORTES IC, SGC_TT_INMUEBLES INM
				 WHERE
				 INM.CODIGO_INM=IC.CODIGO_INM
				 AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
				 AND IC.USR_ASIG='$usuario'
				 AND IC.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini', 'yyyy-mm-dd') AND TO_DATE('$fecfin', 'yyyy-mm-dd')
				 AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'
				 ORDER BY IC.FECHA_EJE DESC)
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
		 SELECT IC.LONGITUD, IC.LATITUD, IC.CODIGO_INM COD_INMUEBLE ,TO_CHAR(IC.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
		FROM SGC_TT_INSPECCIONES_CORTES IC, SGC_TT_INMUEBLES INM
		 WHERE
		INM.CODIGO_INM=IC.CODIGO_INM
		AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
		AND IC.USR_ASIG='$usuario'
		AND IC.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini', 'yyyy-mm-dd') AND TO_DATE('$fecfin', 'yyyy-mm-dd')
		AND LONGITUD IS NOT NULL  AND LONGITUD <>'0'

		AND IC.CODIGO_INM<>'$codini'
		AND IC.CODIGO_INM<>'$codfin'
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

        $sql=" SELECT IC.LONGITUD, IC.LATITUD, IC.CODIGO_INM COD_INMUEBLE ,TO_CHAR(IC.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHAFIN
		FROM SGC_TT_INSPECCIONES_CORTES IC, SGC_TT_INMUEBLES INM
		 WHERE
		INM.CODIGO_INM=IC.CODIGO_INM
		AND CONCAT(INM.ID_SECTOR,INM.ID_RUTA)='$ruta'
		AND IC.USR_ASIG='$usuario'
		AND IC.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini', 'yyyy-mm-dd') AND TO_DATE('$fecfin', 'yyyy-mm-dd')
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

    public function getInsByProSecZonUsu($proy,$sec,$zon,$oper,$inm,$fechaAsig){
        $sql="SELECT R.CONSEC_INSPECCION ORDEN, to_char(SYSDATE,'DD/MM/YYYY') FECHACT, CONCAT(CONCAT(U.NOM_USR,' '),U.APE_USR) OPERARIO,I.CODIGO_INM,I.DIRECCION,URB.DESC_URBANIZACION,
        NVL(C.ALIAS,CLI.NOMBRE_CLI) NOMBRE,S.DESC_SERVICIO, NVL(CLI.TELEFONO,I.TELEFONO) TELEFONO,I.ID_PROCESO, I.CATASTRO, R.TIPO_CORTE, ME.DESC_MED, CA.DESC_CALIBRE, MI.SERIAL,DESC_USO,
        (SELECT DC.DESC_CALIBRE FROM SGC_TP_CALIBRES DC WHERE DC.COD_CALIBRE=I.COD_DIAMETRO) DIAMETRO,
        U.ID_USUARIO
        FROM SGC_TT_INSPECCIONES_CORTES R, SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CLI, SGC_TP_ACTIVIDADES AC,
        SGC_TP_USOS USO, SGC_TP_URBANIZACIONES URB, SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TP_MEDIDORES ME,SGC_TP_CALIBRES CA,
        sgc_tt_servicios_inmuebles si, SGC_TP_SERVICIOS S
              WHERE I.CODIGO_INM=R.CODIGO_INM
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
              AND R.USR_ASIG=U.ID_USUARIO
              AND R.USR_ASIG IS NOT NULL
              AND R.FECHA_EJE IS NULL
              AND I.ID_ESTADO='SS'
              ";

        if(trim($proy)){
            $sql=$sql." AND I.ID_PROYECTO='$proy' ";
        }
        if (trim($inm) != '') {
            $sql .= " AND I.CODIGO_INM = '$inm' ";
        }
        if(trim($sec)!=""){
            $sql=$sql." AND I.ID_SECTOR=$sec ";
        }
        if(trim($zon)!=""){
            $sql=$sql." AND I.ID_zona='$zon' ";
        }

        if(trim($oper)!=""){
            $sql=$sql." AND R.USR_ASIG='$oper' ";
        }

        if ($fechaAsig != '') {
            $sql .= " AND TO_CHAR(R.FECHA_ASIG, 'YYYY-MM-DD') = '$fechaAsig' ";
        }

        $sql=$sql." ORDER BY R.USR_ASIG, ID_PROCESO ";


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

    public function GetCantInmueblesAInsByProSecZonUsu($proy,$sec,$rut,$oper){
          $sql="SELECT COUNT(1) NUMERO       FROM SGC_TT_INSPECCIONES_CORTES R, SGC_TT_INMUEBLES I
            WHERE I.CODIGO_INM=R.CODIGO_INM
            AND R.USR_ASIG IS NOT NULL
            AND R.FECHA_EJE IS NULL
              ";

        if(trim($proy)){
            $sql=$sql." AND I.ID_PROYECTO='$proy' ";
        }
        if(trim($sec)!=""){
            $sql=$sql." AND I.ID_SECTOR=$sec ";
        }
        if(trim($rut)!=""){
            $sql=$sql." AND I.ID_RUTA='$rut' ";
        }

        if(trim($oper)!=""){
            $sql=$sql." AND R.USR_ASIG='$oper' ";
        }

        $sql=$sql." ORDER BY R.USR_ASIG, ID_PROCESO ";


         $sql;

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

    public function setDesAsigInsByZon($zona,$desasignar_loc, $inm){
        $sql="BEGIN SGC_P_DESASIGINS('$zona','$desasignar_loc', '$inm',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        //echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,"123");
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror,"123");
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

    public function setAperLotInsByZonUsu($zona,$usrAper){
        $sql="BEGIN  SGC_P_APERINSCORTE('$zona','$usrAper',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        // echo $sql;
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msresult,"123");
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

    public function setAsignaIns( $zona,$ruta,$operario,$asignador,$uso,$medidor,$facini,$facfin,$fecPla,$inm,$cat){
       echo  $sql="BEGIN SGC_P_ASIGINS('$zona',$ruta,'$operario','$asignador','$uso','$medidor','$facini','$facfin','$fecPla','$inm','$cat',:PMSGRESULT,:PCODRESULT) ;COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msresult,123);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult,123);
        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }
        else{
            oci_close($this->_db);
            echo "error consulta asigna inspeccion";
            return $this->sqlstr;
        }


    }

//prueba
    public function setDesAsignacionIns($zona,$facin,$facfin, $inm,$calibre){
        $sql = "SELECT COUNT(I.CODIGO_INM)CANTIDAD, CONCAT(I.ID_SECTOR,I.ID_RUTA) RUTA, ID_USUARIO, NOM_USR, APE_USR
        FROM SGC_TT_REGISTRO_cortes rc, SGC_TT_USUARIOS U, sgc_TT_INMUEBLES I,SGC_TT_INSPECCIONES_CORTES IR
        WHERE
        IR.ORDEN_CORTE=RC.ORDEN
        AND
        U.ID_USUARIO = IR.USR_ASIG
        AND RC.ID_INMUEBLE=I.CODIGO_INM
        AND IR.USR_ASIG IS NOT NULL AND IR.FECHA_EJE IS NULL";

        if (trim($inm)) {
            $sql .= " AND I.CODIGO_INM = '$inm'";
        }
        if (trim($zona) != '') {
            $sql .= " AND I.ID_ZONA='$zona'";
        }

        if ($calibre != "") {
            $sql .= " AND I.COD_DIAMETRO=$calibre";
        }

        if (trim($facin)!=''){

            $sql=$sql."
            AND RC.ID_INMUEBLE IN (
                select F.INMUEBLE from sgc_tt_factura f
                WHERE
                F.FACTURA_PAGADA='N'
            AND F.FEC_EXPEDICION IS NOT NULL
            AND F.INMUEBLE =RC.ID_INMUEBLE
                HAVING COUNT(1)>='$facin' AND COUNT(1)<='$facfin'
                GROUP BY F.INMUEBLE

        )";
        }


        $sql.="GROUP BY  I.ID_SECTOR,I.ID_RUTA, ID_USUARIO, NOM_USR, APE_USR ORDER BY I.ID_SECTOR,I.ID_RUTA";
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
// funcion
public function getCantInsRutMedByZonUsoFac($zona,$uso,$facin,$facfin,$inm,$calibre,$categoria){

        $sql = "SELECT COUNT(I.CODIGO_INM)CANTIDAD, CONCAT(I.ID_SECTOR,I.ID_RUTA)  RUTA,NVL(ME.ESTADO_MED,'N') MEDIDO,
                    TAR.CATEGORIA
                from SGC_TT_INMUEBLES I, SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TP_MEDIDORES ME,SGC_TT_INSPECCIONES_CORTES IC,
                SGC_TT_REGISTRO_CORTES RC, SGC_TT_SERVICIOS_INMUEBLES SI,SGC_TP_TARIFAS TAR ";
        ///// uso
        if(trim($uso)!=""){
            $sql .= " , SGC_TP_ACTIVIDADES ACT ";
        }

        $sql .= " WHERE IC.ORDEN_CORTE=RC.ORDEN
                    AND RC.ID_INMUEBLE=I.CODIGO_INM
                    AND I.CODIGO_INM=MI.COD_INMUEBLE(+)
                    AND MI.COD_MEDIDOR=ME.CODIGO_MED(+)
                    AND MI.FECHA_BAJA (+) IS NULL
                    AND IC.USR_ASIG IS NULL 
                    AND SI.COD_INMUEBLE=I.CODIGO_INM
                    AND SI.COD_SERVICIO IN (1,3)
                    AND SI.CONSEC_TARIFA=TAR.CONSEC_TARIFA
                    ";

        if (!is_null($zona)) {
            $sql .= " AND I.ID_ZONA='$zona' ";
        }

    if ($calibre != "") {
        $sql .=  " AND I.COD_DIAMETRO=$calibre ";
    }

        ///// uso
        if(trim($uso)!=""){

            $sql .=" AND ACT.ID_USO='$uso'   AND ACT.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD";
        }  ///// categoria
        if(trim($categoria)!=""){

            $sql .=" AND TAR.CATEGORIA='$categoria' ";
        }

        if (trim($facin)!=''){
          /*  if ($calibre != "") {
                $sql2 =" AND I.COD_DIAMETRO=$calibre ";
            }*/

            $sql .= "
            AND RC.ID_INMUEBLE IN (
                select F.INMUEBLE from sgc_tt_factura f
                WHERE
                F.FACTURA_PAGADA='N'
            AND F.FEC_EXPEDICION IS NOT NULL
            AND F.INMUEBLE =RC.ID_INMUEBLE
                HAVING COUNT(1)>='$facin' AND COUNT(1)<='$facfin'
                GROUP BY F.INMUEBLE

        )";
        }

        if (trim($inm) != '') {
            $sql .= " 
            AND I.CODIGO_INM ='$inm' ";
        }

        $sql .= "  GROUP BY I.ID_SECTOR,I.ID_RUTA,NVL(ME.ESTADO_MED,'N'),TAR.CATEGORIA
        ORDER BY I.ID_SECTOR,I.ID_RUTA,NVL(ME.ESTADO_MED,'N') ASC  ";

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

    public function getCantRutInsFlexy ($tname, $where)
    {
        $sql = "SELECT COUNT(*) TOTAL FROM (SELECT  COUNT(IC.CODIGO_INM)
        FROM $tname
        WHERE U.ID_USUARIO = IC.USR_ASIG
        AND IC.FECHA_PLANIFICACION IS NOT NULL
        $where
        GROUP BY IC.USR_ASIG, U.NOM_USR, U.APE_USR, TO_CHAR(IC.FECHA_PLANIFICACION,'DD/MM/YYYY'), I.ID_RUTA)";
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

    public function getResumenRutInsFlexy ($tname, $where, $sort, $start, $end)
    {
         $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (
		SELECT  /*+ USE_NL(U,L) ORDERED */IC.USR_ASIG COD_LECTOR, (U.NOM_USR||' '||U.APE_USR)NOM_COMPLETO, TO_DATE(TO_CHAR(IC.FECHA_PLANIFICACION,'DD/MM/YYYY'),'DD/MM/YYYY')FECHA_ASIG,
		COUNT(IC.CODIGO_INM) TOTAL,  CONCAT(I.ID_SECTOR,ID_RUTA) RUTA, SUM(DECODE(IC.FECHA_EJE, '', 1, 0))NOLEIDOS
		FROM $tname
		WHERE U.ID_USUARIO = IC.USR_ASIG
		AND IC.FECHA_ASIG IS NOT NULL
		$where
		GROUP BY IC.USR_ASIG, U.NOM_USR, U.APE_USR, TO_CHAR(IC.FECHA_PLANIFICACION,'DD/MM/YYYY'), CONCAT(I.ID_SECTOR,ID_RUTA)
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

    public function getFechasEjeInsByRutFec ($cod_ruta, $fecini, $fecfin)
    {
        if($fecini != "" && $fecfin != "")
            $where = "IC.FECHA_PLANIFICACION  BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
        $sql = "SELECT COUNT(*)CANTIDAD, TO_CHAR(IC.FECHA_EJE,'DD/MM/YYYY')DIA
		FROM SGC_TT_INMUEBLES I, SGC_TT_INSPECCIONES_CORTES IC
		WHERE $where
		AND  IC.CODIGO_INM=I.CODIGO_INM
		AND I.ID_RUTA = $cod_ruta
		GROUP BY TO_CHAR(IC.FECHA_EJE,'DD/MM/YYYY') ";
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

    public function getFechMaxEjeInsByRutFec($cod_ruta, $dia_calc, $fecini, $fecfin)
    {
        //echo "diacalc ".$dia_calc;
        if($fecini != "" && $fecfin != "")
            $where = "IC.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
        $sql = "SELECT MAX(TO_CHAR(IC.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS')) FEC_MAX
            FROM SGC_TT_INMUEBLES I, SGC_TT_INSPECCIONES_CORTES IC
            WHERE $where
            AND IC.CODIGO_INM=I.CODIGO_INM AND I.ID_RUTA = $cod_ruta
            AND TO_CHAR(IC.FECHA_EJE,'DD/MM/YYYY') = '$dia_calc'";
        $resultado = oci_parse($this->_db,$sql);

        //echo $sql;
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

    public function getFechMinEjeInsByRutFec ($cod_ruta, $dia_calc, $fecini, $fecfin)
    {
        if($fecini != "" && $fecfin != "")
            $where = "IC.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecini 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD HH24:MI:SS')";
        $sql = "SELECT MIN(TO_CHAR(IC.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS')) FEC_MIN
        	FROM  SGC_tt_INMUEBLES I, SGC_TT_INSPECCIONES_CORTES IC
        	WHERE $where AND
        	 IC.CODIGO_INM=I.CODIGO_INM AND
        	 I.ID_RUTA = '$cod_ruta'
       		AND TO_CHAR(IC.FECHA_EJE,'DD/MM/YYYY') = '$dia_calc'";
       // echo $sql;
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


    public function CantidaddetalleIns ($fname,$tname,$where)
    {

        $sql="SELECT COUNT(1) CANTIDAD FROM (
		SELECT  INM.CODIGO_INM  FROM SGC_TT_INSPECCIONES_CORTES IC, SGC_TT_INMUEBLES INM
		WHERE INM.CODIGO_INM=IC.CODIGO_INM $where
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

    public function TodosDetalleIns ($where,$sort,$start,$end)
    {
        $sql="SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
		FROM (
		SELECT IC.CONSEC_INSPECCION, INM.CODIGO_INM, INM.CATASTRO,O.ALIAS, MI.SERIAL,IC.OBSERVACION OBS,IC.RECONECTADO,  IC.LATITUD,IC.LONGITUD, INM.ID_PROCESO,INM.DIRECCION, to_char(IC.FECHA_EJE,'DD/MM/YYYY HH24:MI:SS') FECHA_EJECUCION
		FROM SGC_TT_INSPECCIONES_CORTES IC, SGC_TT_INMUEBLES INM,SGC_TT_MEDIDOR_INMUEBLE MI,
		SGC_TT_CONTRATOS O
		WHERE
		O.CODIGO_INM(+)=INM.CODIGO_INM
		AND INM.CODIGO_INM=IC.CODIGO_INM
		AND O.FECHA_FIN (+) IS NULL
		AND MI.COD_INMUEBLE(+)=INM.CODIGO_INM
		AND MI.FECHA_BAJA (+)  IS NULL
		$where $sort
		)a WHERE rownum <= $start
		) WHERE rnum >= $end+1";
        //echo $sql;
        $resultado = oci_parse($this->_db,$sql);
        if(oci_execute($resultado))
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

    public function existefotoins($inmueble,$fechaini,$fechafin,$operario)
    {
        $sql="SELECT count(1) CANTIDAD FROM SGC_TT_FOTOS_INSPECCION FC,SGC_TT_INSPECCIONES_CORTES C
        WHERE C.CODIGO_INM=FC.ID_INMUEBLE
        AND C.USR_ASIG='$operario'
        AND FC.ID_INMUEBLE='$inmueble'
        AND C.USR_ASIG<>'1024525260'
        AND FC.FECHA BETWEEN TO_DATE('$fechaini 00:00:00', 'yyyy-mm-dd hh24:mi:ss') AND TO_DATE('$fechafin 23:59:59', 'yyyy-mm-dd hh24:mi:ss' ) ";

       // echo $sql;
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




    public function existecoordenadains($ins)
    {
        $sql="SELECT COUNT(*) CANTIDAD FROM  SGC_TT_INSPECCIONES_CORTES IC
		where
		IC.CONSEC_INSPECCION=$ins
		AND IC.LATITUD>0 ";


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

    public function urlfotoIns($inmueble,$fecini,$fecfin)
    {
        $sql=" SELECT DISTINCT URL_FOTO  FROM SGC_TT_FOTOS_INSPECCION FC,SGC_TT_INSPECCIONES_CORTES C
        WHERE C.CODIGO_INM=FC.ID_INMUEBLE
        AND FC.ID_INMUEBLE='$inmueble'
        AND FC.FECHA BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD hh24:mi:ss') AND TO_DATE('$fecfin 23:59:59', 'YYYY-MM-DD hh24:mi:ss')   ";

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

    public function getInspEjecutadas($proyecto,$fechaIn,$fechaFn,$contratista,$reconect){

        $where="";
        if ($reconect!='')
            $where.=" AND ip.RECONECTADO='$reconect' ";

        if ($contratista!='')
            $where.=" AND u.CONTRATISTA='$contratista' ";

            $sql = "select ip.CODIGO_INM,ip.FECHA_EJE,ip.TIPO_CORTE TIPO_CORTE_ENCONTRADO_TERRENO, ip.TIPO_CORTE_EJE TIPO_CORTE_EJECUTADO,ip.FECHA_ASIG,u.LOGIN,ip.RECONECTADO,ip.USR_ASIG
from SGC_TT_INSPECCIONES_CORTES ip, SGC_TT_USUARIOS u,SGC_TT_INMUEBLES i
where ip.USR_ASIG=u.ID_USUARIO
and ip.CODIGO_INM=i.CODIGO_INM
and i.ID_PROYECTO='$proyecto'
and ip.FECHA_EJE between TO_DATE('$fechaIn 00:01','YYYY/MM/DD HH24:MI' )and TO_DATE('$fechaFn 23:59','YYYY/MM/DD HH24:MI')
and ip.FECHA_EJE is not null $where";

      //  echo $sql;
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

            return false;
        }

    }


    public function getInsXFecAsig($fecIni, $fecFin, $proy, $cont)
    {
        $fecIni = addslashes($fecIni);
        $fecFin = addslashes($fecFin);
        $proy = addslashes($proy);
        $cont = addslashes($cont);

        $sql = "SELECT IC.CODIGO_INM,IC.FECHA_EJE,IC.TIPO_CORTE,
  IC.TIPO_CORTE_EJE,IC.RECONECTADO,IC.FECHA_PLANIFICACION,
  USU.LOGIN,TC.MEDIDOR,I.ID_SECTOR,I.ID_RUTA,
  ORE.IMPORTE,ORE.FECHA_PAGO,RC.GESTION,
  (select count(1) from SGC_TT_FACTURA f
  where f.FECHA_PAGO<ORE.FECHA_PAGO+1 and
        f.FECHA_PAGO>ORE.FECHA_PAGO-3 and
        rc.ID_INMUEBLE=f.INMUEBLE
  )FACT_PEND

FROM SGC_TT_INSPECCIONES_CORTES iC , SGC_TT_INMUEBLES I,
  SGC_TT_USUARIOS USU, SGC_TP_TIPO_CORTE TC,SGC_TT_REGISTRO_CORTES RC,
  SGC_TT_OTROS_RECAUDOS ORE
WHERE I.CODIGO_INM=IC.CODIGO_INM AND
      IC.FECHA_PLANIFICACION BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24/MI/SS') AND
      TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24/MI/SS') AND
      I.ID_PROYECTO='$proy' AND
      USU.ID_USUARIO=IC.USR_ASIG AND
      USU.CONTRATISTA=$cont AND
      TC.CODIGO=IC.TIPO_CORTE AND
      RC.ORDEN=IC.ORDEN_CORTE AND
      ORE.CODIGO(+)=RC.ID_OTRO_RECAUDO
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

    public function getInsXFecEjec($fecIni, $fecFin, $proy, $cont)
    {
        $fecIni = addslashes($fecIni);
        $fecFin = addslashes($fecFin);
        $proy = addslashes($proy);
        $cont = addslashes($cont);

        $sql = "SELECT IC.CODIGO_INM,IC.FECHA_EJE,IC.TIPO_CORTE,
  IC.TIPO_CORTE_EJE,IC.RECONECTADO,IC.FECHA_PLANIFICACION,
  USU.LOGIN,TC.MEDIDOR,I.ID_SECTOR,I.ID_RUTA,
  ORE.IMPORTE,ORE.FECHA_PAGO,RC.GESTION,
  (select count(1) from SGC_TT_FACTURA f
  where f.FECHA_PAGO<ORE.FECHA_PAGO+1 and
        f.FECHA_PAGO>ORE.FECHA_PAGO-3 and
        rc.ID_INMUEBLE=f.INMUEBLE
  )FACT_PEND

FROM SGC_TT_INSPECCIONES_CORTES iC , SGC_TT_INMUEBLES I,
  SGC_TT_USUARIOS USU, SGC_TP_TIPO_CORTE TC,SGC_TT_REGISTRO_CORTES RC,
  SGC_TT_OTROS_RECAUDOS ORE
WHERE I.CODIGO_INM=IC.CODIGO_INM AND
      IC.FECHA_EJE BETWEEN TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24/MI/SS') AND
      TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24/MI/SS') AND
      I.ID_PROYECTO='$proy' AND
      USU.ID_USUARIO=IC.USR_ASIG AND
      USU.CONTRATISTA=$cont AND
      TC.CODIGO=IC.TIPO_CORTE AND
      RC.ORDEN=IC.ORDEN_CORTE AND
      ORE.CODIGO(+)=RC.ID_OTRO_RECAUDO
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