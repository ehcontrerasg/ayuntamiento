<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 	<title>Reporte Usuarios Alcantarillado Por Gerencia, Uso, Concepto y Periodo</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
    <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
    <!-- iconos botones   -->
    <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
    <!--estilo pag    -->
    <link rel="stylesheet" type="text/css" href="../css/gerencia.css " />
    <!--logica pag    -->
    <script type="text/javascript" src="../js/repUsuAlcGerUsoCon.js"></script>
</head>
<body>
	<header>
		<div class="cabecera">
			Usuarios Alcan Por Gerencia, Uso, Concepto. y Periodo
		</div>
	</header>
	<section>
		<article>
			<form onSubmit="return false" id="genRepUsuAlcGerUsoConForm">
				<div class="subCabecera">
					Filtros de Búsqueda Usuarios Alcantarillado Por Gerencia, Uso, Concepto y Periodo
				</div>
				<div class="divfiltros">
					<div class="contenedor">
						<label> Proyecto: </label>
						<select id="selProyUsuAlcGerUsoCon"></select>
					</div>
					<div class="contenedor">
						<label>Perido: </label>
						<input type="number" id="inpPerUsuAlcGerUsoCon">
					</div>
					<div class="contenedor">
						<button id="butGenUsuAlcGerUsoCon">Generar Reporte</button>
					</div>
				</div>
				<div>
					<input type="hidden" id="inpHidValRes">
				</div>
			</form>
		</article>
	</section>
	<footer>
	</footer>
	<script type="text/javascript">
		repUsuAlcGerUsoConInicio();
	</script>
</body>
</html>
