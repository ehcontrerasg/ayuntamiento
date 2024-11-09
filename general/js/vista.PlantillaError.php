<?php

if($_POST['bandera']=='true'){
    $intentos=$_POST['intentos'];
    $password=$_POST['password'];
    $user=$_POST['username'];
    $url=$_POST['url'];
    $intentos+=1;

    include_once('../../clases/class.login.php');
    $user = strtoupper(addslashes(trim($user)));
    $password = addslashes(trim($password));
    settype($user, 'string');
    settype($password, 'string');

    $Login = new Login($user, $password);

    oci_fetch_all($Login->data, $data);

    if ($Login->isUser($data)) {
        include_once ('../../clases/class.PermisosURL.php');
        $permisos = new PermisosURL();


        session_start();
        //print_r($data);
        $Login->setSession($data);
        $verificarPermisos = $permisos-> VerificaPermisosPaginaRequerida($url);
        if($verificarPermisos==true){
            // echo "Prueba URL:".$url;
            header("location:".$url);
        }else {header("location:" . '../../index.php'); }
    }
    if($intentos==4){
        header("location:".'../../index.php');
    }
}else{
    $intentos=0;
    $url= $_SERVER['REQUEST_URI'];

}
/*echo $url;*/
/*echo $intentos;*/

?>

<html>
<head>
    <script src="../../js/jquery-3.2.1.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style_login.css">
    <link rel='stylesheet' href='../../css/error.css'>
</head>
<body>
<div class="error-wall load-error">
    <div class="error-container">
        <h1>Acceso denegado</h1>
        <?if ($_SESSION == NULL){?>
            <h3>Su sesión ha expirado.</h3>
            <button class="btn btn-success" data-toggle="modal" data-target="#exampleModal" > Continuar </button>
        <?} else {?>
            <h3>No tiene permisos para acceder.</h3>
            <form method="POST" action="../../general/datos/datos.PlantillaError2.php">
                <input class="btn btn-success" type="submit" value="Continuar">
            </form>

        <?}?>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">LOGIN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/aceasoft/general/vistas/vista.PlantillaError2.php" id="frmLogin" autocomplete="on" method="post">
                        <div class="form-group">
                            <input name="url" type="hidden" value=<?echo $url?> />
                        </div>
                        <div class="form-group">
                            <label for="username" class="uname" data-icon="u"><i class="glyphicon glyphicon-user"></i> Usuario </label>
                            <input id="username" class="form-control" name="username" type="text" placeholder="Usuario" autofocus required />
                        </div>
                        <div class="form-group">
                            <label for="password" class="youpasswd" data-icon="p"><i class="glyphicon glyphicon-lock"></i> Contrase&ntilde;a </label>
                            <input id="password" class="form-control" name="password" type="password" placeholder="Contrase&ntilde;a" required/>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit"  class="btn btn-primary" value="Ingresar" />
                        </div>

                        <input type="hidden" name="bandera" value="true" >
                        <input type="hidden" name="intentos" value="<?echo $intentos?>" >


                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>