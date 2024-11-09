<!DOCTYPE html>
<html lang="es">
<head>

    <meta  charset=UTF-8" />
    <title>Agregar Observacion</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css"/>
    <script type="text/javascript" src="../../js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <!--pag-->
    <script type="text/javascript" src="../js/baja_medidor.js"></script>
    <link href="../../css/general.css " rel="stylesheet" type="text/css" />
</head>
<body>
<header class="cabeceraTit fondMed">
    Observacion a inmuebles
</header>

<section class="contenido">
    <article>
        <form onsubmit="return false" id="bajMedForm">

            <div class="subCabecera fondMed"> Datos de formulario</div>
            <span class="datoForm col1">
                <span class="titDato numCont2">Asunto</span>
                <span class="inpDatp numCont4"><input name="asunto" autofocus="true" tabindex="1" required id="agrObsInpAsu" placeholder="Codigo sistema" type="number" min="0" max="9999999"></span>
                <span class="titDato numCont2">Codigo</span>
               <span class="inpDatp numCont4"><select required name="codigo" select id="agrObsSelCod" ></select></span>

            </span>


            <div class="subCabecera fondMed">Observaciones</div>

            <span class="datoForm col1">
                <span class="inpDatp numCont1"><textarea tabindex="15" name="observacion" id="agrObsTexObs" required placeholder="Observaciones"></textarea></span>

            </span>

            <span class="datoForm col1">
                <input type="submit" tabindex="17" value="Generar" class="botonFormulario botFormMed" tabindex="9"">
            </span>

        </form>
    </article>
</section>

<footer>
</footer>
</body>
</html>