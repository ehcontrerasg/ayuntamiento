<?php
include_once '../../clases/class.conexion.php';
class ReportesConMen extends ConexionClass{

    public function __construct()
    {
        parent::__construct();
    }

    public function getcodresult(){
        return $this->codresult;
    }

    public function getmsgresult(){
        return $this->msgresult;
    }


    public function verificaCierreZonas($proyecto, $periodo){
        $sql = "SELECT COUNT(*)CANTIDAD
                FROM SGC_TP_PERIODO_ZONA PZ
                WHERE PZ.PERIODO = $periodo
                        AND PZ.FEC_CIERRE IS NULL
                        AND PZ.CODIGO_PROYECTO = '$proyecto'";

        //echo $sql;
        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == TRUE) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function FacturacionNorteConceptoAgr($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT HPC.CONCEPTO, HPC.PERIODO, HPC.FACTURADO
			FROM SGC_TH_FACT_PERIODO_CONCEPTO HPC
			WHERE HPC.PERIODO BETWEEN $perini AND $perfin
			AND HPC.PROYECTO = '$proyecto'
			AND HPC.GERENCIA IN ('N')
		)
		PIVOT 
		(
		   SUM(FACTURADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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


    public function RecaudacionNorteConceptoAgr($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT HPCR.CONCEPTO, HPCR.PERIODO, HPCR.RECAUDADO
			FROM SGC_TH_REC_PERIODO_CONCEPTO HPCR
			WHERE HPCR.PERIODO BETWEEN $perini AND $perfin
			AND HPCR.PROYECTO = '$proyecto'
			AND HPCR.GERENCIA IN ('N')
		)
		PIVOT 
		(
		   SUM(RECAUDADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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


    public function FacturacionEsteConceptoAgr($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT HPC.CONCEPTO, HPC.PERIODO, HPC.FACTURADO
			FROM SGC_TH_FACT_PERIODO_CONCEPTO HPC
			WHERE HPC.PERIODO BETWEEN $perini AND $perfin
			AND HPC.PROYECTO = '$proyecto'
			AND HPC.GERENCIA IN ('E')
		)
		PIVOT 
		(
		   SUM(FACTURADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function RecaudacionEsteConceptoAgr($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT HPCR.CONCEPTO, HPCR.PERIODO, HPCR.RECAUDADO
			FROM SGC_TH_REC_PERIODO_CONCEPTO HPCR
			WHERE HPCR.PERIODO BETWEEN $perini AND $perfin
			AND HPCR.PROYECTO = '$proyecto'
			AND HPCR.GERENCIA IN ('E')
		)
		PIVOT 
		(
		   SUM(RECAUDADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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


    public function FacturacionTotalConceptoAgr($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT HPC.CONCEPTO, HPC.PERIODO, HPC.FACTURADO
			FROM SGC_TH_FACT_PERIODO_CONCEPTO HPC
			WHERE HPC.PERIODO BETWEEN $perini AND $perfin
			AND HPC.PROYECTO = '$proyecto'
			AND HPC.GERENCIA IN ('N','E')
		)
		PIVOT 
		(
		   SUM(FACTURADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function RecaudacionTotalConceptoAgr($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT HPCR.CONCEPTO, HPCR.PERIODO, HPCR.RECAUDADO
			FROM SGC_TH_REC_PERIODO_CONCEPTO HPCR
			WHERE HPCR.PERIODO BETWEEN $perini AND $perfin
			AND HPCR.PROYECTO = '$proyecto'
			AND HPCR.GERENCIA IN ('N','E')
		)
		PIVOT 
		(
		   SUM(RECAUDADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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
///norte
    public function FacturacionNorteUso($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.USO, FPU.PERIODO, FPU.FACTURADO
			FROM SGC_TH_FACT_PERIODO_USO FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('N')
		)
		PIVOT 
		(
		   SUM(FACTURADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function RecaudacionNorteUso($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.USO, FPU.PERIODO, FPU.RECAUDADO
			FROM SGC_TH_REC_PERIODO_USO FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('N')
		)
		PIVOT 
		(
		   SUM(RECAUDADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function FacturasNorteUso($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.USO, FPU.PERIODO, FPU.FACTURAS
			FROM SGC_TH_FACT_PERIODO_USO FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('N')
		)
		PIVOT 
		(
		   SUM(FACTURAS)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function PagosNorteUso($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.USO, FPU.PERIODO, FPU.PAGOS
			FROM SGC_TH_REC_PERIODO_USO FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('N')
		)
		PIVOT 
		(
		   SUM(PAGOS)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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
 /////ESTE
    public function FacturacionEsteUso($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.USO, FPU.PERIODO, FPU.FACTURADO
			FROM SGC_TH_FACT_PERIODO_USO FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('E')
		)
		PIVOT 
		(
		   SUM(FACTURADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function RecaudacionEsteUso($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.USO, FPU.PERIODO, FPU.RECAUDADO
			FROM SGC_TH_REC_PERIODO_USO FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('E')
		)
		PIVOT 
		(
		   SUM(RECAUDADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function FacturasEsteUso($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.USO, FPU.PERIODO, FPU.FACTURAS
			FROM SGC_TH_FACT_PERIODO_USO FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('E')
		)
		PIVOT 
		(
		   SUM(FACTURAS)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function PagosEsteUso($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.USO, FPU.PERIODO, FPU.PAGOS
			FROM SGC_TH_REC_PERIODO_USO FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('E')
		)
		PIVOT 
		(
		   SUM(PAGOS)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    //gerencia oriental

    public function FacturacionTotalUso($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.USO, FPU.PERIODO, FPU.FACTURADO
			FROM SGC_TH_FACT_PERIODO_USO FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('N','E')
		)
		PIVOT 
		(
		   SUM(FACTURADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function RecaudacionTotalUso($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.USO, FPU.PERIODO, FPU.RECAUDADO
			FROM SGC_TH_REC_PERIODO_USO FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('N','E')
		)
		PIVOT 
		(
		   SUM(RECAUDADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function FacturasTotalUso($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.USO, FPU.PERIODO, FPU.FACTURAS
			FROM SGC_TH_FACT_PERIODO_USO FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('N','E')
		)
		PIVOT 
		(
		   SUM(FACTURAS)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function PagosTotalUso($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.USO, FPU.PERIODO, FPU.PAGOS
			FROM SGC_TH_REC_PERIODO_USO FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('N','E')
		)
		PIVOT 
		(
		   SUM(PAGOS)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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


    ///facturacion recaudo por sector - norte

    public function FacturacionNorteSector($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.SECTOR, FPU.PERIODO, FPU.FACTURADO
			FROM SGC_TH_FACT_PERIODO_SECTOR FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('N')
		)
		PIVOT 
		(
		   SUM(FACTURADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function RecaudacionNorteSector($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.SECTOR, FPU.PERIODO, FPU.RECAUDADO
			FROM SGC_TH_REC_PERIODO_SECTOR FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('N')
		)
		PIVOT 
		(
		   SUM(RECAUDADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function FacturasNorteSector($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.SECTOR, FPU.PERIODO, FPU.FACTURAS
			FROM SGC_TH_FACT_PERIODO_SECTOR FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('N')
		)
		PIVOT 
		(
		   SUM(FACTURAS)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function PagosNorteSector($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.SECTOR, FPU.PERIODO, FPU.PAGOS
			FROM SGC_TH_REC_PERIODO_SECTOR FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('N')
		)
		PIVOT 
		(
		   SUM(PAGOS)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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


    ///facturacion recaudo por sector - ESTE

    public function FacturacionEsteSector($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.SECTOR, FPU.PERIODO, FPU.FACTURADO
			FROM SGC_TH_FACT_PERIODO_SECTOR FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('E')
		)
		PIVOT 
		(
		   SUM(FACTURADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function RecaudacionEsteSector($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.SECTOR, FPU.PERIODO, FPU.RECAUDADO
			FROM SGC_TH_REC_PERIODO_SECTOR FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('E')
		)
		PIVOT 
		(
		   SUM(RECAUDADO)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function FacturasEsteSector($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.SECTOR, FPU.PERIODO, FPU.FACTURAS
			FROM SGC_TH_FACT_PERIODO_SECTOR FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('E')
		)
		PIVOT 
		(
		   SUM(FACTURAS)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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

    public function PagosEsteSector($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT FPU.SECTOR, FPU.PERIODO, FPU.PAGOS
			FROM SGC_TH_REC_PERIODO_SECTOR FPU
			WHERE FPU.PERIODO BETWEEN $perini AND $perfin
			AND FPU.PROYECTO = '$proyecto'
			AND FPU.GERENCIA IN ('E')
		)
		PIVOT 
		(
		   SUM(PAGOS)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 ASC";

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
?>