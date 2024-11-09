<?php
include_once 'class.conexion.php';
class observacion extends ConexionClass{
	private $fecha;
	private $descripcion;
	private $id_operario;
	
	public function __construct(){
		parent::__construct();
		$this->fecha="";	
	}
	
	public function setfecha($valor)		{$this->fecha=$valor;}
	public function setdescripcion($valor)	{$this->descripcion=$valor;}
	public function setoperario($valor)		{$this->id_operario=$valor;}
	
	public function obtenertipocorte(){
		$resultado=oci_parse($this->_db,"SELECT CODIGO, DESCRIPCION
				FROM SGC_TP_TIPO_CORTE ");
		$bandera=oci_execute($resultado);
		if($bandera=TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "Error al obtener llos tipos de corte";
			return false;
		}
		
	}
	
	
	public function obtenerobscorte(){

		$sql="SELECT CODIGO, DESCRIPCION, TIPO
				FROM SGC_TP_OBS_CORTE  WHERE
				TIPO='C' AND VISIBLE='S'";
		$resultado=oci_parse($this->_db,$sql);

		$bandera=oci_execute($resultado);
		if($bandera){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "Error al obtener llos tipos de corte";
			return false;
		}
	
	}
	

	
	public function obtenermateriales(){
		$resultado=oci_parse($this->_db,"SELECT MPC.COD_CORTE, MPC.ID_MATERIAL, MA.DESCRIPCION,  UM.DESCRIPCION UNIDAD FROM SGC_TP_MATERIAL_X_CORTE MPC, 
				SGC_TP_UNIDADES_MEDIDA UM, SGC_TP_MATERIALES MA
				WHERE 
				MPC.ID_MATERIAL= MA.ID_MATERIAL
				AND UM.CODIGO=MA.UNIDAD_MEDIDA ");
	
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "Error al obtener llos tipos de corte";
			return false;
		}
	
	}
	
	public function obtenerfacturas(){
		$resultado=oci_parse($this->_db,"SELECT 
                /*+ FIRST_ROWS */ 
                DISTINCT
    F.INM_CODIGO,
    F.TOTAL,
    F.PFC_CODIGO,
    F.CONSE
    --usv.cod_sgc_sistema_nuevo
  FROM FACTURAS@LK_ACEA1 F WHERE PAGADO IS NULL
  and F.IND_DEUDA_CERO is null
  AND F.FACT_SUST IS NULL
  AND F.TOTAL<>0
  AND F.FECVTO IS NOT NULL
  
    AND F.INM_CODIGO IN (
        SELECT
         /*+ FIRST_ROWS */ 
         I.CODIGO_INM COD_INM
            From OrdCorte@LK_ACEA1 O, sgc_tt_inmuebles I,sgc_tt_temp_usr usv
            Where 
                o.inm_codigo=i.codigo_inm
                And FecRea Is Null  
                and Epl_Codigo_Procede=(SELECT COD_SISTEMA_VIEJO FROM SGC_TT_TEMP_USR WHERE COD_SGC_SISTEMA_NUEVO='$this->id_operario')
                and USV.COD_SGC_SISTEMA_NUEVO='$this->id_operario'   
         )");
	
				$bandera=oci_execute($resultado);
				if($bandera=TRUE){
				oci_close($this->_db);
				return $resultado;
				}else{
				oci_close($this->_db);
				echo "Error al obtener las facturas";
				return false;
				}
	
		}
}