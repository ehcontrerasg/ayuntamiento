<?
/**
 * Created by PhpStorm.
 * User: xavier
 * Date: 25/09/2018
 * Time: 10:07
 */
$lat=$_GET['lat'];
$log=$_GET['long'];

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
      <script type="text/javascript" src="../js/detalleAverias.js?<?PHP echo time()?>" ></script>
      <style>


/* Set the size of the div element that contains the map */
#map {
height: 260px;  /* The height is 400 pixels */
        width: 55%;  /* The width is the width of the web page */

       }
    </style>
  </head>

  <body>
  <div id="container">

      <header>
      <div class="cabecera">
          DETALLLES</div>
  </header>

      <div class="row text" id="row">
          <div class="col-sm-5 ">
              <div class="subtitulo" style="alignment: left;">
                  DATOS DEL INFORME
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
              <div class="subtitulo"  style="alignment: left;">
                  UBICACIÓN
              </div>
              <center>
                  <span id="coordenadas"></span>
              <div id="map"></div></center>
              <div class="subtitulo " style="alignment: right;">
                  FOTOS
              </div>
              <div id="foto1"></div>
          </div>
      </div>

  <p>
  </p>
  <button class="btn btn-primary" id="marcAtendida">Marcar como atendida <span class="glyphicon glyphicon-ok"></span></button>
  <button  class="btn btn-primary" onclick="printDiv('row')">Imprimir <span class="glyphicon glyphicon-print"></span></button>
      <input type="text" id="idReclamo" hidden size="128" value="<?PHP echo $idReclamo?>">
  </div>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDhc6UkP_v17lXZ9hPKfnWKRtjPTMEmW2k">
    </script>
  </body>
</html>