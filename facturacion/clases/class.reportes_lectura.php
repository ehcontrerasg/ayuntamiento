
<?php
include_once '../../clases/class.conexion.php';
class Reportes extends ConexionClass{
	private $id_proyecto;
	private $id_zonini;
	private $id_zonfin;
	private $id_lecini;
	//private $id_lecfin;
	private $id_perini;
	
	public function __construct()
	{
		parent::__construct();
		$this->id_proyecto="";
		$this->id_zonini="";
		$this->id_zonfin="";
		$this->id_lecini="";
		//$this->id_lecfin="";
		$this->id_perini="";
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
	
	public function seleccionaZonas ($user_input2){
		$sql = "SELECT ID_SECTOR 
	   	FROM SGC_TP_SECTORES
		WHERE ID_SECTOR LIKE UPPER('$user_input2%') ";

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

	public function seleccionaLectores ($user_input2){
		$sql = "SELECT ID_USUARIO, LOGIN
	   	FROM SGC_TT_USUARIOS
		WHERE LOGIN LIKE '$user_input2%' AND ID_CARGO = '6'
		ORDER BY LOGIN ASC";

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



    public function obtener_grupos (){
        $sql = "SELECT G.COD_GRUPO CODIGO, G.DESC_GRUPO DESCRIPCION FROM SGC_TP_GRUPOS G
                ORDER BY 2";

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

	
	public function seleccionaCantObservaciones($perini,$zonini,$zonfin,$lecini,$proyecto){
	    //$where = '';
		if($lecini != '') $where .= " AND R.COD_LECTOR_ORI = '$lecini'";
		if($zonini != '' && $zonfin == '') $zonfin = $zonini;
		if($zonini == '' && $zonfin != '') $zonini = $zonfin;
		if($zonini != '' && $zonfin != '') $where .= " AND R.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
		$sql = "SELECT COUNT(*)CANTIDAD FROM(
		SELECT R.OBSERVACION_ACTUAL
		FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TP_ZONAS Z 
		WHERE R.ID_ZONA = Z.ID_ZONA AND Z.ID_PROYECTO = '$proyecto' AND R.PERIODO = '$perini' AND R.OBSERVACION_ACTUAL  IS NOT NULL $where
		GROUP BY R.OBSERVACION_ACTUAL  ORDER BY R.OBSERVACION_ACTUAL)";
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



	
	
	public function seleccionaObservaciones($perini,$zonini,$zonfin,$lecini,$proyecto){
		if($lecini != '') $where .= " AND R.COD_LECTOR_ORI = '$lecini'";
		if($zonini != '' && $zonfin == '') $zonfin = $zonini;
		if($zonini == '' && $zonfin != '') $zonini = $zonfin;
		if($zonini != '' && $zonfin != '') $where .= " AND R.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
		$sql = "SELECT R.OBSERVACION_ACTUAL
		FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TP_ZONAS Z
		WHERE  R.ID_ZONA = Z.ID_ZONA AND Z.ID_PROYECTO = '$proyecto' 
		AND R.PERIODO = '$perini' $where AND R.OBSERVACION_ACTUAL  IS NOT NULL
		GROUP BY R.OBSERVACION_ACTUAL
		ORDER BY R.OBSERVACION_ACTUAL ";
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
	
	public function ObtieneZonasObslec($perini,$zonini,$zonfin,$lecini,$proyecto){
		if($lecini != '') $where .= " AND R.COD_LECTOR_ORI = '$lecini'";
		if($zonini != '' && $zonfin == '') $zonfin = $zonini;
		if($zonini == '' && $zonfin != '') $zonini = $zonfin;
		if($zonini != '' && $zonfin != '') $where .= " AND R.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
		$sql = "SELECT DISTINCT R.ID_ZONA
        FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TP_ZONAS Z
        WHERE  R.ID_ZONA = Z.ID_ZONA AND Z.ID_PROYECTO = '$proyecto' 
		AND R.PERIODO = '$perini' AND R.OBSERVACION_ACTUAL  IS NOT NULL $where
        GROUP BY R.ID_ZONA
        ORDER BY R.ID_ZONA";
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
	
	public function ObtieneCantLector($perini,$proyecto,$zona,$lecini){
		if($lecini != '') $where .= " AND R.COD_LECTOR = '$lecini'";
		$sql = "SELECT COUNT(*)CANTIDAD FROM(SELECT DISTINCT R.COD_LECTOR_ORI
        FROM SGC_TT_REGISTRO_LECTURAS R
        WHERE R.PERIODO = '$perini' AND R.ID_ZONA = '$zona' AND R.COD_LECTOR_ORI IS NOT NULL $where
        GROUP BY R.COD_LECTOR_ORI
        ORDER BY R.COD_LECTOR_ORI)";
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
	
	
	public function ObtieneLectorObslec($perini,$proyecto,$zona,$lecini){
		if($lecini != '') $where .= " AND R.COD_LECTOR_ORI = '$lecini'";
		$sql = "SELECT DISTINCT R.COD_LECTOR_ORI, U.NOM_USR, U.APE_USR, MAX(TO_CHAR(R.FECHA_LECTURA_ORI,'DD/MM/YYYY HH24:MI:SS'))FECMAXLEC
        FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TT_USUARIOS U
        WHERE R.COD_LECTOR_ORI = U.ID_USUARIO
		AND R.PERIODO = '$perini' AND R.OBSERVACION_ACTUAL  IS NOT NULL
		AND R.ID_ZONA = '$zona' $where
        GROUP BY R.COD_LECTOR_ORI, U.NOM_USR, U.APE_USR
        ORDER BY R.COD_LECTOR_ORI, U.NOM_USR, U.APE_USR";
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


    public function ObtieneModificaciones($perini,$zonini,$zonfin,$proyecto){
        $sql = "SELECT RL.COD_INMUEBLE, RL.ID_ZONA, RL.LECTURA_ORIGINAL, RL.OBSERVACION, (U.NOM_USR||' '||U.APE_USR)LECTOR_ORI, FECHA_LECTURA_ORI, RL.CONSUMO,
       RL.LECTURA_ACTUAL, RL.OBSERVACION_ACTUAL, RL.FECHA_LECTURA, RL.CONSUMO_ACT, (SELECT (U2.NOM_USR||' '||U2.APE_USR)
           FROM  SGC_TT_USUARIOS U2 WHERE U2.ID_USUARIO = RL.COD_LECTOR)MODIFICADOR
            FROM SGC_TT_REGISTRO_LECTURAS RL, SGC_TT_USUARIOS U
            WHERE PERIODO = $perini
              AND U.ID_USUARIO = RL.COD_LECTOR_ORI
              AND RL.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')
              AND LECTURA_ACTUAL <> LECTURA_ORIGINAL";
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


    public function ObtieneObservaciones($perini,$lector,$proyecto,$zona,$observacionop){
		if($lector != '') $where .= " AND R.COD_LECTOR_ORI = '$lector'";
		$sql = "SELECT OBSERVACION_ACTUAL , COUNT(R.OBSERVACION_ACTUAL )CANTIDAD
		FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TP_ZONAS Z
		WHERE Z.ID_ZONA = R.ID_ZONA
		AND Z.ID_PROYECTO = '$proyecto' AND R.OBSERVACION_ACTUAL  IS NOT NULL
		AND R.ID_ZONA = '$zona' AND R.OBSERVACION_ACTUAL  = '$observacionop'
		AND R.PERIODO = '$perini' $where
		GROUP BY OBSERVACION_ACTUAL
		ORDER BY OBSERVACION_ACTUAL ";
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
	
	
	public function ObtieneTotalesObservacion($perini,$proyecto,$observacionop,$zonini,$zonfin,$lecini){
		if($lecini != '') $where .= " AND R.COD_LECTOR_ORI = '$lecini'";
		if($zonini != '' && $zonfin == '') $zonfin = $zonini;
		if($zonini == '' && $zonfin != '') $zonini = $zonfin;
		if($zonini != '' && $zonfin != '') $where .= " AND R.ID_ZONA BETWEEN UPPER('$zonini') AND UPPER('$zonfin')";
		$sql = "SELECT COUNT(R.OBSERVACION_ACTUAL )CANTIDAD
        FROM SGC_TT_REGISTRO_LECTURAS R, SGC_TP_ZONAS Z
        WHERE  R.ID_ZONA = Z.ID_ZONA AND Z.ID_PROYECTO = '$proyecto'
		AND R.OBSERVACION_ACTUAL  = '$observacionop' AND R.OBSERVACION_ACTUAL  IS NOT NULL
		AND R.PERIODO = '$perini'
		$where ";
		//echo $sql.'<br>';
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
	
	public function obtieneUsos (){
		$sql = "SELECT ID_USO, DESC_USO
		FROM SGC_TP_USOS
		WHERE VISIBLE = 'S'
		ORDER BY DESC_USO";
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
	
	public function obtieneConceptos (){
		$sql = "SELECT COD_SERVICIO, DESC_SERVICIO
		FROM SGC_TP_SERVICIOS
		ORDER BY DESC_SERVICIO";
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


    public function seleccionaCabeceraNCF (){
        $sql = "SELECT DISTINCT ID_NCF
        FROM SGC_TP_NCF_USOS
        WHERE PROYECTO IN ('SD','BC')
        AND PERIODO_INI >= 201805
        ORDER BY ID_NCF";
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

    public function seleccionaFormatos (){
        $sql = "SELECT CODFORMATO, DESCFORMATO
        FROM SGC_TP_FORMATOS_DGII
        ORDER BY CODFORMATO";
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
