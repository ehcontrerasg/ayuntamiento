<?php

require_once '../../clases/class.conexion.php';

class EncuestaSatisfaccionUsuarios extends ConexionClass {

    function __construct(){
       parent::__construct();
    }

    function oficinas(){
        $sql = "SELECT ID_PUNTO_PAGO,DESCRIPCION FROM SGC_TP_PUNTO_PAGO P
                WHERE P.ENTIDAD_COD=900 AND P.ID_PUNTO_PAGO NOT IN (619,604,606)";
        $statement = oci_parse($this->db,$sql);
        if(oci_execute($statement)){

            $json = array('status'=>200,'offices'=>array());
            while($row = oci_fetch_assoc($statement)){
                $array = array(
                    "ID_PUNTO_PAGO"=>$row["ID_PUNTO_PAGO"],
                    "DESCRIPCION"=>$row["DESCRIPCION"],
                );
                array_push($json['offices'],$array);
            }

            return json_encode($json);
        }else{
            $error = oci_error($statement);
            return json_encode(array('status'=>500,'message'=>$error));
        }

    }

    function insertQuiz($form=[]){

        $codigo_respuesta  = 0;
        $mensaje_respuesta = '';
        $id_encuesta = 0;
        $encuestador = 'CAASD EN LINEA';
        $proyecto =  'SD';

        if(!count($form)>0) { return json_encode(array('status'=>400,'message'=>'No se encontraron datos para enviar.'));}

        $sql = "BEGIN SGC_P_ING_ENCUESTA_SATISF_USR@aceaprueba(:ENCUESTADOR_IN,:NOMBRES_CLIENTE_IN,:APELLIDOS_CLIENTE_IN,:ID_PROYECTO_IN,:ID_OFICINA_IN,:ID_RESPUESTA_ENCUESTA,:MSJ_OUT,:COD_OUT); END;";
        $statement = oci_parse($this->dbConnection,$sql);

        oci_bind_by_name($statement,':ENCUESTADOR_IN',$encuestador,100);
        oci_bind_by_name($statement,':NOMBRES_CLIENTE_IN',$form['nombres_cliente'],200);
        oci_bind_by_name($statement,':APELLIDOS_CLIENTE_IN',$form['apellidos_cliente'],200);
        oci_bind_by_name($statement,':ID_PROYECTO_IN',$proyecto,200);
        oci_bind_by_name($statement,':ID_OFICINA_IN',$form['oficina']);
        oci_bind_by_name($statement,':ID_RESPUESTA_ENCUESTA',$id_encuesta);
        oci_bind_by_name($statement,":MSJ_OUT",$mensaje_respuesta,500);
        oci_bind_by_name($statement,":COD_OUT",$codigo_respuesta);

        if(oci_execute($statement)){
            return json_encode(array('status'=>200,'message'=>$mensaje_respuesta,'data'=>array('id_respuesta_encuesta'=>$id_encuesta)));
        }

        //echo json_encode(array('status'=>500,'message'=>$mensaje_respuesta));
        return json_encode(array('status'=>500,'message'=>$mensaje_respuesta));
    }

    function insertQuizDetail($form=[]){

        $message = '';
        $code = 0;

        if(!count($form)>0) { echo json_encode(array('status'=>400,'message'=>'No se encontraron datos para enviar.')); return false;}

        $sql = "BEGIN SGC_P_INGR_DETALLE_SATISF_USR@aceaprueba(:ID_PREGUNTA_IN,:ID_RESPUESTA_ENCUESTA_IN,:RESPUESTA_IN,:MSJ_OUT,:COD_OUT); END;";
        $statement = oci_parse($this->dbConnection,$sql);

        oci_bind_by_name($statement,':ID_PREGUNTA_IN',$form['id_pregunta']);
        oci_bind_by_name($statement,':ID_RESPUESTA_ENCUESTA_IN',$form['id_respuesta_encuesta']);
        oci_bind_by_name($statement,':RESPUESTA_IN',$form['respuesta'],500);
        oci_bind_by_name($statement,':MSJ_OUT',$message,500);
        oci_bind_by_name($statement,':COD_OUT',$code);

        if(oci_execute($statement)){
            //echo json_encode(array('status'=>200,'message'=>$message));
            return json_encode(array('status'=>200,'message'=>$message));;
        }

        //echo json_encode(array('status'=>500,'message'=>$message));
        return json_encode(array('status'=>500,'message'=>$message));

    }

    function getEncuestas($proyecto, $fechaDesde, $fechaHasta){

        $sql = "SELECT RE.ID,RE.ENCUESTADOR,INITCAP(RE.NOMBRES_CLIENTE||' '||RE.APELLIDOS_CLIENTE)CLIENTE,PP.DESCRIPCION OFICINA, TO_CHAR(RE.FECHA,'DD/MM/YYYY HH24:MI') FECHA
                FROM SGC_TT_RESPUESTA_ENCUESTA RE, SGC_TP_PUNTO_PAGO PP
                WHERE PP.ID_PUNTO_PAGO = RE.ID_OFICINA
                  AND RE.ID_ENCUESTA = 2
                  AND RE.FECHA BETWEEN TO_DATE('$fechaDesde 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fechaHasta 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
                  AND RE.ID_PROYECTO = '$proyecto'";

        $sentencia = oci_parse($this->_db,$sql);
        if(oci_execute($sentencia)){
            $json = array('status'=>200,'data'=>array());
            while($fila = oci_fetch_assoc($sentencia)){
                $array = array(
                    'ID'=> $fila['ID'],
                    'ENCUESTADOR'=> $fila['ENCUESTADOR'],
                    'CLIENTE'=> $fila['CLIENTE'],
                    'OFICINA'=> $fila['OFICINA'],
                    'FECHA'=> $fila['FECHA'],
                    'VISUALIZAR'=> '<button class="btn btn-primary btnVisualizarEncuesta">Visualizar</button>'
                );
                array_push($json['data'],$array);
            }

            return json_encode($json);
        }else{
            $error = oci_error($this->_db);
            $json = array('status'=>500,'message'=>$error);
            return json_encode($json);
        }
    }

    function getEncuesta($idEncuesta){
         $sql = "SELECT * FROM (SELECT RE.ID, TO_CHAR(RE.FECHA,'DD/MM/YYYY') FECHA, PP.DESCRIPCION OFICINA,INITCAP(RE.NOMBRES_CLIENTE||' '||RE.APELLIDOS_CLIENTE) CLIENTE,
                      RE.ENCUESTADOR, (('PREGUNTA_')||EP.NUMERO_PREGUNTA) PREGUNTA, DE.RESPUESTA,
                      (SELECT 'FO-SEC-02' FROM dual) CODIGO_FORMULARIO,
                      (SELECT F.DESCRIPCION FROM SGC_TP_FORMULARIO F WHERE F.CODIGO = 'FO-SEC-02') NOMBRE_FORMULARIO,
                      (SELECT TO_CHAR(FECHA_EMISION,'DD/MM/YYYY') FROM (SELECT MAX(FE.FECHA_EMISION),FE.FECHA_EMISION,FE.CODIGO_FORMULARIO FROM SGC_TP_FORMULARIO_EDICION FE GROUP BY FE.FECHA_EMISION,FE.CODIGO_FORMULARIO)
                       WHERE ROWNUM = 1 AND FECHA_EMISION<=RE.FECHA AND CODIGO_FORMULARIO='FO-SEC-02') FECHA_EMISION_FORMULARIO,
                      (SELECT IMAGEN FROM (SELECT MAX(FE.FECHA_EMISION),FE.FECHA_EMISION,FE.CODIGO_FORMULARIO,FE.IMAGEN FROM SGC_TP_FORMULARIO_EDICION FE GROUP BY FE.FECHA_EMISION,FE.CODIGO_FORMULARIO,FE.IMAGEN )
                       WHERE ROWNUM = 1 AND FECHA_EMISION<=RE.FECHA AND CODIGO_FORMULARIO='FO-SEC-02') IMAGEN_FORMULARIO,
                      (SELECT lpad(EDICION, 2, '0') EDICION FROM (SELECT MAX(FE.FECHA_EMISION),FE.FECHA_EMISION,FE.CODIGO_FORMULARIO,FE.EDICION FROM SGC_TP_FORMULARIO_EDICION FE GROUP BY FE.FECHA_EMISION,FE.CODIGO_FORMULARIO,FE.EDICION )
                       WHERE ROWNUM = 1 AND FECHA_EMISION<=RE.FECHA AND CODIGO_FORMULARIO='FO-SEC-02') EDICION_FORMULARIO
               FROM SGC_TT_RESPUESTA_ENCUESTA RE,
                    SGC_TT_DETALLE_ENCUESTA DE,
                    SGC_TP_ENCUESTA_PREGUNTA EP,
                    SGC_TP_PUNTO_PAGO PP
               WHERE RE.ID = DE.ID_RESPUESTA_ENCUESTA
                 AND EP.ID = DE.ID_PREGUNTA
                 AND PP.ID_PUNTO_PAGO = RE.ID_OFICINA
                 AND RE.ID_ENCUESTA = 2
                 AND RE.ID = $idEncuesta
              )
                  PIVOT(
                    MAX(RESPUESTA)
                  FOR PREGUNTA IN(
                      'PREGUNTA_1',
                      'PREGUNTA_2',
                      'PREGUNTA_3',
                      'PREGUNTA_4',
                      'PREGUNTA_5',
                      'PREGUNTA_6',
                      'PREGUNTA_7',
                      'PREGUNTA_8',
                      'PREGUNTA_9',
                      'PREGUNTA_10',
                      'PREGUNTA_11',
                      'PREGUNTA_12',
                      'PREGUNTA_13',
                      'PREGUNTA_14',
                      'PREGUNTA_15'
              )
              )";

        $sentencia = oci_parse($this->_db,$sql);
        if(oci_execute($sentencia)){
            $json = array('status'=>200,'data'=>array());
            while($fila = oci_fetch_assoc($sentencia)){
                $array = array(
                    'id'=> $fila['ID'],
                    'fecha'=> $fila['FECHA'],
                    'oficina'=> $fila['OFICINA'],
                    'cliente'=> $fila['CLIENTE'],
                    'encuestador'=> $fila['ENCUESTADOR'],
                    'respuesta'=>array(
                                            array('name'=>'PREGUNTA_1', 'value'=>$fila["'PREGUNTA_1'"]),
                                            array('name'=>'PREGUNTA_2', 'value'=>$fila["'PREGUNTA_2'"]),
                                            array('name'=>'PREGUNTA_3', 'value'=>$fila["'PREGUNTA_3'"]),
                                            array('name'=>'PREGUNTA_4', 'value'=>$fila["'PREGUNTA_4'"]),
                                            array('name'=>'PREGUNTA_5', 'value'=>$fila["'PREGUNTA_5'"]),
                                            array('name'=>'PREGUNTA_6', 'value'=>$fila["'PREGUNTA_6'"]),
                                            array('name'=>'PREGUNTA_7', 'value'=>$fila["'PREGUNTA_7'"]),
                                            array('name'=>'PREGUNTA_8', 'value'=>$fila["'PREGUNTA_8'"]),
                                            array('name'=>'PREGUNTA_9', 'value'=>$fila["'PREGUNTA_9'"]),
                                            array('name'=>'PREGUNTA_10','value'=>$fila["'PREGUNTA_10'"]),
                                            array('name'=>'PREGUNTA_11','value'=>$fila["'PREGUNTA_11'"]),
                                            array('name'=>'PREGUNTA_12','value'=>$fila["'PREGUNTA_12'"]),
                                            array('name'=>'PREGUNTA_13','value'=>$fila["'PREGUNTA_13'"]),
                                            array('name'=>'PREGUNTA_14','value'=>$fila["'PREGUNTA_14'"]),
                                            array('name'=>'PREGUNTA_15','value'=>$fila["'PREGUNTA_15'"]),
                                            ),
                    'codigo_formulario' => $fila['CODIGO_FORMULARIO'],
                    'nombre_formulario' => $fila['NOMBRE_FORMULARIO'],
                    'fecha_emision_formulario' => $fila['FECHA_EMISION_FORMULARIO'],
                    'imagen_formulario' => $fila['IMAGEN_FORMULARIO'],
                    'edicion_formulario' => $fila['EDICION_FORMULARIO']
                );
                array_push($json['data'],$array);
            }

            return json_encode($json);
        }else{
            $error = oci_error($this->_db);
            $json = array('status'=>500,'message'=>$error);
            return json_encode($json);
        }
    }

    function getEncuestaParaImprimir($idProyecto,$fechaDesde,$fechaHasta){

        $sql = "SELECT * FROM (SELECT RE.ID,
                      RE.ENCUESTADOR,
                      TO_CHAR(RE.FECHA,'DD/MM/YYYY HH24:MI:SS') FECHA,
                      INITCAP(RE.NOMBRES_CLIENTE || ' ' || RE.APELLIDOS_CLIENTE) CLIENTE,
                      PP.DESCRIPCION                                      OFICINA,
                      'PREGUNTA_' || EP.NUMERO_PREGUNTA                   PREGUNTA,
                      NVL(LRE.DESCRIPCION, DE.RESPUESTA)                  RESPUESTA
               FROM SGC_TT_RESPUESTA_ENCUESTA RE,
                    SGC_TP_PUNTO_PAGO PP,
                    SGC_TT_DETALLE_ENCUESTA DE,
                    SGC_TP_ENCUESTA_PREGUNTA EP,
                    SGC_TP_LEYENDA_RESP_ENCUESTA LRE
               WHERE RE.ID_ENCUESTA = 2
                 AND PP.ID_PUNTO_PAGO = RE.ID_OFICINA
                 AND RE.ID = DE.ID_RESPUESTA_ENCUESTA
                 AND EP.ID = DE.ID_PREGUNTA
                 AND LRE.RESPUESTA (+) = DE.RESPUESTA
                 AND RE.ID_PROYECTO = '$idProyecto'
                 AND RE.FECHA BETWEEN TO_DATE('$fechaDesde 00:00:00', 'YYYY-MM-DD HH24:MI:SS') AND TO_DATE('$fechaHasta 23:59:59', 'YYYY-MM-DD HH24:MI:SS'))
                PIVOT (                
                    MAX(RESPUESTA)
                    FOR PREGUNTA IN(
                                  'PREGUNTA_1',
                                  'PREGUNTA_2',
                                  'PREGUNTA_3',
                                  'PREGUNTA_4',
                                  'PREGUNTA_5',
                                  'PREGUNTA_6',
                                  'PREGUNTA_7',
                                  'PREGUNTA_8',
                                  'PREGUNTA_9',
                                  'PREGUNTA_10',
                                  'PREGUNTA_11',
                                  'PREGUNTA_12',
                                  'PREGUNTA_13',
                                  'PREGUNTA_14',
                                  'PREGUNTA_15'
                                )
                    )";

        $sentencia = oci_parse($this->_db,$sql);

        if(oci_execute($sentencia)){

            $json = array('status'=>200,'data'=>array());

            while ($fila = oci_fetch_assoc($sentencia)){

                $arr = array(
                    'id'=>$fila['ID'],
                    'fecha'=>$fila['FECHA'],
                    'oficina'=>$fila['OFICINA'],
                    'cliente'=>$fila['CLIENTE'],
                    'encuestador'=>$fila['ENCUESTADOR'],
                    'preguntas'=>array(
                        array('nombre_pregunta'=>'pregunta_1','respuesta'=>$fila["'PREGUNTA_1'"]),
                        array('nombre_pregunta'=>'pregunta_2','respuesta'=>$fila["'PREGUNTA_2'"]),
                        array('nombre_pregunta'=>'pregunta_3','respuesta'=>$fila["'PREGUNTA_3'"]),
                        array('nombre_pregunta'=>'pregunta_4','respuesta'=>$fila["'PREGUNTA_4'"]),
                        array('nombre_pregunta'=>'pregunta_5','respuesta'=>$fila["'PREGUNTA_5'"]),
                        array('nombre_pregunta'=>'pregunta_6','respuesta'=>$fila["'PREGUNTA_6'"]),
                        array('nombre_pregunta'=>'pregunta_7','respuesta'=>$fila["'PREGUNTA_7'"]),
                        array('nombre_pregunta'=>'pregunta_8','respuesta'=>$fila["'PREGUNTA_8'"]),
                        array('nombre_pregunta'=>'pregunta_9','respuesta'=>$fila["'PREGUNTA_9'"]),
                        array('nombre_pregunta'=>'pregunta_10','respuesta'=>$fila["'PREGUNTA_10'"]),
                        array('nombre_pregunta'=>'pregunta_11','respuesta'=>$fila["'PREGUNTA_11'"]),
                        array('nombre_pregunta'=>'pregunta_12','respuesta'=>$fila["'PREGUNTA_12'"]),
                        array('nombre_pregunta'=>'pregunta_13','respuesta'=>$fila["'PREGUNTA_13'"]),
                        array('nombre_pregunta'=>'pregunta_14','respuesta'=>$fila["'PREGUNTA_14'"]),
                        array('nombre_pregunta'=>'pregunta_15','respuesta'=>$fila["'PREGUNTA_15'"]),
                    ),
                );
                array_push($json['data'],$arr);
            }

            return json_encode($json);
        }else
            return json_encode(array('status'=>500,'message'=>oci_error($sentencia)));

    }

    function __destruct(){
        oci_close($this->dbConnection);
    }
}