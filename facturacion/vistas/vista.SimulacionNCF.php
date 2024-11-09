<!DOCTYPE html>
<html lang="es">
<head>

    <meta  charset="UTF-8" />
    <title>Simulacion de NCF</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <!--autocompltar -->
    <script src="../../js/jquery-1.11.2.min.js"></script>
    <!--pag-->
    <script type="text/javascript" src="../js/RepSimulacionNCF.js"></script>
    <link href="../../css/general.css " rel="stylesheet" type="text/css" />
</head>
<body>
<header class="cabeceraTit fondFac">
    Generacion Reporte Simulacion NCF
</header>

<section class="contenido">
    <article>
        <form onSubmit="return false" id="repGenMed3KForm">
            <span class="datoForm col1">
                <span class="titDato numCont1">Acueducto:</span>
                <span class="inpDatp numCont1"><select name="proyecto" tabindex="1" select id="repGenMed3KSelPro" required ></select></span><br/>
                <span class="titDato numCont1">Zona:</span>
                <span class="inpDatp numCont1"><select name="zona" tabindex="1" select id="repGenMed3KSelZon" required ></select></span>
                 <span class="titDato numCont1">Período:</span>
                <span class="inpDatp numCont2"><input type="text" name="periodo" tabindex="1" id="repGenMed3KSelPer" required ></input></span>
                <span class="titDato numCont1">Raiz NCF:</span>
                <span class="inpDatp numCont2"><select name="raizNCF" tabindex="1" select id="repGenMed3KSelNCF" required ></select></span>
                <span class="titDato numCont1">Exportar a:</span>
                <span class="inpDatp numCont2">
                    <select name="descarga" tabindex="2" select id="repGenMed3KSelDes" required >
                        <option value=""></option>
                        <!--<option value="pdf">PDF</option>-->
                        <option value="xls">XLS</option>
                    </select>
                </span>
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