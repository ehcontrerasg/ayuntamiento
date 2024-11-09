<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

class Contrato extends ConexionClass{
	Private $id_contrato;
	Private $cod_inm;
	Private $fecha_inicio;
	Private $fecha_fin;
	Private $codigo_cli;
	Private $fecha_creacion;
	Private $usuario_creacion;
	Private $fecha_modificacion;
	Private $usuario_modifiacion;
	Private $cont_anterior;
	Private $alias;
	Private $fecsol;
	Private $mserror;
	Private $coderror;

	
	public function __construct()
	{
		parent::__construct();
		$this->id_contrato="";
		$this->cod_inm="";
		$this->fecha_inicio="";
		$this->fecha_fin="";
		$this->codigo_cli="";
		$this->fecha_creacion="";
		$this->fecha_modificacion="";
		$this->usuario_modifiacion="";
		$this->cont_anterior="";
		$this->alias="";
		$this->fecsol="";
		$this->mserror="";
		$this->coderror="";

	}


    public function getMsError(){
        return $this->mserror;
    }

    public function getCodRes(){
        return $this->coderror;
    }

	public function setid_contrato($valor){
		$this->id_contrato=$valor;
	}

	public function setcod_inm($valor){
		$this->cod_inm=$valor;
	}

	public function setfechaini($valor){
		$this->fecha_inicio=$valor;
	}

	public function setfechafin($valor){
		$this->fecha_fin=$valor;
	}

	public function setcodigocli($valor){
		$this->codigo_cli=$valor;
	}
	
	public function setfecha_creacion($valor){
		$this->fecha_creacion=$valor;
	}
	
	public function setusuario_creacion($valor){
		$this->usuario_creacion=$valor;
	}

	public function setfecha_mod($valor){
		$this->fecha_modificacion=$valor;
	}

	public function setusuario_mod($valor){
		$this->usuario_modifiacion=$valor;
	}

	public function setalias($valor){
		$this->alias=$valor;
	}
	
	public function setfecsol($valor){
		$this->fecsol=$valor;
	}
	
	public function NuevoContrato(){
		
		
		if($this->alias==''){
			$sql="INSERT INTO SGC_TT_CONTRATOS(ID_CONTRATO,CODIGO_INM,FECHA_INICIO,CODIGO_CLI,USUARIO_CREACION,FECHA_SOLICITUD) VALUES(CONCAT('$this->id_contrato',SQ_CONTRATOS.NEXTVAL),
					'$this->cod_inm',TO_DATE('$this->fecha_inicio','yyyy/mm/dd hh24:mi:ss'),'$this->codigo_cli','$this->usuario_creacion',TO_DATE('$this->fecsol','YYYY-MM-DD'))";
			$resultado = oci_parse($this->_db,$sql);
			//echo $sql;
		}else{
				$sql="INSERT INTO SGC_TT_CONTRATOS
                      (ID_CONTRATO,CODIGO_INM,FECHA_INICIO,CODIGO_CLI,USUARIO_CREACION,ALIAS,FECHA_SOLICITUD)
                        VALUES(CONCAT('$this->id_contrato',SQ_CONTRATOS.NEXTVAL),
					'$this->cod_inm',TO_DATE('$this->fecha_inicio','yyyy/mm/dd hh24:mi:ss'),'$this->codigo_cli',
					'$this->usuario_creacion','$this->alias',TO_DATE('$this->fecsol','YYYY-MM-DD'))";
			//echo $sql;
			$resultado = oci_parse($this->_db,$sql);
			
		}
 		
 		
 		$banderas=oci_execute($resultado);
 		if($banderas==TRUE)
 		{
 			oci_close($this->_db);
 			return $banderas;
 		}
 		else
 		{
 			oci_close($this->_db);
 			echo "false";
 			return false;
 		}

	}
	
	
	public function CambioContrato(){
		
		
		
		if($this->alias==''){

                echo $sql="INSERT INTO SGC_TT_CONTRATOS (ID_CONTRATO,CODIGO_INM,FECHA_INICIO,CODIGO_CLI,USUARIO_CREACION,FECHA_SOLICITUD,CONTRATO_NUEVO)
                  VALUES('$this->id_contrato',
				'$this->cod_inm',SYSDATE,'$this->codigo_cli',
				'$this->usuario_creacion',TO_DATE('$this->fecsol','yyyy-mm-dd'),'N')";
				$resultado = oci_parse($this->_db,$sql);

		}else{
				$resultado = oci_parse($this->_db,"INSERT INTO SGC_TT_CONTRATOS
                          (ID_CONTRATO,CODIGO_INM,FECHA_INICIO,CODIGO_CLI,USUARIO_CREACION,ALIAS,FECHA_SOLICITUD,CONTRATO_NUEVO)
                VALUES('$this->id_contrato',
				'$this->cod_inm',SYSDATE,'$this->codigo_cli',
				'$this->usuario_creacion','$this->alias',TO_DATE('$this->fecsol','yyyy-mm-dd'),'N')");
			
		}
		
	
	
		$banderas=oci_execute($resultado);
		if($banderas==TRUE)
		{
			oci_close($this->_db);
			return $banderas;
		}
            else
                {
			oci_close($this->_db);
			echo "false";
			return false;
		}
	
	}
	
	
	public function ActualizaAlias(){
	
		$resultado = oci_parse($this->_db,"UPDATE SGC_TT_CONTRATOS SET ALIAS='$this->alias',FECHA_MODIFICACION=SYSDATE,USUARIO_MODIFICACION='$this->usuario_modifiacion' where ID_CONTRATO='$this->id_contrato' and FECHA_FIN IS NULL ");
			

		$banderas=oci_execute($resultado);
		if($banderas==TRUE)
		{
		oci_close($this->_db);
		return $banderas;
		}
		else
		{
		oci_close($this->_db);
		echo "false";
		return false;
		}
	
		}
	
	public function ObtenerCodcontrato(){
	
		$resultado = oci_parse($this->_db,"SELECT ID_CONTRATO FROM SGC_TT_CONTRATOS WHERE CODIGO_INM='$this->cod_inm' and CODIGO_CLI='$this->codigo_cli'");

			
			
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
	
	
	public function ObtenerDatcontrato(){
		 $sql="SELECT * FROM SGC_TT_CONTRATOS WHERE ID_CONTRATO='$this->id_contrato'
				AND FECHA_FIN IS NULL";
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
	
	public function Todos ($where,$sort,$start,$end){
		$resultado = oci_parse($this->_db,"SELECT * FROM ( SELECT /*+ FIRST_ROWS(n) */ a.*, rownum rnum FROM 
				(SELECT CON.ID_CONTRATO, CON.CODIGO_INM,TO_CHAR(CON.FECHA_INICIO,'yyyy/mm/dd') FECHA_INICIO,
				TO_CHAR(CON.FECHA_FIN,'yyyy/mm/dd') FECHA_FIN, CON.CODIGO_CLI , CON.ALIAS NOMBRE_CLI,
				CLI.NOMBRE_CLI NOMBRE ,CLI.DOCUMENTO
				FROM SGC_TT_CONTRATOS CON , SGC_TT_CLIENTES CLI
				WHERE CON.CODIGO_CLI=CLI.CODIGO_CLI $where $sort)a WHERE rownum <= $start ) WHERE rnum >= $end+1 ");
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
	
		$resultado = oci_parse($this->_db,"SELECT count($fname) CANTIDAD FROM $tname WHERE CON.CODIGO_CLI= CLI.CODIGO_CLI $where $sort");
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
	
	public function existe(){
	$resultado = oci_parse($this->_db,"SELECT COUNT(CON.ID_CONTRATO) NUMERO, ID_CONTRATO FROM SGC_TT_CONTRATOS CON WHERE CON.CODIGO_INM='$this->cod_inm'
            and CON.FECHA_INICIO IS NOT NULL AND CON.FECHA_FIN IS NULL GROUP BY(CON.ID_CONTRATO)  ");
		$banderas=oci_execute($resultado);
		if($banderas==TRUE)
		{
			oci_fetch($resultado);
			$numcontratos=oci_result($resultado,"NUMERO");
			oci_close($this->_db);
			return $numcontratos;
		}
		else
		{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}
	
	
	public function Eliminar_Contrato(){
		$resultado = oci_parse($this->_db,"DELETE FROM SGC_TT_CONTRATOS WHERE ID_CONTRATO IN ($this->id_contrato)");
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
	
	public function inmueble_sin_contrato(){
		$resultado = oci_parse($this->_db,"SELECT  I.CODIGO_INM 
 		FROM  SGC_TT_INMUEBLES I, SGC_TP_ESTADOS_INMUEBLES EI
		WHERE
 		I.CODIGO_INM NOT IN ( SELECT C.CODIGO_INM FROM SGC_TT_CONTRATOS C WHERE C.CODIGO_INM=I.CODIGO_INM AND C.FECHA_FIN IS  NULL )
				AND I.ID_ESTADO= EI.ID_ESTADO
				AND EI.INDICADOR_ESTADO='A'
				ORDER BY ABS(CODIGO_INM) DESC");
		ECHO
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
	
/*
	public function Cancelar_Contrato(){
		$resultado = oci_parse($this->_db,"UPDATE SGC_TT_CONTRATOS SET FECHA_FIN=TO_DATE(SYSDATE,'dd/mm/yy hh24:mi:ss'),
				FECHA_MODIFICACION=TO_DATE(SYSDATE,'dd/mm/yy hh24:mi:ss'),USUARIO_MODIFICACION='$this->usuario_modifiacion' WHERE ID_CONTRATO ='$this->id_contrato'
				AND FECHA_FIN is NULL
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
	}*/


    public function Cancelar_Contrato(){

        $sql="BEGIN SGC_P_CANCELA_CONTRATO('$this->id_contrato','$this->usuario_modifiacion',:PCODRESULT,:PMSGRESULT);COMMIT;END;";


        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->mserror,"123");
        oci_bind_by_name($resultado,":PCODRESULT",$this->coderror,"123");
        $bandera=oci_execute($resultado);

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
	

	
	
	
	
	

	
	
		
}
