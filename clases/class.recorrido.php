<?php
include_once 'class.conexion.php';

class recorrido extends ConexionClass{
	private $zona;
	private $operario;
	private $tiporuta;
	private $idgrupo;
	
	public function __construct(){
		parent::__construct();
		$this->zona="";
		$this->operario="";
		$this->tiporuta="3";
		$this->idgrupo="";
		
	}
	public function setzona		($valor){$this->zona=$valor;}
	public function setoperario	($valor){$this->operario=$valor;}
	public function settiporuta	($valor){$this->tiporuta=$valor;}
	public function setgrupo	($valor){$this->idgrupo=$valor;}

	


	
	public function obtenerrecorrido(){
		if( trim($this->idgrupo)!=""){
			$this->operario='';
		}
		$sql="SELECT distinct
    		/*+ FIRST_ROWS */
            I.CODIGO_INM COD_INM,
            RC.ORDEN ORDEN,
            RC.DESCRIPCION DESCRIPCION_CORTE ,
            I.ID_PROCESO PROCESO,
            I.CATASTRO CATASTRO,
            I.DIRECCION DIRECCION,
            CON.ALIAS NOMBRE,
            EM.DESC_EMPLAZAMIENTO EMPLAZAMIENTO,
            MED.DESC_MED MEDIDOR,
            AC.DESC_ACTIVIDAD ACTIVIDAD,
            C.DESC_CALIBRE CALIBRE,
            MI.SERIAL SERIAL,
            RC.ID_PERIODO PERIODO,
            TAR.VALOR_TARIFA
         	From
              	sgc_tt_registro_cortes rc,
               	sgc_tt_inmuebles I,
              	sgc_tp_calibres C,
               	SGC_TT_CONTRATOS CON,
              	SGC_TT_CLIENTES CLI,
               	sgc_tp_actividades ac,
              	sgc_tt_medidor_inmueble mi,
              	SGC_TP_EMPLAZAMIENTO EM,
              	SGC_TP_MEDIDORES MED,
               	
               	SGC_TP_TARIFAS_RECONEXION TAR,
               	SGC_TP_USOS USO,
               	SGC_TP_GRUPOS_TRABAJO GRT
            Where
            	RC.ID_INMUEBLE=i.codigo_inm
               	and C.COD_CALIBRE(+)=MI.COD_CALIBRE
               	and AC.SEC_ACTIVIDAD= I.SEC_ACTIVIDAD
              	AND EM.COD_EMPLAZAMIENTO(+)=MI.COD_EMPLAZAMIENTO
             	AND CON.CODIGO_INM = I.CODIGO_INM
              	and CLI.CODIGO_CLI= CON.CODIGO_CLI
            	AND MED.CODIGO_MED(+) = MI.COD_MEDIDOR
           		AND TAR.CODIGO_USO=USO.ID_USO
            	AND AC.ID_USO =USO.ID_USO
           		AND TAR.CODIGO_CALIBRE= NVL(MI.COD_CALIBRE,0)
            	AND TAR.PROYECTO=I.ID_PROYECTO
         		AND	TAR.CODIGO_DIAMETRO   =I.COD_DIAMETRO
          		AND TAR.MEDIDOR=NVL(MED.ESTADO_MED,'N')
            	and MI.COD_INMUEBLE(+)=I.CODIGO_INM
            	AND RC.USR_EJE='$this->operario'
            	and RC.FECHA_EJE is null
				AND RC.FECHA_ACUERDO IS NULL
				AND RC.REVERSADO='N'
				AND RC.PERVENC='N'";

		$resultado=oci_parse($this->_db,$sql);
				
		//echo $sql; 
		$bandera=oci_execute($resultado);
		if($bandera=TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "error al obtener la ruta de corte";
			return false;
		}
	
	
	}
	
	
	
	
	
	public function obtenerutas(){
		$resultado=oci_parse($this->_db, "Select count(*) CANTIDAD           
               From sgc_Tt_registro_cortes rc
               where RC.USR_EJE ='$this->operario'
               and RC.FECHA_EJE is null");
		
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