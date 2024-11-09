<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 	<title>Reporte Unidades Usuarios Activos Por Uso y Periodo</title>
    <!--JQUERY -->
    <script type="text/javascript"  src="../../js/jquery-1.11.2.min.js"></script>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css" />
    <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
    <!-- iconos botones   -->
    <link href="../../font-awesome/css/font-awesome.min.css " rel="stylesheet" />
    <!--estilo pag    -->
    <link rel="stylesheet" type="text/css" href="../css/gerencia.css " />
    <!--logica pag    -->
    <script type="text/javascript" src="../js/repUniUsoPer.js?3"></script>
</head>
<body> 
	<header>
		<div class="cabecera">
			Total Unidades Usuarios Activos Por Uso y Periodo 
		</div>
	</header>
	<section>
		<article>
			<form onSubmit="return false" id="genRepUniUsoPerForm">
				<div class="subCabecera">
					Filtros de Búsqueda Total Unidades Usuarios Activos Por Uso y Periodo 
				</div>
				<div class="divfiltros">
					<div class="contenedor">
						<label> Proyecto: </label>
						<select id="selProyUniUsoPer" name="proyecto"></select>
					</div>
					<div class="contenedor">
						<label>Período: </label>
						<input type="number"  name="periodo" id="inpPerUniUsoPer">
					</div>
					<div class="contenedor">
						<button id="butGenUniUsoPer" name="">Generar Reporte</button>
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
		repUniUsoPerInicio();
	</script>
</body>
</html>