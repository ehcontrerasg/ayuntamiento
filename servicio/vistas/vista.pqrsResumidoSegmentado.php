<?php
include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true): ?>
    <!doctype html>
    <html>
        <head>
            <meta charset="UTF-8" />
            <!--Estilos-->
                <link href="../../servicio/css/pqrResumidoSegmentado.css?<?php echo time();?>" rel="stylesheet" type="text/css"/>
                <!--Sweetalert-->
                <link href="../../css/sweetalert.css" rel="stylesheet" type="text/css"/>
                <!--Bootstrap-->
                <link href="../../librerias/bootstrap-4.6/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        </head>
        <body>
            <div class="container-fluid">
                <p id="pTitle">PQRs resumido segmentado</p>
                <form class="row">
                    <div class="form-group col-sm-3">
                        <label for="slcProyecto">Proyecto</label>
                        <select id="slcProyecto" name="proyecto" class="form-control">
                            <option value="">Todos los proyectos</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="slcDepartamento">Departamento</label>
                        <select id="slcDepartamento" name="departamento" class="form-control">
                            <option value="">Todos los departamentos</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="dtFechaInicio">Fecha inicio</label>
                        <input type="date" id="dtFechaInicio" name="fecha_inicio" class="form-control" placeholder="dd/mm/yyyy" required/>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="dtFechaFin">Fecha fin</label>
                        <input type="date" id="dtFechaFin" name="fecha_fin" class="form-control" required/>
                    </div>
                    <div class="row col-sm-12 justify-content-center">
                        <input type="submit" value="Generar" class="btn btn-success col-sm-4"/>
                    </div>
                </form>
                <div id="dvReporte">
                    <table id="tblReporte" border="1">
                        <thead>
                            <tr><td id="tdTituloArchivo"></td></tr>
                            <tr> <!--Fila en blanco.--> </tr>
                            <tr>
                                <th>Departamento</th>
                                <th>Código</th>
                                <th>Tipo</th>
                                <th>Generados</th>
                                <th>Pendientes</th>
                                <th>Cerrados procedentes</th>
                                <th>Cerrados no procedentes</th>
                                <th>Creado en pestaña incorrecta</th>
                                <th>Total cerrados</th>
                                <th>Dentro del tiempo</th>
                                <th>Fuera del tiempo</th>
                                <th>Tiempo promedio (dias)</th>
                                <th>Efectividad (%)</th>
                                <th>Dentro del tiempo / total cerrrados</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--Los datos obtenidos dados los parámetros del formulario aparecerán aquí.-->
                        </tbody>
                    </table>
                </div>
            </div>
        </body>
        <script src="../../js/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src="../../js/sweetalert.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="../../librerias/tableToExcel/tableToExcel.js"></script>
        <script src="../js/pqrResumidoSegmentado.js?<?echo time();?>" type="text/javascript"></script>
    </html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>