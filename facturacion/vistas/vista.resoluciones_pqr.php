<?php
session_start();

include '../../destruye_sesion_cierra.php';

$_SESSION['tiempo']=time();
$_POST['codigo']=$_SESSION['codigo'];
$cod_pqr=$_GET['cod_pqr'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="../../flexigrid/style.css" />
    <link rel="stylesheet" type="text/css" href="../../flexigrid/css/flexigrid/flexigrid.css">
    <script type="text/javascript" src="../../flexigrid/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="../../flexigrid/flexigrid.js"></script>
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
<form name="FMTarifas" action="vista.resoluciones_pqr.php" method="post" onsubmit="return rend();">
    <div class="flexigrid" style="width:100%">
    	<table id="flex1" style="display:none">
    	</table>
	</div>
</form>
<script type="text/javascript">
    <!--
    $('.flexme1').flexigrid();
    $('.flexme2').flexigrid({height:'auto',striped:false});
    $("#flex1").flexigrid	(
        {

            url: './../datos/datos.resoluciones_pqr.php?cod_pqr=<?php echo $cod_pqr;?>',

            dataType: 'json',
            colModel : [
                {display: 'No', name: 'rnum', width:25,  align: 'center'},
                {display: 'Area', name: 'AREA_ACTUAL', width: 120, sortable: true, align: 'center'},
                {display: 'Fecha<br>Entrada', name: 'FECHA_ENTRADA', width: 110, sortable: true, align: 'center'},
                {display: 'Fecha<br>Salida', name: 'FECHA_SALIDA', width: 110, sortable: true, align: 'center'},
                {display: 'Respuesta', name: 'RESPUESTA', width: 220, sortable: true, align: 'center'},
				{display: 'Usuario<br>Respuesta', name: 'USUARIO_RESPUESTA', width: 100, sortable: true, align: 'center'}
            ],


            sortname: "CONSECUTIVO",
            sortorder: "DESC",
          
            title: 'Listado de Resoluciones',
          
            rp: 1000,
            page: 1,
       
            width: 1050,
            height: 300
        }
    ); 
    //-->
</script>
</body>
</html>
