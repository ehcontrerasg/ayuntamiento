<?php

include_once '../../clases/class.conexion.php';

class Reportes_Rec_Ruta extends ConexionClass{
	

		
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

	public function obtieneRutasCantidadRec($proyecto,$secini,$perini,$motivo,$diagnostico)
	{
        if($diagnostico == '') $where = "";
        else if($diagnostico == '0') $where = " P.DIAGNOSTICO IS NULL AND";
	    else $where = "P.DIAGNOSTICO IN ($diagnostico) AND";
	    if($motivo == 10 || $motivo == 41) {
            $sql="SELECT I.ID_SECTOR, I.ID_RUTA, P.COD_INMUEBLE, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS')FECHA_RECLAMO, P.DESCRIPCION,
            TO_CHAR((SELECT MAX(REF.FECHA_ASIGNA) FROM SGC_TT_REGISTRO_ENTREGA_FAC REF WHERE REF.COD_INMUEBLE = P.COD_INMUEBLE AND REF.FECHA_ASIGNA < P.FECHA_PQR),'DD/MM/YYYY HH24:MI:SS')ULTIMA_FECHA_ASIGNA,
            (SELECT MAX(U.NOM_USR||' '||U.APE_USR) FROM SGC_TT_REGISTRO_ENTREGA_FAC REF, SGC_TT_USUARIOS U WHERE REF.COD_INMUEBLE = P.COD_INMUEBLE AND REF.FECHA_ASIGNA < P.FECHA_PQR AND REF.USR_EJE = U.ID_USUARIO 
            AND REF.FECHA_ASIGNA = (SELECT MAX(RF.FECHA_ASIGNA) FROM SGC_TT_REGISTRO_ENTREGA_FAC RF WHERE RF.COD_INMUEBLE = P.COD_INMUEBLE AND RF.FECHA_ASIGNA < P.FECHA_PQR))ASIGNADO_A,
            TO_CHAR((SELECT MAX(REF.FECHA_EJECUCION) FROM SGC_TT_REGISTRO_ENTREGA_FAC REF WHERE REF.COD_INMUEBLE = P.COD_INMUEBLE AND REF.FECHA_EJECUCION < P.FECHA_PQR),'DD/MM/YYYY HH24:MI:SS')ULTIMA_FECHA_ENTREGA,
            (SELECT MAX(U.NOM_USR||' '||U.APE_USR) FROM SGC_TT_REGISTRO_ENTREGA_FAC REF, SGC_TT_USUARIOS U WHERE REF.COD_INMUEBLE = P.COD_INMUEBLE AND REF.FECHA_EJECUCION < P.FECHA_PQR AND REF.USR_EJE = U.ID_USUARIO 
            AND REF.FECHA_EJECUCION = (SELECT MAX(RF.FECHA_EJECUCION) FROM SGC_TT_REGISTRO_ENTREGA_FAC RF WHERE RF.COD_INMUEBLE = P.COD_INMUEBLE AND RF.FECHA_EJECUCION < P.FECHA_PQR))ENTREGADO_POR 
            FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
            WHERE I.CODIGO_INM = P.COD_INMUEBLE AND 
            P.MOTIVO_PQR = $motivo AND 
            I.ID_SECTOR LIKE ('$secini%') AND
            TO_CHAR(P.FECHA_REGISTRO,'YYYYMM') = $perini AND
            $where
            I.ID_PROYECTO = '$proyecto'
            ORDER BY I.ID_SECTOR, I.ID_RUTA";
	    }
	    else{
            $sql="SELECT I.ID_SECTOR, I.ID_RUTA, P.COD_INMUEBLE, TO_CHAR(P.FECHA_PQR,'DD/MM/YYYY HH24:MI:SS')FECHA_RECLAMO, P.DESCRIPCION,
            TO_CHAR((SELECT MAX(REF.FECHA_ASIGNACION) FROM SGC_TT_REGISTRO_LECTURAS REF WHERE REF.COD_INMUEBLE = P.COD_INMUEBLE AND REF.FECHA_ASIGNACION < P.FECHA_PQR),'DD/MM/YYYY HH24:MI:SS')ULTIMA_FECHA_ASIGNA,
            (SELECT MAX(U.NOM_USR||' '||U.APE_USR) FROM SGC_TT_REGISTRO_LECTURAS REF, SGC_TT_USUARIOS U WHERE REF.COD_INMUEBLE = P.COD_INMUEBLE AND REF.FECHA_ASIGNACION < P.FECHA_PQR AND REF.COD_LECTOR_ORI = U.ID_USUARIO 
            AND REF.FECHA_ASIGNACION = (SELECT MAX(RF.FECHA_ASIGNACION) FROM SGC_TT_REGISTRO_LECTURAS RF WHERE RF.COD_INMUEBLE = P.COD_INMUEBLE AND RF.FECHA_ASIGNACION < P.FECHA_PQR))ASIGNADO_A,
            TO_CHAR((SELECT MAX(REF.FECHA_LECTURA_ORI) FROM SGC_TT_REGISTRO_LECTURAS REF WHERE REF.COD_INMUEBLE = P.COD_INMUEBLE AND REF.FECHA_LECTURA_ORI < P.FECHA_PQR),'DD/MM/YYYY HH24:MI:SS')ULTIMA_FECHA_ENTREGA,
            (SELECT MAX(U.NOM_USR||' '||U.APE_USR) FROM SGC_TT_REGISTRO_LECTURAS REF, SGC_TT_USUARIOS U WHERE REF.COD_INMUEBLE = P.COD_INMUEBLE AND REF.FECHA_LECTURA_ORI < P.FECHA_PQR AND REF.COD_LECTOR_ORI = U.ID_USUARIO 
            AND REF.FECHA_LECTURA_ORI = (SELECT MAX(RF.FECHA_LECTURA_ORI) FROM SGC_TT_REGISTRO_LECTURAS RF WHERE RF.COD_INMUEBLE = P.COD_INMUEBLE AND RF.FECHA_LECTURA_ORI < P.FECHA_PQR))ENTREGADO_POR 
            FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
            WHERE I.CODIGO_INM = P.COD_INMUEBLE AND 
            P.MOTIVO_PQR = $motivo AND 
            I.ID_SECTOR LIKE ('$secini%') AND
            TO_CHAR(P.FECHA_REGISTRO,'YYYYMM') = $perini AND
            $where
            I.ID_PROYECTO = '$proyecto'
            ORDER BY I.ID_SECTOR, I.ID_RUTA";
        }
	
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

    public function obtieneCantidadRecOperario($proyecto,$secini,$perini,$motivo,$diagnostico)
    {
        if($diagnostico == '') $where = "";
        else if($diagnostico == '0') $where = " P.DIAGNOSTICO IS NULL AND";
        else $where = "P.DIAGNOSTICO IN ($diagnostico) AND";
        if($motivo == 10 || $motivo == 41) {
            $sql = " SELECT  NVL(OPERARIO,'NO EJECUTADO')OPERARIO, COUNT(NVL(CANTIDAD,0))CANTIDAD FROM(  SELECT (SELECT MAX(U.NOM_USR||' '||U.APE_USR) FROM SGC_TT_REGISTRO_ENTREGA_FAC REF, SGC_TT_USUARIOS U WHERE REF.COD_INMUEBLE = P.COD_INMUEBLE AND REF.FECHA_ASIGNA < P.FECHA_PQR AND REF.USR_EJE = U.ID_USUARIO 
            AND REF.FECHA_ASIGNA = (SELECT MAX(RF.FECHA_ASIGNA) FROM SGC_TT_REGISTRO_ENTREGA_FAC RF WHERE RF.COD_INMUEBLE = P.COD_INMUEBLE AND RF.FECHA_ASIGNA < P.FECHA_PQR)) OPERARIO ,
            (SELECT MAX(U.NOM_USR||' '||U.APE_USR) FROM SGC_TT_REGISTRO_ENTREGA_FAC REF, SGC_TT_USUARIOS U WHERE REF.COD_INMUEBLE = P.COD_INMUEBLE AND REF.FECHA_ASIGNA < P.FECHA_PQR AND REF.USR_EJE = U.ID_USUARIO 
            AND REF.FECHA_ASIGNA = (SELECT MAX(RF.FECHA_ASIGNA) FROM SGC_TT_REGISTRO_ENTREGA_FAC RF WHERE RF.COD_INMUEBLE = P.COD_INMUEBLE AND RF.FECHA_ASIGNA < P.FECHA_PQR))CANTIDAD
            FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
            WHERE I.CODIGO_INM = P.COD_INMUEBLE AND 
            P.MOTIVO_PQR = $motivo AND 
            I.ID_SECTOR LIKE ('$secini%') AND
            TO_CHAR(P.FECHA_REGISTRO,'YYYYMM') = $perini AND
            $where
            I.ID_PROYECTO = '$proyecto')
            GROUP BY OPERARIO 
            ORDER BY 2 DESC";
        }
        else{
            $sql = "SELECT  NVL(OPERARIO,'NO EJECUTADO')OPERARIO, COUNT(NVL(CANTIDAD,0))CANTIDAD FROM(  SELECT (SELECT MAX(U.NOM_USR||' '||U.APE_USR) FROM SGC_TT_REGISTRO_LECTURAS REF, SGC_TT_USUARIOS U WHERE REF.COD_INMUEBLE = P.COD_INMUEBLE AND REF.FECHA_ASIGNACION < P.FECHA_PQR AND REF.COD_LECTOR_ORI = U.ID_USUARIO 
            AND REF.FECHA_ASIGNACION = (SELECT MAX(RF.FECHA_ASIGNACION) FROM SGC_TT_REGISTRO_LECTURAS RF WHERE RF.COD_INMUEBLE = P.COD_INMUEBLE AND RF.FECHA_ASIGNACION < P.FECHA_PQR)) OPERARIO,  
            (SELECT MAX(U.NOM_USR||' '||U.APE_USR) FROM SGC_TT_REGISTRO_LECTURAS REF, SGC_TT_USUARIOS U WHERE REF.COD_INMUEBLE = P.COD_INMUEBLE AND REF.FECHA_ASIGNACION < P.FECHA_PQR AND REF.COD_LECTOR_ORI = U.ID_USUARIO 
            AND REF.FECHA_ASIGNACION = (SELECT MAX(RF.FECHA_ASIGNACION) FROM SGC_TT_REGISTRO_LECTURAS RF WHERE RF.COD_INMUEBLE = P.COD_INMUEBLE AND RF.FECHA_ASIGNACION < P.FECHA_PQR))CANTIDAD
            FROM SGC_TT_PQRS P, SGC_TT_INMUEBLES I
            WHERE I.CODIGO_INM = P.COD_INMUEBLE AND 
            P.MOTIVO_PQR = $motivo AND 
            I.ID_SECTOR LIKE ('$secini%') AND
            TO_CHAR(P.FECHA_REGISTRO,'YYYYMM') = $perini AND
            $where
            I.ID_PROYECTO = '$proyecto')
            GROUP BY OPERARIO 
            ORDER BY 2 DESC";
        }

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

    public function obtieneMotivosReclamosFac()
    {
        $sql="SELECT R.ID_MOTIVO_REC, R.DESC_MOTIVO_REC
         FROM SGC_TP_MOTIVO_RECLAMOS R
         WHERE R.AREA_PERTENECE = 2 AND GERENCIA = 'N'
         AND R.ID_TIPO_RECLAMO = 1
         ORDER BY R.ID_MOTIVO_REC";

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

    public function obtieneDiagnosticoReclamo()
    {
        $sql="SELECT R.ID_DIAGNOSTICO, R.DESC_DIAGNOSTICO
         FROM SGC_TP_DIAGNOSTICOS_PQR R";

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