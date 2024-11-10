<?php
namespace Controllers;

use App;
use Models;

/**
 * Clase para gestionar los accesos de la CAASD
 */
class Caasd extends App\Controller
{

    public function GetAll($proceso){
        $c    = new Models\CaasdModel();
        $data = $c->GetAllData();
        echo stripslashes(json_encode($data));
    }


    public function GetBalance($id_proceso){
        $c = new Models\CaasdModel();
        $data = $c->GetBalance($id_proceso);

        echo stripslashes(json_encode($data));
    }

    public function GetDatosCliente($id_proceso){
        $c = new Models\CaasdModel();
        $data = $c->GetDatosCliente($id_proceso);

        echo stripslashes(json_encode($data));
    }


}
