<?php
session_start();
require_once '../../clases/class.conexion.php';
include_once '../../clases/class.plantillasCorreo.php';
include_once '../../facturacion/clases/class.correo.php';
include_once '../../clases/class.usuario.php';


/**
 * Modelo para gestionar las solicitudes
 * Algenis Mosquea 05/06/2018
 */
class Solicitudes extends ConexionClass
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getUserData($user)
    {
        $sql = "SELECT a.id_area,
               a.desc_area,
               U.id_usuario,
               U.id_cargo,
               U.Nom_usr || ' ' || U.ape_usr nombre,
               U.login,
               U.solicitudes
          FROM SGC_TT_USUARIOS U, SGC_TP_cargos c, SGC_TP_AREAS A
         WHERE u.id_cargo = c.id_cargo
           AND a.id_area = c.id_area
           AND a.visible = 'S'
           AND u.id_usuario = '$user'";

        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            return oci_fetch_assoc($result);
        } else {
            return false;
        }

    }

    public function getProgrammers()
    {
        $sql = "SELECT id_usuario, id_cargo, INITCAP(Nom_usr || ' ' || ape_usr) nombre
         FROM sgc_tt_usuarios s
        WHERE s.Programador = 'S'
          AND s.fec_fin is null";

        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            return $result;
        } else {
            return false;
        }
    }

    public function getSolPro($programmer)
    {
        $sql = "SELECT S.ID_SCMS,
			       M.DESC_MENU MODULO,
			       (SELECT M2.DESC_MENU
			          FROM SGC_TP_MENUS M2
			         WHERE M2.ID_MENU = S.ID_PANTALLA) PANTALLA
			  FROM SGC_TT_SCMS S, SGC_TP_MENUS M
			 WHERE S.DESARROLLADOR = '$programmer'
			   AND S.ESTADO IN ('ESP', 'PRO', 'PAU')
			   AND M.ID_MENU = S.ID_MODULO
               order BY S.ID_SCMS DESC";

        $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            return $result;
        } else {
            return false;
        }
    }

    public function asigDesarollador($id_scms, $programer, $type, $asignador)
    {
        $sql = "BEGIN sgc_pk_SCMS.asig_Desarrollador_SCMS(pID_SCMS       => :id_scms,
					                                      pDesarrollador => :programer,
					                                      pValida        => :vValida,
					                                      pAsignador     => :asignador,
					                                      MSG_ERR_OUT    => :vMSG_ERR_OUT);
					END; ";

        $resultado = oci_parse($this->_db, $sql);

        oci_bind_by_name($resultado, ':id_scms', $id_scms, 9);
        oci_bind_by_name($resultado, ':programer', $programer, 14);
        oci_bind_by_name($resultado, ':vValida', $type, 1);
        oci_bind_by_name($resultado, ':asignador', $asignador, 14);
        oci_bind_by_name($resultado, ':vMSG_ERR_OUT', $msgerr, 50);

        if (oci_execute($resultado)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getScms($type,$codigoSolicitud=0 ,$estado = '',$filaDesde = 0,$filaHasta = 5, $fechaDesde = '', $fechaHasta = '')
    {
        $user = $_SESSION['codigo'];


        switch ($type) {
            case 'A':
                $where = " AND S.estado not in ('FIN','ANU','DPR')
						   AND S.desarrollador is null
						   and S.valida_calidad = 'S'";
                break;
            case 'C':

                $where = " AND S.usr_calidad is null
                           AND S.ID_SCMS = $codigoSolicitud
                         ";

                if ($codigoSolicitud == 0) {
                    $where = " AND S.usr_calidad is null";
                }

                break;
            case 'S':
                $where = '';

                if ($estado != '') {
                    $where = " AND S.estado        = '$estado'";
                }

                if ($codigoSolicitud != 0) { $where .= " AND S.ID_SCMS = $codigoSolicitud"; }
                
                $where .= "
                    AND S.valida_calidad = 'S'
                    AND S.desarrollador = '$user'
                ";

                break;

            case 'F':
                $where = " AND S.solicitado         =  '$user'";

                if ($estado != '') {
                    $where .= " AND S.estado        = '$estado'";
                }


                if ($codigoSolicitud != 0) {
                    $where = " AND S.solicitado =  '$user'
                              AND S.ID_SCMS    = $codigoSolicitud";
                }
                break;
            case 'R':
                $where = " AND S.valida_solicitante = 'S'
            			   AND S.USR_REVISION is null
            			   AND S.valida_calidad = 'S'
            			   AND S.estado = 'FIN'";

                if ($codigoSolicitud != 0) {
                    $where = " AND S.valida_solicitante = 'S'
            			          AND S.USR_REVISION       is null
            			          AND S.valida_calidad     = 'S'
            			          AND S.estado             = 'FIN'
                                  AND S.ID_SCMS            = $codigoSolicitud";
                }
                break;
        }


        if($fechaDesde != ''){
            $where.= " AND S.FECHA_SOLICITUD >= TO_DATE('$fechaDesde 00:00:00','YYYY-MM-DD HH24:MI:SS') ";
        }

        if($fechaHasta != ''){
            $where.= " AND S.FECHA_SOLICITUD <= TO_DATE('$fechaHasta 23:59:59','YYYY-MM-DD HH24:MI:SS') ";
        }
        $sql = "SELECT * FROM (SELECT ROWNUM RN,SCMS.* FROM ( SELECT S.ID_SCMS,
                S.SOLICITADO id_solicitador,
                INITCAP((SELECT u.nom_usr || ' ' || u.ape_usr
                        FROM sgc_tt_usuarios u
                        where id_usuario = S.SOLICITADO)) solicitador,
                S.ID_AREA,
                S.ID_MODULO,
                INITCAP((SELECT m.desc_menu
                        FROM sgc_tp_menus m
                        WHERE m.id_menu = S.ID_MODULO
                        AND m.activo = 'S')) MODULO,
                NVL(TO_CHAR(S.FECHA_CONCLUCION, 'DD/MM/YYYY HH:MI:SS AM'), '-') FECHA_CONCLUSION,
                NVL(TO_CHAR(S.FECHA_INICIO, 'DD/MM/YYYY HH:MI:SS AM'), '-') FECHA_INICIO,
                TO_CHAR(S.FECHA_SOLICITUD, 'DD/MM/YYYY HH:MI:SS AM') FECHA_SOLICITUD,
                S.DESCRIPCION,
                NVL(TO_CHAR(S.FECHA_COMPROMISO, 'DD/MM/YYYY'), '-') FECHA_COMPROMISO,
                S.DESARROLLADOR ID_DESARROLLADOR,
                NVL(INITCAP((SELECT (u.nom_usr || ' ' || u.ape_usr)
                            FROM sgc_tt_usuarios u
                            where id_usuario = S.DESARROLLADOR)),
                    '-') DESARROLLADOR,
                S.ID_PANTALLA,
                INITCAP(e.DESCRIPCION) ESTADO,
                MS.ESTADO_SOLICITUD ID_ESTADO,
                INITCAP(t.PRIORIDAD_REQUERI) PRIORIDAD_REQUERI,
                INITCAP(t.DESC_REQUERIMIENTO) DESC_REQUERIMIENTO,
                INITCAP(P.DESC_PRIORIDAD) DESC_PRIORIDAD,
                INITCAP((SELECT m.desc_menu
                        FROM sgc_tp_menus m
                        WHERE m.id_menu = S.ID_PANTALLA
                        AND m.activo = 'S')) PANTALLA,
                CASE S.VALIDA_CALIDAD
                WHEN 'S' THEN
                'Sí'
                ELSE
                'NO'
                end VALIDA_CALIDAD,
                (SELECT c.COMENTARIO
                FROM (SELECT CSCMS.ID_SCMS, CSCMS.COMENTARIO
                        FROM SGC_TT_COMENTARIO_SCMS CSCMS
                        WHERE CSCMS.TIPO_COMENTARIO = 1
                        ORDER BY CSCMS.FECHA_CREACION DESC) C
                WHERE C.ID_SCMS = S.ID_SCMS
                    AND ROWNUM = 1) MENSAJE_CALIDAD,
                (SELECT TO_CHAR(C.FECHA_CREACION, 'DD/MM/YYYY HH:MI:SS AM')
                FROM (SELECT CSCMS.ID_SCMS, CSCMS.FECHA_CREACION
                        FROM SGC_TT_COMENTARIO_SCMS CSCMS
                        WHERE CSCMS.TIPO_COMENTARIO = 1
                        ORDER BY CSCMS.FECHA_CREACION DESC) C
                WHERE C.ID_SCMS = S.ID_SCMS
                    AND ROWNUM = 1) FECHA_MENSAJE_CALIDAD,
                S.VALIDA_SOLICITANTE,
                S.USR_REVISION,
                NVL((SELECT c1.COMENTARIO
                    FROM (SELECT C.COMENTARIO, C.ID_SCMS, C.USR_CREACION
                            FROM SGC_TT_COMENTARIO_SCMS C
                            WHERE C.TIPO_COMENTARIO = 3
                            ORDER BY C.FECHA_CREACION DESC) C1
                    WHERE C1.ID_SCMS = S.ID_SCMS
                    AND C1.USR_CREACION = S.SOLICITADO
                    AND ROWNUM = 1),
                    '-') COMENT_DESAPRUEBA_SOLICITANTE,
                NVL((SELECT TO_CHAR(C1.FECHA_CREACION, 'DD/MM/YYYY HH:MI:SS AM')
                    FROM (SELECT C.ID_SCMS, C.FECHA_CREACION, C.USR_CREACION
                            FROM SGC_TT_COMENTARIO_SCMS C
                            WHERE C.TIPO_COMENTARIO = 3
                            ORDER BY C.FECHA_CREACION DESC) C1
                    WHERE C1.ID_SCMS = S.ID_SCMS
                    AND C1.USR_CREACION = S.SOLICITADO
                    AND ROWNUM = 1),
                    '-') FECHA_COMENT_SOLICITANTE,
                NVL((SELECT c1.COMENTARIO
                    FROM (SELECT C.COMENTARIO, C.ID_SCMS, C.USR_CREACION
                            FROM SGC_TT_COMENTARIO_SCMS C
                            WHERE C.TIPO_COMENTARIO = 2
                            ORDER BY C.FECHA_CREACION DESC) C1
                    WHERE C1.ID_SCMS = S.ID_SCMS
                    AND ROWNUM = 1),
                    '-') COMENT_DESAPRUEBA_TI,
                NVL((SELECT INITCAP(c1.USUARIO_COMENTARIO)
                    FROM (SELECT C.ID_SCMS,
                                (U.NOM_USR || ' ' || U.APE_USR) USUARIO_COMENTARIO
                            FROM SGC_TT_COMENTARIO_SCMS C, SGC_TT_USUARIOS U
                            WHERE U.ID_USUARIO = C.USR_CREACION
                            AND C.TIPO_COMENTARIO = 2
                            ORDER BY C.FECHA_CREACION DESC) C1
                    WHERE C1.ID_SCMS = S.ID_SCMS
                    AND ROWNUM = 1),
                    '-') USUARIO_COMENT_ANULA_TI,
                NVL((SELECT TO_CHAR(C1.FECHA_CREACION, 'DD/MM/YYYY HH:MI:SS AM')
                    FROM (SELECT /*C.COMENTARIO,*/
                            C.ID_SCMS, C.FECHA_CREACION
                            FROM SGC_TT_COMENTARIO_SCMS C, SGC_TT_USUARIOS U
                            WHERE U.ID_USUARIO = C.USR_CREACION
                            AND C.TIPO_COMENTARIO = 2
                            ORDER BY C.FECHA_CREACION DESC) C1
                    WHERE C1.ID_SCMS = S.ID_SCMS
                    AND ROWNUM = 1),
                    '-') FECHA_COMENT_ANULA_TI
        FROM SGC_TT_SCMS S,
                (SELECT MAX(MS.ID_MOVIMIENTO), MS.ID_SCMS, MS.ESTADO_SOLICITUD
                FROM SGC_TT_MOVIMIENTO_SCMS MS
                WHERE MS.ID_MOVIMIENTO =
                        (SELECT MAX(MS1.ID_MOVIMIENTO)
                        FROM SGC_TT_MOVIMIENTO_SCMS MS1
                        WHERE MS.ID_SCMS = MS1.ID_SCMS)
                GROUP BY MS.ID_SCMS, MS.ESTADO_SOLICITUD) MS,
                SGC_TP_ESTADOS_SCMS E,
                SGC_TP_TIPO_SCMS T,
                SGC_TP_PRIORIDAD_SCMS P
        WHERE S.ID_SCMS = MS.ID_SCMS(+)
            AND E.CODIGO = S.ESTADO
            AND T.ID_CODIGO(+) = S.ID_TIPO_SCMS
            AND P.CODIGO_PRIORIDAD(+) = T.PRIORIDAD_REQUERI
            AND E.VALIDA = 'S'
            AND T.VALIDA = 'S'
            AND P.VALIDA = 'S'
            ".$where."
        ORDER BY S.FECHA_SOLICITUD DESC) SCMS";
             $sql .=" ) WHERE RN >= $filaDesde  AND RN < $filaHasta";
            
            $result = oci_parse($this->_db, $sql);

        if (oci_execute($result)) {
            return $result;
        } else {
            return false;
        }
    }

    public function cambiaStado($scms, $status)
    {
        $sql ="BEGIN sgc_pk_SCMS.actualiza_estado('$status',$scms,:PCODRESULT,:PMSGRESULT);END;";

        $result = oci_parse($this->_db, $sql);
        oci_bind_by_name($result,":PMSGRESULT",$mesrror,"1000");
        oci_bind_by_name($result,":PCODRESULT",$coderror);

        if (oci_execute($result)) {
            echo $coderror.' true';
            return true;
        } else {
            echo $mesrror.' false';
            return false;
        }
    }

    public function respCalidad($id_scms, $comment, $resp)
    {
        $user = $_SESSION['codigo'];

        $sql = "BEGIN sgc_pk_SCMS.vali_calidad_SCMS(pID_SCMS      => :vID_SCMS,
                                pUSR_CREACION => :vUSR_CREACION,
                                pCOMENTARIO   => :vCOMENTARIO,
                                pValida       => :vValida,
                                MSG_ERR_OUT   => :vMSG_ERR_OUT);
			END;";

        $resultado = oci_parse($this->_db, $sql);

        oci_bind_by_name($resultado, ':vID_SCMS', $id_scms, 9);
        oci_bind_by_name($resultado, ':vUSR_CREACION', $user, 14);
        oci_bind_by_name($resultado, ':vCOMENTARIO', $comment, 2500);
        oci_bind_by_name($resultado, ':vValida', $resp, 1);
        oci_bind_by_name($resultado, ':vMSG_ERR_OUT', $msgerr, 50);

        if (oci_execute($resultado)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function respSolicitante($id_scms)
    {
        $sql = "UPDATE SGC_TT_SCMS S SET S.VALIDA_SOLICITANTE = 'S', S.FECHA_VALIDA_SOLICITANTE=sysdate WHERE S.ID_SCMS = $id_scms";

        $resultado = oci_parse($this->_db, $sql);

        if (oci_execute($resultado)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function valFinal($id_scms,$comment)
    {
        $user = $_SESSION['codigo'];
       // $sql  = "UPDATE SGC_TT_SCMS S SET S.USR_REVISION = '$user' WHERE S.ID_SCMS = $id_scms";
         $sql = "BEGIN sgc_pk_SCMS.revision_scms(pID_SCMS_IN      => :vID_SCMS,
                                                 pUSR_REVISION_IN => :vUSR_CREACION,
                                                 pCOMENTARIO      => :vCOMENTARIO,
                                                 MSG_ERR_OUT      => :vMSG_ERR_OUT,
                                                 COD_ERR_OUT      => :vCOD_ERR_OUT);
			END;";

        $resultado = oci_parse($this->_db, $sql);

        oci_bind_by_name($resultado, ':vID_SCMS'     , $id_scms,     9);
        oci_bind_by_name($resultado, ':vUSR_CREACION', $user,       14);
        oci_bind_by_name($resultado, ':vCOMENTARIO'  , $comment,  2500);
        oci_bind_by_name($resultado, ':vMSG_ERR_OUT' , $msgerr,    200);
        oci_bind_by_name($resultado, ':vCOD_ERR_OUT' , $coderr,     50);

        /*print_r([$id_scms,
$user,
$comment,
$msgerr,
$coderr]);*/
       // $resultado = oci_parse($this->_db, $sql);

        if (oci_execute($resultado)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getEstadosScms($tipo_estado = 'A'){

        switch ($tipo_estado){
            case 'A':
                $where = "";
                break;
            case 'B':
                $where = " AND ESCMS.CODIGO IN ('PRO','ANU','FIN','ESP')";
                break;

        }

        $sql = "SELECT ESCMS.CODIGO,ESCMS.DESCRIPCION 
                FROM SGC_TP_ESTADOS_SCMS ESCMS
                WHERE ESCMS.VALIDA = 'S'
                ";

         $sql.= $where;

        $result   = oci_parse($this->_db, $sql);
        $bandera  = oci_execute($result);

        if($bandera){
            return $result;
        }else{
            return false;
        }
    }

    public function getHistoricoSolicitud($idSCMS){

        //Se verifica si tiene modificaciones a nivel global.
          if($this->scmsTieneModificacion($idSCMS)== false) {
                //Se verifica si tiene modificaciones pendientes.
              if($this->scmsTieneModificacion($idSCMS," AND ID_MODIFICACION is null") == true) {

                  //Si tiene modificaciones pendientes ejecuta esta consulta.
                  $sql = "SELECT * FROM (
                SELECT S.ID_SCMS, TO_CHAR(S.FECHA_SOLICITUD, 'DD/MM/YYYY HH:MI:SS AM') FECHA_SOLICITUD, S.DESCRIPCION , '-' FECHA_MODIFICACION,
                       INITCAP(NVL(M1.DESC_MENU,'-')||'\'||NVL(M2.DESC_MENU,'-')) PANTALLA,
                       (U2.NOM_USR ||' '||U2.APE_USR) USUARIO_SOLICITADOR,(NVL(U3.NOM_USR,'-') ||' '||nvl(U3.APE_USR,'-')) DESARROLLADOR, ES.DESCRIPCION ESTADO,
                       CASE WHEN S.VALIDA_SOLICITANTE =  'S' THEN 'Sí' ELSE 'No' END VALIDA_SOLICITANTE, 0 ID_MODIFICACION,
                       CASE WHEN S.VALIDA_CALIDAD =  'S' THEN 'Sí' ELSE 'No' END VALIDA_CALIDAD,NVL(TO_CHAR(S.FECHA_CALIDAD, 'DD/MM/YYYY HH:MI:SS AM'),'-') FECHA_CALIDAD,
                       NVL(TO_CHAR(S.FECHA_INICIO, 'DD/MM/YYYY HH:MI:SS AM'),'-') FECHA_INICIO,
                       NVL(TO_CHAR(S.FECHA_CONCLUCION, 'DD/MM/YYYY HH:MI:SS AM'),'-') FECHA_CONCLUSION,
                       NVL(TO_CHAR(S.FECHA_COMPROMISO, 'DD/MM/YYYY'),'-') FECHA_COMPROMISO,
                       TO_CHAR((SELECT MS.FECHA FROM SGC_TT_MOVIMIENTO_SCMS MS WHERE MS.ID_MOVIMIENTO = (SELECT MAX(MS1.ID_MOVIMIENTO) FROM SGC_TT_MOVIMIENTO_SCMS MS1 WHERE MS1.ID_SCMS = S.ID_SCMS AND MS1.ESTADO_SOLICITUD = 'VAL')),'DD/MM/YYYY HH24:MI:SS') FECHA_PREAPROBACION_DESARROLLO
                FROM SGC_TT_SCMS S, SGC_TP_MENUS M1, SGC_TP_MENUS M2, SGC_TT_USUARIOS U2, SGC_TT_USUARIOS U3,SGC_TP_ESTADOS_SCMS ES
                WHERE S.ID_MODULO     = M1.ID_MENU(+)
                  AND S.ID_PANTALLA     = M2.ID_MENU(+)
                  AND S.FECHA_CALIDAD   IS NOT NULL
                  AND U2.ID_USUARIO     = S.SOLICITADO
                  AND U3.ID_USUARIO(+)  = S.DESARROLLADOR
                  AND ES.CODIGO(+)      = S.ESTADO
                  AND S.ID_SCMS         = $idSCMS
                UNION
                    SELECT S.ID_SCMS, NVL(TO_CHAR(HS.FECHA_SOLICITUD, 'DD/MM/YYYY HH:MI:SS AM'),TO_CHAR(S.FECHA_SOLICITUD, 'DD/MM/YYYY HH:MI:SS AM')) FECHA_SOLICITUD,NVL(HS.DESCRIPCION, S.DESCRIPCION) DESCRIPCION,
                          NVL(TO_CHAR(HS.FECHA_MODIFICACION, 'DD/MM/YYYY HH:MI:SS AM'),'SOLICITANTE NO HA MODIFICADO') FECHA_MODIFICACION,
                          INITCAP(NVL(M1.DESC_MENU,M3.DESC_MENU)||'\'||NVL(M2.DESC_MENU,M4.DESC_MENU)) PANTALLA,
                          (U2.NOM_USR ||' '||U2.APE_USR) USUARIO_SOLICITADOR,(NVL(U3.NOM_USR,'-') ||' '||nvl(U3.APE_USR,'-')) DESARROLLADOR, NVL(ES1.DESCRIPCION, ES2.DESCRIPCION) ESTADO,
                          CASE WHEN NVL(HS.VALIDA_SOLICITANTE,S.VALIDA_SOLICITANTE) =  'S' THEN 'Sí' ELSE 'No' END VALIDA_SOLICITANTE, NVL(HS.ID_MODIFICACION,0) ID_MODIFICACION,
                          CASE WHEN nvl(HS.VALIDA_CALIDAD, S.VALIDA_CALIDAD) =  'S' THEN 'Sí' ELSE 'No' END VALIDA_CALIDAD,NVL(TO_CHAR(HS.FECHA_CALIDAD, 'DD/MM/YYYY HH:MI:SS AM'),TO_CHAR(S.FECHA_CALIDAD, 'DD/MM/YYYY HH:MI:SS AM')) FECHA_CALIDAD,
                          NVL(NVL(TO_CHAR(HS.FECHA_INICIO, 'DD/MM/YYYY HH:MI:SS AM'),TO_CHAR(S.FECHA_INICIO, 'DD/MM/YYYY HH:MI:SS AM')),'-') FECHA_INICIO,
                          NVL(TO_CHAR(S.FECHA_CONCLUCION, 'DD/MM/YYYY HH:MI:SS AM'),'-') FECHA_CONCLUSION,
                          NVL(TO_CHAR(S.FECHA_COMPROMISO, 'DD/MM/YYYY'),'-') FECHA_COMPROMISO,
                          TO_CHAR((SELECT MS.FECHA FROM SGC_TT_MOVIMIENTO_SCMS MS WHERE MS.ID_MOVIMIENTO = (SELECT MAX(MS1.ID_MOVIMIENTO) FROM SGC_TT_MOVIMIENTO_SCMS MS1 WHERE MS1.ID_SCMS = S.ID_SCMS AND MS1.ESTADO_SOLICITUD = 'VAL')),'DD/MM/YYYY HH24:MI:SS') FECHA_PREAPROBACION_DESARROLLO
                    FROM SGC_TT_SCMS S,SGC_TT_COMENT_MODIF CM, SGC_TT_COMENTARIO_SCMS CS,SGC_TT_HIST_MODIFICA_SCMS HS, SGC_TT_USUARIOS U, SGC_TP_MENUS M1, SGC_TP_MENUS M2, SGC_TP_MENUS M3, SGC_TP_MENUS M4, SGC_TT_USUARIOS U2, SGC_TT_USUARIOS U3,
                         SGC_TP_ESTADOS_SCMS ES1,SGC_TP_ESTADOS_SCMS ES2
                    WHERE S.ID_SCMS(+)        = CM.ID_SCMS
                      AND CS.ID_COMENTARIO(+)   = CM.ID_COMENTARIO
                      AND HS.ID_MODIFICACION(+) = CM.ID_MODIFICACION
                      AND U.ID_USUARIO          = CS.USR_CREACION
                      AND M1.ID_MENU(+)         = HS.ID_MODULO
                      AND M2.ID_MENU(+)         = HS.ID_PANTALLA
                      AND M3.ID_MENU(+)         = S.ID_MODULO
                      AND M4.ID_MENU(+)         = S.ID_PANTALLA
                      AND U2.ID_USUARIO         = S.SOLICITADO
                      AND U3.ID_USUARIO(+)      = S.DESARROLLADOR
                      AND ES1.CODIGO(+)         = HS.ESTADO
                      AND ES2.CODIGO(+)         = S.ESTADO
                      AND S.ID_SCMS(+)          = $idSCMS
                      AND S.FECHA_MODIFICACION IS NOT NULL
                )ORDER BY FECHA_SOLICITUD ASC";

              }else{
                  $sql = "SELECT S.ID_SCMS, NVL(TO_CHAR(HS.FECHA_SOLICITUD, 'DD/MM/YYYY HH:MI:SS AM'),TO_CHAR(S.FECHA_SOLICITUD, 'DD/MM/YYYY HH:MI:SS AM')) FECHA_SOLICITUD,NVL(HS.DESCRIPCION, S.DESCRIPCION) DESCRIPCION,
                            NVL(TO_CHAR(HS.FECHA_MODIFICACION, 'DD/MM/YYYY HH:MI:SS AM'),'SOLICITANTE NO HA MODIFICADO') FECHA_MODIFICACION,
                            INITCAP(NVL(M1.DESC_MENU,M3.DESC_MENU)||'\'||NVL(M2.DESC_MENU,M4.DESC_MENU)) PANTALLA,
                            (U2.NOM_USR ||' '||U2.APE_USR) USUARIO_SOLICITADOR,(NVL(U3.NOM_USR,'-') ||' '||nvl(U3.APE_USR,'-')) DESARROLLADOR, NVL(ES1.DESCRIPCION, ES2.DESCRIPCION) ESTADO,
                            CASE WHEN NVL(HS.VALIDA_SOLICITANTE,S.VALIDA_SOLICITANTE) =  'S' THEN 'Sí' ELSE 'No' END VALIDA_SOLICITANTE, NVL(HS.ID_MODIFICACION,0) ID_MODIFICACION,
                            CASE WHEN nvl(HS.VALIDA_CALIDAD, S.VALIDA_CALIDAD) =  'S' THEN 'Sí' ELSE 'No' END VALIDA_CALIDAD,NVL(TO_CHAR(HS.FECHA_CALIDAD, 'DD/MM/YYYY HH:MI:SS AM'),TO_CHAR(S.FECHA_CALIDAD, 'DD/MM/YYYY HH:MI:SS AM')) FECHA_CALIDAD,
                            NVL(NVL(TO_CHAR(HS.FECHA_INICIO, 'DD/MM/YYYY HH:MI:SS AM'),TO_CHAR(S.FECHA_INICIO, 'DD/MM/YYYY HH:MI:SS AM')),'-') FECHA_INICIO,
                            NVL(TO_CHAR(S.FECHA_CONCLUCION, 'DD/MM/YYYY HH:MI:SS AM'),'-') FECHA_CONCLUSION,
                            NVL(TO_CHAR(S.FECHA_COMPROMISO, 'DD/MM/YYYY'),'-') FECHA_COMPROMISO,
                            TO_CHAR((SELECT MS.FECHA FROM SGC_TT_MOVIMIENTO_SCMS MS WHERE MS.ID_MOVIMIENTO = (SELECT MAX(MS1.ID_MOVIMIENTO) FROM SGC_TT_MOVIMIENTO_SCMS MS1 WHERE MS1.ID_SCMS = S.ID_SCMS AND MS1.ESTADO_SOLICITUD = 'VAL')),'DD/MM/YYYY HH24:MI:SS') FECHA_PREAPROBACION_DESARROLLO
                  FROM SGC_TT_SCMS S,SGC_TT_COMENT_MODIF CM, SGC_TT_COMENTARIO_SCMS CS,SGC_TT_HIST_MODIFICA_SCMS HS, SGC_TT_USUARIOS U, SGC_TP_MENUS M1, SGC_TP_MENUS M2, SGC_TP_MENUS M3, SGC_TP_MENUS M4, SGC_TT_USUARIOS U2, SGC_TT_USUARIOS U3,
                       SGC_TP_ESTADOS_SCMS ES1,SGC_TP_ESTADOS_SCMS ES2
                  WHERE S.ID_SCMS(+)        = CM.ID_SCMS
                    AND CS.ID_COMENTARIO(+)   = CM.ID_COMENTARIO
                    AND HS.ID_MODIFICACION(+) = CM.ID_MODIFICACION
                    AND U.ID_USUARIO          = CS.USR_CREACION
                    AND M1.ID_MENU(+)         = HS.ID_MODULO
                    AND M2.ID_MENU(+)         = HS.ID_PANTALLA
                    AND M3.ID_MENU(+)         = S.ID_MODULO
                    AND M4.ID_MENU(+)         = S.ID_PANTALLA
                    AND U2.ID_USUARIO         = S.SOLICITADO
                    AND U3.ID_USUARIO(+)      = S.DESARROLLADOR
                    AND ES1.CODIGO(+)         = HS.ESTADO
                    AND ES2.CODIGO(+)         = S.ESTADO
                    AND S.ID_SCMS(+)          = $idSCMS
                    AND S.FECHA_MODIFICACION IS NOT NULL
                    ORDER BY FECHA_SOLICITUD 
                    ";

              }
          }
        else{
            //Si tiene modificaciones a nivel global ejecuta esta consulta.
             $sql = "SELECT S.ID_SCMS, TO_CHAR(S.FECHA_SOLICITUD, 'DD/MM/YYYY HH:MI:SS AM') FECHA_SOLICITUD, S.DESCRIPCION , '-' FECHA_MODIFICACION,
                            INITCAP(NVL(M1.DESC_MENU,'-')||'\'||NVL(M2.DESC_MENU,'-')) PANTALLA,
                            (U2.NOM_USR ||' '||U2.APE_USR) USUARIO_SOLICITADOR,(NVL(U3.NOM_USR,'-') ||' '||nvl(U3.APE_USR,'-')) DESARROLLADOR, ES.DESCRIPCION ESTADO,
                            CASE WHEN S.VALIDA_SOLICITANTE =  'S' THEN 'Sí' ELSE 'No' END VALIDA_SOLICITANTE, 0 ID_MODIFICACION,
                            CASE WHEN S.VALIDA_CALIDAD =  'S' THEN 'Sí' ELSE 'No' END VALIDA_CALIDAD,NVL(TO_CHAR(S.FECHA_CALIDAD, 'DD/MM/YYYY HH:MI:SS AM'),'-') FECHA_CALIDAD,
                            NVL(TO_CHAR(S.FECHA_INICIO, 'DD/MM/YYYY HH:MI:SS AM'),'-') FECHA_INICIO,
                            NVL(TO_CHAR(S.FECHA_CONCLUCION, 'DD/MM/YYYY HH:MI:SS AM'),'-') FECHA_CONCLUSION,
                            NVL(TO_CHAR(S.FECHA_COMPROMISO, 'DD/MM/YYYY'),'-') FECHA_COMPROMISO,
                            TO_CHAR((SELECT MS.FECHA FROM SGC_TT_MOVIMIENTO_SCMS MS WHERE MS.ID_MOVIMIENTO = (SELECT MAX(MS1.ID_MOVIMIENTO) FROM SGC_TT_MOVIMIENTO_SCMS MS1 WHERE MS1.ID_SCMS = S.ID_SCMS AND MS1.ESTADO_SOLICITUD = 'VAL')),'DD/MM/YYYY HH24:MI:SS') FECHA_PREAPROBACION_DESARROLLO
                      FROM SGC_TT_SCMS S, SGC_TP_MENUS M1, SGC_TP_MENUS M2, SGC_TT_USUARIOS U2, SGC_TT_USUARIOS U3,SGC_TP_ESTADOS_SCMS ES
                      WHERE S.ID_MODULO     = M1.ID_MENU(+)
                        AND S.ID_PANTALLA   = M2.ID_MENU(+)
                        AND U2.ID_USUARIO   = S.SOLICITADO
                        AND U3.ID_USUARIO(+)= S.DESARROLLADOR
                        AND ES.CODIGO(+)    = S.ESTADO
                        AND S.ID_SCMS       = $idSCMS";
          }

         //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);

        if($bandera){
            return $resultado;
        }else{
            return false;
        }

    }

    function anulaSCMS($id_scms,$comment = ''){
        $user = $_SESSION['codigo'];

        $sql = "BEGIN sgc_pk_SCMS.anula_SCMS(pID_SCMS      => :vID_SCMS,
                                             pUSR_CREACION => :vUSR_CREACION,
                                             pCOMENTARIO   => :vCOMENTARIO,
                                             MSG_ERR_OUT   => :vMSG_ERR_OUT,
                                             COD_ERR_OUT   => :vCOD_ERR_OUT
                                             );
			END;";




        $resultado = oci_parse($this->_db, $sql);

        oci_bind_by_name($resultado, ':vID_SCMS'     , $id_scms , 9);
        oci_bind_by_name($resultado, ':vUSR_CREACION', $user    , 14);
        oci_bind_by_name($resultado, ':vCOMENTARIO'  , $comment , 2500);
        oci_bind_by_name($resultado, ':vMSG_ERR_OUT' , $msgerr  , 500);
        oci_bind_by_name($resultado, ':vCOD_ERR_OUT' , $coderr  , 50);

        if (oci_execute($resultado)) {
            return 1;
        } else {
            return 0;
        }

    }

    function desapruebaSCMS($id_scms,$comment=''){


        $user = $_SESSION['codigo'];

         $sql = "BEGIN sgc_pk_SCMS.desaprueba_SCMS(pID_SCMS      => :vID_SCMS,
                                             pUSR_CREACION => :vUSR_CREACION,
                                             pCOMENTARIO   => :vCOMENTARIO,
                                             MSG_ERR_OUT   => :vMSG_ERR_OUT,
                                             COD_ERR_OUT   => :vCOD_ERR_OUT
                                             );
                                             
			END;";
        
        $resultado = oci_parse($this->_db, $sql);

        oci_bind_by_name($resultado, ':vID_SCMS'     , $id_scms , 9);
        oci_bind_by_name($resultado, ':vUSR_CREACION', $user    , 14);
        oci_bind_by_name($resultado, ':vCOMENTARIO'  , $comment , 2500);
        oci_bind_by_name($resultado, ':vMSG_ERR_OUT' , $msgerr  , 500);
        oci_bind_by_name($resultado, ':vCOD_ERR_OUT' , $coderr  , 50);


        if (oci_execute($resultado)) {
            return 1;
        } else {
            return 0;
        }
    }

    function validarDesarrolloScms($idScms, $idUsuario){
        $sql = "BEGIN sgc_pk_SCMS.validar_desarrollo(:ID_SCMS_IN,:USUARIO_IN,:MSG_ERR_OUT); COMMIT; END;";

        $statement = oci_parse($this->_db, $sql);

        oci_bind_by_name($statement, ":ID_SCMS_IN",$idScms);
        oci_bind_by_name($statement, ":USUARIO_IN",$idUsuario);
        oci_bind_by_name($statement, ":MSG_ERR_OUT",$mensaje,500);
        oci_close($this->_db);

        $mensaje = (oci_execute($statement)) ? $mensaje : oci_error($statement)['message'];

        return array(
            'mensaje' => $mensaje
        );
    }

    function getComentarios ($id_scms, $id_modificacion = 0, $parsed = false){

        /**
         * @parsed: false = Determina si va a retornar los datos en bruto para luego parsearlo. true= Retorna los datos como una arreglo.
        */

          if($this->solicitudConcluida($id_scms)){

            $sql = "SELECT * FROM (SELECT NVL(TO_CHAR(DS.FECHA_CONCLUSION, 'DD/MM/YYYY HH:MI:SS AM'), '-')    FECHA_CONCLUSION,
                                          NVL(TO_CHAR(DS.FECHA_DESAPROBACION, 'DD/MM/YYYY HH:MI:SS AM'), '-') FECHA_DESAPROBACION,
                                          NVL((SELECT CS.COMENTARIO
                                               FROM SGC_TT_COMENTARIO_SCMS CS
                                               WHERE CS.ID_SCMS = DS.ID_SCMS
                                                 AND CS.FECHA_CREACION = DS.FECHA_DESAPROBACION
                                                 AND CS.TIPO_COMENTARIO = 3), '-')                            COMENTARIO,
                                          NVL((SELECT (U.NOM_USR || ' ' || U.APE_USR) USUARIO
                                               FROM SGC_TT_COMENTARIO_SCMS CS,
                                                    SGC_TT_USUARIOS U
                                               WHERE CS.ID_SCMS = DS.ID_SCMS
                                                 AND CS.FECHA_CREACION = DS.FECHA_DESAPROBACION
                                                 AND CS.TIPO_COMENTARIO = 3
                                                 AND U.ID_USUARIO = CS.USR_CREACION), '-')                    USUARIO_COMENTA,
                                          NVL((SELECT TO_CHAR(CS.FECHA_CREACION, 'DD/MM/YYYY HH:MI:SS AM') FECHA_CREACION
                                               FROM SGC_TT_COMENTARIO_SCMS CS
                                               WHERE CS.ID_SCMS = DS.ID_SCMS
                                                 AND CS.FECHA_CREACION = DS.FECHA_DESAPROBACION
                                                 AND CS.TIPO_COMENTARIO = 3), '-')                            FECHA_CREACION
                                   FROM SGC_TT_HIST_DESAPROBACION_SCMS DS
                                   WHERE DS.ID_SCMS = $id_scms
                                   UNION
                        SELECT '-' FECHA_CONCLUSION, '-' FECHA_DESAPROBACION, CS.COMENTARIO, (U.NOM_USR||' '||U.APE_USR) USUARIO_COMENTA, TO_CHAR(CS.FECHA_CREACION, 'DD/MM/YYYY HH:MI:SS AM') FECHA_CREACION
                        FROM SGC_TT_COMENTARIO_SCMS CS, SGC_TT_SCMS S, SGC_TT_USUARIOS U
                        WHERE S.ID_SCMS = CS.ID_SCMS
                        AND U.ID_USUARIO = CS.USR_CREACION
                        AND CS.TIPO_COMENTARIO = 2
                        AND S.ID_SCMS = $id_scms
              ) ORDER BY  FECHA_CREACION";
          }else{
              $sql = "SELECT * FROM (SELECT * FROM (SELECT CS.COMENTARIO, (U.NOM_USR ||' '||U.APE_USR) USUARIO_COMENTA,TO_CHAR(CS.FECHA_CREACION, 'DD/MM/YYYY HH:MI:SS AM') FECHA_CREACION,NVL(CH.ID_MODIFICACION,0) ID_MODIFICACION
                                    FROM SGC_TT_COMENT_MODIF CM, SGC_TT_HIST_MODIFICA_SCMS CH,SGC_TT_COMENTARIO_SCMS CS, SGC_TT_USUARIOS U
                                    WHERE CH.ID_MODIFICACION(+)  = CM.ID_MODIFICACION
                                      AND CS.ID_COMENTARIO       = CM.ID_COMENTARIO
                                      AND U.ID_USUARIO           = CS.USR_CREACION
                                      AND CM.ID_SCMS             = CS.ID_SCMS
                                      AND CM.ID_SCMS             = $id_scms) A
                                    WHERE A.ID_MODIFICACION      = $id_modificacion
                                    UNION
                                    SELECT CS.COMENTARIO, (U.NOM_USR||' '||U.APE_USR) USUARIO_COMENTA, TO_CHAR(CS.FECHA_CREACION, 'DD/MM/YYYY HH:MI:SS AM') FECHA_CREACION,0 ID_MODIFICACION
                                    FROM SGC_TT_COMENTARIO_SCMS CS, SGC_TT_SCMS S, SGC_TT_USUARIOS U
                                    WHERE S.ID_SCMS = CS.ID_SCMS
                                    AND U.ID_USUARIO = CS.USR_CREACION
                                    AND CS.TIPO_COMENTARIO = 2
                                    AND S.ID_SCMS = $id_scms
                                              ) ORDER BY  FECHA_CREACION";
          }

          //echo $sql;
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);

        if($bandera){
            if($parsed == false){
                return $resultado;
            }else{
                $data = [];
                while($fila = oci_fetch_assoc($resultado)){

                    $comentario      = $fila["COMENTARIO"];
                    $usuario_comenta = $fila["USUARIO_COMENTA"];
                    $fecha_creacion  = $fila["FECHA_CREACION"];

                    $arr = [$comentario,$usuario_comenta,$fecha_creacion];

                    array_push($data,$arr);
                }

                return $data;
            }
        }else{
            return false;
        }

    }

    function cierre_scms($id_scms){

        //$user = $_SESSION['codigo'];

        $sql = "BEGIN   sgc_pk_SCMS.cierre_SCMS(pID_SCMS    => :vID_SCMS,
                                                MSG_ERR_OUT => :vMSG_ERR_OUT); 
			    END;";

        $resultado = oci_parse($this->_db, $sql);

        oci_bind_by_name($resultado, ':vID_SCMS'     , $id_scms , 9);
        oci_bind_by_name($resultado, ':vMSG_ERR_OUT' , $msgerr  , 500);


        if (oci_execute($resultado)) {
            /*return ["status" => 1,
                    "msgerr" => $msgerr];*/
            return 1;
        } else {
            return 0;
        }
    }

    function scmsTieneModificacion($id_scms, $where= ""){

        /*$sql = "SELECT COUNT(CM.ID_SCMS) CANTIDAD_MODIFICACIONES
                FROM ACEASOFT.SGC_TT_COMENT_MODIF CM 
                WHERE CM.ID_SCMS =$id_scms
                AND   ID_MODIFICACION is not null";*/

         $sql = "SELECT COUNT(CM.ID_SCMS) CANTIDAD_MODIFICACIONES  
                FROM ACEASOFT.SGC_TT_COMENT_MODIF CM 
                WHERE CM.ID_SCMS =$id_scms".
                $where;
        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            $cantidadModificaciones = oci_fetch_assoc($resultado)["CANTIDAD_MODIFICACIONES"];
            //$fechaCalidad           = oci_fetch_assoc($resultado)["FECHA_CALIDAD"];

            if($cantidadModificaciones == 0 /*&& ($fechaCalidad != null || $fechaCalidad != "")*/)
                return true;
            else
                return false;
        }else{

            return 0;
        }

    }

    function validaCalidad($id_scms){
        $sql = "SELECT COUNT(*) VALIDA_CALIDAD FROM ACEASOFT.SGC_TT_SCMS S WHERE S.ID_SCMS = $id_scms AND FECHA_CALIDAD IS NOT NULL AND S.VALIDA_CALIDAD = 'S' AND S.USR_CALIDAD IS NOT NULL";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            $validaCalidad = oci_fetch_assoc($resultado)["VALIDA_CALIDAD"];
            //$fechaCalidad           = oci_fetch_assoc($resultado)["FECHA_CALIDAD"];

            if($validaCalidad>0 /*&& ($fechaCalidad != null || $fechaCalidad != "")*/)
                return true;
            else
                return false;
        }else{

            return 0;
        }

    }

    function solicitudConcluida($idSCMS){

        $sql = "SELECT COUNT(*) CONCLUSIONES
                FROM SGC_TT_HIST_DESAPROBACION_SCMS DS
                WHERE  DS.ID_SCMS = $idSCMS";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            $conclusiones = oci_fetch_assoc($resultado)["CONCLUSIONES"];

            if($conclusiones > 0)
                return true;
            else
                return false;
        }else{
            return false;
        }
    }

    function getSCMSData($idScms){
        $sql = "SELECT S.ID_SCMS ID_SOLICITUD, INITCAP(U1.NOM_USR||' '||U1.APE_USR) SOLICITADOR,TO_CHAR(S.FECHA_SOLICITUD,'DD/MM/YYYY HH24:MI:SS') FECHA_SOLICITUD,
                      TO_CHAR(S.FECHA_COMPROMISO,'DD/MM/YYYY HH24:MI:SS') FECHA_COMPROMISO,INITCAP(M1.DESC_MENU||'\'||M2.DESC_MENU) PANTALLA,ID_TIPO_SCMS,TS.DESC_REQUERIMIENTO, M1.ID_MENU ID_MODULO, 
                      M2.ID_MENU ID_PANTALLA, S.DESCRIPCION, U1.EMAIL_USR EMAIL_SOLICITADOR, ES.DESCRIPCION ESTADO, (U2.NOM_USR ||' '||U2.APE_USR) DESARROLLADOR 
                FROM  SGC_TT_SCMS S, SGC_TP_TIPO_SCMS TS, SGC_TP_MENUS M1, SGC_TP_MENUS M2,SGC_TT_USUARIOS U1,SGC_TP_ESTADOS_SCMS ES, ACEASOFT.SGC_TT_USUARIOS U2
                WHERE TS.ID_CODIGO     = S.ID_TIPO_SCMS
                AND   M1.ID_MENU(+)    = S.ID_MODULO
                AND   M2.ID_MENU(+)    = S.ID_PANTALLA
                AND   U1.ID_USUARIO    = S.SOLICITADO
                AND   U2.ID_USUARIO(+) = S.DESARROLLADOR
                AND   ES.CODIGO        = S.ESTADO
                AND   S.ID_SCMS        = $idScms";

        $resultado = oci_parse($this->_db,$sql);

        if(oci_execute($resultado)){
            $data = [];
            while($fila = oci_fetch_assoc($resultado)){
                $idTipoSCMS         = $fila["ID_TIPO_SCMS"];
                $descRequerimiento  = $fila["DESC_REQUERIMIENTO"];
                $idModulo           = $fila["ID_MODULO"];
                $idPantalla         = $fila["ID_PANTALLA"];
                $descripcion        = $fila["DESCRIPCION"];
                $idSolicitud        = $fila["ID_SOLICITUD"];
                $solicitador        = $fila["SOLICITADOR"];
                $fecha_solicitud    = $fila["FECHA_SOLICITUD"];
                $fecha_compromiso   = $fila["FECHA_COMPROMISO"];
                $pantalla           = $fila["PANTALLA"];
                $email_solicitador  = $fila["EMAIL_SOLICITADOR"];
                $estado             = $fila["ESTADO"];
                $desarrollador      = $fila["DESARROLLADOR"];

                $data  = ["ID_TIPO_SCMS"      => $idTipoSCMS,
                          "DESC_REQUERIMIENTO"=> $descRequerimiento,
                          "ID_MODULO"         => $idModulo,
                          "ID_PANTALLA"       => $idPantalla,
                          "DESCRIPCION"       => $descripcion,
                          "ID_SOLICITUD"      => $idSolicitud,
                          "SOLICITADOR"       => $solicitador,
                          "FECHA_SOLICITUD"   => $fecha_solicitud,
                          "FECHA_COMPROMISO"  => $fecha_compromiso,
                          "PANTALLA"          => $pantalla,
                          "EMAIL_SOLICITADOR" => $email_solicitador,
                          "ESTADO"            => $estado,
                          "DESARROLLADOR"     => $desarrollador
                         ];
            }

            return $data;
        }else{
            return false;
        }

    }

    public function EnviarCorreo($idScms,$tipoCorreo = "A"){

        /**
         *A = Solicitud para validar (departamento de calidad).
         *B = Solicitud rechazada por calidad.
         *C = Solicitud aprobada por calidad.
         *D = Solicitud para asignar a un desarrollador.
         *E = Solicitud anulada.
         *F = Solicitud asignada a un desarrollador.
         *G = Solicitud finalizada por un desarrollador.
         *H = Solicitud rechazada por el solicitante.
         *I = Solicitud aprobada por el solicitante.
         *J = Solicitud iniciada por el desarrollador.
         *K = Solicitud preaprobada para desarrollo.
         *L = Solicitud desaprobada para desarrollo.
         *M = Solicitud anulada automáticamente.
         *N = Solicitud aprobada automáticamente.
        */

        $plantilla             = '';
        $asunto                = '';
        $destinatario          = '';
        $remitente             = '';
        $informacion_adicional = ''; //Es el apartado en el correo para especificar información adicional.
        $desarrollador         = '';
        $cc                    = '';
        $datosSolicitud        = $this->getSCMSData($idScms);
        $comentarios           = $this->getComentarios($idScms,0,true); //Consigue los comentarios de la solicitud.
        $cantidad_comentarios  = count($comentarios);                   //Obtiene la cantidad de comentarios.
        $ultimo_comentario     = ($cantidad_comentarios > 0) ? $comentarios[$cantidad_comentarios-1] : null; //Obtiene los datos del último comentario.
        $comentario            = [
                                    "COMENTARIO"      => $ultimo_comentario[0],
                                    "USUARIO_COMENTA" => ucwords(strtolower($ultimo_comentario[1]))
                                 ];
        $plantillaCorreoObject = new PlantillaCorreo();
        $correoObject          = new correo();
        $modeloUsuario         = new Usuario();

        switch ($tipoCorreo){

            case "A":
                $remitente              = $datosSolicitud["EMAIL_SOLICITADOR"];
                $destinatario           = 'calidad@aceadom.local';
                $asunto                 = 'Solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' lista para validar.';
                $informacion_adicional  = 'Solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' está lista para ser validada por el departamento de gestión de calidad.';
                $plantilla              = $plantillaCorreoObject->SolicitudParaValidarCalidad($datosSolicitud,$informacion_adicional);
                break;
            case "B":
                $remitente              = 'calidad@aceadom.local';
                $destinatario           = $datosSolicitud["EMAIL_SOLICITADOR"];
                $asunto                 = 'Solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido rechazada.';
                $informacion_adicional  = 'Solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido rechazada por el departamento de gestión de calidad, por el siguiente motivo: ';
                $plantilla              = $plantillaCorreoObject->SolicitudAtendidaPorCalidad($datosSolicitud,$informacion_adicional,$comentario);
                break;
            case "C":
                $remitente              = 'calidad@aceadom.local';
                $destinatario           = $datosSolicitud["EMAIL_SOLICITADOR"];
                $asunto                 = 'Solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido aprobada.';
                $informacion_adicional  = 'Solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido aprobada por el departamento de gestión de calidad.';
                $plantilla              = $plantillaCorreoObject->SolicitudAtendidaPorCalidad($datosSolicitud,$informacion_adicional);
                break;
            case "D":
                $remitente              = 'desarrollo@aceadom.local';
                $destinatario           = 'desarrollo@aceadom.local';
                $asunto                 = 'Solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' lista para asignarle un desarrollador.';
                $informacion_adicional  = $asunto;
                $plantilla              = $plantillaCorreoObject->SolicitudParaAsignar($datosSolicitud,$informacion_adicional);
                break;
            case "E":
                $remitente             = 'desarrollo@aceadom.local';
                $destinatario          = $datosSolicitud["EMAIL_SOLICITADOR"];
                $asunto                = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido anulada.';
                $informacion_adicional = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido anulada por el departamento de informática.';
                $plantilla             = $plantillaCorreoObject->SolicitudAnulada($datosSolicitud,$informacion_adicional, $comentario);
                break;
            case "F":
                $remitente             = 'desarrollo@aceadom.local';
                $destinatario          = $datosSolicitud["EMAIL_SOLICITADOR"];
                $cc                    = [$remitente];
                $desarrollador         = ucwords(strtolower($datosSolicitud["DESARROLLADOR"]));
                $asunto                = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido asignada a un desarrollador.';
                $informacion_adicional = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido asignada al desarrollador '.$desarrollador.'.';
                $plantilla             = $plantillaCorreoObject->SolicitudAsignada($datosSolicitud,$informacion_adicional,$desarrollador);
                break;
            case "G":
                $remitente             = 'desarrollo@aceadom.local';
                $destinatario          = $datosSolicitud["EMAIL_SOLICITADOR"];
                $desarrollador         = ucwords(strtolower($datosSolicitud["DESARROLLADOR"]));
                $asunto                = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' finalizada.';
                $informacion_adicional = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido finalizada por '.$desarrollador.'.';
                $plantilla             = $plantillaCorreoObject->SolicitudFinalizada($datosSolicitud,$informacion_adicional,$desarrollador);
                break;
            case "H":
                $remitente             = $datosSolicitud["EMAIL_SOLICITADOR"];
                $destinatario          = 'desarrollo@aceadom.local';
                $desarrollador         = ucwords(strtolower($datosSolicitud["DESARROLLADOR"]));
                $asunto                = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido desaprobada por el solicitante.';
                $informacion_adicional = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido desaprobada por el solicitante por la siguiente razón: ';
                $plantilla             = $plantillaCorreoObject->SolicitudRechazadaPorSolicitante($datosSolicitud,$informacion_adicional,$desarrollador,$comentario);
                break;
            case "I":
                $remitente             = $datosSolicitud["EMAIL_SOLICITADOR"];
                $destinatario          = 'desarrollo@aceadom.local';
                $desarrollador         = ucwords(strtolower($datosSolicitud["DESARROLLADOR"]));
                $datosUsuarioRevisa    = $modeloUsuario->getUsuarioPorCargo(111);
                $correoUsuarioRevisa   = oci_fetch_assoc($datosUsuarioRevisa)["EMAIL"];
                $cc                    = [$correoUsuarioRevisa];
                $asunto                = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido aprobada por el solicitante.';
                $informacion_adicional = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido aprobada por el solicitante.';
                $plantilla             = $plantillaCorreoObject->SolicitudAprobadaPorSolicitante($datosSolicitud,$informacion_adicional,$desarrollador);
                break;
            case "J":
                $remitente             = 'desarrollo@aceadom.local';
                $destinatario          = $datosSolicitud["EMAIL_SOLICITADOR"];
                $desarrollador         = ucwords(strtolower($datosSolicitud["DESARROLLADOR"]));
                $asunto                = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido iniciada por el desarrollador.';
                $informacion_adicional = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido iniciada por '.$desarrollador.'.';
                $plantilla             = $plantillaCorreoObject->SolicitudIniciadaPorDesarrollador($datosSolicitud,$informacion_adicional,$desarrollador);
                break;
            case "K":
                $remitente             = 'desarrollo@aceadom.local';
                $destinatario          = $datosSolicitud["EMAIL_SOLICITADOR"];
                $desarrollador         = ucwords(strtolower($datosSolicitud["DESARROLLADOR"]));
                $asunto                = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido preaprobada para desarrollo.';
                $informacion_adicional = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido preaprobada para desarrollo.';
                $plantilla             = $plantillaCorreoObject->SolicitudIniciadaPorDesarrollador($datosSolicitud,$informacion_adicional,$desarrollador);
                break;
            case "L":
                $remitente             = 'desarrollo@aceadom.local';
                $destinatario          = $datosSolicitud["EMAIL_SOLICITADOR"];
                $desarrollador         = ucwords(strtolower($datosSolicitud["DESARROLLADOR"]));
                $asunto                = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido desaprobada para desarrollo.';
                $informacion_adicional = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido desaprobada para desarrollo.';
                $plantilla             = $plantillaCorreoObject->SolicitudAnulada($datosSolicitud,$informacion_adicional, $comentario);
                break;
            case "M":
                $remitente             = 'desarrollo@aceadom.local';
                $destinatario          = $datosSolicitud["EMAIL_SOLICITADOR"];
                $desarrollador         = ucwords(strtolower($datosSolicitud["DESARROLLADOR"]));
                $asunto                = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido anulada automáticamente.';
                $informacion_adicional = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido anulada porque no ha modificado la solicitud luego de ser desaprobada para desarrollo.';
                $plantilla             = $plantillaCorreoObject->SolicitudAnulada($datosSolicitud,$informacion_adicional, $comentario);
                break;
            case "N":
                $remitente             = $datosSolicitud["EMAIL_SOLICITADOR"];
                $destinatario          = 'desarrollo@aceadom.local';
                $desarrollador         = ucwords(strtolower($datosSolicitud["DESARROLLADOR"]));
                $datosUsuarioRevisa    = $modeloUsuario->getUsuarioPorCargo(111);
                $correoUsuarioRevisa   = oci_fetch_assoc($datosUsuarioRevisa)["EMAIL"];
                $cc                    = [$correoUsuarioRevisa];
                $asunto                = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido aprobada automáticamente.';
                $informacion_adicional = 'La solicitud #'.$datosSolicitud["ID_SOLICITUD"].' de '.$datosSolicitud["SOLICITADOR"].' ha sido aprobada automáticamente.';
                $plantilla             = $plantillaCorreoObject->SolicitudAprobadaPorSolicitante($datosSolicitud,$informacion_adicional,$desarrollador);
                break;
        };

        $correoObject->enviarCorreoSolicitudes($plantilla,$remitente,$destinatario,$asunto,$cc);
    }

    public function SolicitanteAprueba($idScms){
        $sql = "BEGIN
                    sgc_pk_SCMS.aprobacion_scms(:ID_SCMS_IN,
                                                :PMSGERROR,
                                                :PCODERROR); 
                    COMMIT;
                END;";

        $result = oci_parse($this->_db, $sql);

        oci_bind_by_name($result,":ID_SCMS_IN", $idScms,    20);
        oci_bind_by_name($result,":PMSGERROR",  $msjerror, 200);
        oci_bind_by_name($result,":PCODERROR",  $coderror,  10);

        $bandera = oci_execute($result);

        if($bandera){
            return [
                        "Code"   => $coderror,
                        "Status" => $msjerror
                    ];
        }else{
            $ociError = oci_error($result);
            return [
                        "Code"   => "01",
                        "Status" => $ociError["message"]
                   ];
        }
    }

    public function SolicitudesParaAprobar(){
        $sql = "
                    SELECT  S.ID_SCMS
                    FROM SGC_TT_SCMS S
                    WHERE (SYSDATE -S.FECHA_CONCLUCION) >=  7
                      AND S.VALIDA_SOLICITANTE = 'N'
                      AND S.FECHA_VALIDA_SOLICITANTE IS NULL
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);

        if($bandera){
            return $resultado;
        }else{
            return false;
        }
    }

    public function desaprobarDesarrollo($idSolicitud, $usuarioDesaprueba, $comentario){
        $sql = "BEGIN sgc_pk_scms.desaprueba_desarrollo(:ID_SCMS_IN            ,
                                                        :USUARIO_DESAPRUEBA_IN ,
                                                        :COMENTARIO_IN         ,
                                                        :PMSG_ERR_OUT
                                                    ); COMMIT; END;";

        $statement = oci_parse($this->_db, $sql);

        oci_bind_by_name($statement, ":ID_SCMS_IN", $idSolicitud,4);
        oci_bind_by_name($statement, ":USUARIO_DESAPRUEBA_IN", $usuarioDesaprueba,13);
        oci_bind_by_name($statement, ":COMENTARIO_IN", $comentario,2500);
        oci_bind_by_name($statement, ":PMSG_ERR_OUT", $msgout,500);      

        return oci_execute($statement);
    }

    public function solicitudesParaAnular(){
        $sql = "SELECT S.ID_SCMS
        FROM SGC_TT_SCMS S,
             (SELECT MAX(MS.ID_MOVIMIENTO),
                     MS.ID_SCMS,
                     MS.ESTADO_SOLICITUD,
                     MS.FECHA
                FROM SGC_TT_MOVIMIENTO_SCMS MS
               WHERE MS.ID_MOVIMIENTO =
                     (SELECT MAX(MS1.ID_MOVIMIENTO)
                        FROM SGC_TT_MOVIMIENTO_SCMS MS1
                       WHERE MS.ID_SCMS = MS1.ID_SCMS)
               GROUP BY MS.ID_SCMS, MS.ESTADO_SOLICITUD, MS.FECHA) MS
        WHERE S.ID_SCMS = MS.ID_SCMS(+)
            AND (MS.FECHA - SYSDATE) >= 3
            AND MS.ESTADO_SOLICITUD(+) = 'DPR'
        ";
        
        $statement = oci_parse($this->_db, $sql);
        oci_execute($statement);

        return $statement;
    }

    public function __destruct(){
        oci_close($this->_db);
    }
}
