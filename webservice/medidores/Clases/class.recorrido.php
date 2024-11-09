<?php
include_once 'class.conexion.php';

class recorrido extends ConexionClass{
	private $zona;
	private $operario;
	private $tiporuta;
	
	public function __construct(){
		parent::__construct();
		$this->zona="";
		$this->operario="";
		$this->tiporuta="4";
		
	}
	public function setzona		($valor){$this->zona=$valor;}
	public function setoperario	($valor){$this->operario=$valor;}
	public function settiporuta	($valor){$this->tiporuta=$valor;}

	


	


	public function obtenerrecorridoMantPre(){
		 $sql="SELECT 
                OC.ID_ORDEN ORDEN,
                OC.CODIGO_INM COD_SISTEMA, 
                INM.DIRECCION,
                INM.CATASTRO,
                INM.ID_PROCESO PROCESO, 
                CON.ALIAS,
                EMP.DESC_EMPLAZAMIENTO EMPLAZAMIENTO,
                MED.DESC_MED MEDIDOR,
                CAL.DESC_CALIBRE,
                MI.SERIAL
              FROM
                SGC_TT_MANT_MED OC,
                SGC_TT_INMUEBLES INM,
                SGC_TT_CLIENTES CLI,
                SGC_TT_CONTRATOS CON,
                SGC_TP_EMPLAZAMIENTO EMP,
                SGC_TP_MEDIDORES MED,
                SGC_TP_CALIBRES CAL,
                SGC_TT_MEDIDOR_INMUEBLE MI 
              WHERE 
                oc.USUARIO_ASIGNADO='$this->operario' AND
                ESTADO='A' AND
                MI.ID_MEDINMU=OC.ID_MEDINM AND
                INM.CODIGO_INM=OC.CODIGO_INM AND
                CON.CODIGO_INM(+)=INM.CODIGO_INM AND
                CON.FECHA_FIN(+) IS NULL  AND 
                MI.FECHA_BAJA (+) IS NULL AND
                CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
                EMP.COD_EMPLAZAMIENTO(+)=MI.COD_EMPLAZAMIENTO AND
                MED.CODIGO_MED(+)=MI.COD_MEDIDOR AND
                CAL.COD_CALIBRE(+)=MI.COD_CALIBRE";



		$resultado=oci_parse($this->_db,$sql);

		//echo $sql;
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "error al obtener la ruta de mantenimiento correctivo de medidores";
			return false;
		}
	}

	public function obtenerrecorridoMantCorr(){
		 $sql="SELECT
OC.ID_ORDEN ORDEN,
OC.CODIGO_INM COD_SISTEMA,
INM.DIRECCION,
INM.CATASTRO,
INM.ID_PROCESO PROCESO,
CON.ALIAS,
EMP.DESC_EMPLAZAMIENTO EMPLAZAMIENTO,
MED.DESC_MED MEDIDOR,
CAL.DESC_CALIBRE,
MI.SERIAL
FROM
SGC_TT_MANT_CORRMED OC,
SGC_TT_INMUEBLES INM,
SGC_TT_CLIENTES CLI,
SGC_TT_CONTRATOS CON,
SGC_TP_EMPLAZAMIENTO EMP,
SGC_TP_MEDIDORES MED,
SGC_TP_CALIBRES CAL,
SGC_TT_MEDIDOR_INMUEBLE MI
WHERE
oc.USUARIO_ASIGNADO='$this->operario' AND
ESTADO='A' AND
INM.CODIGO_INM=OC.CODIGO_INM AND
CON.CODIGO_INM(+)=INM.CODIGO_INM AND
CON.FECHA_FIN(+) IS NULL   AND 
MI.FECHA_BAJA (+) IS NULL AND
CLI.CODIGO_CLI(+)=CON.CODIGO_CLI AND
EMP.COD_EMPLAZAMIENTO(+)=MI.COD_EMPLAZAMIENTO AND
MED.CODIGO_MED(+)=MI.COD_MEDIDOR AND
CAL.COD_CALIBRE(+)=MI.COD_CALIBRE AND
MI.COD_INMUEBLE=INM.CODIGO_INM";



		$resultado=oci_parse($this->_db,$sql);

		//echo $sql;
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "error al obtener la ruta de mantenimiento correctivo de medidores";
			return false;
		}
	}

	public function obtenerrecorridoInsMed(){
		$sql="SELECT
                rr.ID_ORDEN ORDEN,
                rr.CODIGO_INM COD_SISTEMA,
                rr.FECHA_ASIGNACION FECHA,
                to_char(rr.FECHA_ASIGNACION,'yyyymm') PERIODO,
                C.ALIAS,
                I.DIRECCION,
                I.ID_PROCESO PROCESO,
                I.CATASTRO,
                I.TELEFONO,
                MI.COD_MEDIDOR MEDIDOR,
                CAL.DESC_CALIBRE,
                MI.SERIAL SERIAL,
                MI.COD_EMPLAZAMIENTO EMPLAZAMIENTO,
                trunc(MI.FECHA_INSTALACION) FEC_INSTALL,
                I.ID_PROCESO
              from 
                SGC_TT_ORDENES_CAMBINS_MED rr, 
                sgc_tt_contratos c, 
                sgc_tt_inmuebles i,
                sgc_tt_medidor_inmueble mi, 
                sgc_tp_calibres cal
              where
                I.CODIGO_INM=rr.CODIGO_INM and
                I.CODIGO_INM=MI.COD_INMUEBLE(+) and
                CAL.COD_CALIBRE(+)=MI.COD_CALIBRE AND
                C.CODIGO_INM(+)=I.CODIGO_INM AND 
                MI.FECHA_BAJA (+) IS NULL AND 
                rr.ESTADO='A' and 
                rr.USUARIO_ASIGNADO='$this->operario' AND 
                C.FECHA_FIN (+) IS NULL";



		$resultado=oci_parse($this->_db,$sql);

		//echo $sql;
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "error al obtener la ruta de instalacion de medidores";
			return false;
		}
	}
	public function obtenerrecorridoInsEstMed(){
		$sql="SELECT 
              I.CODIGO ORDEN,
              MI.COD_INMUEBLE COD_SISTEMA,
              I.FECHA_APERTURA FECHA,
              I.PERIODO, 
              co.ALIAS, 
              I2.DIRECCION, 
              I2.ID_PROCESO PROCESO,
              I2.CATASTRO,
              I2.TELEFONO,
              M.DESC_MED MEDIDOR,
              C.DESC_CALIBRE,
              MI.SERIAL,
              ID_ZONA ZONA, 
              E.DESC_EMPLAZAMIENTO EMPLAZAMIENTO , 
              MI.FECHA_INSTALACION,
              i.ULTIMA_LECTURA LECTURA,
              I.ULTIMA_LECTURA_OBS OBSLECTURA
FROM SGC_TT_INS_MED I, SGC_TT_MEDIDOR_INMUEBLE MI, SGC_TT_CONTRATOS co, SGC_tT_INMUEBLES I2,SGC_TP_MEDIDORES M,
SGC_TP_CALIBRES C, SGC_TP_EMPLAZAMIENTO E
WHERE MI.ID_MEDINMU=I.ID_MED_INM and
co.CODIGO_INM(+)=MI.COD_INMUEBLE
and co.FECHA_FIN (+) IS NULL
AND MI.COD_INMUEBLE=I2.CODIGO_INM
AND M.CODIGO_MED=MI.COD_MEDIDOR
AND C.COD_CALIBRE = MI.COD_CALIBRE
AND E.COD_EMPLAZAMIENTO = MI.COD_EMPLAZAMIENTO
AND MI.FECHA_BAJA (+) IS NULL
AND I.FECHA_EJE IS  NULL 
AND I.USUARIO_ASIGNADO='$this->operario'";



		$resultado=oci_parse($this->_db,$sql);

		//echo $sql;
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "error al obtener la ruta de instalacion de medidores";
			return false;
		}
	}



}