<?php
session_start();

include '../../destruye_sesion.php';


$rol=$_SESSION['rol'];
$_SESSION['tiempo']=time();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" media="screen" href="../funciones/Static_Tabstrip.css">
<link rel="stylesheet" href="../flexigrid/style.css" />
<link rel="stylesheet" type="text/css" href="../flexigrid/css/flexigrid/flexigrid.css">
<script type="text/javascript" src="../flexigrid/lib/jquery/jquery.js"></script>
<script type="text/javascript" src="../flexigrid/flexigrid.js"></script>
<script language="javascript">
	$(document).ready(function() {
		$(".botonExcel").click(function(event) {
			$("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel").eq(0).clone()).append( $("#flex1").eq(0).clone()).html());
			$("#FormularioExportacion").submit();
	});
	});
</script>
</head>
<body>
<form action="../funciones/ficheroExcel.php" method="post" target="_blank"  id="FormularioExportacion">
	<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    <div class="flexigrid" style="width:100%">
		<div class="mDiv">
    		<div>Exportar a:</div>
            <div style="background-color:rgb(255,255,255)">
            	<img src="../images/excel/xls.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="Excel"/>
                <img src="../images/excel/pdf.ico" onMouseOver="this.src" width="30" height="30" class="botonExcel" title="PDF"/>
            </div>    	
    	</div>
	</div>  	
</form>
<div>
<table id="Exportar_a_Excel" style="display:none">
	<tr>
		<th align="center">NUMERO</th>
        <th align="center">CONTRATO</th>
        <th align="center">EMAIL</th>
        <th align="center">FECHA DE CREACION</th>
        <th align="center">ULTIMA VISITA</th>
        <th align="center">ESTADO</th>
    </tr>
</table>
<table id="flex1" style="display:none">
</table>
</div>
<script type="text/javascript">
<!--
  $('.flexme1').flexigrid();
  $('.flexme2').flexigrid({height:'auto',striped:false});
  $("#flex1").flexigrid	(
	{
			url: '../datos/datos_usuarios.php',
			dataType: 'json',
			colModel : [
				{display: 'No', name: 'numero', width: 20, sortable: false, align: 'center'},
				{display: 'contrato', name: 'username', width: 90, sortable: true, align: 'center'},
				{display: 'email', name: 'email', width: 195, sortable: true, align: 'center'},
			    {display: 'fecha creacion', name: 'fecha_creacion', width: 150, sortable: true, align: 'center'},
				{display: 'ultima visita', name: 'ultima_visita', width: 150, sortable: true, align: 'center'},
				{display: 'estado', name: 'descripcion', width: 50, sortable: true, align: 'center'},
				{display: 'Cambiar estado', name: 'estado', width: 80, sortable: false, align: 'center'}
				
				],	
			searchitems : [	
				{display: 'Contrato', name: 'username', isdefault: true},
				{display: 'Estado', name: 'descripcion'},
				{display: 'Ultima_Visita', name: 'ultima_visita'},
				{display: 'Email',name: 'email'}
				
				
				],
				buttons: [
				         {'name':'Agregar', 'bclass':'add'},
				         {'name':'Eliminar', 'bclass':'delete'},
		
				       ],
				
			sortname: "descripcion",
			sortorder: "desc",
			usepager: true,
			title: 'Usuarios',
			useRp: true,
			rp: 30,
			page: 1,			
			showTableToggleBtn: true,
			width: 960,
			height: 358
			}
			);

//-->		
</script>
</body>
</html>
<script type="text/javascript" language="javascript">	

</script>


   	 	
 

