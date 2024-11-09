<!DOCTYPE html>
<html>
    <head>        
        <meta name="viewport" content="width=device-width, initial-scale=1">            
        <!-- Sweetalert -->
        <link rel="stylesheet" href="../../css/sweetalert.css">
        <link rel="stylesheet" href="../css/recuperacionPass.css">
        <link rel="stylesheet" href="../../librerias/bootstrap-4.6/css/bootstrap.min.css">
    </head>
    <body class="container-fluid">   
        <div class="row justify-content-center align-items-center" id="content">
            <div id="dvFormulario" class="col-10 col-lg-06">
                <div id="encabezado" class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <img src="../../images/ACEA-Logo.svg" alt="Logo de ACEA"><span id="spnAceasoft">SOFT</span>
                        </div>
                        <div class="col-sm-6">
                            <label id="lblTitulo">Recuperación de contraseña</label>
                        </div>
                    </div>             
                    <hr>
                </div>
                <div id="cuerpo" class="col-sm-12">
                    <form action="#" method="post" id="frmRecuperacionPass">
                        <div class="form-group">
                            <label for="txtNombreUsuario">Nombre de usuario</label>
                            <input type="text" name="login" id="txtNombreUsuario" class="form-control" placeholder="Ingrese su nombre de usuario. Ej. vcorrea">
                        </div>
                        <div class="form-group row justify-content-center">
                            <input type="submit" value="Enviar correo" class="btn btn-primary col-sm-4">
                        </div>
                    </form>
                </div>
            </div>
        </div>     
    </body>
    <script src="../../js/jquery-3.3.1.min.js"></script>
    <!-- Sweetalert -->
    <script src="../../js/sweetalert.min.js"></script>
    <!-- Script principal -->
    <script src="../js/recuperacionPass.js"></script>
</html>