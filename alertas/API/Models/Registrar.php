<?php
namespace Models;

use App;

/**
 * Clase para los modelos del registro de usuarios al sistema.
 */
class Registrar extends App\Conexion
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *   Metodo que verifica si el contrato ingresado es real y activo.
     *   @param string $contrato Numero de contrato del sistema.
     *   @return boolean Retorna TRUE si el contrato es correcto.
     */
    public function verificarContrato($contrato)
    {
        $sql = "SELECT verifica_contrato('$contrato') contrato FROM dual"; // Modificar esta funcion desde oracle en plataforma CASSD

        $result = oci_parse($this->_db, $sql);
        $flag   = oci_execute($result);

        if ($flag == true) {

            if (oci_fetch_assoc($result)['CONTRATO'] == 'S') {
                return true;
            } else {
                return false;
            }

        } else {
            return 'Error en la consulta';
        }

        oci_free_statement($result);
    }

    /**
     *   Metodo que verifica que el contrato no este registrado en la plataforma
     *   de pagos en linea.
     *   @param string $contrato Numero de contrato del sistema.
     *   @return boolean Retorna TRUE si el contrato ya esta registrado en la plataforma.
     */
    public function verificaRegistro($contrato)
    {
        $sql = "SELECT id FROM TBL_USERS WHERE username = '$contrato' ";

        $result = oci_parse($this->_db, $sql);
        $flag   = oci_execute($result);
        $rows   = oci_fetch($result);
        if (oci_num_rows($result) > 0) {
            return false;
        } else {
            return true;
        }
        oci_free_statement($result);
    }

    /**
     *   Metodo que registra un nuevo usuario al sistema.
     *   @param strign $contrato numero de inmueble.
     *   @param string $name Nombre del usuario.
     *   @param string $last_name Apellido del usuario.
     *   @param string $cedula Cedula registrada.
     *   @param string $tel Telefomo registrado.
     *   @param string $email Email registrado.
     *   @param string $pass Contraseña registrada.
     *   @param string $token Token de activacion.
     *   @return boolean Retorna TRUE si el usuario es registrado correctamente.
     */
    public function registrarContrato($contrato, $name, $last_name, $cedula, $tel, $email, $pass, $token)
    {
        $sql = "BEGIN REGISTRO_USUARIO('$contrato', '$name', '$last_name', '$cedula', '$tel', '$email', '$pass', '$token');END;";

        $result = oci_parse($this->_db, $sql);
        $flag   = oci_execute($result);

        if ($flag) {
            oci_commit($this->_db);
            return 1;
        } else {
            oci_rollback($this->_db);
            return 0;
        }
        oci_free_statement($result);
    }

    public function __destruct()
    {
        oci_close($this->_db);
    }
}

//5488 7800 2524 5579
