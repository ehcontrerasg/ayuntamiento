<!DOCTYPE html>
<html>
<head>
    <meta  charset="UTF-8" />
    <title>Reporte De Tarifas </title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <!--pag-->
    <script type="text/javascript" src="../js/repFacTar.js?4"></script>
    <link href="../../css/general.css" rel="stylesheet" type="text/css" />
</head>
<body>
<header class="cabeceraTit fondFac">
    Reporte De Tarifas
</header>

<section class="contenido">
    <article>
        <form onsubmit="return false" id="genRepFacTar">

            <div class="subCabecera fondFac"> Filtros de busuqeda</div>
            <span class="datoForm col1">
                <span class="titDato">Proyecto:</span>
                <span class="inpDatp"><select tabindex="2" name="proyecto" required select id="genRepFacSelTarPro" ></select></span>

                 <span class="titDato">Periodo:</span>
                <span class="inpDatp"><select tabindex="2" name="periodo" required select id="genRepFacSelTarPer" ></select></span>
                   <span>
                <input type="submit" value="Generar" class="botonFormulario botFormFac" tabindex="4">
              </span>
            </span>
        </form>

        <div id="contenedorPdf">
            <object id="PdfRepTar" class="conPdf" type="application/pdf"></object>
        </div>

    </article>
</section>

<footer>
</footer>
</body>
</html>

