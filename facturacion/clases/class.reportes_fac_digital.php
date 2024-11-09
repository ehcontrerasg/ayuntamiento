<?php

include_once '../../clases/class.conexion.php';

class Reportes_Fac_Digital extends ConexionClass{



    public function __construct()
    {
        parent::__construct();

    }

    public function obtieneFacturas($proyecto, $periodo)
    {
        $sql="SELECT F.INMUEBLE, F.ID_ZONA, EF.CONSEC_FACTURA, EF.EMAIL, TO_CHAR(EF.FECHA_ENVIO,'DD/MM/YYYY HH24:MI:SS') FECHA
        FROM SGC_TT_ENVIO_FACDIGITAL EF, SGC_TT_FACTURA F
        WHERE EF.CONSEC_FACTURA = F.CONSEC_FACTURA 
        AND EF.ENVIADO = 'S'
        AND EF.PERIODO = $periodo
        AND EF.ACUEDUCTO = '$proyecto'";

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