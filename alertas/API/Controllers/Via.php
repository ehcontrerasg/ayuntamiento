<?php
namespace Controllers;

use App;
use Models;

/**
 * Clase para Obtener los datos de via.
 */
class Via extends App\Controller
{
    public function getData($inm)
    {
        //$inmueble = int $inm;
        $c    = new Models\ViaModel();
        $data = $c->GetData($inm);

        echo stripslashes(json_encode($data));
    }

    public function setData()
    {
        $inm   = addslashes($_GET['inmueble']);
        $monto = $_GET['monto'];

        $c = new Models\ViaModel();

        if ($c->checkAmount($inm, $monto)) {
            $data = $c->SetData($inm, $monto);
        } else {
            $data = array(
                "Status" => "Amount incorrect.",
                "Code"   => "10",
            );
        }

        echo stripcslashes(json_encode($data));
    }

    public function Reverse($payment)
    {
        $c    = new Models\ViaModel();
        $data = $c->ReversePayment($payment);

        echo stripcslashes(json_encode($data));
    }
}
