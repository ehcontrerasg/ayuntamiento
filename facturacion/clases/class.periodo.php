<?php
include_once '../../clases/class.conexion.php';
class Periodo extends ConexionClass{
	Private $id_proyecto;
	Private $sigla;
	Private $descripcion;
	
	
	public function __construct()
	{
		parent::__construct();
		
	}
		
	public function obtenerperiodos (){
		$resultado = oci_parse($this->_db,"SELECT ID_PERIODO  FROM SGC_TP_PERIODOS");

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


    public function obtenerMaxperiodo ($zona){
        $sql="SELECT
                TO_CHAR(ADD_MONTHS(TO_DATE(MAX(PERIODO),'YYYYMM'),1),'YYYYMM')MAXPER,
                TO_CHAR(ADD_MONTHS(TO_DATE(MAX(PERIODO),'YYYYMM'),1),'Month','nls_date_language=spanish') MES
              FROM
                SGC_TP_PERIODO_ZONA
              WHERE
                ID_ZONA = '$zona' AND
                FEC_CIERRE IS NOT NULL";
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

    public function obtenerMaxPerModFecha ($zona){
        $sql="SELECT
                TO_CHAR(ADD_MONTHS(TO_DATE(MAX(PERIODO),'YYYYMM'),0),'YYYYMM')MAXPER,
                TO_CHAR(ADD_MONTHS(TO_DATE(MAX(PERIODO),'YYYYMM'),0),'Month','nls_date_language=spanish') MES
              FROM
                SGC_TP_PERIODO_ZONA
              WHERE
                ID_ZONA = '$zona' AND
                FEC_CIERRE IS NOT NULL";
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

    public function obtenerFechas ($zona){
        $sql="SELECT DISTINCT 
                TO_CHAR(F.FEC_EXPEDICION,'DD/MM/YYYY')FEC_EXPEDICION, 
                TO_CHAR(F.FEC_VCTO,'DD/MM/YYYY')FEC_VENCIMIENTO, 
                TO_CHAR(F.FECHA_CORTE,'DD/MM/YYYY')FEC_CORTE
              FROM 
                SGC_TT_FACTURA F, SGC_TP_PERIODO_ZONA Z
              WHERE 
                F.PERIODO = Z.PERIODO AND  
                F.ID_ZONA = '$zona' AND 
                Z.FEC_CIERRE IS NOT NULL AND
                Z.PERIODO = (SELECT MAX (Z2.PERIODO) FROM SGC_TP_PERIODO_ZONA Z2 WHERE Z2.ID_ZONA = '$zona')";
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


    //public function obtenerMaxPerNCF ($zona){
    public function obtenerMaxPerNCF (){
        $sql="SELECT
                TO_CHAR(ADD_MONTHS(TO_DATE(MAX(PERIODO),'YYYYMM'),0),'YYYYMM')MAXPER,
                TO_CHAR(ADD_MONTHS(TO_DATE(MAX(PERIODO),'YYYYMM'),0),'Month','nls_date_language=spanish') MES
              FROM
                SGC_TP_PERIODO_ZONA
              WHERE
                --ID_ZONA = 'zona' AND
                FEC_CIERRE IS NULL";
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


    public function obtenerCantidadNcf ($zona, $proyecto){
	    if($proyecto <> ""){
            $sqla = " AND I.ID_PROYECTO = '$proyecto' ";
        }
	    if($zona <> ""){
	        $sqlb = " AND F.ID_ZONA = '$zona' ";
        }
        $sql="SELECT N.ID_NCF,  COUNT(F.NCF_ID)CANTIDAD
        FROM SGC_TT_FACTURA F, SGC_TP_NCF_USOS N, SGC_TT_INMUEBLES I
        WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.NCF_ID = N.ID_NCF_USO
        AND F.PERIODO = (SELECT MAX(F2.PERIODO) FROM SGC_TP_PERIODO_ZONA F2 WHERE F2.PERIODO = F.PERIODO AND  F2.FEC_CIERRE IS NULL)
        $sqla
        $sqlb
        AND N.ID_NCF IN ('B01','B02','B14','B15')
        GROUP BY N.ID_NCF
        ORDER BY N.ID_NCF";
	    //echo "resultado: ".$sql;
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
