<?php
include_once "class.conexion.php";


class Pqr extends ConexionClass{


    public function __construct()
    {
        parent::__construct();
    }

    public function getPqrByInmFlexy ($codinmueble,$sort)
    {
        $sql="SELECT DISTINCT P.CODIGO_PQR, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS')FECHA_PQR, T.DESC_TIPO_RECLAMO, R.DESC_MOTIVO_REC,
		DECODE(P.CERRADO,'S','Cerrado','N','Abierto')CERRADO, DECODE(P.DIAGNOSTICO,'1','Procedente','2','No Procedente')DIAGNOSTICO
        FROM SGC_TT_PQRS P, SGC_TP_TIPOS_RECLAMOS T, SGC_TP_MOTIVO_RECLAMOS R
        WHERE P.TIPO_PQR = T.ID_TIPO_RECLAMO
        AND P.MOTIVO_PQR = R.ID_MOTIVO_REC
        AND FECHA_PQR >= TO_DATE('01/08/2024','DD/MM/YYYY')
          --AND P.AYUNTAMIENTO = 'S'
		AND P.COD_INMUEBLE = '$codinmueble'
		$sort";
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

    public function getDocByInmFlexy ($codinmueble,$sort)
    {
        $sql="SELECT ID_REGISTRO, CODIGO_ARCH, A.DESC_AREA AREA, D.DESC_DOCUMENTO TIPDOC, TO_CHAR(FECHA_DOCUMENTO,'DD/MM/YYYY') FECHA, OBSERVACION, SUBSTR(RUTA_ARCHIVO,3) RUTA
        FROM SGC_TT_REGISTRO R, SGC_TP_AREAS A, SGC_TP_TIP_DOCUMENTOS D
        WHERE A.ID_AREA = R.ID_AREA
        AND R.TIP_DOCUMENTOS = D.TIP_DOCUMENTOS
        AND R.CODIGO_INM = '$codinmueble'
		$sort";
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

    public function getTipPqr(){
        $sql = "SELECT ID_TIPO_RECLAMO, DESC_TIPO_RECLAMO
		FROM SGC_TP_TIPOS_RECLAMOS WHERE CLASIFICACION = 'P'
		AND ID_TIPO_RECLAMO IN (1,2)";
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
    public function getMotRecl(){
        $sql = "
            SELECT
              DISTINCT
              MR.DESC_MOTIVO_REC DESCRIPCION,
              MR.ID_MOTIVO_REC CODIGO
            FROM
              SGC_TP_MOTIVO_RECLAMOS MR
            ORDER BY
              MR.DESC_MOTIVO_REC";
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

    public function getCantPqrByProTipSecRutFecCodAreFlexy ($proyecto, $tipo_pqr, $secini, $secfin, $rutini, $rutfin, $fecini, $fecfin, $cod_inmueble, $where, $area_user){
        $sql = "SELECT COUNT(P.CODIGO_PQR) CANTIDAD
        FROM SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M,  SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A
        WHERE P.MOTIVO_PQR = M.ID_MOTIVO_REC
        AND P.GERENCIA = M.GERENCIA
        AND I.CODIGO_INM = P.COD_INMUEBLE
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = F.CODIGO_PQR
        AND A.ID_AREA = F.AREA_ACTUAL
        AND P.CERRADO = 'N'
        AND F.FECHA_SALIDA IS NULL
		AND F.AREA_ACTUAL = '$area_user' $where";
        if ($tipo_pqr != '') $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
        if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
        if ($secini != '' && $secfin != '') $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
        if ($rutini != '' && $rutfin != '') $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
        if ($cod_inmueble != '') $sql .= " AND P.COD_INMUEBLE = '$cod_inmueble'";
        if ($fecini != '' && $fecfin != '') $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
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

    public function getDatosPqrByProTipSecRutFecInmFlexy($proyecto, $tipo_pqr, $secini, $secfin, $rutini, $rutfin, $fecini, $fecfin, $cod_inmueble, $start, $end, $where, $area_user){
        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
		FROM (SELECT P.CODIGO_PQR, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS')FECHAPQR, P.COD_INMUEBLE, (P.MOTIVO_PQR||'-'||M.DESC_MOTIVO_REC)MOTIVO,
        P.COD_ENTIDAD, U.LOGIN, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECHAMAX, I.ID_PROCESO, A.DESC_AREA,
        CASE WHEN (DIF_DIASLAB(SYSDATE,P.FECHA_MAX_RESOL)) <= 0 THEN 0 ELSE (DIF_DIASLAB(SYSDATE,P.FECHA_MAX_RESOL)) END PORVENCER,
        CASE WHEN (DIF_DIASLAB(SYSDATE,P.FECHA_MAX_RESOL)) >= 0 THEN 0 ELSE (DIF_DIASLAB(SYSDATE,P.FECHA_MAX_RESOL))END VENCIDOS
        FROM SGC_TT_PQRS P, SGC_TP_MOTIVO_RECLAMOS M,  SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A
        WHERE P.MOTIVO_PQR = M.ID_MOTIVO_REC
        AND P.GERENCIA = M.GERENCIA
        AND I.CODIGO_INM = P.COD_INMUEBLE
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = F.CODIGO_PQR
        AND A.ID_AREA = F.AREA_ACTUAL
        AND P.CERRADO = 'N'
        AND F.FECHA_SALIDA IS NULL
		AND F.AREA_ACTUAL = '$area_user' $where";
        if ($tipo_pqr != '') $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
        if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
        if ($secini != '' && $secfin != '') $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
        if ($rutini != '' && $rutfin != '') $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
        if ($cod_inmueble != '') $sql .= " AND P.COD_INMUEBLE = '$cod_inmueble'";
        if ($fecini != '' && $fecfin != '') $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        $sql .= " ORDER BY TO_DATE(TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS'),'DD/MM/YYYY hh24:mi:ss') DESC)a WHERE rownum <= $start ) WHERE rnum >= $end+1";
    //    echo $sql;
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

    public function getDatPqrByPqr($cod_pqr){
        $sql = "SELECT P.COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, P.TEL_CLIENTE, P.DESCRIPCION, F.AREA_ACTUAL, F.ORDEN, P.GERENCIA
        FROM SGC_TT_PQRS P, SGC_TT_PQR_FLUJO F
        WHERE P.CODIGO_PQR = F.CODIGO_PQR
        AND F.FECHA_SALIDA IS NULL
        AND P.CODIGO_PQR = '$cod_pqr'";
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

    public function getResolucionesPqrsByPqr ($cod_pqr){
        $sql = "SELECT A.DESC_AREA, F.RESPUESTA
		FROM SGC_TT_PQR_FLUJO F, SGC_TP_AREAS A
		WHERE A.ID_AREA = F.AREA_ACTUAL
		AND F.CODIGO_PQR = '$cod_pqr'
		AND F.FECHA_SALIDA IS NOT NULL
		ORDER BY F.ORDEN ASC";
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

    public function setFlujoPqr($cod_inm,$cod_pqr,$resolucion,$area_act,$orden,$coduser,$area_res){
        $sql="BEGIN SGC_P_INGRESA_FLUJO_PQR('$cod_inm','$cod_pqr','$resolucion','$area_act','$orden','$coduser','$area_res',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

        //echo $sql;
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

    public function getDatTotPqrByPqr ($cod_pqr){
        $sql = "SELECT P.COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, P.TEL_CLIENTE, P.DESCRIPCION, P.GERENCIA, P.MEDIO_REC_PQR, R.DESC_MEDIO_REC, P.TIPO_PQR,
        M.DESC_MOTIVO_REC,P.MOTIVO_PQR, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECMAX, P.COD_ENTIDAD, E.DESC_ENTIDAD, P.COD_PUNTO, PP.DESCRIPCION DESCPUNTO, P.USER_RECIBIO_PQR,
        U.NOM_USR||' '||U.APE_USR USUARIOREC ,TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY')FECREG, I.ID_PROCESO, I.CATASTRO, T.DESC_TIPO_RECLAMO, P.EMAIL_CLIENTE,
        MI.SERIAL SERIAL, (SELECT RL.LECTURA_ACTUAL FROM SGC_TT_REGISTRO_LECTURAS RL WHERE RL.COD_INMUEBLE=I.CODIGO_INM
        AND TO_CHAR(RL.FECHA_LECTURA_ORI,'DDMMYYYY') =(SELECT TO_CHAR(MAX(R2.FECHA_LECTURA_ORI),'DDMMYYYY')
		FROM SGC_TT_REGISTRO_LECTURAS R2  WHERE R2.FECHA_LECTURA_ORI<=P.FECHA_REGISTRO AND R2.COD_INMUEBLE=I.CODIGO_INM ) ) LECTURA, I.ID_PROYECTO ACUEDUCTO
        FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TP_MOTIVO_RECLAMOS M, SGC_TP_MEDIO_RECEPCION R, SGC_TP_TIPOS_RECLAMOS T,
        SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO PP, SGC_TT_USUARIOS U, SGC_TT_MEDIDOR_INMUEBLE MI
        WHERE P.COD_INMUEBLE = I.CODIGO_INM
        AND P.GERENCIA = M.GERENCIA
        AND MI.COD_INMUEBLE(+)=I.CODIGO_INM
        AND MI.FECHA_BAJA(+) IS NULL
        AND M.ID_MOTIVO_REC = P.MOTIVO_PQR
        AND P.MEDIO_REC_PQR = R.ID_MEDIO_REC
        AND T.ID_TIPO_RECLAMO = P.TIPO_PQR
        AND E.COD_ENTIDAD = P.COD_ENTIDAD
        AND PP.ID_PUNTO_PAGO(+) = P.COD_PUNTO
        AND U.ID_USUARIO = P.USER_RECIBIO_PQR
        AND P.CODIGO_PQR = '$cod_pqr'";
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

    public function generaDocMasivoPqr ($area_user,$tipo_pqr,$proyecto,$secini,$secfin,$rutini,$rutfin,$fecini,$fecfin,$cod_inmueble){
     $sql = "SELECT P.COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, P.TEL_CLIENTE, P.DESCRIPCION, P.GERENCIA, P.MEDIO_REC_PQR, R.DESC_MEDIO_REC, P.TIPO_PQR,
       M.DESC_MOTIVO_REC,P.MOTIVO_PQR, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECMAX, P.COD_ENTIDAD, E.DESC_ENTIDAD, P.COD_PUNTO, PP.DESCRIPCION DESCPUNTO, P.USER_RECIBIO_PQR,
       U.NOM_USR||' '||U.APE_USR USUARIOREC ,TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY')FECREG, I.ID_PROCESO, I.CATASTRO, T.DESC_TIPO_RECLAMO,
       (SELECT  C.EMAIL  FROM SGC_TT_CONTRATOS C WHERE C.CODIGO_INM(+)=I.CODIGO_INM  AND C.FECHA_FIN  IS NULL) EMAIL_CLIENTE,
       C.EMAIL EMAIL_CLIENTE,
       P.CODIGO_PQR,
       MI.SERIAL,
       I.ID_PROYECTO ACUEDUCTO
FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TP_MOTIVO_RECLAMOS M, SGC_TP_MEDIO_RECEPCION R, SGC_TP_TIPOS_RECLAMOS T,
     SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO PP, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO PR
    , SGC_TT_MEDIDOR_INMUEBLE MI
    , SGC_TT_CONTRATOS C
WHERE P.COD_INMUEBLE = I.CODIGO_INM
  AND C.CODIGO_INM = I.CODIGO_INM
  AND P.CODIGO_PQR = PR.CODIGO_PQR
  AND P.GERENCIA = M.GERENCIA
  AND M.ID_MOTIVO_REC = P.MOTIVO_PQR
  AND P.MEDIO_REC_PQR = R.ID_MEDIO_REC
  AND T.ID_TIPO_RECLAMO = P.TIPO_PQR
  AND E.COD_ENTIDAD = P.COD_ENTIDAD
  AND PP.ID_PUNTO_PAGO(+) = P.COD_PUNTO
  AND U.ID_USUARIO = P.USER_RECIBIO_PQR
  AND P.CERRADO = 'N'
  AND PR.FECHA_SALIDA IS NULL
  AND MI.COD_INMUEBLE(+) = I.CODIGO_INM
  AND  C.FECHA_INICIO=(select MAX(c2.FECHA_INICIO) from SGC_TT_CONTRATOS c2
                        where c2.CODIGO_INM=c.CODIGO_INM)";
    if ($area_user != '') $sql .= " AND PR.AREA_ACTUAL = $area_user";
    if ($tipo_pqr != '') $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
    if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
    if ($secini != '' && $secfin != '') $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
    if ($rutini != '' && $rutfin != '') $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
    if ($cod_inmueble != '') $sql .= " AND P.COD_INMUEBLE = '$cod_inmueble'";
    if ($fecini != '' && $fecfin != '') $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
    $sql .= " ORDER BY P.TIPO_PQR, P.MOTIVO_PQR";
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


    public function generaDocMasivoPqrServicio ($area_user,$tipo_pqr,$proyecto,$secini,$secfin,$rutini,$rutfin,$fecini,$fecfin,$cod_inmueble,$departamento,$motivo_pqr,$codpqr){
        if($motivo_pqr <> 64) {
            $sql = "SELECT P.COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, P.TEL_CLIENTE, P.DESCRIPCION, P.GERENCIA, P.MEDIO_REC_PQR, R.DESC_MEDIO_REC, P.TIPO_PQR,
            M.DESC_MOTIVO_REC,P.MOTIVO_PQR, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECMAX, P.COD_ENTIDAD, E.DESC_ENTIDAD, P.COD_PUNTO, PP.DESCRIPCION DESCPUNTO, P.USER_RECIBIO_PQR, 
            U.NOM_USR||' '||U.APE_USR USUARIOREC ,TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY')FECREG, I.ID_PROCESO, I.CATASTRO, T.DESC_TIPO_RECLAMO, C.EMAIL EMAIL_CLIENTE, P.CODIGO_PQR, MI.SERIAL,
            I.ID_PROYECTO ACUEDUCTO
            FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I, SGC_TP_MOTIVO_RECLAMOS M, SGC_TP_MEDIO_RECEPCION R, SGC_TP_TIPOS_RECLAMOS T,
            SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO PP, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO PR, SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TT_CONTRATOS C         
            WHERE P.COD_INMUEBLE = I.CODIGO_INM 
            AND C.CODIGO_INM = I.CODIGO_INM
            AND C.FECHA_FIN (+) IS NULL
            AND P.CODIGO_PQR = PR.CODIGO_PQR
            AND MI.COD_INMUEBLE(+) = I.CODIGO_INM
            AND P.GERENCIA = M.GERENCIA
            AND M.ID_MOTIVO_REC = P.MOTIVO_PQR
            AND P.MEDIO_REC_PQR = R.ID_MEDIO_REC
            AND T.ID_TIPO_RECLAMO = P.TIPO_PQR
            AND E.COD_ENTIDAD = P.COD_ENTIDAD
            AND PP.ID_PUNTO_PAGO(+) = P.COD_PUNTO
            AND U.ID_USUARIO = P.USER_RECIBIO_PQR
            AND P.CERRADO = 'N' 
            AND PR.FECHA_SALIDA IS NULL
            AND MI.FECHA_BAJA IS NULL";
        }
        if($motivo_pqr == 64) {
            $sql = "SELECT DISTINCT '111111' COD_INMUEBLE, P.NOM_CLIENTE, P.DIR_CLIENTE, P.TEL_CLIENTE, P.DESCRIPCION, P.MEDIO_REC_PQR, R.DESC_MEDIO_REC, P.TIPO_PQR,
            M.DESC_MOTIVO_REC,P.MOTIVO_PQR, TO_CHAR(P.FECHA_MAX_RESOL,'DD/MM/YYYY')FECMAX, P.COD_ENTIDAD, E.DESC_ENTIDAD, P.COD_PUNTO, PP.DESCRIPCION DESCPUNTO, P.USER_RECIBIO_PQR, 
            U.NOM_USR||' '||U.APE_USR USUARIOREC ,TO_CHAR(P.FECHA_REGISTRO,'DD/MM/YYYY')FECREG, '1111111' ID_PROCESO, '111111111-1' CATASTRO , T.DESC_TIPO_RECLAMO, P.CODIGO_PQR, 
            P.PROYECTO ACUEDUCTO
            FROM SGC_TT_PQRS_CATASTRALES P, SGC_TP_MOTIVO_RECLAMOS M, SGC_TP_MEDIO_RECEPCION R, SGC_TP_TIPOS_RECLAMOS T,
            SGC_TP_ENTIDAD_PAGO E, SGC_TP_PUNTO_PAGO PP, SGC_TT_USUARIOS U, SGC_TT_PQR_FLUJO_CAT PR        
            WHERE 
             P.CODIGO_PQR = PR.CODIGO_PQR       
            AND M.ID_MOTIVO_REC = P.MOTIVO_PQR
            AND P.MEDIO_REC_PQR = R.ID_MEDIO_REC
            AND T.ID_TIPO_RECLAMO = P.TIPO_PQR
            AND E.COD_ENTIDAD = P.COD_ENTIDAD
            AND PP.ID_PUNTO_PAGO(+) = P.COD_PUNTO
            AND U.ID_USUARIO = P.USER_RECIBIO_PQR
            AND P.CERRADO = 'N' 
            AND PR.FECHA_SALIDA IS NULL";
        }
        if ($departamento != '') $sql .= " AND PR.AREA_ACTUAL = $departamento";
        if ($motivo_pqr != '') $sql .= " AND P.MOTIVO_PQR = $motivo_pqr";
        if ($codpqr != '') $sql .= " P.CODIGO_PQR = $codpqr";
        //if ($area_user != '') $sql .= " AND PR.AREA_ACTUAL = $area_user";
        if ($tipo_pqr != '') $sql .= " AND P.TIPO_PQR = '$tipo_pqr'";
        if($motivo_pqr <> 64) {
            if ($proyecto != '') $sql .= " AND I.ID_PROYECTO = '$proyecto'";
            if ($secini != '' && $secfin != '') $sql .= " AND I.ID_SECTOR BETWEEN $secini AND $secfin";
            if ($rutini != '' && $rutfin != '') $sql .= " AND I.ID_RUTA BETWEEN $rutini AND $rutfin";
        }
        if($motivo_pqr == 64) {
            if ($proyecto != '') $sql .= " AND P.PROYECTO = '$proyecto'";
        }
        if ($cod_inmueble != '') $sql .= " AND P.COD_INMUEBLE = '$cod_inmueble'";
        if ($fecini != '' && $fecfin != '') $sql .= " AND P.FECHA_PQR BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS') 
		AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        $sql .= " ORDER BY P.TIPO_PQR, P.MOTIVO_PQR";
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

    public function getPqrByFecPro ($proyecto,$fecIni,$fecFin,$motRecl){
         $sql = "
         SELECT
           I.ID_SECTOR SECTOR
          ,U.DESC_URBANIZACION URBANIZACION
          ,I.DIRECCION
          ,NVL(NVL(P.TEL_CLIENTE,CL.TELEFONO),I.TELEFONO) TELEFONO
          ,i.CODIGO_INM
          ,p.FECHA_PQR
          ,P.FECHA_CIERRE
        FROM
          SGC_TT_PQRS P
          ,SGC_TP_MOTIVO_RECLAMOS MR
          ,SGC_TT_INMUEBLES I
          ,SGC_TP_URBANIZACIONES U
          ,SGC_TT_CONTRATOS C
          ,SGC_TT_CLIENTES CL
        WHERE
          MR.GERENCIA=P.GERENCIA AND
          MR.ID_MOTIVO_REC=P.MOTIVO_PQR AND
          I.CODIGO_INM=P.COD_INMUEBLE AND
          U.CONSEC_URB=I.CONSEC_URB AND
          I.CODIGO_INM=C.CODIGO_INM(+) AND
          C.FECHA_FIN (+) IS NULL AND
          C.CODIGO_CLI=CL.CODIGO_CLI(+) AND
          P.FECHA_PQR>=TO_DATE('$fecIni 00:00:00' , 'YYYY-MM-DD HH24:MI:SS') AND
          P.FECHA_PQR<=TO_DATE('$fecFin 00:00:00' , 'YYYY-MM-DD HH24:MI:SS') AND
          I.ID_PROYECTO='$proyecto' AND
          P.MOTIVO_PQR='$motRecl'
ORDER BY
  P.FECHA_PQR
         ";
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
