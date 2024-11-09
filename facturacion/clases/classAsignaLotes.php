<?php
include_once "../../clases/class.conexion.php";
class AsignaLotes extends ConexionClass{
	private $coduser;
	private $operario_asignado;
	private $operario_desasignado;
	private $zona;
	private $ruta;
	private $periodo;
    private $codresult;
    private $msgresult;
	
	public function __construct(){
		parent::__construct();
		$this->coduser="";
		$this->operario_asignado="";
		$this->operario_desasignado="";
		$this->zona="";
		$this->ruta="";
		$this->periodo="";
	}
	
	public function getcodresult(){
    	return $this->codresult;
    }
	
    public function getmsgresult(){
    	return $this->msgresult;
    }
	
	
	 public function seleccionaProyecto($coduser){
		$sql = "SELECT PR.ID_PROYECTO, PR.SIGLA_PROYECTO 
		FROM SGC_TP_PROYECTOS PR, SGC_TT_PERMISOS_USUARIO PU
		WHERE PR.ID_PROYECTO = PU.ID_PROYECTO AND PU.ID_USUARIO = '$coduser' ORDER BY 2";
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
	
	public function seleccionaPeriodo($proyecto){
		$sql = "SELECT Z.PERIODO PERIODO
        FROM SGC_TT_REGISTRO_LECTURAS L, SGC_TP_PERIODO_ZONA Z
        WHERE L.ID_ZONA = Z.ID_ZONA AND FECHA_LECTURA IS NULL AND Z.CODIGO_PROYECTO = '$proyecto'
        AND Z.FEC_DIFE IS NULL
        GROUP BY Z.PERIODO ORDER BY Z.PERIODO DESC";
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
	
	public function seleccionaZona($periodo, $proyecto){
		$sql = "SELECT L.ID_ZONA
        FROM SGC_TT_REGISTRO_LECTURAS L, SGC_TP_PERIODO_ZONA Z
        WHERE L.ID_ZONA = Z.ID_ZONA AND Z.PERIODO = '$periodo' AND Z.CODIGO_PROYECTO = '$proyecto'
        AND Z.FEC_DIFE IS NULL
        AND L.FECHA_LECTURA IS NULL
        GROUP BY L.ID_ZONA ORDER BY 1 ASC";
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
	
	public function seleccionaOperario($proyecto){
		$sql = "SELECT ID_USUARIO, NOM_USR, APE_USR
		FROM SGC_TT_USUARIOS
		WHERE LECTURA = 'S' 
        AND ID_PROYECTO = '$proyecto'
        AND FEC_FIN IS NULL";
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
	
	public function seleccionaAsignacion($periodo, $zona){
		$sql = "SELECT COUNT(COD_INMUEBLE)CANTIDAD, RUTA
		FROM SGC_TT_REGISTRO_LECTURAS
		WHERE PERIODO = '$periodo' AND ID_ZONA = '$zona' AND COD_LECTOR IS NULL
		GROUP BY RUTA
		ORDER BY RUTA ASC";
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
	
	
	public function seleccionaDesasignacion($periodo, $zona){
		$sql = "SELECT COUNT(COD_INMUEBLE)CANTIDAD, RUTA, ID_USUARIO, NOM_USR, APE_USR
        FROM SGC_TT_REGISTRO_LECTURAS L, SGC_TT_USUARIOS U
        WHERE U.ID_USUARIO = L.COD_LECTOR AND ID_ZONA='$zona' AND PERIODO = '$periodo'
        AND COD_LECTOR IS NOT NULL AND FECHA_LECTURA IS NULL
        GROUP BY  RUTA, ID_USUARIO, NOM_USR, APE_USR ORDER BY RUTA";
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
	
	
    public function AsignaRuta($coduser,$operario_asignado,$zona,$ruta,$periodo,$fechaPla){
       	$sql="BEGIN SGC_P_ASIGNA_LECTURAS('$coduser','$operario_asignado','$zona','$ruta',$periodo,'$fechaPla',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
		$resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,"123");
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult,"123");
        $bandera=oci_execute($resultado);
		
		if($bandera){
	        if($this->codresult==0){
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
	
	public function DesasignaRuta($zona,$desasignar_loc,$periodo){
       	$sql="BEGIN SGC_P_DESASIGNA_LECTURAS('$zona','$desasignar_loc',$periodo,:PMSGRESULT,:PCODRESULT);COMMIT;END;";
		//echo $sql;
		$resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,"123");
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult,"123");
        $bandera=oci_execute($resultado);
		
		if($bandera){
	        if($this->codresult==0){
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