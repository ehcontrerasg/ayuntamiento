<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Rendimiento Cortes</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?3" />
    <script type="text/javascript" src="../../js/sweetalert.min.js?2"></script>
    <!--autocompltar -->
    <script src="../../js/jquery-1.11.2.min.js?3" type="text/javascript"></script>
    <!-- iconos botones   -->
    <link href="../../font-awesome/css/font-awesome.min.css?3" rel="stylesheet" />
    <!--estilo pag    -->
    <link rel="stylesheet" type="text/css" href="../../css/general.css?<?echo time();?>" />
    <!--<link rel="stylesheet" type="text/css" href="../../gerencial/css/gerencia.css?4" />-->
    <!--logica pag    -->
    <script type="text/javascript" src="../js/rendcor.js?<?echo time();?>"></script>
</head>
<body>
<header class="cabeceraTit fondCorte">
    Reporte Gerencial de rendimiento de cortes
</header>
<section class="contenido">
    <article>
        <form id="genRepAntMedForm" onsubmit="return false">
              <span class="datoForm col1">
                    <span class="titDato">Proyecto:</span>
                    <span class="inpDato"><select id="selProyAntMed" name="proyecto" autofocus="true" required></select></span>
                    <span class="titDato">Contratista:</span>
                    <span class="inpDato"><select id="selContAntMed" name="contratista" autofocus="true" required></select></span>
                    <span class="titDato">Fecha inicial:</span>
                    <span class="inpDato"><input type="date"  required name="fecini"></span>
                    <span class="titDato">Fecha final:</span>
                    <span class="inpDato"><input type="date"  required name="fecfin"></span>
                    <input id="butGenAntMed" class="botonFormulario botFormCorte" type="submit" value="Generar Reporte"/>
             </span>
        </form>


    </article>
</section>
<footer>
</footer>
</body>
</html>
