<!DOCTYPE html>
<head>
    <html lang="es">
    <meta  charset=UTF-8" />
    <title>Reportes mensuales</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
    <link rel="stylesheet" type="text/css" href="../css/catastro_modal.css?<?php echo time(); ?>" />
    <link href="../../font-awesome/css/font-awesome.min.css?<?php echo time(); ?>" rel="stylesheet" />
    <script type="text/javascript" src="../../js/sweetalert.min.js?<?php echo time(); ?>"></script>
    <script type="text/javascript" src="../js/repMensInmFac.js?<?php echo time(); ?>"></script>
</head>
<body>
<header>
    <div class="cabecera">
        Reporte Mensual de inmuebles facturados
    </div>
</header>

<section>
    <article>
        <div class="subCabecera">
            Criterios de generacion de reporte
        </div>



        <div class="divfiltros">
            <div class="contenedor">
                <label> Proyecto</label>
                <select id="selRepMenFacProy"></select>
            </div>

            <div class="contenedor">
                <label>Periodo</label>
                <input type="number" id="inpRepMenFac">
                <button id="butRepMenFac" >Generar</button>
            </div>

        </div>
    </article>
</section>

<footer>

</footer>
<script type="text/javascript">
    repMensInmInicio();
</script>

</body>
</html>