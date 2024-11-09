<?php session_start();
require_once '../Clases/clase.solicitudes.php';
$user = $_SESSION['codigo'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link type="image/ico" href="../images/favicon.ico" rel="icon">
    <title>Aprobacion de Solicitudes</title>
    <link rel="stylesheet" type="text/css" href="../../librerias/bootstrap-4.6/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/sidebar.css">
    <link rel="stylesheet" type="text/css" href="../../librerias/fontAwesome/css/fontawesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css?<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="../css/reportes.css?<?php echo time();?>" />
    <link rel="stylesheet" href="../css/sweetalert.css">
</head>

<body>   
    <!-- Modal de sesión -->
        <?php include_once('./modal-sesion.php'); ?>
    <!-- Fin de modal de sesión -->
    
    <div class="container-fluid">
        <div id="AsignacionSolTI">            
            <div class="row container-fluid justify-content-center">
                <h3 class="col-sm-12" style="text-align: center;">Desarrolladores</h3>
                <?php
                /*Obtener informacion del usuario actual*/
                $s = new Solicitudes();
                $u = $s->getUserData($user);

                $usrArea = $u['ID_AREA'];

                if ($u['ID_AREA'] == 11 and ($u['ID_CARGO'] == 111 or $u['ID_CARGO'] == 112)) {
                    $p = $s->getProgrammers();

                    while (oci_fetch($p)) {
                        $p_name = oci_result($p, 'NOMBRE');
                        $p_id   = oci_result($p, 'ID_USUARIO');
                ?>
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" id="<?= $p_id; ?>" ondrop="drop(event, this)" ondragover="allowDrop(event)">
                            <figure>
                                <img src="../img/softtware-engineer.png" class="img-responsive rounded-circle" alt="Responsive image">
                                <figcaption>
                                    <center><?= strtoupper($p_name); ?></center>
                                </figcaption>
                            </figure>
                            <br>
                            <div class="list-group pre-scrollable" style="width: 80%; max-height: 300px;" id="<?= $p_id; ?>">
                                <?php
                                $soli = $s->getSolPro($p_id);
                                while (oci_fetch($soli)) {
                                ?>

                                    <li class="list-group-item list-group-item-success" idCtn="prg<?= $p_id; ?>" id="<?= oci_result($soli, 'ID_SCMS'); ?>">
                                        <span class="glyphicon glyphicon-remove text-danger" onclick="asigDesarollador(this,<?= oci_result($soli, 'ID_SCMS') . ',' . $p_id . ',\'D\''; ?>)">X</span>
                                        <b><?= oci_result($soli, 'ID_SCMS'); ?></b>
                                        <?= oci_result($soli, 'MODULO') . ' / ' . oci_result($soli, 'PANTALLA'); ?>
                                    </li>
                                <?php
                                }
                                ?>

                            </div>
                        </div>

                <?php
                    }
                }
                ?>
            </div>
        </div>
        <div id="dvSolicitudes"></div>
    </div>
</body>

<script src="../js/lib/jquery/jquery-3.1.1.min.js"></script>
<script src="../../librerias/popper/popper.min.js"></script>
<script src="../../librerias/bootstrap-4.6/js/bootstrap.min.js"></script>
<script src="../js/sweetalert.min.js"></script>
<script src="../../js/fnLogin.js"></script>
<script src="../js/solicitudAsignacion.js?<? echo time(); ?>"></script>
<script src="../js/asignacion.js?<? echo time(); ?>"></script>
</html>