<?php
include_once "class.conexion.php";


class Medidor extends ConexionClass
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

    public function getActMantCor (){
        $sql="SELECT
                A.DESCRIPCION,
                SUBSTR(A.ID_ACTMANTMED,3,4) CODIGO,
                A.ID_ACTMANTMED CODCOMP
              FROM
                SGC_TP_ACT_MANTMED_COR  A
              ORDER BY 2";
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
    public function getMedMay3K ($proyecto){
        $sql="SELECT
                I.CODIGO_INM,
                URB.DESC_URBANIZACION,
                I.DIRECCION ,
                NVL(CON.ALIAS,CLI.NOMBRE_CLI) CLIENTE,
                I.ID_PROCESO,
                I.CATASTRO,
                ME.DESC_MED,
                MI.SERIAL,
                EMP.DESC_EMPLAZAMIENTO,
                CAL.DESC_CALIBRE,
                AC.ID_USO,
                I.ID_ESTADO,
                (SELECT RL.LECTURA_ORIGINAL FROM SGC_tT_REGISTRO_LECTURAS RL
                WHERE RL.COD_INMUEBLE=I.CODIGO_INM
                AND RL.PERIODO=(
                SELECT MAX(RL2.PERIODO) FROM SGC_tT_REGISTRO_LECTURAS RL2
                WHERE RL2.FECHA_LECTURA_ORI IS NOT NULL AND
                RL.COD_INMUEBLE=RL2.COD_INMUEBLE
                ) ) ULT_LECT
                FROM
                  SGC_TT_INMUEBLES I,
                  SGC_TP_URBANIZACIONES URB,
                  SGC_TT_CONTRATOS CON,
                  SGC_TT_CLIENTES CLI,
                  SGC_TT_MEDIDOR_INMUEBLE MI,
                  SGC_TP_MEDIDORES ME,
                  SGC_TP_EMPLAZAMIENTO EMP,
                  SGC_TP_CALIBRES CAL,
                  SGC_TP_ACTIVIDADES AC
                WHERE
                  URB.CONSEC_URB=I.CONSEC_URB AND
                  CON.CODIGO_INM(+)=I.CODIGO_INM AND
                  CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                  CON.FECHA_FIN (+)IS NULL AND 
                  MI.COD_INMUEBLE=I.CODIGO_INM AND
                  MI.FECHA_BAJA IS NULL AND
                  ME.CODIGO_MED=MI.COD_MEDIDOR AND
                  EMP.COD_EMPLAZAMIENTO=MI.COD_EMPLAZAMIENTO AND
                  CAL.COD_CALIBRE=MI.COD_CALIBRE AND
                  AC.SEC_ACTIVIDAD=I.SEC_ACTIVIDAD AND
                    (SELECT RL.LECTURA_ORIGINAL FROM SGC_tT_REGISTRO_LECTURAS RL
                    WHERE RL.COD_INMUEBLE=I.CODIGO_INM
                    AND RL.PERIODO=(
                        SELECT MAX(RL2.PERIODO) FROM SGC_tT_REGISTRO_LECTURAS RL2
                        WHERE RL2.FECHA_LECTURA_ORI IS NOT NULL AND
                        RL.COD_INMUEBLE=RL2.COD_INMUEBLE
                    ) )>=3000
                  AND I.ID_PROYECTO='$proyecto'
                  ORDER BY 5 ASC";
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

    public function getDatMed($periodo,$proyecto){
        $periodo=addslashes($periodo);
        $proyecto=addslashes($proyecto);
        $sql="SELECT
                I.CODIGO_INM CODIGO,
                I.ID_RUTA RUTA,
                I.ID_SECTOR SECTOR,
                I.CATASTRO,
                NVL(CON.ALIAS,CLI.NOMBRE_CLI) NOMBRE,
                I.DIRECCION,
                URB.DESC_URBANIZACION URBANIZACION ,
                I.ID_ESTADO ESTADO,
                AC.ID_USO USO,
                MI.FECHA_INSTALACION FECHA_INSTALACION,
                MI.SERIAL SERIAL ,
                CAL.DESC_CALIBRE CALIBRE,
                RL.LECTURA_ACTUAL LECTURA,
                S.ID_GERENCIA GERENCIA,
                RL.OBSERVACION_ACTUAL OBSERVACION,
                (SELECT COUNT(1) FROM SGC_TT_FACTURA F WHERE F.FEC_EXPEDICION IS NOT NULL AND F.FACTURA_PAGADA='N' AND F.TOTAL>0 AND F.INMUEBLE=I.CODIGO_INM ) FACTURA
            FROM
              SGC_TT_INMUEBLES I ,
              SGC_TT_CONTRATOS CON,
              SGC_TT_CLIENTES CLI,
              SGC_TP_URBANIZACIONES URB,
              SGC_TP_ACTIVIDADES AC,
              SGC_TT_MEDIDOR_INMUEBLE MI,
              SGC_TT_REGISTRO_LECTURAS RL,
              SGC_TP_CALIBRES CAL,
              SGC_TP_SECTORES S,
              sgc_tp_observaciones_lect ol
            WHERE
              CON.CODIGO_INM(+)=I.CODIGO_INM AND
              CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
              CON.FECHA_FIN(+) IS NULL AND
              OL.CODIGO=RL.OBSERVACION_ACTUAL AND
              OL.MEDIDOR_DAGNADO='S' AND
              URB.CONSEC_URB(+)=I.CONSEC_URB AND
              AC.SEC_ACTIVIDAD(+)=I.SEC_ACTIVIDAD AND
              MI.COD_INMUEBLE=I.CODIGO_INM AND
              MI.FECHA_BAJA IS NULL AND
              RL.COD_INMUEBLE=I.CODIGO_INM AND
              RL.PERIODO=$periodo AND
              CAL.COD_CALIBRE=MI.COD_CALIBRE AND
              S.ID_SECTOR=I.ID_SECTOR AND
              I.ID_PROYECTO='$proyecto'
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

    public function getCantMantByPerPro($periodo,$proyecto){
        $periodo=addslashes($periodo);
        $proyecto=addslashes($proyecto);
        $sql="SELECT
                COUNT(1) CANTIDAD,
                GER.DESC_GERENCIA
              FROM
                SGC_TT_MANT_MED MM,
                SGC_TT_INMUEBLES INM,
                SGC_TP_SECTORES SEC,
                SGC_TP_GERENCIAS GER
              WHERE
                TO_CHAR(MM.FECHA_REEALIZACION,'YYYYMM')=$periodo AND
                MM.PROCESO_EFECTIVO='S' AND
                MM.ESTADO='T' AND
                INM.CODIGO_INM=MM.CODIGO_INM AND
                INM.ID_PROYECTO='$proyecto' AND
                SEC.ID_SECTOR=INM.ID_SECTOR AND
                GER.ID_GERENCIA=SEC.ID_GERENCIA
              GROUP BY
                GER.DESC_GERENCIA ";

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


    public function getCantMantCorZonByPerPro($periodo,$proyecto){
        $periodo=addslashes($periodo);
        $proyecto=addslashes($proyecto);
        $sql="SELECT
                COUNT(1) CANTIDAD,
                GER.DESC_GERENCIA
              FROM
                SGC_TT_MANT_CORRMED MM,
                SGC_TT_INMUEBLES INM,
                SGC_TP_SECTORES SEC,
                SGC_TP_GERENCIAS GER
              WHERE
                TO_CHAR(MM.FECHA_REEALIZACION,'YYYYMM')='$periodo' AND
                MM.PROCESO_EFECTIVO='S' AND
                MM.ESTADO='T' AND
                INM.CODIGO_INM=MM.CODIGO_INM AND
                INM.ID_PROYECTO='$proyecto' AND
                SEC.ID_SECTOR=INM.ID_SECTOR AND
                GER.ID_GERENCIA=SEC.ID_GERENCIA
              GROUP BY
                GER.DESC_GERENCIA ";

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

    public function getMantCorByProyProManzOper($proyecto,$procIni,$procFin,$codSis,$manIni,$manFin,$oper,$periodo)
    {
        $proyecto=addslashes($proyecto);
        $procIni=addslashes($procIni);
        $procFin=addslashes($procFin);
        $codSis=addslashes($codSis);
        $manIni=addslashes($manIni);
        $manFin=addslashes($manFin);
        $oper=addslashes($oper);
        $periodo=addslashes($periodo);


        $where="";
        if(trim($procIni)!=""){$where.=" AND INM.ID_PROCESO>='$procIni'";}
        if(trim($procFin)!=""){$where.=" AND INM.ID_PROCESO<='$procFin'";}
        if(trim($codSis)!=""){$where.=" AND INM.CODIGO_INM='$codSis'";}
        if(trim($manIni)!=""){$where.=" AND SUBSTR(INM.CATASTRO,3,3)>='$manIni'";}
        if(trim($manFin)!=""){$where.=" AND SUBSTR(INM.CATASTRO,3,3)<='$manFin'";}
        if(trim($oper)!="")  {$where.=" AND MC.USUARIO_ASIGNADO ='$oper'";}
        if(trim($periodo)!="")  {$where.=" AND MC.PERIODO ='$periodo'";}



        $sql = "SELECT
                  MC.FECHA_GENORDEN FECHA,
                  MC.CODIGO_INM CODIGO_INM,
                  ACT.ID_USO ID_USO,
                  INM.ID_ESTADO ID_ESTADO,
                  INM.CATASTRO CATASTRO,
                  INM.ID_PROCESO ID_PROCESO,
                  INM.DIRECCION DIRECCION,
                  NVL(CON.ALIAS,CLI.NOMBRE_CLI) NOMBRE,
                  MC.ID_ORDEN ID_ORDEN,
                  US.LOGIN LOGIN,
                  CT.DESCRIPCION CONTRATISTA,
                  MI.COD_MEDIDOR MEDIDOR,
                  MED.DESC_MED DESC_MEDIDOR,
                  CAL.DESC_CALIBRE DESC_CALIBRE,
                  MI.SERIAL SERIAL,
                  EMP.DESC_EMPLAZAMIENTO DESC_EMPLAZAMIENTO
                FROM
                  SGC_TT_MANT_CORRMED MC,
                  SGC_TT_INMUEBLES INM,
                  SGC_TP_ACTIVIDADES ACT,
                  SGC_TT_CONTRATOS CON,
                  SGC_TT_CLIENTES CLI,
                  SGC_TT_USUARIOS US,
                  SGC_TP_CONTRATISTAS CT,
                  SGC_TT_MEDIDOR_INMUEBLE MI,
                  SGC_TP_MEDIDORES MED,
                  SGC_TP_CALIBRES CAL,
                  SGC_TP_EMPLAZAMIENTO EMP
                WHERE
                  INM.CODIGO_INM=MC.CODIGO_INM AND
                  ACT.SEC_ACTIVIDAD=INM.SEC_ACTIVIDAD AND
                  CON.CODIGO_INM(+)=INM.CODIGO_INM AND
                  CON.FECHA_FIN(+) IS NULL AND
                  CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                  US.ID_USUARIO=MC.USUARIO_ASIGNADO AND
                  CT.ID_CONTRATISTA=US.CONTRATISTA AND
                  MI.COD_INMUEBLE=INM.CODIGO_INM AND
                  MI.FECHA_BAJA IS NULL AND
                  MED.CODIGO_MED=MI.COD_MEDIDOR AND
                  CAL.COD_CALIBRE=MI.COD_CALIBRE AND
                  MI.COD_EMPLAZAMIENTO=EMP.COD_EMPLAZAMIENTO AND
                  MC.FECHA_REEALIZACION IS NULL AND
                  INM.ID_PROYECTO='$proyecto'
                  $where
                  ORDER BY 6 ASC";

        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }


    }

    public function IngresaOrdMantCorMed($usr,$orden,$medio,$fecMan,$med,$cal,$ser,$lect,$emp,$obMan,$obGen,$long,$lat,$inm,$act){
        $usr=addslashes($usr);
        $orden=addslashes($orden);
        $medio=addslashes($medio);
        $fecMan=addslashes($fecMan);
        $med=addslashes($med);
        $cal=addslashes($cal);
        $ser=addslashes($ser);
        $lect=addslashes($lect);
        $emp=addslashes($emp);
        $obMan=addslashes($obMan);
        $obGen=addslashes($obGen);
        $long=addslashes($long);
        $lat=addslashes($lat);
        $inm=addslashes($inm);
        $act=addslashes($act);
        $usr=addslashes($usr);

        $sql="BEGIN SGC_P_INGMANTCORR('$usr',$orden,'$medio',$lect,'$fecMan','$med','$cal','$ser','$emp','$obMan','$obGen','$long','$lat','$inm','$act',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }

    public function IngresaOrdMantActMedCor($act,$orden){
        $act=addslashes($act);
        $orden=addslashes($orden);

        $sql="BEGIN SGC_P_INGMACTMED_CORR('$act',$orden,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }

    public function bajMedInm($mot,$usr,$inm){
        $inm=addslashes($inm);
        $mot=addslashes($mot);
        $usr=addslashes($usr);

        $sql="BEGIN SGC_P_BAJA_MED('$inm','$mot','$usr',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,1000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);
        if($bandera==TRUE){
            if( $this->coderror >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }

    public function actInfoMed($inm,$med,$cal,$emp,$ser,$mot,$usu){
        $inm=addslashes($inm);
        $med=addslashes($med);
        $cal=addslashes($cal);
        $emp=addslashes($emp);
        $ser=addslashes($ser);
        $mot=addslashes($mot);
        $usu=addslashes($usu);

        $sql="BEGIN SGC_P_ACT_MED('$inm','$med','$cal','$emp','$ser','$mot','$usu',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,1000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);
        if($bandera==TRUE){
            if( $this->coderror >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }

    }



    public function getCantDatMedFlexy ($fname,$tname,$where,$sort){

        $resultado = oci_parse($this->_db,"SELECT count($fname) CANTIDAD FROM SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_EMPLAZAMIENTO E, SGC_TP_MEDIDORES I, SGC_TP_CALIBRES C WHERE M.COD_MEDIDOR=I.CODIGO_MED 
				AND M.COD_EMPLAZAMIENTO=E.COD_EMPLAZAMIENTO AND M.COD_CALIBRE=C.COD_CALIBRE  $where $sort");
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

    public function getDatMedFlexy ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT M.COD_INMUEBLE,I.DESC_MED, E.DESC_EMPLAZAMIENTO, C.DESC_CALIBRE, M.SERIAL, TO_CHAR(M.FECHA_INSTALACION,'DD/MM/YYYY')FECHA, TO_CHAR(M.FECHA_BAJA,'DD/MM/YYYY')FECBAJA, S.DESC_SUMINISTRO, ME.DESCRIPCION,
						M.LECTURA_INSTALACION, M.OBS_INS,M.FECHA_BAJA,M.METODO_SUMINISTRO
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

    public function getDatMedHijFlexy ($where,$sort,$start,$end)
    {
        $sql="SELECT *
				FROM (
					SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum
					FROM (
						SELECT M.COD_INMUEBLE,I.DESC_MED, E.DESC_EMPLAZAMIENTO, C.DESC_CALIBRE, M.SERIAL, TO_CHAR(M.FECHA_INSTALACION,'DD/MM/YYYY')FECHA, S.DESC_SUMINISTRO, ME.DESCRIPCION,
						M.LECTURA_INSTALACION, M.OBS_INS,M.FECHA_BAJA,M.METODO_SUMINISTRO
						FROM SGC_TT_MEDIDOR_INMUEBLE M, SGC_TP_MEDIDORES I, SGC_TP_CALIBRES C, SGC_TP_EMPLAZAMIENTO E, SGC_TP_ESTADOS_MEDIDOR ME, SGC_TP_MET_SUMINISTRO S,SGC_TT_INM_TOTALIZADOS IT
						WHERE M.COD_MEDIDOR = I.CODIGO_MED
						AND C.COD_CALIBRE = M.COD_CALIBRE
						AND E.COD_EMPLAZAMIENTO = M.COD_EMPLAZAMIENTO
						AND ME.CODIGO = M.ESTADO_MEDIDOR
						AND S.COD_SUMINISTRO = M.METODO_SUMINISTRO
                        AND S.COD_SUMINISTRO = M.METODO_SUMINISTRO
                        AND M.COD_INMUEBLE=IT.COD_INM_HIJO
						$where
						  $sort
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

    public function getDatInmMedByAcuProManMedEst($proyecto,$procesoIni,$procesoFin,$codSis,$manzIni,$manzFin,$medidor,$estInm){

        $where="";
        if(trim($procesoIni)!=''){$where.=" AND I.ID_PROCESO>='$procesoIni'"; }
        if(trim($procesoFin)!=''){$where.=" AND I.ID_PROCESO<='$procesoFin'"; }
        if(trim($codSis)!=''){$where.=" AND I.CODIGO_INM='$codSis'"; }
        if(trim($manzIni)!=''){$where.="AND SUBSTR(I.CATASTRO,3,3)>=$manzIni "; }
        if(trim($manzFin)!=''){$where.="AND SUBSTR(I.CATASTRO,3,3)<=$manzFin "; }
        if(trim($medidor)!=''){$where.="AND NVL(M.ESTADO_MED,'N')='$medidor' "; }
        if(trim($estInm)!=''){$where.="AND EI.INDICADOR_ESTADO='$estInm' "; }


        $sql="SELECT
                I.ID_ZONA,
                I.ID_ESTADO,
                I.CODIGO_INM,
                I.DIRECCION,
                M.DESC_MED,
                MI.SERIAL,
                CAL.DESC_CALIBRE,
                MI.FECHA_INSTALACION,
                I.ID_PROYECTO
              FROM
                SGC_TT_INMUEBLES I,
                SGC_TT_MEDIDOR_INMUEBLE MI,
                sgc_TP_MEDIDORES M,
                SGC_TP_ESTADOS_INMUEBLES EI,
                SGC_TP_CALIBRES CAL
              WHERE
                MI.COD_INMUEBLE(+)=I.CODIGO_INM and
                M.CODIGO_MED(+)=MI.COD_MEDIDOR AND
                MI.FECHA_BAJA(+) IS NULL AND
                CAL.COD_CALIBRE(+)=MI.COD_CALIBRE AND
                EI.ID_ESTADO=I.ID_ESTADO AND
                --MI.ESTADO_MEDIDOR IN ('LV','RT','ET') AND
                I.ID_PROYECTO='$proyecto'
                $where
              ORDER BY I.ID_PROCESO";

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


    public function getDatInmMedByInm($inmueble){
        $sql="SELECT
                INM.ID_PROYECTO,
                PRO.SIGLA_PROYECTO,
                USR.LOGIN,
                ORD.FECHA_GENORDEN,
                MOT.DESCRIPCION MOTIVO,
                ORD.ID_ORDEN,
                NVL(CON.CODIGO_CLI,9999999) CODIGO_CLI,
                NVL(CASE (CON.CODIGO_CLI)
                  WHEN 9999999 THEN CON.ALIAS
                  ELSE CLI.NOMBRE_CLI||
                  (
                    SELECT
                    CASE NVL(TRIM(CON.ALIAS),'00')
                      WHEN '00' THEN ''
                      ELSE '('||CON.ALIAS||')'
                    END
                    FROM DUAL
                  )
                END,'N/A')
                NOMBRE,
                INM.DIRECCION,
                INM.ID_ZONA,
                NVL(MEI.COD_MEDIDOR,'N/A')COD_MEDIDOR,
                NVL(MED.DESC_MED,'N/A')DESC_MED,
                NVL(EMP.DESC_EMPLAZAMIENTO,'N/A')DESC_EMPLAZAMIENTO,
                NVL(CAL.DESC_CALIBRE,'N/A')DESC_CALIBRE,
                NVL(MEI.SERIAL,'N/A')SERIAL
              FROM
                SGC_tT_INMUEBLES INM,
                SGC_TP_PROYECTOS PRO,
                SGC_TT_ORDENES_CAMBINS_MED ORD,
                SGC_TT_USUARIOS USR,
                SGC_TP_MOTIVOS_CAMBMED MOT,
                SGC_TT_CONTRATOS CON,
                SGC_TT_CLIENTES CLI,
                SGC_TT_MEDIDOR_INMUEBLE MEI,
                SGC_TP_MEDIDORES MED,
                SGC_TP_EMPLAZAMIENTO EMP,
                SGC_TP_CALIBRES CAL
              WHERE
                PRO.ID_PROYECTO=INM.ID_PROYECTO AND
                ORD.CODIGO_INM=INM.CODIGO_INM AND
                ORD.ESTADO='A' AND
                USR.ID_USUARIO=ORD.USUARIO_GENORDEN AND
                MOT.CODIGO=ORD.MOTIVO_CAMB AND
                CON.CODIGO_INM(+)=INM.CODIGO_INM AND
                CON.FECHA_FIN(+) IS NULL AND
                CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                MEI.COD_INMUEBLE(+)=INM.CODIGO_INM AND
                MEI.FECHA_BAJA(+)IS NULL AND
                MED.CODIGO_MED(+)= MEI.COD_MEDIDOR AND
                EMP.COD_EMPLAZAMIENTO(+)=MEI.COD_EMPLAZAMIENTO AND
                CAL.COD_CALIBRE(+)=MEI.COD_CALIBRE AND
                INM.CODIGO_INM='$inmueble'";

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


    public function getDatInmMedMantByInm($inmueble){
        $sql="SELECT
                INM.ID_PROYECTO,
                PRO.SIGLA_PROYECTO,
                USR.LOGIN,
                ORD.FECHA_GENORDEN,
                MOT.DESCRIPCION MOTIVO,
                ORD.ID_ORDEN,
                NVL(CON.CODIGO_CLI,9999999) CODIGO_CLI,
                NVL(CASE (CON.CODIGO_CLI)
                  WHEN 9999999 THEN CON.ALIAS
                  ELSE CLI.NOMBRE_CLI||
                  (
                    SELECT
                    CASE NVL(TRIM(CON.ALIAS),'00')
                      WHEN '00' THEN ''
                      ELSE '('||CON.ALIAS||')'
                    END
                    FROM DUAL
                  )
                END,'N/A')
                NOMBRE,
                INM.DIRECCION,
                INM.ID_ZONA,
                NVL(MEI.COD_MEDIDOR,'N/A')COD_MEDIDOR,
                NVL(MED.DESC_MED,'N/A')DESC_MED,
                NVL(EMP.DESC_EMPLAZAMIENTO,'N/A')DESC_EMPLAZAMIENTO,
                NVL(CAL.DESC_CALIBRE,'N/A')DESC_CALIBRE,
                NVL(MEI.SERIAL,'N/A')SERIAL
              FROM
                SGC_tT_INMUEBLES INM,
                SGC_TP_PROYECTOS PRO,
                SGC_TT_MANT_MED ORD,
                SGC_TT_USUARIOS USR,
                SGC_TP_MOTIVOMANTMED MOT,
                SGC_TT_CONTRATOS CON,
                SGC_TT_CLIENTES CLI,
                SGC_TT_MEDIDOR_INMUEBLE MEI,
                SGC_TP_MEDIDORES MED,
                SGC_TP_EMPLAZAMIENTO EMP,
                SGC_TP_CALIBRES CAL
              WHERE
                PRO.ID_PROYECTO=INM.ID_PROYECTO AND
                ORD.CODIGO_INM=INM.CODIGO_INM AND
                ORD.ESTADO='A' AND
                USR.ID_USUARIO=ORD.USUARIO_GENORDEN AND
                MOT.ID_MOTMANT=ORD.MOTIVO_MANT AND
                CON.CODIGO_INM(+)=INM.CODIGO_INM AND
                CON.FECHA_FIN(+) IS NULL AND
                CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                MEI.COD_INMUEBLE(+)=INM.CODIGO_INM AND
                MEI.FECHA_BAJA(+)IS NULL AND
                MED.CODIGO_MED(+)= MEI.COD_MEDIDOR AND
                EMP.COD_EMPLAZAMIENTO(+)=MEI.COD_EMPLAZAMIENTO AND
                CAL.COD_CALIBRE(+)=MEI.COD_CALIBRE AND
                INM.CODIGO_INM='$inmueble'";

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

    public function generaOrdenesCambioInstMedi($proyecto,$procesoIni,$procesoFin,$inmueble,$manzanaIni,$manzanaFin,$medido,$estadoIn,$usr,$motivo,$usr_asignado,$usr_asignador,$desc,$aplFact){


         $sql="BEGIN SGC_P_GENERA_ORD_INSCAMB_MED('$proyecto','$procesoIni','$procesoFin','$inmueble','$manzanaIni','$manzanaFin','$medido','$estadoIn','$usr','$motivo','$usr_asignado','$usr_asignador','$desc','$aplFact',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,10000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror,1000);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera){
            if( $this->coderror>0){
                return false;
            }else{
                return true;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }

    public function generaOrdenesMantMed($proyecto,$procesoIni,$procesoFin,$inmueble,$manzanaIni,$manzanaFin,$usr,$motivo,$usr_asignado,$usr_asignador,$desc){


        $sql="BEGIN SGC_P_GENERA_ORD_MANT_MED('$proyecto','$procesoIni','$procesoFin','$inmueble','$manzanaIni','$manzanaFin','$usr','$motivo','$usr_asignado','$usr_asignador','$desc',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,1000);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror,1000);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera){
            if( $this->coderror>0){
                return false;
            }else{
                return true;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }

    public function getOrdenesByAcuProManMedEst($proyecto,$proIni,$proFin,$codSis,$manIni,$manFin,$medidor,$estInm,$usr_asignado,$fecIni,$fecFin){

        $where="";
        if(trim($proIni)!=""){$where.=" AND INM.ID_PROCESO>=$proIni";}
        if(trim($proFin)!=""){$where.=" AND INM.ID_PROCESO<=$proFin";}
        if(trim($codSis)!=""){$where.=" AND INM.CODIGO_INM=$codSis";}
        if(trim($manIni)!=""){$where.=" AND SUBSTR(INM.CATASTRO,3,3)>=$manIni";}
        if(trim($manFin)!=""){$where.=" AND SUBSTR(INM.CATASTRO,3,3)<=$manFin";}
        if(trim($medidor)!=""){$where.=" AND NVL(MED.ESTADO_MED,'N')='$medidor'";}
        if(trim($estInm)!=""){$where.=" AND EI.INDICADOR_ESTADO ='$estInm'";}
        if(trim($usr_asignado)!=""){$where.=" AND ORD.USUARIO_ASIGNADO ='$usr_asignado'";}

        if(trim($fecIni)!=""){$where.=" AND ORD.FECHA_ASIGNACION >= TO_DATE('$fecIni 00:00:00','YYYY-MM-DD hh24:mi:ss')";}
        if(trim($fecFin)!=""){$where.=" AND ORD.FECHA_ASIGNACION <= TO_DATE('$fecFin 23:59:59','YYYY-MM-DD hh24:mi:ss')";}


        $sql="SELECT ORD.ID_ORDEN,INM.CODIGO_INM,INM.CATASTRO,INM.ID_PROCESO,INM.DIRECCION,
                USU.LOGIN,CONT.DESCRIPCION CONTRATISTA,
                CASE (CON.CODIGO_CLI)
                WHEN 9999999 THEN CON.ALIAS
                ELSE CLI.NOMBRE_CLI
                END NOMBRE, NVL(CLI.TELEFONO,INM.TELEFONO) TELEFONO,
                nvl(MED.DESC_MED,'N/A') MEDIDOR,
                NVL(CAL.DESC_CALIBRE,'N/A') DESC_CALIBRE,
                NVL(MEI.SERIAL,'N/A') SERIAL,

                ORD.TIPO_ORDEN,
                ORD.FECHA_ASIGNACION FECHA,
                ORD.DESCRIPCION DESCORDEN,
                ORD.MOTIVO_CAMB,
                MOT.DESCRIPCION DESCMOT,
                (SELECT U.LOGIN FROM SGC_TT_USUARIOS U WHERE U.ID_USUARIO=ORD.USUARIO_GENORDEN) LOGINGENORDEN,
                (SELECT C.DESCRIPCION FROM SGC_TT_USUARIOS U, sgc_tp_contratistas c WHERE U.ID_USUARIO=ORD.USUARIO_GENORDEN AND U.CONTRATISTA=C.ID_CONTRATISTA) LOGINCONT,
                NVL(EMP.DESC_EMPLAZAMIENTO,'N/A') DESC_EMPLAZAMIENTO
                 FROM SGC_TT_ORDENES_CAMBINS_MED ORD, SGC_TT_INMUEBLES INM,SGC_TP_MOTIVOS_CAMBMED MOT,SGC_TT_CONTRATOS CON, SGC_TT_CLIENTES CLI,
                SGC_TT_USUARIOS USU, SGC_TT_MEDIDOR_INMUEBLE MEI, SGC_TP_MEDIDORES MED, SGC_TP_CONTRATISTAS CONT, SGC_TP_CALIBRES CAL, SGC_TP_EMPLAZAMIENTO EMP,
                SGC_TP_ESTADOS_INMUEBLES EI

                WHERE


                ORD.CODIGO_INM=INM.CODIGO_INM AND
                ORD.ESTADO='A' AND
                ORD.MOTIVO_CAMB=MOT.CODIGO AND
                CON.CODIGO_INM(+)=INM.CODIGO_INM AND
                CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                USU.ID_USUARIO=ORD.USUARIO_ASIGNADO AND
                MEI.COD_INMUEBLE(+)=INM.CODIGO_INM AND
                MED.CODIGO_MED(+)=MEI.COD_MEDIDOR AND
                CON.FECHA_FIN(+) IS NULL AND
                MEI.FECHA_BAJA(+) IS NULL AND
                CONT.ID_CONTRATISTA=USU.CONTRATISTA AND
                CAL.COD_CALIBRE(+)=MEI.COD_CALIBRE AND
                EMP.COD_EMPLAZAMIENTO(+)=MEI.COD_EMPLAZAMIENTO AND
                ORD.FECHA_REEALIZACION IS NULL AND
                EI.ID_ESTADO=INM.ID_ESTADO AND
                INM.ID_PROYECTO='$proyecto'
                $where
                ORDER BY INM.ID_PROCESO ASC";
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

    public function getOrdenesManByAcuProMan($proyecto,$proIni,$proFin,$codSis,$manIni,$manFin,$usr_asignado,$fecIni,$fecFin){

        $where="";
        if(trim($proIni)!=""){$where.=" AND INM.ID_PROCESO>=$proIni";}
        if(trim($proFin)!=""){$where.=" AND INM.ID_PROCESO<=$proFin";}
        if(trim($codSis)!=""){$where.=" AND INM.CODIGO_INM=$codSis";}
        if(trim($manIni)!=""){$where.=" AND SUBSTR(INM.CATASTRO,3,3)>=$manIni";}
        if(trim($manFin)!=""){$where.=" AND SUBSTR(INM.CATASTRO,3,3)<=$manFin";}
        if(trim($usr_asignado)!=""){$where.=" AND ORD.USUARIO_ASIGNADO ='$usr_asignado'";}
        if(trim($fecIni)!=""){$where.=" AND ORD.FECHA_ASIGNACION >= TO_DATE('$fecIni 00:00:00','YYYY-MM-DD hh24:mi:ss')";}
        if(trim($fecFin)!=""){$where.=" AND ORD.FECHA_ASIGNACION <= TO_DATE('$fecFin 23:59:59','YYYY-MM-DD hh24:mi:ss')";}



        $sql="SELECT ORD.ID_ORDEN,INM.CODIGO_INM,INM.CATASTRO,INM.ID_PROCESO,INM.DIRECCION,INM.ID_ESTADO,ACT.ID_USO ,
                USU.LOGIN,CONT.DESCRIPCION CONTRATISTA,
                CASE (CON.CODIGO_CLI)
                WHEN 9999999 THEN CON.ALIAS
                ELSE CLI.NOMBRE_CLI
                END NOMBRE, NVL(CLI.TELEFONO,INM.TELEFONO) TELEFONO,
                nvl(MED.DESC_MED,'N/A') MEDIDOR,
                NVL(CAL.DESC_CALIBRE,'N/A') DESC_CALIBRE,
                NVL(MEI.SERIAL,'N/A') SERIAL,

                --ORD.TIPO_ORDEN,
                TO_CHAR(ORD.FECHA_ASIGNACION,'DD-MM-YYYY HH24:MI') FECHA,
                ORD.DESCRIPCION DESCORDEN,
                ORD.MOTIVO_MANT,
                MOT.DESCRIPCION DESCMOT,
                (SELECT U.LOGIN FROM SGC_TT_USUARIOS U WHERE U.ID_USUARIO=ORD.USUARIO_GENORDEN) LOGINGENORDEN,
                (SELECT C.DESCRIPCION FROM SGC_TT_USUARIOS U, sgc_tp_contratistas c WHERE U.ID_USUARIO=ORD.USUARIO_GENORDEN AND U.CONTRATISTA=C.ID_CONTRATISTA) LOGINCONT,
                NVL(EMP.DESC_EMPLAZAMIENTO,'N/A') DESC_EMPLAZAMIENTO
                 FROM SGC_TT_MANT_MED ORD, SGC_TT_INMUEBLES INM,
                 SGC_TP_MOTIVOMANTMED MOT,
                 SGC_TT_CONTRATOS CON, SGC_TT_CLIENTES CLI,
                SGC_TT_USUARIOS USU, SGC_TT_MEDIDOR_INMUEBLE MEI, SGC_TP_MEDIDORES MED, SGC_TP_CONTRATISTAS CONT, SGC_TP_CALIBRES CAL, SGC_TP_EMPLAZAMIENTO EMP,
                SGC_TP_ESTADOS_INMUEBLES EI,
                SGC_TP_ACTIVIDADES ACT

                WHERE


                ORD.CODIGO_INM=INM.CODIGO_INM AND
                ORD.ESTADO='A' AND
                ORD.MOTIVO_MANT=MOT.ID_MOTMANT AND
                CON.CODIGO_INM(+)=INM.CODIGO_INM AND
                CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                USU.ID_USUARIO=ORD.USUARIO_ASIGNADO AND
                MEI.COD_INMUEBLE(+)=INM.CODIGO_INM AND
                MED.CODIGO_MED(+)=MEI.COD_MEDIDOR AND
                CON.FECHA_FIN(+) IS NULL AND
                MEI.FECHA_BAJA(+) IS NULL AND
                CONT.ID_CONTRATISTA=USU.CONTRATISTA AND
                CAL.COD_CALIBRE(+)=MEI.COD_CALIBRE AND
                EMP.COD_EMPLAZAMIENTO(+)=MEI.COD_EMPLAZAMIENTO AND
                ORD.FECHA_REEALIZACION IS NULL AND
                EI.ID_ESTADO=INM.ID_ESTADO AND
                ACT.SEC_ACTIVIDAD(+)=INM.SEC_ACTIVIDAD AND
                INM.ID_PROYECTO='$proyecto'
                $where
                ORDER BY INM.ID_PROCESO ASC";

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

    public function getImpEjeOrd(){
        $sql="SELECT
                CM.CODIGO,
                CM.DESCRIPCION
              FROM
                SGC_TP_MOTIVO_NOCAMBMED CM
              WHERE
                CM.ACTIVO='S'
              ORDER BY CM.DESCRIPCION ";

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


    public function getSiPadreTotByInm($inm){
        $sql="SELECT
                COUNT(1) CANTIDAD
              FROM
                SGC_TT_INM_TOTALIZADOS IT
              WHERE
                IT.COD_INM_PADRE='$inm' ";

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){
            oci_fetch($resultado);
            $cantidad=oci_result($resultado, 'CANTIDAD');
            oci_close($this->_db);
            return $cantidad;
        }else{
            oci_close($this->_db);
            return false;
        }
    }


    public function getSiHijoTotByInm($inm){
        $sql="SELECT
                COUNT(1) CANTIDAD
              FROM
                SGC_TT_INM_TOTALIZADOS IT
              WHERE
                IT.COD_INM_HIJO='$inm'";

        $resultado=oci_parse($this->_db,$sql);
        $bandera=oci_execute($resultado);
        if($bandera){

            oci_fetch($resultado);
            $cantidad=oci_result($resultado, 'CANTIDAD');
            oci_close($this->_db);
            return $cantidad;
        }else{
            oci_close($this->_db);
            return false;
        }
    }

    public function IngresaOrdCambioMed($usr,$orden,$medio,$lecRet,$obsLec,$motImp,$fecIns,$med,$cal,$ser,$lect,$emp,$entUsr,$obins,$oblecins,$long,$lat,$inm,$fact){


        $sql="BEGIN SGC_P_INGCAMBMED('$usr',$orden,'$medio','$lecRet','$obsLec','$motImp','$fecIns','$med','$cal','$ser',$lect,'$emp','$entUsr','$obins','$oblecins','$long','$lat','$inm','$fact',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }

    public function IngresaOrdMantMed($usr,$orden,$medio,$fecMan,$med,$cal,$ser,$lect,$emp,$obMan,$obGen,$long,$lat,$inm,$lisAct){



        $sql="BEGIN SGC_P_INGMANTMED('$usr',$orden,'$medio',$lect,'$fecMan','$med','$cal','$ser','$emp','$obMan','$obGen','$long','$lat','$inm','$lisAct',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }

    public function IngresaOrdMantACTMed($act,$orden){

        $sql="BEGIN SGC_P_INGMACTMED('$act',$orden,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }

    public function GeneraFacCaasdManMed($pro,$cant,$usr,$periodo){

         $sql="BEGIN SGC_P_GENERA_FAC_CAASD ( '$pro', $cant, $usr, $periodo, :PMSGRESULT, :PCODRESULT );COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mesrror,500);
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror);
        $bandera=oci_execute($resultado);
        oci_close($this->_db);

        if($bandera==TRUE){
            if( $this->coderror >0){
                return false;
            }else{
                return true;
            }
        }
        else{
            return false;
        }
    }


    public function getMotCambio (){
        $resultado = oci_parse($this->_db,"SELECT MC.CODIGO, MC.DESCRIPCION FROM SGC_TP_MOTIVOS_CAMBMED MC
        WHERE MC.ACTIVO='S'");

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

    public function getMedidores (){

        $sql="SELECT
                MED.CODIGO_MED CODIGO,
                MED.DESC_MED DESCRIPCION
              FROM
                SGC_TP_MEDIDORES MED
              WHERE
                MED.ACTIVO='S'
              ORDER BY 2";
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

    public function getMotMant (){
        $resultado = oci_parse($this->_db,"SELECT  MM.ID_MOTMANT CODIGO, MM.DESCRIPCION FROM SGC_TP_MOTIVOMANTMED MM
        ");

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

    public function getActMant (){
        $sql="SELECT
                A.DESCRIPCION,
                SUBSTR(A.ID_ACTMANTMED,3,4) CODIGO,
                A.ID_ACTMANTMED CODCOMP
              FROM
                SGC_TP_ACT_MANTMED  A
              ORDER BY 2";
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

    public function getFacInsByPro($Proy){
        $sql="SELECT
                NOMBRE_FACTURA FACTURA,
                CANT_INSTALACIONES CANTIDAD
              FROM
                SGC_TT_FACTURAS_MED_CAASD
              WHERE
                PROYECTO='$Proy'
              ORDER BY
                PERIODO DESC,
                NUM_FACTURA DESC";

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


    public function getInsPendByPro($Proy){
        $sql="SELECT count(1) CANTIDAD
              FROM SGC_TT_ORDENES_CAMBINS_MED CM, SGC_TT_INMUEBLES I, SGC_TP_ESTADOS_INMUEBLES EI
             WHERE CM.ESTADO = 'T'
               AND CM.TIPO_ORDEN = 'I'
               AND CM.PROCESO_EFECTIVO = 'S'
               AND CM.PAGADO_CAASD = 'N'
               AND CM.APLICA_FACTCAASD = 'S'
               AND CM.MOTIVO_CAMB = 'IN'
               AND EI.ID_ESTADO=I.ID_ESTADO
               --AND EI.ESTADO_FACT='A'
               AND I.CODIGO_INM = CM.CODIGO_INM
               AND I.ID_PROYECTO = '$Proy'";

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



    public function getMedInmByFact($fact){
        $sql="SELECT
                INM.ID_ZONA,
                INM.CODIGO_INM,
                INM.DIRECCION,
                URB.DESC_URBANIZACION,
                NVL(CASE (CON.CODIGO_CLI)
                  WHEN 9999999 THEN CON.ALIAS
                  ELSE CLI.NOMBRE_CLI||
                  (
                    SELECT
                      CASE NVL(TRIM(CON.ALIAS),'00')
                        WHEN '00' THEN ''
                        ELSE '('||CON.ALIAS||')'
                      END
                    FROM DUAL
                  )
                END,'N/A')
                NOMBRE,
                CASE (CLI.DOCUMENTO)
                  WHEN '9999999' THEN 'N/A'
                  ELSE NVL(CLI.DOCUMENTO,'N/A')
                END DOCUMENTO,
                INM.ID_PROCESO,
                INM.CATASTRO,
                MC.MARCA_MEDNUEVO,
                MC.SERIAL_MEDNUEVO,
                EMP.DESC_EMPLAZAMIENTO,
                CAL.DESC_CALIBRE,
                ACT.ID_USO,
                ACT.ID_ACTIVIDAD,
                INM.UNIDADES_HAB,
                CASE (SI.COD_SERVICIO)
                  WHEN 1 THEN 'RED'
                  WHEN 3 THEN 'POZO'
                END SUMINISTRO,
                NVL(CON.ID_CONTRATO,'N/A') CONTRATO,
                INM.ID_ESTADO,
                S.ID_GERENCIA,
                MC.FECHA_REEALIZACION 
              FROM
                SGC_TT_ORDENES_CAMBINS_MED MC,
                SGC_TT_INMUEBLES INM,
                SGC_TP_URBANIZACIONES URB,
                SGC_TT_CONTRATOS CON,
                SGC_TT_CLIENTES CLI,
                SGC_TP_EMPLAZAMIENTO EMP,
                SGC_TP_CALIBRES CAL,
                SGC_TP_ACTIVIDADES ACT,
                SGC_TT_SERVICIOS_INMUEBLES SI,
                SGC_TP_SECTORES S
              WHERE
                MC.CODIGO_INM=INM.CODIGO_INM AND
                URB.CONSEC_URB(+)=INM.CONSEC_URB AND
                CON.CODIGO_INM(+)=INM.CODIGO_INM AND
                CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                CON.FECHA_FIN(+) IS NULL AND
                MC.EMPLAZAMIENTO=EMP.COD_EMPLAZAMIENTO(+) AND
                CAL.COD_CALIBRE(+)=MC.CALIBRE_MEDNUEVO AND
                ACT.SEC_ACTIVIDAD(+)=INM.SEC_ACTIVIDAD AND
                SI.COD_INMUEBLE(+)=INM.CODIGO_INM AND
                SI.COD_SERVICIO(+) IN(1,3) AND
                S.ID_SECTOR=INM.ID_SECTOR AND
                MC.NOMFACTURA_CAASD='$fact'
              ORDER BY
                INM.ID_ZONA,INM.ID_PROCESO ";

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
    public function getFecMinMaxInsByFact($fact){
        $sql="SELECT
                to_char(min(MC.FECHA_REEALIZACION),'MM/DD/YYYY') MINIMA,
                to_char(max(MC.FECHA_REEALIZACION),'MM/DD/YYYY') MAXIMA
              FROM
                SGC_TT_ORDENES_CAMBINS_MED MC
              WHERE
                MC.NOMFACTURA_CAASD='$fact' ";

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


    public function getCoordInstMedReaByFecRutSecPer($fecha, $ruta,  $usr)
    {
        $fecha  = addslashes($fecha);
        $ruta    = addslashes($ruta);
        $usr     = addslashes($usr);
        $sql     = "SELECT
                RC.LATITUD lat,
                RC.LONGITUD lgn,
                RC.CODIGO_INM,
                TO_CHAR(RC.FECHA_REEALIZACION,'DD/MM/YYYY HH24:MI:SS') FECHA
              FROM
                SGC_TT_ORDENES_CAMBINS_MED RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=RC.CODIGO_INM AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                MI.FECHA_BAJA(+) IS NULL AND
                TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY')='$fecha' AND
                RC.USUARIO_ASIGNADO='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta' AND
                RC.LATITUD<>0 AND
                RC.LONGITUD<>0
                ORDER BY RC.FECHA_REEALIZACION ASC
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



    public function getCoordInstMedReaByFecEjeRutSecPer($fechaini,$fechafin, $ruta,  $usr)
    {
        $fechafin  = addslashes($fechafin);
        $fechaini  = addslashes($fechaini);
        $ruta    = addslashes($ruta);
        $usr     = addslashes($usr);
        $sql     = "SELECT
                RC.LATITUD lat,
                RC.LONGITUD lgn,
                RC.CODIGO_INM,
                TO_CHAR(RC.FECHA_REEALIZACION,'DD/MM/YYYY HH24:MI:SS') FECHA
              FROM
                SGC_TT_ORDENES_CAMBINS_MED RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=RC.CODIGO_INM AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                MI.FECHA_BAJA(+) IS NULL AND
                RC.FECHA_REEALIZACION>=TO_DATE('$fechaini 00:01','DD-MM-YYYY HH24:MI') AND
                RC.FECHA_REEALIZACION<=TO_DATE('$fechafin 23:59','DD-MM-YYYY HH24:MI') AND
                RC.USUARIO_ASIGNADO='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta' AND
                RC.LATITUD<>0 AND
                RC.LONGITUD<>0
                ORDER BY RC.FECHA_REEALIZACION ASC
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


    public function getCoordManPreReaByFecRutSecPer($fecha, $ruta,  $usr)
    {
        $fecha  = addslashes($fecha);
        $ruta    = addslashes($ruta);
        $usr     = addslashes($usr);
        $sql     = "SELECT
                RC.LATITUD lat,
                RC.LONGITUD lgn,
                RC.CODIGO_INM,
                TO_CHAR(RC.FECHA_REEALIZACION,'DD/MM/YYYY HH24:MI:SS') FECHA
              FROM
                SGC_TT_MANT_MED RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=RC.CODIGO_INM AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                MI.FECHA_BAJA(+) IS NULL AND
                TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY')='$fecha' AND
                RC.USUARIO_ASIGNADO='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta' AND
                RC.LATITUD<>0 AND
                RC.LONGITUD<>0
                ORDER BY RC.FECHA_REEALIZACION ASC
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

 public function getCoordManCorrReaByFecRutSecPer($fecha, $ruta,  $usr)
    {
        $fecha  = addslashes($fecha);
        $ruta    = addslashes($ruta);
        $usr     = addslashes($usr);
        $sql     = "SELECT
                RC.LATITUD lat,
                RC.LONGITUD lgn,
                RC.CODIGO_INM,
                TO_CHAR(RC.FECHA_REEALIZACION,'DD/MM/YYYY HH24:MI:SS') FECHA
              FROM
                SGC_TT_MANT_CORRMED RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=RC.CODIGO_INM AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                MI.FECHA_BAJA(+) IS NULL AND
                TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY')='$fecha' AND
                RC.USUARIO_ASIGNADO='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta' AND
                RC.LATITUD<>0 AND
                RC.LONGITUD<>0
                ORDER BY RC.FECHA_REEALIZACION ASC
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



    public function getCoordManCorrReaByFecEjeRutSecPer($fechaini,$fechafin, $ruta,  $usr)
    {
        $fechaini  = addslashes($fechaini);
        $fechafin  = addslashes($fechafin);
        $ruta    = addslashes($ruta);
        $usr     = addslashes($usr);
        $sql     = "SELECT
                RC.LATITUD lat,
                RC.LONGITUD lgn,
                RC.CODIGO_INM,
                TO_CHAR(RC.FECHA_REEALIZACION,'DD/MM/YYYY HH24:MI:SS') FECHA
              FROM
                SGC_TT_MANT_CORRMED RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=RC.CODIGO_INM AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                MI.FECHA_BAJA(+) IS NULL AND
                RC.FECHA_REEALIZACION >=TO_DATE('$fechaini 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                RC.FECHA_REEALIZACION <=TO_DATE('$fechafin 23:59:00','YYYY-MM-DD HH24:MI:SS') AND
                RC.USUARIO_ASIGNADO='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta' AND
                RC.LATITUD<>0 AND
                RC.LONGITUD<>0
                ORDER BY RC.FECHA_REEALIZACION ASC
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
    public function getCoordManPreReaByFecEjeRutSecPer($fechaini,$fechafin, $ruta,  $usr)
    {
        $fechaini  = addslashes($fechaini);
        $fechafin  = addslashes($fechafin);
        $ruta    = addslashes($ruta);
        $usr     = addslashes($usr);
        $sql     = "SELECT
                RC.LATITUD lat,
                RC.LONGITUD lgn,
                RC.CODIGO_INM,
                TO_CHAR(RC.FECHA_REEALIZACION,'DD/MM/YYYY HH24:MI:SS') FECHA
              FROM
                ACEASOFT.SGC_TT_MANT_MED RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=RC.CODIGO_INM AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                MI.FECHA_BAJA(+) IS NULL AND
                RC.FECHA_REEALIZACION >=TO_DATE('$fechaini 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                RC.FECHA_REEALIZACION <=TO_DATE('$fechafin 23:59:00','YYYY-MM-DD HH24:MI:SS') AND
                RC.USUARIO_ASIGNADO='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta' AND
                RC.LATITUD<>0 AND
                RC.LONGITUD<>0
                ORDER BY RC.FECHA_REEALIZACION ASC
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


    public function getCantRutInstMedPerEje($pro, $sector, $fecini, $fecfin)
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
            $where .= " and RC.FECHA_ASIGNACION >=TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecfin) != '') {
            $where .= " and RC.FECHA_ASIGNACION <=TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql = "SELECT
                COUNT(*) TOTAL
              FROM (
                SELECT
                  count(1)
                FROM
                  SGC_TT_ORDENES_CAMBINS_MED RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                  RC.CODIGO_INM=I.CODIGO_INM AND
                  RC.MEDIO_DIGITACION='M' AND
                  US.ID_USUARIO=RC.USUARIO_ASIGNADO AND
                  RC.FECHA_REEALIZACION IS NOT NULL AND
                  RC.FECHA_ASIGNACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where
                GROUP BY
                  RC.USUARIO_ASIGNADO,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA,
                  TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY'))";
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





    public function getCantRutInstMedFecEje($pro, $sector, $fecini, $fecfin)
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
            $where .= " and RC.FECHA_REEALIZACION >=TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecfin) != '') {
            $where .= " and RC.FECHA_REEALIZACION <=TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql = "SELECT
                COUNT(*) TOTAL
              FROM (
                SELECT
                  count(1)
                FROM
                  SGC_TT_ORDENES_CAMBINS_MED RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                  RC.CODIGO_INM=I.CODIGO_INM AND
                  --RC.MEDIO_DIGITACION='M' AND
                  US.ID_USUARIO=RC.USUARIO_ASIGNADO AND
                  RC.FECHA_REEALIZACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where
                GROUP BY
                  RC.USUARIO_ASIGNADO,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA)";
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


    public function getCantRutManPrePerEje($pro, $sector, $fecini, $fecfin)
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
            $where .= " and RC.FECHA_ASIGNACION >=TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecfin) != '') {
            $where .= " and RC.FECHA_ASIGNACION <=TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql = "SELECT
                COUNT(*) TOTAL
              FROM (
                SELECT
                  count(1)
                FROM
                  SGC_TT_MANT_MED RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                  RC.CODIGO_INM=I.CODIGO_INM AND
                  US.ID_USUARIO=RC.USUARIO_ASIGNADO AND
                  RC.FECHA_ASIGNACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where
                GROUP BY
                  RC.USUARIO_ASIGNADO,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA,
                  TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY'))";
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


    public function getCantRutManCorrPerEje($pro, $sector, $fecini, $fecfin)
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
            $where .= " and RC.FECHA_ASIGNACION >=TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecfin) != '') {
            $where .= " and RC.FECHA_ASIGNACION <=TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

         $sql = "SELECT
                COUNT(*) TOTAL
              FROM (
                SELECT
                  count(1)
                FROM
                  SGC_TT_MANT_CORRMED RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                   RC.CODIGO_INM=I.CODIGO_INM AND
                  US.ID_USUARIO=RC.USUARIO_ASIGNADO AND
                  --RC.MEDIO_DIGITACION='M' AND
                  --RC.FECHA_EJE IS NOT NULL AND
                  RC.FECHA_ASIGNACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where
                GROUP BY
                  RC.USUARIO_ASIGNADO,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA,
                  TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY'))";
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


    public function getCantRutManCorrEjePerEje($pro, $sector, $fecini, $fecfin)
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
            $where .= " and RC.FECHA_REEALIZACION >=TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecfin) != '') {
            $where .= " and RC.FECHA_REEALIZACION <=TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

         $sql = "SELECT
                COUNT(*) TOTAL
              FROM (
                SELECT
                  count(1)
                FROM
                  SGC_TT_MANT_CORRMED RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                   RC.CODIGO_INM=I.CODIGO_INM AND
                  US.ID_USUARIO=RC.USUARIO_ASIGNADO AND
                  --RC.MEDIO_DIGITACION='M' AND
                  --RC.FECHA_EJE IS NOT NULL AND
                  RC.FECHA_REEALIZACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where
                GROUP BY
                  RC.USUARIO_ASIGNADO,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA,
                  TO_CHAR(RC.FECHA_REEALIZACION,'DD-MM-YYYY'))";
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
    public function getCantRutManPreEjePerEje($pro, $sector, $fecini, $fecfin)
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
            $where .= " and RC.FECHA_REEALIZACION >=TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecfin) != '') {
            $where .= " and RC.FECHA_REEALIZACION <=TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

         $sql = "SELECT
                COUNT(*) TOTAL
              FROM (
                SELECT
                  count(1)
                FROM
                  SGC_TT_MANT_MED RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                   RC.CODIGO_INM=I.CODIGO_INM AND
                  US.ID_USUARIO=RC.USUARIO_ASIGNADO AND
                  --RC.MEDIO_DIGITACION='M' AND
                  --RC.FECHA_EJE IS NOT NULL AND
                  RC.FECHA_REEALIZACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where
                GROUP BY
                  RC.USUARIO_ASIGNADO,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA,
                  TO_CHAR(RC.FECHA_REEALIZACION,'DD-MM-YYYY'))";
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




    public function obtenerResumenRutasInstMed($sort, $start, $end, $pro, $sec, $fecIni, $fecFin)
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
            $where .= " and RC.FECHA_ASIGNACION >=TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecFin) != '') {
            $where .= " and RC.FECHA_ASIGNACION <=TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (
                  SELECT
                RC.USUARIO_ASIGNADO ID_USUARIO,
                  US.NOM_USR||' '||US.APE_USR NOMBRE,
                  I.ID_SECTOR||I.ID_RUTA RUTA,
                  TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY') FECHA_PLA,
                  TO_CHAR(MAX(RC.FECHA_REEALIZACION),'YYYY/MM/DD HH24:MI:SS') FIN,
                  TO_CHAR(MIN(RC.FECHA_REEALIZACION),'YYYY/MM/DD HH24:MI:SS') INICIO,
                   SUBSTR('0'||TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24,0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24*60,0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24*60*60,0),60),-2) TIEMPO,

                   SUBSTR('0'||TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24/count(1),0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1),0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60*60/count(1),0),60),-2) TIEMPO_PRO,
                    case (((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1)) when
                   0 then 0
                   else
                   round(60/(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1)),1)
                   end PRE_HORA,


                  count(1) CANTIDAD
                FROM
                  SGC_TT_ORDENES_CAMBINS_MED RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                  RC.CODIGO_INM=I.CODIGO_INM AND
                  US.ID_USUARIO=RC.USUARIO_ASIGNADO AND
                  RC.FECHA_ASIGNACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where

                GROUP BY
                  RC.USUARIO_ASIGNADO,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA,
                  TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY')
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

    public function obtenerDetalleRutasInstMed( $pro, $sec, $ini, $fin)
    {

        $pro    = addslashes($pro);
        $sec    = addslashes($sec);
        $ini    = addslashes($ini);
        $fin    = addslashes($fin);

        $where = '';

        if (trim($sec) != '') {
            $where .= " AND I.ID_SECTOR='$sec' ";
        }

        if (trim($ini) != '') {
            $where .= " AND RC.FECHA_ASIGNACION >=TO_DATE('$ini 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fin) != '') {
            $where .= " AND RC.FECHA_ASIGNACION <=TO_DATE('$fin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql = "SELECT
        RC.ID_ORDEN, I.CODIGO_INM, RC.USUARIO_ASIGNADO ID_USUARIO, US.NOM_USR||' '||US.APE_USR NOMBRE, I.DIRECCION, U.DESC_URBANIZACION, I.ID_SECTOR, 
        I.ID_RUTA, I.ID_PROCESO, I.CATASTRO, RC.MARCA_MEDNUEVO, RC.SERIAL_MEDNUEVO, RC.CALIBRE_MEDNUEVO, RC.FECHA_REEALIZACION, 
        RC.OBSERVACIONES, FC.URL_FOTO
        FROM
                  SGC_TT_ORDENES_CAMBINS_MED RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I,
                  SGC_TP_URBANIZACIONES U,
                  SGC_TT_FOTOS_CAMBINS_MED FC
        WHERE
                 I.CONSEC_URB = U.CONSEC_URB AND
                 RC.CODIGO_INM=I.CODIGO_INM AND
                 US.ID_USUARIO=RC.USUARIO_ASIGNADO AND 
                 RC.ID_ORDEN = FC.ID_ORDEN (+) AND
                 RC.FECHA_ASIGNACION IS NOT NULL AND
                 RC.SERIAL_MEDNUEVO IS NOT NULL  AND
                 FC.CONSECUTIVO (+) = 1 AND
                 I.ID_PROYECTO='$pro'
                 $where";

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




    public function obtenerResumenRutasInstMedFecEje($sort, $start, $end, $pro, $sec, $fecIni, $fecFin)
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
            $where .= " and RC.FECHA_REEALIZACION >=TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecFin) != '') {
            $where .= " and RC.FECHA_REEALIZACION <=TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (
                  SELECT
                RC.USUARIO_ASIGNADO ID_USUARIO,
                  US.NOM_USR||' '||US.APE_USR NOMBRE,
                  I.ID_SECTOR||I.ID_RUTA RUTA,
                  TO_CHAR(MAX(RC.FECHA_REEALIZACION),'YYYY/MM/DD HH24:MI:SS') FIN,
                  TO_CHAR(MIN(RC.FECHA_REEALIZACION),'YYYY/MM/DD HH24:MI:SS') INICIO,
                   SUBSTR('0'||TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24,0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24*60,0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24*60*60,0),60),-2) TIEMPO,

                   SUBSTR('0'||TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24/count(1),0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1),0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60*60/count(1),0),60),-2) TIEMPO_PRO,
                    case (((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1)) when
                   0 then 0
                   else
                   round(60/(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1)),1)
                   end PRE_HORA,


                  count(1) CANTIDAD
                FROM
                  SGC_TT_ORDENES_CAMBINS_MED RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                  RC.CODIGO_INM=I.CODIGO_INM AND
                  US.ID_USUARIO=RC.USUARIO_ASIGNADO AND
                  --RC.MEDIO_DIGITACION='M' AND
                  --RC.FECHA_EJE IS NOT NULL AND
                  RC.FECHA_ASIGNACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where

                GROUP BY
                  RC.USUARIO_ASIGNADO,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA
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




    public function obtenerResumenRutasManPre($sort, $start, $end, $pro, $sec, $fecIni, $fecFin)
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
            $where .= " and RC.FECHA_ASIGNACION >=TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecFin) != '') {
            $where .= " and RC.FECHA_ASIGNACION <=TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (
                  SELECT
                RC.USUARIO_ASIGNADO ID_USUARIO,
                  US.NOM_USR||' '||US.APE_USR NOMBRE,
                  I.ID_SECTOR||I.ID_RUTA RUTA,
                  TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY') FECHA_PLA,
                  TO_CHAR(MAX(RC.FECHA_REEALIZACION),'YYYY/MM/DD HH24:MI:SS') FIN,
                  TO_CHAR(MIN(RC.FECHA_REEALIZACION),'YYYY/MM/DD HH24:MI:SS') INICIO,
                   SUBSTR('0'||TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24,0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24*60,0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24*60*60,0),60),-2) TIEMPO,

                   SUBSTR('0'||TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24/count(1),0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1),0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60*60/count(1),0),60),-2) TIEMPO_PRO,
                    case (((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1)) when
                   0 then 0
                   else
                   round(60/(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1)),1)
                   end PRE_HORA,


                  count(1) CANTIDAD
                FROM
                  SGC_TT_MANT_MED RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                  RC.CODIGO_INM=I.CODIGO_INM AND
                  US.ID_USUARIO=RC.USUARIO_ASIGNADO AND
                  --RC.MEDIO_DIGITACION='M' AND
                  --RC.FECHA_EJE IS NOT NULL AND
                  RC.FECHA_ASIGNACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where

                GROUP BY
                  RC.USUARIO_ASIGNADO,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA,
                  TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY')
                   $sort)a
        WHERE rownum <= $start ) WHERE rnum >= $end+1";
       // echo  $sql;
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


    public function obtenerResumenRutasManCorr($sort, $start, $end, $pro, $sec, $fecIni, $fecFin)
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
            $where .= " and RC.FECHA_ASIGNACION >=TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecFin) != '') {
            $where .= " and RC.FECHA_ASIGNACION <=TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (
                  SELECT
                RC.USUARIO_ASIGNADO ID_USUARIO,
                  US.NOM_USR||' '||US.APE_USR NOMBRE,
                  I.ID_SECTOR||I.ID_RUTA RUTA,
                  TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY') FECHA_PLA,
                  TO_CHAR(MAX(RC.FECHA_REEALIZACION),'YYYY/MM/DD HH24:MI:SS') FIN,
                  TO_CHAR(MIN(RC.FECHA_REEALIZACION),'YYYY/MM/DD HH24:MI:SS') INICIO,
                   SUBSTR('0'||TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24,0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24*60,0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24*60*60,0),60),-2) TIEMPO,

                   SUBSTR('0'||TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24/count(1),0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1),0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60*60/count(1),0),60),-2) TIEMPO_PRO,
                    case (((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1)) when
                   0 then 0
                   else
                   round(60/(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1)),1)
                   end PRE_HORA,


                  count(1) CANTIDAD
                FROM
                  SGC_TT_MANT_CORRMED RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                  RC.CODIGO_INM=I.CODIGO_INM AND
                  US.ID_USUARIO=RC.USUARIO_ASIGNADO AND
                  --RC.MEDIO_DIGITACION='M' AND
                  --RC.FECHA_EJE IS NOT NULL AND
                  RC.FECHA_ASIGNACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where

                GROUP BY
                  RC.USUARIO_ASIGNADO,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA,
                  TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY')
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


    public function obtenerResumenRutasManCorrEje($sort, $start, $end, $pro, $sec, $fecIni, $fecFin)
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
            $where .= " and RC.FECHA_REEALIZACION >=TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecFin) != '') {
            $where .= " and RC.FECHA_REEALIZACION <=TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (
                  SELECT
                RC.USUARIO_ASIGNADO ID_USUARIO,
                  US.NOM_USR||' '||US.APE_USR NOMBRE,
                  I.ID_SECTOR||I.ID_RUTA RUTA,
                  TO_CHAR(MAX(RC.FECHA_REEALIZACION),'YYYY/MM/DD HH24:MI:SS') FIN,
                  TO_CHAR(MIN(RC.FECHA_REEALIZACION),'YYYY/MM/DD HH24:MI:SS') INICIO,
                   SUBSTR('0'||TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24,0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24*60,0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24*60*60,0),60),-2) TIEMPO,

                   SUBSTR('0'||TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24/count(1),0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1),0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60*60/count(1),0),60),-2) TIEMPO_PRO,
                    case (((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1)) when
                   0 then 0
                   else
                   round(60/(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1)),1)
                   end PRE_HORA,


                  count(1) CANTIDAD
                FROM
                  SGC_TT_MANT_CORRMED RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                  RC.CODIGO_INM=I.CODIGO_INM AND
                  US.ID_USUARIO=RC.USUARIO_ASIGNADO AND
                  --RC.MEDIO_DIGITACION='M' AND
                  --RC.FECHA_EJE IS NOT NULL AND
                  RC.FECHA_REEALIZACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where

                GROUP BY
                  RC.USUARIO_ASIGNADO,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA
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
    public function obtenerResumenRutasManPreEje($sort, $start, $end, $pro, $sec, $fecIni, $fecFin)
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
            $where .= " and RC.FECHA_REEALIZACION >=TO_DATE('$fecIni 00:00:00','YYYY-MM-DD HH24:MI:SS')";
        }

        if (trim($fecFin) != '') {
            $where .= " and RC.FECHA_REEALIZACION <=TO_DATE('$fecFin 23:59:59','YYYY-MM-DD HH24:MI:SS')";
        }

        $sql = "SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM (
                  SELECT
                RC.USUARIO_ASIGNADO ID_USUARIO,
                  US.NOM_USR||' '||US.APE_USR NOMBRE,
                  I.ID_SECTOR||I.ID_RUTA RUTA,
                  TO_CHAR(MAX(RC.FECHA_REEALIZACION),'YYYY/MM/DD HH24:MI:SS') FIN,
                  TO_CHAR(MIN(RC.FECHA_REEALIZACION),'YYYY/MM/DD HH24:MI:SS') INICIO,
                   SUBSTR('0'||TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24,0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24*60,0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24*60*60,0),60),-2) TIEMPO,

                   SUBSTR('0'||TRUNC((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION))*24/count(1),0),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1),0),60),-2)||':'||
                   SUBSTR('0'||MOD(TRUNC(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60*60/count(1),0),60),-2) TIEMPO_PRO,
                    case (((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1)) when
                   0 then 0
                   else
                   round(60/(((max(RC.FECHA_REEALIZACION)-MIN(RC.FECHA_REEALIZACION)))*24*60/count(1)),1)
                   end PRE_HORA,


                  count(1) CANTIDAD
                FROM
                  SGC_TT_MANT_MED RC,
                  SGC_TT_USUARIOS US,
                  SGC_tt_INMUEBLES I
                WHERE
                  RC.CODIGO_INM=I.CODIGO_INM AND
                  US.ID_USUARIO=RC.USUARIO_ASIGNADO AND
                  --RC.MEDIO_DIGITACION='M' AND
                  --RC.FECHA_EJE IS NOT NULL AND
                  RC.FECHA_REEALIZACION IS NOT NULL AND
                  I.ID_PROYECTO='$pro'
                  $where

                GROUP BY
                  RC.USUARIO_ASIGNADO,
                  US.NOM_USR||' '||US.APE_USR,
                  I.ID_SECTOR||I.ID_RUTA
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


    public function getInstMedReaByFecRutSecPer($fecha, $ruta, $usr)
    {
        $fecha  = addslashes($fecha);
        $ruta    = addslashes($ruta);
        $usr     = addslashes($usr);
         $sql     = "SELECT
                RC.CODIGO_INM ID_INMUEBLE,
                I.ID_PROCESO,
                I.CATASTRO,
                NVL(C.ALIAS,CLI.NOMBRE_CLI)NOMBRE,
                I.DIRECCION,
                NVL(MI.SERIAL,'N/A') SERIAL,
                NVL(RC.OBSERVACIONES,'N/A') OBSERVACIONES,
                TO_CHAR(RC.FECHA_REEALIZACION,'DD/MM/YYYY HH24:MI:SS') FECHA,
                rc.ID_ORDEN ORDEN
              FROM
                SGC_TT_ORDENES_CAMBINS_MED RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_CONTRATOS C,
                SGC_TT_CLIENTES CLI,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=RC.CODIGO_INM AND
                C.CODIGO_INM(+)=I.CODIGO_INM AND
                CLI.CODIGO_CLI(+)=C.CODIGO_CLI AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                C.FECHA_FIN (+)IS NULL AND 
                MI.FECHA_BAJA(+) IS NULL AND
                TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY')='$fecha' AND
                RC.USUARIO_ASIGNADO='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta'
                ORDER BY RC.FECHA_REEALIZACION ASC
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


    public function getInstMedReaByFecIniFinRutSecPer($fechaini,$fechafin, $ruta, $usr)
    {
        $fechaini  = addslashes($fechaini);
        $fechafin  = addslashes($fechafin);
        $ruta    = addslashes($ruta);
        $usr     = addslashes($usr);
         $sql     = "SELECT
                RC.CODIGO_INM ID_INMUEBLE,
                I.ID_PROCESO,
                I.CATASTRO,
                NVL(C.ALIAS,CLI.NOMBRE_CLI)NOMBRE,
                I.DIRECCION,
                NVL(MI.SERIAL,'N/A') SERIAL,
                NVL(RC.OBSERVACIONES,'N/A') OBSERVACIONES,
                TO_CHAR(RC.FECHA_REEALIZACION,'DD/MM/YYYY HH24:MI:SS') FECHA,
                rc.ID_ORDEN ORDEN
              FROM
                SGC_TT_ORDENES_CAMBINS_MED RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_CONTRATOS C,
                SGC_TT_CLIENTES CLI,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=RC.CODIGO_INM AND
                C.CODIGO_INM(+)=I.CODIGO_INM AND
                CLI.CODIGO_CLI(+)=C.CODIGO_CLI AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                C.FECHA_FIN (+)IS NULL AND 
                MI.FECHA_BAJA(+) IS NULL AND
                RC.FECHA_REEALIZACION>=TO_DATE('$fechaini 00:01','YYYY-MM-DD hh24:mi') AND
                RC.FECHA_REEALIZACION<=TO_DATE('$fechafin 23:59','YYYY-MM-DD hh24:mi') AND
                RC.USUARIO_ASIGNADO='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta'
                ORDER BY RC.FECHA_REEALIZACION ASC
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

public function getManPreReaByFecRutSecPer($fecha, $ruta, $usr)
    {
        $fecha  = addslashes($fecha);
        $ruta    = addslashes($ruta);
        $usr     = addslashes($usr);
         $sql     = "SELECT
                RC.CODIGO_INM ID_INMUEBLE,
                I.ID_PROCESO,
                I.CATASTRO,
                NVL(C.ALIAS,CLI.NOMBRE_CLI)NOMBRE,
                I.DIRECCION,
                NVL(MI.SERIAL,'N/A') SERIAL,
                NVL( TRIM(RC.OBS_MANTENIMIENTO||' '||RC.OBS_GENERAL) ,'N/A') OBSERVACIONES,
                TO_CHAR(RC.FECHA_REEALIZACION,'DD/MM/YYYY HH24:MI:SS') FECHA,
                rc.ID_ORDEN ORDEN,
               (SELECT COUNT(1)TOTAL
                FROM SGC_TT_FOTOS_MED_PRE FC1
                WHERE FC1.ORDEN=RC.ID_ORDEN) CANTIDAD_FOTOS
              FROM
                SGC_TT_MANT_MED RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_CONTRATOS C,
                SGC_TT_CLIENTES CLI,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=RC.CODIGO_INM AND
                C.CODIGO_INM(+)=I.CODIGO_INM AND
                CLI.CODIGO_CLI(+)=C.CODIGO_CLI AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                C.FECHA_FIN (+)IS NULL AND 
                MI.FECHA_BAJA(+) IS NULL AND
                TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY')='$fecha' AND
                RC.USUARIO_ASIGNADO='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta'
                ORDER BY RC.FECHA_REEALIZACION ASC
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

public function getManCorrReaByFecRutSecPer($fecha, $ruta, $usr)
    {
        $fecha  = addslashes($fecha);
        $ruta    = addslashes($ruta);
        $usr     = addslashes($usr);
         $sql     = "SELECT
                RC.CODIGO_INM ID_INMUEBLE,
                I.ID_PROCESO,
                I.CATASTRO,
                NVL(C.ALIAS,CLI.NOMBRE_CLI)NOMBRE,
                I.DIRECCION,
                NVL(MI.SERIAL,'N/A') SERIAL,
                NVL( TRIM(RC.OBS_MANTENIMIENTO||' '||RC.OBS_GENERAL) ,'N/A') OBSERVACIONES,
                TO_CHAR(RC.FECHA_REEALIZACION,'DD/MM/YYYY HH24:MI:SS') FECHA,
                rc.ID_ORDEN ORDEN
              FROM
                SGC_TT_MANT_CORRMED RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_CONTRATOS C,
                SGC_TT_CLIENTES CLI,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=RC.CODIGO_INM AND
                C.CODIGO_INM(+)=I.CODIGO_INM AND
                CLI.CODIGO_CLI(+)=C.CODIGO_CLI AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                C.FECHA_FIN (+)IS NULL AND 
                MI.FECHA_BAJA(+) IS NULL AND
                TO_CHAR(RC.FECHA_ASIGNACION,'DD-MM-YYYY')='$fecha' AND
                RC.USUARIO_ASIGNADO='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta'
                ORDER BY RC.FECHA_REEALIZACION ASC
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



public function getManCorrReaByFecEjeRutSecPer($fechaini,$fechafin, $ruta, $usr)
    {
        $fechaini  = addslashes($fechaini);
        $fechafin  = addslashes($fechafin);
        $ruta    = addslashes($ruta);
        $usr     = addslashes($usr);
         $sql     = "SELECT
                RC.CODIGO_INM ID_INMUEBLE,
                I.ID_PROCESO,
                I.CATASTRO,
                NVL(C.ALIAS,CLI.NOMBRE_CLI)NOMBRE,
                I.DIRECCION,
                NVL(MI.SERIAL,'N/A') SERIAL,
                NVL( TRIM(RC.OBS_MANTENIMIENTO||' '||RC.OBS_GENERAL) ,'N/A') OBSERVACIONES,
                TO_CHAR(RC.FECHA_REEALIZACION,'DD/MM/YYYY HH24:MI:SS') FECHA,
                rc.ID_ORDEN ORDEN
              FROM
                SGC_TT_MANT_CORRMED RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_CONTRATOS C,
                SGC_TT_CLIENTES CLI,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=RC.CODIGO_INM AND
                C.CODIGO_INM(+)=I.CODIGO_INM AND
                CLI.CODIGO_CLI(+)=C.CODIGO_CLI AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                C.FECHA_FIN (+)IS NULL AND 
                MI.FECHA_BAJA(+) IS NULL AND
                RC.FECHA_REEALIZACION >=TO_DATE('$fechaini 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                RC.FECHA_REEALIZACION <=TO_DATE('$fechafin 23:59:00','YYYY-MM-DD HH24:MI:SS') AND
                RC.USUARIO_ASIGNADO='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta'
                ORDER BY RC.FECHA_REEALIZACION ASC
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


public function getManPreReaByFecEjeRutSecPer($fechaini,$fechafin, $ruta, $usr)
    {
        $fechaini  = addslashes($fechaini);
        $fechafin  = addslashes($fechafin);
        $ruta    = addslashes($ruta);
        $usr     = addslashes($usr);
         $sql     = "SELECT
                RC.CODIGO_INM ID_INMUEBLE,
                I.ID_PROCESO,
                I.CATASTRO,
                NVL(C.ALIAS,CLI.NOMBRE_CLI)NOMBRE,
                I.DIRECCION,
                NVL(MI.SERIAL,'N/A') SERIAL,
                NVL( TRIM(RC.OBS_MANTENIMIENTO||' '||RC.OBS_GENERAL) ,'N/A') OBSERVACIONES,
                TO_CHAR(RC.FECHA_REEALIZACION,'DD/MM/YYYY HH24:MI:SS') FECHA,
                rc.ID_ORDEN ORDEN
              FROM
                SGC_TT_MANT_MED RC,
                SGC_TT_INMUEBLES I,
                SGC_TT_CONTRATOS C,
                SGC_TT_CLIENTES CLI,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=RC.CODIGO_INM AND
                C.CODIGO_INM(+)=I.CODIGO_INM AND
                CLI.CODIGO_CLI(+)=C.CODIGO_CLI AND
                MI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                C.FECHA_FIN (+)IS NULL AND 
                MI.FECHA_BAJA(+) IS NULL AND
                RC.FECHA_REEALIZACION >=TO_DATE('$fechaini 00:00:00','YYYY-MM-DD HH24:MI:SS') AND
                RC.FECHA_REEALIZACION <=TO_DATE('$fechafin 23:59:00','YYYY-MM-DD HH24:MI:SS') AND
                RC.USUARIO_ASIGNADO='$usr' AND
                I.ID_SECTOR||I.ID_RUTA='$ruta'
                ORDER BY RC.FECHA_REEALIZACION ASC
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



    public function getCantFotInstMedByFecInm($orden)
    {
        $orden = addslashes($orden);

        $sql = "SELECT
                COUNT(1)TOTAL
              FROM
                SGC_TT_FOTOS_CAMBINS_MED FC
              WHERE
                FC.ID_ORDEN=$orden";
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

    public function getMantCorrByProPer($periodo,$proyecto)
    {
        $periodo = addslashes($periodo);
        $proyecto = addslashes($proyecto);

        $sql = "SELECT
  ROWNUM,
  MI.COD_INMUEBLE,
  I.ID_SECTOR,
  I.ID_RUTA,
  CON.ALIAS,
  I.DIRECCION,
  MI.SERIAL,
  MED.DESC_MED,
  RL.OBSERVACION,
  MC.LECTURA,
  RL.LECTURA_ORIGINAL,
  MC.FECHA_REEALIZACION,
  CAL.DESC_CALIBRE,
  MC.OBS_MANTENIMIENTO||' '||MC.OBS_GENERAL OBS
FROM
  SGC_TT_MANT_CORRMED MC, SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TT_INMUEBLES I,
  SGC_TT_CONTRATOS CON, SGC_TP_MEDIDORES MED, SGC_TT_REGISTRO_LECTURAS RL,
  SGC_TP_CALIBRES CAL
WHERE
  MC.ID_MEDINMU=MI.ID_MEDINMU AND
  I.CODIGO_INM=MI.COD_INMUEBLE AND
  CON.CODIGO_INM(+)=I.CODIGO_INM AND
  CON.FECHA_FIN (+) IS NULL AND
  MED.CODIGO_MED=MI.COD_MEDIDOR AND
  RL.COD_INMUEBLE=I.CODIGO_INM AND
  RL.PERIODO(+)=MC.PERIODO AND
  CAL.COD_CALIBRE=MI.COD_CALIBRE AND
  MC.FECHA_REEALIZACION IS NOT NULL AND
  TO_CHAR(MC.FECHA_REEALIZACION,'YYYYMM')='$periodo' AND
  I.ID_PROYECTO='$proyecto'
ORDER BY I.ID_SECTOR,I.ID_RUTA";

        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false getMantCorrByProPer";
            return false;
        }
    }

    public function getActByMant($orden, $tipo = 'preventivo')
    {
        $orden = addslashes($orden);

        if ($tipo == "preventivo") {
          $sql = "Select ACM.DESCRIPCION
          FROM SGC_TT_ACT_MANTMED am, SGC_TP_ACT_MANTMED ACM
         WHERE am.ID_MANTMED = $orden
           AND AM.ID_ACTMANT = ACM.ID_ACTMANTMED";
        }else{
          $sql = "SELECT AM.DESCRIPCION
          FROM SGC_TT_ACT_MANTMED_CORR AMC, SGC_TP_ACT_MANTMED_COR AM
         WHERE AMC.ID_ACTMANT = AM.ID_ACTMANTMED
           AND AMC.ID_MANTMED = $orden";
        }        

        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false getMantCorrByProPer";
            return false;
        }
    }

    public function getMantPreByProPer($periodo,$proyecto)
    {
        $periodo = addslashes($periodo);
        $proyecto = addslashes($proyecto);

        $sql = "SELECT 
                ROWNUM, 
                MI.COD_INMUEBLE,
                I.ID_SECTOR,
                I.ID_RUTA, 
                CON.ALIAS,
                I.DIRECCION,
                MI.SERIAL, 
                MED.DESC_MED, 
                RL.OBSERVACION, 
                MC.LECTURA, 
                RL.LECTURA_ORIGINAL, 
                MC.FECHA_REEALIZACION,
                CAL.DESC_CALIBRE,
                 MC.OBS_MANTENIMIENTO||' '||MC.OBS_GENERAL OBS
FROM
  ACEASOFT.SGC_TT_MANT_MED MC, SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TT_INMUEBLES I,
  SGC_TT_CONTRATOS CON, SGC_TP_MEDIDORES MED, SGC_TT_REGISTRO_LECTURAS RL,
  SGC_TP_CALIBRES CAL
WHERE
  MC.ID_MEDINM=MI.ID_MEDINMU AND
  I.CODIGO_INM=MI.COD_INMUEBLE AND
  CON.CODIGO_INM(+)=I.CODIGO_INM AND
  CON.FECHA_FIN (+) IS NULL AND
  MED.CODIGO_MED=MI.COD_MEDIDOR AND
  RL.COD_INMUEBLE(+)=I.CODIGO_INM AND
  RL.PERIODO(+)=$periodo AND
  CAL.COD_CALIBRE=MI.COD_CALIBRE AND
  TO_CHAR(MC.FECHA_REEALIZACION,'YYYYMM')='$periodo' AND
  I.ID_PROYECTO='$proyecto'
ORDER BY I.ID_SECTOR,I.ID_RUTA";
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false getMantPreByProPer";
            return false;
        }
    }


    public function getCantFotManPreByOrd($orden)
    {
        $orden = addslashes($orden);


        $sql = "SELECT
                COUNT(1)TOTAL
              FROM
                SGC_TT_FOTOS_MED_PRE FC
              WHERE
                FC.ORDEN=$orden";
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
    public function getCantFotManCorrByOrd($orden)
    {
        $orden = addslashes($orden);


        $sql = "SELECT
                COUNT(1)TOTAL
              FROM
                SGC_TT_FOTOS_MED_CORR FC
              WHERE
                FC.ORDEN=$orden";
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

    public function getUrlFotInsMedByOrden($orden)
    {
        $orden = addslashes($orden);
        $sql = "SELECT FC.URL_FOTO, U.LOGIN, C.FECHA_REEALIZACION 
        FROM SGC_TT_FOTOS_CAMBINS_MED FC, SGC_TT_ORDENES_CAMBINS_MED C, SGC_TT_USUARIOS U
        WHERE FC.ID_ORDEN = C.ID_ORDEN
        AND U.ID_USUARIO = C.USUARIO_ASIGNADO
        AND FC.ID_ORDEN=$orden   ";

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

    public function getUrlFotManPreByOrden($orden)
    {
        $orden = addslashes($orden);
        $sql = "SELECT FC.URL_FOTO, U.LOGIN, M.FECHA_REEALIZACION
        FROM SGC_TT_FOTOS_MED_PRE FC, SGC_TT_MANT_MED M, SGC_TT_USUARIOS U
        WHERE FC.ORDEN = M.ID_ORDEN
        AND U.ID_USUARIO = M.USUARIO_ASIGNADO
        AND FC.ORDEN=$orden";

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

    public function getUrlFotManCorrByOrden($orden)
    {
        $orden = addslashes($orden);
        $sql = "SELECT FC.URL_FOTO, U.LOGIN, M.FECHA_REEALIZACION
        FROM SGC_TT_FOTOS_MED_CORR FC, SGC_TT_MANT_CORRMED M, SGC_TT_USUARIOS U
        WHERE FC.ORDEN = M.ID_ORDEN
        AND U.ID_USUARIO = M.USUARIO_ASIGNADO
        AND FC.ORDEN=$orden   ";

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



    public function getMantPreByFecIniFin($proyecto,$fecini,$fecfin)
    {
        $proyecto = addslashes($proyecto);
        $fecini = addslashes($fecini);
        $fecfin = addslashes($fecfin);

        $sql = "
           
           SELECT
    MM.ID_ORDEN,       
    MM.CODIGO_INM,
    U.DESC_URBANIZACION,
    I.DIRECCION,
    C.ALIAS,
    MM.LATITUD,
    MM.LONGITUD,
    MI.SERIAL,
    I.ID_SECTOR,
    I.ID_RUTA,
    MM.FECHA_REEALIZACION,
    U.LOGIN,
    MED.DESC_MED,
     CAL.DESC_CALIBRE,
     I.ID_PROCESO,
       I.CATASTRO,
       MM.FECHA_REEALIZACION,
       MM.OBS_MANTENIMIENTO,
       MM.OBS_GENERAL,
       MM.OBS_IMP OBS, 
    (SELECT MIN(FM.URL_FOTO) FROM SGC_TT_FOTOS_MED_PRE FM
     WHERE FM.ORDEN=MM.ID_ORDEN
    ) FOTO_INICIAL,
    (SELECT MAX(FM.URL_FOTO) FROM SGC_TT_FOTOS_MED_PRE FM
     WHERE FM.ORDEN=MM.ID_ORDEN
    ) FOTO_FINAL,
    (
         select count(1) from SGC_TT_FACTURA F 
WHERE F.INMUEBLE=I.CODIGO_INM
AND F.FEC_EXPEDICION IS NOT NULL AND
      F.FECHA_PAGO IS NULL AND 
      F.FACTURA_PAGADA='N'   
    ) FAC_PEND,
    (
    
    SELECT
 RL.OBSERVACION
FROM SGC_TT_REGISTRO_LECTURAS RL
WHERE PERIODO= ( SELECT MAX(PERIODO)
    FROM SGC_TT_REGISTRO_LECTURAS RL2
    WHERE RL2.COD_INMUEBLE=I.CODIGO_INM

    ) AND RL.COD_INMUEBLE=I.CODIGO_INM
    
    
    ) LECTURA

FROM
    SGC_TT_MANT_MED MM, SGC_TT_INMUEBLES I,
    SGC_TT_CONTRATOS C, SGC_TT_MEDIDOR_INMUEBLE MI,
    SGC_TT_USUARIOS U, SGC_TP_MEDIDORES MED,
     SGC_TP_URBANIZACIONES U, SGC_TP_CALIBRES CAL
WHERE MM.ID_MEDINM=MI.ID_MEDINMU AND
        I.CODIGO_INM=MI.COD_INMUEBLE AND
        U.ID_USUARIO=MM.USUARIO_ASIGNADO AND
        C.CODIGO_INM(+)=I.CODIGO_INM AND
        C.FECHA_FIN (+) IS NULL AND
        MED.CODIGO_MED=MI.COD_MEDIDOR AND
        U.CONSEC_URB=I.CONSEC_URB AND
      CAL.COD_CALIBRE=MI.COD_CALIBRE AND
        I.ID_PROYECTO='$proyecto' AND
        MM.FECHA_REEALIZACION>=TO_DATE('$fecini 00:01','YYYY-MM-DD HH24:MI') AND
        MM.FECHA_REEALIZACION<=TO_DATE('$fecfin 23:59','YYYY-MM-DD HH24:MI')
           
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




    public function getMantCorrByFecIniFin($proyecto,$fecini,$fecfin)
    {
        $proyecto = addslashes($proyecto);
        $fecini = addslashes($fecini);
        $fecfin = addslashes($fecfin);

        $sql = "
            SELECT
            MM.ID_ORDEN,
             MM.CODIGO_INM,
             I.DIRECCION,
             C.ALIAS,
             MI.SERIAL,
             MM.LATITUD,
             MM.LONGITUD,
             I.ID_SECTOR,
             I.ID_RUTA,
             MM.FECHA_REEALIZACION,
             U.LOGIN,
             MED.DESC_MED,
             I.ID_PROCESO,
             CAL.DESC_CALIBRE,
             I.CATASTRO,
             MM.OBS_MANTENIMIENTO,
             (SELECT RL.OBSERVACION FROM SGC_TT_REGISTRO_LECTURAS RL
             WHERE RL.COD_INMUEBLE=MM.CODIGO_INM 
              AND RL.PERIODO=(
              SELECT MAX(RL2.PERIODO) FROM SGC_TT_REGISTRO_LECTURAS RL2
              where RL2.COD_INMUEBLE = RL.COD_INMUEBLE
              AND RL2.FECHA_LECTURA_ORI<=MM.FECHA_REEALIZACION
              )) OBS, 
              (SELECT COUNT(*) FROM SGC_TT_FACTURA F
              WHERE F.INMUEBLE = MM.CODIGO_INM
              AND F.FACTURA_PAGADA = 'N')FAC_PEND,
              (SELECT MAX(FM.URL_FOTO) FROM SGC_TT_FOTOS_MED_CORR FM
                WHERE FM.ORDEN=MM.ID_ORDEN
              ) FOTO,
              (SELECT RL.OBSERVACION
              FROM SGC_TT_REGISTRO_LECTURAS RL
              WHERE PERIODO= ( SELECT MAX(PERIODO)
              FROM SGC_TT_REGISTRO_LECTURAS RL2
              WHERE RL2.COD_INMUEBLE=I.CODIGO_INM) AND RL.COD_INMUEBLE=I.CODIGO_INM) LECTURA,
              U.DESC_URBANIZACION
             FROM
            ACEASOFT.SGC_TT_MANT_CORRMED MM, SGC_TT_INMUEBLES I,
            SGC_TT_CONTRATOS C, SGC_TT_MEDIDOR_INMUEBLE MI,
            SGC_TT_USUARIOS U, SGC_TP_MEDIDORES MED, SGC_TP_CALIBRES CAL,
            SGC_TP_URBANIZACIONES U
            WHERE MM.ID_MEDINMU=MI.ID_MEDINMU AND 
            I.CODIGO_INM=MI.COD_INMUEBLE AND 
            U.ID_USUARIO=MM.USUARIO_ASIGNADO AND
            C.CODIGO_INM(+)=I.CODIGO_INM AND
            C.FECHA_FIN (+) IS NULL AND 
            MED.CODIGO_MED=MI.COD_MEDIDOR AND 
            CAL.COD_CALIBRE=MI.COD_CALIBRE AND
            I.ID_PROYECTO='$proyecto' AND 
            MM.FECHA_REEALIZACION>=TO_DATE('$fecini 00:01','YYYY-MM-DD HH24:MI') AND 
            MM.FECHA_REEALIZACION<=TO_DATE('$fecfin 23:59','YYYY-MM-DD HH24:MI') AND
            U.CONSEC_URB=I.CONSEC_URB
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