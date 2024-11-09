<!DOCTYPE html>
<html lang="es">
<head>

    <meta  charset="UTF-8" />
    <title>Reporte Diferidos</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <!--autocompltar -->
    <script src="../../js/jquery-1.11.2.min.js"></script>
    <!--pag-->
    <script type="text/javascript" src="../js/repDiferidos.js?<?php echo time();?>"></script>
    <link href="../../css/general.css " rel="stylesheet" type="text/css" />
</head>
<body>
<header class="cabeceraTit fondFac">
    Reporte Diferidos Por Periodo
</header>

<section class="contenido">
    <article>
        <form onSubmit="return false" id="repGenDifForm">
            <span class="datoForm col1">
                <span class="titDato numCont2">Acueducto:</span>
                <span class="inpDatp numCont2"><select name="proyecto" tabindex="1" select id="repGenDifSelPro" required ></select></span>
                <span class="titDato numCont2">Período:</span>
                <span class="inpDatp numCont2"><input type="text" name="periodo" tabindex="1" id="repGenDifSelPer" required ></input></span>
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