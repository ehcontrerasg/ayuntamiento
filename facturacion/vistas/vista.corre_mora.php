<!DOCTYPE html>
<head>
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
    <script type="text/javascript" src="../js/corre_mora.js "></script>
    <link href="../../css/general.css" rel="stylesheet" type="text/css" />
</head>

<body>
<header class="cabeceraTit fondFac">
    Proceso de Mora
</header>

<section class="contenido">
    <article>
         <span class="datoForm col1">
            <span class="titDato numCont3">Acueducto:</span>
            <span class="inpDatp numCont3"><select select id="corMorSelPro" ></select></span>
            <span class="inpDatp numCont3"><input id="corMorInpProDsc" readonly ></span>
        </span>

        <span class="datoForm col1">
            <span class="titDato numCont3">Zona:</span>
            <span class="inpDatp numCont3"><input id="corMorInpZon" type="text"></span>
            <span class="inpDatp numCont3"><input id="corMorInpZonDesc" readonly type="text"></span>
        </span>

        <span class="datoForm col1">
            <span class="titDato numCont3">Periodo:</span>
            <span class="inpDatp numCont3"><input id="corMorInpPer" readonly></span>
            <span class="inpDatp numCont3"><input id="corMorInpPerDesc" readonly ></span>
        </span>
        <span class="datoForm col1">
            <button class="botonFormulario fondFac" id="corMorButPerDesc">Procesar</button>
        </span>

    </article>
</section>
<script type="text/javascript">
    corMorInicio();
</script>
</body>
</html>