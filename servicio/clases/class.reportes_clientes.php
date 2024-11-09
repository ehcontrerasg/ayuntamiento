<?php
include_once 'class.conexion.php';
class ReportesGerencia extends ConexionClass
{
    private $id_proyecto;
    private $id_fecini;
    private $id_fecfin;

    public function __construct()
    {
        parent::__construct();
        $this->id_proyecto = "";
        $this->id_fecini   = "";
        $this->id_fecfin   = "";
    }

    public function seleccionaAcueducto()
    {
        $sql = "SELECT ID_PROYECTO, SIGLA_PROYECTO
		FROM SGC_TP_PROYECTOS
		ORDER BY SIGLA_PROYECTO";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    /////////////////////CONSULTAS REPORTES DEUDA ACTUAL OFICIALES

    public function RepRecaudoGrandesClientes($proyecto, $fechaini, $fechafin, $inmueble)
    {
        $sql = "SELECT I.CODIGO_INM, I.ID_ESTADO, I.ID_PROCESO, I.CATASTRO, C.ALIAS, I.DIRECCION, U.DESC_URBANIZACION, SUM(P.IMPORTE) IMPORTE, MAX(P.ID_PAGO) PAGO,
		MAX(TO_CHAR(P.FECHA_PAGO,'DD/MM/YYYY HH24:MI:SS')) FECPAGO
		FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TP_URBANIZACIONES U, SGC_TT_PAGOS P
		WHERE I.CODIGO_INM = C.CODIGO_INM(+) AND
		I.CONSEC_URB = U.CONSEC_URB AND
		I.CODIGO_INM = P.INM_CODIGO AND
		I.ID_PROYECTO = '$proyecto' AND
		I.ID_TIPO_CLIENTE = 'CN' AND
		C.FECHA_FIN IS NULL
		AND P.ESTADO NOT IN ('I')
		AND P.FECHA_PAGO BETWEEN TO_DATE ('$fechaini 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE ('$fechafin 23:59:59','YYYY-MM-DD HH24:MI:SS')";

        if ($inmueble != '') {
            $sql .= " AND I.CODIGO_INM = $inmueble";
        }

        $sql .= " GROUP BY I.CODIGO_INM, I.ID_ESTADO, I.ID_PROCESO, I.CATASTRO, C.ALIAS, I.DIRECCION, U.DESC_URBANIZACION";
        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }
}
