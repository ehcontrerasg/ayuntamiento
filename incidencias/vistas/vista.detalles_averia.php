<?
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 25/09/2018
 * Time: 10:07
 */


include_once ('../../clases/class.PermisosURL.php');
$permisos = new PermisosURL();
$verificarPermisos = $permisos->VerificaPermisos();
if ($verificarPermisos==true):


$idReclamo=$_GET['id'];

?>
<!DOCTYPE html>
<html>
  <head>
      <title>Detalles</title>
      <!-- alertas -->
      <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
      <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
      <script src="../../js/jquery-3.2.1.min.js"></script>
      <link href="../../css/bootstrap/css/bootstrap.css" media="all" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="../../css/bootstrap/js/bootstrap.js" ></script>
      <link href="../css/general_averias.css?<?PHP echo time()?>"  media="all" rel="stylesheet" />
      <link href="../css/botonesDaTableAverRecib.css" rel="stylesheet" />
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhc6UkP_v17lXZ9hPKfnWKRtjPTMEmW2k">
      </script>
      <script type="text/javascript" src="../js/detalleAverias.js?<?PHP echo time()?>" ></script>

  </head>

  <body>
  <div id="contenido">

      <header>
      <div class="cabecera">
          <span>DETALLLES <span class="glyphicon glyphicon-list"></span></span>
      </div>
  </header>

      <div class="row text" id="row">
          <div class="col-sm-5 ">
              <div class="subtitulo" >
                  <span>DATOS DEL INFORME  <span class="glyphicon glyphicon-folder-open"></span></span>
              </div>
              <label> ID:</label>
              <br>
              <span id="id"></span>
              <p>
              <label>Fecha:</label>
                  <br>
              <span id="fecha"></span>
              <p>
              <label>Motivo:</label>
              <br>
              <span id="motivo"></span>
              <p>
              <label> observación:</label>
              <br>
              <span id="observacion"></span>
              <p>
              <label> Atendida:</label>
              <br>
              <span id="atendida"></span>
              <p>
              <label>Nombre:</label>
              <br>
              <span id="Nombre"></span> <p>
              <label>Teléfono:</label>
              <br>
              <span id="Telefono"></span>
              <p>
              <label>Dirección:</label>
              <br>
              <span id="Direccion"></span> <p>
              <label>Email:</label>
              <br>
              <span id="email"></span> <br>
          </div>
          <div class="col-sm-7 text-center">
              <div class="subtitulo"  >
                  <span> UBICACIÓN <span class="glyphicon glyphicon-map-marker"></span></span>
              </div>
              <center>
                  <span id="coordenadas"></span>
              <div id="map"></div></center>
              <div class="subtitulo ">
                  <span>FOTOS  <span class="glyphicon glyphicon-camera"></span></span>
              </div>
              <div id="foto1"></div>
          </div>
      </div>
  </div>
  <p>

  </p>

  <button class="btn btn-primary" id="marcAtendida">Marcar como atendida <span class="glyphicon glyphicon-ok"></span></button>
  <button  class="btn btn-primary" onclick="printDiv('row')">Imprimir <span class="glyphicon glyphicon-print"></span></button>

      <input type="text" id="idReclamo" hidden size="128" value="<?PHP echo $idReclamo?>">
  <div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <form class="modal-content" onsubmit="return false" id="actCampos">

              <div class="modal-header">
                  <h4 class="modal-title" id="myModalLabel">Foto ampliada</h4>
              </div>


              <div class="modal-body">
                  <center>
                      <TABLE >

                          <TR>
                              <TD>
                                  <div class="">
                                      <div class="col-10">
                                          <img src="" id="imagepreview" style="width: 500px; height: 400px;" >
                                      </div>
                                  </div>
                              </TD>
                              <TD>
                                  <div class="">
                                      <div class="col-10">
                                      </div>
                                  </div>
                              </TD>
                          </TR>
                      </TABLE>
                  </center>
              </div>

              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
              </div>

  </body>
</html>
<?php endif;
if ($verificarPermisos==false):
    include "../../general/vistas/vista.PlantillaError.php";
endif; ?>
