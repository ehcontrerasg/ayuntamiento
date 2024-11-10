<?php
/*
 *    Functiones generales de la plataforma
 */

// Function para agregar estilos CSS

function css($route)
{
    echo ASSETS_PATH . 'css/' . $route . '.css';
}

// Function para agregar los archivos de Javascript
function js($route)
{
    echo ASSETS_PATH . 'js/' . $route . '.js';
}

function checkToken($token)
{
    $accessToken = date('Ymd');
    $string      = "d19f66c29c50de8ae1ba627a4e50546b";

    $accessToken = md5(sha1($string . $accessToken));
    if ($token === $accessToken) {
        return true;
    } else {
        return false;
    }
}

function generateToken()
{
    $accessToken = date('Ymd');
    $string      = "d19f66c29c50de8ae1ba627a4e50546b";

    $accessToken = md5(sha1($string . $accessToken));

    return $accessToken;
}
