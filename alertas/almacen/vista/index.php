<?php
include_once ('../../clases/class.PermisosURL.php');
session_start();
$coduser = $_SESSION['codigo'];

 $permisos = new PermisosURL();
 $permisos->VerificaPermisos($coduser);



?>
<html>
    <head>


    </head>
    <body>
        <h1>Prueba Almacén</h1>

    </body>

</html>