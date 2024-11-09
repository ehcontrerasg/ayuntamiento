<?php
    include_once "class.conexion.php";
    class PermisosURL extends ConexionClass
    {
        public function VerificaPermisos()
        {
            session_start();
            $coduser = $_SESSION['codigo'];
            $porciones = explode("/",$_SERVER['PHP_SELF']);
            $vista = end($porciones);

             $sql = "SELECT M.URL
                     FROM SGC_TP_PERFILES P, SGC_TP_MENUS M
                     WHERE
                      M.ID_MENU= P.ID_MENU
                      AND M.URL LIKE '%$vista%' AND
                      P.ID_USUARIO = '$coduser'";
            $resultado=oci_parse($this->_db,$sql);
            $bandera=oci_execute($resultado);

            if($bandera){

                while(oci_fetch($resultado)) {
                    $url = oci_result($resultado,"URL");}
                if ($url!="") {
                    oci_close($this->_db);
                    return true; }
                else {
                    oci_close($this->_db);
                    return false;}
            }else{
                oci_close($this->_db);
                return false;}
        }
        public function VerificaPermisosPaginaRequerida($vista)
        {
            session_start();
            $coduser = $_SESSION['codigo'];
            $porciones = explode("/",$vista);
            $vista = end($porciones);

            $sql = "SELECT M.URL
                     FROM SGC_TP_PERFILES P, SGC_TP_MENUS M
                     WHERE
                      M.ID_MENU= P.ID_MENU
                      AND M.URL LIKE '%$vista%' AND
                      P.ID_USUARIO = '$coduser'";

            $resultado=oci_parse($this->_db,$sql);
            $bandera=oci_execute($resultado);



            if($bandera){

                while(oci_fetch($resultado)) {
                    $url = oci_result($resultado,"URL");}
                if ($url!="") {
                    oci_close($this->_db);
                    return true; }
                else {
                    oci_close($this->_db);
                    return false;}
            }else{
                oci_close($this->_db);
                return false;}
        }
    }