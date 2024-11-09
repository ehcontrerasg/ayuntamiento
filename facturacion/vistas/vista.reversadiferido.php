<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
    <meta content="text/html; charset=UTF-8" />
    <title>Reversi&oacute;n de Diferidos</title>
    <!-- alertas -->
    <link rel="stylesheet" type="text/css" href="../../css/sweetalert.css " />
    <script type="text/javascript" src="../../js/sweetalert.min.js "></script>
    <!--autocompletar -->
    <link href="../../css/css.css " rel="stylesheet" type="text/css" />
    <!--pag-->
    <script type="text/javascript" src="../js/datInmueblesDif.js"></script>
    <link href="../css/facturacion.css " rel="stylesheet" type="text/css" />
	<link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<!--flexigrid-->
	<!--link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
	<script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script-->
</head>
<body id="revDifBod" >
<header class="cabeceraTit">
    Reversi&oacute;n Diferidos
</header>

<section class="contenido">
    <article>
		<span class="datoForm col1">
            <span class="titDato numCont2">Diferido N&deg;:</span>
			<span class="inpDatp numCont2"><input id="numDifRev" type="text" readonly ></span>
        </span>
        <span class="datoForm col1">
            <span class="titDato numCont2">Observaci&oacute;n:</span>
			<span class="inpDatp numCont2"><textarea id="txtObsRevDif" required></textarea></span>
        </span>
		<span class="datoForm col1">
		</span>
        <span class="datoForm col1">
            <button class="btn btn-INFO" id="botonRevDiferido"><i class="fa fa-refresh"></i><b>&nbsp;Reversar Diferido</b></button>
        </span>
    </article>
</section>

<footer>
</footer>
<script type="text/javascript">
	inicioInmueblesDiferido();
    inicioDiferidoRev();
</script>
</body>
</html>

