<?php
/**
 * Created by PhpStorm.
 * User: Tecnologia
 * Date: 28/10/2020
 * Time: 10:54
 */
require_once 'class.archivo.php';
require_once 'class.conexion.php';

class ArchivoGerencia extends ConexionClass implements Archivo
{
    public function __construct(){
        parent::__construct();
    }

    public function registrar($parametros = array()){

        $procedureMessage = '';
        $codigoMensaje = 0;
        $sql = "BEGIN SGC_P_INGR_ARCH_GERENCIA(:NUMERO_PROTOCOLO_IN,:TIPO_CORRESPONDENCIA_IN,:TIPO_DOCUMENTO_IN,:FECHA_DOCUMENTO_IN,:RUTA_ARCHIVO_IN,:ASUNTO_IN,:ID_EMPRESA_IN,:USUARIO_CARGA_IN,:MSG_OUT,:COD_OUT); END;";
        $statement = oci_parse($this->_db,$sql);
        oci_bind_by_name($statement,':NUMERO_PROTOCOLO_IN',$parametros['numero_protocolo']);
        oci_bind_by_name($statement,':TIPO_CORRESPONDENCIA_IN',$parametros['tipo_correspondencia']);
        oci_bind_by_name($statement,':TIPO_DOCUMENTO_IN',$parametros['tipo_documento']);
        oci_bind_by_name($statement,':FECHA_DOCUMENTO_IN',$parametros['fecha_documento']);
        oci_bind_by_name($statement,':RUTA_ARCHIVO_IN',$parametros['ruta_archivo']);
        oci_bind_by_name($statement,':ASUNTO_IN',$parametros['asunto']);
        oci_bind_by_name($statement,':ID_EMPRESA_IN',$parametros['id_emoresa']);
        oci_bind_by_name($statement,':USUARIO_CARGA_IN',$parametros['usuario_carga']);
        oci_bind_by_name($statement,':MSG_OUT',$procedureMessage,500);
        oci_bind_by_name($statement,':COD_OUT',$codigoMensaje,10);

        $executed = oci_execute($statement);

        if($executed){
            $json = array('message'=>$procedureMessage, 'error_message'=>oci_error($statement)['message'],'message_code'=>$codigoMensaje);
            return json_encode($json);
        }else{
            $json = array('message'=>$procedureMessage,'error_message'=>oci_error($statement)['message'], 'message_code'=>$codigoMensaje);
            return json_encode($json);
        }
    }

    public function actualizar($parametros = array()){
        $mensaje = '';
        $codigoMensaje = 0;

        $sql = "BEGIN SGC_P_ACTUALIZA_ARCH_GERENCIA(:ID_ARCHIVO_IN,:TIPO_CORRESPONDENCIA_IN,:TIPO_DOCUMENTO_IN,:FECHA_DOCUMENTO_IN,:RUTA_ARCHIVO_IN,:ARCHIVO_CAMBIA_IN,:ASUNTO_IN,:ID_EMPRESA_IN,:USUARIO_IN,:MS_OUT,:COD_OUT); END;";
        $statement = oci_parse($this->_db,$sql);

        oci_bind_by_name($statement,':ID_ARCHIVO_IN',$parametros['id_archivo']);
        oci_bind_by_name($statement,':TIPO_CORRESPONDENCIA_IN',$parametros['tipo_correspondencia']);
        oci_bind_by_name($statement,':TIPO_DOCUMENTO_IN',$parametros['tipo_documento']);
        oci_bind_by_name($statement,':FECHA_DOCUMENTO_IN',$parametros['fecha_documento']);
        oci_bind_by_name($statement,':RUTA_ARCHIVO_IN',$parametros['ruta_archivo']);
        oci_bind_by_name($statement,':ARCHIVO_CAMBIA_IN',$parametros['archivo_cambia']);
        oci_bind_by_name($statement,':ASUNTO_IN',$parametros['asunto']);
        oci_bind_by_name($statement,':ID_EMPRESA_IN',$parametros['id_empresa']);
        oci_bind_by_name($statement,':USUARIO_IN',$parametros['usuario']);
        oci_bind_by_name($statement,':MS_OUT',$mensaje,500);
        oci_bind_by_name($statement,':COD_OUT',$codigoMensaje);

        $executed = oci_execute($statement);

        if($executed){
            return array('message_code'=>$codigoMensaje,'message'=>$mensaje.' '.oci_error($statement));
        }else{
            return array('message_code'=>400,'message'=>oci_error($statement));
        }
    }

    public function obtenerTodos($parametros = array()){

        $sql = "SELECT AG.ID,AG.NUMERO_PROTOCOLO,DAG.ASUNTO, INITCAP(EMP.DESC_EMPRESA) EMPRESA, DAG.TIPO_CORRESPONDENCIA,
                       INITCAP(DOC.DESC_DOCUMENTO) TIPO_DOCUMENTO,TO_CHAR(DAG.FECHA_DOCUMENTO,'DD/MM/YYYY') FECHA_DOCUMENTO,DAG.RUTA_ARCHIVO,INITCAP(U.NOM_USR||' '||U.APE_USR) USUARIO_CARGA,
                       TO_CHAR(DAG.FECHA_CARGA,'DD/MM/YYYY HH12:MI am') FECHA_CARGA,DAG.TIPO_DOCUMENTO ID_TIPO_DOCUMENTO, DAG.ID_EMPRESA ID_EMPRESA
                FROM SGC_TT_ARCHIVO_GERENCIA AG, SGC_TT_DET_ARCH_GERENCIA DAG, SGC_TP_EMPRESAS EMP, SGC_TP_TIP_DOCUMENTOS DOC,SGC_TT_USUARIOS U
                WHERE AG.ID = DAG.ID_ARCHIVO
                AND   DAG.FECHA_CARGA = (SELECT MAX(DAG1.FECHA_CARGA) FROM SGC_TT_DET_ARCH_GERENCIA DAG1 WHERE DAG1.ID_ARCHIVO = AG.ID)
                AND   EMP.ID_EMPRESA = DAG.ID_EMPRESA
                AND   DOC.TIP_DOCUMENTOS = DAG.TIPO_DOCUMENTO
                AND   U.ID_USUARIO = DAG.USUARIO_CARGA
                AND   AG.ACTIVO =  'S'
                AND   AG.FECHA_DESACTIVACION IS NULL 
                ";

        $resource = oci_parse($this->_db,$sql);
        $executed = oci_execute($resource);

        if($executed){
            $data = array('message_code'=>200,'data'=>array());
            while($row = oci_fetch_assoc($resource)){
                $array = array(
                    'id' => $row['ID'],
                    'numero_protocolo' => $row['NUMERO_PROTOCOLO'],
                    'asunto' => $row['ASUNTO'],
                    'empresa' => $row['EMPRESA'],
                    'tipo_correspondencia' => $row['TIPO_CORRESPONDENCIA'],
                    'tipo_documento' => $row['TIPO_DOCUMENTO'],
                    'fecha_documento' => $row['FECHA_DOCUMENTO'],
                    'ruta_archivo' => $row['RUTA_ARCHIVO'],
                    'usuario_carga' => $row['USUARIO_CARGA'],
                    'fecha_carga' => $row['FECHA_CARGA'],
                );
                array_push($data['data'],$array);
            }
            return $data;
        }else
            return array('message_code'=>400,'message'=>oci_error($resource));
    }

    public function eliminar($parametros = array()){
        $message = '';
        $messageCode = 0;
        $sql = "BEGIN SGC_P_ELIMINAR_ARCH_GERENCIA(:ID_ARCHIVO_GERENCIA_IN, :USUARIO_IN, :MSG_OUT, :COD_OUT); END;";
        $statement = oci_parse($this->_db,$sql);

        oci_bind_by_name($statement,':ID_ARCHIVO_GERENCIA_IN',$parametros['id_archivo']);
        oci_bind_by_name($statement,':USUARIO_IN',$parametros['id_usuario']);
        oci_bind_by_name($statement,':MSG_OUT',$message,500);
        oci_bind_by_name($statement,':COD_OUT',$messageCode);

        $executed = oci_execute($statement);

        if($executed)
            return array('message_code'=>$messageCode,'message'=>$message);
        else
            return array('message_code'=>$messageCode,'message'=>$message.' '.oci_error($statement));
    }

    function getHistorico($idArchivo){
        $sql = "SELECT ROWNUM NUMERO_FILA,C.* FROM (SELECT AG.NUMERO_PROTOCOLO,DAG.ASUNTO,DAG.TIPO_CORRESPONDENCIA, EMP.DESC_EMPRESA EMPRESA,
                      DOC.DESC_DOCUMENTO DOCUMENTO,NVL(TO_CHAR(DAG.FECHA_DOCUMENTO,'DD/MM/YYYY'),'-') FECHA_DOCUMENTO,(U.NOM_USR||' '||U.APE_USR) USUARIO_CARGA,
                      TO_CHAR(DAG.FECHA_CARGA,'DD/MM/YYYY HH12:MI:SS am') FECHA_CARGA, DECODE(DAG.ARCHIVO_CAMBIA,'S','Sí'
                                                                                                    ,'N','No') ARCHIVO_CAMBIA
                FROM SGC_TT_DET_ARCH_GERENCIA DAG, SGC_TP_EMPRESAS EMP, SGC_TP_TIP_DOCUMENTOS DOC,SGC_TT_USUARIOS U, SGC_TT_ARCHIVO_GERENCIA AG 
                WHERE DAG.ID_ARCHIVO = $idArchivo
                AND   EMP.ID_EMPRESA = DAG.ID_EMPRESA
                AND   DOC.TIP_DOCUMENTOS = DAG.TIPO_DOCUMENTO
                AND   U.ID_USUARIO = DAG.USUARIO_CARGA
                AND   AG.ID = DAG.ID_ARCHIVO
                ORDER BY DAG.FECHA_CARGA DESC) C ";

        $resource = oci_parse($this->_db,$sql);
        $executed = oci_execute($resource);

        if($executed){
            $data = array('status'=>200,'data'=>array());
            while($row = oci_fetch_assoc($resource)){
                $array = array(
                    'numero_fila'=>$row['NUMERO_FILA'],
                    'numero_protocolo'=>$row['NUMERO_PROTOCOLO'],
                    'asunto'=>$row['ASUNTO'],
                    'tipo_correspondencia'=>$row['TIPO_CORRESPONDENCIA'],
                    'empresa'=>$row['EMPRESA'],
                    'documento'=>$row['DOCUMENTO'],
                    'fecha_documento'=>$row['FECHA_DOCUMENTO'],
                    'usuario_carga'=>$row['USUARIO_CARGA'],
                    'fecha_carga'=>$row['FECHA_CARGA'],
                    'archivo_cambia'=>$row['ARCHIVO_CAMBIA']
                );

                array_push($data['data'],$array);
            }
            return $data;
        }else
            return array('status'=>400,'message'=>'Ocurrió un error al intentar conseguir el histórico. '.oci_error($resource));
    }

    function getArchivo($idRegistro){

         $sql = "SELECT AG.ID,AG.NUMERO_PROTOCOLO,DAG.ASUNTO, INITCAP(EMP.DESC_EMPRESA) EMPRESA, DAG.TIPO_CORRESPONDENCIA,
                       INITCAP(DOC.DESC_DOCUMENTO) TIPO_DOCUMENTO,TO_CHAR(DAG.FECHA_DOCUMENTO,'YYYY-MM-DD') FECHA_DOCUMENTO,DAG.RUTA_ARCHIVO,INITCAP(U.NOM_USR||' '||U.APE_USR) USUARIO_CARGA,
                       TO_CHAR(DAG.FECHA_CARGA,'DD/MM/YYYY HH12:MI am') FECHA_CARGA, DAG.ID_EMPRESA,DAG.TIPO_DOCUMENTO ID_TIPO_DOCUMENTO 
                FROM SGC_TT_ARCHIVO_GERENCIA AG, SGC_TT_DET_ARCH_GERENCIA DAG, SGC_TP_EMPRESAS EMP, SGC_TP_TIP_DOCUMENTOS DOC,SGC_TT_USUARIOS U
                WHERE AG.ID = DAG.ID_ARCHIVO
                AND   DAG.FECHA_CARGA = (SELECT MAX(DAG1.FECHA_CARGA) FROM SGC_TT_DET_ARCH_GERENCIA DAG1 WHERE DAG1.ID_ARCHIVO = AG.ID)
                AND   EMP.ID_EMPRESA = DAG.ID_EMPRESA
                AND   DOC.TIP_DOCUMENTOS = DAG.TIPO_DOCUMENTO
                AND   U.ID_USUARIO = DAG.USUARIO_CARGA
                AND   AG.ID = $idRegistro
                ";

        $resource = oci_parse($this->_db,$sql);
        $executed = oci_execute($resource);

        if($executed){
            $data = array();
            while($row = oci_fetch_assoc($resource)){
                $array = array(
                    'id' => $row['ID'],
                    'numero_protocolo' => $row['NUMERO_PROTOCOLO'],
                    'asunto' => $row['ASUNTO'],
                    'empresa' => $row['EMPRESA'],
                    'tipo_correspondencia' => $row['TIPO_CORRESPONDENCIA'],
                    'tipo_documento' => $row['TIPO_DOCUMENTO'],
                    'fecha_documento' => $row['FECHA_DOCUMENTO'],
                    'ruta_archivo' => $row['RUTA_ARCHIVO'],
                    'usuario_carga' => $row['USUARIO_CARGA'],
                    'fecha_carga' => $row['FECHA_CARGA'],
                    'id_empresa' => $row['ID_EMPRESA'],
                    'id_tipo_documento' => $row['ID_TIPO_DOCUMENTO'],
                );
                array_push($data,$array);
            }
            return array('message_code'=>200,'data'=>$data[0]);
        }else{
            return array('message_code'=>400,'message'=>oci_error($resource));
        }
    }

    function getIdArchivo(){
        $sql = "SELECT (NVL(MAX(AG.ID),0) + 1) ULTIMO_ID_ARCHIVO FROM SGC_TT_ARCHIVO_GERENCIA AG ";

        $statement = oci_parse($this->_db, $sql);
        $executed = oci_execute($statement);

        if(!$executed){
            $error = oci_error($statement);
            return array('status'=>500,'message'=>$error);
        }

        $lastFileId = 0;
        while($row = oci_fetch_assoc($statement))
            $lastFileId = $row['ULTIMO_ID_ARCHIVO'];

        return array('status'=>200, 'last_file_id'=>$lastFileId);
    }

    function getArchivoPorNumeroProtocolo($numeroProtocolo){
        $sql = "SELECT count(*) CANTIDAD_ARCHIVOS from ACEASOFT.SGC_TT_ARCHIVO_GERENCIA where NUMERO_PROTOCOLO = $numeroProtocolo and FECHA_DESACTIVACION is null and ACTIVO = 'S' AND USUARIO_DESACTIVA IS NULL ";
        $statement = oci_parse($this->_db,$sql);
        $executed = oci_execute($statement);

        if(!$executed){
            $error = oci_error($statement);
            return array('status'=>500,'error'=>$error);
        }

        $fila = oci_fetch_assoc($statement);
        return array('status'=>200,'encontrados'=>$fila['CANTIDAD_ARCHIVOS']);
    }
}