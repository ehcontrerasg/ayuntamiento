<?php
if (file_exists('../../clases/class.conexion.php')) {
    include_once '../../clases/class.conexion.php';}

/*if (file_exists('../clases/class.conexion.php')) {
    include_once '../clases/class.conexion.php';}*/


class Inmnueble extends ConexionClass{
	Private $cod_inm;
	Private $cod_inm2;
	Private $id_zona;
	Private $id_sector;
	Private $id_ciclo;
	Private $id_ruta;
	Private $consec_urbanizacion;
	Private $id_proyecto;
	Private $fech_sol_alta;
	Private $fech_alta;
	Private $fech_sol_baja;
	Private $fech_baja;
	Private $direccion;
	Private $id_tipo_cliente;
	Private $id_estado;
	Private $codigo_cli;
	Private $clasificacion;
	Private $per_alta;
	Private $per_baja;
	Private $catastro;
	Private $facturar;
	Private $id_proceso;
	Private $contrato;
	Private $telefono;
	Private $sec_actividad;
	Private $total_unidades;
	Private $unidades_hab;
	Private $unidades_des;
	Private $fecha_creacion;
	Private $usuario_creacion;
	private $calculamora;
	Private $fianza;
	private $fianza_pagada;
	private $tipovia;
	private $calle;
	private $numcalle;
	private $subnum;
	private $bloque;
	private $apartamento;
	private $complemento;
    private $codresult;
    private $msgresult;

    public function __construct()
	{
		parent::__construct();
		$this->cod_inm="";
		$this->cod_inm2="";
		$this->id_zona="";
		$this->id_sector="";
		$this->id_ciclo="";
		$this->id_ruta="";
		$this->consec_urbanizacion="";
		$this->id_proyecto="";
		$this->fech_sol_alta="";
		$this->fech_alta="";
		$this->fech_sol_baja="";
		$this->fech_baja="";
		$this->direccion="";
		$this->id_tipo_cliente="";
		$this->id_estado="";
		$this->codigo_cli="";
		$this->clasificacion="";
		$this->per_alta="";
		$this->per_baja="";
		$this->catastro="";
		$this->facturar="";
		$this->id_proceso="";
		$this->contrato="";
		$this->telefono="";
		$this->sec_actividad="";
		$this->total_unidades="";
		$this->unidades_hab="";
		$this->unidades_des="";
		$this->fecha_creacion="";
		$this->usuario_creacion="";
		$this->calculamora="S";
		$this->fianza="";
		$this->fianza_pagada="S";
		$this->tipovia="";
		$this->calle="";
		$this->numcalle="";
		$this->subnum="";
		$this->bloque="";
		$this->apartamento="";
		$this->complemento="";
		$this->codresult="";
		$this->msgresult="";


	}
	
	public function getMsError(){
		return $this->msgresult;
	}
	
	public function getCodRes(){
		return $this->codresult;
	}
	

    public function setcod_inm($valor){
		$this->cod_inm=$valor;
	}
	
	public function setfianza($valor){
		$this->fianza=$valor;
	}
	
	public function setfianza_pag($valor){
		$this->fianza_pagada=$valor;
	}
	
	public function setcod_inm2($valor){
		$this->cod_inm2=$valor;
	}
	public function setid_zona($valor){
		$this->id_zona=$valor;
	}
	public function setid_sector($valor){
		$this->id_sector=$valor;
	}
	public function setid_ciclo($valor){
		$this->id_ciclo=$valor;
	}
	public function setid_ruta($valor){
		if (strlen($valor)==1){
			$this->id_ruta="0".$valor;
		}else{
			$this->id_ruta=$valor;
		}
		
	}

	public function seturbanizacion($valor){
		if ($valor==''){
			$this->consec_urbanizacion=9999;
		}else{
			$this->consec_urbanizacion=$valor;
		}
	}
	public function setid_proyecto($valor){
		$this->id_proyecto=$valor;
	}
	public function setfech_sol_alta($valor){
		$this->fech_sol_alta=$valor;
	}
	public function setfech_alta($valor){
		$this->fech_alta=$valor;
	}
	public function setsol_fech_baja($valor){
		$this->fech_sol_baja=$valor;
	}
	public function setfech_baja($valor){
		$this->fech_baja=$valor;
	}
	public function setdireccion($valor){
		$this->direccion=$valor;
	}
	public function setid_tipo_cli($valor){
		$this->id_tipo_cliente=$valor;
	}	
	public function setid_estado($valor){
		$this->id_estado=$valor;
	}
	public function setcod_cli($valor){
		$this->codigo_cli=$valor;
	}
	public function setclasificacion($valor){
		$this->clasificacion=$valor;
	}
	public function setper_alta($valor){
		$this->per_alta=$valor;
	}
	public function setper_baja($valor){
		$this->per_baja=$valor;
	}
	public function setcatastro($valor){
		$this->catastro=$valor;
	}
	public function setfacturar($valor){
		$this->facturar=$valor;
	}
	public function setid_proceso($valor){
		$this->id_proceso=$valor;
	}
	public function setcontrato($valor){
		$this->contrato=$valor;
	}
	public function settelefono($valor){
		$this->telefono=$valor;
	}
	public function setsec_actividad($valor){
		$this->sec_actividad=$valor;
	}
	public function settotal_unidades($valor){
		$this->total_unidades=$valor;
	}
	public function setunidades_hab($valor){
		$this->unidades_hab=$valor;
	}
	public function setunidades_des($valor){
		$this->unidades_des=$valor;
	}
	public function setfecha_creacion($valor){
		$this->fecha_creacion=$valor;
	}
	public function setusuario_creacion($valor){
		$this->usuario_creacion=$valor;
	}
	
	
	
	public function settipovia($valor){
		if($valor==""){
			$this->tipovia="";
		}else{
			$this->tipovia=$valor;
		}
	}
	public function setnomvia($valor){
	if($valor==""){
			$this->calle="";
		}else{
			$this->calle=$valor;
		}
	}
	public function setnumero($valor){
	if($valor==""){
			$this->numcalle="";
		}else{
			$this->numcalle=$valor;
		}
	}
	public function setsubnum($valor){
	if($valor==""){
			$this->subnum="";
		}else{
			$this->subnum=$valor;
		}
	}
	public function setbloque($valor){
		$this->bloque=$valor;
	}
	public function setapartamento($valor){
		$this->apartamento=$valor;
	}
	public function setcomplemento($valor){
		$this->complemento=$valor;
	}




	public function NuevoInmueble($zonaf){
		
		
		 echo $sql= "BEGIN SGC_P_CREA_INM('$this->cod_inm','$this->id_zona','$this->id_sector','$this->id_ciclo','$this->id_ruta',$this->consec_urbanizacion,'$this->id_proyecto','$this->direccion','$this->id_tipo_cliente','$this->catastro','$this->facturar','$this->id_proceso','$this->telefono','$this->sec_actividad','$this->total_unidades','$this->unidades_hab','$this->unidades_des','$this->usuario_creacion',$this->fianza,$this->fianza_pagada,'$this->tipovia','$this->calle','$this->numcalle','$this->subnum','$this->bloque','$this->apartamento','$this->complemento','$zonaf',:CODERR,:MSERR);COMMIT;END;";
		$resultado=oci_parse($this->_db,$sql);
		//ECHO "BEGIN ACEA. SGC_P_CREA_INM('$this->cod_inm','$this->id_zona','$this->id_sector','$this->id_ciclo','$this->id_ruta',$this->consec_urbanizacion,'$this->id_proyecto','$this->direccion','$this->id_tipo_cliente','$this->id_estado','$this->per_alta','$this->catastro','$this->facturar','$this->id_proceso','$this->telefono','$this->sec_actividad','$this->total_unidades','$this->unidades_hab','$this->unidades_des','$this->usuario_creacion',$this->fianza,$this->fianza_pagada,$this->tipovia,'$this->calle',$this->numcalle,$this->subnum,'$this->bloque','$this->apartamento','$this->complemento',:PCODRESULT,:PMSGRESULT);COMMIT;END;";
		oci_bind_by_name($resultado,':CODERR',$this->codresult,"123");
		oci_bind_by_name($resultado,':MSERR',$this->msgresult,1000);

		$bandera=oci_execute($resultado);
		if($bandera)
		{
			if ($this->codresult>0)
			{
				oci_close($this->_db);
				return false;
			}
			else{
				oci_close($this->_db);
				return true;
			}
		}else{
			oci_close($this->_db);
			return false;
		}
        return true;
	}
	
	public function inmespecifico($cod){
		$resultado = oci_parse($this->_db,"SELECT 
    I.CODIGO_INM,
    I.TELEFONO, 
    P.SIGLA_PROYECTO, 
    I.CATASTRO,
    U.CONSEC_URB,
    I.COD_DIAMETRO,
    P.ID_PROYECTO, 
    A.DESC_ACTIVIDAD , 
    I.ID_ZONA, 
    I.ID_RUTA, 
    I.ID_SECTOR, 
    U.DESC_URBANIZACION, 
    I.DIRECCION, 
    I.ID_ESTADO , 
    I.ID_PROCESO, 
    A.ID_USO, 
    I.TOTAL_UNIDADES, 
    I.UNIDADES_HAB, 
    I.UNIDADES_DES, 
    I.PER_ALTA, 
    I.SEC_ACTIVIDAD, 
    USO.DESC_USO, 
    I.CALLE, 
    I.NOM_CALLE,
    I.NUMERO, 
    I.SUB_NUMERO, 
    I.BLOQUE,
    I.ID_TIPO_CLIENTE,
    I.APARTAMENTO, 
    I.COMPLEMENTO,
    I.FIANZA_PAGA,
    NVL(C.DOCUMENTO,'SIN DOCUMENTO') DOCUMENTO, 
    NVL(NVL(ALIAS,NOMBRE_CLI),'SIN CONTRATO ASICIADO') ALIAS,
    NVL(C.CODIGO_CLI,9999999) CODIGO_CLI,
    O.GRUPO GRUPO,
    i.ZONA_FRANCA 
FROM 
    SGC_TT_INMUEBLES I, 
    SGC_TP_URBANIZACIONES U,
    SGC_TP_PROYECTOS P,  
    SGC_TP_USOS USO,  
    SGC_TP_ACTIVIDADES A, 
    SGC_TT_CLIENTES C, 
    SGC_TP_ESTADOS_INMUEBLES E, 
    SGC_TT_CONTRATOS O,
    SGC_TP_GRUPOS G
    WHERE I.CONSEC_URB = U.CONSEC_URB(+) AND
    I.SEC_ACTIVIDAD = A.SEC_ACTIVIDAD(+) AND 
    P.ID_PROYECTO(+)=I.ID_PROYECTO  AND 
    C.CODIGO_CLI(+) = O.CODIGO_CLI AND 
    G.COD_GRUPO(+)=C.COD_GRUPO AND            
    I.CODIGO_INM = O.CODIGO_INM(+) AND 
    I.ID_ESTADO = E.ID_ESTADO(+) AND 
    I.CODIGO_INM='$cod' AND 
    A.ID_USO=USO.ID_USO(+) AND 
    O.FECHA_FIN (+)IS NULL
	 AND C.CODIGO_CLI(+) <> 9999999");
		
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
	

	
	

	public function existe ($id){
		$resultado = oci_parse($this->_db,"SELECT * FROM SGC_TT_INMUEBLES WHERE CODIGO_INM='$id'");	
	
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
	
	
public function siguiente_cod (){
		$resultado = oci_parse($this->_db,"SELECT MAX(CODIGO_INM)CODIGO_INM FROM SGC_TT_INMUEBLES ");	
	
		$banderas=oci_execute($resultado);
		oci_fetch($resultado);
		$codigo_inm = oci_result($resultado, 'CODIGO_INM');
		$codigo_inm+=1;
		if($banderas==TRUE)
		{
			return $codigo_inm;
		}
		else
		{

			echo "false";
			return false;
		}
	
	}



    public function actualizar_inmueble($fianza,$diametro,$tipoCliente,$grupo,$zona_fra){
        if($this->id_sector==60 || $this->id_sector==52){
            $tipocli='GC';
        }ELSE{
            $tipocli='CN';
        }
        echo $sql="BEGIN SGC_P_ACT_INM ('$tipoCliente','$this->id_sector','$this->id_ruta','$this->id_zona', '$this->id_ciclo',$this->consec_urbanizacion,'$this->catastro','$this->id_proceso','$this->per_alta','$this->usuario_creacion','$this->tipovia','$this->calle','$this->numcalle','$this->subnum','$this->bloque','$this->apartamento','$this->complemento','$this->id_estado','$this->telefono',$this->cod_inm ,'$fianza','$diametro','$grupo','$zona_fra',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado=oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,':PCODRESULT',$this->codresult,2);
        oci_bind_by_name($resultado,':PMSGRESULT',$this->msgresult,1000);
        $bandera=oci_execute($resultado);
        if($bandera){
            if($this->codresult==0){
                oci_close($this->_db);
                return true;
            }
            else{
				echo $this->msgresult;
                oci_close($this->_db);
                return false;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }

	public function Eliminar_Inmueble(){
		$resultado = oci_parse($this->_db,"DELETE FROM SGC_TT_INMUEBLES WHERE CODIGO_INM IN ($this->cod_inm)");
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
	
	
	public function CambioUb(){
	
		$resultado=oci_parse($this->_db," BEGIN  SGC_P_CAMBIO_UBICACION('$this->cod_inm','$this->cod_inm2','$this->usuario_creacion',:PMSGRESULT,:PCODRESULT);COMMIT;END;");
		oci_bind_by_name($resultado,':PCODRESULT',$codresult,1000);
		oci_bind_by_name($resultado,':PMSGRESULT',$msgresult,1000);
		$bandera=oci_execute($resultado);
		if($bandera=TRUE && $codresult==0 ){
			oci_close($this->_db);
			return $codresult;
		}else{
			oci_close($this->_db);
			echo "$msgresult: $codresult";
			return $codresult;
		}
	}
	
	
	public function obtenerProy($codigoInm){
	
		$resultado = oci_parse($this->_db,"SELECT I.ID_PROYECTO FROM SGC_TT_INMUEBLES I WHERE CODIGO_INM=$codigoInm");	
		$banderas=oci_execute($resultado);
		if($banderas==TRUE)
		{
			oci_fetch($resultado);
			$proyecto= oci_result($resultado, 'ID_PROYECTO') ;
			oci_close($this->_db);
			return $proyecto;
		}
		else
		{
			oci_close($this->_db);
			echo "false";
			return false;
		}
	}


    public function obtenerInmueblesFacturadosPerdiodo($periodo,$proyecto){
        $sql="SELECT
                I.CODIGO_INM INMUEBLE,
                trim(RTRIM(LTRIM(NVL(CL.NOMBRE_CLI,CL.NOMBRE_CLI||'('||trim(C.ALIAS)||')'),'('),')')) NOMBRE,
                I.ID_PROCESO PROCESO
              FROM
                SGC_TT_INMUEBLES I,
                SGC_TT_FACTURA F,
                SGC_TT_CONTRATOS C,
                SGC_TT_CLIENTES CL
              WHERE
                F.INMUEBLE=I.CODIGO_INM AND
                I.CODIGO_INM=C.CODIGO_INM(+) AND
                C.CODIGO_CLI=CL.CODIGO_CLI(+) AND
                CL.CODIGO_CLI(+) <> 9999999 AND
                C.FECHA_FIN (+) IS NULL AND
                I.ID_PROYECTO='$proyecto' AND
                F.PERIODO=$periodo
              ORDER BY
                trim(RTRIM(LTRIM(NVL(CL.NOMBRE_CLI,CL.NOMBRE_CLI||'('||trim(C.ALIAS)||')'),'('),')'))";

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



    public function ActulizarTel($tel,$inm){
        echo $sql="UPDATE
                SGC_TT_INMUEBLES C
              SET
                C.TELEFONO='$tel'
              WHERE
                C.CODIGO_INM='$inm'";
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


    public function obtenerInmueblesCatastrados($proyecto){
        $sql="SELECT
                I.CODIGO_INM INMUEBLE,
                trim(RTRIM(LTRIM(NVL(CL.NOMBRE_CLI,CL.NOMBRE_CLI||'('||trim(C.ALIAS)||')'),'('),')')) NOMBRE,
                I.ID_PROCESO PROCESO
              FROM
                SGC_TT_INMUEBLES I,
                SGC_TT_CONTRATOS C,
                SGC_TT_CLIENTES CL
              WHERE
                I.CODIGO_INM=C.CODIGO_INM(+) AND
                C.CODIGO_CLI=CL.CODIGO_CLI(+) AND
                CL.CODIGO_CLI(+) <> 9999999 AND
                C.FECHA_FIN (+) IS NULL AND
                I.ID_PROYECTO='$proyecto'
              ORDER BY
                RTRIM(LTRIM(NVL(CL.NOMBRE_CLI,CL.NOMBRE_CLI||'('||C.ALIAS||')'),'('),')')";

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

    public function obtenerCantInmCatGer($proyecto,$gerencia){
        $sql="SELECT
                COUNT(1) CANTIDAD
              FROM
                SGC_TT_INMUEBLES I,
                SGC_TP_SECTORES S
              WHERE
                S.ID_SECTOR=I.ID_SECTOR AND
                S.ID_PROYECTO= I.ID_PROYECTO AND
                S.ID_GERENCIA='$gerencia' AND
                I.ID_PROYECTO='$proyecto'";

        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            oci_fetch($resultado);
            $cantidad=oci_result($resultado,"CANTIDAD");
            return $cantidad;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }



    public function obtenerCantInmFacGer($proyecto,$gerencia,$periodo){
        $sql="SELECT
                COUNT(1) CANTIDAD
              FROM
                SGC_TT_INMUEBLES I,
                SGC_TT_FACTURA F,
                SGC_TP_SECTORES S
              WHERE
                F.INMUEBLE=I.CODIGO_INM AND
                F.PERIODO=$periodo AND
                S.ID_SECTOR=I.ID_SECTOR AND
                S.ID_PROYECTO= I.ID_PROYECTO AND
                S.ID_GERENCIA='$gerencia' AND
                I.ID_PROYECTO='$proyecto'";
        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            oci_fetch($resultado);
            $cantidad=oci_result($resultado,"CANTIDAD");
            return $cantidad;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function obtenerCantInmIncGer($proyecto,$gerencia,$periodo){
        $sql="SELECT
                count(1) CANTIDAD
              FROM
                SGC_TT_INMUEBLES I,
                SGC_TP_SECTORES S
              WHERE
                I.ID_PROYECTO='$proyecto' AND
                S.ID_SECTOR=I.ID_SECTOR AND
                S.ID_PROYECTO= I.ID_PROYECTO AND
                S.ID_GERENCIA='$gerencia' AND
                TO_CHAR(i.FECHA_CREACION,'YYYYMM')=$periodo";

        $resultado = oci_parse($this->_db,$sql);
        $banderas=oci_execute($resultado);
        if($banderas==TRUE)
        {
            oci_close($this->_db);
            oci_fetch($resultado);
            $cantidad=oci_result($resultado,"CANTIDAD");
            return $cantidad;
        }
        else
        {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }


    public function obtenerInmueblesIncorporadosPerdiodo($periodo,$proyecto){
        $sql="SELECT
                I.CODIGO_INM INMUEBLE,
                NVL(ALIAS,CL.NOMBRE_CLI) NOMBRE,
                C.FECHA_INICIO FECHA,
                I.ID_PROCESO PROCESO,
                SUBSTR(I.ID_PROCESO,0,2) SECTOR,
                SUBSTR(I.ID_PROCESO,3,2) RUTA,
                T.DESC_TARIFA TARIFA,
                T.CODIGO_TARIFA,
                T.COD_USO USO,
                SI.UNIDADES_HAB
              
              FROM
                SGC_TT_INMUEBLES I,
                SGC_TT_CONTRATOS C,
                SGC_TT_CLIENTES CL,
                SGC_TT_SERVICIOS_INMUEBLES SI,
                SGC_TP_TARIFAS T
              WHERE
                I.CODIGO_INM=C.CODIGO_INM(+) AND
                C.CODIGO_CLI=CL.CODIGO_CLI(+) AND
                CL.CODIGO_CLI(+) <> 9999999 AND
                SI.COD_INMUEBLE(+)=I.CODIGO_INM AND
                SI.COD_SERVICIO (+) IN (1,3) AND
                I.ID_PROYECTO='$proyecto' AND
                T.CONSEC_TARIFA(+)=SI.CONSEC_TARIFA AND
                C.FECHA_SOLICITUD IS NOT NULL AND
                TO_CHAR(C.FECHA_INICIO,'YYYYMM')=$periodo
              ORDER BY
                NVL(ALIAS,CL.NOMBRE_CLI)";

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


    public function agregaCliTemp($inmueble,$nombre,$tipoDoc,$documento,$telefono){
        $sql="BEGIN SGC_P_INGNOMTEMP($inmueble,'$nombre','$tipoDoc','$documento','$telefono',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado= oci_parse($this->_db,$sql);
        oci_bind_by_name($resultado,":PMSGRESULT",$this->msgresult,123);
        oci_bind_by_name($resultado,":PCODRESULT",$this->codresult,123);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            if($this->codresult>0){
                return false;
            }else{
                return true;
            }
        }
        else{
            oci_close($this->_db);
            return false;
        }
    }



	
		
}
