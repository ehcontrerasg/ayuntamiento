<?php
//error_reporting(E_ERROR);
//ini_set("display_errors", "1");


session_start();
if (isset($_SESSION['usuario'])) {
    $segundos = 1800; //si pasa este tiempo se detecta la inactividad del usuario en el sitio
    if (($_SESSION['tiempo'] + $segundos) < time()) {
        session_destroy();
    } else {
        $_SESSION['tiempo'] = time();
        header('location: index2.php/');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>.::ACEASOFT::.</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style_login.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
</head>

<body>
    <section>

        <div class="container">
            <form action="webServices/ws.login.php" id="frmLogin" autocomplete="on" method="post" caso="1">
                <div class="form-sup">
                    <img src="images/ACEA-Logo.svg" alt="Acea Logo">
                    <h3>SOFT</h3>
                </div>

                <hr>
                <h1>Sistema de Gestión Comercial</h1>
                <div class="row" id="msgUser" style="display: none">
                    <div class="col-xs-12 col-sm-11 col-md-11 col-lg-11">
                        <div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <p>El <b>usuario</b> o la <b>contraseña</b> son incorrectos.</p>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username" class="uname" data-icon="u"><i class="glyphicon glyphicon-user"></i> Usuario </label>
                    <input id="username" name="username" type="text" placeholder="Usuario" autofocus required />
                </div>
                <div class="form-group">
                    <label for="password" class="youpasswd" data-icon="p"><i class="glyphicon glyphicon-lock"></i> Contrase&ntilde;a </label>
                    <input id="password" name="password" type="password" placeholder="Contrase&ntilde;a" required/>
                </div>
                <div class="form-group">
                    <input type="submit" value="Ingresar" />
                </div>                
                <a href="./general/vistas/vista.recuperacionPass.php" id="aOlvideMiPass">Olvidé mi contraseña</a>                                
            </form>
        </div>
    </section>
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="alertas/dialog_box.js"></script>
    <script src="js/fnLogin.js?2"></script>
 </body>
</html>
