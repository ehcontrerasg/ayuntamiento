<?php
/********************************************************************/
/*  SOFTWARE DE GESTION COMERCIAL ACEA DOMINICANA       	        */
/*  ACEA DOMINICANA - REPUBLICA DOMINICANA							*/
/*  CREADO POR EDWIN HERNAN CONTRERAS								*/
/*  FECHA CREACION 24/10/2014								*/
/********************************************************************/

if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/
class Concepto_inmueble extends ConexionClass{
	Private $cod_inmueble;
	Private $cod_concepto;
	Private $cod_concepto_v;
 	Private $estado;
 	Private $usr_creacion;
 	Private $tarifa;
 	Private $unid_total;
 	Private $unid_hab;
 	private $cupo_bas;
 	private $actividad;
	private $msError;
	private $codError;
 
 	
 			
	
 	public function __construct()
 	{
 		parent::__construct();
 		$this->cod_inmueble="";
 		$this->cod_concepto="";
 		$this->cod_concepto_v="";
 		$this->estado="";
 		$this->usr_creacion="";
 		$this->unid_hab=0;
 		$this->unid_total=0;
 		$this->cupo_bas=0;
 		$this->actividad=0;
 	}
	
	function getMsError(){
		return $this->msError;
	}
	
	function getCodError(){
		return $this->codError;
	}
 	
 	public function settarifa($valor){
 		$this->tarifa=$valor;
 	}
 	public function setunitot($valor){
 		$this->unid_total=$valor;
 	}
 	public function setunihab($valor){
 		$this->unid_hab=$valor;
 	}
 	public function setcupobas($valor){
 		$this->cupo_bas=$valor;
 	}
 	
	public function setcodinmueble($valor){
		$this->cod_inmueble=$valor;
	}
	public function setcodconcepto($valor){
		$this->cod_concepto=$valor;
	}
	public function setcodconceptov($valor){
		$this->cod_concepto_v=$valor;
	}
	public function setestado($valor){
		$this->estado=$valor;
	}
	public function setusrcreacion($valor){
		$this->usr_creacion=$valor;
	}	
	public function setactividad($valor){
		$this->actividad=$valor;
	}
	
	public function nuevoconcepto (){
		$des=$this->unid_total-$this->unid_hab;
		$pro=$this->cupo_bas;
		$conmin=$pro*$this->unid_hab;

        echo $sql="
          INSERT INTO SGC_TT_SERVICIOS_INMUEBLES (CONSEC_SERVICIO_INM,COD_SERVICIO,CONSEC_TARIFA,COD_INMUEBLE,
              UNIDADES_TOT,UNIDADES_HAB,UNIDADES_DESH,CUPO_BASICO,PROMEDIO,CONSUMO_MINIMO,ACTIVO,USR_CREACION)
                values(SGC_S_SERV_INM.NEXTVAL,'$this->cod_concepto','$this->tarifa','$this->cod_inmueble',
                '$this->unid_total','$this->unid_hab','$des','$this->cupo_bas','$pro','$conmin','S','$this->usr_creacion')";
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
	
	
	public function nuevoconcepto2 (){
		$des=$this->unid_total-$this->unid_hab;
		$pro=$this->cupo_bas;
		$conmin=$pro*$this->unid_hab;
		$resultado = oci_parse($this->_db,"INSERT INTO SGC_TT_SERVICIOS_INMUEBLES VALUES (SGC_S_SERV_INM.NEXTVAL,
				'$this->cod_concepto',
				'$this->tarifa',
				'$this->cod_inmueble',
				nvl((select UNIDADES_TOT FROM SGC_TT_SERVICIOS_INMUEBLES WHERE COD_INMUEBLE='$this->cod_inmueble' and (cod_servicio=1 or cod_servicio=3 )),(SELECT TOTAL_UNIDADES FROM SGC_TT_INMUEBLES WHERE CODIGO_INM='$this->cod_inmueble')),
				nvl((select UNIDADES_HAB FROM SGC_TT_SERVICIOS_INMUEBLES WHERE COD_INMUEBLE='$this->cod_inmueble' and (cod_servicio=1 or cod_servicio=3 )),(SELECT UNIDADES_HAB FROM SGC_TT_INMUEBLES WHERE CODIGO_INM='$this->cod_inmueble')),
				nvl((select UNIDADES_DESH FROM SGC_TT_SERVICIOS_INMUEBLES WHERE COD_INMUEBLE='$this->cod_inmueble' and (cod_servicio=1 or cod_servicio=3)),(SELECT UNIDADES_DES FROM SGC_TT_INMUEBLES WHERE CODIGO_INM='$this->cod_inmueble')),
				nvl((select CUPO_BASICO FROM SGC_TT_SERVICIOS_INMUEBLES WHERE COD_INMUEBLE='$this->cod_inmueble' and (cod_servicio=1 or cod_servicio=3 )),(SELECT NVL(CONSUMO_MIN,0) FROM SGC_TP_TARIFAS WHERE CONSEC_TARIFA='$this->tarifa')),
				nvl((select PROMEDIO FROM SGC_TT_SERVICIOS_INMUEBLES WHERE COD_INMUEBLE='$this->cod_inmueble' and (cod_servicio=1 or cod_servicio=3 )),(SELECT NVL(CONSUMO_MIN,0) FROM SGC_TP_TARIFAS WHERE CONSEC_TARIFA='$this->tarifa')),
				
				'0','M','S','$this->usr_creacion','',
				nvl(
					(select CONSUMO_MINIMO FROM SGC_TT_SERVICIOS_INMUEBLES WHERE COD_INMUEBLE='$this->cod_inmueble' and (cod_servicio=1 or cod_servicio=3 )),
					((SELECT NVL(CONSUMO_MIN,0) FROM SGC_TP_TARIFAS WHERE CONSEC_TARIFA='$this->tarifa')*
						(SELECT UNIDADES_HAB FROM SGC_TT_INMUEBLES WHERE CODIGO_INM='$this->cod_inmueble')))
				)");

		//echo $this->cod_concepto.' '.$this->tarifa.' '.$this->cod_inmueble;


		/*echo "INSERT INTO SGC_TT_SERVICIOS_INMUEBLES VALUES (SGC_S_SERV_INM.NEXTVAL,
				'$this->cod_concepto',
				'$this->tarifa',
				'$this->cod_inmueble',
				nvl((select UNIDADES_TOT FROM SGC_TT_SERVICIOS_INMUEBLES WHERE COD_INMUEBLE='$this->cod_inmueble' and (cod_servicio=1 or cod_servicio=3 )),(SELECT TOTAL_UNIDADES FROM SGC_TT_INMUEBLES WHERE CODIGO_INM='$this->cod_inmueble')),
				nvl((select UNIDADES_HAB FROM SGC_TT_SERVICIOS_INMUEBLES WHERE COD_INMUEBLE='$this->cod_inmueble' and (cod_servicio=1 or cod_servicio=3 )),(SELECT UNIDADES_HAB FROM SGC_TT_INMUEBLES WHERE CODIGO_INM='$this->cod_inmueble')),
				nvl((select UNIDADES_DESH FROM SGC_TT_SERVICIOS_INMUEBLES WHERE COD_INMUEBLE='$this->cod_inmueble' and (cod_servicio=1 or cod_servicio=3)),(SELECT UNIDADES_DES FROM SGC_TT_INMUEBLES WHERE CODIGO_INM='$this->cod_inmueble')),
				nvl((select CUPO_BASICO FROM SGC_TT_SERVICIOS_INMUEBLES WHERE COD_INMUEBLE='$this->cod_inmueble' and (cod_servicio=1 or cod_servicio=3 )),(SELECT NVL(CONSUMO_MIN,0) FROM SGC_TP_TARIFAS WHERE CONSEC_TARIFA='$this->tarifa')),
				nvl((select PROMEDIO FROM SGC_TT_SERVICIOS_INMUEBLES WHERE COD_INMUEBLE='$this->cod_inmueble' and (cod_servicio=1 or cod_servicio=3 )),(SELECT NVL(CONSUMO_MIN,0) FROM SGC_TP_TARIFAS WHERE CONSEC_TARIFA='$this->tarifa')),
				nvl(
					(select CONSUMO_MINIMO FROM SGC_TT_SERVICIOS_INMUEBLES WHERE COD_INMUEBLE='$this->cod_inmueble' and (cod_servicio=1 or cod_servicio=3 )),
					((SELECT NVL(CONSUMO_MIN,0) FROM SGC_TP_TARIFAS WHERE CONSEC_TARIFA='$this->tarifa')*
						(SELECT UNIDADES_HAB FROM SGC_TT_INMUEBLES WHERE CODIGO_INM='$this->cod_inmueble'))),
				'0','M','S','$this->usr_creacion','')";*/

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
	
	
	
	public function Especifico(){
		$resultado = oci_parse($this->_db,"SELECT CON.COD_SERVICIO, CON.ACTIVO, CON.UNIDADES_TOT, CON.UNIDADES_HAB,
                CON.CUPO_BASICO,CON.PROMEDIO,CON.CONSUMO_MINIMO,CON.CONSEC_TARIFA,AC.ID_USO, AC.SEC_ACTIVIDAD
                FROM SGC_TT_SERVICIOS_INMUEBLES CON ,SGC_TT_INMUEBLES INM,SGC_TP_ACTIVIDADES AC
                WHERE CON.COD_INMUEBLE='$this->cod_inmueble' AND CON.COD_SERVICIO='$this->cod_concepto' AND INM.CODIGO_INM=CON.COD_INMUEBLE
                AND INM.SEC_ACTIVIDAD=AC.SEC_ACTIVIDAD
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
	
	
	
	
	
	
	public function Todos ($where,$sort,$start,$end,$inmueble){
		$resultado = oci_parse($this->_db,"SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM
				(SELECT CON.COD_SERVICIO, CON2.DESC_SERVICIO, CON.ACTIVO, CON.UNIDADES_TOT, CON.UNIDADES_HAB,
				con.CONSEC_SERVICIO_INM,
				CON.CUPO_BASICO,CON.PROMEDIO,CON.CONSUMO_MINIMO, TAR.DESC_TARIFA, TAR.COD_USO
				FROM SGC_TT_SERVICIOS_INMUEBLES CON , SGC_TP_SERVICIOS CON2, SGC_TP_TARIFAS TAR, SGC_TT_INMUEBLES INM, SGC_TP_ACTIVIDADES AC
				WHERE CON.COD_INMUEBLE='$inmueble' AND CON.COD_SERVICIO=CON2.COD_SERVICIO 
				AND TAR.CONSEC_TARIFA(+)=CON.CONSEC_TARIFA AND INM.CODIGO_INM=CON.COD_INMUEBLE AND INM.SEC_ACTIVIDAD=AC.SEC_ACTIVIDAD(+)  $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ");
			
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
	
	public function CantidadRegistros ($fname,$tname,$where,$sort){
	
		$resultado = oci_parse($this->_db,"SELECT count($fname) CANTIDAD FROM $tname WHERE CON.COD_SERVICIO=CON2.COD_SERVICIO  $where $sort");
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
	
	
	public function Eliminar_Concepto($inmueble){
		$resultado = oci_parse($this->_db,"DELETE FROM SGC_TT_SERVICIOS_INMUEBLES WHERE COD_INMUEBLE='$inmueble' AND COD_SERVICIO IN ($this->cod_concepto)");
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
	
	
	public function Cambiarestado($servicio){
		$resultado=oci_parse($this->_db,"BEGIN SGC_P_CAMBIO_EST_SERV('$servicio','$this->usr_creacion','$this->cod_inmueble');COMMIT;END;");
		
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
	
	
	public function actualizar_servicio(){
		
		$resultado=oci_parse($this->_db,"BEGIN SGC_P_ACT_SERV('$this->cod_concepto','$this->cod_concepto_v','$this->tarifa','$this->unid_total','$this->unid_hab','$this->cupo_bas','$this->usr_creacion', '$this->cod_inmueble',$this->actividad, :PCODRESULT, :PMSGRESULT);COMMIT;END;");
		
		oci_bind_by_name($resultado,':PCODRESULT',$this->codError,"123");
		oci_bind_by_name($resultado,':PMSGRESULT',$this->msError,"123");
		
		//echo "BEGIN SGC_P_ACT_SERV('$this->cod_concepto','$this->cod_concepto_v','$this->tarifa','$this->unid_total','$this->unid_hab','$this->cupo_bas','$this->usr_creacion', '$this->cod_inmueble',$this->actividad, :PCODRESULT, :PMSGRESULT);COMMIT;END;";
		//echo "BEGIN ACEA.PROC_ENTRADA_CORTE($this->idinmueble,$this->orden,'$this->tipocorte', '$this->imposibilidad', 17,$this->usrviejo,'$this->fechalect','$this->obsgen',0,'',:PCODRESULT, :PMSGRESULT );COMMIT;END;";
		$bandera=oci_execute($resultado);
		if($bandera){
			if($this->codError==0){
				oci_close($this->_db);
				return true;
			}else{
				oci_close($this->_db);
				return false;
			}
		}else{
			echo "Error al actualizar";
			oci_close($this->_db);
			return false;
		}
		
	}
	
	
	
	
	public function obtenerdesc ($cod){
		
		$sql="SELECT SER.DESC_SERVICIO FROM SGC_TP_SERVICIOS SER
				WHERE SER.COD_SERVICIO=$cod";
		$resultado = oci_parse($this->_db,$sql);
	
		$banderas=oci_execute($resultado);
		oci_fetch($resultado);
		$descripcion = oci_result($resultado, 'DESC_SERVICIO');
		if($banderas==TRUE)
		{
			return $descripcion;
		}
		else
		{
	
			echo "false";
			return false;
		}
	
	}

    public function obtenerSaldo($cod){
        $sql="SELECT SUM(NVL(SALDO,0))SALDO  FROM(
        SELECT SUM(O.IMPORTE-O.APLICADO)SALDO
        FROM SGC_TT_OTROS_RECAUDOS O
        WHERE ESTADO = 'A'
        AND O.CONCEPTO IN (1,2,3,4)
        AND O.INMUEBLE = '$cod'
        union
        SELECT SUM(IMPORTE-VALOR_APLICADO) SALDO
        FROM SGC_TT_SALDO_FAVOR F
        WHERE ESTADO = 'A'
        AND INM_CODIGO = '$cod')";
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