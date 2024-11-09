<?php
echo "Envio de email";
$para      = 'ingjgutierrez@gmail.com, chuchini65@hotmail.com';
$titulo    = 'Prueba';
$mensaje   = 'Hola';
$cabeceras = 'From: ingjgutierrez@gmail.com' . "\r\n" .
    'Reply-To: ingjgutierrez@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($para, $titulo, $mensaje, $cabeceras);
?>