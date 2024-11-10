<?php
header('Access-Control-Allow-Origin: http://172.16.1.211:8081');
header("Content-type: application/json; charset=utf-8");

/*   Declaracion de constrantes de la plataforma    */
define('ASSETS_PATH', dirname($_SERVER['PHP_SELF']) . '/' . 'Assets/');
define('CONTROLLERS_PATH', 'Controllers/');
define('FUNCTIONS_PATH', 'Core/functions/');
define('CLASS_PATH', 'Class/');
define('VIEWS_PATH', 'Views/');

/*     Constantes de conexion a la base de datos */
define('DB_USER', 'ACEASOFT');
define('DB_PASS', 'acea');
define('DB_HOST', '172.16.1.181');
define('DB_SID', 'ORCL');

// Inclusion de classes de la plataforma
require 'vendor/autoload.php';
require 'Core/Functions/functions.php';
