<!DOCTYPE html>
<head>
    <html lang="es">
    <meta  charset=UTF-8" />
    <title>Apertura Ciclo Facturacion</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css " />
    <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
    <!--autocompltar -->
    <script src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui-1.8.2.custom.min.js "></script>
    <link href="../../css/css.css " rel="stylesheet" type="text/css" />
    <!--pag-->
    <script type="text/javascript" src="../js/reliquidacion_pag.js?<?echo time();?> "></script>
    <link href="../css/facturacion.css?6 " rel="stylesheet" type="text/css" />
    <!--flexygrid-->
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js "></script>

</head>
<body>
<header class="cabeceraTit">
    <span>Saldo a favor por facturas o Inmueble</span>
    <input id="relPagBusInm" required autofocus="true" type="number" min="0" max="999999999" class="busqueda">
</header>

<section class="contenido">
    <article>
        <form onsubmit="return false" id="relPagForm">
            <div class="contenedor-flexy" >
                <table id="flexFactPag">
                </table>
            </div>

            <span class="datoForm col1">
                <input type="submit" tabindex="17" value="Seleccionar" class="botonFormulario" tabindex="9" id="relPagButSel">
            </span>
        </form>

    </article>
</section>
<script type="text/javascript">
    relFacPagInicio();
</script>
</body>
</html>