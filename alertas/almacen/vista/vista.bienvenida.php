<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <h1>Hola, has entrado a la vista de bienvenida</h1>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError2.php";
endif; ?>

