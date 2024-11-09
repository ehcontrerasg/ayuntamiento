<?php
include_once '../../clases/class.conexion.php';
class ReportesDifCortes extends ConexionClass{
	private $id_proyecto;
	private $id_fecini;
	private $id_fecfin;
	
	public function __construct()
	{
		parent::__construct();
		$this->id_proyecto="";
		$this->id_fecini="";
		$this->id_fecfin="";
	}

	
	public function DatosDiferidosCorte($proyecto, $fecini, $fecfin,$start='',$end=''){
        if($start!=""||$end!="") {
            $sql = "select * from (
              select a.*, ROWNUM rnum from (SELECT D.CODIGO, TO_CHAR(D.FECHA_APERTURA,'DD/MM/YYYY')FECPAGO, D.CONCEPTO, S.DESC_SERVICIO, M.ID_FORM_PAGO, F.DESCRIPCION, U.LOGIN, D.VALOR_DIFERIDO, D.INMUEBLE
				FROM SGC_TT_DIFERIDOS D, SGC_TT_OTROS_RECAUDOS R, SGC_TT_MEDIOS_RECAUDO M, SGC_TP_SERVICIOS S, 
				SGC_TP_FORMA_PAGO F, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I
				WHERE I.CODIGO_INM = D.INMUEBLE 
				AND S.COD_SERVICIO = D.CONCEPTO 
				AND U.ID_USUARIO = D.USUARIO_CREACION
				AND F.CODIGO = M.ID_FORM_PAGO
				AND R.INMUEBLE = D.INMUEBLE 
				AND M.ID_OTRREC = R.CODIGO
				AND D.ACTIVO = 'S' 
				AND I.ID_PROYECTO = '$proyecto'
				AND D.FECHA_APERTURA BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
				AND  TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')) a   where rownum <=$start) where rnum >=$end+1";
        } else {
            $sql = "select * from (
              select a.*, ROWNUM rnum from (SELECT D.CODIGO, TO_CHAR(D.FECHA_APERTURA,'DD/MM/YYYY')FECPAGO, D.CONCEPTO, S.DESC_SERVICIO, M.ID_FORM_PAGO, F.DESCRIPCION, U.LOGIN, D.VALOR_DIFERIDO, D.INMUEBLE
				FROM SGC_TT_DIFERIDOS D, SGC_TT_OTROS_RECAUDOS R, SGC_TT_MEDIOS_RECAUDO M, SGC_TP_SERVICIOS S, 
				SGC_TP_FORMA_PAGO F, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I
				WHERE I.CODIGO_INM = D.INMUEBLE 
				AND S.COD_SERVICIO = D.CONCEPTO 
				AND U.ID_USUARIO = D.USUARIO_CREACION
				AND F.CODIGO = M.ID_FORM_PAGO
				AND R.INMUEBLE = D.INMUEBLE 
				AND M.ID_OTRREC = R.CODIGO
				AND D.ACTIVO = 'S' 
				AND I.ID_PROYECTO = '$proyecto'
				AND D.FECHA_APERTURA BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
				AND  TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')) a where rownum >=0  )  where rnum >=0";
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

	public function cantidadDiferidosCorte($proyecto, $fecini, $fecfin){
            $sql = "select count (*) from (SELECT D.CODIGO, TO_CHAR(D.FECHA_APERTURA,'DD/MM/YYYY')FECPAGO, D.CONCEPTO, S.DESC_SERVICIO, M.ID_FORM_PAGO, F.DESCRIPCION, U.LOGIN, D.VALOR_DIFERIDO, D.INMUEBLE
				FROM SGC_TT_DIFERIDOS D, SGC_TT_OTROS_RECAUDOS R, SGC_TT_MEDIOS_RECAUDO M, SGC_TP_SERVICIOS S, 
				SGC_TP_FORMA_PAGO F, SGC_TT_USUARIOS U, SGC_TT_INMUEBLES I
				WHERE I.CODIGO_INM = D.INMUEBLE 
				AND S.COD_SERVICIO = D.CONCEPTO 
				AND U.ID_USUARIO = D.USUARIO_CREACION
				AND F.CODIGO = M.ID_FORM_PAGO
				AND R.INMUEBLE = D.INMUEBLE 
				AND M.ID_OTRREC = R.CODIGO
				AND D.ACTIVO = 'S' 
				AND I.ID_PROYECTO = '$proyecto'
				AND D.FECHA_APERTURA BETWEEN TO_DATE('$fecini 00:00:00','YYYY-MM-DD HH24:MI:SS')
				AND  TO_DATE('$fecfin 23:59:59','YYYY-MM-DD HH24:MI:SS')) ";

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
	
