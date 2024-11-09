<?php
include "../clases/class.conexion.php";
class Medidor extends ConexionClass{
	
	public function __construct(){
		parent::__construct();
	}
	


	public function TotMedTot ($inmueble)
	{	
		$sql="SELECT I1.COD_INM_PADRE,I1.COD_INM_HIJO,ROWNUM FROM SGC_TT_INM_TOTALIZADOS I1 WHERE
                I1.COD_INM_PADRE= (SELECT I.COD_INM_PADRE FROM SGC_TT_INM_TOTALIZADOS I
                WHERE I.COD_INM_HIJO=$inmueble OR I.COD_INM_PADRE=$inmueble
                GROUP BY I.COD_INM_PADRE)
                ORDER BY I1.COD_INM_HIJO";
		//echo $sql;
		$resultado = oci_parse(
				$this->_db,$sql
        );

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