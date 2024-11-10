<?php
namespace App;

/**
 * Clase principal de la aplicacion para llamar a los controladores
 */
class App
{
    private $_controller;
    private $_method;
    private $_params;

    const NAMESPACE_PATH = "Controllers\\";

    public function __construct()
    {
        $url     = self::parseUrl(); // Llamamos a la function que almacena la url y la parsea.
        $default = 'index'; // Variable que se usara por defecto, cuando no se llama un metodo en concreto.

        // Verificamos si se llama un controlador, de lo contrario nos dirigimos al index.
        if (!isset($url[0])) {
            // Varificamos si existe una session iniciada para evitar el logueo a la plataforma.
            if (!isset($_POST['token'])) {
                $json = array(
                    "Status" => "No Access Token.",
                    "Code"   => "01",
                );

                echo json_encode($json);
                exit;
            } else if (checkToken($_POST['token'])) {
                $json = array(
                    "Status" => "Ok.",
                    "Code"   => "00",
                );

                echo json_encode($json);
                exit;
            } else {
                $json = array(
                    "Status" => "Access Denied.",
                    "Code"   => "05",
                );

                echo json_encode($json);
                exit;
            }
        }

        //  Verificamos si el token recibido es correcto.
        /* if (!checkToken($_POST['token'])) {
        $json = array(
        "Status" => "Token authentication error.",
        "Code"   => "02",
        );

        echo json_encode($json);
        exit;
        }*/

        // Verificamos si existe el controlador llamado, de lo contrario llamamos la pagina de error.
        if (isset($url[0]) && file_exists(CONTROLLERS_PATH . ucfirst($url[0]) . '.php')) {
            $this->_controller = ucfirst($url[0]);
            unset($url[0]);
        } else {
            $json = array(
                "Status" => "Controller not found",
                "Code"   => "03",
            );

            echo json_encode($json);
            exit;
        }

        $classFull = self::NAMESPACE_PATH . $this->_controller;

        $this->_controller = new $classFull;

        // verificamos si se está llamando un metodo y tambien si existe tal metodo.
        if (isset($url[1])) {
            if (method_exists($this->_controller, $url[1])) {
                $this->_method = $url[1];
                unset($url[1]);
            } else {
                $json = array(
                    "Status" => "Method not found.",
                    "Code"   => "04",
                );

                echo json_encode($json);
            }

        } else {
            //include VIEWS_PATH . '404.php';
            $this->_method = $default;
            //exit;
        }

        // Verificamos si se envian parametros en la url y lo asociamos al metodo llamado.
        $this->_params = $url ? array_values($url) : [];
    }

    /*
     *   Metodo que obtiene y filtra la url.
     */
    public function parseUrl()
    {
        if (isset($_GET['request'])) {
            return explode('/', filter_var(rtrim(strtolower($_GET['request']), '/'), FILTER_SANITIZE_URL));
        }
    }

    /*
     *   Metodo que renderiza y asocia los parametros de la url con un controlador.
     */
    public function render()
    {
        call_user_func_array([$this->_controller, $this->_method], $this->_params);
    }

    // Metodo que obtiene el controlador solicitado.
    public function getController()
    {
        return $this->_controller;
    }

    // Metodo que obtiene el metodo solicitado.
    public function getMethod()
    {
        return $this->_method;
    }

    // Metodo que obtiene los parametros enviados al metodo.
    public function getParams()
    {
        return $this->_params;
    }
}
