<?php

define('BASEPATH', basename(__DIR__) . '/');
require_once 'Core/core.php';
use App\App;

$myApp = new App();
$myApp->render();
