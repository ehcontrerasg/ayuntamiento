<?php
    $currentDateTime = date('Y-m-d H:i:s'); // para oberter la fecha actual



if(!is_dir('/var/www/html/aceasoft/logs')) {
    mkdir('/var/www/html/aceasoft/logs',0777);
}


error_log("Se correo este script en fecha: $currentDateTime",3,'/var/www/html/aceasoft/logs/intento_ejecucion_cronbtab.log');


echo "hola mundo $currentDateTime";


?>