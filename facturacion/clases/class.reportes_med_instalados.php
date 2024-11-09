<?php

include_once '../../clases/class.conexion.php';

class Reportes_Med_Instalados extends ConexionClass{
	

		
	public function __construct()
	{
		parent::__construct();

	}
	
	public function obtieneCantidadMedidores($proyecto)
	{
		$sql="SELECT G.ID_GERENCIA, COUNT(I.CODIGO_INM)CANTIDAD
			FROM SGC_TT_INMUEBLES I, SGC_TP_ACTIVIDADES A, SGC_TT_MEDIDOR_INMUEBLE M,sgc_tp_sectores s, SGC_TP_GERENCIAS G
			WHERE I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD 
			AND S.ID_SECTOR = I.ID_SECTOR
			AND I.CODIGO_INM = M.COD_INMUEBLE
			AND S.ID_GERENCIA = G.ID_GERENCIA 
			AND I.ID_PROYECTO = '$proyecto'
			AND M.FECHA_BAJA IS NULL
			--AND I.ID_ESTADO <> 'CC'
			GROUP BY G.ID_GERENCIA";
	
		//echo $sql;
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
	
	public function obtieneCantidadMedidoresCancelados($proyecto)
	{
		$sql="SELECT G.ID_GERENCIA, COUNT(I.CODIGO_INM)CANTIDAD
			FROM SGC_TT_INMUEBLES I,  SGC_TT_MEDIDOR_INMUEBLE M,sgc_tp_sectores s, SGC_TP_GERENCIAS G
			WHERE S.ID_SECTOR = I.ID_SECTOR
			AND I.CODIGO_INM = M.COD_INMUEBLE
			AND S.ID_GERENCIA = G.ID_GERENCIA 
			AND I.ID_PROYECTO = '$proyecto'
			AND M.FECHA_BAJA IS NULL 
			AND I.ID_ESTADO = 'CK'
			GROUP BY G.ID_GERENCIA";
	
		//echo $sql;
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


    public function obtieneCantidadMedidoresCt($proyecto)
    {
        $sql="SELECT G.ID_GERENCIA, COUNT(I.CODIGO_INM)CANTIDAD
			FROM SGC_TT_INMUEBLES I,  SGC_TT_MEDIDOR_INMUEBLE M,sgc_tp_sectores s, SGC_TP_GERENCIAS G
			WHERE S.ID_SECTOR = I.ID_SECTOR
			AND I.CODIGO_INM = M.COD_INMUEBLE
			AND S.ID_GERENCIA = G.ID_GERENCIA 
			AND I.ID_PROYECTO = '$proyecto'
			AND M.FECHA_BAJA IS NULL 
			AND I.ID_ESTADO = 'CT'
			GROUP BY G.ID_GERENCIA";

        //echo $sql;
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