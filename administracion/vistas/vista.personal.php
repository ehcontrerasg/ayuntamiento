<!doctype html>
<html>
    <head>
        <link href="../../css/sweetalert.css" rel="stylesheet" type="text/css"/>
        <!--Bootstrap-->
        <link href="../../librerias/bootstrap-4.5.3-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!--Estilos personalizados-->
        <link href="../css/personal.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="container-fluid">
            <p class="screenTitle">Personal</p>
            <form class="container" enctype="application/x-www-form-urlencoded" id="frmCrearUsuario">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="dtFechaInicio">Fecha de inicio</label>
                        <input type="date" id="dtFechaInicio" name="fecha_inicio" class="form-control"  required/>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="slcContratista">Contratista</label>
                        <select id="slcContratista" class="form-control" name="contratista" required></select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="slcProyecto">Proyecto</label>
                        <select id="slcProyecto" class="form-control" name="proyecto" required></select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txtCédula">Cédula</label>
                        <input type="number" class="form-control" id="txtCédula" name="cedula" required/>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txtNombre">Nombres</label>
                        <input type="text" class="form-control" id="txtNombre" name="nombres" required/>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txtApellidos">Apellidos</label>
                        <input type="text" class="form-control" id="txtApellidos" name="apellidos" required/>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="slcDepartamento">Departamento</label>
                        <select id="slcDepartamento" class="form-control" name="departamento" required></select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="slcCargo">Cargo</label>
                        <select id="slcCargo" class="form-control" name="cargo" required></select>
                    </div>
                    <div class="col-sm-12 row justify-content-center">
                        <input type="submit" class="col-sm-8 btn btn btn-success" value="Crear usuario"/>
                    </div>
                </div>
            </form>
        </div>
    </body>
    <script src="../../js/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="../../js/sweetalert.min.js" type="text/javascript"></script>
    <script src="../js/personal.js?<?php echo time(); ?>" type="text/javascript"></script>
</html>