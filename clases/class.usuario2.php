<?php
include_once "class.conexion.php";

class Usuario extends ConexionClass
{

    private $mesrror;
    private $coderror;
    //private $_query;

    private function _consultar($sql)
    {
        //$link = new OracleConn(UserGeneral, PassGeneral)->link;
        // Preparar la sentencia
        $parse = oci_parse($this->_db, $sql);
        if (!$parse) {
            $e = oci_error($this->_db);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        // Realizar la lógica de la consulta
        $result = oci_execute($parse);
        if (!$r) {
            $e = oci_error($parse);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        //$result = oci_fetch_array($parse);
        // oci_fetch_all($parse, $result);
        oci_close($this->_db);
        oci_free_statement($parse);
        return $result;
    }
    private function _setQuery($caso)
    {
        switch ($caso) {
            case 'getUsrByusuPas':
                return;
                break;

            default:
                # code...
                break;
        }
    }
    public function getMesrror()
    {
        return $this->mesrror;
    }

    public function getCoderror()
    {
        return $this->coderror;
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function getUsrByusuPas($user, $pass)
    {
        $user = addslashes($user);
        $pass = addslashes($pass);
        $sql  = "SELECT
                ID_USUARIO USUARIO,
                NOM_USR NOMBRE,
                APE_USR APELLIDO
              FROM
                SGC_TT_USUARIOS
              WHERE
                LOGIN = upper('$user') AND
                PASS = '$pass' AND
                FEC_FIN IS NULL
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getPerfUsrByusu($user, $url)
    {
        $user = addslashes($user);
        $url  = addslashes($url);
        $sql  = "
            SELECT
              COUNT(1) CANTIDAD
            FROM
              SGC_TP_PERFILES P,
              SGC_TP_MENUS M
            WHERE
              P.ID_USUARIO='$user' AND
              P.ID_MENU=M.ID_MENU AND
              M.URL LIKE'%$url%'
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getUsrMedByuProy($proy)
    {
        $proy = addslashes($proy);
        $sql  = "SELECT
                (US.NOM_USR||' '||US.APE_USR) NOMBRE,
                US.ID_USUARIO CODIGO
              FROM
                SGC_TT_USUARIOS US
              WHERE
                US.MEDIDORES='S' AND
                US.ID_PROYECTO='$proy'
                AND US.FEC_FIN IS NULL
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getUsrLecByProy($proy)
    {
        $proy = addslashes($proy);
        $sql  = "SELECT
                (US.NOM_USR||' '||US.APE_USR) NOMBRE,
                US.ID_USUARIO CODIGO
              FROM
                SGC_TT_USUARIOS US
              WHERE
                US.LECTURA='S' AND
                US.ID_PROYECTO='$proy'
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getOperariosCorte()
    {

        $sql = "Select U.ID_USUARIO CODIGO, U.LOGIN DESCRIPCION from sgc_tt_usuarios u
              WHERE  U.CORTES='S'";
        $resourse = oci_parse($this->_db, $sql);
        $bandera  = oci_execute($resourse);
        if ($bandera) {
            oci_close($this->_db);
            return $resourse;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function getOperariosCorteByPro($pro)
    {

        $sql = "Select U.ID_USUARIO CODIGO, U.LOGIN DESCRIPCION,U.NOM_USR NOMBRE, U.APE_USR APELLIDO from sgc_tt_usuarios u
              WHERE  U.CORTES='S'
              AND U.ID_PROYECTO='$pro'
              AND U.FEC_FIN IS NULL ";
        $resourse = oci_parse($this->_db, $sql);
        $bandera  = oci_execute($resourse);
        if ($bandera) {
            oci_close($this->_db);
            return $resourse;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function getUsrMntMedBySec($sec)
    {
        $sec = addslashes($sec);
        $sql = "SELECT
                (I.ID_SECTOR||I.ID_RUTA) RUTA,
                COUNT(1) CANTIDAD,
                (US.NOM_USR||' '||US.APE_USR) NOMBRE,
                US.ID_USUARIO
              FROM
                SGC_TT_MANT_CORRMED MC,
                SGC_TT_INMUEBLES I,
                SGC_TT_USUARIOS US
              WHERE
                I.CODIGO_INM=MC.CODIGO_INM AND
                US.ID_USUARIO(+)=MC.USUARIO_ASIGNADO AND
                MC.ESTADO='A' and
                I.ID_SECTOR='$sec'
              GROUP BY
                (I.ID_SECTOR||I.ID_RUTA),
                (US.NOM_USR||' '||US.APE_USR),
                US.ID_USUARIO
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getUsrMantPreBySec($sec)
    {
        $sec = addslashes($sec);
        $sql = "SELECT
                (I.ID_SECTOR||I.ID_RUTA) RUTA,
                COUNT(1) CANTIDAD,
                (US.NOM_USR||' '||US.APE_USR) NOMBRE,
                US.ID_USUARIO
              FROM
                SGC_TT_MANT_MED MC,
                SGC_TT_INMUEBLES I,
                SGC_TT_USUARIOS US
              WHERE
                I.CODIGO_INM=MC.CODIGO_INM AND
                US.ID_USUARIO(+)=MC.USUARIO_ASIGNADO AND
                I.ID_SECTOR='$sec' AND
                MC.ESTADO='A'
              GROUP BY
                (I.ID_SECTOR||I.ID_RUTA),
                (US.NOM_USR||' '||US.APE_USR),
                US.ID_USUARIO
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function asignaUsrMantCorr($usrasignado, $usrAsignador, $ruta)
    {

        $sql       = "BEGIN SGC_P_ASIGNA_MANT_CORR('$usrasignado','$usrAsignador','$ruta',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 500);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);
        $bandera = oci_execute($resultado);
        oci_close($this->_db);

        if ($bandera == true) {
            if ($this->coderror > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function asignaUsrMantPre($usrasignado, $usrAsignador, $ruta)
    {

        $sql       = "BEGIN SGC_P_ASIGNA_MANT_PRE('$usrasignado','$usrAsignador','$ruta',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 500);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);
        $bandera = oci_execute($resultado);
        oci_close($this->_db);

        if ($bandera == true) {
            if ($this->coderror > 0) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function getPerfRepByAreUsu($area, $usu)
    {
        $area = addslashes($area);
        $usu  = addslashes($usu);
        $sql  = "SELECT
                RG.NOMBRE,
                RG.LOGO,RG.URL
              FROM
                SGC_TP_REPORTES_GEN RG,
                SGC_TP_PERFILES_REPORTES PR
              WHERE
                RG.ID_AREA='$area' AND
                RG.ID_REPORTE=PR.ID_PERFIL_REP AND
                PR.ID_USUARIO='$usu' AND
                PR.ACTIVO='S'
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getOperCorByProSecZon($proy, $sec, $zon)
    {
        $sql = "SELECT R.USR_EJE CODIGO, CONCAT(CONCAT(U.NOM_USR,' '),U.APE_USR) DESCRIPCION FROM SGC_TT_REGISTRO_CORTES R, SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U
              WHERE I.CODIGO_INM=R.ID_INMUEBLE
              AND R.USR_EJE=U.ID_USUARIO
              AND R.USR_EJE IS NOT NULL
              AND R.FECHA_EJE IS NULL
              AND R.REVERSADO='N'
              AND R.PERVENC='N'
              AND R.FECHA_ACUERDO IS NULL
              ";
        if (trim($proy)) {
            $sql = $sql . " AND I.ID_PROYECTO='$proy' ";
        }
        if (trim($sec) != "") {
            $sql = $sql . " AND I.ID_SECTOR=$sec ";
        }
        if (trim($zon) != "") {
            $sql = $sql . " AND I.ID_zona='$zon' ";
        }
        $sql = $sql . " GROUP BY (R.USR_EJE , CONCAT(CONCAT(U.NOM_USR,' '),U.APE_USR)) ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getUsrAsignadoAsignadorCorteByFecha($fecha, $proyecto, $gerencia)
    {
        $where = "";
        if (trim($gerencia) != "") {
            $where = " AND S.ID_GERENCIA='$gerencia'";
        }
        $sql = "select USR_EJE ID_ASIGNADO,
              USUARIO_ASIGNADOR ID_ASIGNADOR,
              (SELECT CONCAT(CONCAT(US.NOM_USR,' '),US.APE_USR) FROM SGC_TT_USUARIOS US WHERE RC.USR_EJE=US.ID_USUARIO) USR_EJE,
              (SELECT CONCAT(CONCAT(US.NOM_USR,' '),US.APE_USR) FROM SGC_TT_USUARIOS US WHERE RC.USUARIO_ASIGNADOR =US.ID_USUARIO) USUARIO_ASIGNADOR
              ,COUNT(1) CANTIDAD

              from sgc_tt_registro_cortes rc,
                SGC_TT_INMUEBLES I,
                SGC_TP_SECTORES S
              where
              RC.USR_EJE is not null AND
              I.CODIGO_INM=RC.ID_INMUEBLE AND
              I.ID_PROYECTO='$proyecto'
              and RC.USUARIO_ASIGNADOR is not null
              and TO_CHAR(RC.FECHA_PLANIFICACION,'YYYY-MM-DD')='$fecha'
              AND I.ID_SECTOR=S.ID_SECTOR
              $where
              group by (RC.USR_EJE, RC.USUARIO_ASIGNADOR)";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getUsrAsignadoAsignadorInspByFecha($fecha, $proyecto, $gerencia,$contratista)
    {

        $where = "";
        if (trim($gerencia) != "") {
            $where = " AND S.ID_GERENCIA='$gerencia'";
        }
        $sql = "SELECT IC.USR_ASIG ID_ASIGNADO,
                   IC.USR_APER ID_ASIGNADOR,
                   
                   (SELECT CONCAT(CONCAT(US.NOM_USR, ' '), US.APE_USR)
                      FROM SGC_TT_USUARIOS US
                     WHERE IC.USR_ASIG = US.ID_USUARIO ) USR_EJE,
                   
                   (SELECT CONCAT(CONCAT(US.NOM_USR, ' '), US.APE_USR)
                      FROM SGC_TT_USUARIOS US
                     WHERE IC.USR_APER = US.ID_USUARIO) USUARIO_ASIGNADOR,
                   COUNT(1) CANTIDAD

              from sgc_tt_inspecciones_cortes ic, SGC_TT_INMUEBLES I, SGC_TP_SECTORES S, SGC_TT_USUARIOS U
             where IC.FECHA_EJE is not null
               AND I.CODIGO_INM = IC.CODIGO_INM
               AND I.ID_PROYECTO = '$proyecto'
               AND IC.USR_ASIG=U.ID_USUARIO
             AND U.CONTRATISTA='$contratista'
               and IC.USR_ASIG is not null
               and TO_CHAR(IC.FECHA_PLANIFICACION, 'YYYY-MM-DD') = '$fecha'
               AND I.ID_SECTOR = S.ID_SECTOR
                $where
             group by (IC.USR_ASIG, IC.USR_APER)";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getOperInsAbiByProSecZon($proy, $sec, $zon)
    {
        $sql = "SELECT R.USR_ASIG USR_EJE , CONCAT(CONCAT(U.NOM_USR,' '),U.APE_USR) NOMBRE FROM SGC_TT_INSPECCIONES_CORTES R, SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U
              WHERE I.CODIGO_INM=R.CODIGO_INM
              AND R.USR_ASIG=U.ID_USUARIO
              AND R.USR_ASIG IS NOT NULL
              AND R.FECHA_EJE IS NULL";
        if (trim($proy)) {
            $sql = $sql . " AND I.ID_PROYECTO='$proy' ";
        }
        if (trim($sec) != "") {
            $sql = $sql . " AND I.ID_SECTOR=$sec ";
        }
        if (trim($zon) != "") {
            $sql = $sql . " AND I.ID_zona='$zon' ";
        }
        $sql = $sql . " GROUP BY (R.USR_ASIG , CONCAT(CONCAT(U.NOM_USR,' '),U.APE_USR)) ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getOperRecAbiProSecZon($proy, $sec, $zon)
    {
        $sql = "SELECT R.USR_EJE , CONCAT(CONCAT(U.NOM_USR,' '),U.APE_USR) NOMBRE FROM SGC_TT_REGISTRO_RECONEXION R, SGC_TT_INMUEBLES I, SGC_TT_USUARIOS U
              WHERE I.CODIGO_INM=R.ID_INMUEBLE
              AND R.USR_EJE=U.ID_USUARIO
              AND R.USR_EJE IS NOT NULL
              AND R.FECHA_EJE IS NULL";
        if (trim($proy)) {
            $sql = $sql . " AND I.ID_PROYECTO='$proy' ";
        }
        if (trim($sec) != "") {
            $sql = $sql . " AND I.ID_SECTOR=$sec ";
        }
        if (trim($zon) != "") {
            $sql = $sql . " AND I.ID_zona='$zon' ";
        }
        $sql = $sql . " GROUP BY (R.USR_EJE , CONCAT(CONCAT(U.NOM_USR,' '),U.APE_USR)) ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getUsrAsignadoAsignadorRecByFecha($fecha, $proyecto, $gerencia)
    {
        $where = "";
        if (trim($gerencia) != "") {
            $where = " AND S.ID_GERENCIA='$gerencia'";
        }

        $sql = "select USR_EJE ID_ASIGNADO,
              USUARIO_ASIGNACION ID_ASIGNADOR,
              (SELECT CONCAT(CONCAT(US.NOM_USR,' '),US.APE_USR) FROM SGC_TT_USUARIOS US WHERE RC.USR_EJE=US.ID_USUARIO) USR_EJE,
              (SELECT CONCAT(CONCAT(US.NOM_USR,' '),US.APE_USR) FROM SGC_TT_USUARIOS US WHERE RC.USUARIO_ASIGNACION =US.ID_USUARIO) USUARIO_ASIGNADOR
              ,COUNT(1) CANTIDAD

              from sgc_tt_registro_RECONEXION rc,
                SGC_TT_INMUEBLES I,
                SGC_TP_SECTORES S
              where
              RC.USR_EJE is not null
              and RC.USUARIO_ASIGNACION is not null
              AND I.CODIGO_INM=RC.ID_INMUEBLE
              AND I.ID_PROYECTO='$proyecto'
              and TO_CHAR(RC.FECHA_PLANIFICACION,'YYYY-MM-DD')='$fecha'
              AND I.ID_SECTOR=S.ID_SECTOR
              $where
              group by (RC.USR_EJE, RC.USUARIO_ASIGNACION)";
        $resultado = oci_parse($this->_db, $sql);
        $bandera   = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getNomOperByCod($operario)
    {
        $resultado = oci_parse($this->_db, "SELECT ID_USUARIO, NOM_USR, APE_USR  FROM SGC_TT_USUARIOS
                WHERE ID_USUARIO='$operario' ");

        $banderas = oci_execute($resultado);
        if ($banderas == true) {
            while (oci_fetch($resultado)) {
                $nombre   = oci_result($resultado, 'NOM_USR');
                $apellido = oci_result($resultado, 'APE_USR');

            }oci_free_statement($resultado);
            oci_close($this->_db);
            return $nombre . " " . $apellido;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }

    }

    public function getAreaUsuByUsu($coduser)
    {
        $sql = "SELECT A.ID_AREA
        FROM SGC_TT_USUARIOS U, SGC_TP_CARGOS C, SGC_TP_AREAS A
        WHERE U.ID_CARGO = C.ID_CARGO
        AND C.ID_AREA = A.ID_AREA
        AND U.ID_USUARIO = '$coduser'
        AND U.FEC_FIN IS NULL ";
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

    public function getUsuariosByContratista($contratista)
    {
        if (trim($contratista != '')) {$where = "U.CONTRATISTA='$contratista' AND";}
         $sql = "Select U.ID_USUARIO, U.LOGIN from sgc_tt_usuarios u
              WHERE $where U.MEDIDORES='S' AND U.FEC_FIN IS NULL";
        $resourse = oci_parse($this->_db, $sql);
        $bandera  = oci_execute($resourse);
        if ($bandera) {
            oci_close($this->_db);
            return $resourse;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

}
