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
		$this->tiporuta="2";
		
	}
	public function setzona		($valor){$this->zona=$valor;}
	public function setoperario	($valor){$this->operario=$valor;}
	public function settiporuta	($valor){$this->tiporuta=$valor;}

	


	
	public function obtenerrecorrido(){
		$sql="
	      	SELECT 
               INM.DIRECCION DIRECCION, 
               INM.CODIGO_INM CODIGO_INM, 
               INM.CATASTRO CATASTRO,   
               INM.ID_PROCESO PROCESO,
               INM.ID_TIPO_CLIENTE TIPO_CLIENTE,
               NVL(CON.ALIAS,CLI.NOMBRE_CLI ) NOMBRE,
               RL.PERIODO ID_PERIODO,
               EMP.DESC_EMPLAZAMIENTO EMPLAZAMIENTO,
               MED.DESC_MED MEDIDOR,  
               CAL.DESC_CALIBRE CALIBRE, 
               MI.SERIAL MEDSERIAL, 
               INM.ID_ZONA ZON_CODIGO, 
               CON.ID_CONTRATO,
               (SELECT RC.CONSUMO_MINIMO FROM SGC_TT_RANGOS_CONSUMOS RC WHERE RC.PERIODO=RL.PERIODO AND RC.COD_INM=INM.CODIGO_INM) CONSUMO_MINIMO,
               (SELECT RC.CONSUMO_MAXIMO FROM SGC_TT_RANGOS_CONSUMOS RC WHERE RC.PERIODO=RL.PERIODO AND RC.COD_INM=INM.CODIGO_INM) CONSUMO_MAXIMO
               
              
            
            FROM SGC_TT_REGISTRO_LECTURAS RL, 
                SGC_TT_MEDIDOR_INMUEBLE MI,  
                SGC_Tt_INMUEBLES INM,
                SGC_TT_CONTRATOS CON,
                SGC_TP_EMPLAZAMIENTO EMP,
                SGC_TP_MEDIDORES MED,
                SGC_TP_CALIBRES CAL,
                SGC_TP_ESTADOS_MEDIDOR EM,
                SGC_TT_CLIENTES CLI
            
                WHERE
                RL.COD_INMUEBLE=INM.CODIGO_INM
                AND MI.FECHA_BAJA (+) IS NULL  
                AND MI.COD_INMUEBLE=INM.CODIGO_INM
                AND CON.CODIGO_INM(+)=INM.CODIGO_INM
                AND CON.FECHA_FIN (+) IS  NULL
                AND CLI.CODIGO_CLI(+)=CON.CODIGO_CLI
                AND EMP.COD_EMPLAZAMIENTO=MI.COD_EMPLAZAMIENTO
                AND MED.CODIGO_MED=MI.COD_MEDIDOR
                  
                AND CAL.COD_CALIBRE=MI.COD_CALIBRE
                AND EM.CODIGO=MI.ESTADO_MEDIDOR
                AND RL.COD_LECTOR='$this->operario'
                AND RL.FECHA_LECTURA IS NULL";
		
		$resultado=oci_parse($this->_db,$sql);
				
		//echo $sql; 
		$bandera=oci_execute($resultado);
		if($bandera=TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "error al obtener la ruta de lectura";
			return false;
		}
	
	
	}
	
	
	
	public function actualizarasig(){
		$resultado=oci_parse($this->_db, "UPDATE SGC_TT_ASIGNACION SET FECHA_FIN='' CONCAT(ID_SECTOR, ID_RUTA) RUTAS FROM SGC_TT_ASIGNACION WHERE ID_TIPO_RUTA='$this->tiporuta'
				AND ID_OPERARIO='$this->operario' AND FECHA_FIN IS NULL GROUP BY(ID_SECTOR,ID_RUTA)");
		
		$bandera=oci_execute($resultado);
		if($bandera=TRUE){
			oci_close($this->_db);
			return  $resultado;
		}else{
			echo "error al obtener las rutas";
			oci_close($this->_db);
			return false;
		}
		
	}
		

	
	
	public function obtenerutas(){
         $sql="SELECT COUNT (1) CANTIDAD
            FROM SGC_TT_REGISTRO_LECTURAS RL
                WHERE
                 RL.COD_LECTOR='$this->operario'
                AND RL.FECHA_LECTURA IS NULL";

		$resultado=oci_parse($this->_db,$sql);
		

	
		$bandera=oci_execute($resultado);
		if($bandera=TRUE){
			oci_close($this->_db);
			return  $resultado;
		}else{
			echo "error al obtener las rutas";
			oci_close($this->_db);
			return false;
		}
	}
	
	
	
	
}