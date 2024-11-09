<?php
session_start();

/********************************************************************/
/*  SOFTWARE DE GESTION COMERCIAL ACEA DOMINICANA                   */
/*  ACEA DOMINICANA - REPUBLICA DOMINICANA                            */
/*  CREADO POR JESUS GUTIERREZ ORTIZ                                */
/*  FECHA CREACION 23/09/2014                                        */
/********************************************************************/

include_once 'include.php';
$loguser   = $_SESSION['usuario'];
$passuser  = $_SESSION['contrasena'];
$coduser   = $_SESSION['codigo'];
$nomuser   = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>.:: ACEA DOMINICANA ::.</title>
  <link type="image/ico" href="../images/favicon.ico" rel="icon">

    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../css/style.css?2" />
    <link href="./../css/bootstrap.min.css" rel="stylesheet" />
  <link href="./../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
     <!-- Alertas -->
    <link rel="stylesheet" type="text/css" href="../alertas/dialog_box.css" />
  <script type="text/javascript" src="../alertas/dialog_box.js"></script>
  <!-- SweetAlert -->
  <link href="../css/sweetalert.css" rel="stylesheet" type="text/css"/>
  <!-- Estilos de cambio de contraseña -->
  <link rel="stylesheet" href="../css/cambiarPass.css">

  <script language="JavaScript" type="text/JavaScript">

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<!--
  <body style="background-color:#FFFFFF; background-repeat:no-repeat; background-position:50% -50%" background="../images/aceasoft3.png" >
-->
  </head>
  <body style="background-color:#FFFFFF; background-repeat:no-repeat; background-position:50% -50%" background="../images/aceasoft3.png" >
<!--  Fondo para navidad:
<body style=" background-size: 50%; background-color:#FFFFFF; background-repeat:no-repeat; background-position:75% -40%"     background="../images/fondo feliz navidad.jpg"  >
-->
<div class="menu" id="menu">
          <div class="logo-acea" >
              <img src="../images/aceadom201904.png" class="img-responsive" width="130" height="100">
            </div>

            <div class="" >
            <h1>Portal de aplicaciones</h1>
            </div>
            <div id="user-menu">
              <!-- <img src="../images/logo_caasd.png" class="img-responsive" width="110" height="130"> -->
              <?php if (isset($_SESSION['usuario'])) {?>
              <h5><?php echo $nomuser; ?> <i class="glyphicon glyphicon-chevron-down"></i></h5>
              <?php } else {
    ?>
              <a href="../logout.php"><i class="glyphicon glyphicon-off"></i> Salir</a>
                <?php
}?>
            </div>

 <?php if (isset($_SESSION['usuario'])) {?>
            <ul id="sub-menu-top">
              <li><a href="#" data-toggle="modal" data-target="#cambiarPass"><i class="glyphicon glyphicon-refresh"></i> Cambiar Contraseña</a></li>
              <li><a href="../logout.php"><i class="glyphicon glyphicon-off"></i> Salir</a></li>
            </ul>
            <?php }?>
    </div>

  <div class="modal fade" id="cambiarPass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cambio de contraseña</h4>
      </div>
      <div class="modal-body">
        <form id="frmCambiarPass">          
          <input type="hidden" name="codigo_usuario" value="<?php echo $coduser;?>">
          <input type="hidden" name="pass_vence" value="">
          <div class="form-group">
            <label for="pass">Contraseña actual</label>
            <input type="password" name="pass_actual" id="txtPassActual" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="newPass">Nueva Contraseña</label><label for="newPass"></label>
            <input type="password" name="pass_nuevo" id="txtPassNuevo" class="form-control"/>
          </div>
          <div class="form-group">
            <label for="newPass2">Repita Contraseña</label><label for="newPass2"></label>
            <input type="password" name="confirmacion_pass_nuevo" id="txtPassNuevo2" class="form-control">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="btnCerrarModal">Cerrar</button>
        <input type="submit" value="Guardar" class="btn btn-primary" />
      </div>
      </form>
    </div>
  </div>
</div>

    <div class="container">
      <div class="row">
          <div class="col-xs-12">&nbsp;</div>
        </div>
   </div>
   <div class="container">
    <div class="row">
   <?php

$Cnn  = new OracleConn(UserGeneral, PassGeneral);
$link = $Cnn->link;

$sql = "SELECT M.ID_MODULO, M.DESC_MODULO, U.ACTIVO
         FROM SGC_TP_MODULOS M, SGC_TT_USUARIO_MODULO U
         WHERE M.ID_MODULO = U.ID_MODULO AND M.ACTIVO = 'S'
         AND U.ID_USUARIO = '$coduser'
         ORDER BY M.ORDEN";
//echo $sql;
$stid = oci_parse($link, $sql);
oci_execute($stid);
while (oci_fetch($stid)) {
    $cod_modulo = oci_result($stid, 'ID_MODULO');
    $des_modulo = oci_result($stid, 'DESC_MODULO');
    $act_modulo = oci_result($stid, 'ACTIVO');
    if ($act_modulo == "S") {
        ?>

        <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2">
        <a href="../<?php echo strtolower($des_modulo); ?>/" >
        <p></p>
        <img align="absmiddle" style="vertical-align:middle; " src="../images/modulos/<?php echo strtolower($des_modulo); ?>.png" alt=""
    name="<?php echo strtolower($des_modulo); ?>" width="160" height="100" border="0" id="<?php echo strtolower($des_modulo); ?>"
    onmouseover="MM_swapImage('<?php echo strtolower($des_modulo); ?>','','../images/modulos/<?php echo strtolower($des_modulo); ?>1.png',1)"
    onMouseOut="MM_swapImgRestore()"/>
        </a>
        </div>
        <?php
} else {
        ?>

        <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2" style="text-align:center">
        <p></p>
        <img src="../images/modulos/<?php echo strtolower($des_modulo); ?>.png" alt="" name="<?php echo strtolower($des_modulo); ?>" width="160" height="100" border="0" id="<?php echo strtolower($des_modulo); ?>" style="background-color:#FFFFFF"/>
        </div>

        <?php
}
}
oci_free_statement($stid);
?>

</div>

 </body>
  <script type="text/javascript" src="../js/jquery.min.js"></script>
  <script type="text/JavaScript" src="../js/bootstrap.min.js"></script>
  <script type="text/JavaScript" src="../js/sweetalert.min.js"></script>
  <script type="text/javascript" src="../js/cambiarPass.js?<?php echo time();?>"></script>
  
  <script>
    var menu = document.getElementById('menu'),
        userMenu = document.getElementById('user-menu'),
        subMenu = document.getElementById('sub-menu-top');

    subMenu.style.top = menu.getBoundingClientRect().height + 'px';
    subMenu.style.width = userMenu.getBoundingClientRect().width + 'px';

    $(userMenu).click(function(){
      $(subMenu).slideToggle('fast');
    })
  </script>
</html>