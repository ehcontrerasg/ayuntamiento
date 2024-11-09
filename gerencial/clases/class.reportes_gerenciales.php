<?php
include_once '../../clases/class.conexion.php';
class ReportesGerencia extends ConexionClass{
    private $id_proyecto;
    private $id_fecini;
    private $id_fecfin;

    public function __construct()
    {
        parent::__construct();
        $this->id_proyecto="";
        $this->sigla="";
        $this->descripcion="";

    }

    public function setid			($valor){$this->id_proyecto=$valor;}
    public function setsigla		($valor){$this->sigla=$valor;}
    public function setdescripcion	($valor){$this->descripcion=$valor;}

    public function seleccionaAcueducto ($usr){
        $sql = "SELECT PR.ID_PROYECTO, PR.SIGLA_PROYECTO
		FROM SGC_TP_PROYECTOS PR, SGC_TT_PERMISOS_USUARIO PU
		WHERE PR.ID_PROYECTO = PU.ID_PROYECTO AND PU.ID_USUARIO = '$usr'
		ORDER BY PR.SIGLA_PROYECTO ";
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


    public function seleccionaSector($pro){
        $sql = "SELECT ID_SECTOR DESCRIPCION, ID_SECTOR CODIGO
        FROM SGC_TP_SECTORES S
        WHERE ID_PROYECTO = '$pro'
        AND ACTIVO = 'S'
        ORDER BY ID_SECTOR";
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


    public function seleccionaRuta($sec){
        $sql = "SELECT DESC_RUTA, ID_RUTA
        FROM SGC_TP_RUTAS
        WHERE ID_SECTOR = $sec
        ORDER BY ID_RUTA";
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


    public function seleccionaEmpleado($pro){
        $sql = "SELECT ID_USUARIO, NOM_USR||' '||APE_USR  NOM_USUARIO
        FROM SGC_TT_USUARIOS U
        WHERE U.CATASTRO = 'S'
        AND U.FEC_FIN IS NULL
        AND U.ID_PROYECTO = '$pro'";
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


    public function seleccionaEmpleadoLec($pro){
        $sql = "SELECT ID_USUARIO, NOM_USR||' '||APE_USR  NOM_USUARIO
        FROM SGC_TT_USUARIOS U
        WHERE U.LECTURA = 'S'
        AND U.FEC_FIN IS NULL
        AND U.ID_PROYECTO = '$pro'";
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


    public function seleccionaEmpleadoFac($pro){
        $sql = "SELECT ID_USUARIO, NOM_USR||' '||APE_USR  NOM_USUARIO
        FROM SGC_TT_USUARIOS U
        WHERE U.REPARTO = 'S'
        AND U.FEC_FIN IS NULL
        AND U.ID_PROYECTO = '$pro'";
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


    public function seleccionaUso(){
        $sql = "SELECT ID_USO, DESC_USO
        FROM SGC_TP_USOS 
        WHERE VISIBLE = 'S'
        ORDER BY ID_USO";
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


    public function seleccionaDiametro(){
        $sql = "SELECT COD_CALIBRE, DESC_CALIBRE
        FROM SGC_TP_CALIBRES
        WHERE ACTIVO = 'S'
        ORDER BY COD_CALIBRE";
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



/////////////////////************************************/////////////////////////

    public function UnidadesUsoPeriodo($proyecto, $periodo)
    {
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido') USO, S.DESC_SERVICIO CONCEPTO, SUM(SI.UNIDADES_HAB) UNIDADES
        FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_ACTIVIDADES A, SGC_TT_INMUEBLES I, SGC_TP_ESTADOS_INMUEBLES EI, SGC_TP_USOS U, SGC_TP_SERVICIOS S
        WHERE I.CODIGO_INM = SI.COD_INMUEBLE 
        AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
        AND I.ID_ESTADO = EI.ID_ESTADO
        AND U.ID_USO(+) = A.ID_USO
        AND S.COD_SERVICIO = SI.COD_SERVICIO
        AND SI.COD_SERVICIO IN (1,2,3,4)
        AND EI.INDICADOR_ESTADO = 'A'
        AND I.PER_ALTA <= $periodo
        AND I.ID_PROYECTO = '$proyecto'
        GROUP BY U.DESC_USO, S.DESC_SERVICIO
        ORDER BY U.DESC_USO";

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

    /////REPORTE METROS CUBICOS COMPARATIVO  MES ANTERIOR AGUA RED
    public function MetrosMesAnteriorMedidosEste($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;

        $sql = "SELECT * FROM (
            SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('E')
        AND I.FACTURAR IN ('D')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('N')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC


/*SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    public function MetrosMesAnteriorMedidosEsteZF($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('E')
        AND I.FACTURAR IN ('D')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('S')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        
        /*SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA  IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    public function MetrosMesAnteriorMedidosNorte($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT (''||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('N')
        AND I.FACTURAR IN ('D')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('N')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        
        /*SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    //FACTURACION POR CONCEPTO AGRUPADO

    public function MetrosMesAnteriorMedidosNorteZF($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('N')
        AND I.FACTURAR IN ('D')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('S')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        /*SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    public function MetrosMesAnteriorMedidosTotal($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT (''||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('N','E')
        AND I.FACTURAR IN ('D')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('N')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        
        /*SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    public function MetrosMesAnteriorMedidosTotalZF($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('N','E')
        AND I.FACTURAR IN ('D')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('S')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        /*SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    public function MetrosMesAnteriorNoMedidosEste($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT (''||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('E')
        AND I.FACTURAR IN ('P')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('N')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC

        /*SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    public function MetrosMesAnteriorNoMedidosEsteZF($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('E')
        AND I.FACTURAR IN ('P')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('S')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC


        /*SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    public function MetrosMesAnteriorNoMedidosNorte($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT (''||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('N')
        AND I.FACTURAR IN ('P')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('N')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        
        /*SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    public function MetrosMesAnteriorNoMedidosTotal($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT (''||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('N','E')
        AND I.FACTURAR IN ('P')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('N')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        
        /*SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    public function MetrosMesAnteriorNoMedidosTotalZF($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('N','E')
        AND I.FACTURAR IN ('P')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('S')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        
        /*SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    public function MetrosMesAnteriorTotalEste($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT (''||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('E')
        AND I.FACTURAR IN ('D','P')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('N')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        
        /*SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

       // echo $sql;
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

    public function MetrosMesAnteriorTotalEsteZF($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('E')
        AND I.FACTURAR IN ('D','P')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('S')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        
        /*SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    public function MetrosMesAnteriorTotalNorte($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT (''||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('N')
        AND I.FACTURAR IN ('D','P')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('N')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        
        
        /*SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    public function MetrosMesAnteriorTotalNorteZF($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('N')
        AND I.FACTURAR IN ('D','P')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('S')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        
        /*SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    public function MetrosMesAnteriorTotalTotal($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT (''||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('N','E')
        AND I.FACTURAR IN ('D','P')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('N')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        
        /*SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)

		ORDER BY 1 ASC*/";
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

    public function MetrosMesAnteriorTotalTotalZF($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
            SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_SECTORES S, SGC_TP_USOS U
			WHERE I.CODIGO_INM = F.INMUEBLE
        AND F.CONSEC_FACTURA = DF.FACTURA
        AND F.USO = U.ID_USO
        AND F.ID_SECTOR = S.ID_SECTOR
        AND DF.CONCEPTO IN (1)
        AND DF.PERIODO BETWEEN $perini AND $perfin
        AND S.ID_PROYECTO  IN ('$proyecto')
        AND S.ID_GERENCIA IN ('N','E')
        AND I.FACTURAR IN ('D','P')
        AND U.ID_USO IN ('R','I','C','O','M')
        AND I.ZONA_FRANCA  IN ('S')
		)
		PIVOT
        (
            SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin')
		)
		ORDER BY 1 DESC
        
        
        
        /*SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC*/";

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

    //////REPORTE METROS MES ANTERIOR AGUA POZO

    public function MetrosMesAnteriorMedidosEstePozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";

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

    public function MetrosMesAnteriorMedidosEsteZFPozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA  IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";

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

    public function MetrosMesAnteriorMedidosNortePozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";

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

    public function MetrosMesAnteriorMedidosNorteZFPozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";

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

    public function MetrosMesAnteriorMedidosTotalPozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";

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

    public function MetrosMesAnteriorNoMedidosNorteZF($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosMesAnteriorMedidosTotalZFPozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosMesAnteriorNoMedidosEstePozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosMesAnteriorNoMedidosEsteZFPozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosMesAnteriorNoMedidosNortePozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosMesAnteriorNoMedidosNorteZFPozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosMesAnteriorNoMedidosTotalPozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosMesAnteriorNoMedidosTotalZFPozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosMesAnteriorTotalEstePozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosMesAnteriorTotalEsteZFPozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosMesAnteriorTotalNortePozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosMesAnteriorTotalNorteZFPozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;


        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosMesAnteriorTotalTotalPozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
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

    public function MetrosMesAnteriorTotalTotalZFPozo($proyecto, $periodo){
        $ano = substr($periodo,0,4);
        $mes = substr($periodo,4,2);
        if($mes == '01') $perini = ($ano-1).'12';
        else $perini = $periodo -1;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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
    /////////////  REPORTE FACTURACION DE AGUA EN M3 COMPARATIVO A�O ANTERIOR

    public function MetrosAnoAnteriorMedidosEste($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorMedidosEsteZF($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA  IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorMedidosNorte($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorMedidosNorteZF($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorMedidosTotal($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorMedidosTotalZF($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorNoMedidosEste($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorNoMedidosEsteZF($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorNoMedidosNorte($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorNoMedidosNorteZF($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorNoMedidosTotal($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorNoMedidosTotalZF($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorTotalEste($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorTotalEsteZF($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorTotalNorte($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorTotalNorteZF($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorTotalTotal($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
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

    public function MetrosAnoAnteriorTotalTotalZF($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (1,3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    //////REPORTE METROS MES ANTERIOR AGUA POZO

    public function MetrosAnoAnteriorMedidosEstePozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorMedidosEsteZFPozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA  IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorMedidosNortePozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorMedidosNorteZFPozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorMedidosTotalPozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorMedidosTotalZFPozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorNoMedidosEstePozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorNoMedidosEsteZFPozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorNoMedidosNortePozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorNoMedidosNorteZFPozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorNoMedidosTotalPozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorNoMedidosTotalZFPozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorTotalEstePozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorTotalEsteZFPozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorTotalNortePozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorTotalNorteZFPozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    public function MetrosAnoAnteriorTotalTotalPozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT U.DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('N')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
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

    public function MetrosAnoAnteriorTotalTotalZFPozo($proyecto, $periodo){
        $anoini = substr($periodo,0,4) - 1;
        $mesini = substr($periodo,4,2);
        $perini = $anoini.$mesini;
        $perfin = $periodo;
        $sql = "SELECT * FROM (
			SELECT ('ZF '||U.DESC_USO)DESC_USO, DF.PERIODO, DF.UNIDADES_ORI 
			FROM SGC_TT_INMUEBLES I, SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U, SGC_TP_SECTORES S
			WHERE I.CODIGO_INM = F.INMUEBLE 
			AND F.CONSEC_FACTURA = DF.FACTURA
			--AND F.PERIODO = DF.PERIODO
			AND A.SEC_ACTIVIDAD = I.SEC_ACTIVIDAD
			AND A.ID_USO = U.ID_USO
			AND S.ID_SECTOR = I.ID_SECTOR
			AND DF.CONCEPTO IN (3)
			AND DF.PERIODO BETWEEN $perini AND $perfin
			AND I.ID_PROYECTO = '$proyecto'
			AND S.ID_GERENCIA IN ('N','E')
			AND I.FACTURAR IN ('D','P')
			AND A.ID_USO IN ('R','I','C','O','M')
			AND I.ID_ESTADO NOT IN ('CC','CT','CB','CK')
			AND I.ZONA_FRANCA IN ('S')
		)
		PIVOT 
		(
		   SUM(UNIDADES_ORI)
		   FOR (PERIODO) IN ('$perini','$perfin') 
		)
		ORDER BY 1 DESC";
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

    /////////////////////CONSULTAS REPORTES DEUDA ACTUAL OFICIALES

    public function DeudaOficialVencidas($proyecto){
        $sql = "SELECT C.GRUPO, G.DESC_GRUPO, SUM(F.TOTAL) FACTURADO, COUNT(F.CONSEC_FACTURA) CANTIDADFACVE
		FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL, SGC_TP_GRUPOS G, SGC_TT_FACTURA F
		WHERE C.GRUPO = G.COD_GRUPO
		AND I.CODIGO_INM = C.CODIGO_INM
		AND C.CODIGO_CLI = CL.CODIGO_CLI
		AND F.INMUEBLE = I.CODIGO_INM
		AND C.GRUPO IS NOT NULL
		AND F.FACTURA_PAGADA IN ('N')
		AND C.GRUPO > 0
		AND C.FECHA_FIN IS NULL
		AND F.FEC_VCTO < SYSDATE
		AND I.ID_PROYECTO = '$proyecto'
		AND ACTIVO = 'S' AND OFICIAL = 'S'
		GROUP BY C.GRUPO, G.DESC_GRUPO
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

    public function DeudaOficialPeriodo($proyecto, $periodo, $cod_grupo){
        $sql = "SELECT SUM(F.TOTAL) FACTURADOPER
		FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL, SGC_TP_GRUPOS G, SGC_TT_FACTURA F
		WHERE C.GRUPO = G.COD_GRUPO
		AND I.CODIGO_INM = C.CODIGO_INM
		AND C.CODIGO_CLI = CL.CODIGO_CLI
		AND F.INMUEBLE = I.CODIGO_INM
		AND C.GRUPO IS NOT NULL
		AND F.FACTURA_PAGADA IN ('N')
		AND C.GRUPO > 0
		AND C.FECHA_FIN IS NULL
		AND F.PERIODO = $periodo
		AND C.GRUPO = $cod_grupo
		AND I.ID_PROYECTO = '$proyecto'
		AND ACTIVO = 'S' AND OFICIAL = 'S'
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

    /////////////////////CONSULTAS REPORTES UNIDADES POR GERENCIA USO CONCEPTO Y PERIODO

    public function UnidadesGerenciaUsoConceptoPeriodoUsos($proyecto, $periodo){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO
		FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE I.CODIGO_INM = SI.COD_INMUEBLE
		AND S.ID_SECTOR = I.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND SI.COD_SERVICIO IN (1)
		AND I.ID_ESTADO NOT IN ('CC','CT','CB')
		AND S.ID_GERENCIA IN ('E')
		AND I.ID_PROYECTO = '$proyecto'
		AND I.PER_ALTA <= $periodo
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function UnidadesGerenciaUsoConceptoPeriodoEsteAgua($proyecto, $periodo, $des_uso){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, NVL(SUM(SI.UNIDADES_TOT),0)UNIDADES
		FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE I.CODIGO_INM = SI.COD_INMUEBLE
		AND S.ID_SECTOR = I.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND SI.COD_SERVICIO IN (1)
		AND I.ID_ESTADO NOT IN ('CC','CT','CB')
		AND S.ID_GERENCIA IN ('E')
		AND I.ID_PROYECTO = '$proyecto'
		AND I.PER_ALTA <= $periodo
		AND U.DESC_USO = '$des_uso'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function UnidadesGerenciaUsoConceptoPeriodoEstePozo($proyecto, $periodo, $des_uso){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, NVL(SUM(SI.UNIDADES_TOT),0)UNIDADES
		FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE I.CODIGO_INM = SI.COD_INMUEBLE
		AND S.ID_SECTOR = I.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND SI.COD_SERVICIO IN (3)
		AND I.ID_ESTADO NOT IN ('CC','CT','CB')
		AND S.ID_GERENCIA IN ('E')
		AND I.ID_PROYECTO = '$proyecto'
		AND I.PER_ALTA <= $periodo
		AND U.DESC_USO = '$des_uso'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function UnidadesGerenciaUsoConceptoPeriodoEsteAlc($proyecto, $periodo, $des_uso){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, NVL(SUM(SI.UNIDADES_TOT),0)UNIDADES
		FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE I.CODIGO_INM = SI.COD_INMUEBLE
		AND S.ID_SECTOR = I.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND SI.COD_SERVICIO IN (2,4)
		AND I.ID_ESTADO NOT IN ('CC','CT','CB')
		AND S.ID_GERENCIA IN ('E')
		AND I.ID_PROYECTO = '$proyecto'
		AND I.PER_ALTA <= $periodo
		AND U.DESC_USO = '$des_uso'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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


    /////////*********************************************************************************
    public function UnidadesGerenciaUsoConceptoPeriodoNorteAgua2($proyecto, $periodo, $des_uso){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, NVL(SUM(SI.UNIDADES_TOT),0)UNIDADES
		FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE I.CODIGO_INM = SI.COD_INMUEBLE
		AND S.ID_SECTOR = I.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND SI.COD_SERVICIO IN (1)
		AND I.ID_ESTADO NOT IN ('CC','CT','CB')
		AND S.ID_GERENCIA IN ('N')
		AND I.ID_PROYECTO = '$proyecto'
		AND I.PER_ALTA <= $periodo
		AND U.DESC_USO = '$des_uso'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function UnidadesGerenciaUsoConceptoPeriodoNortePozo2($proyecto, $periodo, $des_uso){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, NVL(SUM(SI.UNIDADES_TOT),0)UNIDADES
		FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE I.CODIGO_INM = SI.COD_INMUEBLE
		AND S.ID_SECTOR = I.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND SI.COD_SERVICIO IN (3)
		AND I.ID_ESTADO NOT IN ('CC','CT','CB')
		AND S.ID_GERENCIA IN ('N')
		AND I.ID_PROYECTO = '$proyecto'
		AND I.PER_ALTA <= $periodo
		AND U.DESC_USO = '$des_uso'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function UnidadesGerenciaUsoConceptoPeriodoNorteAlc2($proyecto, $periodo, $des_uso){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, NVL(SUM(SI.UNIDADES_TOT),0)UNIDADES
		FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE I.CODIGO_INM = SI.COD_INMUEBLE
		AND S.ID_SECTOR = I.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND SI.COD_SERVICIO IN (2,4)
		AND I.ID_ESTADO NOT IN ('CC','CT','CB')
		AND S.ID_GERENCIA IN ('N')
		AND I.ID_PROYECTO = '$proyecto'
		AND I.PER_ALTA <= $periodo
		AND U.DESC_USO = '$des_uso'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    ///////**********************************************







    public function UnidadesGerenciaUsoConceptoPeriodoNorteAgua($proyecto, $periodo){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, NVL(SUM(SI.UNIDADES_TOT),0)UNIDADES
		FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE I.CODIGO_INM = SI.COD_INMUEBLE
		AND S.ID_SECTOR = I.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND SI.COD_SERVICIO IN (1)
		AND I.ID_ESTADO NOT IN ('CC','CT','CB')
		AND S.ID_GERENCIA IN ('N')
		AND I.ID_PROYECTO = '$proyecto'
		AND I.PER_ALTA <= $periodo
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function UnidadesGerenciaUsoConceptoPeriodoNortePozo($proyecto, $periodo){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, NVL(SUM(SI.UNIDADES_TOT),0)UNIDADES
		FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE I.CODIGO_INM = SI.COD_INMUEBLE
		AND S.ID_SECTOR = I.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND SI.COD_SERVICIO IN (3)
		AND I.ID_ESTADO NOT IN ('CC','CT','CB')
		AND S.ID_GERENCIA IN ('N')
		AND I.ID_PROYECTO = '$proyecto'
		AND I.PER_ALTA <= $periodo
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function UnidadesGerenciaUsoConceptoPeriodoNorteAlc($proyecto, $periodo){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, NVL(SUM(SI.UNIDADES_TOT),0)UNIDADES
		FROM SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE I.CODIGO_INM = SI.COD_INMUEBLE
		AND S.ID_SECTOR = I.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND SI.COD_SERVICIO IN (2,4)
		AND I.ID_ESTADO NOT IN ('CC','CT','CB')
		AND S.ID_GERENCIA IN ('N')
		AND I.ID_PROYECTO = '$proyecto'
		AND I.PER_ALTA <= $periodo
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function UsuariosAlcGerenciaUsoConceptoPeriodoEste($proyecto, $periodo){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, COUNT(I.CODIGO_INM)CANTIDAD
		FROM SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE I.CODIGO_INM = SI.COD_INMUEBLE
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND SI.COD_SERVICIO IN (2,4)
		AND S.ID_GERENCIA = 'E'
		AND I.ID_ESTADO NOT IN ('CC','CT','CB')
		AND I.ID_PROYECTO = '$proyecto'
		AND I.PER_ALTA <= $periodo
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function UsuariosAlcGerenciaUsoConceptoPeriodoNorte($proyecto, $periodo){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, COUNT(I.CODIGO_INM)CANTIDAD
		FROM SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TT_SERVICIOS_INMUEBLES SI, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE I.CODIGO_INM = SI.COD_INMUEBLE
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND SI.COD_SERVICIO IN (2,4)
		AND S.ID_GERENCIA = 'N'
		AND I.ID_ESTADO NOT IN ('CC','CT','CB')
		AND I.ID_PROYECTO = '$proyecto'
		AND I.PER_ALTA <= $periodo
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    /////////////////////CONSULTAS REPORTES GRANDES CLIENTES

    public function RepGrandesClientes($proyecto, $periodo){
        $sql = "SELECT I.CODIGO_INM, C.ALIAS, CL.NOMBRE_CLI, SUM(DF.UNIDADES)METROS, SUM(F.TOTAL)FACTURADO 
		FROM SGC_TT_INMUEBLES I, SGC_TT_CONTRATOS C, SGC_TT_CLIENTES CL, SGC_TT_DETALLE_FACTURA DF, SGC_TT_FACTURA F
		WHERE I.CODIGO_INM = C.CODIGO_INM 
		AND C.CODIGO_CLI = CL.CODIGO_CLI
		AND I.CODIGO_INM = DF.COD_INMUEBLE
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO
		AND I.ID_TIPO_CLIENTE = 'GC'
		AND I.ID_PROYECTO = '$proyecto'
		AND I.ID_ESTADO NOT IN ('CC','CT','CB')
		AND DF.PERIODO = $periodo
		AND DF.CONCEPTO IN (1,3)
		GROUP BY I.CODIGO_INM, C.ALIAS, CL.NOMBRE_CLI
		ORDER BY CODIGO_INM
		";
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

    public function RepGrandesClientesRecaudo($cod_inm, $periodo){
        $sql = "SELECT NVL(SUM(P.IMPORTE),0)IMPORTE
		FROM SGC_TT_PAGOS P
		WHERE P.INM_CODIGO = $cod_inm
		AND SUBSTR (FECIND,0,6) = $periodo";
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

    /////REPORTE FACTURACION POR GERENCIA Y USO
    public function FacturacionUsoAguaEste($proyecto, $periodo){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO,  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (1,3)
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function FacturacionUsoAguaEsteSolar($proyecto, $periodo){
        $sql = "SELECT  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (1,3)
		AND I.SEC_ACTIVIDAD IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		";
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

    public function FacturacionUsoAguaEsteZFC($proyecto, $periodo){
        $sql = "SELECT  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (1,3)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('C')
		";
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

    public function FacturacionUsoAguaEsteZFI($proyecto, $periodo){
        $sql = "SELECT  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (1,3)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('I')
		";
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

    public function FacturacionUsoAlcEste($proyecto, $periodo){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO,  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (2,4)
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function FacturacionUsoAlcEsteSolar($proyecto, $periodo){
        $sql = "SELECT  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (2,4)
		AND I.SEC_ACTIVIDAD  IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		";
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

    public function FacturacionUsoAlcEsteZFC($proyecto, $periodo){
        $sql = "SELECT  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (2,4)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('C')
		";
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

    public function FacturacionUsoAlcEsteZFI($proyecto, $periodo){
        $sql = "SELECT  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND DF.CONCEPTO IN (2,4)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('I')
		";
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

    //FACTURACION AGUA ALC NORTE

    public function FacturacionUsoAguaNorte($proyecto, $periodo){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO,  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (1,3)
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function FacturacionUsoAguaNorteSolar($proyecto, $periodo){
        $sql = "SELECT  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (1,3)
		AND I.SEC_ACTIVIDAD IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		";
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

    public function FacturacionUsoAguaNorteZFC($proyecto, $periodo){
        $sql = "SELECT  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (1,3)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('C')
		";
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

    public function FacturacionUsoAguaNorteZFI($proyecto, $periodo){
        $sql = "SELECT  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (1,3)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('I')
		";
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

    public function FacturacionUsoAlcNorte($proyecto, $periodo){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO,  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (2,4)
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function FacturacionUsoAlcNorteSolar($proyecto, $periodo){
        $sql = "SELECT  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (2,4)
		AND I.SEC_ACTIVIDAD  IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		";
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

    public function FacturacionUsoAlcNorteZFC($proyecto, $periodo){
        $sql = "SELECT  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (2,4)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('C')
		";
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

    public function FacturacionUsoAlcNorteZFI($proyecto, $periodo){
        $sql = "SELECT  SUM(DF.VALOR)FACTURADO
		FROM SGC_TT_FACTURA F, SGC_TT_DETALLE_FACTURA DF, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE F.INMUEBLE = I.CODIGO_INM 
		AND DF.COD_INMUEBLE = I.CODIGO_INM
		AND F.CONSEC_FACTURA = DF.FACTURA
		--AND F.PERIODO = DF.PERIODO 
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND F.PERIODO = $periodo
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND DF.CONCEPTO IN (2,4)
		AND I.ZONA_FRANCA = 'S'
		AND A.ID_USO IN ('I')
		";
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

    /////REPORTE RECAUDACION POR GERENCIA Y USO
    public function RecaudoUsoAguaEste($proyecto, $ano, $mes, $dia){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND PD.CONCEPTO IN (1,3)
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function RecaudoUsoAguaEsteSolar($proyecto, $ano, $mes, $dia){
        $sql = "SELECT SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND PD.CONCEPTO IN (1,3)
		AND I.SEC_ACTIVIDAD IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		";
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

    public function RecaudoUsoAguaEsteZFC($proyecto, $ano, $mes, $dia){
        $sql = "SELECT SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND PD.CONCEPTO IN (1,3)
		AND A.ID_USO IN ('C')
		AND I.ZONA_FRANCA = 'S'
		";
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

    public function RecaudoUsoAguaEsteZFI($proyecto, $ano, $mes, $dia){
        $sql = "SELECT SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND PD.CONCEPTO IN (1,3)
		AND A.ID_USO IN ('I')
		AND I.ZONA_FRANCA = 'S'
		";
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

    public function RecaudoUsoAlcEste($proyecto, $ano, $mes, $dia){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		AND PD.CONCEPTO IN (2,4)
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function RecaudoUsoAlcEsteSolar($proyecto, $ano, $mes, $dia){
        $sql = "SELECT SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND I.SEC_ACTIVIDAD IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		AND PD.CONCEPTO IN (2,4)
		";
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

    public function RecaudoUsoAlcEsteZFC($proyecto, $ano, $mes, $dia){
        $sql = "SELECT SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I'
		AND P.ID_CAJA NOT IN (463,391) 
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND A.ID_USO IN ('C')
		AND I.ZONA_FRANCA = 'S'
		AND PD.CONCEPTO IN (2,4)
		";
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

    public function RecaudoUsoAlcEsteZFI($proyecto, $ano, $mes, $dia){
        $sql = "SELECT SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I'
		AND P.ID_CAJA NOT IN (463,391) 
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('E')
		AND A.ID_USO IN ('I')
		AND I.ZONA_FRANCA = 'S'
		AND PD.CONCEPTO IN (2,4)
		";
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

    ///RECAUDO AGUA -ALCANTARILLADO NORTE

    public function RecaudoUsoAguaNorte($proyecto, $ano, $mes, $dia){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND PD.CONCEPTO IN (1,3)
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function RecaudoUsoAguaNorteSolar($proyecto, $ano, $mes, $dia){
        $sql = "SELECT SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND PD.CONCEPTO IN (1,3)
		AND I.SEC_ACTIVIDAD IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		";
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

    public function RecaudoUsoAguaNorteZFC($proyecto, $ano, $mes, $dia){
        $sql = "SELECT SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND PD.CONCEPTO IN (1,3)
		AND A.ID_USO IN ('C')
		AND I.ZONA_FRANCA = 'S'
		";
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

    public function RecaudoUsoAguaNorteZFI($proyecto, $ano, $mes, $dia){
        $sql = "SELECT SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND PD.CONCEPTO IN (1,3)
		AND A.ID_USO IN ('I')
		AND I.ZONA_FRANCA = 'S'
		";
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

    public function RecaudoUsoAlcNorte($proyecto, $ano, $mes, $dia){
        $sql = "SELECT NVL(U.DESC_USO,'Indefinido')USO, SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND I.SEC_ACTIVIDAD NOT IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		AND PD.CONCEPTO IN (2,4)
		GROUP BY U.DESC_USO
		ORDER BY U.DESC_USO
		";
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

    public function RecaudoUsoAlcNorteSolar($proyecto, $ano, $mes, $dia){
        $sql = "SELECT SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I
		AND P.ID_CAJA NOT IN (463,391) '
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND I.SEC_ACTIVIDAD IN (84,83)
		AND I.ZONA_FRANCA = 'N'
		AND PD.CONCEPTO IN (2,4)
		";
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

    public function RecaudoUsoAlcNorteZFC($proyecto, $ano, $mes, $dia){
        $sql = "SELECT SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I'
		AND P.ID_CAJA NOT IN (463,391) 
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND A.ID_USO IN ('C')
		AND I.ZONA_FRANCA = 'S'
		AND PD.CONCEPTO IN (2,4)
		";
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

    public function RecaudoUsoAlcNorteZFI($proyecto, $ano, $mes, $dia){
        $sql = "SELECT SUM(PD.PAGADO)RECAUDADO
		FROM SGC_TT_PAGOS P, SGC_TT_PAGO_DETALLEFAC PD, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TP_ACTIVIDADES A, SGC_TP_USOS U
		WHERE P.ID_PAGO = PD.PAGO 
		AND I.CODIGO_INM = P.INM_CODIGO
		AND I.ID_SECTOR = S.ID_SECTOR
		AND I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+)
		AND A.ID_USO = U.ID_USO(+)
		AND P.FECHA_PAGO BETWEEN TO_DATE('$ano-$mes-01 00:00:00','YYYY-MM-DD HH24:MI:SS')
		AND TO_DATE('$ano-$mes-$dia 23:59:59','YYYY-MM-DD HH24:MI:SS')
		AND P.ESTADO NOT IN 'I'
		AND P.ID_CAJA NOT IN (463,391) 
		AND I.ID_PROYECTO = '$proyecto'
		AND S.ID_GERENCIA IN ('N')
		AND A.ID_USO IN ('I')
		AND I.ZONA_FRANCA = 'S'
		AND PD.CONCEPTO IN (2,4)
		";
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

    public function RecaudoPorEntidad($proyecto, $fecini, $fecfin){
        $sql = "SELECT ENTIDAD_COD, DESC_ENTIDAD, SUM(IMPORTE)IMPORTE, SUM(CANTIDAD)CANTIDAD FROM(
			SELECT PP.ENTIDAD_COD, EP.DESC_ENTIDAD, SUM(P.IMPORTE)IMPORTE, COUNT(P.ID_PAGO)CANTIDAD
			FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO EP, SGC_TT_INMUEBLES I
			WHERE P.ID_CAJA = C.ID_CAJA
			AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
			AND EP.COD_ENTIDAD = PP.ENTIDAD_COD
			AND I.CODIGO_INM = P.INM_CODIGO
			AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND P.ESTADO NOT IN 'I'
			AND C.ID_CAJA NOT IN (463,391) 
			AND I.ID_PROYECTO = '$proyecto'
			GROUP BY PP.ENTIDAD_COD, EP.DESC_ENTIDAD
			UNION ALL
			SELECT PP.ENTIDAD_COD, EP.DESC_ENTIDAD, SUM(O.IMPORTE)IMPORTE, COUNT(O.CODIGO)CANTIDAD
			FROM SGC_TT_OTROS_RECAUDOS O, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO EP, SGC_TT_INMUEBLES I
			WHERE O.CAJA = C.ID_CAJA
			AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
			AND EP.COD_ENTIDAD = PP.ENTIDAD_COD
			AND I.CODIGO_INM = O.INMUEBLE
			AND FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
			AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
			AND ESTADO NOT IN 'I'
			AND O.CAJA NOT IN (463,391) 
			AND I.ID_PROYECTO = '$proyecto'
			GROUP BY PP.ENTIDAD_COD, EP.DESC_ENTIDAD
		)GROUP BY ENTIDAD_COD, DESC_ENTIDAD
		ORDER BY ENTIDAD_COD
		";
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

    public function RecaudoPorEntidadesAcea($proyecto, $fecini, $fecfin){
        $sql = "SELECT ID_PUNTO_PAGO, DESCRIPCION, SUM(IMPORTE)IMPORTE, SUM(CANTIDAD)CANTIDAD FROM(
            SELECT PP.ID_PUNTO_PAGO, PP.DESCRIPCION, SUM(P.IMPORTE)IMPORTE, COUNT(P.ID_PAGO)CANTIDAD
            FROM SGC_TT_PAGOS P, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO EP, SGC_TT_INMUEBLES I
            WHERE P.ID_CAJA = C.ID_CAJA
            AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
            AND EP.COD_ENTIDAD = PP.ENTIDAD_COD
            AND I.CODIGO_INM = P.INM_CODIGO
            AND P.FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND P.ESTADO NOT IN 'I'
            AND C.ID_CAJA NOT IN (463,391) 
            AND I.ID_PROYECTO = '$proyecto'
            AND PP.ENTIDAD_COD = 900
            GROUP BY  PP.ID_PUNTO_PAGO, PP.DESCRIPCION
            UNION ALL
            SELECT PP.ID_PUNTO_PAGO, PP.DESCRIPCION, SUM(O.IMPORTE)IMPORTE, COUNT(O.CODIGO)CANTIDAD
            FROM SGC_TT_OTROS_RECAUDOS O, SGC_TP_CAJAS_PAGO C, SGC_TP_PUNTO_PAGO PP, SGC_TP_ENTIDAD_PAGO EP, SGC_TT_INMUEBLES I
            WHERE O.CAJA = C.ID_CAJA
            AND PP.ID_PUNTO_PAGO = C.ID_PUNTO_PAGO
            AND EP.COD_ENTIDAD = PP.ENTIDAD_COD
            AND I.CODIGO_INM = O.INMUEBLE
            AND FECHA_PAGO BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
            AND TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')
            AND ESTADO NOT IN 'I'
            AND O.CAJA NOT IN (463,391) 
            AND I.ID_PROYECTO = '$proyecto'
            AND PP.ENTIDAD_COD = 900
            GROUP BY  PP.ID_PUNTO_PAGO, PP.DESCRIPCION
        )GROUP BY  ID_PUNTO_PAGO, DESCRIPCION
        ORDER BY ID_PUNTO_PAGO
		";
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