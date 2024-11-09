<?php
namespace ns;
error_reporting(E_ALL);
ini_set('display_errors', '1');

//include  './pruebaNamespace.php';
//punto control
class Clase1{
    function __construct()
    {
        new PruebaNamespace();
      //echo 'Hello';
    }
}

new Clase1();