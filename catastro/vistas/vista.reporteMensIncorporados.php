<!DOCTYPE html>
<head>
    <html lang="es">
    <meta  charset=UTF-8" />
    <title>Reportes mensuales</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
    <script type="text/javascript" src="../../js/sweetalert.min.js?<?php echo time(); ?>"></script>
    <!-- iconos botones   -->
    <link href="../../font-awesome/css/font-awesome.min.css?<?php echo time(); ?>" rel="stylesheet" />
    <!--estilo pag    -->
    <link rel="stylesheet" type="text/css" href="../css/catastro_modal.css?<?php echo time(); ?>" />
    <!--logica pag    -->
    <script type="text/javascript" src="../js/repMensInmInc.js?<?php echo time(); ?>"></script>
</head>
<body>
<header>
    <div class="cabecera">
        Reporte Mensual de inmuebles incorporados
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
                <select id="selRepMenIncProy"></select>
            </div>
            <div class="contenedor">
                <label>Periodo</label>
                <input type="number" id="inpRepMenInc">
            </div>
            <div class="contenedor">
                <button id="butRepMenInc">Generar</button>
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