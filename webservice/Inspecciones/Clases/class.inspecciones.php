<?php
include "class.conexion.php";
class Inspecciones extends ConexionClass{

	private $secInspeccion;
	private $fechEje;
	private $observacion;
	private $latitud;
	private $longitud;
	private $reconectado;
	private $meError;
	private $codError;
	private $codUsr;
	private $tipoCor;

	public function __construct(){
		parent::__construct();
		$this->secInspeccion="";
		$this->fechEje="";
		$this->observacion="";
		$this->latitud="";
		$this->longitud="";
		$this->reconectado="";
		$this->meError="";
		$this->codError="";
		$this->codUsr="";
		$this->tipoCor="";
	}

	public function setSecIns($valor){
		$this->secInspeccion=$valor;
	}
	public function setTipoCor($valor){
		$this->tipoCor=$valor;
	}
	public function setFechaEje($valor){
		$this->fechEje=$valor;
	}
	public function setObs($valor){
		$this->observacion=$valor;
	}
	public function setLatitud($valor){
		$this->latitud=$valor;
	}
	public function setLongitud($valor){
		$this->longitud=$valor;
	}
	public function setReconectado($valor){
		$this->reconectado=$valor;
	}
	public function setUsr($valor){
		$this->codUsr=$valor;
	}
	public function getMsError(){
		return $this->meError;
	}
	public function getCodError(){
		return $this->codError;
	}

	public function guardaIns(){

		$sql="BEGIN SGC_P_INGRESA_INSPECCION($this->secInspeccion,'$this->fechEje','$this->observacion','$this->latitud','$this->longitud','$this->reconectado','$this->tipoCor',:PMSGRESULT,:PCODRESULT);COMMIT;END;";

		$resultado=oci_parse($this->_db,$sql);
		oci_bind_by_name($resultado,":PMSGRESULT",$this->meError,123);
		oci_bind_by_name($resultado,":PCODRESULT",$this->codError,123);

		if(oci_execute($resultado)){
			if($this->codError==0){
                $nombrelog=date('Y-m-d');
                $file = fopen("Logs/log-$nombrelog-insOK- $this->codUsr.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql . PHP_EOL);
                fclose($file);
				return true;
			}else{
                $nombrelog=date('Y-m-d');
                $file = fopen("Logs/log-$nombrelog-insFail- $this->codUsr.txt", "a");
                fwrite($file, date('Ymd H:i:s') . PHP_EOL);
                fwrite($file, $sql . PHP_EOL);
                fwrite($file,  "error:  ".$this->meError);
                fclose($file);
                echo "Error al guardar el corte $this->meError   $this->secInspeccion   $this->secInspeccion ";
				false;
			}
		}
		else
		{
			echo "error consulta ingresa corte";
			return false;
		}
	}

	public function obtenerrecorrido(){
		if( trim($this->idgrupo)!=""){
			$this->operario='';
		}

		$sql="select
       I.CODIGO_INM COD_INM,
       IC.CONSEC_INSPECCION ORDEN,
       I.ID_PROCESO PROCESO,
       I.CATASTRO CATASTRO,
       I.DIRECCION DIRECCION,
       CON.ALIAS NOMBRE,
       (case when MI.FECHA_BAJA is null then  EM.DESC_EMPLAZAMIENTO else '' end) EMPLAZAMIENTO,
       (case when MI.FECHA_BAJA is null then  MED.DESC_MED  else '' end)  MEDIDOR,
       AC.DESC_ACTIVIDAD ACTIVIDAD,
       (case when MI.FECHA_BAJA is null then  C.DESC_CALIBRE  else '' end) CALIBRE,
       (case when MI.FECHA_BAJA is null then  MI.SERIAL  else '' end) SERIAL,
       AC.DESC_ACTIVIDAD ACTIVIDAD,mi.FECHA_INSTALACION,MI.FECHA_BAJA
FROM SGC_TT_INSPECCIONES_CORTES IC, SGC_TT_REGISTRO_CORTES RC, SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS CON, SGC_TT_MEDIDOR_INMUEBLE MI,SGC_TP_EMPLAZAMIENTO EM
    ,SGC_TP_MEDIDORES MED, SGC_tP_ACTIVIDADES AC, SGC_TP_CALIBRES C,(
                                                                    select MAX(NVL(TO_CHAR(MI2.FECHA_INSTALACION,'YYYY-MM-DD HH24:MI:SS'),'SIN MEDIDOR')) FECHA_INSTALACION
                                                                           ,I2.CODIGO_INM

                                                                    FROM SGC_TT_INSPECCIONES_CORTES IC2, SGC_TT_REGISTRO_CORTES RC2, SGC_TT_INMUEBLES I2, SGC_TT_CONTRATOS CON2, SGC_TT_MEDIDOR_INMUEBLE MI2,SGC_TP_EMPLAZAMIENTO EM2
                                                                        ,SGC_TP_MEDIDORES MED2, SGC_tP_ACTIVIDADES AC2, SGC_TP_CALIBRES C2
                                                                    WHERE
                                                                          RC2.ORDEN=IC2.ORDEN_CORTE
                                                                      AND I2.CODIGO_INM=RC2.ID_INMUEBLE
                                                                      AND I2.CODIGO_INM= CON2.CODIGO_INM(+)
                                                                      AND CON2.FECHA_FIN (+)IS NULL
                                                                      AND MI2.FECHA_BAJA (+) IS NULL
                                                                      AND I2.CODIGO_INM=MI2.COD_INMUEBLE(+)
                                                                      AND MI2.COD_EMPLAZAMIENTO=EM2.COD_EMPLAZAMIENTO(+)
                                                                      AND MI2.COD_MEDIDOR=MED2.CODIGO_MED(+)
                                                                      AND I2.SEC_ACTIVIDAD=AC2.SEC_ACTIVIDAD
                                                                      AND MI2.COD_CALIBRE=C2.COD_CALIBRE(+)
                                                                      AND IC2.USR_ASIG='$this->codUsr'
                                                                      AND IC2.FECHA_EJE IS NULL
                                                                      AND IC2.ANULADA='N'
GROUP BY I2.CODIGO_INM
ORDER BY I2.CODIGO_INM
) IA
WHERE
    RC.ORDEN=IC.ORDEN_CORTE
  AND I.CODIGO_INM=RC.ID_INMUEBLE
  AND I.CODIGO_INM=IA.CODIGO_INM
  AND I.CODIGO_INM= CON.CODIGO_INM(+)
  AND CON.FECHA_FIN (+)IS NULL
  AND I.CODIGO_INM=MI.COD_INMUEBLE(+)
  AND MI.COD_EMPLAZAMIENTO=EM.COD_EMPLAZAMIENTO(+)
  AND MI.COD_MEDIDOR=MED.CODIGO_MED(+)
  AND MI.FECHA_BAJA (+) IS NULL
  AND I.SEC_ACTIVIDAD=AC.SEC_ACTIVIDAD
  AND MI.COD_CALIBRE=C.COD_CALIBRE(+)
  AND NVL(TO_CHAR(MI.FECHA_INSTALACION,'YYYY-MM-DD HH24:MI:SS'),'SIN MEDIDOR')=IA.FECHA_INSTALACION
  --and MI.FECHA_INSTALACION= (select max(mi2.FECHA_INSTALACION) from SGC_TT_MEDIDOR_INMUEBLE mi2 where i.CODIGO_INM=mi2.COD_INMUEBLE)
 AND IC.USR_ASIG='$this->codUsr'
  AND IC.FECHA_EJE IS NULL
  AND IC.ANULADA='N'
ORDER BY I.CODIGO_INM";

		$resultado=oci_parse($this->_db,$sql);

		//echo $sql;
		$bandera=oci_execute($resultado);
		if($bandera=TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "error al obtener la ruta de inspecciones";
			return false;
		}
	}

	public function obteneCantRuta(){

		$sql="Select count(*) CANTIDAD
               From SGC_TT_INSPECCIONES_CORTES IC
               where IC.USR_ASIG ='$this->codUsr'
               and IC.FECHA_EJE is null";
		$resultado=oci_parse($this->_db,$sql );

		$bandera=oci_execute($resultado);
		if($bandera){
			oci_close($this->_db);
			return  $resultado;
		}else{
			echo "error al obtener las rutas";
			oci_close($this->_db);
			return false;
		}
	}


}