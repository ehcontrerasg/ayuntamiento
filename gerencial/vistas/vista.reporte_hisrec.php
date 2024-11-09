
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 	<title>Reporte Hist&oacute;rico de Recaudaci&oacute;n</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css?2" />
    <script type="text/javascript" src="../../js/sweetalert.min.js?2"></script>
	<!--autocompltar -->
    <script src="../../js/jquery-3.3.1.min.js?2"></script>
    <!-- iconos botones   -->
    <link href="../../font-awesome/css/font-awesome.min.css?2" rel="stylesheet" />
    <!--estilo pag    -->
    <link rel="stylesheet" type="text/css" href="../css/gerencia.css?3" />
    <!--logica pag    -->
    <script type="text/javascript" src="../js/repHisRec.js?<?echo time();?>"></script>
</head>
<body>
	<header>
		<div class="cabecera">
			Hist&oacute;rico Recaudaci&oacute;n Por Concepto, Uso, Sector
		</div>
	</header>
	<section>
		<article>
			<form onSubmit="return false" id="genRepHisRecForm">
				<div class="subCabecera">
					Filtros de Búsqueda Hist&oacute;rico Recaudaci&oacute;n Por Concepto, Uso, Sector
				</div>
				<div class="divfiltros">
					<div class="contenedor">
						<label> Proyecto: </label>
						<select id="selProyHisRec" name="proyecto"></select>
					</div>
					<div class="contenedor">
						<label>Fecha Pago Inicial: </label>
						<input type="date" id="inpFecIniHisRec" name="fecini">
					</div>
					<div class="contenedor">
						<label>Fecha Pago Final: </label>
						<input type="date" id="inpFecFinHisRec" name="fecfin">
					</div>
					<div class="contenedor">
						<button id="butGenHisRec">Generar Reporte</button>
					</div>
				</div>
			</form>
		</article>
	</section>
	<footer>
	</footer>
</body>
</html>
