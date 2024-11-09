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
		$this->tiporuta="3";
		
	}
	public function setzona		($valor){$this->zona=$valor;}
	public function setoperario	($valor){$this->operario=$valor;}
	public function settiporuta	($valor){$this->tiporuta=$valor;}

	


	
	public function obtenerrecorrido(){
		   $sql="SELECT EF.COD_INMUEBLE COD_INMUEBLE,I.CATASTRO CATASTRO, I.ID_PROCESO PROCESO,
			I.DIRECCION DIRECCION,NVL(C.ALIAS,CLI.NOMBRE_CLI) NOMBRECLIENTE, EF.PERIODO PERIODO
			  FROM SGC_TT_REGISTRO_ENTREGA_FAC EF, SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CLI
			  WHERE
			  EF.COD_INMUEBLE=I.CODIGO_INM
			  AND I.CODIGO_INM=C.CODIGO_INM(+)
			  AND CLI.CODIGO_CLI(+)=C.CODIGO_CLI
			  AND EF.USR_EJE='$this->operario'
			  AND EF.FECHA_EJECUCION IS NULL
			  AND EF.ENTREGADO='N'
			  AND EF.ANULADO='N'";
		
		$resultado=oci_parse($this->_db,$sql);
				
		//echo $sql; 
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "error al obtener la ruta de lectura";
			return false;
		}
	
	
	}


    public function obtenerrecorridoSup(){
        $sql="SELECT EF.COD_INMUEBLE COD_INMUEBLE,I.CATASTRO CATASTRO, I.ID_PROCESO PROCESO,
			I.DIRECCION DIRECCION,NVL(C.ALIAS,CLI.NOMBRE_CLI) NOMBRECLIENTE, EF.PERIODO PERIODO
			  FROM SGC_TT_SUPERVISION_ENTREGA_FAC EF, SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CLI
			  WHERE
			  EF.COD_INMUEBLE=I.CODIGO_INM
			  AND I.CODIGO_INM=C.CODIGO_INM(+)
			  AND CLI.CODIGO_CLI(+)=C.CODIGO_CLI
			  AND EF.USR_EJE='$this->operario'
			  AND EF.FECHA_EJECUCION IS NULL
			  AND EF.ENTREGADO='N'
			  AND EF.ANULADO='N'";

        $resultado=oci_parse($this->_db,$sql);

        //echo $sql;
        $bandera=oci_execute($resultado);
        if($bandera==TRUE){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($this->_db);
            echo "error al obtener la ruta de lectura";
            return false;
        }


    }
	
	
	
	
	
	public function obtenerutas(){
	
	 $sql="select 
               COUNT(1) CANTIDAD
		  FROM SGC_TT_REGISTRO_ENTREGA_FAC EF
		  WHERE
		  
		   EF.USR_EJE='$this->operario'
		  AND EF.FECHA_EJECUCION IS NULL";
		$resultado=oci_parse($this->_db,$sql);
		
		$bandera=oci_execute($resultado);
		if($bandera==TRUE){
			oci_close($this->_db);
			return  $resultado;
		}else{
			echo "error al obtener las rutas";
			oci_close($this->_db);
			return false;
		}
	}
	
	
	
	
}