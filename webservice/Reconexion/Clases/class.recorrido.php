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

	


	
	public function obtenerrecorrido(){
		$sql=" select
            RR.ORDEN ORDEN,
            RR.ID_INMUEBLE COD_SISTEMA,
            RR.FECHA_ACUERDO FECHA,
            TO_CHAR(RR.FECHA_ACUERDO,'YYYYMM') PERIODO,
            C.ALIAS,
            I.DIRECCION,
            I.ID_PROCESO PROCESO,
            I.CATASTRO,
            I.TELEFONO,
            RC.TIPO_CORTE TIPOCORTE,
            MI.COD_MEDIDOR MEDIDOR,
            CAL.DESC_CALIBRE,
            MI.SERIAL SERIAL,
            MI.COD_EMPLAZAMIENTO EMPLAZAMIENTO,
            trunc(MI.FECHA_INSTALACION) FEC_INSTALL,
            I.ID_PROCESO,
            RC.OBS_GENERALES OBSREA

           from sgc_tt_registro_reconexion rr, sgc_tt_contratos c, sgc_tt_inmuebles i, sgc_tt_registro_cortes rc,
           sgc_tt_medidor_inmueble mi, sgc_tp_calibres cal
           where
           RR.ORDEN=RC.ORDEN(+) and
           I.CODIGO_INM=RR.ID_INMUEBLE and
           I.CODIGO_INM=MI.COD_INMUEBLE(+) and
           CAL.COD_CALIBRE(+)=MI.COD_CALIBRE AND
           C.CODIGO_INM(+)=I.CODIGO_INM
           and RR.FECHA_EJE is null
           AND MI.FECHA_BAJA (+) IS NULL  
           and RR.USR_EJE='$this->operario'
           AND C.FECHA_FIN (+) IS NULL";


		
		$resultado=oci_parse($this->_db,$sql);
				
		//echo $sql; 
		$bandera=oci_execute($resultado);
		if($bandera=TRUE){
			oci_close($this->_db);
			return $resultado;
		}else{
			oci_close($this->_db);
			echo "error al obtener la ruta de reconexion";
			return false;
		}
	}


    /**
     * @return bool|resource
     */
    public function obtenerutas(){


        $sql="select COUNT(1) CANTIDAD
        from SGC_TT_REGISTRO_RECONEXION RR

           WHERE RR.FECHA_EJE IS NULL
           AND RR.USR_EJE='$this->operario'";
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