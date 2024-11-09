<!DOCTYPE html>
<head>
    <html lang="es">
    <meta  charset="UTF-8" />
    <title>Reportes mensuales</title>

    <script src="../../js/jquery.min.js?<?php echo time(); ?>"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js?<?php echo time(); ?>"></script>
    <script type="text/javascript" src="../../js/bootstrap.min.js?<?php echo time(); ?>"></script>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?<?php echo time(); ?>" />
    <script type="text/javascript" src="../../js/sweetalert.min.js?<?php echo time(); ?>"></script>
    <!-- iconos botones   -->
    <link href="../../font-awesome/css/font-awesome.min.css?<?php echo time(); ?>" rel="stylesheet" />
    <!--estilo pag    -->
    <link rel="stylesheet" type="text/css" href="../css/catastro.css?<?php echo time(); ?>" />
    <!--logica pag    -->
    <script type="text/javascript" src="../js/repMensInm.js?<?php echo time(); ?>"></script>
</head>
<body>
<header>
    <div class="cabecera">
        Reportes Mensuales de inmuebles
    </div>
</header>

<section>
    <article>
        <div id="butRepMensInc" class="butReportes" data-toggle="modal" data-target="#consultar-1">
            <i class="fa fa-home"></i>
            Inmuebles Incorporados
        </div>
        <div id="butRepMensFact" class="butReportes" data-toggle="modal" data-target="#consultar-1">
            <i class="fa fa-home"></i>
            Inmuebles Facturados
        </div>
        <div id="butRepMensCat" class="butReportes" data-toggle="modal" data-target="#consultar-1">
            <i class="fa fa-home"></i>
            Inmuebles Catastrados
        </div>

        <div id="butRepMensResInm" class="butReportes" data-toggle="modal" data-target="#consultar-1">
            <i class="fa fa-home"></i>
            Reporte Resumen de inmuebles
        </div>
    </article>
</section>

<footer>

</footer>

<div class="modal fade" id="consultar-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 40%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-close="1"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
        <iframe  frameborder="0" width="100%" class="modal-consulta-body" name="modal-consulta" style="min-height: 290px"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" data-close="1">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    repMensInmInicio();
</script>

</body>
</html>