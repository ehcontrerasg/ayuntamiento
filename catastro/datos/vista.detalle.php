<?php?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="../../css/style.css"/>
<script type="text/javascript" src="../../js/cambiarPestanna.js"></script>
<script type="text/javascript" src="../../js/jquery-1.11.1.min.js"></script>
<title></title>
</head>
<body>
<div class="contenedor">
<div id="pestanas">
<ul id=lista>
<li id="pestana1"><a href='javascript:cambiarPestanna(pestanas,pestana1);'>SERVICIOS</a></li>
<li id="pestana2"><a href='javascript:cambiarPestanna(pestanas,pestana2);'>MEDIDOR</a></li>
<li id="pestana3"><a href='javascript:cambiarPestanna(pestanas,pestana3);'>FACTURAS</a></li>
</ul>
</div>

<body onload="javascript:cambiarPestanna(pestanas,pestana1);">

<div id="contenidopestanas">
<div id="cpestana1">
<FRAMESET ROWS=75,*>

           <iframe src="vista.servicios.php?cod_inmueble=<?php echo $cod_inmueble;?>"></iframe>

 </FRAMESET>
 
</div>
<div id="cpestana2">
Contenido de la pestaña 2
</div>
<div id="cpestana3">
Contenido de la pestaña 3
</div>
</div>
</body>
</html>
</pre>