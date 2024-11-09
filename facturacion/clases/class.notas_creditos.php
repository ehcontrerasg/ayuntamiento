<?php

include_once '../../clases/class.conexion.php';

class Notas_Credito extends ConexionClass{



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

    public function totalNc($proyecto, $fechaIn, $fechaFn)
    {
        $sql="SELECT DECODE(NF.ID_NCF, 42, 'B04', 45, 'B99') NCF, SUM(DN.VALOR) VALOR
        FROM SGC_TT_FACTURA                F,
             SGC_TT_NOTAS_FACTURAS         NF,
             SGC_TT_INMUEBLES              I,
             SGC_TT_DETALLE_NOTAS_FACTURAS DN
       WHERE I.CODIGO_INM = F.INMUEBLE
         AND NF.ID_NOTA = DN.ID_NOTA_FACT
         AND F.CONSEC_FACTURA = NF.FACTURA_APLICA
         AND NF.FECHA_EMISION BETWEEN
             TO_DATE('$fechaIn 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
             TO_DATE('$fechaFn 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
         AND NF.TIPO_NOTA = 'NC'
         AND I.ID_PROYECTO = '$proyecto'
         AND NF.FECHA_ANULACION IS NULL
         AND NF.ANULADA = 'N'
       GROUP BY NF.ID_NCF";

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

    public function totalNd($proyecto, $fechaIn, $fechaFn)
    {
        $sql="SELECT DECODE(NF.ID_NCF, 49, 'B98') NCF, SUM(DN.VALOR) VALOR
        FROM SGC_TT_FACTURA                F,
             SGC_TT_NOTAS_FACTURAS         NF,
             SGC_TT_INMUEBLES              I,
             SGC_TT_DETALLE_NOTAS_FACTURAS DN
       WHERE I.CODIGO_INM = F.INMUEBLE
         AND NF.ID_NOTA = DN.ID_NOTA_FACT
         AND F.CONSEC_FACTURA = NF.FACTURA_APLICA
         AND NF.FECHA_EMISION BETWEEN
             TO_DATE('$fechaIn 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
             TO_DATE('$fechaFn 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
         AND NF.TIPO_NOTA = 'ND'
         AND I.ID_PROYECTO = '$proyecto'
         AND NF.FECHA_ANULACION IS NULL
         AND NF.ANULADA = 'N'
       GROUP BY NF.ID_NCF";

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

    public function ConceptosNc($proyecto, $fechaIn, $fechaFn)
    {
        $sql=" SELECT S.DESC_SERVICIO CONCEPTO, SUM(DN.VALOR) VALOR
        FROM SGC_TT_FACTURA                F,
             SGC_TT_NOTAS_FACTURAS         NF,
             SGC_TT_INMUEBLES              I,
             SGC_TT_DETALLE_NOTAS_FACTURAS DN,
             SGC_TP_SERVICIOS              S
       WHERE I.CODIGO_INM = F.INMUEBLE
         AND NF.ID_NOTA = DN.ID_NOTA_FACT
         AND F.CONSEC_FACTURA = NF.FACTURA_APLICA
         AND NF.FECHA_EMISION BETWEEN
             TO_DATE('$fechaIn 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
             TO_DATE('$fechaFn 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
         AND NF.TIPO_NOTA = 'NC'
         AND I.ID_PROYECTO = '$proyecto'
         AND NF.FECHA_ANULACION IS NULL
         AND NF.ANULADA = 'N'
         AND S.COD_SERVICIO = DN.CONCEPTO
       GROUP BY S.DESC_SERVICIO";

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

    public function ConceptosNd($proyecto, $fechaIn, $fechaFn)
    {
        $sql=" SELECT S.DESC_SERVICIO CONCEPTO, SUM(DN.VALOR) VALOR
        FROM SGC_TT_FACTURA                F,
             SGC_TT_NOTAS_FACTURAS         NF,
             SGC_TT_INMUEBLES              I,
             SGC_TT_DETALLE_NOTAS_FACTURAS DN,
             SGC_TP_SERVICIOS              S
       WHERE I.CODIGO_INM = F.INMUEBLE
         AND NF.ID_NOTA = DN.ID_NOTA_FACT
         AND F.CONSEC_FACTURA = NF.FACTURA_APLICA
         AND NF.FECHA_EMISION BETWEEN
             TO_DATE('$fechaIn 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND
             TO_DATE('$fechaFn 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
         AND NF.TIPO_NOTA = 'ND'
         AND I.ID_PROYECTO = '$proyecto'
         AND NF.FECHA_ANULACION IS NULL
         AND NF.ANULADA = 'N'
         AND S.COD_SERVICIO = DN.CONCEPTO
       GROUP BY S.DESC_SERVICIO";

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


