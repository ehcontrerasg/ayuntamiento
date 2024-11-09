<?php
include_once "class.conexion.php";

class Usuario extends ConexionClass
{

    private $mesrror;
    private $coderror;

    public function registrar($cedula, $nombres, $apellidos, $idCargo, $fechaInicio, $proyecto, $contratista, $creador){

       $sql = "BEGIN  SGC_P_CREAR_USUARIO(:CEDULA_IN, :NOMBRE_IN, :APELLIDO_IN, :IDCARGO_IN, :FECHA_INICIO_IN, :PROYECTO_IN, :CONTRATISTA_IN, :CREADOR_IN,:PMSJ,:PCOD); COMMIT; END;";
        $statement = oci_parse($this->_db,$sql);

        oci_bind_by_name($statement,':CEDULA_IN',$cedula);
        oci_bind_by_name($statement,':NOMBRE_IN',$nombres);
        oci_bind_by_name($statement,':APELLIDO_IN',$apellidos);
        oci_bind_by_name($statement,':IDCARGO_IN',$idCargo);
        oci_bind_by_name($statement,':FECHA_INICIO_IN',$fechaInicio);
        oci_bind_by_name($statement,':PROYECTO_IN',$proyecto);
        oci_bind_by_name($statement,':CONTRATISTA_IN',$contratista);
        oci_bind_by_name($statement,':CREADOR_IN',$creador);
        oci_bind_by_name($statement,':PMSJ',$pMsj,500);
        oci_bind_by_name($statement,':PCOD',$pCod);

        if(oci_execute($statement)){
            return array("codigo" => $pCod, "mensaje" => $pMsj);
        }else{
            return array("codigo" => $pCod, "mensaje" => oci_error($statement));
        }

    }
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
        if (!$result) {
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
        $sql = "SELECT
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
        $bandera = oci_execute($resultado);
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
        $url = addslashes($url);
        $sql = "
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
        $bandera = oci_execute($resultado);
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
        $sql = "SELECT
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
        $bandera = oci_execute($resultado);
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
        $sql = "SELECT
                (US.NOM_USR||' '||US.APE_USR) NOMBRE,
                US.ID_USUARIO CODIGO
              FROM
                SGC_TT_USUARIOS US
              WHERE
                US.LECTURA='S' AND
                US.ID_PROYECTO='$proy'
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);
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
        $bandera = oci_execute($resourse);
        if ($bandera) {
            oci_close($this->_db);
            return $resourse;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }




    public function getOperariosFactura()
    {

        $sql = "Select U.ID_USUARIO CODIGO, U.LOGIN DESCRIPCION from sgc_tt_usuarios u
              WHERE  U.LECTURA='S'";
        $resourse = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resourse);
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

         /*$sql = "Select U.ID_USUARIO CODIGO, U.LOGIN DESCRIPCION,U.NOM_USR NOMBRE, U.APE_USR APELLIDO from sgc_tt_usuarios u
              WHERE  U.CORTES='S'
              AND U.ID_PROYECTO='$pro'
              AND U.FEC_FIN IS NULL ";    */

         $sql = "Select U.ID_USUARIO CODIGO, U.LOGIN DESCRIPCION,U.NOM_USR NOMBRE, U.APE_USR APELLIDO from sgc_tt_usuarios u
              WHERE  U.CORTES='S'
              AND U.FEC_FIN IS NULL ";
        $resourse = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resourse);
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
                SGC_TT_USUARIOS US,
                SGC_TT_MEDIDOR_INMUEBLE MI
              WHERE
                I.CODIGO_INM=MC.CODIGO_INM AND
                US.ID_USUARIO(+)=MC.USUARIO_ASIGNADO AND
                MI.COD_INMUEBLE=I.CODIGO_INM AND
                MI.FECHA_BAJA IS NULL AND
                MC.ESTADO='A' AND 
                I.ID_SECTOR='$sec'
              GROUP BY
                (I.ID_SECTOR||I.ID_RUTA),
                (US.NOM_USR||' '||US.APE_USR),
                US.ID_USUARIO
                ";

        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);
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
        $bandera = oci_execute($resultado);
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

        $sql = "BEGIN SGC_P_ASIGNA_MANT_CORR('$usrasignado','$usrAsignador','$ruta',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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

        $sql = "BEGIN SGC_P_ASIGNA_MANT_PRE('$usrasignado','$usrAsignador','$ruta',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
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
        $usu = addslashes($usu);
        $sql = "SELECT
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
        $bandera = oci_execute($resultado);
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
        $bandera = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getUsrAsignadoAsignadorCorteByFecha($fecha, $proyecto, $gerencia, $contratista)
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
                SGC_TP_SECTORES S,
                SGC_TT_USUARIOS U
              where
               RC.USR_EJE=U.ID_USUARIO AND
              RC.USR_EJE is not null AND
              I.CODIGO_INM=RC.ID_INMUEBLE AND
              I.ID_PROYECTO='$proyecto'
              AND U.CONTRATISTA='$contratista'
              and RC.USUARIO_ASIGNADOR is not null
              and TO_CHAR(RC.FECHA_PLANIFICACION,'YYYY-MM-DD')='$fecha'
              AND I.ID_SECTOR=S.ID_SECTOR
              $where
              group by (RC.USR_EJE, RC.USUARIO_ASIGNADOR)";

        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getUsrAsignadoAsignadorInspByFecha($fecha, $proyecto, $gerencia, $contratista)
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
             where 
           --  IC.FECHA_EJE is not null AND
              I.CODIGO_INM = IC.CODIGO_INM
               AND I.ID_PROYECTO = '$proyecto'
               AND IC.USR_ASIG=U.ID_USUARIO
             AND U.CONTRATISTA='$contratista'
               and IC.USR_ASIG is not null
               and TO_CHAR(IC.FECHA_PLANIFICACION, 'YYYY-MM-DD') = '$fecha'
               AND I.ID_SECTOR = S.ID_SECTOR
                $where
             group by (IC.USR_ASIG, IC.USR_APER)";
        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);
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
        $bandera = oci_execute($resultado);
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
        $bandera = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return $resultado;
        } else {
            oci_close($this->_db);
            return false;
        }
    }

    public function getUsrAsignadoAsignadorRecByFecha($fecha, $proyecto, $gerencia, $contratista)
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
                SGC_TP_SECTORES S,
                SGC_TT_USUARIOS U
              where
              RC.USR_EJE is not null
              and RC.USUARIO_ASIGNACION is not null
              AND I.CODIGO_INM=RC.ID_INMUEBLE
              AND RC.USR_EJE=U.ID_USUARIO
              AND U.CONTRATISTA='$contratista'
              AND I.ID_PROYECTO='$proyecto'
              and TO_CHAR(RC.FECHA_PLANIFICACION,'YYYY-MM-DD')='$fecha'
              AND I.ID_SECTOR=S.ID_SECTOR
              $where
              group by (RC.USR_EJE, RC.USUARIO_ASIGNACION)";
        $resultado = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resultado);
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
                $nombre = oci_result($resultado, 'NOM_USR');
                $apellido = oci_result($resultado, 'APE_USR');

            }
            oci_free_statement($resultado);
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
        if (trim($contratista != '')) {
            $where = "U.CONTRATISTA='$contratista' AND";
        }
        $sql = "Select U.ID_USUARIO, U.LOGIN from sgc_tt_usuarios u
              WHERE $where U.MEDIDORES='S' AND U.FEC_FIN IS NULL";
        $resourse = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resourse);
        if ($bandera) {
            oci_close($this->_db);
            return $resourse;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;
        }
    }

    public function getUsuariosCajas()
    {
        $sql = "SELECT U.NOM_USR, U.APE_USR, U.ID_USUARIO,
  CASE
  WHEN U.FEC_FIN is  NULL THEN 'Habilitada'
  ELSE 'Deshabilitada'
  END ESTADO
FROM SGC_TT_USUARIOS U
WHERE ID_CARGO=200";

        $resourse = oci_parse($this->_db, $sql);
        $bandera = oci_execute($resourse);
        if ($bandera) {
            oci_close($this->_db);
            return $resourse;
        } else {
            oci_close($this->_db);
            echo "false";
            return false;

        }

    }

    public function actEstadoCajeras($idUsuario,$estado)
    {
        $idUsuario = addslashes($idUsuario);
        $estado = addslashes($estado);

        $sql       = "BEGIN SGC_P_ACT_ESTADO_CAJERAS('$idUsuario','$estado',:PMSGRESULT,:PCODRESULT);COMMIT;END;";
        $resultado = oci_parse($this->_db, $sql);
        oci_bind_by_name($resultado, ":PMSGRESULT", $this->mesrror, 500);
        oci_bind_by_name($resultado, ":PCODRESULT", $this->coderror);

        $bandera = oci_execute($resultado);
        if ($bandera) {
            oci_close($this->_db);
            return true;
        } else {
            oci_close($this->_db);
            return false;

        }

    }

    public function getUsuariosPorArea($area){

       $sql = "SELECT U.ID_USUARIO,U.NOM_USR||' '||U.APE_USR NOMBRE_USUARIO,U.LOGIN,U.ID_CARGO
                FROM   ACEASOFT.SGC_TT_USUARIOS U,ACEASOFT.SGC_TP_CARGOS C
                WHERE  U.ID_CARGO = c.ID_CARGO 
                AND    C.ID_AREA = $area
                AND    U.FEC_FIN IS NULL";

        $resultado = oci_parse($this->_db, $sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($resultado);
            return false;
        }
    }

    public function getUsuarioPorCargo($idCargo){

        $sql = "SELECT U.ID_USUARIO,U.NOM_USR||' '||U.APE_USR NOMBRE_USUARIO,U.LOGIN,U.ID_CARGO, U.EMAIL_USR EMAIL
                FROM   ACEASOFT.SGC_TT_USUARIOS U,ACEASOFT.SGC_TP_CARGOS C
                WHERE  U.ID_CARGO = c.ID_CARGO 
                AND    C.ID_CARGO = $idCargo
                AND    U.FEC_FIN IS NULL";

        $resultado = oci_parse($this->_db, $sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($resultado);
            return false;
        }
    }

    public function GetUsuariosSinPosicion($in){
        $sql = "SELECT U1.ID_USUARIO, (U1.NOM_USR||' '||U1.APE_USR) NOMBRE_USUARIO, CP.ID_CAJA
                FROM SGC_TT_USUARIOS U1, SGC_TP_CAJAS_PAGO CP
                WHERE U1.ID_USUARIO= CP.ID_USUARIO(+)
                AND   CP.ID_USUARIO IS NULL
                AND   U1.ID_CARGO IN ($in)
                AND   U1.FEC_FIN IS NULL
                ";

        $resultado = oci_parse($this->_db, $sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($resultado);
            return false;
        }
    }

    public function getModulosByUser($coduser){
        $sql = "SELECT M.ID_MODULO, M.DESC_MODULO, U.ACTIVO
         FROM SGC_TP_MODULOS M, SGC_TT_USUARIO_MODULO U
         WHERE M.ID_MODULO = U.ID_MODULO AND M.ACTIVO = 'S'
         AND U.ID_USUARIO = '$coduser'
         ORDER BY M.ORDEN
                ";

        $resultado = oci_parse($this->_db, $sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($resultado);
            return false;
        }
    }

    public function getMenuByModulosUser($coduser,$menu){
        $sql = "SELECT M.ID_MENU, M.DESC_MENU, M.ICONO, M.ORDEN
	FROM SGC_TP_MENUS M, SGC_TP_PERFILES P 
	WHERE M.ID_MENU = P.ID_MENU AND M.ID_PADRE = $menu
	AND P.ID_USUARIO = '$coduser' AND M.ACTIVO = 'S' 
	AND (M.ID_MODULO = $menu) ORDER BY ORDEN ASC
                ";

        $resultado = oci_parse($this->_db, $sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($resultado);
            return false;
        }
    }
    public function getMenuHijByMenuUser($cod_menu,$coduser){
        $sql = "SELECT DISTINCT M.DESC_MENU, M.URL, M.ORDEN 
		FROM SGC_TP_MENUS M, SGC_TP_PERFILES P  
		WHERE M.URL IS NOT NULL AND M.ID_PADRE = $cod_menu AND  P.ID_MENU=M.ID_MENU AND P.ID_USUARIO='$coduser'
		ORDER BY ORDEN ASC
                ";

        $resultado = oci_parse($this->_db, $sql);

        if(oci_execute($resultado)){
            oci_close($this->_db);
            return $resultado;
        }else{
            oci_close($resultado);
            return false;
        }
    }

    public function getUsuario( $arrayUsuario = array(
                                            'codigo' => null,
                                            'login' => null, 
                                            'id_area' => null,
                                            'id_cargo' => null, 
                                            'descripcion_cargo' => null,
                                            'proyecto' => null,
                                            'fecha_fin' => null,
                                            'pass1' => null)
        ){     
            
       $sql = "SELECT U.ID_USUARIO, U.NOM_USR, U.APE_USR, U.EMAIL_USR EMAIL, U.LOGIN, C.ID_CARGO, C.DESC_CARGO CARGO, A.ID_AREA, A.DESC_AREA AREA, U.PASS1 PASS, U.FEC_FIN,
        TO_CHAR(U.PASS_VENCE, 'DD/MM/YYYY HH24:MI:SS') PASS_VENCE, U.ID_PROYECTO 
        FROM SGC_TT_USUARIOS U, SGC_TP_CARGOS C, SGC_TP_AREAS A 
        WHERE C.ID_CARGO = U.ID_CARGO
        AND   A.ID_AREA = C.ID_AREA"
        .$this->whereGetUsuario($arrayUsuario);

        $statement = oci_parse($this->_db, $sql);
        $executed = oci_execute($statement);

        if($executed){
            
            $json = array();
            while($fila = oci_fetch_assoc($statement)){

                $arr = array(
                    'id'        => $fila['ID_USUARIO'],
                    'nombre'    => $fila['NOM_USR'],
                    'apellido'  => $fila['APE_USR'],
                    'email'     => $fila['EMAIL'],
                    'login'     => $fila['LOGIN'],
                    'id_cargo'  => $fila['ID_CARGO'],                
                    'cargo'     => $fila['CARGO'],                
                    'id_area'   => $fila['ID_AREA'],                
                    'area'      => $fila['AREA'],                 
                    'pass'      => $fila['PASS'],                
                    'fecha_fin' => $fila['FEC_FIN'],                
                    'pass_vence'=> $fila['PASS_VENCE'],                
                    'proyecto'  => $fila['ID_PROYECTO'],                
                );

                array_push($json, $arr);
            }

            return array('codigo' => 200, 'data' => $json);
        }else{
            $error = oci_error($statement);
            return array('codigo' => $error['code'], 'mensaje' => $error['message']);
        }
    }

    public function cambiarPass($idUsuario, $pass, $passProvisional = 'N'){
        $sql = "BEGIN SGC_P_CAMBIO_PASS (:ID_USUARIO_IN, :PASS_IN, :PASS_PROVISIONAL_IN, :PCOD, :PMSG); END;";
        
        $statement = oci_parse($this->_db, $sql);
        
        oci_bind_by_name($statement,':ID_USUARIO_IN',$idUsuario);
        oci_bind_by_name($statement,':PASS_IN',$pass);
        oci_bind_by_name($statement,':PASS_PROVISIONAL_IN',$passProvisional);
        oci_bind_by_name($statement,':PCOD',$codigo,4);
        oci_bind_by_name($statement,':PMSG',$mensaje,500);
        $executed = oci_execute($statement);

        if($executed){            
            return array('codigo' => $codigo, 'mensaje'=> $mensaje);
        }else{
            $error = oci_error($statement);
            return array('codigo' => $error["code"], 'mensaje'=> $error["message"]);
        }
    }

    private function whereGetUsuario($arregloParametros = array()){

        //Función para formar la clausula 'Where' de la función 'getUsuario'.

        $where = "";

        if(isset($arregloParametros["codigo"]))  $where = " AND U.ID_USUARIO = '".$arregloParametros['codigo']."'";
        
        if(isset($arregloParametros["login"]))   $where .= " AND U.LOGIN = UPPER('".$arregloParametros['login']."')";

        if(isset($arregloParametros['id_area'])) $where .= " AND  A.ID_AREA = ".$arregloParametros['id_area'];

        if(isset($arregloParametros['descripcion_cargo'])) $where .= " AND C.DESC_CARGO = UPPER('".$arregloParametros['descripcion_cargo']."')";
        
        if(isset($arregloParametros['fecha_fin'])) $where .= " AND U.FEC_FIN ".$arregloParametros['fecha_fin'];

        if(isset($arregloParametros['proyecto']))  $where .= " AND U.ID_PROYECTO = '".$arregloParametros['proyecto']."'";

        if(isset($arregloParametros['pass1']))  $where .= " AND U.PASS1 = '".$arregloParametros['pass1']."'";

        return $where; 
    }
}