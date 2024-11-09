<?php
require '../../clases/class.conexion.php';

/**
 * Clase general de solicitudes TI para realizar las solicitudes
 */
class Solicitar extends ConexionClass
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getMenus($usuario)
    {
        /*$sql = "SELECT m.id_menu, m.desc_menu
          FROM sgc_tp_perfiles p, sgc_tp_menus m
         WHERE p.id_menu = m.id_menu
           AND p.id_usuario = '$usuario'
           AND (p.activo = 'S' AND m.activo = 'S')
           AND (m.id_padre is null or m.id_padre = 0)";*/

        //print_r($usuario);

        $idUsuario = $usuario["ID_USUARIO"];

        if($usuario["ID_CARGO"] == "111" || $usuario["ID_CARGO"] == "112" || $usuario["ID_CARGO"] == "600" ){
            $sql = "SELECT m.id_menu, m.desc_menu
                FROM sgc_tp_perfiles p, sgc_tp_menus m
                WHERE p.id_menu = m.id_menu
                  AND p.id_usuario = '$idUsuario'
                  AND (p.activo = 'S' AND m.activo = 'S')
                  AND (m.id_padre is null or m.id_padre = 0)";
        }else{
            $sql = "SELECT m.id_menu, m.desc_menu
                FROM sgc_tp_perfiles p, sgc_tp_menus m,SGC_TP_MODULOS MD
                WHERE p.id_menu = m.id_menu
                  AND p.id_usuario = '$idUsuario'
                  AND (p.activo = 'S' AND m.activo = 'S')
                  AND (m.id_padre is null or m.id_padre = 0)
                  AND  MD.ID_MODULO = M.ID_MODULO
                  AND  MD.AREA_PERTENECE IN (SELECT A.ID_AREA
                                          FROM SGC_TT_USUARIOS U,SGC_TP_CARGOS C,SGC_TP_AREAS A
                                          WHERE  C.ID_CARGO   = U.ID_CARGO
                                            AND  A.ID_AREA    = C.ID_AREA
                                            AND  U.ID_USUARIO = p.ID_USUARIO)";
        }

        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getPantalla($usuario, $id_menu){

        $sql = "SELECT m.id_menu, m.desc_menu
				  FROM sgc_tp_perfiles p, sgc_tp_menus m
				 WHERE p.id_menu = m.id_menu
				   AND p.id_usuario = '$usuario'
				   AND m.id_padre = $id_menu
				   AND (p.activo = 'S' AND m.activo = 'S')
				   union 
				   SELECT rg.ID_AREA, rg.NOMBRE
				  FROM SGC_TP_PERFILES_REPORTES pr, SGC_TP_REPORTES_GEN rg
				 WHERE pr.ID_PERFIL_REP = rg.ID_REPORTE 
				   AND pr.id_usuario = '$usuario'
				   AND pr.activo = 'S'";

        $resultado = oci_parse($this->_db, $sql);

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function setSolicitud($usuario, $datos){

        $sql = "BEGIN
				  sgc_pk_SCMS.interaciones_SCMS(pSOLICITADO  => :usuarios,
				                                pID_MODULO   => :sltMenu,
				                                pDESCRIPCION => :obsRequerimiento,
				                                pTip_SCMS    => :optTipRequermiento,
				                                pid_pantalla => :sltPantalla,
				                                MSG_ERR_OUT  => :errmsg,
				                                pID_SCMS     => :id_scms);
				END;";

        $resultado = oci_parse($this->_db, $sql);

        oci_bind_by_name($resultado, ':usuarios', $usuario, 14);
        oci_bind_by_name($resultado, ':sltMenu', $datos['sltMenu'], 5);
        oci_bind_by_name($resultado, ':obsRequerimiento', $datos['obsRequerimiento'], 4000);
        oci_bind_by_name($resultado, ':optTipRequermiento', $datos['optTipRequermiento'], 5);
        oci_bind_by_name($resultado, ':sltPantalla', $datos['sltPantalla'], 5);
        oci_bind_by_name($resultado, ':errmsg', $errmsg, 100);
        oci_bind_by_name($resultado, ':id_scms', $id_scms, 5);

        if (oci_execute($resultado)) {
            oci_close($this->_db);
            return [
                    "Code"   => "00",
                    "Status" => "Success.",
                    "Data"   => ["Id_scms"=>$id_scms]
                   ];
        } else {
            oci_close($this->_db);
            return [
                "Code"   => "01",
                "Status" => "Success.",
                "Data"   => $errmsg
            ];
        }
    }

    public function editaSolicitud($datos){

        
        $sql = "BEGIN sgc_pk_scms.edita_scms(:ID_SCMS_IN,:ID_MODULO_IN,:ID_TIPO_SCMS_IN,:DESCRIPCION_IN,:ID_PANTALLA_IN, :PMSJERROR_OUT,:PCODERROR_OUT); COMMIT; END;";

        $resultado = oci_parse($this->_db, $sql);

        oci_bind_by_name($resultado, ':ID_SCMS_IN'     , $datos['idSCMS'],              10);
        oci_bind_by_name($resultado, ':ID_MODULO_IN'   , $datos['sltMenu'],             10);
        oci_bind_by_name($resultado, ':ID_TIPO_SCMS_IN', $datos['optTipRequermiento'],  10);
        oci_bind_by_name($resultado, ':DESCRIPCION_IN' , $datos['obsRequerimiento'],  4000);
        oci_bind_by_name($resultado, ':ID_PANTALLA_IN' , $datos['sltPantalla'],         10);
        oci_bind_by_name($resultado, ':PMSJERROR_OUT'  , $msjError,                    500);
        oci_bind_by_name($resultado, ':PCODERROR_OUT'  , $coderror,                     10);

        if (oci_execute($resultado)) {
            oci_close($this->_db);
            return true;
        } else {
            oci_close($this->_db);
            return $msjError;
        }
    }
}
