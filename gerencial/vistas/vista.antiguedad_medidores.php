<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Antiguedad Medidores</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?3" />
    <script type="text/javascript" src="../../js/sweetalert.min.js?2"></script>
    <!--autocompltar -->
    <script src="../../js/jquery-1.11.2.min.js?3"></script>
    <!-- iconos botones   -->
    <link href="../../font-awesome/css/font-awesome.min.css?3" rel="stylesheet" />
    <!--estilo pag    -->
    <link rel="stylesheet" type="text/css" href="../css/gerencia.css?3" />
    <!--logica pag    -->
    <script type="text/javascript" src="../js/repAntMed.js?4"></script>
</head>
<body>
<header>
    <!--div class="cabecera">
        Reporte Analisis Consumos
    </div-->
</header>
<section>
    <article>
        <form onSubmit="return false" id="genRepAntMedForm"><!--1-->
            <!--div class="subCabecera">
                Filtros de Búsqueda Reporte Analisis Consumos
            </div-->
            <div class="divfiltros">
                <div class="contenedor">
                    <label> Proyecto: </label>
                    <select id="selProyAntMed" name="proyecto" autofocus="true" required></select><!--1-->
                </div>
                <div class="contenedor">
                    <label> Fecha inicial: </label>
                    <input type="date" id="selRutAntMed" name="ruta">
                </div>
                <div class="contenedor">
                    <label> Fecha Final: </label>
                    <input type="date" id="selUsoAntMed" name="cliente">
                </div>

                <div class="contenedor">
                    <button id="butGenAntMed">Generar Reporte</button><!--1-->
                </div>
            </div>
        </form>
    </article>
</section>
<footer>
</footer>
</body>
</html>
