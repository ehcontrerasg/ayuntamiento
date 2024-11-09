<?php

include_once '../../clases/class.conexion.php';

class Entrega_Factura extends ConexionClass{



    public function __construct()
    {
        parent::__construct();

    }

    public function seleccionaAcueducto (){
		$sql = "SELECT ID_PROYECTO, SIGLA_PROYECTO
		FROM SGC_TP_PROYECTOS
		ORDER BY SIGLA_PROYECTO";
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

    public function Rendimiento_entregafac($proyecto, $periodo)
    {
        $sql="SELECT FAC.ID_ZONA,
                     TO_CHAR(MIN(FAC.FECHA_EJECUCION), 'dd/mm/yyyy hh24:mi:ss') INICIO,
                     TO_CHAR(MAX(FAC.FECHA_EJECUCION), 'dd/mm/yyyy hh24:mi:ss') FIN,
                     COUNT(1) TOTAL
               FROM SGC_TT_REGISTRO_ENTREGA_FAC FAC, SGC_TT_INMUEBLES I
             WHERE FAC.COD_INMUEBLE = I.CODIGO_INM
                AND I.ID_PROYECTO = '$proyecto'
                AND FAC.PERIODO = $periodo
                AND FAC.ENTREGADO = 'S'
            GROUP BY FAC.ID_ZONA
            ORDER BY FAC.ID_ZONA ASC";

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


