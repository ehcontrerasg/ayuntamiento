<!DOCTYPE html>
<html lang="es" ng-app="appSolicitudesTI">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <link type="image/ico" href="../images/favicon.ico" rel="icon">
    <title>Aprobacion de Solicitudes</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/sidebar.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/reportes.css" />
    <script src="http://172.16.1.214:3000/socket.io/socket.io.js"></script>
    <script src="js/lib/angular/angular.min.js"></script>
</head>
<body ng-controller="ctrlMainTI" >
    <!-- Modal modal-sm-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
        <div class="modal-dialog center-block" role="document" style="max-width: 400px;color: #0c6abc;">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #f0f0f0;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <center><h4 class="modal-title" id="myModalLabel"><b>ACEA SOFT</b></h4></center>
                </div>
                <form id="frmLogin" class="form-horizontal" action="../webServices/ws.login.php" method="post" caso="2">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username" class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4">Usuario:</label>
                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                    <input type="text" name="username" id="username" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label col-xs-4 col-sm-4 col-md-4 col-lg-4">Contraseña:</label>
                            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-key"></span></span>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row" id="msgUser" style="display: none">
                            <div class="col-xs-12 col-sm-11 col-md-11 col-lg-11">
                                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <p>El <b>usuario</b> o la <b>contraseña</b> son incorrectos.</p>
                                </div>
                            </div>
                        </div>
                        <center>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" ng-click="logout()" style="width: 100px"><span class="glyphicon glyphicon-remove"></span> Salir</button>
                            <button type="submit" class="btn btn-primary" style="width: 100px"><span class="glyphicon glyphicon-ok"></span> Entrar</button>
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <header class="navbar-fixed-top">
        <div class="container">
            <h4><b>ACEA SOFT</b></h4>
        </div>
    </header>
    <br>
    <br>
    <nav class="navbar navbar-default sidebar" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
                    <span class="sr-only">Menu</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li ng-repeat="option in optMenuNavBar" id="{{option.ID_MENU}}">
                        <a href="{{option.URL}}">
                            <strong>{{option.DESC_MENU}}</strong><span style="font-size:16px;" class="pull-right hidden-xs showopacity {{option.ICONO}}"></span>
                        </a>
                    </li>
                    <li id="logout" ng-click="goIndex()"><a href="#"><strong>Menu Principal</strong><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-th"></span></a></li>
                     <li id="logout" ng-click="logout()"><a href="#"><strong>Cerrar Sesion</strong><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-log-out"></span></a></li>

                    <!-- li class="dropdown">
                        <a href="#" class="dropdown-toggle" id="menPrestamos" data-toggle="dropdown">Prestamos<span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-usd"></span></a>
                        <ul class="dropdown-menu forAnimate" role="menu">
                             <li><a href="#">Vigentes</a></li>
                            <li class="divider"></li>
                            <li id="solReenganche"><a class="" href="#">Reenganche</a></li>
                        </ul>
                    </li>
                    <!-- <li class="dropdown">
                        <a href="#" class="dropdown-toggle" id="menPerDatos" data-toggle="dropdown">Mis Datos<span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-user"></span></a>
                        <ul class="dropdown-menu forAnimate" role="menu">
                            <li><a class="hola" href="#DatosPersonales" id="one">Datos Personales</a></li>
                            <li class="divider"></li>
                            <li><a class="" href="#DireccionCasa" id="two">Direccion Domicilio</a></li>
                            <li class="divider"></li>
                            <li><a class="" href="#RefLaborales" id="four">Referencia Laborales</a></li>
                        </ul>
                    </li>
                    <li id="logout"><a href="php/logout.php">Cerrar Sesion<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-log-out"></span></a></li>
                        ng-model="idMenu" ng-change="lstPantallas(this)"
                    -->
                </ul>
            </div>
        </div>
    </nav>
    <div ng-view class="main container" id="main">

    </div>
    <!-- Aqui incluimos las librerias que trabajan con jquery -->

    <script src="js/lib/jquery/jquery-3.1.1.min.js"></script>
    <script src="js/lib/jquery/bootstrap.min.js"></script>
    <script src="../js/fnLogin.js"></script>
    <!-- estas son todas las librerias que trabajan con  angular -->
    <script src="js/lib/angular/ngRoute.js"></script>
    <script src="js/lib/angular/socket.min.js"></script>
    <!-- script src="js/angular/angular-animate.js"></script -->
    <script src="js/lib/angular/angular-cookies.min.js"></script>
    <script src="js/lib/angular/draganddrop.min.js"></script>
    <script src="js/lib/angular/angular-local-storage.min.js"></script>
    <script src="js/lib/angular/toArrayFilter.js"></script>

    <script src="js/appSolicitudesTI.js"></script>
    <!-- Aqui incluimos los controladores -->
    <script src="js/ctrl/ctrlMainTI.js"></script>
    <script src="js/ctrl/ctrlSolicitudesTI.js"></script>
    <script src="js/ctrl/ctrlSolicitarTI.js"></script>

</body>
</html>
