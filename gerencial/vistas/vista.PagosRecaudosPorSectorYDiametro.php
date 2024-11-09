<?PHP
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../../css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="../css/PagosRecaudosPorSectorYDiametro.css?<?echo time();?>" type="text/css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
    </head>
    <body>
        <div class="contenedor">
            <div class="cabecera">
                <h4>Pagos y recaudos por Uso, sector y diámetro</h4>
            </div>
            <div>
                <form onsubmit="return false" id="frmGeneraReporte" enctype="application/x-www-form-urlencoded">
                    <div class="form-group">
                        <label>Período desde</label>
                        <input type="number" name="periodoDesde" class="form-control" required="true">
                    </div>
                    <div class="form-group">
                        <label>Período hasta</label>
                        <input type="number" name="periodoHasta" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Proyecto</label>
                        <select name="proyecto" class="form-control" id="slcProyecto" required>
                            <option value="">--Seleccione un proyecto--</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Reporte</label>
                        <select name="reporte" class="form-control" required></select>
                    </div>
                    <input type="submit" value="Generar" class="btn btn-success" id="btnGenerar">
                </form>
            </div>
        </div>

        <div id="grupoReportes">
            <div id="dvNumeroPagosAgua"></div>
            <div id="dvRecaudosAgua"></div>
            <div id="dvNumeroPagosPorSector"></div>
            <div id="dvRecaudosPorSector"></div>
            <div id="dvConsumoFacturadoRed"></div>
            <div id="dvConsumoFacturadoPozo"></div>
        </div>

    </body>
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/session.js?<?echo time();?>"></script>
    <script src="../../js/momentJs/moment-with-locales.js"></script>
    <script src="../../js/sweetalert.min.js?<?echo time();?>"></script>
    <script src="../js/PagosRecaudosPorSectorYDiametro.js?<?echo time();?>"></script>
</html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>
