<!DOCTYPE html>
<head>
    <meta  charset=UTF-8" />
    <title>Revision aleatorias de cortes</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <!--pag-->
    <script type="text/javascript" src="../js/revAleatoriaCorte.js?<?echo time();?>"></script>
    <link href="../../css/general.css?<?echo time();?>" rel="stylesheet" type="text/css" />
</head>
<body>
<header class="cabeceraTit fondCorte">
    Revision aleatoria de cortes
</header>

<section class="contenido">
    <article>
        <form onsubmit="return false" id="revAleForm">

            <div class="subCabecera fondCorte"> Filtros de ejecucion</div>
            <span class="datoForm col1">
                <span class="titDato numCont1">Proyecto:</span>
                <span class="inpDatp numCont1"><select autofocus="true" tabindex="1" name="proyecto" required select id="revAleSelPro" ></select></span>
                <span class="titDato numCont1">Usuario:</span>
                <span class="inpDatp numCont2"><select tabindex="10" name="usuario" required select id="revAleUsu" ></select></span>
                <span class="titDato numCont1">proceso:</span>
                <span class="inpDatp numCont2"><input  id="revAleProIni" tabindex="20"  name="proIni"  min="10000000000" max="99999999999" title="proceso inicial" type="number" ></span>
                <span class="inpDatp numCont2"><input  id="revAleProFin" tabindex="30"  name="proFin" min="10000000000" max="99999999999"  title="proceso final" type="number" ></span>
                <span class="inpDatp numCont2"></span>
            </span>
            <span class="datoForm col1">
                <span class="titDato numCont2">Fecha ejecucion:</span>
                <span class="inpDatp numCont1"><input  id="revAleFecIni" tabindex="40" required name="fechIni"  title="fecha inicial" type="date" ></span>
                <span class="inpDatp numCont1"><input  id="revAleFecFin" tabindex="50" required name="fechFin"  title="fecha final" type="date" ></span>
                <span class="titDato numCont1">Porcentaje:</span>
                <span class="inpDatp numCont1"><input  id="revAlePorc" value="10" tabindex="40" required name="porc"  title="porcentaje" type="number" min="0"max="100" ></span>
                <span class="titDato numCont2">cantidad a visitar:</span>
                <span class="inpDatp numCont1"><input readonly id="revAleCan" tabindex="50" required name="cant"  title="cantidad" type="number" ></span>
                <span class="titDato numCont2">cantidad total:</span>
                <span class="inpDatp numCont1"><input readonly id="revAleCanTot" tabindex="50" required   title="cantidad" type="number" ></span>

            </span>



            <span class="datoForm col1">
                <input type="submit" value="Generar"  tabindex="60"class="botonFormulario botFormCorte" >
            </span>

            <div id="contenedorPdf">
                <object id="rutRevAleCortPdf" class="conPdf" type="application/pdf"></object>
            </div>

        </form>



    </article>
</section>

<footer>
</footer>
</body>
</html>