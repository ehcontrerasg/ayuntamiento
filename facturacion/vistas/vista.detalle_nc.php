<!DOCTYPE html>
<html lang="es">
<head>

    <meta  charset="UTF-8" />
    <title>Detalle Notas</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <!--autocompltar -->
    <script src="../../js/jquery-1.11.2.min.js"></script>
    <!--pag-->
    <script type="text/javascript" src="../js/repNotas.js?<?php echo time();?>"></script>
    <link href="../../css/general.css " rel="stylesheet" type="text/css" />
</head>
<body>
<header class="cabeceraTit fondFac">
    Reporte Detalle Notas Credito
</header>

<section class="contenido">
    <article>
        <form onSubmit="return false" id="repGenNotForm">
            <span class="datoForm col1">
                <span class="titDato numCont2">Acueducto:</span>
                <span class="inpDatp numCont2"><select name="proyecto" tabindex="1" select id="repGenNotSelPro" required ></select></span>
                <span class="titDato numCont2">Fecha Inicial:</span>
                <span class="inpDatp numCont2"><input type="date" name="fecini" tabindex="1" id="repGenDifSelFecIni" required ></input></span>
                <span class="titDato numCont2">Fecha Final:</span>
                <span class="inpDatp numCont2"><input type="date" name="fecfin" tabindex="1" id="repGenDifSelFecFin" required ></input></span>
            </span>
            <span class="datoForm col1">
                <input type="submit" value="Generar" class="botonFormulario botFormFac" tabindex="9">
            </span>

        </form>


    </article>
</section>

<footer>
</footer>
</body>
</html>