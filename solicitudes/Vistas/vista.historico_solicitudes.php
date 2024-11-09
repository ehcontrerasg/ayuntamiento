<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de solicitudes</title>
    <link rel="stylesheet" href="../../librerias/bootstrap-4.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/historico_solicitudes.css?<?php echo time(); ?>">
</head>

<body>
    <!-- Modal de sesión -->
    <?php include_once('./modal-sesion.php'); ?>
    <!-- Fin de modal de sesión -->

    <div class="container">
        <div class="row">
            <p class="col-sm-12 title">Histórico de solicitudes</p>
            <form method="POST" id="frmHistoricoSolicitud" class="col-sm-12">
                <div class="form-group row">
                    <label for="txtCodigoSolicitud" class="col-sm-3">Código de solicitud</label>
                    <input type="text" name="codigo_solicitud" id="txtCodigoSolicitud" placeholder="Código de la solicitud" class="form-control col-sm-5" required>
                    <input type="submit" value="Generar" class="btn btn-success col-sm-3">
                </div>
            </form>
            <div class="col-sm-12" id="dvInformacion">
                <div class="row">
                    <h4 class="col-sm-12" id="hSolicitud">Información de la solicitud #<span id="spnCodigoSolicitud"></span> de <span class="spnSolicitante"></span></h4>
                    <fieldset class="col-sm-12" id="fstInformacionGeneral">
                        <legend>Información general</legend>
                        <div class="row cuerpo">
                            <p class="col-sm-4"><strong>Estado:</strong> <span id="spnEstado"></span></p>
                            <p class="col-sm-4"><strong>Prioridad:</strong> <span id="spnPrioridad"></span></p>
                            <p class="col-sm-4"><strong>Fecha de solicitud:</strong> <span id="spnFechaSolicitud"></span></p>
                            <p class="col-sm-4"><strong>Fecha de compromiso:</strong> <span id="spnFechaCompromiso"></span></p>
                            <p class="col-sm-4"><strong>Desarrollador:</strong> <span id="spnDesarrollador"></span></p>
                            <p class="col-sm-4"><strong>Módulo/Pantalla:</strong> <span id="spnModuloPantalla"></span></p>
                            <p class="col-sm-4"><strong>Solicitante:</strong> <span class="spnSolicitante"></span></p>
                            <p class="col-sm-8"><strong>Requerimiento:</strong> <span id="spnRequerimiento"></span></p>
                            <p class="col-sm-4"><strong>Tipo de requerimiento:</strong> <span id="spnTipoRequerimiento"></span></p>
                            <p class="col-sm-4"><strong>Fecha de inicio:</strong> <span id="spnFechaInicio"></span></p>
                            <p class="col-sm-4"><strong>Fecha de conclusión:</strong> <span id="spnFechaConclusion"></span></p>
                        </div>
                    </fieldset>
                    <fieldset class="col-sm-12" id="fstHistorico">
                        <legend>Historial</legend>
                        <div class="row cuerpo"></div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../../librerias/SweetAlert/sweetalert.min.js"></script>
<script src="../../js/jquery-3.3.1.min.js"></script>
<script src="../../js/fnLogin.js"></script>
<script src="../js/historico_solicitud.js?<?php echo time(); ?>"></script>

</html>