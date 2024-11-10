<?php
namespace App;

require 'vendor/autoload.php';

/**
 * Controllador general de la plataforma.
 */

class Controller
{

    public function __construct()
    {

    }

    /**
     *    Metodo para enviar las vistas por medio de los controladores.
     *    @param string $view Nombre de la vista que se va a inyectar.
     *    @param array  $params Array con todas las variables que se inyectaran a la vista.
     */
    public function view($view, $params = [])
    {
        extract($params); // Extraemos las variables pasadas a la vista.

        include VIEWS_PATH . strtolower($view) . '.php'; // Incluimos la vista solicitada.
    }
}
