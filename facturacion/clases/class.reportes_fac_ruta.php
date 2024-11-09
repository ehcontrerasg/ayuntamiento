<?php

include_once '../../clases/class.conexion.php';

class Reportes_Fac_Ruta extends ConexionClass{

	public function __construct()
	{
		parent::__construct();

	}

	public function ultimo_periodo_zona($proyecto,$zonini)
	{
		$sql="SELECT MAX(PERIODO)PERIODO
		FROM SGC_TP_PERIODO_ZONA
		WHERE CODIGO_PROYECTO = '$proyecto'
		AND ID_ZONA = '$zonini'
		AND FEC_CIERRE IS NOT NULL";
	
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
	
	public function obtieneRutasCantidadFac($proyecto,$zonini,$perini)
	{
		$sql="SELECT I.ID_RUTA, COUNT(F.CONSEC_FACTURA)CANTIDAD
		FROM SGC_TT_FACTURA F, SGC_TT_INMUEBLES I
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND F.PERIODO = $perini
		AND I.ID_ZONA = '$zonini'
		AND I.ID_PROYECTO = '$proyecto'
		GROUP BY I.ID_RUTA
		ORDER BY I.ID_RUTA";
	
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

    public function obtieneRutasCantidadMed($proyecto,$secini,$perini)
    {
        $sql="SELECT I.ID_RUTA, COUNT(F.CONSEC_FACTURA)CANTIDAD
                FROM  SGC_TT_INMUEBLES I, SGC_TT_MEDIDOR_INMUEBLE M, SGC_TT_FACTURA F
                WHERE M.COD_INMUEBLE = I.CODIGO_INM
                  AND F.INMUEBLE = M.COD_INMUEBLE
                  AND F.PERIODO = $perini
                  AND F.ID_SECTOR = $secini
                  AND I.ID_PROYECTO = '$proyecto'
                AND M.FECHA_BAJA IS NULL
                GROUP BY I.ID_RUTA
                ORDER BY I.ID_RUTA";

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